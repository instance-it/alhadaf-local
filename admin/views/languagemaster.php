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
								<h5 class="my-2">Language</h5>
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
													<th class="tbl-name sorting sorting_asc" data-th="languagename">Language Name</th>
													<th class="tbl-name sorting " data-th="languageengname">Language English Name</th>
													<th class="tbl-name sorting" data-th="displayorder">Display Order</th>
													<th class="tbl-name sorting" data-th="label1">Label 1</th>
													<th class="tbl-name sorting" data-th="label2">Label 2</th>
													<th class="tbl-name sorting" data-th="label3">Label 3</th>
													<?php 
														if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
														{
															?>
															<th class="tbl-name">Is Active</th>
															<th class="tbl-name">Show In App</th>
															<th class="tbl-name">Is Default</th>
															<?php
														}
													?>
													
													<th class="tbl-img">Icon</th>
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
										<a href="javascript:void(0)" class="btn btn-danger m-0 rounded-circle btn-close-right-sidebar" id="btnCloseRightSidebar" data-toggle="tooltip" data-placement="top" title="<?php echo $config->getCloseSidebar(); ?>" data-original-title="<?php echo $config->getCloseSidebar(); ?>"><i class="bi bi-x-lg"></i></a>
									</div>
								</div>
							</div>                                         
							<div class="col-lg-12 col-md-12 col-12">
								<div class="widget">
									<div class="widget-content">
										<form class="row validate-form" id="languagemasterForm" method="post" action="languagemaster" enctype="multipart/form-data">
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
													<label class="mb-1">Language Name <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="Language Name" id="languagename" name="languagename" require>
												</div>
											</div>

											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">Language English Name <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="Language English Name" id="languageengname" name="languageengname" require>
												</div>
											</div>

											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">Display Order <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="Display Order" id="displayorder" name="displayorder" onkeypress="numbonly(event)" require>
												</div>
											</div>

											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">Label 1 <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="Label 1" id="label1" name="label1" require>
												</div>
											</div>

											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">Label 2 <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="Label 2" id="label2" name="label2" require>
												</div>
											</div>

											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">Label 3</label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="Label 3" id="label3" name="label3">
												</div>
											</div>

											

											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">Upload Icon <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3 mb-lg-3">
													<input class="form-control d-none" type="file" name="img" id="img" accept="image/*">
													<label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="img">
														<span style="background-image: url(assets/img/salon.jpg);"></span>
														<i class="bi bi-folder2-open d-block pt-1"></i> Browse...
													</label>
													<span style="color:#B00" class="imgsizelbl2">** Please upload image in JPG,JPEG,PNG Format</span>
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


		<!-- Images View Modal -->
		<div class="modal animated fade" id="LanguageIconModal" tabindex="-1" role="dialog" aria-labelledby="modalImgViewLabel" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row mb-4">
							<div class="col-12">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
								<h5 class="add-event-title modal-title">Language Icon</h5>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-12 text-center">                                                
								<a href="" class="clscatimglink" target="_blank"><img src="" alt="category" class="img-thumbnail clscatimg"/></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	<script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');

			img.onchange = evt => 
			{
				const [file] = img.files
				if (file) 
				{
					filepreviewimg.src = URL.createObjectURL(file);
					var imgurlshopimg = $('#filepreviewimg').attr('src');

					$("#filepreviewimg").parent(".filepreviewbg").css("background-image", "url(" + imgurlshopimg + ")");
				}
			}


        });
		
		function resetdata()
        {
            $("#languagemasterForm").validate().resetForm();
            $('#languagemasterForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');
			Edittimeformnamechange(2);   
			$("#filepreviewimg").parent(".filepreviewbg").css("background-image", "");
        }


		//Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};

            formdata = new FormData();
            formdata.append("action", "editlanguagemaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessLanguageedit,OnErrorLanguageedit); 
        }

        function OnsuccessLanguageedit(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            $('#id').val(resultdata.id);
			$('#apptypeid').val(resultdata.apptypeid);
			$('#apptypeid').selectpicker('refresh');
            $('#languagename').val(resultdata.languagename);
			$('#languageengname').val(resultdata.languageengname);  
			$('#label1').val(resultdata.label1);  
			$('#label2').val(resultdata.label2);  
			$('#label3').val(resultdata.label3);  
			$('#displayorder').val(resultdata.displayorder);  

            $('#formevent').val('editright'); 
            $('#btnsubmit').text('<?php echo $config->getUpdateSidebar(); ?>');     

            //Open Side bar
			Edittimeformnamechange(1);
            $("#rightSidebar").toggleClass("active-right-sidebar"); 
            $('.overlay').addClass('show');
            $("body").addClass("overflow-hidden");
        }

        function OnErrorLanguageedit(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
        //Edit End

		//Start Change Active Status
		function changelanguagestatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changelanguagestatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangelanguagestatus,OnErrorchangelanguagestatus); 
        }

        function Onsuccesschangelanguagestatus(data)
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
        function OnErrorchangelanguagestatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
		//End Change Active Status

		//Start Change Show in app Status
		function changeshowinappstatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changeshowinappstatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangeshowinappstatus,OnErrorchangeshowinappstatus); 
        }

        function Onsuccesschangeshowinappstatus(data)
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
        function OnErrorchangeshowinappstatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
		//End Change Show in app Status


		//Start Change Default Status
		function changedefaultstatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changedefaultstatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangedefaultstatus,OnErrorchangedefaultstatus); 
        }

        function Onsuccesschangedefaultstatus(data)
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
        function OnErrorchangedefaultstatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
		//End Change Default Status

		//view language icon
		function viewlanguageimage(imgfullurl)
        {
			$('#LanguageIconModal .clscatimg').attr('src',imgfullurl);
			$('#LanguageIconModal .clscatimglink').attr('href',imgfullurl);
            $('#LanguageIconModal').modal('show');
        }


		if($('#languagemasterForm').length){		
            $('#languagemasterForm').validate({
                rules:{
					apptypeid:{
                        required: true,			
                    },
                    languagename:{
                        required: true,			
                    },
					languageengname:{
						required: true,		
					},
					displayorder:{
                        required: true,	
						number: true		
                    },
					label1:{
						required: true,		
					},
					label2:{
						required: true,		
					},
					
                },messages:{
					apptypeid:{
                        required:"App type is required",
                    },
                    languagename:{
                        required:"Language name is required",
                    },
					languageengname:{
                        required:"Language english name is required",
                    },
					displayorder:{
                        required: "Display order is required",	
						number: "Please enter valid display order",			
                    },
					label1:{
						required: "Label 1 is required",			
					},
					label2:{
						required: "Label 2 is required",	
					},
                },
                submitHandler: function(form){

                    $('#loaderprogress').show();
                    var pagename=getpagename();
                    var useraction=$('#formevent').val();
                    formdata = new FormData(form);
                    formdata.append("action", "insertlanguagemaster");
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit)

			    },
            });
        }

	</script>	