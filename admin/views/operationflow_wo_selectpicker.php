<?php 
require_once dirname(__DIR__, 1).'/config/init.php';
?>
    <div class="layout-px-spacing">
        <!-- CONTENT AREA -->
        <div class="row layout-top-spacing">
            <div class="col-12 layout-spacing" id="layoutContent">
                <div class="widget">
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
                               
                            </div>
                        </div>
                    </div>
                    <div class="widget-content">
                        <div class="row min-height-100">
                            <div class="col-12 min-height-100">
                                <div class="table-responsive main-grid">
                                    <form class="col-12 text-left validate-form" id="operationflowForm" method="post" action="productionpipeline.php" enctype="multipart/form-data">
                                        <input type="hidden" id="formevent" name="formevent" value='addright'>
                                        <input type="hidden" id="id" name="id" value=''>
                                        
                                        <div class="row">
                                            <div class="col-12 sidebar-right-sec">
                                                <div class="widget-operationflow">
                                                    <div class="widget-content row">
                                                        <div class="col-3 " style="border:1px solid #ddd;border-radius:4px 0 0 4px">
                                                            <div class="row mt-3">
                                                                <div class="col-12">
                                                                    <div class="input-group">
                                                                        <label class="mb-1">Store/Counter <span class="text-danger">*</span></label>
                                                                        <label class="ml-auto "></label>
                                                                    </div>
                                                                    <div class="input-group mb-3">
                                                                        <select class="form-control selectpicker" data-live-true="true" data-size="8" name="storeid" id="storeid">
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-12" style="height:250px;overflow:auto;position: relative;">
                                                                    <div class="input-group">
                                                                        <label class="mb-1">Operation</label>
                                                                        <label class="ml-auto "></label>
                                                                    </div>
                                                                    <ul class="drawflow-list operationlist">                 
                                                                    </ul>
                                                                </div>
                                                                <div class="col-12 mx-3 my-3">
                                                                    <button type="submit" class="btn btn-primary m-0" id="btnsubmit"><?php echo $config->getSaveSidebar(); ?></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-9 right-sec pr-0">
                                                            <div id="drawflow" class="right-dragflow-sec" ondrop="drop(event)" ondragover="allowDrop(event)">
                                                                <div class="btn-clear" onclick="clearnodeview()">Clear</div>
                                                                <div class="btn-lock">
                                                                    <i id="lock" class="bi bi-unlock" onclick="editor.editor_mode='fixed'; changeMode('lock');"></i>
                                                                    <i id="unlock" class="bi bi-lock" onclick="editor.editor_mode='edit'; changeMode('unlock');" style="display:none;"></i>
                                                                </div>
                                                                <div class="bar-zoom">
                                                                    <i class="bi bi-zoom-out" onclick="editor.zoom_out()"></i>
                                                                    <i class="bi bi-search" onclick="editor.zoom_reset()"></i>
                                                                    <i class="bi bi-zoom-in" onclick="editor.zoom_in()"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- table End -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTENT AREA -->

    </div>
   
    <script src="assets/drawflow.min.js"></script>
    <script src="assets/sweetalert2@9.js"></script>
    <script src="assets/micromodal.min.js"></script>
    <link rel="stylesheet" href="assets/drawflow.min.css">
    <link rel="stylesheet" type="text/css" href="assets/beautiful.css" />
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script>
          $(document).ready(function () {
            $('.selectpicker').selectpicker('refresh');
            filloperation();
            fillstore();
            filloperationflow();
        });
		
        function resetdata()
        {
            $("#operationflowForm").validate().resetForm();
            $('#operationflowForm').trigger("reset");
            $('#formevent').val('addright');
            $('.selectpicker').selectpicker('refresh');
            $('#btnsubmit').text('<?php echo $config->getSaveSidebar(); ?>');
            editor.clearModuleSelected();
            Edittimeformnamechange(2);   
           
            /* fillproduct(); */

        }
        
        function fillstore()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillempstore");
            formdata.append("nostoreutype", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillstore,OnErrorSelectpicker); 
        }

        function Onsuccessfillstore(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#storeid').html(resultdata.data);
            $('#storeid').selectpicker('refresh');	
        }
       
        function filloperation()
        {
            var isstagegate = $("input:radio[name=isstagegate]:checked").val()
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "filloperation");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfilloperation,OnErrorfillrowproduct);
        }
        function Onsuccessfilloperation(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
           
            $('.operationlist').html(resultdata.data);
            /* $('#processid').selectpicker('refresh');	 */
        }

        function OnErrorfillrowproduct(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }

        
        /**------------------------------------------------------------------------------------------------------------------------------------------------------------------- */
       
    
    var id = document.getElementById("drawflow");
    //delete editor;
    var editor = new Drawflow(id);
    

    //window.localStorage.setItem('test', new Drawflow(id));
    

    editor.start();
   

    /* Mouse and Touch Actions */

    var elements = document.getElementsByClassName('drag-drawflow');
    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener('touchend', drop, false);
        elements[i].addEventListener('touchmove', positionMobile, false);
        elements[i].addEventListener('touchstart', drag, false);
    }

    var processid = '';
    var operationname = '';
    var mobile_last_move = null;

    function positionMobile(ev) {
        mobile_last_move = ev;
    }

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        if (ev.type === "touchstart") {
            processid = ev.target.closest(".drag-drawflow").getAttribute('data-node');
            operationname = ev.target.closest(".drag-drawflow").getAttribute('data-operationname');
        } else {
            ev.dataTransfer.setData("node", ev.target.getAttribute('data-node')+'_'+ev.target.getAttribute('data-operationname'));
            //ev.operationname.setData("operationname", ev.target.getAttribute('data-operationname'));
        }
    }

    function drop(ev) {
        if (ev.type === "touchend") {
            var parentdrawflow = document.elementFromPoint(mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY).closest("#drawflow");
            if (parentdrawflow != null) {
                addNodeToDrawFlow(processid,operationname, mobile_last_move.touches[0].clientX, mobile_last_move.touches[0].clientY);
            }
            processid = '';
            operationname = '';
        } else {
            ev.preventDefault();
            var data = ev.dataTransfer.getData("node").split("_");
            var pid=data[0];
            var operationname=data[1];
            addNodeToDrawFlow(pid,operationname, ev.clientX, ev.clientY);
        }

    }

    function addNodeToDrawFlow(name,operationname, pos_x, pos_y) {
     
      /*  ev.preventDefault();
            var data = ev.dataTransfer.getData("node").split("_"); */
       var isstagegate = $("input:radio[name=isstagegate]:checked").val();
       var storeid = $("#storeid").val();
       var storename = $("#storeid :selected").text();
       if(storeid)
       {
            var count = $(".parent-node .drawflow-node").length;
       
            if (editor.editor_mode === 'fixed') {
                return false;
            }
            var bgcolor="#dcd8d6";
            var hidden='';
            if(isstagegate == 2)
            {
                var bgcolor="#0023aa1a";
                var hidden='d-none';
            }
    
            pos_x = pos_x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)) - (editor.precanvas.getBoundingClientRect().x * (editor.precanvas.clientWidth / (editor.precanvas.clientWidth * editor.zoom)));
            pos_y = pos_y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)) - (editor.precanvas.getBoundingClientRect().y * (editor.precanvas.clientHeight / (editor.precanvas.clientHeight * editor.zoom)));
            var telin = '<div   data-count="'+count+'"  data-operationid="'+name+'" class="opflowDiv">';
            telin += '       <div class="title-box" style="background-color:'+bgcolor+'">'+storename+' - '+operationname+'  ';
            telin += '           <input type="hidden" class="custom-control-input open-right-sidebar aptitudetest-sidebar" id="countid'+name+''+count+'" value="'+count+'" name="countid[]">';
            telin += '           <input type="hidden" class="custom-control-input open-right-sidebar aptitudetest-sidebar" id="storeid'+name+''+count+'" value="'+name+'" name="storeid[]">';
            telin += '           <input type="hidden" class="custom-control-input open-right-sidebar aptitudetest-sidebar" id="operationid'+name+''+count+'" value="'+name+'" name="operationid[]">';
            telin += '           <a href="javascript:void(0);" class="ml-auto float-right open-right-sidebar more-sidebar"><i class="bi bi-three-dots-vertical"></i></a>';
            telin += '       </div>';
            telin += '       <div class="box">';
            telin += '            <div class="col-12 '+hidden+'">';
            telin += '                 <div class="row">';
            telin += '                      <div class="col-12 '+hidden+'">';
            telin += '                           <div class="row">';
            telin += '                                <div class="col">';
            telin += '                                    <p>Is compulsory :</p>';
            telin += '                                </div>';
            telin += '                                <div class="col-auto my-auto">';
            telin += '                                   <label class="switch switch-success m-0">';
            telin += '                                       <input type="checkbox" class="iscompulsory" id="iscompulsory'+name+''+count+'" data-count="'+count+'"  data-operationid="'+name+'" value="1" name="iscompulsory'+count+'" checked>';
            telin += '                                       <span class="slider round"></span>';
            telin += '                                    </label>';
            telin += '                                </div>';
            telin += '                           </div>';
            telin += '                      </div>';
            telin += '                 </div>';
            telin += '                 <div class="row" id="textDiv'+name+''+count+'">';
            telin += '                     <div class="col-12 '+hidden+'">';
            telin += '                          <div class="row">';
            telin += '                             <div class="col-12 my-auto">';
            telin += '                                 <select  id="membertypeid'+name+''+count+'"  name="membertypeid'+count+'[]" multiple="multiple" data-count="'+count+'"  data-operationid="'+name+'" class="form-control selectpicker membertypeid">';
            telin += '                                      <option value="" selected>Select Instruction Group</option>';
            telin += '                                   </select>';
            telin += '                               </div>';
            telin += '                           </div>';
            telin += '                      </div>';
            telin += '                 </div>';
            telin += '                 <div class="row mt-3" id="insgrouptextDiv'+name+''+count+'">';
            telin += '                     <div class="col-12">';
            telin += '                          <div class="row">';
            telin += '                             <div class="col-12 my-auto">';
            telin += '                                 <select  id="insgroupid'+name+''+count+'"  name="insgroupid'+count+'" data-count="'+count+'"  data-operationid="'+name+'" class="form-control selectpicker insgroupid">';
            telin += '                                 </select>';
            telin += '                             </div>';
            telin += '                          </div>';
            telin += '                     </div>';
            telin += '                 </div>';
            telin += '                 <div class="row mt-3" id="rangetextDiv'+name+''+count+'">';
            telin += '                     <div class="col-12">';
            telin += '                          <div class="row">';
            telin += '                             <div class="col-12 my-auto">';
            telin += '                                 <select  id="rangeid'+name+''+count+'"  name="rangeid'+count+'[]" multiple="multiple" data-count="'+count+'"  data-operationid="'+name+'" class="form-control selectpicker11 rangeid">';
            telin += '                                 </select>';
            telin += '                             </div>';
            telin += '                          </div>';
            telin += '                     </div>';
            telin += '                 </div>';
            telin += '            </div>';
            telin += '           <div class="col-12 d-none ">';
            telin += '              <table id="hiddenradiotbl" ><tbody class="parameterdetailsdiv'+count+'"></tbody></table>';
            telin += '           </div>';
            telin += '       </div>';
            telin += '</div>';


            //Fill Member type
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillmembertype");
            formdata.append("nostoreutype", 1);
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillmembertype,OnErrorSelectpicker); 

            function Onsuccessfillmembertype(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata = jQuery.parseJSON(JsonData);
                $('#membertypeid'+name+''+count).html(resultdata.data);
                $('#membertypeid'+name+''+count).selectpicker('refresh');	
            }


            //Fill Instruction Group
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillinstructiongroup");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillinstructiongroup,OnErrorSelectpicker); 

            function Onsuccessfillinstructiongroup(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata = jQuery.parseJSON(JsonData);
                $('#insgroupid'+name+''+count).html(resultdata.data);
                $('#insgroupid'+name+''+count).selectpicker('refresh');	
            }


            //Fill Range
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
            formdata = new FormData();
            formdata.append("action", "fillrange");
            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillrange,OnErrorSelectpicker); 

            function Onsuccessfillrange(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata = jQuery.parseJSON(JsonData);
                $('#rangeid'+name+''+count).html(resultdata.data);
                //$('#rangeid'+name+''+count).selectpicker('refresh');	
            }

            editor.addNode('telin', 1, 1, pos_x, pos_y, 'telin', {
                "db": {
                    "dbname": '',
                    "key": '',
                    "operationid": name,
                    "storeid": storeid,
                    "storename": storename,
                    "countid": count,
                }
            }, telin);

            $('.open-right-sidebar').click(function(){
                $("#productwiseprocessModal").modal("show"); 
                operationid = $(this).parent().parent().attr('data-operationid');
                count = $(this).parent().parent().attr('data-count');
                var stgtid=$('#stgtid'+stgtid).val();
               // $('.parameterdetailsdiv'+count).html('');
                fillparameter(operationid,count,stgtid);
            });
            $('.open-right-sidebar1').click(function(){
                $("#productwiseprocessModal").modal("show"); 
                operationid = $(this).parent().parent().parent().parent().parent().parent().parent().parent().attr('data-operationid');
                count = $(this).parent().parent().parent().parent().parent().parent().parent().parent().attr('data-count');
                var stgtid=$('#stgtid'+stgtid).val();
              //  $('.parameterdetailsdiv'+count).html('');
                fillparameter(operationid,count,stgtid);
            });
            $('.more-sidebar').on('click', function() {
                processid = $(this).parent().parent().attr('data-processid');
                operationid = $(this).parent().parent().attr('data-operationid');
                machineid = $(this).parent().parent().attr('data-machineid');

            });
            $('.aptitudetest-sidebar').on('click', function() {
                $("#dotsidebar").addClass('d-none');
                $("#aptitudetestsb").removeClass('d-none');
            });
            $("#stgtname").val('');

            $('.iscompulsory').on('change', function() {
               var operationid = $(this).attr('data-operationid');
               var count = $(this).attr('data-count');
               if ($(this).is(':checked')) 
               {
                    $('#textDiv'+operationid+count).removeClass('d-none');
               }
               else
               {
                    $('#textDiv'+operationid+count).addClass('d-none');
               }
            });

            //fillownershipperson(name,count);
       }
       else{
        alertify('Please select Store',0);
       }
      
    }
    
    $('body').on('change', '.iscompulsory', function() {
        var operationid = $(this).attr('data-operationid');
        var count = $(this).attr('data-count');
        if ($(this).is(':checked')) 
        {
            $('#textDiv'+operationid+count).removeClass('d-none');
        }
        else
        {
            $('#textDiv'+operationid+count).addClass('d-none');
        }
    });

    $('body').on('input','.tblorderby',function(){
       var od=$(this).val();
       $(this).attr('value',od);
       // alert($(this).val());
        
    });
    $('body').on('change','.checkhd',function(){
        $(this).attr('checked', false);
        $(this).attr('value', 0);
        if($(this).prop("checked") == true){
            $(this).attr('checked', true);
            $(this).attr('value', 1);
        }
    });
    $('body').on('change','.reqcheckhd',function(){
        $(this).attr('checked', false);
        $(this).attr('value', 0);
        if($(this).prop("checked") == true){
            $(this).attr('checked', true);
            $(this).attr('value', 1);
        }
        
    });

    var transform = '';

    function showpopup(e) {
        e.target.closest(".drawflow-node").style.zIndex = "9999";
        e.target.children[0].style.display = "block";
        transform = editor.precanvas.style.transform;
        editor.precanvas.style.transform = '';
        editor.precanvas.style.left = editor.canvas_x + 'px';
        editor.precanvas.style.top = editor.canvas_y + 'px';
        editor.editor_mode = "fixed";

    }

    function closemodal(e) {
        e.target.closest(".drawflow-node").style.zIndex = "2";
        e.target.parentElement.parentElement.style.display = "none";
        //document.getElementById("modalfix").style.display = "none";
        editor.precanvas.style.transform = transform;
        editor.precanvas.style.left = '0px';
        editor.precanvas.style.top = '0px';
        editor.editor_mode = "edit";
    }

    function changeModule(event) {
        var all = document.querySelectorAll(".menu ul li");
        for (var i = 0; i < all.length; i++) {
            all[i].classList.remove('selected');
        }
        event.target.classList.add('selected');
    }

    function changeMode(option) {
        //console.log(lock.id);
        if (option == 'lock') {
            lock.style.display = 'none';
            unlock.style.display = 'block';
        } else {
            lock.style.display = 'block';
            unlock.style.display = 'none';
        }
    }

    $(document).ready(function () {
        
        $('#aptitudetestyes').on('click', function() {            
            if($(this).prop("checked") == true) {
                $(".question-list-block").removeClass('d-none');
            }
            else{
                $(".question-list-block").addClass('d-none');
            }
        });
        $('#aptitudetestno').on('click', function() {            
            if($(this).prop("checked") == true) {
                $(".question-list-block").addClass('d-none');
            }
        });

        
        if ($(".main-content-wrap").find("#full-editor")){
            var quill = new Quill('#full-editor', {
                modules: {
                syntax: !0,
                toolbar: [[{
                    font: []
                }, {
                    size: []
                }], ["bold", "italic", "underline", "strike"], [{
                    color: []
                }, {
                    background: []
                }], [{
                    script: "super"
                }, {
                    script: "sub"
                }], [{
                    header: "1"
                }, {
                    header: "2"
                }, "blockquote", "code-block"], [{
                    list: "ordered"
                }, {
                    list: "bullet"
                }, {
                    indent: "-1"
                }, {
                    indent: "+1"
                }], ["direction", {
                    align: []
                }], ["link", "image", "video", "formula"], ["clean"]]
                },
                theme: 'snow'
            });
        }
    });
        /**------------------------------------------------------------------------------------------------------------------------------------------------------------------- */

        if($('#operationflowForm').length){		
            $('#operationflowForm').validate({
                rules:{
                },messages:{
                },
                submitHandler: function(form){
                    //window.localStorage.getItem('test')
                    if($('.drawflow-node').length > 0)
                    {
                        var chkoperation = 1;
                        $(".drawflow-node").each(function(){
                            
                            var trtmp = $(this).find('.opflowDiv').attr('data-operationid');
                            var count = $(this).find('.opflowDiv').attr('data-count');
                           if($('#iscompulsory'+trtmp+count).is(':checked') == true &&  $('#membertypeid'+trtmp+count+' :selected').length == 0)
                           {
                                chkoperation = 0;
                           }
                        });

                        if(chkoperation == 1)
                        {
                            var  productexport=JSON.stringify  (editor.export(),null,4);
                            var  productexport1=editor.export();
                            $('#loaderprogress').show();
                            var pagename=getpagename();
                            var useraction=$('#formevent').val();
                            formdata = new FormData(form);
                            formdata.append("action", "insertoperationflow");
                            formdata.append("productexport", productexport);
                            formdata.append("productexport1", productexport1);
                            var headersdata= {'Accept': 'application/json',platform: 1,userpagename:pagename,useraction:useraction,masterlisting:false};
                            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessoperationflow,OnErrorOnsuccessoperationflow); 
                        }
                        else
                        {
                            alertify('Please Select Member Type At List One',0);
                        }
                        return false;
                        
                    }
                    else
                    {
                        alertify('Please Add At List One Operation',0);
                    }
                   
			    },
            });
        }

        function Onsuccessoperationflow(data)
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
                editor.clearModuleSelected();
                filloperationflow();
            }
        }

        function OnErrorOnsuccessoperationflow()
        {
            ajaxrequest("POST", page404url, '', '', OnsuccessRender, OnErrorRender);
        }

        function clearnodeview()
        {
            $('#confirmedclearview').modal('show');
        }

        $('.confirmedclearviewbtn').on('click', function(e){ 
            editor.clearModuleSelected();
        })

        //View Details Start
        function viewpipelinedetails(id)
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:false,responsetype: 'HTML'};

            formdata = new FormData();
            formdata.append("action", "viewpipelinedetails");
            formdata.append("id", id);

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessviewpipelinedetails,OnErrorviewpipelinedetails); 
        }

		function Onsuccessviewpipelinedetails(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);
            $('#GeneralModal').modal('show');
			$('#GeneralModal .modal-dialog').addClass('modal-xl');
			$('#GeneralModal #GeneralModalLabel').html('Pipeline Details');
			$('#GeneralModal #Generaldata').html(resultdata.data);
        }
        
        function OnErrorviewpipelinedetails(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }


         //Fill operation Start
         function filloperationflow()
        {
            var pagename=getpagename();
            var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'editright',masterlisting:false};

            formdata = new FormData();
            formdata.append("action", "filloperationflow");

            ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,OnsuccessOperationflow,OnErrorOperationflow); 
        }

        function OnsuccessOperationflow(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            $('#id').val(resultdata.id);
            fillstore();
             //var nodetext=JSON.stringify(resultdata.nodetext);
             var resultdata1212 = jQuery.parseJSON(resultdata.nodetext);
            //console.log(resultdata1212);
            editor.import(resultdata1212);
            var kk=0;	
            for(var i in resultdata.nodedata)
            {
                var operationid = resultdata.nodedata[i].operationid;
                var countid = resultdata.nodedata[i].countid;
                
                $('#storeid'+operationid+countid).val(resultdata.nodedata[i].storeid);
                $('#operationid'+operationid+countid).val(resultdata.nodedata[i].operationid);
                $('#countid'+operationid+countid).val(resultdata.nodedata[i].countid);
               
                if(resultdata.nodedata[i].iscompulsory == 1)
                {
                    $("#iscompulsory"+operationid+countid).prop("checked", true);
                    $('#textDiv'+operationid+countid).removeClass('d-none');
                }
                else
                {
                    $("#iscompulsory"+operationid+countid).prop("checked", false);
                    $('#textDiv'+operationid+countid).addClass('d-none');
                }



                var membertypeid = resultdata.nodedata[i].membertypeid;
                var insgroupid = resultdata.nodedata[i].insgroupid;
                var rangeid = resultdata.nodedata[i].rangeid;
                //var membertypename = resultdata.nodedata[i].membertypename;
                //alert(membertypeid);
                //alert(operationid+'***'+countid);


                //Fill Member Type
                var pagename=getpagename();
                var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
                formdata = new FormData();
                formdata.append("action", "fillmembertype");
                formdata.append("operationid", operationid);
                formdata.append("countid", countid);
                formdata.append("membertypeid", membertypeid);
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessfillmembertype,OnErrorSelectpicker); 

                function Onsuccessfillmembertype(data)
                {
                    var JsonData = JSON.stringify(data);
                    var resultdata = jQuery.parseJSON(JsonData);
                    
                    $('#membertypeid'+resultdata.operationid+''+resultdata.countid).html(resultdata.data);
                   
                    if(resultdata.membertypeid !== null || resultdata.membertypeid !== '')
                    {
                        var mt = resultdata.membertypeid.split(",");
                        // console.log(resultdata.membertypeid)
                        // console.log(mt)
                        //alert(resultdata.membertypeid);
                        //alert(resultdata.operationid+''+resultdata.countid);
                        //alert(mt);
                        $('#membertypeid'+resultdata.operationid+''+resultdata.countid).selectpicker('val', mt);
                    }
                    $('#membertypeid'+resultdata.operationid+''+resultdata.countid).selectpicker('refresh');	
                }



                //Fill Instruction Group
                var pagename=getpagename();
                var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
                formdata = new FormData();
                formdata.append("action", "fillinstructiongroup");
                formdata.append("operationid", operationid);
                formdata.append("countid", countid);
                formdata.append("insgroupid", insgroupid);
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillinstructiongroup,OnErrorSelectpicker); 

                function Onsuccessedtfillinstructiongroup(content)
                {
                    var JsonData = JSON.stringify(content);
                    var resultigdata = jQuery.parseJSON(JsonData);
                    $('#insgroupid'+resultigdata.operationid+''+resultigdata.countid).html(resultigdata.data);
                    $('#insgroupid'+resultigdata.operationid+''+resultigdata.countid).val(resultigdata.insgroupid);
                    $('#insgroupid'+resultigdata.operationid+''+resultigdata.countid).selectpicker('refresh');	
                }



                //Fill Range
                var pagename=getpagename();
                var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'viewright',masterlisting:true,responsetype: 'HTML'};
                formdata = new FormData();
                formdata.append("action", "fillrange");
                formdata.append("operationid", operationid);
                formdata.append("countid", countid);
                formdata.append("rangeid", rangeid);
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'master',formdata,headersdata,Onsuccessedtfillrange,OnErrorSelectpicker); 

                function Onsuccessedtfillrange(content)
                {
                    var JsonData = JSON.stringify(content);
                    var resultrdata = jQuery.parseJSON(JsonData);
                    
                    $('#rangeid'+resultrdata.operationid+''+resultrdata.countid).html(resultrdata.data);
                   
                    if(resultrdata.rangeid !== null || resultrdata.rangeid !== '')
                    {
                        var rr = resultrdata.rangeid.split(",");

                        alert(rr);
                        
                        $('#rangeid'+resultrdata.operationid+''+resultrdata.countid).val( rr);
                    }
                    // $('#rangeid'+resultrdata.operationid+''+resultrdata.countid).selectpicker('refresh');
                }

               
                kk=kk+1;
            } 
            $('.aptitudetest-sidebar').on('click', function() {
                $("#dotsidebar").addClass('d-none');
                $("#aptitudetestsb").removeClass('d-none');
            }); 
        }

        function OnErrorOperationflow(content)
        { 
            ajaxrequest("POST","'<?php echo $config->getPage404url(); ?>'",'','',OnsuccessRender,OnErrorRender);
        }
        //Fill operation End

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

    
