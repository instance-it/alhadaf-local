<?php 


class config {

    private $weburl;
	private $websiteurl;
	private $imageurl;
	private $endpointurl;
	private $endpointverson;
    private $docurl;
    private $regdomain;
    private $mailurl;
    private $protocol;
    private $docpath;
    private $cdnurl;
    private $servermode;
	private $maindirpath;
    private $dirpath;
	private $page404url;
	private $nodatafound;

    private $mssqldefval;
    private $mysqldefval;
    private $errmsg;

	private $sessionname;
	private $adminutype;
	private $storeutype;
	private $memberutype;
	private $guestutype;

	private $savesidebar;
	private $resetsidebar;
	private $closesidebar;
	private $applyfiltersidebar;

	//for Company Type
	private $cmpregisterid;
	private $cmpunregisterid;
	private $cmpcompositionschemeid;
	private $cmpid;
	
	private $updatemyprofile;
	private $changepassword;

	private $stateid;

	private $adminuid;

	private $defaultcatmembershipid;
	private $defaultcatcourseid;
	private $defaultcatpackageid;

	private $defaultcatsaleableid;
	private $defaultcatconsumableid;
	private $defaultcatreturnableid;
	private $websitefilesize;

	
	private $defaultposimage;
	private $defaultposicon;

	private $defualtmemberimageurl;
	private $defualtcourseimageurl;
	private $defualtmembershipimageurl;
	private $defualtpackageimageurl;

	private $deffirsttimeguestid;
	private $defrepeatedguestid;
	private $defmemberguestid;

	
	private $invoicetermsconditionsid;
	private $alhadafpdfurl;

	private $isaccessSAP;

	private $filepdfimg;

