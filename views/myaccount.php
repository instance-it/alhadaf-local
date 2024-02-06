<?php require_once dirname(__DIR__, 1).'\config\config.php';
$config = new config();
?>
<!--page header section start-->
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="breadcrumb-list">
                    <li><a href="home" class="notranslate lan-home-menu">Home</a></li>
                    <li class="active">My Profile</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing gray-light-bg">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-4 col-xl-3 mb-4 mb-lg-0 profile-sidebar">
                <div class="card">
                    <div class="block-content card-body pb-0">
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="user-img-block">
                                    <span class="user-profile-img userprofilebg" style="background-image:url(assets/images/user.png);">
                                        <img class="d-none" id="userprofileimg" src="assets/images/user.png">
                                    </span>
                                    <div class="edit-profile-img profileimgDiv d-none">
                                        <input type="file" class="d-none" name="userDisplayPic" id="userDisplayPic" accept=".jpg,.jpeg,.bmp,.gif,.png,.PNG,.JPG,.JPEG,.BMP,.GIF" class="" value="">
                                        <label for="userDisplayPic" class="user-pic"><i class="user-img fal fa-user-edit"></i></label>
                                    </div>
                                </div>
                                <h4 class="my-2 myaccoutname"></h4>       
                            </div>
                            <div class="col-12 px-0">
                                <ul role="tablist" class="nav flex-column dashboard-list">
                                    <li><a href="#tabprofile" class="nav-link active" data-toggle="tab"><i class="fal fa-user mr-2"></i>Profile</a></li>
                                    <li><a href="#tabMembershipHistory" class="nav-link" data-toggle="tab" onclick="listmembershipdetail(0)"><i class="fal fa-history mr-2"></i>Membership History</a></li>
                                    <li><a href="#tabPackages" class="nav-link" data-toggle="tab" onclick="listpackagedetail(0)"><i class="fal fa-history mr-2"></i>Packages History</a></li>
                                    <li><a href="#tabCourseHistory" class="nav-link" data-toggle="tab" onclick="listcoursedetail(0)"><i class="fal fa-history mr-2" ></i>Course History</a></li>
                                    <li><a href="#tabOrderHistory" class="nav-link" data-toggle="tab" onclick="listorderhistory(0)"><i class="fal fa-history mr-2"></i>Order History</a></li>
                                    <li><a href="#tabchangespassword" class="nav-link" data-toggle="tab"><i class="fal fa-key mr-2"></i>Changes Password</a></li>
                                    <li class="last"><a href="javascript:void(0)" class="nav-link logout-btn" data-toggle="tab"><i class="fal fa-sign-out mr-2"></i>Log Out</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8 col-xl-9 pl-lg-0">
                <div class="tab-content h-100">
                    
                    <!------------------------------------ Start For Profile Update Section -------------------------------------->
                    <div class="tab-pane fade show active card mb-0 h-100" id="tabprofile" role="tabpanel" aria-labelledby="tabprofile">
                        <div class="card-body">
                            <form class="row memberprofileForm" id="memberprofileForm" method="post">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col common-car-head">
                                            <h3>Profile</h3>
                                        </div>
                                        <div class="col-auto ml-auto text-right">
                                            <button type="button" class="btn btn-brand-03 btn-edit-account" id="edit-profile"><i class="fas fa-pencil-alt"></i><span>Edit</span></button>
                                        </div>
                                    </div>    
                                    
                                    <div class="row">
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">First Name</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_fname"></p>
                                                    <input class="my-profile-form-control form-control m_fname d-none" type="text" id="m_fname" name="m_fname" placeholder="First Name *" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Middle Name</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_mname"></p>
                                                    <input class="my-profile-form-control form-control m_mname d-none" type="text" id="m_mname" name="m_mname" placeholder="Middle Name" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Last Name</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_lname"></p>
                                                    <input class="my-profile-form-control form-control m_lname d-none" type="text" id="m_lname" name="m_lname" placeholder="Last Name *" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Email</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_email"></p>
                                                    <input class="my-profile-form-control form-control m_email d-none readonly" type="email" id="m_email" name="m_email" placeholder="Email *" readonly="readonly" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Mobile Number</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_mobile"></p>
                                                    <input class="my-profile-form-control form-control m_mobile d-none readonly" type="text" id="m_mobile" name="m_mobile" maxlength="12" onkeypress="numbonly(event)" placeholder="Mobile Number *" readonly="readonly" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Qatar id Number</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_qataridno"></p>
                                                    <input class="my-profile-form-control form-control m_qataridno d-none" type="text" id="m_qataridno" name="m_qataridno" placeholder="Qatar id Number "  value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Qatar id Expiry</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_qataridexpiry"></p>
                                                    <input class="my-profile-form-control form-control m_qataridexpiry d-none" type="text" id="m_qataridexpiry" name="m_qataridexpiry" autocomplete="off" placeholder="Qatar id Expiry " readonly="readonly" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Passport id Number</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_passportidno"></p>
                                                    <input class="my-profile-form-control form-control m_passportidno d-none" type="text" id="m_passportidno" name="m_passportidno" placeholder="Passport id Number "  value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Passport id Expiry</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_passportidexpiry"></p>
                                                    <input class="my-profile-form-control form-control m_passportidexpiry d-none" type="text" id="m_passportidexpiry" name="m_passportidexpiry" autocomplete="off" placeholder="Passport id Expiry " readonly="readonly" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Date of Birth</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_dob"></p>
                                                    <input class="my-profile-form-control form-control m_dob d-none" type="text" id="m_dob" name="m_dob" autocomplete="off" placeholder="Date of Birth *" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Nationality</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_nationality"></p>
                                                    <input class="my-profile-form-control form-control m_nationality d-none" type="text" id="m_nationality" name="m_nationality" placeholder="Nationality *" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Company</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_companyname"></p>
                                                    <input class="my-profile-form-control form-control m_companyname d-none" type="text" id="m_companyname" name="m_companyname" placeholder="Company" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 list-profile pr-0">
                                            <div class="row mx-0">
                                                <div class="col-4 col-lg-3 col-xl-2 pl-2 pr-0">
                                                    <p class="m-0">Address</p>
                                                </div>
                                                <div class="col-8 col-lg-9 col-xl-10 ans-data ans-data-edit pr-0 pl-2">
                                                    <p class="m-0 m_txt_address"></p>
                                                    <textarea class="my-profile-form-control form-control m_address d-none" type="text" id="m_address" name="m_address" placeholder="Address"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                    <!------------------------------------ End For Profile Update Section -------------------------------------->

                    <!------------------------------------ Start For Membership History Section -------------------------------------->
                    <div class="tab-pane fade card mb-0 h-100" id="tabMembershipHistory"  role="tabpanel" aria-labelledby="tabMembershipHistory">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="common-car-head">
                                                <h3>Membership</h3>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-12 d-none d-xl-block">
                                    <div class="row mx-0">
                                        <div class="col-12 heading-list-profile px-0">
                                            <div class="row mx-0">
                                                <div class="col-sm-3 pr-1 pl-2">
                                                    <h6>Order No. / Date</h6>
                                                </div>
                                                <div class="col-sm-3 px-1">
                                                    <h6>Membership Details</h6>
                                                </div>
                                                <div class="col-sm-2 px-1">
                                                    <h6>Validity</h6>
                                                </div>
                                                <div class="col-sm-2 px-1">
                                                    <h6>Amount</h6>
                                                </div>
                                                <div class="col-sm-2 px-1">
                                                    <h6>Status</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 list-profile-content">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ End For Membership History Section -------------------------------------->

                    <!------------------------------------ Start For Packages History Section -------------------------------------->
                    <div class="tab-pane fade card mb-0 h-100" id="tabPackages"  role="tabpanel" aria-labelledby="tabPackages">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="common-car-head">
                                                <h3>Packages</h3>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-12 d-none d-xl-block">
                                    <div class="row mx-0">
                                        <div class="col-12 heading-list-profile px-0">
                                            <div class="row mx-0">
                                                <div class="col-sm-3 pr-1 pl-2">
                                                    <h6>Order No. / Date</h6>
                                                </div>
                                                <div class="col-sm-3 px-1">
                                                    <h6>Package Details</h6>
                                                </div>
                                                <div class="col-sm-2 px-1">
                                                    <h6>Validity</h6>
                                                </div>
                                                <div class="col-sm-2 px-1">
                                                    <h6>Amount</h6>
                                                </div>
                                                <div class="col-sm-2 px-1">
                                                    <h6>Status</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 list-profile-content">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ End For Packages History Section -------------------------------------->

                    <!------------------------------------ Start For Course History Section -------------------------------------->
                    <div class="tab-pane fade card mb-0 h-100" id="tabCourseHistory"  role="tabpanel" aria-labelledby="tabCourseHistory">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="common-car-head">
                                                <h3>Course History</h3>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-12 d-none d-xl-block">
                                    <div class="row mx-0">
                                        <div class="col-12 heading-list-profile px-0">
                                            <div class="row mx-0">
                                                <div class="col-sm-3 pr-1 pl-2">
                                                    <h6>Order No. / Date</h6>
                                                </div>
                                                <div class="col-sm-3 px-1">
                                                    <h6>Course Details</h6>
                                                </div>
                                                <div class="col-sm-2 px-1">
                                                    <h6>Validity</h6>
                                                </div>
                                                <div class="col-sm-2 px-1">
                                                    <h6>Amount</h6>
                                                </div>
                                                <div class="col-sm-2 px-1">
                                                    <h6>Status</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 list-profile-content">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ End For Course History Section -------------------------------------->

                    <!------------------------------------ Start For Order History Section -------------------------------------->
                    <div class="tab-pane fade card mb-0 h-100" id="tabOrderHistory"  role="tabpanel" aria-labelledby="tabOrderHistory">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="common-car-head">
                                                <h3>Order History</h3>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-12 d-none d-xl-block">
                                    <div class="row mx-0">
                                        <div class="col-12 heading-list-profile px-0">
                                            <div class="row mx-0">
                                                <div class="col-sm-4 pr-1 pl-2">
                                                    <h6>Order No</h6>
                                                </div>
                                                <div class="col-sm-4 px-1">
                                                    <h6>Order Date</h6>
                                                </div>
                                                <div class="col-sm-4 px-1">
                                                    <h6>Amount</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 list-profile-content">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ End For Order History Section -------------------------------------->


                    
                    <!------------------------------------ Start For Change Password Section -------------------------------------->
                    <div class="tab-pane fade card mb-4 h-100" id="tabchangespassword" role="tabpanel" aria-labelledby="tabprofile">
                        <div class="card-body">
                            <form class="row" method="post" id="changepasswordForm" novalidate="true">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 common-car-head">
                                            <h3>Changes Password</h3>
                                        </div>
                                    </div>    
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group validate-input">
                                                        <input class="form-control m_oldpassword" type="Password" id="m_oldpassword" name="m_oldpassword" required="required">
                                                        <span class="focus-form-control"></span>
                                                        <label class="label-form-control" for="m_oldpassword">Old Password <span class="text-danger">*</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group validate-input">
                                                        <input class="form-control m_newpassword" type="Password" id="m_newpassword" name="m_newpassword" required="required">
                                                        <span class="focus-form-control"></span>
                                                        <label class="label-form-control" for="m_newpassword">New Password <span class="text-danger">*</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group validate-input">
                                                        <input class="form-control m_cpassword" type="Password" id="m_cpassword" name="m_cpassword" required="required">
                                                        <span class="focus-form-control"></span>
                                                        <label class="label-form-control" for="m_cpassword">Confirm Password <span class="text-danger">*</span></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-brand-01" type="submit"><?php echo $config->getChangePassword() ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!------------------------------------ End For Change Password Section -------------------------------------->



                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(){
        $("ul.main-navbar-nav li.nav-item").removeClass("active");
        $("ul.main-navbar-nav li.limyaccount").addClass("active");
        $(".mobile-navigation li").removeClass("active");
        $(".mobile-navigation li.limyaccount").addClass("active");
        

        userDisplayPic.onchange = evt => 
        {
            const [file] = userDisplayPic.files
            if (file) 
            {
                userprofileimg.src = URL.createObjectURL(file);
                var imgurluserDisplayPic = $('#userprofileimg').attr('src');

                $(".userprofilebg").css("background-image", "url(" + imgurluserDisplayPic + ")");
            }
        }

        //List Profile Data
        listprofiledata();
    });


    /************************* Start For Logout Section ******************************/
    $('.logout-btn').on('click', function () {
        $("#preloader").fadeIn();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:'logout',useraction:'addright',masterlisting:false,responsetype: 'JSON'};
        var formdata = new FormData();
        formdata.append("action", "logout");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'logout',formdata,headersdata,OnsuccessLogout,OnErrorJson);
    });

    function OnsuccessLogout(content)
    {
        $("#preloader").fadeOut();
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);
        if(resultdata.redirecturl)
        {
            window.location.href = resultdata.redirecturl;
        }
    }

    function OnErrorJson(content) {
        $("#preloader").fadeOut();
    }
    /************************* End For Logout Section ******************************/



    /************************* Start For Profile Section ******************************/
    //Date Picker Call    
    new When({
        input: document.getElementById('m_qataridexpiry'),
        singleDate: true,
        outputFormat: "DD/MM/YYYY",
        showHeader: false,
    });
    new When({
        input: document.getElementById('m_passportidexpiry'),
        singleDate: true,
        outputFormat: "DD/MM/YYYY",
        showHeader: false,
    });
    new When({
        input: document.getElementById('m_dob'),
        singleDate: true,
        outputFormat: "DD/MM/YYYY",
        showHeader: false,
    });
    

    //List Profile Data
    function listprofiledata()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 4,userpagename:pagename,useraction:'',masterlisting:false,responsetype: 'HTML'};

        formdata = new FormData();
        formdata.append("action", "listprofiledata");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccesslistprofiledata,OnErrorlistprofiledata);
    }

    function Onsuccesslistprofiledata(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        if(resultdata.status==0)
        {
            toastr.error(resultdata.message);
        }
        else if(resultdata.status==1)
        {
            var i=0;

            $('#memberprofileForm #m_fname').val(resultdata.profiledata[i].firstname);
            $('#memberprofileForm .m_txt_fname').text(resultdata.profiledata[i].firstname);

            $('#memberprofileForm #m_mname').val(resultdata.profiledata[i].middlename);
            $('#memberprofileForm .m_txt_mname').text(resultdata.profiledata[i].middlename);

            $('#memberprofileForm #m_lname').val(resultdata.profiledata[i].lastname);
            $('#memberprofileForm .m_txt_lname').text(resultdata.profiledata[i].lastname);

            $('#memberprofileForm #m_email').val(resultdata.profiledata[i].email);
            $('#memberprofileForm .m_txt_email').text(resultdata.profiledata[i].email);

            $('#memberprofileForm #m_mobile').val(resultdata.profiledata[i].contact);
            $('#memberprofileForm .m_txt_mobile').text(resultdata.profiledata[i].contact);

            $('#memberprofileForm #m_qataridno').val(resultdata.profiledata[i].qataridno);
            $('#memberprofileForm .m_txt_qataridno').text(resultdata.profiledata[i].qataridno);

            $('#memberprofileForm #m_qataridexpiry').val(resultdata.profiledata[i].qataridexpiry);
            $('#memberprofileForm .m_txt_qataridexpiry').text(resultdata.profiledata[i].qataridexpiry);

            $('#memberprofileForm #m_passportidno').val(resultdata.profiledata[i].passportidno);
            $('#memberprofileForm .m_txt_passportidno').text(resultdata.profiledata[i].passportidno);

            $('#memberprofileForm #m_passportidexpiry').val(resultdata.profiledata[i].passportidexpiry);
            $('#memberprofileForm .m_txt_passportidexpiry').text(resultdata.profiledata[i].passportidexpiry);

            $('#memberprofileForm #m_dob').val(resultdata.profiledata[i].dob);
            $('#memberprofileForm .m_txt_dob').text(resultdata.profiledata[i].dob);

            $('#memberprofileForm #m_nationality').val(resultdata.profiledata[i].nationality);
            $('#memberprofileForm .m_txt_nationality').text(resultdata.profiledata[i].nationality);

            $('#memberprofileForm #m_companyname').val(resultdata.profiledata[i].companyname);
            $('#memberprofileForm .m_txt_companyname').text(resultdata.profiledata[i].companyname);

            $('#memberprofileForm #m_address').val(resultdata.profiledata[i].address);
            $('#memberprofileForm .m_txt_address').text(resultdata.profiledata[i].address);

            $(".myaccoutname").html(resultdata.profiledata[i].personname);
            $(".userprofilebg").css("background-image", "url(" + resultdata.profiledata[i].profileimg + ")");

            new When({
                input: document.getElementById('m_qataridexpiry'),
                singleDate: true,
                outputFormat: "DD/MM/YYYY",
                showHeader: false,
                startDate: "2021-04-08",
            });

            new When({
                input: document.getElementById('m_passportidexpiry'),
                singleDate: true,
                outputFormat: "DD/MM/YYYY",
                showHeader: false,
                startDate: "2021-04-08",
            });
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 
    }
    
    function OnErrorlistprofiledata(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }



    //Click Event Of Edit Account
    // $(".btn-edit-account").on('click', function(){
    //     $(".ans-data p").toggleClass("d-none");
    //     $(".ans-data").find(".form-control").toggleClass("d-none");
    //     $(".ans-data").find(".btn-add-save").toggleClass("d-none");
    //     $(this).find("span").html($(this).find("span").html() == 'Edit' ? 'Save' : 'Edit');
    //     $(this).toggleClass("active btnupdateprofile");
    //     $(this).find("i").toggleClass("fa-pencil-alt fa-save");

    //     if($(".btn-edit-account").hasClass('btnupdateprofile'))
    //     {
    //         $('.edit-profile-img').removeClass('d-none');
    //     }
    //     else
    //     {
    //         $('.edit-profile-img').addClass('d-none');

    //         $('#memberprofileForm').valid();
    //     }
        
    // });


    $('#edit-profile').on('click', function(){
        
        $(".ans-data p").toggleClass("d-none");
        $(".ans-data").find(".form-control").toggleClass("d-none");
        $(".ans-data").find(".btn-add-save").toggleClass("d-none");
        $(this).find("span").html($(this).find("span").html() == 'Edit' ? 'Save' : 'Edit');
        $(this).toggleClass("active btnupdateprofile");
        $(this).find("i").toggleClass("fa-pencil-alt fa-save");
    

        if($(this).find("span").html() == 'Save')
        {
            $('#edit-profile').removeClass('save-myaccount');
            $('.profileimgDiv').removeClass('d-none');
            //$('.verifiedDiv').addClass('d-none');
        }
        else
        {
            $('#edit-profile').addClass('save-myaccount');
            $('.profileimgDiv').addClass('d-none');
            //$('.verifiedDiv').removeClass('d-none');
        }
      
        

        if($(this).hasClass("save-myaccount")==true)
        {
            $("#preloader").fadeIn();
            var pagename=getpagename();
            formdata = new FormData();
            formdata.append("action", "updateprofiledata");
            var files = $('#userDisplayPic')[0].files;

            formdata.append("m_fname",$('#memberprofileForm #m_fname').val());
            formdata.append("m_mname",$('#memberprofileForm #m_mname').val());
            formdata.append("m_lname",$('#memberprofileForm #m_lname').val());
            formdata.append("m_email",$('#memberprofileForm #m_email').val());
            formdata.append("m_mobile",$('#memberprofileForm #m_mobile').val());
            formdata.append("m_qataridno",$('#memberprofileForm #m_qataridno').val());
            formdata.append("m_qataridexpiry",$('#memberprofileForm #m_qataridexpiry').val());
            formdata.append("m_passportidno",$('#memberprofileForm #m_passportidno').val());
            formdata.append("m_passportidexpiry",$('#memberprofileForm #m_passportidexpiry').val());
            formdata.append("m_dob",$('#memberprofileForm #m_dob').val());
            formdata.append("m_nationality",$('#memberprofileForm #m_nationality').val());
            formdata.append("m_companyname",$('#memberprofileForm #m_companyname').val());
            formdata.append("m_address",$('#memberprofileForm #m_address').val());
            formdata.append('userprofileimg',files[0]);

			var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
			ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessupdateprofile,OnErrorupdateprofile); 
 

            function Onsuccessupdateprofile(content)
            {
                var JsonData = JSON.stringify(content);
                var resultdata = jQuery.parseJSON(JsonData);
                $("#preloader").fadeOut();

                if(resultdata.status==0)
                {
                    toastr.error(resultdata.message);
                }
                else if(resultdata.status==1)
                {
                    toastr.success(resultdata.message);
                    listprofiledata();
                }
                else if(resultdata.status=-1)
                {
                    logoutwebsitepage();
                } 
            }

            function OnErrorupdateprofile(content)
            { 
                $("#preloader").fadeOut();
                ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
            }
        }    

        
    });


    
    /************************* End For Profile Section ******************************/



    /************************* Start For Change Password Section ******************************/
    //Validate Change Password Form
    if($('#changepasswordForm').length){		
        $('#changepasswordForm').validate({
            rules:{
                m_oldpassword:{
                    required: true,
                },
                m_newpassword:{
                    required: true,
                },
                m_cpassword:{
                    required: true,
                    equalTo:'#m_newpassword',
                }
            },messages:{
                m_oldpassword:{
                    required: "Old password is required",				
                },
                m_newpassword:{
                    required: "New password is required",				
                },
                m_cpassword:{
                    required: "Confirm password is required",
                    equalTo:"Confirm password must be same as new password"					
                }
            },
            submitHandler: function(form){

                $("#preloader").fadeIn();
                var pagename=getpagename();
                formdata = new FormData(form);
                formdata.append("action", "changepassword");
                var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
                ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+pagename,formdata,headersdata,Onsuccessmemberchangepassword,OnErrormemberchangepassword); 
                
            },
        });
    }


    function Onsuccessmemberchangepassword(content)
    {
        var JsonData = JSON.stringify(content);
        var resultdata = jQuery.parseJSON(JsonData);

        $("#preloader").fadeOut();

        if(resultdata.status==0)
        {
            toastr.error(resultdata.message);
        }
        else if(resultdata.status==1)
        {
            $("#changepasswordForm").validate().resetForm();
            $('#changepasswordForm').trigger("reset");
            toastr.success(resultdata.message);
        }
        else if(resultdata.status=-1)
        {
            logoutwebsitepage();
        } 
    }


    function OnErrormemberchangepassword(content)
    { 
        ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
    }


    /************************* END For Change Password Section ******************************/

    /************************* Start For List Order Hisotry Section ******************************/

    function listorderhistory(page)
    {
        var nextpage=parseInt(page)+1;
        var perpage=10;

        $("#preloader").fadeIn();
        var pagename=getpagename();
        formdata = new FormData();
        formdata.append("action", "listorderhistory");
        formdata.append("nextpage", nextpage);
        formdata.append("perpage", perpage);
        var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'orderhistory',formdata,headersdata,Onsuccesslistorderhistory,OnErrorlistorderhistory); 


        function Onsuccesslistorderhistory(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            $("#preloader").fadeOut();
            var htmldata = '';
            if(resultdata.status==0)
            {
                htmldata+='<div class="row mx-0">';
                    htmldata+='<div class="col-12 list-profile px-0">';
                        htmldata+='<h5><center><b>'+resultdata.message+'</b></center></h5>';
                    htmldata+='</div>';
                htmldata+='</div>';
            }
            else if(resultdata.status==1)
            {
                if(resultdata.isorderhistorydata == 1)
                {
                    for(var i in resultdata.orderhistorydata)
                    {
                        
                        htmldata+='<div class="row mx-0">';
                            htmldata+='<div class="col-12 list-profile px-0">';
                                htmldata+='<div class="row mx-0">';
                                    htmldata+='<div class="col-6 col-sm-4 col-xl-4 px-1 mb-2 mb-xl-0">';
                                        htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Order No: </b>'+resultdata.orderhistorydata[i].orderno+' </p>';
                                    htmldata+='</div>';
                                    htmldata+='<div class="col-6 col-sm-4 col-xl-4 px-1 mb-2 mb-xl-0">';
                                        htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Order Date: </b>'+resultdata.orderhistorydata[i].ofulldate+'</p>';
                                    htmldata+='</div>';
                                    htmldata+='<div class="col-5 col-sm-3 col-xl-3 px-1 mb-2 mb-xl-0">';
                                        htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Amount: </b> Qr '+parseFloat(resultdata.orderhistorydata[i].totalpaid).toFixed(2)+'</p>';
                                    htmldata+='</div>';
                                    htmldata+='<div class="col-1 col-sm-1 col-xl-1 px-1 mb-2 mb-xl-0">';
                                        htmldata+='<p class="m-0"><a href="#collapseOrderHistory1" class="list-profile-collapse collapsed" data-toggle="collapse" data-target="#collapseOrderHistory'+resultdata.orderhistorydata[i].id+'"><i class="far fa-angle-down"></i></a></p>';
                                    htmldata+='</div>';
                                
                                    htmldata+='<div class="col-12 px-0 collapse" id="collapseOrderHistory'+resultdata.orderhistorydata[i].id+'">';
                                        htmldata+='<div class="row mt-3 px-3">';
                                            htmldata+='<div class="col-12 table-body-content">';
                                                htmldata+='<div class="row d-none d-md-flex plan-table-header">';
                                                    htmldata+='<div class="col-12 col-md-4">';
                                                        htmldata+='<h4>Membership</h4>';
                                                    htmldata+='</div>';
                                                    htmldata+='<div class="col-12 col-md-2">';
                                                        htmldata+='<h4>Validity</h4>';
                                                    htmldata+='</div>';
                                                    htmldata+='<div class="col-12 col-md-3">';
                                                        htmldata+='<h4>Price</h4>';
                                                    htmldata+='</div>';
                                                    htmldata+='<div class="col-12 col-md-3 text-md-right">';
                                                        htmldata+='<h4>Amount</h4>';
                                                    htmldata+='</div>';
                                                htmldata+='</div>';


                                                for(var j in resultdata.orderhistorydata[i].orderdetailinfo)
                                                {
                                                    htmldata+='<div class="row membership-row">';
                                                        htmldata+='<div class="col-12">';
                                                            htmldata+='<div class="row">';
                                                                htmldata+='<div class="col-12 col-md-4 membership-detail">';
                                                                    htmldata+='<h4 class="mob-plan-header d-md-none">Membership</h4>';
                                                                    htmldata+='<div class="membership-plan-table-name">';
                                                                        htmldata+='<a href="javascript:void(0)" class="viewfulldetails" data-oid ='+resultdata.orderhistorydata[i].orderdetailinfo[j].orderid+' data-odid ='+resultdata.orderhistorydata[i].orderdetailinfo[j].id+'>'+resultdata.orderhistorydata[i].orderdetailinfo[j].itemname+'</a>';
                                                                    htmldata+='</div>';
                                                                htmldata+='</div>';
                                                                htmldata+='<div class="col-12-xs col-6 col-sm-4 col-md-2 membership-validity-detail">';
                                                                    htmldata+='<h4 class="mob-plan-header d-md-none">Validity</h4>';
                                                                    htmldata+='<div class="membership-plan-table-validity">';
                                                                        htmldata+=''+resultdata.orderhistorydata[i].orderdetailinfo[j].strvalidityduration+'';
                                                                    htmldata+='</div>';
                                                                htmldata+='</div>';
                                                                htmldata+='<div class="col-12-xs col-6 col-sm-4 col-md-3 membership-validity-detail">';
                                                                    htmldata+='<h4 class="mob-plan-header d-md-none mt-2">Price</h4>';
                                                                    htmldata+='<div class="membership-plan-table-validity">';
                                                                    htmldata+='Qr '+parseFloat(resultdata.orderhistorydata[i].orderdetailinfo[j].price).toFixed(2)+'';
                                                                    htmldata+='</div>';
                                                                htmldata+='</div>';
                                                                htmldata+='<div class="col-12-xs col-6 col-sm-4 col-md-3 membership-price-detail">';
                                                                    htmldata+='<h4 class="mob-plan-header d-md-none mt-2">Amount</h4>';
                                                                    htmldata+='<div class="membership-plan-table-price text-md-right">';
                                                                        htmldata+='Qr '+parseFloat(resultdata.orderhistorydata[i].orderdetailinfo[j].finalprice).toFixed(2)+'';
                                                                    htmldata+='</div>';
                                                                htmldata+='</div>';
                                                            htmldata+='</div>';
                                                        htmldata+='</div>';
                                                        htmldata+='<div class="col-12">';
                                                            htmldata+='<div class="row">';
                                                                htmldata+='<div class="col-12 col-md mt-2 my-lg-auto">';
                                                                    if(resultdata.orderhistorydata[i].orderdetailinfo[j].strexpire)
                                                                    {
                                                                        htmldata+='<p class="m-0" style="color:'+resultdata.orderhistorydata[i].orderdetailinfo[j].strexpirecolor+'">Your '+resultdata.orderhistorydata[i].orderdetailinfo[j].typename+' '+resultdata.orderhistorydata[i].orderdetailinfo[j].strexpire+'</strong>.</p>';
                                                                    }
                                                                htmldata+='</div> ';
                                                            htmldata+='</div>  ';
                                                        htmldata+='</div>';
                                                    htmldata+='</div>';
                                                }
                                                


                                            htmldata+='</div>';

                                            

                                            htmldata+='<div class="col-12 col-sm-6 ml-auto mt-3 px-0">';
                                                htmldata+='<div class="cart-totals-inner border">';
                                                    htmldata+='<div class="cart-subtotal">';
                                                        htmldata+='<ul class="m-0">';
                                                            htmldata+='<li>';
                                                                htmldata+='<p>Subtotal</p>';
                                                                htmldata+='<p class="cart-amount">Qr '+parseFloat(resultdata.orderhistorydata[i].totalamount).toFixed(2)+'</p>';
                                                            htmldata+='</li>';
                                                            htmldata+='<li>';
                                                                htmldata+='<p>Taxable Amount</p>';
                                                                htmldata+='<p class="cart-amount">Qr '+parseFloat(resultdata.orderhistorydata[i].totaltaxableamt).toFixed(2)+'</p>';
                                                            htmldata+='</li>';
                                                            htmldata+='<li>';
                                                                htmldata+='<p>Tax Amount</p>';
                                                                htmldata+='<p class="cart-amount">Qr '+parseFloat(resultdata.orderhistorydata[i].totaltax).toFixed(2)+'</p>';
                                                            htmldata+='</li>';
                                                            htmldata+='<li class="cart-total-price">';
                                                                htmldata+='<p>Total</p>';
                                                                htmldata+='<p class="cart-amount">Qr '+parseFloat(resultdata.orderhistorydata[i].totalpaid).toFixed(2)+'</p>';
                                                            htmldata+='</li>';

                                                            if(resultdata.orderhistorydata[i].iscancel == 1)
                                                            {
                                                                htmldata+='<li>';
                                                                    htmldata+='<p class="cancel-order-label">Cancelled</p>';
                                                                htmldata+='</li>';
                                                            }    
                                                        htmldata+='</ul>';
                                                    htmldata+='</div>';
                                                htmldata+='</div>';
                                            htmldata+='</div>';

                                        htmldata+='</div>';
                                    htmldata+='</div>';
                                
                                htmldata+='</div>';
                            htmldata+='</div>';
                        htmldata+='</div>';
                    }

                    if(resultdata.loadmore == 1)
                    {
                        htmldata+='<div class="row">';
                            htmldata+='<div class="col-12 text-center">';
                                htmldata+='<button class="btn btn-brand-01 btnloadmore" type="button" data-nextpage = "'+resultdata.nextpage+'">Show More</button>';
                            htmldata+='</div>';
                        htmldata+='</div>';
                    }
                }
            }
            else if(resultdata.status=-1)
            {
                logoutwebsitepage();
            } 

            if(page==0)
            {   
                $('#tabOrderHistory .list-profile-content').html(htmldata)                                         
            }
            else
            {
                $('#tabOrderHistory .list-profile-content').append(htmldata)
            }
            
           
        }


        function OnErrorlistorderhistory(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
    }


    $('body').on('click','.btnloadmore',function(){
        $(this).parent().hide();
        var nextpage  = $(this).attr('data-nextpage');
        listorderhistory(nextpage)
    });

    /************************* END For List Order Hisotry Section ******************************/

    /************************* Start For List Membership Hisotry Section ******************************/

    function listmembershipdetail(page)
    {
        var nextpage=parseInt(page)+1;
        var perpage=10;

        $("#preloader").fadeIn();
        var pagename=getpagename();
        formdata = new FormData();
        formdata.append("action", "listorderdetail");
        formdata.append("type", "1");
        formdata.append("nextpage", nextpage);
        formdata.append("perpage", perpage);
        var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'orderhistory',formdata,headersdata,Onsuccesslistmembershipdetail,OnErrorlistmembershipdetail); 


        function Onsuccesslistmembershipdetail(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            $("#preloader").fadeOut();
            var htmldata = '';
            if(resultdata.status==0)
            {
                htmldata+='<div class="row mx-0">';
                    htmldata+='<div class="col-12 list-profile px-0">';
                        htmldata+='<h5><center><b>'+resultdata.message+'</b></center></h5>';
                    htmldata+='</div>';
                htmldata+='</div>';
            }
            else if(resultdata.status==1)
            {
                if(resultdata.isorderdetail == 1)
                {
                    for(var i in resultdata.orderdetail)
                    {           
                        htmldata+='<div class="col-12 list-profile px-0">';
                            htmldata+='<div class="row mx-0">';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-3 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Order No. / Date: </b>'+resultdata.orderdetail[i].orderno+' <span class="d-block">'+resultdata.orderdetail[i].ofulldate+'</span></p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-3 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Membership: </b> <a href="javascript:void(0)" class="viewfulldetails" data-oid="'+resultdata.orderdetail[i].orderid+'" data-odid="'+resultdata.orderdetail[i].id+'">'+resultdata.orderdetail[i].itemname+'</a></p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-2 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Validity: </b> '+resultdata.orderdetail[i].strvalidityduration+'</p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-2 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Amount: </b> Qr '+parseFloat(resultdata.orderdetail[i].finalprice).toFixed(2)+'</p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-2 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Status: </b><span style="color:'+resultdata.orderdetail[i].strexpirecolor+'">'+resultdata.orderdetail[i].strexpirestatus+'</span></p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-12 col-md-12 my-lg-auto px-1">';
                                    if(resultdata.orderdetail[i].strexpire)
                                    {
                                        htmldata+='<p class="m-0" style="color:'+resultdata.orderdetail[i].strexpirecolor+'">Your '+resultdata.orderdetail[i].typename+' '+resultdata.orderdetail[i].strexpire+'</strong>.</p>';
                                    }
                                htmldata+='</div>';
                            htmldata+='</div>';
                        htmldata+='</div>';
                    }

                    if(resultdata.loadmore == 1)
                    {
                        htmldata+='<div class="row">';
                            htmldata+='<div class="col-12 text-center">';
                                htmldata+='<button class="btn btn-brand-01 btnloadmoredetail" data-type = "1" type="button" data-nextpage = "'+resultdata.nextpage+'">Show More</button>';
                            htmldata+='</div>';
                        htmldata+='</div>';
                    }
                }
            }
            else if(resultdata.status=-1)
            {
                logoutwebsitepage();
            } 

            if(page==0)
            {   
                $('#tabMembershipHistory .list-profile-content').html(htmldata)                                         
            }
            else
            {
                $('#tabMembershipHistory .list-profile-content').append(htmldata)
            }
        }

        function OnErrorlistmembershipdetail(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
    }

    /************************* END For List Membership Hisotry Section ******************************/

    /************************* Start For List Package Hisotry Section ******************************/

    function listpackagedetail(page)
    {
        var nextpage=parseInt(page)+1;
        var perpage=10;

        $("#preloader").fadeIn();
        var pagename=getpagename();
        formdata = new FormData();
        formdata.append("action", "listorderdetail");
        formdata.append("type", "2");
        formdata.append("nextpage", nextpage);
        formdata.append("perpage", perpage);
        var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'orderhistory',formdata,headersdata,Onsuccesslistpackagedetail,OnErrorlistpackagedetail); 


        function Onsuccesslistpackagedetail(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            $("#preloader").fadeOut();
            var htmldata = '';
            if(resultdata.status==0)
            {
                htmldata+='<div class="row mx-0">';
                    htmldata+='<div class="col-12 list-profile px-0">';
                        htmldata+='<h5><center><b>'+resultdata.message+'</b></center></h5>';
                    htmldata+='</div>';
                htmldata+='</div>';
            }
            else if(resultdata.status==1)
            {
                if(resultdata.isorderdetail == 1)
                {
                    for(var i in resultdata.orderdetail)
                    {           
                        htmldata+='<div class="col-12 list-profile px-0">';
                            htmldata+='<div class="row mx-0">';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-3 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Order No. / Date: </b>'+resultdata.orderdetail[i].orderno+' <span class="d-block">'+resultdata.orderdetail[i].ofulldate+'</span></p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-3 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Membership: </b> <a href="javascript:void(0)" class="viewfulldetails" data-oid="'+resultdata.orderdetail[i].orderid+'" data-odid="'+resultdata.orderdetail[i].id+'">'+resultdata.orderdetail[i].itemname+'</a></p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-2 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Validity: </b> '+resultdata.orderdetail[i].strvalidityduration+'</p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-2 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Amount: </b> Qr '+parseFloat(resultdata.orderdetail[i].finalprice).toFixed(2)+'</p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-2 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Status: </b><span style="color:'+resultdata.orderdetail[i].strexpirecolor+'">'+resultdata.orderdetail[i].strexpirestatus+'</span></p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-12 col-md-12 my-lg-auto px-1">';
                                    if(resultdata.orderdetail[i].strexpire)
                                    {
                                        htmldata+='<p class="m-0" style="color:'+resultdata.orderdetail[i].strexpirecolor+'">Your '+resultdata.orderdetail[i].typename+' '+resultdata.orderdetail[i].strexpire+'</strong>.</p>';
                                    }
                                htmldata+='</div>';
                            htmldata+='</div>';
                        htmldata+='</div>';
                    }

                    if(resultdata.loadmore == 1)
                    {
                        htmldata+='<div class="row">';
                            htmldata+='<div class="col-12 text-center">';
                                htmldata+='<button class="btn btn-brand-01 btnloadmoredetail" data-type = "2"  type="button" data-nextpage = "'+resultdata.nextpage+'">Show More</button>';
                            htmldata+='</div>';
                        htmldata+='</div>';
                    }
                }
            }
            else if(resultdata.status=-1)
            {
                logoutwebsitepage();
            } 

            if(page==0)
            {   
                $('#tabPackages .list-profile-content').html(htmldata)                                         
            }
            else
            {
                $('#tabPackages .list-profile-content').append(htmldata)
            }
            
           
        }


        function OnErrorlistpackagedetail(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
    }

    /************************* END For List Package Hisotry Section ******************************/


    /************************* Start For List Course Hisotry Section ******************************/

    function listcoursedetail(page)
    {
        var nextpage=parseInt(page)+1;
        var perpage=10;

        $("#preloader").fadeIn();
        var pagename=getpagename();
        formdata = new FormData();
        formdata.append("action", "listorderdetail");
        formdata.append("type", "3");
        formdata.append("nextpage", nextpage);
        formdata.append("perpage", perpage);
        var headersdata= {'Accept': 'application/json',platform: 4,userpagename:'',useraction:'',masterlisting:false};
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'orderhistory',formdata,headersdata,Onsuccesslistcoursedetail,OnErrorlistcoursedetail); 


        function Onsuccesslistcoursedetail(content)
        {
            var JsonData = JSON.stringify(content);
            var resultdata = jQuery.parseJSON(JsonData);

            $("#preloader").fadeOut();
            var htmldata = '';
            if(resultdata.status==0)
            {
                htmldata+='<div class="row mx-0">';
                    htmldata+='<div class="col-12 list-profile px-0">';
                        htmldata+='<h5><center><b>'+resultdata.message+'</b></center></h5>';
                    htmldata+='</div>';
                htmldata+='</div>';
            }
            else if(resultdata.status==1)
            {
                if(resultdata.isorderdetail == 1)
                {
                    for(var i in resultdata.orderdetail)
                    {           
                        htmldata+='<div class="col-12 list-profile px-0">';
                            htmldata+='<div class="row mx-0">';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-3 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Order No. / Date: </b>'+resultdata.orderdetail[i].orderno+' <span class="d-block">'+resultdata.orderdetail[i].ofulldate+'</span></p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-3 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Membership: </b> <a href="javascript:void(0)" class="viewfulldetails" data-oid="'+resultdata.orderdetail[i].orderid+'" data-odid="'+resultdata.orderdetail[i].id+'">'+resultdata.orderdetail[i].itemname+'</a></p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-2 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Validity: </b> '+resultdata.orderdetail[i].strvalidityduration+'</p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-2 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Amount: </b> Qr '+parseFloat(resultdata.orderdetail[i].finalprice).toFixed(2)+'</p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-6 col-sm-4 col-xl-2 px-1 mb-2 mb-xl-0">';
                                    htmldata+='<p class="m-0"><b class="d-block d-xl-none d-sm-inline-block">Status: </b><span style="color:'+resultdata.orderdetail[i].strexpirecolor+'">'+resultdata.orderdetail[i].strexpirestatus+'</span></p>';
                                htmldata+='</div>';
                                htmldata+='<div class="col-12 col-md-12 my-lg-auto px-1">';
                                    if(resultdata.orderdetail[i].strexpire)
                                    {
                                        htmldata+='<p class="m-0" style="color:'+resultdata.orderdetail[i].strexpirecolor+'">Your '+resultdata.orderdetail[i].typename+' '+resultdata.orderdetail[i].strexpire+'</strong>.</p>';
                                    }
                                htmldata+='</div>';
                            htmldata+='</div>';
                        htmldata+='</div>';
                    }

                    if(resultdata.loadmore == 1)
                    {
                        htmldata+='<div class="row">';
                            htmldata+='<div class="col-12 text-center">';
                                htmldata+='<button class="btn btn-brand-01 btnloadmoredetail" data-type = "3"  type="button" data-nextpage = "'+resultdata.nextpage+'">Show More</button>';
                            htmldata+='</div>';
                        htmldata+='</div>';
                    }
                }
            }
            else if(resultdata.status=-1)
            {
                logoutwebsitepage();
            } 

            if(page==0)
            {   
                $('#tabCourseHistory .list-profile-content').html(htmldata)                                         
            }
            else
            {
                $('#tabCourseHistory .list-profile-content').append(htmldata)
            }
            
           
        }


        function OnErrorlistcoursedetail(content)
        { 
            ajaxrequest("POST",'<?php echo $config->getPage404url(); ?>','','',OnsuccessRender,OnErrorRender);
        }
    }

    /************************* END For List Course Hisotry Section ******************************/


    $('body').on('click','.btnloadmoredetail',function(){
        $(this).parent().hide();
        var type =  $(this).attr('data-type');
        var nextpage  = $(this).attr('data-nextpage');
        if(type == 1)
        {
            listmembershipdetail(nextpage)
        }
        else if(type == 2)
        {
            listpackagedetail(nextpage) 
        }
        else if(type == 3)
        {
            listcoursedetail(nextpage) 
        }
    });
</script>