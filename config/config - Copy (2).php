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

	private $defualtgalleryimageurl;
	private $defualtcourseimageurl;
	private $defualtmembershipimageurl;
	private $defualtpackageimageurl;
	private $defualtmemberimageurl;

	private $defaultcatmembershipid;
	private $defaultcatcourseid;
	private $defaultcatpackageid;

	private $defaultcatsaleableid;
	private $defaultcatconsumableid;
	private $defaultcatreturnableid;

	private $termsconditionsid;
	private $aboutusid;
	private $privacypolicyid;
	private $missionid;
	private $vissionid;
	private $valuesid;

	private $defaultnotiimageurl;


	private $defdurationdailyid;
	private $defdurationweeklyid;
	private $defdurationfortnightid;
	private $defdurationmonthlyid;
	private $defdurationbimonthlyid;
	private $defdurationquarterlyid;
	private $defdurationhalfyearlyid;
	private $defdurationyearlyid;

	private $defdurationweekday;
	private $defdurationfortnightfirstday;
	private $defdurationfortnightsecondday;
	private $defdurationmonthday;

	private $defdurationbimonthfirstday;
	private $defdurationbimonthsecondday;
	private $defdurationbimonththirdday;
	private $defdurationbimonthforthday;
	private $defdurationbimonthfifthday;
	private $defdurationbimonthsixthday;

	private $defdurationquarterfirstday;
	private $defdurationquartersecondday;
	private $defdurationquarterthirdday;
	private $defdurationquarterforthday;

	private $defdurationhalfyearfirstday;
	private $defdurationhalfyearsecondday;
	private $defdurationyearday;

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
		$this->page404url='views/404';

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


		$this->updatemyprofile='Update Profile';
		$this->changepassword='Change Password';
		$this->confirmedstatus='Confirmed';
		$this->cancelledstatus='Cancelled';

		$this->termsconditionsid='5953856C-1D54-45D5-A44E-088C70309FEA';
		$this->aboutusid='0B7FCE31-D38E-4517-937A-0F401129F952';
		$this->privacypolicyid='FC748AEC-89AA-4607-A480-1324B7ED9627';
		$this->missionid='DAEE6839-BCEF-46E5-AFBA-0F105D40DD92';
		$this->vissionid='A3D3DB6F-34AF-4E17-BC7D-D71C8D82A2D9';
		$this->valuesid='2465B7CA-2908-4574-BDC7-2ABE858398C6';


		$this->defdurationdailyid='53FBC6CE-36C2-4898-9500-08DDAB2B59F6';
		$this->defdurationweeklyid='A553A37A-6D4D-4AA4-94A7-1470AEB1369C';
		$this->defdurationfortnightid='45F96656-850C-4A75-9BE3-16789168C156';
		$this->defdurationmonthlyid='1A734CED-6D2C-4A76-AE78-1D597CF12F4B';
		$this->defdurationbimonthlyid='87881C29-2681-41AE-A8CE-226B0B1EB893';
		$this->defdurationquarterlyid='09184F5E-28CD-4300-B279-01C8C177FD0B';
		$this->defdurationhalfyearlyid='BD8FB1FE-3E17-427A-8432-021717868518';
		$this->defdurationyearlyid='175731CD-D470-48B7-8575-0241198640B8';

		$this->defdurationweekday='Monday';
		$this->defdurationfortnightfirstday='01';
		$this->defdurationfortnightsecondday='16';
		$this->defdurationmonthday='01';

		$this->defdurationbimonthfirstday='01/01';
		$this->defdurationbimonthsecondday='01/03';
		$this->defdurationbimonththirdday='01/05';
		$this->defdurationbimonthforthday='01/07';
		$this->defdurationbimonthfifthday='01/09';
		$this->defdurationbimonthsixthday='01/11';

		$this->defdurationquarterfirstday='01/01';
		$this->defdurationquartersecondday='01/04';
		$this->defdurationquarterthirdday='01/07';
		$this->defdurationquarterforthday='01/10';

		$this->defdurationhalfyearfirstday='01/01';
		$this->defdurationhalfyearsecondday='01/07';
		$this->defdurationyearday='01/01';

		$this->invoicetermsconditionsid='6D862ADC-5501-4AA2-A21A-217D15C5012E';

		$this->filepdfimg='images/filepdf.png';

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
        $this->dirpath=$this->maindirpath.'';  /* Directory Name also look in index.php script tag*/
        $this->host=$_SERVER['HTTP_HOST'].$this->dirpath;
        $this->weburl = $this->protocol.$this->host;
		$this->websiteurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath;
        $this->docpath = $this->docroot;
        $this->cdnurl =  $this->protocol.$this->host;
        $this->regdomain = '192.168.1.2/';
        $this->mailurl = "192.168.1.2/alhadaf_dev/";
		$this->endpointverson = 'v1.0/';
		$this->endpointurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->dirpath.'api/'.$this->endpointverson;
		$this->imageurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/';
		$this->page404url=$this->protocol.$this->host.$this->page404url;
		$this->nodatafound=$this->protocol.$this->host.'views/nodatafound.php';
		
		$this->defualtgalleryimageurl=$this->imageurl.'images/defualtgallery.jpg';
		$this->defualtcourseimageurl=$this->imageurl.'images/defualtcourse.jpg';
		$this->defualtmembershipimageurl=$this->imageurl.'images/defualtmembership.jpg';
		$this->defualtpackageimageurl=$this->imageurl.'images/defualtpackage.jpg';
		$this->defualtmemberimageurl=$this->imageurl.'images/defualtmember.png';

		$this->defaultnotiimageurl=$this->imageurl.'images/defaultnotification.png';

		$this->alhadafpdfurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/plugin/alhadafpdf/';

    }
    else if ($this->servermode == "dev") // Development 
    {
		$this->maindirpath='/alhadaf_dev/';
        $this->dirpath=$this->maindirpath.'';  /* Directory Name also look in index.php script tag*/
        $this->host=$_SERVER['HTTP_HOST'].$this->dirpath;
        $this->weburl = $this->protocol.$this->host;
		$this->websiteurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath;
        $this->docpath = $this->docroot;
        $this->cdnurl =  $this->protocol.$this->host;
        $this->regdomain = 'localhost/';
        $this->mailurl = "localhost/alhadaf_dev/";
		$this->endpointverson = 'v1.0/';
		$this->endpointurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->dirpath.'api/'.$this->endpointverson;
		$this->imageurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/';
		$this->page404url=$this->protocol.$this->host.$this->page404url;
		$this->nodatafound=$this->protocol.$this->host.'views/nodatafound.php';
		
		$this->defualtgalleryimageurl=$this->imageurl.'images/defualtgallery.jpg';
		$this->defualtcourseimageurl=$this->imageurl.'images/defualtcourse.jpg';
		$this->defualtmembershipimageurl=$this->imageurl.'images/defualtmembership.jpg';
		$this->defualtpackageimageurl=$this->imageurl.'images/defualtpackage.jpg';
		$this->defualtmemberimageurl=$this->imageurl.'images/defualtmember.png';

		$this->defaultnotiimageurl=$this->imageurl.'images/defaultnotification.png';

		$this->alhadafpdfurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/plugin/alhadafpdf/';

    }
    else if($this->servermode=="prod") // live 
    {
		$this->maindirpath='/';
        $this->dirpath=$this->maindirpath.'';  /* Directory Name also look in index.php script tag*/
        $this->host=$_SERVER['HTTP_HOST'].$this->dirpath;
        $this->weburl = $this->protocol.$this->host;
		$this->websiteurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath;
        $this->docpath = $this->docroot;
        $this->cdnurl =  $this->protocol.$this->host;
        $this->regdomain = 'alhadafrange.com/';
        $this->mailurl = "alhadafrange.com/admin/";
		$this->endpointverson = 'v1.0/';
		$this->endpointurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->dirpath.'api/'.$this->endpointverson;
		$this->imageurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/';
		$this->page404url=$this->protocol.$this->host.$this->page404url;
		$this->nodatafound=$this->protocol.$this->host.'views/nodatafound.php';

		$this->defualtgalleryimageurl=$this->imageurl.'images/defualtgallery.jpg';
		$this->defualtcourseimageurl=$this->imageurl.'images/defualtcourse.jpg';
		$this->defualtmembershipimageurl=$this->imageurl.'images/defualtmembership.jpg';
		$this->defualtpackageimageurl=$this->imageurl.'images/defualtpackage.jpg';
		$this->defualtmemberimageurl=$this->imageurl.'images/defualtmember.png';

		$this->defaultnotiimageurl=$this->imageurl.'images/defaultnotification.png';

		$this->alhadafpdfurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/plugin/alhadafpdf/';
		
    }
    else if($this->servermode=="uat") // test 
    { 
		$this->maindirpath='/alhadaf_dev/';
        $this->dirpath=$this->maindirpath.'admin/';  /* Directory Name also look in index.php script tag*/
        $this->host=$_SERVER['HTTP_HOST'].$this->dirpath;
        $this->weburl = $this->protocol.$this->host;
		$this->websiteurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath;
        $this->docpath = $this->docroot;
        $this->cdnurl =  $this->protocol.$this->host;
        $this->regdomain = '192.168.1.27';
        $this->mailurl = "192.168.1.27/alhadaf_dev/admin/";
		$this->endpointverson = 'v1.0/';
		$this->endpointurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->dirpath.'api/'.$this->endpointverson;
		$this->imageurl = $this->protocol.$_SERVER['HTTP_HOST'].$this->maindirpath.'assets/';
		$this->page404url=$this->protocol.$this->host.$this->page404url;
		$this->nodatafound=$this->protocol.$this->host.'views/nodatafound.php';
		
		$this->defualtgalleryimageurl=$this->imageurl.'images/defualtgallery.jpg';
		$this->defualtcourseimageurl=$this->imageurl.'images/defualtcourse.jpg';
		$this->defualtmembershipimageurl=$this->imageurl.'images/defualtmembership.jpg';
		$this->defualtpackageimageurl=$this->imageurl.'images/defualtpackage.jpg';
		$this->defualtmemberimageurl=$this->imageurl.'images/defualtmember.png';

		$this->defaultnotiimageurl=$this->imageurl.'images/defaultnotification.png';

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
		//'registersuccess'=>"You are successfully registered.",
		'registersuccess'=>"You are successfully registered. your account is under verification. ",
		'noregverified'=>"Sorry, your account is under verification.",
		'loginsuccess'=>"You are successfully login.",
		'logoutsuccess'=>"Logout Successfully",
		'emailnotfound'=>"Email address not found",
		'emailnotregister'=>"This email address is not registered.",
		'appupdate'=>"We have released new version of Alhadaf Shooting Range App. Download the update and install to continue use this App.",
		'nomshipfound'=>"Membership Not Found!",
		'nopackagefound'=>"Packages Not Found!",
		'nocoursefound'=>"Courses Not Found!",
		'contactussuccess'=>"Your message has been successfully sent, We will contact you soon.",
		'rangebooksuccess'=>"Your booking data has been successfully inserted.",
		'slotbooksuccess'=>"Your slot has been booked.",
		'slotbookalready'=>"Your slot has been already booked for this time slot.",
		'nofaqfound'=>"FAQ Not Found!",
		'notncfound'=>"Terms and Condition Not Found!",
		'nopolicyfound'=>"Privacy Policy Not Found!",
		'invalidlogemail'=>"Invalid Email/Mobile Number or Password.",
		'invalidlogemailpass'=>"Invalid Email/Mobile Number or Password.",
		'itemcartsession'=>"#item# successfully added in cart",
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
		'itemcartnotfound'=>"Your cart is empty",
		'profile-doc-upload'=>"Document uploaded successfully",
		'verifycodesent'=>"Verification code sent to #email#",
		'invalidverifycode'=>"Invalid Verification Code",
		//'emailsuccess'=>"Email data inserted successfully.",
		'emailsuccess'=>"Request sent successfully.",
		'noorderseries'=>"Sorry, invoice has been not place without series",

		
		'nobindmembersap'=>"Sorry, Member not bind with SAP",
		'nobinditemsap'=>"Sorry, Invoice item not bind with SAP",
		'noqatarpassportdata'=>"Qatar or Passport data is required",
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

	public function getDefaultCatSaleableId(){
		return $this->defaultcatsaleableid;
	}

	public function getDefaultCatConsumableId(){
		return $this->defaultcatconsumableid;
	}

	public function getDefaultCatReturnableId(){
		return $this->defaultcatreturnableid;
	}

	public function getDefualtGalleryImageurl(){
		return $this->defualtgalleryimageurl;
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

	public function getDefualtMemberImageurl(){
		return $this->defualtmemberimageurl;
	}

	public function getTermsConditionId(){
		return $this->termsconditionsid;
	}
	public function getAboutUsId(){
		return $this->aboutusid;
	}
	public function getMissionId(){
		return $this->missionid;
	}
	public function getVissionId(){
		return $this->vissionid;
	}
	public function getValuesId(){
		return $this->valuesid;
	}
	public function getPrivacyPolicyId(){
		return $this->privacypolicyid;
	}

	public function getDefaultNotiImageurl(){
		return $this->defaultnotiimageurl;
	}




	public function getDefDurationDailyId(){
		return $this->defdurationdailyid;
	}

	public function getDefDurationWeeklyId(){
		return $this->defdurationweeklyid;
	}

	public function getDefDurationFortNightId(){
		return $this->defdurationfortnightid;
	}

	public function getDefDurationMonthlyId(){
		return $this->defdurationmonthlyid;
	}

	public function getDefDurationBiMonthlyId(){
		return $this->defdurationbimonthlyid;
	}

	public function getDefDurationQuarterlyId(){
		return $this->defdurationquarterlyid;
	}

	public function getDefDurationHalfYearlyId(){
		return $this->defdurationhalfyearlyid;
	}

	public function getDefDurationYearlyId(){
		return $this->defdurationyearlyid;
	}


	public function getDefDurationWeekDay(){
		return $this->defdurationweekday;
	}

	public function getDefDurationFortNightFirstDay(){
		return $this->defdurationfortnightfirstday;
	}

	public function getDefDurationFortNightSecondDay(){
		return $this->defdurationfortnightsecondday;
	}

	public function getDefDurationMonthDay(){
		return $this->defdurationmonthday;
	}


	

	public function getDefdurationbimonthfirstday(){
		return $this->defdurationbimonthfirstday;
	}

	public function setDefdurationbimonthfirstday($defdurationbimonthfirstday){
		$this->defdurationbimonthfirstday = $defdurationbimonthfirstday;
	}

	public function getDefdurationbimonthsecondday(){
		return $this->defdurationbimonthsecondday;
	}

	public function setDefdurationbimonthsecondday($defdurationbimonthsecondday){
		$this->defdurationbimonthsecondday = $defdurationbimonthsecondday;
	}

	public function getDefdurationbimonththirdday(){
		return $this->defdurationbimonththirdday;
	}

	public function setDefdurationbimonththirdday($defdurationbimonththirdday){
		$this->defdurationbimonththirdday = $defdurationbimonththirdday;
	}

	public function getDefdurationbimonthforthday(){
		return $this->defdurationbimonthforthday;
	}

	public function setDefdurationbimonthforthday($defdurationbimonthforthday){
		$this->defdurationbimonthforthday = $defdurationbimonthforthday;
	}

	public function getDefdurationbimonthfifthday(){
		return $this->defdurationbimonthfifthday;
	}

	public function setDefdurationbimonthfifthday($defdurationbimonthfifthday){
		$this->defdurationbimonthfifthday = $defdurationbimonthfifthday;
	}

	public function getDefdurationbimonthsixthday(){
		return $this->defdurationbimonthsixthday;
	}

	public function setDefdurationbimonthsixthday($defdurationbimonthsixthday){
		$this->defdurationbimonthsixthday = $defdurationbimonthsixthday;
	}

	public function getDefdurationquarterfirstday(){
		return $this->defdurationquarterfirstday;
	}

	public function setDefdurationquarterfirstday($defdurationquarterfirstday){
		$this->defdurationquarterfirstday = $defdurationquarterfirstday;
	}

	public function getDefdurationquartersecondday(){
		return $this->defdurationquartersecondday;
	}

	public function setDefdurationquartersecondday($defdurationquartersecondday){
		$this->defdurationquartersecondday = $defdurationquartersecondday;
	}

	public function getDefdurationquarterthirdday(){
		return $this->defdurationquarterthirdday;
	}

	public function setDefdurationquarterthirdday($defdurationquarterthirdday){
		$this->defdurationquarterthirdday = $defdurationquarterthirdday;
	}

	public function getDefdurationquarterforthday(){
		return $this->defdurationquarterforthday;
	}

	public function setDefdurationquarterforthday($defdurationquarterforthday){
		$this->defdurationquarterforthday = $defdurationquarterforthday;
	}

	public function getDefdurationhalfyearfirstday(){
		return $this->defdurationhalfyearfirstday;
	}

	public function setDefdurationhalfyearfirstday($defdurationhalfyearfirstday){
		$this->defdurationhalfyearfirstday = $defdurationhalfyearfirstday;
	}

	public function getDefdurationhalfyearsecondday(){
		return $this->defdurationhalfyearsecondday;
	}

	public function setDefdurationhalfyearsecondday($defdurationhalfyearsecondday){
		$this->defdurationhalfyearsecondday = $defdurationhalfyearsecondday;
	}

	public function getDefdurationyearday(){
		return $this->defdurationyearday;
	}

	public function setDefdurationyearday($defdurationyearday){
		$this->defdurationyearday = $defdurationyearday;
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