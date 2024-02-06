<?php header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\operationflow.php';
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	
	if($action=='insertoperationflow')   
	{
		$formevent=$IISMethods->sanitize($_POST['formevent']);
		$productexport=json_decode($_POST['productexport'],true);	
		

		$nodearray=array_values($productexport['drawflow']['Home']['data']);
		$id=$IISMethods->sanitize($_POST['id']);
		$datetime=$IISMethods->getdatetime();
		if($_POST['productexport'])
		{			
			$insqry=array(					
				'[nodetext]'=>$_POST['productexport'],
			);

			$opflowdata=array();

			
			// if($formevent=='addright')
			// {
				try 
				{
					$DB->begintransaction();


					if($id)
					{
						$extraparams = array(
							'[id]'=>$id
						);
						$extraparams1 = array(
							'[oflowid]'=>$id
						);
						$extraparams2 = array(
							'[oflowid]'=>$id
						);
						$extraparams3 = array(
							'[oflowid]'=>$id
						);
						$DB->executedata('d','tbloperationflow','',$extraparams);
						$DB->executedata('d','tbloperationflowdetail','',$extraparams1);
						$DB->executedata('d','tbloperationflowmember','',$extraparams2);
						$DB->executedata('d','tbloperationflowrange','',$extraparams3);
					}
					
					$unqid = $IISMethods->generateuuid();
					$insqry['[id]']=$unqid;
					$insqry['[timestamp]']=$IISMethods->getdatetime();	
					$insqry['[entry_uid]']=$uid;	
					$insqry['[entry_date]']=$datetime;
					$DB->executedata('i','tbloperationflow',$insqry,'');

					for($i=0;$i<sizeof($nodearray);$i++)
					{
						$subunqid = $IISMethods->generateuuid();
						$subnodefordb=$nodearray[$i]['data']['db'];
						$subnodeforin=$nodearray[$i]['inputs']['input_1']['connections'];
						$subnodeforout=$nodearray[$i]['outputs']['output_1']['connections'];
						$orderby=0;
						if(sizeof($subnodeforin) > 0)
						{
							$orderby=$subnodeforin[0]['node'];
						}
						
						// if($subnodeforin[0]['node'])
						// {
							$nodein=$subnodeforin[0]['node'];
						// }
						// else
						// {
						// 	$nodein=0;
						// }

						// if($subnodeforout[0]['node'])
						// {
							$nodeout=$subnodeforout[0]['node'];
						// }
						// else
						// {
						// 	$nodeout=0;
						// }
						
						$operationid =$subnodefordb['operationid'];
						$storeid=$subnodefordb['storeid'];
						$countid=$subnodefordb['countid'];
						$isserviceorder=$_POST['isserviceorder'.$countid];
						if($isserviceorder)
						{
							$isserviceorder = $isserviceorder;
						}
						else
						{
							$isserviceorder = 0;
						}
						$iscompulsory=$_POST['iscompulsory'.$countid];
						if($iscompulsory)
						{
							$iscompulsory = $iscompulsory;
						}
						else
						{
							$iscompulsory = 0;
						}
						$membertypeid=$_POST['membertypeid'.$countid];
						$insgroupid=$_POST['insgroupid'.$countid];

						if($insgroupid == '')
						{
							$insgroupid=$mssqldefval['uniqueidentifier'];
						}

						$rangeid=$_POST['rangeid'.$countid];
						
						$subinsqry=array(					
							'id'=>$subunqid,
							'oflowid'=>$unqid,
							'operationid'=>$operationid,
							'countid'=>$countid,
							'storeid'=>$storeid,
							'iscompulsory'=>$iscompulsory,
							'isserviceorder'=>$isserviceorder,
							'insgroupid'=>$insgroupid,
							'displayorder'=>$orderby,
							'timestamp'=>$IISMethods->getdatetime(),
							'nodeforin'=>$nodein,
							'nodeforout'=>$nodeout,
							'nodeid'=>$nodearray[$i]['id'],
						);

						array_push($opflowdata, $subinsqry);
					}

					$DB->insertoperaionflowdetailsdata($opflowdata,sizeof($nodearray),0,0);


					
					$qrysub="SELECT * from tbloperationflowdetail where oflowid=:oflowid";
					$parmsub = array(
						':oflowid'=>$unqid,
					);
					$result_sub=$DB->getmenual($qrysub,$parmsub);
					for($i=0;$i<sizeof($result_sub);$i++)
					{
						$rowsub=$result_sub[$i];

						$membertypeid=$_POST['membertypeid'.$rowsub['countid']];
						$rangeid=$_POST['rangeid'.$rowsub['countid']];

						for($j=0;$j<sizeof($membertypeid);$j++)
						{
							$detailid = $IISMethods->generateuuid();
							$subiqry=array(	
								'[id]'=>$detailid,				
								'[flowdetailid]'=>$rowsub['id'],
								'[oflowid]'=>$unqid,
								'[membertypeid]'=>$membertypeid[$j],
								'[timestamp]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tbloperationflowmember',$subiqry,'');
						}


						for($k=0;$k<sizeof($rangeid);$k++)
						{
							$detailid = $IISMethods->generateuuid();
							$subiqry=array(	
								'[id]'=>$detailid,				
								'[flowdetailid]'=>$rowsub['id'],
								'[oflowid]'=>$unqid,
								'[rangeid]'=>$rangeid[$k],
								'[timestamp]'=>$IISMethods->getdatetime(),
							);
							$DB->executedata('i','tbloperationflowrange',$subiqry,'');
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
				
			// }	
		}
		else
		{
			$message=$errmsg['reqired'];
		}
	}
	else if($action=='filloperationflow')
	{
			$qrychk="SELECT id,nodetext from tbloperationflow";
			$parms = array();
			$result_ary=$DB->getmenual($qrychk,$parms);

			if(sizeof($result_ary)>0)
			{
				$row=$result_ary[0];
				$id = $IISMethods->sanitize($row['id']);
				$response['id']=$IISMethods->sanitize($row['id']);
				$response['nodetext']=$IISMethods->sanitize($row['nodetext'],'OUT');

				$qrychkat="SELECT od.*,
					ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), om.membertypeid) AS [text()] FROM tbloperationflowmember om WHERE CONVERT(VARCHAR(255), om.flowdetailid)= od.id FOR XML PATH ('')),2,10000),'') as membertypeid,
					ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), tor.rangeid) AS [text()] FROM tbloperationflowrange tor WHERE CONVERT(VARCHAR(255), tor.flowdetailid)= od.id FOR XML PATH ('')),2,10000000),'') as rangeid,
					ISNULL(SUBSTRING((select ','+CONVERT(VARCHAR(255), mt.type) AS [text()] FROM tbloperationflowmember om inner join tblmembertype mt on mt.id=om.membertypeid WHERE CONVERT(VARCHAR(255), om.flowdetailid)= od.id FOR XML PATH ('')),2,10000),'') as membertypename
					FROM tbloperationflowdetail od
					WHERE od.oflowid = :id ORDER BY od.countid ASC";
				$parmat = array(
					':id'=>$id,
				);
				$result_att=$DB->getmenual($qrychkat,$parmat);
				$k=0;
				foreach($result_att as $result_atte)
				{
					$flowdetailid = $result_atte['id'];
					$response['nodedata'][$k]['id'] = $result_atte['id'];
					$response['nodedata'][$k]['oflowid'] = $result_atte['oflowid'];
					$response['nodedata'][$k]['countid'] = $result_atte['countid'];
					$response['nodedata'][$k]['isserviceorder'] = $result_atte['isserviceorder'];
					$response['nodedata'][$k]['iscompulsory'] = $result_atte['iscompulsory'];
					$response['nodedata'][$k]['insgroupid'] = $result_atte['insgroupid'];
					$response['nodedata'][$k]['displayorder'] = $result_atte['displayorder'];
					$response['nodedata'][$k]['nodeforin'] = $result_atte['nodeforin'];
					$response['nodedata'][$k]['nodeforout'] = $result_atte['nodeforout'];
					$response['nodedata'][$k]['operationid'] = $result_atte['operationid'];
					$response['nodedata'][$k]['storeid'] = $result_atte['storeid'];
					$response['nodedata'][$k]['membertypeid'] = $result_atte['membertypeid'];
					$response['nodedata'][$k]['membertypename'] = $result_atte['membertypename'];
					$response['nodedata'][$k]['rangeid'] = $result_atte['rangeid'];

					/*
					$subqry="SELECT pm.* from tbloperationflowmember pm where pm.flowdetailid = :id";
					$subparams = array(
						':id'=>$flowdetailid,
					);
					//print_r($subparams);
					$subresult_att=$DB->getmenual($subqry,$subparams);
					$j=0;
					foreach($subresult_att as $result_flow)
					{
						$response['nodedata'][$k]['id'] = $result_flow['id'];
						$response['nodedata'][$k]['oflowid'] = $result_flow['oflowid'];
						$response['nodedata'][$k]['flowdetailid'] = $result_flow['flowdetailid'];
						$response['nodedata'][$k]['membertypeid'] = $result_flow['membertypeid'];
						$j++;
					}
					*/


					$k++;
				}	

			}
			$status=1;
			$message=$errmsg['success'];
	}

}
require_once dirname(__DIR__, 3).'\config\apifoot.php';  
?>

  