<script src="<?php echo $config->getCdnurl(); ?>assets/js/vendors/jquery-3.5.1.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery-ui.min.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery.countTo.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/vendors/popper.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/vendors/bootstrap.min.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/plugin/toastr/toastr.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/vendors/jquery.easing.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/vendors/owl.carousel.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/vendors/countdown.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/vendors/jquery.waypoints.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/vendors/jquery.rcounterup.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/plugin/lightGallery-master/dist/js/lightgallery-all.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/isotope.pkgd.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/images-loaded.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/vendors/validator.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/vendors/hs.megamenu.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/app.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/countdown.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/plugin/datepicker/site.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/validate.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/IISMethods.js"></script>



<script> var endpointurl='<?php echo $config->getEndpointurl() ?>'; </script>
<script> var dirpath='<?php echo $config->getDirpath() ?>'; </script>
<script> var page404url='<?php echo $config->getPage404url() ?>'; </script>

<script type="text/javascript"> 
    function googleTranslateElementInit() { 
        new google.translate.TranslateElement({pageLanguage: 'en',includedLanguages: "ar,en"}, 
            'google_translate_element'
        ); 
    } 

    

    function changeASRLanguage() {
        var language = $('.asrlanguage').attr('data-language');
        
        
        var selectField = document.querySelector("#google_translate_element select");
        for(var i=0; i < selectField.children.length; i++){
            var option = selectField.children[i];
            // find desired langauge and change the former language of the hidden selection-field 
            if(option.value==language){
            selectField.selectedIndex = i;
            // trigger change event afterwards to make google-lib translate this side
            selectField.dispatchEvent(new Event('change'));
            break;
            }
        }
    }


    // function changeASRLanguage_new() {
    //     var language = $('#h_languageid').val();

    //     $('#google_translate_element select').val(language);
        
    //     var selectField = document.querySelector("#google_translate_element select");
    //     for(var i=0; i < selectField.children.length; i++){
    //         var option = selectField.children[i];
    //         // find desired langauge and change the former language of the hidden selection-field 
    //         //alert(option.value+' == '+language);
    //         if(option.value==language){
    //         selectField.selectedIndex = i;
    //         // trigger change event afterwards to make google-lib translate this side
    //         selectField.dispatchEvent(new Event('change'));
    //         break;
    //         }
    //     }
    // }

</script> 

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


<script>
$(document).ready(function() {
    $("#agreetermsconditions").change(function() {
        if ($(this).is(':checked')) {
            $("#btnsignupemail").removeAttr("disabled", "disabled");
        } else {
            $("#btnsignupemail").attr("disabled", "disabled");
        }
    });

    $(".mobile-nav-toggler").on('click', function() {
        $(this).toggleClass("active");
        $("#navBarMobile").toggleClass("active");
        $(".navmobile-overlay").toggleClass("open");
    });
    $(".navmobile-overlay").on('click', function() {
        $(".mobile-nav-toggler").removeClass("active");
        $("#navBarMobile").removeClass("active");
        $(".navmobile-overlay").removeClass("open");
    });
    $(".navbar-country .dropdown-item").on('click', function() {
        // var countryflagImg = $(this).find(".flag-img img").attr("src");
        var countryName = $(this).html();
        var name = $(this).attr('data-name');
        var language = $(this).attr('data-language');
        $(".navbar-country .dropdown-menu li").removeClass("active")
        $(this).parent("li").addClass("active");
        // $(this).closest(".dropdown").find(".btn .flag-img img").attr("src",countryflagImg);
        $(this).closest(".dropdown").find(".btn").html(countryName);
        $(this).closest(".dropdown").find(".asrlanguage").attr('data-name',name);
        $(this).closest(".dropdown").find(".asrlanguage").attr('data-language',language);
        changeASRLanguage();
    });


    // $("#h_languageid").on('change', function() {
    //     changeASRLanguage_new();
    // });


    new When({
        input: document.getElementById('bamdob'),
        singleDate: true
    });
    new When({
        input: document.getElementById('bamqataridexpiry'),
        singleDate: true
    });

    // setTimeout(function() {
    //     $('#cookiepolicypopup').addClass("show");
    // }, 1000);
    $("#cookiepolicypopup .btn-cookie-policy").click(function() {
        $('#cookiepolicypopup').removeClass("show");
    });

    $(".shop-item-cart").click(function(){
        $(this).toggleClass("active");
    });
    $(".shop-item-heart").click(function(){
        $(this).toggleClass("active");
    });
    $('.qtyadd').click(function () {            
        if ($(this).parent().prev().val()) {
            $(this).parent().prev().val(+$(this).parent().prev().val() + 1);
            $(this).closest(".addon-qty-content").find(".qtyminus").removeAttr("disabled");
            var qtyamount = $(this).parent().prev().val()
            var currentamount = $(this).closest(".price-block").find(".amount").attr("data-amount");
            var newammount = currentamount * qtyamount
            $(this).closest(".price-block").find(".sub-price-block .sub-amount").html(newammount);
            $(this).closest(".price-block").find(".sub-price-block").removeClass("d-none");
        }
    });
    $('.qtyminus').click(function () {
        if ($(this).parent().next().val() > 1) {
            $(this).parent().next().val(+$(this).parent().next().val() - 1);
            var currentnewamount = $(this).closest(".price-block").find(".sub-price-block .sub-amount").html();
            var currentamount = $(this).closest(".price-block").find(".amount").attr("data-amount");
            var newammount = currentnewamount - currentamount
            $(this).closest(".price-block").find(".sub-price-block .sub-amount").html(newammount);
        } else {
            $(this).attr("disabled","disabled");
            $(this).closest(".price-block").find(".sub-price-block").addClass("d-none");
        }
    });

    // Start input file upload
    $(".filepreview .form-control").click(function (){
        $(this).closest(".filepreview").find(".filepreviewbg").removeAttr("style");
    });

    /*
    myFile1.onchange = evt => 
    {
        const [file] = myFile1.files
        if (file) 
        {
            filepreviewmyFile1.src = URL.createObjectURL(file);
            var imgurlmyFile1 = $('#filepreviewmyFile1').attr('src');

            $("#filepreviewmyFile1").parent(".filepreviewbg").css("background-image", "url(" + imgurlmyFile1 + ")");
        }
    }

    my2File.onchange = evt => 
    {
        const [file] = my2File.files
        if (file) 
        {
            filepreviewmy2File.src = URL.createObjectURL(file);
            var imgurlmy2File = $('#filepreviewmy2File').attr('src');

            $("#filepreviewmy2File").parent(".filepreviewbg").css("background-image", "url(" + imgurlmy2File + ")");
        }
    }
    
    my3File.onchange = evt => 
    {
        const [file] = my3File.files
        if (file) 
        {
            filepreviewmy3File.src = URL.createObjectURL(file);
            var imgurlmy3File = $('#filepreviewmy3File').attr('src');

            $("#filepreviewmy3File").parent(".filepreviewbg").css("background-image", "url(" + imgurlmy3File + ")");
        }
    }
    */
    // End input file upload

    //For Show Cart Item Count
    countcartitem();
});
setInterval(hasValCheck, 100);

