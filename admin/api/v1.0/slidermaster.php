<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\slidermaster.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertslidermaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$title=$IISMethods->sanitize($_POST['title']);
		$displayorder=$IISMethods->sanitize($_POST['displayorder']);
		$img=$_FILES['img']['name'];
		$id=$IISMethods->sanitize($_POST['id']);

		if($title && ($img || $formevent=='editright'))
		{
			$insqry=array(
				'[title]'=>$title,
				'[displayorder]'=>$displayorder,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT title from tblslidermaster where title=:title or displayorder=:displayorder";
				$parms = array(
					':title'=>$title,
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
					if($_FILES["img"]["type"] == "image/jpeg" || $_FILES["img"]["type"] == "image/jpg" || $_FILES["img"]["type"] == "image/png")
					{
						
						// if($_FILES['img']['size'] <= $config->getWebsitefilesize())
						// {
							$unqid = $IISMethods->generateuuid();
							if($_FILES['img']['name'])
							{
								$sourcePath = $_FILES['img']['tmp_name'];
								$targetPath = $IISMethods->uploadallfiles(1,'slider',$img,$sourcePath,$_FILES['img']['type'],$unqid);
								$insqry['[image]']=$targetPath;
							}
						
							try 
							{
								$DB->begintransaction();
								$insqry['[id]']=$unqid;	
								$insqry['[entry_uid]']=$uid;	
								$insqry['[entry_date]']=$IISMethods->getdatetime();

								$DB->executedata('i','tblslidermaster',$insqry,'');

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
						// }
						// else
						// {
						// 	$status=0;
						// 	$message=$errmsg['websitefilesize'];
						// }
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
				$qrychk="SELECT title from tblslidermaster where (title=:title or displayorder=:displayorder) AND id<>:id";
				$parms = array(
					':title'=>$title,
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
						
						if($_FILES["img"]["type"] == "image/jpeg" || $_FILES["img"]["type"] == "image/jpg" || $_FILES["img"]["type"] == "image/png")
						{
							
							// if($_FILES['img']['size'] <= $config->getWebsitefilesize())
							// {
								$qryimg="select id,image from tblslidermaster where id=:id";
								$parms = array(
									':id'=>$id,
								);
								$resimg=$DB->getmenual($qryimg,$parms);
								$row=$resimg[0];
			
								unlink($config->getImageurl().$row['img']);

								$sourcePath = $_FILES['img']['tmp_name'];
								$targetPath = $IISMethods->uploadallfiles(1,'slider',$img,$sourcePath,$_FILES['img']['type'],$id);
								$insqry['[image]']=$targetPath;
								try 
								{
									$DB->begintransaction();
										$insqry['[update_uid]']=$uid;	
										$insqry['[update_date]']=$IISMethods->getdatetime();

										$extraparams=array(
											'[id]'=>$id
										);
										$DB->executedata('u','tblslidermaster',$insqry,$extraparams);

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
							// }
							// else
							// {
							// 	$status=0;
							// 	$message=$errmsg['websitefilesize'];
							// }
	
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
							$DB->executedata('u','tblslidermaster',$insqry,$extraparams);
		
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
	else if($action=='deleteslidermaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			$qryimg="select id,image from tblslidermaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$resimg=$DB->getmenual($qryimg,$parms);
			$row=$resimg[0];
			if($row['image'])
			{
				unlink($config->getImageurl().$row['image']);
			}
			
			try 
			{
				$DB->begintransaction();

				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('d','tblslidermaster','',$extraparams);

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

					$qryimg="select id,image from tblslidermaster where id=:id";
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
					$DB->executedata('d','tblslidermaster','',$extraparams);

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
	else if($action=='editslidermaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,title,displayorder from tblslidermaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);


			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['title']=$IISMethods->sanitize($row['title'],'OUT');
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
	else if($action=='listslidermaster')   
	{
		//error_reporting(1);
		$slidermasters=new slidermaster();
		$qry="select gm.timestamp as primary_date,gm.id,gm.title as title,ISNULL(gm.displayorder,0) AS displayorder,gm.image,gm.isactive
			from tblslidermaster gm
			where gm.title like :titlefilter or ISNULL(gm.displayorder,0) like :disorderfilter 
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':titlefilter'=>$filter,
			':disorderfilter'=>$filter,
		);
		$slidermasters=$DB->getmenual($qry,$parms,'slidermaster');
		
		if($responsetype=='HTML')
		{
			if($slidermasters)
			{
				$i=0;
				foreach($slidermasters as $slidermaster)
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
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($slidermaster->title,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($slidermaster->displayorder).'</td>';

					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($slidermaster->isactive==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeSliderstatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeSliderstatus('.$id.')">';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
	
					}

					$htmldata.='<td class="tbl-img">';
					if($slidermaster->image)
					{
						$htmldata.='<div class="dropdown img-dropdown dropleft">';
						$htmldata.='<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick=viewSliderimage("'.$IISMethods->sanitize($config->getImageurl().$slidermaster->image).'")><img src="'.$IISMethods->sanitize($config->getImageurl().$slidermaster->image).'" class="img-thumbnail"></a>';
						$htmldata.='<div class="dropdown-menu img-popover-content" aria-labelledby="tableDropdown3">';
						$htmldata.='<a href="javascript:void(0)" onclick=viewSliderimage("'.$IISMethods->sanitize($config->getImageurl().$slidermaster->image).'")><img src="'.$IISMethods->sanitize($config->getImageurl().$slidermaster->image).'"></a>';
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
				if($page<=0 && sizeof($slidermasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($slidermasters);
		}

		$common_listdata=$slidermasters;

	} 
	else if($action=='changeSliderstatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			
			$qrychk="select isactive from tblslidermaster where id=:id";
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
			$DB->executedata('u','tblslidermaster',$insqry,$extraparams);
		
		
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

  