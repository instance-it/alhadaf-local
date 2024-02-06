<?php 
header("Content-Type:application/json");
require_once 'config/init.php';
require_once 'config/apiconfig.php';

$title = "New Order";
$msgtext="Your order(123456) has been placed.";
$uid='3FA08E83-3E23-419A-8EB1-29FB8F779692';

$qrydevice="SELECT 
	isnull((SELECT SUBSTRING((select ','+CONVERT(VARCHAR(max), deviceid) AS [text()] from tbldevice where os='a' and apptype=1 and uid=:amemberid FOR XML PATH ('')),2,1000000)),'') as adeviceid,
	isnull((SELECT SUBSTRING((select ','+CONVERT(VARCHAR(max), deviceid) AS [text()] from tbldevice where os='i' and apptype=1 and uid=:imemberid FOR XML PATH ('')),2,1000000)),'') as ideviceid";
$deviceparams=array(            
	':amemberid'=>$uid,
	':imemberid'=>$uid,
);

$resdevice=$DB->getmenual($qrydevice,$deviceparams);
$rowdevice=$resdevice[0];

$adeviceid= explode(",", $rowdevice['adeviceid']);

$clickaction="alhadaf_ntf";
$clickflag=1;
$data="";
$pagename='orderhistory';
$actionname='listorderhistory';
				
$extra = array('clickflag' => $clickflag,'pagename' =>$pagename,'actionname' =>$actionname,'orderid' =>'','imageurl' => '');

//$IISMethods->androidnotification($adeviceid,$msgtext,$title,$clickaction,$extra);


define( 'API_ACCESS_KEY', 'AAAAq-IK96g:APA91bFIIuJoCiRhzc5j5zVN865-QgMJZp8POvHOZwMRzloSZBQ0tIj2crU6Zc4x20DhdrOTLjx9AkYYQ19sGDlym2LHlkw06hKObv5qoy5gvxl8hOVM9VrXkZMfbz9WiHXkwGpbaXdZ' );


$msg = array
	(
		'body' 	=> $msgtext,
		'title'	=> $title,
		'click_action' => $clickaction,
		//'icon'	=> 'images/bell.png',
		'icon'	=> 'myicon',/*Default Icon*/
		'sound' => 'mySound'/*Default sound*/  	
	);

$fields = array
	(
		'registration_ids' => $adeviceid,
		'notification' => $msg,
		'data' => $extra
	);
$headers = array
	(
		'Authorization: key=' . API_ACCESS_KEY,
		'Content-Type: application/json'
	);
	
		
#Send Reponse To FireBase Server
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
echo $result;
curl_close( $ch );

//$data1 = json_encode( $fields );
//print_r($data1);

?>

  