function hasValCheck() {
    $('.form-control').each(function() {
        if ($(this).val() != "") {
            $(this).addClass('has-val');
            $(this).parent(".file-form").addClass('has-val');
            $(this).closest(".form-group").find(".input-group").addClass('has-val');
        }

        if ($(this).hasClass("error") != "") {
            $(this).parent(".validate-input").addClass('has-error');
        } else {
            $(this).parent(".validate-input").removeClass('has-error');
        }

        $(this).on('blur', function() {
            if ($(this).val().trim() != "") {
                $(this).addClass('has-val');
                $(this).parent(".file-form").addClass('has-val');
                $(this).closest(".form-group").find(".input-group").addClass('has-val');
            } else {
                $(this).removeClass('has-val');
                $(this).parent(".file-form").removeClass('has-val');
                $(this).closest(".form-group").find(".input-group").removeClass('has-val');
            }
        })
    });
}


/************************ Start For Number Only ***********************/
//for number only
function numbonly(e) {
    var k = e.which;
    var ok = k >= 48 && k <= 57 || // 0-9
        k ==8 || //backspace
        k==0 || //arrow key
        k==46; //. sign
    if (!ok){
        e.preventDefault();
    }
}
/************************ End For Number Only ***********************/


/************************ Start For Menu Render Data ***********************/

// Page Navigation on Click Main Menu
$('.clsparentmenu').on('click', function(e) {
    
    var pagename = $(this).attr('pagename');
    if(pagename)
    {
        e.preventDefault(); 
        if (e.ctrlKey){ //ctrl click event
            window.open(pagename,'_blank');
        }
        else{
            currentXhr.abort();
            render(pagename);
            window.history.pushState(pagename, 'Title', dirpath + pagename);
        }
    }
});
/************************ End For Menu Render Data ***********************/



/************************* Start For Cookies ******************************/
setTimeout(function() {
    $('#cookiepolicypopup').addClass("show");
}, 1000);

//Click Event Of Accept Cookie
$('#acceptcookie').click(function(){
    var nDays = 999;
    var cookieName = "AlhadafShootingRangeWebCookie";
    var cookieValue = "true";
    var today = new Date();
    var expire = new Date();
    var expiretime = today.getTime() + 3600000*24*parseInt(nDays);
    expire.setTime(today.getTime() + 3600000*24*parseInt(nDays));
    document.cookie = cookieName+"="+escape(cookieValue)+";expires="+expire.toGMTString()+";path=/";

    formdata = new FormData();
    formdata.append("action", "insertacceptcookie");
    formdata.append("expiretime", expiretime);
    var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:true,responsetype: 'JSON'};

    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'home',formdata,headersdata,Onsuccessacceptcookie,OnErroracceptcookie)

    function Onsuccessacceptcookie(data)
    {
        
    }

    function OnErroracceptcookie()
    {
        
    }
});
/************************* End For Cookies ******************************/



