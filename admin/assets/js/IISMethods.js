var views = 'views/';
var currentXhr;

//for a session timeout manage

$.ajaxSetup({
    beforeSend: function(jqXHR) {
    },
    complete: function(jqXHR) 
    {
        
    }
});

//for a session timeout manage

// Page Navigation on Click
$('body').on('click', '#accordionMenu li a,#accordionMenu a', function(e) {
    var pagename = $(this).attr('pagename');
    if(pagename)
    {
        $('#ordbycolumnname').val('primary_date');
        $('#ordby').val('desc');
        e.preventDefault(); 
        if (e.ctrlKey){ //ctrl click event
            window.open(pagename,'_blank');
        }
        else{
            currentXhr.abort();
            render(pagename);
            window.history.pushState(pagename, 'Title', dirpath + pagename);
        }

        var subPageName = $("#sidebar ul.menu-categories li.menu ul li.active a").text();
        var mainPagename = $("#sidebar ul.menu-categories li.active a").text();
        var subformname = $("#sidebar ul.menu-categories li.menu ul li.active a").attr('formname');
        var mainformname = $("#sidebar ul.menu-categories li.active a").attr('formname');

        if(subPageName){
            $(".navbar-expand-sm .navbar-item .nav-page-name a.nav-link span").text(subPageName);
            $(".menunamelbl").text(subPageName);
            $(".formnamelbl").text('Add '+subformname);
        }
        else{
            $(".navbar-expand-sm .navbar-item .nav-page-name a.nav-link span").text(mainPagename);
            $(".menunamelbl").text(mainPagename);
            $(".formnamelbl").text('Add '+mainformname);
        }
    }
});

$('body').on('change','.selectpicker', function () {
    $(this).parent('div').removeClass('error');  
	$(this).parent().find('label.error').html('');
});


//middle click event
$(document).on("mousedown", '#accordionMenu li a, #accordionMenu a' , function(e) {
    var pagename = $(this).attr('pagename');
    switch(e.which)
    {
        case 1:
            //left Click
        break;
        case 2:
            //middle Click
            window.open(pagename,'_blank');
        break;
        case 3:
            //right Click
        break;
    }
    return true;// to allow the browser to know that we handled it.
});
//middle click event

$(window).on('popstate', function() {
    var urlpath = window.location.pathname;
    var pagename = urlpath.split("/");
    var pagename = pagename[pagename.length-1];
    render(pagename);
});


// Page Render Start
function render(pagename) {

    ajaxrequest("POST", views + pagename, '', '', OnsuccessRender, OnErrorRender);
    $("#sidebar li").removeClass("active");
    $("#sidebar li a").attr("aria-expanded", "false");
    $("#sidebar li#li" + pagename).addClass("active");
    $("#sidebar li#li" + pagename + " a").attr("aria-expanded", "true");

   
}

function OnsuccessRender(content) {
    // console.log(content)
    $("#content").html(content);

    var subPageName = $("#sidebar ul.menu-categories li.menu ul li.active a").text();
    var mainPagename = $("#sidebar ul.menu-categories li.active a").text();
    var subformname = $("#sidebar ul.menu-categories li.menu ul li.active a").attr('formname');
    var mainformname = $("#sidebar ul.menu-categories li.active a").attr('formname');
    
    if(subPageName){
        $(".menunamelbl").text(subPageName);
        $(".formnamelbl").text('Add '+subformname);
    }
    else{
        $(".menunamelbl").text(mainPagename);
        $(".formnamelbl").text('Add '+mainformname);
    }

    $('#tableDataList').attr('data-tbldata-show','1');

    
    listdata();
    $('.main-grid').on('scroll', function() {
        if($(this).scrollTop() +
            $(this).innerHeight() >=
            $(this)[0].scrollHeight) {
            var dataact = getpagename();
            
            var show = $('#tableDataList').attr('data-show');

            if (dataact && show == 1) {
                
                listdata();
            }
        }
    });
}

function OnErrorRender(content) {
    ajaxrequest("POST", page404url, '', '', OnsuccessRender, OnErrorRender);
}
// Page Render Start

function getpagename()
{
    var urlpath = window.location.pathname;
    var pagename = urlpath.split("/");
    var pagename = pagename[pagename.length-1];
    return pagename;
}

function ajaxrequest(method, url, paramsdata, headerdata, successCallback, errorCallback,Complete=null,beforeSend=null) {
    currentXhr=$.ajax({
        type: method,
        url: url + '.php',
        headers: headerdata,
        data: paramsdata,
        processData: false,
        cache:false,
        contentType: false,
        error: errorCallback,
        success: successCallback,
        beforeSend: beforeSend,
        complete: Complete
       
    });
}

