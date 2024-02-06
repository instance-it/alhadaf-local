<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\member.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();
	$currdate=$IISMethods->getformatcurrdate();


	//List User Type (Member and Guest)
	if($action == 'listmemberusertype')
	{	
		$listmemberusertype=new listmemberusertype();

		$qry="select distinct ut.id,ut.usertype as name,case when (ut.id=:guestusertype) then 1 else 0 end as isguest
			from tblusertypemaster ut 
			where ut.id in (:memberutype,:guestutype) 
			order by ut.usertype desc";
		$parms = array(
			':memberutype'=>$config->getMemberutype(),
			':guestutype'=>$config->getGuestutype(),
			':guestusertype'=>$config->getGuestutype(),
		);
		$listmemberusertype=$DB->getmenual($qry,$parms,'listmemberusertype');

		$response['isutypedata']=0;
		if($listmemberusertype)
		{
			$response['isutypedata']=1;
			$response['utypedata']=$listmemberusertype;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//List Member
	else if($action == 'listmember')
	{	
		$isall = $IISMethods->sanitize($_POST['isall']);
		$listmember=new listmember();

		$qry="SELECT distinct pm.id,concat(pm.personname,' (',pm.contact,')') as name,pm.personname,pm.contact
			FROM tblpersonmaster pm 
			INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
			WHERE isnull(pm.isdelete,0)=0 AND isnull(pm.isverified,0)=1 AND pu.utypeid IN (:utypeid,:guestutypeid) order by pm.personname";
		$params = array(
			':utypeid'=>$config->getMemberutype(),
			':guestutypeid'=>$config->getGuestutype(),

		);
		$listmember=$DB->getmenual($qry,$params,'listmember');

		if($isall == 1)
		{
			$suballdata=array(					
				'id'=>'%',
				'name'=>'All',
				'personname'=>'%',
				'contact'=>'%',
			);
			array_unshift($listmember, $suballdata);
		}

		$response['ismemberdata']=0;
		if($listmember)
		{
			$response['ismemberdata']=1;
			$response['memberdata']=$listmember;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//Insert Member Data
	else if($action == 'insertmemberdata')
	{
		$r_utypeid = $IISMethods->sanitize($_POST['r_utypeid']);
		$r_refmemberid = $IISMethods->sanitize($_POST['r_refmemberid']);

		$r_fname = $IISMethods->sanitize($_POST['r_fname']);
		$r_mname = $IISMethods->sanitize($_POST['r_mname']);
		$r_lname = $IISMethods->sanitize($_POST['r_lname']);
		$r_email = $IISMethods->sanitize($_POST['r_email']);
		$r_mobile = $IISMethods->sanitize($_POST['r_mobile']);
		$r_qataridno = $IISMethods->sanitize($_POST['r_qataridno']);
		$r_qataridexpiry = $IISMethods->sanitize($_POST['r_qataridexpiry']);
		$r_passportidno = $IISMethods->sanitize($_POST['r_passportidno']);
		$r_passportidexpiry = $IISMethods->sanitize($_POST['r_passportidexpiry']);
		$r_dob = $IISMethods->sanitize($_POST['r_dob']);
		$r_nationality = $IISMethods->sanitize($_POST['r_nationality']);
		$r_address = $IISMethods->sanitize($_POST['r_address']);
		$r_companyname = $IISMethods->sanitize($_POST['r_companyname']);
		$r_password = $IISMethods->sanitize($_POST['r_password']);

		$regtype='n';
		$isnormal=1;

		$qataridproof =$_FILES['r_qataridproof']['name'];      //Qatar ID Proof
		$passportproof =$_FILES['r_passportproof']['name'];    //Passport Proof
		$othergovernmentproof =$_FILES['r_othergovernmentproof']['name'];    //Other Government Valid Proof
	
		$datetime=$IISMethods->getdatetime();
		$unqid = $IISMethods->generateuuid();

		//if($r_fname && $r_lname && $r_email && $r_mobile && $r_qataridno && $r_qataridexpiry && $r_dob && $r_nationality && ($r_password || $isnormal == 0) && $qataridproof && $passportproof)
		if($r_utypeid && $r_fname && $r_lname && $r_email && $r_mobile && $r_dob && $r_nationality && ($r_password || $isnormal == 0))
		{
			if(($r_qataridno && $r_qataridexpiry) || ($r_passportidno && $r_passportidexpiry))
			{
				$qrychk="SELECT personname,isnull(isverified,0) as isverified from tblpersonmaster where email=:email AND isnull(isdelete,0)=0 ";
				$parms = array(
					':email'=>$r_email,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					// if($result_ary[0]['isverified'] == 1)
					// {
					// 	$message=$errmsg['emailexist'];
					// }
					// else
					// {
						$message=$errmsg['emailexist'];
					//}
					$status=0;
				}
				else
				{
					$qrychk="SELECT personname,isnull(isverified,0) as isverified from tblpersonmaster where contact=:contact AND isnull(isdelete,0)=0 ";
					$parms = array(
						':contact'=>$r_mobile,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					if(sizeof($result_ary) > 0)
					{
						// if($result_ary[0]['isverified'] == 1)
						// {
						// 	$message=$errmsg['mobileexist'];
						// }
						// else
						// {
							$message=$errmsg['mobileexist'];
						//}
						$status=0;
					}
					else
					{
						try 
						{ 
							$DB->begintransaction();

							$imagePath_qataridproof='';
							if($qataridproof)
							{
								$sourcePath_qataridproof = $_FILES['r_qataridproof']['tmp_name'];
								$imagePath_qataridproof = $IISMethods->uploadallfiles(1,'memberproof',$qataridproof,$sourcePath_qataridproof,$_FILES['r_qataridproof']['type'],$unqid.'1');
							}

							$imagePath_passportproof='';
							if($passportproof)
							{
								$sourcePath_passportproof = $_FILES['r_passportproof']['tmp_name'];
								$imagePath_passportproof = $IISMethods->uploadallfiles(1,'memberproof',$passportproof,$sourcePath_passportproof,$_FILES['r_passportproof']['type'],$unqid.'2');
							}

							$imagePath_othergovernmentproof='';
							if($othergovernmentproof)
							{
								$sourcePath_othergovernmentproof = $_FILES['r_othergovernmentproof']['tmp_name'];
								$imagePath_othergovernmentproof = $IISMethods->uploadallfiles(1,'memberproof',$othergovernmentproof,$sourcePath_othergovernmentproof,$_FILES['r_othergovernmentproof']['type'],$unqid.'3');
							}

							if($r_refmemberid == '' || $r_refmemberid == null)
							{
								$r_refmemberid=$mssqldefval['uniqueidentifier'];
							}

							$personname=$r_fname.' '.$r_lname;
							$insqry=array(					
								'[id]'=>$unqid,	
								'[personname]'=>$personname,	
								'[firstname]'=>$r_fname,
								'[middlename]'=>$r_mname,	
								'[lastname]'=>$r_lname,	
								'[contact]'=>$r_mobile,		
								'[email]'=>$r_email,	
								'[username]'=>$r_email,	
								'[qataridno]'=>$r_qataridno,
								'[qataridexpiry]'=>$r_qataridexpiry,
								'[passportidno]'=>$r_passportidno,
								'[passportidexpiry]'=>$r_passportidexpiry,
								'[address]'=>$r_address,	
								'[dob]'=>$r_dob,
								'[nationality]'=>$r_nationality,
								'[companyname]'=>$r_companyname,
								'[password]'=>md5($r_password),	
								'[strpassword]'=>$r_password,
								'[qataridproof]'=>$imagePath_qataridproof,
								'[passportproof]'=>$imagePath_passportproof,
								'[othergovernmentproof]'=>$imagePath_othergovernmentproof,
								'[isnormal]'=>$isnormal,
								'[regtype]'=>$regtype,
								'[webid]'=>'',
								'[refmemberid]'=>$r_refmemberid,	
								'[platform]'=>$platform,	
								'[isactive]'=>1,
								'[isverified]'=>1,
								'[iscontactverified]'=>1,
								'[isemailverified]'=>1,
								'[timestamp]'=>$datetime,	
								'[entry_date]'=>$datetime,	

							);
							$DB->executedata('i','tblpersonmaster',$insqry,'');


							//Insert User Type
							$qrychk="SELECT usertype from tblusertypemaster where id=:id";
							$parms = array(
								//':id'=>$config->getMemberutype(),
								':id'=>$r_utypeid,
							);
							$result_utype=$DB->getmenual($qrychk,$parms);
							$utid = $IISMethods->generateuuid();
							$insperutype=array(	
								'[id]'=>$utid,				
								'[pid]'=>$unqid,
								//'[utypeid]'=>$config->getMemberutype(),
								'[utypeid]'=>$r_utypeid,
								'[userrole]'=>$result_utype[0]['usertype'],
								'[entry_date]'=>$datetime,
							);
							$DB->executedata('i','tblpersonutype',$insperutype,'');

							$response['memberid']=$unqid;

							if($config->getIsAccessSAP() == 1)
							{
								//Insert Member Data in SAP (HaNa DB)
								$DB->SAPInsertMemberData($SubDB,$unqid);
							}

							$status = 1;
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
	//List Member History
	else if($action == 'listmemberhistory')
	{
		$storeid =$IISMethods->sanitize($_POST['storeid']);
		$contactno = $IISMethods->sanitize($_POST['contactno']);
		$isserviceorder =$IISMethods->sanitize($_POST['isserviceorder']);

		$soid = $IISMethods->sanitize($_POST['soid']);   //Service Order ID

		if($soid == '' || $soid == null)
		{
			$soid = $mssqldefval['uniqueidentifier'];
		}

		$todaydate = $IISMethods->getcurrdate();

		$incompleteprevstore=0;
		if($contactno)
		{

			$memberdata=new listmemberdata();
			$qry="SELECT pm.id,isnull(pm.personname,'') as personname,isnull(pm.firstname,'') as firstname,isnull(pm.middlename,'') as middlename,isnull(pm.lastname,'') as lastname,isnull(pm.contact,'') as contact,isnull(pm.email,'') as email,isnull(pm.address,'') as address,
				isnull(pm.qataridno,'') as qataridno,isnull(pm.qataridexpiry,'') as qataridexpiry,isnull(pm.passportidno,'') as passportidno,isnull(pm.passportidexpiry,'') as passportidexpiry,isnull(pm.dob,'') as dob,isnull(pm.nationality,'') as nationality,isnull(pm.companyname,'') as companyname,
				case when (isnull(pm.profileimg,'') = '') then :defualtmemberimageurl else concat(:imageurl,pm.profileimg) end as profileimg
				FROM tblpersonmaster pm 
				INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
				WHERE isnull(pm.isdelete,0)=0 AND isnull(pm.isverified,0)=1 AND pu.utypeid in (:utypeid,:guestutypeid) AND pm.contact=:contactno";
			$parms = array(
				':utypeid'=>$config->getMemberutype(),
				':guestutypeid'=>$config->getGuestutype(),
				':contactno'=>$contactno,
				':defualtmemberimageurl'=>$config->getDefualtMemberImageurl(),
				':imageurl'=>$imgpath,
	
			);
			
			$memberdata=$DB->getmenual($qry,$parms,'listmemberdata');
			
			$response['ismemberdata']=0;
			if($memberdata)
			{
				/************ Start For Add Operation Flow in Day ************/
				$qrychk="select so.* 
					from tblstoreoperation1 so 
					where memberid=:memberid and convert(date,so.date,103)=convert(date,:todaydate,103)";
				$chkparms = array(
					':memberid'=>$memberdata[0]->getId(),
					':todaydate'=>$todaydate,
				);
				$reschk=$DB->getmenual($qrychk,$chkparms);

				if(sizeof($reschk) > 0)
				{

				}
				else
				{
					$qry="select ofd.*,sm.storename,op.name as operationname 
						from tbloperationflowdetail ofd 
						inner join tblstoremaster sm on sm.id=ofd.storeid 
						inner join tbloperationmaster op on op.id=ofd.operationid
						order by ofd.displayorder";
					$parms = array();
					$res=$DB->getmenual($qry,$parms);
					
					if(sizeof($res) > 0)
					{
						for($i=0;$i<sizeof($res);$i++)
						{
							$row=$res[$i];

							$unqid = $IISMethods->generateuuid();

							$sop_insqry['[id]']=$unqid;
							$sop_insqry['[memberid]']=$memberdata[0]->getId();
							$sop_insqry['[date]']=$todaydate;
							$sop_insqry['[storeid]']=$row['storeid'];
							$sop_insqry['[storename]']=$row['storename'];
							$sop_insqry['[operationid]']=$row['operationid'];
							$sop_insqry['[operationname]']=$row['operationname'];
							$sop_insqry['[isserviceorder]']=$row['isserviceorder'];
							$sop_insqry['[iscompulsory]']=$row['iscompulsory'];
							$sop_insqry['[displayorder]']=$row['displayorder'];
							$sop_insqry['[insgroupid]']=$row['insgroupid'];
							$sop_insqry['[timestamp]']=$datetime;
							
							$DB->executedata('i','tblstoreoperation1',$sop_insqry,'');


							//Operation Flow Member
							$qrymem="select ofm.*,mt.type as membertypename 
								from tbloperationflowmember ofm 
								inner join tblmembertype mt on mt.id=ofm.membertypeid 
								where ofm.flowdetailid=:flowdetailid";
							$memparms = array(
								':flowdetailid'=>$row['id'],
							);
							$resmem=$DB->getmenual($qrymem,$memparms);

							if(sizeof($resmem) > 0)
							{
								for($j=0;$j<sizeof($resmem);$j++)
								{
									$rowmem=$resmem[$j];

									$m_unqid = $IISMethods->generateuuid();

									$sopm_insqry['[id]']=$m_unqid;
									$sopm_insqry['[sopid]']=$unqid;
									$sopm_insqry['[membertypeid]']=$rowmem['membertypeid'];
									$sopm_insqry['[membertype]']=$rowmem['membertypename'];
									$sopm_insqry['[timestamp]']=$datetime;
									
									$DB->executedata('i','tblstoreoperationmembertype1',$sopm_insqry,'');
								}	
							}


							//Operation Flow Instruction
							$qryins="select i.*
								from tblinstructiongroupdetail igd 
								inner join tblinstruction i on i.id=igd.instructionid 
								where igd.insgroupid=:insgroupid and i.isactive=1 order by displayorder";
							$insparms = array(
								':insgroupid'=>$row['insgroupid'],
							);
							$resins=$DB->getmenual($qryins,$insparms);

							if(sizeof($resins) > 0)
							{
								for($j=0;$j<sizeof($resins);$j++)
								{
									$rowins=$resins[$j];

									$i_unqid = $IISMethods->generateuuid();

									$sopi_insqry['[id]']=$i_unqid;
									$sopi_insqry['[sopid]']=$unqid;
									$sopi_insqry['[insgroupid]']=$row['insgroupid'];
									$sopi_insqry['[instructionid]']=$rowins['id'];
									$sopi_insqry['[instructionname]']=$rowins['name'];
									$sopi_insqry['[displayorder]']=$rowins['displayorder'];
									$sopi_insqry['[timestamp]']=$datetime;
									
									$DB->executedata('i','tblstoreoperationinstruction1',$sopi_insqry,'');
								}	
							}


							//Operation Flow Range
							$qryrange="select ofr.*
								from tbloperationflowrange ofr 
								where ofr.flowdetailid=:flowdetailid";
							$rangeparms = array(
								':flowdetailid'=>$row['id'],
							);
							$resrange=$DB->getmenual($qryrange,$rangeparms);

							if(sizeof($resrange) > 0)
							{
								for($j=0;$j<sizeof($resrange);$j++)
								{
									$rowrange=$resrange[$j];

									$r_unqid = $IISMethods->generateuuid();

									$sopm_insqry['[id]']=$r_unqid;
									$sopm_insqry['[sopid]']=$unqid;
									$sopm_insqry['[rangeid]']=$rowrange['rangeid'];
									$sopm_insqry['[timestamp]']=$datetime;
									
									$DB->executedata('i','tblstoreoperationrange1',$sopm_insqry,'');
								}	
							}

						}
					}
				}
				/************ End For Add Operation Flow in Day ************/




				$qrycurrorder="select top 1 so1.displayorder as curr_displayorder from tblstoreoperation1 so1 where so1.storeid=:s_storeid and so1.memberid=:s_uid and convert(date,so1.date,103)=:s_currdate and isnull(so1.temp_iscompleted,0)=0 order by so1.displayorder";
				$currorderparms = array(
					':s_uid'=>$memberdata[0]->getId(), 
					':s_storeid'=>$storeid, 
					':s_currdate'=>$currdate, 
				);
				$rescurrorder=$DB->getmenual($qrycurrorder,$currorderparms);
				$rowcurrorder=$rescurrorder[0];



				$qryprev="select tmp.id,tmp.storename from (
					select so.*,:currdisplayorder as curr_displayorder
					from tblstoreoperation1 so where so.memberid=:uid and convert(date,so.date,103)=:currdate and isnull(so.iscompulsory,0)=1 
				) tmp where tmp.displayorder < tmp.curr_displayorder and isnull(tmp.iscompleted,0)=0 order by tmp.displayorder";
				$prevparms = array(
					//':s_uid'=>$memberdata[0]->getId(), 
					//':s_storeid'=>$storeid, 
					//':s_currdate'=>$currdate, 
					':uid'=>$memberdata[0]->getId(), 
					':currdate'=>$currdate, 
					':currdisplayorder'=>$rowcurrorder['curr_displayorder'], 
				);
				$resprev=$DB->getmenual($qryprev,$prevparms);
				$numprev=sizeof($resprev);
				

				if($numprev > 0 && $isserviceorder != 1)
				{
					$rowprev=$resprev[0];

					$incompleteprevstore=1;
					$status=0;
					$message=str_replace("#storename#",$rowprev['storename'],$errmsg['incompleteoperation']);
				}
				else
				{
					$response['ismemberdata']=1;


					/************************* Start For Membership Details ****************************/
					$mshipdetail = new mshipdetail();
					$qrymship="select od.id as id,REPLACE(od.itemname,'&amp;','&') as name,
					case when (isnull((select count(mf.id) from tblmemberfreezemship mf where mf.membershipid=od.id and :currdate1 between convert(date,mf.startdate,103) and convert(date,mf.enddate,103)),0) > 0) then 1 else 0 end as isfreezemship
					from tblorder o
					inner join tblorderdetail od on od.orderid = o.id
					where o.uid = :uid and o.status=1 and o.iscancel = 0 and convert(date,od.n_expirydate,103) >= :currdate order by od.timestamp desc";
					$mshipparams = array(
						':uid'=>$memberdata[0]->getId(), 
						':currdate'=>$currdate, 
						':currdate1'=>$currdate, 
					);
					$mshipdetail=$DB->getmenual($qrymship,$mshipparams,'mshipdetail');

					$memberdata[0]->setIsMshipDetail(0);
					if(sizeof($mshipdetail)>0)
					{
						$memberdata[0]->setIsMshipDetail(1);
						$memberdata[0]->setMshipDetail($mshipdetail);
					}
					/************************* End For Membership Details ****************************/



					/************************* Start For Item Details ****************************/
					$itemdetail = new itemdetail();
					// $qryitem="select tmp.categoryid,tmp.category,tmp.subcategoryid,tmp.subcategory,tmp.id,tmp.name from (
					// 	select distinct oid.catid as categoryid,oid.category,oid.subcatid as subcategoryid,oid.subcategory,oid.itemid as id,REPLACE(oid.itemname,'&amp;','&') as name,
					// 	case when (isnull((select count(mf.id) from tblmemberfreezemship mf where mf.membershipid=od.id and :currdate1 between convert(date,mf.startdate,103) and convert(date,mf.enddate,103)),0) > 0) then 1 else 0 end as isfreezemship
					// 	from tblorder o
					// 	inner join tblorderdetail od on od.orderid = o.id 
					// 	inner join tblorderitemdetail oid on oid.odid=od.id 
					// 	inner join tblitemstore si on si.itemid=oid.itemid 
					// 	where o.uid = :uid and o.status=1 and o.iscancel = 0 and convert(date,od.n_expirydate,103) >= :currdate and isnull(oid.iswebsiteattribute,0)=0 
					// 	and si.storeid=:storeid and oid.remainqty > 0
					// ) tmp where tmp.isfreezemship=0";
					// $itemparams = array(
					// 	':uid'=>$memberdata[0]->getId(), 
					// 	':currdate'=>$currdate, 
					// 	':currdate1'=>$currdate, 
					// 	':storeid'=>$storeid, 
					// );
					$qryitem="select tmp.categoryid,tmp.category,tmp.subcategoryid,tmp.subcategory,tmp.id,tmp.name from (
						select distinct oid.catid as categoryid,oid.category,oid.subcatid as subcategoryid,oid.subcategory,oid.itemid as id,REPLACE(oid.itemname,'&amp;','&') as name,
						case when (isnull((select count(mf.id) from tblmemberfreezemship mf where mf.membershipid=od.id and :currdate1 between convert(date,mf.startdate,103) and convert(date,mf.enddate,103)),0) > 0) then 1 else 0 end as isfreezemship
						from tblorder o
						inner join tblorderdetail od on od.orderid = o.id 
						inner join tblorderitemdetail oid on oid.odid=od.id 
						inner join tblitemstore si on si.itemid=oid.itemid 
						where o.uid = :uid and o.status=1 and o.iscancel = 0 and convert(date,od.n_expirydate,103) >= :currdate and isnull(oid.iswebsiteattribute,0)=0 
						 
					) tmp where tmp.isfreezemship=0";
					$itemparams = array(
						':uid'=>$memberdata[0]->getId(), 
						':currdate'=>$currdate, 
						':currdate1'=>$currdate, 
					);
					$itemdetail=$DB->getmenual($qryitem,$itemparams,'itemdetail');

					$memberdata[0]->setIsItemDetail(0);
					if(sizeof($itemdetail)>0)
					{
						$memberdata[0]->setIsItemDetail(1);
						$memberdata[0]->setItemDetail($itemdetail);


						/**************** Start For Item Sub Details *******************/
						for($j=0;$j<sizeof($itemdetail);$j++)
						{
							$itemsubdetail = new itemsubdetail();
							// $qryitem="select tmp.oidid,tmp.qty,tmp.remainqty,tmp.durationid,tmp.durationname,tmp.durationdays,tmp.discount,tmp.price,tmp.taxtypename,tmp.taxtype,tmp.sgst,tmp.cgst,tmp.igst,tmp.type,tmp.typename,tmp.timestamp from (
							// 	select distinct oid.id as oidid,oid.remainqty as qty,oid.remainqty,oid.durationid,oid.durationname,oid.durationdays,oid.discount,oid.price,
							// 	tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,oid.type,oid.typestr as typename,o.timestamp,
							// 	case when (isnull((select count(mf.id) from tblmemberfreezemship mf where mf.membershipid=od.id and :currdate1 between convert(date,mf.startdate,103) and convert(date,mf.enddate,103)),0) > 0) then 1 else 0 end as isfreezemship
							// 	from tblorder o
							// 	inner join tblorderdetail od on od.orderid = o.id 
							// 	inner join tblorderitemdetail oid on oid.odid=od.id 
							// 	inner join tbltaxtype tt on tt.id=oid.gsttypeid
							// 	inner join tbltax tx on tx.id=oid.gstid 
							// 	inner join tblitemstore si on si.itemid=oid.itemid 
							// 	where o.uid = :uid and o.status=1 and o.iscancel = 0 and convert(date,od.n_expirydate,103) >= :currdate and isnull(oid.iswebsiteattribute,0)=0 
							// 	and si.storeid=:storeid and oid.itemid=:itemid and oid.remainqty > 0 
							// ) tmp where tmp.isfreezemship=0 order by tmp.timestamp";	
							// $itemparams = array(
							// 	':uid'=>$memberdata[0]->getId(), 
							// 	':itemid'=>$itemdetail[$j]->getItemId(), 
							// 	':currdate'=>$currdate, 
							// 	':currdate1'=>$currdate, 
							// 	':storeid'=>$storeid, 
							// );
							$qryitem="select tmp.oidid,tmp.baseqty,(tmp.qty-tmp.so_holdqty+tmp.so_returnqty) as qty,(tmp.remainqty-tmp.so_holdqty+tmp.so_returnqty) as remainqty,tmp.durationid,tmp.durationname,tmp.durationdays,tmp.discount,tmp.price,tmp.taxtypename,tmp.taxtype,tmp.sgst,tmp.cgst,tmp.igst,tmp.type,tmp.typename,tmp.timestamp from (
								select distinct oid.id as oidid,oid.qty as baseqty,oid.qty as qty,oid.qty as remainqty,oid.durationid,oid.durationname,oid.durationdays,oid.discount,oid.price,
								tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,oid.type,oid.typestr as typename,o.timestamp,
								case when (isnull((select count(mf.id) from tblmemberfreezemship mf where mf.membershipid=od.id and :currdate1 between convert(date,mf.startdate,103) and convert(date,mf.enddate,103)),0) > 0) then 1 else 0 end as isfreezemship,
								isnull((select sum(sod.qty) from tblserviceorderdetail sod inner join tblserviceorder so on so.id=sod.orderid where so.iscancel=0 and sod.iscancel=0 and sod.orderid<>:soid and sod.oidid=oid.id),0) as so_holdqty,
								isnull((select sum(sod.return_qty) from tblserviceorderdetail sod inner join tblserviceorder so on so.id=sod.orderid where so.iscancel=0 and sod.iscancel=0 and sod.oidid=oid.id),0) as so_returnqty
								from tblorder o
								inner join tblorderdetail od on od.orderid = o.id 
								inner join tblorderitemdetail oid on oid.odid=od.id 
								inner join tbltaxtype tt on tt.id=oid.gsttypeid
								inner join tbltax tx on tx.id=oid.gstid 
								inner join tblitemstore si on si.itemid=oid.itemid 
								where o.uid = :uid and o.status=1 and o.iscancel = 0 and convert(date,od.n_expirydate,103) >= :currdate and isnull(oid.iswebsiteattribute,0)=0 
								and oid.itemid=:itemid
							) tmp where tmp.isfreezemship=0 order by (case when ((tmp.qty-tmp.so_holdqty+tmp.so_returnqty)>0) then 1 else 0 end) desc,tmp.timestamp";	
							$itemparams = array(
								':uid'=>$memberdata[0]->getId(), 
								':itemid'=>$itemdetail[$j]->getItemId(), 
								':currdate'=>$currdate, 
								':currdate1'=>$currdate, 
								':soid'=>$soid,
							);
							$itemsubdetail=$DB->getmenual($qryitem,$itemparams,'itemsubdetail');

							$itemdetail[$j]->setIsItemSubDetail(0);
							if(sizeof($itemsubdetail)>0)
							{
								$itemdetail[$j]->setIsItemSubDetail(1);
								$itemdetail[$j]->setItemSubDetail($itemsubdetail);
							}
						}
						/*************** End For Item Sub Details ******************/

					}
					/************************* End For Item Details ****************************/




					/************************* Start For Last Visit Details ****************************/
					$qryso="select top 1 so.date as lastvisitdate,convert(varchar,convert(date,so.date,103),106) as strlastvisitdate 
						from tblstoreoperation1 so 
						where so.memberid=:uid and convert(date,so.date,103)<>:currdate order by convert(date,so.date,103) desc";
					$soparams = array(
						':uid'=>$memberdata[0]->getId(), 
						//':uid'=>'3FA08E83-3E23-419A-8EB1-29FB8F779692', 
						':currdate'=>$currdate, 
					);
					$resso=$DB->getmenual($qryso,$soparams);

					$lastvisit_date=$resso[0]['lastvisitdate'];
					$lastvisit_strdate=$resso[0]['strlastvisitdate'];
					//$response['lastvisitdate']=(string)$lastvisit_strdate;

					
					$lastvisitcategory = new lastvisitcategory();
					$qrycat="select distinct sod.catid as categoryid,sod.category
						from tblstoreorderdetail sod 
						inner join tblstoreorder so on so.id=sod.orderid
						where so.status=1 and so.uid=:uid and convert(date,so.orderdate,103)=convert(date,:lastvisitdate,103) order by sod.category";
					$catparams = array(
						':uid'=>$memberdata[0]->getId(), 
						':lastvisitdate'=>$lastvisit_date,
						//':lastvisitdate'=>'11/03/2022',  
					);
					$lastvisitcategory=$DB->getmenual($qrycat,$catparams,'lastvisitcategory');

					$memberdata[0]->setLastVisitDate($lastvisit_strdate);
					$memberdata[0]->setIsLastVisitCategory(0);
					if(sizeof($lastvisitcategory)>0)
					{
						$memberdata[0]->setIsLastVisitCategory(1);
						$memberdata[0]->setLastVisitCategory($lastvisitcategory);


						/**************** Start For Item Sub Details *******************/
						
						for($j=0;$j<sizeof($lastvisitcategory);$j++)
						{
							$lastvisititem = new lastvisititem();
							$qryitem="select tmp.id,tmp.name,tmp.qty,
								case when (tmp.isreturnableitem=1 and tmp.isreturned=1) then 'Returned' when (tmp.isreturnableitem=1 and tmp.isreturned=0) then 'Not Returned' else '' end as returnstatus,
								case when (tmp.isreturnableitem=1 and tmp.isreturned=1) then '#008140' when (tmp.isreturnableitem=1 and tmp.isreturned=0) then '#b00000' else '' end as returnstatuscolor 
								from (
									select sod.itemid as id,REPLACE(sod.itemname,'&amp;','&') as name,sum(sod.qty) as qty,min(sod.isreturned) as isreturned,case when (sod.catid=:returnablecatid) then 1 else 0 end as isreturnableitem
									from tblstoreorderdetail sod 
									inner join tblstoreorder so on so.id=sod.orderid
									where so.status=1 and so.uid=:uid and convert(date,so.orderdate,103)=convert(date,:lastvisitdate,103) and sod.catid=:catid
									group by sod.itemid,sod.itemname,sod.catid
								) tmp ";	
							$itemparams = array(
								':uid'=>$memberdata[0]->getId(), 
								':catid'=>$lastvisitcategory[$j]->getCategoryId(), 
								':lastvisitdate'=>$lastvisit_date,
								':returnablecatid'=>$config->getDefaultCatReturnableId(),
								//':consumablecatid'=>$config->getDefaultCatConsumableId(),
								//':lastvisitdate'=>'11/03/2022', 
							);
							//print_r($itemparams);
							$lastvisititem=$DB->getmenual($qryitem,$itemparams,'lastvisititem');

							$lastvisitcategory[$j]->setIsLastVisitItem(0);
							if(sizeof($lastvisititem)>0)
							{
								$lastvisitcategory[$j]->setIsLastVisitItem(1);
								$lastvisitcategory[$j]->setLastVisitItem($lastvisititem);
							}
						}
						
						/*************** End For Item Sub Details ******************/

					}
					
					/************************* End For Last Visit Details ****************************/



					/************************* Start For Update Previous Store From Current Store Temporary Completed Flag ****************************/
					if($isserviceorder != 1)
					{
						$qryprevstore="select so.id,so.storeid,REPLACE(so.storename,'&amp;','&') as storename,so.operationid,REPLACE(so.operationname,'&amp;','&') as operationname,so.iscompulsory,so.iscompleted,so.displayorder,so.temp_iscompleted
							from tblstoreoperation1 so 
							where so.memberid=:uid and convert(date,so.date,103)=:currdate and so.displayorder < :currdisplayorder order by so.displayorder";
						$prevstoreparms = array(
							':uid'=>$memberdata[0]->getId(), 
							':currdate'=>$currdate, 
							':currdisplayorder'=>$rowcurrorder['curr_displayorder'], 
						);
						$resprevstore=$DB->getmenual($qryprevstore,$prevstoreparms);
						for($k=0;$k<sizeof($resprevstore);$k++)
						{
							$rowprevstore=$resprevstore[$k];
							$updprevstore['[temp_iscompleted]']=1;

							$updprevstoredata['[id]']=$rowprevstore['id'];
							$DB->executedata('u','tblstoreoperation1',$updprevstore,$updprevstoredata);
						}
					}
					/************************* End For Update Previous Store From Current Store Temporary Completed Flag ****************************/

					
					$response['memberdata']=$memberdata;
					
					$status = 1;
					$message = $errmsg['success'];
				}	
			}
			else
			{
				$status=0;
				$message=$errmsg['invalidcontact'];
			}
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}

		$response['incompleteprevstore']=(double)$incompleteprevstore;
		
	}
	
	
	

	
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  