/************************* Start For Set URL (Course) ******************************/
function getwebredirecturl(type,id,n_name,unqid)   //type 1-Item,2-Vendor,3-Service
{
    var typename='';
    var pagename='';
    if(type == 1)  //For Course
    {
        typename='cd';

        pagename='coursedetail';
    }
    

    var lastcurrurl = typename+'-'+unqid+'-'+n_name;
    
    currentXhr.abort();
    render(pagename);
    window.history.pushState(pagename, 'Title', dirpath + lastcurrurl);
}
/************************* End For Set URL (Item,Vendor,Service) ******************************/



/************************* Start For Member Registeration ******************************/
new When({
    input: document.getElementById('r_qataridexpiry'),
    singleDate: true,
    outputFormat: "DD/MM/YYYY",
    showHeader: false,
});
new When({
    input: document.getElementById('r_dob'),
    singleDate: true,
    outputFormat: "DD/MM/YYYY",
    showHeader: false,
    maxDate: '<?php echo date('Y-m-d') ?>',
});

//Validate Register Form
if($('#registerModal #registrationForm').length){		
    $('#registerModal #registrationForm').validate({
        rules:{
            r_fname:{
                required: true,					
            },
            r_lname:{
                required: true,				
            },
            r_email:{
                required: true,
                email:true,
            },
            r_mobile:{
                required: true,
                number:true,
                maxlength: 12
            },
            r_qataridno:{
                required: true,
            },
            r_qataridexpiry:{
                required: true,
            },
            r_dob:{
                required: true,
            },
            r_nationality:{
                required: true,
            },
            r_password:{
                required: true,
            },
            r_cpassword:{
                required: true,
                equalTo:'#r_password',
            }
        },messages:{
            r_fname:{
                required: "First name is required",						
            },
            r_lname:{
                required: "Last name is required",					
            },
            r_email:{
                required: "Email is required",
            },
            r_mobile:{
                required: "Mobile number is required",	
            },
            r_qataridno:{
                required: "Qatar id number is required",		
            },
            r_qataridexpiry:{
                required: "Qatar id expiry is required",		
            },
            r_dob:{
                required: "Date of birth is required",		
            },
            r_nationality:{
                required: "Nationality is required",		
            },
            r_password:{
                required: "Password is required",				
            },
            r_cpassword:{
                required: "Confirm password is required",
                equalTo:"Confirm password must be same as password"					
            }
        },
        submitHandler: function(form){

            var redirecturl = window.location.href;

            $("#preloader").fadeIn();
            var pagename=getpagename();
            formdata = new FormData(form);
            formdata.append("action", "register");
            formdata.append("redirecturl", redirecturl);
            var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'registeration',formdata,headersdata,Onsuccessmemberregister,OnErrormemberregister); 
            
        },
    });
}


function Onsuccessmemberregister(content)
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
        toastr.success(resultdata.message);
        $('#registerModal').modal('hide');
    }
}


function OnErrormemberregister(content)
{ 
    ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
} 

//Register Modal Shown Evet
$('#registerModal').on('shown.bs.modal', function() {
    $("#registerModal #registrationForm").validate().resetForm();
    $('#registerModal #registrationForm').trigger("reset");
});
/************************* End For Member Registeration ******************************/




/************************* Start For Member Login ******************************/

//Validate Login Form
if($('#loginModal #loginForm').length){		
    $('#loginModal #loginForm').validate({
        rules:{
            l_username:{
                required: true,					
            },
            l_password:{
                required: true,				
            },
        },messages:{
            l_username:{
                required: "Email/Mobile number is required",						
            },
            l_password:{
                required: "Password is required",					
            },
        },
        submitHandler: function(form){

            var redirecturl = window.location.href;

            $("#preloader").fadeIn();
            var pagename=getpagename();
            formdata = new FormData(form);
            formdata.append("action", "login");
            formdata.append("redirecturl", redirecturl);
            var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'login',formdata,headersdata,Onsuccessmemberlogin,OnErrormemberlogin); 
            
        },
    });
}


function Onsuccessmemberlogin(content)
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
        //toastr.success(resultdata.message);
        if(resultdata.url)
        {
            window.location=resultdata.url;
        }
        else
        {
            window.location='<?php echo $config->getDirpath(); ?>'+"home";
        }

    }
}


function OnErrormemberlogin(content)
{ 
    ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
} 

//Login Modal Shown Evet
$('#loginModal').on('shown.bs.modal', function() {
    $("#loginModal #loginForm").validate().resetForm();
    $('#loginModal #loginForm').trigger("reset");
});
/************************* End For Member Login ******************************/



