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

}


?>