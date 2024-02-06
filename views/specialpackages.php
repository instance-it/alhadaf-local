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
                    <li class="active lan-specialpackage-menu">Special Packages</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->

<!--pricing section start-->
<section id="pricing" class="pricing-section section-spacing section-support section-opacity" style="background-image: url(assets/images/day-hours-bg.jpg);">
    <div class="container">
        
        <div class="row" id="packagedatadiv">

            
            <!-- <div class="col-12">
                <div class="support-cta text-center">
                    <a href="#" data-toggle="modal" data-target="#modalComparePlans">
                        <h5 class="mb-1 text-white" ><span class="ti-loop color-primary mr-2"></span>Compare Plans</h5>
                    </a>
                </div>
            </div> -->

        </div>


        <div class="row">
            <div class="col-12">
                <div class="support-cta text-center">
                    <a href="javascript:void(0)" id="btnviewcomparepackage">
                        <h5 class="mb-1 text-white" ><span class="ti-loop color-primary mr-2"></span>Compare Package</h5>
                    </a>
                </div>
            </div>
        </div> 
           
    </div>
</section>
<!--pricing section end-->

<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script>
    $(document).ready(function() {
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.lispecialpackages").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.lispecialpackages").addClass("active");

        //List Package Data
        listpackages();
    });


    /**************************** Start For List Packages *****************************/
    function listpackages()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listpackages");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistpackages,OnErrorlistpackages);
    }

    function Onsuccesslistpackages(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        var htmldata="";
        if(resultdata.status==0)
        {
            htmldata+='<div class="col-12">';
            htmldata+='<div class="not-found-content text-center">';
            htmldata+='<div class="not-found-img">';
            htmldata+='<img class="img-fluid" src="<?php echo $config->getImageurl(); ?>images/not-found.png" alt="img" />';
            htmldata+='</div>';
            htmldata+='<div class="not-found-text">';
            htmldata+='<h3 class="text-white">'+resultdata.message+'</h3>';
            htmldata+='<a href="javascript:void(0)" class="clshomepage" pagename="home">';
            htmldata+='<h5 class="mb-1 text-white" ><span class="ti-loop color-primary mr-2"></span>Back to Home</h5>';
            htmldata+='</a>';
            htmldata+='</div>';
            htmldata+='</div>';
            htmldata+='</div>';
        }
        else if(resultdata.status==1)
        {
            if(resultdata.ispackagedata > 0)
            {
                for(var i in resultdata.packagedata)
                {
                    htmldata+='<div class="col-lg-4 col-md-6 col-sm-12 mb-4">';
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
            else
            {
                
                htmldata+='<div class="col-12">';
                htmldata+='<div class="not-found-content text-center">';
                htmldata+='<div class="not-found-img">';
                htmldata+='<img class="img-fluid" src="<?php echo $config->getImageurl(); ?>images/not-found.png" alt="img" />';
                htmldata+='</div>';
                htmldata+='<div class="not-found-text">';
                htmldata+='<h3 class="text-white">'+resultdata.message+'</h3>';
                htmldata+='<a href="javascript:void(0)" class="clshomepage" pagename="home">';
                htmldata+='<h5 class="mb-1 text-white" ><span class="ti-loop color-primary mr-2"></span>Back to Home</h5>';
                htmldata+='</a>';
                htmldata+='</div>';
                htmldata+='</div>';
                htmldata+='</div>';
            }
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 

        $('#packagedatadiv').html(htmldata);


        //Click Event Of Full Specification
        $('.clsfullspecification').on('click',function(){
            var id = $(this).attr('data-id');

            //Full Details
            listpackagesdetail(id);
        });


        //Click Event Of Back To Home Page
        $('.clshomepage').on('click',function(){
            var pagename = $(this).attr('pagename');

            currentXhr.abort();
            render(pagename);
            window.history.pushState(pagename, 'Title', dirpath + pagename);
        });
 
    }
    
    function OnErrorlistpackages(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End For List Packages *****************************/



    /**************************** Start For Package Detail *****************************/
    function listpackagesdetail(id)
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};
        formdata = new FormData();
        formdata.append("action", "listpackages");
        formdata.append("id", id);
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistpackagesdetail,OnErrorlistpackagesdetail);
    }

    function Onsuccesslistpackagesdetail(content)
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
                            htmldata+='<p>';
                            if(resultdata.packagedata[i].attributedetail[j].attributename)
                            {
                                htmldata+='<b>'+resultdata.packagedata[i].attributedetail[j].attributename+' : </b>';
                            }
                            htmldata+=resultdata.packagedata[i].attributedetail[j].name;
                            htmldata+='</p>';
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
    
    function OnErrorlistpackagesdetail(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End For Package Detail *****************************/


    /**************************** Start For Compare Plan *****************************/
    //Click Event Of View Compare Plan
    $('#btnviewcomparepackage').on('click',function(){
        listcomparepkgdetail();
    });


    function listcomparepkgdetail()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};
        formdata = new FormData();
        formdata.append("action", "listcomparepkgdetail");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistcomparepkgdetail,OnErrorlistcomparepkgdetail);
    }

    function Onsuccesslistcomparepkgdetail(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        var htmldata="";
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {
            var comparisondata = resultdata.comparisondata;

            $('#modalComparePlans #compareplanData').html(comparisondata);
            $('#modalComparePlans .modal-title').text('Compare Package');
            $('#modalComparePlans').modal('show');
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 
    }
    
    function OnErrorlistcomparepkgdetail(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }

    /**************************** End For Compare Plan *****************************/


</script>