<?php
/*
 * Diet, Workout, Water Glass Notification
 *
 *
 */
header("Content-Type:application/json");

require_once dirname(__DIR__, 1).'/config/init.php';

//Daily 1 Time  (After 12 AM Night)
$cdate=$IISMethods->getformatcurrdate();
$datetime=$IISMethods->getdatetime();
$currfordate=$IISMethods->getformatcurrdate();


$timestamp = strtotime($currfordate);

$day = date('d', $timestamp);

// echo $day;
// exit;

//$currfordate='2022-03-18';


/***************************** Start For Reset Order Item Qty ***************************************/
$qry="select oid.id,oid.durationid,oid.qty,oid.usedqty,oid.remainqty 
	from tblorder o 
	inner join tblorderdetail od on od.orderid=o.id 
	inner join tblorderitemdetail oid on oid.odid = od.id 
	inner join tbldurationmaster d on d.id=oid.durationid
	where o.status=1 and o.iscancel = 0 and convert(date,od.n_expirydate,103) >= :currfordate and isnull(oid.iswebsiteattribute,0)=0 and isnull(d.isonce,0)=0";	
$params=array(
	':currfordate'=>$currfordate,
);
$res=$DB->getmenual($qry,$params);
$num=sizeof($res);
if($num > 0)
{
	for($i=0;$i<sizeof($res);$i++)
	{
		$row=$res[$i];

		$o_oidid=$row['id'];
		$o_durationid=$row['durationid'];
		$o_qty=$row['qty'];


		$isresetqty=0;


		//When Daily 
		if($o_durationid == $config->getDefDurationDailyId())  
		{
			$isresetqty=1;
		}
		//When Weekly
		else if($o_durationid == $config->getDefDurationWeeklyId())  
		{	
			$curr_timestamp = strtotime($currfordate);
			$curr_dayname = date('l', $curr_timestamp);

			if($curr_dayname == $config->getDefDurationWeekDay())
			{
				$isresetqty=1;
			}
		}
		//When Fort Night (15 Day)
		else if($o_durationid == $config->getDefDurationMonthlyId())  
		{
			$curr_timestamp = strtotime($currfordate);
			$curr_day = date('d', $curr_timestamp);

			if($curr_day == $config->getDefDurationFortNightFirstDay() || $curr_day == $config->getDefDurationFortNightSecondDay())
			{
				$isresetqty=1;
			}
		}
		//When Monthly
		else if($o_durationid == $config->getDefDurationMonthlyId())  
		{
			$curr_timestamp = strtotime($currfordate);
			$curr_day = date('d', $curr_timestamp);

			if($curr_day == $config->getDefDurationMonthDay())
			{
				$isresetqty=1;
			}
		}
		//When Bi Monthly
		else if($o_durationid == $config->getDefDurationBiMonthlyId())  
		{
			$curr_timestamp = strtotime($currfordate);
			$curr_day = date('d/m', $curr_timestamp);

			if($curr_day == $config->getDefdurationbimonthfirstday() || $curr_day == $config->getDefdurationbimonthsecondday() || $curr_day == $config->getDefdurationbimonththirdday() || 
				$curr_day == $config->getDefdurationbimonthforthday() || $curr_day == $config->getDefdurationbimonthfifthday() || $curr_day == $config->getDefdurationbimonthsixthday()
			)
			{
				$isresetqty=1;
			}
		}
		//When Quarterly
		else if($o_durationid == $config->getDefDurationQuarterlyId())  
		{
			$curr_timestamp = strtotime($currfordate);
			$curr_day = date('d/m', $curr_timestamp);

			if($curr_day == $config->getDefdurationquarterfirstday() || $curr_day == $config->getDefdurationquartersecondday() || $curr_day == $config->getDefdurationquarterthirdday() || $curr_day == $config->getDefdurationquarterforthday())
			{
				$isresetqty=1;
			}
		}
		//When Half Yearly
		else if($o_durationid == $config->getDefDurationHalfYearlyId())  
		{
			$curr_timestamp = strtotime($currfordate);
			$curr_day = date('d/m', $curr_timestamp);

			if($curr_day == $config->getDefdurationhalfyearfirstday() || $curr_day == $config->getDefdurationhalfyearsecondday())
			{
				$isresetqty=1;
			}
		}
		//When Yearly
		else if($o_durationid == $config->getDefDurationYearlyId())  
		{
			$curr_timestamp = strtotime($currfordate);
			$curr_day = date('d/m', $curr_timestamp);

			if($curr_day == $config->getDefdurationyearday())
			{
				$isresetqty=1;
			}
		}




		//When Reset Qty
		if($isresetqty == 1)
		{	
			$updqry=array(	
				'usedqty'=>0,	
				'remainqty'=>$o_qty,					
			);

			$extraparams=array(
				'[id]'=>$o_oidid
			);
			$DB->executedata('u','tblorderitemdetail',$updqry,$extraparams);
		}


	}
}
/***************************** End For Reset Order Item Qty ***************************************/


/*==============================================================================================================================================================================================================*/

?>