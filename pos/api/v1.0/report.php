<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\report.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();

	//List Report Category
	if($action == 'listreportcategory')
	{	
		$listreportcategory=new listreportcategory();

		$qry="select c.id,c.category as name 
			from tblcategory c 
			where c.isactive=1 and c.id in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid) 
			order by (case when (c.displayorder>0) then c.displayorder else 99999 end) ";
		$parms = array(
			':defaultcatmembershipid'=>$config->getDefaultCatMembershipId(),
			':defaultcatcourseid'=>$config->getDefaultCatCourseId(),
			':defaultcatpackageid'=>$config->getDefaultCatPackageId(),
		);
		$listreportcategory=$DB->getmenual($qry,$parms,'listreportcategory');
		

		$response['iscategorydata']=0;
		if($listreportcategory)
		{
			$response['iscategorydata']=1;
			$response['categorydata']=$listreportcategory;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}


		$response['isalloption']=1;
	}
	//List Report Sub Category
	else if($action == 'listreportsubcategory')
	{	
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$listreportsubcategory=new listreportsubcategory();

		$qry="select sc.id,sc.subcategory as name 
			from tblsubcategory sc 
			where sc.isactive=1 and CONVERT(VARCHAR(255), sc.categoryid) like :categoryid 
			order by (case when (sc.displayorder>0) then sc.displayorder else 99999 end)";
		$parms = array(
			':categoryid'=>$categoryid,
		);
		$listreportsubcategory=$DB->getmenual($qry,$parms,'listreportsubcategory');
		

		$response['issubcategorydata']=0;
		if($listreportsubcategory)
		{
			$response['issubcategorydata']=1;
			$response['subcategorydata']=$listreportsubcategory;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}


		$response['isalloption']=1;
	}
	//List Report Item
	else if($action == 'listreportitem')
	{	
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$subcategoryid=$IISMethods->sanitize($_POST['subcategoryid']);

		$listreportitem=new listreportitem();

		$qry="SELECT im.id,im.itemname as name
			from tblitemmaster im 
			where CONVERT(VARCHAR(255), im.categoryid) like :categoryid  and CONVERT(VARCHAR(255), im.subcategoryid) like :subcategoryid";
		$parms = array(
			':categoryid'=>$categoryid,
			':subcategoryid'=>$subcategoryid,
		);	
		$listreportitem=$DB->getmenual($qry,$parms,'listreportitem');
		

		$response['isitemdata']=0;
		if($listreportitem)
		{
			$response['isitemdata']=1;
			$response['itemdata']=$listreportitem;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}


		$response['isalloption']=1;
	}
	//List Report Member
	else if($action == 'listreportmember')
	{	
		$listreportmember=new listreportmember();

		$qry="SELECT distinct pm.id,concat(pm.personname,' (',pm.contact,')') as name,pm.personname
			from tblpersonmaster pm 
			inner join tblpersonutype pu on pu.pid = pm.id
			where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND pu.utypeid=:memberutypeid order by pm.personname";
		$parms = array(
			':memberutypeid'=>$config->getMemberutype(),
			':adminuid'=>$config->getAdminUserId(),
		);
		$listreportmember=$DB->getmenual($qry,$parms,'listreportmember');
		

		$response['ismemberdata']=0;
		if($listreportmember)
		{
			$response['ismemberdata']=1;
			$response['memberdata']=$listreportmember;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}


		$response['isalloption']=1;
	}
	//List Report Payment Type
	else if($action == 'listreportpaymenttype')
	{	
		$response['ispaymenttypedata']=1;

		$i=0;
		$response['paymenttypedata'][$i]['id']='1';
		$response['paymenttypedata'][$i]['name']='Cash';
		$i++;

		$response['paymenttypedata'][$i]['id']='2';
		$response['paymenttypedata'][$i]['name']='Online Payment';
		$i++;
		
		$status = 1;
		$message = $errmsg['success'];


		$response['isalloption']=1;
	}
	//List Sale Report Data
	else if($action == 'listsalereportdata')
	{	
		$memberid=$IISMethods->sanitize($_POST['memberid']);
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$subcategoryid=$IISMethods->sanitize($_POST['subcategoryid']);
		$itemid=$IISMethods->sanitize($_POST['itemid']);
		$paymenttype=$IISMethods->sanitize($_POST['paymenttype']);
		$fromdate=$IISMethods->sanitize($_POST['fromdate']);
		$todate=$IISMethods->sanitize($_POST['todate']);

		$withitemdetail=$IISMethods->sanitize($_POST['withitemdetail']);
		$withfulldetail=$IISMethods->sanitize($_POST['withfulldetail']);


		$listsalereportdata=new listsalereportdata();

		$qryord="select distinct o.id,o.transactionid,o.orderno,pm.personname as membername,pm.contact as membercontact,
			convert(varchar, o.timestamp,100) AS ofulldate,o.totalpaid as totalamount,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,
			case when (o.iscancel = 1) then 'Cancelled' else 'Confirmed' end as ordstatusname,o.timestamp,
			case when (isnull(o.paymenttype,0) = 1) then 'Cash' when (isnull(o.paymenttype,0) = 2) then 'Online Payment' else '' end as paymenttypename
			from tblorder o 
			inner join tblpersonmaster pm on pm.id=o.uid 
			inner join tblpersonmaster pm1 on pm1.id=o.entry_uid 
			inner join tblorderdetail od on od.orderid=o.id 
			inner join tblitemmaster im on im.id=od.itemid
			where convert(varchar(50),o.uid) like :memberid and convert(varchar(50),im.categoryid) like :categoryid and convert(varchar(50),im.subcategoryid) like :subcategoryid and convert(varchar(50),im.id) like :itemid and isnull(o.paymenttype,0) like :paymenttype ";
		$ordparms = array(
			':memberid'=>$memberid,
			':categoryid'=>$categoryid,
			':subcategoryid'=>$subcategoryid,
			':itemid'=>$itemid,
			':paymenttype'=>$paymenttype,
		);
		if($fromdate && $todate)
		{
			//$qryord.=" AND CONVERT(date,o.timestamp,103) BETWEEN CONVERT(date,:fromdate,103) and CONVERT(date,:todate,103)";
			$qryord.=" AND o.timestamp BETWEEN :fromdate and :todate ";
			
			$ordparms[':fromdate']=$fromdate;	
			$ordparms[':todate']=$todate;	
		} 
		$qryord.=" order by o.timestamp desc";
		//echo $qryord;
		//print_r($ordparms);
		$result_ary=$DB->getmenual($qryord,$ordparms,'listsalereportdata');
		

		$response['issalereportdata']='0';
		if(sizeof($result_ary) > 0)
		{
			$response['issalereportdata']='1';

			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$result_ary[$i]->setIsItemDetail('0');
				
				if($withitemdetail == 1)
				{
					$itemdetail=new itemdetail();

					$qryod="select od.id,od.type,od.itemname,od.durationname,od.price,od.taxable,od.igsttaxamt,od.finalprice,od.igst,isnull(od.couponamount,0) as couponamount,od.expirydate,od.n_expirydate,od.strvalidityduration,
						case when (od.type = 1) then 'Membership' when (od.type = 2) then 'Packages' when (od.type = 3) then 'Course' else '' end as typename
						from tblorderdetail od 
						inner join tblitemmaster im on im.id=od.itemid 
						where od.orderid = :orderid and convert(varchar(50),im.categoryid) like :categoryid and convert(varchar(50),im.subcategoryid) like :subcategoryid and convert(varchar(50),im.id) like :itemid ";
					$odparams = array(
						':orderid'=>$result_ary[$i]->getId(), 
						':categoryid'=>$categoryid,
						':subcategoryid'=>$subcategoryid,
						':itemid'=>$itemid,
					);
					$itemdetail=$DB->getmenual($qryod,$odparams,'itemdetail');

					$result_ary[$i]->setIsItemDetail('0');
					if(sizeof($itemdetail)>0)
					{
						$result_ary[$i]->setIsItemDetail('1');


						for($j=0;$j<sizeof($itemdetail);$j++)
						{
							$itemdetail[$j]->setIsItemFullDetail('0');
							$itemdetail[$j]->setIsItemWebsiteDetail('0');
							$itemdetail[$j]->setIsCourseBenefitDetail('0');
							
							if($withfulldetail == 1)
							{
							
								/************************** Start For Composite Item Details *************************/
								$itemfulldetail=new itemfulldetail();

								$qryodd="select distinct oid.id,oid.catid,oid.category,oid.subcatid,oid.subcategory,oid.itemid,oid.itemname,oid.qty,oid.usedqty,oid.remainqty,
									oid.durationid,oid.durationname,oid.durationdays,oid.discount,oid.price,
									tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,oid.type,oid.typestr as typename
									from tblorderitemdetail oid 
									inner join tbltaxtype tt on tt.id=oid.gsttypeid
									inner join tbltax tx on tx.id=oid.gstid 
									where isnull(oid.iswebsiteattribute,0)=0 and isnull(oid.iscourse,0)=0 and oid.odid = :orderdetid ";
								$oddparams = array(
									':orderdetid'=>$itemdetail[$j]->getId(),
								);
								$itemfulldetail=$DB->getmenual($qryodd,$oddparams,'itemfulldetail');

								$itemdetail[$j]->setIsItemFullDetail('0');
								if(sizeof($itemfulldetail)>0)
								{
									$itemdetail[$j]->setIsItemFullDetail('1');

									$itemdetail[$j]->setItemFullDetail($itemfulldetail);
								}
								/************************** Start For Composite Item Details *************************/




								/************************** Start For Website Display Items *************************/
								$itemwebsitedetail=new itemwebsitedetail();

								$qryodd = "select distinct tod.id,tod.webdisplayname as name,isnull(tod.attributename,'') as attributename,tod.displayorder
									from tblorderitemdetail tod 
									left join tblitemiconmaster iim on iim.id=tod.iconid
									where tod.iswebsiteattribute=1 and tod.odid=:orderdetid order by tod.displayorder";
								$oddparams = array(
									':orderdetid'=>$itemdetail[$j]->getId(),
								);
								$itemwebsitedetail=$DB->getmenual($qryodd,$oddparams,'itemwebsitedetail');

								$itemdetail[$j]->setIsItemWebsiteDetail('0');
								if(sizeof($itemwebsitedetail)>0)
								{
									$itemdetail[$j]->setIsItemWebsiteDetail('1');

									$itemdetail[$j]->setItemWebsiteDetail($itemwebsitedetail);
								}
								/************************** Start For Website Display Items *************************/




								/************************** Start For Course Benefit *************************/
								$coursebenefitdetail=new coursebenefitdetail();

								$qryodd = "select distinct tod.id,tod.webdisplayname as name,tod.durationname,tod.displayorder
									from tblorderitemdetail tod 
									left join tblitemiconmaster iim on iim.id=tod.iconid
									where tod.iscourse=1 and tod.odid=:orderdetid order by tod.displayorder";
								$oddparams = array(
									':orderdetid'=>$itemdetail[$j]->getId(),
								);
								$coursebenefitdetail=$DB->getmenual($qryodd,$oddparams,'coursebenefitdetail');

								$itemdetail[$j]->setIsCourseBenefitDetail('0');
								if(sizeof($coursebenefitdetail)>0)
								{
									$itemdetail[$j]->setIsCourseBenefitDetail('1');

									$itemdetail[$j]->setCourseBenefitDetail($coursebenefitdetail);
								}
								/************************** Start For Course Benefit *************************/
							
							}
						}	

						$result_ary[$i]->setItemDetail($itemdetail);


					}
				}

			}	


			$response['salereportdata']=json_decode(json_encode($result_ary));
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	/****************************************** Start For Sale Summary Reports ************************************/
	//List Sale Person
	else if($action == 'listreportsaleperson')
	{	
		$listreportsaleperson=new listreportsaleperson();

		$qry="SELECT distinct pm.id,concat(pm.personname,' (',pm.contact,')') as name,pm.personname
			from tblpersonmaster pm 
			inner join tblpersonutype pu on pu.pid = pm.id
			where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND pu.utypeid<>:memberutypeid 
			order by pm.personname";
		$parms = array(
			':memberutypeid'=>$config->getMemberutype(),
			':adminuid'=>$config->getAdminUserId(),
		);
		$listreportsaleperson=$DB->getmenual($qry,$parms,'listreportsaleperson');
		

		$response['issalepersondata']=0;
		if($listreportsaleperson)
		{
			$response['issalepersondata']=1;
			$response['salepersondata']=$listreportsaleperson;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}


		$response['isalloption']=1;
	}
	//List Store
	else if($action == 'liststore')
	{	
		$liststore=new liststore();

		$qry="SELECT id,storename as name from tblstoremaster WHERE ISNULL(isactive,0) = 1 AND ISNULL(isdelete,0) = 0";	
		$parms = array();
		$liststore=$DB->getmenual($qry,$parms,'liststore');
		

		$response['isstoredata']=0;
		if($liststore)
		{
			$response['isstoredata']=1;
			$response['storedata']=$liststore;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}


		$response['isalloption']=1;
	}
	//List Item Sale Report Item
	else if($action == 'listreportitems')
	{	
		$isnotmshippkgcourse=$IISMethods->sanitize($_POST['isnotmshippkgcourse']);   
		$listreportitems=new listreportitems();

		$qry="SELECT im.id,im.itemname as name 
		from tblitemmaster im where 1=1 ";
		$parms = array(
			
		);	
		if($isnotmshippkgcourse == 1)  //Items (Remain Membership, Package, Course)
		{
			$qry.=" and im.categoryid not in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid)  ";
			$parms[':defaultcatmembershipid']=$config->getDefaultCatMembershipId();
			$parms[':defaultcatcourseid']=$config->getDefaultCatCourseId();
			$parms[':defaultcatpackageid']=$config->getDefaultCatPackageId();
		}
		else  //Items (Membership, Package, Course)
		{
			$qry.=" and im.categoryid in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid)  ";
			$parms[':defaultcatmembershipid']=$config->getDefaultCatMembershipId();
			$parms[':defaultcatcourseid']=$config->getDefaultCatCourseId();
			$parms[':defaultcatpackageid']=$config->getDefaultCatPackageId();
		}
		$listreportitems=$DB->getmenual($qry,$parms,'listreportitems');
		

		$response['isreportitemsdata']=0;
		if($listreportitems)
		{
			$response['isreportitemsdata']=1;
			$response['reportitemsdata']=$listreportitems;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}


		$response['isalloption']=1;
	}
	//List Item Sale Summary Report Data
	else if($action == 'listitemsalesummaryreportdata')
	{	
		$storeid=$IISMethods->sanitize($_POST['storeid']);
		$salepersonid=$IISMethods->sanitize($_POST['salepersonid']);
		$itemid=$IISMethods->sanitize($_POST['itemid']);

		$withfreeitem=$IISMethods->sanitize($_POST['withfreeitem']);

		$fromdate=$IISMethods->sanitize($_POST['fromdate']);
		$todate=$IISMethods->sanitize($_POST['todate']);


		$listitemsalesummaryreportdata=new listitemsalesummaryreportdata();



		$itemparms = array(
			':defaultcatmembershipid'=>$config->getDefaultCatMembershipId(),
			':defaultcatcourseid'=>$config->getDefaultCatCourseId(),
			':defaultcatpackageid'=>$config->getDefaultCatPackageId(),
			':storeid'=>$storeid,
			':itemid'=>$itemid,
		);


		$salepersoncond1='';
		$salepersoncond2='';
		$salepersoncond3='';
		if($salepersonid != '%')  //When Not All Sale Person
		{
			$salepersoncond1=" and so.entry_uid like :salepersonid1 ";
			$salepersoncond2=" and so.entry_uid like :salepersonid2 ";
			$salepersoncond3=" and so.entry_uid like :salepersonid3 ";
			$itemparms[':salepersonid1']=$salepersonid;
			$itemparms[':salepersonid2']=$salepersonid;
			$itemparms[':salepersonid3']=$salepersonid;
		}


		$datecond1='';
		$datecond2='';
		$datecond3='';
		if($fromdate && $todate)   //When Date Filter
		{
			// $datecond1=" AND CONVERT(date,so.timestamp,103) BETWEEN CONVERT(date,:fromdate1,103) and CONVERT(date,:todate1,103) ";
			// $datecond2=" AND CONVERT(date,so.timestamp,103) BETWEEN CONVERT(date,:fromdate2,103) and CONVERT(date,:todate2,103) ";
			// $datecond3=" AND CONVERT(date,so.timestamp,103) BETWEEN CONVERT(date,:fromdate3,103) and CONVERT(date,:todate3,103) ";

			$datecond1=" AND so.timestamp BETWEEN :fromdate1 and :todate1 ";
			$datecond2=" AND so.timestamp BETWEEN :fromdate2 and :todate2 ";
			$datecond3=" AND so.timestamp BETWEEN :fromdate3 and :todate3 ";
		   
			$itemparms[':fromdate1']=$fromdate;	
			$itemparms[':todate1']=$todate;	
			$itemparms[':fromdate2']=$fromdate;	
			$itemparms[':todate2']=$todate;	
			$itemparms[':fromdate3']=$fromdate;	
			$itemparms[':todate3']=$todate;	
		} 

		$withoutfreeitemcond='';
		if($withfreeitem == 0)  //When Without Free Item
		{
			$withoutfreeitemcond=' and sod.finalprice > 0 ';
		}

		$qryitem="select tmp.* from ( 
			SELECT distinct im.id,im.itemname,
			isnull((select sum(isnull(sod.qty,0)) from tblserviceorder so inner join tblserviceorderdetail sod on sod.orderid=so.id where so.iscancel=0 and sod.iscancel=0 and sod.itemid=im.id  $salepersoncond1  $datecond1  $withoutfreeitemcond),0) as sale_qty,
			isnull((select sum(isnull(sod.return_qty,0)) from tblserviceorder so inner join tblserviceorderdetail sod on sod.orderid=so.id where so.iscancel=0 and sod.iscancel=0 and sod.itemid=im.id  $salepersoncond2  $datecond2  $withoutfreeitemcond),0) as return_qty,
			isnull((select sum(isnull(sod.finalprice,0)) from tblserviceorder so inner join tblserviceorderdetail sod on sod.orderid=so.id where so.iscancel=0 and sod.iscancel=0 and sod.itemid=im.id  $salepersoncond3  $datecond3  $withoutfreeitemcond),0) as sale_amount   
			from tblitemmaster im 
			inner join tblitemstore ims on ims.itemid=im.id where im.categoryid not in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid) and 
			convert(varchar(50),ims.storeid) like :storeid and convert(varchar(50),im.id) like :itemid 
		) tmp order by tmp.itemname";
		
		$listitemsalesummaryreportdata=$DB->getmenual($qryitem,$itemparms,'listitemsalesummaryreportdata');

		$response['issalesummaryreportdata']=0;
		if($listitemsalesummaryreportdata)
		{
			$response['issalesummaryreportdata']=1;

			$response['salesummaryreportdata']=$listitemsalesummaryreportdata;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Membership Sale Summary Report Data
	else if($action == 'listmshipsalesummaryreportdata')
	{	
		$storeid=$IISMethods->sanitize($_POST['storeid']);
		$salepersonid=$IISMethods->sanitize($_POST['salepersonid']);
		$itemid=$IISMethods->sanitize($_POST['itemid']);


		$fromdate=$IISMethods->sanitize($_POST['fromdate']);
		$todate=$IISMethods->sanitize($_POST['todate']);


		$listmshipsalesummaryreportdata=new listmshipsalesummaryreportdata();



		$itemparms = array(
			':defaultcatmembershipid'=>$config->getDefaultCatMembershipId(),
			':defaultcatcourseid'=>$config->getDefaultCatCourseId(),
			':defaultcatpackageid'=>$config->getDefaultCatPackageId(),
			':storeid'=>$storeid,
			':itemid'=>$itemid,
		);


		$salepersoncond1='';
		$salepersoncond3='';
		if($salepersonid != '%')  //When Not All Sale Person
		{
			$salepersoncond1=" and convert(varchar(50),o.entry_uid) like :salepersonid1 ";
			$salepersoncond3=" and convert(varchar(50),o.entry_uid) like :salepersonid3 ";
			$itemparms[':salepersonid1']=$salepersonid;
			$itemparms[':salepersonid3']=$salepersonid;
		}


		$datecond1='';
		$datecond3='';
		if($fromdate && $todate)   //When Date Filter
		{
			// $datecond1=" AND CONVERT(date,o.timestamp,103) BETWEEN CONVERT(date,:fromdate1,103) and CONVERT(date,:todate1,103) ";
			// $datecond3=" AND CONVERT(date,o.timestamp,103) BETWEEN CONVERT(date,:fromdate3,103) and CONVERT(date,:todate3,103) ";

			$datecond1=" AND o.timestamp BETWEEN :fromdate1 and :todate1 ";
			$datecond3=" AND o.timestamp BETWEEN :fromdate3 and :todate3 ";
		   
			$itemparms[':fromdate1']=$fromdate;	
			$itemparms[':todate1']=$todate;	
			$itemparms[':fromdate3']=$fromdate;	
			$itemparms[':todate3']=$todate;	
		} 


		$qryitem="select tmp.* from ( 
			SELECT distinct im.id,im.itemname,
			isnull((select count(convert(varchar(50),od.id)) from tblorder o inner join tblorderdetail od on od.orderid=o.id where o.status=1 and o.iscancel=0 and od.itemid=im.id  $salepersoncond1  $datecond1  ),0) as sale_qty,
			isnull((select sum(isnull(od.finalprice,0)) from tblorder o inner join tblorderdetail od on od.orderid=o.id where o.status=1 and o.iscancel=0 and od.itemid=im.id  $salepersoncond3  $datecond3  ),0) as sale_amount   
			from tblitemmaster im 
			inner join tblitemstore ims on ims.itemid=im.id where im.categoryid in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid) and 
			convert(varchar(50),ims.storeid) like :storeid and convert(varchar(50),im.id) like :itemid 
		) tmp order by tmp.itemname";
		
		$listmshipsalesummaryreportdata=$DB->getmenual($qryitem,$itemparms,'listmshipsalesummaryreportdata');

		$response['issalesummaryreportdata']=0;
		if($listmshipsalesummaryreportdata)
		{
			$response['issalesummaryreportdata']=1;

			$response['salesummaryreportdata']=$listmshipsalesummaryreportdata;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	/****************************************** Start For Sale Summary Reports ************************************/

	
	
	
	

	
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  