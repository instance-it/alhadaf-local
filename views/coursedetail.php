<?php require_once dirname(__DIR__, 1).'\config\config.php';
$config = new config();
?>
<!--page header section start-->
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="breadcrumb-list">
                    <li><a href="javascript:void(0)" class="clspageparentmenu notranslate lan-home-menu" pagename="home">Home</a></li>
                    <li><a href="javascript:void(0)" class="clspageparentmenu notranslate lan-course-menu" pagename="courses">Courses</a></li>
                    <li class="active course-name">Kids Airgun</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing courses-details-section">
    <div class="container">
        <div class="row">

            <div class="col-md-8 col-lg-8 mb-4 leftblock-section">
                <div class="row">
                    <div class="col-12">
                        <h3 class="course-name">Kids Airgun</h3>
                    </div>
                    <div class="col-12">
                        <div class="row mb-3">
                            <div class="col-12">

                                <!---------------------- Start For Image Section ------------------------>
                                <div class="owl-carousel owl-theme courses-details-slider arrow-rightbottom owl-custom-nav dot-bottom-center custom-dot" id="courseimageDiv">
                                    <div class="courses-item">
                                        <figure class="courses-img">
                                            <img src="assets/images/gallery/gallery_02.jpg" alt="logo" class="img-fluid" />
                                        </figure>
                                    </div>
                                    <div class="courses-item">
                                        <figure class="courses-img">
                                            <img src="assets/images/gallery/gallery_03.jpg" alt="logo" class="img-fluid" />
                                        </figure>
                                    </div>
                                    <div class="courses-item">
                                        <figure class="courses-img">
                                            <img src="assets/images/gallery/gallery-defualt.jpg" alt="logo" class="img-fluid" />
                                        </figure>
                                    </div>
                                </div>
                                <!---------------------- End For Image Section ------------------------>

                            </div>

                        </div>
                        <!-- info Start -->
                        <div class="row mb-3 course-descr-section">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <!---------------------- Start For Course Description Section ------------------------>
                                        <div class="row col-12" id="coursedescription">


                                            

                                        </div>
                                        <!---------------------- End For Course Description Section ------------------------>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- info End -->
                        <!-- Curriculum Start -->
                        <div class="row mb-3 coursebenefit-section">
                            <div class="col-12">
                                <h3>Course Curriculum</h3>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row accordion" id="accordionCurriculum">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <a href="javascript:void(0);" class="" data-toggle="collapse" data-target="#collapseCurriculum1" aria-expanded="true">
                                                            <!-- <span>Item 1</span> -->
                                                            <h4>Benefits</h4>
                                                        </a>
                                                    </div>
                                                    <div class="card-body collapse show" id="collapseCurriculum1" data-parent="#accordionCurriculum" style="">
                                                        <div class="discription">
                                                            <ul class="course-curriculum-list coursebenefitdata">
                                                                
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Curriculum End -->
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 mb-4 rightblock-section">
                <div class="row content-sticky">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-12 cart-header hourstudentDiv">
                                        <div class="row">
                                            <div class="col-5">
                                                <h4 class="durationhourdata"></h4>
                                            </div>
                                            <div class="col-7 text-right">
                                                <h4 class="noofstudentdata"></h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 px-0">
                                        <ul class="cart-list coursedisplaydata" >
                                            
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <a href="javascript:void(0)" data-isbuynow="0" data-type="3" class="btn btn-brand-01 w-100 btnaddtocart">Enroll Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script>
    $(document).ready(function () {
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.licourse").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.licourse").addClass("active");

        //List Course Details Data
        listcoursedetails();
    });
    
    // $('.courses-details-slider').owlCarousel({
    //     loop: true,
    //     nav: true,
    //     dots: false,
    //     margin: 0,
    //     autoplay: true,
    //     items: 1
    // });

    /**************************** Start For List Courses Details *****************************/
    function listcoursedetails()
    {
        var pagename=getpagename();
        pagenamearry = pagename.split("-");
        var itemunqid = pagenamearry[1];

        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};
        formdata = new FormData();
        formdata.append("action", "listcoursedetails");
        formdata.append("itemunqid", itemunqid);
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'coursedetail',formdata,headersdata,Onsuccesslistcoursedetails,OnErrorlistcoursedetails);
    }

    function Onsuccesslistcoursedetails(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        $('.course-descr-section').addClass('d-none');
        $('.coursebenefit-section').addClass('d-none');

        $('.rightblock-section .durationhourdata').html('');
        $('.rightblock-section .noofstudentdata').html('');

        var courseimagedata="";
        var right_coursedisplaydata='';
        var coursebenefitdata='';
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {
            if(resultdata.iscoursedata > 0)
            {
                var i = 0; 

                $('.course-name').text(resultdata.coursedata[i].itemname);
                if(resultdata.coursedata[i].descr)
                {
                    $('#coursedescription').html(resultdata.coursedata[i].descr);
                    $('.course-descr-section').removeClass('d-none');
                }
                else
                {
                    $('#coursedescription').html('');
                }
                
                $('.btnaddtocart').attr('data-id',resultdata.coursedata[i].id);
                
                /********************* Strat For Course Image *******************/
                var courseimglen = (resultdata.coursedata[i].courseimage).length;
                
                if(parseInt(courseimglen) > 0)
                {
                    for(var k in resultdata.coursedata[i].courseimage)
                    {   
                        courseimagedata+='<div class="courses-item">';
                        courseimagedata+='<figure class="courses-img">';
                        courseimagedata+='<img src="'+resultdata.coursedata[i].courseimage[k].courseimg+'" alt="'+resultdata.coursedata[i].itemname+'" class="img-fluid" />';
                        courseimagedata+='</figure>';
                        courseimagedata+='</div>';
                    }
                }
                else
                {
                    courseimagedata+='<div class="courses-item">';
                    courseimagedata+='<figure class="courses-img">';
                    courseimagedata+='<img src="'+resultdata.coursedata[i].defaultcourseimg+'" alt="'+resultdata.coursedata[i].itemname+'" class="img-fluid" />';
                    courseimagedata+='</figure>';
                    courseimagedata+='</div>';
                }
                /********************* End For Course Image *******************/


                /********************* Start For Right Block Section *******************/
                if(resultdata.coursedata[i].duration > 0 || resultdata.coursedata[i].noofstudent > 0)
                {
                    $('.hourstudentDiv').removeClass('d-none');
                }
                else
                {
                    $('.hourstudentDiv').addClass('d-none');
                }
                $('.rightblock-section .durationhourdata').html(resultdata.coursedata[i].duration+' <small>'+resultdata.coursedata[i].strduration+'</small>');
                $('.rightblock-section .noofstudentdata').html(resultdata.coursedata[i].noofstudent+' <small>'+resultdata.coursedata[i].strnoofstudent+'</small>');

                for(var k in resultdata.coursedata[i].coursedisplaydata)
                {   
                    right_coursedisplaydata+='<li class="cart-item"><img class="iconimg" src="'+resultdata.coursedata[i].coursedisplaydata[k].iconimg+'"> <span>'+resultdata.coursedata[i].coursedisplaydata[k].name+'</span></li>';
                }
                /********************* End For Right Block Section *******************/


                /********************* Start For Course Benefit Section *******************/
                for(var k in resultdata.coursedata[i].coursebenefit)
                {  
                    $('.coursebenefit-section').removeClass('d-none'); 
                    coursebenefitdata+='<li><img class="iconimg" src="'+resultdata.coursedata[i].coursebenefit[k].iconimg+'">'+resultdata.coursedata[i].coursebenefit[k].name+'<span class="ml-auto">'+resultdata.coursedata[i].coursebenefit[k].rowdurationname+'</span></li>';
                }
                /********************* End For Course Benefit Section *******************/
               
            }
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 

        $('#courseimageDiv').html(courseimagedata);

        $('.rightblock-section .coursedisplaydata').html(right_coursedisplaydata);
        $('.coursebenefit-section .coursebenefitdata').html(coursebenefitdata);


        /************* Start For Image Slider *******************/
        $('.courses-details-slider').owlCarousel({
            loop: true,
            nav: true,
            dots: false,
            margin: 0,
            autoplay: true,
            items: 1
        });
        /************* End For Image Slider *******************/

    }
    
    function OnErrorlistcoursedetails(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End For List Courses Details *****************************/

</script>