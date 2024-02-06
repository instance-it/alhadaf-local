<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="<?php echo $config->getCdnurl(); ?>assets/js/libs/jquery-3.1.1.min.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery.dragtable.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/bootstrap/js/popper.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?php echo $config->getCdnurl(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo $config->getCdnurl(); ?>assets/js/daterangepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $config->getCdnurl(); ?>assets/js/bootstrap-datetimepicker.min.js"></script> 
<script src="<?php echo $config->getCdnurl(); ?>assets/js/owl.carousel.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<!-- <script src="plugins/tabs/jquery.scrolling-tabs.js"></script> -->
<script src="<?php echo $config->getCdnurl(); ?>assets/js/app.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/custom.js"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->

<script src="<?php echo $config->getCdnurl(); ?>assets/js/validate.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/jstree.js"></script>

<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
<script src="<?php echo $config->getCdnurl(); ?>assets/plugins/apex/apexcharts.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/dashboard/dash_1.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo $config->getCdnurl(); ?>assets/plugins/fullcalendar/moment.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/plugins/flatpickr/flatpickr.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/plugins/fullcalendar/fullcalendar.min.js"></script>

<!-- END PAGE LEVEL SCRIPTS -->

<script src="<?php echo $config->getCdnurl(); ?>assets/plugins/table/datatable/datatables.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery.jscroll.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery.responsivetabs.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/plugins/toastr/toastr.min.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/IISMethods.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/bootstrap-material-datetimepicker.js"></script>

<script src="<?php echo $config->getCdnurl(); ?>assets/js/qrcode.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/datetimepicker/spectrum.min.js"></script>

<!-- ================================================+
froala
================================================ -->
<script src="<?php echo $config->getCdnurl(); ?>assets/js/froala/froala_editor.min.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/froala/froala_plugins.js"></script>


<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery.caret.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/bootstrap-tagsinput.js"></script>
<script src="<?php echo $config->getCdnurl(); ?>assets/js/tag-input-custom.js"></script>

<script> var endpointurl='<?php echo $config->getEndpointurl() ?>'; </script>
<script> var dirpath='<?php echo $config->getDirpath() ?>'; </script>
<script> var page404url='<?php echo $config->getPage404url() ?>'; </script>


