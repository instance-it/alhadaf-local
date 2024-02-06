<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\posstoreorder.php';

// echo $isvalidUser['status'];
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	$currdate=$IISMethods->getformatcurrdate();
	

	//List Store Order Data
	if($action=='listposstoreorder')   
	{
		$fltstoreid=$IISMethods->sanitize($_POST['fltstoreid']);
		$issidebarflt=$IISMethods->sanitize($_POST['issidebarflt']);

		$fltfromdate=$IISMethods->sanitize($_POST['fltfromdate']);
		$flttodate=$IISMethods->sanitize($_POST['flttodate']);

		$posstoreorders=new posstoreorder();

		
		$qry="select o.timestamp as primary_date,o.id,o.transactionid,o.orderno,pm.personname,pm.contact,
		convert(varchar, o.timestamp,100) AS ofulldate,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,s.storename
		from tblstoreorder o 
		inner join tblpersonmaster pm on pm.id=o.uid 
		inner join tblpersonmaster pm1 on pm1.id=o.entry_uid 
		inner join tblstoremaster s on s.id=o.storeid 
		where (o.transactionid like :transactionidfilter OR o.orderno like :ordernofilter OR pm.personname like :personnamefilter OR pm.contact like :contactfilter 
		OR convert(varchar, o.timestamp,100) like :orderdatefilter OR pm1.personname like :entrypersonfilter 
		OR pm1.contact like :entrypersoncontactfilter OR s.storename like :storefilter)  ";
		$parms = array(
			':transactionidfilter'=>$filter,
			':ordernofilter'=>$filter,
			':personnamefilter'=>$filter,
			':contactfilter'=>$filter,
			':orderdatefilter'=>$filter,
			':entrypersonfilter'=>$filter,
			':entrypersoncontactfilter'=>$filter,
			':storefilter'=>$filter,
		);

		if($issidebarflt == 1)
		{
			if($fltstoreid)
			{
				$qry.=" and o.storeid like :fltstoreid";
				$parms[':fltstoreid']=$fltstoreid;  
			}	

			if($fltfromdate && $flttodate)
			{
				$qry.=" and CONVERT(date,orderdate,103) between  CONVERT(date,:fromdate,103) and  CONVERT(date,:todate,103)";
				$parms[':fromdate']=$fltfromdate; 
				$parms[':todate']=$flttodate; 
			}
		}


		if($IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==0)
		{
			$qry.=" and o.uid = :id";
			$parms[':id'] = $LoginInfo->getUid();
		}

		$qry.=" order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$posstoreorders=$DB->getmenual($qry,$parms,'posstoreorder');
		
		if($responsetype=='HTML')
		{
			if($posstoreorders)
			{
				$i=0;
				foreach($posstoreorders as $ordermaster)
				{
					$id="'".$ordermaster->id."'";
					$htmldata.='<tr data-index="'.$i.'">';
					
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->storename,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->transactionid).'</td>';
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Order Detail" onclick="viewstoreorderdata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($ordermaster->orderno).'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->ofulldate).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->personname,'OUT').' ('.$IISMethods->sanitize($ordermaster->contact).')</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->entrypersonname,'OUT').' ('.$IISMethods->sanitize($ordermaster->entrypersoncontact).')</td>';

					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($posstoreorders)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($posstoreorders);
		}

		$common_listdata=$posstoreorders;
	}  
	//View Store Order Data
	else if($action=='viewstoreorderdata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		
		$mainqry = "select o.timestamp as primary_date,o.id,o.transactionid,o.orderno,pm.personname,pm.contact,o.ordernotes,
		convert(varchar, o.timestamp,100) AS ofulldate,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,s.storename
		from tblstoreorder o 
		inner join tblstoreorderdetail od on od.orderid=o.id 
		inner join tblpersonmaster pm on pm.id=o.uid 
		inner join tblpersonmaster pm1 on pm1.id=o.entry_uid 
		inner join tblstoremaster s on s.id=o.storeid 
		where o.id = :id";
		$parms = array(
			':id'=>$id,
		);
		$vesseldetails=$DB->getmenual($mainqry,$parms);
		$row=$vesseldetails[0];

		if($responsetype=='HTML')
		{
			$htmldata="";

			$htmldata.='<div class="col-12">';
				$htmldata.='<div class="table-responsive">';
				$htmldata.='<div class="col-12 view-data-details">';
				$htmldata.='<div class="row my-3">';
				$htmldata.='<div class="col-12 col-md-8 col-lg">';
				$htmldata.='<div class="row">';
			
			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Store/Counter <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['storename'].'</b></label>';
			$htmldata.='</div></div></div>';
				
			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Transaction Id <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['transactionid'].'</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Order No <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['orderno'].'</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Order Date <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['ofulldate'].'</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Member <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['personname'].' ('.$row['contact'].')</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Entry By <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['entrypersonname'].' ('.$row['entrypersoncontact'].')</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Order Note <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['ordernotes'].'</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';										
			$htmldata.='</div>';

			

			/********************** Start For Order Item Details ********************/
			$qryod="select sod.id,sod.orderid,sod.type,sod.typename,sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname,sod.qty,
				sod.taxtype,sod.taxtypename,sod.sgst,sod.cgst,sod.igst,sod.price,sod.discountper,sod.discountamt,sod.taxable,sod.sgsttaxamt,sod.cgsttaxamt,sod.igsttaxamt,sod.finalprice
				from tblstoreorderdetail sod 
				where sod.orderid=:orderid";
			$parms = array(
				':orderid'=>$id,
			);
			$result_ary=$DB->getmenual($qryod,$parms);
			if(sizeof($result_ary) > 0)
			{
				$htmldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
					$htmldata.='<div class="widget mt-10">';
						$htmldata.='<div class="widget-content row">';

							$htmldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
							$htmldata.='<div class="row1">';
							$htmldata.='<div class="table-responsive pt-2">';
							$htmldata.='<h6>Item Details</h6>';
							$htmldata.='<div class="col-12 p-0">';
							$htmldata.='<table id="tabview" class="table table-bordered table-hover table-striped">';
							$htmldata.='<thead>';
							$htmldata.='<tr>';
							$htmldata.='<th>Item</th>';
							$htmldata.='<th style="text-align: center;">Type</th>';
							$htmldata.='<th style="text-align: center;">Qty</th>';
							// $htmldata.='<th style="text-align: right;">Price</th>';
							// $htmldata.='<th style="text-align: right;">Discount</th>';
							// $htmldata.='<th style="text-align: right;">Taxable</th>';
							// $htmldata.='<th style="text-align: right;">VAT</th>';
							// $htmldata.='<th style="text-align: right;">Amount</th>';
							$htmldata.='</tr>';
							$htmldata.='</thead>';
							$htmldata.='<tbody id="tblviewdata">';
							
							$totalqty=0;
							$totalprice=0;
							$totaldiscountamt=0;
							$totaltaxable=0;
							$totaltaxamt=0;
							$totalfinalprice=0;
							for($i=0;$i<sizeof($result_ary);$i++)
							{	
								$subrow=$result_ary[$i];
								
								$htmldata.='<tr>';
								$htmldata.='<td align="left">'.$IISMethods->sanitize($subrow['itemname'],'OUT').'<br><small>('.$IISMethods->sanitize($subrow['category']).')</td>';
								if($subrow['typename'])
								{
									$htmldata.='<td align="center">'.$IISMethods->sanitize($subrow['typename']).'</td>';
								}
								else
								{
									$htmldata.='<td align="center">-</td>';
								}
								
								$htmldata.='<td align="center">'.$IISMethods->sanitize($subrow['qty']).'</td>';
								// $htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['price'])).'</td>';
								// $htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['discountamt'])).'</td>';
								// $htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['taxable'])).'</td>';
								// $htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['igsttaxamt'])).'<br><small>('.$IISMethods->sanitize($IISMethods->ind_format($subrow['igst'])).'%)</small></td>';
								// $htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['finalprice'])).'</td>';
								$htmldata.='</tr>';

								$totalqty+=$subrow['qty'];
								$totalprice+=$subrow['price'];
								$totaldiscountamt+=$subrow['discountamt'];
								$totaltaxable+=$subrow['taxable'];
								$totaltaxamt+=$subrow['igsttaxamt'];
								$totalfinalprice+=$subrow['finalprice'];
							}

							$htmldata.='<tr>';
							$htmldata.='<td align="right" colspan="2"><b>Total</b></td>';
							$htmldata.='<td align="center"><b>'.$totalqty.'</b></td>';
							// $htmldata.='<td align="right"><b>'.$totalprice.'</b></td>';
							// $htmldata.='<td align="right"><b>'.$totaldiscountamt.'</b></td>';
							// $htmldata.='<td align="right"><b>'.$totaltaxable.'</b></td>';
							// $htmldata.='<td align="right"><b>'.$totaltaxamt.'</b></td>';
							// $htmldata.='<td align="right"><b>'.$totalfinalprice.'</b></td>';
							$htmldata.='</tr>';
							
							$htmldata.='</tbody>';
							$htmldata.='</table>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';


						$htmldata.='</div>';
					$htmldata.='</div>';
				$htmldata.='</div>';
				
			}
			/********************** End For Order Item Details ********************/




			/********************** Start For Order Payment Details ********************/
			/*
			$qryopd="select sopd.id,sopd.orderid,sopd.type,sopd.paytypeid,sopd.paytypename,sopd.amount 
				from tblstoreorderpaymentdetail sopd 
				where sopd.orderid=:orderid";	
			$parms = array(
				':orderid'=>$id,
			);
			$result_ary=$DB->getmenual($qryopd,$parms);
			if(sizeof($result_ary) > 0)
			{
				$htmldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
					$htmldata.='<div class="widget mt-10">';
						$htmldata.='<div class="widget-content row">';

							$htmldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
							$htmldata.='<div class="row1">';
							$htmldata.='<div class="table-responsive pt-2">';
							$htmldata.='<h6>Payment Details</h6>';
							$htmldata.='<div class="col-12 p-0">';
							$htmldata.='<table id="tabview" class="table table-bordered table-hover table-striped">';
							$htmldata.='<thead>';
							$htmldata.='<tr>';
							$htmldata.='<th>Payment Type</th>';
							$htmldata.='<th style="text-align: right;">Amount</th>';
							$htmldata.='</tr>';
							$htmldata.='</thead>';
							$htmldata.='<tbody id="tblviewdata">';
							
							
							$totalamount=0;
							for($i=0;$i<sizeof($result_ary);$i++)
							{	
								$subrow=$result_ary[$i];
								
								$htmldata.='<tr>';
								$htmldata.='<td align="left">'.$IISMethods->sanitize($subrow['paytypename']).'</td>';
								$htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['amount'])).'</td>';
								$htmldata.='</tr>';

								$totalamount+=$subrow['amount'];
							}

							$htmldata.='<tr>';
							$htmldata.='<td align="right"></td>';
							$htmldata.='<td align="right"><b>Total : '.$IISMethods->ind_format($totalamount).'</b></td>';
							$htmldata.='</tr>';
							
							$htmldata.='</tbody>';
							$htmldata.='</table>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';


						$htmldata.='</div>';
					$htmldata.='</div>';
				$htmldata.='</div>';
				
			}
			*/
			/********************** End For Order Payment Details ********************/


			
			
			$status=1;
			$message=$errmsg['success'];

			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($vesseldetails);
		}

	}
	




}

require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  