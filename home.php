<?php require_once 'config/init.php'; 
//session_unset();

//Generate Order Invoice Number
//$DB->generateorderinvoicenumber('8D0FC82C-C25E-4955-BB15-21F4D07D8059','034FB884-A865-4127-B90B-3D06047A72CC','4');

// echo '<pre>';
// print_r($LoginInfo);
// echo '</pre>';

?>
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
    
    <link href="<?php echo $config->getImageurl() ?>images/favicon.jpg" rel="icon">

    <!--title-->
    <title>Alhadaf Shooting Range</title>

    <!-- CSS Start -->
    <?php include('css.php'); ?>
    <!-- CSS End -->
    
</head>

<body class="home-page">
    
    <!-- Header Start -->
    <?php include('header.php'); ?>    
    <!-- Header End -->

    <div class="main" id="web-content">
        <?php //include('views/home.php'); ?>
    </div>
    
    <!-- Footer Start -->
    <?php include('footer.php'); ?>
    <!-- Footer End -->

    <!-- JS Start -->
    <?php include('js.php'); ?>
    <!-- JS End -->

    <script>
        $(document).ready(function() {
            //For Render PAGE
            var urlpath=window.location.pathname;
            if(urlpath=='<?php echo $config->getDirpath(); ?>')
            {
                var pagename='home';

            }
            else
            {
                var pagename=urlpath.split("/");
                //var prev_pagename = pagename[pagename.length-2];
                pagename = pagename[pagename.length-1];

                pagenamearry = pagename.split("-");
                var prefix_pagename = pagenamearry[0];
                
                //alert(pagename);
            }

            if(prefix_pagename == 'cd')
            {
                render('coursedetail');
            }
            else
            {
                render(pagename);
            }

        });

    </script>
</body>
</html>