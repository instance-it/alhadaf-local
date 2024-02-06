<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\assignlanguagewisemessage.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='assignlanguagewiselabel')   
	{
		$languageid=$IISMethods->sanitize($_POST['languageid']);
		$messageid=$_POST['messageid'];
		$langmessagename=$_POST['tbllangmessagename'];	

		$datetime=$IISMethods->getdatetime();

		if($languageid)
		{
			try 
			{
				$DB->begintransaction();
		
				$extraparams_d=array(
					'[languageid]'=>$languageid,
				);
				$DB->executedata('d','tblassignlangwisemessage','',$extraparams_d);	
				
				
				for($i=0;$i<sizeof($messageid);$i++)
				{	 
					$unqid = $IISMethods->generateuuid();
					$insqry=array(
						'[id]'=>$unqid,	
						'[languageid]'=>$languageid,				
						'[messageid]'=>$messageid[$i],
						'[langmessagename]'=>$langmessagename[$i],	
						'[timestamp]'=>$datetime,		
						'[entry_uid]'=>$uid,			
						'[entry_date]'=>$datetime,			

					);
					$DB->executedata('i','tblassignlangwisemessage',$insqry,'');

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
	else if($action=='filllangwisemsgdata')   
	{
		$languageid = $IISMethods->sanitize($_POST['languageid']);

		$qry = "select mm.timestamp as primary_date,mm.id,mm.messagenameid,mm.messageengname,mm.variables,
				ISNULL((select langmessagename from tblassignlangwisemessage where mm.id=messageid AND languageid=:languageid),'') AS langmessagename 
				from tblmessagemaster mm 
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
				$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($row['messagenameid']).'';
				$htmldata.='<input type="hidden" id="messageid'.$IISMethods->sanitize($i).'" name="messageid[]" value="'.$IISMethods->sanitize($row['id']).'" /></td>';

				$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($row['messageengname']).'</td>';

				$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($row['variables']).'</td>';

				$htmldata.='<td class="tbl-w150">';
				$htmldata.='<div class="text-right">';
				$htmldata.='<input type="text" class="form-control heightpx-35 tblcapacity" id="tbllangmessagename'.$IISMethods->sanitize($i).'" name="tbllangmessagename[]" value="'.$IISMethods->sanitize($row['langmessagename']).'"  placeholder="Enter Message" >';
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

		$qry="select id,languagename from tbllanguagemaster where isnull(isdefault,0) = 0  order by (case when (displayorder>0) then displayorder else 99999 end)";
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

  