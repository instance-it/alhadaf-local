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
									<a href="javascript:void(0)" class="btn btn-primary m-0 ml-auto" id="openRightSidebar"><i class="bi bi-plus-lg"></i> Add New</a>
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
										<li class="mb-2">
											<a href="javascript:void(0);" class="btn btn-primary m-0" id="openfilter">
												<i class="bi bi-funnel"></i>
											</a>
										</li>
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
													
													<th class="tbl-name sorting sorting_asc" data-th="c.category">Category</th>
													<th class="tbl-name sorting" data-th="sc.subcategory">Sub Category</th>
													<th class="tbl-name sorting" data-th="im.itemname">Item Name</th>
													<th class="tbl-name sorting" data-th="im.price">Price</th>
													<th class="tbl-info sorting" data-th="t.taxname">Tax</th>
													<th class="tbl-name">Duration</th>
													<th class="tbl-name">Is Composite Item</th>
													<th class="tbl-img">Image</th>
													<?php 
													if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
													{
														echo '<th class="tbl-name">Show on Home</th>';
														echo '<th class="tbl-name">Show on Web</th>';
														echo '<th class="tbl-name">Show on POS</th>';
														echo '<th class="tbl-name">Is Active</th>';
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
										<a href="javascript:void(0)" class="btn btn-danger m-0 rounded-circle btn-close-right-sidebar" id="btnCloseRightSidebar" data-toggle="tooltip" data-placement="top" title="<?php echo $config->getCloseSidebar(); ?>" data-original-title="<?php echo $config->getCloseSidebar(); ?>"><i class="bi bi-x-lg"></i></a>
									</div>
								</div>
							</div>   
							
								<div class="col-12">
									<form class="row validate-form" id="itemmasterForm" method="post" action="itemmaster" enctype="multipart/form-data">
										<input type="hidden" id="formevent" name="formevent" value='addright'>
										<input type="hidden" id="id" name="id" value=''>
										

										<!----------------------- Start For Basic Details Items ------------------------->
										<div class="col-lg-12 col-md-12 col-12">
											<div class="widget">
												<div class="widget-content row">
													
													<div class="col-12 col-sm-3 col-md-3 col-lg-3">
														<div class="input-group">
															<label class="mb-1">Category <span class="text-danger">*</span></label>
															<label class="ml-auto "></label>
														</div>
														<div class="input-group mb-3">
															<select class="form-control selectpicker" data-live-search="true" id="categoryid" name="categoryid" data-size="10" data-dropup-auto="false">
																<option value="">Select Category</option>
															</select>
														</div>
													</div>

													<div class="col-12 col-sm-3 col-md-3 col-lg-3">
														<div class="input-group">
															<label class="mb-1">Sub Category <span class="text-danger">*</span></label>
															<label class="ml-auto "></label>
														</div>
														<div class="input-group mb-3">
															<select class="form-control selectpicker" data-live-search="true" id="subcategoryid" name="subcategoryid" data-size="10" data-dropup-auto="false">
																<option value="">Select Sub Category</option>
															</select>
														</div>
													</div>

													<div class="col-12 col-sm-3 col-md-3 col-lg-3">
														<div class="input-group mb-0">
															<label class="mb-1">Store/Counter <span class="text-danger">*</span></label>
														</div>
														<div class="input-group mb-3 mb-lg-3">
															<select class="form-control selectpicker" data-live-search="true" multiple="multiple" id="storeid" name="storeid[]" data-size="10" data-dropup-auto="false" title="Select Store/Counter" data-actions-box="true">
																<option value="">Select Store/Counter</option>
															</select>
														</div>
													</div>

													<div class="col-12 col-sm-3 col-md-3 col-lg-3">
														<div class="input-group">
															<label class="mb-1">SAP Item </label>
															<label class="ml-auto "></label>
														</div>
														<div class="input-group mb-3">
															<select class="form-control selectpicker" data-live-search="true" id="sapitemid" name="sapitemid" data-size="10" data-dropup-auto="false">
																<option value="">Select SAP Item</option>
															</select>
														</div>
													</div>
													

													<div class="col-12 col-sm-6 col-md-6 col-lg-6">
														<div class="input-group">
															<label class="mb-1">Item Name <span class="text-danger">*</span></label>
															<label class="ml-auto "></label>
														</div>
														<div class="input-group mb-3">
															<input type="text" class="form-control" placeholder="Item Name" id="itemname" name="itemname" require>
														</div>
													</div>

													<div class="col-12 col-sm-2 col-md-2 col-lg-2">
														<div class="input-group">
															<label class="mb-1">Price <span class="text-danger">*</span></label>
															<label class="ml-auto "></label>
														</div>
														<div class="input-group mb-3">
															<input type="text" class="form-control" placeholder="Price" id="price" name="price" onkeypress="numbonly(event)" require>
														</div>
													</div>

													<div class="col-12 col-sm-2 col-md-2 col-lg-2">
														<div class="input-group mb-0">
															<label class="mb-1">Tax Type <span class="text-danger">*</span></label>
														</div>
														<div class="input-group mb-3 mb-lg-3">
															<select class="form-control selectpicker" data-live-search="true" id="gsttypeid" name="gsttypeid" data-size="10" data-dropup-auto="false" >
																<option value="">Select Tax Type</option>
															</select>
														</div>
													</div>

													<div class="col-12 col-sm-2 col-md-2 col-lg-2">
														<div class="input-group mb-0">
															<label class="mb-1">Tax <span class="text-danger">*</span></label>
														</div>
														<div class="input-group mb-3 mb-lg-3">
															<select class="form-control selectpicker" data-live-search="true" id="gstid" name="gstid" data-size="10" data-dropup-auto="false" >
																<option value="">Select Tax</option>
															</select>
														</div>
													</div>

													<div class="col-12 col-sm-2 col-md-2 col-lg-2 browmaterialdiv  d-none">
														<div class="input-group">
															<label class="mb-1">Duration (Hours) <span class="text-danger">*</span></label>
															<label class="ml-auto "></label>
														</div>
														<div class="input-group mb-3">
															<input type="text" class="form-control" placeholder="Duration (Hours)" id="duration" name="duration" onkeypress="numbonly(event)" require>
														</div>
													</div>

													<div class="col-12 col-sm-2 col-md-2 col-lg-2 browmaterialdiv  d-none">
														<div class="input-group">
															<label class="mb-1">No Of Students <span class="text-danger">*</span></label>
															<label class="ml-auto "></label>
														</div>
														<div class="input-group mb-3">
															<input type="text" class="form-control" placeholder="No of Students" id="noofstudent" name="noofstudent" onkeypress="numbonly(event)" require>
														</div>
													</div>

													<div class="col-12 col-sm-4 col-md-4 col-lg-4 durationDiv">
														<div class="input-group mb-0">
															<label class="mb-1">Duration <span class="text-danger">*</span></label>
														</div>
														<div class="input-group mb-3 mb-lg-3">
															<select class="form-control selectpicker" data-live-search="true" id="durationid" name="durationid" data-size="10" data-dropup-auto="false" >
																<option value="">Select Duration</option>
															</select>
														</div>
													</div>

													<div class="col-12">
														<div class="input-group">
															<label class="mb-1">Short Description</label>
															<label class="ml-auto "></label>
														</div>
														<div class="input-group mb-3">
															<textarea class="form-control" rows="3" placeholder="Short Description" id="shortdescr" name="shortdescr"></textarea>
														</div>
													</div>

													<div class="col-12">
														<div class="input-group">
															<label class="mb-1">Description </label>
															<label class="ml-auto "></label>
														</div>
														<div class="input-group mb-3">
															<textarea class="form-control" placeholder="Description" id="descr" name="descr" require></textarea>
														</div>
													</div>

													<div class="col-12 col-sm-4 col-md-4 col-lg-4">
														<div class="input-group mb-0">
															<label class="mb-1">Icon</label>
														</div>
														<div class="input-group mb-3 mb-lg-3">
															<select class="form-control selectpicker" data-live-search="true" id="iconid" name="iconid" data-size="10" data-dropup-auto="false" >
																<option value="">Select Icon</option>
															</select>
														</div>
													</div>

													<div class="col-12 col-sm-4 col-md-4 col-lg-4 compositeitemDiv">
														<div class="input-group mb-3 mb-lg-3" style="margin-top:27px">
															<div class="custom-control custom-checkbox m-0">
																<input type="checkbox" class="custom-control-input d-none" id="iscompositeitem" name="iscompositeitem" value="1" >
																<label class="custom-control-label mb-0" for="iscompositeitem">Composite Item</label>
															</div>
														</div>
													</div>  

												</div>
											</div>
										</div>
										<!----------------------- End For Basic Details Items ------------------------->


										<!----------------------- Start For Website Displaying Composite Items ------------------------->
										<div class="col-lg-12 col-md-12 col-12 mt-3 wrowmaterialdiv  d-none">
											<div class="widget">
												<div class="widget-title mb-3"><b>Website Display Items</b></div>
												<div class="widget-content row">
													
													<div class="col-12 ">
														<div class="row">
															<div class="col-12 col-sm-3 col-md-3 col-lg-3">
																<div class="input-group mb-0">
																	<label class="mb-1">Attribute <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3 mb-lg-3">
																	<select class="form-control selectpicker" data-live-search="true" id="wrowattributeid" name="wrowattributeid" data-size="6" data-dropup-auto="false" >
																		<option value="">Select Attribute</option>
																	</select>
																</div>
															</div>
															<div class="col-12 col-sm-4 col-md-4 col-lg-4">
																<div class="input-group">
																	<label class="mb-1">Website Display Name <span class="text-danger">*</span></label>
																	<label class="ml-auto "></label>
																</div>
																<div class="input-group mb-3">
																	<input type="text" class="form-control" placeholder="Website Display Name" id="wrowwebdisplayname" name="wrowwebdisplayname"  require>
																</div>
															</div>
															<div class="col-12 col-sm-2 col-md-2 col-lg-2">
																<div class="input-group mb-0">
																	<label class="mb-1">Icon <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3 mb-lg-3">
																	<select class="form-control selectpicker" data-live-search="true" id="wrowiconid" name="wrowiconid" data-size="6" data-dropup-auto="false" >
																		<option value="">Select Icon</option>
																	</select>
																</div>
															</div>
															<div class="col-12 col-sm-2 col-md-2 col-lg-2">
																<div class="input-group">
																	<label class="mb-1">Display Order <span class="text-danger">*</span></label>
																	<label class="ml-auto "></label>
																</div>
																<div class="input-group mb-3">
																	<input type="text" class="form-control" placeholder="Display Order" id="wrowdisplayorder" name="wrowdisplayorder" onkeypress="numbonly(event)"  require>
																</div>
															</div>
															<div class="col-12 col-sm-1 col-md-1 col-lg-1">
																<div class="col-auto" style="margin-top:21px">
																	<button type="button" class="btn btn-primary" id="btnAddwproduct" data-toggle="tooltip" data-placement="top" title="Add" data-original-title="Add"><i class="bi bi-plus-lg"></i></button>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-12">
																<div class="table-responsive table-bordered rounded">
																	<table class="table table-bordered sctable table-striped table-hover table-fixed tblwcompositeitem" id="tblwcompositeitem" style="font-size: 1.1em;text-align: center;">
																		<thead style="border-bottom: 1px solid rgb(221, 221, 221);">
																			<tr class="info">
																			<th class="text-left" width="20%">Attribute</th> 
																				<th class="text-left" width="50%">Website Display Name</th> 
																				<th class="text-center" width="10%">Icon</th> 
																				<th class="text-center" width="10%">Display Order</th> 
																				<th class="text-center" width="5%">Remove</th>                                              
																			</tr>										                
																		</thead>
																		<tbody id="tblwdata"></tbody>
																	</table>
																</div>
															</div>
														</div>   
													</div> 
													
												</div>
											</div>
										</div>
										<!----------------------- End For Website Displaying Composite Items ------------------------->

										<!----------------------- Start For Benifit Displaying Composite Items ------------------------->
										<div class="col-lg-12 col-md-12 col-12 mt-3 browmaterialdiv  d-none">
											<div class="widget">
												<div class="widget-title mb-3"><b>Courses Benefits</b></div>
												<div class="widget-content row">
													
													<div class="col-12 ">
														<div class="row">
															<div class="col-12 col-sm-6 col-md-6 col-lg-5">
																<div class="input-group">
																	<label class="mb-1">Benefit Name <span class="text-danger">*</span></label>
																	<label class="ml-auto "></label>
																</div>
																<div class="input-group mb-3">
																	<input type="text" class="form-control" placeholder="Benefit Name" id="browwebdisplayname" name="browwebdisplayname"  require>
																</div>
															</div>
															<div class="col-12 col-sm-2 col-md-2 col-lg-2">
																<div class="input-group">
																	<label class="mb-1">Duration <span class="text-danger">*</span></label>
																	<label class="ml-auto "></label>
																</div>
																<div class="input-group mb-3">
																	<input type="text" class="form-control" placeholder="Duration (Hours)" id="browduration" name="browduration" require>
																</div>
															</div>
															<div class="col-12 col-sm-3 col-md-3 col-lg-2">
																<div class="input-group mb-0">
																	<label class="mb-1">Icon <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3 mb-lg-3">
																	<select class="form-control selectpicker" data-live-search="true" id="browiconid" name="browiconid" data-size="6" data-dropup-auto="false" >
																		<option value="">Select Icon</option>
																	</select>
																</div>
															</div>
															<div class="col-12 col-sm-2 col-md-2 col-lg-2">
																<div class="input-group">
																	<label class="mb-1">Display Order <span class="text-danger">*</span></label>
																	<label class="ml-auto "></label>
																</div>
																<div class="input-group mb-3">
																	<input type="text" class="form-control" placeholder="Display Order" id="browdisplayorder" name="browdisplayorder" onkeypress="numbonly(event)"  require>
																</div>
															</div>
															
															<div class="col-12 col-sm-1 col-md-1 col-lg-1">
																<div class="col-auto" style="margin-top:21px">
																	<button type="button" class="btn btn-primary" id="btnAddbproduct" data-toggle="tooltip" data-placement="top" title="Add" data-original-title="Add"><i class="bi bi-plus-lg"></i></button>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-12">
																<div class="table-responsive table-bordered rounded">
																	<table class="table table-bordered sctable table-striped table-hover table-fixed tblbcompositeitem" id="tblbcompositeitem" style="font-size: 1.1em;text-align: center;">
																		<thead style="border-bottom: 1px solid rgb(221, 221, 221);">
																			<tr class="info">
																				<th class="text-left" width="60%">Benefit Name</th> 
																				<th class="text-center" width="10%">Duration</th> 
																				<th class="text-center" width="10%">Icon</th> 
																				<th class="text-center" width="10%">Display Order</th> 
																				
																				<th class="text-center" width="5%">Remove</th>                                              
																			</tr>										                
																		</thead>
																		<tbody id="tblbdata"></tbody>
																	</table>
																</div>
															</div>
														</div>   
													</div> 
													
												</div>
											</div>
										</div>
										<!----------------------- End For Benifit Displaying Composite Items ------------------------->

										<!----------------------- Start For Composite Items ------------------------->
										<div class="col-lg-12 col-md-12 col-12 mt-3 rowmaterialdiv  d-none">
											<div class="widget">
												<div class="widget-title  mb-3"><b>Composite Items</b></div>
												<div class="widget-content row">
													
													<div class="col-12 ">
														<div class="row">
															<div class="col-12 col-sm-4 col-md-4 col-lg-3">
																<div class="input-group">
																	<label class="mb-1">Category <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3">
																	<select class="form-control selectpicker" data-live-search="true" id="rowcatid" name="rowcatid" data-size="10" data-dropup-auto="false">
																	</select>
																</div>
															</div>
															<div class="col-12 col-sm-4 col-md-4 col-lg-3">
																<div class="input-group">
																	<label class="mb-1">Sub Category <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3">
																	<select class="form-control selectpicker" data-live-search="true" id="rowsubcatid" name="rowsubcatid" data-size="10" data-dropup-auto="false">
																	</select>
																</div>
															</div>
															<div class="col-12 col-sm-4 col-md-4 col-lg-3">
																<div class="input-group">
																	<label class="mb-1">Item <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3">
																	<select class="form-control selectpicker" data-live-search="true" id="rowitemid" name="rowitemid" data-size="10" data-dropup-auto="false">
																	</select>
																</div>
															</div>
															<div class="col-12 col-sm-3 col-md-3 col-lg-3 d-none">
																<div class="input-group">
																	<label class="mb-1">Website Display Name <span class="text-danger">*</span></label>
																	<label class="ml-auto "></label>
																</div>
																<div class="input-group mb-3">
																	<input type="text" class="form-control" placeholder="Website Display Name" id="rowwebdisplayname" name="rowwebdisplayname"  require>
																</div>
															</div>
															<div class="col-12 col-sm-4 col-md-4 col-lg-3">
																<div class="input-group mb-0">
																	<label class="mb-1">Type <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3 mb-lg-3">
																	<select class="form-control selectpicker" data-live-search="true" id="rowtypeid" name="rowtypeid" data-size="10" data-dropup-auto="false" >
																		<option value="">Select Type</option>
																	</select>
																</div>
															</div>
															<div class="col-12 col-sm-1 col-md-1 col-lg-1">
																<div class="input-group">
																	<label class="mb-1">QTY <span class="text-danger">*</span></label>
																	<label class="ml-auto "></label>
																</div>
																<div class="input-group mb-3">
																	<input type="text" class="form-control" placeholder="Qty" id="rowqty" name="rowqty"  onkeypress="numbonly(event);" require>
																</div>
															</div>
															<div class="col-12 col-sm-2 col-md-2 col-lg-2 d-none showhideDiv">
																<div class="input-group">
																	<label class="mb-1">Discount <span class="text-danger">*</span></label>
																	<label class="ml-auto "></label>
																</div>
																<div class="input-group mb-3">
																	<input type="text" class="form-control" placeholder="Discount" id="rowdiscount" name="rowdiscount"  onkeypress="numbonly(event);" require>
																</div>
															</div>
															<div class="col-12 col-sm-2 col-md-2 col-lg-2 d-none11 showhideDiv11">
																<div class="input-group mb-0">
																	<label class="mb-1">Duration <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3 mb-lg-3">
																	<select class="form-control selectpicker" data-live-search="true" id="comdurationid" name="comdurationid" data-size="10" data-dropup-auto="false" >
																		<option value="">Select Duration</option>
																	</select>
																</div>
															</div>
															<div class="col-12 col-sm-2 col-md-2 col-lg-2">
																<div class="input-group">
																	<label class="mb-1">Price <span class="text-danger">*</span></label>
																	<label class="ml-auto "></label>
																</div>
																<div class="input-group mb-3">
																	<input type="text" class="form-control" placeholder="Price" id="rowprice" name="rowprice"  onkeypress="numbonly(event);" require>
																</div>
															</div>
															<div class="col-12 col-sm-2 col-md-2 col-lg-2">
																<div class="input-group mb-0">
																	<label class="mb-1">Tax Type <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3 mb-lg-3">
																	<select class="form-control selectpicker" data-live-search="true" id="rowgsttypeid" name="rowgsttypeid" data-size="10" data-dropup-auto="false" >
																		<option value="">Select Tax Type</option>
																	</select>
																</div>
															</div>

															<div class="col-12 col-sm-2 col-md-2 col-lg-2">
																<div class="input-group mb-0">
																	<label class="mb-1">Tax <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3 mb-lg-3">
																	<select class="form-control selectpicker" data-live-search="true" id="rowgstid" name="rowgstid" data-size="10" data-dropup-auto="false" >
																		<option value="">Select Tax</option>
																	</select>
																</div>
															</div>
															<div class="col-12 col-sm-3 col-md-3 col-lg-3 d-none">
																<div class="input-group mb-0">
																	<label class="mb-1">Icon <span class="text-danger">*</span></label>
																</div>
																<div class="input-group mb-3 mb-lg-3">
																	<select class="form-control selectpicker" data-live-search="true" id="rowiconid" name="rowiconid" data-size="6" data-dropup-auto="false" >
																		<option value="">Select Icon</option>
																	</select>
																</div>
															</div>
															<div class="col-12 col-sm-1 col-md-1 col-lg-1">
																<div class="col-auto" style="margin-top:21px">
																	<button type="button" class="btn btn-primary" id="btnAddproduct" data-toggle="tooltip" data-placement="top" title="Add" data-original-title="Add"><i class="bi bi-plus-lg"></i></button>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-12">
																<div class="table-responsive table-bordered rounded">
																	<table class="table table-bordered sctable table-striped table-hover table-fixed tblcompositeitem" id="tblcompositeitem" style="font-size: 1.1em;text-align: center;">
																		<thead style="border-bottom: 1px solid rgb(221, 221, 221);">
																			<tr class="info">
																				<th class="text-left" width="15%">Category</th>
																				<th class="text-left" width="15%">Sub Category</th>
																				<th class="text-left" width="15%">Item</th>
																				<!-- <th class="text-left" width="20%">Website Display Name</th>  -->
																				<!-- <th class="text-left" width="1%"></th>  -->
																				<th class="text-center" width="5%">Type</th> 
																				<th class="text-center" width="10%">Qty</th> 
																				<th class="text-center" width="10%">Discount</th>
																				<th class="text-center" width="10%">Duration</th>  
																				<th class="text-center" width="10%">Price</th> 
																				<th class="text-center" width="10%">Tax Type</th> 
																				<th class="text-center" width="5%">Tax</th> 
																				<th class="text-center" width="5%">Remove</th>                                              
																			</tr>										                
																		</thead>
																		<tbody id="tbldata"></tbody>
																	</table>
																</div>
															</div>
														</div>   
													</div> 
													
												</div>
											</div>
										</div>
										<!----------------------- End For Composite Items ------------------------->


										<!----------------------- Start For Submit Button ------------------------->
										<div class="col-lg-12 col-md-12 col-12 mt-3">
											<div class="widget">
												<div class="widget-content">
													
													<div class="row col-12">
														<div class="ml-auto">
															<div class="input-group mb-0">
																<button type="submit" class="btn btn-primary m-0" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
																<button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
															</div>
														</div>
													</div>
													
												</div>
											</div>
										</div>
										<!----------------------- End For Submit Button ------------------------->


									</form>	
								</div>




						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- Upload Item Image View Modal -->
		<div class="modal animated fade" id="ItemImageModal" tabindex="-1" role="dialog" aria-labelledby="modalImgViewLabel" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<div class="row mb-4">
							<div class="col-12">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
								<h5 class="add-event-title modal-title">Upload Image</h5>
							</div>
						</div>
						<div class="row">
							<form class="w-100 validate-form" id="itemupdimageForm" method="post" action="itemmaster" enctype="multipart/form-data">
								<input type="hidden" name="itemid" id="itemid" />                
								<div class="col-12">
									<div class="input-group">
										<label class="mb-1">Item Image <span class="text-danger">*</span></label>
										<label class="ml-auto "></label>
									</div>
									<div class="input-group mb-3 mb-lg-3">
										<input type="file" class="form-control d-none" id="itemimg" name="itemimg[]" multiple="multiple" accept="image/*">
										<label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="itemimg">
											<span style="background-image: url(assets/img/salon.jpg);"></span>
											<i class="bi bi-folder2-open d-block pt-1"></i> Browse...
										</label>
										<span style="color:#B00" class="imgsizelbl2">** Please upload image in JPG,JPEG,PNG Format</span>
										<!-- <span style="color:#B00">** Please upload image of size 456px X 307px</span> -->
									</div>
								</div>
								<div class="col-12">    
									<div class="ml-auto">
										<div class="input-group mb-0">
											<button type="submit" class="btn btn-primary m-0  ml-auto" id="btnupditemimage"><?php echo $config->getSaveSidebar(); ?></button>
										</div>
									</div>
								</div>
								
							</form>	
						</div>
						<hr style="margin-bottom: 10px;margin-top: 0;">
						<div class="row viewitemimageblock">
			
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
										<label class="mb-1">Store/Counter</label>
									</div>
									<div class="input-group mb-3 mb-sm-3">
										<select class="form-control selectpicker" data-live-search="true" data-size="10" data-dropup-auto="false" id="fltstoreid" name="fltstoreid">
										
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
			$('#itemmasterForm #descr').froalaEditor();

			//fill Store filter
			fillfltstore();

			//fill Category filter
			fillfltcategory();
			
			resetdata();
        });
		
		function resetdata()
        {
            $("#itemmasterForm").validate().resetForm();
            $('#itemmasterForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');

			$('#id').val('');
			$('.rowmaterialdiv').addClass('d-none');
			$('#iscompositeitem').prop('checked',false);
			$('#iscompositeitem').val(0);
			$('#tblcompositeitem #tbldata').html('');

			$('.durationDiv').addClass('d-none');
			$('.compositeitemDiv').addClass('d-none');
			$('.wrowmaterialdiv').addClass('d-none');
			$('.browmaterialdiv').addClass('d-none');
			$('#tblwcompositeitem #tblwdata').html('');
			$('#tblbcompositeitem #tblbdata').html('');
			Edittimeformnamechange(2);   

			fillcategory();
			fillsapitems();
			fillstore();
			fillgsttype();
			fillgst();
			fillattribute();
			fillicon();
			fillduration();
			fillrowduration();
			fillrowgsttype();
			fillrowgst();
			fillcompositeitemtype();

			$('.showhideDiv').addClass('d-none');
        }

		//Fill Category
		function fillcategory()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcategory");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcategory,OnErrorSelectpicker);
        }

        function Onsuccessfillcategory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#categoryid').html(resultdata.data);
            $('#categoryid').selectpicker('refresh');
        }


		//Change Event Of Category
		$('body').on('change','#categoryid',function(){
			var iswebattribute = $('#categoryid :selected').attr('data-iswebattribute');
			var iscourse = $('#categoryid :selected').attr('data-iscourse');
			var iscompositeitem = $('#categoryid :selected').attr('data-iscompositeitem');
			var chkcompositeitem = $('#iscompositeitem').is(':checked');
			if(iscompositeitem == 1)
			{
				$('.compositeitemDiv').removeClass('d-none');
				if(chkcompositeitem)
				{
					$('.rowmaterialdiv').removeClass('d-none');
				}
				$('#tblcompositeitem #tbldata').empty();
				fillrowgsttype();
				fillrowgst();
			}
			else
			{
				$('.compositeitemDiv').addClass('d-none');
				$('.rowmaterialdiv').addClass('d-none');
				$('#tblcompositeitem #tbldata').empty();
				$('#iscompositeitem').prop('checked',false);
				$('#iscompositeitem').val(0);
			}

			if(iswebattribute == 1)
			{
				$('.wrowmaterialdiv').removeClass('d-none');
				$('#tblwcompositeitem #tblwdata').empty();
				$('.durationDiv').removeClass('d-none');

			}
			else
			{
				$('.wrowmaterialdiv').addClass('d-none');
				$('#tblwcompositeitem #tblwdata').empty();
				$('.durationDiv').addClass('d-none');
			}

			if(iscourse == 1)
			{
				$('.browmaterialdiv').removeClass('d-none');
				$('#tblwcompositeitem #tblbdata').empty();
			}
			else
			{
				$('.browmaterialdiv').addClass('d-none');
				$('#tblwcompositeitem #tblbdata').empty();
			}


			fillsubcategory();
		});


		//Fill Sub Category
		function fillsubcategory()
        {
			var categoryid = $('#categoryid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillsubcategory");
			formdata.append("categoryid", categoryid);
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillsubcategory,OnErrorSelectpicker);
        }

        function Onsuccessfillsubcategory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#subcategoryid').html(resultdata.data);
            $('#subcategoryid').selectpicker('refresh');
        }


		//Fill SAP Items
		function fillsapitems()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillsapitems");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'saphanamaster',formdata,headersdata,Onsuccessfillsapitems,OnErrorSelectpicker);
        }

        function Onsuccessfillsapitems(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
			$('#sapitemid').html(resultdata.data);
            $('#sapitemid').selectpicker('refresh');
        }


		//Fill Store
		function fillstore()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillempstore");
			formdata.append("selectoption", 0);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillstore,OnErrorSelectpicker); 
        }

        function Onsuccessfillstore(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#storeid').html(resultdata.data);
            $('#storeid').selectpicker('refresh');	
        }


		//Fill Tax Type
		function fillgsttype()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "filltaxtype");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillgsttype,OnErrorSelectpicker);
        }

        function Onsuccessfillgsttype(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#gsttypeid').html(resultdata.data);
            $('#gsttypeid').selectpicker('refresh');
        }


		//Fill Tax
		function fillgst()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillgst");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillgst,OnErrorSelectpicker);
        }

        function Onsuccessfillgst(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#gstid').html(resultdata.data);
            $('#gstid').selectpicker('refresh');
        }


		//Fill Attribute
		function fillattribute()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillattribute");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillattribute,OnErrorSelectpicker);
        }

        function Onsuccessfillattribute(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            
			$('#wrowattributeid').html(resultdata.data);
            $('#wrowattributeid').selectpicker('refresh');
        }

		//Fill Icon
		function fillicon()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillicon");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillicon,OnErrorSelectpicker);
        }

        function Onsuccessfillicon(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#iconid').html(resultdata.data);
            $('#iconid').selectpicker('refresh');

			$('#rowiconid').html(resultdata.data);
            $('#rowiconid').selectpicker('refresh');

			$('#wrowiconid').html(resultdata.data);
            $('#wrowiconid').selectpicker('refresh');

			$('#browiconid').html(resultdata.data);
            $('#browiconid').selectpicker('refresh');
        }

		//Fill Composite Item Type
		function fillcompositeitemtype()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcompositeitemtype");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcompositeitemtype,OnErrorSelectpicker);
        }

        function Onsuccessfillcompositeitemtype(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#rowtypeid').html(resultdata.data);
            $('#rowtypeid').selectpicker('refresh');
        }

		function fillrowduration()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillduration");
			formdata.append("selectoption", 1);
			formdata.append("isonce", '%');
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillrowduration,OnErrorSelectpicker);
        }

        function Onsuccessfillrowduration(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#comdurationid').html(resultdata.data);
            $('#comdurationid').selectpicker('refresh');
        }

		function fillduration()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillduration");
			formdata.append("selectoption", 1);
			formdata.append("istext", 1);
			formdata.append("isonce", 0);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillduration,OnErrorSelectpicker);
        }

        function Onsuccessfillduration(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#durationid').html(resultdata.data);
            $('#durationid').selectpicker('refresh');
        }

		/********************* Start For Add Web Composite Items ***************************/

		function fillrowgsttype()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "filltaxtype");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillrowgsttype,OnErrorSelectpicker);
        }

        function Onsuccessfillrowgsttype(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#rowgsttypeid').html(resultdata.data);
            $('#rowgsttypeid').selectpicker('refresh');
        }


		//Fill Tax
		function fillrowgst()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillgst");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillrowgst,OnErrorSelectpicker);
        }

        function Onsuccessfillrowgst(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#rowgstid').html(resultdata.data);
            $('#rowgstid').selectpicker('refresh');
        }

		//Click Event OF Add Web Composit Item
		$('#btnAddwproduct').click(function (e) { 
            
			var rowattributeid=$('#wrowattributeid').val();
			var rowattributename=$("#wrowattributeid :selected").text();

			var rowwebdisplayname=$('#wrowwebdisplayname').val();

			var rowiconid=$('#wrowiconid').val();
			var rowiconname=$("#wrowiconid :selected").attr('data-iconname');
			var rowicon=$("#wrowiconid :selected").attr('data-iconimg');

			var rowdisplayorder=$('#wrowdisplayorder').val();

             if(rowattributeid && rowwebdisplayname && rowiconid)
             {
                $('#tblwcompositeitem tbody tr').each(function(){
                    var tmp = $(this).attr('data-index');
                    if($('#tblwrowattributeid'+tmp).val() == rowattributeid)
                    {
                        $(this).remove();
                    }
                });

				var trtmp = $('#tblwcompositeitem tbody tr:last').attr('data-index') | 0;
            	trtmp = parseInt(trtmp+1);

                var tbldata="";	
                tbldata+='<tr data-index="'+trtmp+'">';
				tbldata+='<td align="left">'+rowattributename+'<input type="hidden" name="tblwrowattributeid[]" id="tblwrowattributeid'+trtmp+'" class="form-control tblwrowattributeid" value="'+rowattributeid+'" /><input type="hidden" name="tblwrowattributename[]" id="tblwrowattributename'+trtmp+'" class="form-control tblwrowattributename" value="'+rowattributename+'" /></td>';
                tbldata+='<td align="left"><input type="text" name="tblwrowwebdisplayname[]" id="tblwrowwebdisplayname'+trtmp+'" class="form-control tblwrowwebdisplayname" value="'+rowwebdisplayname+'" /></td>';
				tbldata+='<td align="center"><img src="'+rowicon+'" alt="'+rowiconname+'" width="25" /><input type="hidden" name="tblwrowiconid[]" id="tblwrowiconid'+trtmp+'" value="'+rowiconid+'" /></td>';
                tbldata+='<td align="left"><input type="text" name="tblwrowdisplayorder[]" id="tblwrowdisplayorder'+trtmp+'" class="form-control tblwrowdisplayorder" onkeypress="numbonly(event)" value="'+rowdisplayorder+'" /></td>';
				tbldata+='<td  align="center" class="tbl-action">';
                tbldata+='<a href="javascript:void(0);" class="btn-tbl text-danger m-0 rounded-circle removetblitem" id="removetblwitem" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="bi bi-x-lg"></i></a>';
                tbldata+='</td>';
				tbldata+='</tr>';

                $('#tblwcompositeitem #tblwdata').append(tbldata);
                

                $('#wrowwebdisplayname').val('');
				$('#wrowdisplayorder').val('');
             }
			 else
			 {
                alertify('Please fill in all required fields',0);
             }
             
        }); 


		//Remove Web Composite Item in Table
		$('body').on('click','.removetblwitem', function () {            
            $(this).parent().parent().remove();
        });
		/********************* End For Add Web Composite Items ***************************/



		/********************* Start For Add Composite Items ***************************/
		//Change Event OF Composite Item
		$('#iscompositeitem').on('change', function(){
			if (!this.checked) 
			{
				$('.rowmaterialdiv').addClass('d-none');
				$('#tblcompositeitem #tbldata').empty();
				$('#iscompositeitem').val(0);
			}
			else
			{
				$('.rowmaterialdiv').removeClass('d-none');
				$('#tblcompositeitem #tbldata').empty();
				$('#iscompositeitem').val(1);

				//Fill Composite Items
				fillcompositecategory();
			}   
		});	


		//Fill Composite Category
		function fillcompositecategory()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcompositecategory");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcompositecategory,OnErrorSelectpicker);
        }

        function Onsuccessfillcompositecategory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#rowcatid').html(resultdata.data);
            $('#rowcatid').selectpicker('refresh');
        }


		//Change Event Of Composite Category
		$('body').on('change','#rowcatid',function(){
			$('#rowitemid').html('');
            $('#rowitemid').selectpicker('refresh');	

			fillcompositesubcategory();
		});


		//Fill Composite Sub Category
		function fillcompositesubcategory()
        {
			var categoryid = $('#rowcatid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcompositesubcategory");
			formdata.append("categoryid", categoryid);
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcompositesubcategory,OnErrorSelectpicker);
        }

        function Onsuccessfillcompositesubcategory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#rowsubcatid').html(resultdata.data);
            $('#rowsubcatid').selectpicker('refresh');
        }

		//Change Event Of Composite Sub Category
		$('body').on('change','#rowsubcatid',function(){
			fillcompositeitem();
		});


		//Fill Composite Items
		function fillcompositeitem()
        {
			var categoryid = $('#rowcatid').val();
			var subcategoryid = $('#rowsubcatid').val();
			var itemid = $('#id').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillitems");
			formdata.append("iscompositeitem", 0);
			formdata.append("alloption", 1);
			formdata.append("itemid", itemid);
			formdata.append("categoryid", categoryid);
			formdata.append("subcategoryid", subcategoryid);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcompositeitem,OnErrorSelectpicker); 
        }

        function Onsuccessfillcompositeitem(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#rowitemid').html(resultdata.data);
            $('#rowitemid').selectpicker('refresh');	

			var rowprice = $('#rowitemid :selected').attr('data-price');
			var rowgsttypeid = $('#rowitemid :selected').attr('data-gsttypeid');
			var rowgstid = $('#rowitemid :selected').attr('data-gstid');

			$('#rowprice').val(rowprice);
			$('#rowgsttypeid').val(rowgsttypeid);
			$('#rowgsttypeid').selectpicker('refresh');	
			$('#rowgstid').val(rowgstid);
			$('#rowgstid').selectpicker('refresh');	
        }

		$('body').on('change', '#rowitemid', function(){
			var rowprice = $('#rowitemid :selected').attr('data-price');
			var rowgsttypeid = $('#rowitemid :selected').attr('data-gsttypeid');
			var rowgstid = $('#rowitemid :selected').attr('data-gstid');
			// console.log(rowprice, rowgsttypeid, rowgstid)
			$('#rowprice').val(rowprice);
			$('#rowgsttypeid').val(rowgsttypeid);
			$('#rowgsttypeid').selectpicker('refresh');	
			$('#rowgstid').val(rowgstid);
			$('#rowgstid').selectpicker('refresh');	
		})

		$('#rowdiscount').on('input',function(){ 
			var discount = $(this).val();
			if(discount > 100)
			{
				$('#rowdiscount').val(100);
			}
		});

		$('body').on('input','.tblrowdiscount, .tblrowprice, .tblrowqty',function(){
            var trtmp = $(this).parent().parent().attr('data-index');
			var qty = $('#tblrowqty'+trtmp).val();
            var discount = $('#tblrowdiscount'+trtmp).val();
			var price = $('#tblrowprice'+trtmp).val();
			if(discount == '' || isNaN(discount))
			{
				$('#tblrowdiscount'+trtmp).val('0');
			}
			else if(discount > 100)
			{
				$('#tblrowdiscount'+trtmp).val('100');
			}

			if(price == '' || isNaN(price))
			{
				$('#tblrowprice'+trtmp).val('0');
			}

			if(qty == '' || isNaN(qty))
			{
				$('#tblrowqty'+trtmp).val('1');
			}

        });

		//Click Event OF Add Composit Item
		$('#btnAddproduct').click(function (e) { 
            
			var rowcatid=$('#rowcatid').val();
			var rowcategory=$("#rowcatid option:selected").text();

			var rowsubcatid=$('#rowsubcatid').val();
			var rowsubcategory=$("#rowsubcatid option:selected").text();

			var rowitemid=$('#rowitemid').val();
			var rowitem=$("#rowitemid option:selected").text();

			var rowwebdisplayname=$('#rowwebdisplayname').val();
			var rowqty=$('#rowqty').val();

			var rowiconid=$('#rowiconid').val();
			var rowiconname=$("#rowiconid :selected").attr('data-iconname');
			var rowicon=$("#rowiconid :selected").attr('data-iconimg');

			var durationid = $('#comdurationid').val();
			var durationname = $('#comdurationid :selected').attr('data-name');
			var durationday = $('#comdurationid :selected').attr('data-day');

			var rowprice=$('#rowprice').val();
			var rowdiscount = $('#rowdiscount').val();
			if(rowdiscount == '' || isNaN(rowdiscount))
			{
				rowdiscount = 0;
			}
			var rowgsttypeid = $("#rowgsttypeid :selected").val();
			var rowgsttype = $("#rowgsttypeid :selected").text();

			var rowgstid = $("#rowgstid :selected").val();
			var rowgst = $("#rowgstid :selected").text();

			var rowtypeid = $("#rowtypeid :selected").val();
			var rowtypestr = $("#rowtypeid :selected").text();
			var rowtype = $("#rowtypeid :selected").attr('data-type');

			// console.log(durationid, durationname, durationday)
			if(durationid == '')
			{
				durationid = '';
				durationname = '';
				durationday = '';
			}

			var isvalid = 0
			if(rowtype == 2)
			{
				if(durationid)
				{
					isvalid = 1
				}
			}
			else
			{
				isvalid = 1
			}

			if(rowitemid && rowqty && rowprice && rowgsttypeid && rowgstid && isvalid == 1)
			{
				$('#tblcompositeitem tbody tr').each(function(){
					var tmp = $(this).attr('data-index');
					if($('#tblrowsubcatid'+tmp).val() == rowsubcatid && $('#tblrowitemid'+tmp).val() == rowitemid)
					{
						$(this).remove();
					}
				});

				var trtmp = $('#tblcompositeitem tbody tr:last').attr('data-index') | 0;
				trtmp = parseInt(trtmp+1);

				var tbldata="";	
				tbldata+='<tr data-index="'+trtmp+'">';
				tbldata+='<td align="left">'+rowcategory+'<input type="hidden" name="tblrowcatid[]" id="tblrowcatid'+trtmp+'" value="'+rowcatid+'" /><input type="hidden" name="tblrowcategory[]" id="tblrowcategory'+trtmp+'" value="'+rowcategory+'" /></td>';
				tbldata+='<td align="left">'+rowsubcategory+'<input type="hidden" name="tblrowsubcatid[]" id="tblrowsubcatid'+trtmp+'" value="'+rowsubcatid+'" /><input type="hidden" name="tblrowsubcategory[]" id="tblrowsubcategory'+trtmp+'" value="'+rowsubcategory+'" /></td>';
				tbldata+='<td align="left">'+rowitem+'<input type="hidden" name="tblrowitemid[]" id="tblrowitemid'+trtmp+'" value="'+rowitemid+'" /><input type="hidden" name="tblrowitem[]" id="tblrowitem'+trtmp+'" value="'+rowitem+'" /></td>';
				// tbldata+='<td align="left"><input type="hidden" name="tblrowwebdisplayname[]" id="tblrowwebdisplayname'+trtmp+'" class="form-control tblrowwebdisplayname" value="'+rowwebdisplayname+'" /></td>';
				// tbldata+='<td align="center"><img src="'+rowicon+'" alt="'+rowiconname+'" width="25" /><input type="hidden" name="tblrowiconid[]" id="tblrowiconid'+trtmp+'" value="'+rowiconid+'" /></td>';
				
				tbldata+='<td align="left">'+rowtypestr+'<input type="hidden" name="tblrowtypeid[]" id="tblrowtypeid'+trtmp+'" value="'+rowtypeid+'" /><input type="hidden" name="tblrowtypestr[]" id="tblrowtypestr'+trtmp+'" value="'+rowtypestr+'" /><input type="hidden" name="tblrowtype[]" id="tblrowtype'+trtmp+'" value="'+rowtype+'" /></td>';
				
				tbldata+='<td align="center"><input type="text" name="tblrowqty[]" id="tblrowqty'+trtmp+'" onkeypress="numbonly(event)" class="form-control tblrowqty" onkeypress="numbonly(event)" value="'+rowqty+'" /></td>';
				
				if(rowtype == 2)
				{
					tbldata+='<td align="center"><input type="text" name="tblrowdiscount[]" id="tblrowdiscount'+trtmp+'" onkeypress="numbonly(event)" class="form-control tblrowdiscount" onkeypress="numbonly(event)" value="'+rowdiscount+'" /></td>';
				}
				else
				{
					tbldata+='<td align="center"><input type="hidden" name="tblrowdiscount[]" id="tblrowdiscount'+trtmp+'" onkeypress="numbonly(event)" class="form-control tblrowdiscount" onkeypress="numbonly(event)" value="0" /></td>';
				}
				
				tbldata+='<td align="center">'+durationname+'<input type="hidden" name="tbldurationid[]" id="tbldurationid'+trtmp+'" class="form-control tbldurationid" value="'+durationid+'" /><input type="hidden" name="tbldurationname[]" id="tbldurationname'+trtmp+'" class="form-control tbldurationname" value="'+durationname+'" /><input type="hidden" name="tbldurationday[]" id="tbldurationday'+trtmp+'" class="form-control tbldurationday" value="'+durationday+'" /></td>';
				
				tbldata+='<td align="center"><input type="text" name="tblrowprice[]" id="tblrowprice'+trtmp+'" onkeypress="numbonly(event)" class="form-control tblrowprice" onkeypress="numbonly(event)" value="'+rowprice+'" /></td>';
				tbldata+='<td align="center">'+rowgsttype+'<input type="hidden" name="tblrowgsttypeid[]" id="tblrowgsttypeid'+trtmp+'" class="form-control tblrowgsttypeid" onkeypress="numbonly(event)" value="'+rowgsttypeid+'" /><input type="hidden" name="tblrowgsttype[]" id="tblrowgsttype'+trtmp+'" class="form-control tblrowgsttype" onkeypress="numbonly(event)" value="'+rowgsttype+'" /></td>';
				tbldata+='<td align="center">'+rowgst+'<input type="hidden" name="tblrowgstid[]" id="tblrowgstid'+trtmp+'" class="form-control tblrowgstid" onkeypress="numbonly(event)" value="'+rowgstid+'" /><input type="hidden" name="tblrowgst[]" id="tblrowgst'+trtmp+'" class="form-control tblrowgst" onkeypress="numbonly(event)" value="'+rowgst+'" /></td>';
				
				
				tbldata+='<td  align="center" class="tbl-action">';
				tbldata+='<a href="javascript:void(0);" class="btn-tbl text-danger m-0 rounded-circle removetblitem" id="removetblitem" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="bi bi-x-lg"></i></a>';
				tbldata+='</td>';
				tbldata+='</tr>';

				$('#tblcompositeitem #tbldata').append(tbldata);
				

				$('#rowwebdisplayname').val('');
				$('#rowqty').val('');
				$('#rowitemid').val('');
				$('#rowitemid').selectpicker('refresh');
			}
			else
			{
				alertify('Please fill in all required fields',0);
			}
             
        }); 


		//Remove Composite Item in Table
		$('body').on('click','.removetblitem', function () {            
            $(this).parent().parent().remove();
        });
		/********************* End For Add Composite Items ***************************/


		//Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};

            formdata = new FormData();
            formdata.append("action", "edititemmaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessSubcategoryedit,OnErrorSubcategoryedit); 
        }

        function OnsuccessSubcategoryedit(content)
        {
            var JsonData = JSON.stringify(content);
            var resulteditdata = jQuery.parseJSON(JsonData);

            $('#id').val(resulteditdata.id);
            $('#itemname').val(resulteditdata.itemname);
			$('#price').val(resulteditdata.price);  
			$('#shortdescr').val(resulteditdata.shortdescr);  
			$('#descr').froalaEditor('html.set',resulteditdata.descr);  
			$('#duration').val(resulteditdata.duration);  
			$('#noofstudent').val(resulteditdata.noofstudent);  

			//Fill Category
			var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcategory");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillcategory,OnErrorSelectpicker);
        
			function Onsuccessedtfillcategory(content)
			{
				var JsonData = JSON.stringify(content);
				var resultdata = jQuery.parseJSON(JsonData);
				$('#categoryid').html(resultdata.data);
				$('#categoryid').val(resulteditdata.categoryid);
				$('#categoryid').selectpicker('refresh');


				//Fill Sub Category
				var pagename=getpagename();
				var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
				formdata = new FormData();
				formdata.append("action", "fillsubcategory");
				formdata.append("categoryid", resulteditdata.categoryid);
				formdata.append("selectoption", 1);
				ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillsubcategory,OnErrorSelectpicker);

				function Onsuccessedtfillsubcategory(content)
				{
					var JsonData = JSON.stringify(content);
					var resultdata = jQuery.parseJSON(JsonData);
					$('#subcategoryid').html(resultdata.data);
					$('#subcategoryid').val(resulteditdata.subcategoryid);
					$('#subcategoryid').selectpicker('refresh');


					//Fill SAP Items
					var pagename=getpagename();
					var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
					formdata = new FormData();
					formdata.append("action", "fillsapitems");
					ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'saphanamaster',formdata,headersdata,Onsuccessedtfillsapitems,OnErrorSelectpicker);

					function Onsuccessedtfillsapitems(content)
					{
						var JsonData = JSON.stringify(content);
						var resultdata = jQuery.parseJSON(JsonData);
						$('#sapitemid').html(resultdata.data);
						$('#sapitemid').val(resulteditdata.sapitemid);
						$('#sapitemid').selectpicker('refresh');
					}

					

					//Fill Store
					var pagename=getpagename();
					var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
					formdata = new FormData();
					formdata.append("action", "fillempstore");
					formdata.append("selectoption", 0);
					ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillempstore,OnErrorSelectpicker); 

					function Onsuccessedtfillempstore(content)
					{
						var JsonData = JSON.stringify(content);
						var resultdata = jQuery.parseJSON(JsonData);
						$('#storeid').html(resultdata.data);
						if(resulteditdata.storeid !== null)
						{
							var si=resulteditdata.storeid.split(",");
							$('#storeid').selectpicker('val',si);
						}
						$('#storeid').selectpicker('refresh');	


						//Fill GST Type
						var pagename=getpagename();
						var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
						formdata = new FormData();
						formdata.append("action", "filltaxtype");
						formdata.append("selectoption", 1);
						ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillgsttype,OnErrorSelectpicker);

						function Onsuccessedtfillgsttype(content)
						{
							var JsonData = JSON.stringify(content);
							var resultdata = jQuery.parseJSON(JsonData);
							$('#gsttypeid').html(resultdata.data);
							$('#gsttypeid').val(resulteditdata.gsttypeid);
							$('#gsttypeid').selectpicker('refresh');


							//Fill GST
							var pagename=getpagename();
							var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
							formdata = new FormData();
							formdata.append("action", "fillgst");
							formdata.append("selectoption", 1);
							ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillgst,OnErrorSelectpicker);

							function Onsuccessedtfillgst(content)
							{
								var JsonData = JSON.stringify(content);
								var resultdata = jQuery.parseJSON(JsonData);
								$('#gstid').html(resultdata.data);
								$('#gstid').val(resulteditdata.gstid);
								$('#gstid').selectpicker('refresh');

								// fill duration
								var pagename=getpagename();
								var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
								formdata = new FormData();
								formdata.append("action", "fillduration");
								formdata.append("selectoption", 1);
								formdata.append("isonce", '%');
								ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillrowduration,OnErrorSelectpicker);
							
								function Onsuccessfillrowduration(content)
								{
									var JsonData = JSON.stringify(content);
									var resultdata = jQuery.parseJSON(JsonData);
									$('#comdurationid').html(resultdata.data);
									$('#comdurationid').selectpicker('refresh');
								}



								//Fill Icon
								var pagename=getpagename();
								var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
								formdata = new FormData();
								formdata.append("action", "fillicon");
								formdata.append("selectoption", 1);
								ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillicon,OnErrorSelectpicker);


								function Onsuccessedtfillicon(content)
								{
									var JsonData = JSON.stringify(content);
									var resultdata = jQuery.parseJSON(JsonData);
									$('#iconid').html(resultdata.data);
									$('#iconid').val(resulteditdata.iconid);
									$('#iconid').selectpicker('refresh');

									$('#rowiconid').html(resultdata.data);
									$('#rowiconid').selectpicker('refresh');

									$('#browiconid').html(resultdata.data);
									$('#browiconid').selectpicker('refresh');
									

									//Fill Composite Category
									var pagename=getpagename();
									var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
									formdata = new FormData();
									formdata.append("action", "fillcompositecategory");
									formdata.append("selectoption", 1);
									ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfilledtcompositecategory,OnErrorSelectpicker);
								
									function Onsuccessfilledtcompositecategory(content)
									{
										var JsonData = JSON.stringify(content);
										var resultdata = jQuery.parseJSON(JsonData);
										$('#rowcatid').html(resultdata.data);
										$('#rowcatid').selectpicker('refresh');



										var pagename=getpagename();
										var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
										formdata = new FormData();
										formdata.append("action", "fillattribute");
										formdata.append("selectoption", 1);
										ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfilledtattribute,OnErrorSelectpicker);

										function Onsuccessfilledtattribute(content)
										{
											var JsonData = JSON.stringify(content);
											var resultdata = jQuery.parseJSON(JsonData);
											
											$('#wrowattributeid').html(resultdata.data);
											$('#wrowattributeid').selectpicker('refresh');
										}
									}

								}

							}
						}
						
					}
				}
			}



			//When Web Composite Item
			if (resulteditdata.iswebattribute == 1) 
			{
				$('.wrowmaterialdiv').removeClass('d-none');
				$('.durationDiv').removeClass('d-none');
				$('#tblwcompositeitem #tblwdata').empty();

				var tbldata="";
                for(var i in resulteditdata.dataweb)
                {
                    var trtmp = parseInt(i)+1;

					tbldata+='<tr data-index="'+trtmp+'">';
					tbldata+='<td align="left">'+resulteditdata.dataweb[i].rowattributename+'<input type="hidden" name="tblwrowattributeid[]" id="tblwrowattributeid'+trtmp+'" class="form-control tblwrowattributeid" value="'+resulteditdata.dataweb[i].rowattributeid+'" /><input type="hidden" name="tblwrowattributename[]" id="tblwrowattributename'+trtmp+'" class="form-control tblwrowattributename" value="'+resulteditdata.dataweb[i].rowattributename+'" /></td>';
					tbldata+='<td align="left"><input type="text" name="tblwrowwebdisplayname[]" id="tblwrowwebdisplayname'+trtmp+'" class="form-control tblwrowwebdisplayname" value="'+resulteditdata.dataweb[i].rowwebdisplayname+'" /></td>';
					tbldata+='<td align="center"><img src="'+resulteditdata.dataweb[i].iconimg+'" alt="'+resulteditdata.dataweb[i].iconname+'" width="25" /><input type="hidden" name="tblwrowiconid[]" id="tblwrowiconid'+trtmp+'" value="'+resulteditdata.dataweb[i].rowiconid+'" /></td>';
					tbldata+='<td align="left"><input type="text" name="tblwrowdisplayorder[]" id="tblwrowdisplayorder'+trtmp+'" class="form-control tblwrowdisplayorder" onkeypress="numbonly(event)" value="'+resulteditdata.dataweb[i].rowdisplayorder+'" /></td>';
					tbldata+='<td  align="center" class="tbl-action">';
					tbldata+='<a href="javascript:void(0);" class="btn-tbl text-danger m-0 rounded-circle removetblwitem" id="removetblwitem" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="bi bi-x-lg"></i></a>';
					tbldata+='</td>';
					tbldata+='</tr>';
					
                    trtmp++;
                }

                $('#tblwcompositeitem #tblwdata').html(tbldata);
			}
			else
			{
				$('.wrowmaterialdiv').addClass('d-none');
				$('.durationDiv').addClass('d-none');
				$('#tblwcompositeitem #tblwdata').empty();
			}  

			//Courses Item
			if (resulteditdata.iscourse == 1) 
			{
				$('.browmaterialdiv').removeClass('d-none');
				$('#tblbcompositeitem #tblwdata').empty();

				var tbldata="";
                for(var i in resulteditdata.datacourse)
                {
                    var trtmp = parseInt(i)+1;

					tbldata+='<tr data-index="'+trtmp+'">';
					tbldata+='<td align="left"><input type="text" name="tblbrowwebdisplayname[]" id="tblbrowwebdisplayname'+trtmp+'" class="form-control tblbrowwebdisplayname" value="'+resulteditdata.datacourse[i].rowwebdisplayname+'" /></td>';
					tbldata+='<td align="left"><input type="text" name="tblbrowduration[]" id="tblbrowduration'+trtmp+'" class="form-control tblbrowduration" value="'+resulteditdata.datacourse[i].rowdurationname+'" /></td>';
					tbldata+='<td align="center"><img src="'+resulteditdata.datacourse[i].iconimg+'" alt="'+resulteditdata.datacourse[i].iconname+'" width="25" /><input type="hidden" name="tblbrowiconid[]" id="tblbrowiconid'+trtmp+'" value="'+resulteditdata.datacourse[i].rowiconid+'" /></td>';
					tbldata+='<td align="left"><input type="text" name="tblbrowdisplayorder[]" id="tblbrowdisplayorder'+trtmp+'" class="form-control tblbrowdisplayorder" onkeypress="numbonly(event)" value="'+resulteditdata.datacourse[i].rowdisplayorder+'" /></td>';
					tbldata+='<td  align="center" class="tbl-action">';
					tbldata+='<a href="javascript:void(0);" class="btn-tbl text-danger m-0 rounded-circle removetblwitem" id="removetblwitem" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="bi bi-x-lg"></i></a>';
					tbldata+='</td>';
					tbldata+='</tr>';
					
                    trtmp++;
                }

                $('#tblbcompositeitem #tblbdata').html(tbldata);
			}
			else
			{
				$('.browmaterialdiv').addClass('d-none');
				$('#tblbcompositeitem #tblbdata').empty();
			}  

			var pagename=getpagename();
			var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
			formdata = new FormData();
			formdata.append("action", "fillduration");
			formdata.append("selectoption", 1);
			formdata.append("istext", 1);
			formdata.append("isonce", 0);
			ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillduration,OnErrorSelectpicker);
		
			function Onsuccessfillduration(content)
			{
				var JsonData = JSON.stringify(content);
				var resultdata = jQuery.parseJSON(JsonData);
				$('#durationid').html(resultdata.data);
				console.log(resulteditdata.durationid)
				$('#durationid').val(resulteditdata.durationid);
				$('#durationid').selectpicker('refresh');
			}

			if(resulteditdata.c_iscompositeitem == 1)
			{
				$('.compositeitemDiv').removeClass('d-none');
				$('.rowmaterialdiv').removeClass('d-none');
			}
			else
			{
				$('.compositeitemDiv').addClass('d-none');
				$('.rowmaterialdiv').addClass('d-none');
			}

			//When Composite Item
			if (resulteditdata.iscompositeitem == 1) 
			{
				$('.rowmaterialdiv').removeClass('d-none');
				$('#tblcompositeitem #tbldata').empty();
				$('#iscompositeitem').val(1);
				$('#iscompositeitem').prop('checked',true);

				var tbldata="";
                for(var i in resulteditdata.data)
                {
                    var trtmp = parseInt(i)+1;

					tbldata+='<tr data-index="'+trtmp+'">';
					tbldata+='<td align="left">'+resulteditdata.data[i].rowcategory+'<input type="hidden" name="tblrowcatid[]" id="tblrowcatid'+trtmp+'" value="'+resulteditdata.data[i].rowcatid+'" /><input type="hidden" name="tblrowcategory[]" id="tblrowcategory'+trtmp+'" value="'+resulteditdata.data[i].rowcategory+'" /></td>';
					tbldata+='<td align="left">'+resulteditdata.data[i].rowsubcategory+'<input type="hidden" name="tblrowsubcatid[]" id="tblrowsubcatid'+trtmp+'" value="'+resulteditdata.data[i].rowsubcatid+'" /><input type="hidden" name="tblrowsubcategory[]" id="tblrowsubcategory'+trtmp+'" value="'+resulteditdata.data[i].rowsubcategory+'" /></td>';
					tbldata+='<td align="left">'+resulteditdata.data[i].rowitemname+'<input type="hidden" name="tblrowitemid[]" id="tblrowitemid'+trtmp+'" value="'+resulteditdata.data[i].rowitemid+'" /><input type="hidden" name="tblrowitem[]" id="tblrowitem'+trtmp+'" value="'+resulteditdata.data[i].rowitemname+'" /></td>';
					// tbldata+='<td align="left"><input type="hidden" name="tblrowwebdisplayname[]" id="tblrowwebdisplayname'+trtmp+'" class="form-control tblrowwebdisplayname" value="'+resulteditdata.data[i].rowwebdisplayname+'" /></td>';
					// tbldata+='<td align="center"><img src="'+resulteditdata.data[i].iconimg+'" alt="'+resulteditdata.data[i].iconname+'" width="25" /><input type="hidden" name="tblrowiconid[]" id="tblrowiconid'+trtmp+'" value="'+resulteditdata.data[i].rowiconid+'" /></td>';
					
					tbldata+='<td align="left">'+resulteditdata.data[i].rowtypestr+'<input type="hidden" name="tblrowtypeid[]" id="tblrowtypeid'+trtmp+'" value="'+resulteditdata.data[i].rowtypeid+'" /><input type="hidden" name="tblrowtypestr[]" id="tblrowtypestr'+trtmp+'" value="'+resulteditdata.data[i].rowtypestr+'" /><input type="hidden" name="tblrowtype[]" id="tblrowtype'+trtmp+'" value="'+resulteditdata.data[i].rowtype+'" /></td>';
					tbldata+='<td align="center"><input type="text" name="tblrowqty[]" id="tblrowqty'+trtmp+'" class="form-control tblrowqty" onkeypress="numbonly(event)" value="'+resulteditdata.data[i].rowqty+'" /></td>';
					if(resulteditdata.data[i].rowtype == 2)
					{
						tbldata+='<td align="center"><input type="text" name="tblrowdiscount[]" id="tblrowdiscount'+trtmp+'" onkeypress="numbonly(event)" class="form-control tblrowdiscount" onkeypress="numbonly(event)" value="'+resulteditdata.data[i].rowdiscount+'" /></td>';
					}
					else
					{
						tbldata+='<td align="center"><input type="hidden" name="tblrowdiscount[]" id="tblrowdiscount'+trtmp+'" onkeypress="numbonly(event)" class="form-control tblrowdiscount" onkeypress="numbonly(event)" value="0" /></td>';
					}
					
					tbldata+='<td align="center">'+resulteditdata.data[i].rowdurationname+'<input type="hidden" name="tbldurationid[]" id="tbldurationid'+trtmp+'" class="form-control tbldurationid" value="'+resulteditdata.data[i].rowdurationid+'" /><input type="hidden" name="tbldurationname[]" id="tbldurationname'+trtmp+'" class="form-control tbldurationname" value="'+resulteditdata.data[i].rowdurationname+'" /><input type="hidden" name="tbldurationday[]" id="tbldurationday'+trtmp+'" class="form-control tbldurationday" value="'+resulteditdata.data[i].rowdurationdays+'" /></td>';

					tbldata+='<td align="center"><input type="text" name="tblrowprice[]" id="tblrowprice'+trtmp+'" onkeypress="numbonly(event)" class="form-control tblrowprice" onkeypress="numbonly(event)" value="'+resulteditdata.data[i].rowprice+'" /></td>';
					tbldata+='<td align="center">'+resulteditdata.data[i].rowgsttype+'<input type="hidden" name="tblrowgsttypeid[]" id="tblrowgsttypeid'+trtmp+'" class="form-control tblrowgsttypeid" onkeypress="numbonly(event)" value="'+resulteditdata.data[i].rowgsttypeid+'" /><input type="hidden" name="tblrowgsttype[]" id="tblrowgsttype'+trtmp+'" class="form-control tblrowgsttype" onkeypress="numbonly(event)" value="'+resulteditdata.data[i].rowgsttype+'" /></td>';
					tbldata+='<td align="center">'+resulteditdata.data[i].rowgst+'<input type="hidden" name="tblrowgstid[]" id="tblrowgstid'+trtmp+'" class="form-control tblrowgstid" onkeypress="numbonly(event)" value="'+resulteditdata.data[i].rowgstid+'" /><input type="hidden" name="tblrowgst[]" id="tblrowgst'+trtmp+'" class="form-control tblrowgst" onkeypress="numbonly(event)" value="'+resulteditdata.data[i].rowgst+'" /></td>';
				

					tbldata+='<td  align="center" class="tbl-action">';
					tbldata+='<a href="javascript:void(0);" class="btn-tbl text-danger m-0 rounded-circle removetblitem" id="removetblitem" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="bi bi-x-lg"></i></a>';
					tbldata+='</td>';
					tbldata+='</tr>';
					
                    trtmp++;
                }

                $('#tblcompositeitem #tbldata').html(tbldata);
			}
			else
			{
				$('.rowmaterialdiv').addClass('d-none');
				$('#tblcompositeitem #tbldata').empty();
				$('#iscompositeitem').val(0);

				$('#iscompositeitem').prop('checked',false);
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


		//View Details Start
        function viewitemdetaildata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "viewitemdata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewitemdetaildata,OnErrorviewitemdetaildata); 
        }

		function Onsuccessviewitemdetaildata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-xl');
			$('#GeneralModal #GeneralModalLabel').html('Item Details');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorviewitemdetaildata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }

		
		//Change Item Status 
        function changeitemstatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changeitemstatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangeitemstatus,OnErrorchangeitemstatus); 
        }

        function Onsuccesschangeitemstatus(data)
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
        function OnErrorchangeitemstatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }



		//Change Item Status (Show On Home)
        function changeshowonhomestatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changeshowonhomestatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangeshowonhomestatus,OnErrorchangeshowonhomestatus); 
        }

        function Onsuccesschangeshowonhomestatus(data)
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
        function OnErrorchangeshowonhomestatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }



		//Change Item Status (Show On Web)
        function changeshowonwebstatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changeshowonwebstatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangeshowonwebstatus,OnErrorchangeshowonwebstatus); 
        }

        function Onsuccesschangeshowonwebstatus(data)
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
        function OnErrorchangeshowonwebstatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }



		//Change Item Status (Show On POS)
        function changeshowonposstatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changeshowonposstatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangeshowonposstatus,OnErrorchangeshowonposstatus); 
        }

        function Onsuccesschangeshowonposstatus(data)
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
        function OnErrorchangeshowonposstatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }


		if($('#itemmasterForm').length){		
            $('#itemmasterForm').validate({
                rules:{
					categoryid:{
						required: true,			
					},
                    subcategoryid:{
                        required: true,			
                    },
					itemname:{
                        required: true,			
                    },
					'storeid[]':{
                        required: true,					
                    },
					price:{
                        required: true,			
                    },
					gsttypeid:{
                        required: true,			
                    },
					gstid:{
                        required: true,			
                    },
					duration:{
						required: true,
					},
					noofstudent:{
						required: true,
					},
					durationid:{
						required: true,
					}
                },messages:{
					categoryid:{
						required:"Category is required",
					},
                    subcategoryid:{
                        required:"Sub category is required",
                    },
					itemname:{
                        required: "Item name is required",			
                    },
					'storeid[]':{
                        required: "Store/Counter is required",						
                    },
					price:{
                        required: "Price is required",			
                    },
					gsttypeid:{
                        required: "Tax type is required",			
                    },
					gstid:{
                        required: "Tax is required",			
                    },
					duration:{
						required: "Duration is required",
					},
					noofstudent:{
						required: "No of Student is required",
					},
					durationid:{
						required: "Duration is required",
					}
                },
                submitHandler: function(form){

					var isvalidate = 0;
					var trlength = $('#tblcompositeitem #tbldata tr').length;
					if ($('#iscompositeitem').is(":checked"))
					{
						if(parseInt(trlength) > 0)
						{
							isvalidate = 1;
						}
					}
					else
					{
						isvalidate = 1;
					}

					if(isvalidate == 1)
					{
						var iswebattribute = $('#categoryid :selected').attr('data-iswebattribute');
						var iscourse = $('#categoryid :selected').attr('data-iscourse'); 
						$('#loaderprogress').show();
						var pagename=getpagename();
						var useraction=$('#formevent').val();
						formdata = new FormData(form);
						formdata.append("action", "insertitem");
						formdata.append("iswebattribute", iswebattribute);
						formdata.append("iscourse", iscourse);
						var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
						ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit);
					}
					else
					{
						alertify('Please add atleast one composite item',0);
					}
			    },
            });
        }


		/************************ Start For Item Images *****************************/
		//Start Upload Item Images
		function uploaditemimage(id)
		{
			$('#ItemImageModal').modal('show');
			$('#ItemImageModal #itemid').val(id);

			getitemimages(id);
		}
		//End Upload Item Images

		//Start submit Upload Item Image form
		if($('#itemupdimageForm').length)
		{		
			$('#itemupdimageForm').validate({
				rules:{
					
				},messages:{
					
				},
				submitHandler: function(form){
					$('#loaderprogress').show();
					var pagename=getpagename();
					var useraction=$('#formevent').val();
					formdata = new FormData(form);
					formdata.append("action", "insertitemimages");
					var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
					ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessUploadItemImage,OnErrorUploadItemImage)
			
				},
			});
		}
		

		function OnsuccessUploadItemImage(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

			if(resultdata.status==0)
			{
				alertify(resultdata.message,0);
			}
			else if(resultdata.status==1)
			{
				alertify(resultdata.message,1);

				$("#itemupdimageForm").validate().resetForm();
            	$('#itemupdimageForm').trigger("reset");

				getitemimages(resultdata.itemid);		
			}
        }

		function OnErrorUploadItemImage(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
		//End submit Upload Item Image form


		function getitemimages(itemid)
		{
		
			$('#loaderprogress').show();
			var pagename=getpagename();
			formdata = new FormData();
			formdata.append("action", "getitemimages");
			formdata.append("itemid", itemid);
			var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'',masterlisting:false};
			ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessgetitemimages,OnErrorgetitemimages)
		}

		function Onsuccessgetitemimages(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
			var imgdiv="";
			if(resultdata.status==0)
			{
				$('#ItemImageModal .viewitemimageblock').html(imgdiv);	
			}
			else if(resultdata.status==1)
			{
				for(var i in resultdata.result)
				{
					
					var id="'"+resultdata.result[i].id+"'";
					var itemid="'"+resultdata.result[i].itemid+"'";

					imgdiv+='<div class="col-md-4 imgblock">';
					imgdiv+='<a class="pull-right delimg" href="javascript:void(0)" onclick="removeitemimage('+id+','+itemid+')" ><i class="bi bi-x-lg"></i></a>';
					imgdiv+='<a href="'+resultdata.result[i].imgpath+'" class="pdfimg" target="_blank"><img style="width: 100%;border:1px solid #eee;" src="'+resultdata.result[i].imgpath+'"></a>';
					imgdiv+='<input type="number" name="imgdisplayorder" min="1" id="imgorder'+resultdata.result[i].id+'" value="'+resultdata.result[i].displayorder+'" style="width:70px;height:30px">';
		            imgdiv+='<button class="btnordsave" data-itemid="'+resultdata.result[i].itemid+'"  data-attr="'+resultdata.result[i].id+'" id="btnordsave'+resultdata.result[i].id+'">Save</button>';
					imgdiv+='</div>';
				}

				$('#ItemImageModal .viewitemimageblock').html(imgdiv);	
			}
        }

		function OnErrorgetitemimages(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }


		$('body').on('click','.btnordsave',function()
		{

			var imgid=$(this).attr('data-attr');
			var displayorder=$('#imgorder'+imgid).val();
			var itemid=$(this).attr('data-itemid');


			$('#loaderprogress').show();
			var pagename=getpagename();
			formdata = new FormData();
			formdata.append("action", "changeitemimgdiplayorder");
			formdata.append("imgid", imgid);
			formdata.append("displayorder", displayorder);
			formdata.append("itemid", itemid);
			var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'',masterlisting:false};
			ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessupdatedisorder,OnErrorupdatedisorder)
			
		});

		function Onsuccessupdatedisorder(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

			if(resultdata.status==0)
			{
				alertify(resultdata.message,0);
			}
			else if(resultdata.status==1)
			{
				alertify(resultdata.message,1);

				getitemimages(resultdata.itemid);		
			}
        }

		function OnErrorupdatedisorder(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }


		function removeitemimage(id,itemid)
		{
			$('#loaderprogress').show();
			var pagename=getpagename();
			formdata = new FormData();
			formdata.append("action", "removeitemimage");
			formdata.append("id", id);
			formdata.append("itemid", itemid);
			var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'',masterlisting:false};
			ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessremoveitemimage,OnErrorremoveitemimage)
		}

		function Onsuccessremoveitemimage(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

			if(resultdata.status==0)
			{
				alertify(resultdata.message,0);
			}
			else if(resultdata.status==1)
			{
				alertify(resultdata.message,1);

				getitemimages(resultdata.itemid);	
			}
        }

		function OnErrorremoveitemimage(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
		/************************ End For Item Images *****************************/


		// Add benefits details
		$('#btnAddbproduct').click(function (e) { 
            
			var rowwebdisplayname=$('#browwebdisplayname').val();

			var rowiconid=$('#browiconid').val();
			var rowiconname=$("#browiconid :selected").attr('data-iconname');
			var rowicon=$("#browiconid :selected").attr('data-iconimg');

			var rowdisplayorder=$('#browdisplayorder').val();
			var rowduration=$('#browduration').val();

             if(rowwebdisplayname && rowiconid)
             {
                $('#tblbcompositeitem tbody tr').each(function(){
                    var tmp = $(this).attr('data-index');
                    if($('#tblbrowwebdisplayname'+tmp).val() == rowwebdisplayname)
                    {
                        $(this).remove();
                    }
                });

				var trtmp = $('#tblwcompositeitem tbody tr:last').attr('data-index') | 0;
            	trtmp = parseInt(trtmp+1);

                var tbldata="";	
                tbldata+='<tr data-index="'+trtmp+'">';
                tbldata+='<td align="left"><input type="text" name="tblbrowwebdisplayname[]" id="tblbrowwebdisplayname'+trtmp+'" class="form-control tblbrowwebdisplayname" value="'+rowwebdisplayname+'" /></td>';
				tbldata+='<td align="left"><input type="text" name="tblbrowduration[]" id="tblbrowduration'+trtmp+'" class="form-control tblbrowduration" value="'+rowduration+'" /></td>';
				tbldata+='<td align="center"><img src="'+rowicon+'" alt="'+rowiconname+'" width="25" /><input type="hidden" name="tblbrowiconid[]" id="tblbrowiconid'+trtmp+'" value="'+rowiconid+'" /></td>';
                tbldata+='<td align="left"><input type="text" name="tblbrowdisplayorder[]" id="tblbrowdisplayorder'+trtmp+'" class="form-control tblbrowdisplayorder" onkeypress="numbonly(event)" value="'+rowdisplayorder+'" /></td>';
				
				tbldata+='<td  align="center" class="tbl-action">';
                tbldata+='<a href="javascript:void(0);" class="btn-tbl text-danger m-0 rounded-circle removetblitem" id="removetblwitem" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="bi bi-x-lg"></i></a>';
                tbldata+='</td>';
				tbldata+='</tr>';

                $('#tblbcompositeitem #tblbdata').append(tbldata);
                

                $('#browwebdisplayname').val('');
				$('#browdisplayorder').val('');
				$('#browduration').val('');
             }
			 else
			 {
                alertify('Please fill in all required fields',0);
             }
             
        }); 

		$('body').on('change','#rowtypeid',function(){

			var type = $('#rowtypeid :selected').attr('data-type');
			$('#rowdiscount').val(0);
			$('#comdurationid').val('');
			$('#comdurationid').selectpicker('refresh');

			if(type == 2)
			{
				$('.showhideDiv').removeClass('d-none');
			}
			else
			{
				$('.showhideDiv').addClass('d-none');
			}
		});




		/* ******** Start Fill Filter ********* */ 
		function fillfltstore()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillfltstore");
			formdata.append("isall", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillfltstore,OnErrorSelectpicker);
        }

        function Onsuccessfillfltstore(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#fltstoreid').html(resultdata.data);
            $('#fltstoreid').selectpicker('refresh');

        }


		function fillfltcategory()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcategory");
			formdata.append("isall", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillfltcategory,OnErrorSelectpicker);
        }

        function Onsuccessfillfltcategory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#fltcatid').html(resultdata.data);
            $('#fltcatid').selectpicker('refresh');

        }


		//Filter
		function filterresetdata()
		{
			$("#fltitemmasterFrm").validate().resetForm();
            $('#fltitemmasterFrm').trigger("reset");
			fillfltstore();
			fillfltcategory();
		}

		$('body').on('click','#btnfiltersave',function(){
			$('#fltitemmasterFrm #issidebarflt').val(1);

			$("#filterSidebar").removeClass("active-right-sidebar"); 
			$('.overlay').removeClass('show');
    		$("body").removeClass("overflow-hidden");

			$('#tableDataList').attr('data-nextpage', 0);
			setTimeout(function(){ 
				listdata();
			}, 120);
		});
		/* ******** End Fill Filter ********* */ 


		

	</script>	