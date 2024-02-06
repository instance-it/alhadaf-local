<?php 
require_once dirname(__DIR__, 1).'/config/init.php';

$memberid=$IISMethods->sanitize($_POST['memberid']);

$categoryid=$IISMethods->sanitize($_POST['categoryid']);
$subcategoryid=$IISMethods->sanitize($_POST['subcategoryid']);
$itemid=$IISMethods->sanitize($_POST['itemid']);

$paymenttype=$IISMethods->sanitize($_POST['paymenttype']);

$withitemdetail=$IISMethods->sanitize($_POST['withitemdetail']);
$withfulldetail=$IISMethods->sanitize($_POST['withfulldetail']);

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
                                    $totalamount=0;

                                    $qryord="select distinct o.timestamp as primary_date,o.id,o.transactionid,o.orderno,isnull(o.iscancel,0) as iscancel,pm.personname,pm.contact,
                                        convert(varchar, o.timestamp,100) AS ofulldate,o.totalpaid,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,
                                        case when (o.iscancel = 1) then 'Cancelled' else 'Confirmed' end as ordstatusname,
                                        case when (isnull(o.paymenttype,0) = 1) then 'Cash' when (isnull(o.paymenttype,0) = 2) then 'Online Payment' else '' end as paymenttypename
                                        from tblorder o 
                                        inner join tblpersonmaster pm on pm.id=o.uid 
                                        inner join tblpersonmaster pm1 on pm1.id=o.entry_uid 
                                        inner join tblorderdetail od on od.orderid=o.id 
                                        inner join tblitemmaster im on im.id=od.itemid
                                        where convert(varchar(50),o.uid) like :memberid and convert(varchar(50),im.categoryid) like :categoryid and convert(varchar(50),im.subcategoryid) like :subcategoryid and convert(varchar(50),im.id) like :itemid and isnull(o.paymenttype,0) like :paymenttype ";
                                    $ordparms = array(
                                        ':memberid'=>$memberid,
                                        ':categoryid'=>$categoryid,
                                        ':subcategoryid'=>$subcategoryid,
                                        ':itemid'=>$itemid,
                                        ':paymenttype'=>$paymenttype,
                                    );

                                    if($fromdate && $todate)
                                    {
                                        //$qryord.=" AND CONVERT(date,o.timestamp,103) BETWEEN CONVERT(date,:fromdate,103) and CONVERT(date,:todate,103)";
                                        $qryord.=" AND o.timestamp BETWEEN :fromdate and :todate";
                                       
                                        $ordparms[':fromdate']=$fromdate;	
                                        $ordparms[':todate']=$todate;	
                                 
                                    } 
                                    $qryord.=" order by o.timestamp desc";
                                    //echo $qrymember;
                                    $resord=$DB->getmenual($qryord,$ordparms);

                    
                                    if(sizeof($resord)>0)
                                    {
                                        ?>
                                        <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                            <thead>
                                                <tr>
                                                    <th class="tbl-name text-center" style="width: 2%;">No</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Transaction ID</th>
                                                    <th class="tbl-name text-center" style="width: 8%;">Order No</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Member</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Payment Type</th>
                                                    <th class="tbl-name text-right" style="width: 8%;">Amount</th>
                                                    <th class="tbl-name text-center" style="width: 15%;">Order Date</th>
                                                    <th class="tbl-name text-center" style="width: 8%;">Order Status</th>
                                                    <th class="tbl-name text-left" style="width: 15%;">Entry By</th>
                                                </tr>
                                            </thead>
                                            <tbody id="datalist">
                                                <?php
                                                    for($i=0;$i<sizeof($resord);$i++)
                                                    {
                                                        $roword=$resord[$i];

                                                        $totalamount+=round($roword['totalpaid'],2);
                                                        ?>
                                                        <tr>
                                                            <td class="tbl-name text-center"><?php echo $i+1; ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($roword['transactionid']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($roword['orderno']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($roword['personname']).' ('.$IISMethods->sanitize($roword['contact']).')'; ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($roword['paymenttypename']); ?></td>
                                                            <td class="tbl-name text-right"><?php echo $IISMethods->sanitize(round($roword['totalpaid'],2)); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($roword['ofulldate']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($roword['ordstatusname']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($roword['entrypersonname']).' ('.$IISMethods->sanitize($roword['entrypersoncontact']).')'; ?></td> 
                                                            
                                                        </tr>
                                                        <?php

                                                        if($withitemdetail == 1 || $withfulldetail == 1)  //Order Item Detail
                                                        {
                                                            $qryod="select od.id,od.type,od.itemname,od.durationname,od.price,od.taxable,od.igsttaxamt,od.finalprice,od.igst,isnull(od.couponamount,0) as couponamount,od.expirydate,od.n_expirydate,od.strvalidityduration,
                                                                case when (od.type = 1) then 'Membership' when (od.type = 2) then 'Packages' when (od.type = 3) then 'Course' else '' end as typename
                                                                from tblorderdetail od 
                                                                inner join tblitemmaster im on im.id=od.itemid 
                                                                where od.orderid = :orderid and convert(varchar(50),im.categoryid) like :categoryid and convert(varchar(50),im.subcategoryid) like :subcategoryid and convert(varchar(50),im.id) like :itemid ";
                                                            $odparams = array(
                                                                ':orderid'=>$roword['id'], 
                                                                ':categoryid'=>$categoryid,
                                                                ':subcategoryid'=>$subcategoryid,
                                                                ':itemid'=>$itemid,
                                                            );
                                                            $resod=$DB->getmenual($qryod,$odparams);
                                                            if(sizeof($resod)>0)
                                                            {
                                                            ?>
                                                            <tr> 
                                                                <td colspan="2" class="text-center"><b>Order Detail</b></td>
                                                                <td colspan="7">
                                                                <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="tbl-name text-center" style="width: 5%;">No</th>
                                                                            <th class="tbl-name text-left" style="width: 30%;">Description</th>
                                                                            <th class="tbl-name text-center" style="width: 8%;">Validity</th>
                                                                            <th class="tbl-name text-center" style="width: 8%;">Expiry Date</th>
                                                                            <th class="tbl-name text-right" style="width: 10%;">Price</th>
                                                                            <th class="tbl-name text-right" style="width: 10%;">Coupon Discount</th>
                                                                            <th class="tbl-name text-right" style="width: 10%;">Taxable</th>
                                                                            <th class="tbl-name text-right" style="width: 10%;">VAT</th>
                                                                            <th class="tbl-name text-right" style="width: 10%;">Amount</th>
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
                                                                                    <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowod['itemname']) ?></td>
                                                                                    <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowod['n_expirydate']) ?></td>
                                                                                    <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowod['strvalidityduration']) ?></td>
                                                                                    <td class="tbl-name text-right"><?php echo $IISMethods->sanitize($rowod['price']) ?></td>
                                                                                    <td class="tbl-name text-right"><?php echo round($IISMethods->sanitize($rowod['couponamount']),2) ?></td>
                                                                                    <td class="tbl-name text-right"><?php echo round($IISMethods->sanitize($rowod['taxable']),2) ?></td>
                                                                                    <td class="tbl-name text-right"><?php echo round($IISMethods->sanitize($rowod['igsttaxamt']),2) ?><br>(<?php echo $IISMethods->sanitize($rowod['igst']) ?>%)</td>
                                                                                    <td class="tbl-name text-right"><?php echo round($IISMethods->sanitize($rowod['finalprice']),2) ?></td>

                                                                                </tr>
                                                                            <?php
                                                                                if($withfulldetail == 1)  //Order Item Detail
                                                                                {
                                                                                    
                                                                                    /************************ Start For Composite Items *************************/
                                                                                    $qryodd="select distinct oid.id as oidid,oid.catid,oid.category,oid.subcatid,oid.subcategory,oid.itemid,oid.itemname,oid.qty,oid.usedqty,oid.remainqty,
                                                                                        oid.durationid,oid.durationname,oid.durationdays,oid.discount,oid.price,
                                                                                        tt.taxtype as taxtypename,tt.type as taxtype,tx.sgst,tx.cgst,tx.igst,oid.type,oid.typestr as typename
                                                                                        from tblorderitemdetail oid 
                                                                                        inner join tbltaxtype tt on tt.id=oid.gsttypeid
                                                                                        inner join tbltax tx on tx.id=oid.gstid 
                                                                                        where isnull(oid.iswebsiteattribute,0)=0 and isnull(oid.iscourse,0)=0 and oid.odid = :orderdetid ";
                                                                                    $oddparams = array(
                                                                                        ':orderdetid'=>$rowod['id'], 
                                                                                    );
                                                                                    $resodd=$DB->getmenual($qryodd,$oddparams);
                                                                                    if(sizeof($resodd)>0)
                                                                                    {
                                                                                    ?>
                                                                                    <tr> 
                                                                                        <td colspan="1" class="text-center"><b>Item Detail</b></td>
                                                                                        <td colspan="9">
                                                                                        <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th class="tbl-name text-center" style="width: 2%;">No</th>
                                                                                                    <th class="tbl-name text-left" style="width: 25%;">Item</th>
                                                                                                    <th class="tbl-name text-center" style="width: 6%;">Type</th>
                                                                                                    <th class="tbl-name text-center" style="width: 6%;">Qty</th>
                                                                                                    <th class="tbl-name text-center" style="width: 8%;">Used Qty</th>
                                                                                                    <th class="tbl-name text-center" style="width: 8%;">Remain Qty</th>
                                                                                                    <th class="tbl-name text-right" style="width: 8%;">Discount</th>
                                                                                                    <th class="tbl-name text-center" style="width: 10%;">VAT Type</th>
                                                                                                    <th class="tbl-name text-right" style="width: 8%;">VAT</th>
                                                                                                    <th class="tbl-name text-right" style="width: 10%;">Price</th>
                                                                                                    <th class="tbl-name text-center" style="width: 10%;">Duration</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody id="datalist">
                                                                                                <?php
                                                                                                    for($k=0;$k<sizeof($resodd);$k++)
                                                                                                    {
                                                                                                        $rowodd=$resodd[$k];
                                                                                                    ?>
                                                                                                        <tr>
                                                                                                            <td class="tbl-name text-center"><?php echo $k+1; ?></td>
                                                                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowodd['itemname']) ?></td>
                                                                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowodd['typename']) ?></td>
                                                                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowodd['qty']) ?></td>
                                                                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowodd['usedqty']) ?></td>
                                                                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowodd['remainqty']) ?></td>
                                                                                                            <td class="tbl-name text-right"><?php echo $IISMethods->sanitize($rowodd['discount']) ?></td>
                                                                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowodd['taxtypename']) ?></td>
                                                                                                            <td class="tbl-name text-right"><?php echo $IISMethods->sanitize($rowodd['igst']) ?>%</td>
                                                                                                            <td class="tbl-name text-right"><?php echo round($IISMethods->sanitize($rowodd['price']),2) ?></td>
                                                                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowodd['durationname']) ?></td>

                        
                                                                                                        </tr>
                                                                                                    <?php
                                                                                                    }
                                                                                                ?>
                                                                                                
                                                                                            </tbody>
                                                                                        </table>
                                                                                        </td>
                        
                                                                                    </tr>
                                                                                    <?php
                                                                                    }
                                                                                    /************************ End For Composite Items *************************/




                                                                                    /************************ Start For Website Display Items *************************/
                                                                                    $qryodd = "select distinct tod.id,tod.webdisplayname as name,isnull(tod.attributename,'') as attributename,tod.displayorder
                                                                                        from tblorderitemdetail tod 
                                                                                        left join tblitemiconmaster iim on iim.id=tod.iconid
                                                                                        where tod.iswebsiteattribute=1 and tod.odid=:orderdetid order by tod.displayorder";
                                                                                    $oddparams = array(
                                                                                        ':orderdetid'=>$IISMethods->sanitize($rowod['id']), 
                                                                                    );
                                                                                    $resodd=$DB->getmenual($qryodd,$oddparams);
                                                                                    if(sizeof($resodd)>0)
                                                                                    {
                                                                                    ?>
                                                                                    <tr> 
                                                                                        <td colspan="1" class="text-center"><b>Website Display Attributes</b></td>
                                                                                        <td colspan="9">
                                                                                        <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th class="tbl-name text-center" style="width: 2%;">No</th>
                                                                                                    <th class="tbl-name text-left" style="width: 30%;">Attribute</th>
                                                                                                    <th class="tbl-name text-left" style="width: 60%;">Website Display Name</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody id="datalist">
                                                                                                <?php
                                                                                                    for($k=0;$k<sizeof($resodd);$k++)
                                                                                                    {
                                                                                                        $rowodd=$resodd[$k];
                                                                                                    ?>
                                                                                                        <tr>
                                                                                                            <td class="tbl-name text-center"><?php echo $k+1; ?></td>
                                                                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowodd['attributename']) ?></td>
                                                                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowodd['name']) ?></td>
                                                                                                            
                                                                                                        </tr>
                                                                                                    <?php
                                                                                                    }
                                                                                                ?>
                                                                                                
                                                                                            </tbody>
                                                                                        </table>
                                                                                        </td>
                        
                                                                                    </tr>
                                                                                    <?php
                                                                                    }
                                                                                    /************************ End For Website Display Items *************************/



                                                                                    /************************ Start For Course Benefit *************************/
                                                                                    $qryodd = "select distinct tod.id,tod.webdisplayname as name,tod.durationname,tod.displayorder
                                                                                        from tblorderitemdetail tod 
                                                                                        left join tblitemiconmaster iim on iim.id=tod.iconid
                                                                                        where tod.iscourse=1 and tod.odid=:orderdetid order by tod.displayorder";
                                                                                    $oddparams = array(
                                                                                        ':orderdetid'=>$IISMethods->sanitize($rowod['id']),
                                                                                    );
                                                                                    $resodd=$DB->getmenual($qryodd,$oddparams);
                                                                                    if(sizeof($resodd)>0)
                                                                                    {
                                                                                    ?>
                                                                                    <tr> 
                                                                                        <td colspan="1" class="text-center"><b>Course Benefit</b></td>
                                                                                        <td colspan="9">
                                                                                        <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th class="tbl-name text-center" style="width: 2%;">No</th>
                                                                                                    <th class="tbl-name text-left" style="width: 60%;">Benefit</th>
                                                                                                    <th class="tbl-name text-left" style="width: 30%;">Duration</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody id="datalist">
                                                                                                <?php
                                                                                                    for($k=0;$k<sizeof($resodd);$k++)
                                                                                                    {
                                                                                                        $rowodd=$resodd[$k];
                                                                                                    ?>
                                                                                                        <tr>
                                                                                                            <td class="tbl-name text-center"><?php echo $k+1; ?></td>
                                                                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowodd['name']) ?></td>
                                                                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowodd['durationname']) ?></td>
                                                                                                            
                                                                                                        </tr>
                                                                                                    <?php
                                                                                                    }
                                                                                                ?>
                                                                                                
                                                                                            </tbody>
                                                                                        </table>
                                                                                        </td>
                        
                                                                                    </tr>
                                                                                    <?php
                                                                                    }
                                                                                    /************************ End For Course Benefit *************************/



                                                                                }
                                                                            }
                                                                        ?>
                                                                        
                                                                    </tbody>
                                                                </table>
                                                                </td>

                                                            </tr>
                                                            <?php
                                                            }
                                                        }
    
                                                    }
                                                ?>

                                                <tr>
                                                    <td colspan="5" class="tbl-name text-right" style="background: #c3e3c9;"><b>Total</b></td>
                                                    <td class="tbl-name text-right" style="background: #c3e3c9;"><b><?php echo $IISMethods->sanitize(round($totalamount,2)); ?></b></td>
                                                    <td colspan="3" class="tbl-name text-center" style="background: #c3e3c9;"></td>
                                                </tr> 
                                                
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