<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'/config/init.php';
require_once dirname(__DIR__, 3).'/config/apiconfig.php';
require_once dirname(__DIR__, 2).'/model/dashboard.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	
	//Statistics Data
	if($action=='fillstatisticsdata')   
	{
		$qry="SELECT 
			isnull((SELECT COUNT(DISTINCT pm.id) FROM tblpersonmaster pm INNER JOIN tblpersonutype pu ON pu.pid = pm.id WHERE isnull(pm.isdelete,0)=0 AND pm.id <> :adminuid1 AND pu.utypeid=:memberutypeid1 AND isnull(pm.isverified,0)=1 ),0) AS cnt_totalverifiedmember,
			isnull((SELECT COUNT(DISTINCT pm.id) FROM tblpersonmaster pm INNER JOIN tblpersonutype pu ON pu.pid = pm.id WHERE isnull(pm.isdelete,0)=0 AND pm.id <> :adminuid2 AND pu.utypeid=:memberutypeid2 AND isnull(pm.isverified,0)=0 ),0) AS cnt_totalpendingmember,
			isnull((SELECT COUNT(DISTINCT pm.id) FROM tblpersonmaster pm INNER JOIN tblpersonutype pu ON pu.pid = pm.id WHERE isnull(pm.isdelete,0)=0 AND pm.id <> :adminuid3 AND pu.utypeid=:guestutypeid ),0) AS cnt_totalguest,
			isnull((SELECT COUNT(DISTINCT o.id) FROM tblorder o inner join tblpersonmaster pm on pm.id=o.uid ),0) AS cnt_totalorders,
			isnull((SELECT COUNT(DISTINCT so.id) FROM tblserviceorder so inner join tblpersonmaster pm on pm.id=so.uid inner join tblstoremaster s on s.id=so.storeid where so.iscancel=0),0) AS cnt_totalserviceorders";
		$parms = array(
			':adminuid1'=>$config->getAdminUserId(),
			':memberutypeid1'=>$config->getMemberutype(),
			':adminuid2'=>$config->getAdminUserId(),
			':memberutypeid2'=>$config->getMemberutype(),
			':adminuid3'=>$config->getAdminUserId(),
			':guestutypeid'=>$config->getGuestutype(),
		);
		$result_ary=$DB->getmenual($qry,$parms);
		$row=$result_ary[0];

		$response['cnt_totalverifiedmember']=(string)$row['cnt_totalverifiedmember'];
		$response['cnt_totalpendingmember']=(string)$row['cnt_totalpendingmember'];
		$response['cnt_totalguest']=(string)$row['cnt_totalguest'];
		$response['cnt_totalorders']=(string)$row['cnt_totalorders'];
		$response['cnt_totalserviceorders']=(string)$row['cnt_totalserviceorders'];

		$status=1;
		$message=$errmsg['success'];
	}
	//List Recent Member Data
	else if($action == 'listrecentmemberdata')
	{
		$listrecentmemberdatas=new listrecentmemberdata();

		$qry="SELECT distinct top 10 pm.timestamp,pm.id,pm.personname,pm.contact,pm.email,pu.userrole,convert(varchar,pm.entry_date,100) as entrydate,pm.isverified,
			CASE WHEN(pm.isverified = 1)THEN 'Verified' ElSE 'Pending' END as memberstatus,
			case when (isnull(pm.profileimg,'') = '') then :defualtmemberimageurl else concat(:imageurl,pm.profileimg) end as memberimg
			from tblpersonmaster pm 
			inner join tblpersonutype pu on pu.pid = pm.id
			where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND (pu.utypeid=:memberutypeid or pu.utypeid=:guestutypeid) 
			order by pm.timestamp desc";	
		$parms = array(
			':memberutypeid'=>$config->getMemberutype(),
			':guestutypeid'=>$config->getGuestutype(),
			':adminuid'=>$config->getAdminUserId(),
			':defualtmemberimageurl'=>$config->getDefualtMemberImageurl(),
			':imageurl'=>$config->getImageurl(),
		);
		$listrecentmemberdatas=$DB->getmenual($qry,$parms,'listrecentmemberdata');

		if($responsetype=='JSON')
		{
			if($listrecentmemberdatas)
			{
				$response['data']= $listrecentmemberdatas;
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{
				$status=0;
				$message=$errmsg['nodatafound'];
			}
		}
	}
	//List Recent Order Data
	else if($action == 'listrecentorderdata')
	{
		$listrecentorderdatas=new listrecentorderdata();

		$qry="select top 10 o.timestamp,o.id,o.transactionid,o.orderno,isnull(o.saporderid,'') as saporderid,pm.personname,pm.contact,
		convert(varchar, o.timestamp,100) AS ofulldate,o.totalpaid,
		case when (isnull(pm.profileimg,'') = '') then :defualtmemberimageurl else concat(:imageurl,pm.profileimg) end as memberimg
		from tblorder o 
		inner join tblpersonmaster pm on pm.id=o.uid  
		order by o.timestamp desc";
		$parms = array(
			':defualtmemberimageurl'=>$config->getDefualtMemberImageurl(),
			':imageurl'=>$config->getImageurl(),
		);
		$listrecentorderdatas=$DB->getmenual($qry,$parms,'listrecentorderdata');

		if($responsetype=='JSON')
		{
			if($listrecentorderdatas)
			{
				$response['data']= $listrecentorderdatas;
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{
				$status=0;
				$message=$errmsg['nodatafound'];
			}
		}
	}
	//List Recent Service Order Data
	else if($action == 'listrecentserviceorderdata')
	{
		$listrecentserviceorderdatas=new listrecentserviceorderdata();

		$qry="select top 10 o.timestamp,o.id,o.transactionid,o.orderno,pm.personname,pm.contact,
		convert(varchar, o.timestamp,100) AS ofulldate,o.totalpaid,s.storename,
		case when (isnull(pm.profileimg,'') = '') then :defualtmemberimageurl else concat(:imageurl,pm.profileimg) end as memberimg
		from tblserviceorder o 
		inner join tblpersonmaster pm on pm.id=o.uid 
		inner join tblstoremaster s on s.id=o.storeid 
		where o.iscancel=0 
		order by o.timestamp desc";
		$parms = array(
			':defualtmemberimageurl'=>$config->getDefualtMemberImageurl(),
			':imageurl'=>$config->getImageurl(),
		);
		$listrecentserviceorderdatas=$DB->getmenual($qry,$parms,'listrecentserviceorderdata');

		if($responsetype=='JSON')
		{
			if($listrecentserviceorderdatas)
			{
				$response['data']= $listrecentserviceorderdatas;
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{
				$status=0;
				$message=$errmsg['nodatafound'];
			}
		}
	}
	
	
	



	
}


require_once dirname(__DIR__, 3).'/config/apifoot.php';

?>

  