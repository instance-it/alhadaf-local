<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\storeissueitem.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();
	$currdate=$IISMethods->getformatcurrdate();


	
	//List Store Order Issue History
	if($action == 'liststoreorderissuehistory')
	{
		$storeid=$IISMethods->sanitize($_POST['storeid']);   
		$liststoreorderissuehistory=new liststoreorderissuehistory();

		$fltmemberid=$IISMethods->sanitize($_POST['fltmemberid']);  
		$fltfromdate=$IISMethods->sanitize($_POST['fltfromdate']);  
		$flttodate=$IISMethods->sanitize($_POST['flttodate']);  
		
		$qry = "select distinct so.id,so.orderno,so.uid,so.orderdate,pm.personname as membername,pm.contact as membercontact,so.timestamp 
			from tblserviceorder so 
			inner join tblserviceorderdetail sod on sod.orderid=so.id 
			inner join tblpersonmaster pm on pm.id=so.uid 
			inner join tblitemstore si on si.itemid=sod.itemid 
			where so.status=1 and so.iscancel=0 and sod.iscancel=0 and si.storeid=:storeid ";
		$parms = array(
			':storeid'=>$storeid,
		);
		if($fltmemberid != '%')
		{
			$qry.=" and so.uid LIKE :fltmemberid ";
			$parms[':fltmemberid']=$fltmemberid;
		}
		if($fltfromdate && $flttodate)
		{
			$qry.=" and CONVERT(date,so.orderdate,103) between  CONVERT(date,:fromdate,103) and  CONVERT(date,:todate,103) ";
			$parms[':fromdate']=$fltfromdate; 
			$parms[':todate']=$flttodate; 
		}
		$qry.=" ORDER BY so.timestamp DESC offset $start rows fetch next $per_page rows only";
		$liststoreorderissuehistory=$DB->getmenual($qry,$parms,'liststoreorderissuehistory');
		
		$response['isorderissuedata']=0;
		if($liststoreorderissuehistory)
		{	
			
			$response['isorderissuedata']=1;
			$response['orderissuedata']=$liststoreorderissuehistory;
			
			$response['nextpage']=$nextpage;

			$common_listdata=$result_ary;

			
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Store Order Item Issue History
	else if($action == 'liststoreorderissuedetail')
	{
		$storeid=$IISMethods->sanitize($_POST['storeid']);   
		$orderid=$IISMethods->sanitize($_POST['orderid']); 
		$liststoreorderissuehistory=new liststoreorderissuehistory();
		
		
		$qry = "select distinct so.id,so.orderno,so.uid,so.orderdate,pm.personname as membername,pm.contact as membercontact,so.timestamp 
			from tblserviceorder so 
			inner join tblserviceorderdetail sod on sod.orderid=so.id 
			inner join tblpersonmaster pm on pm.id=so.uid 
			inner join tblitemstore si on si.itemid=sod.itemid 
			where so.status=1 and so.iscancel=0 and sod.iscancel=0 and so.id=:orderid  ";
		$parms = array(
			':orderid'=>$orderid,
		);
		$result_ary=$DB->getmenual($qry,$parms,'liststoreorderissuehistory');
		
		$response['isorderissuedata']=0;
		if(sizeof($result_ary) > 0)
		{	
			$response['isorderissuedata']=1;

			for($i=0;$i<sizeof($result_ary);$i++)
			{
				/*************** Start For Order Item Details ********************/
				
				$issueorderdetailinfo=new issueorderdetailinfo();

				$qryod="select distinct sod.id,sod.orderid,sod.oidid,isnull(sod.type,0) as type,isnull(sod.typename,'') as typename,sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname,sod.qty,sod.issued_qty as issued_qty,sod.remain_qty as remain_qty,
					sod.taxtype,sod.taxtypename,sod.sgst,sod.cgst,sod.igst,sod.price,sod.discountper,sod.discountamt,sod.taxable,sod.sgsttaxamt,sod.cgsttaxamt,sod.igsttaxamt,sod.finalprice,
					case when (isnull(sod.remain_qty,0)=0) then 'Issued' when (isnull(sod.qty,0)<>isnull(sod.remain_qty,0)) then 'Partial Issued' else 'Pending' end as itemstatus,
					case when (isnull(sod.remain_qty,0)=0) then '#009688' when (isnull(sod.qty,0)<>isnull(sod.remain_qty,0)) then '#5bc0de' else '#e2a03f' end as itemstatuscolor 
					from tblserviceorderdetail sod 
					inner join tblitemstore si on si.itemid=sod.itemid 
					where sod.iscancel=0 and si.storeid=:storeid and sod.orderid=:orderid ";
				$odparams = array(
					':storeid'=>$storeid,
					':orderid'=>$result_ary[$i]->getId(), 
				);
				$issueorderdetailinfo=$DB->getmenual($qryod,$odparams,'issueorderdetailinfo');

				$result_ary[$i]->setIsIssueOrderDetail('0');
				if(sizeof($issueorderdetailinfo)>0)
				{
					$result_ary[$i]->setIsIssueOrderDetail('1');

					$result_ary[$i]->setIssueorderdetailinfo($issueorderdetailinfo);
				}
				
				/*************** End For Order Item Details ********************/

			}
			
			
			$response['orderissuedata']=json_decode(json_encode($result_ary));
			
			
			$status=1;
			$message=$errmsg['success'];
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

  