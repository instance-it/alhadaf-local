<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\subcategorymaster.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertsubcategory')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$subcategory=$IISMethods->sanitize($_POST['subcategoryname']);
		$displayorder=$IISMethods->sanitize($_POST['displayorder']);

		$id=$IISMethods->sanitize($_POST['id']);

		if($categoryid && $subcategory)
		{
			$insqry=array(
				'[categoryid]'=>$categoryid,
				'[subcategory]'=>$subcategory,
				'[displayorder]'=>$displayorder,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT subcategory from tblsubcategory where ((categoryid=:categoryid and subcategory=:subcategory) or displayorder=:displayorder)";
				$parms = array(
					':categoryid'=>$categoryid,
					':subcategory'=>$subcategory,
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
						$insqry['[entry_uid]']=$uid;	
						$insqry['[entry_date]']=$IISMethods->getdatetime();

						$DB->executedata('i','tblsubcategory',$insqry,'');

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
				$qrychk="SELECT subcategory from tblsubcategory where ((categoryid=:categoryid and subcategory=:subcategory) or displayorder=:displayorder) AND id<>:id";
				$parms = array(
					':categoryid'=>$categoryid,
					':subcategory'=>$subcategory,
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
						$insqry['[update_date]']=$IISMethods->getdatetime();

						$extraparams=array(
							'[id]'=>$id
						);
						$DB->executedata('u','tblsubcategory',$insqry,$extraparams);

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
	else if($action=='deletesubcategorymaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{

			$qrychk="SELECT distinct case when(convert(varchar(50),im.id) !='') then 1 else 0 end as tem
			from tblsubcategory sc 
			left join tblitemmaster im on im.subcategoryid = sc.id
			where sc.id=:id";

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
					$DB->executedata('d','tblsubcategory','',$extraparams);
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

					$qrychk="SELECT distinct case when(convert(varchar(50),im.id) !='') then 1 else 0 end as tem,sc.subcategory
					from tblsubcategory sc 
					left join tblitemmaster im on im.subcategoryid = sc.id
					where sc.id=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['subcategory'].",";
					}
					else
					{
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblsubcategory','',$extraparams);
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
	else if($action=='editsubcategorymaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,categoryid,subcategory,displayorder from tblsubcategory where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['categoryid']=$IISMethods->sanitize($row['categoryid']);
				$response['subcategory']=$IISMethods->sanitize($row['subcategory']);
				$response['displayorder']=$IISMethods->sanitize($row['displayorder']);

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
	else if($action=='listsubcategorymaster')   
	{
		//error_reporting(1);
		$subcategorymasters=new subcategorymaster();
		$qry="select sc.timestamp as primary_date,sc.id,sc.subcategory,cm.category,ISNULL(sc.displayorder,0) AS displayorder 
			from tblsubcategory sc 
			inner join tblcategory cm on cm.id = sc.categoryid
			where cm.category like :filter or sc.subcategory like :subcatfilter or sc.displayorder like :disorderfilter order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':filter'=>$filter,
			':subcatfilter'=>$filter,
			':disorderfilter'=>$filter,
		);
		$subcategorymasters=$DB->getmenual($qry,$parms,'subcategorymaster');
		
		if($responsetype=='HTML')
		{
			if($subcategorymasters)
			{
				$i=0;
				foreach($subcategorymasters as $subcategorymaster)
				{
					$id="'".$subcategorymaster->id."'";
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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($subcategorymaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($subcategorymaster->category,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($subcategorymaster->subcategory,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($subcategorymaster->displayorder).'</td>';
					
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($subcategorymasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($subcategorymasters);
		}

		$common_listdata=$subcategorymasters;
	} 
	//Fill To Category
	else if($action == 'fillcategory')
	{
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);
		$qry="select c.* from tblcategory c where c.isactive=1 order by (case when (c.displayorder>0) then c.displayorder else 99999 end)";
		$parms = array(
		);
		$result_ary=$DB->getmenual($qry,$parms);
		$htmldata='';
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Category</option>';
		}

		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['category'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
}



require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  