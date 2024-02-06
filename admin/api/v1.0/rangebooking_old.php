<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\rangebooking.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());


if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='listrangebooking')   
	{
		$rangebookings=new rangebooking();
		$qry="select distinct rm.timestamp as primary_date,rm.id,rm.type,
			case when (rm.type=2) then pm.personname else rm.personname end as personname,
			case when (rm.type=2) then pm.firstname else rm.firstname end as firstname,
			case when (rm.type=2) then pm.lastname else rm.lastname end as lastname,
			case when (rm.type=2) then pm.contact else rm.contact end as contact,
			case when (rm.type=2) then pm.email else rm.email end as email,
			case when (rm.type=2) then rm.fromtime else '' end as fromtime,
			case when (rm.type=2) then rm.totime else '' end as totime,
			convert(varchar, rm.entry_date,100) as entrydate,
			case when (rm.type=2) then 'Member' else 'Guest' end as typename
			from tblrangebooking rm 
			left join tblpersonmaster pm on pm.id=rm.uid
			where (isnull(rm.personname,'') like :personfilter or isnull(rm.contact,'') like :contactfilter or isnull(rm.email,'') like :emailfilter or case when (rm.type=2) then 'Member' else 'Guest' end like :typefilter or isnull(rm.fromtime,'') like :fromtimefilter or isnull(rm.totime,'') like :totimefilter )  
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";	
		$parms = array(
			':personfilter'=>'%'.$filter.'%',
			':contactfilter'=>'%'.$filter.'%',
			':emailfilter'=>'%'.$filter.'%',
			':typefilter'=>'%'.$filter.'%',
			':fromtimefilter'=>'%'.$filter.'%',
			':totimefilter'=>'%'.$filter.'%',
		);
		// echo $qry;
		// print_r($params);
		$rangebookings=$DB->getmenual($qry,$parms,'rangebooking');
		
		if($responsetype=='HTML')
		{
			if($rangebookings)
			{
				$i=0;
				foreach($rangebookings as $rangebooking)
				{
					$id="'".$rangebooking->id."'";

					$htmldata.='<tr data-index="'.$i.'">';
					
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($rangebooking->typename).'</td>';
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Details" onclick="viewrangebookingdetaildata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($rangebooking->firstname).'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($rangebooking->lastname).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($rangebooking->email).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($rangebooking->contact).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($rangebooking->fromtime).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($rangebooking->totime).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($rangebooking->entrydate).'</td>';
					
					
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($rangebookings)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($rangebookings);
		}

		$common_listdata=$rangebookings;
		
	} 
	else if($action=='viewrangebookingdata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="SELECT DISTINCT rm.id,rm.companyname,
			case when (rm.type=2) then pm.personname else rm.personname end as personname,
			case when (rm.type=2) then pm.firstname else rm.firstname end as firstname,
			case when (rm.type=2) then pm.lastname else rm.lastname end as lastname,
			case when (rm.type=2) then pm.contact else rm.contact end as contact,
			case when (rm.type=2) then pm.email else rm.email end as email,
			case when (rm.type=2) then pm.address else rm.address end as address,
			case when (rm.type=2) then pm.qataridno else rm.qataridno end as qataridno,
			case when (rm.type=2) then pm.qataridexpiry else rm.qataridexpiry end as qataridexpiry,
			case when (rm.type=2) then pm.passportidno else rm.passportidno end as passportidno,
			case when (rm.type=2) then pm.passportidexpiry else rm.passportidexpiry end as passportidexpiry,
			case when (rm.type=2) then pm.dob else rm.dob end as dob,
			case when (rm.type=2) then pm.nationality else rm.nationality end as nationality,
			case when (rm.type=2) then rm.fromtime else '-' end as fromtime,
			case when (rm.type=2) then rm.totime else '-' end as totime,
			st.type as servicetype
			from tblrangebooking rm 
			left join tblservicetypemaster st on st.id=rm.servicetypeid 
			left join tblpersonmaster pm on pm.id=rm.uid
			where rm.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);
			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];
				$tbldata='';
				$tbldata.='<div class="col-12">';
				$tbldata.='<div class="table-responsive">';
				$tbldata.='<div class="col-12 view-data-details">';
				$tbldata.='<div class="row my-3">';
				$tbldata.='<div class="col-12 col-md-8 col-lg">';
				$tbldata.='<div class="row">';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>First Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['firstname'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Last Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['lastname'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Email <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['email'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Mobile Number <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['contact'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Qatar ID Number <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['qataridno'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Qatar ID Expiry <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['qataridexpiry'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Passport ID Number <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['passportidno'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Passport ID Expiry <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['passportidexpiry'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Date Of Birth <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['dob'].'</b></label>';
					$tbldata.='</div></div></div>';
					
					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Nationality <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['nationality'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Company Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['companyname'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Address <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['address'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>From Time <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['fromtime'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>To Time <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['totime'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Service Type <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['servicetype'].'</b></label>';
					$tbldata.='</div></div></div>';


				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';										
				$tbldata.='</div>';

			}

			$response['data']=$tbldata;
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

  