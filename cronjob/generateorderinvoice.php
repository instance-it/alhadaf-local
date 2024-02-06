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


/***************************** Start For Generate Order Invoice ***************************************/
$qry="select oi.* from tblorderinvoice oi where isnull(oi.pdfurl,'')=''";	
$params=array(
	
);
$res=$DB->getmenual($qry,$params);
$num=sizeof($res);
if($num > 0)
{
	for($i=0;$i<sizeof($res);$i++)
	{
		$row=$res[$i];

		$orderid=$row['orderid'];

		//Generate Order Invoice
		$genpdf = file_get_contents($config->getalhadafpdfurl().'mshipinvoice.php?id='.$orderid.'&type=generate&isshow=1');


	}
}
/***************************** End For Generate Order Invoice ***************************************/



/***************************** Start For Generate Service Order Invoice ***************************************/
$qry="select so.id from tblserviceorder so where so.status=1 and isnull(so.totalpaid,0) > 0 and isnull(so.pdfurl,'')=''";	
$params=array(
	
);
$res=$DB->getmenual($qry,$params);
$num=sizeof($res);
if($num > 0)
{
	for($i=0;$i<sizeof($res);$i++)
	{
		$row=$res[$i];

		$soid=$row['id'];

		//Generate Service Order Invoice
		$genpdf = file_get_contents($config->getalhadafpdfurl().'serviceorderinvoice.php?id='.$soid.'&type=generate&isshow=1');


	}
}
/***************************** End For Generate Service Order Invoice ***************************************/


/*==============================================================================================================================================================================================================*/

?>