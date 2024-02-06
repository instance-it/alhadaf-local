<?php 

class listmemberdata{

    public $id;
    public $personname;
    public $firstname;
    public $lastname;
    public $contact;
    public $email;
    public $address;
    public $qataridno;
    public $qataridexpiry;
    public $dob;
    public $nationality;
    public $companyname;
    public $profileimg;
    public $itemdetail=array();

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getItemDetail(){
        return $this->itemdetail;
    }

    public function setItemDetail($itemdetail){
        $this->itemdetail = $itemdetail;
    }
  

}


class itemdetail{
    public $id;
    public $name;
}

?>