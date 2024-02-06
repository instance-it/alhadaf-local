<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

$appmenuname =trim($_POST['appmenuname']);
$activeusertypeid=trim($_POST['activeusertypeid']); 

error_reporting(1);
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	//Update Profile
	if($action=='updateprofiledata')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$emailid=$IISMethods->sanitize($_POST['emailid']);
		//$mobilenumber1=$IISMethods->sanitize($_POST['mobilenumber1']);
		$mobilenumber2=$IISMethods->sanitize($_POST['mobilenumber2']);

		$profileimg =$_FILES['profileimg']['name'];

		if($platform==6)
		{
			$sourcePath = $_FILES['profileimg']['tmp_name'];
			$targetPath = $IISMethods->uploadallfiles(1,'profile',$profileimg,$sourcePath,$_FILES['profileimg']['type'],$uid);
			$insqry=array(
				'[profileimg]'=>$targetPath,
			);
		}
		else
		{
			$insqry=array(
				'[mobilenumber2]'=>$mobilenumber2,
				'[email]'=>$emailid,
			);
		}
			

		if($formevent=='editright')
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

				if($platform==6)
				{
					$status=1;
					$message=$errmsg['profilephotoupdate'];
				}
				else
				{
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
		
	}
	//Change Password
	else if($action=='changepassword')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$opass=$IISMethods->sanitize($_POST['opass']);
		$npass=$IISMethods->sanitize($_POST['npass']);
		
		$insqry=array(
			'[password]'=>md5($npass),
			'[strpassword]'=>$npass,
		);

		if($formevent=='editright')
		{
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
		
	}
	//Fill Profile Data
	else if($action=='fillprofiledata')   
	{
		$qrychk="SELECT distinct id,personname,contact,mobilenumber2,email,case when (isnull(profileimg,'')='') then 'img/user1.png' else profileimg end as profileimg from tblpersonmaster WHERE id=:id";
		$parms = array(
			':id'=>$uid,
		);
		$result_ary=$DB->getmenual($qrychk,$parms);
		if(sizeof($result_ary)>0)
		{
			$row=$result_ary[0];

			$response['id']=$IISMethods->sanitize($row['id']);
			$response['personname']=$IISMethods->sanitize($row['personname']);
			$response['contact']=$IISMethods->sanitize($row['contact']);
			$response['mobilenumber2']=$IISMethods->sanitize($row['mobilenumber2']);
			$response['email']=$IISMethods->sanitize($row['email']);
			$response['profileimg']=$IISMethods->sanitize($config->getImageurl().$row['profileimg']);
	
			
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=1;
			$message=$errmsg['nodatafound'];
		}
		
	} 
}

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  