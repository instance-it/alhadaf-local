<?php 
require_once dirname(__DIR__, 1).'/config/init.php';
?>
    
<div class="layout-px-spacing">

    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <div class="col-12 layout-spacing" id="layoutContent">
            
            <div class="widget widget-page widget-no-footer">
                <div class="widget-control">
                    <div class="row">
                        <div class="col-auto mr-auto">
                            <!-- <h5 class="m-0" id="customernamelbl">vessel Wise Class Mapping</h5> -->
                        </div>
                        <div class="col-auto ml-auto">
                            <ul class="list-inline">
                                <li><a href="javascript:void(0);" class="btn-tbl content-minimize"><i class="bi bi-dash-lg"></i></a></li>
                                <li><a href="javascript:void(0);" class="btn-tbl content-expand"><i class="bi bi-fullscreen"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    
                </div>

                <div class="widget-content">
                    <form class="text-left validate-form" id="assignlanguagewiselabelForm" method="post" action="assignlanguagewiselabel" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-10 col-xl-6 col-exl-4">
                                <div class="row">


                                    <div class="col-6">
                                        <div class="input-group">
                                            <label class="mb-1">App Type <span class="text-danger">*</span></label>
                                            <label class="ml-auto "></label>
                                        </div>
                                        <div class="input-group mb-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="apptypeid" name="apptypeid" data-size="10" data-dropup-auto="false">
                                                <option value="2">Mobile App</option>
                                                <option value="3">POS</option>
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-6 col-sm">
                                        <div class="input-group mb-0">
                                            <label class="mb-1">Language </label>
                                        </div>
                                        <div class="input-group mb-3 mb-sm-3">
                                            <select class="form-control selectpicker" data-live-search="true" id="languageid" name="languageid" data-size="10" data-dropup-auto="false">
                                                <option value="">Select Language</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <?php 
                                if((sizeof($Pagerights)>0 ? $Pagerights->getAddright():0)==1 || $IISMethods->checkutypeexist($config->getAdminutype(),$LoginInfo->getUtypeid())==1) //rights check admin and right person
                                {
                                    ?>
                                   <div class="col-12 col-sm-auto mt-auto d-flex">
                                        <div class="input-group mb-3 mb-sm-3 w-auto">
                                            <button type="submit" class="btn btn-primary m-0" id="btnAdd"><?php echo $config->getSaveSidebar(); ?></button>
                                        </div>
                                        <div class="input-group mb-3 ml-3 mb-sm-3">
                                            <button type="button" class="btn btn-secondary m-0 ml-2" id="btnReset" onclick="resetdata()"><?php echo $config->getResetSidebar(); ?></button>
                                        </div>      
                                    </div>
                                    <?php
                                }
                            ?>
                            

                            <div class="col-12 col-lg-3 ml-auto mt-auto text-right">
                                <ul class="page-more-setting widthper-100 mb-2">
                                    <li class="tbl-search widthper-100">
                                        <div class="input-group">
                                            <input type="text" name="myInput" id="myInput" class="form-control control-append" placeholder="Search Here...">
                                            <!-- <div class="input-group-append">
                                                <label class="input-group-text" for="selectBrandDate" id="btnfilter" name="btnfilter"><i class="fal fa-search"></i></label>
                                            </div> -->
                                        </div>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>

                        <div class="row min-height-100">
                            <div class="col-12 min-height-100">                                                
                                <div class="table-responsive main-grid">
                                    <table id="tableDataList" class="table table-bordered table-hover table-striped datalisttable table-search">
                                        <thead>
                                            <tr>
                                                <th class="tbl-name" width="20%">Appmenu Name</th>
                                                <th class="tbl-name" width="20%">Label Name Id</th>
                                                <th class="tbl-name" width="30%">Label English Name</th>
                                                <th class="tbl-name" width="30%"> Label Name In <span class="textlanguage"></span></th>
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
<!-- CONTENT AREA -->
</div>

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            filllanguage();
            
        });

        $('#apptypeid').on('change',function(){
            filllanguage();
        });

        function filllanguage()
        {
            var apptypeid = $('#apptypeid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "filllanguage");
            formdata.append("apptypeid", apptypeid);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfilllanguage,OnErrorSelectpicker);
        }

        function Onsuccessfilllanguage(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#languageid').html(resultdata.data);
            $('#languageid').selectpicker('refresh');
            changelanglabel()
            filllangwiselabeldata();
            
        }
    
        function resetdata()
        {
            $("#assignlanguagewiselabelForm").validate().resetForm();
            $('#assignlanguagewiselabelForm').trigger("reset");
           
            $('.selectpicker').selectpicker('refresh');
            Edittimeformnamechange(2);   
            filllanguage();

            $('#tabledata').html('');
        }


        function changelanglabel()
        {
            //textlanguage
            var langtext=$('#languageid option:selected').text();
            $('.textlanguage').html(langtext);
        } 

        //Change Event Of vessel
		$('body').on('change','#languageid',function(){
            changelanglabel();
			filllangwiselabeldata();
            $('#myInput').val('')
		});

        function filllangwiselabeldata()
        {
            var apptypeid =$('#apptypeid').val();
			var languageid =$('#languageid').val();
            var ordby = $('#ordby').val();
            var ordbycolumnname = $('#ordbycolumnname').val();
            $('#tabledata').html('');
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};
            formdata = new FormData();
            formdata.append("action", "filllangwiselabeldata");
			formdata.append("apptypeid", apptypeid);
            formdata.append("languageid", languageid);
            formdata.append("ordby", ordby);
            formdata.append("ordbycolumnname", ordbycolumnname);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfilllangwiselabeldata,OnErrorfilllangwiselabeldata);
        }

        function Onsuccessfilllangwiselabeldata(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#tabledata').html(resultdata.data);
        }

        function OnErrorfilllangwiselabeldata(content)
		{
			ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
		}

       
        //Sorting Start
        $('body').on('click', '#tableDataList .selfsorting', function() {
            $(".main-grid").animate({ scrollTop: 0 }, "slow");
            var ordby = $('#ordby').val();
            var ordbycolumnname = $(this).attr('data-th');
            $('#ordbycolumnname').val(ordbycolumnname);
            $('#tableDataList').attr('data-nextpage', 0);
            if (ordby == 'desc') 
            {
                $('.sorting').removeClass('sorting_desc');
                $('#ordby').val('asc');
                $(this).addClass('sorting_asc');
                filllangwiselabeldata();
            } 
            else if(ordby == 'asc') 
            {
                $('.sorting').removeClass('sorting_asc');
                $('#ordby').val('desc');
                $(this).addClass('sorting_desc');
                filllangwiselabeldata();
            }
        });



        //search function
		$('body').on('input','#myInput',function(){
			var input, filter, table, tr, td,td1,td2, i;
			input = document.getElementById("myInput");
			filter = input.value.toUpperCase();
			table = $('.table-search');
			tr = table.find("tr");
			for (i = 0; i < tr.length; i++) 
			{
				td = tr[i].getElementsByTagName("td")[0];
                td1 = tr[i].getElementsByTagName("td")[1];
                td2 = tr[i].getElementsByTagName("td")[2];
                td3 = tr[i].getElementsByTagName("td")[3];

				if (td || td1 || td2 || td3)
				{
					if (td.innerHTML.toUpperCase().indexOf(filter) > -1 || td1.innerHTML.toUpperCase().indexOf(filter) > -1 || td2.innerHTML.toUpperCase().indexOf(filter) > -1 || td3.innerHTML.toUpperCase().indexOf(filter) > -1) 
						tr[i].style.display = "";
					else
						tr[i].style.display = "none";
				}       
			}
		});

        
        if($('#assignlanguagewiselabelForm').length){		
            $('#assignlanguagewiselabelForm').validate({
                rules:{
                    languageid:{
                        required: true,			
                    },
                },messages:{
                    languageid:{
                        required:"Language is required",
                    },
                },
                submitHandler: function(form){
                    $('#loaderprogress').show();
                    var pagename=getpagename();
                    formdata = new FormData(form);
                    formdata.append("action", "assignlanguagewiselabel");
                    var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
                    ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit1,OnErrorDataSubmit)
                   
			    },
            });
        }

        function OnsuccessDataSubmit1(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            if(resultdata.status==0)
            {
                alertify(resultdata.message, '0');
            }
            else if(resultdata.status==1)
            {
                alertify(resultdata.message, '1');
                //fillvessel();
                filllangwiselabeldata();
            }
        }


    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
