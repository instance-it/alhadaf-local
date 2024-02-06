<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\posstoreorderreturnitem.php';

// echo $isvalidUser['status'];
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	$currdate=$IISMethods->getformatcurrdate();
	

	//List Store Order Return Item Data
	if($action=='listposstoreorderreturnitem')   
	{
		$issidebarflt=$IISMethods->sanitize($_POST['issidebarflt']);
		$fltstoreid=$IISMethods->sanitize($_POST['fltstoreid']);
		$fltmemberid=$IISMethods->sanitize($_POST['fltmemberid']);

		$fltfromdate=$IISMethods->sanitize($_POST['fltfromdate']);
		$flttodate=$IISMethods->sanitize($_POST['flttodate']);

		$posstoreorderreturns=new posstoreorderreturn();

		$qry="select sro.timestamp as primary_date,sro.id,pm.personname,pm.contact,
		convert(varchar, sro.timestamp,100) AS ofulldate,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,s.storename
		from tblstorereturnorder sro 
		inner join tblpersonmaster pm on pm.id=sro.memberid 
		inner join tblpersonmaster pm1 on pm1.id=sro.entry_uid 
		inner join tblstoremaster s on s.id=sro.storeid 
		where (pm.personname like :personnamefilter OR pm.contact like :contactfilter OR convert(varchar, sro.timestamp,100) like :orderdatefilter OR 
		pm1.personname like :entrypersonfilter OR pm1.contact like :entrypersoncontactfilter OR s.storename like :storefilter)  ";
		$parms = array(
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
				$qry.=" and sro.storeid like :fltstoreid";
				$parms[':fltstoreid']=$fltstoreid;  
			}	

			if($fltmemberid)
			{
				$qry.=" and sro.memberid like :fltmemberid";
				$parms[':fltmemberid']=$fltmemberid;  
			}

			if($fltfromdate && $flttodate)
			{
				$qry.=" and CONVERT(date,sro.orderdate,103) between  CONVERT(date,:fromdate,103) and  CONVERT(date,:todate,103)";
				$parms[':fromdate']=$fltfromdate; 
				$parms[':todate']=$flttodate; 
			}
		}

		$qry.=" order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		//echo $qry;
		//print_r($parms);
		$posstoreorderreturns=$DB->getmenual($qry,$parms,'posstoreorderreturn');
		
		if($responsetype=='HTML')
		{
			if($posstoreorderreturns)
			{
				$i=0;
				foreach($posstoreorderreturns as $ordermaster)
				{
					$id="'".$ordermaster->id."'";
					$htmldata.='<tr data-index="'.$i.'">';
					
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->storename,'OUT').'</td>';
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Return Item Detail" onclick="viewreturnorderdata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($ordermaster->personname).' ('.$IISMethods->sanitize($ordermaster->contact).')</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->ofulldate).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->entrypersonname,'OUT').' ('.$IISMethods->sanitize($ordermaster->entrypersoncontact).')</td>';
					

					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($posstoreorderreturns)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($posstoreorderreturns);
		}

		$common_listdata=$posstoreorderreturns;
	}  
	//View Store Order Return Data
	else if($action=='viewreturnorderdata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);

		$mainqry = "select o.timestamp as primary_date,o.id,pm.personname,pm.contact,isnull(o.comment,'') as comment,
		convert(varchar, o.timestamp,100) AS ofulldate,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,s.storename
		from tblstorereturnorder o 
		inner join tblstorereturnorderdetail od on od.sorid=o.id 
		inner join tblpersonmaster pm on pm.id=o.memberid 
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
			$htmldata.='<label>Return By <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['entrypersonname'].' ('.$row['entrypersoncontact'].')</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Comment <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['comment'].'</b></label>';
			$htmldata.='</div></div></div>';

			

			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';										
			$htmldata.='</div>';

			

			/********************** Start For Order Return Item Details ********************/
			$qryod="select sod.id,sod.sorid,sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname,sod.qty
				from tblstorereturnorderdetail sod 
				where sod.sorid=:orderid";
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
							$htmldata.='<h6>Return Item Details</h6>';
							$htmldata.='<div class="col-12 p-0">';
							$htmldata.='<table id="tabview" class="table table-bordered table-hover table-striped">';
							$htmldata.='<thead>';
							$htmldata.='<tr>';
							$htmldata.='<th>Item</th>';
							$htmldata.='<th style="text-align: center;">Qty</th>';
							$htmldata.='</tr>';
							$htmldata.='</thead>';
							$htmldata.='<tbody id="tblviewdata">';
							
							$totalqty=0;
							for($i=0;$i<sizeof($result_ary);$i++)
							{	
								$subrow=$result_ary[$i];
								
								$htmldata.='<tr>';
								$htmldata.='<td align="left">'.$IISMethods->sanitize($subrow['itemname'],'OUT').'</td>';
								$htmldata.='<td align="center">'.$IISMethods->sanitize($subrow['qty']).'</td>';
								$htmldata.='</tr>';

								$totalqty+=$subrow['qty'];
							}

							// $htmldata.='<tr>';
							// $htmldata.='<td align="right"><b></b></td>';
							// $htmldata.='<td align="center"><b>Total : '.$totalqty.' Qty</b></td>';
							// $htmldata.='</tr>';
							
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
			/********************** End For Order Return Item Details ********************/


			
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

  