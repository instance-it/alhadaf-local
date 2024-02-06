<?php 
require_once dirname(__DIR__, 1).'\config\init.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),'membermaster');
?>
    
<div class="layout-px-spacing">

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-12 col-xl-12 mb-3">
                    <div class="widget">
                        <div class="widget-content">
                            <form class="text-left validate-form" id="memberfreezemshipForm" method="post" action="menuassign" enctype="multipart/form-data">
                                <input type="hidden" id="mincharge" name="mincharge" value='0'>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-12 col-lg-4">
                                                <div class="input-group">
                                                    <label class="mb-1">Membership <span class="text-danger">*</span></label>
                                                    <label class="ml-auto "></label>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <select class="form-control selectpicker" data-live-search="true" id="f_mshipid" name="f_mshipid" data-size="10" data-dropup-auto="false">
                                                        <option value="">Select Membership</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-2">
                                                <div class="input-group">
                                                    <label class="mb-1">Start Date <span class="text-danger">*</span></label>
                                                    <label class="ml-auto "></label>
                                                </div>
                                                <div class="input-group mb-3">
													<input type="text" class="form-control control-append f_startdate" placeholder="Start Date" id="f_startdate" name="f_startdate" require autocomplete="off">
													<div class="input-group-append">
														<label class="input-group-text" for="f_startdate"><i class="bi bi-calendar4-week"></i></label>
													</div>
												</div>
                                            </div>
                                            <div class="col-12 col-lg-2">
                                                <div class="input-group">
                                                    <label class="mb-1">End Date <span class="text-danger">*</span></label>
                                                    <label class="ml-auto "></label>
                                                </div>
                                                <div class="input-group mb-3">
													<input type="text" class="form-control control-append f_enddate" placeholder="End Date" id="f_enddate" name="f_enddate" require autocomplete="off">
													<div class="input-group-append">
														<label class="input-group-text" for="f_enddate"><i class="bi bi-calendar4-week"></i></label>
													</div>
												</div>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <div class="input-group">
                                                    <label class="mb-1">Description <span class="text-danger">*</span></label>
                                                    <label class="ml-auto "></label>
                                                </div>
                                                <div class="input-group mb-3">
													<textarea type="text" class="form-control f_descr" placeholder="Description" id="f_descr" name="f_descr" require></textarea>
												</div>
                                            </div>

                                            <?php 
                                            if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                            {
                                                ?>
                                                <div class="col-12 col-lg-1">
                                                    <div class="input-group">
                                                        <label class="mb-1">&nbsp;</label>
                                                        <label class="ml-auto "></label>
                                                    </div>
                                                    <div class="input-group mb-3">
                                                         <button type="submit" class="btn btn-primary m-0" id="btnAdd">Add</button>
                                                    </div>
                                                </div>
                                                <?php
                                            }
									        ?>

                                        </div>
                                    </div>

                                    
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">                                                
                                        <div class="table-responsive">
                                            <table id="tableUserRights" class="table table-bordered table-hover table-striped border-primary mb-2">
                                                <thead>
                                                    <tr>
                                                        <th class="tbl-name">Membership</th>
                                                        <th class="tbl-name">Start Date</th>
                                                        <th class="tbl-name">End Date</th>
                                                        <th class="tbl-name">Entry Datetime</th>
                                                        <th class="tbl-name">Status</th>
                                                        <th class="tbl-name text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id='tabledata'>
                                                    
                                                </tbody>
                                            </table>
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


