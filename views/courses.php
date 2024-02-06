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
                    <li class="active lan-course-menu">Courses</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing">
    <div class="container">
        <div class="row" id="coursesdatadiv">
            
            
        </div>
    </div>
</section>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script>
    $(document).ready(function () {
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.licourses").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.licourses").addClass("active");

        //List Course Data
        listcourses();
    });


    /**************************** Start For List Courses *****************************/
    function listcourses()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listcourses");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistcourses,OnErrorlistcourses);
    }

    function Onsuccesslistcourses(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        var htmldata="";
        if(resultdata.status==0)
        {
            htmldata+='<div class="error-content-wrap text-center ">';
            htmldata+='<h2 class="">'+resultdata.message+'</h2>';
            htmldata+='</div>';
        }
        else if(resultdata.status==1)
        {
            if(resultdata.iscoursedata > 0)
            {
                for(var i in resultdata.coursedata)
                {
                    htmldata+='<div class="col-md-4 col-lg-4 mb-4">';
                    htmldata+='<div class="single-game-hosting">';
                    htmldata+='<span class="img-overlay"></span>';
                    htmldata+='<img src="'+resultdata.coursedata[i].itemimg+'" alt="'+resultdata.coursedata[i].itemname+'" class="img-fluid">';
                    htmldata+='<div class="game-hosting-name">';
                    htmldata+='<h3 class="mb-0 h6">'+resultdata.coursedata[i].itemname+'</h3>';
                    htmldata+='<span>Starting From Qr '+resultdata.coursedata[i].price+'</span>';
                    htmldata+='<a href="javascript:void(0)" class="btn btn-brand-03 btn-sm btn-block clsitem" data-id="'+resultdata.coursedata[i].id+'" data-itemname="'+resultdata.coursedata[i].itemname+'" data-nitemname="'+resultdata.coursedata[i].n_itemname+'" data-unqid="'+resultdata.coursedata[i].itemno+'">View More</a>';
                    htmldata+='</div>';
                    htmldata+='</div>';
                    htmldata+='</div>';
                }
            }
            else
            {
                htmldata+='<div class="error-content-wrap text-center ">';
                htmldata+='<h2 class="">'+resultdata.message+'</h2>';
                htmldata+='</div>';
            }
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 

        $('#coursesdatadiv').html(htmldata);

        //Click Event Of Item
        $('#coursesdatadiv .clsitem').on('click',function(){
            var n_itemname = $(this).attr('data-nitemname');
            var itemunqid = $(this).attr('data-unqid');
            var id = $(this).attr('data-id');

            getwebredirecturl(1,id,n_itemname,itemunqid);   //type 1-Course
        });

    }
    
    function OnErrorlistcourses(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End For List Courses *****************************/

</script>