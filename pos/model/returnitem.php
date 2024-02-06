<?php 

class listreturnordermember{

    public $id;
    public $name;
    public $personname;
    public $contact;

}

class listmemberdata{

    public $id;
    public $membername;
    public $membercontact;
    public $memberemail;
    public $profileimg;

    public $isreturnitemdetail;
    public $returnitemdetail=array();

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }


    public function getIsReturnItemDetail(){
        return $this->isreturnitemdetail;
    }

    public function setIsReturnItemDetail($isreturnitemdetail){
        $this->isreturnitemdetail = $isreturnitemdetail;
    }

    public function getItemDetail(){
        return $this->returnitemdetail;
    }

    public function setReturnItemDetail($returnitemdetail){
        $this->returnitemdetail = $returnitemdetail;
    }
  

}


class returnitemdetail{

    public $catid;
    public $category;
    public $subcatid;
    public $subcategory;
    public $itemid;
    public $itemname;
    public $qty;
    public $oldqty;

}



class listreturnorderhistory{

    public $id;
    public $storeid;
    public $memberid;
    public $membername;
    public $membercontact;
    public $orderdate;
    public $comment;
    public $ofulldate;
    public $entrypersonname;
    public $entrypersoncontact;
    public $timestamp;

    public $isreturnorderdetail;
    public $returnorderdetailinfo = array();

    public function getId(){
      return $this->id;
    }

    public function setId($id){
      $this->id = $id;
    }

    public function getIsReturnOrderDetail(){
        return $this->isreturnorderdetail;
    }

    public function setIsReturnOrderDetail($isreturnorderdetail){
        $this->isreturnorderdetail = $isreturnorderdetail;
    }

    public function getReturnOrderDetailInfo(){
      return $this->returnorderdetailinfo;
    }

    public function setReturnOrderDetailInfo($returnorderdetailinfo){
      $this->returnorderdetailinfo = $returnorderdetailinfo;
    }

}


class returnorderdetailinfo{

    public $id;
    public $orderid;
    public $catid;
    public $category;
    public $subcatid;
    public $subcategory;
    public $itemid;
    public $itemname;
    public $qty;
  
  }


?>