<?php 

class listserviceorderhistory{

    public $id;
    public $transactionid;
    public $orderno;
    public $storeid;
    public $storename;
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
    public $referenceno;
    public $ofulldate;
    public $entrypersonname;
    public $entrypersoncontact;
    public $timestamp;
    public $invoicepdfurl;


    public $showeditbutton;
    public $showcancelbutton;
    public $orderstatus;
    public $orderstatuscolor;


    public $isserviceorderdetail;
    public $serviceorderdetailinfo = array();

    public function getId(){
      return $this->id;
    }

    public function setId($id){
      $this->id = $id;
    }

    public function getIsServiceOrderDetail(){
        return $this->isserviceorderdetail;
    }

    public function setIsServiceOrderDetail($isserviceorderdetail){
        $this->isserviceorderdetail = $isserviceorderdetail;
    }

    public function getServiceOrderDetailInfo(){
      return $this->serviceorderdetailinfo;
    }

    public function setServiceOrderDetailInfo($serviceorderdetailinfo){
      $this->serviceorderdetailinfo = $serviceorderdetailinfo;
    }


    public $isserviceorderpaymentdetail;
    public $serviceorderpaymentdetailinfo = array();

    public function getIsServiceOrderPaymentDetail(){
        return $this->isserviceorderpaymentdetail;
    }

    public function setIsServiceOrderPaymentDetail($isserviceorderpaymentdetail){
        $this->isserviceorderpaymentdetail = $isserviceorderpaymentdetail;
    }

    public function getServiceOrderPaymentDetailInfo(){
      return $this->serviceorderpaymentdetailinfo;
    }

    public function setServiceOrderPaymentDetailInfo($serviceorderpaymentdetailinfo){
      $this->serviceorderpaymentdetailinfo = $serviceorderpaymentdetailinfo;
    }

}


class serviceorderdetailinfo{

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
    public $issuedqty;
    public $remainqty;
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
    public $showcancelbutton;
    public $soitemstatus;
    public $soitemstatuscolor;
  
  }


  class serviceorderpaymentdetailinfo{

    public $id;
    public $orderid;
    public $type;
    public $paytypeid;
    public $paytypename;
    public $amount;
  
  }



?>