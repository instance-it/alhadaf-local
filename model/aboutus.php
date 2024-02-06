<?php 

class listaboutus{

    public $id;
    public $contenttypeid;
    public $title;
    public $title2;
    public $description;
    public $img;

    public $aboutusdetail = array();

    public function getId(){
        return $this->contenttypeid;
    }

    public function setId($contenttypeid){
        $this->contenttypeid = $contenttypeid;
    }

    public function getAboutusDetail(){
        return $this->aboutusdetail;
    }

    public function setAboutusDetail($aboutusdetail){
        $this->aboutusdetail = $aboutusdetail;
    }
}

class aboutusdetail
{
    public $id;
    public $title;
    public $displayorder;
    public $descr;
    public $type;
    public $abtcount;
}


?>