    function  __construct()
	{
		date_default_timezone_set("Asia/Kolkata");        
		
		$this->protocol = 'http://';  // http:// or https://
		$this->servermode= 'prod'; // prod - Live | uat - test | dev = development
		$this->docroot= $_SERVER['DOCUMENT_ROOT'].'/';

		//  project lavel configuration 
		$this->sessionname='alhadaf_1701221033';
		$this->adminutype='98F2B6E0-240A-457B-853A-A0079862315F';
		$this->storeutype='B52EBDBB-6560-4B10-956C-0745E5F36610';
		$this->memberutype='C8DA8E22-A91E-4BFC-894F-1948C5C16315';
		$this->guestutype='833599F5-BB12-4101-95A3-025CD5308796';
		$this->page404url='/views/404';

		$this->adminuid='034FB884-A865-4127-B90B-3D06047A72CC';

		$this->defaultcatmembershipid='8604F182-D96F-4398-AE8E-542B991DD0C3';
		$this->defaultcatcourseid='02FE2B6E-2327-468F-8458-045BD9370DC3';
		$this->defaultcatpackageid='4DB7D448-FFE5-498B-BB1F-5737543AE824';
		$this->defaultcatsaleableid='2AEBCBE4-4074-4BD8-9B11-1375A0533496';
		$this->defaultcatconsumableid='F952A6DE-30B3-44F2-B90B-E9AC400B84F5';
		$this->defaultcatreturnableid='74AE95E7-4709-4FB4-A381-0C5B377EA189';

		$this->savesidebar='Save';
		$this->resetsidebar='Reset';
		$this->closesidebar='Close';
		$this->updatesidebar='Update';
		$this->applyfiltersidebar='Apply';
		

		//company Type configuration
		$this->cmpregisterid='E80F52C1-F01C-4301-9438-62CE1E7DD58A';
		$this->cmpunregisterid='0920A058-CFF3-4000-B018-7F168E87054D';
		$this->cmpcompositionschemeid='4A23364E-8A23-4852-9ACF-D14F1294961E';
		$this->cmpid='B3E18DB5-2A63-4C02-B763-0D3D4A9057CA';


		$this->deffirsttimeguestid='D901970C-577B-45CD-B4B7-E897FD560750';
		//$this->defrepeatedguestid='9BB5C954-34B9-42C0-80AC-80BA00BA4900';
		$this->defrepeatedguestid='44E3AECA-92E6-4718-A0EB-59B70D9F28FB';
		$this->defmemberguestid='B3E18DB5-2A63-4C02-B763-0D3D4A9057CA';


		$this->updatemyprofile='Update Profile';
		$this->changepassword='Change Password';
		$this->confirmedstatus='Confirmed';
		$this->cancelledstatus='Cancelled';

		$this->invoicetermsconditionsid='6D862ADC-5501-4AA2-A21A-217D15C5012E';

		$this->filepdfimg='images/filepdf.png';

		$this->websitefilesize=500000;

		$this->isaccessSAP=1;
	// end of strings 

    /*****************************************************************   
    * 
    * Server Lavel Enviroment Configuration 
    * 
    * 
    */
	if ($this->servermode == "local_dev") // Local Development 
    {
		$this->maindirpath='/alhadaf_dev/';
        $this->dirpath=$this->maindirpath.'pos/';  /* Directory Name also look in index.php script tag*/
        $this->host=$_SERVER['HTTP_HOST'].$this->dirpath;
        $this->weburl = $this->protocol.$this->host;
		$this->websiteurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath;
        $this->docpath = $this->docroot;
        $this->cdnurl =  $this->protocol.$this->host;
        $this->regdomain = '192.168.1.2/';
        $this->mailurl = "192.168.1.2/alhadaf_dev/pos/";
		$this->endpointverson = 'v1.0/';
		$this->endpointurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->dirpath.'api/'.$this->endpointverson;
		$this->imageurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/';
		$this->page404url=$this->protocol.$this->host.$this->page404url;
		$this->nodatafound=$this->protocol.$this->host.'views/nodatafound.php';

		$this->defaultposimage=$this->imageurl.'images/defaultposimage.png';
		$this->defaultposicon=$this->imageurl.'images/defaultposicon.png';
		$this->defualtmemberimageurl=$this->imageurl.'images/defualtmember.png';

		$this->defualtcourseimageurl=$this->imageurl.'images/defualtcourse.jpg';
		$this->defualtmembershipimageurl=$this->imageurl.'images/defualtmembership.jpg';
		$this->defualtpackageimageurl=$this->imageurl.'images/defualtpackage.jpg';

		$this->alhadafpdfurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/plugin/alhadafpdf/';

    }
    else if ($this->servermode == "dev") // Development 
    {
		$this->maindirpath='/alhadaf_dev/';
        $this->dirpath=$this->maindirpath.'pos/';  /* Directory Name also look in index.php script tag*/
        $this->host=$_SERVER['HTTP_HOST'].$this->dirpath;
        $this->weburl = $this->protocol.$this->host;
		$this->websiteurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath;
        $this->docpath = $this->docroot;
        $this->cdnurl =  $this->protocol.$this->host;
        $this->regdomain = 'localhost/';
        $this->mailurl = "localhost/alhadaf_dev/pos/";
		$this->endpointverson = 'v1.0/';
		$this->endpointurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->dirpath.'api/'.$this->endpointverson;
		$this->imageurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/';
		$this->page404url=$this->protocol.$this->host.$this->page404url;
		$this->nodatafound=$this->protocol.$this->host.'views/nodatafound.php';

		$this->defaultposimage=$this->imageurl.'images/defaultposimage.png';
		$this->defaultposicon=$this->imageurl.'images/defaultposicon.png';
		$this->defualtmemberimageurl=$this->imageurl.'images/defualtmember.png';

		$this->defualtcourseimageurl=$this->imageurl.'images/defualtcourse.jpg';
		$this->defualtmembershipimageurl=$this->imageurl.'images/defualtmembership.jpg';
		$this->defualtpackageimageurl=$this->imageurl.'images/defualtpackage.jpg';

		$this->alhadafpdfurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/plugin/alhadafpdf/';
    }
    else if($this->servermode=="prod") // live 
    {
		$this->maindirpath='/';
        $this->dirpath=$this->maindirpath.'pos/';  /* Directory Name also look in index.php script tag*/
        $this->host=$_SERVER['HTTP_HOST'].$this->dirpath;
        $this->weburl = $this->protocol.$this->host;
		$this->websiteurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath;
        $this->docpath = $this->docroot;
        $this->cdnurl =  $this->protocol.$this->host;
        $this->regdomain = 'alhadafrange.com/';
        $this->mailurl = "alhadafrange.com/pos/";
		$this->endpointverson = 'v1.0/';
		$this->endpointurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->dirpath.'api/'.$this->endpointverson;
		$this->imageurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/';
		$this->page404url=$this->protocol.$this->host.$this->page404url;
		$this->nodatafound=$this->protocol.$this->host.'views/nodatafound.php';
		
		$this->defaultposimage=$this->imageurl.'images/defaultposimage.png';
		$this->defaultposicon=$this->imageurl.'images/defaultposicon.png';
		$this->defualtmemberimageurl=$this->imageurl.'images/defualtmember.png';

		$this->defualtcourseimageurl=$this->imageurl.'images/defualtcourse.jpg';
		$this->defualtmembershipimageurl=$this->imageurl.'images/defualtmembership.jpg';
		$this->defualtpackageimageurl=$this->imageurl.'images/defualtpackage.jpg';

		$this->alhadafpdfurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/plugin/alhadafpdf/';
    }
    else if($this->servermode=="uat") // test 
    { 
		$this->maindirpath='/alhadaf_dev/';
        $this->dirpath=$this->maindirpath.'pos/';  /* Directory Name also look in index.php script tag*/
        $this->host=$_SERVER['HTTP_HOST'].$this->dirpath;
        $this->weburl = $this->protocol.$this->host;
		$this->websiteurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath;
        $this->docpath = $this->docroot;
        $this->cdnurl =  $this->protocol.$this->host;
        $this->regdomain = '192.168.1.27';
        $this->mailurl = "192.168.1.27/alhadaf_dev/pos/";
		$this->endpointverson = 'v1.0/';
		$this->endpointurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->dirpath.'api/'.$this->endpointverson;
		$this->imageurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/';
		$this->page404url=$this->protocol.$this->host.$this->page404url;
		$this->nodatafound=$this->protocol.$this->host.'views/nodatafound.php';
		
		$this->defaultposimage=$this->imageurl.'images/defaultposimage.png';
		$this->defaultposicon=$this->imageurl.'images/defaultposicon.png';
		$this->defualtmemberimageurl=$this->imageurl.'images/defualtmember.png';

		$this->defualtcourseimageurl=$this->imageurl.'images/defualtcourse.jpg';
		$this->defualtmembershipimageurl=$this->imageurl.'images/defualtmembership.jpg';
		$this->defualtpackageimageurl=$this->imageurl.'images/defualtpackage.jpg';

		$this->alhadafpdfurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/plugin/alhadafpdf/';
    }

		
	$this->mssqldefval=array(
		'int'=>0,
		'bigint'=>0,
		'binary'=>'',
		'bit'=>0,
		'char'=>'',
		'date'=>'1900-01-01',
		'datetime'=>'1900-01-01 00:00:00',
		'datetime2'=>'1900-01-01 00:00:00',
		'datetimeoffset'=>'1900-01-01 00:00:00',
		'decimal'=>0,
		'float'=>0,
		'geography'=>'',
		'geometry'=>'',
		'hierarchyid'=>'',
		'image'=>'',
		'int'=>0,
		'money'=>0,
		'nchar'=>'',
		'ntext'=>'',
		'numeric'=>0,
		'nvarchar'=>'',
		'real'=>0,
		'smalldatetime'=>'1900-01-01 00:00:00',
		'smallint'=>0,
		'smallmoney'=>0,
		'sql_variant'=>'',
		'text'=>'',
		'time'=>'00:00:00',
		'timestamp'=>'DEFAULT',
		'tinyint'=>0,
		'uniqueidentifier'=>'00000000-0000-0000-0000-000000000000',
		'varbinary'=>(binary)'',
		'varchar'=>'',
		'xml'=>''
	);

	//mysql default value array 

	$this->mysqldefval=array(
		'bigint'=>0,
		'binary'=>'',
		'bit'=>0,
		'blob'=>'',
		'bool'=>'',
		'boolean'=>'',
		'char'=>'',
		'date'=>'0000-00-00',
		'datetime'=>'0000-00-00 00:00',
		'decimal'=>0,
		'double'=>0,
		'enum'=>'',
		'float'=>0,
		'int'=>0,
		'longblob'=>'',
		'longtext'=>'',
		'mediumblob'=>'',
		'mediumint'=>0,
		'mediumtext'=>'',
		'numeric'=>0,
		'real'=>0,
		'set'=>'',
		'smallint'=>0,
		'text'=>'',
		'time'=>'00:00:00',
		'timestamp'=>'0000-00-00 00:00',
		'tinyblob'=>'',
		'tinyint'=>0,
		'tinytext'=>'',
		'varbinary'=>'',
		'varchar'=>'',
		'year'=>'0000'
	);


	//for error message

	$this->errmsg=array(
		'insert'=>'Data inserted successfully.',
		'update'=>'Data updated successfully.',
		'delete'=>'Data deleted successfully.',
		'reqired'=>'Please fill in all required fields.',
		'inuse'=>'Data is already in use.',
		'isexist'=>'Data already exist.',
		'emailexist'=>'Email already exist.',
		'mobileexist'=>'Mobile number already exist.',
		'dberror'=>'Something went wrong, Error Code : ',
		'userright'=>"Sorry, You don't have enough permissions to perform this action",
		'size'=>"Sorry, You don't have enough permissions to perform this action",
		'type'=>"Sorry, You don't have enough permissions to perform this action.",
		'loginright'=>"Sorry, You don't have enough permissions to perform this action.",
		'success'=>"Data found",
		'error'=>"Error",
		'nodatafound'=>"No data found",
		'uservalidate'=>"User Validate.",
		'deactivate'=>"Your account is suspended, please contact administrator to activate account.",
		'invalidrequest'=>"Invalid request.",
		'sessiontimeout'=>"Session timeout",
		'dataduplicate'=>"Data Duplicated Succesfully",
		'tokenvalidate'=>"Token validated",
		'invalidtoken'=>"Invalid token.",
		'usernotfound'=>"User not found",
		'invalidusername'=>"Invalid Username or Password.",
		'invalidpassword'=>"Invalid Username or Password..",
		'verifyemail'=>"Please verify your email addess",
		'filetype'=>"Invalid file extension",
		'profile-update'=>"Profile updated successfully",
		'passchanged'=>"Password changed successfully",
		'passreset'=>"Password reseted successfully",
		'correctpass'=>"Please enter correct old password",
		'dbtransactionerror'=>'Something went wrong,Please try again later.',
		'somethingwrong'=>'Something went wrong',
		'loginsuccess'=>"You are successfully login.",
		'logoutsuccess'=>"Logout Successfully",
		'websitefilesize'=>'Please make sure that your file size should not more than 500 kb',
		'appupdate'=>"We have released new version of Alhadaf Shooting Range POS App. Download the update and install to continue use this App.",
		'invalidmember'=>"Invalid Member",
		'invalidcontact'=>"Invalid contact number",
		'couponrequired'=>"Please enter coupon code.",
		'invalidcoupon'=>"Please enter valid coupon code.",
		'couponexpired'=>"This coupon has been expired.",
		'couponnotapplied'=>"This coupon code can't applied.",
		'minispendcoupon'=>"Please spend minimum #minispendamt# amount to apply this coupon",
		'maxspendcoupon'=>"Please spend maximum #maxspendamt# amount to apply this coupon",
		'couponlimitreach'=>"Coupon usage limit reached.",
		'couponapplied'=>"Coupon code applied successfully.",
		'couponremoved'=>"Coupon code removed successfully.",
		'ordersaved'=>"Your invoice has been successfully saved.",
		'orderdberror'=>'Something went wrong,Please try again later.',
		'returnitemsuccess'=>"Item has been returned successfully",
		'incompleteoperation'=>"sorry, your previous operation procedure is incomplete of #storename# so please complete previous store operation first",
		'invalidlogemail'=>"Invalid Email/Mobile Number or Password.",
		'invalidlogemailpass'=>"Invalid Email/Mobile Number or Password.",
		'noorderseries'=>"Sorry, invoice has been not place without series",
		'nobindmembersap'=>"Sorry, Member not bind with SAP",
		'nobinditemsap'=>"Sorry, Invoice item not bind with SAP",
		'noqatarpassportdata'=>"Qatar or Passport data is required",
		'rangeassignsuccess'=>"Range assigned successfully",
		'rangereleasesuccess'=>"Range released successfully",
		'rangereleasealready'=>"Range already released",
		'norangeavlbl'=>"Sorry, No range available",

		'nosoorderseries'=>"Sorry, service order has been not place without series",
		'soordersaved'=>"Your service order has been placed successfully.",
		'invalidsoorder'=>"Invalid Service Order",
		'soordercancel'=>"Service Order cancelled successfully",
		'soorderitemcancel'=>"Service Order item cancelled successfully",
		'soorderalreadycancel'=>"Sorry, service Order already cancelled",
		'soorderitemalreadycancel'=>"Sorry, service Order item already cancelled",
		'orderitemissued'=>"Your item has been issued successfully.",

		'norangeavailable'=>"All ranges are occupied",
		'nolaneavailable'=>"All lanes are occupied",
		'storeopecomplete'=>"Store operation completed successfully",

	);   
}


    
    public function getErrmsg(){
		return $this->errmsg;
	}


