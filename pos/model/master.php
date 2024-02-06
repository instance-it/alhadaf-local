<?php 
class listpersonstore{

    public $id;
    public $name;
    public $image;

}

class liststoresubcategory{

    public $id;
    public $name;
    public $image;

}

class liststoreitem{

    public $id;
    public $name;
    public $itemno;
    public $categoryid;
    public $category;
    public $subcategoryid;
    public $subcategory;
    public $price;
    public $taxtypename;
    public $taxtype;
    public $sgst;
    public $cgst;
    public $igst;
    public $image;

}

class range{

    public $id;
    public $name;

}


class rangelane{

    public $id;
    public $name;

}



?>