<?php 
require_once 'LoginInfo.php';
require_once 'DBconfig.php';
require_once 'Userrights.php';
require_once 'CompanyInfo.php';
require_once 'ProjectSetting.php';
require_once 'CartItemInfo.php';


$useragent=$_SERVER['HTTP_USER_AGENT'];
$DB= new DBconfig();

$SubDB= new DBconfig();

//Log Database connection
$SubDB->setDBType('HANA');
$SubDB->setDBName('TESTING_1');
$SubDB->setDBUser('SYSTEM');
$SubDB->setDBHost('odbc:ALHADAF_INT');
$SubDB->setDBPass('Hana@2021');
$SubDB->setDBPort('');
//$SubDB->Connect();

//$DB= new DBconfig();
$LoginInfo = new LoginInfo();
$IISMethods = new IISMethods();
$config = new config();

$CartItemInfo = new CartItemInfo();

$errmsg=$config->getErrmsg();
$mssqldefval=$config->getMssqldefval();

$serviceurl=$config->getEndpointurl();


$CompanyInfo = new CompanyInfo();
$CompanyInfo=$DB->getcompanyinfo('CompanyInfo')[0]; 

$ProjectSetting = new ProjectSetting();
$ProjectSetting = $DB->getsettingdata('ProjectSetting')[0];

session_start();

$headerparams=array_change_key_case(getallheaders(),CASE_LOWER);

if($_SESSION[$config->getSessionName()]!='')
{

	$Userights = new Userrights();
    $LoginInfo=$_SESSION[$config->getSessionName()];
	$LoginInfo->setUserrights($DB->getuserright($LoginInfo->getUid(),$LoginInfo->getUtypeid(),1,'Userrights'));
	$_SESSION[$config->getSessionName()]=$LoginInfo;

}

// insert data in history
$DB->puthistory($LoginInfo);

$Pagerights = $IISMethods->getpageaccess($LoginInfo->getUserrights(),$IISMethods->getpagename());

function generateuuid()
{
	return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		mt_rand( 0, 0xffff ),
		mt_rand( 0, 0x0C2f ) | 0x4000,
		mt_rand( 0, 0x3fff ) | 0x8000,
		mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
	);
}



?>