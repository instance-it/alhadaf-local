<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\membership.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	 
	//list Membership
	if($action == 'listmembership')
	{
		$showonhome=$IISMethods->sanitize($_POST['showonhome']);   //From Home Page
		$id=$IISMethods->sanitize($_POST['id']);
		$membershipdata=new membership();
		
		$qry="select im.id,REPLACE(im.itemname,'&amp;','&') as itemname,im.price,tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,dm.day as duration,dm.daytext as durationname,dm.strday as strvalidityduration, 
			case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg,
			ISNULL((select top 1 CONCAT(:baseimgurl,image) from tblitemimage where itemid = im.id order by displayorder),:defaultmembershipimgurl) as image  
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid
			left join tblitemiconmaster iim on iim.id=im.iconid
			where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid ";
		$parms = array(
			':imageurl'=>$imageurl,
			':catmembershipid'=>$config->getDefaultCatMembershipId(),
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
		$qry.=" order by im.price,im.timestamp";
		$result_ary=$DB->getmenual($qry,$parms,'membership');

		$response['ismembershipdata']=0;
		
		if(sizeof($result_ary)>0)
		{
			$response['ismembershipdata']=1;

			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$attributedetail=new attributedetail();
				
				$qrymd="select distinct tid.id,REPLACE(tid.rowwebdisplayname,'&amp;','&') as name,REPLACE(isnull(tid.rowattributename,''),'&amp;','&') as attributename,tid.rowdisplayorder,
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

			$response['membershipdata']= json_decode(json_encode($result_ary));

			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nomshipfound'];
		}
	}
	//list Membership Comparison
	else if($action == 'listcomparemshipdetail')
	{
		$qry="select im.id,REPLACE(im.itemname,'&amp;','&') as itemname,im.price,im.timestamp
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid
			where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid ";
		$parms = array(
			':catmembershipid'=>$config->getDefaultCatMembershipId(),
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

				$foothtmldata.='<td class="text-center"><a href="javascript:void(0)" class="btn btn-brand-01 btn-xs px-3 btnaddtocart" data-isbuynow="1" data-type="1" data-id="'.$result_ary[$i]['id'].'">Buy Now</a></td>';
			}	
			$htmldata.='</tr>';

			$foothtmldata.='</tr>';

			$htmldata.='</thead>';



			//Plan Attributes
			$qryattr="select distinct isnull(tid.rowattributeid,'') as attributeid,REPLACE(isnull(tid.rowattributename,''),'&amp;','&') as attributename,am.displayorder
				from tblitemmaster im 
				inner join tblitemdetails tid on tid.itemid=im.id
				inner join tbltaxtype tt on tt.id=im.gsttypeid
				inner join tbltax tx on tx.id=im.gstid 
				inner join tbldurationmaster dm on dm.id=im.durationid 
				inner join tblattributemaster am on am.id=tid.rowattributeid
				where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid and tid.iswebsiteattribute=1
				order by am.displayorder";
			$attrparms = array(
				':catmembershipid'=>$config->getDefaultCatMembershipId(),
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
					$qrypkgattr="select distinct im.id,REPLACE(im.itemname,'&amp;','&') as itemname,im.price,im.timestamp,
						isnull((select top 1 tid.rowwebdisplayname from tblitemdetails tid where tid.rowattributeid=:attributeid and tid.itemid=im.id),'-') as attributevalue
						from tblitemmaster im 
						inner join tbltaxtype tt on tt.id=im.gsttypeid
						inner join tbltax tx on tx.id=im.gstid 
						inner join tbldurationmaster dm on dm.id=im.durationid
						where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid
						order by im.price,im.timestamp";
					$pkgattrparms = array(
						':catmembershipid'=>$config->getDefaultCatMembershipId(),
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
	//App Membership/Package Comparison For App
	else if($action == 'appcomparemshippkg_1')
	{
		$type=$IISMethods->sanitize($_POST['type']);   //1-Membership, 2-Packages

		$categoryid='';
		if($type == 1)  //1-Membership
		{
			$categoryid=$config->getDefaultCatMembershipId();
		}
		else if($type == 2)  //2-Packages
		{
			$categoryid=$config->getDefaultCatPackageId();
		}

		$qry="select im.id,REPLACE(im.itemname,'&amp;','&') as itemname,im.price,im.timestamp
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid
			where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid ";
		$parms = array(
			':catmembershipid'=>$categoryid,
		);
		$qry.=" order by im.price,im.timestamp";
		$result_ary=$DB->getmenual($qry,$parms);

		$response['iscomparedata']=0;
		
		if(sizeof($result_ary)>0)
		{
			$response['iscomparedata']=1;
			$response['cntdata']=(double)sizeof($result_ary)+1;

			$c=0;
			$m=0;
			$response['comparedata'][$c]['name0']='Package';
			$m++;
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				//$response['comparedata'][$c]['headerdata'][$m]['name']=$result_ary[$i]['itemname'];
				$response['comparedata'][$c]['name'.($i+1)]=$result_ary[$i]['itemname'];

				$m++;
			}
			

			
			$c++;


			//Plan Attributes
			$qryattr="select distinct isnull(tid.rowattributeid,'') as attributeid,REPLACE(isnull(tid.rowattributename,''),'&amp;','&') as attributename,am.displayorder
				from tblitemmaster im 
				inner join tblitemdetails tid on tid.itemid=im.id
				inner join tbltaxtype tt on tt.id=im.gsttypeid
				inner join tbltax tx on tx.id=im.gstid 
				inner join tbldurationmaster dm on dm.id=im.durationid 
				inner join tblattributemaster am on am.id=tid.rowattributeid
				where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid and tid.iswebsiteattribute=1
				order by am.displayorder";
			$attrparms = array(
				':catmembershipid'=>$categoryid,
			);
			$resattr=$DB->getmenual($qryattr,$attrparms);

			if(sizeof($resattr)>0)
			{
				
				for($j=0;$j<sizeof($resattr);$j++)
				{
					$m=0;
					//$response['comparedata'][$c]['bodydata'][$m]['name']=$resattr[$j]['attributename'];
					$response['comparedata'][$c]['name0']=$resattr[$j]['attributename'];



					//Plan Attribute Value
					$qrypkgattr="select distinct im.id,REPLACE(im.itemname,'&amp;','&') as itemname,im.price,im.timestamp,
						isnull((select top 1 tid.rowwebdisplayname from tblitemdetails tid where tid.rowattributeid=:attributeid and tid.itemid=im.id),'-') as attributevalue
						from tblitemmaster im 
						inner join tbltaxtype tt on tt.id=im.gsttypeid
						inner join tbltax tx on tx.id=im.gstid 
						inner join tbldurationmaster dm on dm.id=im.durationid
						where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid
						order by im.price,im.timestamp";
					$pkgattrparms = array(
						':catmembershipid'=>$categoryid,
						':attributeid'=>$resattr[$j]['attributeid'],
					);
					$respkgattr=$DB->getmenual($qrypkgattr,$pkgattrparms);	

					for($k=0;$k<sizeof($respkgattr);$k++)
					{
						$m++;
						//$response['comparedata'][$c]['bodydata'][$m]['name']=$respkgattr[$k]['attributevalue'];
						$response['comparedata'][$c]['name'.($k+1)]=$respkgattr[$k]['attributevalue'];
					}	



					$c++;
				}
			}		



			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nomshipfound'];
		}
	}
	//App Membership/Package Comparison For App
	else if($action == 'appcomparemshippkg')
	{
		$type=$IISMethods->sanitize($_POST['type']);   //1-Membership, 2-Packages

		$categoryid='';
		if($type == 1)  //1-Membership
		{
			$categoryid=$config->getDefaultCatMembershipId();
		}
		else if($type == 2)  //2-Packages
		{
			$categoryid=$config->getDefaultCatPackageId();
		}



		$qryattr="select distinct isnull(tid.rowattributeid,'') as attributeid,REPLACE(isnull(tid.rowattributename,''),'&amp;','&') as attributename,am.displayorder
			from tblitemmaster im 
			inner join tblitemdetails tid on tid.itemid=im.id
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid 
			inner join tblattributemaster am on am.id=tid.rowattributeid
			where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid and tid.iswebsiteattribute=1
			order by am.displayorder";
		$attrparms = array(
			':catmembershipid'=>$categoryid,
		);
		$resattr=$DB->getmenual($qryattr,$attrparms);

		$response['iscomparedata']=0;

		$c=0;
		if(sizeof($resattr)>0)
		{
			$response['iscomparedata']=1;

			$response['comparedata'][$c]['packagename']='PACKAGE';
			for($i=0;$i<sizeof($resattr);$i++)
			{
				//$response['comparedata'][$c]['headerdata'][$m]['name']=$result_ary[$i]['itemname'];

				if($i==0)
				{
					$response['comparedata'][$c]['data'][$i]['name']='PACKAGE';
				}
				$response['comparedata'][$c]['data'][$i+1]['name']=$resattr[$i]['attributename'];

				$m++;
			}

			$c++;




			$qrypkg="select im.id,REPLACE(im.itemname,'&amp;','&') as itemname,im.price,im.timestamp
				from tblitemmaster im 
				inner join tbltaxtype tt on tt.id=im.gsttypeid
				inner join tbltax tx on tx.id=im.gstid 
				inner join tbldurationmaster dm on dm.id=im.durationid
				where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid 
				order by im.price,im.timestamp";
			$pkgparms = array(
				':catmembershipid'=>$categoryid,
			);
			$respkg=$DB->getmenual($qrypkg,$pkgparms);
			if(sizeof($respkg)>0)
			{
				for($j=0;$j<sizeof($respkg);$j++)
				{
					$response['comparedata'][$c]['packagename']=$respkg[$j]['itemname'];
					

					//Plan Attribute Value
					$qrypkgattr="select distinct isnull(tid.rowattributeid,'') as attributeid,REPLACE(isnull(tid.rowattributename,''),'&amp;','&') as attributename,am.displayorder,
						isnull((select top 1 tid1.rowwebdisplayname from tblitemdetails tid1 where tid1.rowattributeid=tid.rowattributeid and tid1.itemid=:itemid),'-') as attributevalue
						from tblitemmaster im 
						inner join tblitemdetails tid on tid.itemid=im.id
						inner join tbltaxtype tt on tt.id=im.gsttypeid
						inner join tbltax tx on tx.id=im.gstid 
						inner join tbldurationmaster dm on dm.id=im.durationid 
						inner join tblattributemaster am on am.id=tid.rowattributeid
						where im.isactive=1 and im.showonweb=1 and im.categoryid=:catmembershipid and tid.iswebsiteattribute=1 
						order by am.displayorder";
					$pkgattrparms = array(
						':catmembershipid'=>$categoryid,
						':itemid'=>$respkg[$j]['id'],
					);
					$respkgattr=$DB->getmenual($qrypkgattr,$pkgattrparms);

					for($k=0;$k<sizeof($respkgattr);$k++)
					{
						if($k == 0)
						{
							$response['comparedata'][$c]['data'][$k]['name']=$respkg[$j]['itemname'];
						}
						$response['comparedata'][$c]['data'][$k+1]['name']=$respkgattr[$k]['attributevalue'];
					}	

					$c++;
				}
			}	



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

  