<?php require_once dirname(__DIR__, 1).'\config\config.php';
$config = new config();
?>
<!--page header section start-->
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="breadcrumb-list notranslate">
                    <li><a href="javascript:void(0)" class="clspageparentmenu lan-home-menu" pagename="home">Home</a></li>
                    <li class="active lan-aboutus-menu">About us</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->

<section class="flat-row  bg-section2">
    <div class="container">
        <div class="row" id="about-us">
            
        </div>
    </div>

</section>

<section class="about-single-promo mt-5">
    <div class="container">
        <div class="row" id="aboutus-detail">
         
        </div>
    </div>
</section>



<section class="mission-vission-sec">
    <div class="container">
        <div class="row" id="missionvission">
            
            <!-- <div class="col-lg-4 col-md-12">
                <div class="our-mission-article">
                    <a class="d-block" href="javascript:void(0);">
                        <div class="our-mission-img mt-3">
                            <figure>
                                <img src="assets/images/vision.png" class="rounded-top img-fluid" alt="img">
                            </figure>
                        </div>
                        <div class="our-mission-content">
                            <div class="our-mission-heading">
                                <h3>vission</h3>
                            </div>
                            <div class="our-mission-text">
                                <p>To become a leader in the field of Shooting Range by providing service and products in an innovative and contemporary ways through the latest technologies while contributing significantly to the economic and social growth in the State of Qatar</p>
                            </div>
                        </div>
                    </a>
                </div>


            </div>
            <div class="col-lg-4 col-md-12">
                <div class="our-mission-article">
                    <a class="d-block" href="javascript:void(0);">
                        <div class="our-mission-img mt-3">
                            <figure>
                                <img src="assets/images/values.png" class="rounded-top img-fluid" alt="img">
                            </figure>
                        </div>
                        <div class="our-mission-content">
                            <div class="our-mission-heading">
                                <h3>values</h3>
                            </div>
                            <div class="our-mission-text">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam dolores
                                    tenetur, facere autem.</p>
                            </div>
                        </div>
                    </a>
                </div>

            </div> -->
        </div>
    </div>
</section>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery.countTo.js"></script>
<script>
$(document).ready(function() {
    $("ul.main-navbar-nav li.nav-item").removeClass("active");
    $("ul.main-navbar-nav li.liaboutus").addClass("active");
    $(".mobile-navigation li").removeClass("active");
    $(".mobile-navigation li.liaboutus").addClass("active");
    listaboutuscount();
});
var counter = function() {
    $('.flat-counter').on('on-appear', function() {
        $(this).find('.numb-count').each(function() {
            var to = parseInt(($(this).attr('data-to')), 10),
                speed = parseInt(($(this).attr('data-speed')), 10);
            if ($().countTo) {
                $(this).countTo({
                    to: to,
                    speed: speed
                });
            }
        });
    });
};

counter();

     /**************************** Start For About Count *****************************/
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
        var tblabtdata = "";
        if(resultdata.status==0)
        {
        }
        else if(resultdata.status==1)
        {
            for(var j in resultdata.abtdata)
            {   
                if(resultdata.abtdata[j].contenttypeid == '<?php echo $config->getAboutUsId(); ?>')
                {
                    htmldata+='<div class="col-12 col-lg-5 col-xl-6 featured-misvis-bg" style="background-image: url('+resultdata.abtdata[j].img+');">';
                    htmldata+='<div class="featured-misvis d-lg-none">';
                    htmldata+='<img src="'+resultdata.abtdata[j].img+'" alt="image">';
                    htmldata+='</div>';
                    htmldata+='</div>';
                    htmldata+='<div class="col-12 col-lg-7 col-xl-6 ml-auto pl-lg-5">';
                    htmldata+='<div class="info-misvis">';
                    htmldata+='<div class="title-section style2 left">';
                    htmldata+='<h1 class="title">'+resultdata.abtdata[j].title+'<span> '+resultdata.abtdata[j].title2+'</span></h1>';
                    htmldata+='<div class="info-misvis-text" id="aboutusText">'+resultdata.abtdata[j].description+'</div>';
                    htmldata+='</div>';
                    htmldata+='<div class="about-counter" id="about-counter">';
                    for(var i in resultdata.abtdata[j].aboutusdetail)
                    {
                        if(resultdata.abtdata[j].aboutusdetail[i].type == 2)
                        {
                            htmldata+='<div class="flat-counter float-left color-another text-center">';
                            htmldata+='<div class="content-counter float-left">';
                            htmldata+='<div class="icon-count">';
                            htmldata+='<img class="iconimg" width="50" height="50" src="'+resultdata.abtdata[j].aboutusdetail[i].iconimg+'">';
                            htmldata+='</div>';
                            htmldata+='<div class="numb-count" data-to="'+resultdata.abtdata[j].aboutusdetail[i].abtcount+'" data-speed="2000" data-waypoint-active="yes">'+resultdata.abtdata[j].aboutusdetail[i].abtcount;
                            htmldata+='</div>';
                            htmldata+='<div class="name-count">'+resultdata.abtdata[j].aboutusdetail[i].title+'</div>';
                            htmldata+='</div>';
                            htmldata+='</div>';
                        }
                        else if(resultdata.abtdata[j].aboutusdetail[i].type == 1)
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
                    htmldata+='</div>';
                    htmldata+='</div>';
                    htmldata+='</div>';
                   
                }
                else
                {
                        tblabtdata+='<div class=" col-lg-4 col-md-12">';
                            tblabtdata+='<div class="our-mission-article">';
                                tblabtdata+='<a class="d-block" href="javascript:void(0);">';
                                        tblabtdata+='<div class="our-mission-img mt-3">';
                                            tblabtdata+='<figure class="mission-img-figure">';
                                                tblabtdata+='<img src="'+resultdata.abtdata[j].img+'" class="rounded-top img-fluid" alt="'+resultdata.abtdata[j].title+'">';
                                            tblabtdata+='</figure>';
                                        tblabtdata+='</div>';
                                        tblabtdata+='<div class="our-mission-content">';
                                            tblabtdata+='<div class="our-mission-heading">';
                                                tblabtdata+='<h3>'+resultdata.abtdata[j].title+'</h3>';
                                            tblabtdata+='</div>';
                                            tblabtdata+='<div class="our-mission-text">';
                                                tblabtdata+='<p>'+resultdata.abtdata[j].description+'</p>';
                                            tblabtdata+='</div>';
                                        tblabtdata+='</div>';
                                tblabtdata+='</a>';
                         tblabtdata+='</div>';
                    tblabtdata+='</div>';
                }
            }
            $('#about-us').html(htmldata);
            $('#missionvission').html(tblabtdata);
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
    /**************************** End For About Counts *****************************/

</script>