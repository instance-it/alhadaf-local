<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\paymenttypemaster.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertpaymenttypemaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);

		$type=$IISMethods->sanitize($_POST['type']);
		$displayorder=$IISMethods->sanitize($_POST['displayorder']);
		
		$img=$_FILES['paytypeimg']['name'];
		$id=$IISMethods->sanitize($_POST['id']);

		if($type && ($id || $formevent=='addright') && ($img || $formevent=='editright'))
		{
			$insqry=array(
				'[type]'=>$type,
				'[displayorder]'=>$displayorder,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT type from tblpaymenttype where type=:type or displayorder=:displayorder";
				$parms = array(
					':type'=>$type,
					':displayorder'=>$displayorder,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					$status=0;
					$message=$errmsg['isexist'];
				}
				else
				{

					if($_FILES["paytypeimg"]["type"] == "image/jpeg" || $_FILES["paytypeimg"]["type"] == "image/jpg" || $_FILES["paytypeimg"]["type"] == "image/png")
					{
						$unqid = $IISMethods->generateuuid();
						if($_FILES['paytypeimg']['name'])
						{
							$sourcePath = $_FILES['paytypeimg']['tmp_name'];
							$targetPath = $IISMethods->uploadallfiles(1,'paymenttype',$img,$sourcePath,$_FILES['paytypeimg']['type'],$unqid);
							$insqry['[image]']=$targetPath;
						}
					
						try 
						{
							$DB->begintransaction();
							$insqry['[id]']=$unqid;	
							$insqry['[isactive]']=1;	
							$insqry['timestamp']=$IISMethods->getdatetime();
							$insqry['[entry_uid]']=$uid;	
							$insqry['[entry_date]']=$IISMethods->getdatetime();

							$DB->executedata('i','tblpaymenttype',$insqry,'');

							// if($_FILES['paytypeimg']['name'])
							// {
							// 	$munqid = $IISMethods->generateuuid();
							// 	$sourcePath = $_FILES['paytypeimg']['tmp_name'];
							// 	$targetPath = $IISMethods->uploadallfiles(1,'paymenttype',$img,$sourcePath,$_FILES['paytypeimg']['type'],$munqid.'aaab');


							// 	$insqry=array(
							// 		'[id]'=>$munqid,
							// 		'[type]'=>$type,
							// 		'[displayorder]'=>$displayorder,
							// 		'[image]'=>$targetPath,
							// 	);
							// 	$DB->executedata('i','tblpaymenttype',$insqry,'');
							// }
					

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
					else
					{
						$status=0;
						$message=$errmsg['filetype'];
					}

				}
			}
			else if($formevent=='editright')
			{
				$qrychk="SELECT type from tblpaymenttype where (type=:type or displayorder=:displayorder) AND id<>:id";
				$parms = array(
					':type'=>$type,
					':displayorder'=>$displayorder,
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
					if($img)
					{
						
						if($_FILES['paytypeimg']['type'] == 'image/jpg'  || $_FILES['paytypeimg']['type'] == 'image/jpeg' || $_FILES['paytypeimg']['type'] == 'image/png')
						{
							$qryimg="select id,image from tblpaymenttype where id=:id";
							$parms = array(
								':id'=>$id,
							);
							$resimg=$DB->getmenual($qryimg,$parms);
							$row=$resimg[0];
		
							unlink($config->getImageurl().$row['image']);

							$sourcePath = $_FILES['paytypeimg']['tmp_name'];
							$targetPath = $IISMethods->uploadallfiles(1,'paymenttype',$img,$sourcePath,$_FILES['paytypeimg']['type'],$id);
							$insqry['[image]']=$targetPath;
							try 
							{
								$DB->begintransaction();
								$insqry['[update_uid]']=$uid;	
								$insqry['[update_date]']=$IISMethods->getdatetime();

								$extraparams=array(
									'[id]'=>$id
								);
								$DB->executedata('u','tblpaymenttype',$insqry,$extraparams);

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
						else
						{
							$status=0;
							$message=$errmsg['filetype'];
						}

					}
					else
					{
						try 
						{
							$DB->begintransaction();
							$insqry['[update_uid]']=$uid;	
							$insqry['[update_date]']=$IISMethods->getdatetime();
		
							$extraparams=array(
								'[id]'=>$id
							);
							$DB->executedata('u','tblpaymenttype',$insqry,$extraparams);
		
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
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deletepaymenttypemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			
			try 
			{
				$DB->begintransaction();


				$qrychk="SELECT case when(convert(varchar(50),opd.id) !='') then 1 else 0 end as tem
				from tblpaymenttype p
				left join tblstoreorderpaymentdetail opd on opd.paytypeid = p.id
				where p.id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];

				if($row['tem'] > 0)
				{
					$status=0;
					$message=$errmsg['inuse'];
				}
				else
				{

					$qryimg="select id,image from tblpaymenttype where id=:id";
					$parms = array(
						':id'=>$id,
					);
					$resimg=$DB->getmenual($qryimg,$parms);
					$row=$resimg[0];
					if($row['image'])
					{
						unlink($config->getImageurl().$row['image']);
					}

					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('d','tblpaymenttype','',$extraparams);

					$status=1;
					$message=$errmsg['delete'];	
				}	

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

					$qrychk="SELECT case when(convert(varchar(50),opd.id) !='') then 1 else 0 end as tem,p.type
					from tblpaymenttype p
					left join tblstoreorderpaymentdetail opd on opd.paytypeid = p.id
					where p.id=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['type'].",";
					}
					else
					{

						$qryimg="select id,image from tblpaymenttype where id=:id";
						$parms = array(
							':id'=>$id,
						);
						$resimg=$DB->getmenual($qryimg,$parms);
						$row=$resimg[0];
						if($row['image'])
						{
							unlink($config->getImageurl().$row['image']);
						}

						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblpaymenttype','',$extraparams);
					}	

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
	else if($action=='editpaymenttypemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,type,displayorder from tblpaymenttype where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);


			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['type']=$IISMethods->sanitize($row['type'],'OUT');
				$response['displayorder']=$IISMethods->sanitize($row['displayorder']);

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
	else if($action=='listpaymenttypemaster')   
	{
		//error_reporting(1);
		$paymenttypemasters=new paymenttypemaster();
		$qry="select p.timestamp as primary_date,p.id,p.type,ISNULL(p.displayorder,0) AS displayorder,p.image,p.isactive
			from tblpaymenttype p
			where p.type like :typefilter or ISNULL(p.displayorder,0) like :disorderfilter 
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':typefilter'=>$filter,
			':disorderfilter'=>$filter,
		);
		$paymenttypemasters=$DB->getmenual($qry,$parms,'paymenttypemaster');
		
		if($responsetype=='HTML')
		{
			if($paymenttypemasters)
			{
				$i=0;
				foreach($paymenttypemasters as $slidermaster)
				{
					$id="'".$slidermaster->id."'";
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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($slidermaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					// $htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Coupon Details" onclick="viewsliderdata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($slidermaster->slidername).'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($slidermaster->type,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($slidermaster->displayorder).'</td>';

					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($slidermaster->isactive==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changepaymenttypestatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changepaymenttypestatus('.$id.')">';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
	
					}

					$htmldata.='<td class="tbl-img">';
					if($slidermaster->image)
					{
						$htmldata.='<div class="dropdown img-dropdown dropleft">';
						$htmldata.='<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick=viewpaymenttypeimage("'.$IISMethods->sanitize($config->getImageurl().$slidermaster->image).'","'.$IISMethods->sanitize($slidermaster->type).'")><img src="'.$IISMethods->sanitize($config->getImageurl().$slidermaster->image).'" class="img-thumbnail"></a>';
						$htmldata.='<div class="dropdown-menu img-popover-content" aria-labelledby="tableDropdown3">';
						$htmldata.='<a href="javascript:void(0)" onclick=viewpaymenttypeimage("'.$IISMethods->sanitize($config->getImageurl().$slidermaster->image).'","'.$IISMethods->sanitize($slidermaster->type).'")><img src="'.$IISMethods->sanitize($config->getImageurl().$slidermaster->image).'"></a>';
						$htmldata.='</div>';
						$htmldata.='</div>';
					}
					else
					{
						$htmldata.='-';
					}
					$htmldata.='</td>';
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($paymenttypemasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($paymenttypemasters);
		}

		$common_listdata=$paymenttypemasters;
	} 
	else if($action=='changepaymenttypestatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			
			$qrychk="select isactive from tblpaymenttype where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);
			$row=$result_ary[0];
			if($row['isactive']==1)
			{
				$isactive=0;
			}
			else
			{
				$isactive=1;
				
			}
			$insqry['[isactive]']=$isactive;	
			$extraparams=array(
				'[id]'=>$id
			);
			$DB->executedata('u','tblpaymenttype',$insqry,$extraparams);
		
		
			$status=1;
			$message=$errmsg['update'];
		}
		else
		{
			$message=$errmsg['reqired'];
		}
	}
}



require_once dirname(__DIR__, 3).'\config\apifoot.php';   

?>

  