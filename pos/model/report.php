<?php 
class listreportcategory{

    public $id;
    public $name;

}

class listreportsubcategory{

    public $id;
    public $name;

}

class listreportitem{

    public $id;
    public $name;

}

class listreportmember{

    public $id;
    public $name;

}

class listsalereportdata{

    public $id;
    public $transactionid;
    public $orderno;
    public $membername;
    public $membercontact;
    public $ofulldate;
    public $totalamount;
    public $entrypersonname;
    public $entrypersoncontact;
    public $ordstatusname;
    public $paymenttypename;
    public $timestamp;


    public $isitemdetail;
    public $itemdetail = array();

    public function getId(){
      return $this->id;
    }

    public function setId($id){
      $this->id = $id;
    }

    public function getIsItemDetail(){
        return $this->isitemdetail;
    }

    public function setIsItemDetail($isitemdetail){
        $this->isitemdetail = $isitemdetail;
    }

    public function getItemDetail(){
      return $this->itemdetail;
    }

    public function setItemDetail($itemdetail){
      $this->itemdetail = $itemdetail;
    }

}


class itemdetail{

    public $id;
    public $type;
    public $itemname;
    public $durationname;
    public $price;
    public $taxable;
    public $igsttaxamt;
    public $finalprice;
    public $igst;
    public $couponamount;
    public $expirydate;
    public $n_expirydate;
    public $strvalidityduration;
    public $typename;


    public function getId(){
      return $this->id;
    }

    public function setId($id){
      $this->id = $id;
    }


    public $isitemfulldetail;
    public $itemfulldetail = array();

    public function getIsItemFullDetail(){
        return $this->isitemfulldetail;
    }

    public function setIsItemFullDetail($isitemfulldetail){
        $this->isitemfulldetail = $isitemfulldetail;
    }

    public function getItemFullDetail(){
      return $this->itemfulldetail;
    }

    public function setItemFullDetail($itemfulldetail){
      $this->itemfulldetail = $itemfulldetail;
    }



    public $isitemwebsitedetail;
    public $itemwebsitedetail = array();
    
    public function getIsItemWebsiteDetail(){
        return $this->isitemwebsitedetail;
    }

    public function setIsItemWebsiteDetail($isitemwebsitedetail){
        $this->isitemwebsitedetail = $isitemwebsitedetail;
    }

    public function getItemWebsiteDetail(){
      return $this->itemwebsitedetail;
    }

    public function setItemWebsiteDetail($itemwebsitedetail){
      $this->itemwebsitedetail = $itemwebsitedetail;
    }


    public $iscoursebenefitdetail;
    public $coursebenefitdetail = array();
    
    public function getIsCourseBenefitDetail(){
        return $this->iscoursebenefitdetail;
    }

    public function setIsCourseBenefitDetail($iscoursebenefitdetail){
        $this->iscoursebenefitdetail = $iscoursebenefitdetail;
    }

    public function getCourseBenefitDetail(){
      return $this->coursebenefitdetail;
    }

    public function setCourseBenefitDetail($coursebenefitdetail){
      $this->coursebenefitdetail = $coursebenefitdetail;
    }
} 


class itemfulldetail{

    public $id;
    public $catid;
    public $category;
    public $subcatid;
    public $subcategory;
    public $itemid;
    public $itemname;
    public $qty;
    public $usedqty;
    public $remainqty;
    public $durationid;
    public $durationname;
    public $durationdays;
    public $discount;
    public $price;
    public $taxtypename;
    public $taxtype;
    public $sgst;
    public $cgst;
    public $igst;
    public $type;
    public $typename;

}

class itemwebsitedetail{

    public $id;
    public $name;
    public $attributename;
    public $displayorder;

}

class coursebenefitdetail{

    public $id;
    public $name;
    public $durationname;
    public $displayorder;

}


class listreportsaleperson{

  public $id;
  public $name;

}

class liststore{

  public $id;
  public $name;

}

class listreportitems{

  public $id;
  public $name;

}

class listitemsalesummaryreportdata{

  public $id;
  public $itemname;
  public $sale_qty;
  public $return_qty;
  public $sale_amount;

}


class listmshipsalesummaryreportdata{

  public $id;
  public $itemname;
  public $sale_qty;
  public $sale_amount;

}

?>