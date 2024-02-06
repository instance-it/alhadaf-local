<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

error_reporting(1);
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='insertsmtpconfiguration')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$hostname=$IISMethods->sanitize($_POST['hostname']);
		$port=$IISMethods->sanitize($_POST['port']);
		$name=$IISMethods->sanitize($_POST['name']);
		$emailid=$IISMethods->sanitize($_POST['emailid']);
		$replyemailid=$IISMethods->sanitize($_POST['replyemailid']);
		$password=$IISMethods->sanitize($_POST['password']);


		$insqry=array(
			'[hostname]'=>$hostname,
			'[port]'=>$port,
			'[name]'=>$name,
			'[emailid]'=>$emailid,
			'[replyemailid]'=>$replyemailid,
			'[password]'=>$password,
		);

		if($formevent=='addright')
		{
			$qrychk="SELECT top 1 id from tblsmtpconfig";
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

					$insqry['[update_uid]']=$uid;	
					$insqry['[update_date]']=$IISMethods->getdatetime();

					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('u','tblsmtpconfig',$insqry,$extraparams);

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
	
					$DB->executedata('i','tblsmtpconfig',$insqry,'');
	
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
	else if($action=='fillsmtpconfigurationdata')   
	{
		$qrychk="SELECT top 1 id,hostname,port,name,emailid,replyemailid,password from tblsmtpconfig";
		$parms = array(
			
		);
		$result_ary=$DB->getmenual($qrychk,$parms);
		if(sizeof($result_ary)>0)
		{
			$row=$result_ary[0];

			$response['id']=$IISMethods->sanitize($row['id']);
			$response['hostname']=$IISMethods->sanitize($row['hostname']);
			$response['port']=$IISMethods->sanitize($row['port']);
			$response['name']=$IISMethods->sanitize($row['name']);
			$response['emailid']=$IISMethods->sanitize($row['emailid']);
			$response['replyemailid']=$IISMethods->sanitize($row['replyemailid']);
			$response['password']=$IISMethods->sanitize($row['password']);

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

  