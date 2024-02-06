<?php require_once dirname(__DIR__, 1).'\config\init.php';
?>
<!--page header section start-->
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="breadcrumb-list notranslate">
                    <li><a href="javascript:void(0)" class="clspageparentmenu lan-home-menu" pagename="home">Home</a></li>
                    <li class="active lan-rangebook-menu">Range Booking</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing pt-4">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading text-center">
                    <h2 class="title mb-0 notranslate lan-rangebook-menu">Range Booking</h2>
                </div>
            </div>
            <?php
            if($ProjectSetting->getRBVideo() != '')
            {
                $rb_videourl=$config->getImageurl().$ProjectSetting->getRBVideo();
            ?>
            <div class="col-12 video-content mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <video class="w-100" src="<?php echo $rb_videourl ?>" autoplay loop playsinline muted width="100%"></video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>



            <?php
            $clswithloginnone='d-none';
            $clswithoutloginnone='';
            if($LoginInfo->getUid()!='' && $LoginInfo->getIsguestuser()==0 && $LoginInfo->getUtypeid() != $config->getAdminutype())
            {
                $clswithloginnone='';
                $clswithoutloginnone='d-none';
            }    
            ?>
            
            <!------------------ Start For Member  ------------------>
            <div class="col-12 <?php echo $clswithloginnone ?>">
                <div class="card">
                    <div class="card-body">
                        <form method="post" class="row" id="rangebookForm">
                            <div class="col-12">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="calendar-responsive mx-auto ">
                                            <div class="calendar-full-banner mx-auto opacity-1">
                                                <div class="calendar-content rangebooking-calendar">
                                                    <div id="" class="picker-d"><input type="hidden" id="datecalendarinput" name="datecalendarinput" value="<?php echo date('d/m/Y') ?>" /></div>
                                                    <div id="datecalendar" class="picker-d"></div>
                                                </div>
                                            </div>
                                            <div class="text-center mt-3 d-none">
                                                <a href="appointments.php" class="btn btn-brand-01 m-0">Book Now</a>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-12 mt-4">
                                        <div class="hour-section">
                                            <ul class="hour-list text-center">
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button class="btn btn-brand-01" type="submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!------------------ End For Member  ------------------>

            

            <!------------------ Start For Guest  ------------------>
             <div class="col-12 <?php echo $clswithoutloginnone ?>">
                <div class="card">
                    <div class="card-body">
                        <form method="post" class="row" id="rangebookingForm">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_fname" type="text" required="required" id="rb_fname" name="rb_fname">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_fname">First Name <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_lname" type="text" required="required" id="rb_lname" name="rb_lname">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_lname">Last Name <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_email" type="email" required="required" id="rb_email" name="rb_email">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_email">Email <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_mobile" type="text" required="required" id="rb_mobile" name="rb_mobile" maxlength="12" onkeypress="numbonly(event)">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_mobile">Mobile Number <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_dob" type="text" required="required" id="rb_dob" name="rb_dob" autocomplete="off" readonly="readonly">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_dob">Date of Birth <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_qataridno" type="text" required="required" id="rb_qataridno" name="rb_qataridno">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_qataridno">Qatar id Number <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_qataridexpiry" type="text" required="required" id="rb_qataridexpiry" name="rb_qataridexpiry" autocomplete="off"  readonly="readonly">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_qataridexpiry">Qatar id Expiry <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_passportidno" type="text" required="required" id="rb_passportidno" name="rb_passportidno">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_passportidno">Passport id Number <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_passportidexpiry" type="text" required="required" id="rb_passportidexpiry" name="rb_passportidexpiry" autocomplete="off"  readonly="readonly">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_passportidexpiry">Passport id Expiry <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_nationality" type="text" required="required" id="rbnationality" id="rb_nationality" name="rb_nationality">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_nationality">Nationality <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <input class="form-control rb_companyname" type="text" id="rb_companyname" name="rb_companyname">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_companyname">Company</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <div class="form-group validate-input">
                                            <select name="rb_servicetypeid" id="rb_servicetypeid" class="form-control selectpicker" data-size="6" data-live-search="true">
                                                
                                            </select>
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_servicetypeid">Type of Service</label>
                                        </div>
                                        
                                    </div>
                                    <div class="col-12 col-sm-12">
                                        <div class="form-group validate-input">
                                            <textarea class="form-control rb_address" id="rb_address" name="rb_address" rows="2"></textarea>
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rb_address">Address</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <button class="btn btn-brand-01" type="submit">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!------------------ End For Guest  ------------------>
            


        </div>
    </div>
</section>


<script src="<?php echo $config->getCdnurl(); ?>assets/js/webmenuclick.js"></script>
<script>
    $(document).ready(function () {
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.lirangebooking").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.lirangebooking").addClass("active");

        

        const todayDate = new Date();

        var caldate1 = getUrlVars()['date'];
        if(caldate1)
        {
            var caldatearr = caldate1.split('/');
            caldate = caldatearr[2]+'-'+caldatearr[1]+'-'+caldatearr[0];
            $('.rangebooking-calendar #datecalendarinput').val(caldate1);
        }
        else
        {
            caldate = todayDate;
        }

        new When({
            input: document.getElementById('datecalendarinput'),
            container: document.getElementById('datecalendar'),
            singleDate: true,
            
            startDate: caldate,
            minDate: todayDate,
            showHeader: false,
            outputFormat: "DD/MM/YYYY",
            inline: true
        });

        


        <?php
        if($LoginInfo->getUid()!='' && $LoginInfo->getIsguestuser()==0 && $LoginInfo->getUtypeid() != $config->getAdminutype())
        {
        ?>
            //List Booking Time Slot
            listbookingtimeslot();
        <?php
        }
        else
        {
        ?>
            window.location='home';
            //List Service Type
            listrbservicetype();
        <?php
        }
        ?>
       
    });


    /************************* Start For Service Type Data ******************************/
    function listrbservicetype()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'JSON'};
        formdata = new FormData();
        formdata.append("action", "listservicetype");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistrbservicetypedata,OnErrorMasterData); 
    }

    function Onsuccesslistrbservicetypedata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        var servicetypedata='<option value="">Select Service Type</option>';
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {
            if(resultdata.isservicetype > 0)
            {
                for(var i in resultdata.servicetype)
                {
                    servicetypedata+='<option value="'+resultdata.servicetype[i].id+'">'+resultdata.servicetype[i].type+'</option>';
                }
            }
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 

        $('#rangebookingForm #rb_servicetypeid').html(servicetypedata);
        $('#rangebookingForm #rb_servicetypeid').selectpicker('refresh');
    }

    function OnErrorMasterData(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }
    /************************* End For Service Type Data ******************************/



    /************************* Start For Range Booking Form Before Login ******************************/
    new When({
        input: document.getElementById('rb_dob'),
        singleDate: true,
        outputFormat: "DD/MM/YYYY",
        showHeader: false,
        maxDate: '<?php echo date('Y-m-d') ?>',
    });
    new When({
        input: document.getElementById('rb_qataridexpiry'),
        singleDate: true,
        outputFormat: "DD/MM/YYYY",
        showHeader: false,
    });
    new When({
        input: document.getElementById('rb_passportidexpiry'),
        singleDate: true,
        outputFormat: "DD/MM/YYYY",
        showHeader: false,
    });

    //Validate Range Booking Form
    if($('#rangebookingForm').length){		
        $('#rangebookingForm').validate({
            rules:{
                rb_fname:{
                    required: true,					
                },
                rb_lname:{
                    required: true,				
                },
                rb_email:{
                    required: true,
                    email:true,
                },
                rb_mobile:{
                    required: true,
                    number:true,
                    maxlength: 12
                },
                rb_qataridno:{
                    required: true,
                },
                rb_qataridexpiry:{
                    required: true,
                },
                rb_dob:{
                    required: true,
                },
                rb_nationality:{
                    required: true,
                },
                rb_passportidno:{
                    required: true,
                },
                rb_passportidexpiry:{
                    required: true,
                },
                
            },messages:{
                rb_fname:{
                    required: "First name is required",						
                },
                rb_lname:{
                    required: "Last name is required",					
                },
                rb_email:{
                    required: "Email is required",
                },
                rb_mobile:{
                    required: "Mobile number is required",	
                },
                rb_qataridno:{
                    required: "Qatar id number is required",		
                },
                rb_qataridexpiry:{
                    required: "Qatar id expiry is required",		
                },
                rb_dob:{
                    required: "Date of birth is required",		
                },
                rb_nationality:{
                    required: "Nationality is required",		
                },
                rb_passportidno:{
                    required: "Passport id number is required",
                },
                rb_passportidexpiry:{
                    required: "Passport id expiry is required",	
                },
            },
            submitHandler: function(form){

                $("#preloader").fadeIn();
                var pagename=getpagename();
                formdata = new FormData(form);
                formdata.append("action", "insertrangebooking");
                var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessinsertrangebooking,OnErrorinsertrangebooking); 
                
            },
        });
    }


    function Onsuccessinsertrangebooking(content)
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
            $("#rangebookingForm").validate().resetForm();
            $('#rangebookingForm').trigger("reset");
            toastr.success(resultdata.message);
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 
    }


    function OnErrorinsertrangebooking(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    } 
    /************************* End For Range Booking Form Before Login ******************************/



    /************************* Start For Range Booking Time Slot Data ******************************/
    $('body').on('click','.rangebooking-calendar #datecalendar .day',function(){
        setTimeout(function(){ 
            listbookingtimeslot();
        }, 300);
    });


    function listbookingtimeslot()
    {
        var date = $('.rangebooking-calendar #datecalendarinput').val();

        $("#preloader").fadeIn();
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'JSON'};
        formdata = new FormData();
        formdata.append("action", "listbookingtimeslot");
        formdata.append("date", date);
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistbookingtimeslotdata,OnErrorMasterData); 
    }

    function Onsuccesslistbookingtimeslotdata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        var bookingtimeslotdata='';
        if(resultdata.status==0)
        {
            
        }
        else if(resultdata.status==1)
        {
            var k=0;
            for(var i in resultdata.timeslot)
            {
                
                if(resultdata.timeslot[i].isbooked == 1)
                {
                    var readonlyclass = 'readonly';
                }
                else
                {
                    var readonlyclass = '';
                    k++;
                }

                var activeclass="";
                if(parseInt(k) == 1)
                {
                    activeclass="active";
                }
                bookingtimeslotdata+='<li class="'+readonlyclass+' '+activeclass+'">';
                bookingtimeslotdata+='<a href="javascript:void(0);" class="btn btn-brand-04" data-fromtime="'+resultdata.timeslot[i].fromtime+'" data-totime="'+resultdata.timeslot[i].totime+'">'+resultdata.timeslot[i].name+'</a>';
                bookingtimeslotdata+='</li>';
            }
        }
        $('.hour-section ul.hour-list').html(bookingtimeslotdata);
        $("#preloader").fadeOut();


        $(".hour-list li a").on('click', function() {
            $(".hour-list li").removeClass("active");
            $(this).parent("li").addClass("active");
        });
    }
    /************************* End For Range Booking Time Slot Data ******************************/


    /************************* Start For Range Booking Form After Login ******************************/
    //Validate Range Booking Form
    if($('#rangebookForm').length){		
        $('#rangebookForm').validate({
            rules:{
                
            },messages:{
                
            },
            submitHandler: function(form){

                var date = $('.rangebooking-calendar #datecalendarinput').val();

                var fromtime = $('.hour-section ul.hour-list li.active a').attr('data-fromtime');
                var totime = $('.hour-section ul.hour-list li.active a').attr('data-totime'); 

                $("#preloader").fadeIn();
                var pagename=getpagename();
                formdata = new FormData(form);
                formdata.append("action", "insertrangebookingslot");
                formdata.append("fromtime", fromtime);
                formdata.append("totime", totime);
                formdata.append("date", date);
                var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessinsertrangebookingslot,OnErrorinsertrangebookingslot); 
                
            },
        });
    }


    function Onsuccessinsertrangebookingslot(content)
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
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 
    }


    function OnErrorinsertrangebookingslot(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    } 
    /************************* End For Range Booking Form After Login ******************************/
</script>