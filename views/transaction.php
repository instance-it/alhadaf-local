<?php require_once dirname(__DIR__, 1).'\config\config.php';
$config = new config();
// echo $transactionid = $_REQUEST['transactionid'];
// exit;
?>
<!--page header section start-->
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="breadcrumb-list">
                    <li><a href="javascript:void(0)" class="clspageparentmenu notranslate lan-home-menu" pagename="home">Home</a></li>
                    <li class="active">Transaction</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing" id="checkout-section">
    <div class="container" id="transactiondetails">  
        <div class="row">
            <div class="col-12 text-center">
                <div class="transaction-img"><img src="assets/images/transaction-complate.png"/></div>
                <h3 class="mb-2 text-success">Transaction Completed Successfully</h3>
                <p><strong>Thank you.</strong> for Billing.</p>
            </div>
            
            <!-- <div class="col-12 text-center">
                <div class="transaction-img"><img src="assets/images/transaction-failed.png"/></div>
                <h3 class="mb-2 text-danger">Transaction Failed</h3>
                <p><strong>Sorry!</strong> Your transaction has been failed.</p>
            </div> -->
        </div>
        
        <div class="row your-transaction-block text-sm-center">
            <div class="col-12 col-sm-4 col-lg-4 mb-3">
                <h4 class="m-0 mb-2">Transaction ID</h4>
                <p>#123456789012</p>
            </div>
            <div class="col-12 col-sm-4 col-lg-4 mb-3">
                <h4 class="m-0 mb-2">Transaction Date</h4>
                <p>10/23/2021</p>
            </div>
            <div class="col-12 col-sm-4 col-lg-4 mb-3">
                <h4 class="m-0 mb-2">Total Amount</h4>
                <p>Qr xxx.xx</p>
            </div>
        </div>
    
        <div class="row mb-3 px-3">
            <div class="col-12 table-body-content">                        
                <div class="row d-none d-md-flex plan-table-header">
                    <div class="col-12 col-md-4">
                        <h4>Membership</h4>
                    </div>
                    <div class="col-12 col-md-2">
                        <h4>Validity</h4>
                    </div>
                    <div class="col-12 col-md-3">
                        <h4>Transaction Date</h4>
                    </div>
                    <div class="col-12 col-md-3 text-md-right">
                        <h4>Amount</h4>
                    </div>
                </div>
                <div class="row membership-row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-md-4 membership-detail">
                                <h4 class="mob-plan-header d-md-none">Membership</h4>
                                <div class="membership-plan-table-name">
                                    <a href="javascript:void(0);" class="clsviewpackage" data-toggle="modal" data-target="#fullspecifications">Family Package (3)</a>
                                </div>
                            </div>
                            <div class="col-12-xs col-6 col-sm-4 col-md-2 membership-validity-detail">
                                <h4 class="mob-plan-header d-md-none">Validity</h4>
                                <div class="membership-plan-table-validity">
                                    1 Month
                                </div>
                            </div>
                            <div class="col-12-xs col-6 col-sm-4 col-md-3 membership-validity-detail">
                                <h4 class="mob-plan-header d-md-none mt-2">Transaction Date</h4>
                                <div class="membership-plan-table-validity">
                                    10/23/2021
                                </div>
                            </div>
                            <div class="col-12-xs col-6 col-sm-4 col-md-3 membership-price-detail">
                                <h4 class="mob-plan-header d-md-none mt-2">Amount</h4>
                                <div class="membership-plan-table-price text-md-right">
                                    Qr xxx.xx
                                </div>
                            </div>
                        </div>                                
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-md mt-2 my-lg-auto">
                                <p class="text-success m-0">Your Membership Plan Expires On <strong> 11/23/2021</strong>.</p>
                            </div> 
                        </div>  
                    </div>
                </div>
            </div>
        </div>            
    
        <div class="row">
            <div class="col-12 text-center about-transaction-content">
                <p class="m-0">We will contact you shortly. <a href="home.php">Alhadaf Shooting Range</a></p>
            </div>
        </div>
    </div>

</section>


<script>
    $(document).ready(function(){
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.licheckout").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.licheckout").addClass("active");

        //List Transaction History
        listtransactionhistory();
    });

    function listtransactionhistory()
    {
        var txnid = getUrlVars()['tid'];
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};
        formdata = new FormData();
        formdata.append("action", "listtransactionhistory");
        formdata.append("txnid", txnid);
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'checkout',formdata,headersdata,Onsuccesslisttransactionhistory,OnErrorlisttransactionhistory);

    }

    function Onsuccesslisttransactionhistory(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        var htmldata="";
        if(resultdata.status==0)
        {
            alertify(resultdata.message,0);
        }
        else if(resultdata.status==1)
        {
            $('#transactiondetails').html(resultdata.transactiondata)
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 
        
    }
    
    function OnErrorlisttransactionhistory(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }


</script>