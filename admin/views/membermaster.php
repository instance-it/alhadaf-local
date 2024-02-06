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
                                                <th></th>
                                                <th class="sorting" data-th="pu.userrole">User Type</th>
                                                <th class="tbl-name sorting_asc sorting" data-th="personname">Name</th>
                                                <th class="sorting" data-th="email">Email</th>
                                                <th class="tbl-name sorting" data-th="contact">Contact No</th>
                                                <th class="tbl-name sorting" data-th="primary_date">Entry Date</th>
                                                <th class="tbl-name text-center">Status</th>
                                                <th class="tbl-name text-center">Document Upload?</th>
                                                <?php 
													if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
													{
                                                        echo '<th class="tbl-name text-center">Reset Password</th>';
                                                        echo '<th class="tbl-name text-center">Is Active</th>';
                                                        echo '<th class="tbl-name text-center">Verified ?</th>';
                                                        echo '<th class="tbl-name text-center">Action</th>';
                                                    }
                                                ?>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="datalist">
                                        </tbody>
                                    </table>
                                </div>
                                <!-- table End -->
                            </div>
                        </div>
                    </div>
                    <?php require_once 'pagefooter.php'; ?>
                </div>
            </div>

            <!-- rightSidebar Start -->
            <div class="col-12 p-4" id="rightSidebar">
                <div class="right-sidebar-content">
                    <div class="row">   
                        <div class="col-12 mb-4 sidebar-header">
                            <div class="row">
                                <div class="col">
                                    <h5 class="my-2"><span class="formnamelbl"></span></h5>
                                </div>
                                <div class="col-auto pl-0">
                                    <a href="javascript:void(0);" class="btn btn-danger m-0 rounded-circle btn-close-right-sidebar" id="btnCloseRightSidebar" data-toggle="tooltip" data-placement="top" title="<?php echo $config->getCloseSidebar(); ?>" data-original-title="<?php echo $config->getCloseSidebar(); ?>"><i class="fas fa-times"></i></a>
                                </div>
                            </div>
                        </div>  
                        
                        <form class="text-left validate-form col-12 p-0" id="membermasterForm" method="post" action="membermaster.php" enctype="multipart/form-data">
                            <input type="hidden" id="formevent" name="formevent" value='addright'>
                            <input type="hidden" id="id" name="id" value=''>
                            
                            <div class="col-12 layout-spacing">
                                <div class="widget">
                                    <div class="widget-content">
                                        <div class="row">

                                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">First Name <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="First Name" id="firstname" name="firstname" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Middle Name </label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="Middle Name" id="middlename" name="middlename" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Last Name <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="Last Name" id="lastname" name="lastname" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Email <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-lg-3">
                                                    <input type="email" class="form-control" placeholder="Email" id="emailid" name="emailid" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Mobile Number <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="Mobile Number" id="mobilenumber" maxlength="15" onkeypress="numbonly(event)"  name="mobilenumber" autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Qatar Id Number </label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="Qatar Id Number" id="qataridno" name="qataridno" autocomplete="off">
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Qatar Id Expiry </label>
                                                </div>
                                                <div class="input-group mb-3">
													<input type="text" class="form-control control-append expiry" placeholder="Qatar Id Expiry"  id="qataridexpiry" name="qataridexpiry" require autocomplete="off">
													<div class="input-group-append">
														<label class="input-group-text" for="qataridexpiry"><i class="bi bi-calendar4-week"></i></label>
													</div>
												</div>
                                            </div>


                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Passport Id Number </label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="Passport Id Number" id="passportidno" name="passportidno" autocomplete="off">
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Passport Id Expiry </label>
                                                </div>
                                                <div class="input-group mb-3">
													<input type="text" class="form-control control-append expiry" placeholder="Passport Id Expiry"  id="passportidexpiry" name="passportidexpiry" require autocomplete="off">
													<div class="input-group-append">
														<label class="input-group-text" for="passportidexpiry"><i class="bi bi-calendar4-week"></i></label>
													</div>
												</div>
                                            </div>

                                            <!-- <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Passport Id Number <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="Passport Id Number" id="qataridno" name="qataridno" autocomplete="off">
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Passport Id Expiry <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3">
													<input type="text" class="form-control control-append expiry" placeholder="Passport Id Expiry"  id="expiry" name="expiry" require autocomplete="off">
													<div class="input-group-append">
														<label class="input-group-text" for="expiry"><i class="bi bi-calendar4-week"></i></label>
													</div>
												</div>
                                            </div> -->

                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Date of Birth <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3">
													<input type="text" class="form-control control-append dob" placeholder="Date of Birth"  id="dob" name="dob" require autocomplete="off">
													<div class="input-group-append">
														<label class="input-group-text" for="dob"><i class="bi bi-calendar4-week"></i></label>
													</div>
												</div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Nationality <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="Nationality" id="nationality" name="nationality" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Address</label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <textarea type="text" class="form-control" placeholder="Address" id="address" name="address"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Company</label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="Company" id="company" name="company" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 passwordDiv">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Password<span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="password" class="form-control" placeholder="Password" id="password" name="password" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 passwordDiv">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Confirm Password<span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="password" class="form-control" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword" autocomplete="off">
                                                </div>
                                            </div>

                                            

                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            

                            <div class="col-12 col-md-12 col-lg-12 layout-spacing">
                                <div class="widget min-height-100">
                                    
                                    <div class="widget-content">
                                        <div class="row">                              
                                            
                                            <div class="col-12 col-sm-4 col-md-4 col-lg-4">
												<div class="input-group">
													<label class="mb-1">Proof Of Qatar ID <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3 mb-lg-3">
													<input type="file" class="form-control d-none" id="qataridproof" name="qataridproof" accept="image/*">
													<label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="qataridproof">
														<span style="background-image: url(assets/img/salon.jpg);"></span>
														<i class="bi bi-folder2-open d-block pt-1"></i> Browse...
													</label>
													<span style="color:#B00" class="imgsizelbl2">** Please upload icon in JPG,JPEG,PNG,PDF Format</span>
												</div>
											</div>
                                            <div class="col-12 col-sm-4 col-md-4 col-lg-4">
												<div class="input-group">
													<label class="mb-1">Proof Of Passport <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3 mb-lg-3">
													<input type="file" class="form-control d-none" id="passportproof" name="passportproof" accept="image/*">
													<label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="passportproof">
														<span style="background-image: url(assets/img/salon.jpg);"></span>
														<i class="bi bi-folder2-open d-block pt-1"></i> Browse...
													</label>
													<span style="color:#B00" class="imgsizelbl2">** Please upload icon in JPG,JPEG,PNG,PDF Format</span>
												</div>
											</div>
                                            <div class="col-12 col-sm-4 col-md-4 col-lg-4">
												<div class="input-group">
													<label class="mb-1">Other Government Valid Proof</label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3 mb-lg-3">
													<input type="file" class="form-control d-none" id="othergovernmentproof" name="othergovernmentproof" accept="image/*">
													<label tabindex="1" class="badge outline-badge-primary w-100 text-center btn-file py-1" for="othergovernmentproof">
														<span style="background-image: url(assets/img/salon.jpg);"></span>
														<i class="bi bi-folder2-open d-block pt-1"></i> Browse...
													</label>
													<span style="color:#B00" class="imgsizelbl2">** Please upload icon in JPG,JPEG,PNG,PDF Format</span>
												</div>
											</div>
                                                    
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 layout-spacing">
                                <div class="widget min-height-100">
                                    
                                    <div class="widget-content">
                                        <div class="row">                              
                                            <div class="col-12 ml-auto">
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
            <!-- rightSidebar End -->
        </div>
        <!-- CONTENT AREA -->



        <div class="modal fade" id="resetpassModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" role="document" style="width: 400px">
				<div class="modal-content" >
					<div class="modal-body">
						<div class="row mb-4">
							<div class="col-12">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
								<h5 class="modal-title" id="AddServicesLabel">Reset Password</h5>
							</div>
						</div>
						<form class="form-horizontal jQueryForm" id="resetpassForm" method="post" enctype="multipart/form-data">
							<input type="hidden" name="userid" id="userid" />
							<div class="form-group" style="margin-bottom:0;">
								<div class="col-sm-12">
									<p>Email : <label class="form-label unametext"></label></label>
                                    <p>Old Password : <label class="form-label oldpasswordtext"></label></label>
								</div>		
							</div>
							
							<div class="form-group">
								<div class="col-sm-12">
									<label class="form-label">New Password *</label>
									<input type="password" id="npass" name="npass" class="form-control" placeholder="Enter New Password" />
								</div>		
							</div>
							
							<div class="form-group">
								<div class="col-sm-12 text-right">
									<input class="btn btn-primary" name="btnsubmit" id="submitbtn" type="submit" value="Reset"/>
								</div>		
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

        <div class="modal fade info-modal-dialog" id="verifiedModal" tabindex="-1" role="dialog" aria-labelledby="confirmedDeleteLabel" aria-hidden="true" >
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="info-body">
                            <div class="modal-logo"><img src="assets/img/favicon.png"></div>
                            <h5 class="my-3 px-3" id="syncLabel"><br>Are you sure?</h5> 
                            <p class="my-3 px-4">You want to verifiy this Member !</p>
                        </div>
                    </div>
                    <form class="form-horizontal jQueryForm" id="verifiedForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id" />
                        <div class="modal-footer text-center p-0">
                            <button type="button" class="col btn btn-primary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="col btn btn-primary">Yes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            
        });

        $('.dob').bootstrapMaterialDatePicker
        ({
            weekStart: 0, 
			format: 'DD/MM/YYYY', 
			time: false,
            switchOnClick: true,
            okText: "",
			maxDate: moment(), // Current day

        }).on('change', function(e, date)
        {
            $(this).removeClass('error');
            $(this).parent('div').removeClass('error');  
	        $(this).parent().find('label.error').html('');
        });

        $('.expiry').bootstrapMaterialDatePicker
        ({
            weekStart: 0, 
			format: 'DD/MM/YYYY', 
			time: false,
            switchOnClick: true,
            okText: "",
			// minDate: moment(), // Current day

        }).on('change', function(e, date)
        {
            $(this).removeClass('error');
            $(this).parent('div').removeClass('error');  
	        $(this).parent().find('label.error').html('');
        });

        function resetdata()
        {
            $("#membermasterForm").validate().resetForm();
            $('#membermasterForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#tbldata').html('');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');
			$('.passwordDiv').removeClass('d-none');
            $('.clslogindiv').addClass('d-none');

            Edittimeformnamechange(2); 
                       						
        } 

        //Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "editmembermaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessedtmembermaster,OnErroredtmembermaster); 
        }

        function Onsuccessedtmembermaster(content)
        {
            var JsonData = JSON.stringify(content);
            var resulteditdata = jQuery.parseJSON(JsonData);

            $('#id').val(resulteditdata.id);
            $('#firstname').val(resulteditdata.firstname);
            $('#middlename').val(resulteditdata.middlename);
            $('#lastname').val(resulteditdata.lastname);
            $('#mobilenumber').val(resulteditdata.mobilenumber);
            $('#qataridno').val(resulteditdata.qataridno);
            $('#qataridexpiry').val(resulteditdata.qataridexpiry);
            $('#passportidno').val(resulteditdata.passportidno);
            $('#passportidexpiry').val(resulteditdata.passportidexpiry);
            $('#emailid').val(resulteditdata.email);
            
            $('#dob').val(resulteditdata.dob);
            $('#nationality').val(resulteditdata.nationality);         
            $('#address').val(resulteditdata.address);
            $('#company').val(resulteditdata.company);

        
            // ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillutype,OnErrorSelectpicker);        
            // ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillstate,OnErrorSelectpicker); 
            
            $('.selectpicker').selectpicker('refresh');           
            $('.passwordDiv').addClass('d-none');
            $('#formevent').val('editright');       
            $('#btnsubmit').text('<?php echo $config->getUpdateSidebar(); ?>');     

            //Open Side bar
            Edittimeformnamechange(1);
            $("#rightSidebar").toggleClass("active-right-sidebar"); 
            $('.overlay').addClass('show');
            $("body").addClass("overflow-hidden");
        }

        function OnErroredtmembermaster(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
        //Edit End

        //change person status
        function changememberstatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changememberstatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangememberstatus,OnErrorchangememberstatus); 
        }

        function Onsuccesschangememberstatus(data)
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
        function OnErrorchangememberstatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }



        //View Details Start
        function viewmemberdetaildata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "viewmemberdata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewmemberdata,OnErrorviewmemberdata); 
        }

		function Onsuccessviewmemberdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-xl');
			$('#GeneralModal #GeneralModalLabel').html('Member Details');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorviewmemberdata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }

        // //user type change
        // $('#utypeid').on('change', function(){
        //     $('.clslogindiv').addClass('d-none');
          
        //     var aryisweblogin = [];
        //     var aryisapplogin = [];
            
        //     var utypeid = $('#utypeid').val();
        //     if(utypeid != null){
        //         for(var i=0;i<utypeid.length;i++)
        //         {
        //             var isweblogin=$('#utypeid option[value='+utypeid[i]+']').attr('data-isweblogin');
        //             var isapplogin=$('#utypeid option[value='+utypeid[i]+']').attr('data-isapplogin');
        //             aryisweblogin.push(isweblogin);
        //             aryisapplogin.push(isapplogin);
            
        //         }
                
        //         if($.inArray('1', aryisweblogin)>=0 || $.inArray('1', aryisapplogin)>=0)
        //         {
        //             $('.clslogindiv').removeClass('d-none');
        //             var formevent = $('#formevent').val();
        //             if(formevent == 'editright')
        //             {
        //                 $('.passwordDiv').addClass('d-none');
        //             }
        //             else
        //             {
        //                 $('.passwordDiv').removeClass('d-none');
        //             }
                    
        //         }
        //         else if($.inArray('1', aryisweblogin) <=0 || $.inArray('1', aryisapplogin) <=0)
        //         {
        //             $('.clslogindiv').addClass('d-none');
                    
        //         }
                
        //     }
        // })


        //Freeze Membership
        function membershipfreeze(memberid)
        {
            var pagename = 'memberfreezemship';
            // currentXhr.abort();
            // render(pagename);
            // window.history.pushState(pagename, 'Title', dirpath + pagename+'?mid='+memberid);

            window.open(dirpath + pagename+'?mid='+memberid,'_blank');
        }

        if($('#membermasterForm').length){		
            $('#membermasterForm').validate({
                rules:{
                    qataridexpiry:{
                        //required: true,	
						//mobileno:true,
                        //maxlength:15				
                    },
					mobilenumber:{
						//mobileno:true,
                        maxlength:15,
                        required: true,				
                    },
                    qataridno:{
						//mobileno:true,
                        //maxlength:15,
                        //required: true,				
                    },
					emailid:{
                        required: true,	
						email: true				
                    },
                    password:{
                        required:true,		
                    },
                                      
                    firstname:{
                        required: true,
                    },
                    lastname:{
                        required: true,
                    },
                    confirmpassword:{
                        required: true,
                        equalTo: '#password'
                    },
                    dob:{
                        required: true,
                    },
                    nationality:{
                        required: true,
                    }
                },messages:{
					
                    mobilenumber:{
                        required: "Mobile number is required",					
                    },
                    qataridno:{
                        //required: "Qatar Id Number is required",					
                    },
					qataridexpiry:{
						//required:"Qatar Id Expiry is required",						
                    },
					emailid:{
                        required: "Email is required",				
                    },
                    
                    password:{
                        required: "Password is required",						
                    },
        
                    firstname:{
                        required: "First name is required",
                    },
                    lastname:{
                        required: "Last name is required",
                    },
                    confirmpassword:{
                        required: "Confirm password is required",
                        equalTo:"Confirm password must be same as password"
                    },
                    dob:{
                        required: "Date of Birth is required",
                    },
                    nationality:{
                        required: "Nationality is required",
                    }
                },
                submitHandler: function(form){

                    
                    $('#loaderprogress').show();
                    var pagename=getpagename();
                    var useraction=$('#formevent').val();
                    formdata = new FormData(form);
                    formdata.append("action", "insertmembermaster");
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false,responsetype: 'HTML'};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit); 
			    },
            });
        }



        //start reset password
		function resetpassword(id,uname,oldpassword)
		{
			$('#resetpassForm #npass').val('');
			$('#resetpassForm #userid').val(id);
			$('#resetpassForm .unametext').html(uname);
            $('#resetpassForm .oldpasswordtext').html(oldpassword);
            
			$('#resetpassModal').modal('show');	
		}

        //submit reset password form
        if($('#resetpassForm').length)
		{		
			$('#resetpassForm').validate({
				rules:{
					npass:{
						required: true,
					},	
				},
		        messages:{
		        	npass:{
						required: "New password is required",
					},
		        },
		        submitHandler: function(form){
					var pagename=getpagename();
					var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
					formdata = new FormData(form);
					formdata.append("action", "changepassword");
					ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangepassword,OnErrorchangepassword); 

					
		        }	
			});
		}

        function Onsuccesschangepassword(data)
        {
            var JsonData = JSON.stringify(data);
            var resultdata = jQuery.parseJSON(JsonData);
            if(resultdata.status==0)
            {
                alertify(resultdata.message,0);
            }
            else if(resultdata.status==1)
            {
				$('#resetpassForm').validate().resetForm();
				$('#resetpassForm').trigger("reset");
				$('#resetpassModal').modal('hide');
                alertify(resultdata.message,1);
            }
            $('#tableDataList').attr('data-nextpage',0); 
            listdata();
        }


        function OnErrorchangepassword(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

        //reset modal hide event
		$('#resetpassModal').on('hide.bs.modal', function() {
			$('#resetpassForm').validate().resetForm();
			$('#resetpassForm').trigger("reset");
			$('#resetpassForm #userid').val('');
		});  


        function memberverifedstatus(id)
        {
           $('#verifiedModal').modal('show');
           $('#verifiedModal #id').val(id);
        }

        if($('#verifiedForm').length)
		{		
			$('#verifiedForm').validate({
				rules:{
				},
		        messages:{
		        },
		        submitHandler: function(form)
                {
                    var pagename=getpagename();
					var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
					formdata = new FormData(form);
					formdata.append("action", "memberverifedstatus");
					ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessmemberverifedstatus,OnErrormemberverifedstatus); 

                    $('#verifiedModal').modal('hide');   
		        }	
			});
		}


        function Onsuccessmemberverifedstatus(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            if(resultdata.status==0)
            {
                alertify(resultdata.message, 0);
            }
            else if(resultdata.status==1)
            {
                alertify(resultdata.message, 1);
               
                $('#tableDataList').attr('data-nextpage',0); 
                listdata();
            }
            
        }

        function OnErrormemberverifedstatus(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->




    
