<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='fillmenuassignlist')   
	{
		error_reporting(1);

		$menutypeid=$IISMethods->sanitize($_POST['menutypeid']);

		$qry="select * from tblmenuassign where menutypeid='$menutypeid' order by displayorder asc";
		$parms = array(
			':menutypeid'=>$menutypeid,
		);
		$result_ary=$DB->getmenual($qry);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$rowqry=$result_ary[$i];
				$htmldata.='<ul>';
				if($rowqry['isparent']==1 AND $rowqry['parentid']==0)
				{
					$aa='"'.$rowqry['iconstyle'];
					$bb=$rowqry['iconclass'].'"';

					$htmldata.='<li class="reqdata" data-id="'.$rowqry['id'].'" data-indi="'.$rowqry['isindividual'].'" data-flag="parent" data-moduleid="'.$rowqry['id'].'" class="jstree-open" data-jstree="{icon:'.$aa.' '.$bb.'}">';
					$htmldata.='<span style="font-size: 18px;" class="mainspan mainspan'.$rowqry['id'].'" data-formname = "'.$rowqry['formname'].'"><i class="'.$rowqry['iconstyle'].' '.$rowqry['iconclass'].'"></i> '.$rowqry['textname'].'</span>';
						$htmldata.='<input class="parent'.$rowqry['id'].'" type="hidden" id="parent" name="parent[]" value="1" />';
						$htmldata.='<input class="child'.$rowqry['id'].'" type="hidden" id="child" name="child[]" value="0"  />';
						$htmldata.='<input type="hidden" name="iconclass[]" id="iconclass" value="'.$rowqry['iconclass'].'" />';
						$htmldata.='<input type="hidden" name="alias[]" id="alias" value="'.$rowqry['alias'].'" />';
						$htmldata.='<input type="hidden" name="moduleid[]" id="moduleid" value="'.$rowqry['moduleid'].'" />';
						$htmldata.='<input type="hidden" name="menuid[]" id="menuid" value="'.$rowqry['menuid'].'" />';
						$htmldata.='<input type="hidden" name="containright[]" id="containright" value="'.$rowqry['containright'].'" />';
						$htmldata.='<input type="hidden" name="isindi[]" id="isindi" value="'.$rowqry['isindividual'].'" />';
						$htmldata.='<input type="hidden" name="defaultopen[]" id="defaultopen" value="'.$rowqry['defaultopen'].'" />';
						$htmldata.='<input type="hidden" name="iconunicode[]" id="iconunicode" value="'.$rowqry['iconunicode'].'" />';
						$htmldata.='<input type="hidden" name="iconstyle[]" id="iconstyle" value="'.$rowqry['iconstyle'].'" />';
						$htmldata.='<input type="hidden" name="textname[]" id="textname" value="'.$rowqry['textname'].'" />';
						$htmldata.='<input type="hidden" name="formname[]" id="formname" value="'.$rowqry['formname'].'" />';
						$htmldata.='<span data-id="'.$rowqry['id'].'" class="editreq editreq'.$rowqry['id'].'"><i class="bi bi-pencil-fill"></i></span>';
						$htmldata.='<span data-id="'.$rowqry['id'].'" class="editreq editreq'.$rowqry['id'].'"></span>';

					
					$selassignqry="select * from tblmenuassign where parentid=:parentid order by displayorder asc";
					$parms = array(
						':parentid'=>$rowqry['id'],
					);
					$result_subary=$DB->getmenual($selassignqry,$parms);

					for($j=0;$j<sizeof($result_subary);$j++)
					{
						$rowassignqry=$result_subary[$j]; 
					
						$htmldata.='<ul data-id="'.$rowqry['id'].'">';
							$htmldata.='<li data-indi="0" data-flag="child" class="reqdata" data-id="'.$rowassignqry['id'].'" data-jstree="{"icon":"'.$rowassignqry['iconstyle'].' '.$rowassignqry['iconclass'].'"}">';
							$htmldata.='<span style="font-size: 15px;padding-right: 100px;" class="subspan mainspan'.$rowassignqry['id'].'" data-formname = "'.$rowassignqry['formname'].'"><i class="'.$rowqry['iconstyle'].' '.$rowqry['iconclass'].'"></i> '.$rowassignqry['textname'].'<span data-id="'.$rowassignqry['id'].'" class=" editreq editreq'.$rowassignqry['id'].'">';
							$htmldata.='<i class="bi bi-pencil-fill"></i></span> <span data-id="'.$rowassignqry['id'].'" class=" deletereq deletereq'.$rowassignqry['id'].'">';
							$htmldata.='</span> <span data-id="'.$rowassignqry['id'].'" class=" deletereq deletereq'.$rowassignqry['id'].'">';
							//$htmldata.='<i class="fas fa-times"></i></span></span><input class="parent'.$rowassignqry['id'].'" type="hidden" id="parent" name="parent[]" value="0"  />';
							$htmldata.='</span></span><input class="parent'.$rowassignqry['id'].'" type="hidden" id="parent" name="parent[]" value="0"  />';
							$htmldata.='<input class="child'.$rowassignqry['id'].'" type="hidden" id="child" name="child[]" value="1" />';
							$htmldata.='<input type="hidden" name="textname[]" id="textname" value="'.$rowassignqry['textname'].'" />';
							$htmldata.='<input type="hidden" name="formname[]" id="formname" value="'.$rowassignqry['formname'].'" />';
							$htmldata.='<input type="hidden" name="iconclass[]" id="iconclass" value="'.$rowassignqry['iconclass'].'" />';
							$htmldata.='<input type="hidden" name="alias[]" id="alias" value="'.$rowassignqry['alias'].'"/>';
							$htmldata.='<input type="hidden" name="moduleid[]" id="moduleid" value="'.$rowassignqry['moduleid'].'" />';
							$htmldata.='<input type="hidden" name="menuid[]" id="menuid" value="'.$rowassignqry['menuid'].'" />';
							$htmldata.='<input type="hidden" name="containright[]" id="containright" value="'.$rowassignqry['containright'].'" />';
							$htmldata.='<input type="hidden" name="isindi[]" id="isindi" value="0" /><input type="hidden" name="defaultopen[]" id="defaultopen" value="'.$rowassignqry['defaultopen'].'" />';
							$htmldata.='<input type="hidden" name="iconunicode[]" id="iconunicode" value="'.$rowassignqry['iconunicode'].'" />';
							$htmldata.='<input type="hidden" name="iconstyle[]" id="iconstyle" value="'.$rowassignqry['iconstyle'].'" /></li> ';
						$htmldata.='</ul>';
			 		}

				}
				$htmldata.='</ul>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillmenuassign')   
	{
		$moduleid = $IISMethods->sanitize($_POST['moduleid']);
		$menutypeid =$IISMethods->sanitize($_POST['menutypeid']);

		$parms = array(
			'columns'=>"DISTINCT m.*,mc.moduleid,mc.isparent,CASE WHEN CAST(mc.id AS VARCHAR(36))!='''' THEN 1 ELSE 0 END AS tem",
			'tblnames'=>'tblmenumaster m LEFT JOIN tblmenuassign mc ON mc.menuid = m.id',
			'strwhr'=>"(isdelete=0 AND m.menutype = $menutypeid)",		
		);
		$result_ary=$DB->getdatafromsp('selectdata',$parms);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];

				$clshidden="1";
				$checked = "";
				if($row['tem']>0 && $moduleid==$row['moduleid'])
				{
					$checked = "checked";
				}
				else if($row['tem']>0)
				{
					$checked = "disabled";
					$clshidden="0";
				}
				
				$parentcheck = "";
				if($row['tem']>0 && $moduleid==$row['moduleid'] && $row['isparent']==1)
				{
					$parentcheck = "checked";
				}
				if($clshidden==1)
				{
					$htmldata.='<tr data-index="'.$IISMethods->sanitize($i).'">';
					$htmldata.='<td class="tbl-w100">';
					$htmldata.='<div class="text-center">';
					$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
					$htmldata.='<input type="checkbox" class="custom-control-input d-none tblchk" value="1"  id="tblchk'.$IISMethods->sanitize($i).'" name="tblchk[]" data-chktmp="'.$IISMethods->sanitize($i).'" '.$IISMethods->sanitize($checked).' >';
					$htmldata.='<label class="custom-control-label mb-0" for="tblchk'.$IISMethods->sanitize($i).'"></label>';
					$htmldata.='</div>';
					$htmldata.='</div>';
					$htmldata.='</td>';
					$htmldata.='<td class="tbl-name text-center">'.$IISMethods->sanitize($row['menuname']).'';
							$htmldata.='<input type="hidden" id="menuid'.$IISMethods->sanitize($i).'" name="menuid[]" value="'.$IISMethods->sanitize($row['id']).'" />';
							$htmldata.='<input type="hidden" id="menuname'.$IISMethods->sanitize($i).'" name="menuname[]" value="'.$IISMethods->sanitize($row['menuname']).'" />';
							$htmldata.='<input type="hidden" id="iconclass'.$IISMethods->sanitize($i).'" name="iconclass[]" value="'.$IISMethods->sanitize($row['iconclass']).'" />';
							$htmldata.='<input type="hidden" id="alias'.$IISMethods->sanitize($i).'" name="alias[]" value="'.$IISMethods->sanitize($row['alias']).'" />';
							$htmldata.='<input type="hidden" id="containright'.$IISMethods->sanitize($i).'" name="containright[]" value="'.$IISMethods->sanitize($row['containright']).'" /></td>';
					$htmldata.='<td class="tbl-w150">';
					$htmldata.='<div class="text-right">';
					$htmldata.='<div class="custom-control custom-radio">';
					$htmldata.='<input type="radio" class="custom-control-input" id="parentrad'.$IISMethods->sanitize($i).'" name="menurad[]"  '.$IISMethods->sanitize($parentcheck).'  value="1" >';
					$htmldata.='<label class="custom-control-label mb-0" for="parentrad'.$IISMethods->sanitize($i).'">Is Parent</label>';
					$htmldata.='</div>';
					$htmldata.='</div>';
					$htmldata.='</td>';
					$htmldata.='</tr>';
				}
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='insertmenudesign')   
	{
		$type=$_POST['type'];
		$parent=$_POST['parent'];
		$child=$_POST['child'];
		$textname=$_POST['textname'];
		$formname=$_POST['formname'];
		$iconclass=$_POST['iconclass'];
		$alias=$_POST['alias'];
		$moduleid=$_POST['moduleid'];
		$menuid=$_POST['menuid'];
		$containright=$_POST['containright'];
		
		$defaultopen=$_POST['defaultopen'];
		$isappmenu=$_POST['isappmenu'];
		$isposmenu=$_POST['isposmenu'];
		$appmenuname=$_POST['appmenuname'];
		$appcontainright=$_POST['appcontainright'];
		$iconunicode=$_POST['iconunicode'];
		$iswebmenu=$_POST['iswebmenu'];
		$iconstyle=$_POST['iconstyle'];

		$menutypeid=$_POST['menutypeid'];
		
		$entry_date = $IISMethods->getdatetime();

		$foundarr=array();
		if($textname)
		{

			try 
			{
				$DB->begintransaction();

				$parems = array(
					'[menutypeid]'=>$menutypeid,
				);
				$DB->executedata('d','tblmenuassign','',$parems);
	
				for($i=0;$i<sizeof($textname);$i++)
				{
					$unqid = $IISMethods->generateuuid();
					$parentid=0;
					if($parent[$i]==1)
					{
						$foundarr=array();
						array_push($foundarr,$unqid);
					}
					if($child[$i]==1)
					{
						$parentid=$foundarr[0];
					}
	
					
	
					$isparent=0;
					$isindividual=0;
					if($parentid==0 && $parent[$i]==1 && $child[$i]==0)
					{
						$isparent=1;
						$isindividual=1;
					}
	
					
					$insdata = array(
						'[id]'=>$unqid,
						'[menuname]'=>$textname[$i],
						'[textname]'=>$textname[$i],
						'[formname]'=>$formname[$i],
						'[iconstyle]'=>$iconstyle[$i],
						'[iconclass]'=>$iconclass[$i],
						'[iconunicode]'=>$iconunicode[$i],
						'[alias]'=>$alias[$i],
						'[isindividual]'=>$isindividual,
						'[isparent]'=>$isparent,
						'[parentid]'=>$parentid,
						'[moduleid]'=>$moduleid[$i],
						'[menuid]'=>$menuid[$i],
						'[containright]'=>$containright[$i],
						'[defaultopen]'=>$defaultopen[$i],
						'[menutypeid]'=>$menutypeid,
						'[displayorder]'=>$i,
				   );
				   $DB->executedata('i','tblmenuassign',$insdata,'','s');
				   if($parentid!='0')
				   {
						   $upddata = array(
							'[isindividual]'=>0,
						);
						$extraparams=array(
							'[id]'=>$parentid,
							'[menutypeid]'=>$menutypeid,
						);
						$DB->executedata('u','tblmenuassign',$upddata,$extraparams);
	
				   }
				}

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
	
	else if($action == 'menunamechange')
	{
	
		$id=$_POST['menuid'];
		$menuname=trim($_POST['menuname']);
		$formname=trim($_POST['formname']);
		$menutypeid=trim($_POST['menutypeid']);

		if($id)
		{
			try 
			{
				$DB->begintransaction();
				
				$upddata = array(
					'[textname]'=>$menuname,
					'[formname]'=>$formname
				);

				$extraparams=array(
					'[id]'=>$id,
					'[menutypeid]'=>$menutypeid,
				);

					// print_r($upddata);
					// print_r($extraparams);
				$DB->executedata('u','tblmenuassign',$upddata,$extraparams);
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

  