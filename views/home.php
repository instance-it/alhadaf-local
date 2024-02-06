<?php require_once dirname(__DIR__, 1).'\config\init.php';
//require_once dirname(__DIR__, 1).'\config\config.php';
//$config = new config();
?>


<!--------------------- Start For Home Slider Section ---------------------->
<?php
$home_videourl='';
if($ProjectSetting->getHomeTopVideo() != '')
{
    $home_videourl=$config->getImageurl().$ProjectSetting->getHomeTopVideo();
}   

$ismemberlogin=0;
if($LoginInfo->getUid()!='' && $LoginInfo->getIsguestuser()==0 && $LoginInfo->getUtypeid() != $config->getAdminutype())
{   
    $ismemberlogin=1;
}
?>
<section class="section-banner overflow-hidden d-flex" >
    <video src="<?php echo $home_videourl ?>" autoplay loop playsinline muted width="100%"></video>
    <div class="container my-auto">
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="calendar-responsive mx-auto">
                    <div class="calendar-full-banner mx-auto">
                        <div class="calendar-img">
                            <a href="javascript:void(0)" class="clspageparentmenu" pagename="home"><img src="<?php echo $config->getImageurl(); ?>images/banner-cal.png" alt="logo" class="img-fluid" /></a>
                        </div>
                        <div class="calendar-content home-calendar">
                            <input type="hidden" id="datecalendarinput" name="datecalendarinput" value="<?php echo date('d/m/Y') ?>" />
                            <div id="datecalendar" class="picker-d"></div>
                        </div>
                    </div>
                    <div class="text-center mt-3 d-none">
                        <a href="appointments.php" class="btn btn-brand-01 m-0">Book Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 mt-4 my-lg-auto ml-auto">
                <div class="hero-slider-content text-white text-center text-lg-left">
                    <h1 class="text-white"><?php echo $ProjectSetting->getHomeTopText() ?></h1>
                    <div class="action-btns mt-4">
                        <?php
                        if($ProjectSetting->getHomeTopButtontext() != '')
                        {
                            $sliderbtnurl='javacript:void(0);';
                            $targetblank='';
                            if($ProjectSetting->getHomeTopButtonurl() != '')
                            {
                                $sliderbtnurl=$ProjectSetting->getHomeTopButtonurl();
                                $targetblank='_blank';
                            }
                        ?>
                            <a href="<?php echo $sliderbtnurl ?>" class="btn btn-brand-03 m-0" target="<?php echo $targetblank ?>"><?php echo $ProjectSetting->getHomeTopButtontext() ?></a>
                        <?php
                        }
                        ?>
                        
                    </div>
                </div>
            </div>
        </div>
        <!--end of row-->
    </div>
    <!--end of container-->
</section>
<!--------------------- End For Home Slider Section ---------------------->



<!--------------------- Start For Home How To Find Us Section ---------------------->
<section class="section-spacing section-findus py-3 notranslate">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-5 col-md mb-3 my-sm-auto pr-0 text-center text-sm-left">
                <h1 class="findus-title lan-howtofindus-menu">How to Find Us</h1>
            </div>
            <div class="col-12 col-sm-7 col-md-auto my-md-auto">
                <div class="row">
                    <div class="col-12 col-lg-12 text-center text-sm-right my-auto ml-auto">
                        <a href="javascript:void(0)"  class="btn btn-brand-05 clspageparentmenu lan-contactus-menu" pagename="contactus">CONTACT US</a>

                        <?php
                         if($CompanyInfo->getGMapLink() != '')
                         {
                         ?>
                            <a href="<?php echo $CompanyInfo->getGMapLink() ?>" target="_blank" class="btn btn-brand-03 lan-googlemap-menu">GOOGLE MAPS</a>
                         <?php
                         }
                        ?>
                        
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!--------------------- End For Home How To Find Us Section ---------------------->


<!--------------------- End For Home Youtube Video Section ---------------------->
<section class="video-section section-spacing pb-0 homevideo-section" id="videosection">
    <div class="container">
        <div class="row" id="homevideodatadiv">
            

        </div>
    </div>
</section>
<!--------------------- End For Home Youtube Video Section ---------------------->



