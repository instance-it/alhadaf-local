<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\itemiconmaster.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();

	if($action=='inserticon')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		
		$iconname=$IISMethods->sanitize($_POST['iconname']);
		$iconimg=$_FILES['iconimg']['name'];

		$id=$IISMethods->sanitize($_POST['id']);

		if($iconname && ($iconimg || $formevent=='editright'))
		{
			$insqry=array(
				'[iconname]'=>$iconname,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT id from tblitemiconmaster where iconname=:iconname";
				$parms = array(
					':iconname'=>$iconname,
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

						if($_FILES['iconimg']['type'] == 'image/jpg'  || $_FILES['iconimg']['type'] == 'image/jpeg' || $_FILES['iconimg']['type'] == 'image/png')
						{
							$sourcePath = $_FILES['iconimg']['tmp_name'];
							$targetPath = $IISMethods->uploadallfiles(1,'icon',$iconimg,$sourcePath,$_FILES['iconimg']['type'],$unqid);
							$insqry['[iconimg]']=$targetPath;

							$isvalidate=1;
						}
						else
						{
							$status=0;
							$message=$errmsg['filetype'];
						}

						if($isvalidate == 1)
						{
							$maxid=$DB->getitemmaxid();
							$itemno=$DB->generateitemnumber();

							$insqry['[id]']=$unqid;	
							$insqry['[timestamp]']=$IISMethods->getdatetime();
							$insqry['[entry_uid]']=$uid;	
							$insqry['[entry_date]']=$IISMethods->getdatetime();

							$DB->executedata('i','tblitemiconmaster',$insqry,'');

							$status=1;
							$message=$errmsg['insert'];
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
			}
			else if($formevent=='editright')
			{
				$qrychk="SELECT id from tblitemiconmaster where iconname=:iconname AND id<>:id";
				$parms = array(
					':iconname'=>$iconname,
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


						if($iconimg)
						{
							if($_FILES['iconimg']['type'] == 'image/jpg'  || $_FILES['iconimg']['type'] == 'image/jpeg' || $_FILES['iconimg']['type'] == 'image/png')
							{
								$qryimg="select id,iconimg from tblitemiconmaster where id=:id";
								$parms = array(
									':id'=>$id,
								);
								$resimg=$DB->getmenual($qryimg,$parms);
								$row=$resimg[0];
	
								unlink($config->getImageurl().$row['iconimg']);

								$sourcePath = $_FILES['iconimg']['tmp_name'];
								$targetPath = $IISMethods->uploadallfiles(1,'icon',$iconimg,$sourcePath,$_FILES['iconimg']['type'],$id);
								$insqry['[iconimg]']=$targetPath;
	
								$isvalidate=1;
							}
							else
							{
								$status=0;
								$message=$errmsg['filetype'];
							}
						}
						else
						{
							$isvalidate=1;
						}


						if($isvalidate == 1)
						{
							$insqry['[update_uid]']=$uid;	
							$insqry['[update_date]']=$IISMethods->getdatetime();

							$extraparams=array(
								'[id]'=>$id
							);
							$DB->executedata('u','tblitemiconmaster',$insqry,$extraparams);

							$status=1;
							$message=$errmsg['update'];
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
			}
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deleteitemiconmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{

			$qrychk="SELECT distinct case when(im.itemname !='' or convert(varchar(50),imd.id) !='') then 1 else 0 end as tem
			from tblitemiconmaster iim 
			left join tblitemmaster im on im.iconid = iim.id 
			left join tblitemdetails imd on imd.rowiconid = iim.id
			where iim.id=:id";

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
				try 
				{
					$DB->begintransaction();

					
					$qryimg="select id,iconimg from tblitemiconmaster where id=:id";
					$parms = array(
						':id'=>$id,
					);
					$resimg=$DB->getmenual($qryimg,$parms);
					$row=$resimg[0];
					if($row['iconimg'])
					{
						unlink($config->getImageurl().$row['iconimg']);
					}
		
					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('d','tblitemiconmaster','',$extraparams);


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

					$qrychk="SELECT distinct case when(im.itemname !='' or convert(varchar(50),imd.id) !='') then 1 else 0 end as tem,iim.iconname
					from tblitemiconmaster iim 
					left join tblitemmaster im on im.iconid = iim.id
					left join tblitemdetails imd on imd.rowiconid = iim.id
					where iim.id=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['iconname'].",";
					}
					else
					{
						
						$qryimg="select id,iconimg from tblitemiconmaster where id=:id";
						$parms = array(
							':id'=>$IISMethods->sanitize($id),
						);
						$resimg=$DB->getmenual($qryimg,$parms);
						$row=$resimg[0];
						if($row['iconimg'])
						{
							unlink($config->getImageurl().$row['iconimg']);
						}
			
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblitemiconmaster','',$extraparams);


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
	else if($action=='editiconmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select im.id,im.iconname from tblitemiconmaster im where im.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['iconname']=$IISMethods->sanitize($row['iconname']);
				
				$status=1;
				$message=$errmsg['datafound'];
			}
			else
			{
				$status=1;
				$message=$errmsg['nodatafound'];
			}
			
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='listitemiconmaster')   
	{
		$itemiconmasters=new itemiconmaster();
		$qry="select im.timestamp as primary_date,im.id,im.iconname,im.iconimg
			from tblitemiconmaster im 
			where im.iconname like :namefilter 
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':namefilter'=>$filter,
		);
		//echo $qry;
		//print_r($parms);
		$itemiconmasters=$DB->getmenual($qry,$parms,'itemiconmaster');
		
		if($responsetype=='HTML')
		{
			if($itemiconmasters)
			{
				$i=0;
				foreach($itemiconmasters as $iconmaster)
				{
					$id="'".$iconmaster->id."'";
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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($iconmaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($iconmaster->iconname,'OUT').'</td>';

					$htmldata.='<td class="tbl-img">';
					if($iconmaster->iconimg)
					{
						$htmldata.='<div class="dropdown img-dropdown dropleft">';
						$htmldata.='<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick=viewiconimage("'.$IISMethods->sanitize($config->getImageurl().$iconmaster->iconimg).'","'.$IISMethods->sanitize($iconmaster->iconname).'")><img src="'.$IISMethods->sanitize($config->getImageurl().$iconmaster->iconimg).'" class="img-thumbnail"></a>';
						$htmldata.='<div class="dropdown-menu img-popover-content" aria-labelledby="tableDropdown3">';
						$htmldata.='<a href="javascript:void(0)" onclick=viewiconimage("'.$IISMethods->sanitize($config->getImageurl().$iconmaster->iconimg).'","'.$IISMethods->sanitize($iconmaster->iconname).'")><img src="'.$IISMethods->sanitize($config->getImageurl().$iconmaster->iconimg).'"></a>';
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
				if($page<=0 && sizeof($itemiconmasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($itemiconmasters);
		}

		$common_listdata=$itemiconmasters;
	} 
	







}



require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  