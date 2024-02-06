<?php 
require_once dirname(__DIR__, 1).'\config\init.php';
?>
    <div class="layout-px-spacing">
        <!-- CONTENT AREA -->
        <div class="row layout-top-spacing">
            <div class="col-12 layout-spacing" id="layoutContent">
                <div class="widget widget-page">
                    <div class="widget-control">
                        <div class="row">
                            <div class="col-auto mr-auto">
                                <h5 class="m-0 menunamelbl" id="customernamelbl"></h5>
                            </div>
                            <div class="col-auto ml-auto">
                                <ul class="list-inline">
                                    <li><a href="javascript:void(0);" class="btn-tbl content-minimize"><i class="bi bi-dash-lg"></i></a></li>
                                    <li><a href="javascript:void(0);" class="btn-tbl content-expand"><i class="bi bi-fullscreen"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-12 col-lg-6 ml-auto text-right">
                                <ul class="page-more-setting">
                                    <?php
                                        if((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                        {
                                            ?>
                                            <li class="delete-selected tbl-check d-none mb-2">
                                            <!-- <a href="javascript:void(0);" class="btn btn-primary m-0 " data-toggle="modal" data-target="#modalConfirmation"><i class="bi bi-trash"></i></a> -->
                                                <a href="javascript:void(0);" class="btn btn-primary m-0 btnmultitrash" data-toggle="modal" ><i class="bi bi-trash"></i></a>
                                            </li>
                                            <li class="mb-2">
                                                <div class="dropdown table-setting">
                                                    <button class="dropdown-toggle btn btn-primary" type="button" id="tableSetting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-check2-circle"></i></button>
                                                    <div class="dropdown-menu" aria-labelledby="tableSetting">
                                                        <a class="dropdown-item select-bulk-delete" href="javascript:void(0);"><i class="bi bi-trash"></i> Bulk Delete</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                    ?>
                                    <li class="tbl-search mb-2">
                                        <div class="input-group">
                                            <input type="text" name="filter" id="filter" class="form-control control-append" dataact='menumaster' placeholder="Search...">
                                            <div class="input-group-append">
                                                <label class="input-group-text" for="selectBrandDate" id="btnfilter" name="btnfilter"><i class="fal fa-search"></i></label>
                                            </div>
                                        </div>
                                    </li>
                                    <?php 
                                        if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                        {
                                            ?>
                                            <li class="mb-2">
                                                <a href="javascript:void(0);" class="btn btn-primary m-0" id="openRightSidebar">Add New</a>
                                            </li>
                                            <?php
                                        }
									?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="row  min-height-100">
                            <div class="col-12 min-height-100">
                                <div class="table-responsive main-grid">
                                    <table id="tableDataList" data-show="1" data-nextpage='0' class="table table-bordered table-hover table-striped datalisttable">
                                        <thead>
                                            <tr>
                                                <?php 
                                                    if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || (sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                                    {
                                                        ?>
                                                        <th class="tbl-grid">
                                                            <a href="javascript:void(0);" class="" id="gridMenu" data-toggle="griddropdown" data-target="#tblDropdownContent" aria-haspopup="true" aria-expanded="false"><i class="bi bi-ui-checks-grid"></i></a>
                                                        </th>
                                                        <?php
                                                    }
                                                ?>
                                                <th class="tbl-check d-none">
                                                    <div class="text-center">
                                                        <div class="custom-control custom-checkbox m-0 w-auto">
                                                            <input type="checkbox" class="custom-control-input" id="tblCheckAll" name="tblCheckAll">
                                                            <label class="custom-control-label mb-n1" for="tblCheckAll">All</label>
                                                        </div>
                                                    </div>
                                                </th>
                                                <th class="tbl-w100 sorting sorting_asc" data-th="menutype">Type</th>
                                                <th class="tbl-name sorting" data-th="menuname">Menu Name</th>
                                                <th class="tbl-name sorting" data-th="formname">Form Name</th>
                                                <th class="tbl-w100">Icon</th>
                                                <th class="tbl-name sorting" data-th="alias">Alias (Page Name)</th>
                                                <th class="tbl-name">Contain User Rights</th>
                                                <th class="tbl-name">Default Page Open</th>
                                            </tr>
                                        </thead>
                                        <tbody id="datalist">
                                        </tbody>
                                    </table>
                                </div>    
                            </div>
                            <!-- table End -->
                        </div>
                    </div>
                    <?php require_once 'pagefooter.php'; ?>
                </div>
            </div>
            <!-- rightSidebar Start -->
            <div class="col-12 p-4 sm-sidebar" id="rightSidebar">
                <div class="right-sidebar-content">
                    <div class="row">   
                        <div class="col-12 mb-4 sidebar-header">
                            <div class="row">
                                <div class="col">
                                    <h5 class="my-2"><span class="formnamelbl"></span></h5>
                                </div>
                                <div class="col-auto pl-0">
                                    <a href="javascript:void(0);" class="btn btn-danger m-0 rounded-circle btn-close-right-sidebar" id="btnCloseRightSidebar" data-toggle="tooltip" data-placement="top" title="<?php echo $config->getCloseSidebar(); ?>" data-original-title="<?php echo $config->getCloseSidebar(); ?>"><i class="bi bi-x-lg"></i></a>
                                </div>
                            </div>
                        </div>                                         
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="widget">
                                <div class="widget-content">
                                    <form class="text-left validate-form" id="menumasterForm" method="post" action="menumaster.php" enctype="multipart/form-data">
                                        <input type="hidden" id="formevent" name="formevent" value='addright'>
                                        <input type="hidden" id="id" name="id" value=''>
                                        <input type="hidden" name="moduletypetxt" id="moduletypetxt"  value="Web" />
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Menu Name <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="Menu Name" id="menuname" name="menuname">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Form Name <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="Form Name" id="formname" name="formname">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Icon <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <select class="form-control selectpicker" data-live-search="true" id="iconid" name="iconid" data-size="10" data-dropup-auto="false">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 ">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Module Type <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <select class="form-control selectpicker" data-live-search="true" id="menutypeid" name="menutypeid" data-size="10" data-dropup-auto="false">
                                                        <option value="1">Web</option>
                                                        <option value="3">POS</option>
                                                        <option value="2">Mobile App</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Alias <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" placeholder="Alias Name" id="alias" name="alias">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm my-auto ans-view">
                                                <div class="input-group mb-3 mb-lg-3">
                                                    <div class="custom-control custom-checkbox m-0">
                                                        <input type="checkbox" class="custom-control-input d-none" id="containrights" name="containrights" value="1" checked="">
                                                        <label class="custom-control-label mb-0" for="containrights">Contain User Rights</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-auto ml-auto">
                                                <div class="input-group mb-0">
                                                    <button type="submit" class="btn btn-primary m-0" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
                                                    <button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- rightSidebar End -->
        </div>
        <!-- CONTENT AREA -->
    </div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            fillicon();
        });

        function resetdata()
        {
            $("#menumasterForm").validate().resetForm();
            $('#menumasterForm').trigger("reset");
            $('.selectpicker').selectpicker('refresh');
            $('#formevent').val('addright');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');
            Edittimeformnamechange(2);   
            fillicon();
        }    

        function fillicon()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};

            formdata = new FormData();
            formdata.append("action", "fillicon");

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillicon,OnErrorfillicon);
        }
        function Onsuccessfillicon(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            //alert(resultdata.data);
            $('#iconid').html(resultdata.data);
            $('#iconid').selectpicker('refresh');	
        }

        function OnErrorfillicon(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
    
        

        //Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};

            formdata = new FormData();
            formdata.append("action", "editmenumaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessMenuedit,OnErrorMenuedit); 
        }

        function OnsuccessMenuedit(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            $('#id').val(resultdata.id);
            $('#menuname').val(resultdata.menuname);  
            $('#formname').val(resultdata.formname);  
            $('#iconid').val(resultdata.iconid);
            $('#iconid').selectpicker('refresh'); 
            $('#menutypeid').val(resultdata.menutype);
            $('#menutypeid').selectpicker('refresh');
            $('#alias').val(resultdata.alias);  
            if(resultdata.containright==1)
            {
                $("#containrights").prop('checked', true);
            }
            else
            {
                $("#containrights").prop('checked', false);
            }
            $('#formevent').val('editright');
            $('#btnsubmit').text('<?php echo $config->getUpdateSidebar(); ?>');    

            //Open Side bar
            Edittimeformnamechange(1);
            $("#rightSidebar").toggleClass("active-right-sidebar"); 
            $('.overlay').addClass('show');
            $("body").addClass("overflow-hidden");
        }

        function OnErrorMenuedit(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
        //Edit End

        function changecontainright(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changecontainright");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessContainright,OnErrorContainright); 
        }

        function OnsuccessContainright(data)
        {
            var JsonData = JSON.stringify(data);
            var resultdata = jQuery.parseJSON(JsonData);
            if(resultdata.status==0)
            {
                alertify(resultdata.message,0);
            }
            else if(resultdata.status==1)
            {
                alertify(resultdata.message,1);
            }
            $('#tableDataList').attr('data-nextpage',0); 
            listdata();
        }
        function OnErrorContainright(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

        function changedefaultopen(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changedefaultopen");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDefaultopen,OnErrorDefaultopen); 
        }

        function OnsuccessDefaultopen(data)
        {
            var JsonData = JSON.stringify(data);
            var resultdata = jQuery.parseJSON(JsonData);
            if(resultdata.status==0)
            {
                alertify(resultdata.message,0);
            }
            else if(resultdata.status==1)
            {
                alertify(resultdata.message,1);
            }
            $('#tableDataList').attr('data-nextpage',0); 
            listdata();
        }
        function OnErrorDefaultopen(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

        if($('#menumasterForm').length){		
            $('#menumasterForm').validate({
                rules:{
                    menuname:{
                        required: true,			
                    },
                    formname:{
                        required: true,			
                    },
                    iconid:{
                        required: true,			
                    },
                    menutypeid:{
                        required: true,			
                    },
                    alias:{
                        required: true,			
                    },
                },messages:{
                    menuname:{
                        required:"Menu name is required",
                    },
                    formname:{
                        required:"Form name is required",
                    },
                    iconid:{
                        required:"Icon is required ",
                    },
                    menutypeid:{
                        required:"Menutype is required ",
                    },
                    alias:{
                        required:"Alias is required ",			
                    },
                },
                submitHandler: function(form){
                    $('#loaderprogress').show();
                    var pagename=getpagename();
                    var useraction=$('#formevent').val();

                    formdata = new FormData(form);
                    formdata.append("action", "insertmenumaster");

                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit); 

					/*$('#loaderprogress').show()
                    var pagename=getpagename();
                    var useraction=$('#formevent').val();
					jqXHR = $.ajax({
						url : '<?php echo $config->getEndpointurl(); ?>'+pagename+'.php',
						type : "POST",
						dataType:'json',
                        headers: {'Accept': 'application/json','platform': 1,userpagename:pagename,useraction:useraction,masterlisting:false},
						data: $('#menumasterForm').serialize()+ "&action=insertmenumaster",
						success : function(data) 
						{
							var JsonData = JSON.stringify(data);
							var resultdata = jQuery.parseJSON(JsonData);
							$('#loaderprogress').hide();	
							if(resultdata.status==0)
							{
								alertify(resultdata.message, 0);
							}
							else if(resultdata.status==1)
							{
                                alertify(resultdata.message,1);
                                $('#tableDataList').attr('data-nextpage',0); 
                                listdata();

                                //Close Side bar
                                $("#rightSidebar").removeClass("active-right-sidebar"); 
                                $('.overlay').removeClass('show');
                                $("body").removeClass("overflow-hidden");
                                resetdata();
							}
						}
					});*/
			    },
            });
        }
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
