<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\specialpackages.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	 
	//list Packages
	if($action == 'listpackages')
	{
		$showonhome=$IISMethods->sanitize($_POST['showonhome']);   //From Home Page
		$id=$IISMethods->sanitize($_POST['id']);
		$packagedata=new package();
		
		$qry="select im.id,im.itemname,im.price,tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,dm.day as duration,dm.daytext as durationname,dm.strday as strvalidityduration,
			case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg,
			ISNULL((select top 1 CONCAT(:baseimgurl,image) from tblitemimage where itemid = im.id order by displayorder),:defaultmembershipimgurl) as image   
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid
			left join tblitemiconmaster iim on iim.id=im.iconid
			where im.isactive=1 and im.showonweb=1 and im.categoryid=:catpackageid ";
		$parms = array(
			':imageurl'=>$imageurl,
			':catpackageid'=>$config->getDefaultCatPackageId(),
			':baseimgurl'=>$imageurl,
			':defaultmembershipimgurl'=>$config->getDefualtMemberShipImageurl(),
		);
		if($showonhome == 1)  //When From Home Page
		{
			$qry.=" and im.showonhome=:showonhome ";
			$parms[':showonhome']=$showonhome;
		}
		if($id)
		{
			$qry.=" and im.id=:itemid ";
			$parms[':itemid']=$id;
		}
		$qry.=" order by im.price,im.timestamp ";
		
		//print_r($parms);
		$result_ary=$DB->getmenual($qry,$parms,'package');

		$response['ispackagedata']=0;
		if(sizeof($result_ary)>0)
		{
			$response['ispackagedata']=1;

			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$attributedetail=new attributedetail();
				
				$qrymd="select distinct tid.id,tid.rowwebdisplayname as name,isnull(tid.rowattributename,'') as attributename,tid.rowdisplayorder,
					case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
					from tblitemdetails tid 
					left join tblitemiconmaster iim on iim.id=tid.rowiconid
					where tid.iswebsiteattribute=1 and tid.itemid=:itemid order by tid.rowdisplayorder";
				$mdparams = array(
					':imageurl'=>$imageurl,
					':itemid'=>$result_ary[$i]->getId(), 
				);
				$attributedetail=$DB->getmenual($qrymd,$mdparams,'attributedetail');

				if(sizeof($attributedetail)>0)
				{
					$result_ary[$i]->setAttributeDetail($attributedetail);
				}
			}	

			$response['packagedata']= json_decode(json_encode($result_ary));

			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nopackagefound'];
		}
	}
	//list Package Comparison
	else if($action == 'listcomparepkgdetail')
	{
		$qry="select im.id,im.itemname,im.price,im.timestamp
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid
			where im.isactive=1 and im.showonweb=1 and im.categoryid=:catpackageid ";
		$parms = array(
			':catpackageid'=>$config->getDefaultCatPackageId(),
		);
		$qry.=" order by im.price,im.timestamp";
		$result_ary=$DB->getmenual($qry,$parms);

		$response['ismembershipdata']=0;
		
		if(sizeof($result_ary)>0)
		{
			$response['ismembershipdata']=1;

			$htmldata='';
			$foothtmldata='';

			$htmldata.='<table class="table pricing-table">';


			$htmldata.='<thead class="primary-bg text-white">';

			$foothtmldata.='<tr class="tbl-row">';
			$foothtmldata.='<td></td>';

			$htmldata.='<tr>';
			$htmldata.='<th>Package</th>';
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$htmldata.='<th class="text-center">'.$result_ary[$i]['itemname'].'</th>';

				$foothtmldata.='<td class="text-center"><a href="javascript:void(0)" class="btn btn-brand-01 btn-xs px-3 btnaddtocart" data-isbuynow="1" data-type="2" data-id="'.$result_ary[$i]['id'].'">Buy Now</a></td>';
			}	
			$htmldata.='</tr>';

			$foothtmldata.='</tr>';

			$htmldata.='</thead>';



			//Plan Attributes
			$qryattr="select distinct isnull(tid.rowattributeid,'') as attributeid,isnull(tid.rowattributename,'') as attributename,am.displayorder
				from tblitemmaster im 
				inner join tblitemdetails tid on tid.itemid=im.id
				inner join tbltaxtype tt on tt.id=im.gsttypeid
				inner join tbltax tx on tx.id=im.gstid 
				inner join tbldurationmaster dm on dm.id=im.durationid 
				inner join tblattributemaster am on am.id=tid.rowattributeid
				where im.isactive=1 and im.showonweb=1 and im.categoryid=:catpackageid and tid.iswebsiteattribute=1
				order by am.displayorder";
			$attrparms = array(
				':catpackageid'=>$config->getDefaultCatPackageId(),
			);
			$resattr=$DB->getmenual($qryattr,$attrparms);

			if(sizeof($resattr)>0)
			{
				$htmldata.='<tbody>';

				
				for($j=0;$j<sizeof($resattr);$j++)
				{
					$htmldata.='<tr class="tbl-row">';
					$htmldata.='<td>'.$resattr[$j]['attributename'].'</td>';


					//Plan Attribute Value
					$qrypkgattr="select distinct im.id,im.itemname,im.price,im.timestamp,
						isnull((select top 1 tid.rowwebdisplayname from tblitemdetails tid where tid.rowattributeid=:attributeid and tid.itemid=im.id),'-') as attributevalue
						from tblitemmaster im 
						inner join tbltaxtype tt on tt.id=im.gsttypeid
						inner join tbltax tx on tx.id=im.gstid 
						inner join tbldurationmaster dm on dm.id=im.durationid
						where im.isactive=1 and im.showonweb=1 and im.categoryid=:catpackageid
						order by im.price,im.timestamp";
					$pkgattrparms = array(
						':catpackageid'=>$config->getDefaultCatPackageId(),
						':attributeid'=>$resattr[$j]['attributeid'],
					);
					$respkgattr=$DB->getmenual($qrypkgattr,$pkgattrparms);	

					for($k=0;$k<sizeof($respkgattr);$k++)
					{
						$htmldata.='<td class="text-center">'.$respkgattr[$k]['attributevalue'].'</td>';
					}	

					$htmldata.='</tr>';
				}
				

				$htmldata.='</tbody>';

				$htmldata.='<tfoot>';
				$htmldata.=$foothtmldata;
				$htmldata.='</tfoot>';
			}	
			


			$htmldata.='</table>';

			$response['comparisondata']= $htmldata;

			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nomshipfound'];
		}
	}
	
	

	
}

require_once dirname(__DIR__, 2).'\config\apifoot.php';

?>

  