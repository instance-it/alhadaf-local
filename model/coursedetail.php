<?php 

class coursedetail{

    public $id;
    public $itemname;
    public $itemno;
    public $n_itemname;
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

?>