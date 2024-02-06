<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

$phpself = $_POST['phpself'];
$mastertype = $_POST['mastertype'];
if($phpself){
	$redirecturl=$config->getWeburl().'lock-screen?session=timeout&targeturl='.$phpself.'&type='.$mastertype;
}else{
	$redirecturl=$config->getWeburl().'';
}
//if($isvalidUser['status'] == 1) 
//{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='logout')
	{
		try 
		{
			$DB->begintransaction();
			
			$macaddress = $IISMethods->sanitize($_POST['macaddress']);
			$os=$IISMethods->sanitize($_POST['os']);

			if($platform==1){
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

			$status=1;
			$message=$errmsg['logoutsuccess'];	

			$DB->committransaction();
		}
		catch (Exception $e) 
		{
			$DB->rollbacktransaction($e);
			$status=0;
			$message=$errmsg['dbtransactionerror'];
		}	
	}
//}
$response['redirecturl'] = $redirecturl;
$response['message'] = $message;
$response['status'] = $status;
$json_response = json_encode($response);
echo "$json_response";    
?>

  