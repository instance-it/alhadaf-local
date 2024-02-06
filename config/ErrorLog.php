<?php
class ErrorLog
{
    public $tblname;
    public $sqlqry;
    public $mainarray;
    public $opration;
    public $errormsg;
    public $errorcode;

    public function getTblname(){
		return $this->tblname;
	}

	public function setTblname($tblname){
		$this->tblname = $tblname;
	}

	public function getSqlqry(){
		return $this->sqlqry;
	}

	public function setSqlqry($sqlqry){
		$this->sqlqry = $sqlqry;
	}

	public function getMainarray(){
		return $this->mainarray;
	}

	public function setMainarray($mainarray){
		$this->mainarray = $mainarray;
	}

    public function getOpration(){
		return $this->opration;
	}

	public function setOpration($opration){
		$this->opration = $opration;
	}

	public function getErrormsg(){
		return $this->errormsg;
	}

	public function setErrormsg($errormsg){
		$this->errormsg = $errormsg;
	}

	public function getErrorcode(){
		return $this->errorcode;
	}

	public function setErrorcode($errorcode){
		$this->errorcode = $errorcode;
	}
}
?>