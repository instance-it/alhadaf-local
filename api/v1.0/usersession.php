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
require_once dirname(__DIR__, 2).'\config\apiconfig.php';


$action = $_REQUEST['action'];

$status=0;
$message=$errmsg['invalidrequest'];


if($key) 
{
	$skey = md5('alhadafrangeshoot_instance04022022');  //430aa391dd70a20241a97a79a85c975f
	if (trim($key) == trim($skey)) 
	{
		//Get User Session
		if($action == 'getusersession')
		{
			$guestuserid = 'guestuser-'.session_id();
			$unqkey= $IISMethods->generateuuid();

			// if($platform == 2)  //Android
			// {
			// 	$iss='alhadaf-android';
			// }
			// else if($platform == 3)  //iOS
			// {
			// 	$iss='alhadaf-ios';
			// }
			
			$key=$DB->getjwt($guestuserid,$unqkey,$iss,$useragent);
			if($key['status']==1)
			{
				$key1=$IISMethods->sanitize($key['token']);
				$unqkey=$IISMethods->sanitize($unqkey);
				$iss=$IISMethods->sanitize($iss);
				$uid =$IISMethods->sanitize($guestuserid);

				$response['guestkey'] = $key['token'];
				$response['guestunqkey'] = $unqkey;
				$response['iss'] = $iss;
				$response['uid'] = $uid;

				$status=1;
				$message=$errmsg['success'];
				
			}
			else
			{
				$status=0;
				$message=$errmsg['sessiontimeout'];
			}	
		}
	}
	else
	{
		$status=0;
		$message=$errmsg['invalidtoken'];
	}	
}
else
{
	$status=0;
	$message=$errmsg['invalidtoken'];
}


require_once dirname(__DIR__, 2).'\config\apifoot.php';
?>