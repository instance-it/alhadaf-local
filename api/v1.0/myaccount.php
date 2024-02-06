<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\myaccount.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();


	//Change Password
	if($action=='changepassword')   
	{
		$opass=$IISMethods->sanitize($_POST['m_oldpassword']);
		$npass=$IISMethods->sanitize($_POST['m_newpassword']);
		
		$insqry=array(
			'[password]'=>md5($npass),
			'[strpassword]'=>$npass,
		);

	
		$qrychk="SELECT id from tblpersonmaster where password=:opass and id=:id";
		$parms = array(
			':opass'=>md5($opass),
			':id'=>$uid,
		);
		$result_ary=$DB->getmenual($qrychk,$parms);
		if(sizeof($result_ary) > 0)
		{
			try 
			{
				$DB->begintransaction();
				
				$insqry['update_uid']=$uid;	
				$insqry['update_date']=$IISMethods->getdatetime();

				$extraparams=array(
					'id'=>$uid
				);
				$DB->executedata('u','tblpersonmaster',$insqry,$extraparams);

				$status=1;
				$message=$errmsg['passchanged'];

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
			$message=$errmsg['correctpass'];
		}
		
	}
	//list Profile Data
	else if($action == 'listprofiledata')
	{
		$profiledata=new listprofiledata();

		$qry="SELECT pm.id,isnull(pm.personname,'') as personname,isnull(pm.firstname,'') as firstname,isnull(pm.middlename,'') as middlename,isnull(pm.lastname,'') as lastname,isnull(pm.contact,'') as contact,isnull(pm.email,'') as email,isnull(pm.address,'') as address,
			isnull(pm.qataridno,'') as qataridno,isnull(pm.qataridexpiry,'') as qataridexpiry,isnull(pm.passportidno,'') as passportidno,isnull(pm.passportidexpiry,'') as passportidexpiry,isnull(pm.dob,'') as dob,isnull(pm.nationality,'') as nationality,isnull(pm.companyname,'') as companyname,
			case when (isnull(pm.profileimg,'') = '') then :defualtmemberimageurl else concat(:imageurl,pm.profileimg) end as profileimg,
			case when (isnull(pm.qataridproof,'') = '') then '' else concat(:imageurl1,pm.qataridproof) end as qataridproof,
			case when (isnull(pm.passportproof,'') = '') then '' else concat(:imageurl2,pm.passportproof) end as passportproof,
			case when (isnull(pm.othergovernmentproof,'') = '') then '' else concat(:imageurl3,pm.othergovernmentproof) end as othergovernmentproof
			FROM tblpersonmaster pm 
			INNER JOIN tblpersonutype pu ON pu.pid=pm.id 
			WHERE isnull(pm.isdelete,0)=0 AND isnull(pm.isverified,0)=1 AND convert(varchar(50),pm.id)=:uid";
		$parms = array(
			':uid'=>$uid,
			':defualtmemberimageurl'=>$config->getDefualtMemberImageurl(),
			':imageurl'=>$imageurl,
			':imageurl1'=>$imageurl,
			':imageurl2'=>$imageurl,
			':imageurl3'=>$imageurl,
		);
		//echo $qry;
		//print_r($parms);
		$profiledata=$DB->getmenual($qry,$parms,'listprofiledata');
		
		$response['isprofiledata']=0;
		if($profiledata)
		{
			$response['isprofiledata']=1;
			$response['profiledata']=$profiledata;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
		
	}
	//Update Profile Data
	else if($action == 'updateprofiledata')
	{
		$m_fname = $IISMethods->sanitize($_POST['m_fname']);
		$m_mname = $IISMethods->sanitize($_POST['m_mname']);
		$m_lname = $IISMethods->sanitize($_POST['m_lname']);
		$m_email = $IISMethods->sanitize($_POST['m_email']);
		$m_mobile = $IISMethods->sanitize($_POST['m_mobile']);
		$m_qataridno = $IISMethods->sanitize($_POST['m_qataridno']);
		$m_qataridexpiry = $IISMethods->sanitize($_POST['m_qataridexpiry']);
		$m_passportidno = $IISMethods->sanitize($_POST['m_passportidno']);
		$m_passportidexpiry = $IISMethods->sanitize($_POST['m_passportidexpiry']);
		$m_dob = $IISMethods->sanitize($_POST['m_dob']);
		$m_nationality = $IISMethods->sanitize($_POST['m_nationality']);
		$m_address = $IISMethods->sanitize($_POST['m_address']);
		$m_companyname = $IISMethods->sanitize($_POST['m_companyname']);

		$userprofileimg =$_FILES['userprofileimg']['name'];

		$qataridproof =$_FILES['m_qataridproof']['name'];      //Qatar ID Proof
		$passportproof =$_FILES['m_passportproof']['name'];    //Passport Proof
		$othergovernmentproof =$_FILES['m_othergovernmentproof']['name'];    //Other Government Valid Proof
		$isdocumentupload =$IISMethods->sanitize($_POST['isdocumentupload']);
	
		$datetime=$IISMethods->getdatetime();

		if($m_fname && $m_lname && $m_email && $m_mobile && $m_dob && $m_nationality && $isdocumentupload != 1)
		{
			if(($m_qataridno && $m_qataridexpiry) || ($m_passportidno && $m_passportidexpiry))
			{
				$qrychk="SELECT personname from tblpersonmaster where email=:email AND isnull(isdelete,0)=0 AND isnull(isverified,0)=1 AND id<>:uid";
				$parms = array(
					':email'=>$m_email,
					':uid'=>$uid,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				if(sizeof($result_ary) > 0)
				{
					$status=0;
					$message=$errmsg['emailexist'];
				}
				else
				{
					$qrychk="SELECT personname from tblpersonmaster where contact=:contact AND isnull(isdelete,0)=0 AND isnull(isverified,0)=1 AND id<>:uid";
					$parms = array(
						':contact'=>$m_mobile,
						':uid'=>$uid,
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

							$qrychk="select personname,profileimg,qataridproof,passportproof,othergovernmentproof from tblpersonmaster where id=:uid";
							$chkparms = array(
								':uid'=>$uid,
							);
							$reschk=$DB->getmenual($qrychk,$chkparms);

							if($userprofileimg)
							{
								$sourcePath = $_FILES['userprofileimg']['tmp_name'];
								$uimagePath = $IISMethods->uploadallfiles(0,'profile',$userprofileimg,$sourcePath,$_FILES['userprofileimg']['type'],$uid);
							}
							else
							{
								$uimagePath = $reschk[0]['profileimg'];
							}
							

							$personname=$m_fname.' '.$m_lname;
							$updqry=array(		
								'[personname]'=>$personname,	
								'[firstname]'=>$m_fname,
								'[middlename]'=>$m_mname,	
								'[lastname]'=>$m_lname,	
								'[contact]'=>$m_mobile,		
								'[email]'=>$m_email,	
								'[username]'=>$m_email,	
								'[qataridno]'=>$m_qataridno,
								'[qataridexpiry]'=>$m_qataridexpiry,
								'[passportidno]'=>$m_passportidno,
								'[passportidexpiry]'=>$m_passportidexpiry,
								'[address]'=>$m_address,	
								'[dob]'=>$m_dob,
								'[nationality]'=>$m_nationality,
								'[companyname]'=>$m_companyname,
								'[profileimg]'=>$uimagePath,
								'[update_date]'=>$datetime,	
							);
							$ary=array(
								'[id]'=>$uid,
							);
							$DB->executedata('u','tblpersonmaster',$updqry,$ary);

							
							$isremainupddocument=0;
							$qryper="select id,isnull(qataridproof,'') as qataridproof,isnull(passportproof,'') as passportproof,isnull(othergovernmentproof,'') as othergovernmentproof 
								from tblpersonmaster where convert(varchar(50),id)=:uid";
							$perparams=array( 
								':uid'=>$uid,           
							);
							$resper=$DB->getmenual($qryper,$perparams);
							if(sizeof($resper) > 0)
							{
								$rowper = $resper[0];

								if($rowper['qataridproof'] == '' || $rowper['passportproof'] == '')
								{
									$isremainupddocument=1;
								}
							}

							$response['isremainupddocument'] = (double)$isremainupddocument;


							$status = 1;
							$message = $errmsg['profile-update'];
							
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
		else if($isdocumentupload == 1 && $qataridproof && $passportproof)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select personname,profileimg,qataridproof,passportproof,othergovernmentproof from tblpersonmaster where id=:uid";
				$chkparms = array(
					':uid'=>$uid,
				);
				$reschk=$DB->getmenual($qrychk,$chkparms);

				if($qataridproof)
				{
					$sourcePath_qataridproof = $_FILES['m_qataridproof']['tmp_name'];
					$imagePath_qataridproof = $IISMethods->uploadallfiles(0,'memberproof',$qataridproof,$sourcePath_qataridproof,$_FILES['m_qataridproof']['type'],$uid.'1');
				}
				else
				{
					$imagePath_qataridproof = $reschk[0]['qataridproof'];
				}

				if($passportproof)
				{
					$sourcePath_passportproof = $_FILES['m_passportproof']['tmp_name'];
					$imagePath_passportproof = $IISMethods->uploadallfiles(0,'memberproof',$passportproof,$sourcePath_passportproof,$_FILES['m_passportproof']['type'],$uid.'2');
				}
				else
				{
					$imagePath_passportproof = $reschk[0]['passportproof'];
				}

				if($othergovernmentproof)
				{
					$sourcePath_othergovernmentproof = $_FILES['m_othergovernmentproof']['tmp_name'];
					$imagePath_othergovernmentproof = $IISMethods->uploadallfiles(0,'memberproof',$othergovernmentproof,$sourcePath_othergovernmentproof,$_FILES['m_othergovernmentproof']['type'],$uid.'3');
				}
				else
				{
					$imagePath_othergovernmentproof = $reschk[0]['othergovernmentproof'];
				}

				$updqry=array(		
					'[qataridproof]'=>$imagePath_qataridproof,
					'[passportproof]'=>$imagePath_passportproof,
					'[othergovernmentproof]'=>$imagePath_othergovernmentproof,
					'[update_date]'=>$datetime,	
				);
				$ary=array(
					'[id]'=>$uid,
				);
				$DB->executedata('u','tblpersonmaster',$updqry,$ary);


				
				$isremainupddocument=0;
				$qryper="select id,isnull(qataridproof,'') as qataridproof,isnull(passportproof,'') as passportproof,isnull(othergovernmentproof,'') as othergovernmentproof 
					from tblpersonmaster where convert(varchar(50),id)=:uid";
				$perparams=array( 
					':uid'=>$uid,           
				);
				$resper=$DB->getmenual($qryper,$perparams);
				if(sizeof($resper) > 0)
				{
					$rowper = $resper[0];

					if($rowper['qataridproof'] == '' || $rowper['passportproof'] == '')
					{
						$isremainupddocument=1;
					}
				}

				$response['isremainupddocument'] = (double)$isremainupddocument;


				$status = 1;
				$message = $errmsg['profile-doc-upload'];
				
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



require_once dirname(__DIR__, 2).'\config\apifoot.php';  

?>

  