<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='listwebsitesetting')   
	{
		$parentid=$_POST['menuid'];
		$qry="select * from tblmenuassign where menutypeid=1 AND parentid=:parentid order by timestamp asc";
		$parms = array(
			':parentid'=>$parentid,
		);
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-exl-2 layout-spacing pl-0">';
				$htmldata.='<a pagename="'.$row['alias'].'" class="d-block h-100 MasterMenu">';
				$htmldata.='<div class="widget widget-hover text-center h-100">';
				$htmldata.='<div class="widget-content">';
				$htmldata.='<h5 class="card-icon mb-2"><i class="'.$row['iconstyle'].' '.$row['iconclass'].' bi-2x"></i></h5>';
				$htmldata.='<h5 class="card-title">'.$row['textname'].'</h5>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</a>';
                $htmldata.='</div>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  