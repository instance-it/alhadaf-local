<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\membershiporder.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();


	//List Order member
	if($action == 'listordermember')
	{	
		$listordermember=new listordermember();

		$qry="SELECT distinct pm.id,concat(pm.personname,' (',pm.contact,')') as name,pm.personname,pm.contact
			from tblpersonmaster pm 
			inner join tblpersonutype pu on pu.pid = pm.id
			where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND pu.utypeid in (:memberutypeid,:guestutypeid) order by pm.personname";
		$params = array(
			':memberutypeid'=>$config->getMemberutype(),
			':adminuid'=>$config->getAdminUserId(),
			':guestutypeid'=>$config->getGuestutype(),

		);
		$listordermember=$DB->getmenual($qry,$params,'listordermember');

		$response['ismemberdata']=0;
		if($listordermember)
		{
			$response['ismemberdata']=1;
			$response['memberdata']=$listordermember;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Order Category
	else if($action == 'listordercategory')
	{	
		$listordercategory=new listordercategory();

		$qry="select c.id,c.category as name,isnull(iscourse,0) as iscourse
			from tblcategory c 
			where c.isactive=1 and c.id in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid) 
			order by (case when (c.displayorder>0) then c.displayorder else 99999 end)";
		$params=array(
			':defaultcatmembershipid'=>$config->getDefaultCatMembershipId(),
			':defaultcatcourseid'=>$config->getDefaultCatCourseId(),
			':defaultcatpackageid'=>$config->getDefaultCatPackageId(),
		);
		$listordercategory=$DB->getmenual($qry,$params,'listordercategory');

		$response['iscategorydata']=0;
		if($listordercategory)
		{
			$response['iscategorydata']=1;
			$response['categorydata']=$listordercategory;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Order Sub Category
	else if($action == 'listordersubcategory')
	{	
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$listordersubcategory=new listordersubcategory();

		$qry="select sc.id,sc.subcategory as name 
			from tblsubcategory sc 
			where sc.isactive=1 and CONVERT(VARCHAR(255), sc.categoryid) =:categoryid 
			order by (case when (sc.displayorder>0) then sc.displayorder else 99999 end)";	
		$params=array(
			':categoryid'=>$categoryid,
		);
		$listordersubcategory=$DB->getmenual($qry,$params,'listordersubcategory');

		$response['issubcategorydata']=0;
		if($listordersubcategory)
		{
			$response['issubcategorydata']=1;
			$response['subcategorydata']=$listordersubcategory;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//list Sub Category Wise Order Item (Membership/Packages)
	else if($action == 'listorderitem')
	{
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$subcategoryid=$IISMethods->sanitize($_POST['subcategoryid']);
		$iscourse=$IISMethods->sanitize($_POST['iscourse']);
		$id=$IISMethods->sanitize($_POST['id']);
		$listorderitem=new listorderitem();

		$type=0;
		if($categoryid == $config->getDefaultCatMembershipId())
		{
			$type=1;
		}
		else if($categoryid == $config->getDefaultCatPackageId())
		{
			$type=2;
		}
		else if($categoryid == $config->getDefaultCatCourseId())
		{
			$type=3;
		}
		
		$qry="select im.id,REPLACE(im.itemname,'&amp;','&') as itemname,im.price,isnull(im.itemno,'') as itemno,isnull(im.duration,'') as duration,'Hours' as strduration,isnull(im.noofstudent,'') as noofstudent,'Students' as strnoofstudent,
			tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,dm.day as duration,dm.daytext as durationname,dm.strday as strvalidityduration,isnull(im.descr,'') as descr, 
			case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg,
			ISNULL((select top 1 CONCAT(:baseimgurl,image) from tblitemimage where itemid = im.id order by displayorder),:defaultitemimgurl) as image,:iscourse as iscourse,:type as type  
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid
			left join tblitemiconmaster iim on iim.id=im.iconid
			where im.isactive=1 and im.showonpos=1 and im.categoryid=:categoryid and im.subcategoryid=:subcategoryid ";
		$parms = array(
			':imageurl'=>$imageurl,
			':categoryid'=>$categoryid,
			':subcategoryid'=>$subcategoryid,
			':baseimgurl'=>$imageurl,
			':iscourse'=>$iscourse,
			':type'=>$type,
		);

		if($iscourse == 1)
		{
			$parms[':defaultitemimgurl']=$config->getDefualtCourseImageurl();
		}
		else
		{
			$parms[':defaultitemimgurl']=$config->getDefualtMemberShipImageurl();
		}

		if($id)
		{
			$qry.=" and im.id=:itemid ";
			$parms[':itemid']=$id;
		}
		$qry.=" order by im.price,im.timestamp";
		$result_ary=$DB->getmenual($qry,$parms,'listorderitem');

		$response['isitemdata']=0;
		
		if(sizeof($result_ary)>0)
		{
			$response['isitemdata']=1;

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

			$response['itemdata']= json_decode(json_encode($result_ary));

			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//list Order Course Details
	else if($action == 'listordercoursedetails')
	{
		$itemid=$IISMethods->sanitize($_POST['itemid']);
		$coursedata=new coursedetail();

		$qry="select im.id,REPLACE(im.itemname,'&amp;','&') as itemname,im.price,isnull(im.itemno,'') as itemno,isnull(im.duration,'') as duration,'Hours' as strduration,
			isnull(im.noofstudent,'') as noofstudent,'Students' as strnoofstudent,tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,
			isnull(im.descr,'') as descr,:defaultcourseimgurl1 as defaultcourseimg,
			ISNULL((select top 1 CONCAT(:baseimgurl,image) from tblitemimage where itemid = im.id order by displayorder),:defaultcourseimgurl) as itemimg,
			dm.strday as strvalidityduration
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid
			where im.isactive=1 and im.id=:itemid";
		$parms = array(
			':itemid'=>$itemid,
			':baseimgurl'=>$imageurl,
			':defaultcourseimgurl'=>$config->getDefualtCourseImageurl(),
			':defaultcourseimgurl1'=>$config->getDefualtCourseImageurl(),
        );
		$result_ary=$DB->getmenual($qry,$parms,'coursedetail');
		
		$response['iscoursedata']=0;
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$ins_itemid=$result_ary[$i]->getId();

				/******************* Start For Course Image Data *********************/
				$courseimages=new courseimage();
				
				$qryimg="select ii.id,CONCAT(:baseimgurl,ii.image) as courseimg from tblitemimage ii WHERE ii.itemid=:itemid order by ii.displayorder";
				$imgparams = array(
					':itemid'=>$result_ary[$i]->getId(), 
					':baseimgurl'=>$imageurl,
				);
				$courseimages=$DB->getmenual($qryimg,$imgparams,'courseimage');

				$result_ary[$i]->setIsCourseImgData('0');
				if(sizeof($courseimages)>0)
				{
					$result_ary[$i]->setIsCourseImgData('1');

					$result_ary[$i]->setCourseImage($courseimages);
				}
				/******************* End For Course Image Data *********************/



				/******************* Start For Course Benefit Data *********************/
				$coursebenefit=new coursebenefit();
				
				$qrycb="select distinct tid.id,REPLACE(tid.rowwebdisplayname,'&amp;','&') as name,tid.rowdurationname,tid.rowdisplayorder,
					case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
					from tblitemdetails tid 
					left join tblitemiconmaster iim on iim.id=tid.rowiconid
					where tid.iscourse=1 and tid.itemid=:itemid order by tid.rowdisplayorder";
				$cbparams = array(
					':imageurl'=>$imageurl,
					':itemid'=>$result_ary[$i]->getId(), 
				);
				$coursebenefit=$DB->getmenual($qrycb,$cbparams,'coursebenefit');

				$result_ary[$i]->setIsCourseBenefit('0');
				if(sizeof($coursebenefit)>0)
				{
					$result_ary[$i]->setIsCourseBenefit('1');
					$result_ary[$i]->setCourseBenefit($coursebenefit);
				}
				/******************* End For Course Benefit Data *********************/



				/******************* Start For Course Display Data *********************/
				$coursedisplaydata=new coursedisplaydata();
				
				$qrycd="select distinct tid.id,REPLACE(tid.rowwebdisplayname,'&amp;','&') as name,tid.rowdisplayorder,
					case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
					from tblitemdetails tid 
					left join tblitemiconmaster iim on iim.id=tid.rowiconid
					where tid.iswebsiteattribute=1 and tid.itemid=:itemid order by tid.rowdisplayorder";
				$cdparams = array(
					':imageurl'=>$imageurl,
					':itemid'=>$result_ary[$i]->getId(), 
				);
				$coursedisplaydata=$DB->getmenual($qrycd,$cdparams,'coursedisplaydata');

				$result_ary[$i]->setIsCourseDisplayData('0');
				if(sizeof($coursedisplaydata)>0)
				{
					$result_ary[$i]->setIsCourseDisplayData('1');
					$result_ary[$i]->setCourseDisplayData($coursedisplaydata);
				}
				/******************* End For Course Display Data *********************/
				

				
			}

			$response['coursedata']= json_decode(json_encode($result_ary));
			$response['iscoursedata']=1;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
		
	}
	//Apply Coupon Code
	else if($action=='applycoupon')
	{
		$memberid=$IISMethods->sanitize($_POST['memberid']);   
		$couponcode=$IISMethods->sanitize($_POST['couponcode']);   
		$cartitemdata = $_POST['cartitemdata'];

		$currdate=$IISMethods->getformatcurrdate();   //Y-m-d

		if($platform == 5)  //POS
		{
			$removecoupon=$IISMethods->sanitize($_POST['removecoupon']);    //1-Remove Applied Coupon Code
			$LoginInfo=$IISMethods->convertcartjsontoobjarr($cartitemdata);
		}
		// else
		// {
		// 	$removecoupon=0;
		// 	$LoginInfo = $_SESSION[$config->getSessionName()];
		// }

		$Sess_CartItemInfo=$LoginInfo->getCartItemInfo();

	

		if($removecoupon==1)
		{
			$LoginInfo=$IISMethods->removecoupon($LoginInfo,$platform); 
			if($platform==5)
			{
				$CartItemInfo=$LoginInfo->getCartItemInfo();
				$LoginInfo->setCartItemInfo($CartItemInfo);
				
				$response['cartitemdata']=[$LoginInfo];
			}
			$status = 1;
			$message = $errmsg['couponremoved'];
		}
		else
		{
			if($couponcode)
			{
				$qrycode="SELECT c.*,
					(select count(id) from tblorder o where o.status=1 and o.iscancel=0 and convert(varchar(50),o.couponid)=convert(varchar(50),c.id)) as totaluse,
					(select count(id) from tblorder o where o.status=1 and o.iscancel=0 and convert(varchar(50),o.couponid)=convert(varchar(50),c.id) and convert(varchar(50),o.uid)=convert(varchar(50),:memberid)) as userwisecoupon 
					FROM tblcouponmaster c WHERE c.statusid=1 AND c.couponcode = :couponcode and (:currdate between CONVERT(date,c.startdate,103) and CONVERT(date,c.expirydate,103))";
				$couponparams=array(            
					':couponcode'=>$couponcode,
					':currdate'=>$currdate,
					':memberid'=>$memberid,
				);	
				//echo $qrycode;
				//print_r($couponparams);
				$rescode=$DB->getmenual($qrycode,$couponparams);
				//print_r($rescode);
				$numcode=sizeof($rescode);		
				
				if($numcode > 0)
				{
					$rowcode=$rescode[0];
					$chkexpdate=date('Y-m-d', strtotime(str_replace('/', '-', $rowcode['expirydate'])));
					$chktodaydate = date('Y-m-d');
					
					if(strtotime($chkexpdate) >= strtotime($chktodaydate))
					{
						$totaltaxableamt = 0;
						$totalamt = 0;
						
						for($i=0; $i<sizeof($Sess_CartItemInfo); $i++)
						{
							$CartItemInfo = new CartItemInfo();

							$totaltaxableamt += round($Sess_CartItemInfo[$i]->getTaxable(),3);
							$totalamt += round($Sess_CartItemInfo[$i]->getFinalprice(),3);
						}

						$disamt=0;
						$isapply=1;
						$distype=$rowcode['discounttype'];
						
						if($rowcode['minispend']>0 && $totaltaxableamt<$rowcode['minispend'])
						{
							$isapply=0;
							$status=0;
							$message=str_replace("#minispendamt#",$rowcode['minispend'],$errmsg['minispendcoupon']);			
						}
						if($rowcode['maxspend']>0 && $totaltaxableamt>$rowcode['maxspend'])
						{
							$isapply=0;
							$status=0;
							$message=str_replace("#maxspendamt#",$rowcode['maxspend'],$errmsg['maxspendcoupon']);			
						}	
						
						if($rowcode['limitpercoupon']>0 && $rowcode['limitpercoupon']<=$rowcode['totaluse'])
						{
							$isapply=0;
							$status=0;
							$message=$errmsg['couponlimitreach'];
						}				
						if($rowcode['limitpermember']>0 && $rowcode['limitpermember']<=$rowcode['userwisecoupon'])
						{
							$isapply=0;
							$status=0;
						}					

						if($isapply==1)
						{
							if($totaltaxableamt > 0)
							{

								$IISMethods->removecoupon($LoginInfo,$platform); 
								
								if($distype==1)  //QAR
								{
									$disamt=$rowcode['couponamt'];
									if($disamt>=$totaltaxableamt)
									{										
										$status=0;
										$message=$errmsg['couponnotapplied'];
									}
									else
									{				
										$disamt=round($rowcode['couponamt']);	
										
										$LoginInfo->setcouponapply(1);
										$LoginInfo->setcouponid($rowcode['id']);
										$LoginInfo->setcouponcode($couponcode);
										$LoginInfo->setcoupontype($distype);
										$LoginInfo->setcouponamount($disamt);
										$LoginInfo->setcouponpercent(0);


										$singleiteminfo=array();
										for($i=0; $i<sizeof($Sess_CartItemInfo); $i++)
										{	
											$CartItemInfo = new CartItemInfo();

											$igst=$Sess_CartItemInfo[$i]->getIgst();
											$igsttaxamt=round($Sess_CartItemInfo[$i]->getIgstTaxAmt(),3);
											$taxable=round($Sess_CartItemInfo[$i]->getTaxable(),3);
											$finalprice=round($Sess_CartItemInfo[$i]->getFinalprice(),3);

											
											$sing_percent=round((($taxable/$totaltaxableamt)*100),2);
											$sing_discamt = round(($disamt*$sing_percent/100),3);
											
											$new_taxable=round(($taxable-$sing_discamt),3);
											$new_igstamt=round(($new_taxable*$igst/100),3);
											$new_cgstamt=round(($new_igstamt/2),3);
											$new_sgstamt=round(($new_igstamt/2),3);
											$new_finalprice=$new_taxable+$new_igstamt;


											$CartItemInfo->setId($Sess_CartItemInfo[$i]->getId());
											$CartItemInfo->setItemname($Sess_CartItemInfo[$i]->getItemname());
											$CartItemInfo->setPrice($Sess_CartItemInfo[$i]->getPrice());

											$CartItemInfo->setTaxtype($Sess_CartItemInfo[$i]->getTaxtype());
											$CartItemInfo->setTaxtypename($Sess_CartItemInfo[$i]->getTaxtypename());
											$CartItemInfo->setSgst($Sess_CartItemInfo[$i]->getSgst());
											$CartItemInfo->setCgst($Sess_CartItemInfo[$i]->getCgst());
											$CartItemInfo->setIgst($Sess_CartItemInfo[$i]->getIgst());
											$CartItemInfo->setTaxable($new_taxable);
											$CartItemInfo->setIgstTaxAmt($new_igstamt);
											$CartItemInfo->setSgstTaxAmt($new_sgstamt);
											$CartItemInfo->setCgstTaxAmt($new_cgstamt);
											$CartItemInfo->setFinalprice($new_finalprice);
											$CartItemInfo->setDuration($Sess_CartItemInfo[$i]->getDuration());
											$CartItemInfo->setDurationname($Sess_CartItemInfo[$i]->getDurationname());
											$CartItemInfo->setStrValidityDuration($Sess_CartItemInfo[$i]->getStrValidityDuration());

											$CartItemInfo->setType($Sess_CartItemInfo[$i]->getType());
											$CartItemInfo->setImage($Sess_CartItemInfo[$i]->getImage());
											$CartItemInfo->setIconImg($Sess_CartItemInfo[$i]->getIconImg());

											$CartItemInfo->setcouponamount($sing_discamt);

											$singleiteminfo[$i]=$CartItemInfo;
										}

										$LoginInfo->setCartItemInfo($singleiteminfo);	

										$response['cartitemdata']=[$LoginInfo];


										$status=1;
										$message=$errmsg['couponapplied'];										
									}
								}
								else if($distype==2)  //Percentage
								{
									$per=$rowcode['couponamt'];
									if($per<100)
									{	
										$disamt=($totaltaxableamt*$per)/100;	

										$LoginInfo->setcouponapply(1);
										$LoginInfo->setcouponid($rowcode['id']);
										$LoginInfo->setcouponcode($couponcode);
										$LoginInfo->setcoupontype($distype);
										$LoginInfo->setcouponamount($disamt);
										$LoginInfo->setcouponpercent($per);

										
										$singleiteminfo=array();
										for($i=0; $i<sizeof($Sess_CartItemInfo); $i++)
										{	
											$CartItemInfo = new CartItemInfo();

											$igst=$Sess_CartItemInfo[$i]->getIgst();
											$igsttaxamt=round($Sess_CartItemInfo[$i]->getIgstTaxAmt(),3);
											$taxable=round($Sess_CartItemInfo[$i]->getTaxable(),3);
											$finalprice=round($Sess_CartItemInfo[$i]->getFinalprice(),3);

											$sing_percent=round((($taxable/$totaltaxableamt)*100),2);
											$sing_discamt = round(($disamt*$sing_percent/100),3);
											
											$new_taxable=round(($taxable-$sing_discamt),3);
											$new_igstamt=round(($new_taxable*$igst/100),3);
											$new_cgstamt=round(($new_igstamt/2),3);
											$new_sgstamt=round(($new_igstamt/2),3);
											$new_finalprice=$new_taxable+$new_igstamt;

											$CartItemInfo->setId($Sess_CartItemInfo[$i]->getId());
											$CartItemInfo->setItemname($Sess_CartItemInfo[$i]->getItemname());
											$CartItemInfo->setPrice($Sess_CartItemInfo[$i]->getPrice());

											$CartItemInfo->setTaxtype($Sess_CartItemInfo[$i]->getTaxtype());
											$CartItemInfo->setTaxtypename($Sess_CartItemInfo[$i]->getTaxtypename());
											$CartItemInfo->setSgst($Sess_CartItemInfo[$i]->getSgst());
											$CartItemInfo->setCgst($Sess_CartItemInfo[$i]->getCgst());
											$CartItemInfo->setIgst($Sess_CartItemInfo[$i]->getIgst());
											$CartItemInfo->setTaxable($new_taxable);
											$CartItemInfo->setIgstTaxAmt($new_igstamt);
											$CartItemInfo->setSgstTaxAmt($new_sgstamt);
											$CartItemInfo->setCgstTaxAmt($new_cgstamt);
											$CartItemInfo->setFinalprice($new_finalprice);
											$CartItemInfo->setDuration($Sess_CartItemInfo[$i]->getDuration());
											$CartItemInfo->setDurationname($Sess_CartItemInfo[$i]->getDurationname());
											$CartItemInfo->setStrValidityDuration($Sess_CartItemInfo[$i]->getStrValidityDuration());

											$CartItemInfo->setType($Sess_CartItemInfo[$i]->getType());
											$CartItemInfo->setImage($Sess_CartItemInfo[$i]->getImage());
											$CartItemInfo->setIconImg($Sess_CartItemInfo[$i]->getIconImg());

											$CartItemInfo->setcouponamount($sing_discamt);

											$singleiteminfo[$i]=$CartItemInfo;
										}

										$LoginInfo->setCartItemInfo($singleiteminfo);

										$response['cartitemdata']=[$LoginInfo];

										$status=1;
										$message=$errmsg['couponapplied'];	
									}
									else
									{
										$status=0;
										$message=$errmsg['couponnotapplied'];						
									}			
								}
							}
							else
							{
								$status=0;
								$message=$errmsg['couponnotapplied'];	
							}					
						}
					}
					else
					{
						$status=0;
						$message=$errmsg['couponexpired'];				
					}
				}
				else
				{
					$status=0;
					$message=$errmsg['invalidcoupon'];	
				}
			}
			else
			{
				$status=0;
				$message=$errmsg['couponrequired'];
			}
		}
	}
	//List Order Payment Type
	else if($action == 'listorderpaymenttype')
	{	
		$i=0;
		$response['paymentdata'][$i]['id']="1";
		$response['paymentdata'][$i]['name']="Cash";
		$response['paymentdata'][$i]['isonline']="0";

		$i++;

		$response['paymentdata'][$i]['id']="2";
		$response['paymentdata'][$i]['name']="Online Payment";
		$response['paymentdata'][$i]['isonline']="1";


		$status = 1;
		$message = $errmsg['success'];
	}
	//Insert Order Data
	else if ($action=='insertorderdata')
	{
		$memberid=$IISMethods->sanitize($_POST['memberid']);   
		$LoginInfo=$IISMethods->convertcartjsontoobjarr($_POST['cartitemdata']);

		$paymenttype = $IISMethods->sanitize($_POST['paymenttype']);	
		$referenceno = $IISMethods->sanitize($_POST['referenceno']);
		$ordernote = $IISMethods->sanitize($_POST['ordernote']);

		$seesionarray=json_encode($LoginInfo,true);

		
		$CartItemInfo=$LoginInfo->getCartItemInfo();
		$cart_sessionarray=json_encode($CartItemInfo,true);


		if(sizeof($CartItemInfo) > 0)
		{
			$typename='order';
			$qryseries = "SELECT TOP 1 * FROM tblseriesmaster WHERE typename=:typename ORDER BY timestamp DESC";
			$seriesparams = array(
				':typename'=>$typename,
			);
			$result_ary=$DB->getmenual($qryseries,$seriesparams);
			$rowseries=$result_ary[0];
			$ord_seriesid=$rowseries['id'];

			if($ord_seriesid)
			{
				try 
				{
					$DB->begintransaction();

					//Check Member Bind With SAP
					$qrymemb = "select isnull(sappersonid,'') as sappersonid from tblpersonmaster where id=:memberid";
					$membparams = array(
						':memberid'=>$memberid,
					);
					$resmem=$DB->getmenual($qrymemb,$membparams);
					$rowmemb=$resmem[0];

					if($rowmemb['sappersonid'] != '' || $config->getIsAccessSAP() == 0)   //When Member Bind With SAP
					{
						//Check Item Bind With SAP
						if(sizeof($CartItemInfo)>0)
						{
							$isbinditem=1;
							for($i=0;$i<sizeof($CartItemInfo);$i++)
							{
								$itemid=$CartItemInfo[$i]->getId();


								$qryitem = "select isnull(sapitemid,'') as sapitemid from tblitemmaster where id=:itemid";
								$itemparams = array(
									':itemid'=>$itemid,
								);
								$resitem=$DB->getmenual($qryitem,$itemparams);
								$rowitem=$resitem[0];

								if($rowitem['sapitemid'] == '')
								{
									$isbinditem=0;
								}
							}
						}


						if($isbinditem == 1 || $config->getIsAccessSAP() == 0)   //When Item Bind With SAP
						{
		
							$datetime = $IISMethods->getdatetime();
							$order_unqid = $IISMethods->generateuuid();
							$order_date = $IISMethods->getcurrdate();
							$transactionid=uniqid('ASR');

							
							$ord_seriesno=$DB->getorderno($ord_seriesid,'order',$order_date);	
							$ord_maxid=$DB->getmaxid($ord_seriesid,'order');
							$ord_prefix=$DB->getseriseprefix($ord_seriesid);


							// $ord_seriesid=$mssqldefval['uniqueidentifier'];;
							// $ord_seriesno='';	
							// $ord_maxid=0;
							// $ord_prefix='';
				

							$insqry['[id]']=$order_unqid;
							$insqry['[transactionid]']=$transactionid;
							$insqry['[orderno]']=$ord_seriesno;
							$insqry['[uid]']=$memberid;
							
							$insqry['[totalamount]']=0;
							$insqry['[couponapply]']=$LoginInfo->getcouponapply();
							if($LoginInfo->getcouponapply() == 1)
							{
								$insqry['[couponid]']=$LoginInfo->getcouponid();
								$insqry['[couponcode]']=$LoginInfo->getcouponcode();
								$insqry['[coupontype]']=$LoginInfo->getcoupontype();
								$insqry['[couponamount]']=$LoginInfo->getcouponamount();
								$insqry['[couponpercent]']=$LoginInfo->getcouponpercent();
							}
							else
							{
								$insqry['[couponid]']=$mssqldefval['uniqueidentifier'];
								$insqry['[couponcode]']='';
								$insqry['[coupontype]']=0;
								$insqry['[couponamount]']=0;
								$insqry['[couponpercent]']=0;
							}
							
							$insqry['[totaltaxableamt]']=0;
							$insqry['[totaltax]']=0;
							$insqry['[totalpaid]']=0;
							$insqry['[ordernotes]']=$ordernote;

							$insqry['[orderdate]']=$order_date;
							
							$insqry['[sessionarray]']=$seesionarray;
							$insqry['[platform]']=$platform;
							$insqry['[paymenttype]']=$paymenttype;
							$insqry['[referenceno]']=$referenceno;
							$insqry['[status]']=1;
							
							$insqry['[seriesid]']=$ord_seriesid;
							$insqry['[prefix]']=$ord_prefix;
							$insqry['[maxid]']=$ord_maxid;

							$insqry['[timestamp]']=$datetime;
							$insqry['[entry_uid]']=$uid;	
							$insqry['[entry_date]']=$datetime;
							//print_r($insqry);
							$DB->executedata('i','tblorder',$insqry,'');
								
				
							// Order DETAILS
							$totalamount=0;
							$totaltaxableamt=0;
							$totaltax=0;
							$totalpaid=0;
							if(sizeof($CartItemInfo)>0)
							{
								for($i=0;$i<sizeof($CartItemInfo);$i++)
								{
									//$CartItemInfo = $CartItemInfo[$k];
									$od_id = $IISMethods->generateuuid();
									
									$type=$CartItemInfo[$i]->getType();
									$itemid=$CartItemInfo[$i]->getId();
									$itemname=$CartItemInfo[$i]->getItemname();
									$durationday=0;
									if($CartItemInfo[$i]->getDuration() > 0)
									{
										$durationday=$CartItemInfo[$i]->getDuration();
									}
									$durationname=$CartItemInfo[$i]->getDurationname();
									$strvalidityduration=$CartItemInfo[$i]->getStrValidityDuration();

									$taxtype=$CartItemInfo[$i]->getTaxtype();
									$taxtypename=$CartItemInfo[$i]->getTaxtypename();
									$sgst=$CartItemInfo[$i]->getSgst();
									$cgst=$CartItemInfo[$i]->getCgst();
									$igst=$CartItemInfo[$i]->getIgst();
									$price=$CartItemInfo[$i]->getPrice();
									$couponamount=$CartItemInfo[$i]->getcouponamount();
									$taxable=$CartItemInfo[$i]->getTaxable();
									$igsttaxamt=$CartItemInfo[$i]->getIgstTaxAmt();
									$sgsttaxamt=$CartItemInfo[$i]->getSgstTaxAmt();
									$cgsttaxamt=$CartItemInfo[$i]->getCgstTaxAmt();
									$finalprice=$CartItemInfo[$i]->getFinalprice();


									//Item 
									$qryim = "select im.id,im.descr,im.duration,im.noofstudent,
										convert(varchar,DATEADD(DAY, $durationday, :currdate),103) as expirydt 
										from tblitemmaster im 
										where im.id=:itemid ";
									$imparams=array(
										':itemid'=>$itemid,
										//':durationday'=>$durationday,
										':currdate'=>$IISMethods->getformatcurrdate(),
									);
									//echo $qryim;
									//print_r($imparams);
									$resim = $DB->getmenual($qryim,$imparams);
									
				
									$od_insqry['[id]']=$od_id;
									$od_insqry['[orderid]']=$order_unqid;
									$od_insqry['[type]']=$type;
									$od_insqry['[itemid]']=$itemid;
									$od_insqry['[itemname]']=$itemname;
									$od_insqry['[description]']=$resim[0]['descr'];
									$od_insqry['[courseduration]']=$resim[0]['duration'];
									$od_insqry['[noofstudent]']=$resim[0]['noofstudent'];
									$od_insqry['[startdate]']=$IISMethods->getcurrdate();
									$od_insqry['[expirydate]']=$resim[0]['expirydt'];
									$od_insqry['[n_expirydate]']=$resim[0]['expirydt'];
									$od_insqry['[durationday]']=$durationday;
									$od_insqry['[durationname]']=$durationname;
									$od_insqry['[strvalidityduration]']=$strvalidityduration;
									$od_insqry['[taxtype]']=$taxtype;
									$od_insqry['[taxtypename]']=$taxtypename;
									$od_insqry['[sgst]']=$sgst;
									$od_insqry['[cgst]']=$cgst;
									$od_insqry['[igst]']=$igst;
									$od_insqry['[price]']=$price;
									$od_insqry['[couponamount]']=$couponamount;
									$od_insqry['[taxable]']=$taxable;
									$od_insqry['[sgsttaxamt]']=$sgsttaxamt;
									$od_insqry['[cgsttaxamt]']=$cgsttaxamt;
									$od_insqry['[igsttaxamt]']=$igsttaxamt;
									$od_insqry['[finalprice]']=$finalprice;
									$od_insqry['[timestamp]']=$datetime;

									//print_r($od_insqry);
									
									$DB->executedata('i','tblorderdetail',$od_insqry,'');



									//Item Details
									$qryitem = "select tid.* from tblitemdetails tid where tid.itemid=:itemid ";
									$itemparams=array(
										':itemid'=>$itemid
									);
									$resitem = $DB->getmenual($qryitem,$itemparams); 

									if(sizeof($resitem) > 0)
									{
										for($j=0;$j<sizeof($resitem);$j++)
										{
											$rowid=$resitem[$j];

											//When All Items Of Subcategory
											if($rowid['rowitemid'] == $mssqldefval['uniqueidentifier'] && $rowid['iswebsiteattribute'] != 1 && $rowid['iscourse'] != 1) 
											{
												//Item Details
												$qrysitem = "select ti.id,ti.itemname from tblitemmaster ti where ti.isactive=1 and ti.subcategoryid=:subcategoryid";
												$sitemparams=array(
													':subcategoryid'=>$rowid['rowsubcatid']
												);
												$ressitem = $DB->getmenual($qrysitem,$sitemparams); 

												if(sizeof($ressitem) > 0)
												{
													for($m=0;$m<sizeof($ressitem);$m++)
													{
														$rowsi=$ressitem[$m];

														$oid_id = $IISMethods->generateuuid();

														$oid_insqry['[id]']=$oid_id;
														$oid_insqry['[orderid]']=$order_unqid;
														$oid_insqry['[odid]']=$od_id;
														$oid_insqry['[catid]']=$rowid['rowcatid'];
														$oid_insqry['[category]']=$rowid['rowcategory'];
														$oid_insqry['[subcatid]']=$rowid['rowsubcatid'];
														$oid_insqry['[subcategory]']=$rowid['rowsubcategory'];
														$oid_insqry['[itemid]']=$rowsi['id'];
														$oid_insqry['[itemname]']=$rowsi['itemname'];
														$oid_insqry['[attributeid]']=$rowid['rowattributeid'];
														$oid_insqry['[attributename]']=$rowid['rowattributename'];
														$oid_insqry['[webdisplayname]']=$rowid['rowwebdisplayname'];
														$oid_insqry['[qty]']=$rowid['rowqty'];
														$oid_insqry['[usedqty]']=0;
														$oid_insqry['[remainqty]']=$rowid['rowqty'];
														$oid_insqry['[iconid]']=$rowid['rowiconid'];
														$oid_insqry['[displayorder]']=$rowid['rowdisplayorder'];
														$oid_insqry['[durationid]']=$rowid['rowdurationid'];
														$oid_insqry['[durationname]']=$rowid['rowdurationname'];
														$oid_insqry['[durationdays]']=$rowid['rowdurationdays'];

														$oid_insqry['[discount]']=$rowid['rowdiscount'];
														$oid_insqry['[price]']=$rowid['rowprice'];
														$oid_insqry['[gsttypeid]']=$rowid['rowgsttypeid'];
														$oid_insqry['[gstid]']=$rowid['rowgstid'];
														$oid_insqry['[gst]']=$rowid['rowgst'];
														$oid_insqry['[gsttype]']=$rowid['rowgsttype'];

														$oid_insqry['[typeid]']=$rowid['rowtypeid'];
														$oid_insqry['[typestr]']=$rowid['rowtypestr'];
														$oid_insqry['[type]']=$rowid['rowtype'];

														$oid_insqry['[iswebsiteattribute]']=$rowid['iswebsiteattribute'];
														$oid_insqry['[iscourse]']=$rowid['iscourse'];
														$oid_insqry['[fromallitem]']=1;
														$oid_insqry['[timestamp]']=$datetime;
														
														$DB->executedata('i','tblorderitemdetail',$oid_insqry,'');

													}
												}
											}
											else
											{
												$oid_id = $IISMethods->generateuuid();

												$oid_insqry['[id]']=$oid_id;
												$oid_insqry['[orderid]']=$order_unqid;
												$oid_insqry['[odid]']=$od_id;
												$oid_insqry['[catid]']=$rowid['rowcatid'];
												$oid_insqry['[category]']=$rowid['rowcategory'];
												$oid_insqry['[subcatid]']=$rowid['rowsubcatid'];
												$oid_insqry['[subcategory]']=$rowid['rowsubcategory'];
												$oid_insqry['[itemid]']=$rowid['rowitemid'];
												$oid_insqry['[itemname]']=$rowid['rowitemname'];
												$oid_insqry['[attributeid]']=$rowid['rowattributeid'];
												$oid_insqry['[attributename]']=$rowid['rowattributename'];
												$oid_insqry['[webdisplayname]']=$rowid['rowwebdisplayname'];
												$oid_insqry['[qty]']=$rowid['rowqty'];
												$oid_insqry['[usedqty]']=0;
												$oid_insqry['[remainqty]']=$rowid['rowqty'];
												$oid_insqry['[iconid]']=$rowid['rowiconid'];
												$oid_insqry['[displayorder]']=$rowid['rowdisplayorder'];
												$oid_insqry['[durationid]']=$rowid['rowdurationid'];
												$oid_insqry['[durationname]']=$rowid['rowdurationname'];
												$oid_insqry['[durationdays]']=$rowid['rowdurationdays'];

												$oid_insqry['[discount]']=$rowid['rowdiscount'];
												$oid_insqry['[price]']=$rowid['rowprice'];
												$oid_insqry['[gsttypeid]']=$rowid['rowgsttypeid'];
												$oid_insqry['[gstid]']=$rowid['rowgstid'];
												$oid_insqry['[gst]']=$rowid['rowgst'];
												$oid_insqry['[gsttype]']=$rowid['rowgsttype'];

												$oid_insqry['[typeid]']=$rowid['rowtypeid'];
												$oid_insqry['[typestr]']=$rowid['rowtypestr'];
												$oid_insqry['[type]']=$rowid['rowtype'];

												$oid_insqry['[iswebsiteattribute]']=$rowid['iswebsiteattribute'];
												$oid_insqry['[iscourse]']=$rowid['iscourse'];
												$oid_insqry['[fromallitem]']=0;
												$oid_insqry['[timestamp]']=$datetime;
												
												$DB->executedata('i','tblorderitemdetail',$oid_insqry,'');
											}	
										}
									}


									$totalamount+=$price;
									$totaltaxableamt+=$taxable;
									$totaltax+=$igsttaxamt;
									$totalpaid+=$finalprice;

								}
							}


							//Update Order Data
							$updord['[totalamount]']=$totalamount;
							$updord['[totaltaxableamt]']=$totaltaxableamt;
							$updord['[totaltax]']=$totaltax;
							$updord['[totalpaid]']=$totalpaid;
							$upddata['[id]']=$order_unqid;
							$DB->executedata('u','tblorder',$updord,$upddata);

							if($platform == 4)
							{
								$IISMethods->removecoupon($LoginInfo,$platform);
								$IISMethods->unsetbookingseesion();
							}

							//Generate Order Invoice Number
							$DB->generateorderinvoicenumber($order_unqid,$uid,$platform);


							/******************* Start For New Order Notifications ******************/

							$title = "New Order";
							$msgtext="Your order(".$ord_seriesno.") has been placed.";
							
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
							$clickflag=1;
							$data="";
							$pagename='orderhistory';
							$actionname='listorderhistory';
											
							$extra = array('clickflag' => $clickflag,'pagename' =>$pagename,'actionname' =>$actionname,'orderid' =>$order_unqid,'imageurl' => '');
							
							$IISMethods->androidnotification($adeviceid,$msgtext,$title,$clickaction,$extra);
							$IISMethods->iosnotification($ideviceid,$msgtext,$title,$extra);
							
							$subnotiunqid = $IISMethods->generateuuid();
							$notiinsary=array(
								'[id]'=>$subnotiunqid,
								'[ntypeid]'=>1,
								'[orderid]'=>$order_unqid,
								'[uid]'=>$memberid,
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
							
							/******************* End For New Order Notifications ******************/

							if($config->getIsAccessSAP() == 1)
							{
								//Insert Package Data in SAP AR Invoice (HaNa DB)
								$DB->SAPInsertARInvoiceData($SubDB,$order_unqid);
							}


							//Generate Order Invoice
							//$genpdf = file_get_contents($config->getalhadafpdfurl().'mshipinvoice.php?id='.$order_unqid.'&type=generate&isshow=1');


							//Send User New Order (Membership/Package/Course) Email
							$DB->userneworderemailssms(1,$order_unqid);   //type 1-Email,2-SMS

							//Send User New Order (Membership/Package/Course) SMS
							//$DB->userneworderemailssms(2,$order_unqid);   //type 1-Email,2-SMS

							
							$response['transactionid']=$transactionid;
							$response['orderno']=(string)$ord_seriesno;
							$response['cmp_logo']=(string)$imageurl.'images/posprint.png';
							//$response['cmp_logo']=(string)$imageurl.$CompanyInfo->getLogoImg();
							$response['cmp_address']=(string)$CompanyInfo->getAddress();
							$response['cmp_email']=(string)$CompanyInfo->getEmail1();
							$response['cmp_contact']=(string)$CompanyInfo->getContact1();

							$response['cmp_israngehour']='0';
							if(sizeof($CompanyInfo->getCmpRangeHour()) > 0)
							{
								$response['cmp_israngehour']='1';
								for($k=0;$k<sizeof($CompanyInfo->getCmpRangeHour());$k++)
								{
									$response['cmp_rangehour'][$k]['name']=$CompanyInfo->getCmpRangeHour()[$k]->getName();
								}	
							}
							
							$response['curr_datetime']=date('M d Y h:iA');


							$status=1;
							$message=$errmsg['ordersaved'];

						}
						else 
						{
							$status=0;
							$message=$errmsg['nobinditemsap'];
						}	
					}
					else
					{
						$status=0;
						$message=$errmsg['nobindmembersap'];
					}		
					
					$DB->committransaction();
				} 
				catch (Exception $e) 
				{
					//Order Error 
					$DB->rollbacktransaction($e);
					$status=0;
					$message=$errmsg['orderdberror'];
				}	
			}
			else
			{
				$status=0;
				$message=$errmsg['noorderseries'];
			}
		}
			
	}
	//List Membership Order Data
	else if ($action=='listorderhistory')
	{
		$fltsearch=$IISMethods->sanitize($_POST['fltsearch']);
		$fltfromdate=$IISMethods->sanitize($_POST['fltfromdate']);
		$flttodate=$IISMethods->sanitize($_POST['flttodate']);

		$listorderhistory=new listorderhistory();
		
		$qry = "select distinct o.id,o.timestamp,o.transactionid,o.orderno,o.uid,o.totalamount,o.couponapply,o.couponid,o.couponcode,o.couponamount,
			o.totaltaxableamt,o.totaltax,o.totalpaid,
			case when (o.iscancel=1) then 0 else 1 end as iscancel,
			case when (o.iscancel=1) then 'Cancelled' else 'Confirmed' end as orderstatus,case when (o.iscancel=1) then '#ff000f' else '#8dbf42' end as orderstatuscolor,
			convert(varchar, o.timestamp,100) AS ofulldate,pm.personname as membername,pm.contact as membercontact,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,
			isnull((select top 1 concat(:imageurl,pdfurl) from tblorderinvoice where orderid=o.id and isnull(pdfurl,'')<>'' and o.iscancel=0),'') as invoicepdfurl
			from tblorder o 
			inner join tblorderdetail od on od.orderid=o.id 
			inner join tblpersonmaster pm on pm.id=o.uid 
			inner join tblpersonmaster pm1 on pm1.id=o.entry_uid 
			where o.status=1  ";
		$parms = array(
			':imageurl'=>$config->getImageurl(),
		);

		if($fltsearch)
		{
			$qry.= " and (o.transactionid like :filter1 or o.orderno like :filter2 or o.totalpaid like :filter3 or convert(varchar, o.timestamp,100) like :filter4 or pm.personname like :filter5 or pm.contact like :filter6 or pm1.personname like :filter7 or pm1.contact like :filter8) ";
			$parms[':filter1']='%'.$fltsearch.'%';
			$parms[':filter2']='%'.$fltsearch.'%';
			$parms[':filter3']='%'.$fltsearch.'%';
			$parms[':filter4']='%'.$fltsearch.'%';
			$parms[':filter5']='%'.$fltsearch.'%';
			$parms[':filter6']='%'.$fltsearch.'%';
			$parms[':filter7']='%'.$fltsearch.'%';
			$parms[':filter8']='%'.$fltsearch.'%';
		}

		if($fltfromdate && $flttodate)
		{
			$qry.= " and (convert(date,o.timestamp,100) between convert(date,:fltfromdate,103) and convert(date,:flttodate,103)) ";
			$parms[':fltfromdate']=$fltfromdate;
			$parms[':flttodate']=$flttodate;
		}

		$qry.=" ORDER BY o.timestamp DESC offset $start rows fetch next $per_page rows only";

		$result_ary=$DB->getmenual($qry,$parms,'listorderhistory');
		
		$response['isorderhistorydata']=0;
		if(sizeof($result_ary) > 0)
		{	
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$orderdetailinfo=new orderdetailinfo();

				$qryod="select od.id,od.orderid,od.type,od.itemid,REPLACE(od.itemname,'&amp;','&') as itemname,od.durationday,od.durationname,isnull(od.strvalidityduration,'') as strvalidityduration,od.description,od.courseduration,od.noofstudent,
					od.startdate,od.expirydate,od.n_expirydate,od.taxtype,od.taxtypename,od.sgst,od.cgst,od.igst,od.price,od.couponamount,od.taxable,od.sgsttaxamt,od.cgsttaxamt,od.igsttaxamt,od.finalprice,
					case when (od.type = 1) then 'Membership' when (od.type = 2) then 'Packages' when (od.type = 3) then 'Course' else '' end as typename,
					case when (o.iscancel=1) then '' when (convert(date,od.n_expirydate,103) >= :currdate1) then concat('Expires On ',od.n_expirydate) else concat('Expired On ',od.n_expirydate) end as strexpire,
					case when (o.iscancel=1) then '' when (convert(date,od.n_expirydate,103) >= :currdate2) then '#008140' else '#e63c2e' end as strexpirecolor 
					from tblorderdetail od 
					inner join tblorder o on od.orderid=o.id 
					where od.orderid=:orderid";
				$odparams = array(
					':orderid'=>$result_ary[$i]->getId(), 
					':currdate1'=>$currdate, 
					':currdate2'=>$currdate, 
				);
				$orderdetailinfo=$DB->getmenual($qryod,$odparams,'orderdetailinfo');

				if(sizeof($orderdetailinfo)>0)
				{
					$result_ary[$i]->setOrderDetailInfo($orderdetailinfo);
				}

			}
			
			$response['isorderhistorydata']=1;
			$response['orderhistorydata']=json_decode(json_encode($result_ary));
			
			$response['nextpage']=$nextpage;

			$common_listdata=$result_ary;

			
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}

		
		if($nextpage!=1)
		{
			$response['isorderhistorydata']=1;
			$status=1;
			$message=$errmsg['success'];
		}



		$response['cmp_logo']=(string)$imageurl.'images/posprint.png';
		//$response['cmp_logo']=(string)$imageurl.$CompanyInfo->getLogoImg();
		$response['cmp_address']=(string)$CompanyInfo->getAddress();
		$response['cmp_email']=(string)$CompanyInfo->getEmail1();
		$response['cmp_contact']=(string)$CompanyInfo->getContact1();

		$response['cmp_israngehour']='0';
		if(sizeof($CompanyInfo->getCmpRangeHour()) > 0)
		{
			$response['cmp_israngehour']='1';
			for($k=0;$k<sizeof($CompanyInfo->getCmpRangeHour());$k++)
			{
				$response['cmp_rangehour'][$k]['name']=$CompanyInfo->getCmpRangeHour()[$k]->getName();
			}	
		}

		$response['curr_datetime']=date('M d Y h:iA');


	}
	//Get Order History Details
	else if($action == 'listorderhistorydetail')
	{
		$orderid=$IISMethods->sanitize($_POST['orderid']);
		$orderdetid=$IISMethods->sanitize($_POST['orderdetid']);

		$isvalidate=0;
		
		/********************* Start For Attribute Details *********************/
		$attributedetail=new attributedetail();

		$qry = "select distinct tod.id,tod.webdisplayname as name,REPLACE(isnull(tod.attributename,''),'&amp;','&') as attributename,tod.displayorder,
			case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
			from tblorderitemdetail tod 
			left join tblitemiconmaster iim on iim.id=tod.iconid
			where tod.iswebsiteattribute=1 and tod.orderid=:orderid and tod.odid=:orderdetid order by tod.displayorder";
		$parms = array(
			':orderid'=>$orderid,
			':orderdetid'=>$orderdetid,
			':imageurl'=>$config->getImageurl(),
		);
		$attributedetail=$DB->getmenual($qry,$parms,'attributedetail');
		
		$response['isattributedetail']=0;
		if($attributedetail)
		{	
			$isvalidate=1;
			$response['isattributedetail']=1;
			$response['attributedetail']=$attributedetail;
			
			$status=1;
			$message=$errmsg['success'];
		}
		/********************* End For Attribute Details *********************/


		/********************* Start For Course Benefit Details *********************/
		$coursebenefit=new coursebenefit();

		$qry = "select distinct tod.id,REPLACE(tod.webdisplayname,'&amp;','&') as name,tod.durationname,tod.displayorder,
			case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
			from tblorderitemdetail tod 
			left join tblitemiconmaster iim on iim.id=tod.iconid
			where tod.iscourse=1 and tod.orderid=:orderid and tod.odid=:orderdetid order by tod.displayorder";
		$parms = array(
			':orderid'=>$orderid,
			':orderdetid'=>$orderdetid,
			':imageurl'=>$config->getImageurl(),
		);
		$coursebenefit=$DB->getmenual($qry,$parms,'coursebenefit');
		
		$response['iscoursebenefit']=0;
		if($coursebenefit)
		{	
			$isvalidate=1;

			$response['iscoursebenefit']=1;
			$response['coursebenefit']=$coursebenefit;
		}
		/********************* End For Course Benefit Details *********************/


		/********************* Start For Item Details *********************/
		$orderitemdetailinfo=new orderitemdetailinfo();

		$qry = "select distinct oid.id as oidid,oid.odid,oid.orderid,oid.catid as categoryid,oid.category,oid.subcatid as subcategoryid,REPLACE(oid.subcategory,'&amp;','&') as subcategory,oid.itemid,REPLACE(oid.itemname,'&amp;','&') as itemname,
			oid.qty,oid.usedqty,oid.remainqty,oid.durationid,oid.durationname,oid.durationdays,oid.discount,oid.price,
			tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,oid.type,oid.typestr as typename,o.timestamp 
			from tblorder o
			inner join tblorderdetail od on od.orderid = o.id 
			inner join tblorderitemdetail oid on oid.odid=od.id 
			inner join tbltaxtype tt on tt.id=oid.gsttypeid
			inner join tbltax tx on tx.id=oid.gstid 
			inner join tblitemstore si on si.itemid=oid.itemid 
			where o.status=1 and o.iscancel = 0 and isnull(oid.iswebsiteattribute,0)=0 and oid.orderid=:orderid and oid.odid=:orderdetid
			order by oid.type";
		$parms = array(
			':orderid'=>$orderid,
			':orderdetid'=>$orderdetid,
		);
		$orderitemdetailinfo=$DB->getmenual($qry,$parms,'orderitemdetailinfo');
		
		$response['isitemdetail']=0;
		if($orderitemdetailinfo)
		{	
			$isvalidate=1;

			$response['isitemdetail']=1;
			$response['itemdetail']=$orderitemdetailinfo;
		}
		/********************* End For Item Details *********************/
		

		if($isvalidate == 1)
		{
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}

	}
	//Cancel Membership Order Data
	else if($action == 'cancelmshiporder')
	{
		$orderid=$IISMethods->sanitize($_POST['orderid']);
		$datetime=$IISMethods->getdatetime();

		if($orderid)
		{
			try 
			{ 
				$DB->begintransaction();

				$updqry=array(					
					'iscancel'=>1,
					'cancel_uid'=>$uid,	
					'cancel_date'=>$datetime,					
				);

				$extraparams=array(
					'[id]'=>$orderid
				);
				$DB->executedata('u','tblorder',$updqry,$extraparams);

				$status = 1;
				$message=$errmsg['moordercancel'];
				
				$DB->committransaction();
			}
			catch (Exception $e) 
			{
				$DB->rollbacktransaction($e);
				$status=0;
				$message=$errmsg['dbtransactionerror'];
			}	
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

  