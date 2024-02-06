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
                                        
                                    <li class="tbl-search">
                                        <div class="input-group">
                                            <input type="text" name="filter" id="filter" class="form-control control-append" placeholder="Search...">
                                            <div class="input-group-append">
                                                <label class="input-group-text" for="selectBrandDate" id="btnfilter" name="btnfilter"><i class="fal fa-search"></i></label>
                                            </div>
                                        </div>
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
                                                <th class="tbl-name sorting_asc sorting" data-th="name">Name</th>
                                                <th class="tbl-name sorting" data-th="email">Email</th>
                                                <th class="tbl-name sorting" data-th="mobile">Mobile Number</th>
                                                <th class="tbl-name sorting" data-th="timestamp">Entry Date Time</th>
                                                
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

        </div>
        <!-- CONTENT AREA -->


    </div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            
        });


        //View Contact US Data Start
        function viewcontactusdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "viewcontactusdata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewcontactusdata,OnErrorviewcontactusdata); 
        }

		function Onsuccessviewcontactusdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-lg');
			$('#GeneralModal #GeneralModalLabel').html('Contact US Details');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorviewcontactusdata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
       

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->




    
