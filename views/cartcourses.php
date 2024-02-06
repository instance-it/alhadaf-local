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
                    <li class="active">Cart</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing" id="cart-section">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8 mb-4 mb-lg-0">
                <div class="row cart-tbl">
                    <div class="col-md-12 heading-order-profile d-none d-md-block">
                        <div class="row mx-n1">
                            <div class="col-md-4 px-1">
                                <h5 class="font-weight-bold">Courses Details</h5>
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
                                <h5><span class="d-md-none d-block font-weight-bold">Courses Details: </span> Kids Airgun</h5>
                            </div>
                            <div class="col-md-2 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Validity: </span> 3 Hours</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Amount: </span> Qr xxx.xx</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Payable Amount: </span> Qr xxx.xx</h5>
                            </div>
                            <div class="col-md-1 col-6 my-auto list-order-close">
                                <h5><a href="#" data-toggle="tooltip" data-placement="bottom" title="Remove Item"><span class="close fal fa-times"></span></a></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 list-order-profile py-3">
                        <div class="row mx-n1">
                            <div class="col-md-4 col-sm-10 col-8 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Courses Details: </span> Kids Basic Gun Handling</h5>
                            </div>
                            <div class="col-md-2 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Validity: </span> 3 Hours</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Amount: </span> Qr xxx.xx</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Payable Amount: </span> Qr xxx.xx</h5>
                            </div>
                            <div class="col-md-1 col-6 my-auto list-order-close">
                                <h5><a href="#" data-toggle="tooltip" data-placement="bottom" title="Remove Item"><span class="close fal fa-times"></span></a></h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 list-order-profile py-3">
                        <div class="row mx-n1">
                            <div class="col-md-4 col-sm-10 col-8 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Courses Details: </span> Skill Development</h5>
                            </div>
                            <div class="col-md-2 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Validity: </span> 3 Hours</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Amount: </span> Qr xxx.xx</h5>
                            </div>
                            <div class="col-md-3 col-4 px-1 my-auto">
                                <h5><span class="d-md-none d-block font-weight-bold">Payable Amount: </span> Qr xxx.xx</h5>
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


<script>
    $(document).ready(function(){
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.licart").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.licart").addClass("active");

        $('#btnapplycoupon').click(function () {
            if ($("#couponcode").hasClass("has-val")) {
                $(".couponscode-content").removeClass("d-none");
                $(".couponscode-content-apply").addClass("d-none");
            } else {
                $(".couponscode-content").addClass("d-none");
                $(".couponscode-content-apply").removeClass("d-none");
            }
        });
        $('.couponscode-remove').click(function () {
            $(".couponscode-content").addClass("d-none");
            $(".couponscode-content-apply").removeClass("d-none");
        });
    });
</script>