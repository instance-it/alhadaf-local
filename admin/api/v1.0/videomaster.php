<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\video.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$userpagename);

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='insertvideo')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$videoid=$IISMethods->sanitize($_POST['videoid']);
		$displayorder=$IISMethods->sanitize($_POST['displayorder']);
		$id=$IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();
		if($videoid)
		{			
			$insqry=array(					
				'[videoid]'=>$videoid,
				'[displayorder]'=>$displayorder,					
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT videoid from tblvideomaster where videoid=:videoid or displayorder=:displayorder";
				$parms = array(
					':videoid'=>$videoid,
					':displayorder'=>$displayorder,
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

						$DB->executedata('i','tblvideomaster',$insqry,'');

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
				$qrychk="SELECT videoid from tblvideomaster where (videoid=:videoid or displayorder=:displayorder) AND id<>:id";
				$parms = array(
					':videoid'=>$videoid,
					':displayorder'=>$displayorder,
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
							'[id]'=>$id
						);
						$DB->executedata('u','tblvideomaster',$insqry,$extraparams);

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
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deletevideomaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('d','tblvideomaster','',$extraparams);
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
					$DB->executedata('d','tblvideomaster','',$extraparams);
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
	else if($action=='editvideomaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,videoid,displayorder from tblvideomaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];
				$response['id']=$IISMethods->sanitize($row['id']);
				$response['videoid']=$IISMethods->sanitize($row['videoid']);
				$response['displayorder']=$IISMethods->sanitize($row['displayorder']);

			}
			$status=1;
			$message=$errmsg['datafound'];
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='listvideomaster')   
	{
		$videos=new video();
		$qry="select timestamp as primary_date,id,videoid,displayorder,isactive 
			from tblvideomaster where (videoid like :videofilter or displayorder like :orderfilter) 
			order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':videofilter'=>$filter,
			':orderfilter'=>$filter,
		);
		$videos=$DB->getmenual($qry,$parms,'video');
		
		if($responsetype=='HTML')
		{
			if($videos)
			{
				$i=0;
				foreach($videos as $video)
				{
					$id="'".$video->id."'";
					$videoid="'".$video->videoid."'";
					$htmldata.='<tr data-index="'.$i.'">';
			
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || (sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-grid">';
						$htmldata.='<div class="dropdown table-dropdown">';
						$htmldata.='<button class="dropdown-toggle btn-tbl rounded-circle" type="button" id="tableDropdown3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>';
						$htmldata.='<div class="dropdown-menu" aria-labelledby="tableDropdown3">';

						if(((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright  - only self person data show
						{
							$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="editdata('.$IISMethods->sanitize($id).')"><i class="bi bi-pencil"></i> Edit</a>';
						}
						if(((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright  - only self person data show
						{
							$htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="deletedata('.$IISMethods->sanitize($id).')"><i class="bi bi-trash"></i> Delete</a>';
						}

						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
					
					$htmldata.='<td class="tbl-check d-none">';
					$htmldata.='<div class="text-center">';
					$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
					$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($video->id).'" name="bulkdelete[]">';
					$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
					$htmldata.='</div>';
					$htmldata.='</div>';
					$htmldata.='</td>';
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Video" onclick="viewvideodata('.$IISMethods->sanitize($id).','.$videoid.')">'.$IISMethods->sanitize($video->videoid).'</a></td>';
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($video->displayorder).'</td>';

					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($video->isactive==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changevideostatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changevideostatus('.$id.')">';
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
				if($page<=0 && sizeof($videos)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($videos);
		}


		$common_listdata=$videos;
	} 
	else if($action=='changevideostatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			
			$qrychk="select isactive from tblvideomaster where id=:id";
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
			$DB->executedata('u','tblvideomaster',$insqry,$extraparams);
		
		
			$status=1;
			$message=$errmsg['update'];
		}
		else
		{
			$message=$errmsg['reqired'];
		}
	}
	
	

}

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  