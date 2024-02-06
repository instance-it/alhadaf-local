<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\storemaster.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertstoremaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$storename=$IISMethods->sanitize($_POST['storename'],'OUT');
		$empname=$IISMethods->sanitize($_POST['empname']);
		$mobilenumber1=$IISMethods->sanitize($_POST['mobilenumber1']);
		$mobilenumber2=$IISMethods->sanitize($_POST['mobilenumber2']);
		$emailid=$IISMethods->sanitize($_POST['emailid']);
		$address=$IISMethods->sanitize($_POST['address']);
		$stateid=$IISMethods->sanitize($_POST['stateid']);
		$cityid=$IISMethods->sanitize($_POST['cityid']);
	
		$id=$IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();

		if($storename && $empname && $mobilenumber1 && $emailid && $address && $stateid && $cityid )
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
				'[storename]'=>$storename,
				'[personname]'=>$empname,
				'[contact]'=>$mobilenumber1,
				'[mobilenumber2]'=>$mobilenumber2,
				'[email]'=>$emailid,
				'[address]'=>$address,
				'[stateid]'=>$stateid,
				'[statename]'=>$result_name[0]['state'],
				'[cityid]'=>$cityid,
				'[cityname]'=>$result_name[0]['city'],	
			);

			if($formevent=='addright')
			{
				$unqid = $IISMethods->generateuuid();
				$qrychk="SELECT storename from tblstoremaster where storename=:storename";
				$parms = array(
					':storename'=>$storename,
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
						$insqry['[id]']=$unqid;	
						$insqry['[timestamp]']=$datetime;	
						$insqry['[entry_uid]']=$uid;	
						$insqry['[entry_date]']=$datetime;

						$DB->executedata('i','tblstoremaster',$insqry,'');

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
				$qrychk="SELECT storename from tblstoremaster where storename=:storename AND id<>:id";
				$parms = array(
					':storename'=>$storename,
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
						$insqry['[update_date]']=$datetime;

						$extraparams=array(
							'id'=>$id
						);
						$DB->executedata('u','tblstoremaster',$insqry,$extraparams);
						
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
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deletestoremaster')   
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
			$qrychk="SELECT case when(convert(varchar(50),tis.id) !='' or convert(varchar(50),ps.id) !='') then 1 else 0 end as tem
			from tblstoremaster pm
			left join tblitemstore tis on tis.storeid = pm.id
			left join tblpersonstore ps on ps.storeid = pm.id
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
					$DB->executedata('u','tblstoremaster',$insqry,$extraparams);

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

					$qrychk="SELECT case when(convert(varchar(50),tis.id) !='' or convert(varchar(50),ps.id) !='') then 1 else 0 end as tem,pm.storename
					from tblstoremaster pm
					left join tblitemstore tis on tis.storeid = pm.id
					left join tblpersonstore ps on ps.storeid = pm.id
					where pm.id=:id";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					$row=$result_ary[0];

					if($row['tem'] > 0)
					{
						$usemenu.=$row['storename'].",";
					}
					else
					{
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('u','tblstoremaster',$insqry,$extraparams);
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
	else if($action=='editstoremaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="SELECT pm.* FROM tblstoremaster pm WHERE pm.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['storename']=$IISMethods->sanitize($row['storename'],'OUT');
				$response['personname']=$IISMethods->sanitize($row['personname']);
				$response['username']=$IISMethods->sanitize($row['username']);
				$response['contact']=$IISMethods->sanitize($row['contact']);
				$response['mobilenumber2']=$IISMethods->sanitize($row['mobilenumber2']);
				$response['email']=$IISMethods->sanitize($row['email']);

				$response['address']=$IISMethods->sanitize($row['address']);
				$response['stateid']=$IISMethods->sanitize($row['stateid']);
				$response['cityid']=$IISMethods->sanitize($row['cityid']);

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
	else if($action=='liststoremaster')   
	{
		$storemasters=new storemaster();
		$qry="select pm.timestamp as primary_date,pm.id,pm.storename,pm.personname,pm.contact,pm.email,pm.statename,pm.cityname,pm.isactive
			from tblstoremaster pm 
			inner join tblstatemaster sm on sm.id=pm.stateid
			inner join tblcitymaster cm on cm.id=pm.cityid
			where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND
			(pm.storename like :storefilter or pm.personname like :personfilter or pm.contact like :contactfilter or pm.email like :emailfilter or pm.statename like :statefilter or pm.cityname like :cityfilter)  
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";	
		$parms = array(
			':adminuid'=>$config->getAdminUserId(),
			':storefilter'=>'%'.$filter.'%',
			':personfilter'=>'%'.$filter.'%',
			':contactfilter'=>'%'.$filter.'%',
			':emailfilter'=>'%'.$filter.'%',
			':statefilter'=>'%'.$filter.'%',
			':cityfilter'=>'%'.$filter.'%',
		);
		$storemasters=$DB->getmenual($qry,$parms,'storemaster');
		
		if($responsetype=='HTML')
		{
			if($storemasters)
			{
				$i=0;
				foreach($storemasters as $storemaster)
				{
					$id="'".$storemaster->id."'";
					$username="'".$storemaster->username."'";
					$password="'".$storemaster->strpassword."'";

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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($storemaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Store Details" onclick="viewstoredetaildata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($storemaster->storename,'OUT').'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($storemaster->personname,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($storemaster->contact).'</td>';
					$htmldata.='<td class="">'.$IISMethods->sanitize($storemaster->email).'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($storemaster->statename,'OUT').'</td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($storemaster->cityname,'OUT').'</td>';
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($storemaster->isactive==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changestorestatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changestorestatus('.$id.')">';
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
				if($page<=0 && sizeof($storemasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($storemasters);
		}

		$common_listdata=$storemasters;
		
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
	else if($action=='changestorestatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select isactive from tblstoremaster where id=:id";
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
				$DB->executedata('u','tblstoremaster',$insqry,$extraparams);
			
			
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
				$DB->executedata('u','tblstoremaster',$insqry,$extraparams);
			
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
	else if($action=='viewstoredata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="SELECT DISTINCT pm.storename,pm.personname,pm.contact,pm.mobilenumber2,pm.email,pm.address,pm.statename,pm.cityname
			from tblstoremaster pm 
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
					$tbldata.='<label>Store/Counter Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['storename'].'</b></label>';
					$tbldata.='</div></div></div>';

					$tbldata.='<div class="col-12 col-lg-6 col-xl-6 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-4 col-lg-5 text-dot-right pr-sm-0">';
					$tbldata.='<label>Person Name <span>:</span></label>';
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

  