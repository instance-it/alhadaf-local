<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\ordermaster.php';

// echo $isvalidUser['status'];
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	$currdate=$IISMethods->getformatcurrdate();
	
	
	if($action=='insertordermaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$memberid=$IISMethods->sanitize($_POST['memberid']);

		$couponapply=$IISMethods->sanitize($_POST['couponapply']);
		$couponid=$IISMethods->sanitize($_POST['couponid']);
		$couponcode=$IISMethods->sanitize($_POST['couponcode']);
		$coupontype=$IISMethods->sanitize($_POST['coupontype']);
		$couponamount=$IISMethods->sanitize($_POST['couponamount']);
		$couponpercent=$IISMethods->sanitize($_POST['couponpercent']);

		$paymenttype = $IISMethods->sanitize($_POST['paymenttype']);	
		$referenceno = $IISMethods->sanitize($_POST['referenceno']);

		$totalamount = $IISMethods->sanitize($_POST['totalamount']);
		$totaltaxable = $IISMethods->sanitize($_POST['totaltaxable']);
		$totaltax = $IISMethods->sanitize($_POST['totaltax']);
		$totalcouponamount = $IISMethods->sanitize($_POST['totalcouponamount']);
		$totalpaidamount = $IISMethods->sanitize($_POST['totalpaidamount']);

		$ordernote = $IISMethods->sanitize($_POST['ordernote']);

		//Multiple Data
		$tblitemid=$_POST['tblitemid'];
		$tblitemname=$_POST['tblitemname'];

		$tblduraton=$_POST['tblduraton'];
		$tbldurationname=$_POST['tbldurationname'];
		$tblstrvalidityduration=$_POST['tblstrvalidityduration'];

		$tblprice=$_POST['tblprice'];
		$tblfinalprice=$_POST['tblfinalprice'];

		$tbltaxtype=$_POST['tbltaxtype'];
		$tbltaxtypename=$_POST['tbltaxtypename'];
		$tblsgst=$_POST['tblsgst'];
		$tblcgst=$_POST['tblcgst'];
		$tbligst=$_POST['tbligst'];
		$tbltaxable=$_POST['tbltaxable'];
		$tbligsttaxamt=$_POST['tbligsttaxamt'];
		$tblsgsttaxamt=$_POST['tblsgsttaxamt'];
		$tblcgsttaxamt=$_POST['tblcgsttaxamt'];
		$tbltype=$_POST['tbltype'];
		$tblimage=$_POST['tblimage'];
		$tbliconimg=$_POST['tbliconimg'];
		$tblcouponamount=$_POST['tblcouponamount'];

		$id=$IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();

		//print_r($tblitemid);
		//exit;
		if($memberid && sizeof($tblitemid) > 0 )
		{		
			if($formevent=='addright')
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

						if($rowmemb['sappersonid'] != ''  || $config->getIsAccessSAP() == 0)   //When Member Bind With SAP
						{
							//Check Item Bind With SAP
							if(sizeof($tblitemid)>0)
							{
								$isbinditem=1;
								for($i=0;$i<sizeof($tblitemid);$i++)
								{
									$itemid=$tblitemid[$i];


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
			
								$order_unqid = $IISMethods->generateuuid();
								$order_date = $IISMethods->getcurrdate();
								$transactionid=uniqid('ASR');

								
								$ord_seriesno=$DB->getorderno($ord_seriesid,'order',$order_date);	
								$ord_maxid=$DB->getmaxid($ord_seriesid,'order');
								$ord_prefix=$DB->getseriseprefix($ord_seriesid);



								$insqry['[id]']=$order_unqid;
								$insqry['[transactionid]']=$transactionid;
								$insqry['[orderno]']=$ord_seriesno;
								$insqry['[uid]']=$memberid;


								$insqry['[totalamount]']=0;
								$insqry['[couponapply]']=$couponapply;
								$insqry['[couponid]']=$couponid;
								$insqry['[couponcode]']=$couponcode;
								$insqry['[coupontype]']=$coupontype;
								$insqry['[couponamount]']=$couponamount;
								$insqry['[couponpercent]']=$couponpercent;
								$insqry['[totaltaxableamt]']=0;
								$insqry['[totaltax]']=0;
								$insqry['[totalpaid]']=0;
								$insqry['[ordernotes]']=$ordernote;

								$insqry['[orderdate]']=$order_date;
								
								$insqry['[sessionarray]']='';
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
								if(sizeof($tblitemid)>0)
								{
									for($i=0;$i<sizeof($tblitemid);$i++)
									{
										$od_id = $IISMethods->generateuuid();
										
										$type=$tbltype[$i];
										$itemid=$tblitemid[$i];
										$itemname=$tblitemname[$i];

										$durationday=0;
										if($tblduraton[$i] > 0)
										{
											$durationday=$tblduraton[$i];
										}
										$durationname=$tbldurationname[$i];
										$strvalidityduration=$tblstrvalidityduration[$i];

										$taxtype=$tbltaxtype[$i];
										$taxtypename=$tbltaxtypename[$i];
										$sgst=$tblsgst[$i];
										$cgst=$tblcgst[$i];
										$igst=$tbligst[$i];
										$price=$tblprice[$i];
										$tblcouponamount=$tblcouponamount[$i];
										$taxable=$tbltaxable[$i];
										$igsttaxamt=$tbligsttaxamt[$i];
										$sgsttaxamt=$tblsgsttaxamt[$i];
										$cgsttaxamt=$tblcgsttaxamt[$i];
										$finalprice=$tblfinalprice[$i];


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

								
								if($config->getIsAccessSAP() == 1)
								{
									//Insert Package Data in SAP AR Invoice (HaNa DB)
									$DB->SAPInsertARInvoiceData($SubDB,$order_unqid);
								}

								
								//Generate Order Invoice Number
								$DB->generateorderinvoicenumber($order_unqid,$uid,$platform);

								//Generate Order Invoice
								//$genpdf = file_get_contents($config->getalhadafpdfurl().'mshipinvoice.php?id='.$order_unqid.'&type=generate&isshow=1');


								//Send User New Order (Membership/Package/Course) Email
								$DB->userneworderemailssms(1,$order_unqid);   //type 1-Email,2-SMS

								//Send User New Order (Membership/Package/Course) SMS
								//$DB->userneworderemailssms(2,$order_unqid);   //type 1-Email,2-SMS

							
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
		else
		{
			$message=$errmsg['reqired'];
		}
		
	}
	else if($action=='listordermaster')   
	{

		$ordermasters=new ordermaster();

		
		$qry="select o.timestamp as primary_date,o.id,o.transactionid,o.orderno,isnull(o.saporderid,'') as saporderid,isnull(o.sapdocnum,'') as sapdocnum,isnull(o.iscancel,0) as iscancel,pm.personname,pm.contact,
		convert(varchar, o.timestamp,100) AS ofulldate,o.totalpaid,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,
		isnull((select top 1 pdfurl from tblorderinvoice where orderid=o.id),'') as inv_pdfurl
		from tblorder o 
		inner join tblpersonmaster pm on pm.id=o.uid 
		inner join tblpersonmaster pm1 on pm1.id=o.entry_uid 
		where (o.transactionid like :transactionidfilter OR o.orderno like :ordernofilter OR pm.personname like :personnamefilter OR pm.contact like :contactfilter 
		OR convert(varchar, o.timestamp,100) like :orderdatefilter OR o.totalpaid like :totalpaidfilter OR pm1.personname like :entrypersonfilter OR pm1.contact like :entrypersoncontactfilter 
		OR isnull(o.saporderid,'') like :entrynumfilter OR isnull(o.sapdocnum,'') like :docnumfilter)  ";
		$parms = array(
			':transactionidfilter'=>$filter,
			':ordernofilter'=>$filter,
			':personnamefilter'=>$filter,
			':contactfilter'=>$filter,
			':orderdatefilter'=>$filter,
			':totalpaidfilter'=>$filter,
			':entrypersonfilter'=>$filter,
			':entrypersoncontactfilter'=>$filter,
			':entrynumfilter'=>$filter,
			':docnumfilter'=>$filter,
		);


		if($IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==0)
		{
			$qry.=" and o.uid = :id";
			$parms[':id'] = $LoginInfo->getUid();
		}

		$qry.=" order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$ordermasters=$DB->getmenual($qry,$parms,'ordermaster');
		
		if($responsetype=='HTML')
		{
			if($ordermasters)
			{
				$i=0;
				foreach($ordermasters as $ordermaster)
				{
					$id="'".$ordermaster->id."'";


					//$sapDocNum='';
					$saporderid=$ordermaster->saporderid;
					$sapDocNum=$ordermaster->sapdocnum;
					/*
					if($config->getIsAccessSAP() == 1 && $saporderid != '')
					{
						$SubDBName=$SubDB->getDBName();
						$qrysap = "SELECT \"DocNum\" from ".$SubDBName.".OINV where \"DocEntry\"='$saporderid'";
						$ressap=$SubDB->getmenual($qrysap);
						$rowsap=$ressap[0];

						$sapDocNum=$rowsap[DocNum];
					}
					*/


					$htmldata.='<tr data-index="'.$i.'">';

					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || (sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-grid">';

						if($ordermaster->iscancel == 0)
						{
							$htmldata.='<div class="dropdown table-dropdown">';
							$htmldata.='<button class="dropdown-toggle btn-tbl rounded-circle" type="button" id="tableDropdown3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>';
							$htmldata.='<div class="dropdown-menu" aria-labelledby="tableDropdown3">';

							if(((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright  - only self person data show
							{
								$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="deletedata('.$IISMethods->sanitize($id).')"><i class="bi bi-trash"></i> Cancel</a>';
							}

							$htmldata.='</div>';
							$htmldata.='</div>';
						}
						$htmldata.='</td>';
					}

					
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->transactionid).'</td>';
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Invoice Detail" onclick="vieworderdata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($ordermaster->orderno).'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->ofulldate).'</td>';
					$htmldata.='<td class="tbl-name">Qr '.$IISMethods->sanitize($IISMethods->ind_format($ordermaster->totalpaid,2)).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->personname).' ('.$IISMethods->sanitize($ordermaster->contact).')</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($ordermaster->entrypersonname).' ('.$IISMethods->sanitize($ordermaster->entrypersoncontact).')</td>';
					//$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="Sync Invoice" onclick="syncsaporderdata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($sapDocNum).'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($saporderid).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($sapDocNum).'</td>';
					
					if($ordermaster->iscancel == 1)
					{
						$htmldata.='<td class="tbl-name text-center"><span class="btn btn-danger px-2 py-0">Cancelled</span></td>';
					}
					else
					{
						$htmldata.='<td class="tbl-name text-center"><span class="btn btn-success px-2 py-0">Confirmed</span></td>';
					}

					$htmldata.='<td class="tbl-name" align="center">';
					if($ordermaster->iscancel == 0)
					{
						if($ordermaster->inv_pdfurl)
						{
							$htmldata.='<a href="'.$imageurl.$ordermaster->inv_pdfurl.'" class="btn-tbl text-primary m-0 rounded-circle" data-toggle="tooltip" data-placement="top" data-original-title="View Invoice" target="_blank"><i class="bi bi-file-pdf-fill"></i></a>';
						}
						//$htmldata.='<a href="javascript:void(0);" class="btn-tbl text-primary m-0 rounded-circle" data-toggle="tooltip" onclick="regenerateorderinvoice('.$IISMethods->sanitize($id).')" data-placement="top" data-original-title="Regenerate Order Invoice" ><i class="bi bi-receipt"></i></a>';
					}
					
					$htmldata.='</td>';

					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($ordermasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($ordermasters);
		}

		$common_listdata=$ordermasters;
	}  
	else if($action=='deleteordermaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();
		
		if($id)
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
					'[id]'=>$id
				);
				$DB->executedata('u','tblorder',$updqry,$extraparams);

				
				$status=1;
				$message=$errmsg['ordercancel'];

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
	else if($action=='vieworderdata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		
		$mainqry = "select o.timestamp as primary_date,o.id,o.transactionid,o.orderno,pm.personname,pm.contact,isnull(o.couponcode,'-') as couponcode,isnull(o.couponamount,'0') as couponamount,
		convert(varchar, o.timestamp,100) AS ofulldate,o.totalpaid,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact
		from tblorder o 
		inner join tblorderdetail od on od.orderid=o.id 
		inner join tblpersonmaster pm on pm.id=o.uid 
		inner join tblpersonmaster pm1 on pm1.id=o.entry_uid
		where o.id = :id";
		$parms = array(
			':id'=>$id,
		);
		$vesseldetails=$DB->getmenual($mainqry,$parms);
		$row=$vesseldetails[0];

		if($responsetype=='HTML')
		{
			$htmldata="";

			$htmldata.='<div class="col-12">';
				$htmldata.='<div class="table-responsive">';
				$htmldata.='<div class="col-12 view-data-details">';
				$htmldata.='<div class="row my-3">';
				$htmldata.='<div class="col-12 col-md-8 col-lg">';
				$htmldata.='<div class="row">';
			
			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Transaction Id <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['transactionid'].'</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Invoice No <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['orderno'].'</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Invoice Date <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['ofulldate'].'</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Amount <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>Qr '.$IISMethods->ind_format($row['totalpaid']).'</b></label>';
			$htmldata.='</div></div></div>';


			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Coupon Code <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['couponcode'].'</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Coupon Amount <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>Qr '.$IISMethods->ind_format($row['couponamount']).'</b></label>';
			$htmldata.='</div></div></div>';


			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Member <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['personname'].' ('.$row['contact'].')</b></label>';
			$htmldata.='</div></div></div>';


			$htmldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
			$htmldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
			$htmldata.='<label>Entry By <span>:</span></label>';
			$htmldata.='</div>';
			$htmldata.='<div class="col-12 col-sm-8 col-lg-6">';
			$htmldata.='<label class="label-view"><b>'.$row['entrypersonname'].' ('.$row['entrypersoncontact'].')</b></label>';
			$htmldata.='</div></div></div>';

			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';
			$htmldata.='</div>';										
			$htmldata.='</div>';

			


			$qry="select id,type,itemname,durationname,price,taxable,igsttaxamt,finalprice,igst,isnull(couponamount,0) as couponamount,expirydate,n_expirydate,strvalidityduration,isnull(sapitemid,'') as sapitemid,
			case when (type = 1) then 'Membership' when (type = 2) then 'Packages' when (type = 3) then 'Course' else '' end as typename,
			case when (convert(date,n_expirydate,103) >= :currdate1) then concat('Expires On ',n_expirydate) else concat('Expired On ',n_expirydate) end as strexpire,
			case when (convert(date,n_expirydate,103) >= :currdate2) then '#008140' else '#e63c2e' end as strexpirecolor 
			from tblorderdetail where orderid = :orderid";
			$parms = array(
				':orderid'=>$id,
				':currdate1'=>$currdate, 
				':currdate2'=>$currdate, 
			);
			$result_ary=$DB->getmenual($qry,$parms);
			if(sizeof($result_ary) > 0)
			{
				$htmldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
					$htmldata.='<div class="widget mt-10">';
						$htmldata.='<div class="widget-content row">';

							$htmldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
							$htmldata.='<div class="row1">';
							$htmldata.='<div class="table-responsive pt-2">';
							$htmldata.='<div class="col-12 p-0">';
							$htmldata.='<table id="tabview" class="table table-bordered table-hover table-striped">';
							$htmldata.='<thead>';
							$htmldata.='<tr>';
							$htmldata.='<th>Membership</th>';
							$htmldata.='<th>Validity</th>';
							$htmldata.='<th style="text-align: right;">Price</th>';
							$htmldata.='<th style="text-align: right;">Coupon Discount</th>';
							$htmldata.='<th style="text-align: right;">Taxable</th>';
							$htmldata.='<th style="text-align: right;">VAT</th>';
							$htmldata.='<th style="text-align: right;">Amount</th>';
							$htmldata.='<th style="text-align: right;">SAP Item ID</th>';
							$htmldata.='</tr>';
							$htmldata.='</thead>';
							$htmldata.='<tbody id="tblviewdata">';
							
							$htmldata1='';
							for($i=0;$i<sizeof($result_ary);$i++)
							{	
								$subrow=$result_ary[$i];
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

								$id="'".$subrow['id']."'";

								$htmldata.='<tr>';
								$htmldata.='<td><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Detail" onclick="vieworderattributedata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($subrow['itemname']).'</a>';
									$htmldata.='<br><p class="m-0" style="color:'.$IISMethods->sanitize($subrow['strexpirecolor']).'">Your '.$IISMethods->sanitize($subrow['typename']).' '.$IISMethods->sanitize($subrow['strexpire']).'</strong>.</p>';
								$htmldata.='</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['strvalidityduration']).'</td>';
								$htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['price'])).'</td>';
								$htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['couponamount'])).'</td>';
								$htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['taxable'])).'</td>';
								$htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['igsttaxamt'])).'<br><small>('.$IISMethods->sanitize($IISMethods->ind_format($subrow['igst'])).'%)</small></td>';
								$htmldata.='<td align="right">'.$IISMethods->sanitize($IISMethods->ind_format($subrow['finalprice'])).'</td>';
								$htmldata.='<td align="right">'.$IISMethods->sanitize($subrow['sapitemid']).'</td>';
								$htmldata.='</tr>';
							}
							$htmldata.='</tbody>';
							$htmldata.='</table>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';


						$htmldata.='</div>';
					$htmldata.='</div>';
				$htmldata.='</div>';
				
				
				$status=1;
				$message=$errmsg['success'];
			}


			
			
			$status=1;
			$message=$errmsg['success'];

			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($vesseldetails);
		}

	}
	else if($action=='vieworderattributedata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		
		if($responsetype=='HTML')
		{
			$htmldata="";

			//Composite Items
			$qry="select distinct oid.id as oidid,oid.catid,oid.category,oid.subcatid,oid.subcategory,oid.itemid,oid.itemname,oid.qty,oid.usedqty,oid.remainqty,
				oid.durationid,oid.durationname,oid.durationdays,oid.discount,oid.price,
				tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,oid.type,oid.typestr as typename
				from tblorderitemdetail oid 
				inner join tbltaxtype tt on tt.id=oid.gsttypeid
				inner join tbltax tx on tx.id=oid.gstid 
				where isnull(oid.iswebsiteattribute,0)=0 and isnull(oid.iscourse,0)=0 and oid.odid = :orderdetid ";
			$parms = array(
				':orderdetid'=>$id,
			);
			$result_ary=$DB->getmenual($qry,$parms);
			if(sizeof($result_ary) > 0)
			{
				$htmldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
					$htmldata.='<div class="widget mt-10">';
						$htmldata.='<div class="widget-content row">';

							$htmldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
							$htmldata.='<div class="row1">';
							$htmldata.='<div class="table-responsive pt-2">';
							$htmldata.='<h6>Item Details</h6>';
							$htmldata.='<div class="col-12 p-0">';
							$htmldata.='<table id="tabview" class="table table-bordered table-hover table-striped">';
							$htmldata.='<thead>';
							$htmldata.='<tr>';
							$htmldata.='<th>Item</th>';
							$htmldata.='<th>Type</th>';
							$htmldata.='<th>Qty</th>';
							$htmldata.='<th>Used Qty</th>';
							$htmldata.='<th>Remain Qty</th>';
							$htmldata.='<th>Discount</th>';
							$htmldata.='<th>VAT Type</th>';
							$htmldata.='<th>VAT</th>';
							$htmldata.='<th>Price</th>';
							$htmldata.='<th>Duration</th>';


							$htmldata.='</tr>';
							$htmldata.='</thead>';
							$htmldata.='<tbody id="tblviewdata">';
							
							$htmldata1='';
							for($i=0;$i<sizeof($result_ary);$i++)
							{	
								$subrow=$result_ary[$i];
								
								$htmldata.='<tr>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['itemname']).'</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['typename']).'</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['qty']).'</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['usedqty']).'</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['remainqty']).'</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['discount']).'</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['taxtypename']).'</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['igst']).'%</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['price']).'</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['durationname']).'</td>';

								$htmldata.='</tr>';
							}
							$htmldata.='</tbody>';
							$htmldata.='</table>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';


						$htmldata.='</div>';
					$htmldata.='</div>';
				$htmldata.='</div>';
				
			}

			
			//Website Display Items
			$qry = "select distinct tod.id,tod.webdisplayname as name,isnull(tod.attributename,'') as attributename,tod.displayorder,
				case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
				from tblorderitemdetail tod 
				left join tblitemiconmaster iim on iim.id=tod.iconid
				where tod.iswebsiteattribute=1 and tod.odid=:orderdetid order by tod.displayorder";
			$parms = array(
				':orderdetid'=>$id,
				':imageurl'=>$imageurl,
			);
			$result_ary=$DB->getmenual($qry,$parms);
			if(sizeof($result_ary) > 0)
			{
				$htmldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
					$htmldata.='<div class="widget mt-10">';
						$htmldata.='<div class="widget-content row">';

							$htmldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
							$htmldata.='<div class="row1">';
							$htmldata.='<div class="table-responsive pt-2">';
							$htmldata.='<h6>Website Display Attributes</h6>';
							$htmldata.='<div class="col-12 p-0">';
							$htmldata.='<table id="tabview" class="table table-bordered table-hover table-striped">';
							$htmldata.='<thead>';
							$htmldata.='<tr>';
							$htmldata.='<th>Name</th>';
							$htmldata.='</tr>';
							$htmldata.='</thead>';
							$htmldata.='<tbody id="tblviewdata">';
							
							$htmldata1='';
							for($i=0;$i<sizeof($result_ary);$i++)
							{	
								$subrow=$result_ary[$i];
								
								$htmldata.='<tr>';
								$htmldata.='<td><img src="'.$IISMethods->sanitize($subrow['iconimg']).'" width="25"> ';
								if($IISMethods->sanitize($subrow['attributename']) != '')
								{
									$htmldata.='<b>'.$IISMethods->sanitize($subrow['attributename']).' : </b>';
								}
								$htmldata.=$IISMethods->sanitize($subrow['name']);
								$htmldata.='</td>';
								$htmldata.='</tr>';
							}
							$htmldata.='</tbody>';
							$htmldata.='</table>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';


						$htmldata.='</div>';
					$htmldata.='</div>';
				$htmldata.='</div>';
				
			}



			//Course Benefit
			$qry = "select distinct tod.id,tod.webdisplayname as name,tod.durationname,tod.displayorder,
				case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg 
				from tblorderitemdetail tod 
				left join tblitemiconmaster iim on iim.id=tod.iconid
				where tod.iscourse=1 and tod.odid=:orderdetid order by tod.displayorder";
			$parms = array(
				':orderdetid'=>$id,
				':imageurl'=>$imageurl,
			);
			$result_ary=$DB->getmenual($qry,$parms);
			if(sizeof($result_ary) > 0)
			{
				$htmldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
					$htmldata.='<div class="widget mt-10">';
						$htmldata.='<div class="widget-content row">';

							$htmldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
							$htmldata.='<div class="row1">';
							$htmldata.='<div class="table-responsive pt-2">';
							$htmldata.='<h6>Courses Benefit</h6>';
							$htmldata.='<div class="col-12 p-0">';
							$htmldata.='<table id="tabview" class="table table-bordered table-hover table-striped">';
							$htmldata.='<thead>';
							$htmldata.='<tr>';
							$htmldata.='<th>Benefit</th>';
							$htmldata.='<th>Duration</th>';
							$htmldata.='</tr>';
							$htmldata.='</thead>';
							$htmldata.='<tbody id="tblviewdata">';
							
							$htmldata1='';
							for($i=0;$i<sizeof($result_ary);$i++)
							{	
								$subrow=$result_ary[$i];
								
								$htmldata.='<tr>';
								$htmldata.='<td><img src="'.$IISMethods->sanitize($subrow['iconimg']).'" width="25"> '.$IISMethods->sanitize($subrow['name']).'</td>';
								$htmldata.='<td>'.$IISMethods->sanitize($subrow['durationname']).'</td>';
								$htmldata.='</tr>';
							}
							$htmldata.='</tbody>';
							$htmldata.='</table>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';
							$htmldata.='</div>';


						$htmldata.='</div>';
					$htmldata.='</div>';
				$htmldata.='</div>';
				
			}


			
			
			$status=1;
			$message=$errmsg['success'];

			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($vesseldetails);
		}

	}
	
	else if($action=='fillcategory')   
	{
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);
		$isall=$IISMethods->sanitize($_POST['isall']);

		$qry="select c.id,c.category,c.iswebattribute,c.iscourse,c.iscompositeitem from tblcategory c where c.isactive=1 and c.id in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid) order by (case when (c.displayorder>0) then c.displayorder else 99999 end)";
		$params=array(
			':defaultcatmembershipid'=>$config->getDefaultCatMembershipId(),
			':defaultcatcourseid'=>$config->getDefaultCatCourseId(),
			':defaultcatpackageid'=>$config->getDefaultCatPackageId(),
		);
		$result_ary=$DB->getmenual($qry,$params);

		$htmldata='';
		if($selectoption == 1)
		{
			$htmldata.='<option value="" data-iswebattribute="" data-iscourse="" data-iscompositeitem="">Select Category</option>';
		}

		if($isall == 1)
		{
			$htmldata.='<option value="%">All</option>';
		}

		
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'" data-iswebattribute="'.$row['iswebattribute'].'" data-iscourse="'.$row['iscourse'].'" data-iscompositeitem="'.$row['iscompositeitem'].'">'.$row['category'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	} 
	//Fill Sub Category
	else if($action == 'fillsubcategory')
	{
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);

		$parms = array(
			':categoryid'=>$categoryid,
		);
		$qry="select sc.id,sc.subcategory from tblsubcategory sc where sc.isactive=1 and CONVERT(VARCHAR(255), sc.categoryid) =:categoryid 
			order by (case when (sc.displayorder>0) then sc.displayorder else 99999 end)";
		$result_ary=$DB->getmenual($qry,$parms);

		$htmldata='';
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Sub Category</option>';
		}

		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['subcategory'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}

	//Fill Item 
	else if($action=='fillitems') 
	{
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$subcategoryid=$IISMethods->sanitize($_POST['subcategoryid']);

		$itemid=$IISMethods->sanitize($_POST['itemid']);
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);
		$alloption = $IISMethods->sanitize($_POST['alloption']);

		$qry="SELECT im.id,im.itemname,im.price,im.gstid,im.gsttypeid from tblitemmaster im where isnull(im.isactive,0)=1 and CONVERT(VARCHAR(255), im.categoryid) = :categoryid  and CONVERT(VARCHAR(255), im.subcategoryid) = :subcategoryid";
		$parms = array(
			':categoryid'=>$categoryid,
			':subcategoryid'=>$subcategoryid,
		);	
		if($itemid != '')
		{
			$qry.=" and im.id<>:itemid";
			$parms[':itemid']=$itemid;
		}
		$result_ary=$DB->getmenual($qry,$parms);
		
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Item</option>';
		}
		else if($alloption == 1)
		{
			$htmldata.='<option value="0" data-price="0" data-gstid="" data-gsttypeid="">All Item</option>';
		}
		if(sizeof($result_ary)>0)
		{
			if($responsetype=='HTML')
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$htmldata.='<option value="'.$row['id'].'" data-price="'.$row['price'].'" data-gstid="'.$row['gstid'].'" data-gsttypeid="'.$row['gsttypeid'].'">'.$row['itemname'].'</option>';
				}
				$response['data']=$htmldata;
			}
		}
		$status=1;
		$message=$errmsg['success'];
	}
	else if ($action == 'fillitemdata')
	{
		$itemid=$IISMethods->sanitize($_POST['itemid']);
		$price=$IISMethods->sanitize($_POST['price']);

		if($itemid)
		{

			$qryitem="select im.id,im.itemname,im.categoryid,im.price,tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,dm.day as duration,dm.daytext as durationname,dm.strday as strvalidityduration, 
			case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg,
			ISNULL((select top 1 CONCAT(:baseimgurl,image) from tblitemimage where itemid = im.id order by displayorder),:defaultmembershipimgurl) as image  
			from tblitemmaster im 
			inner join tbltaxtype tt on tt.id=im.gsttypeid
			inner join tbltax tx on tx.id=im.gstid 
			inner join tbldurationmaster dm on dm.id=im.durationid
			left join tblitemiconmaster iim on iim.id=im.iconid
			where im.isactive=1 and im.id=:itemid ";

			$itemparms = array(
				':itemid'=>$itemid,
				':imageurl'=>$imageurl,
				':baseimgurl'=>$imageurl,
				':defaultmembershipimgurl'=>$config->getDefualtMemberShipImageurl(),
				
			);
			$resitem=$DB->getmenual($qryitem,$itemparms);	
			if(sizeof($resitem) > 0)
			{
				$rowitem=$resitem[0];

				$type = 0;
				if($rowitem['categoryid'] == $config->getDefaultCatMembershipId())
				{
					$type = 1;
				}
				else if($rowitem['categoryid'] == $config->getDefaultCatPackageId())
				{
					$type = 2;
				}
				else if($rowitem['categoryid'] == $config->getDefaultCatCourseId())
				{
					$type = 3;
				}
				

				$taxableamt=0;
				$taxamt=0;
				$finalprice=0;
				if($rowitem['taxtype'] == 1)  //For Exclusive Tax
				{
					$taxableamt=$price;
					$taxamt=round(($price*$rowitem['igst']/100),3);
					$finalprice=$taxableamt+$taxamt;
				}
				else if($rowitem['taxtype'] == 2)  //For Inclusive Tax
				{
					$taxableamt=round((($price*100)/(100+$rowitem['igst'])),3);
					$taxamt=round(($price-$taxableamt),3);
					$finalprice=$price;
				}

				$response['id']=$IISMethods->sanitize($rowitem['id']);
				$response['itemname']=$IISMethods->sanitize($rowitem['itemname']);
				$response['price']=$IISMethods->sanitize($price);
				$response['taxtype']=$IISMethods->sanitize($rowitem['taxtype']);
				$response['taxtypename']=$IISMethods->sanitize($rowitem['taxtypename']);
				$response['sgst']=$IISMethods->sanitize($rowitem['sgst']);
				$response['cgst']=$IISMethods->sanitize($rowitem['cgst']);
				$response['igst']=$IISMethods->sanitize($rowitem['igst']);
				$response['taxableamt']=$IISMethods->sanitize($taxableamt);
				$response['igsttaxamt']=$IISMethods->sanitize($taxamt);
				$response['sgsttaxamt']=$IISMethods->sanitize($taxamt/2);
				$response['cgsttaxamt']=$IISMethods->sanitize($taxamt/2);
				$response['finalprice']=$IISMethods->sanitize($finalprice);
				$response['duraton']=$IISMethods->sanitize($rowitem['duration']);
				$response['durationname']=$IISMethods->sanitize($rowitem['durationname']);
				$response['strvalidityduration']=$IISMethods->sanitize($rowitem['strvalidityduration']);
				$response['type']=$IISMethods->sanitize($type);

				$status=1;
				$message=$errmsg['success'];
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
	//-------------------- Apply Coupon ----------------------
	else if($action=='applycoupon')
	{
		$memberid=$IISMethods->sanitize($_POST['memberid']); 
		$couponcode=$IISMethods->sanitize($_POST['couponcode']); 
		$totalamt=$IISMethods->sanitize($_POST['totalamt']);
		$totaltaxableamt=$IISMethods->sanitize($_POST['totaltaxableamt']); 
		$currdate=$IISMethods->getformatcurrdate();   //Y-m-d

		if($couponcode)
		{
			$qrycode="SELECT c.*,
					(select count(id) from tblorder o where o.status=1 and o.iscancel=0 and convert(varchar(50),o.couponid)=convert(varchar(50),c.id)) as totaluse,
					(select count(id) from tblorder o where o.status=1 and o.iscancel=0 and convert(varchar(50),o.couponid)=convert(varchar(50),c.id) and convert(varchar(50),o.uid)=convert(varchar(50),:memberid)) as userwisecoupon 
				FROM tblcouponmaster c WHERE c.statusid=1 AND c.couponcode=:couponcode and (:currdate between CONVERT(date,c.startdate,103) and CONVERT(date,c.expirydate,103))";
			$couponparams=array(            
				':couponcode'=>$couponcode,
				':currdate'=>$currdate,
				':memberid'=>$memberid,
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
									
									$response['couponapply'] = 1;
									$response['couponid'] = $rowcode['id'];
									$response['couponcode'] = $couponcode;
									$response['coupontype'] = $distype;
									$response['couponamount'] = $disamt;
									$response['couponpercent'] = 0;
								
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

									$response['couponapply'] = 1;
									$response['couponid'] = $rowcode['id'];
									$response['couponcode'] = $couponcode;
									$response['coupontype'] = $distype;
									$response['couponamount'] = $disamt;
									$response['couponpercent'] = $per;

									
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
	//Regenerate Order Invoice
	else if($action=='regenerateorderinvoice')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				//Generate Order Invoice
				$genpdf = file_get_contents($config->getalhadafpdfurl().'mshipinvoice.php?id='.$id.'&type=generate&isshow=1');

				
				$status=1;
				$message=$errmsg['regorderinvoice'];

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
	//Sync SAP Order Data
	else if($action=='syncsaporderdata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				if($config->getIsAccessSAP() == 1)
				{
					//Update Package Data in SAP AR Invoice (HaNa DB)
					//$DB->SAPUpdateARInvoiceData($SubDB,$id);
				}
				
				
				$status=1;
				$message=$errmsg['syncorder'];

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

  