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
                            <form class="text-left validate-form" id="menuassignForm" method="post" action="menuassign" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-8 col-exl-6">
                                        <div class="row">
                                            <div class="col-12 col-sm">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Menu type : </label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <select class="form-control selectpicker" name="menutypeid" id="menutypeid">
                                                        <option value="1">Web</option>
                                                        <option value="3">POS</option>
                                                        <option value="2">Mobile App</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Select Module : </label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <select class="form-control selectpicker" name="moduleid" id="moduleid" data-size="8" data-dropup-auto="false" data-live-search="true">
                                                        <option value="0">All</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php 
                                        if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                        {
                                            ?>
                                            <div class="col-12 col-sm-auto mt-auto d-flex">
                                                <div class="input-group mb-3 mb-sm-3 w-auto">
                                                    <button type="submit" class="btn btn-primary m-0" id="btnAdd"><?php echo $config->getSaveSidebar(); ?></button>
                                                </div>
                                                <div class="input-group mb-3 ml-3 mb-sm-3">
                                                    <button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
                                                </div>      
                                            </div>
                                            <?php
                                        }
									?>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">                                                
                                        <div class="table-responsive">
                                            <table id="tableUserRights" class="table table-bordered table-hover table-striped border-primary mb-2">
                                                <thead>
                                                    <tr>
                                                        <th class="tbl-w100">
                                                            <div class="text-center">
                                                                <div class="custom-control custom-checkbox m-0 mx-auto w-auto">
                                                                    <input type="checkbox" class="custom-control-input d-none" id="tableThAll" name="tableThAll">
                                                                    <label class="custom-control-label mb-0" for="tableThAll">All</label>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th class="tbl-name">Menu</th>
                                                        <th class="tbl-w100">
                                                            <span class="d-block"></span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id='tabledata'>
                                                    
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
            fillmodule();
        });

        $('#menutypeid').on('change', function () {
            fillmodule();
        });

        function fillmodule()
        {
            var pagename=getpagename();
            var menutypeid=$('#menutypeid').val();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};

            formdata = new FormData();
            formdata.append("action", "fillmodule");
            formdata.append("menutypeid", menutypeid);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillmodule,OnErrorfillmodule);
        }
        function Onsuccessfillmodule(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#moduleid').html(resultdata.data);
            $('#moduleid').selectpicker('refresh');	
            fillmenuassign();
        }

        function OnErrorfillmodule(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

        $('body').on('change', '#moduleid', function () {
            fillmenuassign();
        });

        function fillmenuassign()
        {
            var menutypeid=$('#menutypeid').val(); 
            var moduleid=$('#moduleid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};
            formdata = new FormData();
            formdata.append("action", "fillmenuassign");
            formdata.append("menutypeid", menutypeid);
            formdata.append("moduleid", moduleid);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillmenuassign,OnErrorfillmenuassign);
        }
        function Onsuccessfillmenuassign(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#tabledata').html(resultdata.data);
        }

        function OnErrorfillmenuassign(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
    
        function resetdata()
        {
            $("#menuassignForm").validate().resetForm();
            $('#menuassignForm').trigger("reset");
            $('.selectpicker').selectpicker('refresh');

            fillmodule();
            fillmenuassign();
            // Edittimeformnamechange(2);   
        }   

        //All Checkbox Click Event
        $('body').on('change','#tableThAll',function(){
			var dataattr=$(this).attr('data-attr');
			if($(this).is(":checked"))
			{
				$('.tblchk').prop('checked',true);	    			    
			}
			else
			{
				$('.tblchk').prop('checked',false);	  
			}
		}); 

        if($('#menuassignForm').length){		
            $('#menuassignForm').validate({
                rules:{
                    moduleid:{
                        required: true,			
                    },
                },messages:{
                    moduleid:{
                        required:"Module is required",
                    },
                },
                submitHandler: function(form){
                    var onchklength = $('[name="tblchk[]"]:checked').length;
                    var parentradlength = $('[name="menurad[]"]:checked').length;
                    var isvalid=0;
                    if(onchklength>=1)
                    {
                        isvalid=1;
                        if(parentradlength>=1)
                        {
                            isvalid=1;
                        }
                        else 
                        {
                            isvalid=0;
                        }
                    }
                    else
                    {
                        isvalid=1;
                        
                    }
                    if(isvalid==1)
                    {
                        $('#loaderprogress').show();
                        var checkboxval = new Array();
                        var radboxval = new Array();
                        $('#tabledata tr').each(function(){
                            var trtmp = $(this).attr('data-index');
                            if($('#tblchk'+trtmp).is(':checked')) 
                            {
                                checkboxval.push(1);
                            }
                            else
                            {
                                checkboxval.push(0);
                            }
                            if($('#parentrad'+trtmp).is(':checked')) 
                            {
                                radboxval.push(1);
                            }
                            else
                            {
                                radboxval.push(0);
                            }
                        });

                        formdata = new FormData(form);
                        formdata.append("action", "insertmenuassign");
                        formdata.append("checkboxval", checkboxval);
                        formdata.append("radboxval", radboxval);

                        var pagename=getpagename();
                        var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
                        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessMenuAssign,OnErrorMenuAssign); 

                    }
			    },
            });
        }

        function OnsuccessMenuAssign(data)
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
                fillmenuassign();
            }
        }

        function OnErrorMenuAssign(data)
        {
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
