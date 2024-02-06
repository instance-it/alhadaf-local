<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\serviceorder.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();
	$currdate=$IISMethods->getformatcurrdate();


	//Insert Service Order Data
	if ($action=='insertserviceorderdata')
	{
		$id=$IISMethods->sanitize($_POST['id']);   
		$formevent=$IISMethods->sanitize($_POST['formevent']);   //addright, editright

		$storeid=$IISMethods->sanitize($_POST['storeid']);   
		$memberid=$IISMethods->sanitize($_POST['memberid']);   
		$membercontact = $IISMethods->sanitize($_POST['membercontact']);

		$ordernote = $IISMethods->sanitize($_POST['ordernote']);

		$totalpayableamt=$IISMethods->sanitize($_POST['totalpayableamt']);  //Total Payable Amount
		$totalpaidamount=$IISMethods->sanitize($_POST['totalpaidamount']);   //Total Paid Amount
		$totalchangeamount=$IISMethods->sanitize($_POST['totalchangeamount']);  //Total Change Amount

		$referenceno = $IISMethods->sanitize($_POST['referenceno']);

		$paymentsarr = $_POST['paymentdata'];      //Payments Array  (From Webadmin)
		$decodejson_payments=json_decode($paymentsarr,TRUE);

		$cartitemdataarr=$_POST['cartitemdata'];
		$decodejson_cartitems=json_decode($cartitemdataarr,TRUE);

		$seesionarray=json_encode($_POST,true);

		if(sizeof($decodejson_cartitems) > 0)
		{
			if($formevent=='addright')
			{
				$typename='serviceorder';
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
						$transactionid=uniqid('ASRSO');

						
						$ord_seriesno=$DB->getorderno($ord_seriesid,'serviceorder',$order_date);	
						$ord_maxid=$DB->getmaxid($ord_seriesid,'serviceorder');
						$ord_prefix=$DB->getseriseprefix($ord_seriesid);
			
						$insqry['[id]']=$order_unqid;
						$insqry['[transactionid]']=$transactionid;
						$insqry['[orderno]']=$ord_seriesno;
						$insqry['[storeid]']=$storeid;
						$insqry['[uid]']=$memberid;
						
						$insqry['[totalamount]']=0;
						$insqry['[totaltaxableamt]']=0;
						$insqry['[totaltax]']=0;
						$insqry['[totalpaid]']=0;
						$insqry['[totalpayableamt]']=$totalpayableamt;
						$insqry['[totalpaidamount]']=$totalpaidamount;
						$insqry['[totalchangeamount]']=$totalchangeamount;

						$insqry['[ordernotes]']=$ordernote;
						$insqry['[orderdate]']=$order_date;
						
						$insqry['[sessionarray]']=$seesionarray;
						$insqry['[platform]']=$platform;
						$insqry['[paymenttype]']='';
						$insqry['[referenceno]']=$referenceno;
						$insqry['[status]']=1;
						
						$insqry['[seriesid]']=$ord_seriesid;
						$insqry['[prefix]']=$ord_prefix;
						$insqry['[maxid]']=$ord_maxid;

						$insqry['[timestamp]']=$datetime;
						$insqry['[entry_uid]']=$uid;	
						$insqry['[entry_date]']=$datetime;

						$DB->executedata('i','tblserviceorder',$insqry,'');
							
			
						// Order DETAILS
						$totalamount=0;
						$totaltaxableamt=0;
						$totaltax=0;
						$totalpaid=0;
						if(sizeof($decodejson_cartitems)>0)
						{
							foreach($decodejson_cartitems as $k1=>$v1)
							{
								$categoryid=$v1['categoryid'];
								$category=$v1['category'];
								$subcategoryid=$v1['subcategoryid'];
								$subcategory=$v1['subcategory'];
								$itemid=$v1['itemid'];
								$itemname=$v1['itemname'];

								$cartsubitemdataarr=$v1['summarydetail'];
								
								if(sizeof($cartsubitemdataarr)>0)
								{
									for($j=0;$j<sizeof($cartsubitemdataarr);$j++)
									{
										$od_id = $IISMethods->generateuuid();


										$i_oidid=$cartsubitemdataarr[$j]['oidid'];
										if($cartsubitemdataarr[$j]['oidid'] == '')
										{
											$i_oidid=$mssqldefval['uniqueidentifier'];
										}
										
										$od_insqry['[id]']=$od_id;
										$od_insqry['[orderid]']=$order_unqid;
										$od_insqry['[oidid]']=$i_oidid;
										$od_insqry['[type]']=$cartsubitemdataarr[$j]['type'];
										$od_insqry['[typename]']=$cartsubitemdataarr[$j]['typename'];
										$od_insqry['[catid]']=$categoryid;
										$od_insqry['[category]']=$category;
										$od_insqry['[subcatid]']=$subcategoryid;
										$od_insqry['[subcategory]']=$subcategory;
										$od_insqry['[itemid]']=$itemid;
										$od_insqry['[itemname]']=$itemname;

										$od_insqry['[qty]']=$cartsubitemdataarr[$j]['qty'];
										$od_insqry['[issued_qty]']=0;
										$od_insqry['[remain_qty]']=$cartsubitemdataarr[$j]['qty'];
										$od_insqry['[returnqty]']=0;
										$od_insqry['[remainqty]']=$cartsubitemdataarr[$j]['qty'];
										$od_insqry['[taxtype]']=$cartsubitemdataarr[$j]['taxtype'];
										$od_insqry['[taxtypename]']=$cartsubitemdataarr[$j]['taxtypename'];
										$od_insqry['[sgst]']=$cartsubitemdataarr[$j]['sgst'];
										$od_insqry['[cgst]']=$cartsubitemdataarr[$j]['cgst'];
										$od_insqry['[igst]']=$cartsubitemdataarr[$j]['igst'];
										$od_insqry['[price]']=$cartsubitemdataarr[$j]['price'];
										$od_insqry['[discountper]']=$cartsubitemdataarr[$j]['discount'];
										$od_insqry['[discountamt]']=$cartsubitemdataarr[$j]['discountamt'];
										$od_insqry['[taxable]']=$cartsubitemdataarr[$j]['taxable'];
										$od_insqry['[sgsttaxamt]']=$cartsubitemdataarr[$j]['sgsttaxamt'];
										$od_insqry['[cgsttaxamt]']=$cartsubitemdataarr[$j]['cgsttaxamt'];
										$od_insqry['[igsttaxamt]']=$cartsubitemdataarr[$j]['igsttaxamt'];
										$od_insqry['[finalprice]']=$cartsubitemdataarr[$j]['finalprice'];
										$od_insqry['[timestamp]']=$datetime;

										//print_r($od_insqry);
										
										$DB->executedata('i','tblserviceorderdetail',$od_insqry,'');


										/************** Start For Update Qty in Mship Order ****************/
										// $qryoid="select oid.id,isnull(oid.qty,0) as qty,isnull(oid.usedqty,0) as usedqty,isnull(oid.remainqty,0) as remainqty from tblorderitemdetail oid where oid.id=:oidid";	
										// $oidparams = array(
										// 	':oidid'=>$i_oidid, 
										// );
										// $resoid=$DB->getmenual($qryoid,$oidparams);
										// if(sizeof($resoid) > 0)
										// {
										// 	$rowoid=$resoid[0];

										// 	$old_qty=$rowoid['qty'];
										// 	$old_usedqty=$rowoid['usedqty'];
										// 	$old_remainqty=$rowoid['remainqty'];

										// 	$new_usedqty=$rowoid['usedqty']+$cartsubitemdataarr[$j]['qty'];
										// 	$new_remainqty=$rowoid['remainqty']-$cartsubitemdataarr[$j]['qty'];

										// 	$updoidqry=array(					
										// 		'[usedqty]'=>$new_usedqty,
										// 		'[remainqty]'=>$new_remainqty,					
										// 	);
										// 	$extraoidparams=array(
										// 		'[id]'=>$i_oidid
										// 	);
										// 	$DB->executedata('u','tblorderitemdetail',$updoidqry,$extraoidparams);

										// }
										/************** End For Update Qty in Mship Order ****************/


										$totalamount+=$cartsubitemdataarr[$j]['price'];
										$totaltaxableamt+=$cartsubitemdataarr[$j]['taxable'];
										$totaltax+=$cartsubitemdataarr[$j]['igsttaxamt'];
										$totalpaid+=$cartsubitemdataarr[$j]['finalprice'];
									}
								}

								
							}
						}



						//Payment Details
						if(sizeof($decodejson_payments)  > 0)
						{
							foreach($decodejson_payments as $k=>$v)
							{
								$subpayunqid = $IISMethods->generateuuid();
								
								$subpayinsary=array(	
									'[id]'=>$subpayunqid,				
									'[orderid]'=>$order_unqid,
									'[type]'=>$IISMethods->sanitize($v['type']),
									'[paytypeid]'=>$IISMethods->sanitize($v['paytypeid']),	
									'[paytypename]'=>$IISMethods->sanitize($v['paytypename']),
									'[amount]'=>$IISMethods->sanitize($v['payamt']),		
									'[timestamp]'=>$datetime,
								);
								$DB->executedata('i','tblserviceorderpaymentdetail',$subpayinsary,'');
							}
						}


						//Update Order Data
						$updord['[totalamount]']=$totalamount;
						$updord['[totaltaxableamt]']=$totaltaxableamt;
						$updord['[totaltax]']=$totaltax;
						$updord['[totalpaid]']=$totalpaid;
						$upddata['[id]']=$order_unqid;
						$DB->executedata('u','tblserviceorder',$updord,$upddata);


						
						$response['membercontact']=$membercontact;
						$response['orderno']=(string)$ord_seriesno;
						$response['cmp_logo']=(string)$imgpath.'images/posprint.png';
						//$response['cmp_logo']=(string)$imgpath.$CompanyInfo->getLogoImg();
						$response['cmp_address']=(string)$CompanyInfo->getAddress();
						$response['cmp_email']=(string)$CompanyInfo->getEmail1();
						$response['cmp_contact']=(string)$CompanyInfo->getContact1();

						$response['cmp_israngehour']='0';
						if(sizeof($CompanyInfo->getCmpRangeHour()) > 0)
                        {
							$response['cmp_israngehour']='1';
							for($k=0;$k<sizeof($CompanyInfo->getCmpRangeHour());$k++)
                            {
								$response['cmp_rangehour'][$k]['name']=$CompanyInfo->getCmpRangeHour()[$k]->getName();
							}	
						}
						
						$response['curr_datetime']=date('M d Y h:iA');


						$status=1;
						$message=$errmsg['soordersaved'];
						
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
					$message=$errmsg['nosoorderseries'];
				}
			}
			else if($formevent=='editright')
			{
				$qryso = "SELECT id,orderno FROM tblserviceorder WHERE id=:id";
				$soparams = array(
					':id'=>$id,
				);
				$resso=$DB->getmenual($qryso,$soparams);

				if(sizeof($resso) > 0)
				{
					$rowso=$resso[0];
					try 
					{
						$DB->begintransaction();
			
						$datetime = $IISMethods->getdatetime();
						$order_unqid = $id;
						$order_date = $IISMethods->getcurrdate();
						
						$insqry['[uid]']=$memberid;
						
						$insqry['[totalamount]']=0;
						$insqry['[totaltaxableamt]']=0;
						$insqry['[totaltax]']=0;
						$insqry['[totalpaid]']=0;
						$insqry['[totalpayableamt]']=$totalpayableamt;
						$insqry['[totalpaidamount]']=$totalpaidamount;
						$insqry['[totalchangeamount]']=$totalchangeamount;

						$insqry['[ordernotes]']=$ordernote;
						$insqry['[orderdate]']=$order_date;
						
						$insqry['[sessionarray]']=$seesionarray;
						$insqry['[paymenttype]']='';
						$insqry['[referenceno]']=$referenceno;
						
						$insqry['[update_uid]']=$uid;	
						$insqry['[update_date]']=$datetime;

						$extraparams=array(
							'[id]'=>$id
						);
						$DB->executedata('u','tblserviceorder',$insqry,$extraparams);



						$del1extraparams=array(
							'[orderid]'=>$id
						);
						$DB->executedata('d','tblserviceorderdetail','',$del1extraparams);

						$del2extraparams=array(
							'[orderid]'=>$id
						);
						$DB->executedata('d','tblserviceorderpaymentdetail','',$del2extraparams);
							
			
						// Order DETAILS
						$totalamount=0;
						$totaltaxableamt=0;
						$totaltax=0;
						$totalpaid=0;
						if(sizeof($decodejson_cartitems)>0)
						{
							foreach($decodejson_cartitems as $k1=>$v1)
							{
								$categoryid=$v1['categoryid'];
								$category=$v1['category'];
								$subcategoryid=$v1['subcategoryid'];
								$subcategory=$v1['subcategory'];
								$itemid=$v1['itemid'];
								$itemname=$v1['itemname'];

								$cartsubitemdataarr=$v1['summarydetail'];
								
								if(sizeof($cartsubitemdataarr)>0)
								{
									for($j=0;$j<sizeof($cartsubitemdataarr);$j++)
									{
										$od_id = $IISMethods->generateuuid();


										$i_oidid=$cartsubitemdataarr[$j]['oidid'];
										if($cartsubitemdataarr[$j]['oidid'] == '')
										{
											$i_oidid=$mssqldefval['uniqueidentifier'];
										}
										
										$od_insqry['[id]']=$od_id;
										$od_insqry['[orderid]']=$order_unqid;
										$od_insqry['[oidid]']=$i_oidid;
										$od_insqry['[type]']=$cartsubitemdataarr[$j]['type'];
										$od_insqry['[typename]']=$cartsubitemdataarr[$j]['typename'];
										$od_insqry['[catid]']=$categoryid;
										$od_insqry['[category]']=$category;
										$od_insqry['[subcatid]']=$subcategoryid;
										$od_insqry['[subcategory]']=$subcategory;
										$od_insqry['[itemid]']=$itemid;
										$od_insqry['[itemname]']=$itemname;

										$od_insqry['[qty]']=$cartsubitemdataarr[$j]['qty'];
										$od_insqry['[returnqty]']=0;
										$od_insqry['[remainqty]']=$cartsubitemdataarr[$j]['qty'];
										$od_insqry['[taxtype]']=$cartsubitemdataarr[$j]['taxtype'];
										$od_insqry['[taxtypename]']=$cartsubitemdataarr[$j]['taxtypename'];
										$od_insqry['[sgst]']=$cartsubitemdataarr[$j]['sgst'];
										$od_insqry['[cgst]']=$cartsubitemdataarr[$j]['cgst'];
										$od_insqry['[igst]']=$cartsubitemdataarr[$j]['igst'];
										$od_insqry['[price]']=$cartsubitemdataarr[$j]['price'];
										$od_insqry['[discountper]']=$cartsubitemdataarr[$j]['discount'];
										$od_insqry['[discountamt]']=$cartsubitemdataarr[$j]['discountamt'];
										$od_insqry['[taxable]']=$cartsubitemdataarr[$j]['taxable'];
										$od_insqry['[sgsttaxamt]']=$cartsubitemdataarr[$j]['sgsttaxamt'];
										$od_insqry['[cgsttaxamt]']=$cartsubitemdataarr[$j]['cgsttaxamt'];
										$od_insqry['[igsttaxamt]']=$cartsubitemdataarr[$j]['igsttaxamt'];
										$od_insqry['[finalprice]']=$cartsubitemdataarr[$j]['finalprice'];
										$od_insqry['[timestamp]']=$datetime;

										$DB->executedata('i','tblserviceorderdetail',$od_insqry,'');


										/************** Start For Update Qty in Mship Order ****************/
										// $qryoid="select oid.id,isnull(oid.qty,0) as qty,isnull(oid.usedqty,0) as usedqty,isnull(oid.remainqty,0) as remainqty from tblorderitemdetail oid where oid.id=:oidid";	
										// $oidparams = array(
										// 	':oidid'=>$i_oidid, 
										// );
										// $resoid=$DB->getmenual($qryoid,$oidparams);
										// if(sizeof($resoid) > 0)
										// {
										// 	$rowoid=$resoid[0];

										// 	$old_qty=$rowoid['qty'];
										// 	$old_usedqty=$rowoid['usedqty'];
										// 	$old_remainqty=$rowoid['remainqty'];

										// 	$new_usedqty=$rowoid['usedqty']+$cartsubitemdataarr[$j]['qty'];
										// 	$new_remainqty=$rowoid['remainqty']-$cartsubitemdataarr[$j]['qty'];

										// 	$updoidqry=array(					
										// 		'[usedqty]'=>$new_usedqty,
										// 		'[remainqty]'=>$new_remainqty,					
										// 	);
										// 	$extraoidparams=array(
										// 		'[id]'=>$i_oidid
										// 	);
										// 	$DB->executedata('u','tblorderitemdetail',$updoidqry,$extraoidparams);

										// }
										/************** End For Update Qty in Mship Order ****************/


										$totalamount+=$cartsubitemdataarr[$j]['price'];
										$totaltaxableamt+=$cartsubitemdataarr[$j]['taxable'];
										$totaltax+=$cartsubitemdataarr[$j]['igsttaxamt'];
										$totalpaid+=$cartsubitemdataarr[$j]['finalprice'];
									}
								}

								
							}
						}





						//Payment Details
						if(sizeof($decodejson_payments)  > 0)
						{
							foreach($decodejson_payments as $k=>$v)
							{
								$subpayunqid = $IISMethods->generateuuid();
								
								$subpayinsary=array(	
									'[id]'=>$subpayunqid,				
									'[orderid]'=>$order_unqid,
									'[type]'=>$IISMethods->sanitize($v['type']),
									'[paytypeid]'=>$IISMethods->sanitize($v['paytypeid']),	
									'[paytypename]'=>$IISMethods->sanitize($v['paytypename']),
									'[amount]'=>$IISMethods->sanitize($v['payamt']),		
									'[timestamp]'=>$datetime,
								);
								$DB->executedata('i','tblserviceorderpaymentdetail',$subpayinsary,'');
							}
						}


						//Update Order Data
						$updord['[totalamount]']=$totalamount;
						$updord['[totaltaxableamt]']=$totaltaxableamt;
						$updord['[totaltax]']=$totaltax;
						$updord['[totalpaid]']=$totalpaid;
						$upddata['[id]']=$order_unqid;
						$DB->executedata('u','tblserviceorder',$updord,$upddata);

						
						$response['membercontact']=$membercontact;

						$response['orderno']=(string)$rowso['orderno'];
						$response['cmp_logo']=(string)$imgpath.'images/posprint.png';
						//$response['cmp_logo']=(string)$imgpath.$CompanyInfo->getLogoImg();
						$response['cmp_address']=(string)$CompanyInfo->getAddress();
						$response['cmp_email']=(string)$CompanyInfo->getEmail1();
						$response['cmp_contact']=(string)$CompanyInfo->getContact1();

						$response['cmp_israngehour']='0';
						if(sizeof($CompanyInfo->getCmpRangeHour()) > 0)
                        {
							$response['cmp_israngehour']='1';
							for($k=0;$k<sizeof($CompanyInfo->getCmpRangeHour());$k++)
                            {
								$response['cmp_rangehour'][$k]['name']=$CompanyInfo->getCmpRangeHour()[$k]->getName();
							}	
						}

						$response['curr_datetime']=date('M d Y h:iA');

						$status=1;
						$message=$errmsg['soordersaved'];
						
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
					$message=$errmsg['invalidsoorder'];
				}
			}	
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
			
	}
	//List Service Order History
	else if($action == 'listserviceorderhistory')
	{
		$storeid=$IISMethods->sanitize($_POST['storeid']);   
		$listserviceorderhistory=new listserviceorderhistory();

		$fltfilter=$IISMethods->sanitize($_POST['fltfilter']);  
		$fltmemberid=$IISMethods->sanitize($_POST['fltmemberid']);  
		$fltfromdate=$IISMethods->sanitize($_POST['fltfromdate']);  
		$flttodate=$IISMethods->sanitize($_POST['flttodate']);  
		
		$qry = "select distinct tmp.id,tmp.timestamp,tmp.transactionid,tmp.orderno,tmp.storeid,tmp.uid,tmp.orderdate,tmp.totalamount,tmp.totaltaxableamt,tmp.totaltax,tmp.totalpaid,tmp.totalpayableamt,tmp.totalpaidamount,tmp.totalchangeamount,tmp.ordernotes,tmp.referenceno,
			tmp.ofulldate,tmp.membername,tmp.membercontact,tmp.entrypersonname,tmp.entrypersoncontact,tmp.invoicepdfurl,
			case when (tmp.iscancel=1 or tmp.totalissued_qty > 0) then '0' else '1' end AS showeditbutton,
			case when (tmp.iscancel=1 or tmp.totalissued_qty > 0) then '0' else '1' end AS showcancelbutton,
			case when (tmp.iscancel=1) then 'Cancelled' else '' end AS orderstatus,
			case when (tmp.iscancel=1) then '#ff000f' else '' end AS orderstatuscolor 
			from (
				select distinct so.id,so.timestamp,so.transactionid,so.orderno,so.storeid,so.uid,so.orderdate,
				so.totalamount,so.totaltaxableamt,so.totaltax,so.totalpaid,so.totalpayableamt,so.totalpaidamount,so.totalchangeamount,so.ordernotes,so.referenceno,
				convert(varchar, so.timestamp,100) AS ofulldate,pm.personname as membername,pm.contact as membercontact,
				pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,so.iscancel,
				case when (isnull(so.pdfurl,'')<>'' and so.iscancel=0) then concat(:imageurl,pdfurl) else '' end as invoicepdfurl,
				isnull((select sum(issued_qty) from tblserviceorderdetail where orderid=so.id),0) as totalissued_qty
				from tblserviceorder so 
				inner join tblserviceorderdetail sod on sod.orderid=so.id 
				inner join tblpersonmaster pm on pm.id=so.uid 
				inner join tblpersonmaster pm1 on pm1.id=so.entry_uid 
				where so.status=1   ";
		$parms = array(
			':imageurl'=>$config->getImageurl(),
			//':entry_uid'=>$uid,
		);
		if($fltmemberid != '%')
		{
			$qry.=" and so.uid LIKE :fltmemberid ";
			$parms[':fltmemberid']=$fltmemberid;
		}

		if($fltfilter)
		{
			$qry.=" and (so.transactionid LIKE :transactionidfilter or so.orderno LIKE :ordernofilter or pm.personname LIKE :memberfilter or pm.contact LIKE :membercontactfilter or pm1.personname LIKE :entrypersonfilter or pm1.contact LIKE :entrypersoncontactfilter) ";
			$parms[':transactionidfilter']='%'.$fltfilter.'%';
			$parms[':ordernofilter']='%'.$fltfilter.'%';
			$parms[':memberfilter']='%'.$fltfilter.'%';
			$parms[':membercontactfilter']='%'.$fltfilter.'%';
			$parms[':entrypersonfilter']='%'.$fltfilter.'%';
			$parms[':entrypersoncontactfilter']='%'.$fltfilter.'%';
		}

		if($fltfromdate && $flttodate)
		{
			$qry.=" and CONVERT(date,so.orderdate,103) between  CONVERT(date,:fromdate,103) and  CONVERT(date,:todate,103) ";
			$parms[':fromdate']=$fltfromdate; 
			$parms[':todate']=$flttodate; 
		}
		$qry.=" ) tmp ORDER BY tmp.timestamp DESC offset $start rows fetch next $per_page rows only";
		//echo $qry;
		//print_r($parms);
		$listserviceorderhistory=$DB->getmenual($qry,$parms,'listserviceorderhistory');
		
		$response['isserviceorderdata']=0;
		if($listserviceorderhistory)
		{	
			
			$response['isserviceorderdata']=1;
			$response['serviceorderdata']=$listserviceorderhistory;
			
			$response['nextpage']=$nextpage;

			$common_listdata=$listserviceorderhistory;

			
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}


		$response['cmp_logo']=(string)$imgpath.'images/posprint.png';
		//$response['cmp_logo']=(string)$imgpath.$CompanyInfo->getLogoImg();
		$response['cmp_address']=(string)$CompanyInfo->getAddress();
		$response['cmp_email']=(string)$CompanyInfo->getEmail1();
		$response['cmp_contact']=(string)$CompanyInfo->getContact1();

		$response['cmp_israngehour']='0';
		if(sizeof($CompanyInfo->getCmpRangeHour()) > 0)
		{
			$response['cmp_israngehour']='1';
			for($k=0;$k<sizeof($CompanyInfo->getCmpRangeHour());$k++)
			{
				$response['cmp_rangehour'][$k]['name']=$CompanyInfo->getCmpRangeHour()[$k]->getName();
			}	
		}


		$response['curr_datetime']=date('M d Y h:iA');

	}
	//List Service Order Detail
	else if($action == 'listserviceorderdetail')
	{
		$orderid=$IISMethods->sanitize($_POST['orderid']); 
		$listserviceorderhistory=new listserviceorderhistory();
		
		
		$qry = "select distinct so.id,so.timestamp,so.transactionid,so.orderno,so.storeid,so.uid,so.orderdate,
			so.totalamount,so.totaltaxableamt,so.totaltax,so.totalpaid,so.totalpayableamt,so.totalpaidamount,so.totalchangeamount,so.ordernotes,so.referenceno,
			convert(varchar, so.timestamp,100) AS ofulldate,pm.personname as membername,pm.contact as membercontact,
			pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,sm.storename,
			case when (so.iscancel=1) then '0' else '1' end AS showeditbutton,
			case when (so.iscancel=1) then '0' else '1' end AS showcancelbutton,
			case when (so.iscancel=1) then 'Cancelled' else '' end AS orderstatus,
			case when (so.iscancel=1) then '#ff000f' else '' end AS orderstatuscolor      
			from tblserviceorder so 
			inner join tblserviceorderdetail sod on sod.orderid=so.id 
			inner join tblpersonmaster pm on pm.id=so.uid 
			inner join tblpersonmaster pm1 on pm1.id=so.entry_uid 
			inner join tblstoremaster sm on sm.id=so.storeid
			where so.status=1 and so.id=:orderid  ";
		$parms = array(
			':orderid'=>$orderid,
		);
		$result_ary=$DB->getmenual($qry,$parms,'listserviceorderhistory');
		
		$response['isserviceorderdata']=0;
		if(sizeof($result_ary) > 0)
		{	
			$response['isserviceorderdata']=1;

			for($i=0;$i<sizeof($result_ary);$i++)
			{
				/*************** Start For Order Item Details ********************/
				$serviceorderdetailinfo=new serviceorderdetailinfo();

				$qryod="select sod.id,sod.orderid,sod.type,sod.typename,sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,REPLACE(sod.itemname,'&amp;','&') as itemname,sod.qty,sod.issued_qty AS issuedqty,sod.remain_qty as remainqty,
					sod.taxtype,sod.taxtypename,sod.sgst,sod.cgst,sod.igst,sod.price,sod.discountper,sod.discountamt,sod.taxable,sod.sgsttaxamt,sod.cgsttaxamt,sod.igsttaxamt,sod.finalprice,
					case when (sod.iscancel=1 or sod.qty<>sod.remain_qty) then '0' else '1' end AS showcancelbutton,
					case when (sod.iscancel=1) then 'Cancelled' when (isnull(sod.remain_qty,0)=0) then 'Issued' when (isnull(sod.qty,0)<>isnull(sod.remain_qty,0)) then 'Partial Issued' else '' end AS soitemstatus,
					case when (sod.iscancel=1) then '#ff000f' when (isnull(sod.remain_qty,0)=0) then '#009688' when (isnull(sod.qty,0)<>isnull(sod.remain_qty,0)) then '#5bc0de' else '' end AS soitemstatuscolor
					from tblserviceorderdetail sod 
					where sod.orderid=:orderid";
				$odparams = array(
					':orderid'=>$result_ary[$i]->getId(), 
				);
				$serviceorderdetailinfo=$DB->getmenual($qryod,$odparams,'serviceorderdetailinfo');

				$result_ary[$i]->setIsServiceOrderDetail('0');
				if(sizeof($serviceorderdetailinfo)>0)
				{
					$result_ary[$i]->setIsServiceOrderDetail('1');

					$result_ary[$i]->setServiceOrderDetailInfo($serviceorderdetailinfo);
				}
				/*************** End For Order Item Details ********************/


				/*************** Start For Order Payment Details ********************/
				$serviceorderpaymentdetailinfo=new serviceorderpaymentdetailinfo();

				$qryopd="select sopd.id,sopd.orderid,sopd.type,sopd.paytypeid,sopd.paytypename,sopd.amount 
					from tblserviceorderpaymentdetail sopd 
					where sopd.orderid=:orderid";
				$opdparams = array(
					':orderid'=>$result_ary[$i]->getId(), 
				);
				$serviceorderpaymentdetailinfo=$DB->getmenual($qryopd,$opdparams,'serviceorderpaymentdetailinfo');

				$result_ary[$i]->setIsServiceOrderPaymentDetail('0');
				if(sizeof($serviceorderpaymentdetailinfo)>0)
				{
					$result_ary[$i]->setIsServiceOrderPaymentDetail('1');

					$result_ary[$i]->setServiceOrderPaymentDetailInfo($serviceorderpaymentdetailinfo);
				}
				/*************** End For Order Payment Details ********************/
			}
			
			
			$response['serviceorderdata']=json_decode(json_encode($result_ary));
			
			
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//Cancel Service Order Data
	else if ($action=='cancelserviceorder')
	{
		$orderid=$IISMethods->sanitize($_POST['orderid']); 

		if($orderid)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select id,isnull(iscancel,0) as iscancel from tblserviceorder where id=:orderid ";
				$chkparams = array(
					':orderid'=>$orderid, 
				);
				$reschk=$DB->getmenual($qrychk,$chkparams);

				if(sizeof($reschk) > 0)
				{
					$rowchk=$reschk[0];

					if($rowchk['iscancel'] == 1)
					{
						$status=0;
						$message=$errmsg['soorderalreadycancel'];
					}
					else
					{
						$updsodata=array(		
							'[iscancel]'=>1,			
							'[cancel_uid]'=>$uid,
							'[cancel_date]'=>$datetime,						
						);
						$extrasoparams=array(
							'[id]'=>$orderid
						);
						$DB->executedata('u','tblserviceorder',$updsodata,$extrasoparams);



						$updsoddata=array(		
							'[iscancel]'=>1,			
							'[cancel_uid]'=>$uid,
							'[cancel_date]'=>$datetime,						
						);
						$extrasodparams=array(
							'[orderid]'=>$orderid
						);
						$DB->executedata('u','tblserviceorderdetail',$updsoddata,$extrasodparams);


						$status=1;
						$message=$errmsg['soordercancel'];
					}	
				}
				else
				{
					$status=0;
					$message=$errmsg['invalidsoorder'];
				}
				
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
			$message=$errmsg['reqired'];
		}
			
	}
	//Cancel Service Order Item Data
	else if ($action=='cancelserviceorderitem')
	{
		$id=$IISMethods->sanitize($_POST['id']); 
		$orderid=$IISMethods->sanitize($_POST['orderid']); 

		if($id && $orderid)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select id,isnull(iscancel,0) as iscancel from tblserviceorderdetail where id=:id and orderid=:orderid ";
				$chkparams = array(
					':id'=>$id, 
					':orderid'=>$orderid, 
				);
				$reschk=$DB->getmenual($qrychk,$chkparams);

				if(sizeof($reschk) > 0)
				{
					$rowchk=$reschk[0];

					if($rowchk['iscancel'] == 1)
					{
						$status=0;
						$message=$errmsg['soorderitemalreadycancel'];
					}
					else
					{
						$updsodata=array(		
							'[iscancel]'=>1,			
							'[cancel_uid]'=>$uid,
							'[cancel_date]'=>$datetime,						
						);
						$extrasoparams=array(
							'[id]'=>$id
						);
						$DB->executedata('u','tblserviceorderdetail',$updsodata,$extrasoparams);

						$status=1;
						$message=$errmsg['soorderitemcancel'];
					}	
				}
				else
				{
					$status=0;
					$message=$errmsg['invalidsoorder'];
				}
				
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
			$message=$errmsg['reqired'];
		}
			
	}




	
	
	

	
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  