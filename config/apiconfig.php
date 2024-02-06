<?php 

$key=$IISMethods->sanitize($headerparams['key']);
$unqkey=$IISMethods->sanitize($headerparams['unqkey']);
$iss=$IISMethods->sanitize($headerparams['iss']);
$uid =$IISMethods->sanitize($headerparams['uid']);
$platform=$IISMethods->sanitize($headerparams['platform']);
$responsetype=$IISMethods->sanitize($headerparams['responsetype']);
$userpagename=$IISMethods->sanitize($headerparams['userpagename']);
$useraction=$IISMethods->sanitize($headerparams['useraction']);
$masterlisting=$IISMethods->sanitize($headerparams['masterlisting']);
$curl=(int)$IISMethods->sanitize($headerparams['curl']);

if($platform == 4 || $platform == 2 || $platform == 3)  //When 4-Website, 2-Android App, 3-iOS App
{
	$languageid=$IISMethods->sanitize($headerparams['languageid']);
	$errmsg=$DB->getlanguagewisemsg(2,$languageid);	
}
else if($platform == 5)  //When 5-POS
{
	$languageid=$IISMethods->sanitize($headerparams['languageid']);
	$errmsg=$DB->getlanguagewisemsg(3,$languageid);	
}


if($platform == 1)  //1-Admin Side 
{
	$key=$LoginInfo->getKey();
	$unqkey=$LoginInfo->getUnqkey();
	$iss=$LoginInfo->getIss();
	$uid=$LoginInfo->getUid();
}
else if($platform == 4) //4-Website
{
	$key=$LoginInfo->getKey();
	$unqkey=$LoginInfo->getUnqkey();
	$iss=$LoginInfo->getIss();
	$uid=$LoginInfo->getUid();
}
$action=$_POST['action'];
$entrydate=$IISMethods->getdatetime();


$formdataactionarr = array("register","login","logout","checksociallogin","insertacceptcookie","insertcontactus","insertrangebooking","changepassword","updateprofiledata","adddevicedata","addtocartitem","removecartitem","countcartitem","applycoupon","couponscoderemove","forgotpass","setnewpassword","insertemaildata","appcomparemshippkg");

if($uid)
{
	//User Validate
	$isvalidUser=$DB->validateuser($uid,$platform,$key,$unqkey,$iss,$useragent,$userpagename,$useraction,$masterlisting,$action,$formdataactionarr);
	$status=$isvalidUser['status'];
	$message=$isvalidUser['message'];

}
else
{
	
	if($platform == 4)  //4-Website
	{
		$guestuserid = 'guestuser-'.session_id();
		$unqkey= $IISMethods->generateuuid();

		$iss='alhadaf-website';
		$LoginInfo->setIss($iss);
		$LoginInfo->setUnqkey($unqkey);

		$key=$DB->getjwt($guestuserid,$unqkey,$iss,$useragent);
		if($key['status']==1)
		{
			$LoginInfo->setKey($key['token']);
			$LoginInfo->setUid($guestuserid);
			$LoginInfo->setUtypeid($mssqldefval['uniqueidentifier']);
			$LoginInfo->setFullname('Guest User');
			$LoginInfo->setIsguestuser(1);

			$_SESSION[$config->getSessionName()]=$LoginInfo;

			$key=$IISMethods->sanitize($key['token']);
			$unqkey=$IISMethods->sanitize($unqkey);
			$iss=$IISMethods->sanitize($iss);
			$uid =$IISMethods->sanitize($guestuserid);

			//User Validate
			$isvalidUser=$DB->validateuser($guestuserid,$platform,$key,$unqkey,$iss,$useragent,$userpagename,$useraction,$masterlisting,$action,$formdataactionarr);
			
			$status=$isvalidUser['status'];
			$message=$isvalidUser['message'];
		}
		else
		{
			$status=-1;
			$message=$arr_msg['sessiontimeout'];
		}
	}
	else
	{
		$status=-1;
		$message=$errmsg['sessiontimeout'];
	}
}

// print_r($isvalidUser);
//list Data Use variable
$nextpage =$IISMethods->sanitize($_POST['nextpage']);
$per_page = $IISMethods->sanitize($_POST['perpage']);
if($platform == 2 || $platform == 3)
{
	$per_page = 10;
	$appmenuname =$IISMethods->sanitize($_POST['appmenuname']);
	$activeusertypeid = $IISMethods->sanitize($_POST['activeusertypeid']);
}
if(!$per_page)
{
	$per_page=20;
}
$page = $IISMethods->sanitize($_POST['nextpage']);
$filter = $IISMethods->sanitize('%'.$_POST['filter'].'%');
$ordbycolumnname = $IISMethods->sanitize($_POST['ordbycolumnname']);
$ordby = $IISMethods->sanitize($_POST['ordby']);
$page -= 1;
$start = $page * $per_page;

?>

  