/************************* Start For Member Forgot Password ******************************/
//Validate Forgot Password Form
if($('#forgotpaswordModal #forgotpaswordForm').length){		
    $('#forgotpaswordModal #forgotpaswordForm').validate({
        rules:{
            f_username:{
                required: true,					
            },
        },messages:{
            f_username:{
                required: "Email/Mobile number is required",						
            },
        },
        submitHandler: function(form){

            var redirecturl = window.location.href;

            $("#preloader").fadeIn();
            var pagename=getpagename();
            formdata = new FormData(form);
            formdata.append("action", "forgotpass");
            formdata.append("redirecturl", redirecturl);
            var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'forgotpassword',formdata,headersdata,Onsuccessmemberfpass,OnErrormemberfpass); 
            
        },
    });
}


function Onsuccessmemberfpass(content)
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
        toastr.success(resultdata.message);

        var resetpassdiv='';
        resetpassdiv+='<form method="post" id="setpasswordForm" novalidate="true">';
        resetpassdiv+='<input type="hidden" id="fr_userid" name="fr_userid" value="'+resultdata.userid+'">';
        resetpassdiv+='<div class="row">';

        resetpassdiv+='<div class="col-12">';
        resetpassdiv+='<div class="form-group validate-input">';
        resetpassdiv+='<input class="form-control fr_email" id="fr_email" name="fr_email" type="email" value="'+resultdata.email+'" readonly="readonly" required="required">';
        resetpassdiv+='<span class="focus-form-control"></span>';
        resetpassdiv+='<label class="label-form-control" for="fr_email">Email<span class="text-danger">*</span></label>';
        resetpassdiv+='</div>';
        resetpassdiv+='</div>';

        resetpassdiv+='<div class="col-12">';
        resetpassdiv+='<div class="form-group validate-input">';
        resetpassdiv+='<input class="form-control fr_mobile" id="fr_mobile" name="fr_mobile" type="text" value="'+resultdata.mobilemno+'" readonly="readonly" required="required">';
        resetpassdiv+='<span class="focus-form-control"></span>';
        resetpassdiv+='<label class="label-form-control" for="fr_mobile">Mobile Number<span class="text-danger">*</span></label>';
        resetpassdiv+='</div>';
        resetpassdiv+='</div>';

        resetpassdiv+='<div class="col-12">';
        resetpassdiv+='<div class="form-group validate-input">';
        resetpassdiv+='<input class="form-control fr_vercode" id="fr_vercode" name="fr_vercode" type="text" maxlength="6" onkeypress="numbonly(event)" required="required">';
        resetpassdiv+='<span class="focus-form-control"></span>';
        resetpassdiv+='<label class="label-form-control" for="fr_vercode">Verification Code<span class="text-danger">*</span></label>';
        resetpassdiv+='</div>';
        resetpassdiv+='</div>';

        resetpassdiv+='<div class="col-12">';
        resetpassdiv+='<div class="form-group validate-input">';
        resetpassdiv+='<input class="form-control fr_newpass" id="fr_newpass" name="fr_newpass" type="text" required="required">';
        resetpassdiv+='<span class="focus-form-control"></span>';
        resetpassdiv+='<label class="label-form-control" for="fr_newpass">Set New Password<span class="text-danger">*</span></label>';
        resetpassdiv+='</div>';
        resetpassdiv+='</div>';

        resetpassdiv+='</div>';
        resetpassdiv+='<div class="row">';
        resetpassdiv+='<div class="col-12 text-center">';
        resetpassdiv+='<button class="btn btn-brand-01" type="submit">Submit</button>';
        resetpassdiv+='</div>';
        resetpassdiv+='</div>';
        resetpassdiv+='</form>';


        $('#forgotpaswordModal #resetpassDiv').html(resetpassdiv);	



        if($('#forgotpaswordModal #setpasswordForm').length){		
            $('#forgotpaswordModal #setpasswordForm').validate({
                rules:{
                    fr_email:{
                        required: true,					
                    },
                    fr_mobile:{
                        required: true,					
                    },
                    fr_vercode:{
                        required: true,	
                        number:true				
                    },
                    fr_newpass:{
                        required: true,					
                    },
                },messages:{
                    fr_email:{
                        required: "Email is required",						
                    },
                    fr_mobile:{
                        required: "Mobile number is required",						
                    },
                    fr_vercode:{
                        required: "Verification code is required",							
                    },
                    fr_newpass:{
                        required: "New password is required",						
                    },
                },
                submitHandler: function(form){

                    var redirecturl = window.location.href;

                    $("#preloader").fadeIn();
                    var pagename=getpagename();
                    formdata = new FormData(form);
                    formdata.append("action", "setnewpassword");
                    formdata.append("redirecturl", redirecturl);
                    var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'forgotpassword',formdata,headersdata,Onsuccessmembersetpass,OnErrormembersetpass); 
                    
                },
            });



            function Onsuccessmembersetpass(content)
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
                    toastr.success(resultdata.message);

                    if(resultdata.url)
                    {
                        window.location=resultdata.url;
                    }
                    else
                    {
                        window.location='<?php echo $config->getDirpath(); ?>'+"home";
                    }

                }
            }


            function OnErrormembersetpass(content)
            { 
                ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
            } 
        }
    }
}


