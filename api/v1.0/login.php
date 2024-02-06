<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();

	//Login Data
	if($action == 'login')
	{
		$redirecturl = $IISMethods->sanitize($_POST['redirecturl']);
		$l_username = $IISMethods->sanitize($_POST['l_username']);
		$l_password = $IISMethods->sanitize($_POST['l_password']);

		if($l_username && $l_password)
		{
			$qry="SELECT pm.id,pm.personname,pm.contact,pm.email,pm.username,pm.password,pm.isactive,isnull(pm.isverified,0) as isverified,
				ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), pu.utypeid) AS [text()] FROM tblpersonutype pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,1000),'') as usertypeid 
				FROM tblpersonmaster pm 
				INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
				WHERE (pm.contact=:l_username1 or pm.email=:l_username2) AND pu.utypeid = :utypeid AND isnull(pm.isdelete,0)=0 ";
			$parms = array(
				':l_username1'=>$l_username,
				':l_username2'=>$l_username,
				':utypeid'=>$config->getMemberutype(),
			);
			
			$result_ary=$DB->getmenual($qry,$parms);
			if(sizeof($result_ary) > 0)
			{
				$row=$result_ary[0];

				$md5pass=md5($l_password);
				if($md5pass == $row['password'])
				{
					if($row['isverified'] == 1)  //When User Verified
					{
						if($row['isactive'] == 1)
						{
							
							/******************* Start For Add Session Data **********************/
							$utypeid=$row['usertypeid'];
							$uid=$row['id'];

							$unqkey= $IISMethods->generateuuid();

							if($platform == 4)
							{
								$iss='alhadaf-website';
							}
							
							//echo $uid.' ***** '.$unqkey.' ***** '.$iss.' ***** '.$useragent;
							$key=$DB->getjwt($uid,$unqkey,$iss,$useragent);
							// print_r($key['token']);
							
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
					$message=$errmsg['invalidlogemailpass'];
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
	//Check Social Login
	else if($action == 'checksociallogin')
	{  
		$webid=trim($_POST['webid']); 
		$name=trim($_POST['name']); 
		$email=trim($_POST['email']);
		$logtype=trim($_POST['logtype']);
		$targeturl=trim($_POST['targeturl']);

		$emailfound=0;	
		$datetime=$IISMethods->getdatetime();

		if($email)
		{
			$qry="SELECT pm.id,pm.personname,pm.contact,pm.email,pm.username,pm.password,pm.isactive,isnull(pm.isverified,0) as isverified,
				ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), pu.utypeid) AS [text()] FROM tblpersonutype pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,1000),'') as usertypeid 
				FROM tblpersonmaster pm 
				INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
				WHERE pm.email=:email AND pu.utypeid = :utypeid AND isnull(pm.isdelete,0)=0 ";	
			$parms = array(
				':email'=>$email,
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
						
						/******************* Start For Add Session Data **********************/
						$utypeid=$row['usertypeid'];
						$uid=$row['id'];

						$unqkey= $IISMethods->generateuuid();

						if($platform == 4)
						{
							$iss='alhadaf-website';
						}
						
						//echo $uid.' ***** '.$unqkey.' ***** '.$iss.' ***** '.$useragent;
						$key=$DB->getjwt($uid,$unqkey,$iss,$useragent);
						// print_r($key['token']);
						
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
							
							$emailfound=1;

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
				$emailfound=0;	
				$status=1;
				$message=$errmsg['emailnotregister'];
			}
		}
		else
		{
			$message=$errmsg['emailnotfound'];
		}
		
		$response['webid'] = $webid;
		$response['name'] = $name;
		$response['email'] = $email;
		$response['logtype'] = $logtype;
		$response['emailfound'] = $emailfound;
	}




}	
	
require_once dirname(__DIR__, 2).'\config\apifoot.php';  

?>

  