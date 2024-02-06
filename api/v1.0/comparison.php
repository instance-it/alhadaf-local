<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\membership.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	 
	//list Membership
	if($action == 'listcompareplandetail')
	{
		$showonhome=$IISMethods->sanitize($_POST['showonhome']);   //From Home Page
		$id=$IISMethods->sanitize($_POST['id']);
		$membershipdata=new membership();
		
		$qry="select im.id,im.itemname,im.price
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid
			where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid ";
		$parms = array(
			':catmembershipid'=>$config->getDefaultCatMembershipId(),
		);
		if($showonhome == 1)  //When From Home Page
		{
			$qry.=" and im.showonhome=:showonhome ";
			$parms[':showonhome']=$showonhome;
		}
		if($id)
		{
			$qry.=" and im.id=:itemid ";
			$parms[':itemid']=$id;
		}
		$qry.=" order by im.price,im.timestamp";
		$result_ary=$DB->getmenual($qry,$parms,'membership');

		$response['ismembershipdata']=0;
		
		if(sizeof($result_ary)>0)
		{
			$response['ismembershipdata']=1;

			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$attributedetail=new attributedetail();
				
				$qrymd="select distinct tid.id,tid.rowwebdisplayname as name,isnull(tid.rowattributename,'') as attributename,tid.rowdisplayorder,
					case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
					from tblitemdetails tid 
					left join tblitemiconmaster iim on iim.id=tid.rowiconid
					where tid.iswebsiteattribute=1 and tid.itemid=:itemid order by tid.rowdisplayorder";
				$mdparams = array(
					':imageurl'=>$imageurl,
					':itemid'=>$result_ary[$i]->getId(), 
				);
				$attributedetail=$DB->getmenual($qrymd,$mdparams,'attributedetail');

				if(sizeof($attributedetail)>0)
				{
					$result_ary[$i]->setAttributeDetail($attributedetail);
				}
			}	

			$response['membershipdata']= json_decode(json_encode($result_ary));

			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nomshipfound'];
		}
	}
	
	

	
}

require_once dirname(__DIR__, 2).'\config\apifoot.php';

?>

  