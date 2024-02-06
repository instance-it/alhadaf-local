<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\couponmaster.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertcouponmaster')   
	{
		$formevent = $IISMethods->sanitize($_POST['formevent']);
		$discounttype = $IISMethods->sanitize($_POST['discounttype']);
		$couponcode = $IISMethods->sanitize($_POST['couponcode']);
		$couponamt = $IISMethods->sanitize($_POST['couponamt']);
		$startdate = $IISMethods->sanitize($_POST['startdate']);
		$expirydate = $IISMethods->sanitize($_POST['expirydate']);
		$minimumspend = $IISMethods->sanitize($_POST['minimumspend']);
		$maximumspend = $IISMethods->sanitize($_POST['maximumspend']);
		$limitpercoupon = $IISMethods->sanitize($_POST['limitpercoupon']);
		$limitpermember = $IISMethods->sanitize($_POST['limitpermember']);
		$statusid = $_POST['statusid'];

		$memberid = $_POST['memberid'];

		$id = $IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();
		if($discounttype && $couponcode && $couponamt && $startdate && $expirydate)
		{
			$insqry=array(
				'[discounttype]'=>$discounttype,
				'[couponcode]'=>$couponcode,
				'[couponamt]'=>$couponamt,
				'[startdate]'=>$startdate,
				'[expirydate]'=>$expirydate,
				'[minispend]'=>$minimumspend,
				'[maxspend]'=>$maximumspend,
				'[limitpercoupon]'=>$limitpercoupon,
				'[limitpermember]'=>$limitpermember,
				'[statusid]'=>$statusid,
			);
			
			if($formevent=='addright')
			{
				$qrychk="SELECT couponcode from tblcouponmaster where couponcode=:couponcode";
				$parms = array(
					':couponcode'=>$couponcode,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					$status=0;
					$message=$errmsg['isexist'];
				}
				else
				{	
					try 
					{
						$DB->begintransaction();

						$unqid = $IISMethods->generateuuid();
						$insqry['[id]']=$unqid;	
						$insqry['[timestamp]']=$datetime;
						$insqry['[entry_uid]']=$uid;	
						$insqry['[entry_date]']=$IISMethods->getdatetime();

						if(sizeof($memberid) > 0)
						{
							$insqry['isallmember'] = 0;
						}
						else
						{
							$insqry['isallmember'] = 1;
						}
						$DB->executedata('i','tblcouponmaster',$insqry,'');
						
						for($i=0;$i<sizeof($memberid);$i++)
						{
							$stid = $IISMethods->generateuuid();
							$insistore=array(	
								'[id]'=>$stid,				
								'[couponid]'=>$unqid,
								'[memberid]'=>$memberid[$i],
								'[entry_date]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tblcouponmember',$insistore,'');
						}

						$status=1;
						$message=$errmsg['insert'];
						$DB->committransaction();
					}
					catch (Exception $e) 
					{
						$DB->rollbacktransaction($e);
						$status=0;
						$message=$errmsg['dbtransactionerror'];
					}
				}
			}
			else if($formevent=='editright')
			{
				$qrychk="SELECT couponcode from tblcouponmaster where couponcode=:couponcode AND id<>:id";
				$parms = array(
					':couponcode'=>$couponcode,
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					$status=0;
					$message=$errmsg['isexist'];
				}
				else
				{
					try 
					{
						$DB->begintransaction();
						$insqry['[update_uid]']=$uid;	
						$insqry['[update_date]']=$IISMethods->getdatetime();
						if(sizeof($memberid) > 0)
						{
							$insqry['isallmember'] = 0;
						}
						else
						{
							$insqry['isallmember'] = 1;
						}
	
						$extraparams=array(
							'[id]'=>$id
						);
						$DB->executedata('u','tblcouponmaster',$insqry,$extraparams);
	
						$delparams=array(
							'[couponid]'=>$id
						);
						$DB->executedata('d','tblcouponmember','',$delparams);
						for($i=0;$i<sizeof($memberid);$i++)
						{
							$stid = $IISMethods->generateuuid();
							$insistore=array(	
								'[id]'=>$stid,				
								'[couponid]'=>$id,
								'[memberid]'=>$memberid[$i],
								'[entry_date]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tblcouponmember',$insistore,'');
						}

						$status=1;
						$message=$errmsg['update'];
						$DB->committransaction();
					}
					catch (Exception $e) 
					{
						$DB->rollbacktransaction($e);
						$status=0;
						$message=$errmsg['dbtransactionerror'];
					}
				}
			}
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deletecouponmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			$qryimg="select id from tblcouponmaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$resimg=$DB->getmenual($qryimg,$parms);
			try 
			{
				$DB->begintransaction();

				$extraparams1=array(
					'[couponid]'=>$id
				);
				$DB->executedata('d','tblcouponmember','',$extraparams1);

				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('d','tblcouponmaster','',$extraparams);

				$status=1;
				$message=$errmsg['delete'];	

				$DB->committransaction();
			}
			catch (Exception $e) 
			{
				$DB->rollbacktransaction($e);
				$status=0;
				$message=$errmsg['dbtransactionerror'];
			}

		}
		else if(sizeof($bulk)>0)
		{
			try 
			{
				$DB->begintransaction();

				$usemenu='';
				for($i=0;$i<sizeof($bulk);$i++)
				{
					$id=$bulk[$i];

					$qryimg="select id from tblcouponmaster where id=:id";
					$parms = array(
						':id'=>$id,
					);
					$resimg=$DB->getmenual($qryimg,$parms);
					
					$extraparams1=array(
						'[couponid]'=>$IISMethods->sanitize($id)
					);
					$DB->executedata('d','tblcouponmember','',$extraparams1);
					$extraparams=array(
						'[id]'=>$IISMethods->sanitize($id)
					);
					$DB->executedata('d','tblcouponmaster','',$extraparams);

				}
				$status=1;
				$message=$errmsg['delete'].' '.$usemenu;
				$DB->committransaction();
			}
			catch (Exception $e) 
			{
				$DB->rollbacktransaction($e);
				$status=0;
				$message=$errmsg['dbtransactionerror'];
			}
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='editcouponmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select gm.id,gm.couponcode,gm.couponamt,gm.discounttype,
				gm.startdate,gm.expirydate,gm.minispend,gm.maxspend,gm.limitpercoupon,gm.limitpermember,gm.statusid,
				ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), si.memberid) AS [text()] FROM tblcouponmember si WHERE CONVERT(VARCHAR(255), si.couponid)=gm.id FOR XML PATH ('')),2,100000),'') as memberid 
				from tblcouponmaster gm where gm.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);


			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['couponcode']=$IISMethods->sanitize($row['couponcode'],'OUT');
				$response['couponamt']=$IISMethods->sanitize($row['couponamt']);
				$response['discounttype']=$IISMethods->sanitize($row['discounttype']);
				$response['startdate']=$IISMethods->sanitize($row['startdate']);
				$response['expirydate']=$IISMethods->sanitize($row['expirydate']);
				$response['minispend']=$IISMethods->sanitize($row['minispend']);
				$response['maxspend']=$IISMethods->sanitize($row['maxspend']);
				$response['limitpercoupon']=$IISMethods->sanitize($row['limitpercoupon']);
				$response['limitpermember']=$IISMethods->sanitize($row['limitpermember']);
				$response['statusid']=$IISMethods->sanitize($row['statusid']);
				$response['memberid']=$IISMethods->sanitize($row['memberid']);
			}
			$status=1;
			$message=$errmsg['datafound'];
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='listcouponmaster')   
	{
		//error_reporting(1);
		$couponmasters=new couponmaster();
		$qry="SELECT gm.timestamp as primary_date,gm.id,gm.couponcode,gm.couponamt,CASE WHEN(gm.discounttype = 1)THEN 'QAR' ELSE 'Percentage' END as discounttype,
			gm.startdate,gm.expirydate,gm.minispend,gm.maxspend,gm.limitpercoupon,gm.limitpermember,gm.statusid
			from tblcouponmaster gm
			where gm.couponcode like :couponcode or gm.couponamt like :couponamt or 
			gm.startdate like :startdate or gm.expirydate like :expirydate or CASE WHEN(gm.discounttype = 1)THEN 'QAR' ELSE 'Percentage' END like :discounttype
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':couponcode'=>$filter,
			':couponamt'=>$filter,
			':startdate'=>$filter,
			':expirydate'=>$filter,
			':discounttype'=>$filter,
		);
		$couponmasters=$DB->getmenual($qry,$parms,'couponmaster');
		
		if($responsetype=='HTML')
		{
			if($couponmasters)
			{
				$i=0;
				foreach($couponmasters as $couponmaster)
				{
					$id="'".$couponmaster->id."'";
					$htmldata.='<tr data-index="'.$i.'">';
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || (sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-grid">';
						$htmldata.='<div class="dropdown table-dropdown">';
						$htmldata.='<button class="dropdown-toggle btn-tbl rounded-circle" type="button" id="tableDropdown3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>';
						$htmldata.='<div class="dropdown-menu" aria-labelledby="tableDropdown3">';

						if(((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright - all person data show,selfedit - only self person data show
						{
							$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="editdata('.$IISMethods->sanitize($id).')"><i class="bi bi-pencil"></i> Edit</a>';
						}
						if(((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright - all person data show,selfedit - only self person data show
						{
							$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="deletedata('.$IISMethods->sanitize($id).')"><i class="bi bi-trash"></i> Delete</a>';
						}

						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					if((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-check d-none">';
						$htmldata.='<div class="text-center">';
						$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($couponmaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					//$htmldata.='<td class="tbl-info"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Member" onclick="viewmember('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($couponmaster->couponcode).'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($couponmaster->couponcode).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($couponmaster->discounttype).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($couponmaster->couponamt).'</td>';
					$htmldata.='<td class="tbl-name">Start Date : <span>'.$IISMethods->sanitize($couponmaster->startdate).'</span><br/>Expiry Date : <span>'.$IISMethods->sanitize($couponmaster->expirydate).'</span></td>';
					$htmldata.='<td class="tbl-name">Maximum Spend : <span>'.$IISMethods->sanitize($couponmaster->maxspend).'</span><br/>Minimum Spend : <span>'.$IISMethods->sanitize($couponmaster->minispend).'</span></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($couponmaster->limitpercoupon).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($couponmaster->limitpermember).'</td>';
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($couponmaster->statusid==1)
						{
							$htmldata.='<td class="tbl-info text-center"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changecouponstatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-info text-center"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changecouponstatus('.$id.')">';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
					}

					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($couponmasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($couponmasters);
		}

		$common_listdata=$couponmasters;
	} 
	else if($action == 'grandom')
	{
		$key = '';
		$keys = array_merge(range(0, 9), range('a', 'z'),range('A', 'Z'));

		for ($i = 0; $i < 5; $i++) {
			$key .= $keys[array_rand($keys)];
		}


		$response['couponcode']=$IISMethods->sanitize($key);
		$status=1;
		$message=$errmsg['success'];
		
	}
	else if($action=='viewmember')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$mainqry = "SELECT * FROM tblcouponmaster WHERE id = :id";
			
			$qrychk = "select cm.*,pm.personname,pm.contact,pm.email from tblcouponmember cm
						inner join tblpersonmaster pm on pm.id = cm.memberid
						inner join tblcouponmaster c on c.id = cm.couponid
						where cm.couponid = :id";
			$parms = array(
				':id'=>$id,
			);
			$mainrow = $DB->getmenual($mainqry, $parms);
			
			$row=$DB->getmenual($qrychk,$parms);
			if($mainrow[0]['isallmember'] == 0)
			{
				if($row)
				{
					$tbldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
						$tbldata.='<div class="widget mt-10">';
							$tbldata.='<div class="widget-content row">';

								$tbldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
								$tbldata.='<div class="row1">';
								$tbldata.='<div class="table-responsive pt-2">';
								$tbldata.='<div class="col-12 p-0">';
								$tbldata.='<table id="tabviewlevesselmappricing" class="table table-bordered table-hover table-striped">';
								$tbldata.='<thead>';
								$tbldata.='<tr>';
								$tbldata.='<th>Member</th>';
								$tbldata.='<th>contact</th>';
								$tbldata.='<th>Email</th>';
								$tbldata.='</tr>';
								$tbldata.='</thead>';
								$tbldata.='<tbody id="tblviewdataprice">';
								
								$htmldata1='';
								for($i=0;$i<sizeof($row);$i++)
								{	
									$subrow=$row[$i];
									$tbldata.='<tr>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['personname']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['contact']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['email']).'</td>';
									$tbldata.='</tr>';
								}
								$tbldata.='</tbody>';
								$tbldata.='</table>';
								$tbldata.='</div>';
								$tbldata.='</div>';
								$tbldata.='</div>';
								$tbldata.='</div>';


							$tbldata.='</div>';
						$tbldata.='</div>';
					$tbldata.='</div>';
					
					$response['data']=$tbldata;
					$status=1;
					$message=$errmsg['success'];
				}
			}
			else
			{
				$tbldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
						$tbldata.='<div class="widget mt-10">';
							$tbldata.='<div class="widget-content row">';
								$tbldata.='<div class="widget-title mx-3"><b>All Members</b></div>';
							$tbldata.='</div>';
						$tbldata.='</div>';
					$tbldata.='</div>';
						
				$response['data']=$tbldata;
				$status=1;
				$message=$errmsg['success'];
			}
		}
	}
}



require_once dirname(__DIR__, 3).'\config\apifoot.php';   

?>

  