<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\storeorder.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();
	$currdate=$IISMethods->getformatcurrdate();


	//List Payment Type
	if($action == 'listpaymenttype')
	{	
		$listpaymenttype=new listpaymenttype();

		$qry="select distinct pt.id,pt.type as name,concat(:imageurl,pt.image) as image,pt.displayorder 
			from tblpaymenttype pt 
			where pt.isactive=1 
			order by pt.displayorder";
		$parms = array(
			':imageurl'=>$imgpath,
		);
		$listpaymenttype=$DB->getmenual($qry,$parms);

		$response['ispaymenttypedata']=1;

		$response['paymenttypedata'][0]['id']=$mssqldefval['uniqueidentifier'];
		$response['paymenttypedata'][0]['type']='1';
		$response['paymenttypedata'][0]['name']='Cash';
		$response['paymenttypedata'][0]['image']='';

		
		if($listpaymenttype)
		{
			for($i=0;$i<sizeof($listpaymenttype);$i++)
			{
				$rowpt=$listpaymenttype[$i];
				$j=$i+1;
				$response['paymenttypedata'][$j]['id']=$rowpt['id'];
				$response['paymenttypedata'][$j]['type']='2';
				$response['paymenttypedata'][$j]['name']=$rowpt['name'];
				$response['paymenttypedata'][$j]['image']=$rowpt['image'];
			}	
		}

		$status = 1;
		$message = $errmsg['success'];
	}
	//List Note Amount
	else if($action == 'listnoteamount')
	{	
		$listnoteamount=new listnoteamount();

		$qry="select distinct na.id,na.amount as name
			from tblnoteamount na 
			where na.isactive=1 
			order by na.amount";
		$parms = array();
		$listnoteamount=$DB->getmenual($qry,$parms,'listnoteamount');

		$response['isnotedata']=0;

		if($listnoteamount)
		{
			$response['isnotedata']=1;
			$response['notedata']=$listnoteamount;

			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//Insert Store Order Data  (Issue Order Data)
	else if ($action=='insertstoreorderdata')
	{
		$storeid=$IISMethods->sanitize($_POST['storeid']);   
		$soid=$IISMethods->sanitize($_POST['soid']);       //Service Order ID
		$memberid=$IISMethods->sanitize($_POST['memberid']);   
		$membercontact = $IISMethods->sanitize($_POST['membercontact']);

		$ordernote = $IISMethods->sanitize($_POST['ordernote']);

		// $totalpayableamt=$IISMethods->sanitize($_POST['totalpayableamt']);  //Total Payable Amount
		// $totalpaidamount=$IISMethods->sanitize($_POST['totalpaidamount']);   //Total Paid Amount
		// $totalchangeamount=$IISMethods->sanitize($_POST['totalchangeamount']);  //Total Change Amount

		// $paymentsarr = $_POST['paymentdata'];      //Payments Array  (From Webadmin)
		// $decodejson_payments=json_decode($paymentsarr,TRUE);

		$cartitemdataarr=$_POST['cartitemdata'];
		$decodejson_cartitems=json_decode($cartitemdataarr,TRUE);

		$seesionarray=json_encode($_POST,true);

		if($storeid && $soid && sizeof($decodejson_cartitems) > 0)
		{
			$typename='storeorder';
			$qryseries = "SELECT TOP 1 * FROM tblseriesmaster WHERE typename=:typename ORDER BY timestamp DESC";
			$seriesparams = array(
				':typename'=>$typename,
			);
			$result_ary=$DB->getmenual($qryseries,$seriesparams);
			$rowseries=$result_ary[0];

			$ord_seriesid=$rowseries['id'];


			if($ord_seriesid)
			{
				try 
				{
					$DB->begintransaction();
		
					$datetime = $IISMethods->getdatetime();
					$order_unqid = $IISMethods->generateuuid();
					$order_date = $IISMethods->getcurrdate();
					$transactionid=uniqid('ASRS');

					
					$ord_seriesno=$DB->getorderno($ord_seriesid,'storeorder',$order_date);	
					$ord_maxid=$DB->getmaxid($ord_seriesid,'storeorder');
					$ord_prefix=$DB->getseriseprefix($ord_seriesid);
		
					$insqry['[id]']=$order_unqid;
					$insqry['[soid]']=$soid;
					$insqry['[transactionid]']=$transactionid;
					$insqry['[orderno]']=$ord_seriesno;
					$insqry['[storeid]']=$storeid;
					$insqry['[uid]']=$memberid;
					
					$insqry['[totalamount]']=0;
					$insqry['[totaltaxableamt]']=0;
					$insqry['[totaltax]']=0;
					$insqry['[totalpaid]']=0;
					$insqry['[totalpayableamt]']=0;
					$insqry['[totalpaidamount]']=0;
					$insqry['[totalchangeamount]']=0;

					$insqry['[ordernotes]']=$ordernote;
					$insqry['[orderdate]']=$order_date;
					
					$insqry['[sessionarray]']=$seesionarray;
					$insqry['[platform]']=$platform;
					$insqry['[paymenttype]']='';
					$insqry['[referenceno]']='';
					$insqry['[status]']=1;
					
					$insqry['[seriesid]']=$ord_seriesid;
					$insqry['[prefix]']=$ord_prefix;
					$insqry['[maxid]']=$ord_maxid;

					$insqry['[timestamp]']=$datetime;
					$insqry['[entry_uid]']=$uid;	
					$insqry['[entry_date]']=$datetime;

					$DB->executedata('i','tblstoreorder',$insqry,'');
						
		
					// Order DETAILS
					$totalamount=0;
					$totaltaxableamt=0;
					$totaltax=0;
					$totalpaid=0;
					if(sizeof($decodejson_cartitems)>0)
					{
						foreach($decodejson_cartitems as $k1=>$v1)
						{
							$od_id = $IISMethods->generateuuid();

							$i_sodid=$v1['id'];     //Service Order Detail ID
							if($v1['id'] == '')
							{
								$i_sodid=$mssqldefval['uniqueidentifier'];
							}

							$i_oidid=$v1['oidid'];    //Mship Order Item Details
							if($v1['oidid'] == '')
							{
								$i_oidid=$mssqldefval['uniqueidentifier'];
							}


							
							$od_insqry['[id]']=$od_id;
							$od_insqry['[orderid]']=$order_unqid;
							$od_insqry['[oidid]']=$i_oidid;
							$od_insqry['[sodid]']=$i_sodid;
							$od_insqry['[type]']=$v1['type'];
							$od_insqry['[typename]']=$v1['typename'];
							$od_insqry['[catid]']=$v1['catid'];
							$od_insqry['[category]']=$v1['category'];
							$od_insqry['[subcatid]']=$v1['subcatid'];
							$od_insqry['[subcategory]']=$v1['subcategory'];
							$od_insqry['[itemid]']=$v1['itemid'];
							$od_insqry['[itemname]']=$v1['itemname'];

							$od_insqry['[qty]']=$v1['qty'];
							$od_insqry['[returnqty]']=0;
							$od_insqry['[remainqty]']=$v1['qty'];
							$od_insqry['[taxtype]']=$v1['taxtype'];
							$od_insqry['[taxtypename]']=$v1['taxtypename'];
							$od_insqry['[sgst]']=$v1['sgst'];
							$od_insqry['[cgst]']=$v1['cgst'];
							$od_insqry['[igst]']=$v1['igst'];
							$od_insqry['[price]']=$v1['price'];
							$od_insqry['[discountper]']=$v1['discountper'];
							$od_insqry['[discountamt]']=$v1['discountamt'];
							$od_insqry['[taxable]']=$v1['taxable'];
							$od_insqry['[sgsttaxamt]']=$v1['sgsttaxamt'];
							$od_insqry['[cgsttaxamt]']=$v1['cgsttaxamt'];
							$od_insqry['[igsttaxamt]']=$v1['igsttaxamt'];
							$od_insqry['[finalprice]']=$v1['finalprice'];
							$od_insqry['[timestamp]']=$datetime;

							$DB->executedata('i','tblstoreorderdetail',$od_insqry,'');


							/************** Start For Update Qty in Service Order ****************/
							$qrysoid="select sod.id,isnull(sod.qty,0) as qty,isnull(sod.issued_qty,0) as issued_qty,isnull(sod.remain_qty,0) as remain_qty 
								from tblserviceorderdetail sod where sod.id=:sodid";	
							$soidparams = array(
								':sodid'=>$i_sodid, 
							);
							$ressoid=$DB->getmenual($qrysoid,$soidparams);
							if(sizeof($ressoid) > 0)
							{
								$rowsoid=$ressoid[0];

								$qty=$rowsoid['qty'];
								$issued_qty=$rowsoid['issued_qty'];
								$remain_qty=$rowsoid['remain_qty'];

								$new_issuedqty=$rowsoid['issued_qty']+$v1['qty'];
								$new_remainqty=$rowsoid['remain_qty']-$v1['qty'];

								$updsoidqry=array(					
									'[issued_qty]'=>$new_issuedqty,
									'[remain_qty]'=>$new_remainqty,					
								);
								$extrasoidparams=array(
									'[id]'=>$i_sodid
								);
								$DB->executedata('u','tblserviceorderdetail',$updsoidqry,$extrasoidparams);

							}
							/************** End For Update Qty in Service Order ****************/


							/************** Start For Update Qty in Mship Order ****************/
							$qryoid="select oid.id,isnull(oid.qty,0) as qty,isnull(oid.usedqty,0) as usedqty,isnull(oid.remainqty,0) as remainqty from tblorderitemdetail oid where oid.id=:oidid";	
							$oidparams = array(
								':oidid'=>$i_oidid, 
							);
							$resoid=$DB->getmenual($qryoid,$oidparams);
							if(sizeof($resoid) > 0)
							{
								$rowoid=$resoid[0];

								$old_qty=$rowoid['qty'];
								$old_usedqty=$rowoid['usedqty'];
								$old_remainqty=$rowoid['remainqty'];

								$new_usedqty=$rowoid['usedqty']+$v1['qty'];
								$new_remainqty=$rowoid['remainqty']-$v1['qty'];

								$updoidqry=array(					
									'[usedqty]'=>$new_usedqty,
									'[remainqty]'=>$new_remainqty,					
								);
								$extraoidparams=array(
									'[id]'=>$i_oidid
								);
								$DB->executedata('u','tblorderitemdetail',$updoidqry,$extraoidparams);

							}
							/************** End For Update Qty in Mship Order ****************/


							$totalamount+=$v1['price'];
							$totaltaxableamt+=$v1['taxable'];
							$totaltax+=$v1['igsttaxamt'];
							$totalpaid+=$v1['finalprice'];

							
						}
					}




					//Update Order Data
					$updord['[totalamount]']=$totalamount;
					$updord['[totaltaxableamt]']=$totaltaxableamt;
					$updord['[totaltax]']=$totaltax;
					$updord['[totalpaid]']=$totalpaid;
					$updord['[totalpayableamt]']=$totalpaid;
					$updord['[totalpaidamount]']=$totalpaid;
					$upddata['[id]']=$order_unqid;
					$DB->executedata('u','tblstoreorder',$updord,$upddata);

					if($config->getIsAccessSAP() == 1)
					{
						//Update Store Order Stock in SAP Stock Deduct (HaNa DB)
						//$DB->SAPUpdateItemStockData($SubDB,$order_unqid);


						//Insert Issue Item Data in SAP AR Invoice Delivery (HaNa DB)
						$DB->SAPInsertARInvoiceDeliveryData($SubDB,$order_unqid);
					}

					
					$response['membercontact']=$membercontact;

					$status=1;
					$message=$errmsg['orderitemissued'];
					
					$DB->committransaction();
				} 
				catch (Exception $e) 
				{
					//Order Error 
					$DB->rollbacktransaction($e);
					$status=0;
					$message=$errmsg['orderdberror'];
				}	
			}
			else
			{
				$status=0;
				$message=$errmsg['noorderseries'];
			}
		}
			
	}
	//List Store Order History
	else if($action == 'liststoreorderhistory')
	{
		$storeid=$IISMethods->sanitize($_POST['storeid']);  
		$fltsearch=$IISMethods->sanitize($_POST['fltsearch']);   

		$liststoreorderhistory=new liststoreorderhistory();
		
		$qry = "select distinct so.id,so.timestamp,so.transactionid,so.orderno,so.storeid,so.uid,so.orderdate,
			so.totalamount,so.totaltaxableamt,so.totaltax,so.totalpaid,so.totalpayableamt,so.totalpaidamount,so.totalchangeamount,so.ordernotes,
			convert(varchar, so.timestamp,100) AS ofulldate,pm.personname as membername,pm.contact as membercontact,
			pm1.personname as entrypersonname,pm1.contact as entrypersoncontact 
			from tblstoreorder so 
			inner join tblstoreorderdetail sod on sod.orderid=so.id 
			inner join tblpersonmaster pm on pm.id=so.uid 
			inner join tblpersonmaster pm1 on pm1.id=so.entry_uid 
			where so.status=1 and so.storeid=:storeid and so.entry_uid=:entry_uid  ";
		$parms = array(
			':storeid'=>$storeid,
			':entry_uid'=>$uid,
		);
		if($fltsearch)
		{
			$qry.=" and (so.transactionid like :fltsearch1 or so.orderno like :fltsearch2 or so.orderdate like :fltsearch3 or convert(varchar, so.timestamp,100) like :fltsearch4 or pm.personname like :fltsearch5 or pm1.personname like :fltsearch6) ";
			$parms[':fltsearch1']='%'.$fltsearch.'%';
			$parms[':fltsearch2']='%'.$fltsearch.'%';
			$parms[':fltsearch3']='%'.$fltsearch.'%';
			$parms[':fltsearch4']='%'.$fltsearch.'%';
			$parms[':fltsearch5']='%'.$fltsearch.'%';
			$parms[':fltsearch6']='%'.$fltsearch.'%';
		}
		$qry.=" ORDER BY so.timestamp DESC offset $start rows fetch next $per_page rows only";
		//echo $qry;
		//print_r($parms);
		$liststoreorderhistory=$DB->getmenual($qry,$parms,'liststoreorderhistory');
		
		$response['isstoreorderdata']=0;
		if($liststoreorderhistory)
		{	
			
			$response['isstoreorderdata']=1;
			$response['storeorderdata']=$liststoreorderhistory;
			
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
	//List Store Order Detail
	else if($action == 'liststoreorderdetail')
	{
		$orderid=$IISMethods->sanitize($_POST['orderid']); 
		$liststoreorderhistory=new liststoreorderhistory();
		
		
		$qry = "select distinct so.id,so.timestamp,so.transactionid,so.orderno,so.storeid,so.uid,so.orderdate,
			so.totalamount,so.totaltaxableamt,so.totaltax,so.totalpaid,so.totalpayableamt,so.totalpaidamount,so.totalchangeamount,so.ordernotes,
			convert(varchar, so.timestamp,100) AS ofulldate,pm.personname as membername,pm.contact as membercontact,
			pm1.personname as entrypersonname,pm1.contact as entrypersoncontact  
			from tblstoreorder so 
			inner join tblstoreorderdetail sod on sod.orderid=so.id 
			inner join tblpersonmaster pm on pm.id=so.uid 
			inner join tblpersonmaster pm1 on pm1.id=so.entry_uid 
			where so.status=1 and so.id=:orderid  ";
		$parms = array(
			':orderid'=>$orderid,
		);
		$result_ary=$DB->getmenual($qry,$parms,'liststoreorderhistory');
		
		$response['isstoreorderdata']=0;
		if(sizeof($result_ary) > 0)
		{	
			$response['isstoreorderdata']=1;

			for($i=0;$i<sizeof($result_ary);$i++)
			{
				/*************** Start For Order Item Details ********************/
				$storeorderdetailinfo=new storeorderdetailinfo();

				$qryod="select sod.id,sod.orderid,sod.type,sod.typename,sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,REPLACE(sod.itemname,'&amp;','&') as itemname,sod.qty,
					sod.taxtype,sod.taxtypename,sod.sgst,sod.cgst,sod.igst,sod.price,sod.discountper,sod.discountamt,sod.taxable,sod.sgsttaxamt,sod.cgsttaxamt,sod.igsttaxamt,sod.finalprice
					from tblstoreorderdetail sod 
					where sod.orderid=:orderid";
				$odparams = array(
					':orderid'=>$result_ary[$i]->getId(), 
				);
				$storeorderdetailinfo=$DB->getmenual($qryod,$odparams,'storeorderdetailinfo');

				$result_ary[$i]->setIsStoreOrderDetail('0');
				if(sizeof($storeorderdetailinfo)>0)
				{
					$result_ary[$i]->setIsStoreOrderDetail('1');

					$result_ary[$i]->setStoreOrderDetailInfo($storeorderdetailinfo);
				}
				/*************** End For Order Item Details ********************/


				/*************** Start For Order Payment Details ********************/
				$storeorderpaymentdetailinfo=new storeorderpaymentdetailinfo();

				$qryopd="select sopd.id,sopd.orderid,sopd.type,sopd.paytypeid,sopd.paytypename,sopd.amount 
					from tblstoreorderpaymentdetail sopd 
					where sopd.orderid=:orderid";
				$opdparams = array(
					':orderid'=>$result_ary[$i]->getId(), 
				);
				$storeorderpaymentdetailinfo=$DB->getmenual($qryopd,$opdparams,'storeorderpaymentdetailinfo');

				$result_ary[$i]->setIsStoreOrderPaymentDetail('0');
				if(sizeof($storeorderpaymentdetailinfo)>0)
				{
					$result_ary[$i]->setIsStoreOrderPaymentDetail('1');

					$result_ary[$i]->setStoreOrderPaymentDetailInfo($storeorderpaymentdetailinfo);
				}
				/*************** End For Order Payment Details ********************/
			}
			
			
			$response['storeorderdata']=json_decode(json_encode($result_ary));
			
			
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

  