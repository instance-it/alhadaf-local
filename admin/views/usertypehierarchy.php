<?php require_once dirname(__DIR__, 1).'\config\config.php';
$config = new config();
?>

<div class="layout-px-spacing">
    <!-- CONTENT AREA -->
    <div class="row layout-top-spacing">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-12 col-xl-12 mb-3">
                    <div class="widget">
                        <div class="widget-content">
                            <form class="text-left validate-form col-12 table-responsive" id="menudesignForm" method="post" action="menudesign.php" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="id" value=""/>	
                                <input type="hidden" name="formevent" id="formevent"  value="addright" />	
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">                                                
                                        <ul id="org" style="display:none" class="orgdiv"></ul>
                                        <div id="chart" class="orgChart" style="width: 100%"></div>
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
<script src="<?php echo $config->getCdnurl(); ?>assets/js/jquery.jOrgChart.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
        $(document).ready(function () {
            gettreedata();
        });

        $('body').on('mouseenter','.node',function(){
            $(this).find('.btnshow').show();
        });
        
        $('body').on('mouseleave','.node',function(){
            $(this).find('.btnshow').hide();
        });

        function addnode(id)
        {
            var cmpid = $('#cmpid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "addnode");
            formdata.append("cmpid", cmpid);
            formdata.append("id", id); 
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessAddnode,OnErrorAddnode);
        } 

        function OnsuccessAddnode(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal #Generaldata').html(resultdata.data);
            $('#GeneralModal #GeneralModalLabel').html('Add New Node');
            $('#GeneralModal .modal-dialog').addClass('modal-xl');
            $('#GeneralModal').modal('show');
        }

        function OnErrorAddnode(content)
        {
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
        

        $('body').on('click','#GeneralModal .clscheckusertype',function(){
            var trtmp=$(this).attr('usertypeno');
            var utypename=$(this).attr('data-name');
            var uid=$(this).attr('data-id');
            if($(this).find('.inner'+trtmp+' .item').hasClass('selectusrtype'))
            {
                $('#GeneralModal .inner'+trtmp).html('');
                $('#GeneralModal .inner'+trtmp).html('<h5 class="card-title">'+utypename+'</h5>');
            }
            else
            {
                $('#GeneralModal .inner'+trtmp).html('<div class="item selectusrtype"><i class="bi bi-check2-circle"></i></div><h5 class="card-title">'+utypename+'</h5><input type="hidden" id="usertypeid" name="usertypeid[]" value="'+uid+'"><input type="hidden" id="usertypename" name="usertypename[]" value="'+utypename+'">'); 
            }	
        });	


        function addnewnode()
        {
            var cmpid = $('#cmpid').val();
            var onchklength = $('[name="usertypeid[]"]').length;
            
            var isvalid=0;
            if(onchklength>=1)
            {
                isvalid=1;
            }
            else 
            {
                isvalid=0;
            }
            if(isvalid==1)
            {
                $('.loading').show();
                jqXHR = $.ajax({
                    headers : { Accept: 'application/json',platform: 1,responsetype:'HTML',userpagename:'usertypehierarchy',useraction:'viewright',masterlisting:false},
                    url : endpointurl+"usertypehierarchy.php",
                    type: 'POST',
                    data:$('#newnodeform').serialize()+'&action=addnewnode&cmpid='+cmpid,
                    dataType: "json",
                    success : function(data) 
                    {
                        var JsonData = JSON.stringify(data);
                        var resultdata = jQuery.parseJSON(JsonData);
                        $('.loading').hide();
                        if(resultdata.status == 0) 
                        {
                            alertify(resultdata.message,0);
                        }
                        else if(resultdata.status == 1)
                        {
                            alertify(resultdata.message,1);
                            $('#GeneralModal').modal('hide');
                            gettreedata();
                        }                       
                    }
                });
            }
            else
            {
                alertify('Please select data',0);
            }
        }  

        function nodereset()
        {
            $('.tblcheck').attr('checked',false);
        }

        function deletenode(id)
        {
            var cmpid = $('#cmpid').val();
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};
            formdata = new FormData();
            formdata.append("action", "deletenode");
            formdata.append("cmpid", cmpid); 
            formdata.append("id", id); 
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDeletenode,OnErrorDeletenode);
        } 

        function OnsuccessDeletenode(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('.loading').hide();
            if(resultdata.status == 0) 
            {
                alertify(resultdata.message,0);
            }
            else if(resultdata.status == 1)
            {
                alertify(resultdata.message,1);
                gettreedata();
            } 
        }

        function OnErrorDeletenode(content)
        {
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }

    function deletenode132(id)
    {
        var cmpid = $('#cmpid').val();
    	/*swal({
		  title: "Are you sure?",
		  text: "",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#3b4f83",
		  confirmButtonText: "Yes",
		  cancelButtonText: "No",
		  closeOnConfirm: true,
		  closeOnCancel: true
		},
		function(isConfirm){
			  if (isConfirm) 
			  {*/
		        $('.loading').show();
		        jqXHR = $.ajax({
		            headers : { Accept: 'application/json',platform: 1,responsetype:'HTML',userpagename:'usertypehierarchy',useraction:'viewright',masterlisting:false},
                    url : endpointurl+"usertypehierarchy.php",
		            data:{action:'deletenode',id:id,cmpid:cmpid},
		            type : "POST",
		            dataType: "json",
		            success : function(data) 
		            {   
		                var JsonData = JSON.stringify(data);
		                var resultdata = jQuery.parseJSON(JsonData);
		                $('.loading').hide();
		                if(resultdata.status == 0) 
		                {
		                    alertify(resultdata.message,'error');
		                }
		                else if(resultdata.status == 1)
		                {
		                    alertify(resultdata.message,'success');
		                    gettreedata();
		                } 
		            },
		        });
		     /* }
		});*/
    }

    function personnode(id)
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};
        formdata = new FormData();
        formdata.append("action", "personnode");
        formdata.append("id", id); 
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessPersonnode,OnErrorPersonnode);
    } 

    function OnsuccessPersonnode(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);
        $('#GeneralModal #Generaldata').html(resultdata.data);
        $('#GeneralModal #GeneralModalLabel').html('User Type wise Person details');
        $('#GeneralModal .modal-dialog').addClass('modal-xl');
        $('#GeneralModal').modal('show');
        $('.loading').hide();
    }

    function OnErrorPersonnode(content)
    {
        ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
    }

        // $('body').on('change', '#menutypeid', function () {
        //     fillmenuassignlist();
        // });

        // function fillmenuassignlist()
        // {
        //     var pagename=getpagename();
        //     var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true};
		// 	//r peramsdata = {action:'fillmenuassignlist',menutypeid:'1'};

        //     formdata = new FormData();
        //     formdata.append("action", "fillmenuassignlist");
        //     formdata.append("menutypeid", "1");
            
        //     ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessfillmenuassignlist,OnErrorfillmenuassignlist);
        // }
        // function Onsuccessfillmenuassignlist(content)
        // {
        //     var JsonData = JSON.stringify(content);
        //     var resultdata = jQuery.parseJSON(JsonData);

        //     $('#jstree').jstree("destroy");				
        //     $('#jstree').html(resultdata.data);	
        //     $('#jstree').jstree({
        //         "core" : {
        //             "animation" : 0,
        //             "check_callback" : true,
        //             "dblclick_toggle" : false
        //         },
        //         "dnd" : {
        //             "is_draggable" : function(node) {
        //                 return true;
        //             }
        //         },
        //         "types" : {
        //             "default" : {
        //                 max_depth :1
        //             }
        //         },
        //         "plugins" : [
        //             "dnd", 
        //             "state", "types", "contextmenu"
        //         ]
        //     })
        //     .on("copy_node.jstree", function (e, data) {
        //         $(this).jstree("open_all");
        //         menuupdate();
        //     })
        //     .bind("loaded.jstree", function(event, data) {
        //         $(this).jstree("open_all");
        //         menuupdate();
        //     })
        //     .bind("move_node.jstree", function(e, data) {
        //         $(this).jstree("open_all");
        //         menuupdate();
        //     })
        //     $('#jstree').jstree("open_all");
        // }

        // function OnErrorfillmenuassignlist(content)
        // { 
        //     ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        // }

        // function menuupdate()
        // {
        //     $('#jstree ul li').each(function(){			
        //         var level = $(this).attr('aria-level');
        //         if(level==1)
        //         {
        //         $(this).find('a #parent').val(1);
        //         $(this).find('a #child').val(0);
        //         }
        //         else if(level==2)
        //         {
        //         $(this).find('a #parent').val(0);
        //         $(this).find('a #child').val(1);
        //         }
        //     });
        // }

    
        // function resetdata()
        // {
        //     $("#menudesignForm").validate().resetForm();
        //     $('#menudesignForm').trigger("reset");
        // }    

        // if($('#menudesignForm').length){		
        //     $('#menudesignForm').validate({
        //         rules:{
   
        //         },messages:{

        //         },
        //         submitHandler: function(form){


        //             $('#loaderprogress').show();
        //             var pagename=getpagename();

        //             formdata = new FormData(form);
        //             formdata.append("action", "insertmenudesign");

        //             var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:false};
        //             ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessDataSubmit,OnErrorDataSubmit); 

		// 	    },
        //     });
        // }
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
