<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\gallery.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	 
	//list gallery category
	if($action == 'listgallerycategory')
	{
		$gallerycategorydata=new gallerycategory();
		
		$qrycat="select gcm.id,gcm.category 
			from tblgallerycategorymaster gcm 
			inner join tblgallerymaster gm on gm.categoryid = gcm.id 
			where gm.isactive = 1 group by gcm.id,gcm.category,gcm.displayorder 
			order by (case when (gcm.displayorder>0) then gcm.displayorder else 99999 end)";
		$catparms = array();
		$gallerycategorydata=$DB->getmenual($qrycat,$catparms,'gallerycategory');
		
		$response['isgallerycatdata']=0;
		if($gallerycategorydata)
		{

			$response['isgallerycatdata']=1;
			$response['gallerycatdata']=$gallerycategorydata;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
		
	}
	//list gallery data
	else if($action == 'listgallerydata')
	{	
		$showonhome=$IISMethods->sanitize($_POST['showonhome']);   //From Home Page
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);

		$galleryimagedata=new galleryimage();

		$qry="select gm.id,gm.categoryid,gm.title,case when (isnull(gm.img,'')='') then :defaultgalleryimgurl else concat(:defaultimgurl,gm.img) end as img 
			from tblgallerymaster gm 
			inner join tblgallerycategorymaster gcm on gcm.id = gm.categoryid  
			where gm.isactive = 1 ";
		$parms = array(
			':defaultgalleryimgurl'=>$config->getDefualtGalleryImageurl(),
			':defaultimgurl'=>$imgpath,
		);
		if($categoryid)
		{
			$qry.=" and gm.categoryid LIKE :categoryid";
			$parms[':categoryid']=$categoryid;
		}
		if($showonhome == 1)  //When From Home Page
		{
			$qry.=" and gm.showonhome=:showonhome ";
			$parms[':showonhome']=$showonhome;
		}
		$qry.=" order by (case when (gm.displayorder>0) then gm.displayorder else 99999 end)";
		$galleryimagedata=$DB->getmenual($qry,$parms,'galleryimage');

		$response['isgalleryimgdata']=0;
		if($galleryimagedata)
		{
			$response['isgalleryimgdata']=1;
			$response['galleryimgdata']=$galleryimagedata;
			
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

require_once dirname(__DIR__, 2).'\config\apifoot.php';

?>

  