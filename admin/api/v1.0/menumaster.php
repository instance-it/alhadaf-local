<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\menu.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='fillicon')   
	{
		$qry="select id,iconname,iconstyle,iconclass,iconunicode from tbliconmaster";
		$result_ary=$DB->getmenual($qry);
		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$IISMethods->sanitize($row['id']).'" data-style="'.$IISMethods->sanitize($row['iconstyle']).'" data-icon="'.$IISMethods->sanitize($row['iconclass']).'" data-iconunicode="'.$IISMethods->sanitize($row['iconunicode']).'" >'.$IISMethods->sanitize($row['iconname']).'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='insertmenumaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$menuname=$IISMethods->sanitize($_POST['menuname']);
		$formname=$IISMethods->sanitize($_POST['formname']);
		$iconid=$IISMethods->sanitize($_POST['iconid']);
		$menutypeid=$IISMethods->sanitize($_POST['menutypeid']);
		$alias=$IISMethods->sanitize($_POST['alias']);
		$containrights=$IISMethods->sanitize($_POST['containrights']);
		$id=$IISMethods->sanitize($_POST['id']);	
		$datetime=$IISMethods->getdatetime();

		if($menuname && $iconid && $menutypeid && $alias)
		{	
			$qrychk="select iconstyle,iconclass,iconunicode from tbliconmaster where id=:id";
			$parms = array(
				':id'=>$iconid,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			$row=$result_ary[0];

			$insqry=array(					
				'[menuname]'=>$menuname,
				'[formname]'=>$formname,
				'[menutype]'=>$menutypeid,
				'[iconid]'=>$iconid,
				'[iconstyle]'=>$row['iconstyle'],	
				'[iconclass]'=>$row['iconclass'],	
				'[iconunicode]'=>$row['iconunicode'],	
				'[alias]'=>$alias,		
				'[containright]'=>$containrights,					
			);
 
			if($formevent=='addright')  // insert
			{
				$qrychk="SELECT menuname from tblmenumaster where menuname=:menuname AND menutype=:menutype";
				$parms = array(
					':menuname'=>$menuname,
					':menutype'=>$menutype,
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

						$DB->executedata('i','tblmenumaster',$insqry,'');

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
			else if($formevent=='editright')  // Updtae
			{
				$qrychk="SELECT menuname from tblmenumaster where menuname=:menuname AND menutype=:menutype AND id<>:id";
				$parms = array(
					':menuname'=>$menuname,
					':menutype'=>$menutype,
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
						$DB->executedata('u','tblmenumaster',$insqry,$extraparams);

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
	else if($action=='deletemenumaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{

			$qrychk="SELECT case when(CONVERT(VARCHAR(255), ma.id) !='') then 1 else 0 end as tem
			from tblmenumaster mm
			left join tblmenuassign ma on ma.menuid = mm.id
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
				try 
				{
					$DB->begintransaction();

					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('d','tblmenumaster','',$extraparams);
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


					$qrychk="SELECT case when(CONVERT(VARCHAR(255), ma.id) !='') then 1 else 0 end as tem,mm.menuname
					from tblmenumaster mm
					left join tblmenuassign ma on ma.menuid = mm.id 
					where mm.id=:id";

					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$row=$result_ary[0];
						$usemenu.=$row['menuname'].",";
					}
					else
					{
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblmenumaster','',$extraparams);
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
	else if($action=='changecontainright')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{	
			try 
			{
				$DB->begintransaction();

				$qrychk="select containright from tblmenumaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];
				if($row['containright']==1)
				{
					$containright=0;
				}
				else
				{
					$containright=1;
				}
				$insqry['[containright]']=$containright;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tblmenumaster',$insqry,$extraparams);

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
	else if($action=='changedefaultopen')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{	
			try 
			{
				$DB->begintransaction();

				$qrychk="select defaultopen from tblmenumaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];
				if($row['defaultopen']==1)
				{
					$defaultopen=0;
				}
				else
				{
					$defaultopen=1;
				}
				$insqry['[defaultopen]']=$defaultopen;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tblmenumaster',$insqry,$extraparams);

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
	else if($action=='editmenumaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,menuname,formname,menutype,iconid,alias,containright from tblmenumaster where id=:id  ";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['menuname']=$IISMethods->sanitize($row['menuname']);
				$response['formname']=$IISMethods->sanitize($row['formname']);
				$response['menutype']=$IISMethods->sanitize($row['menutype']);
				$response['iconid']=$IISMethods->sanitize($row['iconid']);
				$response['alias']=$IISMethods->sanitize($row['alias']);
				$response['containright']=$IISMethods->sanitize($row['containright']);

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
	else if($action=='listmenumaster')   
	{
		$menus=new menu();
		$qry="select timestamp as primary_date,id,menuname,formname,alias,iconstyle,iconclass,iconunicode,containright,defaultopen,case when (menutype=1) then 'Web' when (menutype=2) then 'Mobile App' when (menutype=3) then 'POS' else '' end as webtype 
			from tblmenumaster where (menuname like :filter or (case when (menutype=1) then 'Web' when (menutype=2) then 'Mobile App' when (menutype=3) then 'POS' else '' end) like :typefilter) order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':filter'=>$filter,
			':typefilter'=>$filter,
		);
		$menus=$DB->getmenual($qry,$parms,'menu');
		if($responsetype=='HTML')
		{
			if($menus)
			{
				$i=0;
				foreach($menus as $menu)
				{
					$id="'".$menu->id."'";
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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$menu->id.'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					$htmldata.='<td class="tbl-w100">'.$IISMethods->sanitize($menu->webtype).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($menu->menuname).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($menu->formname).'</td>';
					$htmldata.='<td class="tbl-w100"><i class="'.$IISMethods->sanitize($menu->iconstyle.' '.$menu->iconclass).'"></i></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($menu->alias).'</td>';

					if($menu->containright==1)
					{
						$htmldata.='<td class="tbl-name"><label class="switch">';
							$htmldata.='<input type="checkbox" onclick="changecontainright('.$id.')" checked>';
							$htmldata.='<span class="slider round"></span>';
						$htmldata.='</label></td>';
					}
					else
					{
						$htmldata.='<td class="tbl-name"><label class="switch">';
							$htmldata.='<input type="checkbox" onclick="changecontainright('.$id.')">';
							$htmldata.='<span class="slider round"></span>';
						$htmldata.='</label></td>';
					}

					if($menu->defaultopen==1)
					{
						$htmldata.='<td class="tbl-name"><label class="switch">';
							$htmldata.='<input type="checkbox" onclick="changedefaultopen('.$id.')" checked>';
							$htmldata.='<span class="slider round"></span>';
						$htmldata.='</label></td>';
					}
					else
					{
						$htmldata.='<td class="tbl-name"><label class="switch">';
							$htmldata.='<input type="checkbox" onclick="changedefaultopen('.$id.')">';
							$htmldata.='<span class="slider round"></span>';
						$htmldata.='</label></td>';
					}

					$htmldata.='</tr>';
					$i++;
				}
					
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($menus)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($menus);
		}


		$common_listdata=$menus;
	}    
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  