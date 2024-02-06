<?php 
require_once dirname(__DIR__, 1).'/config/init.php';

$typeid=$IISMethods->sanitize($_POST['typeid']);
$statusid=$IISMethods->sanitize($_POST['statusid']);

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
                                    
                                    $qrymember="SELECT distinct pm.timestamp,pm.id,pm.personname,pm.contact,pm.email,pm.isnormal,pm.regtype,
                                        pu.userrole,convert(varchar,pm.entry_date,100) as entrydate,
                                        CASE WHEN(pm.isverified = 1)THEN 'Verified' ElSE 'Pending' END as verified,pm.isverified,
                                        CASE WHEN(isnull(pm.qataridproof,'')<>'' and isnull(pm.passportproof,'')<>'') THEN 'Uploaded' ElSE 'Pending' END as strdocverified
                                        from tblpersonmaster pm 
                                        inner join tblpersonutype pu on pu.pid = pm.id
                                        where ISNULL(pm.isdelete,0)=0 and pm.id <> :adminuid AND (pu.utypeid=:memberutypeid or pu.utypeid=:guestutypeid) AND pu.utypeid LIKE :typeid AND isnull(pm.isverified,0) LIKE :statusid ";	
                                    $memberparms = array(
                                        ':memberutypeid'=>$config->getMemberutype(),
                                        ':guestutypeid'=>$config->getGuestutype(),
                                        ':adminuid'=>$config->getAdminUserId(),
                                        ':typeid'=>$typeid,
                                        ':statusid'=>$statusid,
                                    );

                                    


                                    if($fromdate && $todate)
                                    {
                                        //$qrymember.=" AND CONVERT(date,pm.entry_date,103) BETWEEN CONVERT(date,:fromdate,103) and CONVERT(date,:todate,103)";
                                        $qrymember.=" AND pm.entry_date BETWEEN :fromdate and :todate ";
                                       
                                        $memberparms[':fromdate']=$fromdate;	
                                        $memberparms[':todate']=$todate;	
                                 
                                    } 
                                    $qrymember.=" order by pm.timestamp desc";
                                    //echo $qrymember;
                                    $resmember=$DB->getmenual($qrymember,$memberparms);

                    
                                    if(sizeof($resmember)>0)
                                    {
                                        ?>
                                        <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                            <thead>
                                                <tr>
                                                    <th class="tbl-name text-center" style="width: 2%;">No</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">User Type</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Name</th>
                                                    <th class="tbl-name text-left" style="width: 20%;">Email</th>
                                                    <th class="tbl-name text-left" style="width: 15%;">Contact No</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Status</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Document Upload?</th>
                                                    <th class="tbl-name text-center" style="width: 10%;">Entry Date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="datalist">
                                                <?php
                                                    for($i=0;$i<sizeof($resmember);$i++)
                                                    {
                                                        $rowmember=$resmember[$i];
                                                        ?>
                                                        <tr>
                                                            <td class="tbl-name text-center"><?php echo $i+1; ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowmember['userrole']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowmember['personname']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowmember['email']); ?></td>
                                                            <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowmember['contact']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowmember['verified']); ?></td>
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowmember['strdocverified']); ?></td> 
                                                            <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowmember['entrydate']); ?></td>
                                                            
                                                        </tr>
                                                        <?php
    
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