    public function getMssqldefval(){
		return $this->mssqldefval;
	}
	
    public function getMysqldefval(){
		return $this->mysqldefval;
	}

    public function getWeburl(){
		return $this->weburl;
	}

	public function setWeburl($weburl){
		$this->weburl = $weburl;
	}

	public function getEndpointverson(){
		return $this->endpointverson;
	}

	public function setEndpointverson($endpointverson){
		$this->endpointverson = $endpointverson;
	}
	
	public function getEndpointurl(){
		return $this->endpointurl;
	}

	public function setEndpointurl($endpointurl){
		$this->endpointurl = $endpointurl;
	}

	public function getDocurl(){
		return $this->docurl;
	}

	public function setDocurl($docurl){
		$this->docurl = $docurl;
	}

	public function getRegdomain(){
		return $this->regdomain;
	}

	public function setRegdomain($regdomain){
		$this->regdomain = $regdomain;
	}

	public function getMailurl(){
		return $this->mailurl;
	}

	public function setMailurl($mailurl){
		$this->mailurl = $mailurl;
	}

	public function getProtocol(){
		return $this->protocol;
	}

	public function setProtocol($protocol){
		$this->protocol = $protocol;
	}

	public function getDocpath(){
		return $this->docpath;
	}

	public function setDocpath($docpath){
		$this->docpath = $docpath;
	}

