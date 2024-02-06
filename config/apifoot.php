<?php 
$response['key']=(string)$isvalidUser['key'];
$response['unqkey']=(string)$isvalidUser['unqkey'];
if($platform==1 && $isvalidUser['key']!='' && $isvalidUser['unqkey']!=''){ //for web
	$LoginInfo->setKey((string)$isvalidUser['key']);
	$LoginInfo->setUnqkey((string)$isvalidUser['unqkey']);
}


/**------------------------------------Pagenation------------------------------------- */
$response['nextpage']=$nextpage;
if(sizeof($common_listdata)==$per_page){ 
	$showdata=1; 
	$showentries=($nextpage*$per_page);
}else{ 
	$showdata=0; 
	$showentries=(($nextpage-1)*($per_page))+ sizeof($common_listdata); }
$response['loadmore']=$showdata;
$response['datasize']=sizeof($common_listdata);
$response['showentries']=$showentries;
/**------------------------------------Pagenation------------------------------------- */

$response['url'] = (string)$targeturl;
$response['message'] = (string)$message;
$response['status'] = $status;

$json_response = json_encode($response);
echo "$json_response";  
?>

  