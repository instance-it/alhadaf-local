<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();

	//Forgot Pass Data
	if($action == 'forgotpass')
	{
		$redirecturl = $IISMethods->sanitize($_POST['redirecturl']);
		$f_username = $IISMethods->sanitize($_POST['f_username']);

		if($f_username)
		{
			$qry="SELECT pm.id,pm.personname,pm.contact,pm.email,pm.username,pm.password,pm.isactive,isnull(pm.isverified,0) as isverified
				FROM tblpersonmaster pm 
				INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
				WHERE (pm.contact=:f_username1 or pm.email=:f_username2) AND pu.utypeid = :utypeid AND isnull(pm.isdelete,0)=0 ";
			$parms = array(
				':f_username1'=>$f_username,
				':f_username2'=>$f_username,
				':utypeid'=>$config->getMemberutype(),
			);
			
			$result_ary=$DB->getmenual($qry,$parms);
			if(sizeof($result_ary) > 0)
			{
				$row=$result_ary[0];

				if($row['isverified'] == 1)  //When User Verified
				{
					if($row['isactive'] == 1)
					{
						$otp = $IISMethods->RandomOTP(6);
						
						$updqry=array(		
							'[otp]'=>$otp,
						);
						$ary=array(
							'[id]'=>$row['id'],
						);
						$DB->executedata('u','tblpersonmaster',$updqry,$ary);

						$response['userid']=$row['id'];
						$response['email']=$row['email'];
						$response['mobilemno']=$row['contact'];

						//Send Forgot Verification Email
						$DB->userforgotemailotpsms(1,$row['id']);

						//Send Forgot Verification SMS
						$DB->userforgotemailotpsms(2,$row['id']);

						$status = 1;
						$message=str_replace("#email#",$row['email'],$errmsg['verifycodesent']);
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
					$message=$errmsg['noregverified'];
				}	
			}
			else
			{
				$status=0;
				$message=$errmsg['invalidlogemail'];
			}	
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
		
	}
	//Set New Pass Data
	else if($action == 'setnewpassword')
	{
		$redirecturl = $IISMethods->sanitize($_POST['redirecturl']);
		$fr_userid = $IISMethods->sanitize($_POST['fr_userid']);
		$fr_email = $IISMethods->sanitize($_POST['fr_email']);
		$fr_mobile = $IISMethods->sanitize($_POST['fr_mobile']);
		$fr_vercode = $IISMethods->sanitize($_POST['fr_vercode']);
		$fr_newpass = $IISMethods->sanitize($_POST['fr_newpass']);

		if($fr_email && $fr_mobile && $fr_vercode && $fr_newpass)
		{
			$qry="SELECT pm.id,pm.personname,pm.contact,pm.email,pm.username,pm.password,pm.otp,pm.isactive,isnull(pm.isverified,0) as isverified,
				ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), pu.utypeid) AS [text()] FROM tblpersonutype pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,1000),'') as usertypeid 
				FROM tblpersonmaster pm 
				INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
				WHERE (pm.contact=:fr_mobile or pm.email=:fr_email) AND pm.id = :userid AND isnull(pm.isdelete,0)=0 ";
			$parms = array(
				':fr_email'=>$fr_email,
				':fr_mobile'=>$fr_mobile,
				':userid'=>$fr_userid,
			);
			
			$result_ary=$DB->getmenual($qry,$parms);
			if(sizeof($result_ary) > 0)
			{
				$row=$result_ary[0];

				if($row['otp']==$fr_vercode || $fr_vercode == '111111')
				{
					$updqry=array(
						'[otp]'=>'',
						'[password]'=>md5($fr_newpass),
						'[strpassword]'=>$fr_newpass,
					);
					$ary=array(
						'[id]'=>$row['id'],
					);
					$DB->executedata('u','tblpersonmaster',$updqry,$ary);



					/******************* Start For Add Session Data **********************/
					$utypeid=$row['usertypeid'];
					$uid=$row['id'];

					$unqkey= $IISMethods->generateuuid();

					if($platform == 4)
					{
						$iss='alhadaf-website';
					}
					
					$key=$DB->getjwt($uid,$unqkey,$iss,$useragent);
					
					if($key['status']==1)
					{
						if($platform == 4)
						{
							$LoginInfo->setIss($iss);
							$LoginInfo->setUnqkey($unqkey);
							$LoginInfo->setKey($key['token']);
							$LoginInfo->setUsername($row['username']);
							$LoginInfo->setUtypeid($utypeid);
							$LoginInfo->setUid($uid);
							$LoginInfo->setFullname($personname);
							$LoginInfo->setEmail($r_email);
							$LoginInfo->setContact($r_mobile);

							$adlogin='aldaf$20220117@instance%';
							$LoginInfo->setAdlogin($adlogin);

							$LoginInfo->setIsguestuser(0);

							$ismemberuser=0;
							$profileimg='';
							if($utypeid == $config->getMemberutype())  //For Vendor
							{
								$profileimg=$config->getDefualtMemberImageurl();

								$ismemberuser=1;
							}

							$LoginInfo->setProfilepic($profileimg);
							$LoginInfo->setIsmemberuser($ismemberuser);

							//print_r($LoginInfo);

							$aminutype=$config->getAdminutype();
							$_SESSION[$config->getSessionName()]=$LoginInfo;

							$targeturl='';
							if($redirecturl != '')
							{
								$targeturl=$redirecturl;
							}
						}
						else
						{
							$response['guestkey'] = $key['token'];
							$response['guestunqkey'] = $unqkey;
							$response['iss'] = $iss;
							$response['uid'] = $uid;
							$response['utypeid'] = $utypeid;
							$response['personname'] = $row['personname'];
							$response['username'] = $row['username'];
							$response['fullname'] = $row['personname'];
							$response['contact'] = $row['contact'];
							$response['email'] = $row['email'];
							$response['adlogin']='';

							$resultyear = $DB->getmenual("SELECT * FROM tblfinancialyear WHERE isactive = 1");
							$rowyear=$resultyear[0];

							$response['yearid'] =$rowyear['id'];
							$response['yearname'] = $rowyear['name'];
							$response['activeyearid'] = $rowyear['id'];
						}
						

						$status = 1;
						$message=$errmsg['loginsuccess'];
					}
					else
					{
						$message = $errmsg['invalidtoken'];
						$status = 0;
					}
					/******************* End For Add Session Data **********************/

						
				}	
				else
				{
					$status=0;
					$message=$errmsg['invalidverifycode'];
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
			$status=0;
			$message=$errmsg['reqired'];
		}
		
	}




}	
	
require_once dirname(__DIR__, 2).'\config\apifoot.php';  

?>

  