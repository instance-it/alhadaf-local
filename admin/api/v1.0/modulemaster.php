<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\module.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='insertmodulemaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$modulename=$IISMethods->sanitize($_POST['modulename']);
		$moduletypeid=$IISMethods->sanitize($_POST['moduletypeid']);
		$moduletypetxt=$IISMethods->sanitize($_POST['moduletypetxt']);
		$id=$IISMethods->sanitize($_POST['id']);	
		$datetime=$IISMethods->getdatetime();

		if($modulename && $moduletypeid && $moduletypetxt)
		{	
			$insqry=array(					
				'[modulename]'=>$modulename,
				'[moduletypeid]'=>$moduletypeid,	
				'[moduletypetxt]'=>$moduletypetxt,					
			);

			if($formevent=='addright') //insert
			{
				$qrychk="SELECT modulename from tblmodulemaster where modulename=:modulename and moduletypeid=:moduletypeid";
				$parms = array(
					':modulename'=>$modulename,
					':moduletypeid'=>$moduletypeid,
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
						$insqry['[id]']=$IISMethods->sanitize($unqid);	
						$insqry['[entry_uid]']=$IISMethods->sanitize($uid);	
						$insqry['[entry_date]']=$IISMethods->sanitize($datetime);

						$DB->executedata('i','tblmodulemaster',$insqry,'');
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
			else if($formevent=='editright') // Update
			{
				$qrychk="SELECT modulename from tblmodulemaster where modulename=:modulename and moduletypeid=:moduletypeid and id<>:id";
				$parms = array(
					':modulename'=>$modulename,
					':moduletypeid'=>$moduletypeid,
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

						$insqry['[update_uid]']=$IISMethods->sanitize($uid);	
						$insqry['[update_date]']=$IISMethods->sanitize($datetime);

						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('u','tblmodulemaster',$insqry,$extraparams);
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
	else if($action=='deletemodulemaster')   
	{
		$id=$_POST['id'];
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			$qrychk="select id from tblmenuassign where moduleid=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);
			if(sizeof($result_ary) > 0)
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
						'[id]'=>$IISMethods->sanitize($id)
					);
					$DB->executedata('d','tblmodulemaster','',$extraparams);
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
					$qrychk="select top 1 tm.modulename from tblmenuassign tma inner join tblmodulemaster tm on tm.id=tma.moduleid where tma.moduleid=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					if(sizeof($result_ary) > 0)
					{
						$row=$result_ary[0];
						$usemenu.=$row['modulename'].",";
					}
					else
					{
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblmodulemaster','',$extraparams);
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
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='editmodulemaster')   
	{
		$id=$_POST['id'];
		if($id)
		{
			$qry="select id,modulename,moduletypeid from tblmodulemaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qry,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['modulename']=$IISMethods->sanitize($row['modulename']);
				$response['moduletypeid']=$IISMethods->sanitize($row['moduletypeid']);
			}
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='listmodulemaster')   
	{
		$modules=new module();
		$qry="select timestamp as primary_date,id,modulename,moduletypeid,
		case when (moduletypeid=1) then 'Web' when (moduletypeid=2) then 'Mobile App' else 'POS' end as moduletypetxt 
		from tblmodulemaster where (modulename like :filter or (case when (moduletypeid=1) then 'Web' when (moduletypeid=2) then 'Mobile App' else 'POS' end) like :typefilter) order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':filter'=>$filter,
			':typefilter'=>$filter,
		);
		$modules=$DB->getmenual($qry,$parms,'module');
		if($responsetype=='HTML')
		{
			if($modules)
			{
				$i=0;
				foreach($modules as $module)
				{
					$id="'".$module->id."'";
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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$module->id.'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($module->moduletypetxt).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($module->modulename).'</td>';
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($modules)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($modules);
		}
		

		$common_listdata=$modules;
	}    
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  