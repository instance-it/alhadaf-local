<?php 
require_once 'config.php';
require_once 'DB.php'; 

class DBconfig extends DB{

    private $MainDB;

    public function __construct()
    {
        //Main Database connection
        DB::setDBType('MSSQL');
        DB::setDBName('alhadafshootingrange');
        DB::setDBUser('sa');
        DB::setDBHost('192.168.1.2');
        DB::setDBPass('Asd123!@#');
        DB::setDBPort('1433');
        DB::Connect();
    }

}




?>