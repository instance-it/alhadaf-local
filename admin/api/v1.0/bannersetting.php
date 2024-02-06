<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

error_reporting(1);
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='fillcontent')   
	{
		$qry="SELECT * FROM tblsetting";
		$parms = array();
		$result_ary=$DB->getmenual($qry,$parms);

		if(sizeof($result_ary)>0)
		{
			$row=$result_ary[0];

			$response['id']=$IISMethods->sanitize($row['id']);
			$response['home_top_text']=$IISMethods->sanitize($row['home_top_text']);
			$response['home_top_buttontext']=$IISMethods->sanitize($row['home_top_buttontext']);
			$response['home_top_buttonurl']=$row['home_top_buttonurl'];
			if($row['home_top_video'])
			{
				$response['home_top_video']=$IISMethods->sanitize($config->getImageurl().$row['home_top_video']);
			}

			if($row['rb_video'])
			{
				$response['rb_video']=$IISMethods->sanitize($config->getImageurl().$row['rb_video']);
			}

			$status=1;
			$message=$errmsg['datafound'];

		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	else if($action=='insertsetting')   
	{
		$title=$IISMethods->sanitize($_POST['title']);
		$btntext =$IISMethods->sanitize($_POST['btntext']);
		$btntexturl =$IISMethods->sanitize($_POST['btntexturl']);
		$video = $_FILES['video']['name'];
		$rbvideo = $_FILES['rbvideo']['name'];
		
		if($video || $rbvideo || $btntext || $btntexturl || $title)
		{
			$qry="select id from tblsetting";
			$parms = array();
			$result_ary=$DB->getmenual($qry,$parms);
			if(sizeof($result_ary) > 0)
			{
				$row = $result_ary[0];
				$id = $row['id'];

				$insdata = array(
					'[home_top_text]'=>$title,
					'[home_top_buttontext]'=>$btntext,
					'[home_top_buttonurl]'=>$btntexturl,
				);

				if($video)
				{
					if($_FILES["video"]["type"] == "video/mp4")
					{
						$sourcePath = $_FILES['video']['tmp_name'];
						$targetPath = $IISMethods->uploadallfiles(1,'home',$video,$sourcePath,$_FILES['video']['type'],$id);
						$insdata['[home_top_video]'] = $targetPath;

					}
					else
					{
						$status=0;
						$message=$errmsg['filetype'];
					}
				}

				if($rbvideo)
				{
					if($_FILES["rbvideo"]["type"] == "video/mp4")
					{
						$sourcePath = $_FILES['rbvideo']['tmp_name'];
						$targetPath = $IISMethods->uploadallfiles(1,'rangebook',$rbvideo,$sourcePath,$_FILES['rbvideo']['type'],$id);
						$insdata['[rb_video]'] = $targetPath;
					}
					else
					{
						$status=0;
						$message=$errmsg['filetype'];
					}
				}
				
				try 
				{
					$DB->begintransaction();

					$extraparams=array(
						'[id]'=>$row['id'],
					);
					$DB->executedata('u','tblsetting',$insdata,$extraparams);

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
				if($video && $rb_video)
				{
					$unqid = $IISMethods->generateuuid();
					$insdata = array(
						'[id]'=>$unqid,
						'[home_top_text]'=>$title,
						'[home_top_buttontext]'=>$btntext,
						'[home_top_buttonurl]'=>$btntexturl,
						'[entry_uid]'=>$uid,
						'[entry_date]'=>$IISMethods->getdatetime(),
					);

					if($video)
					{
						if($_FILES["video"]["type"] == "video/mp4")
						{
							$sourcePath = $_FILES['video']['tmp_name'];
							$targetPath = $IISMethods->uploadallfiles(1,'home',$video,$sourcePath,$_FILES['video']['type'],$id);
							$insdata['[home_top_video]'] = $targetPath;
						}
						else
						{
							$status=0;
							$message=$errmsg['filetype'];
						}
					}
	
					if($rb_video)
					{
						if($_FILES["rbvideo"]["type"] == "video/mp4")
						{
							$sourcePath = $_FILES['rbvideo']['tmp_name'];
							$targetPath = $IISMethods->uploadallfiles(1,'rangebook',$rb_video,$sourcePath,$_FILES['rbvideo']['type'],$id);
							$insdata['[rb_video]'] = $targetPath;
						}
						else
						{
							$status=0;
							$message=$errmsg['filetype'];
						}
					}

					try 
					{
						$DB->begintransaction();
						$DB->executedata('i','tblsetting',$insdata,'');
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
				else
				{
					$status=0;
					$message=$errmsg['reqired'];
				}
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

  