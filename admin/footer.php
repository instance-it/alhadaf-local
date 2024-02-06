<div class="footer-wrapper footer-fixed">
    

    <!-- <div class="row">
        <div class="col ml-auto footer-section f-section-2 pt-2 text-right">
        <p class="">Designed & Developed By <a href="https://instanceit.com/" target="_blank" class="companylink">Instance IT Solutions®</a></p>
        </div>
    </div>   -->
</div>



<!-- Advance Order Modal -->
<div class="modal fade info-modal-dialog" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="info-body">
                    <div class="modal-logo"><img src="assets/img/logo.svg"/></div>
                    <h5 class="my-3 px-3" id="infoModalLabel">No internet Connection</h5> 
                </div>
            </div>
            <div class="modal-footer text-center p-0">
                <button type="submit" class="col btn btn-primary" data-dismiss="modal">Retry</button>
            </div>
        </div>
    </div>
</div>

<!-- Advance Order Modal -->
<div class="modal fade info-modal-dialog" id="confirmedDelete" tabindex="-1" role="dialog" aria-labelledby="confirmedDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="info-body">
                    <div class="modal-logo"><img src="assets/img/logo.svg"/></div>
                    <h5 class="my-3 px-3" id="confirmedDeleteLabel">Are you sure!<br> You want delete Requested item?</h5> 
                </div>
            </div>
            <div class="modal-footer text-center p-0">
                <button type="button" class="col btn btn-primary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="col btn btn-danger" data-dismiss="modal">Delete</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade info-modal-dialog" id="confirmedclearview" tabindex="-1" role="dialog" aria-labelledby="confirmedclearviewLabel" aria-hidden="true" style="z-index: 999999;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form class="" onsubmit="return false">
                <div class="modal-body p-0">
                    <div class="info-body">
                        <div class="modal-logo"><img src="assets/img/logo.svg"/></div>
                        <h5 class="my-3 px-3" id="confirmedclearviewLabel">Are you sure! <br>You want to remove Operation!</h5> 
                    </div>
                </div>
                <div class="modal-footer text-center p-0">
                    <button type="button" class="col btn btn-danger" data-dismiss="modal">No</button>
                    <button type="submit" class="col btn btn-primary confirmedclearviewbtn" data-dismiss="modal">Yes</button>
                </div>
            </form>    
        </div>
    </div>
</div>
<!-- Images View Modal -->
<div class="modal animated fade" id="modalImgView" tabindex="-1" role="dialog" aria-labelledby="modalImgViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- <div class="modal-header">
                <h5 class="modal-title" id="modalImgViewLabel">View Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div> -->
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                        <h5 class="add-event-title modal-title">View Image</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12 text-center">                                                
                        <img src="assets/img/salon.jpg" alt="camera.jpg" class="img-thumbnail"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-right border-top-0">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<!-- Package View Details Modal -->
<div class="modal animated fade" id="modalPackageViewDetails" tabindex="-1" role="dialog" aria-labelledby="modalPackageViewDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- <div class="modal-header">
                <h5 class="modal-title" id="modalPackageViewDetailsLabel"> Package View Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div> -->
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                        <h5 class="modal-title" id="modalPackageViewDetailsLabel">Package View Details</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12 text-center">                                                
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer text-right border-top-0">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>



<!-- Delete Confirmation Modal -->
<div class="modal delete-confirmation-modal animated fade" id="modalConfirmation" tabindex="-1" role="dialog" aria-labelledby="modalConfirmationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body py-lg-0">
                <div class="row mb-4 d-lg-none">
                    <div class="col-12">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                        <!--<h5 class="modal-title" id="modalConfirmationLabel">Are you sure!</h5>-->
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-4 text-center min-height-100 img-trash">
                        <div class="row justify-content-center align-items-center min-height-100">
                            <div class="col-12">
                                <img class="w-100" src="assets/img/trash.png" alt="trash.png" />
                            </div>
                        </div>
                    </div>                         
                    <div class="col-12 col-lg-8 pt-3 py-lg-3">  
                        <div class="row">
                            <div class="col-12 mb-2 mb-lg-4">
                                <button type="button" class="close d-none d-lg-block" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                                <h5 class="modal-title text-left" id="modalConfirmationLabel">Are you sure!</h5>
                            </div>
                        </div>                                              
                        <form class="row" id="deleteForm" onsubmit="return false">
                            <input type="hidden" id="drid" name="drid" >
                            <div class="col-12">
                                <div class="input-group mb-0">
                                    <label class="mb-1">Type <b>Delete</b> for delete this record.</label>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" id="deleteaction" name="deleteaction" class="form-control textuppercase">
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" name="confirmdelete" id="confirmdelete" class="btn btn-danger float-right" >Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<!-- General Modal ALl Page Use -->
<div class="modal fade animated scrollmodal" id="GeneralModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                        <h5 class="modal-title" id="GeneralModalLabel">Details</h5>
                    </div>
                </div>
                <div class="row" id="Generaldata">
                    
                </div>	
            </div>
        </div>
    </div>
