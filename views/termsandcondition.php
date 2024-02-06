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
                    <li class="active">Terms & Condition</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing privacy-policy page-typography">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="row">
                    <div class="col-12" id="tandcDiv">
                        

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script>
     $(document).ready(function(){
       
        fillcontentsetting()
    });


    function fillcontentsetting()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false};

        formdata = new FormData();
        formdata.append("action", "listcontentsetting");
        formdata.append("contenttypeid", '<?php echo $config->getTermsConditionId(); ?>');

        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'home',formdata,headersdata,Onsuccessfillcontentsetting,OnErrorfillcontentsetting);
    }

    function Onsuccessfillcontentsetting(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        if(resultdata.status==0)
        {
            $('#tandcDiv').html('<h3>'+resultdata.message+'</h3>');
        }
        else if(resultdata.status==1)
        {
            $('#tandcDiv').html(resultdata.description);
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 

    }

    function OnErrorfillcontentsetting(content)
    { 
        ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
    }


</script> 