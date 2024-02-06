<?php 
require_once dirname(__DIR__, 1).'\admin\config\init.php';
if($LoginInfo->getAdlogin()!="aldaf$20220117@instance%")
{
	?> <script> window.location="index.php?session=timeout&targeturl=<?php echo htmlspecialchars(basename($_SERVER['PHP_SELF'])) ?>"; </script> <?php
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title>Alhadaf Shooting Range</title>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <?php require_once 'css.php'; ?>
        
        <!-- END GLOBAL MANDATORY STYLES -->
    </head>
    <body class="alt-menu">
        <?php require_once 'header.php'; ?>
        <div class="main-container sidebar-closed sbar-open" id="container">
            <div class="overlay"></div>
            <div class="search-overlay"></div>

            <!--  BEGIN SIDEBAR  -->
            <?php require_once 'sidebar.php'; ?>
            <!--  END SIDEBAR  -->

            <!--  BEGIN CONTENT AREA  -->
            <div id="content" class="main-content">

            </div>
            <!--  END CONTENT AREA  -->
            
            <!--  START FOOTER AREA  -->
            <?php require_once 'footer.php'; ?>
            <!--  END FOOTER AREA  -->
        </div>
        <?php require_once 'js.php'; ?>

        <script>
            $(document).ready(function () {
                var urlpath=window.location.pathname;
                if(urlpath=='<?php echo $config->getDirpath(); ?>')
                {
                    var pagename='dashboard';
                }
                else
                {
                    var pagename=urlpath.split("/");
                    pagename = pagename[pagename.length-1];
                }
                render(pagename);
            });


        </script>
    </body>
</html>    