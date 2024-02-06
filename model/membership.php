<?php 

class membership{

    public $id;
    public $itemname;
    public $price;
    public $taxtypename;
    public $taxtype;
    public $sgst;
    public $cgst;
    public $igst;
    public $duration;
    public $durationname;
    public $strvalidityduration;
    public $iconimg;
    public $image;

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


class membershipdetail{

    public $id;
    public $name;
    public $iconimg;

}


class attributedetail{

    public $id;
    public $name;
    public $attributename;
    public $iconimg;

}

?>