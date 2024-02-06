<?php 

class listmemberdata{

    public $id;
    public $personname;
    public $firstname;
    public $middlename;
    public $lastname;
    public $contact;
    public $email;
    public $address;
    public $qataridno;
    public $qataridexpiry;
    public $passportidno;
    public $passportidexpiry;
    public $dob;
    public $nationality;
    public $companyname;
    public $profileimg;

    public $ismshipdetail;
    public $mshipdetail=array();

    public $isitemdetail;
    public $itemdetail=array();

    public $lastvisitdate;
    public $islastvisitcategory;
    public $lastvisitcategory=array();

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }


    public function getIsMshipDetail(){
        return $this->ismshipdetail;
    }

    public function setIsMshipDetail($ismshipdetail){
        $this->ismshipdetail = $ismshipdetail;
    }

    public function getMshipDetail(){
        return $this->mshipdetail;
    }

    public function setMshipDetail($mshipdetail){
        $this->mshipdetail = $mshipdetail;
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


    public function getLastVisitDate(){
        return $this->lastvisitdate;
    }

    public function setLastVisitDate($lastvisitdate){
        $this->lastvisitdate = $lastvisitdate;
    }

    public function getIsLastVisitCategory(){
        return $this->islastvisitcategory;
    }

    public function setIsLastVisitCategory($islastvisitcategory){
        $this->islastvisitcategory = $islastvisitcategory;
    }

    public function getLastVisitCategory(){
        return $this->lastvisitcategory;
    }

    public function setLastVisitCategory($lastvisitcategory){
        $this->lastvisitcategory = $lastvisitcategory;
    }
  

}


class mshipdetail{
    public $id;
    public $name;
    public $isfreezemship;
}


class itemdetail{

    public $categoryid;
    public $category;
    public $subcategoryid;
    public $subcategory;
    public $id;
    public $name;

    public $isitemsubdetail;
    public $itemsubdetail=array();

    public function getItemId(){
        return $this->id;
    }

    public function setItemId($id){
        $this->id = $id;
    }

    public function getIsItemSubDetail(){
        return $this->isitemsubdetail;
    }

    public function setIsItemSubDetail($isitemsubdetail){
        $this->isitemsubdetail = $isitemsubdetail;
    }

    public function getItemSubDetail(){
        return $this->itemsubdetail;
    }

    public function setItemSubDetail($itemsubdetail){
        $this->itemsubdetail = $itemsubdetail;
    }

}


class itemsubdetail{

    public $oidid;
    public $baseqty;
    public $qty;
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




class lastvisitcategory{

    public $categoryid;
    public $category;
    

    public $islastvisititem;
    public $lastvisititem=array();

    public function getCategoryId(){
        return $this->categoryid;
    }

    public function setCategoryId($categoryid){
        $this->categoryid = $categoryid;
    }

    public function getIsLastVisitItem(){
        return $this->islastvisititem;
    }

    public function setIsLastVisitItem($islastvisititem){
        $this->islastvisititem = $islastvisititem;
    }

    public function getLastVisitItem(){
        return $this->lastvisititem;
    }

    public function setLastVisitItem($lastvisititem){
        $this->lastvisititem = $lastvisititem;
    }

}


class lastvisititem{

    public $id;
    public $name;
    public $qty;
    public $returnstatus;
    public $returnstatuscolor;
   

}


class listmemberusertype{
    public $id;
    public $name;
    public $isguest;
}

class listmember{
    public $id;
    public $name;
    public $personname;
    public $contact;
}



?>