function OnErrormemberfpass(content)
{ 
    ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
} 


//Click Event Of Reset Password Of Close Button
$('body').on('click','#forgotpaswordModal .modal-close',function(){
    
    var passhtmldata='';
    
    passhtmldata+='<form method="post" id="forgotpaswordForm" novalidate="true">';
    passhtmldata+='<div class="row">';
    passhtmldata+='<div class="col-12">';
    passhtmldata+='<div class="form-group validate-input">';
    passhtmldata+='<input class="form-control f_username" id="f_username" name="f_username" type="text" required="required">';
    passhtmldata+='<span class="focus-form-control"></span>';
    passhtmldata+='<label class="label-form-control" for="f_username">Email / Mobile Number <span class="text-danger">*</span></label>';
    passhtmldata+='</div>';
    passhtmldata+='</div>';
    passhtmldata+='</div>';
    passhtmldata+='<div class="row">';
    passhtmldata+='<div class="col-12 text-center">';
    passhtmldata+='<button class="btn btn-brand-01" type="submit">Send</button>';
    passhtmldata+='</div>';
    passhtmldata+='</div>';
    passhtmldata+='</form>';
    
    $('#forgotpaswordModal #resetpassDiv').html(passhtmldata);
    
    if($('#forgotpaswordModal #forgotpaswordForm').length){		
        $('#forgotpaswordModal #forgotpaswordForm').validate({
            rules:{
                f_username:{
                    required: true,					
                },
            },messages:{
                f_username:{
                    required: "Email/Mobile number is required",						
                },
            },
            submitHandler: function(form){

                var redirecturl = window.location.href;

                $("#preloader").fadeIn();
                var pagename=getpagename();
                formdata = new FormData(form);
                formdata.append("action", "forgotpass");
                formdata.append("redirecturl", redirecturl);
                var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'forgotpassword',formdata,headersdata,Onsuccessmemberfpass,OnErrormemberfpass); 
                
            },
        });
    }
});
/************************* End For Member Forgot Password ******************************/



/*************************************************************************** Start For Member Social Login ********************************************************************************/

