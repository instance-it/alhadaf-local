<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

error_reporting(1);
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='insertsetting')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$twitterlink=$IISMethods->sanitize($_POST['twitterlink']);
		$instagramlink=$IISMethods->sanitize($_POST['instagramlink']);
		$facebooklink=$IISMethods->sanitize($_POST['facebooklink']);
		$whatsappno=$IISMethods->sanitize($_POST['whatsappno']);
		$iframemaplink=$IISMethods->sanitize($_POST['iframemaplink']);

		$work_fromtime=$IISMethods->sanitize($_POST['work_fromtime']);
		$work_totime=$IISMethods->sanitize($_POST['work_totime']);
		$book_duration=$IISMethods->sanitize($_POST['book_duration']);


		$insqry=array(
			'[twitterlink]'=>$twitterlink,
			'[instagramlink]'=>$instagramlink,
			'[facebooklink]'=>$facebooklink,
			'[whatsappno]'=>$whatsappno,
			'[iframemaplink]'=>$iframemaplink,
			'[work_fromtime]'=>$work_fromtime,
			'[work_totime]'=>$work_totime,
			'[book_duration]'=>$book_duration,
		);

		if($formevent=='addright')
		{
			$qrychk="SELECT top 1 id from tblsetting";
			$parms = array(
				
			);
			$result_ary=$DB->getmenual($qrychk,$parms);
			if(sizeof($result_ary) > 0)
			{
				$row=$result_ary[0];


				try 
				{
					$DB->begintransaction();
				
					$id = $IISMethods->sanitize($row['id']);

					$insqry['[entry_uid]']=$uid;	
					$insqry['[entry_date]']=$IISMethods->getdatetime();

					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('u','tblsetting',$insqry,$extraparams);

					$status=1;
					$message=$errmsg['update'];

					$DB->committransaction();
				}
				catch (Exception $e) 
				{
					$DB->rollbacktransaction($e);
					$status=0;
					$message=$errmsg['dbtransactionerror'];
				}	
			
			}
			else
			{
				try 
				{
					$DB->begintransaction();

					$unqid = $IISMethods->generateuuid();
					$insqry['[id]']=$unqid;	
					$insqry['[entry_uid]']=$uid;	
					$insqry['[entry_date]']=$IISMethods->getdatetime();
	
					$DB->executedata('i','tblsetting',$insqry,'');
	
					$status=1;
					$message=$errmsg['insert'];

					$DB->committransaction();
				}
				catch (Exception $e) 
				{
					$DB->rollbacktransaction($e);
					$status=0;
					$message=$errmsg['dbtransactionerror'];
				}
			
			}
		}
		
	}
	else if($action=='fillsettingdata')   
	{
		$qrychk="SELECT top 1 id,twitterlink,instagramlink,facebooklink,whatsappno,iframemaplink,work_fromtime,work_totime,book_duration from tblsetting";
		$parms = array(
			
		);
		$result_ary=$DB->getmenual($qrychk,$parms);
		if(sizeof($result_ary)>0)
		{
			$row=$result_ary[0];

			$response['id']=$IISMethods->sanitize($row['id']);
			$response['twitterlink']=$IISMethods->sanitize($row['twitterlink']);
			$response['instagramlink']=$IISMethods->sanitize($row['instagramlink']);
			$response['facebooklink']=$IISMethods->sanitize($row['facebooklink']);
			$response['whatsappno']=$IISMethods->sanitize($row['whatsappno']);
			$response['iframemaplink']=$IISMethods->sanitize($row['iframemaplink']);
			$response['work_fromtime']=$IISMethods->sanitize($row['work_fromtime']);
			$response['work_totime']=$IISMethods->sanitize($row['work_totime']);
			$response['book_duration']=$IISMethods->sanitize($row['book_duration']);

			$status=1;
			$message=$errmsg['datafound'];

		}
		else
		{
			$status=1;
			$message=$errmsg['nodatafound'];
		}
		
		
	} 
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  