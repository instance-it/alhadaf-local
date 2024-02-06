<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\memberfreezemship.php';

error_reporting(1);
if($isvalidUser['status'] == 1) 
{
	$status=0;
	$message=$errmsg['invalidrequest'];
    $currdate=$IISMethods->getformatcurrdate();
	
	//Fill Member Mship
	if($action == 'fillmembermship')
	{
		$memberid=$IISMethods->sanitize($_POST['memberid']);
		$selectoption=$IISMethods->sanitize($_POST['selectoption']);

        if($memberid == '')
        {
            $memberid=$mssqldefval['uniqueidentifier'];
        }

        $qrymship="select od.id as id,od.itemname as name,od.expirydate,
        isnull((select format(convert(date,DATEADD(day,sum(DATEDIFF(day, convert(date,mf.startdate,103), convert(date,mf.enddate,103)) + 1),convert(date,od.expirydate,103)),120),'dd/MM/yyyy') 
            from tblmemberfreezemship mf where mf.membershipid=od.id),od.expirydate) as new_expirydate
        from tblorder o
        inner join tblorderdetail od on od.orderid = o.id
        where o.uid = :memberid and o.status=1 and o.iscancel = 0 and convert(date,od.n_expirydate,103) >= :currdate order by od.timestamp desc";
        $mshipparams = array(
            ':memberid'=>$memberid, 
            ':currdate'=>$currdate, 
        );
        $result_ary=$DB->getmenual($qrymship,$mshipparams);

		$htmldata='';
		if($selectoption == 1)
		{
			$htmldata.='<option value="">Select Membership</option>';
		}

		if(sizeof($result_ary)>0)
		{
			for($i=0;$i<sizeof($result_ary);$i++)
			{
				$row=$result_ary[$i];
				$htmldata.='<option value="'.$row['id'].'" data-nexpirydate="'.$row['new_expirydate'].'">'.$row['name'].' (Old Expiry : '.$row['expirydate'].') (New Expiry : '.$row['new_expirydate'].')</option>';
			}
		}
		$response['data']=$htmldata;

		$status=1;
		$message=$errmsg['success'];
	} 
    //Insert Member Freeze Membership Data
    else if($action=='insertmemberfreezemship')
    {
        $memberid=$IISMethods->sanitize($_POST['memberid']);
		$f_mshipid=$IISMethods->sanitize($_POST['f_mshipid']);
		$f_startdate=$IISMethods->sanitize($_POST['f_startdate']);
		$f_enddate=$IISMethods->sanitize($_POST['f_enddate']);		
		$f_descr=$IISMethods->sanitize($_POST['f_descr']);
        
		$datetime=$IISMethods->getdatetime();
		if($memberid && $f_mshipid && $f_startdate && $f_enddate && $f_descr)
		{	
            $qrychk="select mf.id
            from tblmemberfreezemship mf 
            where mf.memberid=:memberid and mf.membershipid=:membershipid and 
            ( (convert(date,:startdate1,103) between convert(date,mf.startdate,103) and convert(date,mf.enddate,103)) or (convert(date,mf.startdate,103) between convert(date,:startdate,103) and convert(date,:enddate,103)) )";
            $parms = array(
                ':memberid'=>$memberid,
                ':membershipid'=>$f_mshipid,
                ':startdate1'=>$f_startdate,
                ':startdate'=>$f_startdate,
                ':enddate'=>$f_enddate,
            );
            $result_ary=$DB->getmenual($qrychk,$parms);
            if(sizeof($result_ary) > 0)
            {
                $status=0;
                $message=$errmsg['alreadyfreeze'];
            }
            else
            {    
                try 
                {
                    $DB->begintransaction();

                    $unqid = $IISMethods->generateuuid();

                    $insqry=array(		
                        '[id]'=>$unqid,			
                        '[memberid]'=>$memberid,
                        '[membershipid]'=>$f_mshipid,
                        '[startdate]'=>$f_startdate,
                        '[enddate]'=>$f_enddate,
                        '[descr]'=>$f_descr,
                        '[timestamp]'=>$datetime,
                        '[entry_uid]'=>$uid,
                        '[entry_date]'=>$datetime,
                                        
                    );
                    $DB->executedata('i','tblmemberfreezemship',$insqry,'');


                    /************* Start For Update New Expiry Date ***************/
                    $qrymship="select od.id as id,
                        isnull((select format(convert(date,DATEADD(day,sum(DATEDIFF(day, convert(date,mf.startdate,103), convert(date,mf.enddate,103)) + 1),convert(date,od.expirydate,103)),120),'dd/MM/yyyy') 
                            from tblmemberfreezemship mf where mf.membershipid=od.id),od.expirydate) as new_expirydate
                        from tblorder o
                        inner join tblorderdetail od on od.orderid = o.id
                        where o.uid = :memberid and od.id=:odid";
                    $mshipparams = array(
                        ':memberid'=>$memberid, 
                        ':odid'=>$f_mshipid, 
                    );
                    $result_ary=$DB->getmenual($qrymship,$mshipparams);
                    if(sizeof($result_ary) > 0)
                    {
                        $updqry=array(					
                            '[n_expirydate]'=>$result_ary[0]['new_expirydate'],				
                        );
                        $extraparams=array(
							'[id]'=>$result_ary[0]['id']
						);
						$DB->executedata('u','tblorderdetail',$updqry,$extraparams);

                    }
                    /************* End For Update New Expiry Date ***************/


                    $status=1;
                    $message=$errmsg['freezesuccess'];

                    $DB->committransaction($DBLog);
                }
                catch (Exception $e) 
                {
                    $DB->rollbacktransaction($e,$DBLog);
                    $status=0;
                    $message=$errmsg['dbtransactionerror'];
                }
            }
			
					
		}
		else
		{
			$message=$errmsg['reqired'];
		}
    }

    else if($action=='listmemberfreezemship')
    {
        $memberid=$IISMethods->sanitize($_POST['memberid']);
        //$memberid='B717D900-6B26-4960-B90F-06032F60A6E7';

        if($memberid)
        {
            $memberfreezemship=new memberfreezemship();

            $qry="SELECT mf.id,mf.memberid,mf.membershipid,mf.startdate,mf.enddate,mf.descr,mf.timestamp,od.itemname as mshipname,od.expirydate,od.n_expirydate,convert(varchar,mf.timestamp,100) as fullentrydate,
                case when (convert(date,mf.startdate,103) <= :currdate1 and convert(date,mf.enddate,103) >= :currdate2) then 'Active' else '' end as fmstatus,
                case when (convert(date,mf.startdate,103) <= :currdate3 and convert(date,mf.enddate,103) >= :currdate4) then 'btn-success' else '' end as fmstatusclass 
                FROM tblmemberfreezemship mf
                INNER JOIN  tblorderdetail od ON od.id=mf.membershipid
                WHERE mf.memberid=:memberid ORDER BY mf.timestamp DESC";
            
            $parms = array(
                ':memberid'=>$memberid,
                ':currdate1'=>$currdate, 
                ':currdate2'=>$currdate, 
                ':currdate3'=>$currdate, 
                ':currdate4'=>$currdate, 
            );
            $memberfreezemship=$DB->getmenual($qry,$parms,'memberfreezemship');

            if($responsetype=='HTML')
            {
                if(sizeof($memberfreezemship)>0)
                {
                    $i=0;
                    foreach($memberfreezemship as $memberfreezemship)
                    {
                        $id="'".$memberfreezemship->id."'";
                        $htmldata.='<tr data-index="'.$i.'">';
                        $htmldata.='<td class="tbl-name"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-original-title="View Description" onclick="viewfreezemshipdescr('.$IISMethods->sanitize($id).')">'.$IISMethods->sanitize($memberfreezemship->mshipname).'</a><br><span style="font-size: 10px;">Expiry Date : '.$IISMethods->sanitize($memberfreezemship->n_expirydate).'</span></td>';
                        $htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($memberfreezemship->startdate).'</td>';
                        $htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($memberfreezemship->enddate).'</td>';
                        $htmldata.='<td class="tbl-name">'.$IISMethods->sanitize($memberfreezemship->fullentrydate).'</td>';
                        $htmldata.='<td class="tbl-name">';
                        $htmldata.='<span class="btn '.$IISMethods->sanitize($memberfreezemship->fmstatusclass).' px-2 py-0">'.$IISMethods->sanitize($memberfreezemship->fmstatus).'</span>';
                        $htmldata.='</td>';
                        $htmldata.='<td class="tbl-name text-right">';
                        if(((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1) || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1 ) //admin all right,alleditright - all person data show,selfedit - only self person data show
						{
							$htmldata.='<a href="javascript:void(0)" class="btn-tbl text-primary m-0 rounded-circle" id="btnremovefreezemship" onclick="removefreezemship('.$IISMethods->sanitize($id).')" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="bi bi-x-lg"></i></a>';
						}
                        $htmldata.='</td>';

                        $htmldata.='</tr>';
                        $i++;
                    }
                    $status=1;
                    $message=$errmsg['success'];
                }
                else
                {	

                    $htmldata.='<tr >';
                    $htmldata.='<td class="text-center" colspan="6">'.$errmsg['nodatafound'].'</td>';
                    $htmldata.='</tr>';
                   
                }
                $response['data']=$htmldata;
            }
            else if($responsetype=='JSON')
            {
                $response['data']= json_encode($memberfreezemship);
            }


        }
    }
    else if($action=='viewfreezemshipdescr')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
			$qrychk="SELECT mf.id,mf.descr
			from tblmemberfreezemship mf 
			where mf.id=:id";
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

					$tbldata.='<div class="col-12 col-lg-12 col-xl-12 "><div class="row">';
					$tbldata.='<div class="col-12 col-sm-12 col-lg-12">';
					$tbldata.='<label class="label-view"><b>'.$row['descr'].'</b></label>';
					$tbldata.='</div></div></div>';
					

				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';
				$tbldata.='</div>';										
				$tbldata.='</div>';

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
    else if($action=='removefreezemship')   
	{
		$id=$IISMethods->sanitize($_POST['id']);
		if($id)
		{
            $qrymfreeze="select id,memberid,membershipid from tblmemberfreezemship where id=:id";
            $mfreezeparams = array(
                ':id'=>$id, 
            );
            $resmfreeze=$DB->getmenual($qrymfreeze,$mfreezeparams);


			$extraparams=array(
				'id'=>$id
			);
			$DB->executedata('d','tblmemberfreezemship','',$extraparams);


            /************* Start For Update New Expiry Date ***************/
            $qrymship="select od.id as id,
                isnull((select format(convert(date,DATEADD(day,sum(DATEDIFF(day, convert(date,mf.startdate,103), convert(date,mf.enddate,103)) + 1),convert(date,od.expirydate,103)),120),'dd/MM/yyyy') 
                    from tblmemberfreezemship mf where mf.membershipid=od.id),od.expirydate) as new_expirydate
                from tblorder o
                inner join tblorderdetail od on od.orderid = o.id
                where o.uid = :memberid and od.id=:odid";
            $mshipparams = array(
                ':memberid'=>$resmfreeze[0]['memberid'], 
                ':odid'=>$resmfreeze[0]['membershipid'], 
            );
            $result_ary=$DB->getmenual($qrymship,$mshipparams);
            if(sizeof($result_ary) > 0)
            {
                $updqry=array(					
                    '[n_expirydate]'=>$result_ary[0]['new_expirydate'],				
                );
                $extraparams=array(
                    '[id]'=>$result_ary[0]['id']
                );
                $DB->executedata('u','tblorderdetail',$updqry,$extraparams);

            }
            /************* End For Update New Expiry Date ***************/


			$status=1;
			$message=$errmsg['delete'];
		}
		else
		{
			$message=$errmsg['reqired'];
		}
	}
    


}

require_once dirname(__DIR__, 3).'\config\apifoot.php';  

?>

  