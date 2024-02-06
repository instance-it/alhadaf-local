<!--preloader start-->
<div id="preloader">
    <div class="preloader-wrap">
        <img src="assets/images/loader.png" alt="logo" class="img-fluid" />
        <div class="preloader">
            <i class="icon-untitled-6"></i>
            <i class="icon-untitled-6"></i>
            <i class="icon-untitled-6"></i>
        </div>
    </div>
</div>
<!--preloader end-->

<!-- <div class="google-translate-block">
    <div class="gt-element">
        <div id="google_translate_element"></div> 
    </div> 
    
</div> -->
<!--header section start-->
<header id="header" class="header-main">
    <!--main header menu start-->
    <div id="logoAndNav" class="main-header-menu-wrap fixed-top">
        <div class="container-fluid">
            <nav class="js-mega-menu navbar navbar-expand-lg header-nav">

                <!--logo start-->
                <a class="navbar-brand pt-0 clsparentmenu" pagename="home" href="javascript:void(0);">
                    <img class="img-fluid brand-logo" src="<?php echo $config->getImageurl().$CompanyInfo->getLogoImg() ?>" alt="<?php echo $CompanyInfo->getCompanyname() ?>" />
                </a>
                <!--logo end-->

                <!--responsive toggle button start-->
                <button type="button" class="d-none navbar-toggler btn" aria-expanded="false" aria-controls="navBar" data-toggle="collapse" data-target="#navBar">
                    <span id="hamburgerTrigger">
                        <span class="fal fa-bars"></span>
                    </span>
                </button>
                <!--responsive toggle button end-->

                <!--main menu start-->
                <div id="navBar" class="collapse navbar-collapse">
                    <div class="navbar-contact text-md-right">
                        <ul class="navbar-country">
                            <li class="dropdown">
                                <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-brand-04 main-link-toggle"><span class="flag-img d-none"><img src="assets/images/flag/qatar.png" alt="logo" class="img-fluid" width="25px" /></span> <text>Qatar</text></a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);"><span class="flag-img d-none"><img src="assets/images/flag/qatar.png" alt="logo" class="img-fluid" width="25px" /></span> <text>English</text></a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);"><span class="flag-img d-none"><img src="assets/images/flag/qatar.png" alt="logo" class="img-fluid" width="25px" /></span> <text>Arabic</text></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="navbar-social">
                            
                            <?php
                            if($ProjectSetting->getFacebooklink())
                            {
                            ?>
                                <li><a href="<?php echo $ProjectSetting->getFacebooklink() ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <?php
                            }

                            if($ProjectSetting->getTwitterlink())
                            {
                            ?>
                                <li><a href="<?php echo $ProjectSetting->getTwitterlink() ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <?php
                            }

                            if($ProjectSetting->getInstagramlink())
                            {
                            ?>
                                <li><a href="<?php echo $ProjectSetting->getInstagramlink() ?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                            <?php
                            }
                            ?>
                            
                        </ul>
                        <ul class="navbar-contact-btn">
                            <?php
                            if($LoginInfo->getUid()!='' && $LoginInfo->getIsguestuser()==0 && $LoginInfo->getUtypeid() != $config->getAdminutype())
                            {

                            }
                            else
                            {
                            ?>
                                <li><a href="javascript:void(0);" class="btn btn-brand-02" data-toggle="modal" data-target="#registerModal">Become A Member</a></li>
                                <li><a href="javascript:void(0);" class="btn btn-brand-01" data-toggle="modal" data-target="#loginModal">Login</a></li>
                            <?php
                            }   
                            ?>
                            <li class="licart">
                                <a href="javascript:void(0);" pagename="cart" class="btn btn-brand-01 px-3 clsparentmenu"><i class="fal fa-shopping-cart"></i><span class="cartcount">3</span></a>
                            </li>
                            
                        </ul>
                    </div>
                    <ul class="navbar-nav ml-auto main-navbar-nav">
                        <li class="nav-item custom-nav-item lihome">
                            <a class="nav-link custom-nav-link clsparentmenu" pagename="home" href="javascript:void(0);">Home</a>
                        </li>
                        <li class="nav-item custom-nav-item licourses">
                            <a class="nav-link custom-nav-link clsparentmenu" pagename="courses" href="javascript:void(0);">Courses</a>
                        </li>
                        <li class="nav-item custom-nav-item limembership">
                            <a class="nav-link custom-nav-link clsparentmenu" pagename="membership" href="javascript:void(0);">Premium Membership</a>
                        </li>                            
                        <li class="nav-item custom-nav-item lispecialpackages">
                            <a class="nav-link custom-nav-link clsparentmenu" pagename="specialpackages" href="javascript:void(0);">Special Packages</a>
                        </li>
                        <li class="nav-item custom-nav-item lirangebooking">
                            <a class="nav-link custom-nav-link clsparentmenu" pagename="rangebooking" href="javascript:void(0);">Range Booking</a>
                        </li>
                        <li class="nav-item custom-nav-item liaboutus">
                            <a class="nav-link custom-nav-link clsparentmenu" pagename="aboutus" href="javascript:void(0);">About Us</a>
                        </li>
                        <li class="nav-item custom-nav-item ligallery">
                            <a class="nav-link custom-nav-link clsparentmenu" pagename="gallery" href="javascript:void(0);">Gallery</a>
                        </li>
                        <li class="nav-item custom-nav-item licontactus">
                            <a class="nav-link custom-nav-link clsparentmenu" pagename="contactus" href="javascript:void(0);">Contact Us</a>
                        </li> 
                        <?php
                        if($LoginInfo->getUid()!='' && $LoginInfo->getIsguestuser()==0 && $LoginInfo->getUtypeid() != $config->getAdminutype())
                        {
                        ?>
                            <li class="nav-item custom-nav-item limyaccount">
                                <a class="nav-link custom-nav-link clsparentmenu" pagename="myaccount" href="javascript:void(0);">My Account</a>
                            </li>
                        <?php
                        }
                        ?>
                        
                    </ul>
                </div>
                <!--main menu end-->
            </nav>
        </div>
    </div>
    <!--main header menu end-->
