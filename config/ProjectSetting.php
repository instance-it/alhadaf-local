<?php 


class ProjectSetting{
    
	public function __construct()
	{
	
	}

	private $twitterlink;
	private $instagramlink;
	private $facebooklink;
	private $whatsappno;
	private $youtubelink;
	
	private $home_top_video;
	private $home_top_text;
	private $home_top_buttontext;
	private $home_top_buttonurl;

	private $rb_video;
	private $iframemaplink;

	private $work_fromtime;
	private $work_totime;
	private $book_duration;


	public function getTwitterlink(){
		return $this->twitterlink;
	}

	public function setTwitterlink($twitterlink){
		$this->twitterlink = $twitterlink;
	}

	public function getInstagramlink(){
		return $this->instagramlink;
	}

	public function setInstagramlink($instagramlink){
		$this->instagramlink = $instagramlink;
	}

	public function getFacebooklink(){
		return $this->facebooklink;
	}

	public function setFacebooklink($facebooklink){
		$this->facebooklink = $facebooklink;
	}

	public function getWhatsAppNo(){
		return $this->whatsappno;
	}

	public function setWhatsAppNo($whatsappno){
		$this->whatsappno = $whatsappno;
	}

	public function getYoutubelink(){
		return $this->youtubelink;
	}

	public function setYoutubelink($youtubelink){
		$this->youtubelink = $youtubelink;
	}

	public function getHomeTopVideo(){
		return $this->home_top_video;
	}

	public function setHomeTopVideo($home_top_video){
		$this->home_top_video = $home_top_video;
	}

	public function getHomeTopText(){
		return $this->home_top_text;
	}

	public function setHomeTopText($home_top_text){
		$this->home_top_text = $home_top_text;
	}

	public function getHomeTopButtontext(){
		return $this->home_top_buttontext;
	}

	public function setHomeTopButtontext($home_top_buttontext){
		$this->home_top_buttontext = $home_top_buttontext;
	}

	public function getHomeTopButtonurl(){
		return $this->home_top_buttonurl;
	}

	public function setHomeTopButtonurl($home_top_buttonurl){
		$this->home_top_buttonurl = $home_top_buttonurl;
	}

	public function getRBVideo(){
		return $this->rb_video;
	}

	public function setRBVideo($rb_video){
		$this->rb_video = $rb_video;
	}

	public function getIFrameMapLink(){
		return $this->iframemaplink;
	}

	public function setIFrameMapLink($iframemaplink){
		$this->iframemaplink = $iframemaplink;
	}



	public function getWorkFromTime(){
		return $this->work_fromtime;
	}

	public function setWorkFromTime($work_fromtime){
		$this->work_fromtime = $work_fromtime;
	}

	public function getWorkToTime(){
		return $this->work_totime;
	}

	public function setWorkToTime($work_totime){
		$this->work_totime = $work_totime;
	}

	public function getBookDuration(){
		return $this->book_duration;
	}

	public function setBookDuration($book_duration){
		$this->book_duration = $book_duration;
	}
}


?>