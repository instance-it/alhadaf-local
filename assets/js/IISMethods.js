var views = 'views/';
var currentXhr;


//for a session timeout manage
/*
$.ajaxSetup({
    beforeSend: function(jqXHR) {
    },
    complete: function(jqXHR) {
        if(isJson(jqXHR.responseText)){
            var resultdata = jQuery.parseJSON(jqXHR.responseText);
            if(resultdata.status==-1){ // if jwt token exipre - session out
                ajaxrequest("POST", sessiontimeout, '', '', OnsuccessRender, OnErrorRender);
                var headersdata= {Accept: 'application/json',platform: 1,userpagename:'logout',useraction:'addright',masterlisting:false,responsetype: 'HTML'};
                var formdata = new FormData();
                formdata.append("action", "logout");
                formdata.append("phpself", phpself);
                formdata.append("mastertype", mastertype);
               
                setTimeout(() => {
                    ajaxrequest("POST",endpointurl+'logout',formdata,headersdata,OnsuccessLogout,OnErrorJson);
                }, 2000);
            }
        }
    }
});
*/
//for a session timeout manage


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
                    "couponcode":"Please enter coupon code",
                    "orderhistoryerror":"No order data found",
                    
                };




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
    
    pagenamearry = pagename.split("-");
    var prefix_pagename = pagenamearry[0];

    if(prefix_pagename == 'cd')
    {
        render('coursedetail');
    }
    else
    {
        render(pagename);
    }
});


// Page Render Start
function render(pagename) {

    /*
    var urlpath = window.location.pathname;
    var prev_pagename = urlpath.split("/");
    var prev_pagename = prev_pagename[prev_pagename.length-2];

    if( (prev_pagename == 'vendor' && pagename == 'vendordetail') 
        ||
        (prev_pagename == 'item' && pagename == 'itemdetail') 
        ||
        (prev_pagename == 'service' && pagename == 'servicedetail') 
      )
    {
        views='../'+views;
    }
    */
    $("#preloader").fadeIn();
    ajaxrequest("POST", views + pagename, '', '', OnsuccessRender, OnErrorRender);
}

function OnsuccessRender(content) {
    $("#web-content").html(content);

    $('html,body').animate({ scrollTop: 10 }, 'slow');
    $("#preloader").fadeOut();

    //Set Website Language Wise Label
    setwebsitelanguagelabel();
}

function OnErrorRender(content) {
    $("#preloader").fadeOut();
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

function ajaxrequest(method, url, paramsdata, headerdata, successCallback, errorCallback) {
    currentXhr=$.ajax({
        type: method,
        url: url + '.php',
        headers: headerdata,
        data: paramsdata,
        processData: false,
        cache:false,
        contentType: false,
        error: errorCallback,
        success: successCallback
    });
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


function OnErrorJson(content) {
}




// CHECK REGULAR EXPRESSSION

function checkregularexpression(num,expression)
{
    // console.log(num,expression);
    var regex = new RegExp(expression);
    // console.log(regex.test(num));
    return regex.test(num);

}



