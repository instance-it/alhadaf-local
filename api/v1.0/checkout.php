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
	$imgpath=$config->getImageurl();

	//Insert Order Data
	if ($action=='insertorderdata')
	{
		if($platform==2 || $platform==3)  //From App
		{	
			$LoginInfo=$IISMethods->convertcartjsontoobjarr($_POST['cartitemdata']);

			$qrychkusr = "select pm.id,pm.personname,
			ISNULL(SUBSTRING((select TOP 1 ','+CONVERT(VARCHAR(255), pu.utypeid) AS [text()] FROM tblpersonutype pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,1000),'') as usertypeid
			from tblpersonmaster pm
			INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
			INNER JOIN tblusertypemaster tu ON tu.id=pu.utypeid
			where pm.id=:id and isnull(pm.isdelete,0) = 0 
			GROUP BY pm.id,pm.personname";
			$personparams=array(
				':id'=>$uid
			);
			$reschkusr = $DB->getmenual($qrychkusr,$personparams); 

			$utypeid=$reschkusr[0]['usertypeid'];
			$fullname=$reschkusr[0]['personname'];
			$isguestuser=0;
			$seesionarray=json_encode($LoginInfo,true);
		}	
		else  //From Websites
		{
			$LoginInfo = $_SESSION[$config->getSessionName()];

			$seesionarray=json_encode($LoginInfo,true);
			
			// LOGIN DETAILS
			$uid = $LoginInfo->getUid();
			$utypeid = $LoginInfo->getUtypeid();
			$fullname = $LoginInfo->getFullname();
			$isguestuser = $LoginInfo->getIsguestuser();
		}

		
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
						':memberid'=>$uid,
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

						if($isbinditem == 1  || $config->getIsAccessSAP() == 0)   //When Item Bind With SAP
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
							$insqry['[uid]']=$uid;
							
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
							$insqry['[ordernotes]']='';

							$insqry['[orderdate]']=$order_date;
							
							$insqry['[sessionarray]']=$seesionarray;
							$insqry['[platform]']=$platform;
							$insqry['[paymenttype]']=1;
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
								':amemberid'=>$uid,
								':imemberid'=>$uid,
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
								'[uid]'=>$uid,
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
							//$genpdf = file_get_contents($config->getalhadafpdfurl().'mshipinvoice.php?id='.$order_unqid.'&type=generate&isshow=0');


							//Send User New Order (Membership/Package/Course) Email
							$DB->userneworderemailssms(1,$order_unqid);   //type 1-Email,2-SMS

							//Send User New Order (Membership/Package/Course) SMS
							//$DB->userneworderemailssms(2,$order_unqid);   //type 1-Email,2-SMS

							
							$response['transactionid']=$transactionid;
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
	else if($action == 'listtransactionhistory')
	{
		$txnid=$IISMethods->sanitize($_POST['txnid']);
		$qry = "select id,convert(varchar,timestamp,103) as orderdate,isnull(totalpaid,0) as totalpaid from tblorder where transactionid = :txnid";
		$params=array(
			':txnid'=>$txnid,
		);
		$res = $DB->getmenual($qry,$params);
		if(sizeof($res) > 0)
		{
			$row = $res[0];
			$orderid = $row['id']; 


			$html = '';
			$html.='<div class="row">
						<div class="col-12 text-center">
							<div class="transaction-img"><img src="'.$config->getImageurl().'images/transaction-complate.png"/></div>
							<h3 class="mb-2 text-success">Transaction Completed Successfully</h3>
							<p><strong>Thank you.</strong> for Billing.</p>
						</div>
					</div>';

			$html.='<div class="row your-transaction-block text-sm-center">
						<div class="col-12 col-sm-4 col-lg-4 mb-3">
							<h4 class="m-0 mb-2">Transaction ID</h4>
							<p>'.$IISMethods->sanitize($txnid).'</p>
						</div>
						<div class="col-12 col-sm-4 col-lg-4 mb-3">
							<h4 class="m-0 mb-2">Transaction Date</h4>
							<p>'.$IISMethods->sanitize($row['orderdate']).'</p>
						</div>
						<div class="col-12 col-sm-4 col-lg-4 mb-3">
							<h4 class="m-0 mb-2">Total Amount</h4>
							<p>Qr '.$IISMethods->ind_format($row['totalpaid']).'</p>
						</div>
        			</div>';

			$subqry = "select id,type,itemname,durationname,price,finalprice,n_expirydate,expirydate,strvalidityduration from tblorderdetail where orderid = :orderid";
			$subparams=array(
				':orderid'=>$orderid,
			);
			$subres = $DB->getmenual($subqry,$subparams);
			if(sizeof($subres) > 0)
			{
				$html.='<div class="row mb-3 px-3">';
					$html.='<div class="col-12 table-body-content">';

					$html.='<div class="row d-none d-md-flex plan-table-header">
								<div class="col-12 col-md-4">
									<h4>Membership</h4>
								</div>
								<div class="col-12 col-md-2">
									<h4>Validity</h4>
								</div>
								<div class="col-12 col-md-3">
									<h4>Price</h4>
								</div>
								<div class="col-12 col-md-3 text-md-right">
									<h4>Amount</h4>
								</div>
							</div>';

					for($j=0;$j<sizeof($subres);$j++)
					{
						$subrow = $subres[$j];
						$type = $IISMethods->sanitize($subrow['type']);

						$strtype='';
						if($type == 1)
						{
							$strtype='Membership';
						}
						else if($type == 2)
						{
							$strtype='Package';
						}
						else if($type == 3)
						{
							$strtype='Course';
						}

						$html.='<div class="row membership-row">
									<div class="col-12">
										<div class="row">
											<div class="col-12 col-md-4 membership-detail">
												<h4 class="mob-plan-header d-md-none">Membership</h4>
												<div class="membership-plan-table-name">
													'.$IISMethods->sanitize($subrow['itemname']).'
												</div>
											</div>
											<div class="col-12-xs col-6 col-sm-4 col-md-2 membership-validity-detail">
												<h4 class="mob-plan-header d-md-none">Validity</h4>
												<div class="membership-plan-table-validity">
													'.$IISMethods->sanitize($subrow['strvalidityduration']).'
												</div>
											</div>
											<div class="col-12-xs col-6 col-sm-4 col-md-3 membership-validity-detail">
												<h4 class="mob-plan-header d-md-none mt-2">Transaction Date</h4>
												<div class="membership-plan-table-validity">
												Qr '.$IISMethods->ind_format($subrow['price'],2).'
												</div>
											</div>
											<div class="col-12-xs col-6 col-sm-4 col-md-3 membership-price-detail">
												<h4 class="mob-plan-header d-md-none mt-2">Amount</h4>
												<div class="membership-plan-table-price text-md-right">
													Qr '.$IISMethods->ind_format($subrow['finalprice'],2).'
												</div>
											</div>
										</div>                                
									</div>
									<div class="col-12">
										<div class="row">
											<div class="col-12 col-md mt-2 my-lg-auto">
												<p class="text-success m-0">Your '.$strtype.' Expires On <strong> '.$subrow['n_expirydate'].'</strong>.</p>
											</div> 
										</div>  
									</div>
								</div>';
					}		
					
					

					$html.='</div>';
				$html.='</div>';       
			}
			$html.='<div class="row">
						<div class="col-12 text-center about-transaction-content">
							<p class="m-0">We will contact you shortly. <a href="home">Alhadaf Shooting Range</a></p>
						</div>
					</div>';


			$response['transactiondata'] = $html;
			$status=1;
			$message=$errmsg['success'];
		}


	}
	


	
	
}



require_once dirname(__DIR__, 2).'\config\apifoot.php';  

?>

  