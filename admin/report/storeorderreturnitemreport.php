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
                                    $qryord="select sro.timestamp as primary_date,sro.id,isnull(sro.comment,'') as comment,pm.personname,pm.contact,
                                        convert(varchar, sro.timestamp,100) AS ofulldate,pm1.personname as entrypersonname,pm1.contact as entrypersoncontact,s.storename
                                        from tblstorereturnorder sro 
                                        inner join tblpersonmaster pm on pm.id=sro.memberid 
                                        inner join tblpersonmaster pm1 on pm1.id=sro.entry_uid 
                                        inner join tblstoremaster s on s.id=sro.storeid 
                                        where convert(varchar(50),sro.storeid) like :storeid and convert(varchar(50),sro.memberid) like :memberid  ";    
                                    $ordparms = array(
                                        ':storeid'=>$storeid,
                                        ':memberid'=>$memberid,
                                    );

                                    if($fromdate && $todate)
                                    {
                                        //$qryord.=" AND CONVERT(date,sro.orderdate,103) BETWEEN CONVERT(date,:fromdate,103) and CONVERT(date,:todate,103)";
                                        $qryord.=" AND sro.timestamp BETWEEN :fromdate and :todate";
                                       
                                        $ordparms[':fromdate']=$fromdate;	
                                        $ordparms[':todate']=$todate;	
                                 
                                    } 
                                    $qryord.=" order by sro.timestamp desc";
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
                                                    <th class="tbl-name text-left" style="width: 20%;">Member</th>
                                                    <th class="tbl-name text-center" style="width: 15%;">Return Date</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Return By</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Comment</th>
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
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($roword['storename']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($roword['personname']).' ('.$IISMethods->sanitize($roword['contact']).')'; ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($roword['ofulldate']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($roword['entrypersonname']).' ('.$IISMethods->sanitize($roword['entrypersoncontact']).')'; ?></td> 
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($roword['comment']); ?></td>
                                                        </tr>
                                                        <?php

                                                        if($withorderdetail == 1)  //Return Order Item Detail
                                                        {

                                                            /************************ Start For Return Order Item Details *************************/
                                                            $qryod="select sod.id,sod.sorid,sod.catid,sod.category,sod.subcatid,sod.subcategory,sod.itemid,sod.itemname,sod.qty
                                                                from tblstorereturnorderdetail sod 
                                                                where sod.sorid=:orderid";    
                                                            $odparams = array(
                                                                ':orderid'=>$roword['id'],
                                                            );
                                                            $resod=$DB->getmenual($qryod,$odparams);
                                                            if(sizeof($resod)>0)
                                                            {
                                                            ?>
                                                            <tr> 
                                                                <td colspan="2" class="text-center"><b>Item Detail</b></td>
                                                                <td colspan="3">
                                                                <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="tbl-name text-center" style="width: 4%;">No</th>
                                                                            <th class="tbl-name text-left" style="width: 30%;">Item</th>
                                                                            <th class="tbl-name text-center" style="width: 8%;">Qty</th>
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
                                                                                    <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowod['qty']) ?></td>
                                                                                    
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
                                                            /************************ End For Return Order Item Details *************************/


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