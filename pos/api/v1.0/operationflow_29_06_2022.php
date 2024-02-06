<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\operationflow.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();
	$currdate=$IISMethods->getformatcurrdate();

	//List Member History
	if($action == 'lisoperationflow')
	{
		$storeid=$IISMethods->sanitize($_POST['storeid']);   
		$memberid=$IISMethods->sanitize($_POST['memberid']);   
		$isserviceorder =$IISMethods->sanitize($_POST['isserviceorder']);
		$todaydate = $IISMethods->getcurrdate();


		/************ Start For Add Operation Flow in Day ************/
		/*
		$qrychk="select so.* 
			from tblstoreoperation1 so 
			where memberid=:memberid and convert(date,so.date,103)=convert(date,:todaydate,103)";
		$chkparms = array(
			':memberid'=>$memberid,
			':todaydate'=>$todaydate,
		);
		$reschk=$DB->getmenual($qrychk,$chkparms);

		if(sizeof($reschk) > 0)
		{

		}
		else
		{
			$qry="select ofd.*,sm.storename,op.name as operationname 
				from tbloperationflowdetail ofd 
				inner join tblstoremaster sm on sm.id=ofd.storeid 
				inner join tbloperationmaster op on op.id=ofd.operationid
				order by ofd.displayorder";
			$parms = array();
			$res=$DB->getmenual($qry,$parms);
			
			if(sizeof($res) > 0)
			{
				for($i=0;$i<sizeof($res);$i++)
				{
					$row=$res[$i];

					$unqid = $IISMethods->generateuuid();

					$sop_insqry['[id]']=$unqid;
					$sop_insqry['[memberid]']=$memberid;
					$sop_insqry['[date]']=$todaydate;
					$sop_insqry['[storeid]']=$row['storeid'];
					$sop_insqry['[storename]']=$row['storename'];
					$sop_insqry['[operationid]']=$row['operationid'];
					$sop_insqry['[operationname]']=$row['operationname'];
					$sop_insqry['[iscompulsory]']=$row['iscompulsory'];
					$sop_insqry['[displayorder]']=$row['displayorder'];
					$sop_insqry['[insgroupid]']=$row['insgroupid'];
					$sop_insqry['[timestamp]']=$datetime;
					
					$DB->executedata('i','tblstoreoperation1',$sop_insqry,'');


					//Operation Flow Member
					$qrymem="select ofm.*,mt.type as membertypename 
						from tbloperationflowmember ofm 
						inner join tblmembertype mt on mt.id=ofm.membertypeid 
						where ofm.flowdetailid=:flowdetailid";
					$memparms = array(
						':flowdetailid'=>$row['id'],
					);
					$resmem=$DB->getmenual($qrymem,$memparms);

					if(sizeof($resmem) > 0)
					{
						for($j=0;$j<sizeof($resmem);$j++)
						{
							$rowmem=$resmem[$j];

							$m_unqid = $IISMethods->generateuuid();

							$sopm_insqry['[id]']=$m_unqid;
							$sopm_insqry['[sopid]']=$unqid;
							$sopm_insqry['[membertypeid]']=$rowmem['membertypeid'];
							$sopm_insqry['[membertype]']=$rowmem['membertypename'];
							$sopm_insqry['[timestamp]']=$datetime;
							
							$DB->executedata('i','tblstoreoperationmembertype1',$sopm_insqry,'');
						}	
					}


					//Operation Flow Instruction
					$qryins="select i.*
						from tblinstructiongroupdetail igd 
						inner join tblinstruction i on i.id=igd.instructionid 
						where igd.insgroupid=:insgroupid and i.isactive=1 order by displayorder";
					$insparms = array(
						':insgroupid'=>$row['insgroupid'],
					);
					$resins=$DB->getmenual($qryins,$insparms);

					if(sizeof($resins) > 0)
					{
						for($j=0;$j<sizeof($resins);$j++)
						{
							$rowins=$resins[$j];

							$i_unqid = $IISMethods->generateuuid();

							$sopi_insqry['[id]']=$i_unqid;
							$sopi_insqry['[sopid]']=$unqid;
							$sopi_insqry['[insgroupid]']=$row['insgroupid'];
							$sopi_insqry['[instructionid]']=$rowins['id'];
							$sopi_insqry['[instructionname]']=$rowins['name'];
							$sopi_insqry['[displayorder]']=$rowins['displayorder'];
							$sopi_insqry['[timestamp]']=$datetime;
							
							$DB->executedata('i','tblstoreoperationinstruction1',$sopi_insqry,'');
						}	
					}


					//Operation Flow Range
					$qryrange="select ofr.*
						from tbloperationflowrange ofr 
						where ofr.flowdetailid=:flowdetailid";
					$rangeparms = array(
						':flowdetailid'=>$row['id'],
					);
					$resrange=$DB->getmenual($qryrange,$rangeparms);

					if(sizeof($resrange) > 0)
					{
						for($j=0;$j<sizeof($resrange);$j++)
						{
							$rowrange=$resrange[$j];

							$r_unqid = $IISMethods->generateuuid();

							$sopm_insqry['[id]']=$r_unqid;
							$sopm_insqry['[sopid]']=$unqid;
							$sopm_insqry['[rangeid]']=$rowrange['rangeid'];
							$sopm_insqry['[timestamp]']=$datetime;
							
							$DB->executedata('i','tblstoreoperationrange1',$sopm_insqry,'');
						}	
					}

				}
			}
		}
		*/
		/************ End For Add Operation Flow in Day ************/


		$operationflowdata = new operationflowdata();
		/*
		$qrysop="select tmp.id,tmp.storeid,REPLACE(tmp.storename,'&amp;','&') as storename,tmp.operationid,REPLACE(tmp.operationname,'&amp;','&') AS operationname,tmp.iscompulsory,tmp.iscompleted,tmp.displayorder
		,case when (convert(varchar(50),tmp.id)=tmp.curr_id) then '#0054d2' when (tmp.iscompleted=1) then '#008140' when (tmp.iscompleted=0 and isnull(tmp.temp_iscompleted,0)=1) then '#7c7c7c' else '#cccccc' end as statuscolor
		,case when (convert(varchar(50),tmp.id)=tmp.curr_id) then 1 else 0 end as iscurrent 
		from(
			select so.id,so.storeid,so.storename,so.operationid,so.operationname,so.iscompulsory,so.iscompleted,so.displayorder,so.temp_iscompleted,
			isnull((select top 1 convert(varchar(50),so1.id) from tblstoreoperation1 so1 where so1.storeid=:storeid1 and so1.memberid=:memberid1 and convert(date,so1.date,103)=:currdate1 and isnull(so1.temp_iscompleted,0)=0 order by so1.displayorder),'') as curr_id
			from tblstoreoperation1 so 
			where so.memberid=:memberid and convert(date,so.date,103)=convert(date,:todaydate,103) 
		) tmp order by tmp.displayorder";
		*/

		$qrysop="select tmp.id,tmp.storeid,REPLACE(tmp.storename,'&amp;','&') as storename,tmp.operationid,REPLACE(tmp.operationname,'&amp;','&') AS operationname,tmp.iscompulsory,tmp.iscompleted,tmp.displayorder
		,case when (convert(varchar(50),tmp.id)=tmp.curr_id) then '#0054d2' when (tmp.iscompleted=1) then '#008140' when (tmp.iscompleted=0 and isnull(tmp.temp_iscompleted,0)=1) then '#7c7c7c' else '#cccccc' end as statuscolor
		,case when (convert(varchar(50),tmp.id)=tmp.curr_id) then 1 else 0 end as iscurrent 
		from(
			select so.id,so.storeid,so.storename,so.operationid,so.operationname,so.iscompulsory,so.iscompleted,so.displayorder,so.temp_iscompleted,
			isnull((select top 1 convert(varchar(50),so1.id) from tblstoreoperation1 so1 where so1.storeid=:storeid1 and so1.memberid=:memberid1 and convert(date,so1.date,103)=:currdate1 order by so1.displayorder),'') as curr_id
			from tblstoreoperation1 so 
			where so.memberid=:memberid and convert(date,so.date,103)=convert(date,:todaydate,103) 
		) tmp order by tmp.displayorder";
		$sopparms = array(
			':memberid'=>$memberid,
			':todaydate'=>$todaydate,
			':storeid1'=>$storeid,
			':memberid1'=>$memberid,
			':currdate1'=>$currdate, 
		);
		//$operationflowdata=$DB->getmenual($qrysop,$sopparms,'operationflowdata');
		$operationflowdata=$DB->getmenual($qrysop,$sopparms);
		
		$response['isoperationflowdata'] = 0;

		$currentstorestatus=0;

		$curr_storeid='';
		$curr_operationid='';
		$curr_displayorder='';

		if($operationflowdata)
		{
			$response['isoperationflowdata'] = 1;
			//$response['operationflowdata'] = $operationflowdata;

			for($m=0;$m<sizeof($operationflowdata);$m++)
			{
				$rowoperationflowdata=$operationflowdata[$m];
				$response['operationflowdata'][$m]['id'] = (string)$rowoperationflowdata['id'];
				$response['operationflowdata'][$m]['storeid'] = (string)$rowoperationflowdata['storeid'];
				$response['operationflowdata'][$m]['storename'] = (string)$rowoperationflowdata['storename'];
				$response['operationflowdata'][$m]['operationid'] = (string)$rowoperationflowdata['operationid'];
				$response['operationflowdata'][$m]['operationname'] = (string)$rowoperationflowdata['operationname'];
				$response['operationflowdata'][$m]['iscompulsory'] = (string)$rowoperationflowdata['iscompulsory'];
				$response['operationflowdata'][$m]['iscompleted'] = (string)$rowoperationflowdata['iscompleted'];
				$response['operationflowdata'][$m]['displayorder'] = (string)$rowoperationflowdata['displayorder'];
				$response['operationflowdata'][$m]['statuscolor'] = (string)$rowoperationflowdata['statuscolor'];
				$response['operationflowdata'][$m]['iscurrent'] = (string)$rowoperationflowdata['iscurrent'];

				if($rowoperationflowdata['iscurrent'] == 1 && $rowoperationflowdata['iscompleted'] == 1)
				{
					$currentstorestatus=1;
				}

				if($rowoperationflowdata['iscurrent'] == 1)
				{
					$curr_storeid=$rowoperationflowdata['storeid'];
					$curr_operationid=$rowoperationflowdata['operationid'];
					$curr_displayorder=$rowoperationflowdata['displayorder'];
				}


				//For Store Wise Instruction Data
				$instructiondata = new instructiondata();
				$qryins="select soi.id,REPLACE(soi.instructionname,'&amp;','&') as name,isnull(soi.iscompleted,0) as iscompleted,
					case when (isnull(soi.iscompleted,0)=1) then 'Completed' else 'Not Completed' end as instructionstatus 
					from tblstoreoperationinstruction1 soi 
					where soi.sopid=:sopid  order by soi.displayorder";
				$insparams = array(
					':sopid'=>$rowoperationflowdata['id'],
				);
				$instructiondata=$DB->getmenual($qryins,$insparams,'instructiondata');
				
				$response['operationflowdata'][$m]['isstoreinstruction'] = (string)0;
				if($instructiondata)
				{
					$response['operationflowdata'][$m]['isstoreinstruction'] = (string)1;
					$response['operationflowdata'][$m]['storeinstructiondata'] = $instructiondata;
				}


			}
			
		}


		$response['curr_storeid'] = $curr_storeid;
		$response['curr_operationid'] = $curr_operationid;
		$response['curr_displayorder'] = $curr_displayorder;
		


		//Check Store Operation Flow
		$qryso="select so.id,so.iscompulsory,so.isserviceorder 
			from tblstoreoperation1 so 
			where so.storeid=:storeid and so.memberid=:memberid and convert(date,so.date,103)=convert(date,:todaydate,103)";
		$soparms = array(
			':storeid'=>$storeid,
			':memberid'=>$memberid,
			':todaydate'=>$todaydate,
		);
		$resso=$DB->getmenual($qryso,$soparms);

		if(sizeof($resso) > 0)
		{
			$rowso=$resso[0];

			$response['sopid'] = $rowso['id'];
			

			//Get Member Type (First Time, Repeated, Member)
			$qrymt="select so.id from tblstoreoperation1 so where memberid=:memberid and convert(date,so.date,103) < convert(date,:todaydate,103)";
			$mtparms = array(
				':memberid'=>$memberid,
				':todaydate'=>$todaydate,
			);
			$resmt=$DB->getmenual($qrymt,$mtparms);
			
			if(sizeof($resmt) > 0)  //Repeated Guest
			{
				$set_membertypeid = $config->getDefRepeatedGuestId();
			}
			else  //First Time Guest
			{
				$set_membertypeid = $config->getDefFirstTimeGuestId();
			}


			//Check Member Type
			$qrychkmt="select som.id from tblstoreoperationmembertype1 som where som.sopid=:sopid and som.membertypeid=:membertypeid";
			$chkmtparms = array(
				':sopid'=>$rowso['id'],
				':membertypeid'=>$set_membertypeid,
			);
			$reschkmt=$DB->getmenual($qrychkmt,$chkmtparms);
			
			if($currentstorestatus == 1)   //When Current Store And its Completed
			{
				$response['iscompulsory'] = 2;
			}
			else
			{
				if(sizeof($reschkmt) > 0)  
				{
					$response['iscompulsory'] = (double)$rowso['iscompulsory'];
				}
				else  
				{
					$response['iscompulsory'] = 0;
				}
			}


			//For Instruction Data
			$instructiondata = new instructiondata();
			$qryins="select soi.id,REPLACE(soi.instructionname,'&amp;','&') as name 
				from tblstoreoperationinstruction1 soi 
				where soi.sopid=:sopid  order by soi.displayorder";
			$insparams = array(
				':sopid'=>$rowso['id'],
			);
			$instructiondata=$DB->getmenual($qryins,$insparams,'instructiondata');
			
			$response['isinstruction'] = 0;
			if($instructiondata)
			{
				$response['isinstruction'] = 1;
				$response['instructiondata'] = $instructiondata;
			}



			//Get Range Booking
			$qryr="select isnull((select count(id) from tblstoreoperationrange1 where sopid=:sopid),0) as cntrangedata";
			$rparms = array(
				':sopid'=>$rowso['id'],
			);
			$resr=$DB->getmenual($qryr,$rparms);
			$rowr=$resr[0];
			
			if($rowr['cntrangedata'] > 0) 
			{
				$response['israngeassign'] = 1;
			}
			else 
			{
				$response['israngeassign'] = 0;
			}

			$response['isserviceorder'] = (int)$rowso['isserviceorder'];
		}
		else
		{
			$response['iscompulsory'] = 2;
			$response['isinstruction'] = 0;

			$response['israngeassign'] = 2;
			$response['sopid'] = '';

			$response['isserviceorder'] = 0;
		}


		$status=1;
		$message=$errmsg['success'];	
	}
	//Insert Operation Flow Data
	else if($action == 'insertstoreoperationdata')
	{
		$storeid=$IISMethods->sanitize($_POST['storeid']);   
		$memberid=$IISMethods->sanitize($_POST['memberid']);  
		
		$operationid=$IISMethods->sanitize($_POST['operationid']); 
		$displayorder=$IISMethods->sanitize($_POST['displayorder']); 

		$instructiondataarr = $_POST['instructiondata'];      //Payments Array  (From Webadmin)
		$decodejson_instruction=json_decode($instructiondataarr,TRUE);


		$todaydate = $IISMethods->getcurrdate();
		$datetime=$IISMethods->getdatetime();
		
		if($storeid && $operationid && $memberid)
		{
			//Check Store Operation Flow
			$qryso="select so.id,so.iscompulsory 
				from tblstoreoperation1 so 
				where so.storeid=:storeid and so.operationid=:operationid and so.displayorder=:displayorder and so.memberid=:memberid and convert(date,so.date,103)=convert(date,:todaydate,103)";
			$soparms = array(
				':storeid'=>$storeid,
				':operationid'=>$operationid,
				':displayorder'=>$displayorder,
				':memberid'=>$memberid,
				':todaydate'=>$todaydate,
			);
			$resso=$DB->getmenual($qryso,$soparms);

			if(sizeof($resso) > 0)
			{
				$rowso=$resso[0];

				try 
				{ 
					$DB->begintransaction();
					

					$updord['[iscompleted]']=1;
					$updord['[complete_uid]']=$uid;
					$updord['[complete_date]']=$datetime;

					$upddata['[id]']=$rowso['id'];
					$DB->executedata('u','tblstoreoperation1',$updord,$upddata);


					//Instruction Details
					if(sizeof($decodejson_instruction)  > 0)
					{
						foreach($decodejson_instruction as $k=>$v)
						{
							// $subinsunqid = $IISMethods->generateuuid();
							
							// $subinsinsary=array(	
							// 	'[id]'=>$subinsunqid,				
							// 	'[sopid]'=>$rowso['id'],
							// 	'[instructionid]'=>$IISMethods->sanitize($v['id']),
							// 	'[instruction]'=>$IISMethods->sanitize($v['name']),	
							// 	'[timestamp]'=>$datetime,
							// );
							// $DB->executedata('i','tblstoreoperationinstruction',$subinsinsary,'');


							$updsi['[iscompleted]']=1;
							$updsi['[complete_uid]']=$uid;
							$updsi['[complete_date]']=$datetime;

							$updsidata['[id]']=$IISMethods->sanitize($v['id']);
							$DB->executedata('u','tblstoreoperationinstruction1',$updsi,$updsidata);
						}
					}

					
					$status = 1;
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
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
		
	}
	//List Store Range Data
	else if($action == 'liststorerange')
	{	
		$storerange=new storerange();
		$sopid=$IISMethods->sanitize($_POST['sopid']);   

		$todaydate = $IISMethods->getcurrdate();

		if($sopid)
		{
			$qry="SELECT distinct rm.id,rm.rangename as name 
			from tblstoreoperationrange1 sor 
			inner join tblrangemaster rm on rm.id=sor.rangeid 
			inner join tblrangelane rl on rl.rangeid=rm.id 
			where isnull(rm.isactive,0)=1 and sor.sopid=:sopid and 
			rl.id not in (
				select ar.laneid
				from tblassignrangelane ar 
				where isnull(ar.isreleased,0)=0 and convert(date,ar.date,103) = convert(date,:todaydate,103)
			) 
			order by rm.rangename";	
			$params = array(
				':sopid'=>$sopid,
				':todaydate'=>$todaydate,
			);
			$storerange=$DB->getmenual($qry,$params,'storerange');

			$response['isstorerangedata']=0;
			if($storerange)
			{
				$response['isstorerangedata']=1;
				$response['storerangedata']=$storerange;
				
				$status = 1;
				$message = $errmsg['success'];
			}
			else
			{
				$status=0;
				$message=$errmsg['nodatafound'];
			}
		}
	}
	//List Store Range Wise Lane Data
	else if($action == 'liststorerangelane')
	{	
		$storerangelane=new storerangelane();
		$sopid=$IISMethods->sanitize($_POST['sopid']); 
		$rangeid=$IISMethods->sanitize($_POST['rangeid']);  

		$todaydate = $IISMethods->getcurrdate();

		if($sopid)
		{
		
			$qry="SELECT distinct l.id,l.name 
			from tblstoreoperationrange1 sor 
			inner join tblrangemaster rm on rm.id=sor.rangeid 
			inner join tblrangelane rl on rl.rangeid=rm.id
			inner join tbllanemaster l on l.id=rl.laneid
			where isnull(rm.isactive,0)=1 and sor.sopid=:sopid and sor.rangeid=:rangeid and 
			l.id not in (
				select ar.laneid
				from tblassignrangelane ar 
				where isnull(ar.isreleased,0)=0 and convert(date,ar.date,103) = convert(date,:todaydate,103) 
			)  
			order by l.name";	
			$params = array(
				':sopid'=>$sopid,
				':rangeid'=>$rangeid,
				':todaydate'=>$todaydate,
			);
			$storerangelane=$DB->getmenual($qry,$params,'storerangelane');

			$response['isstorerangelanedata']=0;
			if($storerangelane)
			{
				$response['isstorerangelanedata']=1;
				$response['storerangelanedata']=$storerangelane;
				
				$status = 1;
				$message = $errmsg['success'];
			}
			else
			{
				$status=0;
				$message=$errmsg['nodatafound'];
			}

		}	
	}
	//Insert Range Assign Data
	else if($action == 'insertrangeassigndata')
	{
		$storeid=$IISMethods->sanitize($_POST['storeid']);   
		$memberid=$IISMethods->sanitize($_POST['memberid']);   

		$rangeid=$IISMethods->sanitize($_POST['rangeid']); 
		$laneid=$IISMethods->sanitize($_POST['laneid']); 
		
		$todaydate = $IISMethods->getcurrdate();
		$datetime=$IISMethods->getdatetime();
		
		if($storeid && $memberid && $rangeid && $laneid)
		{
			//Check Store Operation Flow
			$qryso="select so.id,so.iscompulsory 
				from tblstoreoperation1 so 
				where so.storeid=:storeid and so.memberid=:memberid and convert(date,so.date,103)=convert(date,:todaydate,103)";
			$soparms = array(
				':storeid'=>$storeid,
				':memberid'=>$memberid,
				':todaydate'=>$todaydate,
			);
			$resso=$DB->getmenual($qryso,$soparms);

			if(sizeof($resso) > 0)
			{
				$rowso=$resso[0];

				try 
				{ 
					$DB->begintransaction();

					$qrychk="select ar.rangeid
					from tblassignrangelane ar 
					where ar.rangeid=:rangeid and ar.laneid=:laneid and isnull(ar.isreleased,0)=0 and convert(date,ar.date,103) = convert(date,:todaydate,103) ";
					$chkparms = array(
						':rangeid'=>$rangeid,
						':laneid'=>$laneid,
						':todaydate'=>$todaydate,
					);
					$reschk=$DB->getmenual($qrychk,$chkparms);

					if(sizeof($reschk) > 0)
					{
						$status = 0;
						$message=$errmsg['norangeavlbl'];
					}
					else
					{
						$unqid = $IISMethods->generateuuid();
						$insqry=array(					
							'[id]'=>$unqid,	
							'[storeid]'=>$storeid,	
							'[memberid]'=>$memberid,
							'[rangeid]'=>$rangeid,	
							'[laneid]'=>$laneid,	
							'[date]'=>$todaydate,		
							'[isreleased]'=>0,	
							'[timestamp]'=>$datetime,
							'[entry_uid]'=>$uid,
							'[entry_date]'=>$datetime,
						);
						$DB->executedata('i','tblassignrangelane',$insqry,'');

						$status = 1;
						$message=$errmsg['rangeassignsuccess'];
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
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
		
	}
	//List Member Assign Range Lane History
	else if($action == 'listassignedrangemember')
	{
		$storeid =$IISMethods->sanitize($_POST['storeid']);
		$fltmemberid =$IISMethods->sanitize($_POST['fltmemberid']);
		$fltrangeid =$IISMethods->sanitize($_POST['fltrangeid']);
		$fltlaneid =$IISMethods->sanitize($_POST['fltlaneid']);
		//$date =$IISMethods->sanitize($_POST['date']);
		$fltfromdate=$IISMethods->sanitize($_POST['fltfromdate']);  
		$flttodate=$IISMethods->sanitize($_POST['flttodate']);  
		$date=$IISMethods->getcurrdate();
		
		if(!$fltfromdate || !$flttodate)
		{
			$fltfromdate=$date;  
			$flttodate=$date;  
		}
		
		// if($date == '')
		// {
		// 	$date=$IISMethods->getcurrdate();
		// }

		if($date)
		{
			$memberdata=new listmemberrangedata();
			$qry="SELECT distinct pm.id,isnull(pm.personname,'') as personname,isnull(pm.firstname,'') as firstname,isnull(pm.middlename,'') as middlename,isnull(pm.lastname,'') as lastname,isnull(pm.contact,'') as contact,isnull(pm.email,'') as email,
				case when (isnull(pm.profileimg,'') = '') then :defualtmemberimageurl else concat(:imageurl,pm.profileimg) end as profileimg,
				ar.id as rangeassignid,ar.storeid,ar.rangeid,rm.rangename,ar.laneid,lm.name as lanename,ar.date,isnull(ar.isreleased,0) as isreleased,ar.timestamp,
				case when (isnull(ar.isreleased,0)=1) then 'Released' else 'Assigned' end as releasestatus,
				case when (isnull(ar.isreleased,0)=1) then '#17a34f' else '#e6a825' end as releasestatuscolor
				FROM tblassignrangelane ar 
				inner join tblrangemaster rm on rm.id=ar.rangeid 
				inner join tbllanemaster lm on lm.id=ar.laneid 
				INNER JOIN tblpersonmaster pm on pm.id=ar.memberid 
				WHERE isnull(pm.isdelete,0)=0 AND isnull(pm.isverified,0)=1  ";
			$parms = array(
				':defualtmemberimageurl'=>$config->getDefualtMemberImageurl(),
				':imageurl'=>$imgpath,
			);

			if($storeid)
			{
				$qry.=" and ar.storeid LIKE :storeid ";
				$parms[':storeid']=$storeid;
			}

			if($fltmemberid != '%' && $fltmemberid != '')
			{
				$qry.=" and ar.memberid LIKE :fltmemberid ";
				$parms[':fltmemberid']=$fltmemberid;
			}

			if($fltrangeid != '%' && $fltrangeid != '')
			{
				$qry.=" and ar.rangeid LIKE :fltrangeid ";
				$parms[':fltrangeid']=$fltrangeid;
			}

			if($fltlaneid != '%' && $fltlaneid != '')
			{
				$qry.=" and ar.laneid LIKE :fltlaneid ";
				$parms[':fltlaneid']=$fltlaneid;
			}

			if($fltfromdate && $flttodate)
			{
				$qry.=" and CONVERT(date,ar.date,103) between  CONVERT(date,:fromdate,103) and  CONVERT(date,:todate,103) ";
				$parms[':fromdate']=$fltfromdate; 
				$parms[':todate']=$flttodate; 
			}

			$qry.=" order by ar.timestamp desc ";

			$memberdata=$DB->getmenual($qry,$parms,'listmemberrangedata');
			
			$response['ismemberdata']=0;
			if(sizeof($memberdata) > 0)
			{
				$response['ismemberdata']=1;

				for($i=0;$i<sizeof($memberdata);$i++)
				{
					/************************* Start For Returnable Item Details ****************************/
					$itemdetail=new itemdetail();
					$qryitem="select sod.itemid as id,REPLACE(sod.itemname,'&amp;','&') as name,sum(sod.qty) as qty
							from tblstoreorderdetail sod 
							inner join tblstoreorder so on so.id=sod.orderid
							where so.status=1 and so.uid=:uid and convert(date,so.orderdate,103)=convert(date,:date,103) and (sod.catid=:returnablecatid or sod.catid=:consumablecatid)
							group by sod.itemid,sod.itemname,sod.catid";	
					$itemparams = array(
						':uid'=>$memberdata[$i]->getId(),  
						//':date'=>$date,
						':date'=>$memberdata[$i]->getDate(),  
						':returnablecatid'=>$config->getDefaultCatReturnableId(),
						':consumablecatid'=>$config->getDefaultCatConsumableId(),
					);
					$itemdetail=$DB->getmenual($qryitem,$itemparams,'itemdetail');

					$memberdata[$i]->setIsItemDetail(0);
					if(sizeof($itemdetail)>0)
					{
						$memberdata[$i]->setIsItemDetail(1);
						$memberdata[$i]->setItemDetail($itemdetail);
					}
					/************************* End For Returnable Item Details ****************************/
				}
	

				$response['memberdata']= json_decode(json_encode($memberdata));


				//$response['memberdata']=$memberdata;
				
				$status = 1;
				$message = $errmsg['success'];
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

		$response['fltfromdate']=(string)$fltfromdate;
		$response['flttodate']=(string)$flttodate;
		
	}
	//Release Assigned Range Data
	else if($action == 'releaserangedata')
	{
		$rangeassignid=$IISMethods->sanitize($_POST['rangeassignid']);   
		
		$datetime=$IISMethods->getdatetime();
		
		if($rangeassignid)
		{
			try 
			{ 
				$DB->begintransaction();

				$qryar="select ar.id,isnull(ar.isreleased,0) as isreleased from tblassignrangelane ar where ar.id=:rangeassignid";
				$arparms = array(
					':rangeassignid'=>$rangeassignid,
				);
				$resar=$DB->getmenual($qryar,$arparms);

				if(sizeof($resar) > 0)
				{
					$rowar=$resar[0];

					if($rowar['isreleased'] == 0)
					{
						$updar['[isreleased]']=1;
						$updar['[released_uid]']=$uid;
						$updar['[released_date]']=$datetime;

						$upddata['[id]']=$rowar['id'];
						$DB->executedata('u','tblassignrangelane',$updar,$upddata);

						
						$status = 1;
						$message=$errmsg['rangereleasesuccess'];
					}
					else
					{
						$status = 0;
						$message=$errmsg['rangereleasealready'];
					}
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
		else
		{
			$status=0;
			$message=$errmsg['reqired'];
		}
		
	}
	


	
	
	

	
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  