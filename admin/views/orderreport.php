<?php 
require_once dirname(__DIR__, 1).'/config/init.php';
?>
    
<div class="layout-px-spacing">

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-12 col-xl-12 mb-3">
                    <div class="widget">
                        <div class="widget-content">
                            <form class="text-left validate-form" id="orderreportForm" method="post" target="_blank" action="<?php echo $config->getReporturl(); ?>orderreport" enctype="multipart/form-data">
                                <div class="row">
                                    
                                    <div class="col-2">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Category <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="categoryid" name="categoryid" data-size="10" data-dropup-auto="false">
                                               
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Sub Category <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="subcategoryid" name="subcategoryid" data-size="10" data-dropup-auto="false">
                                               
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Item <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="itemid" name="itemid" data-size="10" data-dropup-auto="false">
                                               
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Member <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="memberid" name="memberid" data-size="10" data-dropup-auto="false">
                                               
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Payment Type <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="paymenttype" name="paymenttype" data-size="10" data-dropup-auto="false">
                                               <option value="%">All</option>
                                               <option value="1">Cash</option>
                                               <option value="2">Online Payment</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-2">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">From Date Time<span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="text" name="fromdate" id="fromdate" class="form-control datepicker fromdate" placeholder="From Date Time" />
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">To Date Time <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                        <input type="text" name="todate" id="todate" class="form-control datepicker todate" placeholder="To Date Time" />
                                        </div>
                                    </div>

                                    <div class="col-1">
                                        <div class="input-group mb-0 mt-4">
                                            <a href="javascript:void(0)" onclick="resetdatefilter()" data-toggle="tooltip" data-title="Reset Date"><i class="bi bi-arrow-repeat" style="font-size: 20px;"></i></a>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="input-group mb-3 mb-lg-3 mt-4">
                                            <div class="custom-control custom-checkbox m-0">
                                                <input type="checkbox" class="custom-control-input d-none" id="withitemdetail" name="withitemdetail" value="1" >
                                                <label class="custom-control-label mb-0" for="withitemdetail">With Item Detail</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="input-group mb-3 mb-lg-3 mt-4">
                                            <div class="custom-control custom-checkbox m-0">
                                                <input type="checkbox" class="custom-control-input d-none" id="withfulldetail" name="withfulldetail" value="1" >
                                                <label class="custom-control-label mb-0" for="withfulldetail">With Full Detail</label>
                                            </div>
                                        </div>
                                    </div>

                                    

                                    <div class="col-2">
                                        <div class="ml-auto mt-4">
                                            <div class="input-group mb-0">
                                                <button type="submit" class="btn btn-primary m-0" id="btnsubmit"><?php echo $config->getGeneratereport(); ?></button>
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
<!-- CONTENT AREA -->
</div>

    <script>
        $(document).ready(function () 
        {
            $('.selectpicker').selectpicker('refresh');
        
            fillcategory();
            fillmember();
        });

        

        /*-----------------------Date Filter--------------------------*/
        $('.todate').bootstrapMaterialDatePicker
        ({
            weekStart: 0, 
			format: 'YYYY-MM-DD HH:mm:ss', 
			time: true,
            switchOnClick: true,
            okText: "",
			
        }).on('change', function(e, date)
        {
            $(this).removeClass('error');
            $(this).parent('div').removeClass('error');  
	        $(this).parent().find('label.error').html('');
        });

        $('.fromdate').bootstrapMaterialDatePicker
        ({
            weekStart: 0, 
			format: 'YYYY-MM-DD HH:mm:ss',  
			time: true,
            switchOnClick: true,
            okText: "",
			//minDate: moment(), // Current day

        }).on('change', function(e, date)
        {
            $('.todate').bootstrapMaterialDatePicker('setMinDate', date);
            $('.todate').val("");
			$(this).removeClass('error');
            $(this).parent('div').removeClass('error');  
	        $(this).parent().find('label.error').html('');
        });
		/*-----------------------Date Filter--------------------------*/
        


        function resetdatefilter()
        {
            $('#fromdate').val('');
            $('#todate').val('');

            $(".todate").datepicker( "option", "minDate",'' );
        }

        
        function resetdata()
        {
            $("#orderreportForm").validate().resetForm();
            $('#orderreportForm').trigger("reset");
            fillcategory();
            fillmember();
        }


        //Fill Category
		function fillcategory()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcategory");
			formdata.append("selectoption", 0);
            formdata.append("isall", 1);
            formdata.append("ismshippkgcourse", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcategory,OnErrorSelectpicker);
        }

        function Onsuccessfillcategory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#categoryid').html(resultdata.data);
            $('#categoryid').selectpicker('refresh');

            fillsubcategory();
        }


        //Change Event Of Category
        $('#categoryid').on('change',function(){
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
			formdata.append("selectoption", 0);
            formdata.append("isall", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillsubcategory,OnErrorSelectpicker);
        }

        function Onsuccessfillsubcategory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#subcategoryid').html(resultdata.data);
            $('#subcategoryid').selectpicker('refresh');

            fillitem();
        }


        //Change Event Of Sub Category
        $('#subcategoryid').on('change',function(){
            fillitem();
        });
        

        function fillitem()
        {
            var categoryid = $('#categoryid').val();
            var subcategoryid = $('#subcategoryid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillreportitem");
            formdata.append("categoryid", categoryid);
            formdata.append("subcategoryid", subcategoryid);
			formdata.append("isall", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillitem,OnErrorSelectpicker);
        }

        function Onsuccessfillitem(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#itemid').html(resultdata.data);
            $('#itemid').selectpicker('refresh');
        }

        //Fill Member
		function fillmember()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillmember");
			formdata.append("isall", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillmember,OnErrorSelectpicker);
        }

        function Onsuccessfillmember(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#memberid').html(resultdata.data);
            $('#memberid').selectpicker('refresh');

        }

     
    </script>
