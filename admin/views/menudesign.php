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
                            <form class="text-left validate-form" id="menudesignForm" method="post" action="menudesign.php" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="id" value=""/>	
                                <input type="hidden" name="formevent" id="formevent"  value="addright" />
                                <div class="row ">
                                    <div class="col-lg-4 offset-lg-4 col-md-4 col-12">        
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Module Type <span class="text-danger">*</span></label>
                                        </div>                                        
                                        <select class="form-control selectpicker" data-live-search="true" id="menutypeid" name="menutypeid" data-size="10" data-dropup-auto="false">
                                            <option value="1">Web</option>
                                            <option value="3">POS</option>
                                            <option value="2">Mobile App</option>
                                            
                                        </select>
                                    </div>
                                   
                                </div>	
                                <br /><br />
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">                                                
                                        <div id="jstree">
                                        
                                        </div>
                                    </div>
                                </div>
                                <br /><br />
                                <?php 
                                    if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                    {
                                        ?>
                                        <div class="row">
                                            <div class="col-12 col-sm-auto mt-auto d-flex">
                                                <div class="input-group mb-3 mb-sm-3 w-auto">
                                                    <button type="submit" class="btn btn-primary m-0" id="btnAdd"><?php echo $config->getSaveSidebar(); ?></button>
                                                </div>
                                                <div class="input-group mb-3 ml-3 mb-sm-3">
                                                    <button class="btn btn-secondary m-0 ml-2" id="btnReset"><?php echo $config->getResetSidebar(); ?></button>
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

<div class="modal fade" id="menunamechangeModal" role="dialog" data-backdrop="static" data-keyboard="false" onsubmit="return false;">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body"> 
                    <div class="row mb-4">
                        <div class="col-12">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                            <h5 class="modal-title" id="AddServicesLabel">Menu Design</h5>
                        </div>
                    </div>
                    <div id="menunamechangediv">
                        <form class="form-horizontal" id="menunameForm">
                            <input type="hidden" id="menuid" name="menuid" />

                            <div class="col-12">
                                <div class="input-group">
                                    <label class="mb-1">Menu Name <span id="menuspan"></span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group">
                                    <label class="mb-1">Enter Menu Name <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Enter Menu Name" id="menuname" name="menuname" require>
                                </div>
                            </div>     
                            
                            <div class="col-12">
                                <div class="input-group">
                                    <label class="mb-1">Enter Form Name <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Enter Form Name" id="formname" name="formname" require>
                                </div>
                            </div>

                            <!-- <div class="form-group"> 
                                <div class="col-md-12">
                                    <label class="form-label">Menu Name : <span id="menuspan"></span></label> 
                                </div> 
                            </div>
                            <div class="form-group"> 
                                <div class="col-md-12">
                                    <label class="form-label">Enter Menu Name <span class="text-danger">*</span></label> 
                                    <input id="menuname" name="menuname" type="text" placeholder="Enter Menu Name" class="form-control input-md" required> 
                                </div> 
                            </div>
                            <div class="form-group"> 
                                <div class="col-md-12">
                                    <label class="form-label">Enter Form Name <span class="text-danger">*</span></label> 
                                    <input id="formname" name="formname" type="text" placeholder="Enter Form Name" class="form-control input-md" required> 
                                </div> 
                            </div> -->
                            <!-- <div class="form-group text-right"> 
                                <div class="col-md-12">
                                    <input class="btn btn-primary" name="btnsubmit" id="btnsubmit" type="submit" value="Save" />  
                                </div> 
                            </div>  -->
                            <div class="row col-12">
                                <div class="ml-auto">
                                    <div class="input-group mb-0">
                                        <button type="submit" class="btn btn-primary m-0" id="btnsubmit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>	
                </div>
                <div class="modal-footer" style="border-top: none"></div>
            </div>
        </div>
    </div>                

</div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            fillmenuassignlist();
        });

        $('#menutypeid').on('change', function () {
            fillmenuassignlist();
        });

        function fillmenuassignlist()
        {
            var pagename=getpagename();
            var menutypeid=$('#menutypeid').val();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};

            formdata = new FormData();
            formdata.append("action", "fillmenuassignlist");
            formdata.append("menutypeid",menutypeid);
            
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillmenuassignlist,OnErrorfillmenuassignlist);
        }
        function Onsuccessfillmenuassignlist(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            $('#jstree').jstree("destroy");				
            $('#jstree').html(resultdata.data);	
            $('#jstree').jstree({
                "core" : {
                    "animation" : 0,
                    "check_callback" : true,
                    "dblclick_toggle" : false
                },
                "dnd" : {
                    "is_draggable" : function(node) {
                        return true;
                    }
                },
                "types" : {
                    "default" : {
                        max_depth :1
                    }
                },
                "plugins" : [
                    "dnd", 
                    "state", "types", "contextmenu"
                ]
            })
            .on("copy_node.jstree", function (e, data) {
                $(this).jstree("open_all");
                menuupdate();
            })
            .bind("loaded.jstree", function(event, data) {
                $(this).jstree("open_all");
                menuupdate();
            })
            .bind("move_node.jstree", function(e, data) {
                $(this).jstree("open_all");
                menuupdate();
            })
            $('#jstree').jstree("open_all");
        }

        function OnErrorfillmenuassignlist(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

        function menuupdate()
        {
            $('#jstree ul li').each(function(){			
                var level = $(this).attr('aria-level');
                if(level==1)
                {
                    $(this).find('a #parent').val(1);
                    $(this).find('a #child').val(0);
                }
                else if(level==2)
                {
                    $(this).find('a #parent').val(0);
                    $(this).find('a #child').val(1);
                }
            });
        }

        $('body').on('click','.editreq',function(){
            var id = $(this).attr('data-id');
            $('#menunamechangeModal').modal('show');
            $('#menuname').val('');
            $('#formname').val('');
            var lbl = $('.mainspan'+id).text();

            var formlbl = $('.mainspan'+id).attr('data-formname');

            $('#menuname').val(lbl);
            $('#menunamechangeModal #formname').val(formlbl);
            $('#menuspan').text(lbl);
            $('#menunamechangeModal #menuid').val(id);
            // var nodeToEdit = $('#jstree').jstree().get_selected();
            // $('#jstree').jstree().edit(nodeToEdit);
        })

        $('#menunamechangeModal').on('hide.bs.modal', function () {
            $("#menunameForm").validate().resetForm();
            $('#menunameForm').trigger("reset");
        })


        if($('#menunameForm').length)
        {		
            $('#menunameForm').validate({
                rules:{
                    menuname:{
                        required: true,
                    },
                    formname:{
                        required: true,
                    },
                },
                messages:{
                    menuname:{
                        required: "Menu Name is required",
                    },
                    formname:{
                        required: "Form Name is required",
                    },
                },
                submitHandler: function(form){

                    $('#loaderprogress').show();
                    var menutypeid=$('#menutypeid').val();
                    var pagename=getpagename();
                    formdata = new FormData(form);
                    formdata.append("action", "menunamechange");
                    formdata.append("menutypeid",menutypeid);
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit); 
                }
            });
        }

    
        function resetdata()
        {
            $("#menudesignForm").validate().resetForm();
            $('#menudesignForm').trigger("reset");
            $('.selectpicker').selectpicker("refresh");

            $('#menunamechangeModal').modal('hide');

            fillmenuassignlist();
            Edittimeformnamechange(2);   
        }    

        if($('#menudesignForm').length){		
            $('#menudesignForm').validate({
                rules:{
   
                },messages:{

                },
                submitHandler: function(form)
                {
                    
                    $('#loaderprogress').show();
                    var menutypeid=$('#menutypeid').val();
                    var pagename=getpagename();
                    formdata = new FormData(form);
                    formdata.append("action", "insertmenudesign");
                    formdata.append("menutypeid",menutypeid);
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit); 
                },
            });
        }
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