// Data list Start
function listdata() {
    var nextpage = $('#tableDataList').attr('data-nextpage');
    var pagename=getpagename();
    var menuid=$('#sidebar .active a').attr('data-menuid');
    var submenulength=$('#sidebar .active ul li').length;

    var tbldatashow = $('#tableDataList').attr('data-tbldata-show');

    if(tbldatashow == 1){
        if (pagename && nextpage) {
            $('#tableDataList').attr('data-tbldata-show','0');
            var filter = $('#filter').val();
            var ordby = $('#ordby').val();
            var per_page = $('#per_page').val();
            var ordbycolumnname = $('#ordbycolumnname').val();
            var fltfromdate = $('#fltfromdate').val();
            var flttodate = $('#flttodate').val();
            var fltstatus = $('#fltstatus').val();

            var fltstoreid = $('#fltstoreid').val();
            var fltmemberid = $('#fltmemberid').val();

            var headersdata = { Accept: 'application/json',platform: 1,responsetype:'HTML',userpagename:pagename,useraction:'viewright',masterlisting:false};
            
            formdata = new FormData($('#flt'+pagename+'Frm')[0]); //for filter pass form id
            formdata.append("action", "list"+pagename);
            formdata.append("nextpage", parseInt(nextpage) + 1);
            formdata.append("perpage", per_page);
            formdata.append("filter", filter);
            formdata.append("ordby", ordby);
            formdata.append("ordbycolumnname", ordbycolumnname);
            formdata.append("fltfromdate", fltfromdate);
            formdata.append("flttodate", flttodate);
            formdata.append("fltstatus", fltstatus);

            formdata.append("fltstoreid", fltstoreid);
            formdata.append("fltmemberid", fltmemberid);
            ajaxrequest("POST",endpointurl+pagename,formdata,headersdata,OnsuccessListdata,OnErrorListdata);
        }
        else if (menuid && submenulength > 0)
        {
            $('#tableDataList').attr('data-tbldata-show','0');
            var headersdata = { Accept: 'application/json',platform: 1,responsetype:'HTML',userpagename:pagename,useraction:'viewright',masterlisting:false};
            formdata = new FormData();
            formdata.append("action", "list"+pagename);
            formdata.append("menuid", menuid);
            ajaxrequest("POST",endpointurl+pagename,formdata,headersdata,OnsuccessListMasterdata,OnErrorListdata);
        }
    } 
}

function OnsuccessListMasterdata(content)
{
    var JsonData = JSON.stringify(content);
    var resultdata = jQuery.parseJSON(JsonData);
    $('#MasterMenuList').html(resultdata.data);
    $('#tableDataList').attr('data-tbldata-show','1');
}

function OnsuccessListdata(content) {
    var JsonData = JSON.stringify(content);
    var resultdata = jQuery.parseJSON(JsonData);

    $('#tableDataList').attr('data-tbldata-show','1');
 
    // $('.tbl-check').addClass('d-none');
    // $('.select-bulk-delete').removeClass('active-bulk-delete');    
    if(resultdata.status==-1)
    { 
        ajaxrequest("POST", sessiontimeout, '', '', OnsuccessRender, OnErrorRender);
        var headersdata= {Accept: 'application/json',platform: 1,userpagename:'logout',useraction:'addright',masterlisting:false,responsetype: 'HTML'};
        var formdata = new FormData();
        formdata.append("action", "logout");
        formdata.append("phpself", phpself);
        formdata.append("mastertype", mastertype);
       
        setTimeout(() => {
            ajaxrequest("POST",endpointurl+'logout',formdata,headersdata,OnsuccessLogout,OnErrorJson);
            // window.location=weburl+'lock-screen-logout';
        }, 2000);

    }
    else
    { 
        if(resultdata.nextpage == 1) {
            $('#datalist').html(resultdata.data);
        }else{
            $('#datalist').append(resultdata.data);
        }
        $('#showentries').html(resultdata.showentries);
        $('#tableDataList').attr('data-nextpage', resultdata.nextpage);
        if(resultdata.loadmore == 1){
            $('#tableDataList').attr('data-show', 1);
        } else {
            $('#tableDataList').attr('data-show', 0);
        }
    }

    if($(".select-bulk-delete").hasClass("active-bulk-delete")) 
    {   
        $('.tbl-check').removeClass('d-none');
    }
    $('.btn-tbl[data-toggle="tooltip"]').tooltip();
}

function OnErrorListdata(content) {
    ajaxrequest("POST", page404url, '', '', OnsuccessRender, OnErrorRender);
}

function OnsuccessDataSubmit(data)
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

        $('#tableDataList').attr('data-nextpage',0); 
        listdata();

        //Close Side bar
        $("#rightSidebar").removeClass("active-right-sidebar"); 
        $('.overlay').removeClass('show');
        $("body").removeClass("overflow-hidden");
        resetdata();
    }
}

