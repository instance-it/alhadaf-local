<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'/config/init.php';
require_once dirname(__DIR__, 2).'/config/apiconfig.php';
require_once dirname(__DIR__, 2).'/model/notification.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	

	if($action=='listnotification')   
	{
		$filter=trim($_POST['filter']);

		$notifications=new notification();

		$query_main="SELECT tmp.* FROM
			(
				SELECT n.id,n.ntypeid,n.uid,n.title,n.message,convert(varchar, n.entry_date, 100) AS notificationdt,
				CASE WHEN (isnull(n.nimg,'')<>'') THEN n.nimg ELSE :defaultnotiimgurl END AS nimg,
				CASE WHEN (n.ntypeid=0) THEN 1 ELSE 0 END AS isgeneral,n.clickaction,n.clickflag,n.pagename,n.actionname,n.data,n.timestamp,isnull(n.ordtype,0) as ordtype
				FROM tblnotification n 
				WHERE n.uid=:uid AND n.ntypeid NOT IN (0) AND isnull(n.isdelete,0)=0
			) tmp WHERE 1=1 ";
		$listnotiparams=array(            
			':uid'=>$uid,
			':defaultnotiimgurl'=>$config->getDefaultNotiImageurl(),
		);

		if($filter)
		{
			$query_main.=" AND (tmp.title LIKE :titlefilter OR tmp.message LIKE :msgfilter OR tmp.notificationdt LIKE :notidatefilter) ";
			$listnotiparams[':titlefilter']='%'.$filter.'%'; 
			$listnotiparams[':msgfilter']='%'.$filter.'%'; 
			$listnotiparams[':notidatefilter']='%'.$filter.'%'; 
		}
		$query_main.=" ORDER BY tmp.timestamp DESC  offset $start rows fetch next $per_page rows only";
		
		$notifications=$DB->getmenual($query_main,$listnotiparams,'notification');

		$htmldata='';
		if(sizeof($notifications)>0)
		{
			$response['data']= $notifications;

			$common_listdata=$notifications;

			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nonotifound'];
		}
	} 
	


}


require_once dirname(__DIR__, 2).'/config/apifoot.php';

?>

  