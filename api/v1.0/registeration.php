<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();

	//Insert Registeration Data
	if($action == 'register')
	{
		$redirecturl = $IISMethods->sanitize($_POST['redirecturl']);
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

		$regtype=$IISMethods->sanitize($_POST['regtype']);   //f-facebook,g-google,n-normal
   		$webid=$IISMethods->sanitize($_POST['hiddenid']);  //When Social Register

		if($regtype=='' || $regtype=='n')
		{
			$regtype='n';
			$isnormal=1;
		}
		else if($regtype=='g' || $regtype=='f')
		{
			$isnormal=0;
		}

		$qataridproof =$_FILES['r_qataridproof']['name'];      //Qatar ID Proof
		$passportproof =$_FILES['r_passportproof']['name'];    //Passport Proof
		$othergovernmentproof =$_FILES['r_othergovernmentproof']['name'];    //Other Government Valid Proof
	
		$datetime=$IISMethods->getdatetime();
		$unqid = $IISMethods->generateuuid();

		if($r_fname && $r_lname && $r_email && $r_mobile && $r_dob && $r_nationality && ($r_password || $isnormal == 0) && $qataridproof && $passportproof)
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
								$imagePath_qataridproof = $IISMethods->uploadallfiles(0,'memberproof',$qataridproof,$sourcePath_qataridproof,$_FILES['r_qataridproof']['type'],$unqid.'1');
							}

							$imagePath_passportproof='';
							if($passportproof)
							{
								$sourcePath_passportproof = $_FILES['r_passportproof']['tmp_name'];
								$imagePath_passportproof = $IISMethods->uploadallfiles(0,'memberproof',$passportproof,$sourcePath_passportproof,$_FILES['r_passportproof']['type'],$unqid.'2');
							}

							$imagePath_othergovernmentproof='';
							if($othergovernmentproof)
							{
								$sourcePath_othergovernmentproof = $_FILES['r_othergovernmentproof']['tmp_name'];
								$imagePath_othergovernmentproof = $IISMethods->uploadallfiles(0,'memberproof',$othergovernmentproof,$sourcePath_othergovernmentproof,$_FILES['r_othergovernmentproof']['type'],$unqid.'3');
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
								'[webid]'=>$webid,	
								'[refmemberid]'=>$mssqldefval['uniqueidentifier'],	
								'[platform]'=>$platform,	
								'[isactive]'=>1,
								'[isverified]'=>0,
								'[iscontactverified]'=>1,
								'[isemailverified]'=>1,
								'[timestamp]'=>$datetime,	
								'[entry_date]'=>$datetime,	

							);
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


							//Send User Register Request Email
							$DB->userregisterrequestemailssms(1,$unqid);   //type 1-Email,2-SMS

							//Send User Register Request SMS
							$DB->userregisterrequestemailssms(2,$unqid);   //type 1-Email,2-SMS


							$status = 1;
							$message=$errmsg['registersuccess'];
							
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


}	
	
require_once dirname(__DIR__, 2).'\config\apifoot.php';  

?>

  