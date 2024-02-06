<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\contactus.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='listcontactus')   
	{
		$contactuss=new contactus();
		$qry="select distinct c.timestamp as primary_date,c.id,c.name,c.mobile,c.email,convert(varchar, c.entry_date,100) as entrydate
			from tblcontactus c 
			where (c.name like :namefilter or c.mobile like :mobilefilter or c.email like :emailfilter or convert(varchar, c.entry_date,100) like :datefilter)  
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";	
		$parms = array(
			':namefilter'=>'%'.$filter.'%',
			':mobilefilter'=>'%'.$filter.'%',
			':emailfilter'=>'%'.$filter.'%',
			':datefilter'=>'%'.$filter.'%',
		);
		//echo $qry;
		//print_r($params);
		$contactuss=$DB->getmenual($qry,$parms,'contactus');
		
		if($responsetype=='HTML')
		{
			if($contactuss)
			{
				$i=0;
				foreach($contactuss as $contactus)
				{
					$id="'".$contactus->id."'";

					$htmldata.='<tr data-index="'.$i.'">';
					
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Contact US Details" onclick="viewcontactusdata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($contactus->name).'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($contactus->email).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($contactus->mobile).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($contactus->entrydate).'</td>';
					
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($contactuss)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($contactuss);
		}

		$common_listdata=$contactuss;
		
	} 
	else if($action=='viewcontactusdata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select distinct c.id,c.name,c.mobile,c.email,c.message,convert(varchar, c.entry_date,100) as entrydate
				from tblcontactus c where c.id=:id";
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

					$tbldata.='<div class="col-12 col-lg-12 col-xl-12 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['name'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-12 col-xl-12 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Email <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['email'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-12 col-xl-12 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Mobile Number <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['mobile'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-12 col-xl-12 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Entry Date Time <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['entrydate'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-12 col-xl-12 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Message <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['message'].'</b></label>';
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

  