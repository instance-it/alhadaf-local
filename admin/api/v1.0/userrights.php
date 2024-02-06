<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='fillusertype')   
	{
		$menutypeid =$_POST['menutypeid'];
		$aminutype=$config->getAdminutype();

		$parms = array(
			':id'=>$aminutype,
			':memberutypeid'=>$config->getMemberutype(),
		);
		$qry="select * from tblusertypemaster where id<>:id and id<>:memberutypeid order by timestamp asc";

		// print_r($parms);
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['usertype'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillperson')   
	{
		$usertypeid=$_POST['usertypeid'];
		$parms = array(
			':usertypeid'=>$usertypeid,
		);
		$qry="select pm.id,pm.personname,pm.contact from tblpersonmaster pm inner join tblpersonutype pu on pu.pid=pm.id where pu.utypeid=:usertypeid";
		$result_ary=$DB->getmenual($qry,$parms);
		
		$htmldata='';
		
		if(sizeof($result_ary)>0)
		{
			$htmldata.='<option value="0">All </option>';
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['personname'].' ('.$row['contact'].')</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	} 
	else if($action=='filluserrighttable')   
	{
		$personid = $_POST['personid'];
		$usertypeid =$_POST['usertypeid'];
		$menutypeid =$_POST['menutypeid'];


		if($personid === '0')
		{
			$personid = '';
		}

		$subqry = "  AND type = $menutypeid AND (CONVERT(VARCHAR(255), usertypeid) IN ('".$usertypeid."') AND CONVERT(VARCHAR(255), personid) ='".$personid."') ";
		if($personid!='')
		{
			$subqry = " AND type = $menutypeid AND  (CONVERT(VARCHAR(255), personid) IN ('".$personid."')) ";
			
		}

		$qry="select * from tblmenuassign where (isindividual = 1 OR isparent = 0 OR menuname like 'Reports') AND menutypeid = :menutypeid AND containright=1 order by timestamp asc";
		$parms = array(
			':menutypeid'=>$menutypeid,
		);
		$result_ary=$DB->getmenual($qry,$parms);

		if(sizeof($result_ary)>0)
		{
			$htmldata='';
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$rowcat=$result_ary[$i];

				$qry="select * from tbluserrights where formname='".$rowcat['alias']."' $subqry";
				$seluser=$DB->getmenual($qry);

				$rowuser = $seluser[0];

				$allviewright="";
				$selfviewright="";
				$alladdright="";
				$selfaddright="";
				$alleditright="";
				$selfeditright="";
				$alldelright="";
				$selfdelright="";
				$allprintright="";
				$selfprintright="";
				$allreqright="";
				$allchangepriceright="";
				if($rowuser['allviewright']==1)
				{
					$allviewright="checked";
				}
				else if($rowuser['selfviewright']==1)
				{
					$selfviewright="checked";
				}
				if($rowuser['alladdright']==1)
				{
					$alladdright="checked";
				}
				else if($rowuser['selfaddright']==1)
				{
					$selfaddright="checked";
				}
				if($rowuser['alleditright']==1)
				{
					$alleditright="checked";
				}
				else if($rowuser['selfeditright']==1)
				{
					$selfeditright="checked";
				}
				if($rowuser['alldelright']==1)
				{
					$alldelright="checked";
				}
				else if($rowuser['selfdelright']==1)
				{
					$selfdelright="checked";
				}
				if($rowuser['allprintright']==1)
				{
					$allprintright="checked";
				}
				else if($rowuser['selfprintright']==1)
				{
					$selfprintright="checked";
				}
				if($rowuser['requestright']==1)
				{
					$allreqright="checked";
				}
				if($rowuser['changepriceright']==1){
					$allchangepriceright="checked";
				}


				$htmldata.='<tr>';
				$htmldata.='<td class="tbl-name tdpagename">'.$rowcat['textname'].'';
				$htmldata.='<input type="hidden" name="'.$i.'formtype" id="'.$i.'formtype" VALUE="'.$rowcat['textname'].'" />';
				$htmldata.='<input type="hidden" name="'.$i.'formurl" id="'.$i.'formurl" VALUE="'.$rowcat['alias'].'" />';
				$htmldata.='<input type="hidden" name="'.$i.'pageid" id="'.$i.'pageid" VALUE="'.$rowcat['id'].'" />';
				$htmldata.='</td>';


				$htmldata.='<td class="tbl-name tdview">';
				$htmldata.='<div class="d-flex text-center">';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$allviewright.' type="checkbox" class="custom-control-input d-none tdallview viewrightcls vrcls'.$i.'" value="1" name="'.$i.'allviewright" id="allviewright'.$i.'" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="allviewright'.$i.'">All</label>';
				$htmldata.='</div>';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$selfviewright.' type="checkbox" class="custom-control-input d-none tdselfview viewrightcls vrcls'.$i.'" value="1"  name="'.$i.'selfviewright" id="selfviewright'.$i.'" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="selfviewright'.$i.'">Self</label>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</td>';

				$htmldata.='<td class="tbl-name tdadd">';
				$htmldata.='<div class="d-flex text-center">';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$alladdright.' type="checkbox" class="custom-control-input d-none tdalladd addrightcls arcls'.$i.'" value="1"  id="alladdright'.$i.'" name="'.$i.'alladdright" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="alladdright'.$i.'">All</label>';
				$htmldata.='</div>';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$selfaddright.' type="checkbox" class="custom-control-input d-none tdselfadd addrightcls arcls'.$i.'" value="1"  id="selfaddright'.$i.'" name="'.$i.'selfaddright" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="selfaddright'.$i.'">Self</label>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</td>';

				$htmldata.='<td class="tbl-name">';
				$htmldata.='<div class="d-flex text-center">';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$alleditright.' type="checkbox" class="custom-control-input d-none tdallupdate editrightcls ercls'.$i.'" value="1"  id="alleditright'.$i.'" name="'.$i.'alleditright" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="alleditright'.$i.'">All</label>';
				$htmldata.='</div>';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$selfeditright.' type="checkbox" class="custom-control-input d-none tdselfupdate editrightcls ercls'.$i.'" value="1"  id="selfeditright'.$i.'" name="'.$i.'selfeditright" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="selfeditright'.$i.'">Self</label>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</td>';

				$htmldata.='<td class="tbl-name">';
				$htmldata.='<div class="d-flex text-center">';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$alldelright.' type="checkbox" class="custom-control-input d-none tdalldelete deleterightcls drcls'.$i.'" value="1"  id="alldeleteright'.$i.'" name="'.$i.'alldeleteright" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="alldeleteright'.$i.'">All</label>';
				$htmldata.='</div>';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$selfdelright.' type="checkbox" class="custom-control-input d-none tdselfdelete deleterightcls drcls'.$i.'" value="1"  id="selfdeleteright'.$i.'" name="'.$i.'selfdeleteright" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="selfdeleteright'.$i.'">Self</label>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</td>';

				$htmldata.='<td class="tbl-name">';
				$htmldata.='<div class="d-flex text-center">';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$allprintright.' type="checkbox" class="custom-control-input d-none tdallprint printrightcls prcls'.$i.'" value="1"  id="allprintright'.$i.'" name="'.$i.'allprintright" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="allprintright'.$i.'">All</label>';
				$htmldata.='</div>';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$selfprintright.' type="checkbox" class="custom-control-input d-none tdselfprint printrightcls prcls'.$i.'" value="1"  id="selfprintright'.$i.'" name="'.$i.'selfprintright" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="selfprintright'.$i.'">Self</label>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</td>';

				$htmldata.='<td class="tbl-name d-none">';
				$htmldata.='<div class="d-flex text-center">';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$allreqright.' type="checkbox" class="custom-control-input d-none tdallreq reqrightcls prcls'.$i.'" value="1"  id="allreqright'.$i.'" name="'.$i.'allreqright" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="allreqright'.$i.'"></label>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</td>';

				$htmldata.='<td class="tbl-name d-none">';
				$htmldata.='<div class="d-flex text-center">';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$allchangepriceright.' type="checkbox" class="custom-control-input d-none tdallprice pricerightcls prcls'.$i.'" value="1"  id="allpriceright'.$i.'" name="'.$i.'allpriceright" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="allpriceright'.$i.'"></label>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</td>';

				$htmldata.='</tr>';
			}
			$htmldata.='<input type="hidden" name="totalmenus" id="totalmenus" value="'.sizeof($result_ary).'" />	';
			
			
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}  
	else if($action=='insertuserright')   
	{
		$usertype=$_POST['usertypeid'];
		$personid=$_POST['personid'];
		$menutypeid=$_POST['menutypeid'];

		try 
		{
			$DB->begintransaction();

			if($_POST['personid'] === '0')
			{
				$extraparams=array(
					'[usertypeid]'=>$usertype,
					'[personid]'=>'',
					'[type]'=>$menutypeid,
				);
				$DB->executedata('d','tbluserrights','',$extraparams);
			}
			else 
			{
				$extraparams=array(
					'[personid]'=>$personid,
					'[type]'=>$menutypeid
				);
				$DB->executedata('d','tbluserrights','',$extraparams);
			}


			if($_POST['personid'] === '0')
			{
				$personid = '';
			}

			$totalmenus=$_POST['totalmenus'];

			for ($i=0 ; $i<=$totalmenus ; $i++)
			{
				$allviewright = $_POST[$i."allviewright"];
				
				$selfviewright = $_POST[$i."selfviewright"];
				$alladdright = $_POST[$i."alladdright"];
				$selfaddright = $_POST[$i."selfaddright"];
				$alleditright = $_POST[$i."alleditright"];
				$selfeditright = $_POST[$i."selfeditright"];
				$alldeleteright = $_POST[$i."alldeleteright"];
				$selfdeleteright = $_POST[$i."selfdeleteright"];
				$allprintright = $_POST[$i."allprintright"];
				$selfprintright = $_POST[$i."selfprintright"];
				$allreqright = $_POST[$i."allreqright"];
				$allchangepriceright = $_POST[$i."allpriceright"];
				
				if ($allviewright || $selfviewright || $alladdright || $selfaddright ||$alleditright ||  $selfeditright || $alldeleteright || $selfdeleteright || $allprintright || $selfprintright || $allreqright || $allchangepriceright)
				{
					$viewright=0;
					$hallviewright=0;
					$hselfviewright=0;
					$addright=0;
					$halladdright=0;
					$hselfaddright=0;
					$editright=0;
					$halleditright=0;
					$hselfeditright=0;
					$deleteright=0;
					$halldeleteright=0;
					$hselfdeleteright=0;
					$printright=0;
					$hallprintright=0;
					$hselfprintright=0;
					
					if($allviewright || $selfviewright)
					{
						$viewright=1;
						if($allviewright==1)
							$hallviewright=1;
						
						if($selfviewright==1)
							$hselfviewright=1;
					}
					if($alladdright || $selfaddright)
					{
						$addright=1;
						
						if($alladdright==1)
							$halladdright=1;
						
						if($selfaddright==1)
							$hselfaddright=1;
					}
					if($alleditright || $selfeditright)
					{
						$editright=1;
						if($alleditright==1)
							$halleditright=1;
						
						if($selfeditright==1)
							$hselfeditright=1;
					}
					if($alldeleteright || $selfdeleteright)
					{
						$deleteright=1;
						if($alldeleteright==1)
							$halldeleteright=1;
						
						if($selfdeleteright==1)
							$hselfdeleteright=1;
					}
					if($allprintright || $selfprintright)
					{
						$printright=1;
						if($allprintright==1)
							$hallprintright=1;
						
						if($selfprintright==1)
							$hselfprintright=1;
					}
					
					$hallreqright=0;
					if($allreqright==1)
					{
						$hallreqright=1;
					}
					
					
					$changepriceright=0;
					if($allchangepriceright==1)
					{
						$changepriceright=1;
					}
					
					$formname = $_POST[$i."formtype"];
					$formurl = $_POST[$i."formurl"];
					$formname = trim($formname);
					$formurl = trim($formurl);
					
					$unqid = $IISMethods->generateuuid();
					$insdata=array(
						'[id]'=>$unqid,
						'[formname]'=>$formurl,
						'[formnametext]'=>$formname,
						'[allow]'=>1,
						'[viewright]'=>$viewright,
						'[allviewright]'=>$hallviewright,
						'[selfviewright]'=>$hselfviewright,
						'[addright]'=>$addright,
						'[alladdright]'=>$halladdright,
						'[selfaddright]'=>$hselfaddright,
						'[editright]'=>$editright,
						'[alleditright]'=>$halleditright,
						'[selfeditright]'=>$hselfeditright,
						'[delright]'=>$deleteright,
						'[alldelright]'=>$halldeleteright,
						'[selfdelright]'=>$hselfdeleteright,
						'[printright]'=>$printright,
						'[allprintright]'=>$hallprintright,
						'[selfprintright]'=>$hselfprintright,
						'[requestright]'=>$hallreqright,
						'[changepriceright]'=>$changepriceright,
						'[usertypeid]'=>$usertype,
						'[personid]'=>$personid,
						'[entry_uid]'=>$uid,
						'[entry_date]'=>$IISMethods->getdatetime(),
						'[type]'=>$menutypeid,
					);
					$DB->executedata('i','tbluserrights',$insdata,'');
					
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
	else if($action=='userrights')
	{
		
		$menutypeid =$_POST['menutypeid'];

		$qryperson="SELECT ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), pu.utypeid) AS [text()] FROM tblpersonutype pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,1000),'') as usertypeid
			FROM tblpersonmaster pm
			WHERE pm.id=:uid";
		$parmsperson = array(
			':uid'=>$uid,
		);
		$resultper_ary=$DB->getmenual($qryperson,$parmsperson);
		$rowper=$resultper_ary[0];
		$utypeid=$rowper['usertypeid'];


		$qry="select * from tblmenuassign where (isindividual = 1 OR isparent = 0 OR menuname like 'Reports') AND menutypeid=:menutypeid AND containright=1 order by timestamp asc";
		$parms = array(
			':menutypeid'=>$menutypeid,
		);
		$result_ary=$DB->getmenual($qry,$parms);

		if(sizeof($result_ary)>0)
		{
			try 
			{
				$DB->begintransaction();

				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$response['parent'][$i]['pagename'] = $row['textname'];
					$response['parent'][$i]['appmenuname'] = $row['menuname'];
					$response['parent'][$i]['isindividual'] = (int)$row['isindividual'];
					$response['parent'][$i]['icon'] = '&#x'.$row['iconunicode'].';';
					$response['parent'][$i]['iconunicode'] = $row['iconunicode'];
					$response['parent'][$i]['iconstyle'] = $row['iconstyle'];
					$response['parent'][$i]['iconclass'] = $row['iconclass'];

					$viewright=0;
					$viewallright=0;
					$viewselfright=0;
					$addright=0;
					$addallright=0;
					$addselfright=0;
					$editright=0;
					$editallright=0;
					$editselfright=0;
					$delright=0;
					$delallright=0;
					$delselfright=0;
					$printright=0;
					$printallright=0;
					$printselfright=0;
					$changepriceright=0;


					$qryuser="SELECT TOP 1 ISNULL(id,'') AS id FROM tbluserrights WHERE personid =:personid";
					$paramuser=array(':personid'=>$uid);
					$resuser=$DB->getmenual($qryuser,$paramuser);

					$parmsur = array();
					$qryur="SELECT formname,
					CASE WHEN(SUM(allow)=0) THEN 0 ELSE 1 END AS allow, 
					CASE WHEN(SUM(viewright)=0) THEN 0 ELSE 1 END AS viewright,
					CASE WHEN(SUM(allviewright)=0) THEN 0 ELSE 1 END AS allviewright,
					CASE WHEN(SUM(selfviewright)=0) THEN 0 ELSE 1 END AS selfviewright,
					CASE WHEN(SUM(addright)=0) THEN 0 ELSE 1 END AS addright,
					CASE WHEN(SUM(alladdright)=0) THEN 0 ELSE 1 END AS alladdright,
					CASE WHEN(SUM(selfaddright)=0) THEN 0 ELSE 1 END AS selfaddright, 
					CASE WHEN(SUM(editright)=0) THEN 0 ELSE 1 END AS editright, 
					CASE WHEN(SUM(alleditright)=0) THEN 0 ELSE 1 END AS alleditright,
					CASE WHEN(SUM(selfeditright)=0) THEN 0 ELSE 1 END AS selfeditright,
					CASE WHEN(SUM(delright)=0) THEN 0 ELSE 1 END AS delright, 
					CASE WHEN(SUM(alldelright)=0) THEN 0 ELSE 1 END AS alldelright,
					CASE WHEN(SUM(selfdelright)=0) THEN 0 ELSE 1 END AS selfdelright,
					CASE WHEN(SUM(printright)=0) THEN 0 ELSE 1 END AS printright,
					CASE WHEN(SUM(allprintright)=0) THEN 0 ELSE 1 END AS allprintright,
					CASE WHEN(SUM(selfprintright)=0) THEN 0 ELSE 1 END AS selfprintright,
					CASE WHEN(SUM(changepriceright)=0) THEN 0 ELSE 1 END AS changepriceright
					FROM tbluserrights 
					WHERE formname=:formname AND type=:type ";
					
					if(sizeof($resuser)>0 && $resuser[0]['id']!='')
					{
						$qryur.=" AND personid IN (:uid)";
						$parmsur[':uid']=$uid;
					}
					else
					{
						$qryur.=" AND usertypeid IN (:utypeid) AND personid =''";
						$parmsur[':utypeid']=$utypeid;
					}
					$qryur.=" GROUP BY formname";

					$parmsur[':type']=$menutypeid;
					$parmsur[':formname']=$row['menuname'];

					
					$resur=$DB->getmenual($qryur,$parmsur);
					$numur=sizeof($resur);
					$rowur=$resur[0];

					if($numur > 0)
					{
						$viewright=$rowur['viewright'];
						$viewallright=$rowur['allviewright'];
						$viewselfright=$rowur['selfviewright'];
						$addright=$rowur['addright'];
						$addallright=$rowur['alladdright'];
						$addselfright=$rowur['selfaddright'];
						$editright=$rowur['editright'];
						$editallright=$rowur['alleditright'];
						$editselfright=$rowur['selfeditright'];
						$delright=$rowur['delright'];
						$delallright=$rowur['alldelright'];
						$delselfright=$rowur['selfdelright'];
						$printright=$rowur['printright'];
						$printallright=$rowur['allprintright'];
						$printselfright=$rowur['selfprintright'];
						$changepriceright=$rowur['changepriceright'];
					}

					if($row['containright'] == 0 || $uid=='034FB884-A865-4127-B90B-3D06047A72CC') //default page rights
					{
						$viewright=1;
						$viewallright=1;
						$viewselfright=0;
						$addright=1;
						$addallright=1;
						$addselfright=0;
						$editright=1;
						$editallright=1;
						$editselfright=0;
						$delright=1;
						$delallright=1;
						$delselfright=0;
						$printright=1;
						$printallright=1;
						$printselfright=0;
					}

					$response['parent'][$i]['viewright'] = (int)$viewright;
					$response['parent'][$i]['viewallright'] = (int)$viewallright;
					$response['parent'][$i]['viewselfright'] = (int)$viewselfright;
					$response['parent'][$i]['addright'] = (int)$addright;
					$response['parent'][$i]['addallright'] = (int)$addallright;
					$response['parent'][$i]['addselfright'] = (int)$addselfright;
					$response['parent'][$i]['editright'] = (int)$editright;
					$response['parent'][$i]['editallright'] = (int)$editallright;
					$response['parent'][$i]['editselfright'] = (int)$editselfright;
					$response['parent'][$i]['delright'] = (int)$delright;
					$response['parent'][$i]['delallright'] = (int)$delallright;
					$response['parent'][$i]['delselfright'] = (int)$delselfright;
					$response['parent'][$i]['printright'] = (int)$printright;
					$response['parent'][$i]['printallright'] = (int)$printallright;
					$response['parent'][$i]['printselfright'] = (int)$printselfright;
					$response['parent'][$i]['changepriceright'] = (int)$changepriceright;

					$selassignqry = "SELECT * FROM tblmenuassign WHERE parentid=:parentid AND menutypeid=:menutypeid";
					$paramsel=array(':menutypeid'=>$menutypeid,':parentid'=>$row['id']);
					$resassignqry = $DB->getmenual($selassignqry,$paramsel);
					$numassignqry=sizeof($resassignqry);
					if($numassignqry>0)
					{
						for($j=0;$j<sizeof($resassignqry);$j++)
						{
							$rowassignqry=$resassignqry[$j];
							$response['parent'][$i]['child'][$j]['pagename'] = $rowassignqry['textname'];
							$response['parent'][$i]['child'][$j]['appmenuname'] = $rowassignqry['menuname'];
							$response['parent'][$i]['child'][$j]['isindividual'] = 0;
							$response['parent'][$i]['child'][$j]['icon'] ='&#x'.$rowassignqry['iconunicode'].';';
							$response['parent'][$i]['child'][$j]['iconunicode'] = $rowassignqry['iconunicode'];
							$response['parent'][$i]['child'][$j]['iconstyle'] = $rowassignqry['iconstyle'];
							$response['parent'][$i]['child'][$j]['iconclass'] = $rowassignqry['iconclass'];

							$chviewright=0;
							$chviewallright=0;
							$chviewselfright=0;
							$chaddright=0;
							$chaddallright=0;
							$chaddselfright=0;
							$cheditright=0;
							$cheditallright=0;
							$cheditselfright=0;
							$chdelright=0;
							$chdelallright=0;
							$chdelselfright=0;
							$chprintright=0;
							$chprintallright=0;
							$chprintselfright=0;
							$chchangepriceright=0;

							$parmsur1 = array();
							$qryur1="SELECT formname,
								CASE WHEN(SUM(allow)=0) THEN 0 ELSE 1 END AS allow, 
								CASE WHEN(SUM(viewright)=0) THEN 0 ELSE 1 END AS viewright,
								CASE WHEN(SUM(allviewright)=0) THEN 0 ELSE 1 END AS allviewright,
								CASE WHEN(SUM(selfviewright)=0) THEN 0 ELSE 1 END AS selfviewright,
								CASE WHEN(SUM(addright)=0) THEN 0 ELSE 1 END AS addright,
								CASE WHEN(SUM(alladdright)=0) THEN 0 ELSE 1 END AS alladdright,
								CASE WHEN(SUM(selfaddright)=0) THEN 0 ELSE 1 END AS selfaddright, 
								CASE WHEN(SUM(editright)=0) THEN 0 ELSE 1 END AS editright, 
								CASE WHEN(SUM(alleditright)=0) THEN 0 ELSE 1 END AS alleditright,
								CASE WHEN(SUM(selfeditright)=0) THEN 0 ELSE 1 END AS selfeditright,
								CASE WHEN(SUM(delright)=0) THEN 0 ELSE 1 END AS delright, 
								CASE WHEN(SUM(alldelright)=0) THEN 0 ELSE 1 END AS alldelright,
								CASE WHEN(SUM(selfdelright)=0) THEN 0 ELSE 1 END AS selfdelright,
								CASE WHEN(SUM(printright)=0) THEN 0 ELSE 1 END AS printright,
								CASE WHEN(SUM(allprintright)=0) THEN 0 ELSE 1 END AS allprintright,
								CASE WHEN(SUM(selfprintright)=0) THEN 0 ELSE 1 END AS selfprintright,
								CASE WHEN(SUM(changepriceright)=0) THEN 0 ELSE 1 END AS changepriceright
								FROM tbluserrights 
								WHERE formname=:formname AND type=:type ";
								
								if(sizeof($resuser)>0 && $resuser[0]['id']!='')
								{
									$qryur1.=" AND personid IN (:uid)";
									$parmsur1[':uid']=$uid;
								}
								else
								{
									$qryur1.=" AND usertypeid IN (:utypeid) AND personid =''";
									$parmsur1[':utypeid']=$utypeid;
								}
								$qryur1.=" GROUP BY formname";

								$parmsur1[':type']=$menutypeid;
								$parmsur1[':formname']=$rowassignqry['menuname'];

								
								$resur1=$DB->getmenual($qryur1,$parmsur1);
								$numur1=sizeof($resur1);
								$rowur1=$resur1[0];

								if($numur1 > 0)
								{
									$chviewright=$rowur1['viewright'];
									$chviewallright=$rowur1['allviewright'];
									$chviewselfright=$rowur1['selfviewright'];
									$chaddright=$rowur1['addright'];
									$chaddallright=$rowur1['alladdright'];
									$chaddselfright=$rowur1['selfaddright'];
									$cheditright=$rowur1['editright'];
									$cheditallright=$rowur1['alleditright'];
									$cheditselfright=$rowur1['selfeditright'];
									$chdelright=$rowur1['delright'];
									$chdelallright=$rowur1['alldelright'];
									$chdelselfright=$rowur1['selfdelright'];
									$chprintright=$rowur1['printright'];
									$chprintallright=$rowur1['allprintright'];
									$chprintselfright=$rowur1['selfprintright'];
									$chchangepriceright=$rowur1['changepriceright'];
								}
								if($rowassignqry['containright'] == 0 || $uid==$config->getAdminUserId()) //default page rights
								{
									$chviewright=1;
									$chviewallright=1;
									$chviewselfright=0;
									$chaddright=1;
									$chaddallright=1;
									$chaddselfright=0;
									$cheditright=1;
									$cheditallright=1;
									$cheditselfright=0;
									$chdelright=1;
									$chdelallright=1;
									$chdelselfright=0;
									$chprintright=1;
									$chprintallright=1;
									$chprintselfright=0;
									$chchangepriceright=0;
								}

								$response['parent'][$i]['child'][$j]['viewright'] = (int)$chviewright;
								$response['parent'][$i]['child'][$j]['viewallright'] = (int)$chviewallright;
								$response['parent'][$i]['child'][$j]['viewselfright'] = (int)$chviewselfright;
								$response['parent'][$i]['child'][$j]['addright'] = (int)$chaddright;
								$response['parent'][$i]['child'][$j]['addallright'] = (int)$chaddallright;
								$response['parent'][$i]['child'][$j]['addselfright'] = (int)$chaddselfright;
								$response['parent'][$i]['child'][$j]['editright'] = (int)$cheditright;
								$response['parent'][$i]['child'][$j]['editallright'] = (int)$cheditallright;
								$response['parent'][$i]['child'][$j]['editselfright'] = (int)$cheditselfright;
								$response['parent'][$i]['child'][$j]['delright'] = (int)$chdelright;
								$response['parent'][$i]['child'][$j]['delallright'] = (int)$chdelallright;
								$response['parent'][$i]['child'][$j]['delselfright'] = (int)$chdelselfright;
								$response['parent'][$i]['child'][$j]['printright'] = (int)$chprintright;
								$response['parent'][$i]['child'][$j]['printallright'] = (int)$chprintallright;
								$response['parent'][$i]['child'][$j]['printselfright'] = (int)$chprintselfright;
								$response['parent'][$i]['child'][$j]['changepriceright'] = (int)$chchangepriceright;


						}

					}


				}
				$status=1;
				$message=$errmsg['success'];

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

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  