</div>
<!-- General Modal ALl Page Use -->


<!--Sub General  Modal ALl Page Use -->
<div class="modal fade animated scrollmodal" id="SubGeneralModal" tabindex="1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-12">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                        <h5 class="modal-title" id="SubGeneralModalLabel">Details</h5>
                    </div>
                </div>
                <div class="row" id="SubGeneraldata">
                    
                </div>	
            </div>
        </div>
    </div>
</div>
<!--Sub General Modal ALl Page Use -->


<div class="modal fade in apply-modal" id="vesselpricedeparture" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3 modal-title"><span class="fterminalname">Hazira</span> <i class="fal fa-angle-double-right"></i> <span class="tterminalname">Ghogha</span> <span class="formatdate">Sat, 31 Jul</span>
                        <button type="button" class="close" data-dismiss="modal"><i class="bi bi-x-lg"></i></button></h4>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <form method="post" class="col-12" action="passengers-details.php">
                                <input type="hidden" name="tripid" id="tripid" value="" />
                                <input type="hidden" name="vid" id="vid" value="" />
                                <input type="hidden" name="routeid" id="routeid" value="" />
                                <input type="hidden" name="fromterminalid" id="fromterminalid" value="" />
                                <input type="hidden" name="fromterminal" id="fromterminal" value="" />
                                <input type="hidden" name="fromterminallocation" id="fromterminallocation" value="" />
                                <input type="hidden" name="toterminalid" id="toterminalid" value="" />
                                <input type="hidden" name="toterminal" id="toterminal" value="" />
                                <input type="hidden" name="toterminallocation" id="toterminallocation" value="" />
                                <input type="hidden" name="formatjourneydate" id="formatjourneydate" value="" />
                                <input type="hidden" name="type" id="type" value="" />
                                <input type="hidden" name="dayid" id="dayid" value="" />
                                <input type="hidden" name="dayname" id="dayname" value="" />
                                <input type="hidden" name="fromtime" id="fromtime" value="" />
                                <input type="hidden" name="totime" id="totime" value="" />
                                <input type="hidden" name="hourduration" id="hourduration" value="" />

                                <div class="row">
                                    <div class="col-12 col-lg-8">
                                        <div class="row">

                                            <!------------------- Start For Category Section -------------------->
                                            <div class="col-12 col-lg-12 mb-3">
                                                <div class="row">
                                                    <div class="col-12 col-md-3 pr-0 apply-que-label">
                                                        <label class="w-100">Select Category</label>
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="pc-list-content">
                                                                    <div class="tabs-control">
                                                                        <a class="arrow left-arrow">
                                                                            <i class="far fa-chevron-right"></i>
                                                                        </a>
                                                                        <a class="arrow right-arrow">
                                                                            <i class="far fa-chevron-left"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="pc-list-scroll">
                                                                        <ul class="pc-list counter-guest pc-list-img" id="vesselcategoryDiv">
                                                                            
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="pc-type-form" id="vesselsubcategorysection">
                                                                    <div class="row mr-0">
                                                                        <div class="col-12 col-sm-5 pr-0 mb-2 mb-sm-0 d-none vesselsubcategorylistdiv">
                                                                            <div class="input-group">
                                                                                <label class="label-form-control lblsubcategory" for=""></label>
                                                                            </div>
                                                                            <div class="input-group">
                                                                                <select class="form-control selectpicker" id="subcategoryid" name="subcategoryid" data-size="10" data-dropup-auto="false" title="Select Type">
                                                                                    <!-- <option value="1">Bus With passengers</option>
                                                                                    <option value="2">City Bus</option>
                                                                                    <option value="ST Bus">ST Bus</option> -->
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6 col-sm-3 pr-0 d-none vesselcategoryweightdiv">
                                                                            <div class="input-group">
                                                                                <label class="label-form-control" for="">Gross Weight</label>
                                                                            </div>
                                                                            <div class="input-group">
                                                                                <input class="form-control control-append" type="text" name="categoryweight" id="categoryweight" onkeypress="numbonly(event)" placeholder="Weight" value="0">
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text">Ton</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6 col-sm-4 pr-0 vesselcategorypricediv">
                                                                            <div class="input-group">
                                                                                <label class="label-form-control lblcatprice" for="">Price </label>
                                                                            </div>
                                                                            <div class="input-group">                                                                                
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rs.</span>
                                                                                </div>
                                                                                <input class="form-control control-prepend" type="text" name="categoryprice" readonly="readonly" id="categoryprice" placeholder="" value="0">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <small class="apply-selected-label lblcategoryprice"></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!------------------- End For Category Section -------------------->


                                            <!------------------- Start For Transporation Service Section -------------------->
                                            <div class="col-12 col-lg-12 mb-3" id="transporationserviceDiv">
                                                <div class="row">
                                                    <div class="col-12 col-md-3 pr-0 apply-que-label">
                                                    </div>
                                                    <div class="col-12 col-md">
                                                        <div class="row mr-0">
                                                            <div class="col-12 col-md">
                                                                <div class="input-group mb-3 mb-lg-3">
                                                                    <div class="custom-control custom-checkbox m-0">
                                                                        <input type="checkbox" class="custom-control-input d-none ispickupdrop" id="ispickupdrop" name="ispickupdrop" value="1">
                                                                        <label class="custom-control-label mb-0" for="ispickupdrop">Pick Up & Drop</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-5 pr-0 mb-2 mb-sm-0 transportationvehicleDiv d-none">
                                                                <div class="input-group">
                                                                    <select class="form-control selectpicker" id="transportationvehicleid" name="transportationvehicleid" data-size="10" data-dropup-auto="false" data-live-search="true">
                                                                       
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="pc-type-form d-none" id="fromtransportationsection">
                                                            <div class="row mr-0">
                                                                <div class="col-6 col-sm-6 pr-0">
                                                                    <div class="input-group">
                                                                        <label class="label-form-control" for="">Pickup Point</label>
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <select class="form-control selectpicker" id="pickuppointid" name="pickuppointid" data-size="10" data-dropup-auto="false" data-live-search="true">
                                                                    
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-sm-6 pr-0 ">
                                                                    <div class="input-group">
                                                                        <label class="label-form-control" for="">Drop Point</label>
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <select class="form-control selectpicker" id="topickuppointid" name="topickuppointid" data-size="10" data-dropup-auto="false" data-live-search="true">
                                                                    
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="row mr-0 mt-10">

                                                                <div class="col-6 col-sm-6 pr-0 d-none">
                                                                    <div class="input-group">
                                                                        <label class="label-form-control" for="fromvehicleprice">Price </label>
                                                                    </div>
                                                                    <div class="input-group">                                                                                
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Rs.</span>
                                                                        </div>
                                                                        <input class="form-control control-prepend" type="text" name="pickupprice" readonly="readonly" id="pickupprice" placeholder="" value="0">
                                                                    </div>
                                                                </div>

                                                                
                                                                <div class="col-6 col-sm-6 pr-0 d-none">
                                                                    <div class="input-group">
                                                                        <label class="label-form-control" for="totransportationvehicleprice">Price </label>
                                                                    </div>
                                                                    <div class="input-group">                                                                                
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Rs.</span>
                                                                        </div>
                                                                        <input class="form-control control-prepend" type="text" name="droppickupprice" readonly="readonly" id="droppickupprice" placeholder="" value="0">
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!------------------- End For Transporation Service Section -------------------->


                                            <!------------------- Start For Class Section -------------------->
                                            <div class="col-12 col-lg-12 mb-3 d-none" id="vesselmainclassDiv">
                                                <div class="row">
                                                    <div class="col-12 col-md-3 pr-0 apply-que-label">
                                                        <label class="w-100">Choose Travel Class</label>
                                                    </div>
                                                    <div class="col-12 col-md">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="pc-list-content">
                                                                    <div class="tabs-control">
                                                                        <a class="arrow left-arrow">
                                                                            <i class="far fa-chevron-right"></i>
                                                                        </a>
                                                                        <a class="arrow right-arrow">
                                                                            <i class="far fa-chevron-left"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="pc-list-scroll">
                                                                        <ul class="pc-list counter-guest pc-list-class" id="vesselclassDiv">
                                                                            
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mt-n2">
                                                                <small class="apply-selected-label"> 
                                                                    <b class="class-avl-seat">Seats filling fast</b> 
                                                                    <!-- <b class="blue-text"><i class="rupee-icon"></i> 1300</b> -->
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!------------------- End For Class Section -------------------->

                                            <!------------------- Start For Passenger Type Section -------------------->
                                            <div class="col-12 col-lg-12" id="vesselpassengerDiv">
                                                
                                            </div>
                                            <!------------------- End For Passenger Type Section -------------------->
                                            
                                            <div class="col-12" id="adultmoreDiv">
                                                <p><i class="bi bi-info-circle blue-text adultmore"></i> Adults more than 9 <a class="blue-text" href="#adultsmoreModal" data-toggle="modal" data-target="#adultsmoreModal">Click here</a></p>
                                            </div>
                                            <!-- <div class="col-12 text-center mt-3">
                                                <button type="submit" class="btn btn-brand-03 btn-round btn-pc-apply mr-2">Book Now</button>
                                                <button type="submit" class="btn btn-outline-brand-01" data-dismiss="modal">Cancel</button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 mt-4 mt-lg-0" id="sidebar_fixed">
                                        <div class="dashboard-right">
                                            <h4>Booking Summary</h4>
                                            
                                            <div class="block-content common-card pricingsummarydiv">
                                                
                                                <div class="cart-total pt-0">
                                                    <p class="label-name">Base Fare</p>
                                                    <p class="label-data totalbaseprice"><i class="rupee-icon"></i> 2,800</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Adults(2x <i class="rupee-icon"></i> 500)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,000</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Infant(2x <i class="rupee-icon"></i> 700)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,400</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Vehicle(<i class="rupee-icon"></i> 1300)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>	1300</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="cart-total">
                                                    <p class="label-name">Fee & Surcharges</p>
                                                    <p class="label-data"><i class="rupee-icon"></i> 1000</p>
                                                </div>
                                                <div class="cart-total">
                                                    <p class="label-name">Other Services</p>
                                                    <p class="label-data"><i class="rupee-icon"></i> 100</p>
                                                </div>
                                                <div class="cart-total pb-0">
                                                    <p class="label-name-total">Total Amount:</p>
                                                    <p class="label-data-total"><i class="rupee-icon"></i> 3,900</p>
                                                </div>
                                                
                                            </div>

                                            <div class="cart-total pb-0">
                                                <!-- <button type="button" class="btn btn-outline-brand-01 px-3 mr-auto" data-dismiss="modal">Cancel</button> -->
                                                 <button type="button" class="btn btn-outline-brand-01 px-3 mr-auto" data-dismiss="modal" id="btnaddcancel">Cancel</button>
                                            
                                                <button type="button" class="btn btn-brand-03 btn-round px-3 btn-pc-apply-continue ml-2" id="btnaddcontinue">Continue</button>
                                                <button type="button" class="btn btn-brand-03 btn-round px-3 btn-pc-apply ml-2 d-none" id="btnbooknow">Book Now</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <!--------------------- Start For Show Template Data --------------------->
                                <div class="row">


                                    <!-------------- Start For Passenger Templates ------------->
                                    <div class="col-12 col-lg-6 mt-4 mt-lg-0" id="passenger_template">
                                        <!-- <div class="dashboard-right">
                                            <div class="block-content common-card">
                                                <div class="cart-total pt-0">
                                                    <p class="label-name">Category</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Adults(2x <i class="rupee-icon"></i> 500)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,000</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Infant(2x <i class="rupee-icon"></i> 700)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,400</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Vehicle(<i class="rupee-icon"></i> 1300)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>	1300</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                    <!-------------- End For Passenger Templates ------------->

                                    

                                    <!-------------- Start For Category Templates ------------->
                                    <div class="col-12 col-lg-6 mt-4 mt-lg-0" id="category_template">
                                        <!-- <div class="dashboard-right">
                                            <div class="block-content common-card">
                                                <div class="cart-total pt-0">
                                                    <p class="label-name">Category</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Adults(2x <i class="rupee-icon"></i> 500)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,000</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Infant(2x <i class="rupee-icon"></i> 700)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,400</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Vehicle(<i class="rupee-icon"></i> 1300)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>	1300</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                    <!-------------- End For Category Templates ------------->



                                </div>
                                <!--------------------- End For Show Template Data --------------------->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade in apply-modal" id="vesselpricereturn" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3 modal-title"><span class="fterminalname">Hazira</span> <i class="fal fa-angle-double-right"></i> <span class="tterminalname">Ghogha</span> <span class="formatdate">Sat, 31 Jul</span>
                        <button type="button" class="close" data-dismiss="modal"><i class="bi bi-x-lg"></i></button></h4>   
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <form method="post" class="col-12" action="passengers-details.php">
                                <input type="hidden" name="tripid" id="tripid" value="" />
                                <input type="hidden" name="vid" id="vid" value="" />
                                <input type="hidden" name="routeid" id="routeid" value="" />
                                <input type="hidden" name="fromterminalid" id="fromterminalid" value="" />
                                <input type="hidden" name="fromterminal" id="fromterminal" value="" />
                                <input type="hidden" name="fromterminallocation" id="fromterminallocation" value="" />
                                <input type="hidden" name="toterminalid" id="toterminalid" value="" />
                                <input type="hidden" name="toterminal" id="toterminal" value="" />
                                <input type="hidden" name="toterminallocation" id="toterminallocation" value="" />
                                <input type="hidden" name="formatjourneydate" id="formatjourneydate" value="" />
                                <input type="hidden" name="type" id="type" value="" />
                                <input type="hidden" name="dayid" id="dayid" value="" />
                                <input type="hidden" name="dayname" id="dayname" value="" />
                                <input type="hidden" name="fromtime" id="fromtime" value="" />
                                <input type="hidden" name="totime" id="totime" value="" />
                                <input type="hidden" name="hourduration" id="hourduration" value="" />

                                
                                <div class="row">
                                    <div class="col-12 col-lg-8">
                                        <div class="row">

                                            <!------------------- Start For Category Section -------------------->
                                            <div class="col-12 col-lg-12 mb-3">
                                                <div class="row">
                                                    <div class="col-12 col-md-3 pr-0 apply-que-label">
                                                        <label class="w-100">Select Category</label>
                                                    </div>
                                                    <div class="col-12 col-md-9">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="pc-list-content">
                                                                    <div class="tabs-control">
                                                                        <a class="arrow left-arrow">
                                                                            <i class="far fa-chevron-right"></i>
                                                                        </a>
                                                                        <a class="arrow right-arrow">
                                                                            <i class="far fa-chevron-left"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="pc-list-scroll">
                                                                        <ul class="pc-list counter-guest pc-list-img" id="vesselcategoryDiv">
                                                                            
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                                <div class="pc-type-form" id="vesselsubcategorysection">
                                                                    <div class="row mr-0">
                                                                        <div class="col-12 col-sm-5 pr-0 mb-2 mb-sm-0 d-none vesselsubcategorylistdiv">
                                                                            <div class="input-group">
                                                                                <label class="label-form-control lblsubcategory" for=""></label>
                                                                            </div>
                                                                            <div class="input-group">
                                                                                <select class="form-control selectpicker" id="subcategoryid" name="subcategoryid" data-size="10" data-dropup-auto="false" title="Select Type">
                                                                                    <!-- <option value="1">Bus With passengers</option>
                                                                                    <option value="2">City Bus</option>
                                                                                    <option value="ST Bus">ST Bus</option> -->
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6 col-sm-3 pr-0 d-none vesselcategoryweightdiv">
                                                                            <div class="input-group">
                                                                                <label class="label-form-control" for="">Gross Weight</label>
                                                                            </div>
                                                                            <div class="input-group">
                                                                                <input class="form-control control-append" type="text" name="categoryweight" id="categoryweight" onkeypress="numbonly(event)" placeholder="Weight" value="0">
                                                                                <div class="input-group-append">
                                                                                    <span class="input-group-text">Ton</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6 col-sm-4 pr-0 vesselcategorypricediv">
                                                                            <div class="input-group">
                                                                                <label class="label-form-control lblcatprice" for="">Price </label>
                                                                            </div>
                                                                            <div class="input-group">                                                                                
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">Rs.</span>
                                                                                </div>
                                                                                <input class="form-control control-prepend" type="text" name="categoryprice" readonly="readonly" id="categoryprice" placeholder="" value="0">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <small class="apply-selected-label lblcategoryprice"></small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!------------------- End For Category Section -------------------->

                                            <!------------------- Start For Transporation Service Section -------------------->
                                            <div class="col-12 col-lg-12 mb-3" id="transporationserviceDiv">
                                                <div class="row">
                                                    <div class="col-12 col-md-3 pr-0 apply-que-label">
                                                    </div>
                                                    <div class="col-12 col-md">
                                                        <div class="row mr-0">
                                                            <div class="col-12 col-md">
                                                                <div class="input-group mb-3 mb-lg-3">
                                                                    <div class="custom-control custom-checkbox m-0">
                                                                        <input type="checkbox" class="custom-control-input d-none ispickupdrop" id="retispickupdrop" name="retispickupdrop" value="1">
                                                                        <label class="custom-control-label mb-0" for="retispickupdrop">Pick Up & Drop</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-5 pr-0 mb-2 mb-sm-0 transportationvehicleDiv d-none">
                                                                <div class="input-group">
                                                                    <select class="form-control selectpicker" id="transportationvehicleid" name="transportationvehicleid" data-size="10" data-dropup-auto="false" data-live-search="true">
                                                                       
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="pc-type-form d-none" id="fromtransportationsection">
                                                            <div class="row mr-0">
                                                                <div class="col-6 col-sm-6 pr-0">
                                                                    <div class="input-group">
                                                                        <label class="label-form-control" for="">Pickup Point</label>
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <select class="form-control selectpicker" id="pickuppointid" name="pickuppointid" data-size="10" data-dropup-auto="false" data-live-search="true">
                                                                    
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-sm-6 pr-0 ">
                                                                    <div class="input-group">
                                                                        <label class="label-form-control" for="">Drop Point</label>
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <select class="form-control selectpicker" id="topickuppointid" name="topickuppointid" data-size="10" data-dropup-auto="false" data-live-search="true">
                                                                    
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!------------------- End For Transporation Service Section -------------------->


                                            <!------------------- Start For Class Section -------------------->
                                            <div class="col-12 col-lg-12 mb-3 d-none" id="vesselmainclassDiv">
                                                <div class="row">
                                                    <div class="col-12 col-md-3 pr-0 apply-que-label">
                                                        <label class="w-100">Choose Travel Class</label>
                                                    </div>
                                                    <div class="col-12 col-md">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="pc-list-content">
                                                                    <div class="tabs-control">
                                                                        <a class="arrow left-arrow">
                                                                            <i class="far fa-chevron-right"></i>
                                                                        </a>
                                                                        <a class="arrow right-arrow">
                                                                            <i class="far fa-chevron-left"></i>
                                                                        </a>
                                                                    </div>
                                                                    <div class="pc-list-scroll">
                                                                        <ul class="pc-list counter-guest pc-list-class" id="vesselclassDiv">
                                                                            
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <small class="apply-selected-label"> 
                                                                    <b class="class-avl-seat">Seats filling fast</b> 
                                                                    <!-- <b class="blue-text"><i class="rupee-icon"></i> 1300</b> -->
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!------------------- End For Class Section -------------------->

                                            <!------------------- Start For Passenger Type Section -------------------->
                                            <div class="col-12 col-lg-12" id="vesselpassengerDiv">
                                                
                                            </div>
                                            <!------------------- End For Passenger Type Section -------------------->
                                            
                                            <div class="col-12">
                                                <p><i class="bi bi-info-circle blue-text"></i> Adults more than 9 <a class="blue-text" href="#adultsmoreModal" data-toggle="modal" data-target="#adultsmoreModal">Click here</a></p>
                                            </div>
                                            <!-- <div class="col-12 text-center mt-3">
                                                <button type="submit" class="btn btn-brand-03 btn-round btn-pc-apply mr-2">Book Now</button>
                                                <button type="submit" class="btn btn-outline-brand-01" data-dismiss="modal">Cancel</button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-4 mt-4 mt-lg-0" id="sidebar_fixed">
                                        <div class="dashboard-right">
                                            <h4>Booking Summary</h4>
                                            
                                            <div class="block-content common-card pricingsummarydiv">
                                                
                                                <div class="cart-total pt-0">
                                                    <p class="label-name">Base Fare</p>
                                                    <p class="label-data totalbaseprice"><i class="rupee-icon"></i> 2,800</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Adults(2x <i class="rupee-icon"></i> 500)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,000</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Infant(2x <i class="rupee-icon"></i> 700)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,400</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Vehicle(<i class="rupee-icon"></i> 1300)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>	1300</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="cart-total">
                                                    <p class="label-name">Fee & Surcharges</p>
                                                    <p class="label-data"><i class="rupee-icon"></i> 1000</p>
                                                </div>
                                                <div class="cart-total">
                                                    <p class="label-name">Other Services</p>
                                                    <p class="label-data"><i class="rupee-icon"></i> 100</p>
                                                </div>
                                                <div class="cart-total pb-0">
                                                    <p class="label-name-total">Total Amount:</p>
                                                    <p class="label-data-total"><i class="rupee-icon"></i> 3,900</p>
                                                </div>
                                                
                                            </div>

                                            <div class="cart-total pb-0">
                                                <!-- <button type="button" class="btn btn-outline-brand-01 px-3 mr-auto" data-dismiss="modal" id="btnaddcontinue">Cancel</button> -->
                                                <button type="button" class="btn btn-outline-brand-01 px-3 mr-auto" data-dismiss="modal" id="btnaddcancel">Cancel</button>
                                                <button type="button" class="btn btn-brand-03 btn-round px-3 btn-pc-apply-continue ml-2" id="btnaddcontinue">Continue</button>
                                                <button type="button" class="btn btn-brand-03 btn-round px-3 btn-pc-apply ml-2 d-none" id="btnbooknow">Book Now</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <!--------------------- Start For Show Template Data --------------------->
                                <div class="row">


                                    <!-------------- Start For Passenger Templates ------------->
                                    <div class="col-12 col-lg-6 mt-4 mt-lg-0" id="passenger_template">
                                        <!-- <div class="dashboard-right">
                                            <div class="block-content common-card">
                                                <div class="cart-total pt-0">
                                                    <p class="label-name">Category</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Adults(2x <i class="rupee-icon"></i> 500)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,000</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Infant(2x <i class="rupee-icon"></i> 700)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,400</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Vehicle(<i class="rupee-icon"></i> 1300)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>	1300</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                    <!-------------- End For Passenger Templates ------------->

                                    

                                    <!-------------- Start For Category Templates ------------->
                                    <div class="col-12 col-lg-6 mt-4 mt-lg-0" id="category_template">
                                        <!-- <div class="dashboard-right">
                                            <div class="block-content common-card">
                                                <div class="cart-total pt-0">
                                                    <p class="label-name">Category</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Adults(2x <i class="rupee-icon"></i> 500)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,000</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Infant(2x <i class="rupee-icon"></i> 700)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>1,400</p>
                                                        </div>
                                                        <div class="sub-cart-total">
                                                            <p class="sub-label-name">Vehicle(<i class="rupee-icon"></i> 1300)</p>
                                                            <p class="sub-label-data"><i class="rupee-icon"></i>	1300</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                    <!-------------- End For Category Templates ------------->



                                </div>
                                <!--------------------- End For Show Template Data --------------------->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--modal promoCodeModal-->
<div class="modal fade in" id="promoCodeModal">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <!--modal-body-->
                <div class="row">
                    <div class="col-12 mb-3">
                        <h4 class="modal-title blue-text">Offers Codes</h4>
                        <button type="button" class="close" data-dismiss="modal"><i class="fal fa-times"></i></button>
                    </div>
                    <div class="col-12">
                        <div class="row mr-0" id="listcouponData">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/modal promoCodeModal-->

<!--modal offerdetailsModal-->
<div class="modal fade in" id="offerdetailsModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <!--modal-body-->
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-3 modal-title">Offer Details</h4>
                        <button type="button" class="close" data-dismiss="modal"><i class="fal fa-times"></i></button>
                    </div>
                    <div class="col-12 offerdetails-content"  id="listcoupondescrData">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/modal offerdetailsModal-->

<!-- modalSelectSeat popup-->
<div class="modal fade in" id="modalSelectSeat" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row" id="seatdata">
                   
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ modalSelectSeat popup-->
