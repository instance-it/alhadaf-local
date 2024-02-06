<?php require_once dirname(__DIR__, 1).'\config\config.php';
$config = new config();
?>
<!--page header section start-->
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="breadcrumb-list">
                    <li><a href="home.php" class="notranslate lan-home-menu">Home</a></li>
                    <li class="active">Checkout</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing" id="checkout-section">
    <div class="container">
        <div class="row ">
            <div class="col-12 col-lg-8 mb-4 mb-lg-0">
                <div class="row cart-tbl mb-4">
                    <div class="col-md-12 heading-order-profile">
                        <h5 class="font-weight-bold">Account Information <a href="#" class="collapsed" data-toggle="collapse" data-target="#collapseLogin" aria-expanded="false">Click here to login</a></h5>
                    </div>
                    <div class="col-12 collapse" id="collapseLogin">
                        <form method="post" class="row">
                            <div class="col-12 p-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row mr-0">
                                            <div class="col-12 col-lg-6 pr-0">
                                                <div class="form-group validate-input">
                                                    <input class="form-control" type="text" required="required">
                                                    <span class="focus-form-control"></span>
                                                    <label class="label-form-control" for="inputEmailMobileNumber">Email /
                                                        Mobile Number <span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6 pr-0">
                                                <div class="form-group validate-input">
                                                    <input class="form-control" type="Password" required="required">
                                                    <span class="focus-form-control"></span>
                                                    <label class="label-form-control" for="inputPassword">Password <span
                                                            class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group form-checkbox m-0 text-left sm-checkbox">
                                                    <div class="input-checkbox">
                                                        <input class="d-none" type="checkbox" id="Rememberme"
                                                            name="Rememberme">
                                                        <label for="Rememberme">Remember Me?</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group text-right">
                                                    <a href="#" class="forgot-pass-link" data-toggle="modal"
                                                        data-target="#modalForgotPassword"
                                                        data-dismiss="modal">Forgot Password?</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button class="btn btn-brand-01" type="submit">Login</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

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
                                <h5><span class="d-md-none d-block font-weight-bold">Membership Details: </span>Elite (M4)</h5>
                            </div>
                            <div class="col-md-2 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Validity: </span>1 Month</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Amount: </span>Qr xxx.xx</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Payable Amount: </span>Qr xxx.xx</h5>
                            </div>
                            <div class="col-md-1 col-6 my-auto list-order-close">
                                <h5><a href="#" data-toggle="tooltip" data-placement="bottom" title="Remove Item"><span class="close fal fa-times"></span></a></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 list-order-profile py-3">
                        <div class="row mx-n1">
                            <div class="col-md-4 col-sm-10 col-8 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Membership Details: </span>Platinum (AK47)</h5>
                            </div>
                            <div class="col-md-2 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Validity: </span>1 Month</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Amount: </span>Qr xxx.xx</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Payable Amount: </span>Qr xxx.xx</h5>
                            </div>
                            <div class="col-md-1 col-6 my-auto list-order-close">
                                <h5><a href="#" data-toggle="tooltip" data-placement="bottom" title="Remove Item"><span class="close fal fa-times"></span></a></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 list-order-profile py-3">
                        <div class="row mx-n1">
                            <div class="col-md-4 col-sm-10 col-8 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Membership Details: </span>Family Package (3)</h5>
                            </div>
                            <div class="col-md-2 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Validity: </span>1 Month</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Amount: </span>Qr xxx.xx</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Payable Amount: </span>Qr xxx.xx</h5>
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
                                <h5 class="mb-0">Cart Summary</h5>
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
                                    <a class="btn btn-brand-01 w-100" href="transaction.php">Pay Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
    });
</script>