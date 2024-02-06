<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';


$redirecturl=$config->getWeburl().'home';

//if($isvalidUser['status'] == 1) 
//{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='logout')
	{
		$macaddress = $IISMethods->sanitize($_POST['macaddress']);
		$os=$IISMethods->sanitize($_POST['os']);
	
		if($platform==4){
			session_unset();
		}

		$ary=array(
			'[isvalid]'=>0,
			'[update_date]'=>$IISMethods->getdatetime(),
		);
		$extra = array(
			'[unqkey]'=>$unqkey,
			'[uid]'=>$uid,
			'[iss]'=>$iss,
			'[useragent]'=>$useragent,
		);
		$DB->executedata('u','tblexpiry',$ary,$extra);

		$extraparams=array(
			'[macaddress]'=>$macaddress,
			'[os]'=>$os
		);
		$DB->executedata('d','tbldevice','',$extraparams);

		$guestuserid = 'guestuser-'.session_id();
		$unqkey= $IISMethods->generateuuid();

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
			$message=$errmsg['logoutsuccess'];	
			
		}
		else
		{
			$status=0;
			$message=$errmsg['sessiontimeout'];
		}
		
	}
//}
$response['redirecturl'] = $redirecturl;
$response['message'] = $message;
$response['status'] = $status;
$json_response = json_encode($response);
echo "$json_response";    
?>

  