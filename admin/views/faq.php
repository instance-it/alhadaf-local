<?php 
require_once dirname(__DIR__, 1).'\config\init.php';
?>
	<div class="layout-px-spacing">
		<!-- CONTENT AREA -->
		<div class="row layout-top-spacing">
			<div class="col-12 layout-spacing d-none">
				<div class="widget">
					<div class="widget-heading m-0">
						<div class="row">
							<div class="col">
								<h5 class="my-2"></h5>
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
													
													<th class="tbl-name sorting " data-th="f.question">Question</th>
													<th class="tbl-name sorting " data-th="f.displayorder">Display Order</th>
													<?php 
													if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
													{
														echo '<th class="tbl-name">Status</th>';
													}
													?>
													
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
										<h5 class="my-2"><span class="formnamelbl"></span></h5>
									</div>
									<div class="col-auto pl-0">
										<a href="#" class="btn btn-danger m-0 rounded-circle btn-close-right-sidebar" id="btnCloseRightSidebar" data-toggle="tooltip" data-placement="top" title="<?php echo $config->getCloseSidebar(); ?>" data-original-title="<?php echo $config->getCloseSidebar(); ?>"><i class="bi bi-x-lg"></i></a>
									</div>
								</div>
							</div>                                         
							<div class="col-lg-12 col-md-12 col-12">
								<div class="widget">
									<div class="widget-content">
										<form class="row validate-form" id="faqForm" method="post" action="faq" enctype="multipart/form-data">
											<input type="hidden" id="formevent" name="formevent" value='addright'>
											<input type="hidden" id="id" name="id" value=''>
										
											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">Question <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="Question" id="question" name="question" require>
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
													<label class="mb-1">Answer <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<!-- <input type="text" class="form-control" placeholder="Answer" id="answer" name="answer" require> -->
													<textarea class="" id="answer" name="answer"></textarea>
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
										</from>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- Images View Modal -->
		<div class="modal animated fade" id="SliderImgModal" tabindex="-1" role="dialog" aria-labelledby="modalImgViewLabel" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row mb-4">
							<div class="col-12">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
								<h5 class="add-event-title modal-title">Slider Image</h5>
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

			$('#answer').froalaEditor();

        });
		
		function resetdata()
        {
            $("#faqForm").validate().resetForm();
            $('#faqForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');
			$('#answer').froalaEditor();
			Edittimeformnamechange(2); 
			$("#filepreviewimg").parent(".filepreviewbg").css("background-image", "");
        }

		//Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};

            formdata = new FormData();
            formdata.append("action", "editfaq");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessSubbrandedit,OnErrorSubbrandedit); 
        }

        function OnsuccessSubbrandedit(content)
        {
            var JsonData = JSON.stringify(content);
            var resulteditdata = jQuery.parseJSON(JsonData);

            $('#id').val(resulteditdata.id);
			$('#question').val(resulteditdata.question);
			$('#displayorder').val(resulteditdata.displayorder);
			//$('#answer').val(resulteditdata.answer);
            $('#answer').froalaEditor('html.set',resulteditdata.answer); 

	
            $('#formevent').val('editright'); 
            $('#btnsubmit').text('<?php echo $config->getUpdateSidebar(); ?>');     

            //Open Side bar
			Edittimeformnamechange(1);
            $("#rightSidebar").toggleClass("active-right-sidebar"); 
            $('.overlay').addClass('show');
            $("body").addClass("overflow-hidden");
        }

        function OnErrorSubbrandedit(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
        //Edit End



		// change Status
		function changefaqstatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changefaqstatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangefaqstatus,OnErrorchangefaqstatus); 
        }

        function Onsuccesschangefaqstatus(data)
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
        function OnErrorchangefaqstatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }


		//View Answer Data
        function vieanswerdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "vieanswerdata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessvieanswerdata,OnErrorvieanswerdata); 
        }

		function Onsuccessvieanswerdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-xl');
			$('#GeneralModal #GeneralModalLabel').html('Answer');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorvieanswerdata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }

		if($('#faqForm').length){		
            $('#faqForm').validate({
                rules:{
                    question:{
                        required: true,			
                    },
					displayorder:{
                        required: true,	
						number: true		
                    },
				
                },messages:{
                    question:{
                        required:"Question is required",
                    },
					displayorder:{
                        required: "Display order is required",	
						number: "Please enter valid display order",			
                    },
                },
                submitHandler: function(form){

                    $('#loaderprogress').show();
                    var pagename=getpagename();
                    var useraction=$('#formevent').val();
                    formdata = new FormData(form);
                    formdata.append("action", "insertfaq");
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit)

			    },
            });
        }

	</script>	