<?php require_once dirname(__DIR__, 1).'\config\config.php';
$config = new config();
?>
<!--page header section start-->
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="breadcrumb-list">
                    <li><a href="home.php" class="notranslate lan-home-menu">Home</a></li>
                    <li class="active">Tournaments Fees and Registrations, T&C</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!--page header section end-->
<section class="section-spacing" id="cart-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive table-tournaments-fees thead-sticky tfoot-sticky">
                    <table class="table pricing-table">
                        <thead class="primary-bg text-white">
                            <tr>
                                <th colspan="2">TOURNAMENT NAME</th>
                                <th class="text-center">WEAPON TYPE</th>
                                <th class="text-center">AGE</th>
                                <th class="text-center">TOTAL TIME minutes</th>
                                <th class="text-center">RANGE distance</th>
                                <th class="text-center">TOTAL BULLETS</th>
                                <th class="text-center">TOTAL ROUNDS</th>
                                <th class="text-center">TOTAL COMPETITORS</th>
                                <th class="text-center">COUNTED BULLETS</th>
                                <th class="text-center">TIME PER ROUND</th>
                                <th class="text-center">TIME FOR WARMING</th>
                                <th class="text-center">FEES</th>
                                <th class="text-center">PRIZES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="tbl-row">
                                <td colspan="2">JAGUARS</td>
                                <td class="text-center">AIRGUN</td>
                                <td class="text-center">7 - 15</td>
                                <td class="text-center">15</td>
                                <td class="text-center">15</td>
                                <td class="text-center">20 </td>
                                <td class="text-center">2</td>
                                <td class="text-center">30</td>
                                <td class="text-center">6</td>
                                <td class="text-center">3</td>
                                <td class="text-center">5</td>
                                <td class="text-center">400 QR</td>
                                <td class="text-center">400 QR</td>
                            </tr>
                            <tr class="tbl-row">
                                <td colspan="2">BARAZAN LIONS</td>
                                <td class="text-center">AIRGUN</td>
                                <td class="text-center">16 - 21</td>
                                <td class="text-center">15</td>
                                <td class="text-center">15</td>
                                <td class="text-center">20 </td>
                                <td class="text-center">2</td>
                                <td class="text-center">30</td>
                                <td class="text-center">4</td>
                                <td class="text-center">2</td>
                                <td class="text-center">4</td>
                                <td class="text-center">500 QR</td>
                                <td class="text-center"><a href="javascript:void(0);" data-toggle="modal" data-target="#prizesmodel">Prizes!A5</a></td>
                                
                            </tr>
                            <tr class="tbl-row">
                                <td colspan="2">ZUBARA LEGENDS</td>
                                <td class="text-center">PISTOL</td>
                                <td class="text-center">21</td>
                                <td class="text-center">8</td>
                                <td class="text-center">25</td>
                                <td class="text-center">10 </td>
                                <td class="text-center">2</td>
                                <td class="text-center">32</td>
                                <td class="text-center">3</td>
                                <td class="text-center">2</td>
                                <td class="text-center">6</td>
                                <td class="text-center">850 QR</td>
                                <td class="text-center"><a href="javascript:void(0);" data-toggle="modal" data-target="#prizesmodel">Prizes!A8</a></td>
                            </tr>
                            <tr class="tbl-row">
                                <td colspan="2">WAJBA LEGENDS</td>
                                <td class="text-center">RIFLE</td>
                                <td class="text-center">21</td>
                                <td class="text-center">8</td>
                                <td class="text-center">25</td>
                                <td class="text-center">8 </td>
                                <td class="text-center">2</td>
                                <td class="text-center">32</td>
                                <td class="text-center">3</td>
                                <td class="text-center">2</td>
                                <td class="text-center">4</td>
                                <td class="text-center">950 QR</td>
                                <td class="text-center"><a href="javascript:void(0);" data-toggle="modal" data-target="#prizesmodel">Prizes!A11</a></td>
                            </tr>
                            <tr class="tbl-row">
                                <td colspan="2">ZUBARA & WAJBA LEGEND</td>
                                <td class="text-center">RIFLE & PISTOL</td>
                                <td class="text-center">21</td>
                                <td class="text-center">10</td>
                                <td class="text-center">25</td>
                                <td class="text-center">10</td>
                                <td class="text-center">1 FOR EACH WEAPON</td>
                                <td class="text-center">24</td>
                                <td class="text-center">3</td>
                                <td class="text-center">2</td>
                                <td class="text-center">6</td>
                                <td class="text-center">1250 QR</td>
                                <td class="text-center"><a href="javascript:void(0);" data-toggle="modal" data-target="#prizesmodel">Prizes!A14</a></td>
                            </tr>
                            <tr class="tbl-row">
                                <td colspan="2">RANGE CHAMPION</td>
                                <td class="text-center">RIFLE, PISTOL & AIRGUN</td>
                                <td class="text-center">21</td>
                                <td class="text-center">15</td>
                                <td class="text-center">25</td>
                                <td class="text-center">15</td>
                                <td class="text-center">1 FOR EACH WEAPON</td>
                                <td class="text-center">16</td>
                                <td class="text-center">3</td>
                                <td class="text-center">2</td>
                                <td class="text-center">9</td>
                                <td class="text-center">1350 QR</td>
                                <td class="text-center"><a href="javascript:void(0);" data-toggle="modal" data-target="#prizesmodel">Prizes!A17</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section-spacing">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-heading text-center">
                    <h2 class="title">Registration</h2>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <form action="#" method="POST" id="registrationForm" class="col-12 registration-form">
                                <!-- <input type="hidden" name="csrfToken" id="csrfToken" value="" /> -->
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group validate-input">
                                            <input class="form-control" id="rname" type="text" required="required">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rname">Name <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group validate-input">
                                            <input class="form-control" id="rid" type="email" required="required">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rid">id <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group validate-input">
                                            <input class="form-control" id="rage" type="text" required="required">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rage">Age <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group validate-input">
                                            <select name="gender" id="rinterestedin" class="form-control selectpicker">
                                                <option value="1" selected>AIRGUN under 16</option>
                                                <option value="2">Airgun above 16</option>
                                                <option value="3">Pistol above 21</option>
                                                <option value="4">Rifle above 21</option>
                                                <option value="5">PISTOL AND RIFLE above 21</option>
                                                <option value="6">AL HADAF RANGE CHAMPION (Airgun, Pistol, Rifle)</option>
                                                <option value="7">Skeet above 16</option>
                                            </select>
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rinterestedin">Interested in <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group validate-input">
                                            <input class="form-control" id="rmobilenumber" type="text" required="required">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rmobilenumber">Mobile Number <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group validate-input">
                                            <input class="form-control" type="text" value="11/30/2021" id="rmonth" required="required">
                                            <span class="focus-form-control"></span>
                                            <label class="label-form-control" for="rmonth">Month<span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 mt-3 text-center">
                                        <button type="submit" class="btn btn-brand-01" id="btnRegistration">Submit</button>
                                    </div>
                                </div>
                            </form>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    $("ul.main-navbar-nav li.nav-item").removeClass("active");
    $("ul.main-navbar-nav li.licart").addClass("active");
    $(".mobile-navigation li").removeClass("active");
    $(".mobile-navigation li.licart").addClass("active");

    $('#btnapplycoupon').click(function() {
        if ($("#couponcode").hasClass("has-val")) {
            $(".couponscode-content").removeClass("d-none");
            $(".couponscode-content-apply").addClass("d-none");
        } else {
            $(".couponscode-content").addClass("d-none");
            $(".couponscode-content-apply").removeClass("d-none");
        }
    });
    $('.couponscode-remove').click(function() {
        $(".couponscode-content").addClass("d-none");
        $(".couponscode-content-apply").removeClass("d-none");
    });

    

    new When({
        input: document.getElementById('rmonth'),
        singleDate: true,
        showHeader: false
    });
});
</script>
