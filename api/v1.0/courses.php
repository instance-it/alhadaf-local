<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\courses.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	 
	//list Courses
	if($action == 'listcourses')
	{
		$showonhome=$IISMethods->sanitize($_POST['showonhome']);   //From Home Page
		$id=$IISMethods->sanitize($_POST['id']);
		$coursedata=new listcourse();
		
		$qry="select im.id,im.itemname,im.price,isnull(im.itemno,'') as itemno,isnull(im.duration,'') as duration,'Hours' as strduration,isnull(im.noofstudent,'') as noofstudent,'Students' as strnoofstudent,tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,
			REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(im.itemname,' - ','-'),' ','-'),'&','|'),',','-'),'+','-plus'),'(',''),')','') as n_itemname,isnull(im.descr,'') as descr,:defaultcourseimgurl1 as defaultcourseimg,
			ISNULL((select top 1 CONCAT(:baseimgurl,image) from tblitemimage where itemid = im.id order by displayorder),:defaultcourseimgurl) as itemimg,'0' as iscourseimgdata,
			dm.strday as strvalidityduration
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid
			inner join tbldurationmaster dm on dm.id=im.durationid 
			where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid ";
		$parms = array(
			':baseimgurl'=>$imageurl,
			':defaultcourseimgurl'=>$config->getDefualtCourseImageurl(),
			':catmembershipid'=>$config->getDefaultCatCourseId(),
			':defaultcourseimgurl1'=>$config->getDefualtCourseImageurl(),
		);
		if($showonhome == 1)  //When From Home Page
		{
			$qry.=" and im.showonhome=:showonhome ";
			$parms[':showonhome']=$showonhome;
		}
		$qry.=" order by im.price,im.timestamp ";
		$coursedata=$DB->getmenual($qry,$parms,'listcourse');

		$response['iscoursedata']=0;
		if($coursedata)
		{
			$response['iscoursedata']=1;
			$response['coursedata']= $coursedata;

			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nocoursefound'];
		}
	}
	
	

	
}

require_once dirname(__DIR__, 2).'\config\apifoot.php';

?>

  