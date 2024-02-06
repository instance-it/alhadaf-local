<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\rangebooking.php';

if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imgpath=$config->getImageurl();
	$datetime=$IISMethods->getdatetime();
	$currdate=$IISMethods->getcurrdate();
	$currfordate=$IISMethods->getformatcurrdate();


	
	//List Equipment Data
	if($action == 'listservicetype')
	{	
		$servicetype=new servicetype();

		$qry="select st.id,st.type,REPLACE(st.type,'&amp;','&') as name from tblservicetypemaster st where st.isactive = 1 ";
		$parms = array();
		$servicetype=$DB->getmenual($qry,$parms,'servicetype');

		$response['isservicetype']=0;
		if($servicetype)
		{
			$response['isservicetype']=1;
			$response['servicetype']=$servicetype;
			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}
	//Insert Range Booking Data
	else if($action == 'insertrangebooking')
	{
		$rb_fname = $IISMethods->sanitize($_POST['rb_fname']);
		$rb_lname = $IISMethods->sanitize($_POST['rb_lname']);
		$rb_email = $IISMethods->sanitize($_POST['rb_email']);
		$rb_mobile = $IISMethods->sanitize($_POST['rb_mobile']);
		$rb_qataridno = $IISMethods->sanitize($_POST['rb_qataridno']);
		$rb_qataridexpiry = $IISMethods->sanitize($_POST['rb_qataridexpiry']);
		$rb_passportidno = $IISMethods->sanitize($_POST['rb_passportidno']);
		$rb_passportidexpiry = $IISMethods->sanitize($_POST['rb_passportidexpiry']);
		$rb_dob = $IISMethods->sanitize($_POST['rb_dob']);
		$rb_nationality = $IISMethods->sanitize($_POST['rb_nationality']);
		$rb_address = $IISMethods->sanitize($_POST['rb_address']);
		$rb_companyname = $IISMethods->sanitize($_POST['rb_companyname']);
		$rb_servicetypeid = $IISMethods->sanitize($_POST['rb_servicetypeid']);
		
	
		$datetime=$IISMethods->getdatetime();
		$unqid = $IISMethods->generateuuid();

		if($rb_fname && $rb_lname && $rb_email && $rb_mobile && $rb_qataridno && $rb_qataridexpiry && $rb_dob && $rb_nationality && $rb_passportidno && $rb_passportidexpiry)
		{
			if($rb_servicetypeid == '')
			{
				$rb_servicetypeid=$mssqldefval['uniqueidentifier'];
			}
			
			try 
			{
				$DB->begintransaction();

				$personname=$rb_fname.' '.$rb_lname;
				$insqry=array(					
					'[id]'=>$unqid,	
					'[type]'=>1,
					'[uid]'=>$mssqldefval['uniqueidentifier'],
					'[personname]'=>$personname,	
					'[firstname]'=>$rb_fname,	
					'[lastname]'=>$rb_lname,	
					'[contact]'=>$rb_mobile,		
					'[email]'=>$rb_email,		
					'[qataridno]'=>$rb_qataridno,
					'[qataridexpiry]'=>$rb_qataridexpiry,
					'[passportidno]'=>$rb_passportidno,
					'[passportidexpiry]'=>$rb_passportidexpiry,
					'[address]'=>$rb_address,	
					'[dob]'=>$rb_dob,
					'[nationality]'=>$rb_nationality,
					'[companyname]'=>$rb_companyname,
					'[servicetypeid]'=>$rb_servicetypeid,
					'[date]'=>$currdate,	
					'[platform]'=>$platform,	
					'[timestamp]'=>$datetime,	
					'[entry_date]'=>$datetime,	

				);
				$DB->executedata('i','tblrangebooking',$insqry,'');

				$status=1;
				$message=$errmsg['rangebooksuccess'];
				
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
	else if($action == 'listrangebooking')
	{
		$rangebooking=new rangebooking();

		$qry="select id,rangename as name from tblrangemaster where isactive = 1 ";
		$parms = array();
		$result_ary=$DB->getmenual($qry,$parms,'rangebooking');

		$response['israngedata']=0;
		if(sizeof($result_ary) > 0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				
				$LaneDetail=new LaneDetail();
				$qrylane="select id,lanename as name from tblrangelane where rangeid=:rangeid";
				$laneparms = array(
					':rangeid'=>$result_ary[$i]->getId(), 
				);
				$LaneDetail=$DB->getmenual($qrylane,$laneparms,'LaneDetail');

				$result_ary[$i]->setIsLanedata(0);

				if(sizeof($LaneDetail)>0)
				{
					$result_ary[$i]->setIsLanedata(1);
					$result_ary[$i]->setLanedetail($LaneDetail);
				}

				
				$TimeDetail=new TimeDetail();
				$qrytime="select id,fromtime,totime from tblrangetime where rangeid=:rangeid";
				$timeparms = array(
					':rangeid'=>$result_ary[$i]->getId(), 
				);
				$TimeDetail=$DB->getmenual($qrytime,$timeparms,'TimeDetail');
				$result_ary[$i]->setIsTimedata(0);
				if(sizeof($TimeDetail)>0)
				{
					$result_ary[$i]->setIsTimedata(1);
					$result_ary[$i]->setTimedetail($TimeDetail);
				}

			}

			$response['israngedata']=1;
			$response['rangedata']=json_decode(json_encode($result_ary));

			
			$status = 1;
			$message = $errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nodatafound'];
		}
	}	
	//List Booking Slots
	else if($action=='listbookingtimeslot')   
	{	
		$date = $IISMethods->sanitize($_POST['date']);

		if($date == '')
		{
			$date=$currdate;
		}

		$set_fromtime=$ProjectSetting->getWorkFromTime();
		$set_totime=$ProjectSetting->getWorkToTime();   
		$set_bookduration=$ProjectSetting->getBookDuration();
		
		$str_set_totime=strtotime($set_totime);
		$prev_fromtime=$set_fromtime;

		$isbreak=0;
		for($i=0;$i<50;$i++)
		{
			if($isbreak == 1)
			{
				break;
			}
			
			$fromtime=$prev_fromtime;
			$str_fromtime=strtotime($fromtime);

			$str_totime=strtotime($set_bookduration." minutes", strtotime($fromtime));
			
			if($str_set_totime > $str_totime)
			{
				$totime=date("h:i A", $str_totime);
				$n_totime=date("h:i A", $str_totime);
			}
			else
			{
				$totime=$set_totime;
				$n_totime=$set_totime;
				$isbreak=1;
			}

			$prev_fromtime=$n_totime;


			$response['timeslot'][$i]['id']=$i+1;
			$response['timeslot'][$i]['name']=$fromtime.' to '.$totime;
			$response['timeslot'][$i]['fromtime']=$fromtime;
			$response['timeslot'][$i]['totime']=$totime;


			$qrydt="SELECT 
				CASE WHEN (stuff(convert(varchar(19),CONVERT(DateTime,CONCAT(CONVERT(date,:date,103),' ',:fromtime)) , 126),11,1,' ') < :currdatetime) THEN 1 ELSE 0 END AS ispasttime,
				case when (convert(date,:rbdate,103)>:currfordate) then 1 else 0 end as isfuturedate";
			$dtparams = array(
				':date'=>$currdate,
				':fromtime'=>$fromtime,
				':currdatetime'=>$datetime,
				':currfordate'=>$currfordate,
				':rbdate'=>$date,
			);	
			$resdt=$DB->getmenual($qrydt,$dtparams);
			$rowdt=$resdt[0];

			if($rowdt['ispasttime'] == 1 && $rowdt['isfuturedate'] == 0)  //When Past Time
			{
				$response['timeslot'][$i]['isbooked']=1;
				//$response['timeslot'][$i]['colorcode']='#6c6c6c';
			}
			else
			{
				$response['timeslot'][$i]['isbooked']=0;
				//$response['timeslot'][$i]['colorcode']='#e0e0e1';
			}
			
		}

		$status=1;
		$message=$errmsg['success'];	
	}
	//Insert Range Booking Slot Data
	else if($action == 'insertrangebookingslot')
	{
		$date = $IISMethods->sanitize($_POST['date']);
		$fromtime = $IISMethods->sanitize($_POST['fromtime']);
		$totime = $IISMethods->sanitize($_POST['totime']);
	
		$datetime=$IISMethods->getdatetime();
		$unqid = $IISMethods->generateuuid();

		if($fromtime == 'undefined')
		{
			$fromtime='';
		}

		if($totime == 'undefined')
		{
			$totime='';
		}

		if($date && $fromtime && $totime)
		{
			try 
			{
				$DB->begintransaction();

				$qrychk="select id from tblrangebooking 
					where type=2 and uid=:uid and fromtime=:fromtime and totime=:totime and convert(date,date,103)=convert(date,:date,103)";
				$chkparms = array(
					':uid'=>$uid,
					':fromtime'=>$fromtime,
					':totime'=>$totime,
					':date'=>$date,
				);
				$reschk=$DB->getmenual($qrychk,$chkparms);

				if(sizeof($reschk) > 0)
				{
					$status=0;
					$message=$errmsg['slotbookalready'];
				}
				else
				{
					$insqry=array(					
						'[id]'=>$unqid,	
						'[type]'=>2,
						'[uid]'=>$uid,
						'[date]'=>$date,
						'[fromtime]'=>$fromtime,	
						'[totime]'=>$totime,	
						'[platform]'=>$platform,	
						'[timestamp]'=>$datetime,	
						'[entry_date]'=>$datetime,	
	
					);
					$DB->executedata('i','tblrangebooking',$insqry,'');
	
					$status=1;
					$message=$errmsg['slotbooksuccess'];
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



require_once dirname(__DIR__, 2).'\config\apifoot.php';  

?>

  