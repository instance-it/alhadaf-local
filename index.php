<?php require_once 'config/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tag Start -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="Alhadaf Shooting Range" />
    <meta name="author" content="Alhadaf Shooting Range" />
    <meta name="robots" content="Alhadaf Shooting Range" />
    <meta name="description" content="Alhadaf Shooting Range" />
    <meta property="og:title" content="Alhadaf Shooting Range" />
    <meta property="og:description" content="Alhadaf Shooting Range" />
    <meta property="og:image" content="https://demo.instanceit.com/alhadaf_shooting_range/assets/images/preview.jpg" />
    <!-- Meta Tag End -->
    
    <link href="assets/images/favicon.jpg" rel="icon">

    <!--title-->
    <title>Alhadaf Shooting Range</title>

    <!-- CSS Start -->
    <?php include('css.php'); ?>
    <!-- CSS End -->
    
    <!-- JS Start -->
    <?php // include('js.php'); ?>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <!-- JS End -->
</head>

<body class="home-page">
    <div id="google_translate_element" class="d-none"></div> 
    <div class="main p-0" id="web-content">
        <section class="lending-page bg-image full-height" data-overlay="3">
            <div class="background-image-wraper" style="background: url('assets/images/banner.jpg'); opacity: 1;">
                <video src="assets/images/landing_01.mp4" autoplay loop playsinline muted width="100%"></video>
            </div>
            <div class="col-12">
                <div class="row align-items-center justify-content-center my-auto">
                    <div class="col-12">
                        <div class="hero-content-left text-white text-center">
                           <img class="img-fluid hero-logo mb-5" src="assets/images/logo.png" alt="logo">
                            <!-- <h1 class="text-white">Al HADAF SHOOTING RANGE</h1> -->
                            <h1 class="text-white">Target Shooting Range</h1>
                            <!-- <p class="lead">Our website is under construction. We'll be here soon with our new awesome site, subscribe to be notified.</p> -->
                        </div>
                    </div>
                    <div class="col-12 text-center notranslate">
                        <ul>
                            <!-- <li class="d-inline-block m-2"><a class="btn btn-lg btn-brand-06 px-3" href="comingsoon.php"><span class="flag-img mr-2 my-n2 d-none"><img src="assets/images/flag/saudi-arabia.png" alt="logo" class="img-fluid" width="35px"></span> English</a></li>
                            <li class="d-inline-block m-2"><a class="btn btn-lg btn-brand-06 px-3" href="comingsoon.php"><span class="flag-img mr-2 my-n2 d-none"><img src="assets/images/flag/saudi-arabia.png" alt="logo" class="img-fluid" width="35px"></span> Arabic</a></li> -->
                            <li class="d-inline-block m-2"><a class="btn btn-lg btn-brand-06 px-3 btnasrlanguage" href="javascript:void(0)" data-name="English" data-language="en" onclick="changeASRLanguage('en')"><span class="flag-img mr-2 my-n2 d-none"><img src="<?php echo $config->getImageurl() ?>images/flag/qatar.png" alt="logo" class="img-fluid" width="35px"></span> English</a></li>
                            <li class="d-inline-block m-2"><a class="btn btn-lg btn-brand-06 px-3 btnasrlanguage" href="javascript:void(0)" data-name="Arabic" data-language="ar" onclick="changeASRLanguage('ar')"><span class="flag-img mr-2 my-n2 d-none"><img src="<?php echo $config->getImageurl() ?>images/flag/qatar.png" alt="logo" class="img-fluid" width="35px"></span> Arabic</a></li>
                            <!-- <li class="d-inline-block m-2"><a class="btn btn-lg btn-brand-06 px-3" href="comingsoon.php"><span class="flag-img mr-2 my-n2"><img src="assets/images/flag/saudi-arabia.png" alt="logo" class="img-fluid" width="35px"></span> Saudi Arabia</a></li> -->
                            <!-- <li class="d-inline-block m-2"><a class="btn btn-lg btn-brand-06 px-3" href="home.php"><span class="flag-img mr-2 my-n2"><img src="assets/images/flag/qatar.png" alt="logo" class="img-fluid" width="35px"></span> Qatar</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-12 lending-page-copyright">
                <div class="copyright-wrap m-auto">
                    <p class="mb-0">&copy; 2021 <a href="home">Alhadaf Shooting Range</a>. All Right Reserved.</p>
                </div>
            </div>
        </section>
    </div>

    <script type="text/javascript">

    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: "en"}, 'google_translate_element');
    }

    function changeASRLanguage(language) {
        var selectField = document.querySelector("#google_translate_element select");
        for(var i=0; i < selectField.children.length; i++){
            var option = selectField.children[i];
            // find desired langauge and change the former language of the hidden selection-field 
            if(option.value==language){
            selectField.selectedIndex = i;
            // trigger change event afterwards to make google-lib translate this side
            selectField.dispatchEvent(new Event('change'));
            break;
            }
        }

        setTimeout(function(){ 
            window.location='home'; 
        }, 300);
    }
    </script>
    

    
</body>
</html>