	public function getCdnurl(){
		return $this->cdnurl;
	}

	public function setCdnurl($cdnurl){
		$this->cdnurl = $cdnurl;
	}

	public function getServermode(){
		return $this->servermode;
	}

	public function setServermode($servermode){
		$this->servermode = $servermode;
	}

	public function getMainDirpath(){
		return $this->maindirpath;
	}

	public function setMainDirpath($maindirpath){
		$this->maindirpath = $maindirpath;
	}

	public function getDirpath(){
		return $this->dirpath;
	}

	public function setDirpath($dirpath){
		$this->dirpath = $dirpath;
	}

	public function getMainDB(){
		return $this->MainDB;
	}

	public function setMainDB($MainDB){
		$this->MainDB = $MainDB;
	}

	public function getDB(){
		return $this->DB;
	}

	public function setDB($DB){
		$this->DB = $DB;
	}

	public function getMainDBUser(){
		return $this->MainDBUser;
	}

	public function setMainDBUser($MainDBUser){
		$this->MainDBUser = $MainDBUser;
	}

	public function getDBUser(){
		return $this->DBUser;
	}

	public function setDBUser($DBUser){
		$this->DBUser = $DBUser;
	}

	public function getMainDBHost(){
		return $this->MainDBHost;
	}

	public function setMainDBHost($MainDBHost){
		$this->MainDBHost = $MainDBHost;
	}

