<?php 
require_once dirname(__DIR__, 1).'/config/init.php';

$categoryid=$IISMethods->sanitize($_POST['categoryid']);
$subcategoryid=$IISMethods->sanitize($_POST['subcategoryid']);
$itemid=$IISMethods->sanitize($_POST['itemid']);
$durationid=$IISMethods->sanitize($_POST['durationid']);

$withwebsitedisplayitem=$IISMethods->sanitize($_POST['withwebsitedisplayitem']);
$withcoursebenefit=$IISMethods->sanitize($_POST['withcoursebenefit']);
$withcompositeitem=$IISMethods->sanitize($_POST['withcompositeitem']);

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
                                
                            </div>
                            
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="row min-height-100">
                            <div class="col-12 min-height-100">
                                <div class="table-responsive main-grid">
                                    
                                    <?php
                                    
                                    $qryitem="select distinct im.timestamp as primary_date,im.id,im.categoryid,c.category,im.subcategoryid,sc.subcategory,im.itemname,im.price,im.isactive,im.showonhome,tt.taxtype,t.taxname,
                                    case when((select top 1 CONVERT(VARCHAR(255), itemid) from tblitemimage where CONVERT(VARCHAR(255), itemid)  = im.id) != '') then 'text-warning' else 'text-default' end as imgclass,
                                    isnull((select daytext from tbldurationmaster d where d.id = im.durationid),'') as durationtxt
                                    from tblitemmaster im 
                                    inner join tblcategory c on c.id = im.categoryid
                                    inner join tblsubcategory sc on sc.id = im.subcategoryid 
                                    inner join tbltaxtype tt on tt.id = im.gsttypeid 
                                    inner join tbltax t on t.id = im.gstid
                                    inner join tblitemstore si on si.itemid=im.id 
                                    where convert(varchar(50),im.categoryid) like :categoryid and convert(varchar(50),im.subcategoryid) like :subcategoryid and convert(varchar(50),im.id) like :itemid and convert(varchar(50),im.durationid) like :durationid ";	
                                    $itemparms = array(
                                        ':categoryid'=>$categoryid,
                                        ':subcategoryid'=>$subcategoryid,
                                        ':itemid'=>$itemid,
                                        ':durationid'=>$durationid,
                                    );

                                    $qryitem.=" order by c.category";
                                    $resitem=$DB->getmenual($qryitem,$itemparms);

                    
                                    if(sizeof($resitem)>0)
                                    {
                                        ?>
                                        <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                            <thead>
                                                <tr>
                                                    <th class="tbl-name text-center" style="width: 2%;">No</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Category</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Sub Category</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Name</th>
                                                    <th class="tbl-name text-left" style="width: 15%;">Price</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Tax Type</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Tax</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Duration</th>
                                                </tr>
                                            </thead>
                                            <tbody id="datalist">
                                                <?php
                                                    for($i=0;$i<sizeof($resitem);$i++)
                                                    {
                                                        $rowitem=$resitem[$i];
                                                        ?>
                                                        <tr>
                                                            <td class="tbl-name text-center"><?php echo $i+1; ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowitem['category']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowitem['subcategory']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowitem['itemname']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowitem['price']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowitem['taxtype']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowitem['taxname']); ?></td> 
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowitem['durationtxt']); ?></td>
                                                            
                                                        </tr>
                                                        <?php
                                                        if($withwebsitedisplayitem == 1)  //Website Display Item
                                                        {
                                                            $qryweb="select distinct tid.id,tid.rowwebdisplayname as name,isnull(tid.rowattributename,'') as attributename,tid.rowdisplayorder
                                                                from tblitemdetails tid 
                                                                left join tblitemiconmaster iim on iim.id=tid.rowiconid
                                                                where tid.iswebsiteattribute=1 and tid.itemid=:itemid order by tid.rowdisplayorder";
                                                            $webparams = array(
                                                                ':itemid'=>$rowitem['id'], 
                                                            );
                                                            $resweb=$DB->getmenual($qryweb,$webparams);
                                                            if(sizeof($resweb)>0)
                                                            {
                                                            ?>
                                                            <tr> 
                                                                <td colspan="2" class="text-center"><b>Website Display Item Details</b></td>
                                                                <td colspan="5">
                                                                <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="tbl-name text-center" style="width: 5%;">No</th>
                                                                            <th class="tbl-name text-left" style="width: 30%;">Attribute</th>
                                                                            <th class="tbl-name text-left" style="width: 60%;">Website Display Name</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="datalist">
                                                                        <?php
                                                                            for($ii=0;$ii<sizeof($resweb);$ii++)
                                                                            {
                                                                                $rowweb=$resweb[$ii];
                                                                            ?>
                                                                                <tr>
                                                                                    <td class="tbl-name text-center"><?php echo $ii+1; ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowweb['attributename'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowweb['name'] ?></td>
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
                                                        }


                                                        if($withcoursebenefit == 1)  //Course Benefit
                                                        {
                                                            $qrybenefit="select iid.id,iid.rowwebdisplayname,iid.rowqty,isnull(iim.iconimg,'') as iconimg,iid.rowdisplayorder,iid.rowdurationname
                                                                from tblitemdetails iid 
                                                                left join tblitemiconmaster iim on iim.id=iid.rowiconid
                                                                where iid.itemid=:itemid and iid.iscourse=1 order by iid.rowdisplayorder";    
                                                            $benefitparams = array(
                                                                ':itemid'=>$rowitem['id'], 
                                                            );
                                                            $resbenefit=$DB->getmenual($qrybenefit,$benefitparams);
                                                            if(sizeof($resbenefit)>0)
                                                            {
                                                            ?>
                                                            <tr> 
                                                                <td colspan="2" class="text-center"><b>Course Benefit Details</b></td>
                                                                <td colspan="5">
                                                                <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="tbl-name text-center" style="width: 5%;">No</th>
                                                                            <th class="tbl-name text-left" style="width: 60%;">Benefit Name</th>
                                                                            <th class="tbl-name text-left" style="width: 30%;">Duration</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="datalist">
                                                                        <?php
                                                                            for($ii=0;$ii<sizeof($resbenefit);$ii++)
                                                                            {
                                                                                $rowbenefit=$resbenefit[$ii];
                                                                            ?>
                                                                                <tr>
                                                                                    <td class="tbl-name text-center"><?php echo $ii+1; ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowbenefit['rowwebdisplayname'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowbenefit['rowdurationname'] ?></td>
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
                                                        }


                                                        if($withcompositeitem == 1)  //Composite Item
                                                        {
                                                            $qrycomp="select iid.id,iid.rowcategory,iid.rowsubcategory,iid.rowitemid,iid.rowitemname,iid.rowwebdisplayname,iid.rowqty,isnull(iim.iconimg,'') as iconimg,
                                                                iid.rowdurationname,iid.rowdurationdays,iid.rowdiscount,iid.rowprice,iid.rowgst,iid.rowgsttype,iid.rowtypestr
                                                                from tblitemdetails iid 
                                                                left join tblitemiconmaster iim on iim.id=iid.rowiconid
                                                                where iid.itemid=:itemid and iid.iswebsiteattribute=0 and iid.iscourse = 0 order by iid.rowcategory";    
                                                            $compparams = array(
                                                                ':itemid'=>$rowitem['id'], 
                                                            );
                                                            $rescomp=$DB->getmenual($qrycomp,$compparams);
                                                            if(sizeof($rescomp)>0)
                                                            {
                                                            ?>
                                                            <tr> 
                                                                <td colspan="2" class="text-center"><b>Composite Item Details</b></td>
                                                                <td colspan="5">
                                                                <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="tbl-name text-center" style="width: 2%;">No</th>
                                                                            <th class="tbl-name text-left" style="width: 10%;">Category</th>
                                                                            <th class="tbl-name text-left" style="width: 10%;">Sub Category</th>
                                                                            <th class="tbl-name text-left" style="width: 10%;">Item</th>
                                                                            <th class="tbl-name text-left" style="width: 10%;">Type</th>
                                                                            <th class="tbl-name text-left" style="width: 10%;">Qty</th>
                                                                            <th class="tbl-name text-left" style="width: 10%;">Discount</th>
                                                                            <th class="tbl-name text-left" style="width: 10%;">Duration</th>
                                                                            <th class="tbl-name text-left" style="width: 10%;">Price</th>
                                                                            <th class="tbl-name text-left" style="width: 10%;">Tax Type</th>
                                                                            <th class="tbl-name text-left" style="width: 10%;">Tax</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="datalist">
                                                                        <?php
                                                                            for($ii=0;$ii<sizeof($rescomp);$ii++)
                                                                            {
                                                                                $rowcomp=$rescomp[$ii];
                                                                            ?>
                                                                                <tr>
                                                                                    <td class="tbl-name text-center"><?php echo $ii+1; ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowcomp['rowcategory'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowcomp['rowsubcategory'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowcomp['rowitemname'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowcomp['rowtypestr'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowcomp['rowqty'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowcomp['rowdiscount'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowcomp['rowdurationname'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowcomp['rowprice'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowcomp['rowgsttype'] ?></td>
                                                                                    <td class="tbl-name"><?php echo $rowcomp['rowgst'] ?></td>
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