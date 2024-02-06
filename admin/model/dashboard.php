<?php 

class listrecentmemberdata{

    public $id;
    public $timestamp;
    public $personname;
    public $contact;
    public $email;
    public $userrole;
    public $entrydate;
    public $memberstatus;
    public $isverified;

}


class listrecentorderdata{

    public $id;
    public $timestamp;
    public $transactionid;
    public $orderno;
    public $saporderid;
    public $personname;
    public $contact;
    public $ofulldate;
    public $totalpaid;
    public $memberimg;

}


class listrecentserviceorderdata{

    public $id;
    public $timestamp;
    public $transactionid;
    public $orderno;
    public $storename;
    public $personname;
    public $contact;
    public $ofulldate;
    public $totalpaid;

}

?>