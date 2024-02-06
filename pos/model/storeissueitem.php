<?php 

class liststoreorderissuehistory{

    public $id;
    public $orderno;
    public $uid;
    public $membername;
    public $membercontact;
    public $orderdate;
    public $timestamp;

    public $isissueorderdetail;
    public $issueorderdetailinfo = array();

    public function getId(){
      return $this->id;
    }

    public function setId($id){
      $this->id = $id;
    }

    public function getIsIssueOrderDetail(){
        return $this->isissueorderdetail;
    }

    public function setIsIssueOrderDetail($isissueorderdetail){
        $this->isissueorderdetail = $isissueorderdetail;
    }

    public function getIssueOrderDetailInfo(){
      return $this->issueorderdetailinfo;
    }

    public function setIssueOrderDetailInfo($issueorderdetailinfo){
      $this->issueorderdetailinfo = $issueorderdetailinfo;
    }

}

class issueorderdetailinfo{

  public $id;
  public $orderid;
  public $oidid;
  public $type;
  public $typename;
  public $catid;
  public $category;
  public $subcatid;
  public $subcategory;
  public $itemid;
  public $itemname;
  public $qty;
  public $issued_qty;
  public $remain_qty;
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

  public $itemstatus;
  public $itemstatuscolor;
  

}

?>