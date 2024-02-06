<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';



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
			'[os]'=>$os,
			'[apptype]'=>2
		);
		$DB->executedata('d','tbldevice','',$extraparams);

		$guestuserid = 'guestuser-'.session_id();
		$unqkey= $IISMethods->generateuuid();

		$status=1;
		$message=$errmsg['logoutsuccess'];		
	}
//}
$response['message'] = $message;
$response['status'] = $status;
$json_response = json_encode($response);
echo "$json_response";    
?>

  