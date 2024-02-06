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
													<th class="tbl-name sorting" data-th="p.type">Payment Type</th>
													<th class="tbl-name sorting" data-th="p.displayorder">Display Order</th>
													<?php 
													if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
													{
														echo '<th class="tbl-name">Is Active</th>';
														
													}
													?>
													<th class="tbl-img">Image</th>
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
							<div class="col-12">
								<form class="row validate-form" id="paymenttypemasterForm" method="post" action="paymenttypemaster" enctype="multipart/form-data">                                       
									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget">
											<div class="widget-content row">
												
												<input type="hidden" id="formevent" name="formevent" value='addright'>
												<input type="hidden" id="id" name="id" value=''>

												
												<div class="col-12">
													<div class="input-group">
														<label class="mb-1">Payment Type<span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" placeholder="Payment Type" id="type" name="type" require>
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
														<label class="mb-1">Image <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3 mb-lg-3">
														<input type="file" class="form-control d-none" id="paytypeimg" name="paytypeimg" accept="image/*">
														<label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="paytypeimg">
															<span style="background-image: url(assets/img/salon.jpg);"></span>
															<i class="bi bi-folder2-open d-block pt-1"></i> Browse...
														</label>
														<span style="color:#B00" class="imgsizelbl2">** Please upload image in JPG,JPEG,PNG Format</span>
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

		<!-- Images View Modal -->
		<div class="modal animated fade" id="PaymenttypeImgModal" tabindex="-1" role="dialog" aria-labelledby="modalImgViewLabel" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row mb-4">
							<div class="col-12">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
								<h5 class="add-event-title modal-title">Payment Type Image</h5>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-12 text-center">                                                
								<a href="" class="clspaytypeimglink" target="_blank"><img src="" alt="item" class="img-thumbnail clspaytypeimg"/></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


	<script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
			var currpagename = getpagename();
            if(currpagename == 'ordermaster')
            {
                $('.pos-order').removeClass('d-none');
            }
            else
            {
                $('.pos-order').addClass('d-none');
            }
			
			$('#paymenttypemasterForm #description').froalaEditor();

        });

		
		function resetdata()
        {
            $("#paymenttypemasterForm").validate().resetForm();
            $('#paymenttypemasterForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');  
			Edittimeformnamechange(2);    
			
        }


		//Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "editpaymenttypemaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessEditdata,OnErrorEditdata); 
        }

        function OnsuccessEditdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resulteditdata = jQuery.parseJSON(JsonData);

			$('#id').val(resulteditdata.id);
			$('#type').val(resulteditdata.type);
			$('#displayorder').val(resulteditdata.displayorder);
			
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


		function viewpaymenttypeimage(imgfullurl,typename)
        {
			$('#PaymenttypeImgModal .modal-title').html(typename);
			$('#PaymenttypeImgModal .clspaytypeimg').attr('src',imgfullurl);
			$('#PaymenttypeImgModal .clspaytypeimglink').attr('href',imgfullurl);
            $('#PaymenttypeImgModal').modal('show');
        }


		//Start submit item form
		if($('#paymenttypemasterForm').length){		
            $('#paymenttypemasterForm').validate({
                rules:{
					type:{
                        required: true,			
                    },
					displayorder:{
                        required: true,	
						number:true,				
                    },
                },messages:{
					type:{
						required:"Payment type is required",
					},
                    displayorder:{
                        required:"Display order is required",
                    },
                },
                submitHandler: function(form){
					$('#loaderprogress').show();
					var pagename=getpagename();
					//ar descr = $('#description').froalaEditor('html.get')
					var useraction=$('#formevent').val();
					formdata = new FormData(form);
					formdata.append("action", "insertpaymenttypemaster");
					//formdata.append("descr", descr);
					var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
					ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit)
			
			    },
            });
        }
		//End submit item form

		function changepaymenttypestatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changepaymenttypestatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangepaymenttypestatus,OnErrorchangepaymenttypestatus); 
        }

        function Onsuccesschangepaymenttypestatus(data)
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
        function OnErrorchangepaymenttypestatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }



		
		
	</script>	