<!--------------------- Start For Home About Section ---------------------->
<section class="promo-section section-spacing">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10" id="aboutus">
                
            </div>
        </div>
        <div class="row" id="aboutus-detail">
            <!-- <div class="col-12 col-md-12 col-lg-4 mb-4">
                <div class="card single-promo-card single-promo-hover text-center p-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-auto mb-3 mb-sm-0">
                                <span class="icon-gun icon-size-lg"></span>
                            </div>
                            <div class="col-12 col-sm text-left pl-sm-0">
                                <h5>Classes & Training</h5>
                                <div class="single-promo-desc">
                                    <p class="mb-0">Al Hadaf range prvide a professional classes and courses for all
                                        personnel wo are willing to develope their skills, you can select the courses
                                        that
                                        will suite your deires
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-4 mb-4">
                <div class="card single-promo-card single-promo-hover text-center p-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-auto mb-3 mb-sm-0">
                                <span class="icon-aim icon-size-lg"></span>
                            </div>
                            <div class="col-12 col-sm text-left pl-sm-0">
                                <h5>The Ranges</h5>
                                <div class="single-promo-desc">
                                    <p class="mb-0">10 lanes for Air Guns, 4 Laser, 25 meters (Rifles and Pistos), 100
                                        meters, 2 lanes (Rifles and Psitols), 2 VIP 100 Meters, Rifles and Pitols,
                                        Retail,
                                        VIP Lounge, Two Cafetrias
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-4 mb-4">
                <div class="card single-promo-card single-promo-hover text-center p-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-auto mb-3 mb-sm-0">
                                <span class="icon-token icon-size-lg"></span>
                            </div>
                            <div class="col-12 col-sm text-left pl-sm-0">

                                <h5>VIP Range and Lounge </h5>
                                <div class="single-promo-desc">
                                    <p class="mb-0">Our most impressive VIP LOUNGE , this lounge presents unparalleled
                                        views
                                        of the ranges and VIP Lanes . Entertain VIP guests in luxury envirnoment
                                        including a
                                        truly spectacular master retreat. Offering, personl trainer, drinks and
                                        refreshments
                                        with snacks, locers and relaxing area
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <a href="javascript:void(0)" class="btn btn-brand-01">Tournaments Fees and Registrations, T&C</a>
            </div>
        </div>
    </div>
</section>
<!--------------------- End For Home About Section ---------------------->


<!--------------------- Start For Home Special Package Section ---------------------->
<section class="section-spacing section-support section-opacity homepackage-section" style="background-image: url(assets/images/day-hours-bg.jpg)">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="section-heading text-center">
                    <h2 class="title text-white notranslate lan-specialpackage-menu">Special Packages </h2>
                </div>
            </div>
        </div>
        <div class="row" id="homepackagedatadiv">
            
            
        </div>
    </div>
</section>
<!--------------------- End For Home Special Package Section ---------------------->


<!--------------------- Start For Home Gallery Section ---------------------->
<section class="gallery-section homegallery-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel owl-theme gallery-slider owl-custom-nav dot-bottom-center custom-dot" id="homegallerydatadiv">
                    
                    
                </div>
            </div>
        </div>
    </div>
</section>
<!--------------------- End For Home Gallery Section ---------------------->



<!--------------------- Start For Home Equipment Section ---------------------->
<section class="our-equipment-section section-spacing homeequipment-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading text-center mb-0">
                    <h2 class="title mb-0 notranslate lan-ourequipment-menu">Our Equipment</h2>
                </div>
            </div>
            <div class="col-12 px-0">
                <div class="owl-carousel owl-theme our-equipment-slider owl-custom-nav dot-bottom-center custom-dot" id="homeequipmentdatadiv">
                    
                    
                </div>
            </div>
        </div>
    </div>
</section>
<!--------------------- End For Home Equipment Section ---------------------->

