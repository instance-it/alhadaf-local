<?php 
require_once dirname(__DIR__, 1).'/config/init.php';

$typeid=$IISMethods->sanitize($_POST['typeid']);     //1-Guest, 2-Member
$memberid=$IISMethods->sanitize($_POST['memberid']);

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

                                    if($typeid == 1)  //For guest User
                                    {
                                        $qryrb="select rb.*,convert(varchar, rb.entry_date,100) as entrydate,isnull(st.type,'') as servicetype
                                            from tblrangebooking rb 
                                            left join tblservicetypemaster st on st.id=rb.servicetypeid 
                                            where rb.type=1 ";    
                                        $rbparms = array(
                                            
                                        );

                                        if($fromdate && $todate)
                                        {
                                            $qryrb.=" AND CONVERT(date,rb.date,103) BETWEEN CONVERT(date,:fromdate,103) and CONVERT(date,:todate,103)";
                                        
                                            $rbparms[':fromdate']=$fromdate;	
                                            $rbparms[':todate']=$todate;	
                                    
                                        } 
                                        $qryrb.=" order by convert(date,rb.date,103),rb.timestamp";
                                        //echo $qryrb;
                                        $resrb=$DB->getmenual($qryrb,$rbparms);

                        
                                        if(sizeof($resrb)>0)
                                        {
                                            ?>
                                            <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                <thead>
                                                    <tr>
                                                        <th class="tbl-name text-center" style="width: 2%;">No</th>
                                                        <!-- <th class="tbl-name text-center" style="width: 7%;">Date</th> -->
                                                        <th class="tbl-name text-left" style="width: 8%;">Name</th>
                                                        <th class="tbl-name text-left" style="width: 8%;">Email</th>
                                                        <th class="tbl-name text-left" style="width: 6%;">Mobile No</th>
                                                        <th class="tbl-name text-center" style="width: 5%;">Date Of Birth</th>
                                                        <th class="tbl-name text-center" style="width: 8%;">Qatar ID No</th>
                                                        <th class="tbl-name text-center" style="width: 5%;">Qatar ID Expiry</th>
                                                        <th class="tbl-name text-center" style="width: 8%;">Passport ID No</th>
                                                        <th class="tbl-name text-center" style="width: 5%;">Passport ID Expiry</th>
                                                        <th class="tbl-name text-left" style="width: 6%;">Nationality</th>
                                                        <th class="tbl-name text-left" style="width: 8%;">Company Name</th>
                                                        <th class="tbl-name text-left" style="width: 10%;">Address</th>
                                                        <th class="tbl-name text-left" style="width: 8%;">Service Type</th>
                                                        <th class="tbl-name text-center" style="width: 10%;">Entry Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="datalist">
                                                    <?php
                                                        for($i=0;$i<sizeof($resrb);$i++)
                                                        {
                                                            $rowrb=$resrb[$i];
                                                            ?>
                                                            <tr>
                                                                <td class="tbl-name text-center"><?php echo $i+1; ?></td>
                                                                <!-- <td class="tbl-name text-center"><?php //echo $IISMethods->sanitize($rowrb['date']); ?></td> -->
                                                                <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowrb['firstname']).' '.$IISMethods->sanitize($rowrb['lastname']); ?></td>
                                                                <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowrb['email']); ?></td>
                                                                <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowrb['contact']); ?></td>
                                                                <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowrb['dob']); ?></td>
                                                                <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowrb['qataridno']); ?></td> 
                                                                <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowrb['qataridexpiry']); ?></td>
                                                                <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowrb['passportidno']); ?></td>
                                                                <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowrb['passportidexpiry']); ?></td>
                                                                <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowrb['nationality']); ?></td>
                                                                <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowrb['companyname']); ?></td>
                                                                <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowrb['address']); ?></td>
                                                                <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowrb['servicetype']); ?></td>
                                                                <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowrb['entrydate']); ?></td>

                                                                
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


                                    } 
                                    else if($typeid == 2)  //For Member
                                    {
                                        $qryrb="select rb.id,pm.personname as membername,pm.contact as membercontact,rb.date,rb.fromtime,rb.totime,convert(varchar, rb.entry_date,100) as entrydate
                                            from tblrangebooking rb 
                                            inner join tblpersonmaster pm on pm.id=rb.uid
                                            where rb.type=2 and convert(varchar(50),rb.uid) like :memberid ";    
                                        $rbparms = array(
                                            ':memberid'=>$memberid,
                                        );

                                        if($fromdate && $todate)
                                        {
                                            $qryrb.=" AND CONVERT(date,rb.date,103) BETWEEN CONVERT(date,:fromdate,103) and CONVERT(date,:todate,103)";
                                        
                                            $rbparms[':fromdate']=$fromdate;	
                                            $rbparms[':todate']=$todate;	
                                    
                                        } 
                                        $qryrb.=" order by convert(date,rb.date,103)";
                                        $resrb=$DB->getmenual($qryrb,$rbparms);

                        
                                        if(sizeof($resrb)>0)
                                        {
                                            ?>
                                            <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered11 table-hover datalisttable">
                                                <thead>
                                                    <tr>
                                                        <th class="tbl-name text-center" style="width: 2%;">No</th>
                                                        <th class="tbl-name text-center" style="width: 10%;">Date</th>
                                                        <th class="tbl-name text-center" style="width: 20%;">Time</th>
                                                        <th class="tbl-name text-left" style="width: 40%;">Member</th>
                                                        <th class="tbl-name text-center" style="width: 20%;">Entry Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="datalist">
                                                    <?php
                                                        for($i=0;$i<sizeof($resrb);$i++)
                                                        {
                                                            $rowrb=$resrb[$i];
                                                            ?>
                                                            <tr>
                                                                <td class="tbl-name text-center"><?php echo $i+1; ?></td>
                                                                <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowrb['date']); ?></td>
                                                                <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowrb['fromtime']).' To '.$IISMethods->sanitize($rowrb['totime']); ?></td>
                                                                <td class="tbl-name text-left"><?php echo $IISMethods->sanitize($rowrb['membername']).' ('.$IISMethods->sanitize($rowrb['membercontact']).')'; ?></td>
                                                                <td class="tbl-name text-center"><?php echo $IISMethods->sanitize($rowrb['entrydate']); ?></td>
                                                                
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