<?php 
require_once dirname(__DIR__, 1).'\config\init.php';
?>
    
<div class="layout-px-spacing">

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-12 col-xl-12 mb-3">
                    <div class="widget">
                        <div class="widget-content">
                            <form class="text-left validate-form" id="contentsettingForm" method="post" action="contentsetting" enctype="multipart/form-data">
                                
                                <div class="col-12 col-md-2 col-lg-2 col-xl-8 offset-2">
                                    <h6 class="inv-main-title mb-3">Home Banner</h6>
                                </div>

                                <div class="col-8 offset-2">
                                    <div class="input-group">
                                        <label class="mb-1">Title </label>
                                        <label class="ml-auto "></label>
                                    </div>
                                    <div class="input-group mb-3">
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Title"/>
                                    </div>
                                </div>
                                
                                <div class="col-8 offset-2">
                                    <div class="input-group">
                                        <label class="mb-1">Button Text </label>
                                        <label class="ml-auto "></label>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text" id="btntext" name="btntext" class="form-control" placeholder="Button Text"/>
                                    </div>
                                </div>

                                <div class="col-8 offset-2">
                                    <div class="input-group">
                                        <label class="mb-1">Button Redirect Url </label>
                                        <label class="ml-auto "></label>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text" id="btntexturl" name="btntexturl" class="form-control" placeholder="Button Redirect Url"/>
                                    </div>
                                </div>

                                <div class="row offset-2">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-exl-3">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Video <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3 ">
                                            <img src="" id="imgpreview" class="d-none" style="height: 200px; max-width: 100%" />
                                            <input type="file" class="form-control d-none" id="video" name="video" accept="video/*">
                                            <label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="video">
                                                <span style="background-image: url(assets/img/salon.jpg);"></span>
                                                <i class="bi bi-folder2-open d-block pt-1"></i> Browse...
                                            </label>
                                            <span style="color:#B00">** Please upload Video in mp4 Format</span>
                                            <span style="color:#B00" class="imgsizelbl"></span>
                                        </div> 
                                    </div>
                                </div>

                                <div class="col-12 col-md-2 col-lg-2 col-xl-8 offset-2">
                                    <h6 class="inv-main-title mb-3">Range Booking Banner</h6>
                                </div>
                                <div class="row offset-2">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-exl-3">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Video <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3 ">
                                            <img src="" id="imgpreview" class="d-none" style="height: 200px; max-width: 100%" />
                                            <input type="file" class="form-control d-none" id="rbvideo" name="rbvideo" accept="video/*">
                                            <label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="rbvideo">
                                                <span style="background-image: url(assets/img/salon.jpg);"></span>
                                                <i class="bi bi-folder2-open d-block pt-1"></i> Browse...
                                            </label>
                                            <span style="color:#B00">** Please upload Video in mp4 Format</span>
                                            <span style="color:#B00" class="imgsizelbl"></span>
                                        </div> 
                                    </div>
                                </div>

                                <?php 
                                    if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                    {
                                        ?>
                                        <div class="row col-8 offset-2">
                                            <div class="ml-auto">
                                                <div class="input-group mb-0">
                                                    <button type="submit" class="btn btn-primary m-0" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
                                                    <button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                ?>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- CONTENT AREA -->
</div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            fillcontent();
        });

        
        function resetdata()
        {
            $("#contentsettingForm").validate().resetForm();
            $('#contentsettingForm').trigger("reset");

            Edittimeformnamechange(2);   
            fillcontent();
        }



        // LOGO
        $("#video").on('change',function(){
            var img = $("#video").val();
            if(img)
            {
                $('#imgpreview').addClass('d-none');
            }
            else
            {
                $('#imgpreview').removeClass('d-none');
            }
        });

        $("#video").on('fileclear',function(){
            $('#imgpreview').removeClass('d-none');
        });



        //Fill content
        function fillcontent()
        {
            var contenttypeid = $('#contenttypeid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};

            formdata = new FormData();
            formdata.append("action", "fillcontent");

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillcontent,OnErrorfillcontent);
        }
        function Onsuccessfillcontent(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            $('#contentsettingForm  #title').val(resultdata.home_top_text);
            $('#contentsettingForm  #btntext').val(resultdata.home_top_buttontext);
            $('#contentsettingForm  #btntexturl').val(resultdata.home_top_buttonurl);

            // if(resultdata.home_top_video)
            // {
            //     $('#imgpreview').removeClass('d-none');
		    //     $('#imgpreview').attr('src',resultdata.home_top_video);
            // }
            // else
            // {
            //     $('#imgpreview').addClass('d-none');
		    //     $('#imgpreview').attr('src','');
            // }

            // if(resultdata.rb_video)
            // {
            //     $('#imgpreview1').removeClass('d-none');
		    //     $('#imgpreview1').attr('src',resultdata.rb_video);
            // }
            // else
            // {
            //     $('#imgpreview1').addClass('d-none');
		    //     $('#imgpreview1').attr('src','');
            // }
        }

        function OnErrorfillcontent(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

        if($('#contentsettingForm').length){		
            $('#contentsettingForm').validate({
                rules:{
                    video:{
                        required: true,			
                    },
                },messages:{
                    video:{
                        required:"Video is required",
                    },
                },
                submitHandler: function(form){
                    $('#loaderprogress').show();
                    var descr = $('#contentdescr').froalaEditor('html.get')
                    var pagename=getpagename();
                    formdata = new FormData(form);
                    
                    formdata.append("action", "insertsetting");
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessContentSettingDataSubmit,OnErrorContentSettingDataSubmit)

			    },
            });
        }

        function OnsuccessContentSettingDataSubmit(data)
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
                fillcontent();
            }
        }

        function OnErrorContentSettingDataSubmit(data)
        {
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

       

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
