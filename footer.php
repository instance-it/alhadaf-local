<footer class="footer dark-bg pb-0">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-3 col-xl-4 mb-4 mb-lg-0 pr-xl-5">
                <h6 class="footer-title">L<img src="assets/images/logo-icon.png" alt="logo" />cate Us:</h6>
                <div class="footer-map">
                    <iframe src="<?php echo $ProjectSetting->getIFrameMapLink() ?>" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
            <div class="col-sm-12 col-lg-9 col-xl-8">
                <div class="row">
                    <div class="col-sm-6 col-lg-4 mb-4 mb-lg-0">
                        <h6 class="footer-title notranslate lan-contactus-menu">Contact US</h6>
                        <ul class="footer-contact">
                            
                            <?php
                            if($CompanyInfo->getAddress())
                            {
                                $gmaplinkurlurl='javacript:void(0);';
                                $targetblank='';
                                if($CompanyInfo->getGMapLink() != '')
                                {
                                    $gmaplinkurlurl=$CompanyInfo->getGMapLink();
                                    $targetblank='_blank';
                                }

                                echo '<li><a href="'.$gmaplinkurlurl.'" target="'.$targetblank.'"><i class="icon-untitled-9"></i> '.$CompanyInfo->getAddress().'</a></li>';
                            }
                            if($CompanyInfo->getEmail1())
                            {
                                echo '<li><a href="mailto:'.$CompanyInfo->getEmail1().'"><i class="icon-untitled-11"></i> '.$CompanyInfo->getEmail1().'</a></li>';
                            }
                            if($CompanyInfo->getContact1())
                            {
                                echo '<li><a href="tel:'.$CompanyInfo->getContact1().'"><i class="icon-untitled-10"></i>'.$CompanyInfo->getContact1().'</a></li>';
                            }
                            if($CompanyInfo->getContact2())
                            {
                                echo '<li><a href="tel:'.$CompanyInfo->getContact2().'"><i class="icon-untitled-10"></i>'.$CompanyInfo->getContact2().'</a></li>';
                            }
                            ?>
                            
                        </ul>


                        <!----------------------------- Start For Follow Us --------------------------->
                        <?php
                        if($ProjectSetting->getFacebooklink() || $ProjectSetting->getTwitterlink() || $ProjectSetting->getInstagramlink())
                        {
                        ?>
                        <h6 class="footer-title mt-4">Follow Us</h6>
                        <ul class="list-inline social-list-default background-color social-hover-2">
                            <?php
                            if($ProjectSetting->getFacebooklink())
                            {
                            ?>
                                <li class="list-inline-item"><a class="facebook" href="<?php echo $ProjectSetting->getFacebooklink() ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <?php
                            }

                            if($ProjectSetting->getTwitterlink())
                            {
                            ?>
                                <li class="list-inline-item"><a class="twitter" href="<?php echo $ProjectSetting->getTwitterlink() ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <?php
                            }

                            if($ProjectSetting->getInstagramlink())
                            {
                            ?>
                                <li class="list-inline-item"><a class="instagram" href="<?php echo $ProjectSetting->getInstagramlink() ?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
                            <?php
                            }

                            if($ProjectSetting->getWhatsAppNo())
                            {
                            ?>
                                <li class="list-inline-item"><a class="whatsapp" href="https://api.whatsapp.com/send?phone=<?php echo $ProjectSetting->getWhatsAppNo() ?>" target="_blank"><i class="fab fa-whatsapp"></i></a></li>
                            <?php
                            }
                            ?>
                        </ul>
                        <?php
                        }
                        ?>
                        <!----------------------------- End For Follow Us --------------------------->



                    </div>
                    <div class="col-sm-6 col-lg-4 mb-4 mb-lg-0 notranslate">
                        <h6 class="footer-title lan-footer-customerservice">Customer Service</h6>
                        <ul class="footer-list">
                            <li>
                                <a href="javascript:void(0);" class="lan-footer-simulator">Simulator</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="lan-footer-rifle">Rifle </a>
                            </li>
                            <!-- <li>
                                <a href="javascript:void(0);">Long Gun Rentals</a>
                            </li> -->
                            <li>
                                <a href="javascript:void(0);" class="lan-footer-airgun">Airgun</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="lan-footer-skeet">Skeet</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="clsparentmenu lan-footer-personaltraining" pagename="courses">Personal Training / Courses </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="lan-footer-foodbeverage">Food and Beverage</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="clsparentmenu lan-footer-faq" pagename="faq">FAQ</a>
                            </li>

                            <li>
                                <a href="mailto:hr@alhadafshooting.com" class="lan-footer-careeropportunity">Career Opportunities</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="clsparentmenu lan-footer-champion" pagename="tournaments-fees-and-registrations">Al Hadaf Champion & Tournament</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-12 col-lg-4 mb-0 mb-lg-0">
                        <h6 class="footer-title notranslate lan-footer-signup">Sign Up</h6>
                        <form class="row" method="post" id="footeremailForm">
                            <div class="col-12">
                                <p><span class="notranslate lan-footer-signupreceivefrom">Sign Up to Receive Specials from</span> Honor Gun Club</p>
                            </div>
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control control-append"
                                        placeholder="Enter Your Email Address" id="signupemail" name="signupemail"
                                        required="required">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text" id="btnsignupemail"
                                            disabled="disabled"><i class="far fa-paper-plane"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group form-checkbox m-0">
                                    <div class="input-checkbox">
                                        <input class="d-none" type="checkbox" id="agreetermsconditions"
                                            name="agreetermsconditions">
                                        <label for="agreetermsconditions"><span class="notranslate lan-footer-readagree">I have read and agree to the</span> <a href="javascript:void(0);" class="clsparentmenu notranslate lan-footer-tandc" pagename="termsandcondition">terms & conditions</a></label>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <?php
                        if(sizeof($CompanyInfo->getCmpRangeHour()) > 0)
                        {
                        ?>
                        <h6 class="footer-title mt-4 notranslate lan-footer-rangehour">Range Hours</h6>
                        <ul class="footer-openday">
                            <?php
                            for($k=0;$k<sizeof($CompanyInfo->getCmpRangeHour());$k++)
                            {
                            ?>
                                <li><?php echo $CompanyInfo->getCmpRangeHour()[$k]->getName(); ?></li>
                            <?php
                            }
                            ?>
                        </ul>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12 text-center py-3">
                <div class="copyright-wrap">
                    <p class="mb-0">&copy; <?php echo date('Y') ?> <a href="javascript:void(0);" pagename="home" class="clsparentmenu"><?php echo $CompanyInfo->getCompanyname() ?></a>. All Right Reserved. 
                    <a class="small-text clsparentmenu" href="javascript:void(0);" pagename="privacypolicy">Privacy Policy</a>.</p>
                </div>
            </div>
        </div>
    </div>
    <!--end of container-->
</footer>

<!--footer section end-->
<!--scroll bottom to top button start-->
<div class="scroll-top scroll-to-target primary-bg text-white" data-target="html">
    <span class="fal fa-arrow-up"></span>
</div>
<!--scroll bottom to top button end-->

<?php include('mobile-footer.php'); ?>



<!------------------ Start For Member Register Modal popup --------------------->
<div class="modal fade in" id="registerModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3 modal-title">Become A Member</h4>
                        <button type="button" class="close" data-dismiss="modal"><i class="fal fa-times"></i></button>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <p>Please leave your contact details and our team will get in touch with you.</p>
                            </div>
                            <div class="col-12">
                                <form method="post" id="registrationForm" novalidate="true">
                                    <input type="hidden" name="targeturl" id="targeturl" class="targeturl" value="">
                                    <input type="hidden" name="hiddenid" id="hiddenid">
                                    <input type="hidden" name="regtype" id="regtype" value="n">
                                    <div class="row mr-0">
                                        <div class="col-12 col-md-4 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_fname" type="text" required="required" id="r_fname" name="r_fname">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_fname">First Name <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_fname" type="text" required="required" id="r_mname" name="r_mname">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_fname">Middle Name </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_lname" type="text" required="required" id="r_lname" name="r_lname">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_lname">Last Name <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_email" type="email" required="required" id="r_email" name="r_email">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_email">Email <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_mobile" type="text" required="required" id="r_mobile" name="r_mobile" maxlength="12" onkeypress="numbonly(event)">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_mobile">Mobile Number <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_qataridno" type="text"  id="r_qataridno" name="r_qataridno">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_qataridno">Qatar id Number </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_qataridexpiry" type="text"  id="r_qataridexpiry" name="r_qataridexpiry" autocomplete="off" readonly="readonly">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_qataridexpiry">Qatar id Expiry </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_passportidno" type="text"  id="r_passportidno" name="r_passportidno">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_passportidno">Passport id Number </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_passportidexpiry" type="text"  id="r_passportidexpiry" name="r_passportidexpiry" autocomplete="off" readonly="readonly">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_passportidexpiry">Passport id Expiry </label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_dob" type="text" required="required" id="r_dob" name="r_dob" autocomplete="off" readonly="readonly">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_dob">Date of Birth <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_nationality" type="text" required="required" id="r_nationality" name="r_nationality">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_nationality">Nationality <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12 pr-0">
                                            <div class="form-group validate-input">
                                                <textarea class="form-control r_address" id="r_address" name="r_address" rows="2"></textarea>
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_address">Address</label>
                                            </div>
                                        </div>
                                        <div class="col-12 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_companyname" type="text" id="r_companyname" name="r_companyname">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_companyname">Company</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input filepreview">
                                                <input class="form-control" type="file" name="r_qataridproof" id="r_qataridproof" accept="image/*">
                                                <label class="filepreviewbg" for="img">
                                                    <img class="d-none fileqataridproof" id="fileqataridproof" src="assets/img/logo-white.png">
                                                </label>
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_qataridproof">Proof Of Qatar ID <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input filepreview">
                                                <input class="form-control" type="file" name="r_passportproof" id="r_passportproof" accept="image/*">
                                                <label class="filepreviewbg" for="img">
                                                    <img class="d-none filepassportproof" id="filepassportproof" src="assets/img/logo-white.png">
                                                </label>
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_passportproof">Proof Of Passport <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input filepreview">
                                                <input class="form-control" type="file" name="r_othergovernmentproof" id="r_othergovernmentproof" accept="image/*">
                                                <label class="filepreviewbg" for="img">
                                                    <img class="d-none fileothergovernmentproof" id="fileothergovernmentproof" src="assets/img/logo-white.png">
                                                </label>
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_othergovernmentproof">Other Government Valid Proof</label>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_password" type="password" id="r_password" name="r_password">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_password">Password</label>
                                            </div>
                                        </div>
                                        <!-- <div class="col-12 col-md-6 pr-0">
                                            <div class="form-group validate-input">
                                                <input class="form-control r_cpassword" type="password" id="r_cpassword" name="r_cpassword">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="r_cpassword">Confirm Password</label>
                                            </div>
                                        </div> -->
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button class="btn btn-brand-01" type="submit">Signup</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 text-center">
                                <hr class="divider hrsignup">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-lg-7 mx-auto">
                                        <div class="row">
                                            <div class="col-12 col-sm-6">
                                                <a href="javascript:void(0)" onclick="fb_login(1);">
                                                    <div class="d-flex login-fb login-footer">
                                                        <i class="fab fa-facebook-square"></i>
                                                        <p class="title-social-btn">Facebook</p>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <a href="javascript:void(0)" id="customGoogleBtn1" type="1">
                                                    <div class="d-flex login-google login-footer">
                                                        <img src="assets/images/google-logo.png">
                                                        <p class="title-social-btn">Google</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <span>Already have a Account? <a href="javascript:void(0);" data-toggle="modal" data-target="#loginModal" data-dismiss="modal">Back to Login</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------ End For Member Register Modal popup --------------------->



<!------------------ Start For Member Login Modal popup --------------------->
<div class="modal fade in" id="loginModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3 modal-title">Login</h4>
                        <button type="button" class="close" data-dismiss="modal"><i class="fal fa-times"></i></button>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <form method="post" id="loginForm" novalidate="true">
                                    <input type="hidden" name="targeturl" id="targeturl" class="targeturl" value="">
                                    <input type="hidden" name="lo_ischeckout" id="lo_ischeckout" value="0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group validate-input">
                                                <input class="form-control l_username" type="text" id="l_username" name="l_username" required="required">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="l_username">Email / Mobile Number <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group validate-input">
                                                <input class="form-control l_password" type="Password" id="l_password" name="l_password" required="required">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="l_password">Password <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-6">
                                                    <!-- <div class="form-group form-checkbox m-0 text-left sm-checkbox">
                                                        <div class="input-checkbox">
                                                            <input class="d-none" type="checkbox" id="Rememberme" name="Rememberme">
                                                            <label for="Rememberme">Remember Me?</label>
                                                        </div>
                                                    </div> -->
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group text-right">
                                                        <a href="javascript:void(0)" class="forgot-pass-link" data-toggle="modal" data-target="#forgotpaswordModal" data-dismiss="modal">Forgot Password?</a>
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
                                </form>
                            </div>
                            <div class="col-12 text-center">
                                <hr class="divider hrlogin">
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-sm-6 pr-3">
                                        <a href="javascript:void(0)" onclick="fb_login(2);">
                                            <div class="d-flex login-fb login-footer">
                                                <i class="fab fa-facebook-square"></i>
                                                <p class="title-social-btn">Facebook</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-12 col-sm-6 pl-3">
                                        <a href="javascript:void(0)" id="customGoogleBtn2" type="2">
                                            <div class="d-flex login-google login-footer">
                                                <img src="assets/images/google-logo.png">
                                                <p class="title-social-btn">Google</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <span>Don't have an Account Yet? <a href="javascript:void(0);" data-toggle="modal"
                                        data-target="#registerModal" data-dismiss="modal">Sign Up</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------ End For Member Login Modal popup --------------------->



<!------------------ Start For Member Forgot Password Modal popup --------------------->
<div class="modal fade in" id="forgotpaswordModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3 modal-title">Forgot Password</h4>
                        <button type="button" class="close modal-close" data-dismiss="modal"><i class="fal fa-times"></i></button>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12" id="resetpassDiv">
                                <form method="post" id="forgotpaswordForm" novalidate="true">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group validate-input">
                                                <input class="form-control f_username" id="f_username" name="f_username" type="text" required="required">
                                                <span class="focus-form-control"></span>
                                                <label class="label-form-control" for="f_username">Email / Mobile Number <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button class="btn btn-brand-01" type="submit">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------ End For Member Forgot Password Modal popup --------------------->





<!------------------ Start For Compare Plan Modal popup --------------------->
<div class="modal fade in" id="modalComparePlans" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-exxl">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3 modal-title">Compare Plans</h4>
                        <button type="button" class="close" data-dismiss="modal"><i class="fal fa-times"></i></button>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive pricing-table-responsive thead-sticky tfoot-sticky" id="compareplanData">
                                    
                                   

                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------ End For Compare Plan Modal popup --------------------->


<!-- fullspecifications popup-->
<div class="modal fade in" id="fullspecifications" role="dialog">
    <div class="modal-dialog modal-dialog-centered bg-transparent">
        <div class="modal-content bg-transparent border-0">
            <!-- Modal body -->
            <div class="modal-body p-0 bg-transparent">
                <div class="row">
                    <div class="col-12 mb-4">
                        <!-- <h4 class="mb-3 modal-title">Elite (M4)</h4> -->
                        <button type="button" class="close text-white mr-2" data-dismiss="modal"><i
                                class="fal fa-times"></i></button>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="row">
                            <div class="col-12">
                                <form method="post" class="row">
                                    <div class="col-12">
                                        <div class="popular-price bg-white pricing-new-wrapper p-4 shadow-sm border">
                                            <div
                                                class="pricing-price d-flex justify-content-between align-items-center pb-4">
                                                <span class="p-icon"><i
                                                        class="fal fa-mountains color-accent"></i></span>
                                                <div class="h2 mb-0 pm-price">Qr xxx.xx <span
                                                        class="price-cycle h5">/mo</span></div>
                                            </div>
                                            <div class="pricing-info">
                                                <h5>Company Outings</h5>
                                            </div>
                                            <div class="pricing-content mt-3">
                                                <ul class="list-unstyled pricing-feature-list-2">
                                                    <li><i class="fal fa-user-check mr-2"></i>
                                                        <p><strong>xxx.xx qr/25</strong> Pax, <strong>xxx.xx
                                                                qr/40</strong> Pax, <strong>xxx.xx qr/50</strong> Pax
                                                        </p>
                                                    </li>
                                                    <li><i class="fal fa-calendar-alt mr-2"></i>
                                                        <p>Friday 8 AM to 11:30 AM</p>
                                                    </li>
                                                    <li><i class="fal fa-user-shield mr-2"></i>
                                                        <p><strong>Free</strong> Safety Course</p>
                                                    </li>
                                                    <li><i class="fal fa-badge-percent mr-2"></i>
                                                        <p><strong>Normal Price:</strong> <strong>-</strong> Rifile gun
                                                            (0), <strong>-</strong> Pistol (0),
                                                            <strong>Unlimited</strong> Laser, <strong>Unlimited</strong>
                                                            Airgun
                                                        </p>
                                                    </li>
                                                    <li><i class="fal fa-utensils-alt mr-2"></i>
                                                        <p><strong>Free Sandwiches</strong> Food item</p>
                                                    </li>
                                                    <li><i class="fal fa-glass mr-2"></i>
                                                        <p><strong>Free</strong> Hotdrinks, <strong>Free</strong>
                                                            Softdrinks</p>
                                                    </li>
                                                    <li><i class="fal fa-crosshairs mr-2"></i>
                                                        <p><strong>100 m</strong> Range (50% Dis)</p>
                                                    </li>
                                                    <li><i class="fal fa-stars mr-2"></i>
                                                        <p><strong>xxx.xx/xxx.xx/xxx.xx Qr</strong> VIP Range Upgrade
                                                        </p>
                                                    </li>
                                                </ul>
                                                <!-- <a href="#" class="mb-3 d-block">Full specifications <i class="far fa-arrow-right pl-2"></i></a> -->
                                                <a href="#" class="btn btn-outline-brand-01 btn-block mt-3">Buy Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ fullspecifications popup-->


<!-- fullspecificationsEliteM4 popup-->
<div class="modal fade in" id="fullspecificationsEliteM4" role="dialog">
    <div class="modal-dialog modal-dialog-centered bg-transparent">
        <div class="modal-content bg-transparent border-0">
            <!-- Modal body -->
            <div class="modal-body p-0 bg-transparent">
                <div class="row">
                    <div class="col-12 mb-4">
                        <!-- <h4 class="mb-3 modal-title">Elite (M4)</h4> -->
                        <button type="button" class="close text-white mr-2" data-dismiss="modal"><i
                                class="fal fa-times"></i></button>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="row">
                            <div class="col-12">
                                <form method="post" class="row">
                                    <div class="col-12">
                                        <div class="popular-price bg-white pricing-new-wrapper p-4 shadow-sm border">
                                            <div
                                                class="pricing-price d-flex justify-content-between align-items-center pb-4">
                                                <span class="p-icon"><i class="fal fa-coins color-accent"></i></span>
                                                <div class="h2 mb-0 pm-price">Qr xxx.xx <span
                                                        class="price-cycle h5">/mo</span></div>
                                            </div>
                                            <div class="pricing-info">
                                                <h5>Elite (M4)</h5>
                                            </div>
                                            <div class="pricing-content mt-3">
                                                <ul class="list-unstyled pricing-feature-list-2">
                                                    <li><i class="icon-untitled-6 mr-2"></i>
                                                        <p>160 G, 500 P, 12 Cenario A, 480 AG</p>
                                                    </li>
                                                    <li><i class="fal fa-user mr-2"></i>
                                                        <p><strong>1</strong> Personal Trainer</p>
                                                    </li>
                                                    <li><i class="fal fa-utensils-alt mr-2"></i>
                                                        <p><strong>30%</strong> Food and Beverage</p>
                                                    </li>
                                                    <li><i class="fal fa-glass mr-2"></i>
                                                        <p>Unlimited Hotdrinks-Softdrinks, Fresh Juices 15/Month old</p>
                                                    </li>
                                                    <li><i class="fal fa-stars mr-2"></i>
                                                        <p><strong>Yes (3hr/mo)</strong> VIP Shooting Location</p>
                                                    </li>
                                                    <li><i class="fal fa-hand-holding-box mr-2"></i>
                                                        <p><strong>15%</strong> Retail</p>
                                                    </li>
                                                    <li><i class="fal fa-hand-holding-heart mr-2"></i>
                                                        <p><strong>Yes</strong> Benefit 1 (Personal Weapon)</p>
                                                    </li>
                                                    <li><i class="fal fa-hand-holding-heart mr-2"></i>
                                                        <p><strong>Elite: 5%, Any other LOWER package 10% UPON
                                                                RESERVATION</strong> Benefit 2 Other Package Discounts
                                                        </p>
                                                    </li>
                                                    <li><i class="fal fa-hand-holding-heart mr-2"></i>
                                                        <p><strong>35% FROM Normal Price</strong> Benefit 2 Other
                                                            Package Discounts</p>
                                                    </li>
                                                    <li><i class="fal fa-hand-holding-heart mr-2"></i>
                                                        <p><strong>15% FROM Normal Price 5</strong> Benefits 4
                                                            (Entitlment) Extension (1)</p>
                                                    </li>
                                                    <li><i class="fal fa-hand-holding-heart mr-2"></i>
                                                        <p><strong>20% FROM Normal Price 10</strong> Benefits 4
                                                            (Entitlment) Extension (2)</p>
                                                    </li>
                                                    <li><i class="fal fa-hand-holding-heart mr-2"></i>
                                                        <p><strong>35% FROM Normal Price</strong> Benefits 4 (Entitlment
                                                            Extension)</p>
                                                    </li>
                                                    <li><i class="fal fa-check-circle mr-2"></i>
                                                        <p><strong>Yes</strong> VIP Lounge Access</p>
                                                    </li>
                                                    <li><i class="fal fa-id-badge mr-2"></i>
                                                        <p><strong>International Licence</strong> Courses</p>
                                                    </li>
                                                    <li><i class="fal fa-times-circle mr-2"></i>
                                                        <p><strong>NA</strong> Range Safety Course</p>
                                                    </li>
                                                    <li><i class="fal fa-times-circle mr-2"></i>
                                                        <p><strong>NA</strong> Upgrade for next year</p>
                                                    </li>
                                                    <li><i class="fal fa-repeat mr-2"></i>
                                                        <p><strong>5% Dis</strong> Renewal</p>
                                                    </li>
                                                    <li><i class="fal fa-trophy mr-2"></i>
                                                        <p><strong>75% Dis</strong> Competition</p>
                                                    </li>
                                                    <li><i class="fal fa-user-friends mr-2"></i>
                                                        <p><strong>3 Times per Month</strong> Invite a Friend</p>
                                                    </li>
                                                </ul>
                                                <!-- <a href="#" class="mb-3 d-block">Full specifications <i class="far fa-arrow-right pl-2"></i></a> -->
                                                <a href="#" class="btn btn-outline-brand-01 btn-block mt-3">Buy Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ fullspecifications popup-->



<!------------------ Start For Membership/Package Full Specification Modal popup --------------------->
<div class="modal fade in" id="fullspecificationModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered bg-transparent">
        <div class="modal-content bg-transparent border-0">
            <!-- Modal body -->
            <div class="modal-body p-0 bg-transparent">
                <div class="row">
                    <div class="col-12 mb-4">
                        <button type="button" class="close text-white mr-2" data-dismiss="modal"><i class="fal fa-times"></i></button>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="row">
                            <div class="col-12">
                                <form method="post" class="row">
                                    <div class="col-12">
                                        <div class="popular-price bg-white pricing-new-wrapper p-4 shadow-sm border" id="fullspecificationdata">
                                            
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!------------------ End For Membership/Package Full Specification Modal popup --------------------->

<div class="modal fade in" id="prizesmodel" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-exxl">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-4">
                    <button type="button" class="close  mr-2" data-dismiss="modal"><i
                                class="fal fa-times"></i></button>
                      
                    </div>

                    <div class="col-12">
                        <div class="table-responsive table-tournaments-fees thead-sticky tfoot-sticky">
                            <table class="table pricing-table">
                                <thead class="primary-bg text-white">
                                    <tr>
                                        <th colspan="1">DESCRIPTION</th>
                                        <th class="text-center">RANKS</th>
                                        <th class="text-center">SIMULATOR</th>
                                        <th class="text-center">AIRGUN</th>
                                        <th class="text-center">PISTOL</th>
                                        <th class="text-center">RIFLE</th>
                                        <th class="text-center">SKEET</th>
                                        <th class="text-center">F&B DISCOUNT</th>
                                        <th class="text-center">FREE</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tbl-row">
                                        <td rowspan="3">JAGUARS AIRGUN 7-15</td>
                                       
                                        <td class="text-center">1</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center">HOT DRINKS</td>

                                    </tr>
                                    <tr class="tbl-row">

                                        
                                        <td class="text-center">1</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>

                                    </tr>
                                    <tr class="tbl-row">

                                       
                                        <td class="text-center">1</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>

                                    </tr>
                                    <tr class="tbl-row">
                                        <td rowspan="3">BARAZAN LIONS AIRGUN 16- ABOVE</td>
                                        
                                        <td class="text-center">1</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center">HOT DRINKS</td>

                                    </tr>
                                    <tr class="tbl-row">

                                       
                                        <td class="text-center">1</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>

                                    </tr>
                                    <tr class="tbl-row">

                                        
                                        <td class="text-center">1</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>

                                    </tr>
                                    <tr class="tbl-row">
                                        <td rowspan="3">ZUBARA LEGENDS PISTOL</td>
                                        
                                        <td class="text-center">1</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center"> 3</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center">HOT DRINKS</td>

                                    </tr>
                                    <tr class="tbl-row">

                                       
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>

                                    </tr>
                                    <tr class="tbl-row">
                                     
                                        <td class="text-center">3</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr class="tbl-row">
                                        <td rowspan="3">WAJBA LEGENDS RIFLE</td>
                                        
                                        <td class="text-center">1</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center"> 3</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>

                                    </tr>
                                    <tr class="tbl-row">

                                       
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>

                                    </tr>
                                    <tr class="tbl-row">
                                       
                                        <td class="text-center">3</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr class="tbl-row">
                                        <td rowspan="3">ZUBARA & WAJBA LEGEND RIFLES AND PSITOL</td>
                                        
                                        <td class="text-center">1</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>

                                    </tr>
                                    <tr class="tbl-row">

                                       
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>

                                    </tr>
                                    <tr class="tbl-row">
                                       
                                        <td class="text-center">3</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>
                                    </tr>
                                    <tr class="tbl-row">
                                        <td rowspan="3">RANGE CHAMPION RIFLE, PISTOL & AIRGUN</td>
                                        
                                        <td class="text-center">1</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center">FREE DRINKS EXCEPT ENERGY</td>

                                    </tr>
                                    <tr class="tbl-row">

                                       
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>

                                    </tr>
                                    <tr class="tbl-row">
                                       
                                        <td class="text-center">3</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center"></td>
                                        <td class="text-center">35%</td>
                                        <td class="text-center"></td>
                                    </tr>






                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal cookiepolicyModal -->
<?php
if(empty($_COOKIE["AlhadafShootingRangeWebCookie"]))
{
?>
<div class="cookie-policy-popup fade in" id="cookiepolicypopup">
    <div class="popup-content">
        <div class="popup-body">
            <div class="row">
                <div class="col-12 col-md-auto text-center text-md-left mb-3 my-md-auto">
                    <img src="assets/images/cookie.png" alt="est-seller.png" width="100px" height="100px">
                </div>
                <div class="col-12 col-md text-center text-md-left my-md-auto">
                    <h4 class="modal-title blue-text">Cookie Policy</h4>
                    <div class="cookie-policy-text">
                        <p>This website uses cookies to ensure you get the best experience in our website.</p>
                    </div>
                </div>

                <div class="col-12 col-md-auto text-center text-md-right mt-3 my-md-auto">
                    <button title="Accept Cookies" id="acceptcookie" class="btn btn-cookie-policy m-0" type="button">Accept Cookies</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>
<!-- modal cookiepolicyModal end-->