<?php
//echo dirname(__DIR__, 1);
//exit;
require_once dirname(__DIR__, 1).'\admin\config\init.php';
if($LoginInfo->getAdlogin()=="aldaf$20220117@instance%")
{
	?> <script>    window.location='<?php echo $config->getDirpath(); ?>'+"dashboard"; </script> <?php
	exit;
}
$pagename = $_REQUEST['targeturl'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Alhadaf Shooting Range</title>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <?php include('css.php'); ?>
    <!-- END GLOBAL MANDATORY STYLES -->
    
    <!-- Login STYLES -->
    <link href="assets/css/authentication/form-2.css" rel="stylesheet" type="text/css" />
    <!-- Login STYLES -->
</head>

<body class="form">
    <div class="form-container outer">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">
                        <h1 class="">Sign In</h1>
                        <p class="">Log in to your account to continue.</p>
                        <form class="text-left validate-form" id="loginForm" method="post">
                            <input id="key" name="key" type="hidden" value='dfcb25fea9b08b7a28ef7d8c58a43028' />
                            <div class="form">
                                <div id="username-field" class="field-wrapper input">
                                    <label for="username">USERNAME</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <input id="username" tabindex="1"  name="username" type="text" class="form-control" placeholder="e.g John_Doe">
                                </div>

                                <div id="password-field" class="field-wrapper input mb-2">
                                    <div class="d-flex justify-content-between w-100">
                                        <label for="password">PASSWORD</label>
                                        <a href="javascript:void(0)" class="forgot-pass-link">Forgot Password?</a>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    <input id="password" tabindex="2" name="password" type="password" class="form-control"
                                        placeholder="Password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </div>
                                <div class="d-sm-flex justify-content-between w-100">
                                    <div class="field-wrapper">
                                        <button tabindex="3"  class="btn btn-primary w-100" value="">Log In</button>
                                    </div>
                                </div>
                                <p class="signup-link"></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- start forgot password -->
    <div class="modal fade animated scrollmodal" id="forgotpassModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" >
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                            <h5 class="modal-title" id="ChkLoadCapacityModalLabel">Forgot Password?</h5>
                        </div>
                    </div>
                    <div class="row">
                        <form class="col-12 validate-form" id="forgotpasswordForm" method="post">   
                            
                            <div class="col-12">
                                <div class="input-group">
                                    <label class="mb-1">Username <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input id="forgotuname" name="forgotuname" type="text" placeholder="Enter User Name" class="form-control"> 
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="ml-auto ">
                                    <div class="mb-0 text-right">
                                        <button type="submit" class="btn btn-primary m-0 ml-2"><?php echo $config->getSubmitbutton(); ?></button>
                                    </div>
                                </div>
                                
                            </div>        
                        </form>
                    </div>	
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade animated scrollmodal" id="forgotpassotpModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" >
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                            <h5 class="modal-title" id="ChkLoadCapacityModalLabel">Forgot Password?</h5>
                        </div>
                    </div>
                    <div class="row">
                        <form class="col-12 validate-form" id="forgotpassotpForm" method="post">   
                            <input type="hidden" name="hiddenid" id="hiddenid" value="">
                            <div class="col-12">
                                <div class="input-group">
                                    <label class="mb-1" id="otpnotifydiv"></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input id="verifiedotp" name="verifiedotp" type="text" onkeypress="numbonly(event)" onpaste="numbonly(event)" placeholder="Enter Verification Code" class="form-control"> 
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="ml-auto ">
                                    <div class="mb-0 text-right">
                                        <button type="submit" class="btn btn-primary m-0 ml-2"><?php echo $config->getSubmitbutton(); ?></button>
                                    </div>
                                </div>
                                
                            </div>        
                        </form>
                    </div>	
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade animated scrollmodal" id="changedforgotpassModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" >
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                            <h5 class="modal-title" id="ChkLoadCapacityModalLabel">Change Password</h5>
                        </div>
                    </div>
                    <div class="row">
                        <form class="col-12 validate-form" id="changedforgotpassForm" method="post">   
                            <input type="hidden" name="hiddenid" id="hiddenid" value="">
                            <div class="col-12">
                                <div class="input-group">
                                    <label class="mb-1">Password <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input id="newpassword" name="newpassword" type="password" placeholder="Enter User Name" class="form-control"> 
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group">
                                    <label class="mb-1">Confirm Password <span class="text-danger">*</span></label>
                                    <label class="ml-auto "></label>
                                </div>
                                <div class="input-group mb-3">
                                    <input id="newconfimpass" name="newconfimpass" type="password" placeholder="Enter User Name" class="form-control"> 
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="ml-auto ">
                                    <div class="mb-0 text-right">
                                        <button type="submit" class="btn btn-primary m-0 ml-2"><?php echo $config->getSubmitbutton(); ?></button>
                                    </div>
                                </div>
                                
                            </div>        
                        </form>
                    </div>	
                </div>
            </div>
        </div>
    </div>

    

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <?php include('js.php'); ?>
    <script src="assets/js/authentication/form-2.js"></script>

    <script>
        if($('#loginForm').length){	
            	
            $('#loginForm').validate({
                rules:{
                    username:{
                        required: true,			
                    },
                    password:{
                        required: true,			
                    }
                },messages:{
                    username:{
                        required:"Username is required",
                    },
                    password:{
                        required:"Password is required",
                    }
                },
                submitHandler: function(form){
					$('.loading').show()
					jqXHR = $.ajax({
						url : "<?php echo $config->getEndpointurl(); ?>login.php?action=login",
						type : "POST",
						dataType:'json',
						data: $('#loginForm').serialize(),
						success : function(data) 
						{
							var JsonData = JSON.stringify(data);
							var resultdata = jQuery.parseJSON(JsonData);
							$('.loading').hide();	
							if(resultdata.status==0)
							{
                                alertify(resultdata.message, '0');
							}
							else if(resultdata.status==1)
							{
                                if('<?php echo $pagename ?>'=='')
                                {
								    window.location='<?php echo $config->getDirpath(); ?>'+"dashboard";
                                }
                                else
                                {
                                    window.location='<?php echo $config->getDirpath(); ?>'+'<?php echo $pagename ?>';
                                }
							}
						}
					});
			    },
            });
        }
    </script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->
</body>
</html>