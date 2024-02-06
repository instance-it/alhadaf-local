<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\aboutus.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	 
	//list Courses
	if($action == 'listaboutusdata')
	{
		$abtdata=new listaboutus();
		$qry = "SELECT wc.id,wc.title,isnull(wc.title2,'') as title2,wc.description,wc.contenttypeid,
				CASE WHEN (isnull(wc.img,'')='') THEN '' ELSE  concat(:imageurl,img) END AS img 
				FROM tblwebcontent wc WHERE contenttypeid = :contenttypeid OR contenttypeid = :missionid OR contenttypeid = :vissionid OR contenttypeid = :valueid order by wc.displayorder";
		$parms = array(
			':imageurl'=>$imageurl,
			':contenttypeid'=>$config->getAboutUsId(),
			':missionid'=>$config->getMissionId(),
			':vissionid'=>$config->getVissionId(),
			':valueid'=>$config->getValuesId(),
		);
		$result_ary=$DB->getmenual($qry,$parms,'listaboutus');
		
		if($result_ary)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$abtdatadetail = new aboutusdetail();
				$subqry = "SELECT id, title, displayorder,descr,type, case when (isnull(img,'')='') then '' else concat(:imageurl,img) end as iconimg , abtcount 
							from tblwebcontentdetail WHERE contenttypeid = :contenttypeid ORDER BY displayorder";
				$subparms = array(
					':imageurl'=>$imageurl,
					':contenttypeid'=>$result_ary[$i]->getId(),
				);
				$abtdatadetail=$DB->getmenual($subqry,$subparms,'aboutusdetail');

				if(sizeof($abtdatadetail)>0)
				{
					$result_ary[$i]->setAboutusDetail($abtdatadetail);
				}
			}
			$response['abtdata']= json_decode(json_encode($result_ary));

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

  