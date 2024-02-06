<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\assignlanguagewiselabel.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='assignlanguagewiselabel')   
	{
		$apptypeid=$IISMethods->sanitize($_POST['apptypeid']);
		$languageid=$IISMethods->sanitize($_POST['languageid']);

		$langid=$_POST['langid'];
		$menuid=$_POST['tblmenuid'];
		$labelid=$_POST['labelid'];
		$langlabelname=$_POST['tbllanglabelname'];	

		$datetime=$IISMethods->getdatetime();

		if($apptypeid && $languageid)
		{	
			try 
			{
				$DB->begintransaction();
				
				$extraparams_d=array(
					'[apptypeid]'=>$apptypeid,
					'[languageid]'=>$languageid,
				);
				$DB->executedata('d','tblassignlanguagewiselabel','',$extraparams_d);	
				
				
				for($i=0;$i<sizeof($labelid);$i++)
				{	 
					$insqry=array(
						'[apptypeid]'=>$apptypeid,
						'[languageid]'=>$languageid,				
						'[labelid]'=>$labelid[$i],
						'[menuid]'=>$menuid[$i],		
						'[langlabelname]'=>$langlabelname[$i],						
					);
					$unqid = $IISMethods->generateuuid();
					$insqry['[id]']=$unqid;	
					$insqry['[entry_uid]']=$uid;	
					$insqry['[entry_date]']=$datetime;
					$DB->executedata('i','tblassignlanguagewiselabel',$insqry,'');

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
	else if($action=='filllangwiselabeldata')   
	{
		$apptypeid=$IISMethods->sanitize($_POST['apptypeid']);
		$languageid = $IISMethods->sanitize($_POST['languageid']);

		$qry = "select tmp.* from(
		select lm.timestamp as primary_date,lm.id,m.menuname,lm.appmenuid,lm.labelnameid,lm.labelengname,
				ISNULL((select langlabelname from tblassignlanguagewiselabel where lm.id=labelid AND convert(varchar(50),languageid)=:languageid AND apptypeid=:apptypeid1),'') AS labelname 
				from tbllabelmaster lm 
				inner join tblmenumaster m on m.id= lm.appmenuid
				where lm.apptypeid=:apptypeid
				) as tmp
				ORDER BY case when (tmp.labelname <> '') then '1' else '0' end desc";
		//exit;		
		$parms = array(
			':apptypeid'=>$apptypeid,
			':apptypeid1'=>$apptypeid,
			':languageid'=>$languageid,
		);
		
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			//for($i=0;$i<100;$i++)
			{
				$row=$result_ary[$i];

				$htmldata.='<tr data-index="'.$IISMethods->sanitize($i).'">';
				$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($row['menuname']).'';
				$htmldata.='<input type="hidden" id="tblmenuid'.$IISMethods->sanitize($i).'" name="tblmenuid[]" value="'.$IISMethods->sanitize($row['appmenuid']).'" /></td>';

				$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($row['labelnameid']).'';
				$htmldata.='<input type="hidden" id="labelid'.$IISMethods->sanitize($i).'" name="labelid[]" value="'.$IISMethods->sanitize($row['id']).'" /></td>';

				$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($row['labelengname']).'</td>';

					
				$htmldata.='<td class="tbl-w150">';
				$htmldata.='<div class="text-right">';
				$htmldata.='<input type="text" class="form-control heightpx-35 tblcapacity" id="tbllanglabelname'.$IISMethods->sanitize($i).'" name="tbllanglabelname[]" value="'.$IISMethods->sanitize($row['labelname']).'"  placeholder="Enter Label name" >';
				
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</td>';
				$htmldata.='</tr>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	//Fill Language
	else if($action=='filllanguage')   
	{
		$apptypeid=$IISMethods->sanitize($_POST['apptypeid']);
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);

		$qry="select id,languagename from tbllanguagemaster where isnull(isdefault,0) = 0 and apptypeid=:apptypeid order by (case when (displayorder>0) then displayorder else 99999 end)";
		$parms = array(
			':apptypeid'=>$apptypeid,
		);
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Language</option>';
		}

		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'" >'.$row['languagename'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}  
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  