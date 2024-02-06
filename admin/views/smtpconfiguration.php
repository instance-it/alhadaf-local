<?php 
require_once dirname(__DIR__, 1).'\config\init.php';
?>
    
<div class="layout-px-spacing">

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <div class="col-12">
            <form class="text-left validate-form" id="smtpconfigurationForm" method="post" action="smtpconfiguration" enctype="multipart/form-data">
                <input type="hidden" id="formevent" name="formevent" value='addright'>
                <input type="hidden" id="id" name="id" value=''>

                <div class="col-lg-12 col-md-12 col-12 layout-spacing">
                    <div class="widget">
                        <div class="widget-content row">
                            
                            <div class="col-3">
                                <div class="input-group">
                                    <label class="mb-1">Host Name <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" id="hostname" name="hostname" class="form-control" placeholder="Enter Host Name" />
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="input-group">
                                    <label class="mb-1">Port <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" id="port" name="port" class="form-control" onkeypress="numbonly(event)" placeholder="Enter Port" />
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="input-group">
                                    <label class="mb-1">Name <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter Name" />
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="input-group">
                                    <label class="mb-1">Email ID <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="email" id="emailid" name="emailid" class="form-control"  placeholder="Enter Email ID" />
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="input-group">
                                    <label class="mb-1">Reply Email ID <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="email" id="replyemailid" name="replyemailid" class="form-control"  placeholder="Enter Reply Email ID" />
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="input-group">
                                    <label class="mb-1">Password <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" />
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
            
            fillsmtpconfigurationdata();
        });


        function resetdata()
        {
            $("#smtpconfigurationForm").validate().resetForm();
            $('#smtpconfigurationForm').trigger("reset");
            
        }


        
        //Start Fill SMTP Setting Data
        function fillsmtpconfigurationdata()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false};

            formdata = new FormData();
            formdata.append("action", "fillsmtpconfigurationdata");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillsmtpconfigurationdata,OnErrorfillsmtpconfigurationdata); 
        }

        function Onsuccessfillsmtpconfigurationdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            if(resultdata.status==0)
            {
                
            }
            else if(resultdata.status==1)
            {
                $('#hostname').val(resultdata.hostname);
                $('#port').val(resultdata.port);  
                $('#name').val(resultdata.name); 
                $('#emailid').val(resultdata.emailid); 
                $('#replyemailid').val(resultdata.replyemailid);  
                $('#password').val(resultdata.password);  

            }
        }

        function OnErrorfillsmtpconfigurationdata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
        //End Fill Setting Data

        if($('#smtpconfigurationForm').length){		
            $('#smtpconfigurationForm').validate({
                rules:{
                    hostname:{
                        required: true
                    },
                    port:{
                        required: true
                    },
                    name:{
                        required: true,
                    },
                    emailid:{
                        required: true
                    },
                    replyemailid:{
                        required: true
                    },
                    password:{
                        required: true,
                    },
                    
                },messages:{
                    hostname:{
                        required: "Host name is required"
                    },
                    port:{
                        required: "Port is required"
                    },
                    name:{
                        required: "Name is required",
                    },
                    emailid:{
                        required: "Email id is required"
                    },
                    replyemailid:{
                        required: "Reply email id is required"
                    },
                    password:{
                        required: "Password is required",
                    },
                    
                },
                submitHandler: function(form){
                    
                    formdata = new FormData(form);
                    formdata.append("action", "insertsmtpconfiguration");

                    var pagename=getpagename();
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesssmtpconfigurationa,OnErrorsmtpconfigurationa); 

			    },
            });
        }

        function Onsuccesssmtpconfigurationa(data)
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
                fillsmtpconfigurationdata();
            }
        }

        function OnErrorsmtpconfigurationa(data)
        {
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
