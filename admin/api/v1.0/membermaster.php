<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\membermaster.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();

	if($action=='insertmembermaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$firstname=$IISMethods->sanitize($_POST['firstname']);
		$middlename=$IISMethods->sanitize($_POST['middlename']);
		$lastname=$IISMethods->sanitize($_POST['lastname']);
		$emailid=$IISMethods->sanitize($_POST['emailid']);
		$mobilenumber=$IISMethods->sanitize($_POST['mobilenumber']);
		$qataridno=$IISMethods->sanitize($_POST['qataridno']);
		$qataridexpiry=$IISMethods->sanitize($_POST['qataridexpiry']);
		$passportidno=$IISMethods->sanitize($_POST['passportidno']);
		$passportidexpiry=$IISMethods->sanitize($_POST['passportidexpiry']);
		$dob=$IISMethods->sanitize($_POST['dob']);
		$nationality=$IISMethods->sanitize($_POST['nationality']);
		$address=$IISMethods->sanitize($_POST['address']);
		$company=$IISMethods->sanitize($_POST['company']);
		$password=$IISMethods->sanitize($_POST['password']);
		$confirmpassword=$IISMethods->sanitize($_POST['confirmpassword']);


		$qataridproof=$_FILES['qataridproof']['name'];
		$passportproof=$_FILES['passportproof']['name'];
		$othergovernmentproof=$_FILES['othergovernmentproof']['name'];

		$id=$IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();
		$unqid = $IISMethods->generateuuid();

		if($firstname && $lastname && $emailid && $mobilenumber && $dob && $nationality && (($qataridproof && $passportproof) || $formevent=='editright'))
		{
			if(($qataridno && $qataridexpiry) || ($passportidno && $passportidexpiry))
			{
				$personname=$firstname.' '.$lastname;

				$insqry=array(
					'[personname]'=>$personname,	
					'[firstname]'=>$firstname,
					'[middlename]'=>$middlename,
					'[lastname]'=>$lastname,	
					'[contact]'=>$mobilenumber,		
					'[email]'=>$emailid,	
					'[username]'=>$emailid,	
					'[qataridno]'=>$qataridno,
					'[qataridexpiry]'=>$qataridexpiry,
					'[passportidno]'=>$passportidno,
					'[passportidexpiry]'=>$passportidexpiry,
					'[address]'=>$address,	
					'[dob]'=>$dob,
					'[nationality]'=>$nationality,
					'[companyname]'=>$company,
				);
				

				if($formevent=='addright')
				{
					$qrychk="SELECT personname,isnull(isverified,0) as isverified from tblpersonmaster where email=:email AND isnull(isdelete,0)=0 ";
					$parms = array(
						':email'=>$emailid,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					if(sizeof($result_ary) > 0)
					{
						$status=0;
						$message=$errmsg['emailexist'];
					}
					else
					{
						$qrychk="SELECT personname,isnull(isverified,0) as isverified from tblpersonmaster where contact=:contact AND isnull(isdelete,0)=0 ";
						$parms = array(
							':contact'=>$mobilenumber,
						);
						$result_ary=$DB->getmenual($qrychk,$parms);
						if(sizeof($result_ary) > 0)
						{
							$status=0;
							$message=$errmsg['mobileexist'];	
						}
						else
						{
							try 
							{
								$DB->begintransaction();

								if($qataridproof && ($_FILES["qataridproof"]["type"] == "image/jpeg" || $_FILES["qataridproof"]["type"] == "image/jpg" || $_FILES["qataridproof"]["type"] == "image/png" || $_FILES["qataridproof"]["type"] == "application/pdf"))
								{
									if($passportproof && ($_FILES["passportproof"]["type"] == "image/jpeg" || $_FILES["passportproof"]["type"] == "image/jpg" || $_FILES["passportproof"]["type"] == "image/png" || $_FILES["passportproof"]["type"] == "application/pdf"))
									{
										if(($othergovernmentproof && ($_FILES["othergovernmentproof"]["type"] == "image/jpeg" || $_FILES["othergovernmentproof"]["type"] == "image/jpg" || $_FILES["othergovernmentproof"]["type"] == "image/png" || $_FILES["othergovernmentproof"]["type"] == "application/pdf")) || !$othergovernmentproof)
										{

											$imagePath_qataridproof='';
											if($qataridproof)
											{
												$sourcePath_qataridproof = $_FILES['qataridproof']['tmp_name'];
												$imagePath_qataridproof = $IISMethods->uploadallfiles(1,'memberproof',$qataridproof,$sourcePath_qataridproof,$_FILES['qataridproof']['type'],$unqid.'1');
											}

											$imagePath_passportproof='';
											if($passportproof)
											{
												$sourcePath_passportproof = $_FILES['passportproof']['tmp_name'];
												$imagePath_passportproof = $IISMethods->uploadallfiles(1,'memberproof',$passportproof,$sourcePath_passportproof,$_FILES['passportproof']['type'],$unqid.'2');
											}

											$imagePath_othergovernmentproof='';
											if($othergovernmentproof)
											{
												$sourcePath_othergovernmentproof = $_FILES['othergovernmentproof']['tmp_name'];
												$imagePath_othergovernmentproof = $IISMethods->uploadallfiles(1,'memberproof',$othergovernmentproof,$sourcePath_othergovernmentproof,$_FILES['othergovernmentproof']['type'],$unqid.'3');
											}
																							
											$insqry['[id]']=$unqid;	
											$insqry['[qataridproof]']=$imagePath_qataridproof;	
											$insqry['[passportproof]']=$imagePath_passportproof;	
											$insqry['[othergovernmentproof]']=$imagePath_othergovernmentproof;	
											$insqry['[password]']=md5($password);	
											$insqry['[strpassword]']=$confirmpassword;
											$insqry['[isnormal]']=1;
											$insqry['[regtype]']='n';
											$insqry['[webid]']='';	
											$insqry['[refmemberid]']=$mssqldefval['uniqueidentifier'];
											$insqry['[platform]']=$platform;	
											$insqry['[isactive]']=1;
											$insqry['[isverified]']=1;
											$insqry['[iscontactverified]']=1;
											$insqry['[isemailverified]']=1;
											$insqry['[timestamp]']=$datetime;
											$insqry['[entry_uid]']=$uid;	
											$insqry['[entry_date]']=$datetime;	
					
											$DB->executedata('i','tblpersonmaster',$insqry,'');
					
					
											//Insert User Type
											$qrychk="SELECT usertype from tblusertypemaster where id=:id";
											$parms = array(
												':id'=>$config->getMemberutype(),
											);
											$result_utype=$DB->getmenual($qrychk,$parms);
											$utid = $IISMethods->generateuuid();
											$insperutype=array(	
												'[id]'=>$utid,				
												'[pid]'=>$unqid,
												'[utypeid]'=>$config->getMemberutype(),
												'[userrole]'=>$result_utype[0]['usertype'],
												'[entry_date]'=>$datetime,
											);
											$DB->executedata('i','tblpersonutype',$insperutype,'');

											if($config->getIsAccessSAP() == 1)
											{
												//Insert Member Data in SAP (HaNa DB)
												$DB->SAPInsertMemberData($SubDB,$unqid);
											}
					
					
											$status = 1;
											$message=$errmsg['insert'];
										}	
										else
										{
											$status=0;
											$message=$errmsg['governmentfiletype'];
										}	
									}	
									else
									{
										$status=0;
										$message=$errmsg['passportfiletype'];
									}		
								}	
								else
								{
									$status=0;
									$message=$errmsg['qataridfiletype'];
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
					}
				}			
				else if($formevent=='editright')
				{
					$qrychk="SELECT personname from tblpersonmaster where contact=:contact AND ISNULL(isdelete,0)=0 and id <> :id";
					$parms = array(
						':contact'=>$mobilenumber,
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					if(sizeof($result_ary) > 0)
					{
						$status=0;
						$message=$errmsg['mobileexist'];
					}
					else
					{
						$qrychk="SELECT personname from tblpersonmaster where email=:email AND ISNULL(isdelete,0)=0 and id <> :id";
						$parms = array(
							':email'=>$emailid,
							':id'=>$id,
						);
						$result_ary=$DB->getmenual($qrychk,$parms);
						if(sizeof($result_ary) > 0)
						{
							$status=0;
							$message=$errmsg['emailexist'];
						}
						else
						{
							try 
							{
								$DB->begintransaction();

								if(($qataridproof && ($_FILES["qataridproof"]["type"] == "image/jpeg" || $_FILES["qataridproof"]["type"] == "image/jpg" || $_FILES["qataridproof"]["type"] == "image/png" || $_FILES["qataridproof"]["type"] == "application/pdf")) || !$qataridproof)
								{
									if(($passportproof && ($_FILES["passportproof"]["type"] == "image/jpeg" || $_FILES["passportproof"]["type"] == "image/jpg" || $_FILES["passportproof"]["type"] == "image/png" || $_FILES["passportproof"]["type"] == "application/pdf")) || !$passportproof)
									{
										if(($othergovernmentproof && ($_FILES["othergovernmentproof"]["type"] == "image/jpeg" || $_FILES["othergovernmentproof"]["type"] == "image/jpg" || $_FILES["othergovernmentproof"]["type"] == "image/png" || $_FILES["othergovernmentproof"]["type"] == "application/pdf")) || !$othergovernmentproof)
										{

											$qryimg="select id,qataridproof,passportproof,othergovernmentproof from tblpersonmaster where id=:id";
											$parms = array(
												':id'=>$id,
											);
											$resimg=$DB->getmenual($qryimg,$parms);
											$row=$resimg[0];


											
											if($qataridproof)
											{
												unlink($config->getImageurl().$row['qataridproof']);
												$sourcePath_qataridproof = $_FILES['qataridproof']['tmp_name'];
												$imagePath_qataridproof = $IISMethods->uploadallfiles(1,'memberproof',$qataridproof,$sourcePath_qataridproof,$_FILES['qataridproof']['type'],$unqid.'1');
											}
											else
											{
												$imagePath_qataridproof=$row['qataridproof'];
											}

											
											if($passportproof)
											{
												unlink($config->getImageurl().$row['passportproof']);
												$sourcePath_passportproof = $_FILES['passportproof']['tmp_name'];
												$imagePath_passportproof = $IISMethods->uploadallfiles(1,'memberproof',$passportproof,$sourcePath_passportproof,$_FILES['passportproof']['type'],$unqid.'2');
											}
											else
											{
												$imagePath_passportproof=$row['passportproof'];
											}

											
											if($othergovernmentproof)
											{
												unlink($config->getImageurl().$row['othergovernmentproof']);
												$sourcePath_othergovernmentproof = $_FILES['othergovernmentproof']['tmp_name'];
												$imagePath_othergovernmentproof = $IISMethods->uploadallfiles(1,'memberproof',$othergovernmentproof,$sourcePath_othergovernmentproof,$_FILES['othergovernmentproof']['type'],$unqid.'3');
											}
											else
											{
												$imagePath_othergovernmentproof=$row['othergovernmentproof'];
											}
										
											$insqry['[qataridproof]']=$imagePath_qataridproof;	
											$insqry['[passportproof]']=$imagePath_passportproof;	
											$insqry['[othergovernmentproof]']=$imagePath_othergovernmentproof;	
											$insqry['[update_uid]']=$uid;	
											$insqry['[update_date]']=$datetime;

											$extraparams=array(
												'id'=>$id
											);
											$DB->executedata('u','tblpersonmaster',$insqry,$extraparams);

											if($config->getIsAccessSAP() == 1)
											{
												//Insert Member Data in SAP (HaNa DB)
												$DB->SAPInsertMemberData($SubDB,$id);
											}
											
											$status=1;
											$message=$errmsg['update'];
										}	
										else
										{
											$status=0;
											$message=$errmsg['governmentfiletype'];
										}
									}	
									else
									{
										$status=0;
										$message=$errmsg['passportfiletype'];
									}		
								}
								else
								{
									$status=0;
									$message=$errmsg['qataridfiletype'];
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

					}	
				}
			}	
			else
			{
				$status=0;
				$message=$errmsg['noqatarpassportdata'];
			}
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deletemembermaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		$datetime=$IISMethods->getdatetime();

		$insqry=array(					
			'isdelete'=>1,
			'delete_uid'=>$uid,	
			'delete_date'=>$datetime,					
		);
		if($id)
		{
			$qrychk="SELECT case when(o.transactionid !='' or so.transactionid !='') then 1 else 0 end as tem
			from tblpersonmaster pm
			left join tblorder o on o.uid = pm.id
			left join tblserviceorder so on so.uid = pm.id
			where pm.id=:id";
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
					$DB->executedata('u','tblpersonmaster',$insqry,$extraparams);


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

					$qrychk="SELECT case when(o.transactionid !='' or so.transactionid !='') then 1 else 0 end as tem,pm.personname
					from tblpersonmaster pm
					left join tblorder o on o.uid = pm.id
					left join tblserviceorder so on so.uid = pm.id
					where pm.id=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['personname'].",";
					}
					else
					{
						$extraparams=array(
							'[id]'=>$id
						);
						$DB->executedata('u','tblpersonmaster',$insqry,$extraparams);
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
	else if($action=='editmembermaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="SELECT pm.*,
				ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), pu.utypeid) AS [text()] FROM tblpersonutype pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,10000),'') as utypeid
				FROM tblpersonmaster pm 
				WHERE pm.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['firstname']=$IISMethods->sanitize($row['firstname']);
				$response['middlename']=$IISMethods->sanitize($row['middlename']);
				$response['lastname']=$IISMethods->sanitize($row['lastname']);
				$response['mobilenumber']=$IISMethods->sanitize($row['contact']);
				$response['email']=$IISMethods->sanitize($row['email']);
				$response['qataridno']=$IISMethods->sanitize($row['qataridno']);
				$response['qataridexpiry']=$IISMethods->sanitize($row['qataridexpiry']);
				$response['passportidno']=$IISMethods->sanitize($row['passportidno']);
				$response['passportidexpiry']=$IISMethods->sanitize($row['passportidexpiry']);
				$response['dob']=$IISMethods->sanitize($row['dob']);
				$response['nationality']=$IISMethods->sanitize($row['nationality']);
				$response['address']=$IISMethods->sanitize($row['address']);
				$response['company']=$IISMethods->sanitize($row['companyname']);
			

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
	else if($action=='listmembermaster')   
	{
		$membermasters=new membermaster();
		$qry="SELECT distinct pm.timestamp as primary_date,pm.id,pm.personname,pm.contact,pm.email,pm.isactive,pm.username,pm.strpassword,pm.isnormal,pm.regtype,
			pu.userrole,convert(varchar,pm.entry_date,100) as entrydate,
			CASE WHEN(pm.isverified = 1)THEN 'Verified' ElSE 'Pending' END as verified,pm.isverified,
			CASE WHEN(isnull(pm.qataridproof,'')<>'' and isnull(pm.passportproof,'')<>'') THEN 1 ElSE 0 END as isdocverified
			from tblpersonmaster pm 
			inner join tblpersonutype pu on pu.pid = pm.id
			where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND (pu.utypeid=:memberutypeid or pu.utypeid=:guestutypeid) and 
			(pm.personname like :personfilter or pm.contact like :contactfilter or pm.email like :emailfilter )  
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";	
		$parms = array(
			':memberutypeid'=>$config->getMemberutype(),
			':guestutypeid'=>$config->getGuestutype(),
			':adminuid'=>$config->getAdminUserId(),
			':personfilter'=>'%'.$filter.'%',
			':contactfilter'=>'%'.$filter.'%',
			':emailfilter'=>'%'.$filter.'%',
			
		);
		//echo $qry;
		//print_r($parms);
		$membermasters=$DB->getmenual($qry,$parms,'membermaster');
		
		if($responsetype=='HTML')
		{
			if($membermasters)
			{
				$i=0;
				foreach($membermasters as $membermaster)
				{
					$id="'".$membermaster->id."'";
					$username="'".$membermaster->username."'";
					$password="'".$membermaster->strpassword."'";

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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($membermaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					
					
					$htmldata.='<td>';
					if($membermaster->regtype == 'f')    //Facebook ID
					{
						$htmldata.='<i class="bi bi-facebook" data-toggle="tooltip" data-placement="top" data-original-title="Signup Via Facebook" style="font-size: 15px;"></i>';
					}
					else if($membermaster->regtype == 'g')  //Google ID
					{
						$htmldata.='<i class="bi bi-google" data-toggle="tooltip" data-placement="top" data-original-title="Signup Via Google" style="font-size: 15px;"></i>';
					}
					else if($membermaster->regtype == 'a')  //Apple ID
					{
						$htmldata.='<i class="bi bi-apple" data-toggle="tooltip" data-placement="top" data-original-title="Signup Via Apple ID" style="font-size: 15px;"></i>';
					}
					$htmldata.='</td>';
					$htmldata.='<td class="">'.$IISMethods->sanitize($membermaster->userrole,'OUT').'</td>';
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Member Details" onclick="viewmemberdetaildata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($membermaster->personname,'OUT').'</a></td>';
					$htmldata.='<td class="">'.$IISMethods->sanitize($membermaster->email).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($membermaster->contact).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($membermaster->entrydate).'</td>';
					if($membermaster->isverified == 1)
					{
						$htmldata.='<td class="tbl-name text-center"><span class="btn btn-success px-2 py-0">'.$IISMethods->sanitize($membermaster->verified).'</span></td>';
					}
					else
					{
						$htmldata.='<td class="tbl-name text-center"><span class="btn btn-warning px-2 py-0">'.$IISMethods->sanitize($membermaster->verified).'</span></td>';
					}

					if($membermaster->isdocverified == 1)
					{
						$htmldata.='<td class="tbl-name text-center"><span class="btn btn-success px-2 py-0">Uploaded</span></td>';
					}
					else
					{
						$htmldata.='<td class="tbl-name text-center"><span class="btn btn-warning px-2 py-0">Pending</span></td>';
					}
					
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-name text-center">';
						if($membermaster->isnormal == 1)
						{
							$htmldata.='<a href="javascript:void(0)" onclick="resetpassword('.$id.','.$IISMethods->sanitize($username).','.$IISMethods->sanitize($password).')" data-toggle="tooltip" data-title="Reset Password"><i class="bi bi-arrow-repeat" style="font-size: 20px;"></i></a>';
						}
						$htmldata.='</td>';

						if($membermaster->isactive==1)
						{
							$htmldata.='<td class="tbl-name text-center"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changememberstatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name text-center"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changememberstatus('.$id.')">';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}


						$htmldata.='<td class="tbl-name text-center">';
						if($membermaster->isverified == 0)
						{
							$htmldata.='<a href="javascript:void(0)" class="btn-tbl text-success rounded-circle" data-toggle="tooltip" data-placement="top" data-original-title="Verify Member" onclick="memberverifedstatus('.$IISMethods->sanitize($id).')"><i class="bi bi-check-lg"></i></a>';
						}
						$htmldata.='</td>';


						$htmldata.='<td class="tbl-name text-center">';
						$htmldata.='<a href="javascript:void(0)" class="btn-tbl text-primary rounded-circle" data-toggle="tooltip" data-placement="top" data-original-title="Freeze Membership" onclick="membershipfreeze('.$IISMethods->sanitize($id).')"><i class="bi bi-person-x-fill" style="font-size: 20px;"></i></a>';
						$htmldata.='</td>';
						
					}
					
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($membermasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($membermasters);
		}

		$common_listdata=$membermasters;
		
	} 
	//Fill To Category
	// else if($action == 'fillcategory')
	// {
	// 	$selectoption=$IISMethods->sanitize($_POST['selectoption']);
	// 	$qry="select c.* from tblcategory c order by (case when (c.displayorder>0) then c.displayorder else 99999 end)";
	// 	$parms = array(
	// 	);
	// 	$result_ary=$DB->getmenual($qry,$parms);
	// 	$htmldata='';
	// 	if($selectoption == 1)
	// 	{
	// 		$htmldata.='<option value="">Select Category</option>';
	// 	}

	// 	if(sizeof($result_ary)>0)
	// 	{
	// 		for($i=0;$i<sizeof($result_ary);$i++)
	// 		{
	// 			$row=$result_ary[$i];
	// 			$htmldata.='<option value="'.$row['id'].'">'.$row['category'].'</option>';
	// 		}
	// 	}
	// 	$response['data']=$htmldata;

	// 	$status=1;
	// 	$message=$errmsg['success'];
	// }
	else if($action=='changememberstatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select isactive from tblpersonmaster where id=:id";
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
				$DB->executedata('u','tblpersonmaster',$insqry,$extraparams);
			
			
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
	else if($action=='changepassword')   
	{
		$userid=$IISMethods->sanitize($_POST['userid']);
		$npass=$IISMethods->sanitize($_POST['npass']);
		if($userid && $npass)
		{
			try 
			{
				$DB->begintransaction();

				$insqry=array(	
					'[password]'=>md5($npass),				
					'[strpassword]'=>$npass,
				);

				$extraparams=array(
					'[id]'=>$userid
				);
				$DB->executedata('u','tblpersonmaster',$insqry,$extraparams);
			
				$status=1;
				$message=$errmsg['passreset'];

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
	else if($action=='viewmemberdata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="SELECT DISTINCT pm.id,pm.sappersonid,pm.companyname,pm.firstname,pm.middlename,pm.lastname,pm.personname,pm.contact,pm.email,pm.address,pm.qataridno,pm.qataridexpiry,pm.passportidno,pm.passportidexpiry,pm.dob,pm.nationality,pm.username,pm1.personname as ref_personname,pm1.contact as ref_personcontact,
			isnull(pm.profileimg,'') as profileimg,isnull(pm.qataridproof,'') as qataridproof,isnull(pm.passportproof,'') as passportproof,isnull(pm.othergovernmentproof,'') as othergovernmentproof,
			isnull((SELECT SUBSTRING((select ','+CONVERT(VARCHAR(255), ut.usertype) AS [text()] from tblpersonutype pu inner join tblusertypemaster ut on ut.id=pu.utypeid where pu.pid=pm.id FOR XML PATH ('')),2,1000000)),'') as usertype
			from tblpersonmaster pm 
			left join tblpersonmaster pm1 on pm1.id=pm.refmemberid 
			where pm.id=:id";
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

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>User Type <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['usertype'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>First Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['firstname'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Middle Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['middlename'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Last Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['lastname'].'</b></label>';
					$tbldata.='</div></div></div>';

					// $tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					// $tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					// $tbldata.='<label>Member Name <span>:</span></label>';
					// $tbldata.='</div>';
					// $tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					// $tbldata.='<label class="label-view"><b>'.$row['personname'].'</b></label>';
					// $tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Email <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['email'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Mobile Number <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['contact'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Qatar ID Number <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['qataridno'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Qatar ID Expiry <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['qataridexpiry'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Passport ID Number <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['passportidno'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Passport ID Expiry <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['passportidexpiry'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Date Of Birth <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['dob'].'</b></label>';
					$tbldata.='</div></div></div>';
					
					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Nationality <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['nationality'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Address <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['address'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Company Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['companyname'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Profile Image <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					if($row['profileimg'])
					{
						$tbldata.='<label class="label-view">';
						$tbldata.='<a href="'.$imageurl.$row['profileimg'].'" target="_blank">';
						$tbldata.='<img src="'.$imageurl.$row['profileimg'].'" width="100" height="100">';
						$tbldata.='</a>';
						$tbldata.='</label>';
					}
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Proof Of Qatar ID <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					if($row['qataridproof'])
					{
						$tbldata.='<label class="label-view">';
						$tbldata.='<a href="'.$imageurl.$row['qataridproof'].'" target="_blank">';
						$qatarexploded = explode('.', $row['qataridproof']);
						$qatarext=end($qatarexploded);
						if($qatarext == 'pdf')
						{
							$tbldata.='<img src="'.$imageurl.$config->getFilePdfImg().'" width="200" height="100">';
						}
						else
						{
							$tbldata.='<img src="'.$imageurl.$row['qataridproof'].'" width="200" height="100">';
						}
						//$tbldata.='<img src="'.$imageurl.$row['qataridproof'].'" width="200" height="100">';
						$tbldata.='</a>';
						$tbldata.='</label>';
					}	
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Proof Of Passport <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					if($row['passportproof'])
					{
						$tbldata.='<label class="label-view">';
						$tbldata.='<a href="'.$imageurl.$row['passportproof'].'" target="_blank">';
						$passportexploded = explode('.', $row['passportproof']);
						$passportext=end($passportexploded);
						if($passportext == 'pdf')
						{
							$tbldata.='<img src="'.$imageurl.$config->getFilePdfImg().'" width="200" height="100">';
						}
						else
						{
							$tbldata.='<img src="'.$imageurl.$row['passportproof'].'" width="200" height="100">';
						}
						//$tbldata.='<img src="'.$imageurl.$row['passportproof'].'" width="200" height="100">';
						$tbldata.='</a>';
						$tbldata.='</label>';
					}
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Other Government Valid Proof <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					if($row['othergovernmentproof'])
					{
						$tbldata.='<label class="label-view">';
						$tbldata.='<a href="'.$imageurl.$row['othergovernmentproof'].'" target="_blank">';
						$othergovernmentexploded = explode('.', $row['othergovernmentproof']);
						$othergovernmentext=end($othergovernmentexploded);
						if($othergovernmentext == 'pdf')
						{
							$tbldata.='<img src="'.$imageurl.$config->getFilePdfImg().'" width="200" height="100">';
						}
						else
						{
							$tbldata.='<img src="'.$imageurl.$row['othergovernmentproof'].'" width="200" height="100">';
						}

						//$tbldata.='<img src="'.$imageurl.$row['othergovernmentproof'].'" width="200" height="100">';
						$tbldata.='</a>';
						$tbldata.='</label>';
					}	
					$tbldata.='</div></div></div>';


					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Refrence BY <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					if($row['ref_personname'])
					{
						$tbldata.='<label class="label-view"><b>'.$row['ref_personname'].' ('.$row['ref_personcontact'].')</b></label>';
					}
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>SAP Member <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['sappersonid'].'</b></label>';
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
	else if($action == 'memberverifedstatus')
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select isverified from tblpersonmaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary)>0)
				{
					$insqry = array(
						'[isverified]'=>1,
						'[verified_date]'=>$IISMethods->getdatetime(),
						'[verified_uid]'=>$uid
					);
					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('u','tblpersonmaster',$insqry,$extraparams);

					if($config->getIsAccessSAP() == 1)
					{
						//Insert Member Data in SAP (HaNa DB)
						$DB->SAPInsertMemberData($SubDB,$id);
					}

					//Send User Approval Credential Email
					$DB->userapprovallogincredentialemailssms(1,$id);   //type 1-Email,2-SMS

					//Send User Approval Credential SMS
					$DB->userapprovallogincredentialemailssms(2,$id);   //type 1-Email,2-SMS

					$status=1;
					$message=$errmsg['update'];
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
			$message=$errmsg['reqired'];
			$status=0;
		}
	}


}



require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  