<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';

	
$qrymem="SELECT id,personname,address,email,contact FROM tblpersonmaster WHERE CONVERT(VARCHAR(255), id)=:memberid";
$memparms = array(
    ':memberid'=>'286B0F99-BC50-4AAA-9DD9-00943AD06751',
);
$resmem=self::getmenual($qrymem,$memparms);

if(sizeof($resmem) > 0)
{
    $rowmem=$resmem[0];



    $SAPData['CardCode']="";              
    $SAPData['CardName']=$rowmem['personname']; 
    $SAPData['CardType']='C'; 
    $SAPData['Address']=$rowmem['address']; 
    $SAPData['MailAddress']=$rowmem['email']; 
    $SAPData['Phone1']=$rowmem['contact']; 
    $SAPData['Series']='70'; 
    

    $SAPDataJosn=json_encode($SAPData,true);

    //print_r($SAPDataJosn);

    $CURLOPT_URL=$config->getCurlSAPapiurl().'BusinessPartners';
    $CURLOPT_CUSTOMREQUEST='POST';
    $CURLOPT_POSTFIELDS=$SAPDataJosn;
    
    $CURLOPT_HTTPHEADER=array(
        'Content-Type: application/json',
    );
    $resLoginData=$IISMethods->FnCURLResponse($CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS,$CURLOPT_HTTPHEADER,'Add Member');

    //print_r($resLoginData);

    

}

$response['logindata']=$resLoginData;
$status=1;
$message=$errmsg['success'];


require_once dirname(__DIR__, 2).'\config\apifoot.php';

?>

  