/******************** Start Login with Facebook   *******************/


    // This is called with the results from from FB.getLoginStatus().
    function statusChangeCallback(response) {
    //console.log('statusChangeCallback');
    //console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
        // Logged into your app and Facebook.
        
    } else if (response.status === 'not_authorized') {
        // The person is logged into Facebook, but not your app.
        //document.getElementById('status').innerHTML = 'Please log ' +'into this app.';
    } else {
        // The person is not logged into Facebook, so we're not sure if
        // they are logged into this app or not.
        //document.getElementById('status').innerHTML = 'Please log ' +'into Facebook.';
    }
    }

    // This function is called when someone finishes with the Login
    // Button.  See the onlogin handler attached to it in the sample
    // code below.
    function checkLoginState() {
    
    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });
    }

    window.fbAsyncInit = function() {
    FB.init({
    appId      : '2407664812856133',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    oauth      : true,
    status     : true, // check login status                
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use graph api version 2.5
    });

    // Now that we've initialized the JavaScript SDK, we call 
    // FB.getLoginStatus().  This function gets the state of the
    // person visiting this page and can return one of three states to
    // the callback you provide.  They can be:
    //
    // 1. Logged into your app ('connected')
    // 2. Logged into Facebook, but not your app ('not_authorized')
    // 3. Not logged into Facebook and can't tell if they are logged into
    //    your app or not.
    //
    // These three cases are handled in the callback function.

    FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
    });

    };

    // Load the SDK asynchronously
    (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function fb_login(type)  //type 1-Register,2-Login
    {
    var logtype = type;
    FB.login(function(response) {

        if (response.authResponse) {
            //console.log('Welcome!  Fetching your information....');
            //console.log(response); // dump complete info
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID

            FB.api('/me',{fields: 'name,email'} , function(response) {
                user_email = response.email; //get user email
                // you can store this data into your database  

                $('#preloader').fadeIn('fade');

                var targeturl = '';
                if(logtype == 1)  //For Register
                {
                    targeturl=$('#registrationForm #targeturl').val();
                }
                else if(logtype == 2)  //For Login
                {
                    targeturl=$('#loginForm #targeturl').val();
                }
                

                var headersdata= {Accept: 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false,responsetype: 'HTML'};
                formdata = new FormData(form);
                formdata.append("action", "checksociallogin");
                formdata.append("email", response.email);
                formdata.append("name", response.name);
                formdata.append("webid", response.id);
                formdata.append("logtype", logtype);
                formdata.append("logtype", logtype);
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'login',formdata,headersdata,OnsuccessSocialLogin,OnErrorSocialLogin);
                

                function OnsuccessSocialLogin(content)
                {
                    var JsonData = JSON.stringify(content);
                    var resultdata = jQuery.parseJSON(JsonData);


                    if(resultdata.status == 0)
                    {  
                        toastr.error(resultdata.message);
                    }
                    else if(resultdata.status == 1)
                    {
                        if(resultdata.emailfound==0)
                        {
                            if(resultdata.logtype == 1)  //For Register
                            {
                                $('#registrationForm #r_fname').val(resultdata.name);
                                $('#registrationForm #r_lname').val(resultdata.name);
                                $('#registrationForm #r_email').val(resultdata.email);
                                $('#registrationForm #hiddenid').val(resultdata.webid);
                                $('#registrationForm #regtype').val('f');
                                
                                $('#registrationForm #r_mobile').focus(); 
                            }
                            else if(resultdata.logtype == 2)  //For Login
                            {
                                $('#loginModal').modal('hide');
                                $('#registerModal').modal('show');
                                
                                $('#registrationForm #r_fname').val(resultdata.name);
                                $('#registrationForm #r_lname').val(resultdata.name);
                                $('#registrationForm #r_email').val(resultdata.email);
                                $('#registrationForm #hiddenid').val(resultdata.webid);
                                $('#registrationForm #regtype').val('f');
                                
                                $('#registrationForm #r_mobile').focus(); 
                            }
                        }
                        else if(resultdata.emailfound==1)
                        {
                            if(resultdata.url)
                            {								 		
                                window.location=resultdata.url;
                            }
                            else
                            {
                                location.reload();
                            }
                        }
                    }
                    $('#preloader').fadeOut('fade');
                }


                function OnErrorSocialLogin(content)
                {
                    var JsonData = JSON.stringify(content);
                    var resultdata = jQuery.parseJSON(JsonData);

                    toastr.error(resultdata.message);

                    $('#preloader').fadeOut('fade');
                } 
    
            });

        } else {
            //user hit cancel button
            console.log('User cancelled login or did not fully authorize.');

        }
    }, {
        scope: 'publish_stream,email'
    });
}
(function() {
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    // document.getElementById('fb-root').appendChild(e);
}());

/******************** End Login with Facebook   *******************/




/******************** Start Login with Google   *******************/
var googleUser = {};
  var startApp = function() 
  {
    gapi.load('auth2', function()
    {
      // Retrieve the singleton for the GoogleAuth library and set up the client.
      auth2 = gapi.auth2.init({
        client_id: '1092775194521-eaqk6gi4t9jlnejlhnqf0v6ru8d459ns.apps.googleusercontent.com',
        cookiepolicy: 'single_host_origin',
        // Request scopes in addition to 'profile' and 'email'
        //scope: 'additional_scope'
      });
      attachSignin(document.getElementById('customGoogleBtn'));
    });
  };
startApp();
  function attachSignin(element) {
    console.log(element.id);
    auth2.attachClickHandler(element, {},
        function(googleUser) 
        {
            //  console.log('ID: ' + googleUser.getBasicProfile().getId()); 
			//  console.log('Name: ' + googleUser.getBasicProfile().getName());
			//  console.log('Image URL: ' + googleUser.getBasicProfile().getImageUrl());
			//  console.log('Email: ' + googleUser.getBasicProfile().getEmail());
			//	$('#loader-wrapper').css({'opacity' : '1' , 'visibility' : 'visible'});
			  	var targeturl=$('#targeturl').val()
				jqXHR = $.ajax({
					url : weburl+"getdata.php",
					type : "POST",
					data:  {email:googleUser.getBasicProfile().getEmail(),action:"checkfbgoogleemail"},
					success : function(data) 
					{								
						var JsonData = JSON.stringify(data);
						var resultdata = jQuery.parseJSON(JsonData);
						$('#loader-wrapper').css({'opacity' : '0' , 'visibility' : 'hidden'});	
						
						if(resultdata.status==0)
						{		
							$('#fname').val(googleUser.getBasicProfile().getName());
							$('#email').val(googleUser.getBasicProfile().getEmail());
							$('#hiddenid').val(googleUser.getBasicProfile().getId());
							$('#regtype').val('g');
							$('#contact1').focus();
							$('#passDiv').hide();
							$("#msg2").html('Register Here For Continue...').addClass('alert alert-success').show();
                            setTimeout(function() { $("#msg2").hide(); }, 5000);					
							//swal('',resultdata.message,'error');
									
						}
						else if(resultdata.status==1)
						{
							if(resultdata.emailfound==0)
							{
                                $('#registerModal').modal('show');
                                $('#modalLogin').modal('show');
								//$('#registeruser')[0].scrollIntoView(true);
								$('#fname').val(googleUser.getBasicProfile().getName());
								$('#email').val(googleUser.getBasicProfile().getEmail());
								$('#hiddenid').val(googleUser.getBasicProfile().getId());
								$('#regtype').val('g');
								$('#contact1').focus();
								$('#passDiv').hide();
								$("#msg2").html('Register Here For Continue...').addClass('alert alert-success').show();
	                            setTimeout(function() { $("#msg2").hide(); }, 5000);
							}
							else if(resultdata.emailfound==1)
							{
						 		if(targeturl)
							 	{								 		
							 		window.location=targeturl;
							 	}
							 	else
							 	{
							 		window.location=weburl+'index.php';
							 	}								
							}																														
						}							
					}
				});


        }, function(error) {
          console.log(JSON.stringify(error, undefined, 2));
        });
  }	
