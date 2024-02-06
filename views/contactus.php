<?php require_once dirname(__DIR__, 1).'\config\init.php';
?>
<!--page header section start-->
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="breadcrumb-list notranslate">
                    <li><a href="javascript:void(0)" class="clspageparentmenu lan-home-menu" pagename="home">Home</a></li>
                    <li class="active lan-contactus-menu">Contact Us</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->

<!--our contact promo start-->
<section class="promo-section section-spacing pb-0">
    <div class="container">
        <div class="row justify-content-md-center justify-content-sm-center">
            
            <?php
            if($CompanyInfo->getEmail1())
            {
            ?>
                <div class="col-md-6 col-lg-4 my-3">
                    <div class="card single-promo-card text-center">
                        <div class="card-body">
                            <div class="pb-2">
                                <span class="icon-untitled-11 icon-size-lg color-primary"></span>
                            </div>
                            <div class="pt-2 pb-3">
                                <h5>Mail Us</h5>
                                <p class="mb-0"><a href="mailto:<?php echo $CompanyInfo->getEmail1() ?>"><?php echo $CompanyInfo->getEmail1() ?></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            

            <?php
            if($CompanyInfo->getContact1())
            {
            ?>
                <div class="col-md-6 col-lg-4 my-3">
                    <div class="card single-promo-card text-center">
                        <div class="card-body">
                            <div class="pb-2">
                                <span class="icon-untitled-10 icon-size-lg color-primary"></span>
                            </div>
                            <div class="pt-2 pb-3">
                                <h5>Call Us</h5>
                                <p class="mb-0"><a href="tel:<?php echo $CompanyInfo->getContact1() ?>"><?php echo $CompanyInfo->getContact1() ?></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>


            <?php
            if($CompanyInfo->getAddress())
            {
            ?>
                <div class="col-md-6 col-lg-4 my-3">
                    <div class="card single-promo-card text-center">
                        <div class="card-body">
                            <div class="pb-2">
                                <span class="icon-untitled-9 icon-size-lg color-primary"></span>
                            </div>
                            <div class="pt-2 pb-3">
                                <h5>Visit Us</h5>
                                <?php
                                $gmaplinkurlurl='javacript:void(0);';
                                $targetblank='';
                                if($CompanyInfo->getGMapLink() != '')
                                {
                                    $gmaplinkurlurl=$CompanyInfo->getGMapLink();
                                    $targetblank='_blank';
                                }
                                ?>
                                <p class="mb-0"><a href="<?php echo $gmaplinkurlurl ?>" target="<?php echo $targetblank ?>"><?php echo $CompanyInfo->getAddress() ?></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>


        </div>
    </div>
</section>
<!--our contact promo end-->

<!--our contact section start-->
<section class="contact-us-section section-spacing" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-9">
                <div class="contact-us-form-wrap gray-light-bg position-relative">
                    <form method="POST" id="contactForm" class="contact-us-form">
                        <!-- <input type="hidden" name="csrfToken" id="csrfToken" value="" /> -->
                        <div class="row">
                            <div class="col-12 col-lg-9">
                                <div class="row">
                                    <div class="col-12">
                                        <h2>Send Us a Message</h2>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group validate-input">
                                            <input class="form-control c_name" id="c_name" name="c_name" type="text" required="required">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="c_name">Name <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group validate-input">
                                            <input class="form-control c_email" id="c_email" name="c_email" type="text" required="required">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="c_email">Email <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group validate-input">
                                            <input class="form-control c_mobile" id="c_mobile" name="c_mobile" type="text" required="required">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="c_mobile">Mobile Number <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group validate-input">
                                            <textarea class="form-control c_message" id="c_message" name="c_message" rows="4"></textarea>
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="c_message">Message <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3">
                                        <button type="submit" class="btn btn-brand-01" id="btnContactUs">Send Message</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <?php
                if($ProjectSetting->getIFrameMapLink())
                {
                ?>
                    <div class="google-map primary-bg text-white shadow-lg">
                        <iframe src="<?php echo $ProjectSetting->getIFrameMapLink() ?>" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                <?php
                }
                ?>
            </div>

        </div>
    </div>
</section>
<!--our contact section end-->

<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script>
    $(document).ready(function () {
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.licontactus").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.licontactus").addClass("active");
    });


    /************************* Start For Contact US Form ******************************/
    //Validate Contact Form
    if($('#contactForm').length){		
        $('#contactForm').validate({
            rules:{
                c_name:{
                    required: true,					
                },
                c_email:{
                    required: true,
                    email:true,
                },
                c_mobile:{
                    required: true,
                    number:true,
                    maxlength: 12
                },
                c_message:{
                    required: true,
                },
            },messages:{
                c_name:{
                    required: "Name is required",						
                },
                c_email:{
                    required: "Email is required",
                },
                c_mobile:{
                    required: "Mobile number is required",	
                },
                c_message:{
                    required: "Message is required",		
                },
            },
            submitHandler: function(form){

                $("#preloader").fadeIn();
                var pagename=getpagename();
                formdata = new FormData(form);
                formdata.append("action", "insertcontactus");
                var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesscontactus,OnErrorcontactus); 
                
            },
        });
    }


    function Onsuccesscontactus(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        $("#preloader").fadeOut();

        if(resultdata.status==0)
        {
            toastr.error(resultdata.message);
        }
        else if(resultdata.status==1)
        {
            $("#contactForm").validate().resetForm();
            $('#contactForm').trigger("reset");
            toastr.success(resultdata.message);
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 
    }


    function OnErrorcontactus(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    } 
    /************************* End For Contact US Form ******************************/
</script>