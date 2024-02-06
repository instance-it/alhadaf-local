<?php 

//require_once (__DIR__.'/jwt/jwttoken.php');

class IISMethods {
    
	public function __construct()
	{
		
	}

	function toString($string)
	{
		try{
			return strval($string);
		}
		catch (Exception $e){
			//die(print_r($e->getMessage()));
		}
	}

	function toInt($string)
	{
		try{
			return intval($string);
		}
		catch (Exception $e){
			//die(print_r($e->getMessage()));
		}
	}

	function toDouble($string)
	{
		try{
			return floatval($string);
		}
		catch (Exception $e){
			//die(print_r($e->getMessage()));
		}
	}


	function toDate()
	{
		try{
			
		}
		catch (Exception $e) {
			//die(print_r($e->getMessage()));
		}
	}


	function toBoolean()
	{
		try{
			
		}
		catch (Exception $e){
			//die(print_r($e->getMessage()));
		}
	}

	function TitleCase($string,$type)
	{
		try{
			if($type=='U'){
				return strtoupper($string);
			}
			else if($type=='L'){
				return strtolower($string);
			}
		}
		catch (Exception $e){
			//die(print_r($e->getMessage()));
		}
	}

	function toSum($data)
	{
		try{
			$sum=0;
			for($i=0;$i<count($data);$i++)
			{
			   $sum = $sum + $data[$i];
			}
			return $sum;
		}
		catch (Exception $e){
			//die(print_r($e->getMessage()));
		}
	}

	function RandomOTP($limit)
	{
		try{
			$key = '';
			$keys = array_merge(range(0, 9));
		
			for ($i = 0; $i < $limit; $i++) {
				$key .= $keys[array_rand($keys)];
			}	
			return $key;
		}
		catch (Exception $e){
			//die(print_r($e->getMessage()));
		}
	}	
	
	function RandomString($limit)
	{
		try{
			$key = '';
			$keys = array_merge(range('A', 'Z'));
		
			for ($i = 0; $i < $limit; $i++) {
				$key .= $keys[array_rand($keys)];
			}	
			return $key;
		}
		catch (Exception $e){
			//die(print_r($e->getMessage()));
		}
	}

	

function getpayTMaccesstoken($orderid,$txnamt,$customerid,$type,$dataarray)
{
	$config = new config();

	$result["status"]=0;
	$result["message"]=$errmsg['invalidtoken'];

	if($type == 1) // website booking
	{
		$callbackUrl = $config->getPgcallbackurl();
	}
	else if($type==2) // seat booking payment from web
	{
		$callbackUrl = $config->getSeatPgcallbackurl();
	}
	else if($type==3) // website vehicle charge
	{
		$callbackUrl = $config->getVehiclepgcallbackurl();
	}
	else if($type==4) //agent booking
	{
		$callbackUrl = $config->getBookingPgcallbackurl();
	}
	else if($type==5) //booking from client mobile app (android & ios)
	{
		$callbackUrl = $config->getClientmobileapppgcallbackurl().$orderid;
	}
	else if($type==6) //Terminal booking
	{
		$callbackUrl = $config->getTerminalBookingPgcallbackurl();
	}

	$paytmParams = array();

	$paytmParams["body"] = array(
		"requestType"=>"Payment",
		"mid"=>$config->getPgmerchantid(),
		"websiteName"=>$config->getPgwebsitename(),
		"orderId"=>$orderid,
		"callbackUrl"=>$callbackUrl,
		"txnAmount"=>array(
			"value"=>$txnamt,
			"currency"=> $config->getPgcurrency(),
		),
		"userInfo"=>array(
			"custId"=>$customerid,
		),
		
	);
	
	/*
	* Generate checksum by parameters we have in body
	* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
	*/
	$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $config->getPgmerchantkey());
	
	
	$paytmParams["head"] = array(
		"signature"=>$checksum
	);

	
	
	$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
	
	
	$url = $config->getPgbaseurl()."/theia/api/v1/initiateTransaction?mid=".$config->getPgmerchantid()."&orderId=".$orderid;
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
	$response_curl = curl_exec($ch);

