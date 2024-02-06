<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\config\CartItemInfo.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();


	//-------------------- Apply Coupon ----------------------
	if($action=='applycoupon')
	{
		$couponcode=$IISMethods->sanitize($_POST['couponcode']);   
		$cartitemdata = $_POST['cartitemdata'];

		$currdate=$IISMethods->getformatcurrdate();   //Y-m-d

		if($platform == 2 || $platform == 3)  //Android, iOS
		{
			$removecoupon=$IISMethods->sanitize($_POST['removecoupon']);    //1-Remove Applied Coupon Code
			$LoginInfo=$IISMethods->convertcartjsontoobjarr($cartitemdata);
			//  print_r(json_encode($LoginInfo));
		}
		else
		{
			$removecoupon=0;
			$LoginInfo = $_SESSION[$config->getSessionName()];
		}

		$Sess_CartItemInfo=$LoginInfo->getCartItemInfo();

	

		if($removecoupon==1)
		{
			$LoginInfo=$IISMethods->removecoupon($LoginInfo,$platform); 
			if($platform==2 || $platform==3)
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
					':memberid'=>$uid,
				);	
				// echo $qrycode;
				// print_r($couponparams);
				$rescode=$DB->getmenual($qrycode,$couponparams);
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
	else if($action=='couponscoderemove')
	{
		$config = new config();
		$LoginInfo = $_SESSION[$config->getSessionName()];
		$IISMethods->removecoupon($LoginInfo,$platform); 
		$status = 1;
		$message = $errmsg['couponremoved'];
	}
	
}



require_once dirname(__DIR__, 2).'\config\apifoot.php';  

?>

  