<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';

	
//Login API in SAP (HaNa DB)
//$resLoginData=$DB->SAPLoginAPIData($SubDB); 




$response['logindata']=$resLoginData;
$status=1;
$message=$errmsg['success'];


require_once dirname(__DIR__, 2).'\config\apifoot.php';

?>

  