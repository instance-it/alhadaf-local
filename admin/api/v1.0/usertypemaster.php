<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\usertype.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='insertusertype')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$usertype=$IISMethods->sanitize($_POST['usertype']);

		$isweblogin=0;
		if($IISMethods->sanitize($_POST['isweblogin']))
		{
			$isweblogin = $IISMethods->sanitize($_POST['isweblogin']);
		}

		$isapplogin=0;
		if($IISMethods->sanitize($_POST['isapplogin']))
		{
			$isapplogin = $IISMethods->sanitize($_POST['isapplogin']);
		}
		
		$hasposlogin=0;
		if($IISMethods->sanitize($_POST['hasposlogin']))
		{
			$hasposlogin = $IISMethods->sanitize($_POST['hasposlogin']);
		}
		
		$id=$IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();
		if($usertype)
		{			
			$insqry=array(					
				'[usertype]'=>$usertype,
				'[hasweblogin]'=>$isweblogin,
				'[hasapplogin]'=>$isapplogin,
				'[hasposlogin]'=>$hasposlogin,
			);

			if($formevent=='addright')
			{
				$qrychk="SELECT usertype from tblusertypemaster where usertype=:usertype";
				$parms = array(
					':usertype'=>$usertype,
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
						$insqry['[entry_uid]']=$datetime;

						$DB->executedata('i','tblusertypemaster',$insqry,'');

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
				$qrychk="SELECT usertype from tblusertypemaster where usertype=:usertype AND id<>:id";
				$parms = array(
					':usertype'=>$usertype,
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
						$DB->executedata('u','tblusertypemaster',$insqry,$extraparams);

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
	else if($action=='deleteusertypemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$bulkid=$_POST['bulkid'];
		$bulk=explode (",", $bulkid); 
		if($id)
		{
			$qrychk="select id from tblpersonutype where utypeid=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);
			if(sizeof($result_ary) > 0)
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
					$DB->executedata('d','tblusertypemaster','',$extraparams);
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
					$qrychk="select um.usertype from tblpersonutype pu inner join tblusertypemaster um on um.id=pu.utypeid where pu.utypeid=:id group by um.usertype ";
					$parms = array(
						':id'=>$id,
					);
					$result_ary=$DB->getmenual($qrychk,$parms);
					if(sizeof($result_ary) > 0)
					{
						$row=$result_ary[0];
						$usemenu.=$row['usertype'].",";
					}
					else
					{
						$extraparams=array(
							'[id]'=>$IISMethods->sanitize($id)
						);
						$DB->executedata('d','tblusertypemaster','',$extraparams);
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
	else if($action=='editusertypemaster')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="select id,usertype,hasweblogin,hasapplogin,hasposlogin from tblusertypemaster where id=:id";
			$parms = array(
				':id'=>$id,
			);
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];
				$response['id']=$IISMethods->sanitize($row['id']);
				$response['usertype']=$IISMethods->sanitize($row['usertype']);
				$response['isweblogin']=$IISMethods->sanitize($row['hasweblogin']);
				$response['isapplogin']=$IISMethods->sanitize($row['hasapplogin']);
				$response['hasposlogin']=$IISMethods->sanitize($row['hasposlogin']);
			}
			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='listusertypemaster')   
	{
		$utype = array($config->getMemberutype(),$config->getStoreutype(),$config->getGuestutype());

		$usertypes=new usertype();
		$adminutype=$config->getAdminutype();
		$qry="select timestamp as primary_date,id,usertype,hasweblogin,hasapplogin,hasposlogin 
		from tblusertypemaster where id not in (:usertypes) AND usertype like :filter order by $ordbycolumnname $ordby offset $start rows fetch next $per_page rows only";
		$parms = array(
			':filter'=>$filter,
			':usertypes'=>$adminutype,
		);
		$usertypes=$DB->getmenual($qry,$parms,'usertype');

		if($responsetype=='HTML')
		{
			if($usertypes)
			{
				$i=0;
				foreach($usertypes as $usertype)
				{
					$id="'".$usertype->id."'";
					$htmldata.='<tr data-index="'.$i.'">';
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || (sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{	
						$htmldata.='<td class="tbl-grid">';
						if(!in_array($IISMethods->sanitize($usertype->id), $utype))
						{
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
						
						}
						$htmldata.='</td>';
					}
					if((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						$htmldata.='<td class="tbl-check d-none">';
						if(!in_array($IISMethods->sanitize($usertype->id), $utype))
						{
							$htmldata.='<div class="text-center">';
							$htmldata.='<div class="custom-control custom-checkbox m-0 mx-auto w-auto">';
							$htmldata.='<input type="checkbox" class="custom-control-input bulkdelete" id="bulkdelete'.$i.'" value="'.$IISMethods->sanitize($usertype->id).'" name="bulkdelete[]">';
							$htmldata.='<label class="custom-control-label mb-0" for="bulkdelete'.$i.'"></label>';
							$htmldata.='</div>';
							$htmldata.='</div>';
						}
						$htmldata.='</td>';
					
					}
					$htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($usertype->usertype,'OUT').'</td>';
					
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($usertype->hasweblogin==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeisweblogin('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changeisweblogin('.$id.')">';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
	
					}
					if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
					{
						if($usertype->hasposlogin==1)
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changehasposlogin('.$id.')" checked>';
								$htmldata.='<span class="slider round"></span>';
							$htmldata.='</label></td>';
						}
						else
						{
							$htmldata.='<td class="tbl-name"><label class="switch">';
								$htmldata.='<input type="checkbox" onclick="changehasposlogin('.$id.')">';
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
				if($page<=0 && sizeof($usertypes)<=0)
                {
					$htmldata.=file_get_contents($config->getNoDataFound());
					$message=$errmsg['nodatafound'];
                }
			}
			$response['data']=$htmldata;
		}
		else if($responsetype=='JSON')
		{
			$response['data']= json_encode($usertypes);
		}

		
		$common_listdata=$usertypes;
	}
	
	else if($action=='changeisweblogin')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select hasweblogin from tblusertypemaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];
				if($row['hasweblogin']==1)
				{
					$isweblogin=0;
				}
				else
				{
					$isweblogin=1;
				}
				$insqry['[hasweblogin]']=$isweblogin;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tblusertypemaster',$insqry,$extraparams);

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

	else if($action=='changehasposlogin')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select hasposlogin from tblusertypemaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];
				if($row['hasposlogin']==1)
				{
					$hasposlogin=0;
				}
				else
				{
					$hasposlogin=1;
				}
				$insqry['[hasposlogin]']=$hasposlogin;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tblusertypemaster',$insqry,$extraparams);

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
	else if($action=='changeisapplogin')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{	
			try 
			{
				$DB->begintransaction();
				
				$qrychk="select hasapplogin from tblusertypemaster where id=:id";
				$parms = array(
					':id'=>$id,
				);
				$result_ary=$DB->getmenual($qrychk,$parms);
				$row=$result_ary[0];
				if($row['hasapplogin']==1)
				{
					$isapplogin=0;
				}
				else
				{
					$isapplogin=1;
				}
				$insqry['[hasapplogin]']=$isapplogin;	
				$extraparams=array(
					'[id]'=>$id
				);
				$DB->executedata('u','tblusertypemaster',$insqry,$extraparams);

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

  