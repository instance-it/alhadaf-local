<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\city.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$userpagename);

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='insertcity')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$city=$IISMethods->sanitize($_POST['city']);
		$stateid=$IISMethods->sanitize($_POST['stateid']);
		$id=$IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();
		if($city && $stateid)
		{			
			$insqry=array(					
				'[city]'=>$city,
				'[stateid]'=>$stateid,								
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT city from tblcitymaster where city=:city";
				$parms = array(
					':city'=>$city,
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
						$insqry['[entry_uid]']=$uid;	
						$insqry['[entry_date]']=$datetime;

						$DB->executedata('i','tblcitymaster',$insqry,'');

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
				$qrychk="SELECT city from tblcitymaster where city=:city and id<>:id";
				$parms = array(
					':city'=>$city,
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
						$DB->executedata('u','tblcitymaster',$insqry,$extraparams);

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
	else if($action=='deletecitymaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			$qrychk="SELECT distinct case when(pm.contact !='') then 1 else 0 end as tem
			from tblcitymaster cm
			left join tblpersonmaster pm on pm.cityid = cm.id and pm.isdelete = 0
			where cm.id=:id";
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
					$DB->executedata('d','tblcitymaster','',$extraparams);
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

					$qrychk="SELECT case when(pm.contact !='') then 1 else 0 end as tem,cm.city
					from tblcitymaster cm
					left join tblpersonmaster pm on pm.cityid = cm.id and pm.isdelete = 0
					where cm.id=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['city'].",";
					}
					else
					{
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblcitymaster','',$extraparams);
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
	else if($action=='editcitymaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,city,stateid from tblcitymaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];
				$response['id']=$IISMethods->sanitize($row['id']);
				$response['city']=$IISMethods->sanitize($row['city']);
				$response['stateid']=$IISMethods->sanitize($row['stateid']);

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
	else if($action=='listcitymaster')   
	{
		$citys=new city();
		$qry="select cm.timestamp as primary_date,cm.id,cm.city,cm.stateid,sm.state from tblcitymaster cm 
		inner join tblstatemaster sm on sm.id=cm.stateid 
		where cm.city like :filter  order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':filter'=>$filter,
		);
		$citys=$DB->getmenual($qry,$parms,'city');
		
		if($responsetype=='HTML')
		{
			if($citys)
			{
				$i=0;
				foreach($citys as $city)
				{
					$id="'".$city->id."'";
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

					
					$htmldata.='<td class="tbl-check d-none">';
					$htmldata.='<div class="text-center">';
					$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
					$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($city->id).'" name="bulkdelete[]">';
					$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
					$htmldata.='</div>';
					$htmldata.='</div>';
					$htmldata.='</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($city->city,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($city->state,'OUT').'</td>';
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($citys)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($citys);
		}

		$common_listdata=$citys;

	}    
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  