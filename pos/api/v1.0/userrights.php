<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	
	if($action=='userrights')
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


		$qry="select * from tblmenuassign where (isindividual = 1 OR isparent = 0 OR menuname like 'Reports') AND menutypeid=:menutypeid order by timestamp asc";
		$parms = array(
			':menutypeid'=>$menutypeid,
		);
		$result_ary=$DB->getmenual($qry,$parms);

		if(sizeof($result_ary)>0)
		{
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


				$qryuser="SELECT TOP 1 ISNULL(id,'') AS id FROM tbluserrights WHERE personid =:personid AND type=:type ";
				$paramuser=array(
					':personid'=>$uid,
					':type'=>$menutypeid,
				);
				$resuser=$DB->getmenual($qryuser,$paramuser);

				$parmsur = array(
					':formname'=>$row['menuname'],
					':type'=>$menutypeid,
				);
				$parmsut = array();
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
					$qryur.=" AND personid=:uid";
					$parmsur[':uid']=$uid;
				}
				else
				{
					$usertypeidarr = explode(",",$utypeid);
					$parmsut = array_combine(
						array_map(function($i){ return ':id'.$i; }, array_keys($usertypeidarr)),
						$usertypeidarr
					);
					$usertypeidkey = implode(',', array_keys($parmsut));
		
					$qryur.=" AND usertypeid IN ($usertypeidkey) AND CONVERT(VARCHAR(255), personid) =''";
					//$parmsur[':utypeid']=$utypeid;
				}
				$qryur.=" GROUP BY formname";


				$params=array_merge($parmsur,$parmsut);
				$resur=$DB->getmenual($qryur,$params);

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

				if($row['containright'] == 0 || $uid==$config->getAdminUserId()) //default page rights
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
								$qryur1.=" AND personid=:uid";
								$parmsur1[':uid']=$uid;
							}
							else
							{
								$qryur1.=" AND usertypeid=:utypeid AND personid =''";
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
		}

	}
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  