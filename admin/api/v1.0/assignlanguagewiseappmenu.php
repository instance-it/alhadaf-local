<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\assignlanguagewiseappmenu.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='assignlanguagewiseappmenu')   
	{
		$languageid=$IISMethods->sanitize($_POST['languageid']);
		$appmenuid=$_POST['appmenuid'];
		$tbllangappmenuname=$_POST['tbllangappmenuname'];	

		$datetime=$IISMethods->getdatetime();

		if($languageid)
		{	
			try 
			{
				$DB->begintransaction();
				
				$extraparams_d=array(
					'[languageid]'=>$languageid,
				);
				$DB->executedata('d','tblassignlanguagewiseappmenu','',$extraparams_d);	
				
				
				for($i=0;$i<sizeof($appmenuid);$i++)
				{	 
					$unqid = $IISMethods->generateuuid();
					$insqry=array(
						'[id]'=>$unqid,	
						'[languageid]'=>$languageid,				
						'[appmenuid]'=>$appmenuid[$i],
						'[langappmenuname]'=>$tbllangappmenuname[$i],	
						'[entry_uid]'=>$uid,			
						'[entry_date]'=>$datetime,			

					);
					$DB->executedata('i','tblassignlanguagewiseappmenu',$insqry,'');

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
	else if($action=='filllangwiseappmenudata')   
	{
		$languageid = $IISMethods->sanitize($_POST['languageid']);

		$qry = "select mm.timestamp as primary_date,mm.id,mm.menuname,
				ISNULL((select langappmenuname from tblassignlanguagewiseappmenu where mm.id=appmenuid AND languageid=:languageid),'') AS langappmenuname 
				from tblmenumaster mm where menutype = 2
				ORDER BY $ordbycolumnname $ordby";

		//exit;		
		$parms = array(
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
				$htmldata.='<input type="hidden" id="appmenuid'.$IISMethods->sanitize($i).'" name="appmenuid[]" value="'.$IISMethods->sanitize($row['id']).'" /></td>';
				$htmldata.='<td class="tbl-w150">';
				$htmldata.='<div class="text-right">';
				$htmldata.='<input type="text" class="form-control heightpx-35 tblcapacity" id="tbllangappmenuname'.$IISMethods->sanitize($i).'" name="tbllangappmenuname[]" value="'.$IISMethods->sanitize($row['langappmenuname']).'"  placeholder="Enter Appmenu Name" >';
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
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);

		$qry="select id,languagename from tbllanguagemaster where isnull(isdefault,0) = 0 order by (case when (displayorder>0) then displayorder else 99999 end)";
		$parms = array();
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

  