<div class="modal fade info-modal-dialog" id="RemoveFreezeMshipModal" tabindex="-1" role="dialog" aria-labelledby="confirmedDeleteLabel" aria-hidden="true" >
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="info-body">
                    <div class="modal-logo"><img src="<?php echo $config->getCdnurl(); ?>assets/img/logo-white.png"></div>
                    <h5 class="my-3 px-3" id="syncLabel"><br>Are you sure?</h5> 
                </div>
            </div>
            <form class="form-horizontal jQueryForm" id="removeFreezeMshipForm" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id" />


                <div class="modal-footer text-center p-0">
                    <button type="button" class="col btn btn-primary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="col btn btn-primary">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            resetdata();
        });

        function resetdata()
        {
            $("#memberfreezemshipForm").validate().resetForm();
            $('#memberfreezemshipForm').trigger("reset");
            $('.selectpicker').selectpicker('refresh');

            fillmembermship();
            listmemberfreezemship();
        }


        

       
        //Start Fill Member Mship/Package
		function fillmembermship()
        {
            var memberid = getUrlVars()['mid'];
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillmembermship");
			formdata.append("selectoption",1);
            formdata.append("memberid",memberid);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillmembermship,OnErrorSelectpicker);
        }

        function Onsuccessfillmembermship(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#f_mshipid').html(resultdata.data);
            $('#f_mshipid').selectpicker('refresh');
            
        }
		//End Fill Member Mship/Package


        //Change Event Of Membership
        $('#f_mshipid').on('change',function(){
            var newexpirydate = $('#f_mshipid :selected').attr('data-nexpirydate');

            $('#f_startdate').val('');
            $('#f_enddate').val('');

            if(newexpirydate != '' && newexpirydate != null)
            {
                $('.f_startdate').bootstrapMaterialDatePicker
                ({
                    weekStart: 0, 
                    format: 'DD/MM/YYYY', 
                    time: false,
                    switchOnClick: true,
                    okText: "",
                    minDate: moment(), // Current day

                }).on('change', function(e, date)
                {
                    $('.f_enddate').bootstrapMaterialDatePicker('setMinDate', date);
                    $('.f_enddate').bootstrapMaterialDatePicker('setMaxDate', newexpirydate);
                    $('.f_enddate').val("");
                    $(this).removeClass('error');
                    $(this).parent('div').removeClass('error');  
                    $(this).parent().find('label.error').html('');
                });


                $('.f_enddate').bootstrapMaterialDatePicker
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
            }
        });


        if($('#memberfreezemshipForm').length){		
            $('#memberfreezemshipForm').validate({
                rules:{
                    f_mshipid:{
                        required: true,			
                    },
					f_startdate:{
                        required: true,			
                    },
					f_enddate:{
                        required: true,
					},
					f_descr:{
                        required: true,
					},
					
                },messages:{
                    f_mshipid:{
                        required:"Membership is required",
                    },
					f_startdate:{
                        required:"Start date is required",
                    },
					f_enddate:{
                        required:"End date is required",
                    },
					f_descr:{
                        required:"Description is required",
                    },
					
                },
                submitHandler: function(form)
                {

                    var memberid = getUrlVars()['mid'];

                    $('#loaderprogress').show();
                    var pagename=getpagename();
                    var useraction=$('#formevent').val();
                    formdata = new FormData(form);
                    formdata.append("action", "insertmemberfreezemship");
                    formdata.append("memberid", memberid);
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfreezemship,OnErrorDataSubmit)

			    },
            });
        }

        function Onsuccessfreezemship(data)
        {
            var JsonData = JSON.stringify(data);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#loaderprogress').hide();
                
            if(resultdata.status==0)
            {
                alertify(resultdata.message,0);
            }
            else if(resultdata.status==1)
            {
                alertify(resultdata.message,1);
                resetdata();
            }
        }


        //List Member Freeze Mship
        function listmemberfreezemship()
        {
			var memberid = getUrlVars()['mid'];
           

            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "listmemberfreezemship");
			formdata.append("memberid",memberid);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistmemberfreezemship,OnErrorlistmemberfreezemship);
        }

        function Onsuccesslistmemberfreezemship(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#tabledata').html(resultdata.data); 
        }

        function OnErrorlistmemberfreezemship(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }



        //View Details Start
        function viewfreezemshipdescr(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "viewfreezemshipdescr");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewfreezemshipdescr,OnErrorviewfreezemshipdescr); 
        }

		function Onsuccessviewfreezemshipdescr(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-md');
			$('#GeneralModal #GeneralModalLabel').html('Description');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorviewfreezemshipdescr(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }


        // Remove Freeze Mship 
        function removefreezemship(id)
        {
            $('#RemoveFreezeMshipModal #id').val(id);
		    $('#RemoveFreezeMshipModal').modal('show');
        }

        if($('#removeFreezeMshipForm').length)
		{		
			$('#removeFreezeMshipForm').validate({
				rules:{
				},
		        messages:{
		        },
		        submitHandler: function(form){
					var pagename=getpagename();
                    var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
                    formdata = new FormData(form);
                    formdata.append("action", "removefreezemship");
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessremovefreezemship,OnErrorremovefreezemship); 
                
		        }	
			});
		}

        function Onsuccessremovefreezemship(data)
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
                $('#RemoveFreezeMshipModal').modal('hide');  
            }
            $('#tableDataList').attr('data-nextpage',0); 
            //listmemberfreezemship();
            resetdata();

        }
        function OnErrorremovefreezemship(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
        

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