function OnErrorDataSubmit()
{
    ajaxrequest("POST", page404url, '', '', OnsuccessRender, OnErrorRender);
}


function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

// Data List End

$('body').on('change', '#per_page', function() {
    $('#tableDataList').attr('data-nextpage', 0);
    $(".main-grid").animate({ scrollTop: 0 }, "slow");
    listdata();
});

//Filter Start
$('body').on('click', '#btnfilter', function() {
    $('#tableDataList').attr('data-nextpage', 0);
    var filter = $('#filter').val();
    if (filter) {
        listdata();
    }
});

$('body').on('keyup', '#filter', function(e) {
    $('#tableDataList').attr('data-nextpage', 0);
    if (e.keyCode == 13) {
        listdata();
        return false;
    }
    if (e.keyCode == 8 && ($('#filter').val() == '' || $('#filter').val() == null)) {
        listdata();
    }
});
//Filter End


//Sorting Start
$('body').on('click', '#tableDataList .sorting', function() {
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
        listdata();
    } 
    else if(ordby == 'asc') 
    {
        $('.sorting').removeClass('sorting_asc');
        $('#ordby').val('desc');
        $(this).addClass('sorting_desc');
        listdata();
    }
});
//Sorting End


//Delete Start
function deletedata(id) {
    $('#modalConfirmation').modal('show');
    $('#modalConfirmation #deleteaction').val('');
    $('#drid').val(id);
    
    $('#deleteaction').val('');
    
}

$('#modalConfirmation').on('shown.bs.modal', function() { 
    $('#modalConfirmation #deleteaction').focus();
});

$('#deleteaction').keypress(function (e) {
    if (e.which == 13) {
      $('#confirmdelete').click();
    }
});

$('body').on('click', '#confirmdelete', function() {

    var deleteaction = $('#deleteaction').val();
    if (deleteaction == 'delete' || deleteaction == 'Delete' || deleteaction == 'DELETE') 
    {
        var id = $('#drid').val();
        var bulkid = '';
        if (id == '') 
        {
            var bulkid = new Array();
            $('#datalist tr').each(function() {
                var trtmp = $(this).attr('data-index');
                if ($('#bulkdelete' + trtmp).is(':checked')) {
                    bulkid.push($('#bulkdelete' + trtmp).val());
                }
            });
        }

        if(id || bulkid.length > 0)
        {
            var pagename=getpagename();
            var headersdata = { Accept: 'application/json', platform: 1, responsetype: 'HTML',userpagename:pagename,useraction:'delright',masterlisting:false };
    
            formdata = new FormData();
            formdata.append("action", "delete"+pagename);
            formdata.append("id", id);
            formdata.append("bulkid", bulkid);

            ajaxrequest("POST", endpointurl + pagename, formdata, headersdata, OnsuccessDelete, OnErrorDelete);
            $('#drid').val('');
            $('#deleteaction').val('');
            $('#modalConfirmation').modal('hide');
        }
    } 
    else 
    {
        alertify('Type Delete to Confirm',0);
    }
});

function OnsuccessDelete(content) {
    $('#tableDataList').attr('data-nextpage', 0);
    var JsonData = JSON.stringify(content);
    var resultdata = jQuery.parseJSON(JsonData);
    $('#loaderprogress').hide();
    if (resultdata.status == 0) {
        alertify(resultdata.message, 0);
    } else if (resultdata.status == 1) {
        alertify(resultdata.message, 1);
    }
    listdata();
    $('.tbl-check').addClass('d-none');
    $('.select-bulk-delete').removeClass('active-bulk-delete'); 
}

function OnErrorDelete(content) {
    ajaxrequest("POST", page404url, '', '', OnsuccessRender, OnErrorRender);
}

$('body').on('click', '#bulkdelete', function() {
    $('#modalConfirmation #deleteaction').val('');
    $('#modalConfirmation #deleteaction').focus();
    $('#modalConfirmation').modal('show');
});
//Delete End

function alertify(msg, type) {
    if (type == 1) {
        toastr.success(msg, { timeOut: "50000" });
    } else if (type == 0) {
        toastr.error(msg , { timeOut: "50000" });
    }
}


function OnErrorSelectpicker(content)
{ 
    alertify('Error', '0');
}


$('body').on('click','.logout-btn', function () {
    
    var headersdata= {Accept: 'application/json',platform: 1,userpagename:'logout',useraction:'addright',masterlisting:false,responsetype: 'JSON'};
    var formdata = new FormData();
    formdata.append("action", "logout");
    ajaxrequest("POST",endpointurl+'logout',formdata,headersdata,OnsuccessLogout,OnErrorJson);
});


