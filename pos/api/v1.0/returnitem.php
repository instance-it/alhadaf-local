<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\returnitem.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();
	$currdate=$IISMethods->getformatcurrdate();


	//List Return Order member
	if($action == 'listreturnordermember')
	{	
		$storeid =$IISMethods->sanitize($_POST['storeid']);
		$listreturnordermember=new listreturnordermember();

		// $qry="SELECT distinct pm.id,concat(pm.personname,' (',pm.contact,')') as name,pm.personname,pm.contact
		// 	from tblpersonmaster pm 
		// 	inner join tblpersonutype pu on pu.pid = pm.id
		// 	where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND pu.utypeid=:memberutypeid order by pm.personname";

		$qry="select distinct pm.id,concat(pm.personname,' (',pm.contact,')') as name,pm.personname,pm.contact
			from tblstoreorder so 
			inner join tblstoreorderdetail sod on sod.orderid=so.id 
			inner join tblpersonmaster pm on pm.id=so.uid 
			where isnull(so.status,0)=1 and isnull(so.iscancel,0)=0 and isnull(sod.isreturned,0)=0 and so.storeid=:storeid and (sod.catid=:returnablecatid or sod.catid=:consumablecatid)";	
		$params = array(
			':returnablecatid'=>$config->getDefaultCatReturnableId(),
			':consumablecatid'=>$config->getDefaultCatConsumableId(),
			':storeid'=>$storeid,
		);
		$listreturnordermember=$DB->getmenual($qry,$params,'listreturnordermember');

		$response['ismemberdata']=0;
		if($listreturnordermember)
		{
			$response['ismemberdata']=1;
			$response['memberdata']=$listreturnordermember;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Member Wise Returnable Item 
	else if($action == 'listreturnableitem')
	{
		$storeid =$IISMethods->sanitize($_POST['storeid']);
		$memberid =$IISMethods->sanitize($_POST['memberid']);
		$date =$IISMethods->sanitize($_POST['date']);

		if($date == '')
		{
			$date=$IISMethods->getcurrdate();
		}

		if($storeid && $memberid && $date)
		{
			$returnitemdetail = new returnitemdetail();
			$qryitem="select distinct sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname,sum(sod.remainqty) as qty,sum(sod.remainqty) as oldqty
				from tblstoreorder so 
				inner join tblstoreorderdetail sod on sod.orderid=so.id 
				where isnull(so.status,0)=1 and isnull(so.iscancel,0)=0 and isnull(sod.isreturned,0)=0 and isnull(sod.remainqty,0)>0 and so.uid=:memberid and so.storeid=:storeid and (sod.catid=:returnablecatid or sod.catid=:consumablecatid) and convert(date,so.timestamp,120) <= CONVERT(date,:date,103) 
				group by sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname";	
			$itemparams = array(
				':memberid'=>$memberid, 
				':storeid'=>$storeid,
				':returnablecatid'=>$config->getDefaultCatReturnableId(),
				':consumablecatid'=>$config->getDefaultCatConsumableId(),
				':date'=>$date,
			);
			$returnitemdetail=$DB->getmenual($qryitem,$itemparams,'returnitemdetail');
			
			$response['isreturnitemdata']=0;
			if(sizeof($returnitemdetail)>0)
			{
				$response['isreturnitemdata']=1;
				$response['returnitemdata']=$returnitemdetail;
				
				$status = 1;
				$message = $errmsg['success'];
			}
			else
			{
				$status=0;
				$message=$errmsg['nodatafound'];
			}
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
		
	}
	//Insert Store Return Item Data
	else if ($action=='insertreturnitemdata')
	{
		$storeid=$IISMethods->sanitize($_POST['storeid']);   
		$memberid=$IISMethods->sanitize($_POST['memberid']);   
		$date =$IISMethods->sanitize($_POST['date']);

		if($date == '')
		{
			$date=$IISMethods->getcurrdate();
		}

		$comment =$IISMethods->sanitize($_POST['comment']);

		$itemdataarr=$_POST['itemdata'];
		$decodejson_items=json_decode($itemdataarr,TRUE);

		$seesionarray=json_encode($_POST,true);

		if($storeid && $memberid && $date && sizeof($decodejson_items) > 0)
		{
			try 
			{
				$DB->begintransaction();
	
				$datetime = $IISMethods->getdatetime();
				$order_unqid = $IISMethods->generateuuid();
				$order_date = $IISMethods->getcurrdate();
				
	
				$insqry['[id]']=$order_unqid;
				$insqry['[storeid]']=$storeid;
				$insqry['[memberid]']=$memberid;
				$insqry['[date]']=$date;
				$insqry['[orderdate]']=$order_date;
				$insqry['[comment]']=$comment;
				$insqry['[sessionarray]']=$seesionarray;
				$insqry['[platform]']=$platform;
				$insqry['[timestamp]']=$datetime;
				$insqry['[entry_uid]']=$uid;	
				$insqry['[entry_date]']=$datetime;

				$DB->executedata('i','tblstorereturnorder',$insqry,'');
					
	
				// Order DETAILS
				if(sizeof($decodejson_items)>0)
				{
					foreach($decodejson_items as $k=>$v)
					{
						$od_id = $IISMethods->generateuuid();
						
						$od_insqry['[id]']=$od_id;
						$od_insqry['[sorid]']=$order_unqid;
						$od_insqry['[catid]']=$v['catid'];
						$od_insqry['[category]']=$v['category'];
						$od_insqry['[subcatid]']=$v['subcatid'];
						$od_insqry['[subcategory]']=$v['subcategory'];
						$od_insqry['[itemid]']=$v['itemid'];
						$od_insqry['[itemname]']=$v['itemname'];
						$od_insqry['[qty]']=$v['qty'];
						$od_insqry['[timestamp]']=$datetime;
						
						$DB->executedata('i','tblstorereturnorderdetail',$od_insqry,'');


						/************** Start For Update Qty in Store Order Items ****************/
						$qrysod="select distinct sod.id,isnull(sod.sodid,:uniqueidentifier) as sodid,sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname,isnull(sod.qty,0) as qty,isnull(sod.returnqty,0) as returnqty,isnull(sod.remainqty,0) as remainqty,sod.timestamp
							from tblstoreorder so 
							inner join tblstoreorderdetail sod on sod.orderid=so.id 
							where isnull(so.status,0)=1 and isnull(so.iscancel,0)=0 and isnull(sod.isreturned,0)=0 and so.uid=:memberid and so.storeid=:storeid and (sod.catid=:returnablecatid or sod.catid=:consumablecatid) and 
							convert(date,so.timestamp,120) <= CONVERT(date,:date,103) and sod.itemid=:itemid 
							order by sod.timestamp";
						$sodparams = array(
							':memberid'=>$memberid, 
							':storeid'=>$storeid,
							':returnablecatid'=>$config->getDefaultCatReturnableId(),
							':consumablecatid'=>$config->getDefaultCatConsumableId(),
							':date'=>$date,
							':itemid'=>$v['itemid'],
							':uniqueidentifier'=>$mssqldefval['uniqueidentifier'],
						);
						$ressod=$DB->getmenual($qrysod,$sodparams);
						if(sizeof($ressod) > 0)
						{
							$remain_qty=$v['qty'];
							for($j=0;$j<sizeof($ressod);$j++)
							{
								$rowsod=$ressod[$j];

								$sod_id=$rowsod['sodid'];  //Service Order Detail ID

								$sod_qty=$rowsod['qty'];
								$sod_returnqty=$rowsod['returnqty'];
								$sod_remainqty=$rowsod['remainqty'];


								if($remain_qty >= $sod_remainqty && $remain_qty > 0)
								{
									$returnqty=$sod_returnqty+$sod_remainqty;
									$remainqty=0;
									
									


									//Update Consumable Item Qty in Service Order
									if($rowsod['catid'] == $config->getDefaultCatConsumableId())
									{
										$qrychkso="SELECT isnull(return_qty,0) as return_qty from tblserviceorderdetail where id=:sodid";
										$chksoparms = array(
											':sodid'=>$sod_id,
										);
										$reschkso=$DB->getmenual($qrychkso,$chksoparms);
										$numchkso=sizeof($reschkso);

										if($numchkso > 0)
										{
											$rowchkso=$reschkso[0];

											//$new_return_soqty=$rowchkso['return_qty']+$returnqty;
											$new_return_soqty=$rowchkso['return_qty']+$sod_remainqty;


											$updsodqry=array(			
												'[return_qty]'=>$new_return_soqty,					
											);
											$extrasodparams=array(
												'[id]'=>$sod_id
											);
											$DB->executedata('u','tblserviceorderdetail',$updsodqry,$extrasodparams);

											
											if($config->getIsAccessSAP() == 1)
											{
												//Update Store Return Order Stock in SAP Stock Deduct (HaNa DB)
												//$DB->SAPUpdateReturnItemStockData($SubDB,$sod_id,$new_return_soqty);
											}
										}
									}



									$remain_qty=$remain_qty-$sod_remainqty;

									$updsodqry=array(		
										'[isreturned]'=>1,			
										'[returnqty]'=>$returnqty,
										'[remainqty]'=>$remainqty,						
									);
									$extrasodparams=array(
										'[id]'=>$rowsod['id']
									);
									$DB->executedata('u','tblstoreorderdetail',$updsodqry,$extrasodparams);

								}
								else if($remain_qty < $sod_remainqty && $remain_qty > 0)
								{	
									$returnqty=$sod_returnqty+$remain_qty;
									$remainqty=$sod_remainqty-$remain_qty;
									
									


									//Update Consumable Item Qty in Service Order
									if($rowsod['catid'] == $config->getDefaultCatConsumableId())
									{
										$qrychkso="SELECT isnull(return_qty,0) as return_qty from tblserviceorderdetail where id=:sodid";
										$chksoparms = array(
											':sodid'=>$sod_id,
										);
										$reschkso=$DB->getmenual($qrychkso,$chksoparms);
										$numchkso=sizeof($reschkso);

										if($numchkso > 0)
										{
											$rowchkso=$reschkso[0];

											//$new_return_soqty=$rowchkso['return_qty']+$returnqty;
											$new_return_soqty=$rowchkso['return_qty']+$remain_qty;


											$updsodqry=array(			
												'[return_qty]'=>$new_return_soqty,					
											);
											$extrasodparams=array(
												'[id]'=>$sod_id
											);
											$DB->executedata('u','tblserviceorderdetail',$updsodqry,$extrasodparams);


											if($config->getIsAccessSAP() == 1)
											{
												//Update Store Return Order Stock in SAP Stock Deduct (HaNa DB)
												//$DB->SAPUpdateReturnItemStockData($SubDB,$sod_id,$new_return_soqty);
											}
										}
									}



									$remain_qty=0;

									$isreturned=0;
									if($remainqty == 0)
									{
										$isreturned=1;
									}

									$updsodqry=array(	
										'[isreturned]'=>$isreturned,					
										'[returnqty]'=>$returnqty,
										'[remainqty]'=>$remainqty,						
									);
									$extrasodparams=array(
										'[id]'=>$rowsod['id']
									);
									$DB->executedata('u','tblstoreorderdetail',$updsodqry,$extrasodparams);


									break;
								}	

							}

						}
						/************** End For Update Qty in Store Order Items ****************/
					}
				}


				if($config->getIsAccessSAP() == 1)
				{
					//Insert Issue Item Data in SAP AR Invoice Return (HaNa DB)
					$DB->SAPInsertARInvoiceReturnData($SubDB,$order_unqid); 
				}


				$status=1;
				$message=$errmsg['returnitemsuccess'];
				
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
	//List Store Order Return History
	else if($action == 'listreturnstoreorderhistory')
	{
		$storeid=$IISMethods->sanitize($_POST['storeid']); 
		$fltmemberid =$IISMethods->sanitize($_POST['fltmemberid']);
		$fltfromdate=$IISMethods->sanitize($_POST['fltfromdate']);  
		$flttodate=$IISMethods->sanitize($_POST['flttodate']);  

		$listreturnorderhistory=new listreturnorderhistory();
		
		$qry = "select distinct sro.id,sro.timestamp,sro.storeid,sro.memberid,sro.orderdate,isnull(sro.comment,'') as comment,
			convert(varchar, sro.timestamp,100) AS ofulldate,pm.personname as membername,pm.contact as membercontact,
			pm1.personname as entrypersonname,pm1.contact as entrypersoncontact 
			from tblstorereturnorder sro 
			inner join tblpersonmaster pm on pm.id=sro.memberid 
			inner join tblpersonmaster pm1 on pm1.id=sro.entry_uid 
			where sro.storeid=:storeid  ";
		$parms = array(
			':storeid'=>$storeid,
		);
		if($fltmemberid != '%' && $fltmemberid != '')
		{
			$qry.=" and sro.memberid LIKE :fltmemberid ";
			$parms[':fltmemberid']=$fltmemberid;
		}

		if($fltfromdate && $flttodate)
		{
			$qry.=" and CONVERT(date,sro.orderdate,103) between  CONVERT(date,:fromdate,103) and  CONVERT(date,:todate,103) ";
			$parms[':fromdate']=$fltfromdate; 
			$parms[':todate']=$flttodate; 
		}
		
		$qry.=" ORDER BY sro.timestamp DESC offset $start rows fetch next $per_page rows only";
		$listreturnorderhistory=$DB->getmenual($qry,$parms,'listreturnorderhistory');
		
		$response['isreturnorderdata']=0;
		if($listreturnorderhistory)
		{	
			
			$response['isreturnorderdata']=1;
			$response['returnorderdata']=$listreturnorderhistory;
			
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
	//List Store Order Return Detail
	else if($action == 'listreturnorderdetail')
	{
		$orderid=$IISMethods->sanitize($_POST['orderid']); 
		$listreturnorderhistory=new listreturnorderhistory();
		

		$qry = "select distinct sro.id,sro.timestamp,sro.storeid,sro.memberid,sro.orderdate,isnull(sro.comment,'') as comment,
			convert(varchar, sro.timestamp,100) AS ofulldate,pm.personname as membername,pm.contact as membercontact,
			pm1.personname as entrypersonname,pm1.contact as entrypersoncontact 
			from tblstorereturnorder sro 
			inner join tblpersonmaster pm on pm.id=sro.memberid 
			inner join tblpersonmaster pm1 on pm1.id=sro.entry_uid 
			where sro.id=:orderid  ";
		$parms = array(
			':orderid'=>$orderid,
		);	
		$result_ary=$DB->getmenual($qry,$parms,'listreturnorderhistory');
		
		$response['isreturnorderdata']=0;
		if(sizeof($result_ary) > 0)
		{	
			$response['isreturnorderdata']=1;

			for($i=0;$i<sizeof($result_ary);$i++)
			{
				/*************** Start For Return Order Item Details ********************/
				$returnorderdetailinfo=new returnorderdetailinfo();

				$qryod="select sod.id,sod.sorid as orderid,sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname,sod.qty
					from tblstorereturnorderdetail sod 
					where sod.sorid=:orderid order by sod.category";
				$odparams = array(
					':orderid'=>$result_ary[$i]->getId(), 
				);
				$returnorderdetailinfo=$DB->getmenual($qryod,$odparams,'returnorderdetailinfo');

				$result_ary[$i]->setIsReturnOrderDetail('0');
				if(sizeof($returnorderdetailinfo)>0)
				{
					$result_ary[$i]->setIsReturnOrderDetail('1');

					$result_ary[$i]->setReturnOrderDetailInfo($returnorderdetailinfo);
				}
				/*************** End For Return Order Item Details ********************/

			}
			
			
			$response['returnorderdata']=json_decode(json_encode($result_ary));
			
			
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

  