<?php require_once dirname(__DIR__, 1).'\config\config.php';
require_once dirname(__DIR__, 1).'\config\init.php';
$config = new config();

?>
<!--page header section start-->
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="breadcrumb-list">
                    <li><a href="javascript:void(0)" class="clspageparentmenu notranslate lan-home-menu" pagename="home">Home</a></li>
                    <li class="active">Cart</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing" id="cart-section">
    <div class="container">
        <div class="row" id="cartitem">
            <div class="col-12 col-lg-8 mb-4 mb-lg-0">
                <div class="row cart-tbl">
                    <div class="col-md-12 heading-order-profile d-none d-md-block">
                        <div class="row mx-n1">
                            <div class="col-md-4 px-1">
                                <h5 class="font-weight-bold">Membership Details</h5>
                            </div>
                            <div class="col-md-2 px-1">
                                <h5 class="font-weight-bold">Validity</h5>
                            </div>
                            <div class="col-md-3 px-1">
                                <h5 class="font-weight-bold">Amount</h5>
                            </div>
                            <div class="col-md-3 px-1">
                                <h5 class="font-weight-bold">Payable Amount</h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12 list-order-profile py-3">
                        <div class="row mx-n1">
                            <div class="col-md-4 col-sm-10 col-8 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Membership Details: </span> Elite</h5>
                            </div>
                            <div class="col-md-2 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Validity: </span>1 Month</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Amount: </span>Qr xxxx.xx</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Payable Amount: </span>Qr xxxx.xx</h5>
                            </div>
                            <div class="col-md-1 col-6 my-auto list-order-close">
                                <h5><a href="#" data-toggle="tooltip" data-placement="bottom" title="Remove Item"><span class="close fal fa-times"></span></a></h5>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="row content-sticky">
                    <div class="col-12 ml-auto col-md-6 col-lg-12">
                        <div class="cart-totals">
                            <div class="heading-order-profile">
                                <h5 class="mb-0">Cart Totals</h5>
                            </div>
                            <div class="cart-totals-inner">
                                <div class="cart-coupon couponscode-content-apply">
                                    <h4 class="m-0"><a class="text-left d-flex m-0" href="#collapsecoupon" data-toggle="collapse">Coupon <i class="fal fa-gift ml-auto"></i></a></h4>
                                    <div class="p-0 collapse" id="collapsecoupon">
                                        <div class="cart-coupon-body">
                                            <div class="row">
                                                <div class="col-12 pt-2">
                                                    <p class="mb-2">Enter your coupon code if you have one.</p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group validate-input">
                                                        <input class="form-control" type="text" name="couponcode" id="couponcode">
                                                        <span class="focus-form-control"></span>
                                                        <label class="label-form-control">Coupon Code</label>
                                                    </div>
                                                    <a href="javascript:void(0);" id="btnapplycoupon" class="btn btn-brand-05 btn-sm btn-cc-apply">Apply</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cart-subtotal">
                                    <ul>
                                        <li>
                                            <p>Subtotal</p>
                                            <p class="cart-amount">Qr xxx.xx</p>
                                        </li>
                                        <li>
                                            <p>Tax Amount</p>
                                            <p class="cart-amount">Qr xxx.xx</p>
                                        </li>
                                        <li class="couponscode-content d-none">
                                            <div class="couponscode-block">    
                                                <i class="couponscode-icon far fa-check"></i>
                                                <div class="couponscode-code"><b>alhadafsr10</b> applied</div>
                                                <span class="couponscode-amount">- Qr xxx.xx (10% off)</span>
                                            </div>
                                            <a class="couponscode-remove" href="javascript:void(0);" data-toggle="tooltip" title="Remove" ><i class="icon-trash-empty"></i></a>
                                        </li>
                                        <li class="cart-total-price">
                                            <p>Total</p>
                                            <p class="cart-amount">Qr xxx.xx</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="checkout-btn">
                                    <a class="btn btn-brand-01 w-100" href="checkout.php">Proceed to Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script>
    $(document).ready(function(){
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.licart").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.licart").addClass("active");

        // List Cart Items
        listcartitems();
    });

    /************************* Start For List Cart Items ******************************/
    function listcartitems()
    {
        formdata = new FormData();
        formdata.append("action", "listcartitems");
        var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false,responsetype: 'HTML'};
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'addtocart',formdata,headersdata,Onsuccesslistcartitems,OnErrorlistcartitems)

        function Onsuccesslistcartitems(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            
            if(resultdata.status==0)
            {

            }
            else if(resultdata.status==1)
            {
                $('#cart-section #cartitem').html(resultdata.cartdata);
                countcartitem();



                //Start Apply Coupon Cart Item
                $('#btnapplycoupon').on('click',function(){

                    var couponcode = $('#couponcode').val();
                    if(couponcode)
                    {
                        var pagename=getpagename();
                        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'JSON'};

                        formdata = new FormData();
                        formdata.append("action", "applycoupon");
                        formdata.append("couponcode",couponcode);
                        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'coupon',formdata,headersdata,OnsuccessApplycoupondata,OnErrorApplycoupondata);

                        function OnsuccessApplycoupondata(content)
                        {
                            var JsonData = JSON.stringify(content);
                            var resultdata = jQuery.parseJSON(JsonData);

                            if(resultdata.status==0)
                            {
                                toastr.error(resultdata.message);
                            }
                            else if(resultdata.status==1)
                            {
                                toastr.success(resultdata.message);
                                // $('.coupon-error').html(''); 
                                $(".couponscode-content").removeClass("d-none");
                                $(".couponscode-content-apply").addClass("d-none");  
                                listcartitems();

                            }
                            
                        }
                        
                        function OnErrorApplycoupondata(content)
                        { 
                            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
                        } 

                    }
                    else
                    {
                        toastr.error(errormsgarr['couponcode']);
                    }

                });
                //End Apply Coupon Cart Item


                //Start Remove Cart Item
                $('.removecartitem').on('click',function(){
                    var itemid = $(this).attr('data-itemid');

                    formdata = new FormData();
                    formdata.append("action", "removecartitem");
                    formdata.append("itemid", itemid);
                    var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false,responsetype: 'JSON'};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'addtocart',formdata,headersdata,Onsuccessremovecartitem,OnErrorremovecartitem)

                    function Onsuccessremovecartitem(content)
                    {
                        var JsonData = JSON.stringify(content);
                        var resultdata = jQuery.parseJSON(JsonData);
                        
                        if(resultdata.status==0)
                        {

                        }
                        else if(resultdata.status==1)
                        {
                            listcartitems();

                        }
                    }

                    function OnErrorremovecartitem(content)
                    { 
                        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
                    } 
                
                });
                //End Remove Cart Item

                

                //Start Remove Coupon Cart Item
                $('.couponscode-remove').on('click',function(){

                    var pagename=getpagename();
                    var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'JSON'};
                    formdata = new FormData();
                    formdata.append("action", "couponscoderemove");
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'coupon',formdata,headersdata,OnsuccessApplycouponremovedata,OnErrorApplycouponremovedata); 

                    function OnsuccessApplycouponremovedata(content)
                    {
                        var JsonData = JSON.stringify(content);
                        var resultdata = jQuery.parseJSON(JsonData);

                        var htmldata="";
                        if(resultdata.status==0)
                        {
                            toastr.error(resultdata.message);
                        }
                        else if(resultdata.status==1)
                        {
                            toastr.success(resultdata.message);
                            listcartitems();

                        }
                        
                    }

                    
                    function OnErrorApplycouponremovedata(content)
                    { 
                        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
                    } 
                    
                });
                //End Remove Coupon Cart Item



                $('#btncheckout').on('click',function(){

                    var isuserlogin = 0;
                    if('<?php echo $LoginInfo->getUid() ?>' != '' && '<?php echo $LoginInfo->getIsguestuser() ?>' == 0 && '<?php echo $LoginInfo->getUtypeid()?>' == '<?php echo $config->getMemberutype() ?>')
                    {
                        isuserlogin = 1;
                    }

                    if(isuserlogin == 0)
                    {
                        $('#registerModal #registrationForm #ro_ischeckout').val(1);
                        $('#loginModal #loginForm #lo_ischeckout').val(1);

                        $('#loginModal').modal('show');
                    }
                    else
                    {
                        var pagename=getpagename();
                        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'JSON'};

                        formdata = new FormData();
                        formdata.append("action", "insertorderdata");
                        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'checkout',formdata,headersdata,Onsuccessinsertorderdata,OnErrorinsertorderdata);

                        function Onsuccessinsertorderdata(content)
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
                                alertify(resultdata.message,1);
                                window.location='<?php echo $config->getWeburl(); ?>transaction?tid='+resultdata.transactionid+'';
                            }
                            
                        }
                        
                        function OnErrorinsertorderdata(content)
                        { 
                            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
                        }
                    }
                });
                
            }
            else if(resultdata.status=-1)
            {
                logoutwebsitepage();
            } 
        }

        function OnErrorlistcartitems(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        } 
    }

    
    
    /************************* End For List Cart Items ******************************/
</script>