function OnsuccessLogout(content)
{
    var JsonData = JSON.stringify(content);
    var resultdata = jQuery.parseJSON(JsonData);
    if(resultdata.redirecturl)
    {
        window.location.href = resultdata.redirecturl;
    }
}

function OnErrorJson(content) {
}


$('#modalConfirmation').on('hide.bs.modal', function () {
   $('#deleteaction').val('')
})


$('body').on('click','.btnmultitrash',function(){
   var onchklength = $('[name="bulkdelete[]"]:checked').length;
   if(onchklength > 0)
   {
        $('#modalConfirmation').modal('show');
   }
   else
   {
        alertify('Please select atleast one checkbox', '0');
   }
  
});

function Edittimeformnamechange(type)  //type 1 for edit, 2 for add
{
    var subPageName = $("#sidebar ul.menu-categories li.menu ul li.active a").text();
    var mainPagename = $("#sidebar ul.menu-categories li.active a").text();
    var subformname = $("#sidebar ul.menu-categories li.menu ul li.active a").attr('formname');
    var mainformname = $("#sidebar ul.menu-categories li.active a").attr('formname');

    if(type==1)
    {
        if(subPageName){
            $(".formnamelbl").text('Update '+subformname);
        }
        else{
            $(".formnamelbl").text('Update '+mainformname);
        }
    }
    else{
        if(subPageName){
            $(".formnamelbl").text('Add '+subformname);
        }
        else{
            $(".formnamelbl").text('Add '+mainformname);
        }
    }
}


//loading script
function loadScript(src,callback=null){
    var script = document.createElement("script");
    script.type = "text/javascript";
    if(callback)script.onload=callback;
    $("#content").append(script);
    script.src = src;
}

//Convert Time 12 t0 24
function convertTime12to24(time) {
    var PM = time.match('PM') ? true : false
    time = time.split(':')
    var min = time[1]
    if (PM) {
        var hour = 12 + parseInt(time[0],10)
        var sec = time[1].replace('PM', '')
    } else {
        var hour = time[0]
        var sec = time[1].replace('AM', '')       
    }
    return hour + ':' + min + ':' + sec
}

$('body').on('click','#openfilter',function(){
    $("#filterSidebar").toggleClass("active-right-sidebar"); 
    $('.overlay').addClass('show');
    $("body").addClass("overflow-hidden");
});

$('body').on('click', '.none-link', function(e) {    
    $('#filter').val('');
    $('#tableDataList').attr('data-nextpage', 0);
    filterresetdata();
    listdata();
});


    
var errormsgarr={ 
    "passengererror":"Please fill passenger details", 
    "vehicleerror":"Please fill vehicle details", 
    "prooferror": "Please fill proof details",
    "required": "Please fill all required fields",
    "selectpassenger":"Please select at least one passenger",
    "selectvechicle":"Please select vehicle",
    "cancelreason":"Please select reason",
    "selectatleastone":"Please select at least one action",
    "invalidemail":"Please enter valid email address",
    "requiredemail":"Please enter email address",
    "invalidgstno":"Please enter valid gst no",
    "selectdeparture":"Please select departure details",
    "tripnotavailable":"No Trip Available For Selected Date",
    "invalidvehicleno":"Please enter valid vehicle no",
    "invalidproofno":"Please enter valid proof details",
    "membershiperror":"Please add membership data",
    "couponremove":"Coupon removed successfully"
};

function getweekname(date)  //Format : dd/mm/YY
{
    var weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    var datearr = date.split("/");

    var date1 = datearr[1]+'/'+datearr[0]+'/'+datearr[2];
    var a = new Date(date1);
    var weekname = weekday[a.getDay()];

    return weekname;
}



function checkregularexpression(num,expression)
{
    // console.log(num,expression);
    var regex = new RegExp(expression);
    // console.log(regex.test(num));
    return regex.test(num);

}



// Paytm GateWay

function onScriptLoad(txnToken, orderId, amount,dataarray) 
{
  
    var config = {
        "root": "",
        "flow": "DEFAULT",
        "merchant":{
             "logo":"http://192.168.1.2/rahul/paytm/1/assets/logo.png"
         },
         "style":{
             "headerBackgroundColor":"#8dd8ff",
             "headerColor":"#3f3f40"
        },
        "data": {
            "orderId": orderId,
            "token": txnToken,
            "tokenType": "TXN_TOKEN",
            "amount": amount,
            "dataarray": dataarray,
        },
        "handler":{
             "notifyMerchant": function (eventName, data) {
                if(eventName == 'SESSION_EXPIRED'){
                    alert("Your session has expired!!");
                    location.reload();
                }
             }
        }
    };
}