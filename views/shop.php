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
                    <li class="active">Shop</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing" id="shop-section">
    <div class="container">
        <div class="row mr-0">
            <div class="col-auto pr-0 my-auto">
                <a href="javascript:void(0);" class="btn btn-brand-03 open-filter mb-3">Filters</a>
                <!-- <a href="javascript:void(0);" class="btn btn-brand-03 mb-3">Clear All</a> -->
            </div>
            <div class="col pr-0 my-auto">
                <ul class="mb-3">
                    <!-- <li><a href="javascript:void(0);"><i class="fal fa-times mr-2"></i> Filters</a> </li> -->
                </ul>
            </div>
            <div class="col-12 col-sm-5 col-md-4 col-lg-3 col-xl-2 ml-auto pr-0 my-auto">
                <div class="form-group validate-input mb-3">
                    <select class="form-control selectpicker" id="sortby" name="sortby" title="Sort by">
                        <option value="1">Popularity</option>
                        <option value="2">Price: low to high</option>
                        <option value="3">Price: high to low</option>
                        <option value="4">Average Rating</option>
                        <option value="5">Discount</option>
                    </select>
                    <span class="focus-form-control"></span>
                    <label class="label-form-control" for="sortby">Sort by</label>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- 1st -->
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-1-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 2nd -->
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-2-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 3rd -->
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-3-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 4rt -->
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-4-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 5th -->
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-5-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!--6th  -->
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-6-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-7-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-8-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-9-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-20-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-21-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                <div class="shop-item">
                    <div class="shop-item-img">
                        <a href="shopdetails.php">
                            <figure class="shop-item-figure">
                                <img src="assets/images/shop/shop-22-sm.jpg">
                            </figure>
                        </a>
                        <div class="retting-star">
                            <div class="retting-block">
                                <ul class="retting-link">
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star"></i></li>
                                    <li><i class="icon-star-empty"></i></li>
                                    <li>(10)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="shop-item-content">
                        <div class="shop-item-title">
                            <h4>
                                <a href="shopdetails.php">
                                    Optics Explorer High Powered 12×50 Monocular
                                </a>
                            </h4>
                        </div>
                        <div class="product_price">
                            <del>Qr xxx.xx</del>
                            <span>Qr xxx.xx </span>
                        </div>
                        <div class="shop-item-control">
                            <a href="#" class="btn btn-brand-05 btn-sm">Buy Now</a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-cart"><i
                                    class="far fa-shopping-cart"></i></a>
                            <a href="javascript:void(0);" class="btn btn-brand-01 btn-sm shop-item-heart"><i
                                    class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>


</section>

