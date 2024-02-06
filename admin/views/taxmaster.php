<?php 
// require_once dirname(__DIR__, 1).'\config\config.php';
// $config = new config();
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
								<h5 class="my-2">Tax</h5>
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
								<div class="col-12 col-lg-6  ml-auto text-right">
									<ul class="page-more-setting">
										<li class="delete-selected tbl-check d-none">
											<a href="javascript:void(0);" class="btn btn-primary m-0" data-toggle="modal" data-target="#modalConfirmation"><i class="bi bi-trash"></i></a>
											<a href="javascript:void(0);" class="btn btn-primary m-0 btnmultitrash" data-toggle="modal" ><i class="bi bi-trash"></i></a>
										</li>
										<li>
											<div class="dropdown table-setting">
												<button class="dropdown-toggle btn btn-primary" type="button" id="tableSetting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-check2-circle"></i></button>
												<div class="dropdown-menu" aria-labelledby="tableSetting">
													<a class="dropdown-item select-bulk-delete" href="javascript:void(0)"><i class="bi bi-trash"></i> Bulk Delete</a>
												</div>
											</div>
										</li>
										<li class="tbl-search mb-2">
											<div class="input-group">
												<input type="text" name="filter" id="filter" class="form-control control-append" placeholder="Search...">
												<div class="input-group-append">
													<label class="input-group-text" for="selectBrandDate" id="btnfilter" name="btnfilter"><i class="fal fa-search"></i></label>
												</div>
											</div>
										</li>
										<li>
											<a href="javascript:void(0);" class="btn btn-primary m-0" id="openRightSidebar">Add New</a>
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
													<th class="tbl-name sorting sorting_asc" data-th="t.taxname">VAT Name</th>
													<th class="tbl-name sorting" data-th="t.igst">VAT Rate(%)</th>
													<th class="tbl-name sorting" data-th="t.saptaxid">SAP VAT</th>
													<?php
														if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
														{
															?>
															<th class="tbl-name">Is Active</th>
															<?php
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
										<form class="row validate-form" id="taxmasterForm" method="post" action="taxmaster" enctype="multipart/form-data">
											<input type="hidden" id="formevent" name="formevent" value='addright'>
											<input type="hidden" id="id" name="id" value=''>	
											
											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">VAT Name <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="VAT Name" id="taxname" name="taxname" require>
												</div>
											</div>

											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">VAT Rate(%) <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<input type="text" class="form-control" placeholder="VAT Rate(%)" id="igst" name="igst" onkeypress="numbonly(event)" require>
												</div>
											</div>

											<div class="col-12">
												<div class="input-group">
													<label class="mb-1">SAP VAT <span class="text-danger">*</span></label>
													<label class="ml-auto "></label>
												</div>
												<div class="input-group mb-3">
													<select class="form-control selectpicker" data-live-search="true" id="saptaxid" name="saptaxid" data-size="10" data-dropup-auto="false">
														<option value="">Select SAP VAT</option>
													</select>
												</div>
											</div>
											
											<div class="col-auto ml-auto">
												<div class="input-group mb-0">
													<button type="submit" class="btn btn-primary m-0" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
													<button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
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


	<script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
        });

		function resetdata()
        {
            $("#taxmasterForm").validate().resetForm();
            $('#taxmasterForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');  
			Edittimeformnamechange(2);    

			fillsapvat();
        }


		//Fill SAP VAT
		function fillsapvat()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillsapvat");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'saphanamaster',formdata,headersdata,Onsuccessfillsapvat,OnErrorSelectpicker);
        }

        function Onsuccessfillsapvat(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
			$('#saptaxid').html(resultdata.data);
            $('#saptaxid').selectpicker('refresh');
        }

		//Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};

            formdata = new FormData();
            formdata.append("action", "edittaxmaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesstaxedit,OnErrortaxedit); 
        }

        function Onsuccesstaxedit(content)
        {
            var JsonData = JSON.stringify(content);
            var resulteditdata = jQuery.parseJSON(JsonData);

            $('#id').val(resulteditdata.id);
            $('#taxname').val(resulteditdata.taxname);
            $('#igst').val(resulteditdata.igst); 

			
			var pagename=getpagename();
			var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
			formdata = new FormData();
			formdata.append("action", "fillsapvat");
			ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'saphanamaster',formdata,headersdata,Onsuccessfilledtsapvat,OnErrorSelectpicker);

			function Onsuccessfilledtsapvat(content)
			{
				var JsonData = JSON.stringify(content);
				var resultdata = jQuery.parseJSON(JsonData);
				$('#saptaxid').html(resultdata.data);
				$('#saptaxid').val(resulteditdata.saptaxid);
				$('#saptaxid').selectpicker('refresh');
			}

			 
            $('#formevent').val('editright'); 
            $('#btnsubmit').text('<?php echo $config->getUpdateSidebar(); ?>');     

            //Open Side bar
			Edittimeformnamechange(1);
            $("#rightSidebar").toggleClass("active-right-sidebar"); 
            $('.overlay').addClass('show');
            $("body").addClass("overflow-hidden");
        }

        function OnErrortaxedit(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
        //Edit End


		if($('#taxmasterForm').length){		
            $('#taxmasterForm').validate({
                rules:{
                    taxname:{
                        required: true,			
                    },
					igst:{
                        required: true,			
                    },
					saptaxid:{
                        required: true,			
                    },
                },messages:{
                    taxname:{
                        required:"VAT name is required",
                    },
					igst:{
                        required:"VAT rate is required",
                    },
					saptaxid:{
                        required:"SAP VAT is required",
                    },
                },
                submitHandler: function(form){

                    $('#loaderprogress').show();
                    var pagename=getpagename();
                    var useraction=$('#formevent').val();
                    formdata = new FormData(form);
                    formdata.append("action", "inserttax");
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit)

			    },
            });
        }
		

		function changetaxstatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changetaxstatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangetaxstatus,OnErrorchangetaxstatus); 
        }

        function Onsuccesschangetaxstatus(data)
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
        function OnErrorchangetaxstatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }



	</script>	