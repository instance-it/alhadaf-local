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
                            <form class="text-left validate-form" id="itemreportForm" method="post" target="_blank" action="<?php echo $config->getReporturl(); ?>itemreport" enctype="multipart/form-data">
                                <div class="row">
                                    
                                    <div class="col-3">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Category <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="categoryid" name="categoryid" data-size="10" data-dropup-auto="false">
                                               
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-3">
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
                                            <label class="mb-1">Duration <span class="text-danger">*</span></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="durationid" name="durationid" data-size="10" data-dropup-auto="false">
                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-2 websitedisplayitemdiv">
                                        <div class="input-group mb-3 mb-lg-3 mt-4">
                                            <div class="custom-control custom-checkbox m-0">
                                                <input type="checkbox" class="custom-control-input d-none" id="withwebsitedisplayitem" name="withwebsitedisplayitem" value="1" >
                                                <label class="custom-control-label mb-0" for="withwebsitedisplayitem">With Website Display Item</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-2 coursebenefitdiv">
                                        <div class="input-group mb-3 mb-lg-3 mt-4">
                                            <div class="custom-control custom-checkbox m-0">
                                                <input type="checkbox" class="custom-control-input d-none" id="withcoursebenefit" name="withcoursebenefit" value="1" >
                                                <label class="custom-control-label mb-0" for="withcoursebenefit">With Course Benefit</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-2 compositeitemdiv">
                                        <div class="input-group mb-3 mb-lg-3 mt-4">
                                            <div class="custom-control custom-checkbox m-0">
                                                <input type="checkbox" class="custom-control-input d-none" id="withcompositeitem" name="withcompositeitem" value="1" >
                                                <label class="custom-control-label mb-0" for="withcompositeitem">With Composite Item</label>
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
            fillduration();
        });
        
        function resetdata()
        {
            $("#itemreportForm").validate().resetForm();
            $('#itemreportForm').trigger("reset");

            fillcategory();
            fillduration();
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
            var iswebattribute = $('#categoryid :selected').attr('data-iswebattribute');
            var iscourse = $('#categoryid :selected').attr('data-iscourse');
            var iscompositeitem = $('#categoryid :selected').attr('data-iscompositeitem');

            $('.websitedisplayitemdiv').addClass('d-none');
            $('.coursebenefitdiv').addClass('d-none');
            $('.compositeitemdiv').addClass('d-none');

            $('#withwebsitedisplayitem').prop('checked',false);
            $('#withcoursebenefit').prop('checked',false);
            $('#withcompositeitem').prop('checked',false);

            if(iswebattribute == 1)
            {
                $('.websitedisplayitemdiv').removeClass('d-none');
            }

            if(iscourse == 1)
            {
                $('.coursebenefitdiv').removeClass('d-none');
            }

            if(iscompositeitem == 1)
            {
                $('.compositeitemdiv').removeClass('d-none');
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


        function fillduration()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillduration");
			formdata.append("selectoption", 0);
			formdata.append("istext", 1);
			formdata.append("isonce", 0);
            formdata.append("isall", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillduration,OnErrorSelectpicker);
        }

        function Onsuccessfillduration(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#durationid').html(resultdata.data);
            $('#durationid').selectpicker('refresh');
        }

     
    </script>