<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script>
    $(window).on('load', function(){
    	setTimeout(function(){
    		$('html, body').animate({
    			scrollTop: $('#videosection').offset().top - 80
    		}, 2000);
    	}, 2000);

    })
    //setTimeout(function(){window.location.hash = '#videosection';},3000);

    $(document).ready(function() {
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.lihome").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.lihome").addClass("active");
        const todayDate = new Date();
        new When({
            input: document.getElementById('datecalendarinput'),
            container: document.getElementById('datecalendar'),
            singleDate: true,
            startDate: todayDate,
            minDate: todayDate,
            showHeader: false,
            outputFormat: "DD/MM/YYYY",
            inline: true
        });
        


        //List Home Video Data
        listhomevideodata();

        //List Home Package Data
        listhomepackages();

        //List Home Gallery Data
        listhomegallerydata();

        //List Equipment Data
        listhomeequipmentdata();

        // List About Us Data
        listaboutuscount();

    });

    //Click Event Of Date on Calendar
    $('body').on('click','.home-calendar #datecalendar .day',function(){
        setTimeout(function(){ 
            var ismemberlogin = '<?php echo $ismemberlogin ?>';

            if(ismemberlogin == 1)
            {
                var caldate = $('.home-calendar #datecalendarinput').val();

                var pagename = 'rangebooking';
                var lastcurrurl = pagename+'?date='+caldate;

                currentXhr.abort();
                render(pagename);
                window.history.pushState(pagename, 'Title', dirpath + lastcurrurl);
            }
            
        }, 300);
    });


    /**************************** Start For List Packages *****************************/
    function listhomepackages()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listpackages");
        formdata.append("showonhome", 1);
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'specialpackages',formdata,headersdata,Onsuccesslisthomepackages,OnErrorlisthomepackages);
    }

    function Onsuccesslisthomepackages(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        $('.homepackage-section').addClass('d-none');

        var htmldata="";
        if(resultdata.status==0)
        {
           
        }
        else if(resultdata.status==1)
        {
            if(resultdata.ispackagedata > 0)
            {
                $('.homepackage-section').removeClass('d-none');

                for(var i in resultdata.packagedata)
                {
                    htmldata+='<div class="col-lg-6 col-md-6 col-sm-12 mb-4">';
                    htmldata+='<div class="popular-price bg-white pricing-new-wrapper p-4 shadow-sm border h-100">';
                    htmldata+='<div class="pricing-price d-flex justify-content-between align-items-center pb-4">';
                    if(resultdata.packagedata[i].iconimg)
                    {
                        htmldata+='<img class="p-icon" src="'+resultdata.packagedata[i].iconimg+'">';
                    }
                    else
                    {
                        htmldata+='<span class="p-icon"></span>';
                    }
                    htmldata+='<div class="h2 mb-0 pm-price">';
                    htmldata+='Qr '+resultdata.packagedata[i].price+' <span class="price-cycle h5">/'+resultdata.packagedata[i].durationname+'</span>';
                    if(resultdata.packagedata[i].igst > 0)
                    {
                        htmldata+='<small class="price-tax">('+resultdata.packagedata[i].igst+' % '+resultdata.packagedata[i].taxtypename+')</small>';
                    }
                    htmldata+='</div>';
                    htmldata+='</div>';
                    htmldata+='<div class="pricing-info">';
                    htmldata+='<h5>'+resultdata.packagedata[i].itemname+'</h5>';
                    htmldata+='</div>';
                    htmldata+='<div class="pricing-content mt-3">';
                    htmldata+='<ul class="list-unstyled pricing-feature-list-2">';

                    for(var j in resultdata.packagedata[i].attributedetail)
                    {
                        htmldata+='<li><img class="iconimg" src="'+resultdata.packagedata[i].attributedetail[j].iconimg+'" />';
                        htmldata+='<p>'+resultdata.packagedata[i].attributedetail[j].name+'</p>';
                        htmldata+='</li>';

                        if(parseInt(j) == 4)
                        {
                            break;
                        }
                    }

                    htmldata+='</ul>';
                    htmldata+='<a href="javascript:void(0);" class="mb-3 d-block clsfullspecification" data-id="'+resultdata.packagedata[i].id+'">Full specifications <i class="far fa-arrow-right pl-2"></i></a>';
                    htmldata+='<a href="javascript:void(0)" class="btn btn-brand-01 btn-block btnaddtocart" data-isbuynow="0" data-type="2" data-id="'+resultdata.packagedata[i].id+'">Add to Cart</a>';
                    htmldata+='</div>';
                    htmldata+='</div>';
                    htmldata+='</div>';

                }
            }
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        }

        $('#homepackagedatadiv').html(htmldata);


        //Click Event Of Full Specification
        $('.clsfullspecification').on('click',function(){
            var id = $(this).attr('data-id');

            //Full Details
            listhomepackagesdetail(id);
        });
 
    }
    
    function OnErrorlisthomepackages(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End For List Packages *****************************/



    /**************************** Start For Package Detail *****************************/
    function listhomepackagesdetail(id)
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};
        formdata = new FormData();
        formdata.append("action", "listpackages");
        formdata.append("id", id);
        formdata.append("showonhome", 1);
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'specialpackages',formdata,headersdata,Onsuccesslisthomepackagesdetail,OnErrorlisthomepackagesdetail);
    }

    function Onsuccesslisthomepackagesdetail(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        var htmldata="";
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {
            if(resultdata.ispackagedata > 0)
            {
                for(var i in resultdata.packagedata)
                {
                    htmldata+='<div class="pricing-price d-flex justify-content-between align-items-center pb-4">';
                    if(resultdata.packagedata[i].iconimg)
                    {
                        htmldata+='<img class="p-icon" src="'+resultdata.packagedata[i].iconimg+'">';
                    }
                    else
                    {
                        htmldata+='<span class="p-icon"></span>';
                    }
                    htmldata+='<div class="h2 mb-0 pm-price">';
                    htmldata+='Qr '+resultdata.packagedata[i].price+' <span class="price-cycle h5">/'+resultdata.packagedata[i].durationname+'</span>';
                    if(resultdata.packagedata[i].igst > 0)
                    {
                        htmldata+='<small class="price-tax">('+resultdata.packagedata[i].igst+' % '+resultdata.packagedata[i].taxtypename+')</small>';
                    }
                    htmldata+='</div>';
                    htmldata+='</div>';
                    htmldata+='<div class="pricing-info">';
                    htmldata+='<h5>'+resultdata.packagedata[i].itemname+'</h5>';
                    htmldata+='</div>';
                    htmldata+='<div class="pricing-content mt-3">';
                    htmldata+='<ul class="list-unstyled pricing-feature-list-2">';
                            
                        for(var j in resultdata.packagedata[i].attributedetail)
                        {
                            htmldata+='<li><img class="iconimg" src="'+resultdata.packagedata[i].attributedetail[j].iconimg+'" />';
                            htmldata+='<p>'+resultdata.packagedata[i].attributedetail[j].name+'</p>';
                            htmldata+='</li>';
                        }
                            
                    htmldata+='</ul>';
                    htmldata+='<a href="javascript:void(0)" class="btn btn-outline-brand-01 btn-block mt-3 btnaddtocart" data-isbuynow="1" data-type="2" data-id="'+resultdata.packagedata[i].id+'">Buy Now</a>';
                    htmldata+='</div>';

                }
            }
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        }

        $('#fullspecificationModal #fullspecificationdata').html(htmldata);
        $('#fullspecificationModal').modal('show');
 
    }
    
    function OnErrorlisthomepackagesdetail(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End For Package Detail *****************************/



    /**************************** Start List Home Gallery Section *****************************/
    function listhomegallerydata()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listgallerydata");
        formdata.append("showonhome", 1);
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'gallery',formdata,headersdata,Onsuccesslisthomegallerydata,OnErrorlisthomegallerydata);
    }

    function Onsuccesslisthomegallerydata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        $('.homegallery-section').addClass('d-none');

        var htmldata="";
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {
            if(resultdata.isgalleryimgdata > 0)
            {
                $('.homegallery-section').removeClass('d-none');

                for(var i in resultdata.galleryimgdata)
                {
                    htmldata+='<div class="gallery-item mt-3">';
                    htmldata+='<a href="javascript:void(0);">';
                    htmldata+='<figure class="gallery-img">';
                    htmldata+='<img src="'+resultdata.galleryimgdata[i].img+'" alt="'+resultdata.galleryimgdata[i].title+'" class="img-fluid" />';
                    htmldata+='</figure>';
                    htmldata+='<div class="gallery-text">'+resultdata.galleryimgdata[i].title+'</div>';
                    htmldata+='</a>';
                    htmldata+='</div>';
                }
            }
            
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        }

        $('#homegallerydatadiv').html(htmldata);


        /************* Start For Gallery Slider Call ***********/
        $('.gallery-slider').owlCarousel({
            loop: true,
            nav: true,
            dots: false,
            margin: 15,
            autoplay: true,
            center: true,
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 2
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 3
                }
            }
        });
        /************* End For Gallery Slider Call ***********/
       
    }
    
    function OnErrorlisthomegallerydata(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End List Home Gallery Section *****************************/




    /**************************** Start List Home Equipment Section *****************************/
    function listhomeequipmentdata()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listequipmentdata");
        formdata.append("showonhome", '%');
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'home',formdata,headersdata,Onsuccesslisthomeequipmentdata,OnErrorlisthomeequipmentdata);
    }

    function Onsuccesslisthomeequipmentdata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        $('.homeequipment-section').addClass('d-none');

        var htmldata="";
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {
            if(resultdata.isequipmentdata > 0)
            {
                $('.homeequipment-section').removeClass('d-none');

                for(var i in resultdata.equipmentdata)
                {
                    htmldata+='<div class="our-equipment-article">';
                    htmldata+='<a class="d-block" href="javascript:void(0);">';
                    htmldata+='<div class="our-equipment-img">';
                    htmldata+='<figure style="background-image: url('+resultdata.equipmentdata[i].img+');">';
                    htmldata+='<img src="'+resultdata.equipmentdata[i].img+'" class="rounded-top img-fluid" alt="'+resultdata.equipmentdata[i].name+'">';
                    htmldata+='</figure>';
                    htmldata+='</div>';
                    htmldata+='<div class="our-equipment-content">';
                    htmldata+='<div class="our-equipment-heading">';
                    htmldata+='<h3>'+resultdata.equipmentdata[i].name+'</h3>';
                    htmldata+='</div>';
                    htmldata+='</div>';
                    htmldata+='</a>';
                    htmldata+='</div>';

                }
            }
            
        }

        $('#homeequipmentdatadiv').html(htmldata);


        /************* Start For Equipment Slider Call ***********/
        $('.our-equipment-slider').owlCarousel({
            loop: true,
            nav: true,
            dots: false,
            margin: 0,
            autoplay: true,
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 2
                },
                1200: {
                    items: 3
                }
            }
        });
        /************* End For Equipment Slider Call ***********/
       
    }
    
    function OnErrorlisthomeequipmentdata(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End List Home Equipment Section *****************************/



    /**************************** Start List Home Video Section *****************************/
    function listhomevideodata()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listvideodata");
        formdata.append("showonhome", 1);
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'home',formdata,headersdata,Onsuccesslisthomevideodata,OnErrorlisthomevideodata);
    }

    function Onsuccesslisthomevideodata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        $('.homevideo-section').addClass('d-none');

        var htmldata="";
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {
            if(resultdata.isvideodata > 0)
            {
                $('.homevideo-section').removeClass('d-none');

                for(var i in resultdata.videodata)
                {   
                    htmldata+='<div class="col-12 col-md-6 video-content mb-4">';
                    htmldata+='<div class="card">';
                    htmldata+='<div class="card-body">';
                    htmldata+='<div class="row">';
                    htmldata+='<div class="col-12">';
                    htmldata+='<iframe width="100%" height="315" src="https://www.youtube.com/embed/'+resultdata.videodata[i].videoid+'" data-id="'+resultdata.videodata[i].id+'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'; 
                    htmldata+='</div>';
                    htmldata+='</div>';
                    htmldata+='</div>';
                    htmldata+='</div>';
                    htmldata+='</div>';

                }
            }
            
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        }

        $('#homevideodatadiv').html(htmldata);
    }
    
    function OnErrorlisthomevideodata(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** Start List Home Video Section *****************************/

     /**************************** Start For About Us *****************************/
     function listaboutuscount()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listaboutusdata");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'aboutus',formdata,headersdata,Onsuccesslistaboutuscount,OnErrorlistaboutuscount);
    }

    function Onsuccesslistaboutuscount(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        var htmldata="";
        var tbldata="";
        if(resultdata.status==0)
        {
        }
        else if(resultdata.status==1)
        {
            for(var j in resultdata.abtdata)
            {   
                if(resultdata.abtdata[j].contenttypeid == '<?php echo $config->getAboutUsId(); ?>')
                {
                    htmldata+='<div class="section-heading text-center">';
                    htmldata+='<h2 class="title">'+resultdata.abtdata[j].title+' '+resultdata.abtdata[j].title2+'</h2>';
                    htmldata+='<p class="promo-desc">'+resultdata.abtdata[j].description+'</p>';
                    htmldata+='</div>';
                    for(var i in resultdata.abtdata[j].aboutusdetail)
                    {
                        if(resultdata.abtdata[j].aboutusdetail[i].type == 1)
                        {
                            tbldata+='<div class="col-12 col-md-12 col-lg-4 mb-4">';
                            tbldata+='<div class="card single-promo-card single-promo-hover text-center p-0">';
                            tbldata+='<div class="card-body">';
                            tbldata+='<div class="row">';
                            tbldata+='<div class="col-12 col-sm-auto mb-3 mb-sm-0">';
                            tbldata+='<img class="iconimg" height="50" width="50" src="'+resultdata.abtdata[j].aboutusdetail[i].iconimg+'">';
                            tbldata+='</div>';
                            tbldata+='<div class="col-12 col-sm text-left pl-sm-0">';
                            tbldata+='<h5>'+resultdata.abtdata[j].aboutusdetail[i].title+'</h5>';
                            tbldata+='<p class="mb-0">'+resultdata.abtdata[j].aboutusdetail[i].descr+'</p>';
                            tbldata+='</div>';
                            tbldata+='</div>';
                            tbldata+='</div>';
                            tbldata+='</div>';
                            tbldata+='</div>';
                        }
                    }
                    $('#aboutus-detail').html(tbldata);
                    $('#aboutus').html(htmldata);
                }
            }
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        }  
       
    }
    
    function OnErrorlistaboutuscount(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End For About Us *****************************/



</script>