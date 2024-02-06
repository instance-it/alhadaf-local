<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\employeemaster.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertemployeemaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$utypeid=$_POST['utypeid'];
		$storeid = $_POST['storeid'];
		$empname=$IISMethods->sanitize($_POST['empname']);
		$mobilenumber1=$IISMethods->sanitize($_POST['mobilenumber1']);
		$mobilenumber2=$IISMethods->sanitize($_POST['mobilenumber2']);
		$emailid=$IISMethods->sanitize($_POST['emailid']);
		$address=$IISMethods->sanitize($_POST['address']);
		$stateid=$IISMethods->sanitize($_POST['stateid']);
		$cityid=$IISMethods->sanitize($_POST['cityid']);
	
		$username=$IISMethods->sanitize($_POST['username']);
		$password=$IISMethods->sanitize($_POST['password']);
		$cpassword=$IISMethods->sanitize($_POST['cpassword']);

		if($username == '' || $username == null)
		{
			$username=$mobilenumber1;
		}

		if($password == '' || $password == null)
		{
			$password=$mobilenumber1;
		}



		$id=$IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();

		if(sizeof($storeid) > 0 && sizeof($utypeid) > 0 && $empname && $mobilenumber1 && $emailid && $address && $stateid && $cityid )
		{
			$qtyname="SELECT 
			ISNULL((select state from tblstatemaster where id=:stateid),'') as state,
			ISNULL((select city from tblcitymaster where id=:cityid),'') as city";
			$parms = array(
				':stateid'=>$stateid,
				':cityid'=>$cityid,
			);
			$result_name=$DB->getmenual($qtyname,$parms);
			
			
			$insqry=array(	
				'[personname]'=>$empname,
				'[contact]'=>$mobilenumber1,
				'[mobilenumber2]'=>$mobilenumber2,
				'[email]'=>$emailid,
				'[address]'=>$address,
				'[stateid]'=>$stateid,
				'[statename]'=>$result_name[0]['state'],
				'[cityid]'=>$cityid,
				'[cityname]'=>$result_name[0]['city'],
				'[username]'=>$username,	
			);

			if($formevent=='addright')
			{
				$unqid = $IISMethods->generateuuid();

				$insqry['[password]']=md5($password);
				$insqry['[strpassword]']=$password;
				$insqry['[isverified]']=1;

				$qrychk="SELECT personname from tblpersonmaster where contact=:mobilenumber1 AND ISNULL(isdelete,0)=0 AND ISNULL(isverified,0)=1";
				$parms = array(
					':mobilenumber1'=>$mobilenumber1,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					$status=0;
					$message=$errmsg['mobileexist'];
				}
				else
				{
					$qrychk="SELECT personname from tblpersonmaster where email=:emailid AND ISNULL(isdelete,0)=0 AND ISNULL(isverified,0)=1";
					$parms = array(
						':emailid'=>$emailid,
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

							$unqid = $IISMethods->generateuuid();
							$insqry['[id]']=$unqid;	
							$insqry['[timestamp]']=$datetime;	
							$insqry['[entry_uid]']=$uid;	
							$insqry['[entry_date]']=$datetime;

							$DB->executedata('i','tblpersonmaster',$insqry,'');

							//Insert Person Utype
							for($i=0;$i<sizeof($utypeid);$i++)
							{
								$qrychk="SELECT usertype from tblusertypemaster where id=:id";
								$parms = array(
									':id'=>$utypeid[$i],
								);
								$result_utype=$DB->getmenual($qrychk,$parms);
								$utid = $IISMethods->generateuuid();
								$insperutype=array(	
									'[id]'=>$utid,				
									'[pid]'=>$unqid,
									'[utypeid]'=>$utypeid[$i],
									'[userrole]'=>$result_utype[0]['usertype'],
									'[entry_date]'=>$datetime,
								);
								$DB->executedata('i','tblpersonutype',$insperutype,'');
							}

							for($i=0;$i<sizeof($storeid);$i++)
							{
								$qrychk="SELECT storename from tblstoremaster where id=:id";
								$parms = array(
									':id'=>$storeid[$i],
								);
								$result_store=$DB->getmenual($qrychk,$parms);
								$utid = $IISMethods->generateuuid();
								$insperstore=array(	
									'[id]'=>$utid,				
									'[pid]'=>$unqid,
									'[storeid]'=>$storeid[$i],
									'[storename]'=>$result_store[0]['storename'],
									'[entry_date]'=>$datetime,
								);
								$DB->executedata('i','tblpersonstore',$insperstore,'');
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
			}
			else if($formevent=='editright')
			{
				$qrychk="SELECT personname from tblpersonmaster where contact=:mobilenumber1 AND ISNULL(isdelete,0)=0 AND ISNULL(isverified,0)=1 and id <> :id";
				$parms = array(
					':mobilenumber1'=>$mobilenumber1,
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
					$qrychk="SELECT personname from tblpersonmaster where email=:emailid AND ISNULL(isdelete,0)=0 AND ISNULL(isverified,0)=1 and id <> :id";
					$parms = array(
						':emailid'=>$emailid,
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
						
							$insqry['[update_uid]']=$uid;	
							$insqry['[update_date]']=$datetime;

							$extraparams=array(
								'id'=>$id
							);
							$DB->executedata('u','tblpersonmaster',$insqry,$extraparams);


							//Delete & Insert Person Utype
							$delparams=array(
								'[pid]'=>$id
							);
							$DB->executedata('d','tblpersonutype','',$delparams);
							$DB->executedata('d','tblpersonstore','',$delparams);

							for($i=0;$i<sizeof($utypeid);$i++)
							{
								$qrychk="SELECT usertype from tblusertypemaster where id=:id";
								$parms = array(
									':id'=>$utypeid[$i],
								);
								$result_utype=$DB->getmenual($qrychk,$parms);
								$utid = $IISMethods->generateuuid();
								$insperutype=array(	
									'[id]'=>$utid,				
									'[pid]'=>$id,
									'[utypeid]'=>$utypeid[$i],
									'[userrole]'=>$result_utype[0]['usertype'],
									'[entry_date]'=>$datetime,
								);
								$DB->executedata('i','tblpersonutype',$insperutype,'');
							}

							for($i=0;$i<sizeof($storeid);$i++)
							{
								$qrychk="SELECT storename from tblstoremaster where id=:id";
								$parms = array(
									':id'=>$storeid[$i],
								);
								$result_store=$DB->getmenual($qrychk,$parms);
								$utid = $IISMethods->generateuuid();
								$insperstore=array(	
									'[id]'=>$utid,				
									'[pid]'=>$id,
									'[storeid]'=>$storeid[$i],
									'[storename]'=>$result_store[0]['storename'],
									'[entry_date]'=>$datetime,
								);
								$DB->executedata('i','tblpersonstore',$insperstore,'');
							}

							
							$DB->committransaction();

							$status=1;
							$message=$errmsg['update'];
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
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deleteemployeemaster')   
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
		else if(sizeof($bulk)>0)
		{
			try 
			{
				$DB->begintransaction();

				$usemenu='';
				for($i=0;$i<sizeof($bulk);$i++)
				{
					$id=$bulk[$i];

					
					$extraparams=array(
						'[id]'=>$IISMethods->sanitize($id)
					);
					$DB->executedata('u','tblpersonmaster',$insqry,$extraparams);
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
	else if($action=='editemployeemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="SELECT pm.*,
				ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), pu.utypeid) AS [text()] FROM tblpersonutype pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,10000),'') as utypeid,
				ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), pu.storeid) AS [text()] FROM tblpersonstore pu WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,10000),'') as storeid
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
				$response['personname']=$IISMethods->sanitize($row['personname']);
				$response['username']=$IISMethods->sanitize($row['username']);
				$response['contact']=$IISMethods->sanitize($row['contact']);
				$response['mobilenumber2']=$IISMethods->sanitize($row['mobilenumber2']);
				$response['email']=$IISMethods->sanitize($row['email']);

				$response['address']=$IISMethods->sanitize($row['address']);
				$response['stateid']=$IISMethods->sanitize($row['stateid']);
				$response['cityid']=$IISMethods->sanitize($row['cityid']);

				$response['utypeid']=$IISMethods->sanitize($row['utypeid']);
				$response['storeid']=$IISMethods->sanitize($row['storeid']);

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
	else if($action=='listemployeemaster')   
	{
		$employeemasters=new employeemaster();
		$qry="select distinct pm.timestamp as primary_date,pm.id,pm.personname,pm.contact,pm.email,pm.statename,pm.cityname,pm.isactive,pm.username,pm.strpassword,
			ISNULL(SUBSTRING((select ', '+CONVERT(VARCHAR(255), ut.usertype) AS [text()] FROM tblpersonutype pu inner join tblusertypemaster ut on ut.id=pu.utypeid WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,10000),'') as usertype,
			ISNULL(SUBSTRING((select ', '+CONVERT(VARCHAR(255), ut.storename) AS [text()] FROM tblpersonstore pu inner join tblstoremaster ut on ut.id=pu.storeid WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,10000),'') as store
			from tblpersonmaster pm 
			inner join tblstatemaster sm on sm.id=pm.stateid
			inner join tblcitymaster cm on cm.id=pm.cityid
			inner join tblpersonutype pu on pu.pid = pm.id
			where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid  and pu.utypeid<>:memberutypeid and 
			(pm.personname like :personfilter or pm.contact like :contactfilter or pm.email like :emailfilter or pm.statename like :statefilter or pm.cityname like :cityfilter)  
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";	
		$parms = array(
			//':utypeid'=>$config->getStoreutype(),
			':memberutypeid'=>$config->getMemberutype(),
			':adminuid'=>$config->getAdminUserId(),
			':personfilter'=>'%'.$filter.'%',
			':contactfilter'=>'%'.$filter.'%',
			':emailfilter'=>'%'.$filter.'%',
			':statefilter'=>'%'.$filter.'%',
			':cityfilter'=>'%'.$filter.'%',
		);
		$employeemasters=$DB->getmenual($qry,$parms,'employeemaster');
		
		if($responsetype=='HTML')
		{
			if($employeemasters)
			{
				$i=0;
				foreach($employeemasters as $employeemaster)
				{
					$id="'".$employeemaster->id."'";
					$username="'".$employeemaster->username."'";
					$password="'".$employeemaster->strpassword."'";

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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($employeemaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					
					
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($employeemaster->usertype).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($employeemaster->store,'OUT').'</td>';
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Employee Details" onclick="viewemployeedetaildata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($employeemaster->personname,'OUT').'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($employeemaster->contact).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($employeemaster->email).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($employeemaster->statename,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($employeemaster->cityname,'OUT').'</td>';

					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-name">';
						$htmldata.='<a href="javascript:void(0)" onclick="resetpassword('.$id.','.$IISMethods->sanitize($username).','.$IISMethods->sanitize($password).')" data-toggle="tooltip" data-title="Reset Password"><i class="bi bi-arrow-repeat" style="font-size: 20px;"></i></a>';
						$htmldata.='</td>';

						if($employeemaster->isactive==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeemployeestatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeemployeestatus('.$id.')">';
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
				if($page<=0 && sizeof($employeemasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($employeemasters);
		}

		$common_listdata=$employeemasters;
		
	} 
	//Fill To Category
	else if($action == 'fillcategory')
	{
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);
		$qry="select c.* from tblcategory c order by (case when (c.displayorder>0) then c.displayorder else 99999 end)";
		$parms = array(
		);
		$result_ary=$DB->getmenual($qry,$parms);
		$htmldata='';
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Category</option>';
		}

		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['category'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='changeemployeestatus')   
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
	else if($action=='viewemployeedata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="SELECT DISTINCT pm.id,pm.personname,pm.contact,pm.mobilenumber2,pm.email,pm.address,pm.statename,pm.cityname,pm.username,
			ISNULL(SUBSTRING((select ', '+CONVERT(VARCHAR(255), ut.storename) AS [text()] FROM tblpersonstore pu inner join tblstoremaster ut on ut.id=pu.storeid WHERE CONVERT(VARCHAR(255), pu.pid)=pm.id FOR XML PATH ('')),2,10000),'') as store,
			isnull((SELECT SUBSTRING((select ','+CONVERT(VARCHAR(255), ut.usertype) AS [text()] from tblpersonutype pu inner join tblusertypemaster ut on ut.id=pu.utypeid where pu.pid=pm.id FOR XML PATH ('')),2,1000000)),'') as usertype
			from tblpersonmaster pm 
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
					$tbldata.='<label>Store/Counter <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['store'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Employee Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['personname'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Email <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['email'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Mobile Number 1 <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['contact'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Mobile Number 2 <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['mobilenumber2'].'</b></label>';
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
					$tbldata.='<label>State <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['statename'].'</b></label>';
					$tbldata.='</div></div></div>';
					
					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>City <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['cityname'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Username <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['username'].'</b></label>';
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



}



require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  