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
										<li class="tbl-search mb-2">
											<div class="input-group">
												<input type="text" name="filter" id="filter" class="form-control control-append" placeholder="Search...">
												<div class="input-group-append">
													<label class="input-group-text" for="selectBrandDate" id="btnfilter" name="btnfilter"><i class="fal fa-search"></i></label>
												</div>
											</div>
										</li>
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
												<th class="tbl-name sorting sorting_asc" data-th="s.storename">Store</th>
													<th class="tbl-name sorting sorting_asc" data-th="o.transactionid">Transaction Id</th>
													<th class="tbl-name sorting" data-th="o.orderno">Order No</th>
													<th class="tbl-name sorting" data-th="o.timestamp">Order Date</th>
													<th class="tbl-name sorting" data-th="o.totalpaid">Amount</th>
													<th class="tbl-name sorting" data-th="pm.personname">Member</th>
													<th class="tbl-name sorting" data-th="pm1.personname">Entry By</th>
													<th class="tbl-name text-center" >Status</th>
													<th class="tbl-name" style="text-align: center;">Invoice</th>
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
							<form class="form" action="" name="fltposserviceorderFrm" id="fltposserviceorderFrm">
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
											<label class="mb-1">Member</label>
										</div>
										<div class="input-group mb-3 mb-sm-3">
											<select class="form-control selectpicker" data-live-search="true" data-size="10" data-dropup-auto="false" id="fltmemberid" name="fltmemberid">
											
											</select>
										</div>
									</div>

									<div class="col-6">
										<div class="input-group mb-0">
											<label class="mb-1">From Date</label>
										</div>
										<div class="input-group mb-3 mb-sm-3">
											<input type="text" class="form-control control-append" placeholder="From Date" id="fltfromdate" name="fltfromdate" value="">
										</div>
									</div>


									<div class="col-6">
										<div class="input-group mb-0">
											<label class="mb-1">To Date</label>
										</div>
										<div class="input-group mb-3 mb-sm-3">
											<input type="text" class="form-control control-append" placeholder="To Date" id="flttodate" name="flttodate" value="">
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

			//fill Store filter
			fillfltstore();
        });


		//View Service Order Data Start
        function viewserviceorderdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "viewserviceorderdata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewserviceorderdata,OnErrorviewserviceorderdata); 
        }

		function Onsuccessviewserviceorderdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-exl');
			$('#GeneralModal #GeneralModalLabel').html('Service Order Details');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorviewserviceorderdata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }



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

			fillfltmember();
        }


		function fillfltmember()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillmember");
			formdata.append("isall", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillfltmember,OnErrorSelectpicker);
        }

        function Onsuccessfillfltmember(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#fltmemberid').html(resultdata.data);
            $('#fltmemberid').selectpicker('refresh');

        }


		$('#fltfromdate').bootstrapMaterialDatePicker
        ({
            weekStart: 0, 
			format: 'DD/MM/YYYY',
			time: false,
			switchOnClick: true,
            okText: ""
        }).on('change', function(e, date)
        {
			$('#flttodate').bootstrapMaterialDatePicker('setMinDate',date);
			$('#flttodate').val($('#fltfromdate').val());

			$('#tableDataList').attr('data-nextpage', 0);
			setTimeout(function(){ 
				//listdata();
			}, 200);
        });


		$('#flttodate').bootstrapMaterialDatePicker
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

		$('#flttodate').bootstrapMaterialDatePicker('setMinDate',$('#fltfromdate').val());

		//Filter
		function filterresetdata()
		{
			$("#fltposserviceorderFrm").validate().resetForm();
            $('#fltposserviceorderFrm').trigger("reset");
			fillfltstore();
		}

		$('body').on('click','#btnfiltersave',function(){
			$('#fltposserviceorderFrm #issidebarflt').val(1);

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