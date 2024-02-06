
<?php require_once dirname(__DIR__, 1).'\config\init.php';

$DashPagerights_total_verifiedmember = $IISMethods->getdashpageaccess($LoginInfo->getdashUserrights(),'total_verifiedmember');
$DashPagerights_total_pendingmember = $IISMethods->getdashpageaccess($LoginInfo->getdashUserrights(),'total_pendingmember');
$DashPagerights_total_guest = $IISMethods->getdashpageaccess($LoginInfo->getdashUserrights(),'total_guest');
$DashPagerights_total_orders = $IISMethods->getdashpageaccess($LoginInfo->getdashUserrights(),'total_orders');
$DashPagerights_total_serviceorders = $IISMethods->getdashpageaccess($LoginInfo->getdashUserrights(),'total_serviceorders');

$DashPagerights_recent_member = $IISMethods->getdashpageaccess($LoginInfo->getdashUserrights(),'recent_member');
$DashPagerights_recent_order = $IISMethods->getdashpageaccess($LoginInfo->getdashUserrights(),'recent_order');
$DashPagerights_recent_serviceorder = $IISMethods->getdashpageaccess($LoginInfo->getdashUserrights(),'recent_serviceorder');

?>
<div class="layout-px-spacing">
    <div class="row mt-4">


        <div class="col-12">
            <div class="row mr-0">


                <?php
                if((sizeof($DashPagerights_total_verifiedmember)>0 ? $DashPagerights_total_verifiedmember->getViewright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                {
                ?>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing pr-0 order-md-1 order-lg-1 order-xl-1">
                    <div class="widget widget-card-four min-height-100">
                        <div class="widget-content" id="walletbalance">
                            <div class="row">
                                <div class="w-info col">
                                    <h6 class="value">Total Verified Members</h6>
                                </div>
                            </div>

                            <div class="account-box">
                                <div class="w-content">
                                    <div class="w-info">
                                        <p class="value cnt_totalverifiedmember">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>


                <?php
                if(1==2 && ((sizeof($DashPagerights_total_pendingmember)>0 ? $DashPagerights_total_pendingmember->getViewright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1)) //rights check admin and right person
                {
                ?>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing pr-0 order-md-1 order-lg-1 order-xl-1">
                    <div class="widget widget-card-four min-height-100">
                        <div class="widget-content" id="walletbalance">
                            <div class="row">
                                <div class="w-info col">
                                    <h6 class="value">Pending Members</h6>
                                </div>
                            </div>

                            <div class="account-box">
                                <div class="w-content">
                                    <div class="w-info">
                                        <p class="value cnt_totalpendingmember">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>


                <?php
                if((sizeof($DashPagerights_total_guest)>0 ? $DashPagerights_total_guest->getViewright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                {
                ?>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing pr-0 order-md-1 order-lg-1 order-xl-1">
                    <div class="widget widget-card-four min-height-100">
                        <div class="widget-content" id="walletbalance">
                            <div class="row">
                                <div class="w-info col">
                                    <h6 class="value">Total Guest</h6>
                                </div>
                            </div>

                            <div class="account-box">
                                <div class="w-content">
                                    <div class="w-info">
                                        <p class="value cnt_totalguest">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>


                <?php
                if((sizeof($DashPagerights_total_orders)>0 ? $DashPagerights_total_orders->getViewright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                {
                ?>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing pr-0 order-md-1 order-lg-1 order-xl-1">
                    <div class="widget widget-card-four min-height-100">
                        <div class="widget-content" id="walletbalance">
                            <div class="row">
                                <div class="w-info col">
                                    <h6 class="value">Total Orders</h6>
                                </div>
                            </div>

                            <div class="account-box">
                                <div class="w-content">
                                    <div class="w-info">
                                        <p class="value cnt_totalorders">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>


                <?php
                if((sizeof($DashPagerights_total_serviceorders)>0 ? $DashPagerights_total_serviceorders->getViewright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                {
                ?>
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing pr-0 order-md-1 order-lg-1 order-xl-1">
                    <div class="widget widget-card-four min-height-100">
                        <div class="widget-content" id="walletbalance">
                            <div class="row">
                                <div class="w-info col">
                                    <h6 class="value">Total Service Orders</h6>
                                </div>
                            </div>

                            <div class="account-box">
                                <div class="w-content">
                                    <div class="w-info">
                                        <p class="value cnt_totalserviceorders">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>

                
            </div>
        </div>
       


        <!------------------------- Start For Recent Members Section ---------------------------->
        <?php
        if((sizeof($DashPagerights_recent_member)>0 ? $DashPagerights_recent_member->getViewright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
        {
        ?>
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing" id="recentmemberdataDiv">
            <div class="widget widget-table-two">

                <div class="widget-heading">
                    <div class="row">
                        <div class="col">
                            <h5 class="">Recent Members</h5>
                        </div>

                        <div class="task-action col-auto ml-auto">
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="pendingTask" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="pendingTask" style="will-change: transform;">
                                    <a class="dropdown-item clspageredirect" href="javascript:void(0)" pagename="membermaster">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped border-primary mb-2">
                            <thead>
                                <tr>
                                <th><div class="th-content">Name</div></th>
                                    <th><div class="th-content">Type</div></th>
                                    <th><div class="th-content">Email</div></th>
                                    <th><div class="th-content">Contact No</div></th>
                                    <th><div class="th-content">Entry Date</div></th>
                                    <th><div class="th-content">Status</div></th>
                                </tr>
                            </thead>
                            <tbody id="tblrecentmemberdata">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        <!------------------------- End For Recent Members Section ---------------------------->



        <!------------------------- Start For Recent Orders Section ---------------------------->
        <?php
        if((sizeof($DashPagerights_recent_order)>0 ? $DashPagerights_recent_order->getViewright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
        {
        ?>
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing" id="recentorderdataDiv">
            <div class="widget widget-table-two">

                <div class="widget-heading">
                    <div class="row">
                        <div class="col">
                            <h5 class="">Recent Invoice</h5>
                        </div>

                        <div class="task-action col-auto ml-auto">
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="pendingTask" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="pendingTask" style="will-change: transform;">
                                    <a class="dropdown-item clspageredirect" href="javascript:void(0)" pagename="ordermaster">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped border-primary mb-2">
                            <thead>
                                <tr>
                                    <th><div class="th-content">Member</div></th>
                                    <th><div class="th-content">Invoice No</div></th>
                                    <th><div class="th-content">Amount</div></th>
                                    <th><div class="th-content">Invoice Date</div></th>
                                </tr>
                            </thead>
                            <tbody id="tblrecentorderdata">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        <!------------------------- End For Recent Orders Section ---------------------------->



        <!------------------------- Start For Recent Service Orders Section ---------------------------->
        <?php
        if((sizeof($DashPagerights_recent_serviceorder)>0 ? $DashPagerights_recent_serviceorder->getViewright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
        {
        ?>
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing" id="recentserviceorderdataDiv">
            <div class="widget widget-table-two">

                <div class="widget-heading">
                    <div class="row">
                        <div class="col">
                            <h5 class="">Recent Service Orders</h5>
                        </div>

                        <div class="task-action col-auto ml-auto">
                            <div class="dropdown">
                                <a class="dropdown-toggle" href="#" role="button" id="pendingTask" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="pendingTask" style="will-change: transform;">
                                    <a class="dropdown-item clspageredirect" href="javascript:void(0)" pagename="posserviceorder">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped border-primary mb-2">
                            <thead>
                                <tr>
                                    <th><div class="th-content th-heading">Member</div></th>
                                    <th><div class="th-content">Store</div></th>
                                    <th><div class="th-content th-heading">Order No</div></th>
                                    <th><div class="th-content">Amount</div></th>
                                    <th><div class="th-content">Order Date</div></th>
                                </tr>
                            </thead>
                            <tbody id="tblrecentserviceorderdata">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
        <!------------------------- End For Recent Service Orders Section ---------------------------->



        
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.selectpicker').selectpicker('refresh');
        $('[data-toggle="tooltip"]').tooltip();


        //Fill Statistics Data
        fillstatisticsdata();


        //List Recent Member Data
        listrecentmemberdata();


        //List Recent Order Data
        listrecentorderdata();


        //List Recent Service Order Data
        listrecentserviceorderdata();
    });


    //Click Event (Render Page)
    $('.clspageredirect').on('click',function(){
        var pagename = $(this).attr('pagename');

        render(pagename);
        window.history.pushState(pagename, 'Title', dirpath + pagename);
    });




    /*************************** Start For Top Statistics Section *******************************/
    //Start Fill Statistics Data
    function fillstatisticsdata()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false};

        formdata = new FormData();
        formdata.append("action", "fillstatisticsdata");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillstatisticsdata,OnErrorfillstatisticsdata); 
    }

    function Onsuccessfillstatisticsdata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {
            $('.cnt_totalverifiedmember').text(resultdata.cnt_totalverifiedmember);
            $('.cnt_totalpendingmember').text(resultdata.cnt_totalpendingmember);
            $('.cnt_totalguest').text(resultdata.cnt_totalguest);
            $('.cnt_totalorders').text(resultdata.cnt_totalorders);
            $('.cnt_totalserviceorders').text(resultdata.cnt_totalserviceorders);
            
        }
    }

    function OnErrorfillstatisticsdata(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /*************************** End For Top Statistics Section *******************************/



    /*************************** Start For Recent Member Section *******************************/
    //start List Recent Member Data
    function listrecentmemberdata()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'JSON'};
        formdata = new FormData();
        formdata.append("action", "listrecentmemberdata");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistrecentmemberdata,OnErrorlistrecentmemberdata); 
    }

    function Onsuccesslistrecentmemberdata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);


        var htmldata="";
        if(resultdata.status==0)
        {
            htmldata+='<tr>';
                htmldata+='<td colspan="6" class="text-center tbl-data-found">';
                    htmldata+='<div class="nodata-content text-center">';
                        htmldata+='<p class="nodata-text mb-4 mt-1">'+resultdata.message+'</p>';
                    htmldata+='</div>';
                htmldata+='</td>';
            htmldata+='</tr>';
        }
        else if(resultdata.status==1)
        {
            for(var i in resultdata.data)
            {
                htmldata+='<tr>';
                htmldata+='<td><div class="td-content customer-name"><img src="'+resultdata.data[i].memberimg+'" alt="">'+resultdata.data[i].personname+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+resultdata.data[i].userrole+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+resultdata.data[i].email+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+resultdata.data[i].contact+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+resultdata.data[i].entrydate+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">';
                if(resultdata.data[i].isverified == 1)
                {
                    htmldata+='<span class="badge outline-badge-success">'+resultdata.data[i].memberstatus+'</span>';
                }
                else
                {
                    htmldata+='<span class="badge outline-badge-danger">'+resultdata.data[i].memberstatus+'</span>';
                }
                htmldata+='</div></td>';
                htmldata+='</tr>';
            }
        }
        $('#tblrecentmemberdata').html(htmldata);
    }

    function OnErrorlistrecentmemberdata(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /*************************** End For Recent Member Section *******************************/



    /*************************** Start For Recent Orders Section *******************************/
    //start List Recent Order Data
    function listrecentorderdata()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'JSON'};
        formdata = new FormData();
        formdata.append("action", "listrecentorderdata");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistrecentorderdata,OnErrorlistrecentorderdata); 
    }

    function Onsuccesslistrecentorderdata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);


        var htmldata="";
        if(resultdata.status==0)
        {
            htmldata+='<tr>';
                htmldata+='<td colspan="4" class="text-center tbl-data-found">';
                    htmldata+='<div class="nodata-content text-center">';
                        htmldata+='<p class="nodata-text mb-4 mt-1">'+resultdata.message+'</p>';
                    htmldata+='</div>';
                htmldata+='</td>';
            htmldata+='</tr>';
        }
        else if(resultdata.status==1)
        {

            for(var i in resultdata.data)
            {
                var totalpaid1 = parseFloat(resultdata.data[i].totalpaid);
                var totalpaid = totalpaid1.toFixed(2);
                htmldata+='<tr>';
                //htmldata+='<td><div class="td-content customer-name"><img src="'+resultdata.data[i].memberimg+'" alt="">'+resultdata.data[i].personname+' <br>('+resultdata.data[i].contact+')</div></td>';
                htmldata+='<td><div class="td-content customer-name"><img src="'+resultdata.data[i].memberimg+'" alt="">'+resultdata.data[i].personname+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+resultdata.data[i].orderno+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+totalpaid+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+resultdata.data[i].ofulldate+'</div></td>';
                htmldata+='</tr>';
            }
        }
        $('#tblrecentorderdata').html(htmldata);
    }

    function OnErrorlistrecentorderdata(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /*************************** End For Recent Orders Section *******************************/



    /*************************** Start For Recent Service Orders Section *******************************/
    //start List Recent Service Order Data
    function listrecentserviceorderdata()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'JSON'};
        formdata = new FormData();
        formdata.append("action", "listrecentserviceorderdata");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistrecentserviceorderdata,OnErrorlistrecentserviceorderdata); 
    }

    function Onsuccesslistrecentserviceorderdata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);


        var htmldata="";
        if(resultdata.status==0)
        {
            htmldata+='<tr>';
                htmldata+='<td colspan="5" class="text-center tbl-data-found">';
                    htmldata+='<div class="nodata-content text-center">';
                        htmldata+='<p class="nodata-text mb-4 mt-1">'+resultdata.message+'</p>';
                    htmldata+='</div>';
                htmldata+='</td>';
            htmldata+='</tr>';
        }
        else if(resultdata.status==1)
        {

            for(var i in resultdata.data)
            {
                var totalpaid1 = parseFloat(resultdata.data[i].totalpaid);
                var totalpaid = totalpaid1.toFixed(2);
                htmldata+='<tr>';
                htmldata+='<td><div class="td-content customer-name"><img src="'+resultdata.data[i].memberimg+'" alt="">'+resultdata.data[i].personname+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+resultdata.data[i].storename+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+resultdata.data[i].orderno+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+totalpaid+'</div></td>';
                htmldata+='<td><div class="td-content customer-name">'+resultdata.data[i].ofulldate+'</div></td>';
                htmldata+='</tr>';
            }
        }
        $('#tblrecentserviceorderdata').html(htmldata);
    }

    function OnErrorlistrecentserviceorderdata(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /*************************** End For Recent Service Orders Section *******************************/



</script>    