	public function getDBHost(){
		return $this->DBHost;
	}

	public function setDBHost($DBHost){
		$this->DBHost = $DBHost;
	}

	public function getMainDBPass(){
		return $this->MainDBPass;
	}

	public function setMainDBPass($MainDBPass){
		$this->MainDBPass = $MainDBPass;
	}

	public function getDBPass(){
		return $this->DBPass;
	}

	public function setDBPass($DBPass){
		$this->DBPass = $DBPass;
	}

	public function getMainDBPort(){
		return $this->MainDBPort;
	}

	public function setMainDBPort($MainDBPort){
		$this->MainDBPort = $MainDBPort;
	}

	public function getDBPort(){
		return $this->DBPort;
	}

	public function setDBPort($DBPort){
		$this->DBPort = $DBPort;
	}

    public function getDBType(){
		return $this->DBType;
	}

	public function setDBType($DBType){
		$this->DBType = $DBType;
	}
    
	public function getSessionName(){
		return $this->sessionname;
	}

	public function getAdminutype(){
		return $this->adminutype;
	}

	public function getStoreutype(){
		return $this->storeutype;
	}

	public function getMemberutype(){
		return $this->memberutype;
	}

	public function getGuestutype(){
		return $this->guestutype;
	}

	public function getSaveSidebar(){
		return $this->savesidebar;
	}
	
