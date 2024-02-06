<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	 
	//List Route Wise vessel Data
	if($action=='adddevicedata')
	{
		$appversion =$IISMethods->sanitize($_POST['appversion']);
		$devicemodelname = $IISMethods->sanitize($_POST['devicemodelname']);
		$macaddress = $IISMethods->sanitize($_POST['macaddress']);
		$ipaddress=$_SERVER['REMOTE_ADDR'];
		$deviceid=$IISMethods->sanitize($_POST['deviceid']);
		$os=$IISMethods->sanitize($_POST['os']);
		$osversion = $IISMethods->sanitize($_POST['osversion']);
		$apptype=1;

		$datetime=$IISMethods->getdatetime();

		if($os=='a')
		{ $phonetype=1; }
		else { $phonetype=2; }

		$qrychkapp="select * from tblappupdate WHERE apptype=:apptype and phonetype=:phonetype order by timestamp desc";
		$appupdateparams=array( 
			':apptype'=>$apptype,
			':phonetype'=>$phonetype,
		);
		$reschkapp=$DB->getmenual($qrychkapp,$appupdateparams);
		$rowchkapp=$reschkapp[0];

		$isappupdate=0;
		if($appversion != $rowchkapp['vername'] && sizeof($reschkapp) > 0)
		{
			$isforcefully=$rowchkapp['isforcefully'];
			$isforcelogout=$rowchkapp['isforcelogout'];

			$isappupdate=1;
			$message=$errmsg['appupdate'];
			$status=3;
		}

		$qrydup="SELECT * FROM tbldevice WHERE macaddress=:macaddress and apptype=:apptype";
		$deviceparams=array( 
			':apptype'=>$apptype,           
			':macaddress'=>$macaddress,
		);
		$resdup=$DB->getmenual($qrydup,$deviceparams);
		$numdup= sizeof($resdup);
		$rowdup = $resdup[0];
		if($numdup>0)
		{
			if($deviceid == '' || $deviceid == null)
			{
				$deviceid = $rowdup['deviceid'];
			}
		
			$upddata=array(
				'[os]'=>$os,
				'[appversion]'=>$appversion,
				'[devicemodelname]'=>$devicemodelname,
				'[ipaddress]'=>$ipaddress,
				'[osversion]'=>$osversion,
				'[deviceid]'=>$deviceid,
				'[uid]'=>$uid,
				'[useragent]'=>$useragent,
				'[entry_date]'=>$datetime
			);

			$extraparams=array(
				'[apptype]'=>$apptype,
				'[macaddress]'=>$macaddress
			);
			$DB->executedata('u','tbldevice',$upddata,$extraparams);
			
			if($rowdup['appversion'] < $appversion)
			{
				$upddata=array(
					'appupdate'=>$datetime,
				);


				$extraparams=array(
					'[id]'=>$rowdup['id'],
				);
				$DB->executedata('u','tbldevice',$upddata,$extraparams);
			}
			
		}
		else 
		{
			$id= $IISMethods->generateuuid();
			$insdata=array(
				'[id]'=>$id,
				'[uid]'=>$uid,
				'[os]'=>$os,
				'[appversion]'=>$appversion,
				'[devicemodelname]'=>$devicemodelname,
				'[macaddress]'=>$macaddress,
				'[ipaddress]'=>$ipaddress,
				'[osversion]'=>$osversion,
				'[deviceid]'=>$deviceid,
				'[useragent]'=>$useragent,
				'[entry_date]'=>$datetime,
				'[appupdate]'=>$datetime,
				'[apptype]'=>$apptype,
			);
			$DB->executedata('i','tbldevice',$insdata,'');
		}

		$status=1;
		$message=$errmsg['success'];

		if($isappupdate==1)
		{
			$status=3;
			$message=$errmsg['appupdate'];
		}
			
	}


	$isremainupddocument=0;
	$qryper="select id,isnull(qataridproof,'') as qataridproof,isnull(passportproof,'') as passportproof,isnull(othergovernmentproof,'') as othergovernmentproof 
		from tblpersonmaster where convert(varchar(50),id)=:uid";
	$perparams=array( 
		':uid'=>$uid,           
	);
	$resper=$DB->getmenual($qryper,$perparams);
	if(sizeof($resper) > 0)
	{
		$rowper = $resper[0];

		if($rowper['qataridproof'] == '' || $rowper['passportproof'] == '')
		{
			$isremainupddocument=1;
		}
	}

	$response['isremainupddocument'] = (double)$isremainupddocument;

	//$response['AccDelURL']=''; 
	$response['AccDelURL']=$config->getWeburl().'account/deleteaccount'; 

	
}

require_once dirname(__DIR__, 2).'\config\apifoot.php';

?>

  