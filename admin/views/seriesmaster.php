<?php
require_once dirname(__DIR__, 1).'/config/init.php';
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
								<div class="col-12 col-lg-6 mb-2 ml-auto text-right">
									<ul class="page-more-setting">
										<?php
										
										if((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
										{
											?>
											<li class="delete-selected tbl-check d-none">
											<!-- <a href="javascript:void(0);" class="btn btn-primary m-0 " data-toggle="modal" data-target="#modalConfirmation"><i class="bi bi-trash"></i></a> -->
												<a href="javascript:void(0);" class="btn btn-primary m-0 btnmultitrash" data-toggle="modal" ><i class="bi bi-trash"></i></a>
											</li>
											<li>
												<div class="dropdown table-setting">
													<button class="dropdown-toggle btn btn-primary" type="button" id="tableSetting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Bulk Delete"><i class="bi bi-check2-circle"></i></button>
													<div class="dropdown-menu" aria-labelledby="tableSetting">
														<a class="dropdown-item select-bulk-delete" href="javascript:void(0);"><i class="bi bi-trash"></i> Bulk Delete</a>
													</div>
												</div>
											</li>
											<?php
										}
										?>
										<li class="tbl-search">
											<div class="input-group">
												<input type="text" name="filter" id="filter" class="form-control control-append" placeholder="Search...">
												<div class="input-group-append">
													<label class="input-group-text" for="selectBrandDate" id="btnfilter" name="btnfilter"><i class="fal fa-search"></i></label>
												</div>
											</div>
										</li>
										<?php 
                                        if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                        {
                                            ?>
                                            <li>
                                                <a href="javascript:void(0);" class="btn btn-primary m-0" id="openRightSidebar">Add New</a>
                                            </li>
                                            <?php
                                        }
										?>
										<!-- <li>
											<a href="javascript:void(0);" class="btn btn-primary m-0" id="openfilter">
												<i class="bi bi-funnel"></i>
											</a>
										</li> -->
									</ul>
								</div>
							</div>
						</div>
						<div class="widget-content">
							<div class="row min-height-100">
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
													<th class="tbl-name sorting sorting_asc" data-th="tm.typestr">Type</th>
													<th class="tbl-name sorting" data-th="endno">End No</th>
													<th class="tbl-name ">Start Date</th>
													<th class="tbl-name" data-th="alias">End Date</th>
													<th class="tbl-name ">Prefix</th>
													<th class="tbl-name ">Preview</th>
												</tr>
											</thead>
											<tbody id="datalist">
											</tbody>
										</table>
										<!-- table End -->
									</div>
								</div>
							</div>
						</div>
						<?php require_once 'pagefooter.php'; ?>
					</div>
				</div>
				<!-- rightSidebar Start -->
				<div class="col-12 p-4 md-sidebar" id="rightSidebar">
					<div class="right-sidebar-content">
						<div class="row">   
							<div class="col-12 mb-4 sidebar-header">
								<div class="row">
									<div class="col">
										<h5 class="my-2"><span class="formnamelbl1">Add Series</span></h5>
									</div>
									<div class="col-auto pl-0">
										<a href="javascript:void(0);" class="btn btn-danger m-0 rounded-circle btn-close-right-sidebar" id="btnCloseRightSidebar" data-toggle="tooltip" data-placement="top" title="<?php echo $config->getCloseSidebar(); ?>" data-original-title="<?php echo $config->getCloseSidebar(); ?>"><i class="bi bi-x-lg"></i></a>
									</div>
								</div>
							</div>  
							<div class="col-12">
								<form class="row validate-form" id="seriesmasterForm" method="post" action="seriesmaster" enctype="multipart/form-data">                                       
									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget">
											<div class="widget-content row">
												
												<input type="hidden" id="formevent" name="formevent" value='addright'>
												<input type="hidden" id="id" name="id" value=''>
												<input type="hidden" name="typename" id="typename"/>
                                        		<input type="hidden" name="nseries" id="nseries"/>

                                                <div class="col-6 d-none">
                                                    <div class="input-group mb-0">
                                                        <label class="mb-1">Company <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <select class="form-control selectpicker" data-live-search="true" id="cmpid" name="cmpid" data-size="10" data-dropup-auto="false">
                                                        </select>
                                                    </div>
                                                </div>

												<div class="col-12">
                                                    <div class="input-group mb-0">
                                                        <label class="mb-1">Type <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <select class="form-control selectpicker" data-live-search="true" id="pagetype" name="pagetype" data-size="10" data-dropup-auto="false">
                                                        </select>
                                                    </div>
                                                </div>
												<div class="col-12 col-sm-6 col-md-6 layout-spacing">
                                                    <div class="input-group mb-0">
                                                        <label class="mb-1">Start Date <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="input-group mb-3 mb-md-0" >
                                                        <input type="text" class="form-control control-append" placeholder="Start Date" id="startdate" name="startdate" value="<?php echo date('d/m/Y'); ?>" require aria-label="Select Date" aria-describedby="datecalendar">

                                                        <div class="input-group-append">
                                                            <label class="input-group-text" for="startdate"><i class="bi bi-calendar2-week"></i></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-6 layout-spacing">
                                                    <div class="input-group mb-0">
                                                        <label class="mb-1">End Date <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="input-group mb-3 mb-md-0">
                                                        <input type="text" class="form-control control-append" placeholder="End Date" id="enddate" name="enddate" value="<?php echo date('d/m/Y'); ?>" require aria-label="Select Date" aria-describedby="datecalendar">

                                                        <div class="input-group-append">
                                                            <label class="input-group-text" for="enddate"><i class="bi bi-calendar2-week"></i></label>
                                                        </div>
                                                    </div>
                                                </div>
												<div class="col-12 col-sm-6">
                                                    <div class="input-group mb-0">
                                                        <label class="mb-1">Start No <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="Start No" id="startno" onkeypress="numbonly(event)" onpaste="numbonly(event);" name="startno" value="1">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="input-group mb-0">
                                                        <label class="mb-1">NO Of Series <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="NO Of Series" onkeypress="numbonly(event)" onpaste="numbonly(event);" id="endno" name="endno">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="input-group mb-0">
                                                        <label class="mb-1">Prefix <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="Prefix" id="prefix" name="prefix">
                                                    </div>
                                                </div>

												<div class="col-12 col-sm-6 col-md-12 col-lg-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="input-group mb-0">
                                                                <label class="mb-1">Elements </label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="input-group mb-3 mb-sm-3">
                                                                <select class="form-control selectpicker" data-live-search="true" id="eleid" name="eleid" data-size="10" data-dropup-auto="false">
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button" class="col-auto btn btn-primary m-0" id="btnadd" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Elements"><i class="bi bi-plus-lg"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="input-group mb-0">
                                                        <label class="mb-1"></label>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control tags" name="tags" id="seriestags" readonly="readonly" onchange="return false">	
                                                    </div>
                                                    <div class="hidden" id="hiddendiv"></div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="input-group mb-0">
                                                        <label class="mb-1">Preview <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="Start No" id="preview" onkeypress="numbonly(event)" onpaste="numbonly(event);" name="preview">
                                                    </div>
                                                </div>

												<div class="col-12 ">    
													<div class="ml-auto">
														<div class="input-group mb-0">
															<button type="submit" class="btn btn-primary m-0  ml-auto" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
															<button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
														</div>
													</div>
												</div>
												
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

		



		<!-- filterSidebar Start -->
		<div class="col-12 p-0 filter-sidebar" id="filterSidebar">
            <div class="right-sidebar-content">   
                <div class="row">   
                    <div class="col-12 mb-4 sidebar-header">
                        <div class="row">
                            <div class="col">
                                <h5 class="my-2">Filters</h5>
                            </div>
                            <div class="col-auto pl-0">
                                <a href="javascript:void(0)" class="btn btn-danger m-0 rounded-circle btn-close-right-sidebar" id="btnFilterCloseRightSidebar" data-toggle="tooltip" data-placement="top" title="<?php echo $closeSidebar; ?>" data-original-title="<?php echo $closeSidebar; ?>"><i class="bi bi-x-lg"></i></a>
                            </div>
                        </div>
                    </div>                                         
                    <div class="col-lg-12 col-md-12 col-12">
                        <form class="form" action="" name="fltitemmasterFrm" id="fltitemmasterFrm">
                            <input type="hidden" name="issidebarflt" id="issidebarflt" value="0"/>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-group mb-0">
                                        <label class="mb-1">Canteen</label>
                                    </div>
                                    <div class="input-group mb-3 mb-sm-3">
                                        <select class="form-control selectpicker" data-live-search="true" data-size="10" data-dropup-auto="false" id="fltcanteenid" name="fltcanteenid">
                                        
                                        </select>
                                    </div>
                                </div>
								<div class="col-12">
                                    <div class="input-group mb-0">
                                        <label class="mb-1">Category</label>
                                    </div>
                                    <div class="input-group mb-3 mb-sm-3">
                                        <select class="form-control selectpicker" data-live-search="true" data-size="10" data-dropup-auto="false" id="fltcatid" name="fltcatid">
                                        
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        
            <div class="right-sidebar-footer">
                <div class="row">
                    <div class="col-auto">
                        <div class="input-group mb-0">
                            <button type="button" class="btn btn-primary m-0 mr-auto" id="btnfiltersave"><?php echo $config->getApplyFilterSidebar(); ?></button>
                        </div>
                    </div>
                    <div class="col-auto ml-auto">
                        <div class="input-group mb-0">
                            <button type="button" href="#" class="btn btn-secondary m-0 ml-2" onclick="filterresetdata()" id="btnfilterreset"><?php echo $config->getResetSidebar(); ?></button>
                        </div>
                    </div>
                </div>
            </div>
			
		</div>
		<!-- filterSidebar End -->


		

	<script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
			$('#prefix').prop('readonly',false);
            $('#seriestags').tagEditor({
                forceLowercase: false, 
                removeDuplicates:false,
                onChange: function(field, editor, tags) {
                    showpreview();
                },
            }); 

            fillcompany();
			fillpagetype();
			fillelements();
        });



		function resetdata()
        {
            $("#seriesmasterForm").validate().resetForm();
            $('#seriesmasterForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');  
			Edittimeformnamechange(2);  

			$("#startno").prop("disabled", false);
            $("#startdate").prop("disabled", false);
            $("#endno").prop("disabled", false);
            $("#cmpid").prop("disabled", false);
            $("#pagetype").prop("disabled", false);
            $("#prefix").prop("disabled", false);

            $('#seriestags').tagEditor('destroy');
            $('#seriestags').tagEditor({
                forceLowercase: false, 
                removeDuplicates:false,
                onChange: function(field, editor, tags) {
                    showpreview();
                },
            });  

            fillcompany();
			fillpagetype();
			fillelements();
        }

		// $('#startdate').bootstrapMaterialDatePicker({
        //     time: false,
        //     format: 'DD/MM/YYYY',
        // }); 

        // $('#enddate').bootstrapMaterialDatePicker({
        //     time: false,
        //     format: 'DD/MM/YYYY',
        // });  


        $('#startdate').bootstrapMaterialDatePicker
        ({
            weekStart: 0, 
			format: 'DD/MM/YYYY',
			time: false,
			switchOnClick: true,
            okText: ""
        }).on('change', function(e, date)
        {
			$('#enddate').bootstrapMaterialDatePicker('setMinDate',date);
			$('#enddate').val($('#startdate').val());

			$('#tableDataList').attr('data-nextpage', 0);
			setTimeout(function(){ 
				//listdata();
			}, 200);
        });


		$('#enddate').bootstrapMaterialDatePicker
        ({
            weekStart: 0, 
			format: 'DD/MM/YYYY',
			time: false,
			switchOnClick: true,
            okText: ""
        }).on('change', function(e, date)
        {
			$('#tableDataList').attr('data-nextpage', 0);
			setTimeout(function(){ 
				//listdata();
			}, 200);
        });

		$('#enddate').bootstrapMaterialDatePicker('setMinDate',$('#startdate').val());

        function fillcompany()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcompany");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcompany,OnErrorSelectpicker);
        }
        function Onsuccessfillcompany(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#cmpid').html(resultdata.data);
            $('#cmpid').selectpicker('refresh');	
        }


		function fillpagetype()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillpagetype");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillpagetype,OnErrorSelectpicker);
        }
        function Onsuccessfillpagetype(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#pagetype').html(resultdata.data);
            $('#pagetype').selectpicker('refresh');	
        }

		function fillelements()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillelements");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillelements,OnErrorSelectpicker);
        }
        function Onsuccessfillelements(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#eleid').html(resultdata.data);
            $('#eleid').selectpicker('refresh');	
        }

		$('body').on('click', '#btnadd', function() {     
            var selected_item=$('#eleid').val();	
            var dateli="";
            if(selected_item)
            {
                var itmtxt = $("#eleid option[value='"+selected_item+"']").text();
                $('#seriestags').tagEditor('addTag', itmtxt);
                showpreview();
            }									
        });
        
        $('body').on('blur','#startdate,#enddate,#startno,#endno,#prefix', function(){
            showpreview();
        });
            
        function showpreview()
        {
            var pagename=getpagename();
            var tags=$('#seriestags').val();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
            formdata = new FormData();
            formdata.append("action", "gettagid");
            formdata.append("tags", tags);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessshowpreview,OnErrorSelectpicker);
        }

        function Onsuccessshowpreview(data)
        {
            var cmpprefix = $('#cmpid :selected').attr('data-prefix');
            var storeprefix = $('#branchid :selected').attr('data-prefix');
            var seriesprefix = $('#prefix').val();
            var startno = $('#startno').val();
            var endno = $('#endno').val();
            var endnolen = endno.length;
            var startdate = $('#startdate').val().slice(-4);
            var enddate = $('#enddate').val().slice(-2);
           // console.log(startno+'   '+endnolen);
            var JsonData = JSON.stringify(data);
            var resultdata = jQuery.parseJSON(JsonData);
            //console.log(str_pad(startno, endnolen));
            $('.loading').hide();
            if(resultdata.status == 1)
            {
                var preview = '';
                for(i in resultdata.result)
                {
                    var id = resultdata.result[i].id;
                    if(id == 1)
                        preview+=cmpprefix;
                    else if(id == 2)
                        preview+=seriesprefix;
                    else if(id == 3)
                        preview+=startdate+'/'+enddate;
                    else if(id == 4)
                        preview+=startdate+'-'+enddate;
                    else if(id == 5)
                        preview+=resultdata.curyear;
                    else if(id == 6)
                        preview+=resultdata.curmonth;
                    else if(id == 7)
                        preview+='/';
                    else if(id == 8)
                        preview+='-';
                    else if(id == 9)
                    {
                        //preview+=str_pad(parseInt(startno), parseInt(endnolen));
                        preview+=startno.padStart(endnolen, '0');
                    }                        
                    else if(id == 10)
                        preview+=storeprefix;
                }
                $('#preview').val(preview);
            }
        }

		//Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "editseriesmaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessEditdata,OnErrorEditdata); 
        }

        function OnsuccessEditdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resulteditdata = jQuery.parseJSON(JsonData);

			$('#id').val(resulteditdata.id);

            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcompany");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillcompany,OnErrorSelectpicker);
        
            function Onsuccessedtfillcompany(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata2 = jQuery.parseJSON(JsonData);
                $('#cmpid').html(resultdata2.data);
                $('#cmpid').val(resulteditdata.cmpid);
                $('#cmpid').selectpicker('refresh');	
            }
		
            var pagename=getpagename();
			var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillpagetype");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillpagetype,OnErrorSelectpicker);
            function Onsuccessedtfillpagetype(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata1 = jQuery.parseJSON(JsonData);
                $('#pagetype').html(resultdata1.data);
                $('#pagetype').val(resulteditdata.type);
                $('#pagetype').selectpicker('refresh');	
            }


			var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillelements");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillelements,OnErrorSelectpicker);
            function Onsuccessfillelements(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata12 = jQuery.parseJSON(JsonData);
               // console.log(resultdata12.data);
                $('#eleid').html(resultdata12.data);
                $('#eleid').selectpicker('refresh');	
            }

			
			var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
            formdata = new FormData();
            formdata.append("action", "checkdatause");
            formdata.append("id", resulteditdata.id);
            formdata.append("tblname",resulteditdata.tblname);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccesscheckdatause,OnErrorSelectpicker);
            function Onsuccesscheckdatause(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata1 = jQuery.parseJSON(JsonData);
                $('#editflg').val(0);
                $('#seriestags').tagEditor('destroy');
                $('#seriestags').tagEditor({ 
                    initialTags: resultdata1.element,
                    forceLowercase: false, 
                    removeDuplicates:false,
                    onChange: function(field, editor, tags) {
                        showpreview();
                    }, 
                });
                
                $('#preview').val(resultdata1.preview);
                if(resultdata1.status == 1)
                {
                    // $('#prefix').prop('readonly',true);
                    $('#editflg').val(1);
                    $("#startno").prop("disabled", true);
                    $("#startdate").prop("disabled", true);
                    // $("#prefix").prop("disabled", true);
                    if(resultdata1.checkdate == 1){
                        $("#endno").prop("disabled", true);
                    }
                }	
            }

			$('#startno').val(resulteditdata.startno);  
            $('#endno').val(resulteditdata.endno);
            $('#startdate').val(resulteditdata.startdate);
            $('#enddate').val(resulteditdata.enddate);  
            $('#prefix').val(resulteditdata.prefix);  
            $('#preview').val(resulteditdata.preview);  	

			
            $('#formevent').val('editright'); 
            $('#btnsubmit').text('<?php echo $config->getUpdateSidebar(); ?>');     

            //Open Side bar
			Edittimeformnamechange(1);
            $("#rightSidebar").toggleClass("active-right-sidebar"); 
            $('.overlay').addClass('show');
            $("body").addClass("overflow-hidden");
        }

        function OnErrorEditdata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
        //Edit End


		function viewitemimage(imgfullurl,categoryname)
        {
			$('#ItemImgModal .modal-title').html(itemname);
			$('#ItemImgModal .clsitemimg').attr('src',imgfullurl);
			$('#ItemImgModal .clsitemimglink').attr('href',imgfullurl);
            $('#ItemImgModal').modal('show');
        }


		//Start submit item form
		if($('#seriesmasterForm').length){		
            $('#seriesmasterForm').validate({
                rules:{
                    cmpid:{
                        required: true,			
                    },
					pagetype:{
                        required: true,			
                    },
                    startno:{
                        required: true,			
                    },
                    endno:{
                        required: true,			
                    },
                    prefix:{
                        required: true,			
                    },
                    preview:{
                        required: true,			
                    },
                },messages:{
                    cmpid:{
                        required:"Company is required ",
                    },
					pagetype:{
                        required:"Type  is required ",
                    },
                    startno:{
                        required:"Start No is required ",			
                    },
                    endno:{
                        required:"End No is required ",				
                    },
                    prefix:{
                        required:"Prefix No is required ",				
                    },
                    preview:{
                        required:"Preview is required ",				
                    },
					
                },
                submitHandler: function(form){
					$('#loaderprogress').show();
					var pagename=getpagename();
					//ar descr = $('#description').froalaEditor('html.get')
					var useraction=$('#formevent').val();
					formdata = new FormData(form);
					formdata.append("action", "insertseriesmaster");
					//formdata.append("descr", descr);
					var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
					ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit)
			
			    },
            });
        }
		//End submit item form

	</script>	