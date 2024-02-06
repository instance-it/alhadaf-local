<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\itemmaster.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();

	if($action=='insertitem')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$categoryid=$IISMethods->sanitize($_POST['categoryid']);
		$subcategoryid=$IISMethods->sanitize($_POST['subcategoryid']);
		$sapitemid=$IISMethods->sanitize($_POST['sapitemid']);
		$storeid=$_POST['storeid'];

		$itemname=$IISMethods->sanitize($_POST['itemname']);
		$price=$IISMethods->sanitize($_POST['price']);
		$gsttypeid=$IISMethods->sanitize($_POST['gsttypeid']);
		$gstid=$IISMethods->sanitize($_POST['gstid']);
		$duration = $IISMethods->sanitize($_POST['duration']);
		$noofstudent = $IISMethods->sanitize($_POST['noofstudent']);
		$durationid = $IISMethods->sanitize($_POST['durationid']);

		$shortdescr=$IISMethods->sanitize($_POST['shortdescr']);
		$description=$_POST['descr'];

		$iconid=$IISMethods->sanitize($_POST['iconid']);

		$iscompositeitem=$IISMethods->sanitize($_POST['iscompositeitem']);
		$iswebattribute=$IISMethods->sanitize($_POST['iswebattribute']);   //1 For Website Display Item Data
		$iscourse = $IISMethods->sanitize($_POST['iscourse']);

		//Composite Item Data
		$tblrowcatid=$_POST['tblrowcatid'];
		$tblrowcategory=$_POST['tblrowcategory'];
		$tblrowsubcatid=$_POST['tblrowsubcatid'];
		$tblrowsubcategory=$_POST['tblrowsubcategory'];
		$tblrowitemid=$_POST['tblrowitemid'];
		$tblrowitemname=$_POST['tblrowitem'];
		$tblrowwebdisplayname=$_POST['tblrowwebdisplayname'];
		$tblrowqty=$_POST['tblrowqty'];
		$tblrowiconid=$_POST['tblrowiconid'];
		
		$tblrowdiscount = $_POST['tblrowdiscount'];
		$tblrowprice = $_POST['tblrowprice'];
		$tblrowgsttypeid = $_POST['tblrowgsttypeid'];
		$tblrowgstid = $_POST['tblrowgstid'];
		$tblrowgsttype = $_POST['tblrowgsttype'];
		$tblrowgst = $_POST['tblrowgst'];

		$tbldurationid = $_POST['tbldurationid'];
		$tbldurationname = $_POST['tbldurationname'];
		$tbldurationday = $_POST['tbldurationday'];

		$tblrowtypeid = $_POST['tblrowtypeid'];
		$tblrowtypestr = $_POST['tblrowtypestr'];
		$tblrowtype = $_POST['tblrowtype'];



		//Website Display Item Data
		$tblwrowattributeid=$_POST['tblwrowattributeid'];
		$tblwrowattributename=$_POST['tblwrowattributename'];
		$tblwrowwebdisplayname=$_POST['tblwrowwebdisplayname'];
		$tblwrowiconid=$_POST['tblwrowiconid'];
		$tblwrowdisplayorder=$_POST['tblwrowdisplayorder'];

		// benefit item data
		$tblbrowwebdisplayname=$_POST['tblbrowwebdisplayname'];
		$tblbrowiconid=$_POST['tblbrowiconid'];
		$tblbrowdisplayorder=$_POST['tblbrowdisplayorder'];
		$tblbrowduration = $_POST['tblbrowduration'];
		
		if($iconid == '')
		{
			$iconid=$mssqldefval['uniqueidentifier'];
		}

		if($durationid == '')
		{
			$durationid = $mssqldefval['uniqueidentifier'];
		}

		$id=$IISMethods->sanitize($_POST['id']);


		if($categoryid && $subcategoryid && $itemname && sizeof($storeid) > 0 && $gsttypeid && $gstid)
		{
			$insqry=array(
				'[sapitemid]'=>$sapitemid,
				'[categoryid]'=>$categoryid,
				'[subcategoryid]'=>$subcategoryid,
				'[itemname]'=>$itemname,
				'[price]'=>$price,
				'[gsttypeid]'=>$gsttypeid,
				'[gstid]'=>$gstid,
				'[shortdescr]'=>$shortdescr,
				'[descr]'=>$description,
				'[iconid]'=>$iconid,
				'[iscompositeitem]'=>$iscompositeitem,
				'[noofstudent]'=>$noofstudent,
				'[duration]'=>$duration,
				'[durationid]'=>$durationid,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT id from tblitemmaster where categoryid=:categoryid and subcategoryid=:subcategoryid and itemname=:itemname";
				$parms = array(
					':categoryid'=>$categoryid,
					':subcategoryid'=>$subcategoryid,
					':itemname'=>$itemname,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					$status=0;
					$message=$errmsg['isexist'];
				}
				else
				{
					try 
					{
						$DB->begintransaction();	
						
						$unqid = $IISMethods->generateuuid();

						$maxid=$DB->getitemmaxid();
						$itemno=$DB->generateitemnumber();

						$insqry['[id]']=$unqid;	
						$insqry['[itemno]']=$itemno;	
						$insqry['[maxid]']=$maxid;	
						$insqry['[timestamp]']=$IISMethods->getdatetime();
						$insqry['[entry_uid]']=$uid;	
						$insqry['[entry_date]']=$IISMethods->getdatetime();

						$DB->executedata('i','tblitemmaster',$insqry,'');

						//Insert Item Store
						for($i=0;$i<sizeof($storeid);$i++)
						{
							$stid = $IISMethods->generateuuid();
							$insistore=array(	
								'[id]'=>$stid,				
								'[itemid]'=>$unqid,
								'[storeid]'=>$storeid[$i],
								'[timestamp]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tblitemstore',$insistore,'');
						}
						

						// Insert Composite Item
						if($iscompositeitem == 1)
						{
							// echo sizeof($tblrowitemid);
							for($j=0;$j<sizeof($tblrowitemid);$j++)
							{
								$rowiconid=$tblrowiconid[$j];
								if($tblrowiconid[$j] == '')
								{
									$rowiconid=$mssqldefval['uniqueidentifier'];
								}

								$rowitemid = $tblrowitemid[$j];
								if($tblrowitemid[$j] == '' || $tblrowitemid[$j] == '0')
								{
									$rowitemid = $mssqldefval['uniqueidentifier'];
								}

								$durationid=$tbldurationid[$j];
								$durationname = $tbldurationname[$j];
								$durationday = $tbldurationday[$j];
								if($tbldurationid[$j] == '')
								{
									$durationid=$mssqldefval['uniqueidentifier'];
									$durationname = '';
									$durationday = '';
								}

								$rowqty=1;
								if($tblrowqty[$j] > 0)
								{
									$rowqty=$tblrowqty[$j];
								}
								
								$ciid = $IISMethods->generateuuid();
								$insicomp=array(	
									'[id]'=>$ciid,				
									'[itemid]'=>$unqid,
									'[rowcatid]'=>$tblrowcatid[$j],
									'[rowcategory]'=>$tblrowcategory[$j],
									'[rowsubcatid]'=>$tblrowsubcatid[$j],
									'[rowsubcategory]'=>$tblrowsubcategory[$j],
									'[rowitemid]'=>$rowitemid,
									'[rowitemname]'=>$tblrowitemname[$j],
									'[rowqty]'=>$rowqty,
									'[rowiconid]'=>$rowiconid,
									'[rowdurationid]'=>$durationid,
									'[rowdurationname]'=>$durationname,
									'[rowdurationdays]'=>$durationday,
									'[iswebsiteattribute]'=>0,
									'[timestamp]'=>$IISMethods->getdatetime(),
									'[rowdiscount]'=>$tblrowdiscount[$j],
									'[rowprice]'=>$tblrowprice[$j],
									'[rowgsttypeid]'=>$tblrowgsttypeid[$j],
									'[rowgstid]'=>$tblrowgstid[$j],
									'[rowgsttype]'=>$tblrowgsttype[$j],
									'[rowgst]'=>$tblrowgst[$j],
									'[rowtypeid]'=>$tblrowtypeid[$j],
									'[rowtypestr]'=>$tblrowtypestr[$j],
									'[rowtype]'=>$tblrowtype[$j],

								);
								$DB->executedata('i','tblitemdetails',$insicomp,'');
							}
						}


						// Insert Web Composite Item
						if($iswebattribute == 1)
						{
							for($k=0;$k<sizeof($tblwrowwebdisplayname);$k++)
							{
								$wrowiconid=$tblwrowiconid[$k];
								if($tblwrowiconid[$k] == '')
								{
									$wrowiconid=$mssqldefval['uniqueidentifier'];
								}


								$wrowattributeid=$tblwrowattributeid[$k];
								if($tblwrowattributeid[$k] == '')
								{
									$wrowattributeid=$mssqldefval['uniqueidentifier'];
								}
								
								$ciid = $IISMethods->generateuuid();
								$insicomp=array(	
									'[id]'=>$ciid,				
									'[itemid]'=>$unqid,
									'[rowcatid]'=>$mssqldefval['uniqueidentifier'],
									'[rowcategory]'=>'',
									'[rowsubcatid]'=>$mssqldefval['uniqueidentifier'],
									'[rowsubcategory]'=>'',
									'[rowitemid]'=>$mssqldefval['uniqueidentifier'],
									'[rowitemname]'=>'',
									'[rowattributeid]'=>$wrowattributeid,
									'[rowattributename]'=>$tblwrowattributename[$k],
									'[rowwebdisplayname]'=>$tblwrowwebdisplayname[$k],
									'[rowqty]'=>0,
									'[rowiconid]'=>$wrowiconid,
									'[rowdisplayorder]'=>$tblwrowdisplayorder[$k],
									'[iswebsiteattribute]'=>1,
									'[timestamp]'=>$IISMethods->getdatetime(),
									'[rowdiscount]'=>'',
									'[rowprice]'=>'',
									'[rowgsttypeid]'=>$mssqldefval['uniqueidentifier'],
									'[rowgstid]'=>$mssqldefval['uniqueidentifier'],
									'[rowgsttype]'=>'',
									'[rowgst]'=>'',
								);
								$DB->executedata('i','tblitemdetails',$insicomp,'');
							}
						}

						if($iscourse == 1)
						{
							for($k=0;$k<sizeof($tblbrowwebdisplayname);$k++)
							{
								$browiconid=$tblbrowiconid[$k];
								if($tblbrowiconid[$k] == '')
								{
									$browiconid=$mssqldefval['uniqueidentifier'];
								}
								
								$ciid = $IISMethods->generateuuid();
								$insicomp=array(	
									'[id]'=>$ciid,				
									'[itemid]'=>$unqid,
									'[rowcatid]'=>$mssqldefval['uniqueidentifier'],
									'[rowcategory]'=>'',
									'[rowsubcatid]'=>$mssqldefval['uniqueidentifier'],
									'[rowsubcategory]'=>'',
									'[rowitemid]'=>$mssqldefval['uniqueidentifier'],
									'[rowitemname]'=>'',
									'[rowwebdisplayname]'=>$tblbrowwebdisplayname[$k],
									'[rowqty]'=>0,
									'[rowiconid]'=>$browiconid,
									'[rowdisplayorder]'=>$tblbrowdisplayorder[$k],
									'[rowdurationname]'=>$tblbrowduration[$k],
									'[iscourse]'=>1,
									'[timestamp]'=>$IISMethods->getdatetime(),
									'[rowdiscount]'=>'',
									'[rowprice]'=>'',
									'[rowgsttypeid]'=>'',
									'[rowgstid]'=>'',
									'[rowgsttype]'=>'',
									'[rowgst]'=>'',
								);
								// print_r($insicomp);
								$DB->executedata('i','tblitemdetails',$insicomp,'');
							}
						}

						$status=1;
						$message=$errmsg['insert'];

						$DB->committransaction();
					}
					catch (Exception $e) 
					{
						$DB->rollbacktransaction($e);
						$status=0;
						$message=$errmsg['dbtransactionerror'];
					}
					
				}
			}
			else if($formevent=='editright')
			{

				$qrychk="SELECT id from tblitemmaster where categoryid=:categoryid and subcategoryid=:subcategoryid and itemname=:itemname AND id<>:id";
				$parms = array(
					':categoryid'=>$categoryid,
					':subcategoryid'=>$subcategoryid,
					':itemname'=>$itemname,
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					$status=0;
					$message=$errmsg['isexist'];
				}
				else
				{
					try 
					{
						$DB->begintransaction();

						$insqry['[update_uid]']=$uid;	
						$insqry['[update_date]']=$IISMethods->getdatetime();

						$extraparams=array(
							'[id]'=>$id
						);
						$DB->executedata('u','tblitemmaster',$insqry,$extraparams);

						//Delete & Insert Item Store
						$delparams=array(
							'[itemid]'=>$id
						);
						$DB->executedata('d','tblitemstore','',$delparams);
						for($i=0;$i<sizeof($storeid);$i++)
						{
							$stid = $IISMethods->generateuuid();
							$insistore=array(	
								'[id]'=>$stid,				
								'[itemid]'=>$id,
								'[storeid]'=>$storeid[$i],
								'[timestamp]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tblitemstore',$insistore,'');
						}

						
						$delparams=array(
							'[itemid]'=>$id
						);
						$DB->executedata('d','tblitemdetails','',$delparams);

						// Insert Composite Item
						if($iscompositeitem == 1)
						{
							for($j=0;$j<sizeof($tblrowitemid);$j++)
							{
								$rowiconid=$tblrowiconid[$j];
								if($tblrowiconid[$j] == '')
								{
									$rowiconid=$mssqldefval['uniqueidentifier'];
								}

								

								$rowitemid = $tblrowitemid[$j];
								if($tblrowitemid[$j] == '' || $tblrowitemid[$j] == '0')
								{
									$rowitemid = $mssqldefval['uniqueidentifier'];
								}

								//echo ' || '.$tblrowitemid[$j].' *** '.$rowitemid;

								$durationid=$tbldurationid[$j];
								$durationname = $tbldurationname[$j];
								$durationday = $tbldurationday[$j];
								if($tbldurationid[$j] == '')
								{
									$durationid=$mssqldefval['uniqueidentifier'];
									$durationname = '';
									$durationday = '';
								}

								$rowqty=1;
								if($tblrowqty[$j] > 0)
								{
									$rowqty=$tblrowqty[$j];
								}

								$ciid = $IISMethods->generateuuid();
								$insicomp=array(	
									'[id]'=>$ciid,				
									'[itemid]'=>$id,
									'[rowcatid]'=>$tblrowcatid[$j],
									'[rowcategory]'=>$tblrowcategory[$j],
									'[rowsubcatid]'=>$tblrowsubcatid[$j],
									'[rowsubcategory]'=>$tblrowsubcategory[$j],
									'[rowitemid]'=>$rowitemid,
									'[rowitemname]'=>$tblrowitemname[$j],
									'[rowqty]'=>$rowqty,
									'[rowiconid]'=>$rowiconid,
									'[rowdurationid]'=>$durationid,
									'[rowdurationname]'=>$durationname,
									'[rowdurationdays]'=>$durationday,
									'[iswebsiteattribute]'=>0,
									'[timestamp]'=>$IISMethods->getdatetime(),
									'[rowdiscount]'=>$tblrowdiscount[$j],
									'[rowprice]'=>$tblrowprice[$j],
									'[rowgsttypeid]'=>$tblrowgsttypeid[$j],
									'[rowgstid]'=>$tblrowgstid[$j],
									'[rowgsttype]'=>$tblrowgsttype[$j],
									'[rowgst]'=>$tblrowgst[$j],
									'[rowtypeid]'=>$tblrowtypeid[$j],
									'[rowtypestr]'=>$tblrowtypestr[$j],
									'[rowtype]'=>$tblrowtype[$j],

								);
								$DB->executedata('i','tblitemdetails',$insicomp,'');
							}
						}

					
						// Insert Web Composite Item
						if($iswebattribute == 1)
						{
							for($k=0;$k<sizeof($tblwrowwebdisplayname);$k++)
							{
								$wrowiconid=$tblwrowiconid[$k];
								if($tblwrowiconid[$k] == '')
								{
									$wrowiconid=$mssqldefval['uniqueidentifier'];
								}

								$wrowattributeid=$tblwrowattributeid[$k];
								if($tblwrowattributeid[$k] == '')
								{
									$wrowattributeid=$mssqldefval['uniqueidentifier'];
								}
								
								$ciid = $IISMethods->generateuuid();
								$insicomp=array(	
									'[id]'=>$ciid,				
									'[itemid]'=>$id,
									'[rowcatid]'=>$mssqldefval['uniqueidentifier'],
									'[rowcategory]'=>'',
									'[rowsubcatid]'=>$mssqldefval['uniqueidentifier'],
									'[rowsubcategory]'=>'',
									'[rowitemid]'=>$mssqldefval['uniqueidentifier'],
									'[rowitemname]'=>'',
									'[rowattributeid]'=>$wrowattributeid,
									'[rowattributename]'=>$tblwrowattributename[$k],
									'[rowwebdisplayname]'=>$tblwrowwebdisplayname[$k],
									'[rowqty]'=>0,
									'[rowiconid]'=>$wrowiconid,
									'[rowdisplayorder]'=>$tblwrowdisplayorder[$k],
									'[iswebsiteattribute]'=>1,
									'[timestamp]'=>$IISMethods->getdatetime(),
									
								);
								$DB->executedata('i','tblitemdetails',$insicomp,'');
							}
						}

						if($iscourse == 1)
						{
							for($k=0;$k<sizeof($tblbrowwebdisplayname);$k++)
							{
								$browiconid=$tblbrowiconid[$k];
								if($tblbrowiconid[$k] == '')
								{
									$browiconid=$mssqldefval['uniqueidentifier'];
								}
								
								$ciid = $IISMethods->generateuuid();
								$insicomp=array(	
									'[id]'=>$ciid,				
									'[itemid]'=>$id,
									'[rowcatid]'=>$mssqldefval['uniqueidentifier'],
									'[rowcategory]'=>'',
									'[rowsubcatid]'=>$mssqldefval['uniqueidentifier'],
									'[rowsubcategory]'=>'',
									'[rowitemid]'=>$mssqldefval['uniqueidentifier'],
									'[rowitemname]'=>'',
									'[rowwebdisplayname]'=>$tblbrowwebdisplayname[$k],
									'[rowqty]'=>0,
									'[rowiconid]'=>$browiconid,
									'[rowdisplayorder]'=>$tblbrowdisplayorder[$k],
									'[rowdurationname]'=>$tblbrowduration[$k],
									'[iscourse]'=>1,
									'[timestamp]'=>$IISMethods->getdatetime(),
									'[rowdiscount]'=>'',
									'[rowprice]'=>'',
									'[rowgsttypeid]'=>$mssqldefval['uniqueidentifier'],
									'[rowgstid]'=>$mssqldefval['uniqueidentifier'],
									'[rowgsttype]'=>'',
									'[rowgst]'=>'',
								);
								// print_r($insicomp);
								$DB->executedata('i','tblitemdetails',$insicomp,'');
							}
						}
						$status=1;
						$message=$errmsg['update'];

						

						$DB->committransaction();
					}
					catch (Exception $e) 
					{
						$DB->rollbacktransaction($e);
						$status=0;
						$message=$errmsg['dbtransactionerror'];
					}
					
				}
			}
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deleteitemmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			$qrychk="SELECT case when(convert(varchar(50),imd.id) !='' or convert(varchar(50),od.id) !='' or convert(varchar(50),odi.id) !='') then 1 else 0 end as tem
			from tblitemmaster im
			left join tblitemdetails imd on imd.rowitemid = im.id
			left join tblorderdetail od on od.itemid = im.id
			left join tblorderitemdetail odi on odi.itemid = im.id
			where im.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);
			$row=$result_ary[0];

			if($row['tem'] > 0)
			{
				$status=0;
				$message=$errmsg['inuse'];
			}
			else
			{
				try 
				{
					$DB->begintransaction();

					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('d','tblitemmaster','',$extraparams);

					$delparams=array(
						'[itemid]'=>$id
					);
					$DB->executedata('d','tblitemstore','',$delparams);

					$delparams1=array(
						'[itemid]'=>$id
					);
					$DB->executedata('d','tblitemdetails','',$delparams1);
					
					$status=1;
					$message=$errmsg['delete'];

					$DB->committransaction();
				}
				catch (Exception $e) 
				{
					$DB->rollbacktransaction($e);
					$status=0;
					$message=$errmsg['dbtransactionerror'];
				}
			}

			
		}
		else if(sizeof($bulk)>0)
		{
			try 
			{
				$DB->begintransaction();

				$usemenu='';
				for($i=0;$i<sizeof($bulk);$i++)
				{
					$id=$bulk[$i];

					$qrychk="SELECT case when(convert(varchar(50),imd.id) !='' or convert(varchar(50),od.id) !='' or convert(varchar(50),odi.id) !='') then 1 else 0 end as tem,im.itemname
					from tblitemmaster im
					left join tblitemdetails imd on imd.rowitemid = im.id
					left join tblorderdetail od on od.itemid = im.id
					left join tblorderitemdetail odi on odi.itemid = im.id
					where im.id=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['itemname'].",";
					}
					else
					{
						
			
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblitemmaster','',$extraparams);

						$delparams=array(
							'[itemid]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblitemstore','',$delparams);

						$delparams1=array(
							'[itemid]'=>$id
						);
						$DB->executedata('d','tblitemdetails','',$delparams1);


					}
				}
				$status=1;
				$message=$errmsg['delete'].' '.$usemenu;

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
	else if($action=='edititemmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select im.id,im.sapitemid,im.categoryid,im.subcategoryid,im.itemname,im.price,im.gsttypeid,im.gstid,im.shortdescr,im.descr,im.iconid,c.iscompositeitem as c_iscompositeitem,
				im.iscompositeitem,c.iswebattribute,c.iscourse,im.duration,im.noofstudent,im.durationid,
				ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), si.storeid) AS [text()] FROM tblitemstore si WHERE CONVERT(VARCHAR(255), si.itemid)=im.id FOR XML PATH ('')),2,100000),'') as storeid 
				from tblitemmaster im 
				inner join tblcategory c on c.id=im.categoryid 
				where im.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			print_r($result_ary);
			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['sapitemid']=$IISMethods->sanitize($row['sapitemid']);
				$response['categoryid']=$IISMethods->sanitize($row['categoryid']);
				$response['subcategoryid']=$IISMethods->sanitize($row['subcategoryid']);
				$response['itemname']=$IISMethods->sanitize($row['itemname']);
				$response['price']=$IISMethods->sanitize($row['price']);
				$response['gsttypeid']=$IISMethods->sanitize($row['gsttypeid']);
				$response['gstid']=$IISMethods->sanitize($row['gstid']);
				$response['shortdescr']=$IISMethods->sanitize($row['shortdescr']);
				$response['descr']=$row['descr'];
				$response['iconid']=$IISMethods->sanitize($row['iconid']);
				$response['iscompositeitem']=$IISMethods->sanitize($row['iscompositeitem']);
				$response['iswebattribute']=$IISMethods->sanitize($row['iswebattribute']);
				$response['iscourse']=$IISMethods->sanitize($row['iscourse']);
				$response['duration']=$IISMethods->sanitize($row['duration']);
				$response['noofstudent']=$IISMethods->sanitize($row['noofstudent']);
				$response['durationid']=$IISMethods->sanitize($row['durationid']);
				$response['c_iscompositeitem']=$IISMethods->sanitize($row['c_iscompositeitem']);

				$response['storeid']=$IISMethods->sanitize($row['storeid']);

				//Composite Item Data 
				$subqry="select iid.id,iid.itemid,iid.rowcatid,iid.rowcategory,iid.rowsubcatid,iid.rowsubcategory,iid.rowitemid,iid.rowitemname,iid.rowwebdisplayname,iid.rowqty,iid.rowiconid,
				case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg,isnull(iim.iconname,'') as iconname,
				iid.rowdurationdays,iid.rowdurationid,isnull(iid.rowdurationname,'') as rowdurationname,iid.rowdiscount,iid.rowprice, iid.rowgsttypeid, iid.rowgstid,iid.rowgst,iid.rowgsttype,
				iid.rowtypeid,iid.rowtypestr,iid.rowtype
				from tblitemdetails iid 
				left join tblitemiconmaster iim on iim.id=iid.rowiconid
				where iid.itemid=:id and iid.iswebsiteattribute=0 and iid.iscourse = 0";
				$subparms = array(
					':id'=>$id,
					':imageurl'=>$imageurl,
				);
				$subresult_ary=$DB->getmenual($subqry,$subparms);
				if(sizeof($subresult_ary)>0)
				{
					$response['data'] = $subresult_ary;
				}



				//Website Composite Item Data 
				$wsubqry="select iid.id,iid.itemid,isnull(convert(varchar(50),iid.rowattributeid),'') as rowattributeid,isnull(iid.rowattributename,'') as rowattributename,iid.rowwebdisplayname,iid.rowqty,iid.rowiconid,iid.rowdisplayorder,
				case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg,isnull(iim.iconname,'') as iconname 
				from tblitemdetails iid 
				left join tblitemiconmaster iim on iim.id=iid.rowiconid
				where iid.itemid=:id and iid.iswebsiteattribute=1 order by iid.rowdisplayorder";
				$wsubparms = array(
					':id'=>$id,
					':imageurl'=>$imageurl,
				);
				$wsubresult_ary=$DB->getmenual($wsubqry,$wsubparms);
				if(sizeof($wsubresult_ary)>0)
				{
					$response['dataweb'] = $wsubresult_ary;
				}

				$wsubqry="select iid.id,iid.itemid,iid.rowwebdisplayname,iid.rowqty,iid.rowiconid,iid.rowdisplayorder,iid.rowdurationname,
				case when (isnull(iim.iconimg,'')='') then '' else concat(:imageurl,iim.iconimg) end as iconimg,isnull(iim.iconname,'') as iconname 
				from tblitemdetails iid 
				left join tblitemiconmaster iim on iim.id=iid.rowiconid
				where iid.itemid=:id and iid.iscourse=1 order by iid.rowdisplayorder";
				$wsubparms = array(
					':id'=>$id,
					':imageurl'=>$imageurl,
				);
				$wsubresult_ary=$DB->getmenual($wsubqry,$wsubparms);
				if(sizeof($wsubresult_ary)>0)
				{
					$response['datacourse'] = $wsubresult_ary;
				}

				$status=1;
				$message=$errmsg['datafound'];
			}
			else
			{
				$status=1;
				$message=$errmsg['nodatafound'];
			}
			
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='listitemmaster')   
	{
		$issidebarflt=$IISMethods->sanitize($_POST['issidebarflt']);
		$fltstoreid=$IISMethods->sanitize($_POST['fltstoreid']);
		$fltcatid=$IISMethods->sanitize($_POST['fltcatid']);

		$itemmasters=new itemmaster();
		$qry="select distinct im.timestamp as primary_date,im.id,im.categoryid,c.category,im.subcategoryid,sc.subcategory,im.itemname,im.price,im.isactive,im.showonhome,im.showonweb,im.showonpos,
			case when((select top 1 CONVERT(VARCHAR(255), itemid) from tblitemimage where CONVERT(VARCHAR(255), itemid)  = im.id) != '') then 'text-warning' else 'text-default' end as imgclass,
			t.taxname,case when (im.iscompositeitem=1) then 'Yes' else 'No' end AS str_iscompositeitem,
			isnull((select daytext from tbldurationmaster d where d.id = im.durationid),'') as durationtxt
			from tblitemmaster im 
			inner join tblcategory c on c.id = im.categoryid
			inner join tblsubcategory sc on sc.id = im.subcategoryid
			inner join tbltax t on t.id = im.gstid
			inner join tblitemstore si on si.itemid=im.id
			where (c.category like :catfilter or sc.subcategory like :subcatfilter or im.itemname like :itemfilter or im.price like :pricefilter) ";
		$parms = array(
			':catfilter'=>$filter,
			':subcatfilter'=>$filter,
			':itemfilter'=>$filter,
			':pricefilter'=>$filter,
		);

		if($issidebarflt == 1)
		{
			if($fltstoreid)
			{
				$qry.=" and si.storeid like :fltstoreid";
				$parms[':fltstoreid']=$fltstoreid;  
			}	

			if($fltcatid)
			{
				$qry.=" and im.categoryid like :fltcatid";
				$parms[':fltcatid']=$fltcatid;  
			}	
		}

		$qry.=" order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";

		//echo $qry;
		//print_r($parms);
		$itemmasters=$DB->getmenual($qry,$parms,'itemmaster');
		
		if($responsetype=='HTML')
		{
			if($itemmasters)
			{
				$i=0;
				foreach($itemmasters as $itemmaster)
				{
					$id="'".$itemmaster->id."'";
					$htmldata.='<tr data-index="'.$i.'">';
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || (sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-grid">';
						$htmldata.='<div class="dropdown table-dropdown">';
						$htmldata.='<button class="dropdown-toggle btn-tbl rounded-circle" type="button" id="tableDropdown3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>';
						$htmldata.='<div class="dropdown-menu" aria-labelledby="tableDropdown3">';

						if(((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright - all person data show,selfedit - only self person data show
						{
							$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="editdata('.$IISMethods->sanitize($id).')"><i class="bi bi-pencil"></i> Edit</a>';
						}
						if(((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright - all person data show,selfedit - only self person data show
						{
							$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="deletedata('.$IISMethods->sanitize($id).')"><i class="bi bi-trash"></i> Delete</a>';
						}

						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					if((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-check d-none">';
						$htmldata.='<div class="text-center">';
						$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($itemmaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($itemmaster->category,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($itemmaster->subcategory,'OUT').'</td>';
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Item Details" onclick="viewitemdetaildata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($itemmaster->itemname,'OUT').'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($itemmaster->price).'</td>';
					$htmldata.='<td class="tbl-info">'.$IISMethods->sanitize($itemmaster->taxname).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($itemmaster->durationtxt).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($itemmaster->str_iscompositeitem).'</td>';

					$htmldata.='<td class="tbl-name" align="center"><a href="javascript:void(0);" class="btn-tbl '.$IISMethods->sanitize($itemmaster->imgclass).' rounded-circle" id="btnaddimages" data-toggle="tooltip" data-placement="top" title="Add Image" data-original-title="Add Image" onclick="uploaditemimage('.$IISMethods->sanitize($id).')"><i class="bi bi-plus"></i></a></td>';

					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-name">';
						if($itemmaster->categoryid == $config->getDefaultCatPackageId() || $itemmaster->categoryid == $config->getDefaultCatCourseId() || $itemmaster->categoryid == $config->getDefaultCatMembershipId())
						{
							if($itemmaster->showonhome==1)
							{
								$htmldata.='<label class="switch">';
									$htmldata.='<input type="checkbox" onclick="changeshowonhomestatus('.$id.')" checked>';
									$htmldata.='<span class="slider round"></span>';
								$htmldata.='</label>';
							}
							else
							{
								$htmldata.='<label class="switch">';
									$htmldata.='<input type="checkbox" onclick="changeshowonhomestatus('.$id.')">';
									$htmldata.='<span class="slider round"></span>';
								$htmldata.='</label>';
							}
						}
						$htmldata.='</td>';

						$htmldata.='<td class="tbl-name">';
						if($itemmaster->categoryid == $config->getDefaultCatPackageId() || $itemmaster->categoryid == $config->getDefaultCatCourseId() || $itemmaster->categoryid == $config->getDefaultCatMembershipId())
						{
							if($itemmaster->showonweb==1)
							{
								$htmldata.='<label class="switch">';
									$htmldata.='<input type="checkbox" onclick="changeshowonwebstatus('.$id.')" checked>';
									$htmldata.='<span class="slider round"></span>';
								$htmldata.='</label>';
							}
							else
							{
								$htmldata.='<label class="switch">';
									$htmldata.='<input type="checkbox" onclick="changeshowonwebstatus('.$id.')">';
									$htmldata.='<span class="slider round"></span>';
								$htmldata.='</label>';
							}
						}
						$htmldata.='</td>';

						$htmldata.='<td class="tbl-name">';
						if($itemmaster->categoryid == $config->getDefaultCatPackageId() || $itemmaster->categoryid == $config->getDefaultCatCourseId() || $itemmaster->categoryid == $config->getDefaultCatMembershipId())
						{
							if($itemmaster->showonpos==1)
							{
								$htmldata.='<label class="switch">';
									$htmldata.='<input type="checkbox" onclick="changeshowonposstatus('.$id.')" checked>';
									$htmldata.='<span class="slider round"></span>';
								$htmldata.='</label>';
							}
							else
							{
								$htmldata.='<label class="switch">';
									$htmldata.='<input type="checkbox" onclick="changeshowonposstatus('.$id.')">';
									$htmldata.='<span class="slider round"></span>';
								$htmldata.='</label>';
							}
						}
						$htmldata.='</td>';


						if($itemmaster->isactive==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeitemstatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeitemstatus('.$id.')">';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
					}
					
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($itemmasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($itemmasters);
		}

		$common_listdata=$itemmasters;
	} 
	else if($action=='viewitemdata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select im.timestamp as primary_date,im.id,im.sapitemid,im.categoryid,c.iswebattribute,c.category,im.subcategoryid,sc.subcategory,im.itemname,im.price,tt.taxtype,t.taxname,im.shortdescr,im.descr,c.iscourse,
			im.iscompositeitem,case when (im.iscompositeitem=1) then 'Yes' else 'No' end AS str_iscompositeitem,isnull(ii.iconimg,'') as iconimg,im.duration,im.noofstudent,dm.daytext,
			isnull((SELECT SUBSTRING((select ', '+CONVERT(VARCHAR(255), pm.storename) AS [text()] from tblitemstore si inner join tblstoremaster pm on pm.id=si.storeid where si.itemid=im.id FOR XML PATH ('')),2,1000000)),'') as storename 
			from tblitemmaster im 
			inner join tblcategory c on c.id = im.categoryid
			inner join tblsubcategory sc on sc.id = im.subcategoryid
			inner join tbltaxtype tt on tt.id = im.gsttypeid 
			inner join tbltax t on t.id = im.gstid 
			left join tbldurationmaster dm on dm.id = im.durationid
			left join tblitemiconmaster ii on ii.id = im.iconid 
			where im.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);
			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$sapitemname=$row['sapitemid'];
				


				$tbldata='';
				$tbldata.='<div class="col-12">';
				$tbldata.='<div class="table-responsive">';
				$tbldata.='<div class="col-12 view-data-details">';
				$tbldata.='<div class="row my-3">';
				$tbldata.='<div class="col-12 col-md-8 col-lg">';
				$tbldata.='<div class="row">';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Category <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['category'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Sub Category <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['subcategory'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Store/Counter <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['storename'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>SAP Item <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$sapitemname.'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Item Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['itemname'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Item Price <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['price'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>GST <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['taxname'].' ('.$row['taxtype'].')</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Composite Item <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['str_iscompositeitem'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Courses Duration<span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['duration'].' Hours</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>No Of Student <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['noofstudent'].'</b></label>';
					$tbldata.='</div></div></div>';
					
					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Duration <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['daytext'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Item Icon <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					if($row['iconimg'])
					{
						$tbldata.='<label class="label-view">';
						$tbldata.='<a href="'.$imageurl.$row['iconimg'].'" target="_blank">';
						$tbldata.='<img src="'.$imageurl.$row['iconimg'].'" width="40" height="40">';
						$tbldata.='</a>';
						$tbldata.='</label>';
					}
					$tbldata.='</div></div></div>';


				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';										
				$tbldata.='</div>';


				//Item Short Description
				$tbldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
					$tbldata.='<div class="widget mt-10">';
						$tbldata.='<div class="widget-title  mb-2"><b>Item Short Description</b></div>';
						$tbldata.='<div class="widget-content row">';

							$tbldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
							$tbldata.='<div class="row1">';
							$tbldata.='<div class="table-responsive pt-2">';
							$tbldata.='<div class="col-12 p-0">';
							$tbldata.=$row['shortdescr'];
							$tbldata.='</div>';
							$tbldata.='</div>';
							$tbldata.='</div>';
							$tbldata.='</div>';


						$tbldata.='</div>';
					$tbldata.='</div>';
				$tbldata.='</div>';


				//Item Description
				$tbldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
					$tbldata.='<div class="widget mt-10">';
						$tbldata.='<div class="widget-title  mb-2"><b>Item Description</b></div>';
						$tbldata.='<div class="widget-content row">';

							$tbldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
							$tbldata.='<div class="row1">';
							$tbldata.='<div class="table-responsive pt-2">';
							$tbldata.='<div class="col-12 p-0">';
							$tbldata.=$row['descr'];
							$tbldata.='</div>';
							$tbldata.='</div>';
							$tbldata.='</div>';
							$tbldata.='</div>';


						$tbldata.='</div>';
					$tbldata.='</div>';
				$tbldata.='</div>';
				


				//Website Composite Items
				if($row['iswebattribute'] == 1)
				{
					$qry="select iid.id,iid.rowattributeid,iid.rowattributename,iid.rowwebdisplayname,iid.rowqty,isnull(iim.iconimg,'') as iconimg,iid.rowdisplayorder
						from tblitemdetails iid 
						left join tblitemiconmaster iim on iim.id=iid.rowiconid
						where iid.itemid=:id and iid.iswebsiteattribute=1 order by iid.rowdisplayorder";
					$parms = array(
						':id'=>$id,
					);
					$compitems=$DB->getmenual($qry,$parms);
					if($compitems)
					{
						$tbldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
							$tbldata.='<div class="widget mt-10">';
								$tbldata.='<div class="widget-title  mb-2"><b>Website Display Items</b></div>';
								$tbldata.='<div class="widget-content row">';

									$tbldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
									$tbldata.='<div class="row1">';
									$tbldata.='<div class="table-responsive pt-2">';
									$tbldata.='<div class="col-12 p-0">';
									$tbldata.='<table id="tabviewlevesselmappricing" class="table table-bordered table-hover table-striped">';
									$tbldata.='<thead>';
									$tbldata.='<tr>';
									$tbldata.='<th>Attribute</th>';
									$tbldata.='<th>Website Display Name</th>';
									$tbldata.='<th>Icon</th>';
									$tbldata.='<th>Display Order</th>';
									$tbldata.='</tr>';
									$tbldata.='</thead>';
									$tbldata.='<tbody id="tblviewdataprice">';
									
									$htmldata1='';
									for($i=0;$i<sizeof($compitems);$i++)
									{	
										$subrow=$compitems[$i];
										$tbldata.='<tr>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowattributename']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowwebdisplayname']).'</td>';
										if($subrow['iconimg'])
										{
											$tbldata.='<td><img src="'.$IISMethods->sanitize($imageurl.$subrow['iconimg']).'" width="25" /></td>';
										}
										else
										{
											$tbldata.='<td>-</td>';
										}
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowdisplayorder']).'</td>';
										$tbldata.='</tr>';
									}
									$tbldata.='</tbody>';
									$tbldata.='</table>';
									$tbldata.='</div>';
									$tbldata.='</div>';
									$tbldata.='</div>';
									$tbldata.='</div>';


								$tbldata.='</div>';
							$tbldata.='</div>';
						$tbldata.='</div>';
						
						
						$status=1;
						$message=$errmsg['success'];
					}
				}

				//Website Composite Items
				if($row['iscourse'] == 1)
				{
					$qry="select iid.id,iid.rowwebdisplayname,iid.rowqty,isnull(iim.iconimg,'') as iconimg,iid.rowdisplayorder,iid.rowdurationname
						from tblitemdetails iid 
						left join tblitemiconmaster iim on iim.id=iid.rowiconid
						where iid.itemid=:id and iid.iscourse=1 order by iid.rowdisplayorder";
					$parms = array(
						':id'=>$id,
					);
					$compitems=$DB->getmenual($qry,$parms);
					if($compitems)
					{
						$tbldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
							$tbldata.='<div class="widget mt-10">';
								$tbldata.='<div class="widget-title  mb-2"><b>Courses Benefit</b></div>';
								$tbldata.='<div class="widget-content row">';

									$tbldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
									$tbldata.='<div class="row1">';
									$tbldata.='<div class="table-responsive pt-2">';
									$tbldata.='<div class="col-12 p-0">';
									$tbldata.='<table id="tabviewlevesselmappricing" class="table table-bordered table-hover table-striped">';
									$tbldata.='<thead>';
									$tbldata.='<tr>';
									$tbldata.='<th>Benefit Name</th>';
									$tbldata.='<th>Duration</th>';
									$tbldata.='<th>Icon</th>';
									$tbldata.='<th>Display Order</th>';
									$tbldata.='</tr>';
									$tbldata.='</thead>';
									$tbldata.='<tbody id="tblviewdataprice">';
									
									$htmldata1='';
									for($i=0;$i<sizeof($compitems);$i++)
									{	
										$subrow=$compitems[$i];
										$tbldata.='<tr>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowwebdisplayname']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowdurationname']).'</td>';
										if($subrow['iconimg'])
										{
											$tbldata.='<td><img src="'.$IISMethods->sanitize($imageurl.$subrow['iconimg']).'" width="25" /></td>';
										}
										else
										{
											$tbldata.='<td>-</td>';
										}
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowdisplayorder']).'</td>';
										$tbldata.='</tr>';
									}
									$tbldata.='</tbody>';
									$tbldata.='</table>';
									$tbldata.='</div>';
									$tbldata.='</div>';
									$tbldata.='</div>';
									$tbldata.='</div>';


								$tbldata.='</div>';
							$tbldata.='</div>';
						$tbldata.='</div>';
						
						
						$status=1;
						$message=$errmsg['success'];
					}
				}

				//Composite Items
				if($row['iscompositeitem'] == 1)
				{
					$qry="select iid.id,iid.rowcategory,iid.rowsubcategory,iid.rowitemid,iid.rowitemname,iid.rowwebdisplayname,iid.rowqty,isnull(iim.iconimg,'') as iconimg,
						iid.rowdurationname,iid.rowdurationdays,iid.rowdiscount,iid.rowprice,iid.rowgst,iid.rowgsttype,iid.rowtypestr
						from tblitemdetails iid 
						left join tblitemiconmaster iim on iim.id=iid.rowiconid
						where iid.itemid=:id and iid.iswebsiteattribute=0 and iid.iscourse = 0 order by iid.timestamp";
					$parms = array(
						':id'=>$id,
					);
					$compitems=$DB->getmenual($qry,$parms);
					if($compitems)
					{
						$tbldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
							$tbldata.='<div class="widget mt-10">';
								$tbldata.='<div class="widget-title  mb-2"><b>Composite Items</b></div>';
								$tbldata.='<div class="widget-content row">';

									$tbldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
									$tbldata.='<div class="row1">';
									$tbldata.='<div class="table-responsive pt-2">';
									$tbldata.='<div class="col-12 p-0">';
									$tbldata.='<table id="tabviewlevesselmappricing" class="table table-bordered table-hover table-striped">';
									$tbldata.='<thead>';
									$tbldata.='<tr>';
									$tbldata.='<th>Category</th>';
									$tbldata.='<th>Sub Category</th>';
									$tbldata.='<th>Item</th>';
									$tbldata.='<th>Type</th>';
									$tbldata.='<th>Qty</th>';
									$tbldata.='<th>Discount</th>';
									$tbldata.='<th>Duration</th>';
									$tbldata.='<th>Price</th>';
									$tbldata.='<th>Tax Type</th>';
									$tbldata.='<th>Tax</th>';
									// $tbldata.='<th>Icon</th>';
									
									$tbldata.='</tr>';
									$tbldata.='</thead>';
									$tbldata.='<tbody id="tblviewdataprice">';
									
									$htmldata1='';
									for($i=0;$i<sizeof($compitems);$i++)
									{	
										$subrow=$compitems[$i];
										$tbldata.='<tr>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowcategory']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowsubcategory']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowitemname']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowtypestr']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowqty']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowdiscount']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowdurationname']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowprice']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowgsttype']).'</td>';
										$tbldata.='<td>'.$IISMethods->sanitize($subrow['rowgst']).'</td>';
										
										$tbldata.='</tr>';
									}
									$tbldata.='</tbody>';
									$tbldata.='</table>';
									$tbldata.='</div>';
									$tbldata.='</div>';
									$tbldata.='</div>';
									$tbldata.='</div>';


								$tbldata.='</div>';
							$tbldata.='</div>';
						$tbldata.='</div>';
						
						
						$status=1;
						$message=$errmsg['success'];
					}
				}

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
	//Change Item Status
	else if($action=='changeitemstatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select isactive from tblitemmaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];
				if($row['isactive']==1)
				{
					$isactive=0;
				}
				else
				{
					$isactive=1;
				}
				$insqry['[isactive]']=$isactive;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tblitemmaster',$insqry,$extraparams);
			
			
				$status=1;
				$message=$errmsg['update'];

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
			$message=$errmsg['reqired'];
		}
	}
	//Change Item Status  (Show On Home)
	else if($action=='changeshowonhomestatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select showonhome from tblitemmaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];
				if($row['showonhome']==1)
				{
					$showonhome=0;
				}
				else
				{
					$showonhome=1;
				}
				$insqry['[showonhome]']=$showonhome;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tblitemmaster',$insqry,$extraparams);
			
			
				$status=1;
				$message=$errmsg['update'];

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
			$message=$errmsg['reqired'];
		}
	}
	//Change Item Status  (Show On Web)
	else if($action=='changeshowonwebstatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select showonweb from tblitemmaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];
				if($row['showonweb']==1)
				{
					$showonweb=0;
				}
				else
				{
					$showonweb=1;
				}
				$insqry['[showonweb]']=$showonweb;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tblitemmaster',$insqry,$extraparams);
			
			
				$status=1;
				$message=$errmsg['update'];

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
			$message=$errmsg['reqired'];
		}
	}
	//Change Item Status  (Show On POS)
	else if($action=='changeshowonposstatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select showonpos from tblitemmaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];
				if($row['showonpos']==1)
				{
					$showonpos=0;
				}
				else
				{
					$showonpos=1;
				}
				$insqry['[showonpos]']=$showonpos;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tblitemmaster',$insqry,$extraparams);
			
			
				$status=1;
				$message=$errmsg['update'];

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
			$message=$errmsg['reqired'];
		}
	}
	//Insert Item Images
	else if($action=='insertitemimages')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$itemimg=$_FILES['itemimg']['name'];
		$itemid=$IISMethods->sanitize($_POST['itemid']);

		if($itemid && sizeof($itemimg))
		{
			try 
			{
				$DB->begintransaction();

				if(is_array($_FILES)) 
				{
					$isupdimg = 0;
					foreach ($_FILES['itemimg']['name'] as $name => $value)
					{
						if($_FILES['itemimg']['type'][$name] == 'image/jpg'  || $_FILES['itemimg']['type'][$name] == 'image/jpeg' || $_FILES['itemimg']['type'][$name] == 'image/png')
						{
							
							$unqid = $IISMethods->generateuuid();
							$sourcePath = $_FILES['itemimg']['tmp_name'][$name];
							$targetPath = $IISMethods->uploadallfiles(1,'item',$_FILES['itemimg']['name'][$name],$sourcePath,$_FILES['itemimg']['type'][$name],$unqid);

							$insdata = array(
								'[id]'=>$unqid,
								'[itemid]'=>$itemid,
								'[image]'=>$targetPath,
								'[displayorder]'=>0,
								'[timestamp]'=>$IISMethods->getdatetime(),
								'[entry_uid]'=>$uid,	
								'[entry_date]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tblitemimage',$insdata,'');
							$isupdimg=1;

						}
					}
				}

				if($isupdimg==1)
				{
					$status=1;
					$message=$errmsg['insert'];
					$response['itemid']= $itemid;
				}
				else
				{
					$status=0;
					$message=$errmsg['reqired'];
				}

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
	//Get Uploaded Item Image Details
	else if($action == 'getitemimages')
	{
		$itemid=$IISMethods->sanitize($_POST['itemid']);
		if($itemid)
		{
			$qrysub="select id,itemid,image,isnull(displayorder,0) as displayorder  from tblitemimage where itemid=:itemid";
			$parms = array(
				':itemid'=>$itemid,
			);
			$result_subary=$DB->getmenual($qrysub,$parms);
			if(sizeof($result_subary)>0)
			{	
				for($i=0;$i<sizeof($result_subary);$i++)
				{
					$subrow=$result_subary[$i];
					$response['result'][$i]['id']=$IISMethods->sanitize($subrow['id']);
					$response['result'][$i]['itemid']=$IISMethods->sanitize($subrow['itemid']);
					$response['result'][$i]['displayorder']=$IISMethods->sanitize($subrow['displayorder']);
					$response['result'][$i]['imgpath']=$IISMethods->sanitize($config->getImageurl().$subrow['image']);
				}
				
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
	//Remove Item Image
	else if($action == 'removeitemimage')
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$itemid=$IISMethods->sanitize($_POST['itemid']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qryimg="select id,image from tblitemimage where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$resimg=$DB->getmenual($qryimg,$parms);
				$row=$resimg[0];
				if($row['image'])
				{
					unlink($config->getImageurl().$row['image']);
				}
				
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('d','tblitemimage','',$extraparams);


				$response['itemid']= $itemid;
				$status=1;
				$message=$errmsg['delete'];

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
	//Change Item Image Display Order
	else if($action == 'changeitemimgdiplayorder')
	{
		$id=$IISMethods->sanitize($_POST['imgid']);
		$displayorder=$IISMethods->sanitize($_POST['displayorder']);
		$itemid=$IISMethods->sanitize($_POST['itemid']);

		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$updqry=array(
					'[displayorder]'=>$displayorder,
				);
				
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tblitemimage',$updqry,$extraparams);

				$response['itemid']= $itemid;

				$status=1;
				$message=$errmsg['update'];

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

  