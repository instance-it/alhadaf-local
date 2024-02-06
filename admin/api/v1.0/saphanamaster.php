<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='fillsapitems')   
	{
		if($config->getIsAccessSAP() == 1)
		{
			//Login API in SAP (HaNa DB)
			$resLoginData=$DB->SAPLoginAPIData($SubDB); 
			
			if($resLoginData['SessionId'] != '')
			{
				$CURLOPT_URL=$config->getCurlSAPapiurl().'Items?$select=ItemCode,ItemName';
				$CURLOPT_CUSTOMREQUEST='GET';
				$CURLOPT_POSTFIELDS=$SAPDataJosn;
				
				$CURLOPT_HTTPHEADER=array(
					'Content-Type: application/json',
					'Cookie: B1SESSION='.$resLoginData['SessionId'].'; ROUTEID='.$resLoginData['RouteId']
				);
				$resItemData=$IISMethods->FnCURLResponse($CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS,$CURLOPT_HTTPHEADER,'List Item');
				
				$result_ary=$resItemData['value'];
				

				if(sizeof($result_ary)>0)
				{
					if($responsetype=='HTML')
					{ 
						$htmldata.='<option value="">Select SAP Item</option>';
						for($i=0;$i<sizeof($result_ary);$i++)
						{
							$row=$result_ary[$i];
							$htmldata.='<option value="'.$row['ItemCode'].'">'.$row['ItemName'].' ('.$row['ItemCode'].')</option>';
						}
						$response['data']=$htmldata;
					}
				}
			}	
			
			
		}
		
		
		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillsapvat')   
	{
		if($config->getIsAccessSAP() == 1)
		{
			//Login API in SAP (HaNa DB)
			$resLoginData=$DB->SAPLoginAPIData($SubDB); 
			
			if($resLoginData['SessionId'] != '')
			{
				$CURLOPT_URL=$config->getCurlSAPapiurl().'VatGroups?$select=Code,Name';
				$CURLOPT_CUSTOMREQUEST='GET';
				$CURLOPT_POSTFIELDS=$SAPDataJosn;
				
				$CURLOPT_HTTPHEADER=array(
					'Content-Type: application/json',
					'Cookie: B1SESSION='.$resLoginData['SessionId'].'; ROUTEID='.$resLoginData['RouteId']
				);
				$resVATData=$IISMethods->FnCURLResponse($CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS,$CURLOPT_HTTPHEADER,'List Item');
				
				$result_ary=$resVATData['value'];
				

				if(sizeof($result_ary)>0)
				{
					if($responsetype=='HTML')
					{ 
						$htmldata.='<option value="">Select SAP VAT</option>';
						for($i=0;$i<sizeof($result_ary);$i++)
						{
							$row=$result_ary[$i];
							$htmldata.='<option value="'.$row['Code'].'">'.$row['Code'].'  -  '.$row['Name'].'</option>';
						}
						$response['data']=$htmldata;
					}
				}
			}
			
		}
		
		
		$status=1;
		$message=$errmsg['success'];
	}
	

	
	

	

	
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  