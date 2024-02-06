<?php 
class listpaymenttype{

    public $id;
    public $name;
    public $image;
    public $displayorder;

}

class listnoteamount{

    public $id;
    public $name;

}

class liststoreorderhistory{

    public $id;
    public $transactionid;
    public $orderno;
    public $storeid;
    public $uid;
    public $membername;
    public $membercontact;
    public $orderdate;
    public $totalamount;
    public $totaltaxableamt;
    public $totaltax;
    public $totalpaid;
    public $totalpayableamt;
    public $totalpaidamount;
    public $totalchangeamount;
    public $ordernotes;
    public $ofulldate;
    public $entrypersonname;
    public $entrypersoncontact;
    public $timestamp;

    public $isstoreorderdetail;
    public $storeorderdetailinfo = array();

    public function getId(){
      return $this->id;
    }

    public function setId($id){
      $this->id = $id;
    }

    public function getIsStoreOrderDetail(){
        return $this->isstoreorderdetail;
    }

    public function setIsStoreOrderDetail($isstoreorderdetail){
        $this->isstoreorderdetail = $isstoreorderdetail;
    }

    public function getStoreOrderDetailInfo(){
      return $this->storeorderdetailinfo;
    }

    public function setStoreOrderDetailInfo($storeorderdetailinfo){
      $this->storeorderdetailinfo = $storeorderdetailinfo;
    }


    public $isstoreorderpaymentdetail;
    public $storeorderpaymentdetailinfo = array();

    public function getIsStoreOrderPaymentDetail(){
        return $this->isstoreorderpaymentdetail;
    }

    public function setIsStoreOrderPaymentDetail($isstoreorderpaymentdetail){
        $this->isstoreorderpaymentdetail = $isstoreorderpaymentdetail;
    }

    public function getStoreOrderPaymentDetailInfo(){
      return $this->storeorderpaymentdetailinfo;
    }

    public function setStoreOrderPaymentDetailInfo($storeorderpaymentdetailinfo){
      $this->storeorderpaymentdetailinfo = $storeorderpaymentdetailinfo;
    }

}


class storeorderdetailinfo{

    public $id;
    public $orderid;
    public $type;
    public $typename;
    public $catid;
    public $category;
    public $subcatid;
    public $subcategory;
    public $itemid;
    public $itemname;
    public $qty;
    public $taxtype;
    public $taxtypename;
    public $sgst;
    public $cgst;
    public $igst;
    public $price;
    public $discountper;
    public $discountamt;
    public $taxable;
    public $sgsttaxamt;
    public $cgsttaxamt;
    public $igsttaxamt;
    public $finalprice;
  
  }


  class storeorderpaymentdetailinfo{

    public $id;
    public $orderid;
    public $type;
    public $paytypeid;
    public $paytypename;
    public $amount;
  
  }



?>