<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'/config/init.php';
require_once dirname(__DIR__, 3).'/config/apiconfig.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='filldashuserrighttable')   
	{
		$menutypeid = $_POST['menutypeid'];
		$personid = $_POST['personid'];
		$usertypeid =$_POST['usertypeid'];


		if($personid === '0')
		{
			$personid = '';
		}

		$subqry = "  AND usertypeid IN ('".$usertypeid."') AND personid ='".$personid."'";
		if($personid!='')
		{
			$subqry = " AND  personid IN ('".$personid."')";
			
		}

		$qry="select * from tbldashboarddata where (showweb=1 OR showapp=1)";
		$result_ary=$DB->getmenual($qry);

		if(sizeof($result_ary)>0)
		{
			$htmldata='';
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$rowcat=$result_ary[$i];

				$qry="select * from tbldashboardrights where dashid='".$rowcat['id']."'  $subqry";

				//echo ' ******* ';
				//echo $qry;
				//echo ' ******* ';
				$seluser=$DB->getmenual($qry);

				$rowuser = $seluser[0];

				//echo 'All : '.$rowuser['allviewright'];

				$allviewright="";
        		$selfviewright="";

				if($rowuser['allviewright']==1)
				{
					$allviewright="checked";
				}
				else if($rowuser['selfviewright']==1)
				{
					$selfviewright="checked";
				}


				$htmldata.='<tr>';
				$htmldata.='<td class="tbl-name tdpagename">'.$rowcat['name'].'';
				$htmldata.='<input type="hidden" name="'.$i.'formtype" id="'.$i.'formtype" VALUE="'.$rowcat['name'].'" />';
				$htmldata.='<input type="hidden" name="'.$i.'formurl" id="'.$i.'formurl" VALUE="'.$rowcat['uniqname'].'" />';
				$htmldata.='<input type="hidden" name="'.$i.'pageid" id="'.$i.'pageid" VALUE="'.$rowcat['id'].'" />';
				$htmldata.='</td>';


				$htmldata.='<td class="tbl-name tdview">';
				$htmldata.='<div class="d-flex text-center">';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
				$htmldata.='<input '.$allviewright.' type="checkbox" class="custom-control-input d-none tdallview viewrightcls vrcls'.$i.'" value="1" name="'.$i.'allviewright" id="allviewright'.$i.'" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="allviewright'.$i.'">All</label>';
				$htmldata.='</div>';
				$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto d-none">';
				$htmldata.='<input '.$selfviewright.' type="checkbox" class="custom-control-input d-none tdselfview viewrightcls vrcls'.$i.'" value="1"  name="'.$i.'selfviewright" id="selfviewright'.$i.'" data-id="'.$i.'">';
				$htmldata.='<label class="custom-control-label mb-0" for="selfviewright'.$i.'">Self</label>';
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
	else if($action=='insertdashuserright')   
	{
		$usertype=$_POST['usertypeid'];
		$personid=$_POST['personid'];

		if($_POST['personid'] === '0')
		{
			$extraparams=array(
				'[usertypeid]'=>$usertype,
				'[personid]'=>''
			);
			$DB->executedata('d','tbldashboardrights','',$extraparams);
		}
		else 
		{
			$extraparams=array(
				'[personid]'=>$personid
			);
			$DB->executedata('d','tbldashboardrights','',$extraparams);
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
			
			if ($allviewright || $selfviewright)
			{
				$viewright=0;
				$hallviewright=0;
				$hselfviewright=0;
				
				if($allviewright || $selfviewright)
				{
					$viewright=1;
					if($allviewright==1)
						$hallviewright=1;
					
					if($selfviewright==1)
						$hselfviewright=1;
				}
				
				
				$uniqname = $_POST[$i."formurl"];
				$pageid = $_POST[$i."pageid"];
				$formname = trim($formname);
				$pageid = trim($pageid);

				$unqid = $IISMethods->generateuuid();
				$data=array(
					'[id]'=>$unqid,
					'[dashid]'=>$pageid,
					'[uniqname]'=>$uniqname,
					'[viewright]'=>$viewright,
					'[allviewright]'=>$hallviewright,
					'[selfviewright]'=>$hselfviewright,
					'[usertypeid]'=>$usertype,
					'[personid]'=>$personid,
					'[entry_uid]'=>$uid,
					'[entry_date]'=>$IISMethods->getdatetime(),
				);
				$DB->executedata('i','tbldashboardrights',$data,'');	
				
				
			}
		}
		$status=1;
		$message=$errmsg['insert'];	
	}
}

require_once dirname(__DIR__, 3).'/config/apifoot.php';  

?>

  