<?php
/*
 * Diet, Workout, Water Glass Notification
 *
 *
 */
header("Content-Type:application/json");

require_once dirname(__DIR__, 1).'/config/init.php';

//Daily 1 Time  (Morning 10 AM)
$cdate=$IISMethods->getformatcurrdate();
$datetime=$IISMethods->getdatetime();
$currfordate=$IISMethods->getformatcurrdate();

//$currfordate='2022-03-18';


/***************************** Start For Send Member Package/Membership Expiry Notification ***************************************/
$qry="select o.uid,pm.personname as membername,pm.email as memberemail,pm.contact as membercontact,od.id,od.orderid,o.orderno,od.itemid,od.itemname,isnull(od.strvalidityduration,'') as strvalidityduration,
	od.startdate,od.expirydate,od.n_expirydate,od.type,case when (od.type = 1) then 'Membership' when (od.type = 2) then 'Package' when (od.type = 3) then 'Course' else '' end as typename,
	DATEDIFF(day, :currfordate1, convert(date, od.n_expirydate, 103)) as remainday
	from tblorder o 
	inner join tblorderdetail od on od.orderid=o.id 
	inner join tblpersonmaster pm on pm.id=o.uid
	where o.status=1 and o.iscancel = 0 and od.type in (1,2) 
	and (:currfordate2 between DATEADD(DAY, -3, convert(date, od.n_expirydate, 103)) and convert(date, od.n_expirydate, 103)) 
	order by convert(date, od.n_expirydate, 103)";	
$params=array(
	':currfordate1'=>$currfordate,
	':currfordate2'=>$currfordate,
);
$res=$DB->getmenual($qry,$params);
$num=sizeof($res);
if($num > 0)
{
	for($i=0;$i<sizeof($res);$i++)
	{
		$row=$res[$i];

		$orderid=$row['orderid'];
		$odid=$row['id'];
		$memberid=$row['uid'];
		$itemname=$row['itemname'];
		$type=$row['type'];
		$typename=$row['typename'];
		$remainday=$row['remainday'];

		$membername=$row['membername'];
		$memberemail=$row['memberemail'];
		$membercontact=$row['membercontact'];
		

		$title = $itemname." ".$typename;
		
		if($remainday > 0)
		{
			$msgtext="Your ".$itemname." ".$typename." will expires within ".$remainday." days";
		}
		else
		{
			$msgtext="Your ".$itemname." ".$typename." will expires today";
		}
		
		$qrydevice="SELECT 
			isnull((SELECT SUBSTRING((select ','+CONVERT(VARCHAR(max), deviceid) AS [text()] from tbldevice where os='a' and apptype=1 and uid=:amemberid FOR XML PATH ('')),2,1000000)),'') as adeviceid,
			isnull((SELECT SUBSTRING((select ','+CONVERT(VARCHAR(max), deviceid) AS [text()] from tbldevice where os='i' and apptype=1 and uid=:imemberid FOR XML PATH ('')),2,1000000)),'') as ideviceid";
		$deviceparams=array(            
			':amemberid'=>$memberid,
			':imemberid'=>$memberid,
		);

		$resdevice=$DB->getmenual($qrydevice,$deviceparams);
		$rowdevice=$resdevice[0];
		
		$adeviceid= explode(",", $rowdevice['adeviceid']);
		$ideviceid= explode(",", $rowdevice['ideviceid']);

		$clickaction="alhadaf_ntf";
		if($type == 1)  //For Membership
		{
			$clickflag=2;
			$ntypeid=2;
		}
		else if($type == 2)  //For Package
		{
			$clickflag=3;
			$ntypeid=3;
		}
		else if($type == 3)  //For Course
		{ 
			$clickflag=4;
			$ntypeid=4;
		}
		
		$data="";
		$pagename='orderhistory';
		$actionname='listorderdetail';
						
		$extra = array('clickflag' => $clickflag,'pagename' =>$pagename,'actionname' =>$actionname,'imageurl' => '','type' => $type);

		$IISMethods->androidnotification($adeviceid,$msgtext,$title,$clickaction,$extra);
		$IISMethods->iosnotification($ideviceid,$msgtext,$title,$extra);

		$subnotiunqid = $IISMethods->generateuuid();
		$notiinsary=array(
			'[id]'=>$subnotiunqid,
			'[ntypeid]'=>$ntypeid,
			'[orderid]'=>$orderid,
			'[odid]'=>$odid,
			'[uid]'=>$memberid,
			'[ordtype]'=>$type,
			'[title]'=>$title,
			'[message]'=>$msgtext,
			'[clickaction]'=>$clickaction,
			'[clickflag]'=>$clickflag,
			'[pagename]'=>$pagename,
			'[actionname]'=>$actionname,						
			'[data]'=>'',
			'[timestamp]'=>$datetime,
			'[entry_date]'=>$datetime,
		);
		$DB->executedata('i','tblnotification',$notiinsary,'');



		/**************** Start For Send Email ********************/
		$emailtxt='';
		$emailtxt.='<table>';
		$emailtxt.='<tr><td>Dear '.$membername.',</td></tr>';
		$emailtxt.='<tr><td>'.$msgtext.'</td></tr>';
		$emailtxt.='</table>';

		$emailsubject = $companyname." - ".$typename." Expired";


		//Send Package Expire Email/SMS
		$DB->userexpirepackageemails($memberemail,$emailtxt,$emailsubject);   //type 1-Email,2-SMS

		/**************** End For Send Email ********************/


	}
}
/***************************** End For Send Member Package/Membership Expiry Notification ***************************************/


/*==============================================================================================================================================================================================================*/

?>