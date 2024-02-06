<?php 
header("Content-Type:application/json");
require_once dirname(__DIR__, 2).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
require_once dirname(__DIR__, 2).'\model\language.php';


$action = $_REQUEST['action'];

$status=0;
$message=$errmsg['invalidrequest'];
$imgpath=$config->getImageurl();

//list Language
if($action == 'listlanguage')
{	
	$listlanguagedata=new listlanguagedata();

	$qry="select id,languagename,languageengname,label1,label2,label3,CASE WHEN (isnull(img,'') = '')  THEN '' ELSE concat(:defaultimgurl,img) END AS icon 
		from tbllanguagemaster 
		WHERE isactive=1 AND showinapp=1 AND apptypeid=3
		ORDER BY (case when (displayorder>0) then displayorder else 99999 end)";
	$parms = array(
		':defaultimgurl'=>$imgpath,
	);
	$listlanguagedata=$DB->getmenual($qry,$parms,'listlanguagedata');

	$response['islanguagedata']=0;
	if($listlanguagedata)
	{
		$response['islanguagedata']=1;
		$response['languagedata']=$listlanguagedata;
		
		$status = 1;
		$message = $errmsg['success'];
	}
	else
	{
		$status=0;
		$message=$errmsg['nodatafound'];
	}
}
//list Language Wise Label
else if($action == 'listlanguagewiselabel')
{	
	$languageid=$IISMethods->sanitize($_POST['languageid']);

	$qrychk="SELECT id FROM tbllanguagemaster l WHERE CONVERT(VARCHAR(255), id)=:languageid AND isactive=1 AND showinapp=1 and apptypeid=3";
	$parms = array(
		':languageid'=>$languageid,
	);
	$result_ary=$DB->getmenual($qrychk,$parms);

	if(sizeof($result_ary)==0)
	{
		$defaultlanguaeid = $DB->getdefaultlanguageid(3)[0];
		$languageid = $IISMethods->sanitize($defaultlanguaeid['id']);
	}


	$listlanguagelabeldata=new listlanguagelabeldata();

	
	$qry="SELECT lb.id,lb.labelnameid,CASE WHEN isnull(al.langlabelname,'')='' THEN lb.labelengname ELSE al.langlabelname END AS label
		FROM tbllabelmaster lb 
		LEFT JOIN tblassignlanguagewiselabel al ON lb.id=al.labelid AND al.languageid=:languageid and al.apptypeid=3
		where lb.apptypeid=3";
	$parms = array(
		':languageid'=>$languageid,
	);
	$listlanguagelabeldata=$DB->getmenual($qry,$parms,'listlanguagelabeldata');

	$response['islanguagelabeldata']=0;
	if($listlanguagelabeldata)
	{
		$response['islanguagelabeldata']=1;
		$response['languagelabeldata']=$listlanguagelabeldata;
		
		$status = 1;
		$message = $errmsg['success'];
	}
	else
	{
		$status=0;
		$message=$errmsg['nodatafound'];
	}
}


require_once dirname(__DIR__, 3).'\config\apifoot.php';

?>

  