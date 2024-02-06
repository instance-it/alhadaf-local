<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\home.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();


	//Accept Cookies
	if($action == 'insertacceptcookie')
	{
		$ipaddress=$_SERVER['REMOTE_ADDR'];
		$expiretime = $_POST['expiretime'];
		$unqid = $IISMethods->generateuuid();

		if($uid && $key && $unqkey && $iss)
		{
			$insqry=array(
				'[id]'=>$unqid,					
				'[uid]'=>$uid,
				'[token]'=>$key,
				'[unqkey]'=>$unqkey,
				'[iss]'=>$iss,	
				'[expiretime]'=>$expiretime,	
				'[ipaddress]'=>$ipaddress,		
				'[timestamp]'=>$datetime,						
			);

			$DB->executedata('i','tblusercookie',$insqry,'');

			$status=1;
			$message=$errmsg['success'];
		}
		
	}
	//List Equipment Data
	else if($action == 'listequipmentdata')
	{	
		$showonhome=$IISMethods->sanitize($_POST['showonhome']);   //From Home Page
		$equipmentdata=new equipmentdata();

		$qry="select em.id,em.name,concat(:defaultimgurl,em.img) as img 
			from tblequipmentmaster em 
			where em.isactive = 1 ";
		$parms = array(
			':defaultimgurl'=>$imgpath,
		);
		if($showonhome == 1)  //When From Home Page
		{
			$qry.=" and em.showonhome=:showonhome ";
			$parms[':showonhome']=$showonhome;
		}
		$qry.=" order by (case when (em.displayorder>0) then em.displayorder else 99999 end)";
		$equipmentdata=$DB->getmenual($qry,$parms,'equipmentdata');

		$response['isequipmentdata']=0;
		if($equipmentdata)
		{
			$response['isequipmentdata']=1;
			$response['equipmentdata']=$equipmentdata;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Video Data
	else if($action == 'listvideodata')
	{	
		$listvideodata=new listvideodata();

		$qry="select vm.id,vm.videoid
			from tblvideomaster vm 
			where vm.isactive = 1 order by (case when (vm.displayorder>0) then vm.displayorder else 99999 end)";
		$parms = array();
		$listvideodata=$DB->getmenual($qry,$parms,'listvideodata');

		$response['isvideodata']=0;
		if($listvideodata)
		{
			$response['isvideodata']=1;
			$response['videodata']=$listvideodata;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	// List Web Content
	else if($action == 'listcontentsetting')
	{
		$contenttypeid = $IISMethods->sanitize($_POST['contenttypeid']);
		$type = $IISMethods->sanitize($_POST['type']);

		if($type == 1)  //From App (Terms and Conditions)
		{
			$contenttypeid = $config->getTermsConditionId();
		}
		else if($type == 2)  //From App (Privacy Policy)
		{
			$contenttypeid = $config->getPrivacyPolicyId();
		}

		$qrychk="select * from tblwebcontent where contenttypeid=:contenttypeid";
		$parms = array(
			':contenttypeid'=>$contenttypeid,
		);
		$result_ary=$DB->getmenual($qrychk,$parms);

		if(sizeof($result_ary)>0)
		{
			$row=$result_ary[0];
			$response['description']=$row['description'];

			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			if($contenttypeid == $config->getTermsConditionId())
			{
				$status=0;
				$message=$errmsg['notncfound'];
			}
			else if($contenttypeid == $config->getPrivacyPolicyId())
			{
				$status=0;
				$message=$errmsg['nopolicyfound'];
			}
			
		}
		
	}
	//List Slider Data
	else if($action == 'listsliderdata')
	{	
		$listsliderdata=new listsliderdata();

		$qry="select id,title,concat(:defaultimgurl,image) as image 
			from tblslidermaster
			where isactive = 1 order by (case when (displayorder>0) then displayorder else 99999 end)";
		$parms = array(
			':defaultimgurl'=>$imgpath,
		);
		$listsliderdata=$DB->getmenual($qry,$parms,'listsliderdata');

		$response['issliderdata']=0;
		if($listsliderdata)
		{
			$response['issliderdata']=1;
			$response['sliderdata']=$listsliderdata;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Company Data
	else if($action == 'listcompanydata')
	{
		$response['contact1']=$IISMethods->sanitize($CompanyInfo->getContact1());
		$response['contact2']=$IISMethods->sanitize($CompanyInfo->getContact2());
		$response['email1']=$IISMethods->sanitize($CompanyInfo->getEmail1());
		$response['address']=$IISMethods->sanitize($CompanyInfo->getAddress());
		
		$response['instagramlink']=$IISMethods->sanitize($ProjectSetting->getInstagramlink());
		$response['twitterlink']=$IISMethods->sanitize($ProjectSetting->getTwitterlink());
		$response['facebooklink']=$IISMethods->sanitize($ProjectSetting->getFacebooklink());
	
		$status=1;
		$message=$errmsg['success'];
	}
	//Insert Footer Email Data
	else if($action == 'insertemaildata')
	{
		$signupemail = $_POST['signupemail'];

		if($signupemail)
		{
			try 
			{
				$DB->begintransaction();

				$unqid = $IISMethods->generateuuid();

				$insqry=array(
					'[id]'=>$unqid,					
					'[email]'=>$signupemail,
					'[timestamp]'=>$datetime,						
				);

				$DB->executedata('i','tbluseremail',$insqry,'');

				$status=1;
				$message=$errmsg['emailsuccess'];

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



require_once dirname(__DIR__, 2).'\config\apifoot.php';  

?>

  