	public function getApplyFilterSidebar(){
		return $this->applyfiltersidebar;
	}

	public function getUpdateSidebar(){
		return $this->updatesidebar;
	}
	
	public function getResetSidebar(){
		return $this->resetsidebar;
	}
	
	public function getCloseSidebar(){
		return $this->closesidebar;
	}
	
	public function getPage404url(){
		return $this->page404url;
	}
	
	public function getImageurl(){
		return $this->imageurl;
	}

	public function getNoDataFound(){
		return $this->nodatafound;
	}

	public function getCmpRegisterId(){
		return $this->cmpregisterid;
	}

	public function getCmpId(){
		return $this->cmpid;
	}

	public function getCmpUnRegisterId(){
		return $this->cmpunregisterid;
	}

	public function getCompositionSchemeId(){
		return $this->cmpcompositionschemeid;
	}

	public function getStateId(){
		return $this->stateid;
	}

	public function getAdminUserId(){
		return $this->adminuid;
	}

	public function getUpdateMyProfile(){
		return $this->updatemyprofile;
	}

	public function getChangePassword(){
		return $this->changepassword;
	}

	public function getDefaultCatMembershipId(){
		return $this->defaultcatmembershipid;
	}

	public function getDefaultCatCourseId(){
		return $this->defaultcatcourseid;
	}

	public function getDefaultCatPackageId(){
		return $this->defaultcatpackageid;
	}

	public function getDefaultCatReturnableId(){
		return $this->defaultcatreturnableid;
	}

	public function getDefaultCatSaleableId(){
		return $this->defaultcatsaleableid;
	}
	
	public function getDefaultCatConsumableId(){
		return $this->defaultcatconsumableid;
	}

	public function getWebsitefilesize(){
		return $this->websitefilesize;
	}

	public function getDefaultPOSImage(){
		return $this->defaultposimage;
	}

	public function getDefaultPOSIcon(){
		return $this->defaultposicon;
	}
	
	public function getDefualtMemberImageurl(){
		return $this->defualtmemberimageurl;
	}

	public function getDefualtCourseImageurl(){
		return $this->defualtcourseimageurl;
	}

	public function getDefualtMemberShipImageurl(){
		return $this->defualtmembershipimageurl;
	}

	public function getDefualtPackageImageurl(){
		return $this->defualtpackageimageurl;
	}



	public function getDefFirstTimeGuestId(){
		return $this->deffirsttimeguestid;
	}

	public function getDefRepeatedGuestId(){
		return $this->defrepeatedguestid;
	}

	public function getDefMemberGuestId(){
		return $this->defmemberguestid;
	}

	public function getInvoiceTermsConditionsId(){
		return $this->invoicetermsconditionsid;
	}

	public function getalhadafpdfurl()
	{
		return $this->alhadafpdfurl;
	}

	public function setalhadafpdfurl($alhadafpdfurl){
		$this->alhadafpdfurl = $alhadafpdfurl;
	}

	public function setWebsiteurl($websiteurl){
		$this->websiteurl = $websiteurl;
	}

	public function getWebsiteurl(){
		return $this->websiteurl;
	}
	
	public function getIsAccessSAP(){
		return $this->isaccessSAP;
	}

	public function getFilePdfImg(){
		return $this->filepdfimg;
	}

}


?>