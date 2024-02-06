<?php 
require_once dirname(__DIR__, 1).'/config/init.php';

$storeid=$IISMethods->sanitize($_POST['storeid']);
$memberid=$IISMethods->sanitize($_POST['memberid']);

$withorderdetail=$IISMethods->sanitize($_POST['withorderdetail']);

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
                                    $qryord="select o.timestamp as primary_date,o.id,o.transactionid,o.orderno,pm.personname,pm.contact,
                                        convert(varchar, o.timestamp,100) AS ofulldate,o.totalpaid,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,s.storename
                                        from tblstoreorder o 
                                        inner join tblpersonmaster pm on pm.id=o.uid 
                                        inner join tblpersonmaster pm1 on pm1.id=o.entry_uid 
                                        inner join tblstoremaster s on s.id=o.storeid 
                                        where convert(varchar(50),o.storeid) like :storeid and convert(varchar(50),o.uid) like :memberid ";
                                    $ordparms = array(
                                        ':storeid'=>$storeid,
                                        ':memberid'=>$memberid,
                                    );

                                    if($fromdate && $todate)
                                    {
                                        //$qryord.=" AND CONVERT(date,o.orderdate,103) BETWEEN CONVERT(date,:fromdate,103) and CONVERT(date,:todate,103)";
                                        $qryord.=" AND o.timestamp BETWEEN :fromdate and :todate ";
                                       
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
                                                    <th class="tbl-name text-center" style="width: 10%;">Store</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Transaction ID</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Order No</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Member</th>
                                                    <!-- <th class="tbl-name text-right" style="width: 10%;">Amount</th> -->
                                                    <th class="tbl-name text-center" style="width: 15%;">Order Date</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Entry By</th>
                                                </tr>
                                            </thead>
                                            <tbody id="datalist">
                                                <?php
                                                    for($i=0;$i<sizeof($resord);$i++)
                                                    {
                                                        $roword=$resord[$i];
                                                        ?>
                                                        <tr>
                                                            <td class="tbl-name text-center"><?php echo $i+1; ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($roword['storename']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($roword['transactionid']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($roword['orderno']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($roword['personname']).' ('.$IISMethods->sanitize($roword['contact']).')'; ?></td>
                                                            <!-- <td class="tbl-name text-right"><?php //echo $IISMethods->sanitize(round($roword['totalpaid'],2)); ?></td> -->
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($roword['ofulldate']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($roword['entrypersonname']).' ('.$IISMethods->sanitize($roword['entrypersoncontact']).')'; ?></td> 
                                                            
                                                        </tr>
                                                        <?php

                                                        if($withorderdetail == 1)  //Order Item Detail
                                                        {

                                                            /************************ Start For Order Item Details *************************/
                                                            $qryod="select sod.id,sod.orderid,sod.type,sod.typename,sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname,sod.qty,
                                                                sod.taxtype,sod.taxtypename,sod.sgst,sod.cgst,sod.igst,sod.price,sod.discountper,sod.discountamt,sod.taxable,sod.sgsttaxamt,sod.cgsttaxamt,sod.igsttaxamt,sod.finalprice
                                                                from tblstoreorderdetail sod 
                                                                where sod.orderid=:orderid";
                                                            $odparams = array(
                                                                ':orderid'=>$roword['id'],
                                                            );
                                                            $resod=$DB->getmenual($qryod,$odparams);
                                                            if(sizeof($resod)>0)
                                                            {
                                                            ?>
                                                            <tr> 
                                                                <td colspan="2" class="text-center"><b>Order Detail</b></td>
                                                                <td colspan="4">
                                                                <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="tbl-name text-center" style="width: 4%;">No</th>
                                                                            <th class="tbl-name text-left" style="width: 30%;">Item</th>
                                                                            <th class="tbl-name text-center" style="width: 8%;">Type</th>
                                                                            <th class="tbl-name text-center" style="width: 8%;">Qty</th>
                                                                            <!-- <th class="tbl-name text-right" style="width: 10%;">Price</th>
                                                                            <th class="tbl-name text-right" style="width: 10%;">Discount</th>
                                                                            <th class="tbl-name text-right" style="width: 10%;">Taxable</th>
                                                                            <th class="tbl-name text-right" style="width: 10%;">VAT</th>
                                                                            <th class="tbl-name text-right" style="width: 10%;">Amount</th> -->
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
                                                                                    <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowod['itemname']) ?><br>(<?php echo $IISMethods->sanitize($rowod['category']) ?>)</td>
                                                                                    <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowod['typename']) ?></td>
                                                                                    <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowod['qty']) ?></td>
                                                                                    <!-- <td class="tbl-name text-right"><?php //cho $IISMethods->sanitize($rowod['price']) ?></td>
                                                                                    <td class="tbl-name text-right"><?php //echo round($IISMethods->sanitize($rowod['discountamt']),2) ?></td>
                                                                                    <td class="tbl-name text-right"><?php //echo round($IISMethods->sanitize($rowod['taxable']),2) ?></td>
                                                                                    <td class="tbl-name text-right"><?php //echo round($IISMethods->sanitize($rowod['igsttaxamt']),2) ?><br>(<?php //echo $IISMethods->sanitize($rowod['igst']) ?>%)</td>
                                                                                    <td class="tbl-name text-right"><?php //echo round($IISMethods->sanitize($rowod['finalprice']),2) ?></td> -->

                                                                                </tr>
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                        
                                                                    </tbody>
                                                                </table>
                                                                </td>
                                                                <td class="text-center"></td>

                                                            </tr>
                                                            <?php
                                                            }
                                                            /************************ End For Order Item Details *************************/



                                                            /************************ Start For Order Payment Details *************************/
                                                            /*
                                                            $qryod="select sopd.id,sopd.orderid,sopd.type,sopd.paytypeid,sopd.paytypename,sopd.amount 
                                                                from tblstoreorderpaymentdetail sopd 
                                                                where sopd.orderid=:orderid";	    
                                                            $odparams = array(
                                                                ':orderid'=>$roword['id'],
                                                            );
                                                            $resod=$DB->getmenual($qryod,$odparams);
                                                            if(sizeof($resod)>0)
                                                            {
                                                            ?>
                                                            <tr> 
                                                                <td colspan="2" class="text-center"><b>Payment Detail</b></td>
                                                                <td colspan="6">
                                                                <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="tbl-name text-center" style="width: 4%;">No</th>
                                                                            <th class="tbl-name text-left" style="width: 80%;">Payment Type</th>
                                                                            <th class="tbl-name text-right" style="width: 16%;">Amount</th>
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
                                                                                    <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowod['paytypename']) ?></td>
                                                                                    <td class="tbl-name text-right"><?php echo round($IISMethods->sanitize($rowod['amount']),2) ?></td>

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
                                                            */
                                                            /************************ End For Order Payment Details *************************/




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