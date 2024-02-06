<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\instruction.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$userpagename);

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='insertinstruction')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$name=$IISMethods->sanitize($_POST['name']);
		$displayorder=$IISMethods->sanitize($_POST['displayorder']);
		$id = $IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();
		if($name)
		{			
			$insqry=array(					
				'[name]'=>$name,	
				'[displayorder]'=>$displayorder,				
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT name from tblinstruction where name=:name or displayorder=:displayorder";
				$parms = array(
					':name'=>$name,
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
					try 
					{
						$DB->begintransaction();

						$unqid = $IISMethods->generateuuid();
						$insqry['[id]']=$unqid;	
						$insqry['[isactive]']=1;
						$insqry['[timestamp]']=$datetime;
						$insqry['[entry_uid]']=$uid;	
						$insqry['[entry_date]']=$datetime;

						$DB->executedata('i','tblinstruction',$insqry,'');

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
				$qrychk="SELECT name from tblinstruction where (name=:name or displayorder=:displayorder) AND id<>:id";
				$parms = array(
					':name'=>$name,
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
					try 
					{
						$DB->begintransaction();

						$insqry['[update_uid]']=$uid;	
						$insqry['[update_date]']=$datetime;

						$extraparams=array(
							'[id]'=>$id
						);
						$DB->executedata('u','tblinstruction',$insqry,$extraparams);

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
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deleteinstructionmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			$qrychk="SELECT case when(igd.instructionname !='') then 1 else 0 end as tem
			from tblinstruction i
			left join tblinstructiongroupdetail igd on igd.instructionid = i.id
			where i.id=:id";
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

					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('d','tblinstruction','',$extraparams);
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

					$qrychk="SELECT case when(igd.instructionname !='') then 1 else 0 end as tem,i.name
						from tblinstruction i
						left join tblinstructiongroupdetail igd on igd.instructionid = i.id
						where i.id=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['name'].",";
					}
					else
					{
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblinstruction','',$extraparams);
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
	else if($action=='editinstructionmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,name,displayorder from tblinstruction where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];
				$response['id']=$IISMethods->sanitize($row['id']);
				$response['name']=$IISMethods->sanitize($row['name']);
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
	else if($action=='listinstructionmaster')   
	{
		$instructions=new instruction();
		$qry="select timestamp as primary_date,id,name,displayorder,isactive from tblinstruction where (name like :namefilter or displayorder like :orderfilter) order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':namefilter'=>$filter,
			':orderfilter'=>$filter,
		);
		$instructions=$DB->getmenual($qry,$parms,'instruction');
		
		if($responsetype=='HTML')
		{
			if($instructions)
			{
				$i=0;
				foreach($instructions as $instruction)
				{
					$id="'".$instruction->id."'";
					$htmldata.='<tr data-index="'.$i.'">';
			
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || (sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-grid">';
						$htmldata.='<div class="dropdown table-dropdown">';
						$htmldata.='<button class="dropdown-toggle btn-tbl rounded-circle" type="button" id="tableDropdown3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>';
						$htmldata.='<div class="dropdown-menu" aria-labelledby="tableDropdown3">';

						if(((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright  - only self person data show
						{
							$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="editdata('.$IISMethods->sanitize($id).')"><i class="bi bi-pencil"></i> Edit</a>';
						}
						if(((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright  - only self person data show
						{
							$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="deletedata('.$IISMethods->sanitize($id).')"><i class="bi bi-trash"></i> Delete</a>';
						}

						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					// $htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="editdata('.$IISMethods->sanitize($id).')"><i class="bi bi-pencil"></i> Edit</a>';
					// $htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="deletedata('.$IISMethods->sanitize($id).')"><i class="bi bi-trash"></i> Delete</a>';
					
					$htmldata.='<td class="tbl-check d-none">';
					$htmldata.='<div class="text-center">';
					$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
					$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($instruction->id).'" name="bulkdelete[]">';
					$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
					$htmldata.='</div>';
					$htmldata.='</div>';
					$htmldata.='</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($instruction->name,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($instruction->displayorder).'</td>';
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($instruction->isactive==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeisactiveinstruction('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeisactiveinstruction('.$id.')">';
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
				if($page<=0 && sizeof($instructions)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($instructions);
		}


		$common_listdata=$instructions;
	} 
	else if($action=='changeisactiveinstruction')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select isactive from tblinstruction where id=:id";
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
				$DB->executedata('u','tblinstruction',$insqry,$extraparams);

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

  