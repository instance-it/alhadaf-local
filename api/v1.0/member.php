<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\member.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();
	$currdate=$IISMethods->getformatcurrdate();
	 
	//List Member 360 View Data
	if($action == 'listmemberdata')
	{
		
		$emailid = $IISMethods->sanitize($_POST['emailid']);
		$contactno = $IISMethods->sanitize($_POST['contactno']);
		$unqid = $IISMethods->generateuuid();

		if($contactno)
		{
			$memberdata=new listmemberdata();
			$qry="SELECT pm.id,isnull(pm.personname,'') as personname,isnull(pm.firstname,'') as firstname,isnull(pm.lastname,'') as lastname,isnull(pm.contact,'') as contact,isnull(pm.email,'') as email,isnull(pm.address,'') as address,
				isnull(pm.qataridno,'') as qataridno,isnull(pm.qataridexpiry,'') as qataridexpiry,isnull(pm.dob,'') as dob,isnull(pm.nationality,'') as nationality,isnull(pm.companyname,'') as companyname,
				case when (isnull(pm.profileimg,'') = '') then :defualtmemberimageurl else concat(:imageurl,pm.profileimg) end as profileimg
				FROM tblpersonmaster pm 
				INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
				WHERE isnull(pm.isdelete,0)=0 AND isnull(pm.isverified,0)=1 AND pm.contact=:contactno";
			$parms = array(
				':contactno'=>$contactno,
				':defualtmemberimageurl'=>$config->getDefualtMemberImageurl(),
				':imageurl'=>$imgpath,
	
			);
			//echo $qry;
			//print_r($parms);
			$memberdata=$DB->getmenual($qry,$parms,'listmemberdata');
			
			$response['ismemberdata']=0;
			if($memberdata)
			{
				$itemdetail = new itemdetail();
				$qryod="select od.itemid as id,od.itemname as name
				from tblorder o
				inner join tblorderdetail od on od.orderid = o.id
				where o.uid = :uid and o.status=1 and o.iscancel = 0 and convert(date,od.n_expirydate,103) >= :currdate order by od.timestamp desc";
				$odparams = array(
					':uid'=>$memberdata[0]->getId(), 
					':currdate'=>$currdate, 
				);
				$orderdetailinfo=$DB->getmenual($qryod,$odparams,'itemdetail');

				if(sizeof($orderdetailinfo)>0)
				{
					$memberdata[0]->setItemDetail($orderdetailinfo);
				}

				$response['ismemberdata']=1;
				$response['memberdata']=$memberdata;
				
				$status = 1;
				$message = $errmsg['success'];
			}
			else
			{
				$status=0;
				$message=$errmsg['nodatafound'];
			}
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
		
	}
	
	

	
}

require_once dirname(__DIR__, 2).'\config\apifoot.php';

?>

  