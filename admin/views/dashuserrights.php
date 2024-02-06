<?php
require_once dirname(__DIR__, 1).'/config/init.php';

?>
    
<div class="layout-px-spacing">
    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-12 col-xl-12 mb-3">
                    <div class="widget">
                        <div class="widget-content">
                            <form class="text-left validate-form" id="dashuserrightForm" method="post" action="dashuserright.php" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 col-exl-6">
                                        <div class="row">
                                            <div class="col-12 col-sm d-none">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Company : </label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <select class="form-control selectpicker" name="cmpid" id="cmpid" data-live-search="true" data-size="10" data-dropup-auto="false">
                                                       
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">User type : </label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <select class="form-control selectpicker" name="usertypeid" id="usertypeid" data-live-search="true" data-size="10" data-dropup-auto="false">
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-auto px-0 text-center mt-auto">
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <label class="mb-0 mb-sm-2 mx-auto">-OR-</label>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Person : </label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <select class="form-control selectpicker" name="personid" id="personid" data-live-search="true" data-size="10" data-dropup-auto="false">
                                                    
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php 
                                        if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                        {
                                            ?>
                                            <div class="col-12 col-sm-auto mt-auto">
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <button type="submit" class="btn btn-primary m-0" id="btnAdd"><?php echo $config->getSaveSidebar(); ?></button>
                                                </div>
                                            </div>
                                            <?php
                                        }
									?>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">                                                
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-striped border-primary mb-2">
                                                <thead>
                                                    <tr>
                                                        <th class="tbl-name">
                                                            <span class="d-block">Page Name</span>
                                                        </th>
                                                        <th class="tbl-name">
                                                            <span class="d-block text-center">Can View</span>
                                                            <div class="d-flex text-center">
                                                                <div class="custom-control custom-checkbox m-0 mx-auto theme-light w-auto">
                                                                    <input type="checkbox" class="custom-control-input d-none thcheck tdallview" id="checkcanallview" name="checkcanallview" data-attr="tdallview" data-attr1="tdselfview">
                                                                    <label class="custom-control-label mb-0" for="checkcanallview">All</label>
                                                                </div>
                                                                <div class="custom-control custom-checkbox m-0 mx-auto theme-light w-auto d-none">
                                                                    <input type="checkbox" class="custom-control-input d-none thcheck tdselfview" id="checkcanselfview" name="checkcanselfview" data-attr="tdselfview" data-attr1="tdallview">
                                                                    <label class="custom-control-label mb-0" for="checkcanselfview">Self</label>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody id="usertable">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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

            fillusertype();
        });

        function fillusertype()
        {
            var cmpid = $('#cmpid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};

            formdata = new FormData();
            formdata.append("action", "fillusertype");
            formdata.append("menutypeid", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+"userrights",formdata,headersdata,Onsuccessfillusertype,OnErrorfillusertype);
        }
        function Onsuccessfillusertype(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#usertypeid').html(resultdata.data);
            $('#usertypeid').selectpicker('refresh');
            fillperson()	
        }
        function OnErrorfillusertype(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

        $('#usertypeid').on('change', function() {
            fillperson();
        });

        function fillperson()
        {
            var cmpid = $('#cmpid').val();

            var pagename=getpagename();
            var usertypeid=$('#usertypeid').val();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "fillperson");
            formdata.append("usertypeid", usertypeid);
            formdata.append("menutypeid",1);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+"userrights",formdata,headersdata,Onsuccessfillperson,OnErrorfillperson);
        }
        function Onsuccessfillperson(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#personid').html(resultdata.data);
            $('#personid').selectpicker('refresh');	
            filldashuserrighttable();
        }
        function OnErrorfillperson(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
        
        $('#personid').on('change', function() {
            filldashuserrighttable();
        });

        function filldashuserrighttable()
        {
            var cmpid=$('#cmpid').val();
            var usertypeid=$('#usertypeid').val();
            var personid=$('#personid').val();
            var headersdata= {Accept: 'application/json',platform: 1};
			//var peramsdata = {action:'filldashuserrighttable',usertypeid:usertypeid,personid:personid};

            formdata = new FormData();
            formdata.append("action", "filldashuserrighttable");
            formdata.append("menutypeid", 1);
            formdata.append("usertypeid", usertypeid);
            formdata.append("personid", personid);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+"dashuserrights",formdata,headersdata,Onsuccessfilldashuserrighttable,OnErrorfilldashuserrighttable);
        }
        function Onsuccessfilldashuserrighttable(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('.thcheck').prop('checked',false);
            $('#usertable').html(resultdata.data);	
        }
        function OnErrorfilldashuserrighttable(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

        $('body').on('change','.thcheck',function(){
            var dataattr=$(this).attr('data-attr');
            var dataattr1=$(this).attr('data-attr1');
            $('.'+dataattr1).prop('checked',false);
            if($(this).is(":checked"))
            {
                $('.'+dataattr).prop('checked',true);		  
                
            }
            else
            {
                $('.'+dataattr).prop('checked',false);  
            }
        });
        
        //can view
        $('body').on('change','.viewrightcls',function(){
            var dt = $(this).prop('checked');
            var attr = $(this).attr('data-id');
            $(".vrcls"+attr).prop('checked',false);
            if(dt == true)
                $(this).prop('checked',true);
            else
                $(this).prop('checked',false);
        });
        
        //can add
        $('body').on('change','.addrightcls',function(){
            var dt = $(this).prop('checked');
            var attr = $(this).attr('data-id');
            $(".arcls"+attr).prop('checked',false);
            if(dt == true)
                $(this).prop('checked',true);
            else
                $(this).prop('checked',false);
        });
        
        //can update
        $('body').on('change','.editrightcls',function(){
            var dt = $(this).prop('checked');
            var attr = $(this).attr('data-id');
            $(".ercls"+attr).prop('checked',false);
            if(dt == true)
                $(this).prop('checked',true);
            else
                $(this).prop('checked',false);
        });
        
        //can delete
        $('body').on('change','.deleterightcls',function(){
            var dt = $(this).prop('checked');
            var attr = $(this).attr('data-id');
            $(".drcls"+attr).prop('checked',false);
            if(dt == true)
                $(this).prop('checked',true);
            else
                $(this).prop('checked',false);
        });
        
        //can print
        $('body').on('change','.printrightcls',function(){
            var dt = $(this).prop('checked');
            var attr = $(this).attr('data-id');
            $(".prcls"+attr).prop('checked',false);
            if(dt == true)
                $(this).prop('checked',true);
            else
                $(this).prop('checked',false);
        });
       


        function resetdata()
        {
            $("#dashuserrightForm").validate().resetForm();
            $('#dashuserrightForm').trigger("reset");
            Edittimeformnamechange(2);   
        }    


        if($('#dashuserrightForm').length){		
            $('#dashuserrightForm').validate({
                rules:{
                    usertypeid:{
                        required: true,			
                    },
                    personid:{
                        required: true,			
                    }
                },messages:{
                    usertypeid:{
                        required:"User type is required",
                    },
                    personid:{
                        required:"Person is required",
                    }
                },
                submitHandler: function(form){

                    $('#loaderprogress').show();
                    var pagename=getpagename();

                    formdata = new FormData(form);
                    formdata.append("action", "insertdashuserright");

                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessUserrightsubmit,OnErrorUserrightsubmit); 

			    },
            });
        }

        function OnsuccessUserrightsubmit(data)
        {
            $('#loaderprogress').hide();
            var JsonData = JSON.stringify(data);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#loaderprogress').hide();	
            if(resultdata.status==0)
            {
                alertify(resultdata.message, 0);
            }
            else if(resultdata.status==1)
            {
                alertify(resultdata.message, 1);
                filldashuserrighttable();
            }
        }

        function OnErrorUserrightsubmit(data)
        {
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
