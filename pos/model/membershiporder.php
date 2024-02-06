<?php 
class listordermember{

    public $id;
    public $name;
    public $personname;
    public $contact;

}

class listordercategory{

    public $id;
    public $name;
    public $iscourse;

}

class listordersubcategory{

    public $id;
    public $name;

}


class listorderitem{

    public $id;
    public $itemname;
    public $itemno;
    public $price;
    public $taxtypename;
    public $taxtype;
    public $sgst;
    public $cgst;
    public $igst;
    public $descr;
    public $duration;
    public $durationname;
    public $strduration;
    public $strvalidityduration;
    public $noofstudent;
    public $strnoofstudent;
    public $iconimg;
    public $image;
    public $iscourse;
    public $type;

    public $attributedetail = array();

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getAttributeDetail(){
        return $this->attributedetail;
    }

    public function setAttributeDetail($attributedetail){
        $this->attributedetail = $attributedetail;
    }

}


class attributedetail{

    public $id;
    public $name;
    public $attributename;
    public $iconimg;

}

class coursedetail{

    public $id;
    public $itemname;
    public $itemno;
    public $price;
    public $taxtypename;
    public $taxtype;
    public $sgst;
    public $cgst;
    public $igst;
    public $descr;
    public $duration;
    public $strduration;
    public $noofstudent;
    public $strnoofstudent;
    public $itemimg;
    public $defaultcourseimg;
    public $strvalidityduration;

    public $iscourseimgdata;
    public $courseimage = array();

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getCourseImage(){
        return $this->courseimage;
    }

    public function setCourseImage($courseimage){
        $this->courseimage = $courseimage;
    }


    public function getIsCourseImgData(){
        return $this->iscourseimgdata;
    }

    public function setIsCourseImgData($iscourseimgdata){
        $this->iscourseimgdata = $iscourseimgdata;
    }


    public $iscoursebenefit;
    public $coursebenefit = array();

    public function getIsCourseBenefit(){
        return $this->iscoursebenefit;
    }

    public function setIsCourseBenefit($iscoursebenefit){
        $this->iscoursebenefit = $iscoursebenefit;
    }

    public function getCourseBenefit(){
        return $this->coursebenefit;
    }

    public function setCourseBenefit($coursebenefit){
        $this->coursebenefit = $coursebenefit;
    }



    public $iscoursedisplaydata;
    public $coursedisplaydata = array();

    public function getIsCourseDisplayData(){
        return $this->iscoursedisplaydata;
    }

    public function setIsCourseDisplayData($iscoursedisplaydata){
        $this->iscoursedisplaydata = $iscoursedisplaydata;
    }

    public function getCourseDisplayData(){
        return $this->coursedisplaydata;
    }

    public function setCourseDisplayData($coursedisplaydata){
        $this->coursedisplaydata = $coursedisplaydata;
    }

}


class courseimage{

    public $id;
    public $courseimg;

}


class coursebenefit{

    public $id;
    public $name;
    public $rowdurationname;
    public $iconimg;

}


class coursedisplaydata{

    public $id;
    public $name;
    public $iconimg;

}


class listorderhistory{

    public $id;
    public $transactionid;
    public $orderno;
    public $uid;
    public $membername;
    public $membercontact;
    public $totalamount;
    public $couponapply;
    public $couponid;
    public $couponcode;
    public $couponamount;
    public $totaltaxableamt;
    public $totaltax;
    public $totalpaid;
    public $iscancel;
    public $orderstatus;
    public $orderstatuscolor;
    public $ofulldate;
    public $entrypersonname;
    public $entrypersoncontact;
    public $invoicepdfurl;
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