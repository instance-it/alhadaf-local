<?php require_once dirname(__DIR__, 1).'/config/init.php'; ?>
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-12 layout-spacing">
                <div class="widget">
                    <div class="widget-heading m-0">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3 col-lg-2 ml-auto" id="tableDataList" data-show="1" data-nextpage='0'>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control control-append rb_fromdate" placeholder="From Date"  id="rb_fromdate" name="rb_fromdate" value="<?php echo date('d/m/Y') ?>" require>
                                    <div class="input-group-append">
                                        <label class="input-group-text" for="rb_fromdate"><i class="bi bi-calendar4-week"></i></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3 col-lg-2 mr-auto" id="tableDataList" data-show="1" data-nextpage='0'>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control control-append rb_todate" placeholder="To Date"  id="rb_todate" name="rb_todate" value="<?php echo date('d/m/Y') ?>" require>
                                    <div class="input-group-append">
                                        <label class="input-group-text" for="rb_todate"><i class="bi bi-calendar4-week"></i></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 layout-spacing">
                <div class="row ml-0" id="rangebookingdata">
                
                    
                        
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {

             //Get Range Bookign Time Slot Data
             getrangebookingtimeslot();
            
        });

        $('.rb_fromdate').bootstrapMaterialDatePicker
        ({
            weekStart: 0, 
			format: 'DD/MM/YYYY', 
			time: false,
            switchOnClick: true,
            okText: "",
			//minDate: moment(), // Current day

        }).on('change', function(e, date)
        {
            $('.rb_todate').bootstrapMaterialDatePicker('setMinDate', date);
            $('.rb_todate').val("");
			$(this).removeClass('error');
            $(this).parent('div').removeClass('error');  
	        $(this).parent().find('label.error').html('');

            //Get Range Bookign Time Slot Data
            getrangebookingtimeslot();
        });


        $('.rb_todate').bootstrapMaterialDatePicker
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

            //Get Range Bookign Time Slot Data
            getrangebookingtimeslot();
        });


        //Get Range Bookign Time Slot Data
        function getrangebookingtimeslot()
        {
            var rb_fromdate = $('#rb_fromdate').val();
            var rb_todate = $('#rb_todate').val();

            if(rb_fromdate && rb_todate)
            {
                var pagename=getpagename();
                var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};
                formdata = new FormData();
                formdata.append("action", "getrangebookingtimeslot");
                formdata.append("rb_fromdate", rb_fromdate); 
                formdata.append("rb_todate", rb_todate); 
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessrangebookingtimeslot,OnErrorrangebookingtimeslot);
            }
            else
            {
                $('#rangebookingdata').html('');
            }    
        } 

        function Onsuccessrangebookingtimeslot(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            
            $('#rangebookingdata').html(resultdata.data);

            //Click Event Of View Booked Member Details
            $('.btnbookedmemberdetail').on('click',function(){
                
                var type = $(this).attr('data-type');
                var fromtime = $(this).attr('data-fromtime');
                var totime = $(this).attr('data-totime');

                viewbookedmemberdetail(type,fromtime,totime);
            });

            //View Booked Member Details Start
            function viewbookedmemberdetail(type,fromtime,totime)
            {
                var rb_fromdate = $('#rb_fromdate').val();
                var rb_todate = $('#rb_todate').val();

                var pagename=getpagename();
                var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

                formdata = new FormData();
                formdata.append("action", "viewbookedmemberdetail");
                formdata.append("type", type);
                formdata.append("fromtime", fromtime);
                formdata.append("totime", totime);
                formdata.append("fromdate", rb_fromdate);
                formdata.append("todate", rb_todate);
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewbookedmemberdetail,OnErrorviewbookedmemberdetail); 
            }

            function Onsuccessviewbookedmemberdetail(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata = jQuery.parseJSON(JsonData);
                $('#GeneralModal').modal('show');
                $('#GeneralModal .modal-dialog').addClass('modal-exl');
                if(resultdata.type == 1)
                {
                    $('#GeneralModal #GeneralModalLabel').html('Guest Details');
                }
                else if(resultdata.type == 2)
                {
                    $('#GeneralModal #GeneralModalLabel').html('Member Details');
                }
                $('#GeneralModal #Generaldata').html(resultdata.data);
            }
            
            function OnErrorviewbookedmemberdetail(content)
            { 
                ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
            }
            
        }

        function OnErrorrangebookingtimeslot(content)
        {
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }


        


        
    </script>
