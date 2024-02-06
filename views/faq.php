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
                    <li class="active lan-footer-faq">Faq</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing" id="faq">
    <div class="container">
        <div class="row justify-content-center faqtitlediv">
            <div class="col-md-9 col-lg-8">
                <div class="section-heading text-center mb-5">
                    <h2>Frequently Asked Queries</h2>
                    <p>Efficiently productivate reliable paradigms before ubiquitous models. Continually utilize frictionless expertise whereas tactical relationships. Still have questions? Contact us</p>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-md-12 col-lg-12">
                <div id="accordion" class="accordion faq-wrap faqdatadiv">
                    
                    
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script>
    $(document).ready(function(){

        //List FAQs Data
        listfaq();
    });

    /**************************** Start For List FAQ *****************************/
    function listfaq()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listfaq");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistfaq,OnErrorlistfaq);
    }

    function Onsuccesslistfaq(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        $('.faqtitlediv').addClass('d-none');

        var htmldata="";
        if(resultdata.status==0)
        {
            htmldata+='<div class="error-content-wrap text-center ">';
            htmldata+='<h2 class="">'+resultdata.message+'</h2>';
            htmldata+='</div>';
        }
        else if(resultdata.status==1)
        {
            if(resultdata.isfaqdata > 0)
            {
                $('.faqtitlediv').removeClass('d-none');

                for(var i in resultdata.faqdata)
                {
                    var attrcollapsed = 'collapsed';
                    var attrshow = '';
                    if(i == 0)
                    {
                        attrcollapsed = '';
                        attrshow = 'show';
                    }

                    htmldata+='<div class="card mb-3">';
                    htmldata+='<a class="card-header '+attrcollapsed+'" data-toggle="collapse" href="#collapse'+i+'" aria-expanded="false">';
                    htmldata+='<h4 class="mb-0 d-inline-block">'+resultdata.faqdata[i].question+'</h4>';
                    htmldata+='</a>';
                    htmldata+='<div id="collapse'+i+'" class="collapse '+attrshow+'" data-parent="#accordion" style="">';
                    htmldata+='<div class="card-body white-bg">';
                    htmldata+='<div class="discription">';
                    htmldata+=resultdata.faqdata[i].answer;
                    htmldata+='</div>';
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

        $('.faqdatadiv').html(htmldata);

    }
    
    function OnErrorlistfaq(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /**************************** End For List FAQ *****************************/

</script>