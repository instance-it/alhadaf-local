<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\orderhistory.php';


if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();
	$currdate=$IISMethods->getformatcurrdate();

	//List Order History
	if($action == 'listorderhistory')
	{
		
		$listorderhistory=new listorderhistory();
		
		$qry = "select distinct o.id,o.timestamp,o.transactionid,o.orderno,o.uid,o.totalamount,o.couponapply,o.couponid,o.couponcode,o.couponamount,
			o.totaltaxableamt,o.totaltax,o.totalpaid,o.iscancel,case when (o.iscancel=1) then 'Cancelled' else '' end as strcancel,
			convert(varchar, o.timestamp,100) AS ofulldate 
			from tblorder o 
			inner join tblorderdetail od on od.orderid=o.id 
			where o.status=1 and o.uid=:uid ";
		$parms = array(
			':uid'=>$uid,
		);
		$qry.=" ORDER BY o.timestamp DESC offset $start rows fetch next $per_page rows only";

		$result_ary=$DB->getmenual($qry,$parms,'listorderhistory');
		
		$response['isorderhistorydata']=0;
		if(sizeof($result_ary) > 0)
		{	
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$orderdetailinfo=new orderdetailinfo();

				$qryod="select od.id,od.orderid,od.type,od.itemid,REPLACE(od.itemname,'&amp;','&') as itemname,od.durationday,od.durationname,isnull(od.strvalidityduration,'') as strvalidityduration,od.description,od.courseduration,od.noofstudent,
					od.startdate,od.expirydate,od.n_expirydate,od.taxtype,od.taxtypename,od.sgst,od.cgst,od.igst,od.price,od.couponamount,od.taxable,od.sgsttaxamt,od.cgsttaxamt,od.igsttaxamt,od.finalprice,
					case when (od.type = 1) then 'Membership' when (od.type = 2) then 'Packages' when (od.type = 3) then 'Course' else '' end as typename,
					case when (o.iscancel=1) then '' when (convert(date,od.n_expirydate,103) >= :currdate1) then concat('Expires On ',od.n_expirydate) else concat('Expired On ',od.n_expirydate) end as strexpire,
					case when (o.iscancel=1) then '' when (convert(date,od.n_expirydate,103) >= :currdate2) then '#008140' else '#e63c2e' end as strexpirecolor 
					from tblorderdetail od 
					inner join tblorder o on od.orderid=o.id 
					where od.orderid=:orderid";
				$odparams = array(
					':orderid'=>$result_ary[$i]->getId(), 
					':currdate1'=>$currdate, 
					':currdate2'=>$currdate, 
				);
				$orderdetailinfo=$DB->getmenual($qryod,$odparams,'orderdetailinfo');

				if(sizeof($orderdetailinfo)>0)
				{
					$result_ary[$i]->setOrderDetailInfo($orderdetailinfo);
				}

			}
			
			$response['isorderhistorydata']=1;
			$response['orderhistorydata']=json_decode(json_encode($result_ary));
			
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

		
		if($nextpage!=1)
		{
			$response['isorderhistorydata']=1;
			$status=1;
			$message=$errmsg['success'];
		}
	}
	//Get Order History Details
	else if($action == 'listorderhistorydetail')
	{
		$orderid=$IISMethods->sanitize($_POST['orderid']);
		$orderdetid=$IISMethods->sanitize($_POST['orderdetid']);

		$isvalidate=0;
		
		/********************* Start For Attribute Details *********************/
		$attributedetail=new attributedetail();

		$qry = "select distinct tod.id,tod.webdisplayname as name,REPLACE(isnull(tod.attributename,''),'&amp;','&') as attributename,tod.displayorder,
			case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
			from tblorderitemdetail tod 
			left join tblitemiconmaster iim on iim.id=tod.iconid
			where tod.iswebsiteattribute=1 and tod.orderid=:orderid and tod.odid=:orderdetid order by tod.displayorder";
		$parms = array(
			':orderid'=>$orderid,
			':orderdetid'=>$orderdetid,
			':imageurl'=>$imgpath,
		);
		$attributedetail=$DB->getmenual($qry,$parms,'attributedetail');
		
		$response['isattributedetail']=0;
		if($attributedetail)
		{	
			$isvalidate=1;
			$response['isattributedetail']=1;
			$response['attributedetail']=$attributedetail;
			
			$status=1;
			$message=$errmsg['success'];
		}
		/********************* End For Attribute Details *********************/


		/********************* Start For Course Benefit Details *********************/
		$coursebenefit=new coursebenefit();

		$qry = "select distinct tod.id,REPLACE(tod.webdisplayname,'&amp;','&') as name,tod.durationname,tod.displayorder,
			case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
			from tblorderitemdetail tod 
			left join tblitemiconmaster iim on iim.id=tod.iconid
			where tod.iscourse=1 and tod.orderid=:orderid and tod.odid=:orderdetid order by tod.displayorder";
		$parms = array(
			':orderid'=>$orderid,
			':orderdetid'=>$orderdetid,
			':imageurl'=>$imgpath,
		);
		$coursebenefit=$DB->getmenual($qry,$parms,'coursebenefit');
		
		$response['iscoursebenefit']=0;
		if($coursebenefit)
		{	
			$isvalidate=1;

			$response['iscoursebenefit']=1;
			$response['coursebenefit']=$coursebenefit;
		}
		/********************* End For Course Benefit Details *********************/


		/********************* Start For Item Details *********************/
		$orderitemdetailinfo=new orderitemdetailinfo();

		$qry = "select distinct oid.id as oidid,oid.odid,oid.orderid,oid.catid as categoryid,oid.category,oid.subcatid as subcategoryid,REPLACE(oid.subcategory,'&amp;','&') as subcategory,oid.itemid,REPLACE(oid.itemname,'&amp;','&') as itemname,
			oid.qty,oid.usedqty,oid.remainqty,oid.durationid,oid.durationname,oid.durationdays,oid.discount,oid.price,
			tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,oid.type,oid.typestr as typename,o.timestamp 
			from tblorder o
			inner join tblorderdetail od on od.orderid = o.id 
			inner join tblorderitemdetail oid on oid.odid=od.id 
			inner join tbltaxtype tt on tt.id=oid.gsttypeid
			inner join tbltax tx on tx.id=oid.gstid 
			inner join tblitemstore si on si.itemid=oid.itemid 
			where o.status=1 and o.iscancel = 0 and isnull(oid.iswebsiteattribute,0)=0 and oid.orderid=:orderid and oid.odid=:orderdetid
			order by oid.type";
		$parms = array(
			':orderid'=>$orderid,
			':orderdetid'=>$orderdetid,
		);
		$orderitemdetailinfo=$DB->getmenual($qry,$parms,'orderitemdetailinfo');
		
		$response['isitemdetail']=0;
		if($orderitemdetailinfo)
		{	
			$isvalidate=1;

			$response['isitemdetail']=1;
			$response['itemdetail']=$orderitemdetailinfo;
		}
		/********************* End For Item Details *********************/
		

		if($isvalidate == 1)
		{
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}

	}
	else if ($action == 'listorderdetail')
	{
		$listorderdetail=new listorderdetail();


		$type=$IISMethods->sanitize($_POST['type']);
		if($type == 1)
		{
			$catmembershipid = $config->getDefaultCatMembershipId();
		}
		else if($type == 2)
		{
			$catmembershipid = $config->getDefaultCatPackageId();
		}
		else if($type == 3)
		{
			$catmembershipid = $config->getDefaultCatCourseId();
		}

		$qry = "select od.id,od.orderid,o.orderno,convert(varchar, o.timestamp,100) AS ofulldate,od.type,od.itemid,od.itemname,od.durationday,od.durationname,isnull(od.strvalidityduration,'') as strvalidityduration,od.description,od.courseduration,od.noofstudent,
				od.startdate,od.expirydate,od.n_expirydate,od.taxtype,od.taxtypename,od.sgst,od.cgst,od.igst,od.price,od.couponamount,od.taxable,od.sgsttaxamt,od.cgsttaxamt,od.igsttaxamt,od.finalprice,
				case when (od.type = 1) then 'Membership' when (od.type = 2) then 'Packages' when (od.type = 3) then 'Course' else '' end as typename,
				case when (o.iscancel=1) then '' when (convert(date,od.n_expirydate,103) >= :currdate1) then concat('Expires On ',od.n_expirydate) else concat('Expired On ',od.n_expirydate) end as strexpire,
				case when (o.iscancel=1) then 'Cancelled' when (convert(date,od.n_expirydate,103) >= :currdate3) then 'Running' else 'Expired' end as strexpirestatus,
				case when (o.iscancel=1) then '#e63c2e' when (convert(date,od.n_expirydate,103) >= :currdate2) then '#008140' else '#e63c2e' end as strexpirecolor
				from tblorder o 
				inner join tblorderdetail od on od.orderid=o.id
				inner join tblitemmaster im on im.id = od.itemid 
				where o.status=1 and o.uid=:uid and im.categoryid = :catmembershipid";
		$parms = array(
			':uid'=>$uid,
			':catmembershipid'=>$catmembershipid,
			':currdate1'=>$currdate, 
			':currdate2'=>$currdate, 
			':currdate3'=>$currdate, 
		);
		$qry.=" ORDER BY convert(date,od.n_expirydate,103) DESC offset $start rows fetch next $per_page rows only";

	
		$result_ary=$DB->getmenual($qry,$parms,'listorderdetail');

		$response['isorderdetail']=0;
		if($result_ary)
		{	
			
			$response['isorderdetail']=1;
			// $response['orderdetail']=$result_ary;
			$response['orderdetail']=json_decode(json_encode($result_ary));
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


		if($nextpage!=1)
		{
			$response['isorderdetail']=1;
			$status=1;
			$message=$errmsg['success'];
		}

	}
	
	

	
}


require_once dirname(__DIR__, 2).'\config\apifoot.php';  

?>

  