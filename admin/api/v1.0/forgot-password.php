<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

$appmenuname =trim($_POST['appmenuname']);
$activeusertypeid=trim($_POST['activeusertypeid']); 

    error_reporting(1);

    $status=0;
    $message=$errmsg['invalidrequest'];

    //Send OTP For Forgot Password
    if($action=='sendresetpwdotp')   
    {
        $username=$IISMethods->sanitize($_POST['username']);
        // $mobileno=$IISMethods->sanitize($_POST['mobileno']);
       
        $qry="SELECT * FROM tblpersonmaster WHERE username=:username AND isactive=1";
        $resetpassparams=array(            
            ':username'=>$username,
            // ':mobileno'=>$mobileno,
        );
        $reschkusr = $DB->getmenual($qry,$resetpassparams); 

        if(sizeof($reschkusr)>0)
        {
            $row=$reschkusr[0];
            if($row['contact'])
		    {
				try 
				{
					$DB->begintransaction();

					$otp=$IISMethods->RandomOTP(6);
					$smstext="Dear ".$username.",  ".$otp." is your verification code for reset password. Thank You.";

					$updqry=array(					
						'[otp]'=>$otp,
					);
					$extraparams=array(
						'[id]'=>$row['id']
					);
					$DB->executedata('u','tblpersonmaster',$updqry,$extraparams);

					$status=1;
					$message='Verification code sent to '.$row['contact'].' and '.$row['email'].'.'.$otp;

					$response['uid']=$row['id'];

					$DB->committransaction();
				}
				catch (Exception $e) 
				{
					$DB->rollbacktransaction($e);
					$status=0;
					$message=$errmsg['dbtransactionerror'];
				}	
            }
            else
            {
                $status=0;
                $message="Please contact administrator for your forget password procedure.";
            }
        }
        else
        {
            $status=0;
		    $message="Please enter correct Username";
        }
    }
    //Set New Password
    else if($action=='setnewpassword')   
    {
        $otp=$IISMethods->sanitize($_POST['vercode']);
        $newpass=$IISMethods->sanitize($_POST['newpass']);
        $userid=$IISMethods->sanitize($_POST['uid']);
        
        if($otp && $newpass)
		{
			$qry="SELECT * FROM tblpersonmaster where id=:userid";
			$setnewpassparams=array(            
				':userid'=>$userid,
			);
			$res=$DB->getmenual($qry,$setnewpassparams);
			if(sizeof($res)>0)
			{
				$row=$res[0];
				if($row['otp']==$otp)
				{
					try 
					{
						$DB->begintransaction();	

						$pass=md5($newpass);
						$data=array(
							'[password]'=>$pass,
							'[strpassword]'=>$newpass,
							'otp'=>''
						);
						$extraparams=array(
							'[id]'=>$row['id']
						);
						$DB->executedata('u','tblpersonmaster',$data,$extraparams);
						$status=1;
						$message="Password reset successfully";

						$DB->committransaction();
					}
					catch (Exception $e) 
					{
						$DB->rollbacktransaction($e);
						$status=0;
						$message=$errmsg['dbtransactionerror'];
					}	
				}
				else
				{
					$status=0;
					$message='Invalid verification code.';
				}
			}
			else
			{
				$status=0;
				$message="Sorry something went wrong.";
			}
		}
		else
		{
			$status=0;
			$message="Please fill in all required fields.";
		}
        
    }
    

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  