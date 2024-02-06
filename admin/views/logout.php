<?php require_once dirname(__DIR__, 1).'\config\config.php';
$config = new config();

//session_unset();

?>
<script>
    //window.location='index.php';

    $(document).ready(function () {
        logoutdata();
    });

    function logoutdata()
    {
        var pagename=getpagename();
        var headersdata= {Accept: 'application/json',platform: 1,userpagename:pagename,useraction:'addright',masterlisting:true,responsetype: 'JSON'};
        formdata = new FormData();
        formdata.append("action", "logout");
        ajaxrequest("POST",'<?php echo $config->getEndpointurl(); ?>'+'logout',formdata,headersdata,OnsuccessLogout,OnErrorJson);
    }

</script>