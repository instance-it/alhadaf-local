<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	

	if($action=='getrangebookingtimeslot')   
	{
		$rb_fromdate=$IISMethods->sanitize($_POST['rb_fromdate']);
		$rb_todate=$IISMethods->sanitize($_POST['rb_todate']);

		$isdatafound=0;


		//With Member Login (Timing Slot)
		$qry="select rb.fromtime,rb.totime,count(id) as cntbook 
			from tblrangebooking rb 
			where rb.type=2 and convert(date,rb.date,103) between convert(date,:fromdate,103) and convert(date,:todate,103) 
			group by rb.fromtime,rb.totime order by stuff(convert(varchar(19),CONVERT(DateTime,CONCAT(CONVERT(date,:fromdate1,103),' ',rb.fromtime)) , 126),11,1,' ')";
		$parms = array(
			':fromdate'=>$rb_fromdate,
			':todate'=>$rb_todate,
			':fromdate1'=>$rb_fromdate,
		);
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			$isdatafound=1;
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];


				$htmldata.='<div class="col-12 col-sm-6 col-lg-4 col-xl-3 layout-spacing pl-0">';
				$htmldata.='<a class="viewdetail btnbookedmemberdetail" href="javascript:void(0)" data-type="2" data-fromtime="'.$row['fromtime'].'" data-totime="'.$row['totime'].'">';
				$htmldata.='<div class="widget widget-hover text-center h-100 py-4">';
				$htmldata.='<div class="widget-content inner1 innerdivcls">';
				$htmldata.='<div class="item selectusrtype">'.$row['cntbook'].'</div>';
				$htmldata.='<h5 class="card-title">'.$row['fromtime'].' To '.$row['totime'].'</h5>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</a>';
				$htmldata.='</div>';
				
			}
		}



		//Without Member Login
		$qry="select rb.fromtime,count(id) as cntbook 
			from tblrangebooking rb 
			where rb.type=1 and convert(date,rb.date,103) between convert(date,:fromdate,103) and convert(date,:todate,103) 
			group by rb.fromtime";
		$parms = array(
			':fromdate'=>$rb_fromdate,
			':todate'=>$rb_todate,
		);
		//print_r($parms);
		$result_ary=$DB->getmenual($qry,$parms);

		if(sizeof($result_ary)>0)
		{
			$isdatafound=1;
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];


				$htmldata.='<div class="col-12 col-sm-6 col-lg-4 col-xl-3 layout-spacing pl-0">';
				$htmldata.='<a class="viewdetail btnbookedmemberdetail" href="javascript:void(0)" data-type="1" data-fromtime="" data-totime="">';
				$htmldata.='<div class="widget widget-hover text-center h-100 py-4">';
				$htmldata.='<div class="widget-content inner1 innerdivcls">';
				$htmldata.='<div class="item selectusrtype">'.$row['cntbook'].'</div>';
				$htmldata.='<h5 class="card-title">Undefine Time</h5>';
				$htmldata.='</div>';
				$htmldata.='</div>';
				$htmldata.='</a>';
				$htmldata.='</div>';
				
			}
		}


		if($isdatafound == 0)
		{
			$htmldata.='<div class="col-12 col-sm-12 col-lg-12 col-xl-12 layout-spacing pl-0">';
			$htmldata.='<div class="viewdetail">';
			$htmldata.='<div class="widget text-center h-100 py-4">';
			$htmldata.='<div class="widget-content inner1 innerdivcls">';
			$htmldata.='<h5 class="card-title">No Booking Found</h5>';
			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';
		}


		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	} 
	else if($action=='viewbookedmemberdetail')   
	{
		$fromdate=$IISMethods->sanitize($_POST['fromdate']);
		$todate=$IISMethods->sanitize($_POST['todate']);

		$type=$IISMethods->sanitize($_POST['type']);
		$fromtime=$IISMethods->sanitize($_POST['fromtime']);
		$totime=$IISMethods->sanitize($_POST['totime']);
		

		if($fromdate && $todate && (($type==2 && $fromtime && $totime) || $type==1))
		{
			$tbldata='';

			if($type == 1)  //For guest
			{
				$qry="select rb.*,convert(varchar, rb.entry_date,100) as entrydate,isnull(st.type,'') as servicetype
					from tblrangebooking rb 
					left join tblservicetypemaster st on st.id=rb.servicetypeid 
					where rb.type=1 and (convert(date,rb.date,103) between convert(date,:fromdate,103) and convert(date,:todate,103)) 
					order by convert(date,rb.date,103),rb.timestamp";
				$parms = array(
					':fromdate'=>$fromdate,
					':todate'=>$todate,
				);
				$compitems=$DB->getmenual($qry,$parms);

				$tbldata='';
				if($compitems)
				{
					$tbldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
						$tbldata.='<div class="widget mt-10">';
							$tbldata.='<div class="widget-title  mb-2"></div>';
							$tbldata.='<div class="widget-content row">';

								$tbldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
								$tbldata.='<div class="row1">';
								$tbldata.='<div class="table-responsive pt-2">';
								$tbldata.='<div class="col-12 p-0">';
								$tbldata.='<table class="table table-bordered table-hover table-striped">';
								$tbldata.='<thead>';
								$tbldata.='<tr>';
								$tbldata.='<th>Date</th>';
								$tbldata.='<th>Name</th>';
								$tbldata.='<th>Email</th>';
								$tbldata.='<th>Mobile No</th>';
								$tbldata.='<th>Date Of Birth</th>';
								$tbldata.='<th>Qatar ID No</th>';
								$tbldata.='<th>Qatar ID Expiry</th>';
								$tbldata.='<th>Passport ID No</th>';
								$tbldata.='<th>Passport ID Expiry</th>';
								$tbldata.='<th>Nationality</th>';
								$tbldata.='<th>Company</th>';
								$tbldata.='<th>Service Type</th>';
								$tbldata.='<th>Address</th>';
								$tbldata.='<th>Entry Date</th>';
								$tbldata.='</tr>';
								$tbldata.='</thead>';
								$tbldata.='<tbody id="tblviewdataprice">';
								
								$htmldata1='';
								for($i=0;$i<sizeof($compitems);$i++)
								{	
									$subrow=$compitems[$i];
									$tbldata.='<tr>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['date']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['firstname']).' '.$IISMethods->sanitize($subrow['lastname']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['email']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['contact']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['dob']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['qataridno']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['qataridexpiry']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['passportidno']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['passportidexpiry']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['nationality']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['companyname']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['servicetype']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['address']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['entrydate']).'</td>';
									$tbldata.='</tr>';
								}
								$tbldata.='</tbody>';
								$tbldata.='</table>';
								$tbldata.='</div>';
								$tbldata.='</div>';
								$tbldata.='</div>';
								$tbldata.='</div>';


							$tbldata.='</div>';
						$tbldata.='</div>';
					$tbldata.='</div>';
					
					
					$status=1;
					$message=$errmsg['success'];
				}
			}
			else if($type == 2)   //For Member
			{
				$qry="select rb.id,pm.personname as membername,pm.contact as membercontact,rb.date,rb.fromtime,rb.totime,convert(varchar, rb.entry_date,100) as entrydate
					from tblrangebooking rb 
					inner join tblpersonmaster pm on pm.id=rb.uid
					where rb.type=2 and (convert(date,rb.date,103) between convert(date,:fromdate,103) and convert(date,:todate,103)) 
					and isnull(rb.fromtime,'')=:fromtime and isnull(rb.totime,'')=:totime order by convert(date,rb.date,103)";
				$parms = array(
					':fromdate'=>$fromdate,
					':todate'=>$todate,
					':fromtime'=>$fromtime,
					':totime'=>$totime,
				);
				$compitems=$DB->getmenual($qry,$parms);

				$tbldata='';
				if($compitems)
				{
					$tbldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
						$tbldata.='<div class="widget mt-10">';
							$tbldata.='<div class="widget-title  mb-2"></div>';
							$tbldata.='<div class="widget-content row">';

								$tbldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
								$tbldata.='<div class="row1">';
								$tbldata.='<div class="table-responsive pt-2">';
								$tbldata.='<div class="col-12 p-0">';
								$tbldata.='<table class="table table-bordered table-hover table-striped">';
								$tbldata.='<thead>';
								$tbldata.='<tr>';
								$tbldata.='<th>Date</th>';
								$tbldata.='<th>Time</th>';
								$tbldata.='<th>Member</th>';
								$tbldata.='<th>Entry Date</th>';
								$tbldata.='</tr>';
								$tbldata.='</thead>';
								$tbldata.='<tbody id="tblviewdataprice">';
								
								$htmldata1='';
								for($i=0;$i<sizeof($compitems);$i++)
								{	
									$subrow=$compitems[$i];
									$tbldata.='<tr>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['date']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['fromtime']).' To '.$IISMethods->sanitize($subrow['totime']).'</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['membername']).' ('.$IISMethods->sanitize($subrow['membercontact']).')</td>';
									$tbldata.='<td>'.$IISMethods->sanitize($subrow['entrydate']).'</td>';
									$tbldata.='</tr>';
								}
								$tbldata.='</tbody>';
								$tbldata.='</table>';
								$tbldata.='</div>';
								$tbldata.='</div>';
								$tbldata.='</div>';
								$tbldata.='</div>';


							$tbldata.='</div>';
						$tbldata.='</div>';
					$tbldata.='</div>';
					
					
					$status=1;
					$message=$errmsg['success'];
				}
			}
			

			$response['data']=$tbldata;
			$response['type']=$type;
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	








	
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  