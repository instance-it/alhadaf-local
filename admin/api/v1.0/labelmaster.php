<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\labelmaster.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertlabelmaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$apptypeid=$IISMethods->sanitize($_POST['apptypeid']);
		$appmenuid=$IISMethods->sanitize($_POST['appmenuid']);
		$labelnameid=$IISMethods->sanitize($_POST['labelnameid']);
		$labelengname=$IISMethods->sanitize($_POST['labelengname'],'OUT');

		$id=$IISMethods->sanitize($_POST['id']);

		if($apptypeid && $appmenuid && $labelnameid && $labelengname)
		{
			$insqry=array(
				'[apptypeid]'=>$apptypeid,
				'[appmenuid]'=>$appmenuid,
				'[labelnameid]'=>$labelnameid,
				'[labelengname]'=>$labelengname,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT labelnameid from tbllabelmaster where apptypeid=:apptypeid and labelnameid=:labelnameid";
				$parms = array(
					':apptypeid'=>$apptypeid,
					':labelnameid'=>$labelnameid,
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
						$insqry['[timestamp]']=$IISMethods->getdatetime();
						$insqry['[entry_uid]']=$uid;	
						$insqry['[entry_date]']=$IISMethods->getdatetime();

						$DB->executedata('i','tbllabelmaster',$insqry,'');

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
				$qrychk="SELECT labelnameid from tbllabelmaster where apptypeid=:apptypeid and labelnameid=:labelnameid AND id<>:id";
				$parms = array(
					':apptypeid'=>$apptypeid,
					':labelnameid'=>$labelnameid,
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

						$extraparams=array(
							'[id]'=>$id
						);
						$DB->executedata('u','tbllabelmaster',$insqry,$extraparams);

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
	else if($action=='deletelabelmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="SELECT case when(CONVERT(VARCHAR(255), al.id) !='') then 1 else 0 end as tem
				from tbllabelmaster lm
				left join tblassignlanguagewiselabel al on al.labelid = lm.id
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
					$DB->executedata('d','tbllabelmaster','',$extraparams);
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
					

					$qrychk="SELECT case when(CONVERT(VARCHAR(255), al.id) !='') then 1 else 0 end as tem,lm.labelnameid
					from tbllabelmaster lm
					left join tblassignlanguagewiselabel al on al.labelid = lm.id
					where lm.id=:id";

					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['labelnameid'].",";
					}
					else
					{
					

						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tbllabelmaster','',$extraparams);
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
	else if($action=='editlabelmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,apptypeid,appmenuid,labelnameid,labelengname from tbllabelmaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['apptypeid']=$IISMethods->sanitize($row['apptypeid']);
				$response['appmenuid']=$IISMethods->sanitize($row['appmenuid']);
				$response['labelnameid']=$IISMethods->sanitize($row['labelnameid']);
				$response['labelengname']=$row['labelengname'];

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
	else if($action=='listlabelmaster')   
	{
		//error_reporting(1);
		$labelmasters=new labelmaster();
		$qry="select lm.timestamp as primary_date,lm.id,lm.labelnameid,lm.labelengname,m.menuname,
			case when (apptypeid=2) then 'Mobile App' when (apptypeid=3) then 'POS' else '' end as apptype  
			from tbllabelmaster lm 
			inner join tblmenumaster m on m.id = lm.appmenuid
			where (m.menuname like :menufilter or lm.labelnameid like :lblnamefilter or lm.labelengname like :lblengnamefilter or (case when (apptypeid=2) then 'Mobile App' when (apptypeid=3) then 'POS' else '' end) like :apptypefilter)
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':menufilter'=>$filter,
			':lblnamefilter'=>$filter,
			':lblengnamefilter'=>$filter,
			':apptypefilter'=>$filter,
		);
		$labelmasters=$DB->getmenual($qry,$parms,'labelmaster');
		
		if($responsetype=='HTML')
		{
			if($labelmasters)
			{
				$i=0;
				foreach($labelmasters as $labelmaster)
				{
					$id="'".$labelmaster->id."'";
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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($labelmaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($labelmaster->apptype).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($labelmaster->menuname,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($labelmaster->labelnameid).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($labelmaster->labelengname,'OUT').'</td>';
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($labelmasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($labelmasters);
		}

		$common_listdata=$labelmasters;

		
	} 
	//Fill To Category
	else if($action == 'fillappmenu')
	{
		$apptypeid=$IISMethods->sanitize($_POST['apptypeid']);
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);

		$qry="select * from tblmenumaster where menutype = :apptypeid order by timestamp";
		$parms = array(
			':apptypeid'=>$apptypeid,
		);
		$result_ary=$DB->getmenual($qry,$parms);
		$htmldata='';
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Appmenu</option>';
		}

		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['menuname'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
}



require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  