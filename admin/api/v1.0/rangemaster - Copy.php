<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\rangemaster.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];

	if($action=='insertrangemaster')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$rangename=$IISMethods->sanitize($_POST['rangename']);
		$lanename=$_POST['tbllanename'];
		$starttime=$_POST['tblstarttime'];
		$endtime=$_POST['tblendtime'];
		

		$id=$IISMethods->sanitize($_POST['id']);
		
		if($rangename && sizeof($lanename) > 0 && sizeof($starttime) > 0 && sizeof($endtime) > 0)
		{
			$insqry=array(
				'[rangename]'=>$rangename,			
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT rangename from tblrangemaster where  rangename=:rangename";
				$parms = array(
                    ':rangename'=>$rangename,
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
						$insqry['[entry_uid]']=$uid;	
						$insqry['[entry_date]']=$IISMethods->getdatetime();
						$insqry['[isactive]']=1;	

						$DB->executedata('i','tblrangemaster',$insqry,'');

						for($i=0;$i<sizeof($lanename);$i++)
						{
							$sbunqid = $IISMethods->generateuuid();
							$subdata = array(
								'[id]'=>$sbunqid,
								'[rangeid]'=>$unqid,
								'[lanename]'=>$lanename[$i],
								'[timestamp]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tblrangelane',$subdata,'');
						}

						for($i=0;$i<sizeof($starttime);$i++)
						{
							$sbunqid = $IISMethods->generateuuid();
							$subdata = array(
								'[id]'=>$sbunqid,
								'[rangeid]'=>$unqid,
								'[fromtime]'=>$starttime[$i],
								'[totime]'=>$endtime[$i],
								'[timestamp]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tblrangetime',$subdata,'');
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
				$qrychk="SELECT rangename from tblrangemaster where  rangename=:rangename AND id<>:id";
				$parms = array(
					':rangename'=>$rangename,
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

						$unqid = $IISMethods->generateuuid();
						
                        $insqry['[update_uid]']=$uid;	
                        $insqry['[update_date]']=$IISMethods->getdatetime();
    
                        $extraparams=array(
                            '[id]'=>$id
                        );
                        $DB->executedata('u','tblrangemaster',$insqry,$extraparams);

                        $delparams=array(
                            '[rangeid]'=>$id
                        );
                        $DB->executedata('d','tblrangelane','',$delparams);

                        for($i=0;$i<sizeof($lanename);$i++)
                        {
                            $sbunqid = $IISMethods->generateuuid();
                            $subdata = array(
                                '[id]'=>$sbunqid,
                                '[rangeid]'=>$id,
                                '[lanename]'=>$lanename[$i],
                                '[timestamp]'=>$IISMethods->getdatetime(),
                            );
                            $DB->executedata('i','tblrangelane',$subdata,'');
                        }

                        $delparams=array(
                            '[rangeid]'=>$id
                        );
                        $DB->executedata('d','tblrangetime','',$delparams);

                        for($i=0;$i<sizeof($starttime);$i++)
                        {
                            $sbunqid = $IISMethods->generateuuid();
                            $subdata = array(
                                '[id]'=>$sbunqid,
                                '[rangeid]'=>$id,
                                '[fromtime]'=>$starttime[$i],
                                '[totime]'=>$endtime[$i],
                                '[timestamp]'=>$IISMethods->getdatetime(),
                            );
                            $DB->executedata('i','tblrangetime',$subdata,'');
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
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='deleterangemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{		
            try 
            {
                $DB->begintransaction();
        
                $delparams=array(
                    '[rangeid]'=>$id
                );
                $DB->executedata('d','tblrangelane','',$delparams);

                $extraparams=array(
                    '[rangeid]'=>$id
                );
                $DB->executedata('d','tblrangetime','',$extraparams);

                $extraparams=array(
                    '[id]'=>$id
                );
                $DB->executedata('d','tblrangemaster','',$extraparams);
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
											
					$delparams=array(
						'[rangeid]'=>$IISMethods->sanitize($id)
					);
					$DB->executedata('d','tblrangelane','',$delparams);

					$extraparams=array(
						'[rangeid]'=>$IISMethods->sanitize($id)
					);
					$DB->executedata('d','tblrangetime','',$extraparams);

					$extraparams=array(
						'[id]'=>$IISMethods->sanitize($id)
					);
					$DB->executedata('d','tblrangemaster','',$extraparams);
					
				}
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
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='editrangemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		
		if($id)
		{
			$qrychk="select * from tblrangemaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);
			
			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['rangename']=$IISMethods->sanitize($row['rangename']);
				
				
				$subqrychk="SELECT rl.id,rl.lanename from tblrangelane rl where rl.rangeid=:id order by rl.timestamp desc";
				$parms = array(
					':id'=>$id,
				);
				$subresult_ary=$DB->getmenual($subqrychk,$parms);
				$i=0;
				foreach($subresult_ary as $subrow)
				{
					$response['resultlane'][$i]['lanename']=$IISMethods->sanitize($subrow['lanename']);
					$i++;
				}

                $subqrychk="SELECT rt.id,rt.fromtime,rt.totime from tblrangetime rt where rt.rangeid=:id  order by rt.timestamp desc";
				$parms = array(
					':id'=>$id,
				);
				$subresult_ary=$DB->getmenual($subqrychk,$parms);
				$i=0;
				foreach($subresult_ary as $subrow)
				{
					$response['resulttime'][$i]['starttime']=$IISMethods->sanitize($subrow['fromtime']);
                    $response['resulttime'][$i]['endtime']=$IISMethods->sanitize($subrow['totime']);
					$i++;
				}

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
	else if($action=='listrangemaster')   
	{
		//error_reporting(1);
		$rangemasters=new rangemaster();
		$qry="select rm.timestamp as primary_date,rm.id,rm.rangename,rm.isactive
			from tblrangemaster rm
			where rm.rangename like :rangefilter order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':rangefilter'=>$filter,
		);
		$rangemasters=$DB->getmenual($qry,$parms,'rangemaster');
		
		if($responsetype=='HTML')
		{
			if($rangemasters)
			{
				$i=0;
				foreach($rangemasters as $rangemaster)
				{
					$id="'".$rangemaster->id."'";
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
						$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($rangemaster->id).'" name="bulkdelete[]">';
						$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
						$htmldata.='</div>';
						$htmldata.='</div>';
						$htmldata.='</td>';
					}
                    $htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Range Details" onclick="viewrangedata('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($rangemaster->rangename,'OUT').'</a></td>';		
							
					
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($rangemaster->isactive==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeactivestatus('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeactivestatus('.$id.')">';
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
				if($page<=0 && sizeof($rangemasters)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($rangemasters);
		}


		$common_listdata=$rangemasters;
	} 
	else if($action=='viewrangedata')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="SELECT rm.id,rm.rangename,rm.isactive 
			from tblrangemaster rm
			where rm.id=:id";
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
					$tbldata.='<label>Range Name <span>:</span></label>';
					$tbldata.='</div>';
					$tbldata.='<div class="col-12 col-sm-8 col-lg-6">';
					$tbldata.='<label class="label-view"><b>'.$row['rangename'].'</b></label>';
					$tbldata.='</div></div></div>';
													
				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';										
				$tbldata.='</div>';

				$subqrychk="SELECT rl.id,rl.lanename from tblrangelane rl where rl.rangeid=:id  order by rl.timestamp desc";
				$parms = array(
					':id'=>$id,
				);
				$subresult_ary=$DB->getmenual($subqrychk,$parms);
				if(sizeof($subresult_ary)>0)
				{
					$tbldata.='<table class="table table-bordered sctable table-striped table-hover table-fixed" id="tblshowquot" style="font-size: 1.1em;text-align: center;">';
					$tbldata.='<thead style="border-bottom: 1px solid rgb(221, 221, 221);">';
					$tbldata.='<tr class="info"> ';     	            	                                                                       
					$tbldata.='<th style="text-align:left" width="60%">Lanename</th>';                                            
					$tbldata.='</tr>';									                
					$tbldata.='</thead>';
					$tbldata.='<tbody>';
					foreach($subresult_ary as $result)
					{
						$tbldata.='<tr> ';     	            	                                                                       
						$tbldata.='<td style="text-align:left">'.$result['lanename'].'</td>';
						$tbldata.='</tr>';	
					}
					$tbldata.='</tbody>';
					$tbldata.='</table>';
				}

                $subqrychk="SELECT rt.id,rt.fromtime,rt.totime from tblrangetime rt where rt.rangeid=:id  order by rt.timestamp desc";
				$parms = array(
					':id'=>$id,
				);
				$subresult_ary=$DB->getmenual($subqrychk,$parms);
				if(sizeof($subresult_ary)>0)
				{
					$tbldata.='<table class="table table-bordered sctable table-striped table-hover table-fixed" id="tblshowquot" style="font-size: 1.1em;text-align: center;">';
					$tbldata.='<thead style="border-bottom: 1px solid rgb(221, 221, 221);">';
					$tbldata.='<tr class="info"> ';     	            	                                                                       
					$tbldata.='<th style="text-align:left" width="60%">Start Time</th>'; 
                    $tbldata.='<th style="text-align:center" width="40%">End Time</th>';                                         
					$tbldata.='</tr>';									                
					$tbldata.='</thead>';
					$tbldata.='<tbody>';
					foreach($subresult_ary as $result)
					{
						$tbldata.='<tr> ';     	            	                                                                       
						$tbldata.='<td style="text-align:left">'.$result['fromtime'].'</td>';
                        $tbldata.='<td style="text-align:center">'.$result['totime'].'</td>';  
						$tbldata.='</tr>';	
					}
					$tbldata.='</tbody>';
					$tbldata.='</table>';
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
	else if($action=='changeactivestatus')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select isactive from tblrangemaster where id=:id";
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
				$DB->executedata('u','tblrangemaster',$insqry,$extraparams);
			
			
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
}



require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  