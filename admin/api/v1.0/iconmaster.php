<?php header("Content-Type:application/json");

require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\icon.php';


if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='inserticonmaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$iconname=$IISMethods->sanitize($_POST['iconname']);
		$iconstyle=$IISMethods->sanitize($_POST['iconstyle']);
		$iconclass=$IISMethods->sanitize($_POST['iconclass']);
		$iconunicode=$IISMethods->sanitize($_POST['iconunicode']);
		$id=$IISMethods->sanitize($_POST['id']);	
		$datetime=$IISMethods->getdatetime();

		if($iconname && $iconstyle && $iconclass && $iconunicode)
		{	
			$insqry=array(					
				'[iconname]'=>$IISMethods->sanitize($iconname),
				'[iconstyle]'=>$IISMethods->sanitize($iconstyle),	
				'[iconclass]'=>$IISMethods->sanitize($iconclass),	
				'[iconunicode]'=>$IISMethods->sanitize($iconunicode),					
			);

			if($formevent=='addright')  // Insert
			{
				try 
				{
					$DB->begintransaction();

					$unqid = $IISMethods->generateuuid();
					$insqry['[id]']=$IISMethods->sanitize($unqid);	
					$insqry['[entry_uid]']=$IISMethods->sanitize($uid);	
					$insqry['[entry_date]']=$IISMethods->sanitize($datetime);

					$DB->executedata('i','tbliconmaster',$insqry,'');

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
			else if($formevent=='editright')   // Update
			{
				try 
				{
					$DB->begintransaction();

					$insqry['[update_uid]']=$IISMethods->sanitize($uid);	
					$insqry['[update_date]']=$IISMethods->sanitize($datetime);

					$extraparams=array(
						'[id]'=>$IISMethods->sanitize($id)
					);
					$DB->executedata('u','tbliconmaster',$insqry,$extraparams);
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
		else
		{
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deleteiconmaster')   
	{
		$id=$_POST['id'];
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			$qrychk="select id from tblmenumaster where iconid=:id";
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
					$DB->executedata('d','tbliconmaster','',$extraparams);
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
					$qrychk="select top 1 ti.iconname from tblmenumaster tm inner join tbliconmaster ti on tm.iconid=ti.id where tm.iconid=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					if(sizeof($result_ary) > 0)
					{
						$row=$result_ary[0];
						$usemenu.=$row['iconname'].",";
					}
					else
					{
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tbliconmaster','',$extraparams);
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
			$qry="select id,iconname,iconstyle,iconclass,iconunicode from tbliconmaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qry,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];
				$response['id']=$IISMethods->sanitize($row['id']);
				$response['iconname']=$IISMethods->sanitize($row['iconname']);
				$response['iconstyle']=$IISMethods->sanitize($row['iconstyle']);
				$response['iconclass']=$IISMethods->sanitize($row['iconclass']);
				$response['iconunicode']=$IISMethods->sanitize($row['iconunicode']);
			}
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='listiconmaster')   
	{
		$icons=new icon();
		$qry="select timestamp as primary_date,id,iconname,iconstyle,iconclass,iconunicode,entry_uid,entry_date from tbliconmaster where iconname like :filter order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':filter'=>'%'.$filter.'%',
		);
		$icons=$DB->getmenual($qry,$parms,'icon');
		if($responsetype=='HTML')
		{
			if($icons)
			{
				$i=0;
				foreach($icons as $icon)
				{
					$id="'".$icon->id."'";
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
					$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($icon->id).'" name="bulkdelete[]">';
					$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
					$htmldata.='</div>';
					$htmldata.='</div>';
					$htmldata.='</td>';
					}
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($icon->iconname).'</td>';
					$htmldata.='<td class="tbl-name"><i class="'.$IISMethods->sanitize($icon->iconstyle).' '.$IISMethods->sanitize($icon->iconclass).'"></i></td>';
					$htmldata.='</tr>';
					$i++;
				}
					
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($icons)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($icons);
		}

		$common_listdata=$icons;
	}    
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  