<?php 

class Userrights {
    
	public function __construct()
	{
		
	}

	private $formname;
	private $formnametext;
	private $viewright;
	private $allviewright;
	private $selfviewright;
	private $addright;
	private $alladdright;
	private $selfaddright;
	private $editright;
	private $alleditright;
	private $selfeditright;
	private $delright;
	private $alldelright;
	private $selfdelright;
	private $printright;
	private $allprintright;
	private $selfprintright;
	private $requestright;
	private $changepriceright;

	public function getFormname(){
		return $this->formname;
	}

	public function setFormname($formname){
		$this->formname = $formname;
	}

	public function getFormnametext(){
		return $this->formnametext;
	}

	public function setFormnametext($formnametext){
		$this->formnametext = $formnametext;
	}
	
	public function getViewright(){
		return $this->viewright;
	}

	public function setViewright($viewright){
		$this->viewright = $viewright;
	}

	public function getAllviewright(){
		return $this->allviewright;
	}

	public function setAllviewright($allviewright){
		$this->allviewright = $allviewright;
	}

	public function getSelfviewright(){
		return $this->selfviewright;
	}

	public function setSelfviewright($selfviewright){
		$this->selfviewright = $selfviewright;
	}

	public function getAddright(){
		return $this->addright;
	}

	public function setAddright($addright){
		$this->addright = $addright;
	}

	public function getAlladdright(){
		return $this->alladdright;
	}

	public function setAlladdright($alladdright){
		$this->alladdright = $alladdright;
	}

	public function getSelfaddright(){
		return $this->selfaddright;
	}

	public function setSelfaddright($selfaddright){
		$this->selfaddright = $selfaddright;
	}

	public function getEditright(){
		return $this->editright;
	}

	public function setEditright($editright){
		$this->editright = $editright;
	}

	public function getAlleditright(){
		return $this->alleditright;
	}

	public function setAlleditright($alleditright){
		$this->alleditright = $alleditright;
	}

	public function getSelfeditright(){
		return $this->selfeditright;
	}

	public function setSelfeditright($selfeditright){
		$this->selfeditright = $selfeditright;
	}

	public function getDelright(){
		return $this->delright;
	}

	public function setDelright($delright){
		$this->delright = $delright;
	}

	public function getAlldelright(){
		return $this->alldelright;
	}

	public function setAlldelright($alldelright){
		$this->alldelright = $alldelright;
	}

	public function getSelfdelright(){
		return $this->selfdelright;
	}

	public function setSelfdelright($selfdelright){
		$this->selfdelright = $selfdelright;
	}

	public function getPrintright(){
		return $this->printright;
	}

	public function setPrintright($printright){
		$this->printright = $printright;
	}

	public function getAllprintright(){
		return $this->allprintright;
	}

	public function setAllprintright($allprintright){
		$this->allprintright = $allprintright;
	}

	public function getSelfprintright(){
		return $this->selfprintright;
	}

	public function setSelfprintright($selfprintright){
		$this->selfprintright = $selfprintright;
	}

	public function getRequestright(){
		return $this->requestright;
	}

	public function setRequestright($requestright){
		$this->requestright = $requestright;
	}

	public function getChangepriceright(){
		return $this->changepriceright;
	}

	public function setChangepriceright($changepriceright){
		$this->changepriceright = $changepriceright;
	}
	
}

?>