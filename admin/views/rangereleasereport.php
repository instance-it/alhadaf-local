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
                            <form class="text-left validate-form" id="rangereleasereportForm" method="post" target="_blank" action="<?php echo $config->getReporturl(); ?>rangereleasereport" enctype="multipart/form-data">
                                <div class="row">
                                    
                                    <div class="col-3">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Store <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="storeid" name="storeid" data-size="10" data-dropup-auto="false">
                                               
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

                                    <div class="col-3">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Range <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="rangeid" name="rangeid" data-size="10" data-dropup-auto="false">
                                               
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Lane <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="laneid" name="laneid" data-size="10" data-dropup-auto="false">
                                               
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Status <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="status" name="status" data-size="10" data-dropup-auto="false">
                                               <option value="%">All</option>
                                               <option value="0">Assigned</option>
                                               <option value="1">Released</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">From Date Time <span class="text-danger">*</span></label>
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
                                                <input type="checkbox" class="custom-control-input d-none" id="withserviceorderdetail" name="withserviceorderdetail" value="1" >
                                                <label class="custom-control-label mb-0" for="withserviceorderdetail">With Service Order Detail</label>
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
        
            resetdata();
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
            $("#rangereleasereportForm").validate().resetForm();
            $('#rangereleasereportForm').trigger("reset");
            $('.selectpicker').selectpicker('refresh');
            fillstore();
            fillmember();
            fillrange();
        }


        //Fill Store
		function fillstore()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillempstore");
			formdata.append("selectoption", 0);
            formdata.append("isall", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillstore,OnErrorSelectpicker); 
        }

        function Onsuccessfillstore(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#storeid').html(resultdata.data);
            $('#storeid').selectpicker('refresh');	
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



         //Fill Range
		function fillrange()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "listrptrange");
            formdata.append("isall", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillrange,OnErrorSelectpicker); 
        }

        function Onsuccessfillrange(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#rangeid').html(resultdata.data);
            $('#rangeid').selectpicker('refresh');
            
            fillrangelane();
        }


        //Change Event Of Range
        $('#rangeid').on('change',function(){
            fillrangelane();
        });


        //Fill Range Lane
		function fillrangelane()
        {
            var rangeid = $('#rangeid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "listrptrangelane");
            formdata.append("rangeid", rangeid);
            formdata.append("isall", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillrangelane,OnErrorSelectpicker); 
        }

        function Onsuccessfillrangelane(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#laneid').html(resultdata.data);
            $('#laneid').selectpicker('refresh');	
        }

     
    </script>