<div class="filter-overlay open-filter"></div>
<div class="filter-sidebar">
    <input type="hidden" name="filter_search" id="filter_search" />
    <!-- Sidebar Start -->
    <div class="filter" id="sidebarFilter">
        <div class="sidebar-widget sidebar-widget-header">
            <h5>
                <span>Filter</span>
                <div class="close-btn close-dark open-filter" id="sidebarFilterClose">
                    <i class="fal fa-times"></i>
                </div>
            </h5>
        </div>
        <div class="sidebar sidebar-left">
            <div class="sidebar-widget">
                <form class="row">
                    <div class="col-12 mb-4">
                        <div class="filter-price-range-block">
                            <div class="row mb-2">
                                <div class="col-6 my-auto">
                                    <label class="filter-label m-0">Filter By Price:</label>                                    
                                </div>
                                <div class="col-6 my-auto">
                                    <input type="text" name="text" class="w-100 text-right border-0" id="amount" readonly="readonly"/> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div id="slider-range" class="price-filter-range"></div>
                                </div>
                                <div class="col-6">
                                    <input type="hidden" id="priceMin" name="priceMin" autocomplete="off" readonly="readonly" value="210" class="price-range-field" />
                                </div>
                                <div class="col-6">
                                    <input type="hidden" id="priceMax" name="priceMax"  autocomplete="off" readonly="readonly" value="600" class="price-range-field" />
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="row">
                            <div class="col-12">
                                <label class="filter-label">Categories:</label>  
                            </div>
                            <div class="col-12">
                                <ul class="product-categories">
                                    <li class="cat-item"><a href="javascript:void(0);">Accessories</a></li>
                                    <li class="cat-item"><a href="javascript:void(0);">Ammunition</a></li>
                                    <li class="cat-item"><a href="javascript:void(0);">Handguns</a>
                                        <ul class="children">
                                            <li class="cat-item"><a href="javascript:void(0);">1911</a></li>
                                            <li class="cat-item"><a href="javascript:void(0);">Optic Ready</a></li>
                                            <li class="cat-item"><a href="javascript:void(0);">Polymer</a></li>
                                            <li class="cat-item"><a href="javascript:void(0);">Revolvers</a></li>
                                            <li class="cat-item"><a href="javascript:void(0);">Rifle Style Pistols</a></li>
                                            <li class="cat-item"><a href="javascript:void(0);">Rimfire</a></li>
                                        </ul>
                                    </li>
                                    <li class="cat-item"><a href="javascript:void(0);">Optics</a></li>
                                    <li class="cat-item"><a href="javascript:void(0);">Rifles</a></li>
                                    <li class="cat-item"><a href="javascript:void(0);">Shotguns</a></li>
                                    <li class="cat-item"><a href="javascript:void(0);">Silencers</a></li>
                                    <li class="cat-item"><a href="javascript:void(0);">Uncategorized</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="row">
                            <div class="col-12">
                                <label class="filter-label">Filter By:</label>  
                            </div>
                            <div class="col-12">
                                <div class="form-group form-checkbox mb-2">
                                    <div class="input-checkbox">
                                        <input class="d-none" type="checkbox" id="checkboxfilterby1" name="checkboxfilterby1">
                                        <label for="checkboxfilterby1">10 mm (1)</label>
                                    </div>
                                </div>
                                <div class="form-group form-checkbox mb-2">
                                    <div class="input-checkbox">
                                        <input class="d-none" type="checkbox" id="checkboxfilterby2" name="checkboxfilterby2">
                                        <label for="checkboxfilterby2">12 mm (1)</label>
                                    </div>
                                </div>
                                <div class="form-group form-checkbox mb-2">
                                    <div class="input-checkbox">
                                        <input class="d-none" type="checkbox" id="checkboxfilterby3" name="checkboxfilterby3">
                                        <label for="checkboxfilterby3">5.56 mm (2)</label>
                                    </div>
                                </div>
                                <div class="form-group form-checkbox mb-2">
                                    <div class="input-checkbox">
                                        <input class="d-none" type="checkbox" id="checkboxfilterby4" name="checkboxfilterby4">
                                        <label for="checkboxfilterby4">9 mm (1)</label>
                                    </div>
                                </div>
                                <div class="form-group form-checkbox mb-2">
                                    <div class="input-checkbox">
                                        <input class="d-none" type="checkbox" id="checkboxfilterby5" name="checkboxfilterby5">
                                        <label for="checkboxfilterby5">9.07 mm (1)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" id="applyfilter" class="btn btn-brand-01 w-100 mb-3">Apply filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Sidebar End -->

<script>
$(document).ready(function() {
    $("ul.main-navbar-nav li.nav-item").removeClass("active");
    $("ul.main-navbar-nav li.lishop").addClass("active");
    $(".mobile-navigation li").removeClass("active");
    $(".mobile-navigation li.lishop").addClass("active");

    $(".open-filter").on("click", function() {
        $("#sidebarFilter").toggleClass("open");
        $(".filter-overlay").toggleClass("open");
        $("html, body").toggleClass("overflow-hidden");
    });
    $("#slider-range" ).slider({
		range: true,
		min: 100,
		max: 1000,
		values: [ 210, 600 ],
		change: function( event, ui ) 
		{
			$( "#amount" ).val( "Qr" + ui.values[ 0 ] + " - Qr" + ui.values[ 1 ] );
			$('#priceMin').val(ui.values[ 0 ]);
			$('#priceMax').val(ui.values[ 1 ]);
			// applyproductfilter();
		}
	});
	
	$( "#amount" ).val( "Qr " + $( "#slider-range" ).slider( "values", 0 ) + " - Qr " + $( "#slider-range" ).slider( "values", 1 ) );
});
</script>