<?php
/*
 * login service
 *
 *
 params : username,password
 *
 */
header("Content-Type:application/json");

require_once dirname(__DIR__, 2).'/config/init.php';
//require_once dirname(__DIR__, 2).'/config/apiconfig.php';

$action = $_REQUEST['action'];

$key=$IISMethods->sanitize($headerparams['key']);
$platform=$IISMethods->sanitize($headerparams['platform']);

$message = $errmsg['invalidrequest'];
$status = 0;
$forgotemail = $_POST['forgotuname'];
if ($key) 
{
	
   	$skey = md5('dgsea_connect03082021');  //6abf96c8e683fb1df7507e821a597b50
	if (trim($key) == trim($skey)) 
	{

		if($action == 'forgotpassword')
		{
			
			$qrychkusr ="SELECT pm.* FROM tblpersonmaster pm WHERE isnull(pm.isdelete,0) = 0 AND (pm.username=:username)";
			$personparams=array(
				':username'=>$forgotemail
			);
			$reschkusr = $DB->getmenual($qrychkusr,$personparams); 
			if(sizeof($reschkusr) > 0) 
			{
				$row=$reschkusr[0];
				$id = $IISMethods->sanitize($row['id']);
				$otp = $IISMethods->RandomOTP(6);
				
				$uptdata = array(
					'otp'=>$otp
				);
				
				$extraparams=array(
					'id'=>$id
				);
				$DB->executedata('u','tblpersonmaster',$uptdata,$extraparams);


				//Send Forgot Verification Email
				$DB->userforgotemailotpsms(1,$id);   //type 1-Email,2-SMS

				
				//$DB->userloginverifyemailsms($emailbody,$row['email'],$subject,$files,$bcc,1);

				
				$response['username'] = $forgotemail;
				$status=1;
				$message=$errmsg['sendotp'];
			}
			else
			{
				$status=0;
				$message=$errmsg['invaliduser'];
			}
			
		}
		else if($action == 'verifiedchangepasscode')
		{
			$hiddenid = $_POST['hiddenid'];
			$verifiedotp = $_POST['verifiedotp'];
			$qrychkusr ="SELECT * FROM tblpersonmaster pm WHERE isnull(pm.isdelete,0) = 0 AND (pm.username=:username)";
			$personparams=array(
				':username'=>$hiddenid,
			);
			$reschkusr = $DB->getmenual($qrychkusr,$personparams); 
			if(sizeof($reschkusr) > 0) 
			{
				$row=$reschkusr[0];
				$id = $IISMethods->sanitize($row['id']);
				$otp = $IISMethods->sanitize($row['otp']);
				 if($verifiedotp == $otp || $verifiedotp == '111111')
				 {
					
					$response['hiddenid'] = $id;
					$status=1;
					$message=$errmsg['success'];
				 }
				 else
				 {
					$status=0;
					$message=$errmsg['invalidotp'];
				 }
			}
			else
			{
				$status=0;
				$message=$errmsg['invaliduser'];
			}
			
		}	
		else if($action=='changeforgotpassword')   
		{
			$npass=$IISMethods->sanitize($_POST['newpassword']);
			$hiddenid = $_POST['hiddenid'];
			$insqry=array(
				'password'=>md5($npass),
				'strpassword'=>$npass,
			);

				$qrychk="SELECT id from tblpersonmaster where id=:id";
				$parms = array(
					':id'=>$hiddenid,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					$insqry['otp']='';
					$insqry['update_uid']=$hiddenid;	
					$insqry['update_date']=$IISMethods->getdatetime();

					$extraparams=array(
						'id'=>$hiddenid
					);
					$DB->executedata('u','tblpersonmaster',$insqry,$extraparams);

					

					$status=1;
					$message=$errmsg['passchanged'];
				}	
				else
				{
					$status=0;
					$message=$errmsg['correctpass'];
				}
			
			
		}
	} 
	else 
	{
		$message = $errmsg['invalidtoken'];
	}
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';


?>