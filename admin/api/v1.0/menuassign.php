<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

error_reporting(1);
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='fillmodule')   
	{
		$menutypeid=$IISMethods->sanitize($_POST['menutypeid']);
		$qry="select id,modulename from tblmodulemaster where moduletypeid=:moduletypeid  order by timestamp asc";
		$parms = array(
			':moduletypeid'=>$menutypeid,
		);
		$result_ary=$DB->getmenual($qry,$parms);
		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['modulename'].'</option>';
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

		$qry="select DISTINCT m.*,mc.moduleid,mc.isparent,CASE WHEN CAST(mc.id AS VARCHAR(36))!='''' THEN 1 ELSE 0 END AS tem from tblmenumaster m LEFT JOIN tblmenuassign mc ON mc.menuid = m.id where m.menutype=:moduletypeid order by m.timestamp asc";
		$parms = array(
			':moduletypeid'=>$menutypeid,
		);
		$result_ary=$DB->getmenual($qry,$parms);

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
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($row['menuname']).'';
						$htmldata.='<input type="hidden" id="menuid'.$IISMethods->sanitize($i).'" name="menuid[]" value="'.$IISMethods->sanitize($row['id']).'" />';
						$htmldata.='<input type="hidden" id="menuname'.$IISMethods->sanitize($i).'" name="menuname[]" value="'.$IISMethods->sanitize($row['menuname']).'" />';
						$htmldata.='<input type="hidden" id="formname'.$IISMethods->sanitize($i).'" name="formname[]" value="'.$IISMethods->sanitize($row['formname']).'" />';
						$htmldata.='<input type="hidden" id="iconclass'.$IISMethods->sanitize($i).'" name="iconclass[]" value="'.$IISMethods->sanitize($row['iconclass']).'" />';
						$htmldata.='<input type="hidden" id="alias'.$IISMethods->sanitize($i).'" name="alias[]" value="'.$IISMethods->sanitize($row['alias']).'" />';
						$htmldata.='<input type="hidden" id="containright'.$IISMethods->sanitize($i).'" name="containright[]" value="'.$IISMethods->sanitize($row['containright']).'" />';
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
	else if($action=='insertmenuassign')   
	{
		$moduleid=$_POST['moduleid'];
		$checkboxval=explode(',',$_POST['checkboxval']);
		$radboxval=explode(',',$_POST['radboxval']);
		$menuid=$_POST['menuid'];
		$menuname=$_POST['menuname'];
		$formname=$_POST['formname'];
		$iconclass=$_POST['iconclass'];
		$alias=$_POST['alias'];
		$containright=$_POST['containright'];
		$defaultmenu=$_POST['defaultmenu'];
		$menurad=$_POST['menurad'];

		$extraparams=array(
			'[moduleid]'=>$moduleid
		);
		$DB->executedata('d','tblmenuassign','',$extraparams);
		$parentid=0;
		if($moduleid)
		{
			try 
			{
				$DB->begintransaction();

				for($i=0;$i<sizeof($menuid);$i++)
				{
					if($checkboxval[$i] == 1)
					{
						if($radboxval[$i] == 1)
						{
							$qry="select id,iconstyle,iconclass,iconunicode,alias,containright,defaultopen,menutype from tblmenumaster where id=:id";
							$parms = array(
								':id'=>$menuid[$i],
							);
							$result_ary=$DB->getmenual($qry,$parms);

							$rowmenu = $result_ary[0];

							$unqid = $IISMethods->generateuuid();
							$parentid=$unqid;
							$insdata = array(
								'[id]'=>$unqid,
								'[menuname]'=>$menuname[$i],
								'[formname]'=>$formname[$i],
								'[textname]'=>$menuname[$i],
								'[iconstyle]'=>$rowmenu['iconstyle'],
								'[iconclass]'=>$rowmenu['iconclass'],
								'[iconunicode]'=>$rowmenu['iconunicode'],
								'[alias]'=>$rowmenu['alias'],
								'[isindividual]'=>1,
								'[isparent]'=>1,
								'[parentid]'=>0,
								'[moduleid]'=>$moduleid,
								'[menuid]'=>$menuid[$i],
								'[containright]'=>$rowmenu['containright'],
								'[defaultopen]'=>$rowmenu['defaultopen'],
								'[menutypeid]'=>$rowmenu['menutype'],
							);
							$DB->executedata('i','tblmenuassign',$insdata,'');
						}
					}
				}
				
				for($i=0;$i<sizeof($menuid);$i++)
				{
					if($checkboxval[$i] == 1)
					{
						if($radboxval[$i] == 0)
						{
							$qry="select id,iconstyle,iconclass,iconunicode,alias,containright,defaultopen,menutype from tblmenumaster where id=:id";
							$parms = array(
								':id'=>$menuid[$i],
							);
							$result_ary=$DB->getmenual($qry,$parms);
							$rowmenu = $result_ary[0];
							
							$unqid = $IISMethods->generateuuid();
							$insdata = array(
								'[id]'=>$unqid,
								'[menuname]'=>$menuname[$i],
								'[formname]'=>$formname[$i],
								'[textname]'=>$menuname[$i],
								'[iconstyle]'=>$rowmenu['iconstyle'],
								'[iconclass]'=>$rowmenu['iconclass'],
								'[iconunicode]'=>$rowmenu['iconunicode'],
								'[alias]'=>$rowmenu['alias'],
								'[isindividual]'=>0,
								'[isparent]'=>0,
								'[parentid]'=>$parentid,
								'[moduleid]'=>$moduleid,
								'[menuid]'=>$menuid[$i],
								'[containright]'=>$rowmenu['containright'],
								'[defaultopen]'=>$rowmenu['defaultopen'],
								'[menutypeid]'=>$rowmenu['menutype'],
							);
							$DB->executedata('i','tblmenuassign',$insdata,'');
							if($parentid != '0')
							{
								$upddata = array(
									'[isindividual]'=>0,
								);
								$extraparams=array(
									'[id]'=>$parentid
								);
								$DB->executedata('u','tblmenuassign',$upddata,$extraparams);
							}
						}
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
		else 
		{
			$message=$errmsg['reqired'];
		}
	}  
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  