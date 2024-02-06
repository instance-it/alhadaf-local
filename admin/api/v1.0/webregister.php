<?php
/*
 * login service
 *
 *
 params : username,password
 *
 */
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';


$action = $_REQUEST['action'];

$status=0;
$message=$errmsg['invalidrequest'];


if($action == 'register')
{
	$name=trim($_POST['r_name']);
	$email=trim($_POST['r_email']);
	$contact=trim($_POST['r_contactno']);

	$regtype=trim($_POST['regtype']);   //f-facebook,g-google,n-normal
    $webid=trim($_POST['hiddenid']);
	$targeturl=trim($_POST['targeturl']);

	$issendotp=0;
	if($regtype=='')
	{
		$regtype='n';
		$isnormal=1;
	}
	else if($regtype=='g' || $regtype=='f')
	{
		$isnormal=0;
	}
	$datetime=$IISMethods->getdatetime();
	
	$response['issendotp'] = $issendotp;

	if($name && $contact)
	{
		$qrychk="SELECT id,personname,contact from tblpersonmaster where contact=:contact AND isnull(isverified,0)=1 AND isnull(isdelete,0)=0";
		$parms = array(
			':contact'=>$contact,
		);
		$result_ary=$DB->getmenual($qrychk,$parms);
		if(sizeof($result_ary) > 0)
		{
			$row=$result_ary[0];

			$otp=$IISMethods->RandomOTP(6);
			$contact=$row['contact'];

			try 
			{
				$DB->begintransaction();

				//Person Data
				$updqry=array(					
					'[otp]'=>$otp,
					'[update_uid]'=>$unqid,
					'[update_date]'=>$datetime,
				);
				$extraparams=array(
					'[id]'=>$row['id']
				);
				$DB->executedata('u','tblpersonmaster',$updqry,$extraparams);

				//Person User Type
				$qrychk="SELECT * from tblpersonutype where utypeid=:utypeid and pid=:pid";
				$parms = array(
					':utypeid'=>$config-> getCustUtypeid(),
					':pid'=>$row['id'],
				);
				$result_utype=$DB->getmenual($qrychk,$parms);

				if(sizeof($result_utype) > 0)
				{
				}
				else
				{
					$qryut="SELECT usertype from tblusertypemaster where id=:id";
					$parmsut = array(
						':id'=>$config-> getCustUtypeid(),
					);
					$result_ut=$DB->getmenual($qryut,$parmsut);

					$utid = $IISMethods->generateuuid();
					$insperutype=array(	
						'[id]'=>$utid,				
						'[pid]'=>$row['id'],
						'[utypeid]'=>$config-> getCustUtypeid(),
						'[userrole]'=>$result_ut[0]['usertype'],
						'[entry_date]'=>$datetime,
					);
					$DB->executedata('i','tblpersonutype',$insperutype,'');
				}

				//Person OTP
				$potid = $IISMethods->generateuuid();
				$insperotp=array(	
					'[id]'=>$potid,
					'[type]'=>'Register',	
					'[pid]'=>$row['id'],
					'[otp]'=>$otp,
					'[entry_date]'=>$datetime,
				);
				$DB->executedata('i','tblpersonotp',$insperotp,'');


				$status=1;
				//$message=$errmsg['insert'];
				$message='Verification code sent to '.$contact.'.';

				$DB->committransaction($DBLog);
			}
			catch (Exception $e) 
			{
				$DB->rollbacktransaction($e,$DBLog);
				$status=0;
				$message=$errmsg['dbtransactionerror'];
			}	
			


			$issendotp=1;

			$response['issendotp'] = $issendotp;
			$response['contactno'] = $contact;

			
		}
		else
		{
			$otp=$IISMethods->RandomOTP(6);
			$unqid = $IISMethods->generateuuid();


			try 
			{
				$DB->begintransaction();

				//Person Data
				$insqry=array(					
					'[id]'=>$unqid,
					'[accgrpid]'=>$config-> getCustAccgrpid(),	
					'[personname]'=>$name,
					'[contact]'=>$contact,
					'[username]'=>$contact,
					'[password]'=>$contact,
					'[email]'=>'',							
					'[isactive]'=>1,
					'[isverified]'=>0,
					'[isemailverified]'=>0,
					'[isnormal]'=>$isnormal,
					'[regtype]'=>$regtype,
					'[otp]'=>$otp,
					'[platform]'=>$platform,
					'[entry_uid]'=>$unqid,
					'[entry_date]'=>$datetime,
				);
				$DB->executedata('i','tblpersonmaster',$insqry,'');


				//Person User Type
				$qrychk="SELECT usertype from tblusertypemaster where id=:id";
				$parms = array(
					':id'=>$config-> getCustUtypeid(),
				);
				$result_utype=$DB->getmenual($qrychk,$parms);
				$utid = $IISMethods->generateuuid();
				$insperutype=array(	
					'[id]'=>$utid,				
					'[pid]'=>$unqid,
					'[utypeid]'=>$config-> getCustUtypeid(),
					'[userrole]'=>$result_utype[0]['usertype'],
					'[entry_date]'=>$datetime,
				);
				$DB->executedata('i','tblpersonutype',$insperutype,'');

				$potid = $IISMethods->generateuuid();
				//Person OTP
				$insperotp=array(	
					'[id]'=>$potid,
					'[type]'=>'Register',	
					'[pid]'=>$unqid,
					'[otp]'=>$otp,
					'[entry_date]'=>$datetime,
				);
				$DB->executedata('i','tblpersonotp',$insperotp,'');

				$status=1;
				//$message=$errmsg['insert'];
				$message='Verification code sent to '.$contact.'.';

				$DB->committransaction($DBLog);
			}
			catch (Exception $e) 
			{
				$DB->rollbacktransaction($e,$DBLog);
				$status=0;
				$message=$errmsg['dbtransactionerror'];
			}	

			


			$issendotp=1;

			$response['issendotp'] = $issendotp;
			$response['contactno'] = $contact;

			
		}
	}
	else
	{
		$message=$errmsg['reqired'];
	}	
}
else if($action == 'checkregisterotp')
{
	$ro_contactno=trim($_POST['ro_contactno']);
	$otp=trim($_POST['ro_verifycode']);
	$targeturl=$_POST['targeturl'];

	$datetime=$IISMethods->getdatetime();

	if($otp)
	{
		$qry="SELECT pm.*,
			ISNULL(SUBSTRING((select ','+pu1.utypeid AS [text()] FROM tblpersonutype pu1 WHERE pu1.pid=pm.id FOR XML PATH ('')),2,10000),'') as usertypeid 
			FROM tblpersonmaster pm INNER JOIN tblpersonutype pu ON pu.pid=pm.id WHERE pm.contact = :contact AND isnull(pm.isdelete,0)=0";
		$parms = array(
			':contact'=>$ro_contactno,
		);
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary) > 0)
		{
			$row=$result_ary[0];

			if($row['otp'] == $otp)
			{
				$updqry=array(					
					'[otp]'=>'',
					'[isverified]'=>1,								
				);

				$extraparams=array(
					'[id]'=>$row['id']
				);

				$DB->executedata('u','tblpersonmaster',$updqry,$extraparams);

				$utypeid=$row['usertypeid'];
				$uid=$row['id'];

				$unqkey= $IISMethods->generateuuid();

				$iss='alhadaf-website';
				$LoginInfo->setWebsite_iss($iss);
				
				$LoginInfo->setWebsite_unqkey($unqkey);

				$key=$DB->getjwt($uid,$unqkey,$iss,$useragent);
				
				if($key['status']==1)
				{
					$LoginInfo->setWebsite_key($key['token']);
					
					$LoginInfo->setWebsite_utypeid($utypeid);
					$LoginInfo->setWebsite_uid($uid);

					$LoginInfo->setWebsite_fullname($row['personname']);
					$LoginInfo->setWebsite_isguestuser(0);
					
					$_SESSION[$config->getSessionName()]=$LoginInfo;

					$status=1;
					$message=$errmsg['registersuccess'];
				}
				else
				{
					$message = $errmsg['invalidtoken'];
					$status = 0;
				}
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
			$message=$errmsg['somethingwrong'];
		}
	}
	else
	{
		$message=$errmsg['reqired'];
	}	
}
else if($action == 'resendregloginotp')
{
	$contact=trim($_POST['contact']);
	$type=trim($_POST['type']);
	
	$datetime=$IISMethods->getdatetime();

	if($type == 1)
	{
		$typename='Register';
	}
	else if($type == 2)
	{
		$typename='Login';
	}

	if($contact)
	{
		$qry="SELECT pm.id,contact,otp FROM tblpersonmaster pm WHERE pm.contact = :contact AND isnull(pm.isdelete,0)=0";
		$parms = array(
			':contact'=>$contact,
		);
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary) > 0)
		{
			$row=$result_ary[0];
			$otp=$IISMethods->RandomOTP(6);
			
			try 
			{
				$DB->begintransaction();

				$updqry=array(					
					'[otp]'=>$otp,								
				);
				$extraparams=array(
					'[id]'=>$row['id']
				);
				$DB->executedata('u','tblpersonmaster',$updqry,$extraparams);
	
				//Person OTP
				$potid = $IISMethods->generateuuid();
				$insperotp=array(	
					'[id]'=>$potid,
					'[type]'=>$typename,	
					'[pid]'=>$row['id'],
					'[otp]'=>$otp,
					'[entry_date]'=>$datetime,
				);
				$DB->executedata('i','tblpersonotp',$insperotp,'');
	
				$status=1;
				$message='Verification code sent to '.$contact.'.';

				$DB->committransaction($DBLog);
			}
			catch (Exception $e) 
			{
				$DB->rollbacktransaction($e,$DBLog);
				$status=0;
				$message=$errmsg['dbtransactionerror'];
			}	
			
			
		}
		else
		{
			$status=0;
			$message=$errmsg['somethingwrong'];
		}
	}
	else
	{
		$message=$errmsg['somethingwrong'];
	}	
}
else if($action == 'login')
{
	$l_contactno=trim($_POST['l_contactno']);
	$targeturl=$_POST['targeturl'];

	$datetime=$IISMethods->getdatetime();

	if($l_contactno)
	{
		$qry="SELECT pm.* FROM tblpersonmaster pm 
			INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
			WHERE pm.contact = :contact AND pu.utypeid = :utypeid AND isnull(pm.isdelete,0)=0 AND isnull(pm.isverified,0)=1";
		$parms = array(
			':contact'=>$l_contactno,
			':utypeid'=>$config-> getCustUtypeid(),
		);
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary) > 0)
		{
			$row=$result_ary[0];
			$contact=$row['contact'];

			if($row['isactive'] == 1)
			{
				$otp=$IISMethods->RandomOTP(6);
				
				try 
				{
					$DB->begintransaction();

					$updqry=array(					
						'[otp]'=>$otp,
						'[isverified]'=>1,								
					);
					$extraparams=array(
						'[id]'=>$row['id']
					);
					$DB->executedata('u','tblpersonmaster',$updqry,$extraparams);
	
					//Person OTP
					$potid = $IISMethods->generateuuid();
					$insperotp=array(	
						'[id]'=>$potid,
						'[type]'=>'Login',	
						'[pid]'=>$row['id'],
						'[otp]'=>$otp,
						'[entry_date]'=>$datetime,
					);
					$DB->executedata('i','tblpersonotp',$insperotp,'');

					$status=1;
					$message='Verification code sent to '.$contact.'.';

					$DB->committransaction($DBLog);
				}
				catch (Exception $e) 
				{
					$DB->rollbacktransaction($e,$DBLog);
					$status=0;
					$message=$errmsg['dbtransactionerror'];
				}	

				$response['contactno'] = $contact;
			}
			else
			{
				$status=0;
				$message=$errmsg['deactivate'];
			}
		}
		else
		{
			$status=0;
			$message=$errmsg['invalidmobile'];
		}
	}
	else
	{
		$message=$errmsg['reqired'];
	}	
}
else if($action == 'checkloginotp')
{
	$lo_contactno=trim($_POST['lo_contactno']);
	$otp=trim($_POST['lo_verifycode']);
	$targeturl=$_POST['targeturl'];

	$datetime=$IISMethods->getdatetime();

	if($otp)
	{
		$qry="SELECT pm.*,
			ISNULL(SUBSTRING((select ','+pu1.utypeid AS [text()] FROM tblpersonutype pu1 WHERE pu1.pid=pm.id FOR XML PATH ('')),2,10000),'') as usertypeid 
			FROM tblpersonmaster pm INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
			WHERE pm.contact = :contact AND pu.utypeid = :utypeid AND isnull(pm.isdelete,0)=0 AND isnull(pm.isverified,0)=1";
		$parms = array(
			':contact'=>$lo_contactno,
			':utypeid'=>$config-> getCustUtypeid(),
		);
		$result_ary=$DB->getmenual($qry,$parms);
		if(sizeof($result_ary) > 0)
		{
			$row=$result_ary[0];

			if($row['otp'] == $otp)
			{
				if($row['isactive'] == 1)
				{
					$updqry=array(					
						'[otp]'=>'',
						'[isverified]'=>1,								
					);
					$extraparams=array(
						'[id]'=>$row['id']
					);
					$DB->executedata('u','tblpersonmaster',$updqry,$extraparams);

					$utypeid=$row['usertypeid'];
					$uid=$row['id'];

					$unqkey= $IISMethods->generateuuid();

					$iss='alhadaf-website';
					$LoginInfo->setWebsite_iss($iss);
					
					$LoginInfo->setWebsite_unqkey($unqkey);

					$key=$DB->getjwt($uid,$unqkey,$iss,$useragent);
					
					if($key['status']==1)
					{
						$LoginInfo->setWebsite_key($key['token']);
						
						$LoginInfo->setWebsite_utypeid($utypeid);
						$LoginInfo->setWebsite_uid($uid);

						$LoginInfo->setWebsite_fullname($row['personname']);
						$LoginInfo->setWebsite_isguestuser(0);
						
						$_SESSION[$config->getSessionName()]=$LoginInfo;

						$status=1;
						$message=$errmsg['loginsuccess'];
					}
					else
					{
						$message = $errmsg['invalidtoken'];
						$status = 0;
					}
				}
				else
				{
					$status=0;
					$message=$errmsg['deactivate'];
				}	
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
			$message=$errmsg['somethingwrong'];
		}
	}
	else
	{
		$message=$errmsg['reqired'];
	}	
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';
?>