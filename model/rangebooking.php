<?php 
class servicetype{

    public $id;
    public $type;
	public $name;

}

class rangebooking{
    public $id;
    public $name;
    public $islanedata;
    public $istimedata;
    public $lanedetail = array();
	public $timedetail = array();

    public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

    public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

    public function getIsLanedata(){
		return $this->islanedata;
	}

	public function setIsLanedata($islanedata){
		$this->islanedata = $islanedata;
	}

    public function getIsTimedata(){
		return $this->istimedata;
	}

	public function setIsTimedata($istimedata){
		$this->istimedata = $istimedata;
	}

    public function getLanedetail(){
		return $this->lanedetail;
	}

	public function setLanedetail($lanedetail){
		$this->lanedetail = $lanedetail;
	}

	public function getTimedetail(){
		return $this->timedetail;
	}

	public function setTimedetail($timedetail){
		$this->timedetail = $timedetail;
	}
}



class LaneDetail{

	public function __construct()
	{
		
	}
	public $id;
	public $name;

}

class TimeDetail{

	public function __construct()
	{
		
	}
	public $id;
	public $fromtime;
	public $totime;
}

?>