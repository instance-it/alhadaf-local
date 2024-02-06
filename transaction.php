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
    <?php include('js.php'); ?>
    <!-- JS End -->
</head>

<body class="home-page">
    
    <!-- Header Start -->
    <?php include('header.php'); ?>    
    <!-- Header End -->

    <div class="main" id="web-content">
        <?php include('views/transaction.php'); ?>
    </div>
    
    <!-- Footer Start -->
    <?php include('footer.php'); ?>
    <!-- Footer End -->

    <script>
        
        // $(document).ready(function(){
            //For Render PAGE
        //     var urlpath=window.location.pathname;
        //     if(urlpath=='<?php echo $config->getDirpath(); ?>')
        //     {
        //         var pagename='home';
        //     }
        //     else
        //     {
        //         var pagename=urlpath.split("/");
        //         pagename = pagename[pagename.length-1];
        //     }
        //     render(pagename);
        // });

    </script>
</body>
</html>