<?php 
require_once dirname(__DIR__, 1).'/config/init.php';
?>
	<div class="layout-px-spacing">
		<!-- CONTENT AREA -->
		<div class="row layout-top-spacing">
			<div class="col-12 layout-spacing d-none">
				<div class="widget">
					<div class="widget-heading m-0">
						<div class="row">
							<div class="col">
								<h5 class="my-2">Label</h5>
							</div>
							<div class="col-auto ml-auto">
								<div class="input-group mb-0">
									<a href="#" class="btn btn-primary m-0 ml-auto" id="openRightSidebar"><i class="bi bi-plus-lg"></i> Add New</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
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
													
													<th class="tbl-name">App Type</th>
													<th class="tbl-name sorting sorting_asc" data-th="m.menuname">Appmenu Name</th>
													<th class="tbl-name sorting" data-th="lm.labelnameid">Label Name ID</th>
													<th class="tbl-name sorting" data-th="lm.labelengname">Label English Name</th>
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
										<form class="row validate-form" id="labelmasterForm" method="post" action="labelmaster" enctype="multipart/form-data">
											<input type="hidden" id="formevent" name="formevent" value='addright'>
											<input type="hidden" id="id" name="id" value=''>
											
											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">App Type <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<select class="form-control selectpicker" data-live-search="true" id="apptypeid" name="apptypeid" data-size="10" data-dropup-auto="false">
														<option value="2">Mobile App</option>
														<option value="3">POS</option>
													</select>
												</div>
											</div>

											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">Appmenu Name <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<select class="form-control selectpicker" data-live-search="true" id="appmenuid" name="appmenuid" data-size="10" data-dropup-auto="false">
														<option value="">Select Appmenu</option>
													</select>
												</div>
											</div>


											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">Label Name ID <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="Label Name ID" id="labelnameid" name="labelnameid" require>
												</div>
											</div>

											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">Label English Name <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="Label English Name" id="labelengname" name="labelengname" require>
												</div>
											</div>

											
											<div class="row col-12">
												<div class="ml-auto">
													<div class="input-group mb-0">
														<button type="submit" class="btn btn-primary m-0" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
														<button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
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
			</div>
		</div>



	<script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
			fillappmenu();
        });
		
		function resetdata()
        {
            $("#labelmasterForm").validate().resetForm();
            $('#labelmasterForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');
			Edittimeformnamechange(2);   
			fillappmenu();
        }

		$('#apptypeid').on('change',function(){
			fillappmenu();
		});

		function fillappmenu()
        {
			var apptypeid = $('#apptypeid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillappmenu");
			formdata.append("selectoption", 0);
			formdata.append("apptypeid", apptypeid);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillcategory,OnErrorSelectpicker);
        }

        function Onsuccessfillcategory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#appmenuid').html(resultdata.data);
            $('#appmenuid').selectpicker('refresh');
        }

		//Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};

            formdata = new FormData();
            formdata.append("action", "editlabelmaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessSubcategoryedit,OnErrorSubcategoryedit); 
        }

        function OnsuccessSubcategoryedit(content)
        {
            var JsonData = JSON.stringify(content);
            var resulteditdata = jQuery.parseJSON(JsonData);

            $('#id').val(resulteditdata.id);
			$('#apptypeid').val(resulteditdata.apptypeid);
			$('#apptypeid').selectpicker('refresh');
            $('#labelnameid').val(resulteditdata.labelnameid);
			$('#labelengname').val(resulteditdata.labelengname);  


			var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillappmenu");
			formdata.append("selectoption", 0);
			formdata.append("apptypeid", resulteditdata.apptypeid);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessedtfillappmenu,OnErrorSelectpicker);
        

			function Onsuccessedtfillappmenu(content)
			{
				var JsonData = JSON.stringify(content);
				var resultdata = jQuery.parseJSON(JsonData);
				$('#appmenuid').html(resultdata.data);
				$('#appmenuid').val(resulteditdata.appmenuid);
				$('#appmenuid').selectpicker('refresh');
			}


            $('#formevent').val('editright'); 
            $('#btnsubmit').text('<?php echo $config->getUpdateSidebar(); ?>');     

            //Open Side bar
			Edittimeformnamechange(1);
            $("#rightSidebar").toggleClass("active-right-sidebar"); 
            $('.overlay').addClass('show');
            $("body").addClass("overflow-hidden");
        }

        function OnErrorSubcategoryedit(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
        //Edit End


		function viewsubcategoryimage(imgfullurl)
        {
			$('#SubCategoryImgModal .clscatimg').attr('src',imgfullurl);
			$('#SubCategoryImgModal .clscatimglink').attr('href',imgfullurl);
            $('#SubCategoryImgModal').modal('show');
        }


		if($('#labelmasterForm').length){		
            $('#labelmasterForm').validate({
                rules:{
					apptypeid:{
						required: true,			
					},
					appmenuid:{
						required: true,			
					},
                    labelnameid:{
                        required: true,			
                    },
					labelengname:{
                        required: true,	
                    },
                },messages:{
					apptypeid:{
						required:"App type is required",
					},
					appmenuid:{
						required:"Appmenu name is required",
					},
                    labelnameid:{
                        required:"Label name id is required",
                    },
					labelengname:{
                        required: "Label english name is required",		
                    },
                },
                submitHandler: function(form){
                    $('#loaderprogress').show();
                    var pagename=getpagename();
                    var useraction=$('#formevent').val();
                    formdata = new FormData(form);
                    formdata.append("action", "insertlabelmaster");
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit1,OnErrorDataSubmit1)
			    },
            });
        }

		function OnsuccessDataSubmit1(data)
		{
			var JsonData = JSON.stringify(data);
			var resultdata = jQuery.parseJSON(JsonData);
			$('#loaderprogress').hide();
				
			if(resultdata.status==0)
			{
				alertify(resultdata.message,0);
				
			}
			else if(resultdata.status==1)
			{
				alertify(resultdata.message,1);
				$('#tableDataList').attr('data-nextpage',0); 
				listdata();
				
				$('#labelnameid').val('');
				$('#labelengname').val('');
				$('#formevent').val('addright');
				$('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');
				Edittimeformnamechange(2);   
			}
		}

		function OnErrorDataSubmit1()
		{
			ajaxrequest("POST", page404url, '', '', OnsuccessRender, OnErrorRender);
		}

	</script>	