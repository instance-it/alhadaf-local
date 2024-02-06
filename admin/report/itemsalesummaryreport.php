<?php 
require_once dirname(__DIR__, 1).'/config/init.php';

$storeid=$IISMethods->sanitize($_POST['storeid']);
$salepersonid=$IISMethods->sanitize($_POST['salepersonid']);
$itemid=$IISMethods->sanitize($_POST['itemid']);

$withfreeitem=$IISMethods->sanitize($_POST['withfreeitem']);

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
                                    $totalsaleqty=0;
                                    $totalreturnqty=0;
                                    $totalsaleamount=0;

                                    $itemparms = array(
                                        ':defaultcatmembershipid'=>$config->getDefaultCatMembershipId(),
                                        ':defaultcatcourseid'=>$config->getDefaultCatCourseId(),
                                        ':defaultcatpackageid'=>$config->getDefaultCatPackageId(),
                                        ':storeid'=>$storeid,
                                        ':itemid'=>$itemid,
                                    );


                                    $salepersoncond1='';
                                    $salepersoncond2='';
                                    $salepersoncond3='';
                                    if($salepersonid != '%')  //When Not All Sale Person
                                    {
                                        $salepersoncond1=" and so.entry_uid like :salepersonid1 ";
                                        $salepersoncond2=" and so.entry_uid like :salepersonid2 ";
                                        $salepersoncond3=" and so.entry_uid like :salepersonid3 ";
                                        $itemparms[':salepersonid1']=$salepersonid;
                                        $itemparms[':salepersonid2']=$salepersonid;
                                        $itemparms[':salepersonid3']=$salepersonid;
                                    }


                                    $datecond1='';
                                    $datecond2='';
                                    $datecond3='';
                                    if($fromdate && $todate)   //When Date Filter
                                    {
                                        // $datecond1=" AND CONVERT(date,so.timestamp,103) BETWEEN CONVERT(date,:fromdate1,103) and CONVERT(date,:todate1,103) ";
                                        // $datecond2=" AND CONVERT(date,so.timestamp,103) BETWEEN CONVERT(date,:fromdate2,103) and CONVERT(date,:todate2,103) ";
                                        // $datecond3=" AND CONVERT(date,so.timestamp,103) BETWEEN CONVERT(date,:fromdate3,103) and CONVERT(date,:todate3,103) ";

                                        $datecond1=" AND so.timestamp BETWEEN :fromdate1 and :todate1 ";
                                        $datecond2=" AND so.timestamp BETWEEN :fromdate2 and :todate2 ";
                                        $datecond3=" AND so.timestamp BETWEEN :fromdate3 and :todate3 ";
                                       
                                        $itemparms[':fromdate1']=$fromdate;	
                                        $itemparms[':todate1']=$todate;	
                                        $itemparms[':fromdate2']=$fromdate;	
                                        $itemparms[':todate2']=$todate;	
                                        $itemparms[':fromdate3']=$fromdate;	
                                        $itemparms[':todate3']=$todate;	
                                    } 

                                    $withoutfreeitemcond='';
                                    if($withfreeitem == 0)  //When Without Free Item
                                    {
                                        $withoutfreeitemcond=' and sod.finalprice > 0 ';
                                    }



                                    $qryitem="select tmp.* from ( 
                                        SELECT distinct im.id,im.itemname,
                                        isnull((select sum(isnull(sod.qty,0)) from tblserviceorder so inner join tblserviceorderdetail sod on sod.orderid=so.id where so.iscancel=0 and sod.iscancel=0 and sod.itemid=im.id  $salepersoncond1  $datecond1  $withoutfreeitemcond),0) as sale_qty,
                                        isnull((select sum(isnull(sod.return_qty,0)) from tblserviceorder so inner join tblserviceorderdetail sod on sod.orderid=so.id where so.iscancel=0 and sod.iscancel=0 and sod.itemid=im.id  $salepersoncond2  $datecond2  $withoutfreeitemcond),0) as return_qty,
                                        isnull((select sum(isnull(sod.finalprice,0)) from tblserviceorder so inner join tblserviceorderdetail sod on sod.orderid=so.id where so.iscancel=0 and sod.iscancel=0 and sod.itemid=im.id  $salepersoncond3  $datecond3  $withoutfreeitemcond),0) as sale_amount   
                                        from tblitemmaster im 
                                        inner join tblitemstore ims on ims.itemid=im.id where im.categoryid not in (:defaultcatmembershipid,:defaultcatcourseid,:defaultcatpackageid) and 
                                        convert(varchar(50),ims.storeid) like :storeid and convert(varchar(50),im.id) like :itemid 
                                    ) tmp order by tmp.itemname";
                                    
                                    $resitem=$DB->getmenual($qryitem,$itemparms);
                    
                                    if(sizeof($resitem)>0)
                                    {
                                        ?>
                                        <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                            <thead>
                                                <tr>
                                                    <th class="tbl-name text-center" style="width: 5%;">No</th>
                                                    <th class="tbl-name text-center" style="width: 45%;">Item Name</th>
                                                    <th class="tbl-name text-center" style="width: 15%;">Sale Qty</th>
                                                    <th class="tbl-name text-center" style="width: 15%;">Return Qty</th>
                                                    <th class="tbl-name text-right" style="width: 20%;">Sale Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="datalist">
                                                <?php
                                                    for($i=0;$i<sizeof($resitem);$i++)
                                                    {
                                                        $rowitem=$resitem[$i];

                                                        $totalsaleqty+=round($rowitem['sale_qty']);
                                                        $totalreturnqty+=round($rowitem['return_qty']);
                                                        $totalsaleamount+=round($rowitem['sale_amount'],2);
                                                        ?>
                                                        <tr>
                                                            <td class="tbl-name text-center"><?php echo $i+1; ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowitem['itemname']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize(round($rowitem['sale_qty'])) ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize(round($rowitem['return_qty'])) ?></td>
                                                            <td class="tbl-name text-right"><?php echo $IISMethods->sanitize(round($rowitem['sale_amount'],2)) ?></td>
                                                        </tr>
                                                        <?php
    
                                                    }
                                                ?>

                                                <tr>
                                                    <td colspan="2" class="tbl-name text-right" style="background: #c3e3c9;"><b>Total</b></td>
                                                    <td class="tbl-name text-center" style="background: #c3e3c9;"><b><?php echo $IISMethods->sanitize(round($totalsaleqty,2)); ?></b></td>
                                                    <td class="tbl-name text-center" style="background: #c3e3c9;"><b><?php echo $IISMethods->sanitize(round($totalreturnqty,2)); ?></b></td>
                                                    <td class="tbl-name text-right" style="background: #c3e3c9;"><b><?php echo $IISMethods->sanitize(round($totalsaleamount,2)); ?></b></td>
                                                    
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