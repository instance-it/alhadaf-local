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
                    <li class="active lan-gallery-menu">Gallery</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing">
    <div class="container">
        <div class="row" id="gallerypagecategory">
            
        </div>
        <div class="row justify-content-center portfolio_gallery ps-gallery-image" id="gallerypageimage">
            
        </div>
    </div>
</section>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script>
    $(document).ready(function(){
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.ligallery").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.ligallery").addClass("active");

        listgallerycategory();
        listgallerydata();
    });

    
    /**************************** Start List Gallery Category *****************************/
    function listgallerycategory()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listgallerycategory");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'gallery',formdata,headersdata,Onsuccesslistgallerycategory,OnErrorlistgallerycategory);
    }

    function Onsuccesslistgallerycategory(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        var htmldata="";
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {
            if(resultdata.isgallerycatdata > 0)
            {
                htmldata+='<div class="col-12 portfolio_button mb-4">';
                htmldata+='<button class="btn btn-outline-brand-01 mr-1 active" data-filter="*">All</button>';
                for(var i in resultdata.gallerycatdata)
                {
                    htmldata+='<button class="btn btn-outline-brand-01 mr-1" data-filter=".'+resultdata.gallerycatdata[i].id+'">'+resultdata.gallerycatdata[i].category+'</button>';
                }
                htmldata+='</div>';
            }
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 

        $('#gallerypagecategory').html(htmldata);
 
    }
    
    function OnErrorlistgallerycategory(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End List Gallery Category *****************************/


    /**************************** Start List Gallery Image *****************************/
    function listgallerydata()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listgallerydata");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'gallery',formdata,headersdata,Onsuccesslistgallerydata,OnErrorlistgallerydata);
    }

    function Onsuccesslistgallerydata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        var htmldata="";
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {

            if(resultdata.isgalleryimgdata > 0)
            {
                for(var i in resultdata.galleryimgdata)
                {
                    htmldata+='<div class="col-12 col-md-6 col-lg-4 mb-4 gird_item gallery1-tab '+resultdata.galleryimgdata[i].categoryid+'">';
                    htmldata+='<div class="gallery">';
                    htmldata+='<a href="'+resultdata.galleryimgdata[i].img+'" title="'+resultdata.galleryimgdata[i].title+'" class="ps-gallery-item">';
                    htmldata+='<img class="img-fluid" src="'+resultdata.galleryimgdata[i].img+'" alt="'+resultdata.galleryimgdata[i].title+'">';
                    htmldata+='<span class="overlay"><i class="fal fa-expand-arrows" aria-hidden="true"></i></span>';
                    htmldata+='</a>';
                    htmldata+='</div>';
                    htmldata+='</div>';
                }
            }
            
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 

        $('#gallerypageimage').html(htmldata);

        $('.ps-gallery-image').lightGallery({
            selector: '.ps-gallery-item',
            thumbnail: true,
            share: false,
            fullScreen: false,
            autoplay: false,
            autoplayControls: false,
            actualSize: false
        });


        $('.portfolio_gallery').imagesLoaded( function() {
            // init Isotope
            var $grid = $('.portfolio_gallery').isotope({
            itemSelector: '.gird_item',
                percentPosition: true,
                masonry: {
                    columnWidth: '.gird_item'
                }
            });
                
            // filter items on button click
            $('.portfolio_button').on( 'click', 'button', function() {
                var filterValue = $(this).attr('data-filter');
                $grid.isotope({ filter: filterValue });
                    
                $(this).siblings('.active').removeClass('active');
                $(this).addClass('active');
            });
        
        });
       
    }
    
    function OnErrorlistgallerydata(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End List Gallery Image *****************************/

</script>