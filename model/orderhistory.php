<?php 

class listorderhistory{

    public $id;
    public $transactionid;
    public $orderno;
    public $uid;
    public $totalamount;
    public $couponapply;
    public $couponid;
    public $couponcode;
    public $couponamount;
    public $totaltaxableamt;
    public $totaltax;
    public $totalpaid;
    public $iscancel;
    public $strcancel;
    public $ofulldate;
    public $timestamp;

    public $orderdetailinfo = array();

    public function getId(){
      return $this->id;
    }

    public function setId($id){
      $this->id = $id;
    }

    public function getOrderDetailInfo(){
      return $this->orderdetailinfo;
    }

    public function setOrderDetailInfo($orderdetailinfo){
      $this->orderdetailinfo = $orderdetailinfo;
    }

}


class orderdetailinfo{

    public $id;
    public $orderid;
    public $type;
    public $typename;
    public $itemid;
    public $itemname;
    public $durationday;
    public $durationname;
    public $strvalidityduration;
    public $description;
    public $courseduration;
    public $noofstudent;
    public $startdate;
    public $expirydate;
    public $n_expirydate;
    public $taxtype;
    public $taxtypename;
    public $sgst;
    public $cgst;
    public $igst;
    public $price;
    public $couponamount;
    public $taxable;
    public $sgsttaxamt;
    public $cgsttaxamt;
    public $igsttaxamt;
    public $finalprice;
    public $strexpire;
    public $strexpirecolor;
  
  }


  
class attributedetail{

  public $id;
  public $name;
  public $attributename;
  public $iconimg;

}

class coursebenefit{

  public $id;
  public $name;
  public $durationname;
  public $iconimg;

}



class listorderdetail{

 
  public $id;
  public $orderid;
  public $orderno;
  public $ofulldate;
  public $type;
  public $typename;
  public $itemid;
  public $itemname;
  public $durationday;
  public $durationname;
  public $strvalidityduration;
  public $description;
  public $courseduration;
  public $noofstudent;
  public $startdate;
  public $expirydate;
  public $n_expirydate;
  public $taxtype;
  public $taxtypename;
  public $sgst;
  public $cgst;
  public $igst;
  public $price;
  public $couponamount;
  public $taxable;
  public $sgsttaxamt;
  public $cgsttaxamt;
  public $igsttaxamt;
  public $finalprice;
  public $strexpire;
  public $strexpirecolor;
  public $strexpirestatus;

}


class orderitemdetailinfo{

  public $oidid;
  public $odid;
  public $orderid;
  public $categoryid;
  public $category;
  public $subcategoryid;
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
  public $timestamp;

}

?>