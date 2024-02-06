<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	 
	//Insert Contact US Data
	if($action == 'insertcontactus')
	{
		$name = $IISMethods->sanitize($_POST['c_name']);
		$email = $IISMethods->sanitize($_POST['c_email']);
		$mobile = $IISMethods->sanitize($_POST['c_mobile']);
		$message = $IISMethods->sanitize($_POST['c_message']);
	
		$unqid = $IISMethods->generateuuid();

		if($name && $email && $mobile && $message)
		{
			try 
			{
				$DB->begintransaction();

				$insqry=array(
					'[id]'=>$unqid,					
					'[name]'=>$name,
					'[email]'=>$email,
					'[mobile]'=>$mobile,
					'[message]'=>$message,
					'[timestamp]'=>$IISMethods->getdatetime(),	
					'[entry_date]'=>$IISMethods->getdatetime(),	
				);
				$DB->executedata('i','tblcontactus',$insqry,'');

				//Send User Contact US Email To Admin
				$DB->usercontactusemails($unqid);

				$status=1;
				$message=$errmsg['contactussuccess'];
				
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
			$status=0;
			$message=$errmsg['reqired'];
		}
		
	}
	
	

	
}

require_once dirname(__DIR__, 2).'\config\apifoot.php';

?>

  