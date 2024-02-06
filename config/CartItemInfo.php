<?php 


class CartItemInfo{
    
	public function __construct()
	{
		
	}

	public $id;
	public $itemname;
	public $price;
	public $taxtype;
	public $taxtypename;
	public $sgst;
	public $cgst;
	public $igst;
	public $taxable;
	public $igsttaxamt;
	public $sgsttaxamt;
	public $cgsttaxamt;
	public $finalprice;
	public $duration;
	public $durationname;
	public $strvalidityduration;
	public $type;
	public $image;
	public $iconimg;
	public $couponamount;
	

	
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getItemname(){
		return $this->itemname;
	}
	
	public function setItemname($itemname){
		$this->itemname = $itemname;
	}
	
	public function getPrice(){
		return $this->price;
	}
	
	public function setPrice($price){
		$this->price = $price;
	}
	
	public function getTaxtype(){
		return $this->taxtype;
	}
	
	public function setTaxtype($taxtype){
		$this->taxtype = $taxtype;
	}
	
	public function getTaxtypename(){
		return $this->taxtypename;
	}
	
	public function setTaxtypename($taxtypename){
		$this->taxtypename = $taxtypename;
	}
	
	public function getSgst(){
		return $this->sgst;
	}
	
	public function setSgst($sgst){
		$this->sgst = $sgst;
	}
	
	public function getCgst(){
		return $this->cgst;
	}
	
	public function setCgst($cgst){
		$this->cgst = $cgst;
	}
	
	public function getIgst(){
		return $this->igst;
	}
	
	public function setIgst($igst){
		$this->igst = $igst;
	}

	public function getTaxable(){
		return $this->taxable;
	}

	public function setTaxable($taxable){
		$this->taxable = $taxable;
	}

	public function getIgstTaxAmt(){
		return $this->igsttaxamt;
	}

	public function setIgstTaxAmt($igsttaxamt){
		$this->igsttaxamt = $igsttaxamt;
	}

	public function getSgstTaxAmt(){
		return $this->sgsttaxamt;
	}

	public function setSgstTaxAmt($sgsttaxamt){
		$this->sgsttaxamt = $sgsttaxamt;
	}

	public function getCgstTaxAmt(){
		return $this->cgsttaxamt;
	}

	public function setCgstTaxAmt($cgsttaxamt){
		$this->cgsttaxamt = $cgsttaxamt;
	}

	public function getFinalprice(){
		return $this->finalprice;
	}

	public function setFinalprice($finalprice){
		$this->finalprice = $finalprice;
	}
	
	public function getDuration(){
		return $this->duration;
	}
	
	public function setDuration($duration){
		$this->duration = $duration;
	}
	
	public function getDurationname(){
		return $this->durationname;
	}
	
	public function setDurationname($durationname){
		$this->durationname = $durationname;
	}

	public function getStrValidityDuration(){
		return $this->strvalidityduration;
	}
	
	public function setStrValidityDuration($strvalidityduration){
		$this->strvalidityduration = $strvalidityduration;
	}
	
	public function getType(){
		return $this->type;
	}
	
	public function setType($type){
		$this->type = $type;
	}

	public function getImage(){
		return $this->image;
	}
	
	public function setImage($image){
		$this->image = $image;
	}

	public function getIconImg(){
		return $this->iconimg;
	}
	
	public function setIconImg($iconimg){
		$this->iconimg = $iconimg;
	}

	public function getcouponamount()
	{
		return $this->couponamount;
	}

	public function setcouponamount($couponamount)
	{
		$this->couponamount = $couponamount;
	}

}



?>