<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\useremail.php';


if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='listuseremail')   
	{
		$useremails=new useremail();
		$qry="select distinct e.timestamp as primary_date,e.id,e.email,convert(varchar, e.timestamp,100) as entrydate
			from tbluseremail e 
			where (e.email like :emailfilter or convert(varchar, e.timestamp,100) like :datefilter)  
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";	
		$parms = array(
			':emailfilter'=>'%'.$filter.'%',
			':datefilter'=>'%'.$filter.'%',
		);
		$useremails=$DB->getmenual($qry,$parms,'useremail');
		
		if($responsetype=='HTML')
		{
			if($useremails)
			{
				$i=0;
				foreach($useremails as $useremail)
				{
					$id="'".$useremail->id."'";

					$htmldata.='<tr data-index="'.$i.'">';
					
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($useremail->email).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($useremail->entrydate).'</td>';
					
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($useremails)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($useremails);
		}

		$common_listdata=$useremails;
		
	} 
	



}



require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  