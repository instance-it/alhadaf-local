<?php  //require_once 'config/init.php'; 

echo __DIR__;
exit;

echo  'Normal Time : '.date ('d/m/Y h:i A');

echo '<br><br>';

echo date_default_timezone_get();
echo '<br><br>';

date_default_timezone_set("Asia/Qatar"); 
echo 'Qatar Time : '.date ('d/m/Y h:i A');

echo '<br><br>';

date_default_timezone_set("Asia/Kolkata"); 
echo 'Indian Time : '.date ('d/m/Y h:i A');


echo '<br>'.date('M d Y h:iA');

?>