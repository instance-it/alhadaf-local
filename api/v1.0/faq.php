<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 2).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\faq.php';

if($isvalidUser['status'] == 1)
{
	$status=0;
	$message=$errmsg['invalidrequest'];
	$imageurl=$config->getImageurl();
	 
	//list Courses
	if($action == 'listfaq')
	{
		$id=$IISMethods->sanitize($_POST['id']);
		$faqdata=new listfaq();
		
		$qry="select f.id,f.question,f.answer
			from tblfaq f where f.isactive=1 order by f.displayorder";
		$parms = array();
		$faqdata=$DB->getmenual($qry,$parms,'listfaq');

		$response['isfaqdata']=0;
		if($faqdata)
		{
			$response['isfaqdata']=1;
			$response['faqdata']= $faqdata;

			$status=1;
			$message=$errmsg['success'];
		}
		else
		{
			$status=0;
			$message=$errmsg['nofaqfound'];
		}
	}
	
	

	
}

require_once dirname(__DIR__, 2).'\config\apifoot.php';

?>

  