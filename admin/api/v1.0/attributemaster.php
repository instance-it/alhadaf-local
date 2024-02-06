<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\attribute.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$userpagename);

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='insertattribute')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$attributename=$IISMethods->sanitize($_POST['attributename']);
		$displayorder=$IISMethods->sanitize($_POST['displayorder']);
		$id=$IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();
		if($attributename)
		{			
			$insqry=array(					
				'[attributename]'=>$attributename,		
				'[displayorder]'=>$displayorder,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT attributename from tblattributemaster where attributename=:attributename or displayorder=:displayorder";
				$parms = array(
					':attributename'=>$attributename,
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

						$DB->executedata('i','tblattributemaster',$insqry,'');

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
				$qrychk="SELECT attributename from tblattributemaster where (attributename=:attributename or displayorder=:displayorder) AND id<>:id";
				$parms = array(
					':attributename'=>$attributename,
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
						$DB->executedata('u','tblattributemaster',$insqry,$extraparams);

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
	else if($action=='deleteattributemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{

			// $qrychk="SELECT case when(pm.contact !='' or cm.city != '') then 1 else 0 end as tem
			// from tblstatemaster sm
			// left join tblcitymaster cm on cm.stateid = sm.id
			// left join tblpersonmaster pm on pm.stateid = sm.id and pm.isdelete = 0 
			// where sm.id=:id";
			// $parms = array(
			// 	':id'=>$id,
			// );
			// $result_ary=$DB->getmenual($qrychk,$parms);
			// $row=$result_ary[0];

			// if($row['tem'] > 0)
			// {
			// 	$status=0;
			// 	$message=$errmsg['inuse'];
			// }
			// else
			// {
				try 
				{
					$DB->begintransaction();

					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('d','tblattributemaster','',$extraparams);
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
			//}
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
					// $qrychk="SELECT case when(pm.contact !='' or cm.city != '') then 1 else 0 end as tem,sm.state
					// from tblstatemaster sm
					// left join tblcitymaster cm on cm.stateid = sm.id
					// left join tblpersonmaster pm on pm.stateid = sm.id and pm.isdelete = 0 
					// where sm.id=:id";
					// $parms = array(
					// 	':id'=>$id,
					// );
					// $result_ary=$DB->getmenual($qrychk,$parms);
					// $row=$result_ary[0];

					// if($row['tem'] > 0)
					// {
					// 	$usemenu.=$row['state'].",";
					// }
					// else
					// {
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblattributemaster','',$extraparams);
					//}
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
	else if($action=='editattributemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,attributename,displayorder from tblattributemaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];
				$response['id']=$IISMethods->sanitize($row['id']);
				$response['attributename']=$IISMethods->sanitize($row['attributename']);
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
	else if($action=='listattributemaster')   
	{
		$attributes=new attribute();
		$qry="select timestamp as primary_date,id,attributename,displayorder,isactive 
			from tblattributemaster where (attributename like :namefilter or displayorder like :orderfilter) order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':namefilter'=>$filter,
			':orderfilter'=>$filter,
		);
		$attributes=$DB->getmenual($qry,$parms,'attribute');
		
		if($responsetype=='HTML')
		{
			if($attributes)
			{
				$i=0;
				foreach($attributes as $attribute)
				{
					$id="'".$attribute->id."'";
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
					
					$htmldata.='<td class="tbl-check d-none">';
					$htmldata.='<div class="text-center">';
					$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
					$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($attribute->id).'" name="bulkdelete[]">';
					$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
					$htmldata.='</div>';
					$htmldata.='</div>';
					$htmldata.='</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($attribute->attributename,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($attribute->displayorder).'</td>';

					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($attribute->isactive==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeattributestatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeattributestatus('.$id.')">';
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
				if($page<=0 && sizeof($attributes)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($attributes);
		}


		$common_listdata=$attributes;
	}   
	else if($action=='changeattributestatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			
			$qrychk="select isactive from tblattributemaster where id=:id";
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
			$DB->executedata('u','tblattributemaster',$insqry,$extraparams);
		
		
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

  