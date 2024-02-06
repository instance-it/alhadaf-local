<?php 
require_once dirname(__DIR__, 1).'\config\init.php';
?>
    
<div class="layout-px-spacing">

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <div class="col-12">
            <form class="text-left validate-form" id="settingForm" method="post" action="setting" enctype="multipart/form-data">
                <input type="hidden" id="formevent" name="formevent" value='addright'>
                <input type="hidden" id="id" name="id" value=''>

                <div class="col-lg-12 col-md-12 col-12 layout-spacing">
                    <div class="widget">
                        <div class="widget-content row">
                            
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="mb-1">Twitter Link</label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="url" id="twitterlink" name="twitterlink" class="form-control" placeholder="Enter Twitter Link" />
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="input-group">
                                    <label class="mb-1">Instagram Link</label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="url" id="instagramlink" name="instagramlink" class="form-control" placeholder="Enter Instagram Link" />
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="input-group">
                                    <label class="mb-1">Facebook Link</label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="url" id="facebooklink" name="facebooklink" class="form-control" placeholder="Enter Linkedin Link" />
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="input-group">
                                    <label class="mb-1">Whatsapp Number</label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" id="whatsappno" name="whatsappno" class="form-control" onkeypress="numbonly(event)" maxlength="12" placeholder="Enter Whatsapp Number" />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group">
                                    <label class="mb-1">IFrame Map Link</label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" id="iframemaplink" name="iframemaplink" class="form-control" placeholder="Enter IFrame Map Link" />
                                </div>
                            </div>


                            <div class="col-12 mt-3">
                                <h6 class="inv-main-title">Range Booking Setting</h6>
                            </div>

                            <div class="col-4">
                                <div class="input-group">
                                    <label class="mb-1">From Time<span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="work_fromtime" id="work_fromtime" placeholder="From Time" onpaste="return false" />
                                </div>
                            </div>
                            
                            <div class="col-4">
                                <div class="input-group">
                                    <label class="mb-1">To Time<span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="work_totime" id="work_totime" placeholder="To Time" onpaste="return false" />
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="input-group">
                                    <label class="mb-1">Duration (Minute)<span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="book_duration" id="book_duration" placeholder="Duration (Minute)" onkeypress="numbonly(event)" maxlength="3" />
                                </div>
                            </div>


                        </div>
                    </div>
                </div> 
                
                <?php 
                    if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                    {
                        ?>
                        <div class="col-auto ml-auto">
                            <div class="input-group mb-0">
                                <button type="submit" class="btn btn-primary m-0" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
                                <button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
                            </div>
                        </div>	
                        <?php
                    }
                ?>
                
            </form>
        </div>
    </div>
<!-- CONTENT AREA -->
</div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            
            fillsettingdata();
        });


        function resetdata()
        {
            $("#settingForm").validate().resetForm();
            $('#settingForm').trigger("reset");
            
        }


        $('#work_fromtime').bootstrapMaterialDatePicker({
            date: false,
            format: 'hh:mm A'
        }).on('change', function(e, date)
        {
            $('#work_totime').bootstrapMaterialDatePicker('setMinDate',date);
			$('#work_totime').val($('#work_fromtime').val());
            $(this).removeClass('error');
            $(this).parent('div').removeClass('error');  
	        $(this).parent().find('label.error').html('');
        });

        $('#work_totime').bootstrapMaterialDatePicker({
            date: false,
            format: 'hh:mm A',
        }).on('change', function(e, date)
        {
            $(this).removeClass('error');
            $(this).parent('div').removeClass('error');  
	        $(this).parent().find('label.error').html('');
        });
        


        //Start Fill Setting Data
        function fillsettingdata()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false};

            formdata = new FormData();
            formdata.append("action", "fillsettingdata");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillsettingdata,OnErrorfillsettingdata); 
        }

        function Onsuccessfillsettingdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            if(resultdata.status==0)
            {
                
            }
            else if(resultdata.status==1)
            {
                $('#twitterlink').val(resultdata.twitterlink);
                $('#instagramlink').val(resultdata.instagramlink);  
                $('#facebooklink').val(resultdata.facebooklink); 
                $('#whatsappno').val(resultdata.whatsappno); 
                $('#iframemaplink').val(resultdata.iframemaplink);  

                $('#work_fromtime').val(resultdata.work_fromtime);  
                $('#work_totime').val(resultdata.work_totime);  
                $('#book_duration').val(resultdata.book_duration);  
            }
        }

        function OnErrorfillsettingdata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
        //End Fill Setting Data

        if($('#settingForm').length){		
            $('#settingForm').validate({
                rules:{
                    twitterlink:{
                        url:true,
                    },
                    instagramlink:{
                        url:true,
                    },
                    facebooklink:{
                        url:true,
                    },
                    whatsappno:{
                        number:true,
                    },
                    iframemaplink:{
                        //url:true,
                    },
                    work_fromtime:{
                        required: true
                    },
                    work_totime:{
                        required: true
                    },
                    book_duration:{
                        required: true,
                        min: 1
                    },
                    
                },messages:{
                    // homepagenotice:{
                    //     required:"Home Page Notice is required",
                    // },
                    work_fromtime:{
                        required: "From time is required"
                    },
                    work_totime:{
                        required: "To time is required"
                    },
                    book_duration:{
                        required: "Duration is required",

                    },
                    
                },
                submitHandler: function(form){
                    
                    formdata = new FormData(form);
                    formdata.append("action", "insertsetting");

                    var pagename=getpagename();
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessSettingData,OnErrorSettingData); 

			    },
            });
        }

        function OnsuccessSettingData(data)
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
                fillsettingdata()
            }
        }

        function OnErrorSettingData(data)
        {
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