/******************** End Login with Google   *******************/


/*************************************************************************** End For Member Social Login ********************************************************************************/





/************************* Start For Add To Cart ******************************/
//Click Event OF Add To Cart
$('body').on('click','.btnaddtocart',function(){
    var type = $(this).attr('data-type');      //1-Membership, 2-Packages, 3-Course
    var id = $(this).attr('data-id');
    var isbuynow = $(this).attr('data-isbuynow');     //1-Buy Now, 0-Add To Cart

    //alert(type+' **** '+isbuynow+' **** '+id);

    $("#preloader").fadeIn();
    formdata = new FormData();
    formdata.append("action", "addtocartitem");
    formdata.append("type", type);
    formdata.append("itemid", id);
    formdata.append("isbuynow", isbuynow);
    var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'addtocart',formdata,headersdata,Onsuccessaddtocartitem,OnErroraddtocartitem); 
    
});


function Onsuccessaddtocartitem(content)
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
        // if(resultdata.isbuynow == 1)
        // {
        //     window.location='<?php //echo $config->getDirpath(); ?>'+"cart";
        // }
        // else
        // {
        //     toastr.success(resultdata.message);
        // }

        toastr.success(resultdata.message);
        // setTimeout(function(){ 
        //     window.location='<?php //echo $config->getDirpath(); ?>'+"cart";
        // }, 2000);

        //For Show Cart Item Count
        countcartitem();
    }
}


function OnErroraddtocartitem(content)
{ 
    ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
} 

/************************* End For Add To Cart ******************************/

function countcartitem()
{
    //$("#preloader").fadeIn();
    formdata = new FormData();
    formdata.append("action", "countcartitem");
    var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'addtocart',formdata,headersdata,Onsuccesscountcartitem,OnErrorcountcartitem); 
}

function Onsuccesscountcartitem(content)
{
    var JsonData = JSON.stringify(content);
    var resultdata = jQuery.parseJSON(JsonData);

    //$("#preloader").fadeOut();

    if(resultdata.status==0)
    {
       
    }
    else if(resultdata.status==1)
    {
        $('.cartcount').html(resultdata.totalcartdata);
    }
}


function OnErrorcountcartitem(content)
{ 
    ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
} 


/************************* Start For List Order History Detail ******************************/
$('body').on('click','.viewfulldetails',function(){
    var orderid = $(this).attr('data-oid');
    var orderdetid = $(this).attr('data-odid');

    $("#preloader").fadeIn();
    formdata = new FormData();
    formdata.append("action", "listorderhistorydetail");
    formdata.append("orderid", orderid);
    formdata.append("orderdetid", orderdetid);
    var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'orderhistory',formdata,headersdata,Onsuccesslistorderhistorydetail,OnErrorlistorderhistorydetail); 

   
});   

