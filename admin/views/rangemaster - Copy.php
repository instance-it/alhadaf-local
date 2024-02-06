<?php 
// require_once dirname(__DIR__, 1).'\config\config.php';
// $config = new config();
require_once dirname(__DIR__, 1).'\config\init.php';

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());
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
										<?php
											if((sizeof($Pagerights)>0 ? $Pagerights->getDelright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
											{
												?>
												<li class="delete-selected tbl-check d-none mb-2">
												<!-- <a href="javascript:void(0);" class="btn btn-primary m-0 " data-toggle="modal" data-target="#modalConfirmation"><i class="bi bi-trash"></i></a> -->
													<a href="javascript:void(0);" class="btn btn-primary m-0 btnmultitrash" data-toggle="modal" ><i class="bi bi-trash"></i></a>
												</li>
												<li class="mb-2">
													<div class="dropdown table-setting">
														<button class="dropdown-toggle btn btn-primary" type="button" id="tableSetting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bi bi-check2-circle"></i></button>
														<div class="dropdown-menu" aria-labelledby="tableSetting">
															<a class="dropdown-item select-bulk-delete" href="javascript:void(0);"><i class="bi bi-trash"></i> Bulk Delete</a>
														</div>
													</div>
												</li>
												<?php
											}
										?>
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
													
													<th class="tbl-name sorting" data-th="rangename">Range Name</th>
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
										<!-- table End -->
									</div>
								</div>
							</div>
						</div>
						<?php require_once 'pagefooter.php'; ?>
					</div>
				</div>
				<!-- rightSidebar Start -->
				<div class="col-12 p-4 md-sidebar" id="rightSidebar">
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
								<form class="row validate-form" id="rangemasterForm" method="post" action="rangemaster" enctype="multipart/form-data">                                       
									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget">
											<div class="widget-content row">
												
												<input type="hidden" id="formevent" name="formevent" value='addright'>
												<input type="hidden" id="id" name="id" value=''>												
												<div class="col-12">
													<div class="input-group">
														<label class="mb-1">Range Name <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" id="rangename" name="rangename" class="form-control" placeholder="Enter Range Name" autocomplete="off"/>
													</div>
												</div>
											</div>
										</div>
									</div>					
									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget">
											<div class="widget-content row">

												<div class="col">
													<div class="input-group">
														<label class="mb-1">Lane Name<span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="lanename" id="lanename" placeholder="Enter Lane Name" autocomplete="off" />
													</div>
												</div>
												
												<div class="col-auto">
													<div class="input-group">
														<label class="mb-1">&nbsp;</label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="button" class="form-control btn btn-primary" name="btnaddrange" id="btnaddrange" value="ADD">
													</div>
												</div>
												<div class="col-12">
													<div class="table-responsive table-scroll" style="height: 200px;overflow-y:auto;margin: 10px;padding:0px;" id="table-scroll">
														<!-- <div class="chart-loading">
															<img alt="loading-img" src="img/loading.gif">
														</div> -->
														<table class="table table-bordered main-table" id="main-table" style="font-size: 1.1em;text-align: center;">
															<thead>
																<tr class="info">
																	<th width="65%;" style="text-align: left;">Lane Name</th>
																	
																	<th width="5%;" style="text-align: center;"><i class="fa fa-trash"></i></th>
																</tr>
															</thead>
															<tbody id="tbltbody">
																
															</tbody>	
														</table>								
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="col-lg-12 col-md-12 col-12 layout-spacing d-none">
										<div class="widget">
											<div class="widget-content row">

												<div class="col-md-6 col-12">
													<div class="input-group">
														<label class="mb-1">Start Time <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="starttime" id="starttime" placeholder="Enter Start Time" autocomplete="off" />
													</div>
												</div>
												<div class="col">
													<div class="input-group">
														<label class="mb-1">End Time <span class="text-danger">*</span></label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="text" class="form-control" name="endtime" id="endtime" placeholder="Enter End Time" autocomplete="off" />
													</div>
												</div>
												<div class="col-auto">
													<div class="input-group">
														<label class="mb-1">&nbsp;</label>
														<label class="ml-auto "></label>
													</div>
													<div class="input-group mb-3">
														<input type="button" class="form-control btn btn-primary" name="btnaddtime" id="btnaddtime" value="ADD">
													</div>
												</div>
												<div class="col-12">
													<div class="table-responsive table-scroll" style="height: 200px;overflow-y:auto;margin: 10px;padding:0px;" id="table-scroll">
														<!-- <div class="chart-loading">
															<img alt="loading-img" src="img/loading.gif">
														</div> -->
														<table class="table table-bordered rangetime-table" id="rangetime-table" style="font-size: 1.1em;text-align: center;">
															<thead>
																<tr class="info">
																	<th width="65%;" style="text-align: left;">Start Time</th>
																	<th width="65%;" style="text-align: center;">End Time</th>
																	<th width="5%;" style="text-align: center;"><i class="fa fa-trash"></i></th>
																</tr>
															</thead>
															<tbody id="tbldata">
																
															</tbody>	
														</table>								
													</div>
												</div>
												


												<div class="col-12 ">    
													<div class="ml-auto">
														<div class="input-group mb-0">
															<button type="submit" class="btn btn-primary m-0  ml-auto" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
															<button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>	




									<div class="col-lg-12 col-md-12 col-12 layout-spacing">
										<div class="widget">
											<div class="widget-content row">
												<div class="col-12 ">    
													<div class="ml-auto">
														<div class="input-group mb-0">
															<button type="submit" class="btn btn-primary m-0  ml-auto" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
															<button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>	


								</from>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


	<script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
        });

		
		function resetdata()
        {
            $("#rangemasterForm").validate().resetForm();
            $('#rangemasterForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
			$('#tbltbody').html('');
			$('#tbldata').html('');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');  
			Edittimeformnamechange(2); 
        }

		//Edit Start
        function editdata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "editrangemaster");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessEditdata,OnErrorEditdata); 
        }

        function OnsuccessEditdata(content)
        {
            var JsonData = JSON.stringify(content);
            var resulteditdata = jQuery.parseJSON(JsonData);

			$('#id').val(resulteditdata.id);
			$('#rangename').val(resulteditdata.rangename);
        
			var tbldata='';
			var trtmp=1;
			for (var j in resulteditdata.resulttime)
			{
				tbldata+='<tr data-index="'+trtmp+'">';
				tbldata+='<td align="left" style="vertical-align: middle;">'+resulteditdata.resulttime[j].starttime+'';
				tbldata+='<input type="hidden" class="tblstarttime" name="tblstarttime[]" id="tblstarttime'+trtmp+'" value="'+resulteditdata.resulttime[j].starttime+'" /></td>';
				tbldata+='<td align="center" style="vertical-align: middle;">'+resulteditdata.resulttime[j].endtime+'';
				tbldata+='<input type="hidden" class="tblendtime" name="tblendtime[]" id="tblendtime'+trtmp+'" value="'+resulteditdata.resulttime[j].endtime+'" /></td>';
				tbldata+='<td align="center" style="vertical-align: middle;"><a href="javascript:void(0)" id="btndelete" class="btnremove"><i class="fas fa-trash"></i></a></td>';
				tbldata+='</tr>';
				trtmp++;
			}
			$('.rangetime-table tbody').html(tbldata);	

			tbldata='';
			trtmp=1;
			for (var j in resulteditdata.resultlane)
			{
				tbldata+='<tr data-index="'+trtmp+'">';
				tbldata+='<td align="left" style="vertical-align: middle;">';
				tbldata+='<input type="text" class="tbllanename form-control" name="tbllanename[]" id="tbllanename'+trtmp+'" value="'+resulteditdata.resultlane[j].lanename+'" /></td>';
				tbldata+='<td align="center" style="vertical-align: middle;"><a href="javascript:void(0)" id="btndelete" class="btnremove"><i class="fas fa-trash"></i></a></td>';
				tbldata+='</tr>';
				trtmp++;
			}
			$('.main-table tbody').html(tbldata);	

            $('#formevent').val('editright'); 
            $('#btnsubmit').text('<?php echo $config->getUpdateSidebar(); ?>');     

            //Open Side bar
			Edittimeformnamechange(1);
            $("#rightSidebar").toggleClass("active-right-sidebar"); 
            $('.overlay').addClass('show');
            $("body").addClass("overflow-hidden");
        }

        function OnErrorEditdata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
        //Edit End


		//View Details Start
        function viewrangedata(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "viewrangedata");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewrangedata,OnErrorviewrangedata); 
        }

		function Onsuccessviewrangedata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-xl');
			$('#GeneralModal #GeneralModalLabel').html('Range Details');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorviewrangedata(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }

		//Change Active Status 
        function changeactivestatus(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "changeactivestatus");
            formdata.append("id", id);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesschangeactivestatus,OnErrorchangeactivestatus); 
        }

        function Onsuccesschangeactivestatus(data)
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
        function OnErrorchangeactivestatus(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }


		if($('#rangemasterForm').length){		
            $('#rangemasterForm').validate({
                rules:{              
                    rangename:{
                        required: true,			
                    },
					
                },messages:{
					rangename:{
                        required:"Range name is required",	
                    },                  				
                },
                submitHandler: function(form){
					var flag = 1;
					
					$('#main-table tbody tr').each(function(){
						var tmp = $(this).attr('data-index');
						if(!$('#tbllanename'+tmp).val())
						{
							flag = 0;
						}
                	});

					$('#rangetime-table tbody tr').each(function(){
						var tmp = $(this).attr('data-index');
						if(!$('#tblstarttime'+tmp).val() || !$('#tblendtime'+tmp).val())
						{
							flag = 0;
						}
               		});
					
					if(flag)
					{
						$('#loaderprogress').show();
						var pagename=getpagename();
						var useraction=$('#formevent').val();
						formdata = new FormData(form);
						formdata.append("action", "insertrangemaster");
						var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
						ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit)
					}
					else
					{
						alertify("Please Enter Lanename",0);
					}		
			    },
            });
        }

		$('#starttime').bootstrapMaterialDatePicker({
            date: false,
            format: 'hh:mm A'
        }).on('change', function(e, date)
        {
            $('#endtime').bootstrapMaterialDatePicker('setMinDate',date);
			$('#endtime').val($('#starttime').val());
			
        });

        $('#endtime').bootstrapMaterialDatePicker({
            date: false,
            format: 'hh:mm A',
        });

		// add lane
		$('body').on('click','#btnaddrange',function()
		{
			var lanename = $("#lanename").val();
			
			if(lanename)
			{
				$('#main-table tbody tr').each(function(){
                    var tmp = $(this).attr('data-index');
                    if($('#tbllanename'+tmp).val() == lanename)
                    {
                        $(this).remove();
                    }
                });

				var trtmp = $('.main-table tbody tr:last').attr('data-index') | 0;
				trtmp = parseInt(trtmp+1);

				var tbldata='';
				tbldata+='<tr data-index="'+trtmp+'">';
				tbldata+='<td align="left" style="vertical-align: middle;">';
				tbldata+='<input type="text" class="tbllanename form-control" name="tbllanename[]" id="tbllanename'+trtmp+'" value="'+lanename+'" /></td>';
				tbldata+='<td align="center" style="vertical-align: middle;"><a href="javascript:void(0)" id="btndelete" class="btnremove"><i class="fas fa-trash"></i></a></td>';
				tbldata+='</tr>';

				$('.main-table tbody').append(tbldata);	
				$('#lanename').val('');
				$( "#lanename" ).focus();

			}
			else
			{
				alertify('Please fill all required fields',0);
			}
			
		});

		// add time
		$('body').on('click','#btnaddtime',function()
		{
			var starttime = $("#starttime").val();
			var endtime = $("#endtime").val();
			

			if(starttime && endtime)
			{
				
				var isvalidate = 1;

                $("#rangetime-table tbody tr").each(function(){

                    var trtmp = $(this).attr('data-index');

                    var tblstarttime = $('#rangetime-table #tblstarttime'+trtmp).val();

                    var tblendtime = $('#rangetime-table #tblendtime'+trtmp).val();

                    // if((parseFloat(tblpenaltyfromton) <= parseFloat(penaltyfromton) && parseFloat(tblpenaltytoton) >=  parseFloat(penaltyfromton)) || (parseFloat(tblpenaltyfromton) <= parseFloat(penaltytoton) && parseFloat(tblpenaltytoton) >=  parseFloat(penaltytoton)))

                    if((convertTime12to24(tblstarttime) <= convertTime12to24(starttime) && convertTime12to24(tblendtime) >=  convertTime12to24(starttime)) || (convertTime12to24(starttime) <= convertTime12to24(tblstarttime) && convertTime12to24(endtime) >=  convertTime12to24(tblstarttime)))

                    {

                        isvalidate = 0;

                    }

                });

				if(isvalidate == 1)
				{
					var trtmp = $('.rangetime-table tbody tr:last').attr('data-index') | 0;
					trtmp = parseInt(trtmp+1);

					var tbldata='';
					tbldata+='<tr data-index="'+trtmp+'">';
					tbldata+='<td align="left" style="vertical-align: middle;">'+starttime+'';
					tbldata+='<input type="hidden" class="tblstarttime" name="tblstarttime[]" id="tblstarttime'+trtmp+'" value="'+starttime+'" /></td>';
					tbldata+='<td align="center" style="vertical-align: middle;">'+endtime+'';
					tbldata+='<input type="hidden" class="tblendtime" name="tblendtime[]" id="tblendtime'+trtmp+'" value="'+endtime+'" /></td>';
					tbldata+='<td align="center" style="vertical-align: middle;"><a href="javascript:void(0)" id="btndelete" class="btnremove"><i class="fas fa-trash"></i></a></td>';
					tbldata+='</tr>';

					$('.rangetime-table tbody').append(tbldata);	
					$('#starttime').val('');
					$('#endtime').val('');
					$( "#stattime" ).focus();
				}
				else
				{
					alertify('Please Enter Valid Time Range',0);
				}

				

			}
			else
			{
				alertify('Please fill all required fields',0);
			}
			
		});


		$('body').on('click','.btnremove',function(){
			var thisss=$(this);
			thisss.parent().parent().remove();	
		});
	
	</script>	