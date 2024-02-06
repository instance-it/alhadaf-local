<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\messagemaster.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertmessagemaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$messagenameid=$IISMethods->sanitize($_POST['messagenameid']);
		$messageengname=$IISMethods->sanitize($_POST['messageengname'],'OUT');
		$variables=$IISMethods->sanitize($_POST['variables']);

		$id=$IISMethods->sanitize($_POST['id']);

		if($messagenameid && $messageengname)
		{
			$insqry=array(
				'[messagenameid]'=>$messagenameid,
				'[messageengname]'=>$messageengname,
				'[variables]'=>$variables,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT messagenameid from tblmessagemaster where messagenameid=:messagenameid";
				$parms = array(
					':messagenameid'=>$messagenameid,
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

						$DB->executedata('i','tblmessagemaster',$insqry,'');

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
				$qrychk="SELECT messagenameid from tblmessagemaster where messagenameid=:messagenameid AND id<>:id";
				$parms = array(
					':messagenameid'=>$messagenameid,
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
						$DB->executedata('u','tblmessagemaster',$insqry,$extraparams);

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
	else if($action=='deletemessagemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			try 
			{
				$DB->begintransaction();
				$qrychk="SELECT case when(CONVERT(VARCHAR(255), am.id) !='') then 1 else 0 end as tem,mm.messagenameid
				from tblmessagemaster mm
				left join tblassignlangwisemessage am on am.messageid = mm.id
				where mm.id=:id";

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
					$DB->executedata('d','tblmessagemaster','',$extraparams);
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

					$qrychk="SELECT case when(CONVERT(VARCHAR(255), am.id) !='') then 1 else 0 end as tem,mm.messagenameid
					from tblmessagemaster mm
					left join tblassignlangwisemessage am on am.messageid = mm.id
					where mm.id=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['messagenameid'].",";
					}
					else
					{
					

						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblmessagemaster','',$extraparams);
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
	else if($action=='editmessagemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,messagenameid,messageengname,variables from tblmessagemaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['messagenameid']=$IISMethods->sanitize($row['messagenameid']);
				$response['messageengname']=$row['messageengname'];
				$response['variables']=$IISMethods->sanitize($row['variables']);

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
	else if($action=='listmessagemaster')   
	{
		//error_reporting(1);
		$messagemasters=new messagemaster();
		$qry="select mm.timestamp as primary_date,mm.id,mm.messagenameid,mm.messageengname,mm.variables
			from tblmessagemaster mm 
			where mm.messagenameid like :msgnamefilter or mm.messageengname like :msgengnamefilter or mm.variables like :variablesfilter order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':msgnamefilter'=>$filter,
			':msgengnamefilter'=>$filter,
			':variablesfilter'=>$filter,
		);
		$messagemasters=$DB->getmenual($qry,$parms,'messagemaster');
		
		if($responsetype=='HTML')
		{
			if($messagemasters)
			{
				$i=0;
				foreach($messagemasters as $messagemaster)
				{
					$id="'".$messagemaster->id."'";
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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($messagemaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($messagemaster->messagenameid).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($messagemaster->messageengname,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($messagemaster->variables).'</td>';
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($messagemasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($messagemasters);
		}

		$common_listdata=$messagemasters;

	} 
	//Fill To Category
	else if($action == 'fillappmenu')
	{
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);
		$qry="select * from tblmenumaster where menutype = 2 order by timestamp";
		$parms = array(
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

  