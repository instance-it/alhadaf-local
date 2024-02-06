<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\languagemaster.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertlanguagemaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$apptypeid=$IISMethods->sanitize($_POST['apptypeid']);
		$languagename=$IISMethods->sanitize($_POST['languagename']);
		$languageengname=$IISMethods->sanitize($_POST['languageengname']);
		$displayorder=$IISMethods->sanitize($_POST['displayorder']);
		$label1=$IISMethods->sanitize($_POST['label1']);
		$label2=$IISMethods->sanitize($_POST['label2']);
		$label3=$IISMethods->sanitize($_POST['label3']);
		$img=$_FILES['img']['name'];

		$id=$IISMethods->sanitize($_POST['id']);

		if($apptypeid && $languagename && $languageengname && $label1 && $label2 && ($img || $formevent=='editright'))
		{
			$insqry=array(
				'[apptypeid]'=>$apptypeid,
				'[languagename]'=>$languagename,
				'[languageengname]'=>$languageengname,
				'[displayorder]'=>$displayorder,
				'[label1]'=>$label1,
				'[label2]'=>$label2,
				'[label3]'=>$label3,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT languagename from tbllanguagemaster where ((apptypeid=:apptypeid and languagename=:languagename) or displayorder=:displayorder)";
				$parms = array(
					':apptypeid'=>$apptypeid,
					':languagename'=>$languagename,
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
					if($_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type'] == "image/jpeg" || $_FILES['img']['type'] == "image/png")
					{
						try 
						{
							$DB->begintransaction();

							$unqid = $IISMethods->generateuuid();
							if($_FILES['img']['name'])
							{
								$sourcePath = $_FILES['img']['tmp_name'];
								$targetPath = $IISMethods->uploadallfiles(1,'language',$img,$sourcePath,$_FILES['img']['type'],$unqid);
								$insqry['[img]']=$targetPath;
							}
							
							$insqry['[id]']=$unqid;	
							$insqry['[isactive]']=1;	
							$insqry['[showinapp]']=1;
							$insqry['[isdefault]']=0;	
							$insqry['[timestamp]']=$IISMethods->getdatetime();
							$insqry['[entry_uid]']=$uid;	
							$insqry['[entry_date]']=$IISMethods->getdatetime();

							$DB->executedata('i','tbllanguagemaster',$insqry,'');

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
				$qrychk="SELECT languagename from tbllanguagemaster where ((apptypeid=:apptypeid and languagename=:languagename) or displayorder=:displayorder) AND id<>:id";
				$parms = array(
					':apptypeid'=>$apptypeid,
					':languagename'=>$languagename,
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
						if($_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type'] == "image/jpeg" || $_FILES['img']['type'] == "image/png")
						{
							try 
							{
								$DB->begintransaction();

								$qryimg="select id,img from tbllanguagemaster where id=:id";
								$parms = array(
									':id'=>$id,
								);
								$resimg=$DB->getmenual($qryimg,$parms);
								$row=$resimg[0];

								if($_FILES['img']['name'])
								{
									unlink($config->getImageurl().$row['img']);
									$sourcePath = $_FILES['img']['tmp_name'];
									$targetPath = $IISMethods->uploadallfiles(1,'language',$img,$sourcePath,$_FILES['img']['type'],$id);
									$insqry['[img]']=$targetPath;
								}


								$insqry['[update_uid]']=$uid;	
								$insqry['[update_date]']=$IISMethods->getdatetime();
			
								$extraparams=array(
									'[id]'=>$id
								);
								$DB->executedata('u','tbllanguagemaster',$insqry,$extraparams);
			
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
							$DB->executedata('u','tbllanguagemaster',$insqry,$extraparams);

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
	else if($action=='deletelanguagemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="SELECT distinct case when(CONVERT(VARCHAR(255), al.id) !='' or CONVERT(VARCHAR(255), am.id) !='') then 1 else 0 end as tem
				from tbllanguagemaster lm
				left join tblassignlanguagewiselabel al on al.languageid = lm.id
				left join tblassignlangwisemessage am on am.languageid = lm.id
				where lm.id=:id";

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
					
					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('d','tbllanguagemaster','',$extraparams);
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

					$qrychk="SELECT distinct case when(CONVERT(VARCHAR(255), al.id) !='' or CONVERT(VARCHAR(255), am.id) !='') then 1 else 0 end as tem,lm.languagename
					from tbllanguagemaster lm
					left join tblassignlanguagewiselabel al on al.languageid = lm.id
					left join tblassignlangwisemessage am on am.languageid = lm.id
					where lm.id=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['languagename'].",";
					}
					else
					{
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tbllanguagemaster','',$extraparams);
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
	else if($action=='editlanguagemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,apptypeid,languagename,languageengname,label1,label2,label3,displayorder from tbllanguagemaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);


			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['apptypeid']=$IISMethods->sanitize($row['apptypeid']);
				$response['languagename']=$IISMethods->sanitize($row['languagename']);
				$response['languageengname']=$IISMethods->sanitize($row['languageengname']);
				$response['label1']=$IISMethods->sanitize($row['label1']);
				$response['label2']=$IISMethods->sanitize($row['label2']);
				$response['label2']=$IISMethods->sanitize($row['label2']);
				$response['label3']=$IISMethods->sanitize($row['label3']);
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
	else if($action=='listlanguagemaster')   
	{
		//error_reporting(1);
		$languagemasters=new languagemaster();
		$qry="select timestamp as primary_date,id,languagename,languageengname,label1,label2,label3,img,isactive,showinapp,ISNULL(isdefault,0) AS isdefault,ISNULL(displayorder,0) AS displayorder,
			case when (apptypeid=2) then 'Mobile App' when (apptypeid=3) then 'POS' else '' end as apptype   
			from tbllanguagemaster 
			where (languagename like :lanfilter or languageengname like :languageengfilter or displayorder like :disfilter or label1 like :label1filter or label2 like :label2filter or label3 like :label3filter or (case when (apptypeid=2) then 'Mobile App' when (apptypeid=3) then 'POS' else '' end) like :apptypefilter )
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':lanfilter'=>$filter,
			':languageengfilter'=>$filter,
			':disfilter'=>$filter,
			':label1filter'=>$filter,
			':label2filter'=>$filter,
			':label3filter'=>$filter,
			':apptypefilter'=>$filter,
		);
		$languagemasters=$DB->getmenual($qry,$parms,'languagemaster');
		
		if($responsetype=='HTML')
		{
			if($languagemasters)
			{
				$i=0;
				foreach($languagemasters as $languagemaster)
				{
					$id="'".$languagemaster->id."'";
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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($languagemaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($languagemaster->apptype,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($languagemaster->languagename,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($languagemaster->languageengname,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($languagemaster->displayorder,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($languagemaster->label1,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($languagemaster->label2,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($languagemaster->label3,'OUT').'</td>';
					
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($languagemaster->isactive==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changelanguagestatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changelanguagestatus('.$id.')">';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
	
						if($languagemaster->showinapp==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeshowinappstatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeshowinappstatus('.$id.')">';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}

						if($languagemaster->isdefault==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changedefaultstatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changedefaultstatus('.$id.')">';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
					}
					


					$htmldata.='<td class="tbl-img">';
					if($languagemaster->img)
					{
						$htmldata.='<div class="dropdown img-dropdown dropleft">';
						$htmldata.='<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick=viewlanguageimage("'.$IISMethods->sanitize($config->getImageurl().$languagemaster->img).'")><img src="'.$IISMethods->sanitize($config->getImageurl().$languagemaster->img).'" class="img-thumbnail"></a>';
						$htmldata.='<div class="dropdown-menu img-popover-content" aria-labelledby="tableDropdown3">';
						$htmldata.='<a href="javascript:void(0)" onclick=viewlanguageimage("'.$IISMethods->sanitize($config->getImageurl().$languagemaster->img).'")><img src="'.$IISMethods->sanitize($config->getImageurl().$languagemaster->img).'"></a>';
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
				if($page<=0 && sizeof($languagemasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($languagemasters);
		}

		$common_listdata=$languagemasters;

	} 
	else if($action=='changelanguagestatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();
				$qrychk="select isactive from tbllanguagemaster where id=:id";
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
				$DB->executedata('u','tbllanguagemaster',$insqry,$extraparams);
			
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
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='changeshowinappstatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{	
			try 
			{
				$DB->begintransaction();

				$qrychk="select showinapp from tbllanguagemaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];
				if($row['showinapp']==1)
				{
					$showinapp=0;
				}
				else
				{
					$showinapp=1;
				}	
				$insqry['[showinapp]']=$showinapp;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tbllanguagemaster',$insqry,$extraparams);
			

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
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='changedefaultstatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{	
			try 
			{
				$DB->begintransaction();
				$qry = "SELECT isdefault,apptypeid FROM tbllanguagemaster WHERE id = :id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qry,$parms);
				$row=$result_ary[0];

				$isdefault=1;
			
				$insqry['[isdefault]']=$isdefault;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tbllanguagemaster',$insqry,$extraparams);


				$subqry = "SELECT id FROM tbllanguagemaster WHERE apptypeid=:apptypeid and id <> :id";
				$subparms = array(
					':id'=>$id,
					':apptypeid'=>$row['apptypeid'],
				);
				$result_ary=$DB->getmenual($subqry,$subparms);

				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$subrow=$result_ary[$i];
					$id= $IISMethods->sanitize($subrow['id']);
					$insqry['[isdefault]']=0;	
					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('u','tbllanguagemaster',$insqry,$extraparams);
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
		else
		{
			$message=$errmsg['reqired'];
		}
	}
}



require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  