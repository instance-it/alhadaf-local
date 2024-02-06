<?php 
require_once dirname(__DIR__, 1).'/config/init.php';

$storeid=$IISMethods->sanitize($_POST['storeid']);
$memberid=$IISMethods->sanitize($_POST['memberid']);
$rangeid=$IISMethods->sanitize($_POST['rangeid']);
$laneid=$IISMethods->sanitize($_POST['laneid']);

$status=$IISMethods->sanitize($_POST['status']);

$withserviceorderdetail=$IISMethods->sanitize($_POST['withserviceorderdetail']);

$fromdate=$IISMethods->sanitize($_POST['fromdate']);
$todate=$IISMethods->sanitize($_POST['todate']);

$curtime = date('h:i A');
$currdate=$IISMethods->getdatetime();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title><?php echo $Pagerights->getFormnametext() ?> | <?php echo $CompanyInfo->getCompanyname() ?></title>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <?php require_once '../css.php'; ?>
        
        <!-- END GLOBAL MANDATORY STYLES -->
    </head>
    <body class="alt-menu reportdata">
        
        <?php //require_once '../header.php'; ?>
        <div class="main-container sidebar-closed sbar-open" id="container">
            <div class="overlay"></div>
            <div class="search-overlay"></div>

            <!--  BEGIN SIDEBAR  -->
            <?php //require_once '../sidebar.php'; ?>
            <!--  END SIDEBAR  -->

            <!--  BEGIN CONTENT AREA  -->
            <div id="content" class="main-content" style="margin-left: 10px;margin-right: 10px; margin-top: 20px;">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <!-- <a style="float: right ;margin: 0px 20px " href="javascript:void(0)" id="csvexport" class="btn btn-info">Export Data</a> -->
                        <input style="float: right;margin-bottom:10px;" type="button" type="button" style="padding: 5px;" class="printbutton btn btn-warning" onclick="printDiv('mainReportDiv')" value="Print Report"/>
                    </div>
                </div>

                <div class="widget" id="mainReportDiv">
                    <div class="widget-control">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-theme-table-header text-center mb-2">
                                <h5 class="m-0 menunamelbl"><b><?php echo $Pagerights->getFormnametext() ?></b></h5>
                                <?php
                                if($fromdate && $todate)
                                {
                                ?>
                                    <p><?php echo $fromdate.' To '.$todate; ?></p>   
                                <?php
                                }
                                ?>
                            </div>
                            
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="row min-height-100">
                            <div class="col-12 min-height-100">
                                <div class="table-responsive main-grid">
                                    
                                    <?php
                                    
                                    $qry="SELECT distinct pm.id,isnull(pm.personname,'') as personname,isnull(pm.contact,'') as contact,isnull(pm.email,'') as email,
                                        ar.id as rangeassignid,ar.storeid,sm.storename,ar.rangeid,rm.rangename,ar.laneid,lm.name as lanename,convert(varchar, ar.timestamp,100) AS date,convert(date, ar.timestamp,103) AS ass_date,isnull(ar.isreleased,0) as isreleased,ar.timestamp,
                                        case when (isnull(ar.isreleased,0)=1) then convert(varchar(50),ar.released_date,100) else '' end as releasedatetime,
                                        case when (isnull(ar.isreleased,0)=1) then 'Released' else 'Assigned' end as releasestatus,
                                        case when (isnull(ar.isreleased,0)=1) then '#17a34f' else '#e6a825' end as releasestatuscolor
                                        FROM tblassignrangelane ar 
                                        inner join tblrangemaster rm on rm.id=ar.rangeid 
                                        inner join tbllanemaster lm on lm.id=ar.laneid 
                                        inner join tblstoremaster sm on sm.id=ar.storeid 
                                        INNER JOIN tblpersonmaster pm on pm.id=ar.memberid 
                                        WHERE isnull(pm.isdelete,0)=0 AND isnull(pm.isverified,0)=1  ";
                                    $parms = array(
                                    );

                                    if($storeid != '%' && $memberid != '')
                                    {
                                        $qry.=" and ar.storeid LIKE :storeid ";
                                        $parms[':storeid']=$storeid;
                                    }

                                    if($memberid != '%' && $memberid != '')
                                    {
                                        $qry.=" and ar.memberid LIKE :memberid ";
                                        $parms[':memberid']=$memberid;
                                    }

                                    if($rangeid != '%' && $rangeid != '')
                                    {
                                        $qry.=" and ar.rangeid LIKE :rangeid ";
                                        $parms[':rangeid']=$rangeid;
                                    }

                                    if($laneid != '%' && $laneid != '')
                                    {
                                        $qry.=" and ar.laneid LIKE :laneid ";
                                        $parms[':laneid']=$laneid;
                                    }

                                    if($status != '%' && $status != '')
                                    {
                                        $qry.=" and isnull(ar.isreleased,0) LIKE :status ";
                                        $parms[':status']=$status;
                                    }

                                    if($fromdate && $todate)
                                    {
                                        //$qry.=" and CONVERT(date,ar.date,103) between  CONVERT(date,:fromdate,103) and  CONVERT(date,:todate,103) ";
                                        $qry.=" and ar.timestamp between  :fromdate and :todate ";
                                        
                                        $parms[':fromdate']=$fromdate; 
                                        $parms[':todate']=$todate; 
                                    } 

                                    $qry.=" order by ar.timestamp desc ";
                                    //echo $qry;
                                    $res=$DB->getmenual($qry,$parms);

                    
                                    if(sizeof($res)>0)
                                    {
                                        ?>
                                        <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                            <thead>
                                                <tr>
                                                    <th class="tbl-name text-center" style="width: 5%;">No</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Store</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Member</th>
                                                    <th class="tbl-name text-left" style="width: 10%;">Range</th>
                                                    <th class="tbl-name text-left" style="width: 10%;">Lane</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Assigned Date</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Released Datetime</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="datalist">
                                                <?php
                                                    for($i=0;$i<sizeof($res);$i++)
                                                    {
                                                        $row=$res[$i];
                                                        ?>
                                                        <tr>
                                                            <td class="tbl-name text-center"><?php echo $i+1; ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($row['storename']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($row['personname']).' ('.$IISMethods->sanitize($row['contact']).')'; ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($row['rangename']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($row['lanename']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($row['date']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($row['releasedatetime']); ?></td>
                                                            <td class="tbl-name text-center"><span style="background: <?php echo $IISMethods->sanitize($row['releasestatuscolor']); ?>;padding: 2px 6px;border-radius: 6px;"><?php echo $IISMethods->sanitize($row['releasestatus']); ?></span></td> 
                                                            
                                                        </tr>
                                                        <?php

                                                        if($withserviceorderdetail == 1)  //Service Order Item Detail
                                                        {

                                                            /************************ Start For Service Order Item Details *************************/
                                                            $qryod="SELECT sod.itemid,sod.itemname,sod.category,sum(sod.qty) as totalqty
                                                                from tblserviceorderdetail sod 
                                                                inner join tblserviceorder so on sod.orderid=so.id
                                                                where so.uid=:uid and convert(date,so.timestamp,103)=:assigndate and isnull(so.iscancel,0)=0 and isnull(sod.iscancel,0)=0 
                                                                group by  sod.itemid,sod.itemname,sod.category";
                                                            $odparams = array(
                                                                ':uid'=>$row['id'],
                                                                ':assigndate'=>$row['ass_date'],
                                                                //':itemid'=>$itemid,
                                                            );
                                                            $resod=$DB->getmenual($qryod,$odparams);
                                                            if(sizeof($resod)>0)
                                                            {
                                                            ?>
                                                            <tr> 
                                                                <td colspan="2" class="text-center"><b>Service Order Detail</b></td>
                                                                <td colspan="5">
                                                                    <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="tbl-name text-center" style="width: 4%;">No</th>
                                                                                <th class="tbl-name text-left" style="width: 25%;">Item</th>
                                                                                <th class="tbl-name text-center" style="width: 5%;">Qty</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="datalist">
                                                                            <?php
                                                                                for($ii=0;$ii<sizeof($resod);$ii++)
                                                                                {
                                                                                    $rowod=$resod[$ii];
                                                                                ?>
                                                                                    <tr>
                                                                                        <td class="tbl-name text-center"><?php echo $ii+1; ?></td>
                                                                                        <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowod['itemname']) ?> (<?php echo $IISMethods->sanitize($rowod['category']) ?>)</td>
                                                                                        <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowod['totalqty']) ?></td>
                                                                                       
                                                                                    </tr>
                                                                                <?php
                                                                                }
                                                                            ?>
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                                <td></td>

                                                            </tr>
                                                            <?php
                                                            }
                                                            /************************ End For Service Order Item Details *************************/


                                                        }
    
                                                    }
                                                ?>
                                                
                                            </tbody>
                                        </table>
                                        <br>
                                            <!-- table End -->
                                        <?php
                                        
                                    }
                                    else
                                    {
                                    ?>
                                        <h5 class="m-0 menunamelbl text-center mt-5"><b><?php echo $config->getErrmsg()['nodatafound']; ?> </b></h5>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  END CONTENT AREA  -->

            
            <!--  START FOOTER AREA  -->
            <?php //require_once '../footer.php'; ?>
            <!--  END FOOTER AREA  -->
        </div>
        <?php require_once '../js.php'; ?>

        
    </body>
</html>    
<script>
function printDiv(divName) 
{
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}
</script>