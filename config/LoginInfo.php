<?php 


class LoginInfo{
    
	public function __construct()
	{
	
	}

	public $iss;
	public $key;
	public $username;
	public $uid;
	public $utypeid;
	public $contact;
	public $email;
	public $cmpid;
	public $branchid;
	public $fullname;
	public $adlogin;
	public $unqkey;
	public $yearid;
	public $yearname;
	public $activeyearid;
	public $userrights=array();
	public $profilepic;
	public $isguestuser;
	public $ismemberuser;

	public $couponapply;
	public $couponid;
	public $couponcode;
	public $coupontype;
	public $couponamount;
	public $couponpercent;

	public $cartiteminfo=array();
	
	public function getIss(){
		return $this->iss;
	}

	public function setIss($iss){
		$this->iss = $iss;
	}

	public function getKey(){
		return $this->key;
	}

	public function setKey($key){
		$this->key = $key;
	}

	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;
	}
	
	public function getUid(){
		return $this->uid;
	}

	public function setUid($uid){
		$this->uid = $uid;
	}

	public function getUtypeid(){
		return $this->utypeid;
	}

	public function setUtypeid($utypeid){
		$this->utypeid = $utypeid;
	}

	public function getContact(){
		return $this->contact;
	}

	public function setContact($contact){
		$this->contact = $contact;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getCmpid(){
		return $this->cmpid;
	}

	public function setCmpid($cmpid){
		$this->cmpid = $cmpid;
	}	
	
	public function getBranchid(){
		return $this->branchid;
	}

	public function setBranchid($branchid){
		$this->branchid = $branchid;
	}

	public function getFullname(){
		return $this->fullname;
	}

	public function setFullname($fullname){
		$this->fullname = $fullname;
	}

	public function getAdlogin(){
		return $this->adlogin;
	}

	public function setAdlogin($adlogin){
		$this->adlogin = $adlogin;
	}

	public function getUnqkey(){
		return $this->unqkey;
	}

	public function setUnqkey($unqkey){
		$this->unqkey = $unqkey;
	}

	public function getYearid(){
		return $this->yearid;
	}

	public function setYearid($yearid){
		$this->yearid = $yearid;
	}

	public function getYearname(){
		return $this->yearname;
	}

	public function setYearname($yearname){
		$this->yearname = $yearname;
	}

	public function getActiveyearid(){
		return $this->activeyearid;
	}

	public function setActiveyearid($activeyearid){
		$this->activeyearid = $activeyearid;
	}

	public function getUserrights(){
		return $this->userrights;
	}

	public function setUserrights($userrights){
		$this->userrights = $userrights;
	}

	public function getProfilepic(){
		return $this->profilepic;
	}

	public function setProfilepic($profilepic){
		$this->profilepic = $profilepic;
	}

	public function setIsguestuser($isguestuser) { 
		$this->isguestuser = $isguestuser; 
	}

	public function getIsguestuser() { 
		return $this->isguestuser; 
	}

	public function setIsmemberuser($ismemberuser) { 
		$this->ismemberuser = $ismemberuser; 
	}

	public function getIsmemberuser() { 
		return $this->ismemberuser; 
	}


	public function getCartItemInfo(){
		return $this->cartiteminfo;
	}

	public function setCartItemInfo($cartiteminfo){
		$this->cartiteminfo = $cartiteminfo;
	}


	public function getcouponapply()
	{
		return $this->couponapply;
	}

	public function setcouponapply($couponapply)
	{
		$this->couponapply = $couponapply;
	}

	public function getcouponid()
	{
		return $this->couponid;
	}

	public function setcouponid($couponid)
	{
		$this->couponid = $couponid;
	}

	public function getcouponcode()
	{
		return $this->couponcode;
	}

	public function setcouponcode($couponcode)
	{
		$this->couponcode = $couponcode;
	}

	public function getcoupontype()
	{
		return $this->coupontype;
	}

	public function setcoupontype($coupontype)
	{
		$this->coupontype = $coupontype;
	}
	
	public function getcouponamount()
	{
		return $this->couponamount;
	}

	public function setcouponamount($couponamount)
	{
		$this->couponamount = $couponamount;
	}

	public function getcouponpercent()
	{
		return $this->couponpercent;
	}
	
	public function setcouponpercent($couponpercent)
	{
		$this->couponpercent = $couponpercent;
	}

	public function getdashUserrights(){
		return $this->dashuserrights;
	}

	public function setdashUserrights($dashuserrights){
		$this->dashuserrights = $dashuserrights;
	}
	
}


?>