<?php 
// require_once dirname(__DIR__, 1).'\config\config.php';
// $config = new config();
require_once dirname(__DIR__, 1).'\config\init.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());
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
											/*
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
											*/
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
										/*
                                        if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                        {
                                            ?>
                                            <li class="mb-2">
                                                <a href="javascript:void(0);" class="btn btn-primary m-0" id="openRightSidebar">Add New</a>
                                            </li>
                                            <?php
                                        }
										*/
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
													<th class="tbl-name sorting sorting_asc" data-th="ct.regtype">Company Type</th>
													<th class="tbl-name sorting" data-th="cm.companyname">Company Name</th>
													<th class="tbl-name sorting" data-th="cm.contact1">Contact 1</th>
													<th class="tbl-name sorting" data-th="cm.email1">Email Id 1</th>
													<?php 
														if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
														{
															echo '<th class="tbl-name">Is Default</th>';
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
				<div class="col-12 p-4 lg-sidebar" id="rightSidebar">
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
								<form class="row validate-form" id="companymasterForm" method="post" action="companymaster" enctype="multipart/form-data">                                       
									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget">
											<div class="widget-content row">
												
												<input type="hidden" id="formevent" name="formevent" value='addright'>
												<input type="hidden" id="id" name="id" value=''>

												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Company Type <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<select class="form-control selectpicker" data-live-search="true" id="companytypeid" name="companytypeid" data-size="10" data-dropup-auto="false">
															<option value="">Select Company Type</option>
														</select>
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Company Name <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" id="companyname" name="companyname" class="form-control" placeholder="Enter Company Name" />
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Short Name <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" id="shortname" name="shortname" class="form-control" placeholder="Enter Short Name" />
													</div>
												</div>
												<div class="col-lg-2 col-md-2 col-sm-2 col-12">
													<div class="input-group">
														<label class="mb-1">Prefix <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" id="prefix" name="prefix" class="form-control" placeholder="Enter Prefix" />
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Contact 1 <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" id="contact1" name="contact1" class="form-control" onkeypress="numbonly(event)" maxlength="15" placeholder="Enter Contact 1" />
														
													</div>
													<div class="input-group mb-3 mb-lg-3">
														<div class="custom-control custom-checkbox m-0">
															<input type="checkbox" class="custom-control-input d-none" id="iswhatsappnumcontact1" name="iswhatsappnumcontact1" value="1">
															<label class="custom-control-label mb-0" for="iswhatsappnumcontact1">Is Whatsapp Number</label>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Contact 2</label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" id="contact2" name="contact2" class="form-control" onkeypress="numbonly(event)" maxlength="15" placeholder="Enter Contact 2" />
													</div>
													<div class="input-group mb-3 mb-lg-3">
														<div class="custom-control custom-checkbox m-0">
															<input type="checkbox" class="custom-control-input d-none" id="iswhatsappnumcontact2" name="iswhatsappnumcontact2" value="1">
															<label class="custom-control-label mb-0" for="iswhatsappnumcontact2">Is Whatsapp Number</label>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Contact 3 </label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" id="contact3" name="contact3" class="form-control" onkeypress="numbonly(event)" maxlength="15" placeholder="Enter Contact 3" />
													</div>
													<div class="input-group mb-3 mb-lg-3">
														<div class="custom-control custom-checkbox m-0">
															<input type="checkbox" class="custom-control-input d-none" id="iswhatsappnumcontact3" name="iswhatsappnumcontact3" value="1">
															<label class="custom-control-label mb-0" for="iswhatsappnumcontact3">Is Whatsapp Number</label>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Contact 4</label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" id="contact4" name="contact4" class="form-control" onkeypress="numbonly(event)" maxlength="15" placeholder="Enter Contact 4" />
													</div>
													<div class="input-group mb-3 mb-lg-3">
														<div class="custom-control custom-checkbox m-0">
															<input type="checkbox" class="custom-control-input d-none" id="iswhatsappnumcontact4" name="iswhatsappnumcontact4" value="1">
															<label class="custom-control-label mb-0" for="iswhatsappnumcontact4">Is Whatsapp Number</label>
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Email Id 1 <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="email" id="email1" name="email1" class="form-control" placeholder="Enter email id 1" />
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Email Id 2 </label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="email" id="email2" name="email2" class="form-control" placeholder="Enter email id 2" />
													</div>
												</div>

												<div class="col-lg-3 col-md-3 col-sm-6 col-12" id="gstdiv">
													<div class="input-group">
														<label class="mb-1">VAT No.</label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" id="gstno" name="gstno" class="form-control" maxlength="15" placeholder="Enter VAT No." autocomplete="off" >
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">TDS No. </label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input id="tdsno" name="tdsno" class="form-control" placeholder="Enter TDS No." autocomplete="off" type="text">
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Export Bond No. </label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input id="ebondno" name="ebondno" class="form-control" placeholder="Enter Export Bond No." autocomplete="off" type="text">
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Website</label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input id="website" name="website" class="form-control" placeholder="Enter Website" autocomplete="off" type="url">
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Address <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<textarea id="address" name="address" class="form-control" placeholder="Enter address" rows="3" requied="required"></textarea>
													</div>
												</div>

												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">State <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<select class="selectpicker form-control" data-live-search="true" id="stateid" name="stateid" data-size="8" data-dropup-auto="false" >
														</select>
													</div>
												</div>

												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">City <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<select class="selectpicker form-control" data-live-search="true" id="cityid" name="cityid" data-size="8" data-dropup-auto="false" >
														</select>
													</div>
												</div>

												<div class="col-lg-2 col-md-2 col-sm-2 col-12">
													<div class="input-group">
														<label class="mb-1">Pincode <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="pincode" id="pincode" maxlength="6" onkeypress="numbonly(event)"  placeholder="Enter Pincode" />   
													</div>
												</div>

												<div class="col-lg-6 col-md-6 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Google Map Link </label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="gmaplink" id="gmaplink"  placeholder="Google Map Link " />   
													</div>
												</div>

												<div class="col-lg-3 col-md-3 col-sm-6 col-12 d-none">
													<div class="input-group">
														<label class="mb-1">Inquiry From Time <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="inquiryfromtime" id="inquiryfromtime" placeholder="Enter Inquiry From Time" />   
													</div>
												</div>

												<div class="col-lg-3 col-md-3 col-sm-6 col-12 d-none">
													<div class="input-group">
														<label class="mb-1">Inquiry To Time <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="inquirytotime" id="inquirytotime" placeholder="Enter Inquiry To Time" />   
													</div>
												</div>

											</div>
										</div>
									</div>	
									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget">
											<div class="widget-content row">
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Upload Logo </label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="file" class="form-control d-none" id="logoimg" name="logoimg" accept="image/*">
														<label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="logoimg">
															<span style="background-image: url(assets/img/salon.jpg);"></span>
															<i class="bi bi-folder2-open d-block pt-1"></i> Browse...
														</label>
														<span style="color:#B00" class="imgsizelbl2">** Please upload image in JPG,JPEG,PNG Format</span>
														<span style="color:#B00" class="imgsizelbl2">** Please upload image size of 470px X 170px</span>
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Upload Stamp </label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="file" class="form-control d-none" id="stampimg" name="stampimg" accept="image/*">
														<label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="stampimg">
															<span style="background-image: url(assets/img/salon.jpg);"></span>
															<i class="bi bi-folder2-open d-block pt-1"></i> Browse...
														</label>
														<span style="color:#B00" class="imgsizelbl2">** Please upload image in JPG,JPEG,PNG Format</span>
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Upload Sign </label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="file" class="form-control d-none" id="signimg" name="signimg" accept="image/*">
														<label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="signimg">
															<span style="background-image: url(assets/img/salon.jpg);"></span>
															<i class="bi bi-folder2-open d-block pt-1"></i> Browse...
														</label>	
														<span style="color:#B00" class="imgsizelbl2">** Please upload image in JPG,JPEG,PNG Format</span>
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Upload Icon </label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="file" class="form-control d-none" id="iconimg" name="iconimg" accept="image/*">
														<label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="iconimg">
															<span style="background-image: url(assets/img/salon.jpg);"></span>
															<i class="bi bi-folder2-open d-block pt-1"></i> Browse...
														</label>
														<span style="color:#B00" class="imgsizelbl2">** Please upload image in JPG,JPEG,PNG Format</span>
													</div>
												</div>
												
											</div>
										</div>
									</div>	
									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget">
											<div class="widget-content row">

												<div class="col-lg-3 col-md-3 col-sm-6 col-3 offset-sm-2">
													<div class="input-group">
														<label class="mb-1">Range Hours Title <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="rhname" id="rhname" placeholder="Enter Range Hours Title *" autocomplete="off" />
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-3">
													<div class="input-group">
														<label class="mb-1">Display Order <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="order" id="order" onkeypress="numbonly(event)" placeholder="Enter Display Order *" autocomplete="off"/>
													</div>
												</div>
												<div class="col-lg-1 col-md-1 col-sm-1 col-1">
													<div class="input-group">
														<label class="mb-1">&nbsp;</label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="button" class="form-control btn btn-primary" name="btnaddrange" id="btnaddrange" value="ADD">
													</div>
												</div>
												<div class="col-lg-7 col-md-8 col-sm-7 col-7 offset-sm-2">
													<div class="table-responsive table-scroll" style="height: 200px;overflow-y:auto;margin: 10px;padding:0px;" id="table-scroll">
														<!-- <div class="chart-loading">
															<img alt="loading-img" src="img/loading.gif">
														</div> -->
														<table class="table table-bordered main-table" id="main-table" style="font-size: 1.1em;text-align: center;">
															<thead>
																<tr class="info">
																	<th width="65%;" style="text-align: left;">Range Hour Title</th>
																	<th width="30%;" style="text-align: center;">Display Order</th>
																	<th width="5%;" style="text-align: center;"><i class="fa fa-trash"></i></th>
																</tr>
															</thead>
															<tbody id="tbltbody">
																
															</tbody>	
														</table>								
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget">
											<div class="widget-content row">

												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Person Name <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="pname" id="pname" placeholder="Enter Person Name *" autocomplete="off" required />
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Email <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="email" class="form-control" name="pemail" id="pemail" placeholder="Enter Email *" autocomplete="off" required />
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Contact <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="pcontact" id="pcontact" onkeypress="numbonly(event)" maxlength="15" placeholder="Enter Contact no *" autocomplete="off" required />	
													</div>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-6 col-12">
													<div class="input-group">
														<label class="mb-1">Designation <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="pdesignation" id="pdesignation" placeholder="Enter designation *" autocomplete="off" required />
													</div>
												</div>


												<div class="col-12 ">    
													<div class="ml-auto">
														<div class="input-group mb-0">
															<button type="submit" class="btn btn-primary m-0  ml-auto" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
															<!-- <button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php //echo $config->getResetSidebar(); ?></button> -->
														</div>
													</div>
												</div>
												
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

	<script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');

			fillcompanytype();
			fillstate();
        });

		
		function resetdata()
        {
            $("#companymasterForm").validate().resetForm();
            $('#companymasterForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
			$('.main-table tbody').html('');	
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');  
			Edittimeformnamechange(2); 

			$('#iswhatsappnumcontact1').prop('disabled',true); 
			$('#iswhatsappnumcontact2').prop('disabled',true);
			$('#iswhatsappnumcontact3').prop('disabled',true);
			$('#iswhatsappnumcontact4').prop('disabled',true); 
			
			fillcompanytype();
			fillstate();

        }


		$('#inquiryfromtime').bootstrapMaterialDatePicker({
            date: false,
            format: 'h:mm A',
            switchOnClick: true,
            okText: ""
        }).on('change', function(e, date)
		{
			$('#inquirytotime').bootstrapMaterialDatePicker('setMinDate',date);
			$('#inquirytotime').val($('#inquiryfromtime').val());

		});

		$('#inquirytotime').bootstrapMaterialDatePicker({
            date: false,
            format: 'h:mm A',
            switchOnClick: true,
            okText: ""
        }).on('change', function(e, date)
		{
			
		});


		$('body').on('input','#contact1',function(){
			var contact1 = $(this).val();
			if(contact1)
			{
				$('#iswhatsappnumcontact1').prop('disabled',false); 
			}
			else
			{
				$('#iswhatsappnumcontact1').prop('disabled',true); 
				$('#iswhatsappnumcontact1').prop('checked',false);
			}
		});

		$('body').on('input','#contact2',function(){
			var contact2 = $(this).val();
			if(contact2)
			{
				$('#iswhatsappnumcontact2').prop('disabled',false); 
			}
			else
			{
				$('#iswhatsappnumcontact2').prop('disabled',true); 
				$('#iswhatsappnumcontact2').prop('checked',false);
			}
		});

		$('body').on('input','#contact3',function(){
			var contact3 = $(this).val();
			if(contact3)
			{
				$('#iswhatsappnumcontact3').prop('disabled',false); 
			}
			else
			{
				$('#iswhatsappnumcontact3').prop('disabled',true); 
				$('#iswhatsappnumcontact3').prop('checked',false);
			}
		});


		$('body').on('input','#contact4',function(){
			var contact4 = $(this).val();
			if(contact4)
			{
				$('#iswhatsappnumcontact4').prop('disabled',false); 
			}
			else
			{
				$('#iswhatsappnumcontact4').prop('disabled',true); 
				$('#iswhatsappnumcontact4').prop('checked',false);
			}
		});

		
		function fillcompanytype()
        {
			var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcompanytype");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillcompanytype,OnErrorSelectpicker); 
        }

        function Onsuccessfillcompanytype(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#companytypeid').html(resultdata.data);
            $('#companytypeid').selectpicker('refresh');	
        }


		//start  fill state
		function fillstate()
        {
			var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillstate");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillstate,OnErrorSelectpicker); 
        }

        function Onsuccessfillstate(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#stateid').html(resultdata.data);
            $('#stateid').val('65754311-A0BB-4A7F-9043-05670385BF13');
            $('#stateid').selectpicker('refresh');	
            fillcity();	
        }
		//end  fill state

        $('body').on('change','#stateid', function () {
            fillcity();	
        });

		//start  fill City
        function fillcity()
        {
			var pagename=getpagename();
            var stateid=$('#stateid').val();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcity");
            formdata.append("stateid",stateid);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcity,OnErrorSelectpicker);
        }

        function Onsuccessfillcity(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#cityid').html(resultdata.data);
            $('#cityid').selectpicker('refresh');	
        }
		//end  fill City


		
		$('body').on('change','#companytypeid',function(){
			var companytypeid = $(this).val();
			if(companytypeid != '<?php echo $config->getCmpUnRegisterId(); ?>')
			{
				$('#gstdiv').removeClass('d-none');
			}
			else
			{
				$('#gstdiv').addClass('d-none');
				$('#gstno').val('');
			}
		});


		//Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "editcompanymaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessEditdata,OnErrorEditdata); 
        }

        function OnsuccessEditdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resulteditdata = jQuery.parseJSON(JsonData);

			$('#id').val(resulteditdata.id);

			var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcompanytype");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessedtfillcompanytype,OnErrorSelectpicker); 
        
			function Onsuccessedtfillcompanytype(content)
			{
				var JsonData = JSON.stringify(content);
				var resultdata = jQuery.parseJSON(JsonData);
				$('#companytypeid').html(resultdata.data);
				$('#companytypeid').val(resulteditdata.companytypeid);
				$('#companytypeid').selectpicker('refresh');	
			}

			var pagename=getpagename();
			var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillstate");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillstate,OnErrorSelectpicker); 

			function Onsuccessedtfillstate(content)
			{
				var JsonData = JSON.stringify(content);
				var resultdata = jQuery.parseJSON(JsonData);
				$('#stateid').html(resultdata.data);
				$('#stateid').val(resulteditdata.stateid);
				$('#stateid').selectpicker('refresh');	
			
				var pagename=getpagename();
				var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
				formdata = new FormData();
				formdata.append("action", "fillcity");
				formdata.append("stateid",resulteditdata.stateid);
				ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcity,OnErrorSelectpicker);

				function Onsuccessfillcity(content)
				{
					var JsonData = JSON.stringify(content);
					var resultdata = jQuery.parseJSON(JsonData);
					$('#cityid').html(resultdata.data);
					$('#cityid').val(resulteditdata.cityid);
					$('#cityid').selectpicker('refresh');	
				}

			}

			if(resulteditdata.companytypeid != '<?php echo $config->getCmpUnRegisterId(); ?>')
			{
				$('#gstdiv').removeClass('d-none');
			}
			else
			{
				$('#gstdiv').addClass('d-none');
				$('#gstno').val('');
			}


			$('#companyname').val(resulteditdata.companyname);
			$('#shortname').val(resulteditdata.shortname);
			$('#prefix').val(resulteditdata.prefix);
			$('#contact1').val(resulteditdata.contact1);

			if(resulteditdata.contact1)
			{
				$('#iswhatsappnumcontact1').prop('disabled',false); 
			}
			else
			{
				$('#iswhatsappnumcontact1').prop('disabled',true); 
			}

			if(resulteditdata.contact2)
			{
				$('#iswhatsappnumcontact2').prop('disabled',false); 
			}
			else
			{
				$('#iswhatsappnumcontact2').prop('disabled',true); 
			}

			if(resulteditdata.contact3)
			{
				$('#iswhatsappnumcontact3').prop('disabled',false); 
			}
			else
			{
				$('#iswhatsappnumcontact3').prop('disabled',true); 
			}

			if(resulteditdata.contact4)
			{
				$('#iswhatsappnumcontact4').prop('disabled',false); 
			}
			else
			{
				$('#iswhatsappnumcontact4').prop('disabled',true); 
			}

			$('#contact2').val(resulteditdata.contact2);
			$('#contact3').val(resulteditdata.contact3);
			$('#contact4').val(resulteditdata.contact4);
			$('#email1').val(resulteditdata.email1);
			$('#email2').val(resulteditdata.email2);
			$('#gstno').val(resulteditdata.gstno);
			$('#tdsno').val(resulteditdata.tdsno);
			$('#ebondno').val(resulteditdata.ebondno);
			$('#website').val(resulteditdata.website);
			$('#address').val(resulteditdata.address);
			$('#pincode').val(resulteditdata.pincode);
			$('#gmaplink').val(resulteditdata.gmaplink);
			$('#pname').val(resulteditdata.pname);
			$('#pemail').val(resulteditdata.pemail);
			$('#pcontact').val(resulteditdata.pcontact);
			$('#pdesignation').val(resulteditdata.pdesignation);

			$('#inquirytotime').bootstrapMaterialDatePicker('setMinDate',resulteditdata.inquiryfromtime);

			$('#inquiryfromtime').val(resulteditdata.inquiryfromtime);
			$('#inquirytotime').val(resulteditdata.inquirytotime);

			if(resulteditdata.iswhatsappnumcontact1 == 1)
			{
				$('#iswhatsappnumcontact1').prop('checked',true);
			}
			else
			{
				$('#iswhatsappnumcontact1').prop('checked',false);
			}
			if(resulteditdata.iswhatsappnumcontact2 == 1)
			{
				$('#iswhatsappnumcontact2').prop('checked',true);
			}
			else
			{
				$('#iswhatsappnumcontact2').prop('checked',false);
			}
			if(resulteditdata.iswhatsappnumcontact3 == 1)
			{
				$('#iswhatsappnumcontact3').prop('checked',true);
			}
			else
			{
				$('#iswhatsappnumcontact3').prop('checked',false);
			}
			if(resulteditdata.iswhatsappnumcontact4 == 1)
			{
				$('#iswhatsappnumcontact4').prop('checked',true);
			}
			else
			{
				$('#iswhatsappnumcontact4').prop('checked',false);
			}

			var tbldata='';
			var trtmp=1;
			for (var j in resulteditdata.result)
			{
				tbldata+='<tr data-index="'+trtmp+'">';
				tbldata+='<td align="left" style="vertical-align: middle;">';
				tbldata+='<input type="text" class="form-control rangetitle" name="rangetitle[]" id="rangetitle'+trtmp+'" value="'+resulteditdata.result[j].name+'" /></td>';
				tbldata+='<td align="center" style="vertical-align: middle;">';
				tbldata+='<input type="text" class="form-control displayorder" name="displayorder[]" id="displayorder'+trtmp+'" value="'+resulteditdata.result[j].displayorder+'" onkeypress="numbonly(event)" /></td>';
				tbldata+='<td align="center" style="vertical-align: middle;"><a href="javascript:void(0)" id="btndelete" class="btnremove"><i class="fas fa-trash"></i></a></td>';
				tbldata+='</tr>';
				trtmp++;
			}
			$('.main-table tbody').html(tbldata);	

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


		//View Details Start
        function viewcompanydata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "viewcompanydata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewcompanydata,OnErrorviewcompanydata); 
        }

		function Onsuccessviewcompanydata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-xl');
			$('#GeneralModal #GeneralModalLabel').html('Company Details');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorviewcompanydata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }

		//Is Default Change
		function changedefultstatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changedefultstatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangedefultstatus,OnErrorchangedefultstatus); 
        }

        function Onsuccesschangedefultstatus(data)
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
        function OnErrorchangedefultstatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }


		if($('#companymasterForm').length){		
            $('#companymasterForm').validate({
                rules:{
                    companytypeid:{
                        required: true,			
                    },
                    companyname:{
                        required: true,			
                    },
					shortname:{
						required: true,
					},
					prefix:{
						required: true,
					},
					contact1:{
						required: true,
						//number: true,
						//contact:true,
					},
					contact2:{
						//number: true,
						//contact:true,
					},
					contact3:{
						//number: true,
						//contact:true,
					},
					contact4:{
						//number: true,
						//contact:true,
					},
					email1:{
						required: true,
					},
					gstno:{
						gstno:true
					},
					website:{
						url:true,				
                    },
					address:{
						required: true,
					},
					stateid:{
						required: true,
					},
					cityid:{
						required: true,
					},
					pincode:{
						number:true,
						//required:true,
						maxlength: 6
					},
					pname:{
						required: true,
					},
					pemail:{
						required: true,
					},
					pcontact:{
						required: true,
						//number: true,
						//contact:true,
					},
					pdesignation:{
						required: true,
					},
					inquiryfromtime:{
						required: true,
					},
					inquirytotime:{
						required: true,
					},

                },messages:{
					companytypeid:{
                        required:"Company type is required",	
                    },
                    companyname:{
                        required:"Company name is required",	
                    },
					shortname:{
						required:"Short name is required",	
					},
					prefix:{
						required:"Prefix is required",	
					},
					contact1:{
						required:"Contact 1 is required",	
					},
					email1:{
						required:"Email id 1 is required",	
					},
					address:{
						required:"Address is required",	
					},
					stateid:{
						required:"State is required",	
					},
					cityid:{
						required:"City is required",	
					},
					pincode:{
						required:"Pincode is required",	
					},
					pname:{
						required:"Person name is required",	
					},
					pemail:{
						required:"Email is required",	
					},
					pcontact:{
						required:"Contact is required",	
					},
					pdesignation:{
						required:"Designation is required",	
					},
					inquiryfromtime:{
						required:"Inquiry from time is required",	
					},
					inquirytotime:{
						required:"Inquiry to time is required",	
					},
                },
                submitHandler: function(form){
					$('#loaderprogress').show();
					var pagename=getpagename();
					var useraction=$('#formevent').val();
					formdata = new FormData(form);
					formdata.append("action", "insertcompanymaster");
					var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
					ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit)
			
			    },
            });
        }

		// add range hour texts
		$('body').on('click','#btnaddrange',function()
		{
			var rhname = $("#rhname").val();
			var displayorder=$('#order').val();

			if(rhname && displayorder)
			{
				var trtmp = $('.main-table tbody tr:last').attr('data-index') | 0;
				trtmp = parseInt(trtmp+1);

				var tbldata='';
				tbldata+='<tr data-index="'+trtmp+'">';
				tbldata+='<td align="left" style="vertical-align: middle;">';
				tbldata+='<input type="text" class="form-control rangetitle" name="rangetitle[]" id="rangetitle'+trtmp+'" value="'+rhname+'" /></td>';
				tbldata+='<td align="center" style="vertical-align: middle;">';
				tbldata+='<input type="text" class="form-control displayorder" name="displayorder[]" id="displayorder'+trtmp+'" value="'+displayorder+'" onkeypress="numbonly(event)" /></td>';
				tbldata+='<td align="center" style="vertical-align: middle;"><a href="javascript:void(0)" id="btndelete" class="btnremove"><i class="fas fa-trash"></i></a></td>';
				tbldata+='</tr>';

				$('.main-table tbody').append(tbldata);	
				$('#order').val('');
				$('#rhname').val('');
				$( "#rhname" ).focus();

			}
			else
			{
				alertify('Please fill all required fields',0);
			}
			
		});

		$('body').on('click','.btnremove',function(){
			var thisss=$(this);
			thisss.parent().parent().remove();	
		});

	</script>	