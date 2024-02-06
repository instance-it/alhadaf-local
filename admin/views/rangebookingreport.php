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
                            <form class="text-left validate-form" id="rangebookingForm" method="post" target="_blank" action="<?php echo $config->getReporturl(); ?>rangebookingreport" enctype="multipart/form-data">
                                <div class="row">
                                    
                                    <div class="col-3">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Type <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="typeid" name="typeid" data-size="10" data-dropup-auto="false">
                                                <option value="2">Member</option>
                                                <option value="1">Guest User</option>
                                            </select>
                                        </div>
                                    </div>

                                    
                                    <div class="col-3 memberDiv">
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
                                            <label class="mb-1">From Date <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <input type="text" name="fromdate" id="fromdate" class="form-control datepicker fromdate" placeholder="From Date" />
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">To Date <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                        <input type="text" name="todate" id="todate" class="form-control datepicker todate" placeholder="To Date" />
                                        </div>
                                    </div>

                                    <div class="col-1">
                                        <div class="input-group mb-0 mt-4">
                                            <a href="javascript:void(0)" onclick="resetdatefilter()" data-toggle="tooltip" data-title="Reset Date"><i class="bi bi-arrow-repeat" style="font-size: 20px;"></i></a>
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
			format: 'DD/MM/YYYY',
			time: false,
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
			format: 'DD/MM/YYYY', 
			time: false,
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
            $("#rangebookingForm").validate().resetForm();
            $('#rangebookingForm').trigger("reset");
            $('.memberDiv').removeClass('d-none');
            fillmember();
        }


        //Change Event OF Type
        $('#typeid').on('change',function(){
            var typeid = $(this).val();

            if(typeid == 2)  //For Member
            {
                $('.memberDiv').removeClass('d-none');
            }
            else 
            {
                $('.memberDiv').addClass('d-none');
            }
        });


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
