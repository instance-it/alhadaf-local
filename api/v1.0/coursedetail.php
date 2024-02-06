<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\coursedetail.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	 
	//list Course Details
	if($action == 'listcoursedetails')
	{
		$itemunqid=$IISMethods->sanitize($_POST['itemunqid']);
		$coursedata=new coursedetail();

		$qry="select im.id,im.itemname,im.price,isnull(im.itemno,'') as itemno,isnull(im.duration,'') as duration,'Hours' as strduration,
			isnull(im.noofstudent,'') as noofstudent,'Students' as strnoofstudent,tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,
			REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(im.itemname,' - ','-'),' ','-'),'&','|'),',','-'),'+','-plus'),'(',''),')','') as n_itemname,
			isnull(im.descr,'') as descr,:defaultcourseimgurl1 as defaultcourseimg,
			ISNULL((select top 1 CONCAT(:baseimgurl,image) from tblitemimage where itemid = im.id order by displayorder),:defaultcourseimgurl) as itemimg,
			dm.strday as strvalidityduration
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid
			where im.isactive=1 and im.showonweb=1 and im.itemno=:itemunqid";
		$parms = array(
			':itemunqid'=>$itemunqid,
			':baseimgurl'=>$imageurl,
			':defaultcourseimgurl'=>$config->getDefualtCourseImageurl(),
			':defaultcourseimgurl1'=>$config->getDefualtCourseImageurl(),
        );
		$result_ary=$DB->getmenual($qry,$parms,'coursedetail');
		
		$response['iscoursedata']=0;
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$ins_itemid=$result_ary[$i]->getId();

				/******************* Start For Course Image Data *********************/
				$courseimages=new courseimage();
				
				$qryimg="select ii.id,CONCAT(:baseimgurl,ii.image) as courseimg from tblitemimage ii WHERE ii.itemid=:itemid order by ii.displayorder";
				$imgparams = array(
					':itemid'=>$result_ary[$i]->getId(), 
					':baseimgurl'=>$imageurl,
				);
				$courseimages=$DB->getmenual($qryimg,$imgparams,'courseimage');

				$result_ary[$i]->setIsCourseImgData('0');
				if(sizeof($courseimages)>0)
				{
					$result_ary[$i]->setIsCourseImgData('1');

					$result_ary[$i]->setCourseImage($courseimages);
				}
				/******************* End For Course Image Data *********************/



				/******************* Start For Course Benefit Data *********************/
				$coursebenefit=new coursebenefit();
				
				$qrycb="select distinct tid.id,tid.rowwebdisplayname as name,tid.rowdurationname,tid.rowdisplayorder,
					case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
					from tblitemdetails tid 
					left join tblitemiconmaster iim on iim.id=tid.rowiconid
					where tid.iscourse=1 and tid.itemid=:itemid order by tid.rowdisplayorder";
				$cbparams = array(
					':imageurl'=>$imageurl,
					':itemid'=>$result_ary[$i]->getId(), 
				);
				$coursebenefit=$DB->getmenual($qrycb,$cbparams,'coursebenefit');

				$result_ary[$i]->setIsCourseBenefit('0');
				if(sizeof($coursebenefit)>0)
				{
					$result_ary[$i]->setIsCourseBenefit('1');
					$result_ary[$i]->setCourseBenefit($coursebenefit);
				}
				/******************* End For Course Benefit Data *********************/



				/******************* Start For Course Display Data *********************/
				$coursedisplaydata=new coursedisplaydata();
				
				$qrycd="select distinct tid.id,tid.rowwebdisplayname as name,tid.rowdisplayorder,
					case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
					from tblitemdetails tid 
					left join tblitemiconmaster iim on iim.id=tid.rowiconid
					where tid.iswebsiteattribute=1 and tid.itemid=:itemid order by tid.rowdisplayorder";
				$cdparams = array(
					':imageurl'=>$imageurl,
					':itemid'=>$result_ary[$i]->getId(), 
				);
				$coursedisplaydata=$DB->getmenual($qrycd,$cdparams,'coursedisplaydata');

				$result_ary[$i]->setIsCourseDisplayData('0');
				if(sizeof($coursedisplaydata)>0)
				{
					$result_ary[$i]->setIsCourseDisplayData('1');
					$result_ary[$i]->setCourseDisplayData($coursedisplaydata);
				}
				/******************* End For Course Display Data *********************/
				

				
			}

			$response['coursedata']= json_decode(json_encode($result_ary));
			$response['iscoursedata']=1;
			
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

  