<script>


    $(document).ready(function () {
        App.init();
        
        setTimeout(function(){
            $('#btnAddDetails').click(function(){
                $("#viewDetails").addClass("d-none");
                $("#addDetails").removeClass("d-none");
                $(this).addClass("d-none");
                $("#btnAddCart").removeClass("d-none");
                setTimeout(function(){
                    $("#btnAddCart").addClass("show");
                }, 500);
            });

            $('#btnAddCart').click(function(){
                $("#addDetails").addClass("d-none"); 
                $("#addNewDetails").removeClass("d-none");
                $(this).addClass("d-none");
            });
           

            $('body').on('click', '#openRightSidebar, #menuRightSidebar', function() { 
                resetdata();
                $("#rightSidebar").toggleClass("active-right-sidebar"); 
                $('.overlay').addClass('show');
                $("body").addClass("overflow-hidden");
                $("#rightSidebar").animate({ scrollTop: 0 }, "fast");
            });
            
            $('#openRightSidebarSecondory').click(function(){
                $("#rightSidebarSecondory").toggleClass("active-right-sidebar"); 
                $('.overlay').addClass('show');
                $("body").addClass("overflow-hidden");
            });

            //$('#rightSidebar #btnCloseRightSidebar').click(function(){
            $('body').on('click', '#rightSidebar #btnCloseRightSidebar', function() {       
                $("#rightSidebar").removeClass("active-right-sidebar"); 
                $('.overlay').removeClass('show');
                $("body").removeClass("overflow-hidden");
                resetdata();
             
            });

            $('body').on('click', '#filterSidebar #btnFilterCloseRightSidebar', function() {       
                $("#filterSidebar").removeClass("active-right-sidebar"); 
                $('.overlay').removeClass('show');
                $("body").removeClass("overflow-hidden");
             
            });

            $('#rightSidebarSecondory #btnCloseRightSidebar').click(function(){    
                $("#rightSidebarSecondory").removeClass("active-right-sidebar"); 
                $('.overlay').removeClass('show');
                $("body").removeClass("overflow-hidden");
            
            });
        
            $('.feather-search').click(function(){
                $('#searchinput').focus();
            });
        
            $('body').on('click', '.content-expand', function() {                 
                $('#layoutContent').toggleClass("active-expand");
                $(this).toggleClass("active");
            });

            $('body').on('click', '.content-minimize', function() {                       
                $('#layoutContent > .widget > .widget-content').slideToggle();
                $(this).toggleClass("active");
            });

            $('body').on('click', '.content-360', function() {                       
                $('.customer-view').toggleClass("d-none");
                $(this).toggleClass("active");
            });

            $('.content-resize').click(function(){
                $('#rightSidebarPart.view-appointment').toggleClass("col-lg-7").toggleClass("col-lg-9");
                $('#rightSidebarPart.view-billing-content').toggleClass("col-lg-7").toggleClass("col-lg-6");
                $(this).toggleClass("active");
            });
            $('.service-provider-content li a').click(function(){
                // $('.service-provider-content li a').parent("li").removeClass("active");
                $(this).parent("li").toggleClass("active");
            });

            //bulk delete Start
            $('body').on('click', '.select-bulk-delete', function() {    
                $('.tbl-check').removeClass('d-none'); 
                $(this).addClass('active-bulk-delete');    
               /* $(".delete-selected").toggleClass("d-none");
                $('.table tr .tbl-check').attr('hidden', function(index, attr){
                    return attr == 'hidden' ? null : 'hidden';
                });*/
            });

            $('body').on('click', '.active-bulk-delete', function() {  
                $(this).removeClass('active-bulk-delete');      
                $('.tbl-check').addClass('d-none'); 
            });

            $('body').on('click', '#tblCheckAll', function() {            
                if($(this).prop("checked") == true) {
                    $(this).prop('checked', true);
                    $(this).closest("table").find('.tbl-check .custom-control-input').prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                    $(this).closest("table").find('.tbl-check .custom-control-input').prop('checked', false);
                }
            });
            //bulk delete End
            
            $('.overlay').removeClass('show');    

            $('.menu [data-toggle="tooltip"]').tooltip(
                {
                    template: '<div class="tooltip bs-tooltip-right fade tooltip-for-sidebar"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                }
            );

            $('.btn-tbl[data-toggle="modal"]').tooltip();
            $('[data-toggle="tooltip"]').tooltip();
            setInterval(function () {
                $('[data-toggle="tooltip"]').tooltip('hide'); 
                $('.btn-tbl[data-toggle="modal"]').tooltip('hide');
            }, 1000);

            var scollContent = $('.horizontal-scroll');
            new jScroll(scollContent);
            $(scollContent).jScroll({
                type: 'h',
            });
            

            $(".btn-data-details").on("click", function(){
                $(this).toggleClass("active text-primary text-success");
                $(this).find("i").toggleClass("bi-pencil bi-check2");
                $(this).closest("td").find(".label-view").toggleClass("d-none");
                $(this).closest("td").find(".label-edit").toggleClass("d-none");
                $(this).attr('data-original-title', function(index, attr){
                    return attr == 'Edit' ? 'Save' : 'Edit';
                });
            });
            $("#gridMenu").click(function(event){
                $("#tblDropdownContent").toggleClass("active");
                event.stopPropagation();
            });

            $("#gridCancel, #gridSaveOrder").click(function(){
                $("#tblDropdownContent").removeClass("active");
            });
            $(".grid-list ul").sortable({
                placeholder: "ui-state-highlight"
            });
            $(".grid-list ul").disableSelection();
        }, 500);
        $(".navbar .navbar-item .nav-item a").click(function () {
            var hasId = $(this).attr("id");
            if ($("#notificationDropdown").attr("id") == hasId){
                $(".noti-sidebar").toggleClass("active");
                $(this).parent("li").toggleClass("active");
            } else {
                $(".noti-sidebar").removeClass("active");
                $("#notificationDropdown").parent("li").removeClass("active");
            }
        });

        $('.table-draggable').dragtable();
        
        $("div.daterangepicker-scroll").click(function () {
            $(".daterangepicker").appendTo(this);
        });

        
	    $('.nav.nav-tabs').responsiveTabs(); 

    
        var colorclass=localStorage.getItem('themecoloralhadafAdmin');
        //alert(colorclass);
        $('html').addClass(colorclass);

        $( ".list-unstyled li" ).each(function( index ) 
        {
            if($(this).has('ul').length>0)
            {
                if($(this).find('ul').find('li').siblings().not('.d-none').length==0)
                {
                    $(this).hide();
                    
                }
            }
        });

    });
    $('#advanceOrderDetails').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var recipient = button.data('whatever') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('.modal-title').text(recipient)
    })

    $(window).on('load',function(){
        // $('#infoModal').modal('show');
        //$('html').addClass('theme1');

        
        $('.theme-btns a').click(function(){
            $(".theme-btns a").removeClass("is-active"); 
            var themeValue = $(this).attr("href");
            var themeClass = themeValue.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, ' ');
            $("html").removeClass("theme1 theme2 theme3 theme4 theme5 theme6 theme7 theme8 theme9 theme10 theme11 theme12 theme13 theme14 theme15 theme16 theme17 theme18 theme19 theme20 theme21 theme22 theme23 theme24 theme25 theme26 theme27 theme28 theme29 theme30 theme31 theme32 theme33 theme34 theme35 theme36 theme37 theme38 theme39 theme40 theme41 theme42 theme43 theme44 theme45"); 
            setTimeout(function(){
                $("html").addClass(themeClass);
            }, 100);
            $(this).addClass("is-active"); 
        });
        
        function tabNavActive() {

            // $('.nav.nav-tabs').responsiveTabs(); 
            // $(".nav.nav-tabs li a:not(.dropdown-toggle)").click(function(){
            //     $(this).closest("ul").children("li").find("a").removeClass("active");
            //     $(this).addClass("active");
            //     var tabId = $(this).attr("href");
            //     $(tabId).closest(".tab-content").children(".tab-pane").removeClass("active");
            //     setTimeout(function(){
            //         $(tabId).closest(".tab-content").children(".tab-pane").removeClass("show");
            //     }, 200);
                
            //     setTimeout(function(){
            //         $(tabId).addClass("active");
            //     }, 400);
            //     setTimeout(function(){
            //         $(tabId).addClass("show");
            //     }, 600);
            // 

            $(".nav-tabs > li.dropdown ul.dropdown-menu li a").on('shown.bs.tab',function(e){
                $(this).closest("ul").children("li").find("a").removeClass("active");
                $(this).addClass("active");
                e.target // newly activated tab
                e.relatedTarget // previous active tab
            });
        }
        
               

        $(".nav.nav-tabs li a").click(function(){
            
            setTimeout(function(){
                tabNavActive()
            }, 100);
        });
        setTimeout(function(){
            var pageName = $("#sidebar ul.menu-categories li.menu.active > a span").text();
            var subPageName = $("#sidebar ul.menu-categories li.menu ul li.active a").text();
            
            if(!$('.navbar-expand-sm .navbar-item .nav-page-name a.nav-link span').text()) {
                if( $("#sidebar ul.menu-categories li.menu ul li").hasClass("active")){
                    $(".navbar-expand-sm .navbar-item .nav-page-name").removeClass("d-none");
                    $(".navbar-expand-sm .navbar-item .nav-page-name a.nav-link span").append(subPageName);
                    //$(".menunamelbl").html(subPageName);
                    // $("#customernamelbl").append(subPageName);
                } else if( $("#sidebar ul.menu-categories li.menu").hasClass("active")){
                    $(".navbar-expand-sm .navbar-item .nav-page-name").removeClass("d-none");
                    // $(".navbar-expand-sm .navbar-item .nav-page-name a.nav-link span").append(pageName + " ");
                    // $("#customernamelbl").append(pageName + " ");
                } 
            } else {
                $(".navbar-expand-sm .navbar-item .nav-page-name").removeClass("d-none");
            }

        }, 2000);

        
        setTimeout(function(){
            $("#loader").fadeOut();
            
            $("#loaderprogress .pace-progress").animate({'width':'100%'}, 1000);
            if($('#loaderprogress .pace-progress[style="width: 100%;"]')) {
                $("#loaderprogress").delay( 2000 ).fadeOut();
            }
        }, 1000);
        // $(activate);
        // function activate() {
        //     $('#mainNavTabs')
        //     .scrollingTabs({
        //         enableSwiping: true
        //     })
        //     .on('ready.scrtabs', function() {
        //         $('#mainNavTabs .tab-content').show();
        //     });
        // } 
    });
    /* Get into full screen */
    function GoInFullscreen(element) {
        if(element.requestFullscreen)
            element.requestFullscreen();
        else if(element.mozRequestFullScreen)
            element.mozRequestFullScreen();
        else if(element.webkitRequestFullscreen)
            element.webkitRequestFullscreen();
        else if(element.msRequestFullscreen)
            element.msRequestFullscreen();
    }

    /* Get out of full screen */
    function GoOutFullscreen() {
        if(document.exitFullscreen)
            document.exitFullscreen();
        else if(document.mozCancelFullScreen)
            document.mozCancelFullScreen();
        else if(document.webkitExitFullscreen)
            document.webkitExitFullscreen();
        else if(document.msExitFullscreen)
            document.msExitFullscreen();
    }

    /* Is currently in full screen or not */
    function IsFullScreenCurrently() {
        var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;
        
        // If no element is in full-screen
        if(full_screen_element === null)
            return false;
        else
            return true;
    }

