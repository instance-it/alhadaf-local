<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

$action=$_POST['action'];

$key=$IISMethods->sanitize($headerparams['key']);
$iss=$IISMethods->sanitize($headerparams['iss']);
//$key = $IISMethods->sanitize($_POST['key']);

$status=0;
$message=$errmsg['invalidrequest'];

if($key) 
{	
	$skey = md5('alhadaf_shooting17022022');  //7ed03ebe32559e0b45c915dfa8ad2582

	if (trim($key) == trim($skey)) 
	{
		//Login Data
		if($action == 'login')
		{
			$l_username = $IISMethods->sanitize($_POST['l_username']);
			$l_password = $IISMethods->sanitize($_POST['l_password']);

			if($l_username && $l_password)
			{
				$qry="SELECT distinct pm.id,pm.personname,pm.contact,pm.email,pm.username,pm.password,pm.isactive,isnull(pm.isverified,0) as isverified,isnull(ut.hasposlogin,0) as hasposlogin,
					ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), pu.utypeid) AS [text()] FROM tblpersonutype pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,1000),'') as usertypeid 
					FROM tblpersonmaster pm 
					INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
					INNER JOIN tblusertypemaster ut ON ut.id=pu.utypeid
					WHERE pm.username=:l_username AND isnull(pm.isdelete,0)=0 ";
				$parms = array(
					':l_username'=>$l_username,
				);
				//echo $qry;
				//print_r($parms);
				$result_ary=$DB->getmenual($qry,$parms);
				if(sizeof($result_ary) > 0)
				{
					$row=$result_ary[0];

					$md5pass=md5($l_password);
					if($md5pass == $row['password'])
					{
						if($row['hasposlogin'] == 1)  //When POS Login
						{
							if($row['isactive'] == 1)
							{
								/******************* Start For Add Session Data **********************/
								$utypeid=$row['usertypeid'];
								$uid=$row['id'];

								$unqkey= $IISMethods->generateuuid();

								$key=$DB->getjwt($uid,$unqkey,$iss,$useragent);
								
								if($key['status']==1)
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
							$message=$errmsg['userright'];
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
	}	
	else 
	{
		$status=0;
		$message = $errmsg['invalidtoken'];
	}




}	
	
require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  