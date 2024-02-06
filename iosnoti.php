<?php require_once 'config/init.php';

$title = "Test Title";
$msg="Test Message";


$deviceid=array('013b9cbe0d9d43a77d41c3f4a7f521717778f2052a71ec59999bd73d1dc42ea6');

$clickaction="alhadaf_ntf";
$clickflag=1;
$data="";
$pagename='';
$actionname='';
                
$extra1 = array('clickflag' => $clickflag,'pagename' =>$pagename,'actionname' =>$actionname,'imageurl' => '');
$IISMethods->iosnotification($deviceid,$msg,$title,$extra1);
exit;


$keyid = '2LRQX8XBQ7';                            # <- Your Key ID
$teamid = '3J9DVYACL7';                           # <- Your Team ID (see Developer Portal)
$bundleid = 'com.instanceit.alhadaf';                # <- Your Bundle ID

$url = 'https://api.development.push.apple.com';  # <- development url, or use http://api.push.apple.com for production environment
//$url = 'https://api.push.apple.com';


$extra='';
if($extra1)
{
    $extra=json_encode($extra1);
}


for($i=0 ; $i < sizeof($deviceid) ; $i++)
{
    $token = $deviceid[$i];
    
    $message = '{"aps":{"alert":{"title":"'.$title.'","body":"'.$msg.'"},"sound":"default","data":'.$extra.'}}';

    $key = openssl_pkey_get_private(file_get_contents(__DIR__.'/AuthKey_2LRQX8XBQ7.p8'));
    
    $header = ['alg'=>'ES256','kid'=>$keyid];
    $claims = ['iss'=>$teamid,'iat'=>time()];
    
    $header_encoded = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
    $claims_encoded = rtrim(strtr(base64_encode(json_encode($claims)), '+/', '-_'), '=');
    
    $signature = '';
    openssl_sign($header_encoded . '.' . $claims_encoded, $signature, $key, 'sha256');
    $jwt = $header_encoded . '.' . $claims_encoded . '.' . base64_encode($signature);
    
    // only needed for PHP prior to 5.5.24
    if (!defined('CURL_HTTP_VERSION_2_0')) {
        define('CURL_HTTP_VERSION_2_0', 3);
    }
    
    $http2ch = curl_init();
    curl_setopt_array($http2ch, array(
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
        CURLOPT_URL => "$url/3/device/$token",
        CURLOPT_PORT => 443,
        CURLOPT_HTTPHEADER => array(
        "apns-topic: {$bundleid}",
        "authorization: bearer $jwt"
        ),
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => $message,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HEADER => 1
    ));
    
    $result = curl_exec($http2ch);
    if ($result === FALSE) {
        throw new Exception("Curl failed: ".curl_error($http2ch));
    }
    
    $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);	
    echo $status;
}

?>
   