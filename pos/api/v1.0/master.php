<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\master.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();

	//List Person Store
	if($action == 'listpersonstore')
	{	
		$listpersonstore=new listpersonstore();

		$qry="select distinct s.id,s.storename as name,:defaultposimage as image 
			from tblpersonstore ps 
			inner join tblstoremaster s on s.id=ps.storeid 
			where ps.pid=:uid and isnull(s.isactive,0)=1 
			order by s.storename";
		$parms = array(
			':uid'=>$uid,
			':defaultposimage'=>$config->getDefaultPOSImage(),
		);
		$listpersonstore=$DB->getmenual($qry,$parms,'listpersonstore');
		

		$response['isstoredata']=0;
		if($listpersonstore)
		{
			$response['isstoredata']=1;
			$response['storedata']=$listpersonstore;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Store Item Subcategory
	else if($action == 'liststoresubcategory')
	{	
		$storeid =$IISMethods->sanitize($_POST['storeid']);
		$isserviceorder =$IISMethods->sanitize($_POST['isserviceorder']);
		$liststoresubcategory=new liststoresubcategory();

		$qry="select distinct sc.id,REPLACE(sc.subcategory,'&amp;','&') as name,:defaultposicon as image 
			from tblitemmaster im 
			inner join tblsubcategory sc on sc.id=im.subcategoryid 
			inner join tblitemstore si on si.itemid=im.id 
			where im.isactive=1 and sc.isactive=1 and im.categoryid<>:mshipcatid and im.categoryid<>:packagecatid and im.categoryid<>:coursecatid  ";
		$parms = array(
			':mshipcatid'=>$config->getDefaultCatMembershipId(),
			':packagecatid'=>$config->getDefaultCatPackageId(),
			':coursecatid'=>$config->getDefaultCatCourseId(),
			':defaultposicon'=>$config->getDefaultPOSIcon(),
		);
		if($isserviceorder != 1)
		{
			$qry.=" and si.storeid=:storeid ";
			$parms[':storeid']=$storeid;

		}	
		$qry.=" order by REPLACE(sc.subcategory,'&amp;','&')";
		

		$liststoresubcategory=$DB->getmenual($qry,$parms,'liststoresubcategory');

		$response['issubcategorydata']=0;
		if($liststoresubcategory)
		{
			$response['issubcategorydata']=1;
			$response['subcategorydata']=$liststoresubcategory;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Store Item 
	else if($action == 'liststoreitem')
	{	
		$storeid =$IISMethods->sanitize($_POST['storeid']);
		$subcategoryid =$IISMethods->sanitize($_POST['subcategoryid']);
		$isserviceorder =$IISMethods->sanitize($_POST['isserviceorder']);

		$liststoreitem=new liststoreitem();

		$qry="select distinct im.id,REPLACE(im.itemname,'&amp;','&') as name,im.categoryid,c.category,im.subcategoryid,sc.subcategory,im.itemno,im.price,tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,
			ISNULL((select top 1 CONCAT(:baseimgurl,image) from tblitemimage where itemid = im.id order by displayorder),:defaultposimage) as image  
			from tblitemmaster im 
			inner join tblcategory c on c.id=im.categoryid
			inner join tblsubcategory sc on sc.id=im.subcategoryid
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tblitemstore si on si.itemid=im.id 
			where im.isactive=1 and im.categoryid<>:mshipcatid and im.categoryid<>:packagecatid and im.categoryid<>:coursecatid ";
		$parms = array(
			':baseimgurl'=>$imgpath,
			':defaultposimage'=>$config->getDefaultPOSImage(),
			':mshipcatid'=>$config->getDefaultCatMembershipId(),
			':packagecatid'=>$config->getDefaultCatPackageId(),
			':coursecatid'=>$config->getDefaultCatCourseId(),
		);
		if($subcategoryid)
		{
			$qry.=" and im.subcategoryid=:subcategoryid ";
			$parms[':subcategoryid']=$subcategoryid;
		}
		if($isserviceorder != 1)
		{
			$qry.=" and si.storeid=:storeid ";
			$parms[':storeid']=$storeid;

		}
		$qry.=" order by im.price ";
		$liststoreitem=$DB->getmenual($qry,$parms,'liststoreitem');

		$response['isitemdata']=0;
		if($liststoreitem)
		{
			$response['isitemdata']=1;
			$response['itemdata']=$liststoreitem;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Range Data
	else if($action == 'listrange')
	{	
		$isall = $IISMethods->sanitize($_POST['isall']);
		$range=new range();   

		$qry="SELECT distinct rm.id,rm.rangename as name 
		from tblrangemaster rm 
		inner join tblrangelane rl on rl.rangeid=rm.id 
		where isnull(rm.isactive,0)=1 
		order by rm.rangename";	
		$params = array(
			
		);
		$range=$DB->getmenual($qry,$params,'range');

		if($isall == 1)
		{
			$suballdata=array(					
				'id'=>'%',
				'name'=>'All',
			);
			array_unshift($range, $suballdata);
		}

		$response['israngedata']=0;
		if($range)
		{
			$response['israngedata']=1;
			$response['rangedata']=$range;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Range Wise Lane Data
	else if($action == 'listrangelane')
	{	
		$rangeid=$IISMethods->sanitize($_POST['rangeid']);  
		$isall = $IISMethods->sanitize($_POST['isall']);

		$rangelane=new rangelane();

		$qry="SELECT distinct l.id,l.name 
		from tblrangemaster rm 
		inner join tblrangelane rl on rl.rangeid=rm.id
		inner join tbllanemaster l on l.id=rl.laneid
		where isnull(rm.isactive,0)=1 and convert(varchar(50),rl.rangeid) like :rangeid 
		order by l.name";	
		$params = array(
			':rangeid'=>$rangeid,
		);
		$rangelane=$DB->getmenual($qry,$params,'rangelane');

		if($isall == 1)
		{
			$suballdata=array(					
				'id'=>'%',
				'name'=>'All',
			);
			array_unshift($rangelane, $suballdata);
		}

		$response['israngelanedata']=0;
		if($rangelane)
		{
			$response['israngelanedata']=1;
			$response['rangelanedata']=$rangelane;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}	
	}
	
	
	

	
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  