</header>
<!--header section end-->

<div class="navmobile-overlay"></div>
<div class="mobile-footer-sidebar" id="navBarMobile">
    <div class="mobile-footer-content">        
        <div class="mobile-country mb-2">
            <ul class="navbar-country">
                <li class="dropdown px-3">
                    <a href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn main-link-toggle w-100"><span class="flag-img"><img src="assets/images/flag/qatar.png" alt="logo" class="img-fluid" width="25px" /></span> <text>Qatar</text></a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);"><span class="flag-img d-none"><img src="assets/images/flag/qatar.png" alt="logo" class="img-fluid" width="25px" /></span> <text>English</text></a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);"><span class="flag-img d-none"><img src="assets/images/flag/qatar.png" alt="logo" class="img-fluid" width="25px" /></span> <text>Arabic</text></a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);"><span class="flag-img"><img src="assets/images/flag/saudi-arabia.png" alt="logo" class="img-fluid" width="25px" /></span> <text>Saudi Arabia</text></a>
                        </li>
                        <li class="active">
                            <a class="dropdown-item" href="javascript:void(0);"><span class="flag-img"><img src="assets/images/flag/qatar.png" alt="logo" class="img-fluid" width="25px" /></span> <text>Qatar</text></a>
                        </li>
                        <!-- <li>
                            <a class="dropdown-item" href="javascript:void(0);"><span class="flag-img"><img src="assets/images/flag/india.png" alt="logo" class="img-fluid" width="25px" /></span> India</a>
                        </li> -->
                    </ul>
                </li>
            </ul>
        </div>
        <div class="mobile-navigation">
            <ul>
                <li>
                    <a class="" href="javascript:void(0);" data-toggle="modal" data-target="#registerModal">Become A Member</a>
                </li>
                <li>
                    <a class="" href="javascript:void(0);" data-toggle="modal" data-target="#loginModal">Login</a>
                </li>
                <li class="limyaccount">
                    <a class="clsparentmenu" pagename="myaccount" href="javascript:void(0);">My Account</a>
                </li>
                <li class="lihome">
                    <a class="clsparentmenu" pagename="home" href="javascript:void(0);">Home</a>
                </li>
                <li class="licourse">
                    <a class="clsparentmenu" pagename="cart" href="javascript:void(0);">Course</a>
                </li>
                                          
                <li class="limembership">
                    <a class="clsparentmenu" pagename="membership" href="javascript:void(0);">Premium Membership</a>
                </li>                            
                <li class="lispecialpackages">
                    <a class="clsparentmenu" pagename="specialpackages" href="javascript:void(0);">Special Packages</a>
                </li>
                <li class="lirangebooking">
                    <a class="clsparentmenu" pagename="rangebooking" href="javascript:void(0);">Range Booking</a>
                </li>
                <li class="liaboutus">
                    <a class="clsparentmenu" pagename="aboutus" href="javascript:void(0);">About Us</a>
                </li>
                <li class="ligallery">
                    <a class="clsparentmenu" pagename="gallery" href="javascript:void(0);">Gallery</a>
                </li>
                
                <li class="licontactus">
                    <a class="clsparentmenu" pagename="contactus" href="javascript:void(0);">Contact Us</a>
                </li>
            </ul>
        </div>


        <div class="mobile-social-media">
            <ul>
                <li><a href="#"><i class="fab fa-facebook-f"></i></a>
                </li>
                <li>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </li>
                <li>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </li>

            </ul>
        </div>


    </div>
</div>