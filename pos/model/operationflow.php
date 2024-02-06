<?php 

class instructiondata{

    public $id;
    public $name;
    

}

class operationflowdata{

    public $id;
    public $storeid;
    public $storename;
    public $operationid;
    public $operationname;
    public $iscompulsory;
    public $iscompleted;
    public $displayorder;
    public $statuscolor;
    public $iscurrent;
    

}

class storerange{

    public $id;
    public $name;

}


class storerangelane{

    public $id;
    public $name;

}



class listmemberrangedata{

    public $id;
    public $personname;
    public $firstname;
    public $middlename;
    public $lastname;
    public $contact;
    public $email;
    public $profileimg;

    public $rangeassignid;
    public $storeid;

    public $rangeid;
    public $rangename;
    public $laneid;
    public $lanename;

    public $date;
    

    public $isreleased;
    public $releasestatus;
    public $releasestatuscolor;

    public $isitemdetail;
    public $itemdetail=array();

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getDate(){
        return $this->date;
    }

    public function setDate($date){
        $this->date = $date;
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

}


class itemdetail{

    public $id;
    public $name;
    public $qty;
   
}


class storeinstructiondata{

    public $id;
    public $name;
    public $iscompleted;
    public $instructionstatus;

}


?>