$("#btnFullscreen").on('click', function() {
	if(IsFullScreenCurrently())
		GoOutFullscreen();
	else
		GoInFullscreen($("body").get(0));
});

$(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
	if(IsFullScreenCurrently() || !$(":not(:root):fullscreen::backdrop")) {
		$("#btnFullscreen").addClass('active');
	}
	else {
		$("#btnFullscreen").removeClass('active');
	}
});


function alphaonly(e)
{
    var keyCode = e.which;
    if ( !((keyCode >= 65 && keyCode <= 90) 
    || (keyCode >= 97 && keyCode <= 122) ) 
    && keyCode != 8 && keyCode != 32)
    { 
        e.preventDefault();
    }
}


/*
=====================================================================
|                                                                   |
|                   Validation Regular Expression                   |
|                                                                   |
=====================================================================
*/
 //Aadhar No
jQuery.validator.addMethod("aadharno", function(value, element)
{
    return this.optional(element) || /^\d{4}\d{4}\d{4}$/.test(value);
}, "Please enter a valid Aadhar Card No");

//Pan No
jQuery.validator.addMethod("pancardno", function(value, element)
{
    return this.optional(element) || /[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(value);
}, "Please enter a valid PAN Card No");

//only for alphabets,number ,space and dot
jQuery.validator.addMethod("alphacharacteronly", function(value, element) {
    return this.optional(element) || /^[A-Z@~`!@#$%^&*()_=+\\';:"\/?>.<,-]*$/i.test(value);
}, "Invalid Name"); 

///^[A-Z@~`!@#$%^&*()_=+\\';:"\/?>.<,-]*$/i

//contact validation 
jQuery.validator.addMethod("contact", function(value, element) {
    return this.optional(element) || /^[0]?[6789]\d{9}$/i.test(value);
}, "Invalid Contact");
//india mobile no validation
jQuery.validator.addMethod("mobileno", function(value, element) {
    return this.optional(element) || /^[0]?[6789]\d{9}$/i.test(value);
}, "Invalid mobile no");  
//only for number
jQuery.validator.addMethod("numberonly", function(value, element) {
    return this.optional(element) || /^[0-9]+$/i.test(value);
}, "Invalid Number");

//only for number OR Dot
jQuery.validator.addMethod("numberanddot", function(value, element) {
    return this.optional(element) || /^[0-9\_\.]+$/i.test(value);
}, "Invalid tax");

//india mobile no validation
jQuery.validator.addMethod("gstno", function(value, element) {
    return this.optional(element) || /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(value);
}, "Invalid VAT number");

//only for url
jQuery.validator.addMethod("url", function(value, element) {
	return this.optional(element) || /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/i.test(value);
}, "Please Enter Valid Url"); 

//PAN no
jQuery.validator.addMethod("panno", function(value, element)
{
    return this.optional(element) || /^[A-Z]{5}\d{4}[A-Z]{1}$/.test(value);
}, "Please enter a valid PAN");

 //only for alphabets
jQuery.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-zA-Z\s\.]+$/i.test(value);
}, "Invalid name");  

//for Vehicle number
jQuery.validator.addMethod("vehicleno", function(value, element) {
    return this.optional(element) || /^[a-z]{2}[0-9]{2}[a-z]{1,2}[0-9]{4}$/i.test(value);
    }, "Invalid Vehicle No");

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

function minusnumbonly(e) {
    var k = e.which;
    var ok = k >= 48 && k <= 57 || // 0-9
        k ==8 || //backspace
        k==0 || //arrow key
        k==45 || // Minus Sign
        k==46; //. sign
    if (!ok){
        e.preventDefault();
    }
}


function arrowkeyonly(e) {
    var k = e.which;
    var ok =  k==0  //arrow key
    if (!ok){
        e.preventDefault();
    }
}

$('body').on('click','#openRightSidebarPart', function () {
    $("#rightSidebarPart").toggleClass("active-right-sidebar");
    $("#rightSidebarPart.view-appointment").removeClass("col-lg-9").addClass("col-lg-7");
    $("#rightSidebarPart.view-billing-content").removeClass("col-lg-9").addClass("col-lg-5");
    $(".content-resize").removeClass("active");
    $(this).toggleClass("active-close");
});
        


$('body').on('click','#rightSidebarPart #btnCloseRightSidebar', function () {
    $("#rightSidebarPart").removeClass("active-right-sidebar");
    $("#rightSidebarPart.view-appointment").removeClass("col-lg-9").addClass("col-lg-7");
    $("#rightSidebarPart.view-billing-content").removeClass("col-lg-9").addClass("col-lg-5");
    $(".content-resize").removeClass("active");
    $("#openRightSidebarPart").removeClass("active-close");

});


$('body').on('change','#topbranchid', function () {

    var branchid=$('#topbranchid').val();
    var headersdata= {Accept: 'application/json',platform: 1,userpagename:'master',useraction:'editright',masterlisting:false};
    formdata = new FormData();
    formdata.append("action", "changebranchsession");
    formdata.append("branchid", branchid);
    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>master',formdata,headersdata,OnsuccessBranchsession,OnErrorBranchsession); 
});

function OnsuccessBranchsession()
{
    fillbranch();
    location.reload();
}

function OnErrorBranchsession()
{
}


$('body').on('change','.selectpicker',function(){
    var id=$(this).val();
    if(parseInt(id)>0 || id!='')
    {
        $(this).parent().removeClass('error');
        
        $(this).parent().find('label.error').html('');
    }
});



/************************* Start For Forgot Password ***************************/
//Forgot Password Button Click Event
$('body').on('click','.forgot-pass-link',function(){
    $('#forgotpassModal').modal('show');
});


if($('#forgotpasswordForm').length)
{		
    $('#forgotpasswordForm').validate({
        rules:{
            forgotuname:{
                required: true,			
            },
        },messages:{
            forgotuname:{
                required:"Username is required",
            },
        },
        submitHandler: function(form){
            $('.loading').show()
            jqXHR = $.ajax({
                url : "<?php echo $config->getEndpointurl(); ?>forgotpassword.php?action=forgotpassword",
                type : "POST",
                dataType:'json',
                headers: {  
                    'Accept': 'application/json',  
                    'platform': 1,
                    'key': '6abf96c8e683fb1df7507e821a597b50'
                },
                data: $('#forgotpasswordForm').serialize(),
                success : function(data) 
                {
                    var JsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(JsonData);
                    $('.loading').hide();	
                    if(resultdata.status==0)
                    {
                        alertify(resultdata.message, '0');
                    }
                    else if(resultdata.status==1)
                    {
                        $('#forgotpassModal').modal('hide');
                        $('#forgotpassotpModal').modal('show');
                        $('#forgotpassotpModal #verifiedotp').val('');
                        $('#forgotpassotpModal #otpnotifydiv').html('<span style="color:green">'+resultdata.message+'</span>');
                        $('#forgotpassotpModal #hiddenid').val(resultdata.username);
                        $("#forgotpasswordForm").validate().resetForm();
                        $('#forgotpasswordForm').trigger("reset");
                    }
                }
            });
        },
    });
}

if($('#forgotpassotpForm').length)
{		
    $('#forgotpassotpForm').validate({
        rules:{
            verifiedotp:{
                required: true,			
            },
        },messages:{
            verifiedotp:{
                required:"Verification Code is required",
            },
        },
        submitHandler: function(form){
            $('.loading').show()
            jqXHR = $.ajax({
                url : "<?php echo $config->getEndpointurl(); ?>forgotpassword.php?action=verifiedchangepasscode",
                type : "POST",
                dataType:'json',
                headers: {  
                    'Accept': 'application/json',  
                    'platform': 1,
                    'key': '6abf96c8e683fb1df7507e821a597b50'
                },
                data: $('#forgotpassotpForm').serialize(),
                success : function(data) 
                {
                    var JsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(JsonData);
                    $('.loading').hide();	
                    if(resultdata.status==0)
                    {
                        alertify(resultdata.message, '0');
                    }
                    else if(resultdata.status==1)
                    {
                        $('#forgotpassotpModal').modal('hide');
                        $('#changedforgotpassModal').modal('show');
                        $('#changedforgotpassModal #hiddenid').val(resultdata.hiddenid);
                        $("#forgotpassotpForm").validate().resetForm();
                        $('#forgotpassotpForm').trigger("reset");
                        $('#changedforgotpassModal #newpassword').val('');
                        $('#changedforgotpassModal #newconfimpass').val('');
                    }
                }
            });
        },
    });
}

if($('#changedforgotpassForm').length)
{		
    $('#changedforgotpassForm').validate({
        rules:{
            newpassword:{
                required:true
            },
            newconfimpass:{
                required:true,
                equalTo: "#newpassword"
            }
        },messages:{
            newpassword:{
                required:"Password is required"
            },
            newconfimpass:{
                required:"Confirm password is required",
                equalTo: "Confirm password must be same as password"
            }
        },
        submitHandler: function(form){
            $('.loading').show()
            jqXHR = $.ajax({
                url : "<?php echo $config->getEndpointurl(); ?>forgotpassword.php?action=changeforgotpassword",
                type : "POST",
                dataType:'json',
                headers: {  
                    'Accept': 'application/json',  
                    'platform': 1,
                    'key': '6abf96c8e683fb1df7507e821a597b50'
                },
                data: $('#changedforgotpassForm').serialize(),
                success : function(data) 
                {
                    var JsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(JsonData);
                    $('.loading').hide();	
                    if(resultdata.status==0)
                    {
                        alertify(resultdata.message, '0');
                    }
                    else if(resultdata.status==1)
                    {
                        $('#changedforgotpassModal').modal('hide');
                        alertify(resultdata.message, '1');
                        $("#changedforgotpassForm").validate().resetForm();
                        $('#changedforgotpassForm').trigger("reset");
                    }
                }
            });
        },
    });
}
/************************* End For Forgot Password ***************************/

</script>