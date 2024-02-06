<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\instructiongroup.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$userpagename);

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='insertinstructiongroup')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$groupname=$IISMethods->sanitize($_POST['groupname']);
		$instructionid = $_POST['instructionid'];
		$id = $IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();
		if($groupname && sizeof($instructionid) > 0)
		{			
			$insqry=array(					
				'[groupname]'=>$groupname,				
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT groupname from tblinstructiongroup where groupname=:groupname";
				$parms = array(
					':groupname'=>$groupname,
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
						$insqry['[isactive]']=1;
						$insqry['[timestamp]']=$datetime;
						$insqry['[entry_uid]']=$uid;	
						$insqry['[entry_date]']=$datetime;

						$DB->executedata('i','tblinstructiongroup',$insqry,'');


						for($i=0;$i<sizeof($instructionid);$i++)
						{
							$qrychk="SELECT name from tblinstruction where id=:id";
							$parms = array(
								':id'=>$instructionid[$i],
							);
							$result_store=$DB->getmenual($qrychk,$parms);
							$igdid = $IISMethods->generateuuid();
							$insgroupdet=array(	
								'[id]'=>$igdid,				
								'[insgroupid]'=>$unqid,
								'[instructionid]'=>$instructionid[$i],
								'[instructionname]'=>$result_store[0]['name'],
								'[timestamp]'=>$datetime,
							);
							$DB->executedata('i','tblinstructiongroupdetail',$insgroupdet,'');
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
				$qrychk="SELECT groupname from tblinstructiongroup where groupname=:groupname AND id<>:id";
				$parms = array(
					':groupname'=>$groupname,
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
						$DB->executedata('u','tblinstructiongroup',$insqry,$extraparams);

						$delparams=array(
							'[insgroupid]'=>$id
						);
						$DB->executedata('d','tblinstructiongroupdetail','',$delparams);

						for($i=0;$i<sizeof($instructionid);$i++)
						{
							$qrychk="SELECT name from tblinstruction where id=:id";
							$parms = array(
								':id'=>$instructionid[$i],
							);
							$result_store=$DB->getmenual($qrychk,$parms);
							$igdid = $IISMethods->generateuuid();
							$insgroupdet=array(	
								'[id]'=>$igdid,				
								'[insgroupid]'=>$id,
								'[instructionid]'=>$instructionid[$i],
								'[instructionname]'=>$result_store[0]['name'],
								'[timestamp]'=>$datetime,
							);
							$DB->executedata('i','tblinstructiongroupdetail',$insgroupdet,'');
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
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deleteinstructiongroupmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{

			// $qrychk="SELECT case when(pm.contact !='' or cm.city != '') then 1 else 0 end as tem
			// from tbloperationmaster sm
			// left join tblcitymaster cm on cm.operationid = sm.id
			// left join tblpersonmaster pm on pm.operationid = sm.id and pm.isdelete = 0 
			// where sm.id=:id";
			// $parms = array(
			// 	':id'=>$id,
			// );
			// $result_ary=$DB->getmenual($qrychk,$parms);
			// $row=$result_ary[0];

			// if($row['tem'] > 0)
			// {
			// 	$status=0;
			// 	$message=$errmsg['inuse'];
			// }
			// else
			// {
				try 
				{
					$DB->begintransaction();

					$extraparams1=array(
						'[insgroupid]'=>$id
					);
					$DB->executedata('d','tblinstructiongroupdetail','',$extraparams1);

					$extraparams=array(
						'[id]'=>$id
					);
					$DB->executedata('d','tblinstructiongroup','',$extraparams);
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
			// }
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
					// $qrychk="SELECT case when(pm.contact !='' or cm.city != '') then 1 else 0 end as tem,sm.name
					// from tbloperationmaster sm
					// left join tblcitymaster cm on cm.operationid = sm.id
					// left join tblpersonmaster pm on pm.operationid = sm.id and pm.isdelete = 0 
					// where sm.id=:id";
					// $parms = array(
					// 	':id'=>$id,
					// );
					// $result_ary=$DB->getmenual($qrychk,$parms);
					// $row=$result_ary[0];

					// if($row['tem'] > 0)
					// {
					// 	$usemenu.=$row['name'].",";
					// }
					// else
					// {
						$extraparams1=array(
							'[insgroupid]'=>$id
						);
						$DB->executedata('d','tblinstructiongroupdetail','',$extraparams1);
	
						$extraparams=array(
							'[id]'=>$id
						);
						$DB->executedata('d','tblinstructiongroup','',$extraparams);
					// }
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
	else if($action=='editinstructiongroupmaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select i.id,i.groupname,
			ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), ig.instructionid) AS [text()] FROM tblinstructiongroupdetail ig WHERE CONVERT(VARCHAR(255), ig.insgroupid)=i.id FOR XML PATH ('')),2,1000000),'') as instructionid 
			from tblinstructiongroup i where i.id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];
				$response['id']=$IISMethods->sanitize($row['id']);
				$response['groupname']=$IISMethods->sanitize($row['groupname']);
				$response['instructionid']=$IISMethods->sanitize($row['instructionid']);

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
	else if($action=='listinstructiongroupmaster')   
	{
		$instructiongroups=new instructiongroup();
		$qry="select timestamp as primary_date,id,groupname,isactive from tblinstructiongroup where (groupname like :namefilter ) order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':namefilter'=>$filter,
		);
		$instructiongroups=$DB->getmenual($qry,$parms,'instructiongroup');
		
		if($responsetype=='HTML')
		{
			if($instructiongroups)
			{
				$i=0;
				foreach($instructiongroups as $instructiongroup)
				{
					$id="'".$instructiongroup->id."'";
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
					// $htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="editdata('.$IISMethods->sanitize($id).')"><i class="bi bi-pencil"></i> Edit</a>';
					// $htmldata.='<a class="dropdown-item" href="javascript:void(0);" onclick="deletedata('.$IISMethods->sanitize($id).')"><i class="bi bi-trash"></i> Delete</a>';
					
					$htmldata.='<td class="tbl-check d-none">';
					$htmldata.='<div class="text-center">';
					$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
					$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($instructiongroup->id).'" name="bulkdelete[]">';
					$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
					$htmldata.='</div>';
					$htmldata.='</div>';
					$htmldata.='</td>';
					$htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Instruction Details" onclick="viewinstructiondetaildata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($instructiongroup->groupname,'OUT').'</a></td>';
					$htmldata.='</tr>';
					$i++;
				}
				$status=1;
				$message=$errmsg['success'];
			}
			else
			{	
				if($page<=0 && sizeof($instructiongroups)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($instructiongroups);
		}


		$common_listdata=$instructiongroups;
	} 

	else if($action=='viewinstructiondetaildata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select igd.*,i.name as insname,i.displayorder 
				from tblinstructiongroupdetail igd 
				inner join tblinstruction i on i.id=igd.instructionid 
				where igd.insgroupid=:id order by i.displayorder";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);
			$tbldata='';
			if(sizeof($result_ary)>0)
			{
				for($i=0;$i<sizeof($result_ary);$i++)
				{
					$row=$result_ary[$i];
					$tbldata.='<div class="col-lg-12 col-md-12 col-12 layout-spacing">';
						$tbldata.='<div class="widget pt-2 pb-2">';
							$tbldata.='<div class="widget-content row">';

								$tbldata.='<div class="col-12 col-sm-6 col-md-12 col-lg-12">';
								$tbldata.='<div class="row1">';
								$tbldata.='<div class="table-responsive">';
								$tbldata.='<div class="col-12 p-0">';
								$tbldata.=$row['insname'];
								$tbldata.='</div>';
								$tbldata.='</div>';
								$tbldata.='</div>';
								$tbldata.='</div>';


							$tbldata.='</div>';
						$tbldata.='</div>';
					$tbldata.='</div>';
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
	
	



}

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  