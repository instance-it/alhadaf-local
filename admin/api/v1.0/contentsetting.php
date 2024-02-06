<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';

error_reporting(1);
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='fillcontenttype')   
	{
		$qry="SELECT id,type from tblwebcontenttype WHERE isactive = 1";
		$parms = array(
		);
		$result_ary=$DB->getmenual($qry,$parms);
		$htmldata='';
		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'">'.$row['type'].'</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	}
	else if($action=='fillcontent')   
	{
		$contenttypeid=$IISMethods->sanitize($_POST['contenttypeid']);
		if($contenttypeid)
		{
			$qry="select * from tblwebcontent where contenttypeid = :contenttypeid";
			$parms = array(
				':contenttypeid'=>$contenttypeid,
			);
			$result_ary=$DB->getmenual($qry,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];

				$response['id']=$IISMethods->sanitize($row['id']);
				$response['contenttypeid']=$IISMethods->sanitize($row['contenttypeid']);
				$response['title']=$IISMethods->sanitize($row['title']);
				$response['title2']=$IISMethods->sanitize($row['title2']);
				$response['description']=$row['description'];
				if($row['img'])
				{
					$response['img']=$IISMethods->sanitize($config->getImageurl().$row['img']);
				}

				$qrysub="select * from tblwebcontentdetail where contenttypeid=:contenttypeid AND type = 1 order by (case when (displayorder>0) then displayorder else 99999 end)";
				$parms = array(
					':contenttypeid'=>$contenttypeid,
				);
				$result_subary=$DB->getmenual($qrysub,$parms);
				if(sizeof($result_subary)>0)
				{	
					for($i=0;$i<sizeof($result_subary);$i++)
					{
						$subrow=$result_subary[$i];
						$response['result'][$i]['id']=$IISMethods->sanitize($subrow['id']);
						$response['result'][$i]['contenttypeid']=$IISMethods->sanitize($subrow['contenttypeid']);
						$response['result'][$i]['title']=$IISMethods->sanitize($subrow['title']);
						$response['result'][$i]['title']=$IISMethods->sanitize($subrow['title']);
						$response['result'][$i]['displayorder']=$IISMethods->sanitize($subrow['displayorder']);
						$response['result'][$i]['descr']=$IISMethods->sanitize($subrow['descr']);
						$response['result'][$i]['imgpath']=$IISMethods->sanitize($config->getImageurl().$subrow['img']);
						$response['result'][$i]['imgpath1']=$IISMethods->sanitize($subrow['img']);
					}
				}

				$qrysub="select * from tblwebcontentdetail where contenttypeid=:contenttypeid AND type = 2 order by (case when (displayorder>0) then displayorder else 99999 end)";
				$parms = array(
					':contenttypeid'=>$contenttypeid,
				);
				$result_subary=$DB->getmenual($qrysub,$parms);
				if(sizeof($result_subary)>0)
				{	
					for($i=0;$i<sizeof($result_subary);$i++)
					{
						$subrow=$result_subary[$i];
						$response['abtresult'][$i]['id']=$IISMethods->sanitize($subrow['id']);
						$response['abtresult'][$i]['contenttypeid']=$IISMethods->sanitize($subrow['contenttypeid']);
						$response['abtresult'][$i]['title']=$IISMethods->sanitize($subrow['title']);
						$response['abtresult'][$i]['count']=$IISMethods->sanitize($subrow['abtcount']);
						$response['abtresult'][$i]['imgpath']=$IISMethods->sanitize($config->getImageurl().$subrow['img']);
						$response['abtresult'][$i]['imgpath1']=$IISMethods->sanitize($subrow['img']);
					}
				}


				$qrysub="select * from tblwebcontentdetail where contenttypeid=:contenttypeid AND type = 3 order by (case when (displayorder>0) then displayorder else 99999 end)";
				$parms = array(
					':contenttypeid'=>$contenttypeid,
				);
				$result_subary=$DB->getmenual($qrysub,$parms);
				if(sizeof($result_subary)>0)
				{	
					for($i=0;$i<sizeof($result_subary);$i++)
					{
						$subrow=$result_subary[$i];
						$response['itnctresult'][$i]['id']=$IISMethods->sanitize($subrow['id']);
						$response['itnctresult'][$i]['contenttypeid']=$IISMethods->sanitize($subrow['contenttypeid']);
						$response['itnctresult'][$i]['invtnc']=$IISMethods->sanitize($subrow['invtnc']);
						$response['itnctresult'][$i]['displayorder']=$IISMethods->sanitize($subrow['displayorder']);
					}
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
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='insertcontentsetting')   
	{
		$contenttypeid=$IISMethods->sanitize($_POST['contenttypeid']);
		$description=$_POST['contentdescr'];
		$description=$_POST['descr'];
		$title =$IISMethods->sanitize($_POST['title']);
		$maintitle =$IISMethods->sanitize($_POST['maintitle']);
		$title2 =$IISMethods->sanitize($_POST['title2']);
		$img=$_FILES['img']['name'];

		$obstitle = $_POST['tblobstitle'];
		$obsdescr = $_POST['tblobsdescr'];
		$obsdisplayorder = $_POST['tblobsdisplayorder'];
		$obsimg = $_POST['tblobsimg'];

		$abttitle = $_POST['tblabttitle'];
		$abtcount = $_POST['tblabtcount'];
		$abtimg = $_POST['tblabtimg'];

		$waetitle = $_POST['tblwaetitle'];
		$waedisplayorder = $_POST['tblwaedisplayorder'];

		$tblitnc = $_POST['tblitnc'];
		$tblidisplayorder = $_POST['tblidisplayorder'];
		
	
		if($contenttypeid)
		{
			$qry="select id from tblwebcontent where contenttypeid=:contenttypeid";
			$parms = array(
				':contenttypeid'=>$contenttypeid,
			);
			$result_ary=$DB->getmenual($qry,$parms);
			if(sizeof($result_ary) > 0)
			{
				$row = $result_ary[0];


				if($contenttypeid == $config->getTermsConditionId() || $contenttypeid == $config->getPrivacyPolicyId()) // for Terms & Conditions && Privacy
				{
					if($contenttypeid && $description)
					{
						try 
						{
							$DB->begintransaction();
							$insdata = array(
								'[contenttypeid]'=>$contenttypeid,
								'[description]'=>$description,
								'[update_uid]'=>$uid,
								'[update_date]'=>$IISMethods->getdatetime(),
							);
							$extraparams=array(
								'[id]'=>$row['id'],
							);
							$DB->executedata('u','tblwebcontent',$insdata,$extraparams);

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
						$status=0;
						$message=$errmsg['reqired'];
					}
					
				}
				else if($contenttypeid == $config->getInvoiceTermsConditionsId()) // for Invoice Terms & Conditions 
				{
					if($contenttypeid && sizeof($tblitnc) > 0)
					{
						try 
						{
							$DB->begintransaction();
							$insdata = array(
								'[contenttypeid]'=>$contenttypeid,
								'[title]'=>$maintitle,
								'[description]'=>'',
								'[update_uid]'=>$uid,
								'[update_date]'=>$IISMethods->getdatetime(),
							);
							$extraparams=array(
								'[id]'=>$row['id'],
							);
							$DB->executedata('u','tblwebcontent',$insdata,$extraparams);


							$delextraparams=array(
								'[contenttypeid]'=>$contenttypeid,
							);
							$DB->executedata('d','tblwebcontentdetail','',$delextraparams);
							
							if(sizeof($tblitnc)>0)
							{
								for ($j=0; $j<sizeof($tblitnc); $j++) 
								{
									$subunqid = $IISMethods->generateuuid();
									
									$datains=array(
										'[id]'=>$subunqid,
										'[contenttypeid]'=>$contenttypeid,
										'[invtnc]'=>$tblitnc[$j],
										'[descr]'=>'',
										'[displayorder]'=>$tblidisplayorder[$j],
										'[type]'=>3,
									);

									$DB->executedata('i','tblwebcontentdetail',$datains,'');
								}
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
					else
					{
						$status=0;
						$message=$errmsg['reqired'];
					}
					
				}
				else if($contenttypeid == $config->getAboutUsId()) // For About us
				{
					if($contenttypeid && $title && $description)
					{
						if($img)
						{
							if($_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type'] == "image/jpeg" || $_FILES['img']['type'] == "image/png")
							{
								if($_FILES['img']['size']  <= $config->getWebsitefilesize())
								{
									try 
									{
										$DB->begintransaction();
										unlink('../../../assets/'.$row['img']);
										$sourcePath = $_FILES['img']['tmp_name'];
										$targetPath = $IISMethods->uploadallfiles(1,'aboutus',$img,$sourcePath,$_FILES['img']['type'],$row['id']);
				
										$insdata = array(
											'[contenttypeid]'=>$contenttypeid,
											'[title]'=>$title,
											'[title2]'=>$title2,
											'[img]'=>$targetPath,
											'[description]'=>$description,
											'[update_uid]'=>$uid,
											'[update_date]'=>$IISMethods->getdatetime(),
										);

										$extraparams=array(
											'[id]'=>$row['id'],
										);
										$DB->executedata('u','tblwebcontent',$insdata,$extraparams);
				
										$delextraparams=array(
											'[contenttypeid]'=>$contenttypeid,
										);
										$DB->executedata('d','tblwebcontentdetail','',$delextraparams);
										if(sizeof($obstitle)>0)
										{
											for ($j=0; $j<sizeof($obstitle); $j++) 
											{
												$subunqid = $IISMethods->generateuuid();
												$srcfile=$config->getImageurl().$obsimg[$j]; 
												
												$targetPath = $IISMethods->uploadallfiles(1,'aboutusdetail',$obsimg[$j],$srcfile,$_FILES['tblobsimg']['type'][$j],$subunqid);
												copy($srcfile, '../../../assets/'.$targetPath);
											
												$datains=array(
													'[id]'=>$subunqid,
													'[contenttypeid]'=>$contenttypeid,
													'[title]'=>$obstitle[$j],
													'[descr]'=>$obsdescr[$j],
													'[displayorder]'=>$obsdisplayorder[$j],
													'[img]'=>$targetPath,
													'[type]'=>1,
												);
										
												$DB->executedata('i','tblwebcontentdetail',$datains,'');
												unlink('../../../assets/'.$obsimg[$j]);
												// unlink('../'.$tblsectoimage[$i]);	
											}
										}

										if(sizeof($abttitle)>0)
										{
											for ($j=0; $j<sizeof($abttitle); $j++) 
											{
												$subunqid = $IISMethods->generateuuid();
												$srcfile=$config->getImageurl().$abtimg[$j]; 
												
												$targetPath = $IISMethods->uploadallfiles(1,'aboutusdetail',$abtimg[$j],$srcfile,$_FILES['tblabtimg']['type'][$j],$subunqid);
												copy($srcfile, '../../../assets/'.$targetPath);
											
												$datains=array(
													'[id]'=>$subunqid,
													'[contenttypeid]'=>$contenttypeid,
													'[title]'=>$abttitle[$j],
													'[descr]'=>'',
													'[displayorder]'=>0,
													'[abtcount]'=>$abtcount[$j],
													'[img]'=>$targetPath,
													'[type]'=>2,
												);
										
												$DB->executedata('i','tblwebcontentdetail',$datains,'');
												unlink('../../../assets/'.$obsimg[$j]);
												// unlink('../'.$tblsectoimage[$i]);	
											}
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
								else
								{
									$status=0;
									$message=$errmsg['websitefilesize'];
								}

								
							}
							else
							{
								$status=0;
								$message=$errmsg['filetype'];
							}
						}
						else
						{
							try 
							{
								$DB->begintransaction();
								$insdata = array(
									'[contenttypeid]'=>$contenttypeid,
									'[title]'=>$title,
									'[title2]'=>$title2,
									'[description]'=>$description,
									'[update_uid]'=>$uid,
									'[update_date]'=>$IISMethods->getdatetime(),
								);

								$extraparams=array(
									'[id]'=>$row['id'],
								);
								$DB->executedata('u','tblwebcontent',$insdata,$extraparams);

								$delextraparams=array(
									'[contenttypeid]'=>$contenttypeid,
								);
								$DB->executedata('d','tblwebcontentdetail','',$delextraparams);
								if(sizeof($obstitle)>0)
								{
									for ($j=0; $j<sizeof($obstitle); $j++) 
									{
										$subunqid = $IISMethods->generateuuid();
										$srcfile=$config->getImageurl().$obsimg[$j]; 
										
										$targetPath = $IISMethods->uploadallfiles(1,'aboutusdetail',$obsimg[$j],$srcfile,$_FILES['tblobsimg']['type'][$j],$subunqid);
										copy($srcfile, '../../../assets/'.$targetPath);
									
										$datains=array(
											'[id]'=>$subunqid,
											'[contenttypeid]'=>$contenttypeid,
											'[title]'=>$obstitle[$j],
											'[descr]'=>$obsdescr[$j],
											'[displayorder]'=>$obsdisplayorder[$j],
											'[img]'=>$targetPath,
											'[type]'=>1
										);
								
										$DB->executedata('i','tblwebcontentdetail',$datains,'');
										unlink('../../../assets/'.$obsimg[$j]);
										// unlink('../'.$tblsectoimage[$i]);	
									}
								}

								if(sizeof($abttitle)>0)
								{
									for ($j=0; $j<sizeof($abttitle); $j++) 
									{
										$subunqid = $IISMethods->generateuuid();
										$srcfile=$config->getImageurl().$abtimg[$j]; 
										
										$targetPath = $IISMethods->uploadallfiles(1,'aboutusdetail',$abtimg[$j],$srcfile,$_FILES['tblabtimg']['type'][$j],$subunqid);
										copy($srcfile, '../../../assets/'.$targetPath);
									
										$datains=array(
											'[id]'=>$subunqid,
											'[contenttypeid]'=>$contenttypeid,
											'[title]'=>$abttitle[$j],
											'[descr]'=>'',
											'[displayorder]'=>0,
											'[abtcount]'=>$abtcount[$j],
											'[img]'=>$targetPath,
											'[type]'=>2,
										);
								
										$DB->executedata('i','tblwebcontentdetail',$datains,'');
										unlink('../../../assets/'.$obsimg[$j]);
										// unlink('../'.$tblsectoimage[$i]);	
									}
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
					else
					{
						$status=0;
						$message=$errmsg['reqired'];
					}
				}
				else if($contenttypeid == $config->getMissionId() || $contenttypeid == $config->getVissionId() ||$contenttypeid == $config->getValuesId()) // For Mission
				{
					if($contenttypeid && $maintitle && $description )
					{
						if($img)
						{
							if($_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type'] == "image/jpeg" || $_FILES['img']['type'] == "image/png")
							{
								if($_FILES['img']['size']  <= $config->getWebsitefilesize())
								{
									try 
									{
										$DB->begintransaction();
										unlink('../../../assets/'.$row['img']);
										$sourcePath = $_FILES['img']['tmp_name'];
										$targetPath = $IISMethods->uploadallfiles(1,'mission',$img,$sourcePath,$_FILES['img']['type'],$row['id']);
				
										$insdata = array(
											'[contenttypeid]'=>$contenttypeid,
											'[title]'=>$maintitle,
											'[img]'=>$targetPath,
											'[description]'=>$description,
											'[update_uid]'=>$uid,
											'[update_date]'=>$IISMethods->getdatetime(),
										);

										$extraparams=array(
											'[id]'=>$row['id'],
										);
										$DB->executedata('u','tblwebcontent',$insdata,$extraparams);
				
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
									$status=0;
									$message=$errmsg['websitefilesize'];
								}
							}
							else
							{
								$status=0;
								$message=$errmsg['filetype'];
							}
						}
						else
						{
							try 
							{
								$DB->begintransaction();
								$insdata = array(
									'[contenttypeid]'=>$contenttypeid,
									'[title]'=>$maintitle,
									'[description]'=>$description,
									'[update_uid]'=>$uid,
									'[update_date]'=>$IISMethods->getdatetime(),
								);

								$extraparams=array(
									'[id]'=>$row['id'],
								);
								$DB->executedata('u','tblwebcontent',$insdata,$extraparams);

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
					else
					{
						$status=0;
						$message=$errmsg['reqired'];
					}
				}
			}
			else
			{
				if($contenttypeid == $config->getTermsConditionId() || $contenttypeid == $config->getPrivacyPolicyId()) // for Terms & Conditions && Privacy
				{
					if($contenttypeid && $description)
					{
						$unqid = $IISMethods->generateuuid();
						try 
						{
							$DB->begintransaction();
							$insdata = array(
								'[id]'=>$unqid,
								'[contenttypeid]'=>$contenttypeid,
								'[description]'=>$description,
								'[entry_uid]'=>$uid,
								'[entry_date]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tblwebcontent',$insdata,'');
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
				else if($contenttypeid == $config->getInvoiceTermsConditionsId()) // for Invoice Terms & Conditions 
				{
					if($contenttypeid && sizeof($tblitnc) > 0)
					{
						try 
						{
							$DB->begintransaction();

							$unqid = $IISMethods->generateuuid();
							$insdata = array(
								'[id]'=>$unqid,
								'[contenttypeid]'=>$contenttypeid,
								'[title]'=>$maintitle,
								'[description]'=>'',
								'[entry_uid]'=>$uid,
								'[entry_date]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tblwebcontent',$insdata,'');
							
							
							if(sizeof($tblitnc)>0)
							{
								for ($j=0; $j<sizeof($tblitnc); $j++) 
								{
									$subunqid = $IISMethods->generateuuid();
									
									$datains=array(
										'[id]'=>$subunqid,
										'[contenttypeid]'=>$contenttypeid,
										'[invtnc]'=>$tblitnc[$j],
										'[descr]'=>'',
										'[displayorder]'=>$tblidisplayorder[$j],
										'[type]'=>3,
									);

									$DB->executedata('i','tblwebcontentdetail',$datains,'');
								}
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
					else
					{
						$status=0;
						$message=$errmsg['reqired'];
					}
					
				}
				else if($contenttypeid == $config->getAboutUsId()) // For Roro Ferry Service (about us)
				{
					if($contenttypeid && $title && $description && $img)
					{
						if($_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type'] == "image/jpeg" || $_FILES['img']['type'] == "image/png")
						{
							if($_FILES['img']['size']  <= $config->getWebsitefilesize())
							{
								try 
								{
									$DB->begintransaction();
									$unqid = $IISMethods->generateuuid();
								
									$sourcePath = $_FILES['img']['tmp_name'];
									$targetPath = $IISMethods->uploadallfiles(1,'aboutus',$img,$sourcePath,$_FILES['img']['type'],$unqid);
			
									$insdata = array(
										'[id]'=>$unqid,
										'[contenttypeid]'=>$contenttypeid,
										'[title]'=>$title,
										'[title2]'=>$title2,
										'[img]'=>$targetPath,
										'[description]'=>$description,
										'[entry_uid]'=>$uid,
										'[entry_date]'=>$IISMethods->getdatetime(),
									);
									$DB->executedata('i','tblwebcontent',$insdata,'');
			
									if(sizeof($obstitle)>0)
									{
										for ($j=0; $j<sizeof($obstitle); $j++) 
										{
											$subunqid = $IISMethods->generateuuid();
											$srcfile=$config->getImageurl().$obsimg[$j]; 
											
											$targetPath = $IISMethods->uploadallfiles(1,'aboutusdetail',$obsimg[$j],$srcfile,$_FILES['tblobsimg']['type'][$j],$subunqid);
											copy($srcfile, '../../../assets/'.$targetPath);
										
											$datains=array(
												'[id]'=>$subunqid,
												'[contenttypeid]'=>$contenttypeid,
												'[title]'=>$obstitle[$j],
												'[descr]'=>$obsdescr[$j],
												'[displayorder]'=>$obsdisplayorder[$j],
												'[img]'=>$targetPath,
											);
									
											$DB->executedata('i','tblwebcontentdetail',$datains,'');
											unlink('../../../assets/'.$obsimg[$j]);
											// unlink('../'.$tblsectoimage[$i]);	
										}
									}

									if(sizeof($abttitle)>0)
									{
										for ($j=0; $j<sizeof($abttitle); $j++) 
										{
											$subunqid = $IISMethods->generateuuid();
											$srcfile=$config->getImageurl().$abtimg[$j]; 
											
											$targetPath = $IISMethods->uploadallfiles(1,'aboutusdetail',$abtimg[$j],$srcfile,$_FILES['tblabtimg']['type'][$j],$subunqid);
											copy($srcfile, '../../../assets/'.$targetPath);
										
											$datains=array(
												'[id]'=>$subunqid,
												'[contenttypeid]'=>$contenttypeid,
												'[title]'=>$abttitle[$j],
												'[descr]'=>'',
												'[displayorder]'=>0,
												'[abtcount]'=>$abtcount[$j],
												'[img]'=>$targetPath,
												'[type]'=>2,
											);
									
											$DB->executedata('i','tblwebcontentdetail',$datains,'');
											unlink('../../../assets/'.$obsimg[$j]);
											// unlink('../'.$tblsectoimage[$i]);	
										}
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
							else
							{
								$status=0;
								$message=$errmsg['websitefilesize'];
							}


						
						}
						else
						{
							$status=0;
							$message=$errmsg['filetype'];
						}
					}
					else
					{
						$status=0;
						$message=$errmsg['reqired'];
					}
				}
				else if($contenttypeid == $config->getMissionId() || $contenttypeid == $config->getVissionId() ||$contenttypeid == $config->getValuesId()) // For Mission
				{
					if($contenttypeid && $maintitle && $description)
					{
						if($_FILES['img']['type'] == "image/jpg" || $_FILES['img']['type'] == "image/jpeg" || $_FILES['img']['type'] == "image/png")
						{

							if($_FILES['img']['size']  <= $config->getWebsitefilesize())
							{
								try 
								{
									$DB->begintransaction();
									$unqid = $IISMethods->generateuuid();
								
									$sourcePath = $_FILES['img']['tmp_name'];
									$targetPath = $IISMethods->uploadallfiles(1,'mission',$img,$sourcePath,$_FILES['img']['type'],$unqid);
			
									$insdata = array(
										'[id]'=>$unqid,
										'[contenttypeid]'=>$contenttypeid,
										'[title]'=>$maintitle,
										'[img]'=>$targetPath,
										'[description]'=>$description,
										'[entry_uid]'=>$uid,
										'[entry_date]'=>$IISMethods->getdatetime(),
									);
									$DB->executedata('i','tblwebcontent',$insdata,'');
			
			
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
								$message=$errmsg['websitefilesize'];
							}
							
						}
						else
						{
							$status=0;
							$message=$errmsg['filetype'];
						}
					}
					else
					{
						$status=0;
						$message=$errmsg['reqired'];
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
	else if($action == 'uploadtempfile')
	{
		$title=$IISMethods->sanitize($_POST['title']);
		$description=$IISMethods->sanitize($_POST['description']);
		$displayorder=$IISMethods->sanitize($_POST['displayorder']);
		$count = $IISMethods->sanitize($_POST['count']);
		$iconimg = $_FILES['iconimg']['tmp_name'];

		$updimg=$_FILES['imgfile']['tmp_name'];
		if($updimg)
		{
			if($_FILES['imgfile']['type'] == "image/jpg" || $_FILES['imgfile']['type'] == "image/jpeg" || $_FILES['imgfile']['type'] == "image/png")
			{
				if($_FILES['imgfile']['size']  <= $config->getWebsitefilesize())
				{
					$unqid = $IISMethods->generateuuid();
					$targetPath = $IISMethods->uploadallfiles(1,'tempimg',$_FILES['imgfile']['name'],$updimg,$_FILES['imgfile']['type'],$unqid);
					$response['imgurl1']=$targetPath;
					$response['imgurl']=$config->getImageurl().$targetPath;
					$response['title']=$title;
					$response['description']=$description;
					$response['displayorder']=$displayorder;
		

					$status=1;
					$message=$errmsg['success'];
				}
				else
				{
					$status=0;
					$message=$errmsg['websitefilesize'];
				}

				
			}
			else
			{
				$status=0;
				$message=$errmsg['filetype'];
			}
		}
		else if($iconimg)
		{
			if($_FILES['iconimg']['type'] == "image/jpg" || $_FILES['iconimg']['type'] == "image/jpeg" || $_FILES['iconimg']['type'] == "image/png")
			{
				if($_FILES['iconimg']['size']  <= $config->getWebsitefilesize())
				{
					$unqid = $IISMethods->generateuuid();
					$targetPath = $IISMethods->uploadallfiles(1,'tempimg',$_FILES['iconimg']['name'],$iconimg,$_FILES['iconimg']['type'],$unqid);
					$response['imgurl1']=$targetPath;
					$response['imgurl']=$config->getImageurl().$targetPath;
					$response['title']=$title;
					$response['count']=$count;
		

					$status=1;
					$message=$errmsg['success'];
				}
				else
				{
					$status=0;
					$message=$errmsg['websitefilesize'];
				}

				
			}
			else
			{
				$status=0;
				$message=$errmsg['filetype'];
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

  