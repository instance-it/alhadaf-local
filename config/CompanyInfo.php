<?php 


class CompanyInfo{
    
	public function __construct()
	{
	
	}

	private $companyname;
	private $shortname;
	private $prefix;
	private $address;
	private $gmaplink;
	private $contact1;
	private $iswhatsappnumcontact1;
	private $contact2;
	private $iswhatsappnumcontact2;
	private $contact3;
	private $iswhatsappnumcontact3;
	private $contact4;
	private $iswhatsappnumcontact4;
	private $email1;
	private $email2;
	private $inquiryfromtime;
	private $inquirytotime;
	private $logoimg;
	private $stampimg;
	private $signimg;
	private $iconimg;

	

	public $cmprangehour=array();


	
	public function getCompanyname(){
		return $this->companyname;
	}

	public function setCompanyname($companyname){
		$this->companyname = $companyname;
	}

	public function getShortname(){
		return $this->shortname;
	}

	public function setShortname($shortname){
		$this->shortname = $shortname;
	}

	public function getPrefix(){
		return $this->prefix;
	}

	public function setPrefix($prefix){
		$this->prefix = $prefix;
	}

	public function getAddress(){
		return $this->address;
	}

	public function setAddress($address){
		$this->address = $address;
	}

	public function getGMapLink(){
		return $this->gmaplink;
	}

	public function setGMapLink($gmaplink){
		$this->gmaplink = $gmaplink;
	}

	public function getContact1(){
		return $this->contact1;
	}

	public function setContact1($contact1){
		$this->contact1 = $contact1;
	}

	public function getIswhatsappnumcontact1(){
		return $this->iswhatsappnumcontact1;
	}

	public function setIswhatsappnumcontact1($iswhatsappnumcontact1){
		$this->iswhatsappnumcontact1 = $iswhatsappnumcontact1;
	}

	public function getContact2(){
		return $this->contact2;
	}

	public function setContact2($contact2){
		$this->contact2 = $contact2;
	}

	public function getIswhatsappnumcontact2(){
		return $this->iswhatsappnumcontact2;
	}

	public function setIswhatsappnumcontact2($iswhatsappnumcontact2){
		$this->iswhatsappnumcontact2 = $iswhatsappnumcontact2;
	}

	public function getContact3(){
		return $this->contact3;
	}

	public function setContact3($contact3){
		$this->contact3 = $contact3;
	}

	public function getIswhatsappnumcontact3(){
		return $this->iswhatsappnumcontact3;
	}

	public function setIswhatsappnumcontact3($iswhatsappnumcontact3){
		$this->iswhatsappnumcontact3 = $iswhatsappnumcontact3;
	}

	public function getContact4(){
		return $this->contact4;
	}

	public function setContact4($contact4){
		$this->contact4 = $contact4;
	}

	public function getIswhatsappnumcontact4(){
		return $this->iswhatsappnumcontact4;
	}

	public function setIswhatsappnumcontact4($iswhatsappnumcontact4){
		$this->iswhatsappnumcontact4 = $iswhatsappnumcontact4;
	}

	public function getEmail1(){
		return $this->email1;
	}

	public function setEmail1($email1){
		$this->email1 = $email1;
	}

	public function getEmail2(){
		return $this->email2;
	}

	public function setEmail2($email2){
		$this->email2 = $email2;
	}

	public function getInquiryfromtime(){
		return $this->inquiryfromtime;
	}

	public function setInquiryfromtime($inquiryfromtime){
		$this->inquiryfromtime = $inquiryfromtime;
	}

	public function getInquirytotime(){
		return $this->inquirytotime;
	}

	public function setInquirytotime($inquirytotime){
		$this->inquirytotime = $inquirytotime;
	}

	public function getLogoImg(){
		return $this->logoimg;
	}

	public function setLogoImg($logoimg){
		$this->logoimg = $logoimg;
	}

	public function getStampImg(){
		return $this->stampimg;
	}

	public function setStampImg($stampimg){
		$this->stampimg = $stampimg;
	}

	public function getSignImg(){
		return $this->signimg;
	}

	public function setSignImg($signimg){
		$this->signimg = $signimg;
	}

	public function getIconImg(){
		return $this->iconimg;
	}

	public function setIconImg($iconimg){
		$this->iconimg = $iconimg;
	}


	public function getCmpRangeHour(){
		return $this->cmprangehour;
	}

	public function setCmpRangeHour($cmprangehour){
		$this->cmprangehour = $cmprangehour;
	}

}



class CmpRangeHourInfo{
    
	public function __construct()
	{
		
	}
	public $id;
	public $cmpid;
	public $name;
	public $displayorder;

	
	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getCmpid(){
		return $this->cmpid;
	}

	public function setCmpid($cmpid){
		$this->cmpid = $cmpid;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getDisplayOrder(){
		return $this->displayorder;
	}

	public function setDisplayOrder($displayorder){
		$this->displayorder = $displayorder;
	}

}


?>