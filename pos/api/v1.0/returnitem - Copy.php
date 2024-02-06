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

		$qry="SELECT distinct pm.id,concat(pm.personname,' (',pm.contact,')') as name,pm.personname,pm.contact
			from tblpersonmaster pm 
			inner join tblpersonutype pu on pu.pid = pm.id
			where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND pu.utypeid=:memberutypeid order by pm.personname";

		$qry="select distinct pm.id,concat(pm.personname,' (',pm.contact,')') as name,pm.personname,pm.contact
			from tblstoreorder so 
			inner join tblstoreorderdetail sod on sod.orderid=so.id 
			inner join tblpersonmaster pm on pm.id=so.uid 
			where isnull(so.status,0)=1 and isnull(so.iscancel,0)=0 and isnull(sod.isreturned,0)=0 and so.storeid=:storeid and sod.catid=:returnablecatid";	
		$params = array(
			':returnablecatid'=>$config->getDefaultCatReturnableId(),
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
			$qryitem="select distinct sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname,sum(sod.qty-sod.returnqty) as qty
				from tblstoreorder so 
				inner join tblstoreorderdetail sod on sod.orderid=so.id 
				where isnull(so.status,0)=1 and isnull(so.iscancel,0)=0 and isnull(sod.isreturned,0)=0 and so.uid=:memberid and so.storeid=:storeid and sod.catid=:returnablecatid and convert(date,so.timestamp,120)=CONVERT(date,:date,103) 
				group by sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname";	
			$itemparams = array(
				':memberid'=>$memberid, 
				':storeid'=>$storeid,
				':returnablecatid'=>$config->getDefaultCatReturnableId(),
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
	//List Member Wise Returnable Item 
	else if($action == 'listreturnableitem_old')
	{
		$storeid =$IISMethods->sanitize($_POST['storeid']);
		$date =$IISMethods->sanitize($_POST['date']);

		if($date == '')
		{
			$date=$IISMethods->getcurrdate();
		}

		if($storeid)
		{
			$memberdata=new listmemberdata();
			$qry="select distinct pm.id,pm.personname as membername,pm.contact as membercontact,pm.email as memberemail,
				case when (isnull(pm.profileimg,'') = '') then :defualtmemberimageurl else concat(:imageurl,pm.profileimg) end as profileimg 
				from tblstoreorder so 
				inner join tblstoreorderdetail sod on sod.orderid=so.id 
				inner join tblpersonmaster pm on pm.id=so.uid 
				where isnull(so.status,0)=1 and isnull(so.iscancel,0)=0 and isnull(sod.isreturned,0)=0 and so.storeid=:storeid and sod.catid=:returnablecatid and convert(date,so.timestamp,120)=CONVERT(date,:date,103)";
			$parms = array(
				':returnablecatid'=>$config->getDefaultCatReturnableId(),
				':storeid'=>$storeid,
				':date'=>$date,
				':defualtmemberimageurl'=>$config->getDefualtMemberImageurl(),
				':imageurl'=>$imgpath,
			);
			$memberdata=$DB->getmenual($qry,$parms,'listmemberdata');
			
			$response['ismemberdata']=0;
			//$memberdata[0]->setIsReturnItemDetail(0);
			if(sizeof($memberdata)>0)
			{
				$response['ismemberdata']=1;
				//$memberdata[0]->setIsReturnItemDetail(1);


				for($j=0;$j<sizeof($memberdata);$j++)
				{
					$returnitemdetail = new returnitemdetail();
					$qryitem="select distinct sod.orderid as soid,sod.id as sodid,sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname,sod.qty 
						from tblstoreorder so 
						inner join tblstoreorderdetail sod on sod.orderid=so.id 
						where isnull(so.status,0)=1 and isnull(so.iscancel,0)=0 and isnull(sod.isreturned,0)=0 and so.uid=:memberid and so.storeid=:storeid and sod.catid=:returnablecatid and convert(date,so.timestamp,120)=CONVERT(date,:date,103)";	
					$itemparams = array(
						':memberid'=>$memberdata[0]->getId(), 
						':returnablecatid'=>$config->getDefaultCatReturnableId(),
						':storeid'=>$storeid,
						':date'=>$date,
					);
					$returnitemdetail=$DB->getmenual($qryitem,$itemparams,'returnitemdetail');

					$memberdata[$j]->setIsReturnItemDetail(0);
					if(sizeof($returnitemdetail)>0)
					{
						$memberdata[$j]->setIsReturnItemDetail(1);
						$memberdata[$j]->setReturnItemDetail($returnitemdetail);
					}
				}	

				
				$response['memberdata']=$memberdata;
				
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

		$itemdataarr=$_POST['itemdata'];
		$decodejson_items=json_decode($itemdataarr,TRUE);

		$seesionarray=json_encode($_POST,true);

		if(sizeof($decodejson_items) > 0)
		{
			try 
			{
				$DB->begintransaction();
	
				$datetime = $IISMethods->getdatetime();
				$order_unqid = $IISMethods->generateuuid();
				$order_date = $IISMethods->getcurrdate();
				
	
				$insqry['[id]']=$order_unqid;
				$insqry['[storeid]']=$storeid;
				$insqry['[uid]']=$memberid;
				$insqry['[orderdate]']=$order_date;
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
						
						$od_insqry['[soid]']=$v['soid'];
						$od_insqry['[sodid]']=$v['sodid'];
						$od_insqry['[catid]']=$v['catid'];
						$od_insqry['[category]']=$v['category'];
						$od_insqry['[subcatid]']=$v['subcatid'];
						$od_insqry['[subcategory]']=$v['subcategory'];
						$od_insqry['[itemid]']=$v['itemid'];
						$od_insqry['[itemname]']=$v['itemname'];
						$od_insqry['[qty]']=$v['qty'];
						$od_insqry['[timestamp]']=$datetime;
						
						$DB->executedata('i','tblstorereturnorderdetail',$od_insqry,'');


						/************** Start For Update Qty in Mship Order ****************/
						$qrysod="select sod.id from tblstoreorderdetail sod where sod.id=:sodid";	
						$sodparams = array(
							':sodid'=>$v['sodid'], 
						);
						$ressod=$DB->getmenual($qrysod,$sodparams);
						if(sizeof($ressod) > 0)
						{
							$rowsod=$ressod[0];

							$updsodqry=array(					
								'[isreturned]'=>1,
								'[return_uid]'=>$uid,		
								'[return_date]'=>$datetime,					
							);
							$extrasodparams=array(
								'[id]'=>$v['sodid']
							);
							$DB->executedata('u','tblstoreorderdetail',$updsodqry,$extrasodparams);

						}
						/************** End For Update Qty in Mship Order ****************/
					}
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
			
	}
	
	
	

	
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  