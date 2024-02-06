<?php 
require_once dirname(__DIR__, 1).'\config\init.php';
?>
    
<div class="layout-px-spacing">

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <div class="col-12">
            <form class="text-left validate-form" id="changeemailForm" method="post" action="myprofile" enctype="multipart/form-data">
                <input type="hidden" id="formevent" name="formevent" value='editright'>
                <input type="hidden" id="id" name="id" value=''>

                <div class="col-lg-12 col-md-12 col-12 layout-spacing">
                    <div class="widget">
                        <div class="widget-title">PROFILE</div>
                        <div class="widget-content row">
                            <div class="col-12 col-md-6 mx-auto">
                                <div class="input-group">
                                    <label class="mb-1">Email Id <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Email Id" id="emailid" name="emailid">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mx-auto">
                                <div class="input-group">
                                    <label class="mb-1">Mobile Number 1 <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Mobile Number 1" id="mobilenumber1" maxlength="10" onkeypress="numbonly(event)"  name="mobilenumber1" readonly="readonly">
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mx-auto">
                                <div class="input-group">
                                    <label class="mb-1">Mobile Number 2</label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Mobile Number 2" id="mobilenumber2" maxlength="10" onkeypress="numbonly(event)"  name="mobilenumber2">
                                </div>
                            </div>

                            

                            <?php 
                            //if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                            //{
                                ?>
                                <div class="row col-12 col-md-6 mx-auto">
                                    <div class="ml-auto">
                                        <div class="input-group mb-0">
                                            <button type="submit" class="btn btn-primary m-0" id="btnsubmit"><?php echo $config->getUpdateMyProfile(); ?></button>
                                        </div>
                                    </div>
                                </div>
	
                            <?php
                            //}
                            ?>

                        </div>
                    </div>
                </div> 
            </form>
            <!-- <button class="btn btn-primary m-0 btn-loader"> Loader btn</button>
            <button class="btn btn-secondary m-0 btn-loader"> Loader btn</button> -->

            <form class="text-left validate-form" id="changepassForm" method="post" action="myprofile" enctype="multipart/form-data">
                <input type="hidden" id="formevent" name="formevent" value='editright'>
                <input type="hidden" id="id" name="id" value=''>

                <div class="col-lg-12 col-md-12 col-12 layout-spacing">
                    <div class="widget">
                        <div class="widget-title">CHANGE PASSWORD</div>
                        <div class="widget-content row">
                            <div class="col-12 col-md-6 mx-auto">
                                <div class="input-group">
                                    <label class="mb-1">Old Password <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" id="opass" name="opass" class="form-control" placeholder="Enter old password" required="required"  />
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mx-auto">
                                <div class="input-group">
                                    <label class="mb-1">New Password <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" id="npass" name="npass" class="form-control" placeholder="Enter new password" required="required" />
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mx-auto">
                                <div class="input-group">
                                    <label class="mb-1">Confirm Password <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" id="cpass" name="cpass" class="form-control" placeholder="Enter confirm password" required="required" />
                                </div>
                            </div>
         
                            <?php 
                            //if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                            //{
                                ?>
                                <div class="row col-12 col-md-6 mx-auto">
                                    <div class="ml-auto">
                                        <div class="input-group mb-0">
                                            <button type="submit" class="btn btn-primary m-0" id="btnsubmit"><?php echo $config->getChangePassword(); ?></button>
                                        </div>
                                    </div>
                                </div>
                                
                            <?php
                            //}
                            ?>

                        </div>
                    </div>
                </div> 
            </form>

        </div>
    </div>
<!-- CONTENT AREA -->
</div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            // $(".btn-loader").click(function(){
            //     var loaderBtn = $(this);
            //     loaderBtn.append('<div class="btn-preloader"><img src="assets/img/btn-loader.gif" alt="btn-loader" /></div>');
            //     loaderBtn.attr('disabled','disabled');
            //     setTimeout(function () {
            //         loaderBtn.find(".btn-preloader").remove();
            //         loaderBtn.removeAttr('disabled','disabled');
            //     }, 10000);
            // })
            fillprofiledata();
        });

        
        //Start Fill Setting Data
        function fillprofiledata()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false};

            formdata = new FormData();
            formdata.append("action", "fillprofiledata");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillprofiledata,OnErrorfillprofiledata); 
        }

        function Onsuccessfillprofiledata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            if(resultdata.status==0)
            {
                
            }
            else if(resultdata.status==1)
            {
                $('#emailid').val(resultdata.email);
                $('#mobilenumber1').val(resultdata.contact);
                $('#mobilenumber2').val(resultdata.mobilenumber2); 
               
            }
        }

        function OnErrorfillprofiledata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
        //End Fill Setting Data



        //Update Profile
        if($('#changeemailForm').length){		
            $('#changeemailForm').validate({
                rules:{
                    emailid:{
                        required: true,
                        email:true,				
                    },
                    mobilenumber1:{
                        required: true,
                    }
                },messages:{
                    emailid:{
                        required: "Email address is required",
                    },
                    mobilenumber1:{
                        required:"Mobile number 1 is required",
                    }
                },
                submitHandler: function(form){
 
                    formdata = new FormData(form);
                    formdata.append("action", "updateprofiledata");

                    var pagename=getpagename();
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessProfileData,OnErrorProfileData); 

			    },
            });
        }

        function OnsuccessProfileData(data)
        {
            var JsonData = JSON.stringify(data);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#loaderprogress').hide();	
            if(resultdata.status==0)
            {
                alertify(resultdata.message, '0');
            }
            else if(resultdata.status==1)
            {
                alertify(resultdata.message, '1');
                fillprofiledata()
            }
        }

        function OnErrorProfileData(data)
        {
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }



        //Change Password
        if($('#changepassForm').length){		
            $('#changepassForm').validate({
                rules:{
                    opass:{
                        required:true
                    },
                    npass:{
                        required:true
                    },
                    cpass:{
                        required:true,
                        equalTo: "#npass"
                    }
                },messages:{
                    opass:{
                        required:"Old password is required"
                    },
                    npass:{
                        required:"Password is required"
                    },
                    cpass:{
                        required:"Confirm password is required",
                        equalTo: "Confirm password must be same as password"
                    }
                },
                submitHandler: function(form){
 
                    formdata = new FormData(form);
                    formdata.append("action", "changepassword");

                    var pagename=getpagename();
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessPasswordData,OnErrorPasswordData); 

			    },
            });
        }

        function OnsuccessPasswordData(data)
        {
            var JsonData = JSON.stringify(data);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#loaderprogress').hide();	
            if(resultdata.status==0)
            {
                alertify(resultdata.message, '0');
            }
            else if(resultdata.status==1)
            {
                alertify(resultdata.message, '1');

                $("#changepassForm").validate().resetForm();
                $('#changepassForm').trigger("reset");
                //fillprofiledata()
            }
        }

        function OnErrorPasswordData(data)
        {
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
