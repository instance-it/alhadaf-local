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
                                        <?php
										if((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
										{
											?>
											<li class="delete-selected tbl-check d-none">
											<!-- <a href="javascript:void(0);" class="btn btn-primary m-0 " data-toggle="modal" data-target="#modalConfirmation"><i class="bi bi-trash"></i></a> -->
												<a href="javascript:void(0);" class="btn btn-primary m-0 btnmultitrash" data-toggle="modal" ><i class="bi bi-trash"></i></a>
											</li>
											<li>
												<div class="dropdown table-setting">
													<button class="dropdown-toggle btn btn-primary" type="button" id="tableSetting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Bulk Delete"><i class="bi bi-check2-circle"></i></button>
													<div class="dropdown-menu" aria-labelledby="tableSetting">
														<a class="dropdown-item select-bulk-delete" href="javascript:void(0);"><i class="bi bi-trash"></i> Bulk Delete</a>
													</div>
												</div>
											</li>
											<?php
										}
                                    ?>
                                    <li class="tbl-search">
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
                                            <li>
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
                                                <th class="tbl-name sorting_asc sorting" data-th="storename">Store Name</th>
                                                <th class="tbl-name sorting_asc sorting" data-th="personname">Person Name</th>
                                                <th class="tbl-name sorting" data-th="contact">Contact No</th>
                                                <th class="sorting" data-th="email">Email</th>
                                                <th class="tbl-name sorting" data-th="state">State</th>
                                                <th class="tbl-name sorting" data-th="city">City</th>
                                                <?php 
													if((sizeof($Pagerights)>0 ? $Pagerights->getEditright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
													{
                                                        echo '<th class="tbl-name">Is Active</th>';
                                                    }
                                                ?>
                                                
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

            <!-- rightSidebar Start -->
            <div class="col-12 p-4" id="rightSidebar">
                <div class="right-sidebar-content">
                    <div class="row">   
                        <div class="col-12 mb-4 sidebar-header">
                            <div class="row">
                                <div class="col">
                                    <h5 class="my-2"><span class="formnamelbl"></span></h5>
                                </div>
                                <div class="col-auto pl-0">
                                    <a href="javascript:void(0);" class="btn btn-danger m-0 rounded-circle btn-close-right-sidebar" id="btnCloseRightSidebar" data-toggle="tooltip" data-placement="top" title="<?php echo $config->getCloseSidebar(); ?>" data-original-title="<?php echo $config->getCloseSidebar(); ?>"><i class="fas fa-times"></i></a>
                                </div>
                            </div>
                        </div>  
                        
                        <form class="text-left validate-form" id="storeForm" method="post" action="storemaster.php" enctype="multipart/form-data">
                            <input type="hidden" id="formevent" name="formevent" value='addright'>
                            <input type="hidden" id="id" name="id" value=''>
                            
                            <div class="col-12 layout-spacing">
                                <div class="widget">
                                    <div class="widget-content">
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Store/Counter Name <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="Store/Counter Name" id="storename" name="storename">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Person Name <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <input type="text" class="form-control" placeholder="Person Name" id="empname" name="empname">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Mobile Number 1 <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                <input type="text" class="form-control" placeholder="Mobile Number 1" id="mobilenumber1" maxlength="15" onkeypress="numbonly(event)"  name="mobilenumber1">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Mobile Number 2</label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                <input type="text" class="form-control" placeholder="Mobile Number 2" id="mobilenumber2" maxlength="15" onkeypress="numbonly(event)"  name="mobilenumber2">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Email Id <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-lg-3">
                                                    <input type="email" class="form-control" placeholder="Email Id" id="emailid" name="emailid">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-4">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">Address <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-sm-3">
                                                    <textarea type="text" class="form-control" placeholder="Address" id="address" name="address"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">State <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-lg-3">
                                                    <select class="form-control selectpicker" data-live-search="true" id="stateid" name="stateid" data-size="10" data-dropup-auto="false">
                                                        <option value="">Select State</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                                <div class="input-group mb-0">
                                                    <label class="mb-1">City <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="input-group mb-3 mb-lg-3">
                                                    <select class="form-control selectpicker" data-live-search="true" id="cityid" name="cityid" data-size="10" data-dropup-auto="false">
                                                        <option value="">Select City </option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 layout-spacing">
                                <div class="widget min-height-100">
                                    <div class="widget-content">
                                        <div class="row">
                                            <div class="col-12 ml-auto">
                                                <div class="input-group mb-0">
                                                    <button type="submit" class="btn btn-primary m-0  ml-auto" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
                                                    <button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>       
                    </div>
                </div>
            </div>
            <!-- rightSidebar End -->
        </div>
        <!-- CONTENT AREA -->
    </div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            
        });

        

        function resetdata()
        {
            $("#storeForm").validate().resetForm();
            $('#storeForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#tbldata').html('');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');
			$('.passwordDiv').removeClass('d-none');


            $('.clslogindiv').addClass('d-none');

            Edittimeformnamechange(2); 	
            fillstate();
			
        } 

        function fillstate()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillstate");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillstate,OnErrorSelectpicker); 
        }

        function Onsuccessfillstate(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#stateid').html(resultdata.data);
            $('#stateid').val('<?php echo $config->getStateId(); ?>');
            $('#stateid').selectpicker('refresh');	
            fillcity();	
        }

        $('body').on('change','#stateid', function () {
            fillcity();	
        });

        function fillcity()
        {
            var pagename=getpagename();
            var stateid=$('#stateid').val();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillcity");
            formdata.append("stateid",stateid);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcity,OnErrorSelectpicker);
        }

        function Onsuccessfillcity(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#cityid').html(resultdata.data);
            $('#cityid').selectpicker('refresh');	
        }


		// Fill Username
		$('#mobilenumber1').on('input',function(){
			var contactno=$(this).val();
			$('#username').val(contactno);
		});

        //Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "editstoremaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessedtstoremaster,OnErroredtstoremaster); 
        }

        function Onsuccessedtstoremaster(content)
        {
            var JsonData = JSON.stringify(content);
            var resulteditdata = jQuery.parseJSON(JsonData);

            $('#id').val(resulteditdata.id);
            $('#storename').val(resulteditdata.storename);
            $('#empname').val(resulteditdata.personname);
            $('#mobilenumber1').val(resulteditdata.contact);
            $('#mobilenumber2').val(resulteditdata.mobilenumber2);
            $('#emailid').val(resulteditdata.email);
            $('#address').val(resulteditdata.address);

            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillstate");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillstate,OnErrorSelectpicker); 
            
            function Onsuccessfillstate(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata = jQuery.parseJSON(JsonData);
                $('#stateid').html(resultdata.data);
                $('#stateid').val(resulteditdata.stateid);
                $('#stateid').selectpicker('refresh');	
            

                var pagename=getpagename();
                var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype:'HTML'};
                formdata = new FormData();
                formdata.append("action", "fillcity");
                formdata.append("stateid",resulteditdata.stateid);
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillcity,OnErrorSelectpicker);
            }

            function Onsuccessfillcity(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata = jQuery.parseJSON(JsonData);
                $('#cityid').html(resultdata.data);
                $('#cityid').val(resulteditdata.cityid);
                $('#cityid').selectpicker('refresh');	
            }


            $('#username').val(resulteditdata.username);
            $('.selectpicker').selectpicker('refresh');
            
            $('.passwordDiv').addClass('d-none');

            $('#formevent').val('editright');       
            $('#btnsubmit').text('<?php echo $config->getUpdateSidebar(); ?>');     

            //Open Side bar
            Edittimeformnamechange(1);
            $("#rightSidebar").toggleClass("active-right-sidebar"); 
            $('.overlay').addClass('show');
            $("body").addClass("overflow-hidden");
        }

        function OnErroredtstoremaster(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
        //Edit End

        //change person status
        function changestorestatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changestorestatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangestorestatus,OnErrorchangestorestatus); 
        }

        function Onsuccesschangestorestatus(data)
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
        function OnErrorchangestorestatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }



        //View Details Start
        function viewstoredetaildata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "viewstoredata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewstoredata,OnErrorviewstoredata); 
        }

		function Onsuccessviewstoredata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-xl');
			$('#GeneralModal #GeneralModalLabel').html('Store/Counter Details');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorviewstoredata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }

        //user type change
        $('#utypeid').on('change', function(){
            $('.clslogindiv').addClass('d-none');
          
            var aryisweblogin = [];
            var aryisapplogin = [];
            
            var utypeid = $('#utypeid').val();
            if(utypeid != null){
                for(var i=0;i<utypeid.length;i++)
                {
                    var isweblogin=$('#utypeid option[value='+utypeid[i]+']').attr('data-isweblogin');
                    var isapplogin=$('#utypeid option[value='+utypeid[i]+']').attr('data-isapplogin');
                    aryisweblogin.push(isweblogin);
                    aryisapplogin.push(isapplogin);
            
                }
                
                if($.inArray('1', aryisweblogin)>=0 || $.inArray('1', aryisapplogin)>=0)
                {
                    $('.clslogindiv').removeClass('d-none');
                    var formevent = $('#formevent').val();
                    if(formevent == 'editright')
                    {
                        $('.passwordDiv').addClass('d-none');
                    }
                    else
                    {
                        $('.passwordDiv').removeClass('d-none');
                    }
                    
                }
                else if($.inArray('1', aryisweblogin) <=0 || $.inArray('1', aryisapplogin) <=0)
                {
                    $('.clslogindiv').addClass('d-none');
                    
                }
                
            }
        })

        if($('#storeForm').length){		
            $('#storeForm').validate({
                rules:{
					'utypeid[]':{
                        required: true,					
                    },
                    storename:{
                        required: true,					
                    },
					empname:{
                        required: true,					
                    },
                    mobilenumber1:{
                        required: true,	
						//mobileno:true,
                        maxlength:15				
                    },
					mobilenumber2:{
						//mobileno:true,
                        maxlength:15				
                    },
					emailid:{
                        required: true,	
						email: true				
                    },
                    address:{
                        required: true,					
                    },
                    stateid:{
                        required: true,					
                    },
                    cityid:{
                        required: true,					
                    },
                    pincode:{
                        required: true,
                        numberonly:true,
                        maxlength:6							
                    },
					gender:{
                        required: true,					
                    },
                    persondob:{
                        required: true,					
                    },
                    username:{
                        required: true,					
                    },
                    password:{
                        required:true,		
                    },
                    cpassword:{
                        required:true,
						equalTo:'#password',			
                    },
                    pannumber:{
                        pancardno:true,
                        maxlength:10		
                    }
                },messages:{
					'utypeid[]':{
                        required: "User type is required",						
                    },
                    storename:{
                        required: "Store/Counter name is required",				
                    },
					empname:{
                        required: "Person name is required",				
                    },
                    mobilenumber1:{
                        required: "Mobile number 1 is required",					
                    },
					mobilenumber2:{
						numberonly:"Mobile number 2 is required",						
                    },
					emailid:{
                        required: "Email is required",				
                    },
                    address:{
                        required: "Address is required",					
                    },
                    stateid:{
                        required: "State is required",					
                    },
                    cityid:{
                        required: "City is required",					
                    },
                    username:{
                        required: "Username is required",									
                    },
                    password:{
                        required: "Password is required",						
                    },
                    cpassword:{
                        required:"Confirm password is required",
						equalTo:"Confirm password must be same as password"			
                    },
                },
                submitHandler: function(form){

                    $('#loaderprogress').show();
                    var pagename=getpagename();
                    var useraction=$('#formevent').val();
                    formdata = new FormData(form);
                    formdata.append("action", "insertstoremaster");
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false,responsetype: 'HTML'};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit); 
			    },
            });
        }
       

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->




    
