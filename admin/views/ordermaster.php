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
													<th class="tbl-name sorting sorting_asc" data-th="o.transactionid">Transaction Id</th>
													<th class="tbl-name sorting" data-th="o.orderno">Invoice No</th>
													<th class="tbl-name sorting" data-th="o.timestamp">Invoice Date</th>
													<th class="tbl-name sorting" data-th="o.totalpaid">Amount</th>
													<th class="tbl-name sorting" data-th="pm.personname">Member</th>
													<th class="tbl-name sorting" data-th="pm1.personname">Entry By</th>
													<th class="tbl-name sorting" data-th="o.saporderid">SAP DocEntry</th>
													<th class="tbl-name sorting" data-th="o.sapdocnum">SAP DocNum</th>
													<th class="tbl-name" style="text-align: center;">Status</th>
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
				<div class="col-12 p-4 full-sidebar" id="rightSidebar">
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
								<form class="row validate-form" id="ordermasterForm" method="post" action="ordermaster" enctype="multipart/form-data">     
									<input type="hidden" id="formevent" name="formevent" value='addright'>
									<input type="hidden" id="id" name="id" value=''>
									<input type="hidden" name="couponapply" id="couponapply" value="0">
									<input type="hidden" name="couponid" id="couponid" value="">
									<input type="hidden" name="couponcode" id="couponcode" value="">
									<input type="hidden" name="coupontype" id="coupontype" value="0">
									<input type="hidden" name="couponamount" id="couponamount" value="0">		
									<input type="hidden" name="couponpercent" id="couponpercent" value="0">


									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget">
											<div class="widget-content row">
												<div class="col-12 col-sm-6 col-md-6 col-lg-6">
													<div class="input-group">
														<label class="mb-1">Member <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<select class="form-control selectpicker" data-live-search="true" id="memberid" name="memberid" data-size="10" data-dropup-auto="false">
															<option value="">Select Member</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget mt-10">
											<div class="widget-content row">
												<div class="col-12 col-sm-6 col-md-12 col-lg-12">
													<div class="row">
														<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 col-exl-2">
															<div class="input-group mb-0">
																<label class="mb-1">Category <span class="text-danger">*</span></label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<select class="form-control selectpicker" data-live-search="true" id="categoryid" name="categoryid" data-size="10" data-dropup-auto="false">
																	<option value="">Select Category</option>
																</select>
															</div>
														</div>

														<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 col-exl-2">
															<div class="input-group mb-0">
																<label class="mb-1">Sub Category <span class="text-danger">*</span></label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<select class="form-control selectpicker" data-live-search="true" id="subcategoryid" name="subcategoryid" data-size="10" data-dropup-auto="false">
																	<option value="">Select Sub Category</option>
																</select>
															</div>
														</div>

														<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 col-exl-2">
															<div class="input-group mb-0">
																<label class="mb-1">Item <span class="text-danger">*</span></label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<select class="form-control selectpicker" data-live-search="true" id="itemid" name="itemid" data-size="10" data-dropup-auto="false">
																	<option value="">Select Item</option>
																</select>
															</div>
														</div>

														<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 col-exl-2">
															<div class="input-group mb-0">
																<label class="mb-1">Price <span class="text-danger">*</span></label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<input type="text" class="form-control defaultprice" placeholder="Price" id="price" name="price" onkeypress="numbonly(event)" onpaste="numbonly(event)">
															</div>
														</div>

														<div class="col-auto mt-auto mb-auto">
															<button type="button" class="col-auto btn btn-primary m-0" id="btnadditem" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add"><i class="bi bi-plus-lg"></i></button>
														</div>
													</div>
												</div>

												<div class="col-12 col-sm-12 col-md-12 col-lg-12">
													<div class="row">
														<div class="table-responsive pt-2">
															<div class="col-12 p-0">
																<table id="tableitemdata" class="table table-bordered table-hover table-striped">
																	<thead>
																		<tr>
																			<th class="tbl-name">Item Details </th>
																			<th class="tbl-name">Validity</th>
																			<th class="tbl-name">Amount</th>
																			<th class="tbl-name">Taxable</th>
																			<th class="tbl-name">Tax</th>
																			<th class="tbl-name">Payable Amount</th>
																			<th class="tbl-action">Action</th>
																		</tr>
																	</thead>
																	<tbody id="tblitemdata">
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-6 layout-spacing">
										<div class="widget mt-10">
											<div class="widget-content row">
												<div class="col-12 col-sm-6 col-md-12 col-lg-12">
													<div class="row">
														<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-exl-6 mb-3">
															<div class="input-group mb-0">
																<label class="mb-1">Coupon Code <span class="text-danger">*</span></label>
															</div>
															<div class="input-group">
																<input type="text" class="form-control" placeholder="Coupon Code" id="ccode" name="ccode">
															</div>
															<a href="javascript:void(0)" id="removecoupon" class="text-danger" title="Remove Coupon">Remove Coupon</a>
														</div>

														<div class="col-auto mt-auto mb-auto couponapplyDiv">
															<button type="button" class="col-auto btn btn-primary" id="btncouponapply">Apply</button>
														</div>
													</div>
													<div class="row">
														<div class="col-12 col-sm-6 col-md-6 col-lg-6">
															<div class="input-group mb-0">
																<div class="col-12 p-0">
																	<div class="row mr-0">
																		<div class="col-12">
																			<label class="mb-1">Payment Type <span class="text-danger">*</span></label>
																		</div>
																		<div class="col-12 col-sm-4 pr-0">
																			<div class="custom-control custom-radio mb-3">
																				<input type="radio" class="custom-control-input paymenttype" id="cash" value="1" name="paymenttype" checked="">
																				<label class="custom-control-label mb-0" for="cash">Cash</label>
																			</div>
																		</div>
																		<div class="col-12 col-sm-8 pr-0">
																			<div class="custom-control custom-radio mb-3">
																				<input type="radio" class="custom-control-input paymenttype" id="onlinepayment" value="2" name="paymenttype">
																				<label class="custom-control-label mb-0" for="onlinepayment">Online Payment</label>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 col-exl-6 d-none referenceDiv">
															<div class="input-group mb-0">
																<label class="mb-1">Reference No <span class="text-danger">*</span></label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<input type="text" class="form-control " placeholder="Reference No" id="referenceno" name="referenceno">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-lg-6 col-md-6 col-6 layout-spacing">
										<div class="widget mt-10">
											<div class="widget-content row">
												<div class="col-12 col-sm-6 col-md-12 col-lg-12">
													<div class="row">
														<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-exl-3">
															<div class="input-group mb-0">
																<label class="mb-1">Total Amount <span class="text-danger">*</span></label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<input type="text" id="totalamount" name="totalamount" class="form-control valid" placeholder="Total Amount" readonly="readonly" value="0" autocomplete="off">	
															</div>
														</div>
														<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-exl-3">
															<div class="input-group mb-0">
																<label class="mb-1">Total Taxable <span class="text-danger">*</span></label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<input type="text" id="totaltaxable" name="totaltaxable" class="form-control valid" placeholder="Total Taxable" readonly="readonly" value="0" autocomplete="off">	
															</div>
														</div>
														<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-exl-3">
															<div class="input-group mb-0">
																<label class="mb-1">Total Tax <span class="text-danger">*</span></label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<input type="text" id="totaltax" name="totaltax" class="form-control valid" placeholder="Total Tax" readonly="readonly" value="0" autocomplete="off">	
															</div>
														</div>
														<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-exl-3">
															<div class="input-group mb-0">
																<label class="mb-1">Coupon Amount <span class="text-danger">*</span></label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<input type="text" id="totalcouponamount" name="totalcouponamount" class="form-control valid" placeholder="Coupon Amount" readonly="readonly" value="0" autocomplete="off">	
															</div>
														</div>
														<div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 col-exl-3">
															<div class="input-group mb-0">
																<label class="mb-1">Total Paid Amount <span class="text-danger">*</span></label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<input type="text" id="totalpaidamount" name="totalpaidamount" class="form-control valid" placeholder="Total Paid Amount" readonly="readonly" value="0" autocomplete="off">	
															</div>
														</div>
														<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-exl-12">
															<div class="input-group mb-0">
																<label class="mb-1">Invoice Note</label>
															</div>
															<div class="input-group mb-3 mb-sm-3">
																<textarea id="ordernote" name="ordernote" class="form-control" placeholder="Enter Invoice Notes" spellcheck="false"></textarea>	
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="col-auto ml-auto">
										<div class="input-group mb-0">
											<button type="submit" class="btn btn-primary m-0 btn-loader" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
											<button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
										</div>
									</div>
								</form>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		
	

		


	<script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');

			//Fill Member
			fillmember();

			//Fill Category
			fillcategory();
        });

		
		function resetdata()
        {
            $("#ordermasterForm").validate().resetForm();
            $('#ordermasterForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');  
			Edittimeformnamechange(2);    
			$('#tblitemdata').html('');
			$('.referenceDiv').addClass('d-none');

			$('#ccode').val('')
			$('#couponapply').val(0);
			$('#couponid').val('');
			$('#couponcode').val('');
			$('#coupontype').val(0);
			$('#couponamount').val(0);
			$('#couponpercent').val(0);
			$('#removecoupon').addClass('d-none');
			$('.couponapplyDiv').removeClass('d-none');
			$('#ccode').removeAttr("readonly");

			//Fill Member
			fillmember();

			//Fill Category
			fillcategory();
        }

		//Fill Member
		function fillmember()
		{
			var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillmember");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillmember,OnErrorSelectpicker);
		}

		function Onsuccessfillmember(content)
		{
			var JsonData = JSON.stringify(content);
			var resultdata = jQuery.parseJSON(JsonData);
			$('#memberid').html(resultdata.data);
			$('#memberid').selectpicker('refresh');
		}

		//Fill Category
		function fillcategory()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcategory");
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillcategory,OnErrorSelectpicker);
        }

        function Onsuccessfillcategory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#categoryid').html(resultdata.data);
            $('#categoryid').selectpicker('refresh');

			$('#subcategoryid').html('')
			$('#subcategoryid').selectpicker('refresh');

			$('#itemid').html('')
			$('#itemid').selectpicker('refresh');

			//Fill Sub Category
			fillsubcategory();
        }

		//Change Event Of Category
		$('body').on('change','#categoryid',function(){
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
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillsubcategory,OnErrorSelectpicker);
        }

        function Onsuccessfillsubcategory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#subcategoryid').html(resultdata.data);
            $('#subcategoryid').selectpicker('refresh');

			$('#itemid').html('')
			$('#itemid').selectpicker('refresh');
			//Fill Item
			fillitem();
        }

		//Change Event Of Sub Category
		$('body').on('change','#subcategoryid',function(){
			fillitem();
		});

		//Fill Item
		function fillitem()
        {
			var categoryid = $('#categoryid').val();
			var iscompositeitem = $('#categoryid :selected').attr('data-iscompositeitem');
			var subcategoryid = $('#subcategoryid').val();

            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillitems");
			formdata.append("categoryid", categoryid);
			formdata.append("subcategoryid", subcategoryid);
			formdata.append("iscompositeitem", iscompositeitem);
			formdata.append("selectoption", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillitem,OnErrorSelectpicker);
        }

        function Onsuccessfillitem(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#itemid').html(resultdata.data);
            $('#itemid').selectpicker('refresh');
        }


		//Change Event Of Item
		$('body').on('change','#itemid',function(){
			var price = $('#itemid :selected').attr('data-price');
			$('#price').val(price)
		});

		//Input Event Of Default Value
		$('body').on('input','.defaultprice',function(){
			var  price = $(this).val();
			if(isNaN(parseFloat(price)))
			{
				price=0;
			}
			$(this).val(price)
		});

		/***************** Start For Add Pricing *****************/
		//Add Pricing in Table
		$('body').on('click','#btnadditem', function () {

			var categoryid=$('#categoryid').val();
			var categoryname=$('#categoryid').find("option:selected").text();
			var subcategoryid=$('#subcategoryid').val();
			var subcategory=$('#subcategoryid').find("option:selected").text();
			var itemid=$('#itemid').val();
			var item=$('#itemid').find("option:selected").text();
			var price=parseFloat($('#price').val());
			
			if(isNaN(parseFloat(price)))
			{
				price=0;
			}
		

			if(categoryid && subcategoryid && itemid && price >= 0)
			{

				var pagename=getpagename();
				var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'JSON'};
				formdata = new FormData();
				formdata.append("action", "fillitemdata");
				formdata.append("itemid", itemid);
				formdata.append("price", price);
				ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillitemdata,OnErrorfillitemdata);

				function Onsuccessfillitemdata(content)
				{
					var JsonData = JSON.stringify(content);
					var resultdata = jQuery.parseJSON(JsonData);
					
					if(resultdata.status==0)
					{
						alertify(resultdata.message,0);
					}
					else if(resultdata.status==1)
					{
						$("#tblitemdata tr").each(function(){
							var trtmp = $(this).attr('data-index');
							var tblitemid = $('#tblitemid'+trtmp).val();
						
							if(tblitemid == itemid)
							{
								$(this).remove();
							}
						});

						var trtmp = $('#tblitemdata tr:last').attr('data-index') | 0;
						trtmp = parseInt(trtmp+1);

						var tbldata="";	
						tbldata+='<tr data-index="'+trtmp+'">';
						tbldata+='<td class="tbl-name">'+resultdata.itemname+'<input type="hidden" name="tblitemid[]" id="tblitemid'+trtmp+'" value="'+resultdata.id+'" /><input type="hidden" name="tblitemname[]" id="tblitemname'+trtmp+'" value="'+resultdata.itemname+'" /></td>';
						tbldata+='<td class="tbl-name">'+resultdata.strvalidityduration+'<input type="hidden" name="tblduraton[]" id="tblduraton'+trtmp+'" value="'+resultdata.duraton+'" /><input type="hidden" name="tbldurationname[]" id="tbldurationname'+trtmp+'" value="'+resultdata.durationname+'" /><input type="hidden" name="tblstrvalidityduration[]" id="tblstrvalidityduration'+trtmp+'" value="'+resultdata.strvalidityduration+'" /></td>';
						tbldata+='<td class="tbl-name">'+resultdata.price+'<input type="hidden" name="tblprice[]" id="tblprice'+trtmp+'" value="'+resultdata.price+'" /></td>';
						tbldata+='<td class="tbl-name"><span class="tbltaxabletext'+trtmp+'">'+ resultdata.taxableamt+'</span></td>';
						tbldata+='<td class="tbl-name"><span class="tbltaxtext'+trtmp+'">'+ resultdata.igsttaxamt+'</span></td>';
						tbldata+='<td class="tbl-name"><span class="tblfinalamounttext'+trtmp+'">'+ resultdata.finalprice+'</span><input type="hidden" name="tblfinalprice[]" id="tblfinalprice'+trtmp+'" value="'+resultdata.finalprice+'" /></td>';
						tbldata+='<input type="hidden" name="tbltaxtype[]" id="tbltaxtype'+trtmp+'" value="'+resultdata.taxtype+'" />';
						tbldata+='<input type="hidden" name="tbltaxtypename[]" id="tbltaxtype'+trtmp+'" value="'+resultdata.taxtypename+'" />';
						tbldata+='<input type="hidden" name="tblsgst[]" id="tblsgst'+trtmp+'" value="'+resultdata.sgst+'" />';
						tbldata+='<input type="hidden" name="tblcgst[]" id="tblcgst'+trtmp+'" value="'+resultdata.cgst+'" />';
						tbldata+='<input type="hidden" name="tbligst[]" id="tbligst'+trtmp+'" value="'+resultdata.igst+'" />';
						tbldata+='<input type="hidden" name="tbltaxable[]" id="tbltaxable'+trtmp+'" value="'+resultdata.taxableamt+'" />';
						tbldata+='<input type="hidden" name="tbligsttaxamt[]" id="tbligsttaxamt'+trtmp+'" value="'+resultdata.igsttaxamt+'" />';
						tbldata+='<input type="hidden" name="tblsgsttaxamt[]" id="tblsgsttaxamt'+trtmp+'" value="'+resultdata.sgsttaxamt+'" />';
						tbldata+='<input type="hidden" name="tblcgsttaxamt[]" id="tblcgsttaxamt'+trtmp+'" value="'+resultdata.cgsttaxamt+'" />';
						tbldata+='<input type="hidden" name="tbltype[]" id="tbltype'+trtmp+'" value="'+resultdata.type+'" />';
						tbldata+='<input type="hidden" name="tblimage[]" id="tblimage'+trtmp+'" value="" />';
						tbldata+='<input type="hidden" name="tbliconimg[]" id="tbliconimg'+trtmp+'" value="" />';
						tbldata+='<input type="hidden" name="tblcouponamount[]" id="tblcouponamount'+trtmp+'" value="0" />';
					
						tbldata+='<td class="tbl-action">';
						tbldata+='<a href="javascript:void(0);" class="btn-tbl text-danger m-0 rounded-circle removetblprice" id="removetblprice" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="bi bi-x-lg"></i></a>';
						tbldata+='</td>';
						tbldata+='</tr>';

						$('#tblitemdata').append(tbldata);

						$('#couponapply').val(0);
						$('#couponid').val('');
						$('#couponcode').val('');
						$('#coupontype').val(0);
						$('#couponamount').val(0);
						$('#couponpercent').val(0);
						$('#ccode').val('');

						$('#removecoupon').addClass('d-none');	
						$('.couponapplyDiv').removeClass('d-none');
						$('#ccode').removeAttr("readonly");

						amountcalculation();
	
					}
				}
				
				function OnErrorfillitemdata(content)
				{ 
					ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
				}

			}
			else
			{
				alertify(errormsgarr['required'],0);
			}
		});

		//Remove Row in Table
		$('body').on('click','.removetblprice', function () {           
			$(this).parent().parent().remove();

			$('#couponapply').val(0);
			$('#couponid').val('');
			$('#couponcode').val('');
			$('#coupontype').val(0);
			$('#couponamount').val(0);
			$('#couponpercent').val(0);
			$('#ccode').val('');

			$('#removecoupon').addClass('d-none');	
			$('.couponapplyDiv').removeClass('d-none');
			$('#ccode').removeAttr("readonly");
			
			amountcalculation(); 
		});
		/***************** End For Add Pricing *****************/

		
		/***************** Start For Amount Calculation *****************/
		function amountcalculation()
		{
			var couponapply = $('#couponapply').val();
			var couponid = $('#couponid').val();
			var couponcode = $('#couponcode').val();
			var coupontype = $('#coupontype').val();
			var couponamount = $('#couponamount').val();
			var couponpercent = $('#couponpercent').val();

			var totalamount = 0;
			var totaltaxable = 0;
			var totaltax = 0;
			var totalpaidamount = 0;
			if(couponapply == 1)
			{
				var totaltaxableamt = 0;
				var totalamt = 0;
				$("#tblitemdata tr").each(function(index){
					var trtmp = $(this).attr('data-index');
					var tbltaxable=parseFloat($('#tbltaxable'+trtmp).val());
					var tblfinalprice=parseFloat($('#tblfinalprice'+trtmp).val());

					totalamt+=parseFloat(tblfinalprice);
					totaltaxableamt+=parseFloat(tbltaxable);
				});
				var disamt = couponamount;

				$("#tblitemdata tr").each(function(index){
					var trtmp = $(this).attr('data-index');
					var price=parseFloat($('#tblprice'+trtmp).val());
					var igst=parseFloat($('#tbligst'+trtmp).val());
					var igsttaxamt=parseFloat($('#tbligsttaxamt'+trtmp).val());
					var taxable=parseFloat($('#tbltaxable'+trtmp).val());
					var finalprice=parseFloat($('#tblfinalprice'+trtmp).val());

					var sing_percent = parseFloat((taxable/totaltaxableamt)*100).toFixed(2);
					var sing_discamt = parseFloat((disamt*sing_percent/100)).toFixed(2);

					var new_taxable = parseFloat((taxable-sing_discamt)).toFixed(2);
					var new_igstamt = parseFloat((new_taxable*igst)/100).toFixed(2);
					var new_cgstamt = parseFloat((new_igstamt/2)).toFixed(2);
					var new_sgstamt = parseFloat((new_igstamt/2)).toFixed(2);
					var new_finalprice = parseFloat(parseFloat(new_taxable) + parseFloat(new_igstamt)).toFixed(2); 

		
					$('#tbltaxable'+trtmp).val(new_taxable);
					$('#tbligsttaxamt'+trtmp).val(new_igstamt);
					$('#tblsgsttaxamt'+trtmp).val(new_sgstamt);
					$('#tblcgsttaxamt'+trtmp).val(new_cgstamt);
					$('#tblfinalprice'+trtmp).val(new_finalprice);	
					$('.tbltaxabletext'+trtmp).text(new_taxable);
					$('.tbltaxtext'+trtmp).text(new_igstamt);
					$('.tblfinalamounttext'+trtmp).text(new_finalprice);
					$('#tblcouponamount'+trtmp).val(sing_discamt);

					totalamount+=parseFloat(price);	
					totaltaxable+=parseFloat(new_taxable);	
					totaltax+=parseFloat(new_igstamt);	
					totalpaidamount+=parseFloat(new_finalprice);	
	

				});	
			}
			else
			{
				$("#tblitemdata tr").each(function(index){
					
					var trtmp = $(this).attr('data-index');

					var taxableamt=0;
					var taxamt=0;
					var finalprice=0;
					var taxtype=parseFloat($('#tbltaxtype'+trtmp).val());
					var price=parseFloat($('#tblprice'+trtmp).val());
					var igst=parseFloat($('#tbligst'+trtmp).val());
					 
					if(taxtype == 1)  //For Exclusive Tax
					{
						taxableamt=parseFloat(price);
						taxamt=parseFloat((price*igst/100)).toFixed(2);
						finalprice=parseFloat(taxableamt)+parseFloat(taxamt);
					}
					else if(taxtype == 2)  //For Inclusive Tax
					{
					
						taxableamt=parseFloat((price*100)/(100+igst)).toFixed(2);
						taxamt=parseFloat((price-taxableamt)).toFixed(2);
						finalprice=parseFloat(price);
					}

					$('#tbltaxable'+trtmp).val(parseFloat(taxableamt).toFixed(2));
					$('#tbligsttaxamt'+trtmp).val(parseFloat(taxamt).toFixed(2));
					$('#tblsgsttaxamt'+trtmp).val(parseFloat(taxamt/2).toFixed(2));
					$('#tblcgsttaxamt'+trtmp).val(parseFloat(taxamt/2).toFixed(2));
					$('#tblfinalprice'+trtmp).val(parseFloat(finalprice).toFixed(2));	

					$('.tbltaxabletext'+trtmp).text(parseFloat(taxableamt).toFixed(2));
					$('.tbltaxtext'+trtmp).text(parseFloat(taxamt).toFixed(2));
					$('.tblfinalamounttext'+trtmp).text(parseFloat(finalprice).toFixed(2));

					totalamount+=parseFloat(price);	
					totaltaxable+=parseFloat(taxableamt);	
					totaltax+=parseFloat(taxamt);	
					totalpaidamount+=parseFloat(finalprice);		

				});	
			}

			$('#totalamount').val(parseFloat(totalamount).toFixed(2));
			$('#totaltaxable').val(parseFloat(totaltaxable).toFixed(2));
			$('#totaltax').val(parseFloat(totaltax).toFixed(2));
			$('#totalcouponamount').val(parseFloat(couponamount).toFixed(2));
			$('#totalpaidamount').val(parseFloat(totalpaidamount).toFixed(2));
		}

		/***************** End For Amount Calculation *****************/


		/********** Start For Apply Coupon **********/

		//Apply Coupon Code
		$('body').on('click','#btncouponapply',function(){
			$('#couponapply').val(0);
			$('#couponid').val('');
			$('#couponcode').val('');
			$('#coupontype').val(0);
			$('#couponamount').val(0);
			$('#couponpercent').val(0);
			$('#removecoupon').addClass('d-none');	
			$('.couponapplyDiv').removeClass('d-none');
			$('#ccode').removeAttr("readonly");

			amountcalculation();
			checkcouponcode();
		});
		
		//Check Coupon Code
		function checkcouponcode()
		{
			var memberid = $('#memberid').val();
			var couponcode = $('#ccode').val();
	
			var totaltaxableamt=0;
			var totalamt=0;
			$('#tblitemdata tr').each(function(){
				var trtmp = $(this).attr('data-index');
				var tbltaxable = $('#tbltaxable'+trtmp).val();
				var tblfinalprice = $('#tblfinalprice'+trtmp).val();

				totaltaxableamt+=parseFloat(tbltaxable);
				totalamt+=parseFloat(tblfinalprice);
			});
			
		
			var pagename=getpagename();
			var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'JSON'};
			formdata = new FormData();
			formdata.append("action", "applycoupon");
			formdata.append("memberid", memberid);
			formdata.append("couponcode", couponcode);
			formdata.append("totalamt", totalamt);
			formdata.append("totaltaxableamt", totaltaxableamt);
			ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesscouponapply,OnErrorcouponapply);

			function Onsuccesscouponapply(content)
			{
				var JsonData = JSON.stringify(content);
				var resultdata = jQuery.parseJSON(JsonData);
				
				if(resultdata.status==0)
				{
					alertify(resultdata.message,0);
					$('#couponapply').val(0);
					$('#couponid').val('');
					$('#couponcode').val('');
					$('#coupontype').val(0);
					$('#couponamount').val(0);
					$('#couponpercent').val(0);
					//$('#ccode').val('')
					$('#removecoupon').addClass('d-none');
					$('.couponapplyDiv').removeClass('d-none');
					$('#ccode').removeAttr("readonly");
				}
				else if(resultdata.status==1)
				{
					alertify(resultdata.message,1);

					//$('#ccode').val('')
					$('#couponapply').val(resultdata.couponapply);
					$('#couponid').val(resultdata.couponid);
					$('#couponcode').val(resultdata.couponcode);
					$('#coupontype').val(resultdata.coupontype);
					$('#couponamount').val(resultdata.couponamount);
					$('#couponpercent').val(resultdata.couponpercent);
				
					
					if(resultdata.couponapply > 0)
					{
						$('#removecoupon').removeClass('d-none');
						$('.couponapplyDiv').addClass('d-none');
						$('#ccode').attr("readonly","readonly");
					}
					else
					{
						$('#removecoupon').addClass('d-none');
						$('.couponapplyDiv').removeClass('d-none');
						$('#ccode').removeAttr("readonly");
					}
				
				}
				amountcalculation()
			}

			function OnErrorcouponapply(content)
			{ 
				ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
			}

		}
		
		//Remove Coupon Code
		$('body').on('click','#removecoupon',function(){

			$('#ccode').val('')
			$('#couponapply').val(0);
			$('#couponid').val('');
			$('#couponcode').val('');
			$('#coupontype').val(0);
			$('#couponamount').val(0);
			$('#couponpercent').val(0);
			$('#removecoupon').addClass('d-none');	
			$('.couponapplyDiv').removeClass('d-none');
			$('#ccode').removeAttr("readonly");

			amountcalculation();
			alertify(errormsgarr['couponremove'],1);
		});
		
		/********** End For Apply Coupon **********/

		$('body').on('change','.paymenttype',function(){
			var paymenttype = $(this).val();
			$('#referenceno').val('');
			if(paymenttype == 1)
			{
				$('.referenceDiv').addClass('d-none');
			}
			else
			{
				$('.referenceDiv').removeClass('d-none');
			}
    	});

		
		//View Order Data Start
        function vieworderdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "vieworderdata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessvieworderdata,OnErrorvieworderdata); 
        }

		function Onsuccessvieworderdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-exl');
			$('#GeneralModal #GeneralModalLabel').html('Invoice Details');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorvieworderdata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }



		//View Order Attribute Data Start
        function vieworderattributedata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "vieworderattributedata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewattributedata,OnErrorviewattributedata); 
        }

		function Onsuccessviewattributedata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#SubGeneralModal').modal('show');
			$('#SubGeneralModal .modal-dialog').addClass('modal-exl');
			$('#SubGeneralModal #SubGeneralModalLabel').html('Invoice Details');
			$('#SubGeneralModal #SubGeneraldata').html(resultdata.data);
        }
        
        function OnErrorviewattributedata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }

		if($('#ordermasterForm').length){		
            $('#ordermasterForm').validate({
                rules:{
                    memberid:{
                        required: true,	
                    },
					referenceno:{
                        required: true,				
                    },
                },messages:{
                    memberid:{
                        required: "Member is required",					
                    },
                    referenceno:{
                        required: "Reference no is required",					
                    },
                },
                submitHandler: function(form){
					var itemtbllength=$('#tblitemdata tr').length;
					if(itemtbllength > 0)
					{
						$('#loaderprogress').show();
						var pagename=getpagename();
						var useraction=$('#formevent').val();
						formdata = new FormData(form);
						formdata.append("action", "insertordermaster");
						var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false,responsetype: 'HTML'};
						ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit); 
					}
					else
					{
						alertify(errormsgarr['membershiperror'],0);
						return false;
					}
			    },
            });
        }



		//Regenerate Order Invoice
        function regenerateorderinvoice(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "regenerateorderinvoice");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessregenerateorderinvoice,OnErrorregenerateorderinvoice); 
        }

		function Onsuccessregenerateorderinvoice(content)
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
				
				listdata();
			}
        }
        
        function OnErrorregenerateorderinvoice(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }


		//Sync SAP Order Data
        function syncsaporderdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "syncsaporderdata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesssyncsaporderdata,OnErrorsyncsaporderdata); 
        }

		function Onsuccesssyncsaporderdata(content)
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
			}
        }
        
        function OnErrorsyncsaporderdata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }

	</script>	