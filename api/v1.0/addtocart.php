<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\config\CartItemInfo.php';

session_start();

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();

	//Add To Cart
	if($action == 'addtocartitem')
	{
		$type = $IISMethods->sanitize($_POST['type']);   //1-Membership, 2-Packages, 3-Course
		$itemid = $IISMethods->sanitize($_POST['itemid']);
		$isbuynow = $IISMethods->sanitize($_POST['isbuynow']);

		$response['type']=$type;
		$response['itemid']=$itemid;
		$response['isbuynow']=$isbuynow;

		$strtype='';
		if($type == 1)
		{
			$strtype='Membership';
			$catmembershipid = $config->getDefaultCatMembershipId();
		}
		else if($type == 2)
		{
			$strtype='Package';
			$catmembershipid = $config->getDefaultCatPackageId();
		}
		else if($type == 3)
		{
			$strtype='Course';
			$catmembershipid = $config->getDefaultCatCourseId();
		}
		
		if($type && $itemid)
		{
			$LoginInfo=$_SESSION[$config->getSessionName()];
			$Sess_CartItemInfo=$LoginInfo->getCartItemInfo();
			$itemarryindex=array_search($itemid, array_column($Sess_CartItemInfo,'id'));
		
			//When Same Item Exist
			if($itemarryindex !== false)
			{
				$status=1;
				$message=str_replace("#item#",$strtype,$errmsg['itemcartsession']);
			}
			else
			{	
				$qryitem="select im.id,im.itemname,im.price,tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,dm.day as duration,dm.daytext as durationname,dm.strday as strvalidityduration, 
					case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg,
					ISNULL((select top 1 CONCAT(:baseimgurl,image) from tblitemimage where itemid = im.id order by displayorder),:defaultmembershipimgurl) as image  
					from tblitemmaster im 
					inner join tbltaxtype tt on tt.id=im.gsttypeid
					inner join tbltax tx on tx.id=im.gstid 
					inner join tbldurationmaster dm on dm.id=im.durationid
					left join tblitemiconmaster iim on iim.id=im.iconid
					where im.isactive=1 and im.categoryid=:catmembershipid and im.id=:itemid ";
				$itemparms = array(
					':itemid'=>$itemid,
					':imageurl'=>$imageurl,
					// ':catmembershipid'=>$config->getDefaultCatMembershipId(), //old
					':catmembershipid'=>$catmembershipid,
					':baseimgurl'=>$imageurl,
					':defaultmembershipimgurl'=>$config->getDefualtMemberShipImageurl(),
					
				);
		
				$resitem=$DB->getmenual($qryitem,$itemparms);	
				
				if(sizeof($resitem) > 0)
				{
					$rowitem=$resitem[0];
					
					$LoginInfo=$_SESSION[$config->getSessionName()];
					$Sess_CartItemInfo=$LoginInfo->getCartItemInfo();
					
					
					$singleiteminfo=array();

					for($i=0; $i<sizeof($Sess_CartItemInfo); $i++)
					{
						$CartItemInfo = new CartItemInfo();

						$CartItemInfo->setId($Sess_CartItemInfo[$i]->getId());
						$CartItemInfo->setItemname($Sess_CartItemInfo[$i]->getItemname());
						$CartItemInfo->setPrice($Sess_CartItemInfo[$i]->getPrice());

						$CartItemInfo->setTaxtype($Sess_CartItemInfo[$i]->getTaxtype());
						$CartItemInfo->setTaxtypename($Sess_CartItemInfo[$i]->getTaxtypename());
						$CartItemInfo->setSgst($Sess_CartItemInfo[$i]->getSgst());
						$CartItemInfo->setCgst($Sess_CartItemInfo[$i]->getCgst());
						$CartItemInfo->setIgst($Sess_CartItemInfo[$i]->getIgst());
						$CartItemInfo->setTaxable($Sess_CartItemInfo[$i]->getTaxable());
						$CartItemInfo->setIgstTaxAmt($Sess_CartItemInfo[$i]->getIgstTaxAmt());
						$CartItemInfo->setSgstTaxAmt($Sess_CartItemInfo[$i]->getSgstTaxAmt());
						$CartItemInfo->setCgstTaxAmt($Sess_CartItemInfo[$i]->getCgstTaxAmt());
						$CartItemInfo->setFinalprice($Sess_CartItemInfo[$i]->getFinalprice());
						$CartItemInfo->setDuration($Sess_CartItemInfo[$i]->getDuration());
						$CartItemInfo->setDurationname($Sess_CartItemInfo[$i]->getDurationname());
						$CartItemInfo->setStrValidityDuration($Sess_CartItemInfo[$i]->getStrValidityDuration());

						$CartItemInfo->setType($Sess_CartItemInfo[$i]->getType());
						$CartItemInfo->setImage($Sess_CartItemInfo[$i]->getImage());
						$CartItemInfo->setIconImg($Sess_CartItemInfo[$i]->getIconImg());

						$singleiteminfo[$i]=$CartItemInfo;
					}

					$CartItemInfo = new CartItemInfo();


					$taxableamt=0;
					$taxamt=0;
					$finalprice=0;
					if($rowitem['taxtype'] == 1)  //For Exclusive Tax
					{
						$taxableamt=$rowitem['price'];
						$taxamt=round(($rowitem['price']*$rowitem['igst']/100),3);
						$finalprice=$taxableamt+$taxamt;
					}
					else if($rowitem['taxtype'] == 2)  //For Inclusive Tax
					{
						$taxableamt=round((($rowitem['price']*100)/(100+$rowitem['igst'])),3);
						$taxamt=round(($rowitem['price']-$taxableamt),3);
						$finalprice=$rowitem['price'];
					}

					$CartItemInfo->setId($rowitem['id']);
					$CartItemInfo->setItemname($rowitem['itemname']);
					$CartItemInfo->setPrice($rowitem['price']);

					$CartItemInfo->setTaxtype($rowitem['taxtype']);
					$CartItemInfo->setTaxtypename($rowitem['taxtypename']);
					$CartItemInfo->setSgst($rowitem['sgst']);
					$CartItemInfo->setCgst($rowitem['cgst']);
					$CartItemInfo->setIgst($rowitem['igst']);
					$CartItemInfo->setTaxable($taxableamt);
					$CartItemInfo->setIgstTaxAmt($taxamt);
					$CartItemInfo->setSgstTaxAmt($taxamt/2);
					$CartItemInfo->setCgstTaxAmt($taxamt/2);
					$CartItemInfo->setFinalprice($finalprice);
					$CartItemInfo->setDuration($rowitem['duration']);
					$CartItemInfo->setDurationname($rowitem['durationname']);
					$CartItemInfo->setStrValidityDuration($rowitem['strvalidityduration']);

					$CartItemInfo->setType($type);
					$CartItemInfo->setImage('');
					$CartItemInfo->setIconImg('');

					$singleiteminfo[$i]=$CartItemInfo;
		

					$LoginInfo->setCartItemInfo($singleiteminfo);

					$_SESSION[$config->getSessionName()]=$LoginInfo;

					if($platform == 4)
					{
						$IISMethods->removecoupon($LoginInfo,$platform);
					}
					

					$status=1;
					$message=str_replace("#item#",$strtype,$errmsg['itemcartsession']);
				}
				else
				{
					$status=0;
					$message=$errmsg['invalidrequest'];
				}
			}	
		}
		else
		{
			$status=0;
			$message=$errmsg['invalidrequest'];
		}

	}
	else if($action == 'listcartitems')
	{
		$LoginInfo=$_SESSION[$config->getSessionName()];
		$Sess_CartItemInfo=$LoginInfo->getCartItemInfo();

		// echo '<pre>';
		// print_r($LoginInfo);
		// echo '</pre>';

		$totalprice = 0;
		$totaltaxamout = 0;
		$totalfinalprice = 0;
		$totaltaxableamout = 0;
		$html='';
		//Cart Item List
		if(sizeof($Sess_CartItemInfo) > 0)
		{
			$html.='<div class="col-12 col-lg-8 mb-4 mb-lg-0">';
				$html.='<div class="row cart-tbl">';

							$html.='<div class="col-md-12 heading-order-profile d-none d-md-block">
										<div class="row mx-n1">
											<div class="col-md-4 px-1">
												<h5 class="font-weight-bold">Membership Details</h5>
											</div>
											<div class="col-md-2 px-1">
												<h5 class="font-weight-bold">Validity</h5>
											</div>
											<div class="col-md-3 px-1">
												<h5 class="font-weight-bold">Amount</h5>
											</div>
											<div class="col-md-3 px-1">
												<h5 class="font-weight-bold">Payable Amount</h5>
											</div>
										</div>
									</div>';

							
								for($i=0; $i<sizeof($Sess_CartItemInfo); $i++)
								{
									$html.='<div class="col-md-12 list-order-profile py-3">
												<div class="row mx-n1">
													<div class="col-md-4 col-sm-10 col-8 px-1 my-auto">
														<h5><span class="d-md-none d-block font-weight-bold">Membership Details: </span>'.$Sess_CartItemInfo[$i]->getItemname().'</h5>
													</div>
													<div class="col-md-2 col-4 px-1 my-auto">
														<h5><span class="d-md-none d-block font-weight-bold">Validity: </span>'.$Sess_CartItemInfo[$i]->getStrValidityDuration().'</h5>
													</div>
													<div class="col-md-3 col-4 px-1 my-auto">
														<h5><span class="d-md-none d-block font-weight-bold">Amount: </span>Qr '.$IISMethods->ind_format($Sess_CartItemInfo[$i]->getPrice(),2).'</h5>
													</div>
													<div class="col-md-3 col-4 px-1 my-auto">
														<h5><span class="d-md-none d-block font-weight-bold">Payable Amount: </span>Qr '.$IISMethods->ind_format($Sess_CartItemInfo[$i]->getFinalprice(),2).'</h5>
													</div>
													<div class="col-md-1 col-6 my-auto list-order-close">
														<h5><a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="Remove Item" class="removecartitem" data-itemid="'.$Sess_CartItemInfo[$i]->getId().'"><span class="close fal fa-times"></span></a></h5>
													</div>
												</div>
											</div>';
									$totalprice+=$Sess_CartItemInfo[$i]->getPrice();
									$totaltaxamout+=$Sess_CartItemInfo[$i]->getIgstTaxAmt();
									$totalfinalprice+=$Sess_CartItemInfo[$i]->getFinalprice();
									$totaltaxableamout+=$Sess_CartItemInfo[$i]->getTaxable();
								}	
					
							
							
				$html.='</div>';
			$html.='</div>';

			//Cart Summary
			$html.='<div class="col-12 col-lg-4">
				<div class="row content-sticky">
					<div class="col-12 ml-auto col-md-6 col-lg-12">
						<div class="cart-totals">
							<div class="heading-order-profile">
								<h5 class="mb-0">Cart Totals</h5>
							</div>
							<div class="cart-totals-inner">';

							if($LoginInfo->getcouponapply() == 0)
							{
								$html.='<div class="cart-coupon couponscode-content-apply">
									<h4 class="m-0"><a class="text-left d-flex m-0" href="#collapsecoupon" data-toggle="collapse">Coupon <i class="fal fa-gift ml-auto"></i></a></h4>
									<div class="p-0 collapse" id="collapsecoupon">
										<div class="cart-coupon-body">
											<div class="row">
												<div class="col-12 pt-2">
													<p class="mb-2">Enter your coupon code if you have one.</p>
												</div>
												<div class="col-12">
													<div class="form-group validate-input">
														<input class="form-control" type="text" name="couponcode" id="couponcode">
														<span class="focus-form-control"></span>
														<label class="label-form-control">Coupon Code</label>
													</div>
													<a href="javascript:void(0);" id="btnapplycoupon" class="btn btn-brand-05 btn-sm btn-cc-apply">Apply</a>
												</div>
											</div>
										</div>
									</div>
								</div>';
							}
								
							$html.='<div class="cart-subtotal">
									<ul>
										<li>
											<p>Subtotal</p>
											<p class="cart-amount">Qr '.$IISMethods->ind_format($totalprice,2).'</p>
										</li>
										<li>
											<p>Taxable Amount</p>
											<p class="cart-amount">Qr '.$IISMethods->ind_format($totaltaxableamout,2).'</p>
										</li>
										<li>
											<p>Tax Amount</p>
											<p class="cart-amount">Qr '.$IISMethods->ind_format($totaltaxamout,2).'</p>
										</li>';

										if($LoginInfo->getcouponapply() == 1)
										{
											$html.='<li class="couponscode-content">
														<div class="couponscode-block">    
															<i class="couponscode-icon far fa-check"></i>
															<div class="couponscode-code"><b>'.$LoginInfo->getcouponcode().'</b> applied</div>
															<span class="couponscode-amount">- Qr '.$IISMethods->ind_format($LoginInfo->getcouponamount(),2); 
																if($LoginInfo->getcoupontype() == 2)
																{
																	$html.=' ('.$LoginInfo->getcouponpercent().'% off)';
																}
													$html.='</span>
														</div>
														<a class="couponscode-remove" href="javascript:void(0);" data-toggle="tooltip" title="Remove" ><i class="icon-trash-empty"></i></a>
													</li>';
										}

								


								$html.='<li class="cart-total-price">
											<p>Total</p>
											<p class="cart-amount">Qr '.$IISMethods->ind_format($totalfinalprice,2).'</p>
										</li>
									</ul>
								</div>
								<div class="checkout-btn">
									<a class="btn btn-brand-01 w-100" href="javascript:void(0)" id="btncheckout">Proceed to Checkout</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>';
		}
		else
		{
			$html.='<div class="col-12 col-lg-12 mb-4 mb-lg-0">';
				$html.='<div class="product-main text-left">';
					$html.='<div class="row">';
						$html.='<div class="col-12 col-xl-12 col-md-12 mb-4">';
							$html.='<div class="cart-border">';
								$html.='<div class="col-12 text-center py-3">';
									$html.='<img src="'.$imageurl.'img/empty-cart.png" class="d-block mx-auto mb-3" width="300">';
									$html.='<h3 class="mb-3">Your Cart is Empty</h3>';
									// $html.='<a class="checkout-button btn btn-theme-dark m-0" href="javascript:void(0)">Continue Shopping</a>';
								$html.='</div>';
							$html.='</div>';
						$html.='</div>';
					$html.='</div>';
				$html.='</div>';
			$html.='</div>';

		}
		
		
		$response['cartdata'] = $html;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action == 'removecartitem')
	{
		$itemid = $IISMethods->sanitize($_POST['itemid']);
		$LoginInfo=$_SESSION[$config->getSessionName()];
		$Sess_CartItemInfo=$LoginInfo->getCartItemInfo();

		// echo '<pre>';
		// print_r($LoginInfo);
		// echo '</pre>';

		$itemarryindex=array_search($itemid, array_column($Sess_CartItemInfo,'id'));

		if(gettype($itemarryindex) == 'integer')
		{
			array_splice($Sess_CartItemInfo,$itemarryindex,1);
		}
	

		$LoginInfo->setCartItemInfo($Sess_CartItemInfo);

		if($platform == 4)
		{
			$IISMethods->removecoupon($LoginInfo,$platform);
		}

		$_SESSION[$config->getSessionName()]=$LoginInfo;

		$status=1;
		$message=$errmsg['success'];

	}
	else if($action == 'countcartitem')
	{
		$LoginInfo=$_SESSION[$config->getSessionName()];
		$Sess_CartItemInfo=$LoginInfo->getCartItemInfo();

		$response['totalcartdata'] = sizeof($Sess_CartItemInfo);

		$status=1;
		$message=$errmsg['success'];
	}

}



require_once dirname(__DIR__, 2).'\config\apifoot.php';  

?>

  