	$json_resp=json_decode($response_curl,true);
	$txnToken='';
	if($json_resp['body']['resultInfo']['resultStatus']=='S' && $json_resp['body']['resultInfo']['resultCode']=='0000')
	{
		$result["status"]=1;
		$result["token"]=$json_resp['body']['txnToken'];
		$result["txnamt"]=$txnamt;
		$result["orderid"]=$orderid;
		$result["dataarray"]=$dataarray;

	}
	else
	{
		
		$result["message"]=$json_resp['body']['resultInfo']['resultMsg'];
	}

	return $result;


}


function payTMrefundaccsessToken($orderid,$txnid,$refamt,$refid)
{
	/* echo $orderid.'<br>'.$txnid.'<br>'.$refamt; */
	$config = new config();

	$paytmParams = array();
	
	$paytmParams["body"] = array(
		"mid"          => $config->getPgmerchantid(),
		"txnType"      => "REFUND",
		"orderId"      => $orderid,
		"txnId"        => $txnid,
		"refId"        => $refid,
		"refundAmount" => $refamt,
	);
	// print_r($paytmParams);
	$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $config->getPgmerchantkey());
	
	
	$paytmParams["head"] = array(
		"signature"	  => $checksum
	);
	
	
	$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);


	/* for Staging */
	$url = $config->getPgbaseurl()."/refund/apply";
	
	/* for Production */
	// $url = "https://securegw.paytm.in/refund/apply";
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
	$response = curl_exec($ch);
	
	$json_resp=json_decode($response,true);
	
	return $json_resp;
}



	function sanitize($string,$type = 'IN')
	{
		try{
			if($type == 'OUT')
			{
				//return htmlentities($string, ENT_QUOTES, 'UTF-8');
				return html_entity_decode($string);
			}
			else {
				return htmlentities($string, ENT_QUOTES, 'UTF-8');
			}
		}
		catch (Exception $e){
			//die(print_r($e->getMessage()));
		}
	}


	function SendSMS($mobileno,$smstext)
	{
		$smstext=urlencode($smstext);
		if($mobileno && $smstext)
		{
			//file_get_contents('http://sms.instanceit.in/SecureApi.aspx?usr=bizcompas&KEY=D2E8DE1F-DDEF-488F-AC93-88EC8AB3DB20&smstype=TextSMS&TO='.$mobileno.'&msg='.$smstext.'&rout=Transactional&FROM=BIZCOM');					
		}			
	}

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

	function checkutypeexist($singleutype,$fullutype)
	{
		$arrstring = explode(',',$fullutype);
		if(in_array($singleutype, $arrstring)) 
		{
			$isutypeexist=1;
		}
		else 
		{
			$isutypeexist=0;	
		}	
		return $isutypeexist;
	}

	function getpageaccess($userrights,$pagename)
	{
		$neededObject = array_filter(
			$userrights,
			function ($e) use (&$pagename) {
				return $e->getFormname()== $pagename;
			}
		);
		
		// return reset($neededObject);

		if(sizeof($neededObject) >0)
		{
			return reset($neededObject);
		}else{
			return $neededObject;
		}
	}

	function getdashpageaccess($userrights,$pagename)
	{
		$neededObject = array_filter(
			$userrights,
			function ($e) use (&$pagename) {
				return $e->getUniqname()== $pagename;
			}
		);

		if(sizeof($neededObject) > 0)
		{
			return reset($neededObject);
		}
		else
		{
			return $neededObject;
		}
	}



	//Upload All Files
	function uploadallfiles($type,$foldername,$filename,$tmpfilename,$filetype,$unqid) //$type = 1 for admin and 0 for website // 1 - One Extra Depth , 0 - No Extra Depth
	{
		
		$year = date('Y');
		$month = date('m');
		$time = time();

		$config = new config();

		$imgbaseurl = $config->getImageurl().'uploads/';

		$extradepth='';
		if($type==1)
		{
			$extradepth='../';
		}

		if (!file_exists($imgbaseurl.$year)) {
			mkdir($extradepth.'../../assets/uploads/'.$year, 0777, true);
		}

		$imgyearurl = $config->getImageurl().'uploads/'.$year.'/';

		if (!file_exists($imgyearurl.$month)) {
			mkdir($extradepth.'../../assets/uploads/'.$year.'/'.$month, 0777, true);
		}

		$imgmonthurl = $config->getImageurl().'uploads/'.$year.'/'.$month.'/';

		if (!file_exists($imgmonthurl.$foldername)) {
			mkdir($extradepth.'../../assets/uploads/'.$year.'/'.$month.'/'.$foldername, 0777, true);
		}

		$imgFileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

		//$imgfolderurl = $config->getImageurl().'uploads/'.$year.'/'.$month.'/'.$foldername.'/';
		$targetPath = 'uploads/'.$year.'/'.$month.'/'.$foldername.'/'.$foldername.$unqid.$time.'.'.$imgFileType;


		//$targetPath1 = $config->getImageurl().$targetPath;
		
		move_uploaded_file($tmpfilename,$extradepth.'../../assets/'.$targetPath);

		return $targetPath;
	}

	

	function sendcontactusemail($name,$emailto)
	{
		$emailtxt='';
		$emailtxt.='<div align="center" bgcolor="#f9f9fb" style="min-height:100%;padding-bottom:50px;font-family:Verdana,Geneva,Arial,Helvetica,sans-serif" width="">';
		$emailtxt.='<table align="center"><tbody><tr><td><table width="580">';
		$emailtxt.='<tbody><tr><td bgcolor="#fff" style="padding-right: 10px; padding-left: 10px; border: 2px solid #2B2A28; border-radius: 15px;">';
		$emailtxt.='<table width="100%"><tbody><tr><td><table width="100%"><tbody><tr>';
		$emailtxt.='<td width="500" style="text-align: center;"><a target="_blank" href="javascript:void(0)" title="Alhadaf Shooting Range"><img width="200" class="CToWUd" src="../assets/img/logo-white.png" style="padding: 6px 0;"></a></td>';
		$emailtxt.='</tr></tbody></table></td></tr></tbody></table></td></tr>';
		$emailtxt.='<tr><td><table cellpadding="15" style="border-style: solid; background-color: #fff; width: 100%; border-color: #2B2A28; border-width: 2px; border-radius: 15px;">';
		$emailtxt.='<tbody><tr><td><table width="100%"><tbody><tr>';
		$emailtxt.='<td align="left" valign="top" bgcolor="#F9F9F9" style="color: rgb(44, 44, 44); line-height: 20px; font-weight: 300; margin: 0px; clear: both; background-color: rgb(255, 255, 255); font-size: 13px; padding: 10px 0px 0px;">';
		$emailtxt.='<p>Dear '.$name.', </p>';
		$emailtxt.='<p>Your message has been successfully sent, We will contact you soon</p>';
		$emailtxt.='</td></tr>';
		$emailtxt.='<tr><td style="color: rgb(44, 44, 44); line-height: 20px; font-weight: 300; margin: 0px; clear: both; background-color: rgb(255, 255, 255); font-size: 13px; padding: 10px 0px 0px;"><p><span style="font-weight: 600;">Best regards,</span><br>Alhadaf Shooting Range</p>';
		$emailtxt.='</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></div>';                                                     

		
		$email_body = $emailtxt;

		$email_subject = "Alhadaf Shooting Range | Contact Us";
		
		$headers = "From: Alhadaf Shooting Range <info@alhadafrange.com>\r\n";
		$headers .= "Reply-To: <info@alhadafrange.com> \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
		// mail($emailto,$email_subject,$email_body,$headers);

	}



	//Get Total Days Between Two Dates
	function getdateDiff($date1, $date2 ,$samedateno=1) 
	{
		if($date1 == $date2)
		{
			return $samedateno;
		}
		else 
		{
			$date1_ts = strtotime($date1);
			$date2_ts = strtotime($date2);
			$diff = $date2_ts - $date1_ts;
			return round($diff / 86400);	
		}
		
	}

	//Get Page Name
	function getpagename() 
	{
		$rightpagename = basename($_SERVER['PHP_SELF']);

		// $rightpagename = $_SERVER['PHP_SELF'];
		// $pagename = explode("/",$rightpagename);

		// $pagename = $pagename[sizeof($pagename)-1];
		
		// $pagename = explode(".",$pagename);
		// $pagename = $pagename[sizeof($pagename)-2];

			
		$pagename = explode(".",$rightpagename);
		$pagename = $pagename[sizeof($pagename)-2];

		return $pagename;
		
	}


	//Get Data From Curl
	function getcurl($url,$method='GET',$payload,$header)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_HTTPHEADER => $header
		));

		$response = curl_exec($curl);
		curl_close($curl);
		
		$jsondata = json_decode($response, true);

		return $jsondata;
	}




	function ind_format($num)
	{
		$explrestunits = "" ;
		$num=preg_replace('/,+/', '', $num);
		if($num==''){ $num=0; }
		$num=trim($num);
		$firstchar=substr($num,0,1); //get first char of number , check if contains + or -
		$sign='';
		if($firstchar=='-')
		{
			$num=ltrim($num, '-');	
			$sign='-';
		}
		else if($firstchar=='+')
		{
			$num=ltrim($num, '+');	
		}
		
		$words = explode(".", $num);
		$des="00";
		if(count($words)<=2){
			$num=$words[0];
			if(count($words)>=2){$des=$words[1];}
			if(strlen($des)<2)
			{
				$des=$des."0";
			}
			else
			{
				$thirdchar=substr($des,2,1);
				$des=substr($des,0,2);
				
				if($thirdchar>=5)
				{
					$des=$des+1;
				}			
			}
		}
		if(strlen($num)>3){
			$lastthree = substr($num, strlen($num)-3, strlen($num));
			$restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
			$restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
			$expunit = str_split($restunits, 2);
			for($i=0; $i<sizeof($expunit); $i++){
				// creates each of the 2's group and adds a comma to the end
				if($i==0)
				{
					$explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
				}else{
					$explrestunits .= $expunit[$i].",";
				}
			}
			$thecash = $explrestunits.$lastthree;
		} else {
			$thecash = $num;
		}
		return $sign."".$thecash.".".$des; // writes the final format where $currency is the currency symbol.
	}


	//Convert Cart Item Data
	function convertcartjsontoobjarr($cartitemdata)
	{
		$cartitemdata = json_decode($cartitemdata,true);
		$LoginInfo = new LoginInfo();

		$iss=$cartitemdata[0]['iss'];
		if($cartitemdata[0]['iss']==null){ $iss=""; }
		$LoginInfo->setIss($iss);


		$LoginInfo->setKey($cartitemdata[0]['key']);
		$LoginInfo->setUid($cartitemdata[0]['uid']);
		$LoginInfo->setUnqkey($cartitemdata[0]['unqkey']);


		$username=$cartitemdata[0]['username'];
		if($cartitemdata[0]['username']==null){ $username=""; }
		$LoginInfo->setUsername($username);

		$utypeid=$cartitemdata[0]['utypeid'];
		if($cartitemdata[0]['utypeid']==null){ $utypeid=""; }
		$LoginInfo->setUtypeid($utypeid);

		$cmpid=$cartitemdata[0]['cmpid'];
		if($cartitemdata[0]['cmpid']==null){ $cmpid=""; }
		$LoginInfo->setCmpid($cmpid);

		$branchid=$cartitemdata[0]['branchid'];
		if($cartitemdata[0]['branchid']==null){ $branchid=""; }
		$LoginInfo->setBranchid($branchid);

		$fullname=$cartitemdata[0]['fullname'];
		if($cartitemdata[0]['fullname']==null){ $fullname=""; }
		$LoginInfo->setFullname($fullname);

		$adlogin=$cartitemdata[0]['adlogin'];
		if($cartitemdata[0]['adlogin']==null){ $adlogin=""; }
		$LoginInfo->setAdlogin($adlogin);
		
		$yearid=$cartitemdata[0]['yearid'];
		if($cartitemdata[0]['yearid']==null){ $yearid=""; }
		$LoginInfo->setYearid($yearid);

		$email=$cartitemdata[0]['email'];
		if($cartitemdata[0]['email']==null){ $email=""; }
		$LoginInfo->setEmail($email);

		$Contact=$cartitemdata[0]['contact'];
		if($cartitemdata[0]['contact']==null){ $Contact=""; }
		$LoginInfo->setContact($Contact);

		$yearname=$cartitemdata[0]['yearname'];
		if($cartitemdata[0]['yearname']==null){ $yearname=""; }
		$LoginInfo->setYearname($yearname);

		$activeyearid=$cartitemdata[0]['activeyearid'];
		if($cartitemdata[0]['activeyearid']==null){ $activeyearid=""; }
		$LoginInfo->setActiveyearid($activeyearid);

		$profilepic=$cartitemdata[0]['profilepic'];
		if($cartitemdata[0]['profilepic']==null){ $profilepic=""; }
		$LoginInfo->setProfilepic($profilepic);

		$isguestuser=$cartitemdata[0]['isguestuser'];
		if($cartitemdata[0]['isguestuser']==null){ $isguestuser=""; }
		$LoginInfo->setIsguestuser($isguestuser);

		$ismemberuser=$cartitemdata[0]['ismemberuser'];
		if($cartitemdata[0]['ismemberuser']==null){ $ismemberuser=""; }
		$LoginInfo->setIsmemberuser($ismemberuser);

		$userrights=$cartitemdata[0]['userrights'];
		if($cartitemdata[0]['userrights']==null){ $userrights=""; }
		$LoginInfo->setUserrights($userrights);



		$couponapply=$cartitemdata[0]['couponapply'];
		if($cartitemdata[0]['couponapply']==null){ $couponapply=""; }
		$LoginInfo->setcouponapply($couponapply);

		$couponid=$cartitemdata[0]['couponid'];
		if($cartitemdata[0]['couponid']==null){ $couponid=""; }
		$LoginInfo->setcouponid($couponid);

		$couponcode=$cartitemdata[0]['couponcode'];
		if($cartitemdata[0]['couponcode']==null){ $couponcode=""; }
		$LoginInfo->setcouponcode($couponcode);

		$coupontype=$cartitemdata[0]['coupontype'];
		if($cartitemdata[0]['coupontype']==null){ $coupontype=""; }
		$LoginInfo->setcoupontype($coupontype);

		$couponamount=$cartitemdata[0]['couponamount'];
		if($cartitemdata[0]['couponamount']==null){ $couponamount=""; }
		$LoginInfo->setcouponamount($couponamount);

		$couponpercent=$cartitemdata[0]['couponpercent'];
		if($cartitemdata[0]['couponpercent']==null){ $couponpercent=""; }
		$LoginInfo->setcouponpercent($couponpercent);


		$singleiteminfo = array();
		for($t=0;$t<sizeof($cartitemdata[0]['cartiteminfo']);$t++)
		{
			$CartItemInfo = new CartItemInfo();
			$citemarr=$cartitemdata[0]['cartiteminfo'][$t];
			
			$CartItemInfo->setId($citemarr['id']);
			$CartItemInfo->setItemname($citemarr['itemname']);
			$CartItemInfo->setPrice($citemarr['price']);

			$CartItemInfo->setTaxtype($citemarr['taxtype']);
			$CartItemInfo->setTaxtypename($citemarr['taxtypename']);
			$CartItemInfo->setSgst($citemarr['sgst']);
			$CartItemInfo->setCgst($citemarr['cgst']);
			$CartItemInfo->setIgst($citemarr['igst']);
			$CartItemInfo->setTaxable($citemarr['taxable']);
			$CartItemInfo->setIgstTaxAmt($citemarr['igsttaxamt']);
			$CartItemInfo->setSgstTaxAmt($citemarr['sgsttaxamt']);
			$CartItemInfo->setCgstTaxAmt($citemarr['cgsttaxamt']);
			$CartItemInfo->setFinalprice($citemarr['finalprice']);
			$CartItemInfo->setDuration($citemarr['duration']);
			$CartItemInfo->setDurationname($citemarr['durationname']);
			$CartItemInfo->setStrValidityDuration($citemarr['strvalidityduration']);

			$CartItemInfo->setType($citemarr['type']);
			$CartItemInfo->setImage($citemarr['image']);
			$CartItemInfo->setIconImg($citemarr['iconimg']);


			$couponamount=$citemarr['couponamount'];
			if($citemarr['couponamount']==null){ $couponamount=""; }
			$CartItemInfo->setcouponamount($couponamount);

			//$CartItemInfo->setcouponamount($citemarr['couponamount']);

			$singleiteminfo[$t]=$CartItemInfo;
		}

		$LoginInfo->setCartItemInfo($singleiteminfo);

		return $LoginInfo;
	}
	
	
	//Remove Coupon
	function removecoupon($LoginInfo,$platform)
	{
		$IISMethods = new IISMethods();
		if($platform==4)
		{
			$config = new config();
			$LoginInfo = $_SESSION[$config->getSessionName()];
		}
		
		$LoginInfo->setcouponapply(0);
			
		$LoginInfo = $IISMethods->couponapplyamount($LoginInfo,$platform);

		return $LoginInfo; 	
	}


	//Coupon Apply Amount Calculations
	function couponapplyamount($LoginInfo,$platform)
	{
		if($platform==4)
		{
			$config = new config();
			$LoginInfo = $_SESSION[$config->getSessionName()];	
		}
		$cartiteminfo = $LoginInfo->getCartItemInfo();
		
		$LoginInfo->setcouponapply(0);
		$LoginInfo->setcouponid('');
		$LoginInfo->setcouponcode('');
		$LoginInfo->setcoupontype('');
		$LoginInfo->setcouponamount(0);
		
		$Sess_CartItemInfo=$LoginInfo->getCartItemInfo();

		$singleiteminfo=array();

		for($i=0; $i<sizeof($Sess_CartItemInfo); $i++)
		{
			$CartItemInfo = new CartItemInfo();

			$taxableamt=0;
			$taxamt=0;
			$finalprice=0;
			if($Sess_CartItemInfo[$i]->getTaxtype() == 1)  //For Exclusive Tax
			{
				$taxableamt=$Sess_CartItemInfo[$i]->getPrice();
				$taxamt=round(($Sess_CartItemInfo[$i]->getPrice()*$Sess_CartItemInfo[$i]->getIgst()/100),3);
				$finalprice=$taxableamt+$taxamt;
			}
			else if($Sess_CartItemInfo[$i]->getTaxtype() == 2)  //For Inclusive Tax
			{
				$taxableamt=round((($Sess_CartItemInfo[$i]->getPrice()*100)/(100+$Sess_CartItemInfo[$i]->getIgst())),3);
				$taxamt=round(($Sess_CartItemInfo[$i]->getPrice()-$taxableamt),3);
				$finalprice=$Sess_CartItemInfo[$i]->getPrice();
			}

			$CartItemInfo->setId($Sess_CartItemInfo[$i]->getId());
			$CartItemInfo->setItemname($Sess_CartItemInfo[$i]->getItemname());
			$CartItemInfo->setPrice($Sess_CartItemInfo[$i]->getPrice());

			$CartItemInfo->setTaxtype($Sess_CartItemInfo[$i]->getTaxtype());
			$CartItemInfo->setTaxtypename($Sess_CartItemInfo[$i]->getTaxtypename());
			$CartItemInfo->setSgst($Sess_CartItemInfo[$i]->getSgst());
			$CartItemInfo->setCgst($Sess_CartItemInfo[$i]->getCgst());
			$CartItemInfo->setIgst($Sess_CartItemInfo[$i]->getIgst());
			$CartItemInfo->setTaxable($taxableamt);
			$CartItemInfo->setIgstTaxAmt($taxamt);
			$CartItemInfo->setSgstTaxAmt($taxamt/2);
			$CartItemInfo->setCgstTaxAmt($taxamt/2);
			$CartItemInfo->setFinalprice($finalprice);
			$CartItemInfo->setDuration($Sess_CartItemInfo[$i]->getDuration());
			$CartItemInfo->setDurationname($Sess_CartItemInfo[$i]->getDurationname());
			$CartItemInfo->setStrValidityDuration($Sess_CartItemInfo[$i]->getStrValidityDuration());

			$CartItemInfo->setType($Sess_CartItemInfo[$i]->getType());
			$CartItemInfo->setImage($Sess_CartItemInfo[$i]->getImage());
			$CartItemInfo->setIconImg($Sess_CartItemInfo[$i]->getIconImg());

			$CartItemInfo->setcouponamount(0);

			$singleiteminfo[$i]=$CartItemInfo;
		}

		$LoginInfo->setCartItemInfo($singleiteminfo);

	
		return $LoginInfo;
	}



	function getdatetime()
	{
		return date('Y-m-d H:i:s');
	}

	function getcurrdate()
	{
		return date('d/m/Y');
	}

	function getafteryeardate()
	{
		return date('d/m/Y' ,strtotime('+1 year'));
	}

	function getformatcurrdate()
	{
		return date('Y-m-d');
	}

	function getcurrtimestring()
	{
		return date('Hi');
	}

	function getcurrtimefullstring()
	{
		return date('His');
	}
	
	function cast($array, $className)
	{
		return unserialize(sprintf(
			'O:%d:"%s"%s',
			\strlen($className),
			$className,
			strstr(strstr(serialize($array), '"'), ':')
		));
	}

	function unsetbookingseesion()
	{
		$config = new config();
		$IISMethods = new IISMethods();

		$LoginInfo = $_SESSION[$config->getSessionName()];
		$LoginInfo->setCartItemInfo([]);

		
		$LoginInfo->setcouponapply('');
		$LoginInfo->setcouponid('');
		$LoginInfo->setcouponcode('');
		$LoginInfo->setcoupontype('');
		$LoginInfo->setcouponamount('');
		$LoginInfo->setcouponpercent('');
		
	}


	//--------------------------- Android Notification ----------------------------
	function androidnotification($deviceid,$message,$title,$clickaction,$notidata)
	{
		define( 'API_ACCESS_KEY', 'AAAAq-IK96g:APA91bFIIuJoCiRhzc5j5zVN865-QgMJZp8POvHOZwMRzloSZBQ0tIj2crU6Zc4x20DhdrOTLjx9AkYYQ19sGDlym2LHlkw06hKObv5qoy5gvxl8hOVM9VrXkZMfbz9WiHXkwGpbaXdZ' );
		
		
		$msg = array
			(
				'body' 	=> $message,
				'title'	=> $title,
				'click_action' => $clickaction,
				//'icon'	=> 'images/bell.png',
				'icon'	=> 'myicon',/*Default Icon*/
				'sound' => 'mySound'/*Default sound*/  	
			);
		
		$fields = array
			(
				'registration_ids' => $deviceid,
				'notification' => $msg,
				'data' => $notidata
			);
		$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			
				
		#Send Reponse To FireBase Server
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		//echo $result;
		curl_close( $ch );
		//$data1 = json_encode( $fields );
		
		//print_r($data1);
	}



	//--------------------------- IOS Notification ----------------------------
	function iosnotification($deviceid,$msg,$title,$extra1)
	{
		//if(1 == 2)
		//{
			$keyid = '2LRQX8XBQ7';                            # <- Your Key ID
			$teamid = '3J9DVYACL7';                           # <- Your Team ID (see Developer Portal)
			$bundleid = 'com.instanceit.alhadaf';                # <- Your Bundle ID
		
			$url = 'https://api.development.push.apple.com';  # <- development url, or use http://api.push.apple.com for production environment
			//$url = 'https://api.push.apple.com';
			//$token = '1abd5c69c5464471a829f1d953f1d48255376eba9510b9d2436812da23135bc5';              # <- Device Token
			
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
				//echo $status;
			}
		//}	
	}



	//Curl Response
	function FnCURLResponse($CURLOPT_URL,$CURLOPT_CUSTOMREQUEST,$CURLOPT_POSTFIELDS,$CURLOPT_HTTPHEADER,$APINAME)
	{	
		$DB = new DBconfig();
		
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_URL =>$CURLOPT_URL,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST =>$CURLOPT_CUSTOMREQUEST,
			CURLOPT_POSTFIELDS =>$CURLOPT_POSTFIELDS,
			CURLOPT_HTTPHEADER =>$CURLOPT_HTTPHEADER,
		));
		$response = curl_exec($curl);
		  
		$httpcode='';
		$total_time=0;
		if($response)
		{
			$curl_info = curl_getinfo($curl);
			$httpcode=$curl_info['http_code'];
			$total_time=$curl_info['total_time'];		
		}
		curl_close($curl);
		$DB->InsertCurlApiLogData($APINAME,$CURLOPT_URL,$CURLOPT_POSTFIELDS,$response,$httpcode,$total_time);


		$ary=json_decode($response,true);

		return $ary;
	}



}


?>