function Onsuccesslistorderhistorydetail(content)
{
    var JsonData = JSON.stringify(content);
    var resultdata = jQuery.parseJSON(JsonData);

    $("#preloader").fadeOut();
    var htmldata = '';
    if(resultdata.status==0)
    {
       
    }
    else if(resultdata.status==1)
    {
        if(resultdata.isattributedetail > 0)
        {
            htmldata+='<div class="pricing-content mt-3">';
            htmldata+='<ul class="list-unstyled pricing-feature-list-2">';
                    
                for(var j in resultdata.attributedetail)
                {
                    htmldata+='<li><img class="iconimg" src="'+resultdata.attributedetail[j].iconimg+'" />';
                   
                    htmldata+='<p>';
                    if(resultdata.attributedetail[j].attributename)
                    {
                        htmldata+='<b>'+resultdata.attributedetail[j].attributename+' : </b>';
                    }
                    htmldata+=resultdata.attributedetail[j].name;
                    htmldata+='</p>';
                    htmldata+='</li>';
                }
                    
            htmldata+='</ul>';
            htmldata+='</div>';
        }

        if(resultdata.iscoursebenefit > 0)
        {
            htmldata+='<div class="pricing-info">';
            htmldata+='<h5>Course Benefit</h5>';
            htmldata+='</div>';
            htmldata+='<div class="pricing-content mt-3">';
            htmldata+='<ul class="list-unstyled pricing-feature-list-2">';
                    
                for(var i in resultdata.coursebenefit)
                {
                    htmldata+='<li><img class="iconimg" src="'+resultdata.coursebenefit[i].iconimg+'" />';
                    htmldata+='<p>'+resultdata.coursebenefit[i].name+'</p>';
                    htmldata+='<span class="ml-auto">'+resultdata.coursebenefit[i].durationname+'</span>';
                    htmldata+='</li>';
                }
                    
            htmldata+='</ul>';
            htmldata+='</div>';
        }



        if(resultdata.isitemdetail > 0)
        {
            htmldata+='<div class="col-12 d-none d-xl-block">';
            htmldata+='<div class="row mx-0">';
            htmldata+='<div class="col-12 pricing-info px-0 mb-2 mt-3">';
            htmldata+='<h5>Item Detail</h5>';
            htmldata+='</div>';
            htmldata+='<div class="col-12 heading-list-profile px-0">';
            htmldata+='<div class="row mx-0">';
            htmldata+='<div class="col-sm-4 pr-1 pl-2">';
            htmldata+='<h6>Item</h6>';
            htmldata+='</div>';
            htmldata+='<div class="col-sm-4 px-1">';
            htmldata+='<h6>Qty</h6>';
            htmldata+='</div>';
            htmldata+='<div class="col-sm-4 px-1 text-right">';
            htmldata+='<h6>Price</h6>';
            htmldata+='</div>';
            htmldata+='</div>';
            htmldata+='</div>';
            htmldata+='</div>';
            htmldata+='</div>';
            htmldata+='<div class="col-12 list-profile-content">';

            for(var i in resultdata.itemdetail)
            {
                htmldata+='<div class="col-12 list-profile px-0">';
                    htmldata+='<div class="row mx-0">';
                        htmldata+='<div class="col-6 col-sm-4 col-xl-4 px-1 mb-2 mb-xl-0">';
                            htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Item: </b>'+resultdata.itemdetail[i].itemname;
                            if(resultdata.itemdetail[i].type == 2)  //For Discount
                            {
                                htmldata+='<span class="d-block fs-12">('+resultdata.itemdetail[i].discount+'% '+resultdata.itemdetail[i].typename+')</span>';
                            }
                            else
                            {
                                htmldata+='<span class="d-block fs-12">('+resultdata.itemdetail[i].typename+')</span>';
                            }
                            htmldata+='</p>';
                        htmldata+='</div>';
                        htmldata+='<div class="col-6 col-sm-3 col-xl-4 px-1 mb-2 mb-xl-0">';
                            htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Qty: </b> '+resultdata.itemdetail[i].qty+' <span class="d-block fs-12">(Remaining '+resultdata.itemdetail[i].remainqty+' Qty)</span>';
                            if(resultdata.itemdetail[i].durationname)
                            {
                                htmldata+='<span class="d-block fs-12">('+resultdata.itemdetail[i].durationname+')</span>';
                            }
                            htmldata+='</p>';
                        htmldata+='</div>';
                        htmldata+='<div class="col-6 col-sm-3 col-xl-4 px-1 mb-2 mb-xl-0 text-right">';
                            htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Price: </b> Qr '+resultdata.itemdetail[i].price+'</p>';
                        htmldata+='</div>';
                    htmldata+='</div>';
                htmldata+='</div>';
            }

            htmldata+='</div>';
        }    
    }

    $('#fullspecificationModal #fullspecificationdata').html(htmldata);
    $('#fullspecificationModal').modal('show');
}


function OnErrorlistorderhistorydetail(content)
{ 
    ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
} 

/************************* Start For List Order History Detail ******************************/


/************************* Start For Footer Email Form ******************************/

//Validate Login Form
if($('#footeremailForm').length){		
    $('#footeremailForm').validate({
        rules:{
            signupemail:{
                required: true,
                email: true					
            },
        },messages:{
            signupemail:{
                required: "",	
                email: ""						
            },
        },
        submitHandler: function(form){
            $("#preloader").fadeIn();
            var pagename=getpagename();
            formdata = new FormData(form);
            formdata.append("action", "insertemaildata");
            var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'home',formdata,headersdata,Onsuccessfooteremail,OnErrorfooteremail); 
            
        },
    });
}


function Onsuccessfooteremail(content)
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
        toastr.success(resultdata.message);

        $("#footeremailForm").validate().resetForm();
        $('#footeremailForm').trigger("reset");
    }
}


function OnErrorfooteremail(content)
{ 
    ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
} 

/************************* End For Footer Email Form ******************************/

</script>
