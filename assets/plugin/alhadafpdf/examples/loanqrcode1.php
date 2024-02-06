<?php include('../../functions/function.php');

$id=$_REQUEST['id'];
$pdid = $_REQUEST['pdid'];
$skip=$_REQUEST['skip'];
$time = time();
$edate = date('d-m-Y');
$perrow=5;

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Regency');
$pdf->SetTitle('Loan QR Code');
$pdf->SetSubject('Loan QR Code');
$pdf->SetKeywords('QR, Code, Loan');

$pdf->setPrintFooter(false);
$pdf->setPrintHeader(false);
$pdf->SetMargins(0, 0, 0,0);

$pdf->SetAutoPageBreak(TRUE, 0);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

$pdf->SetFont('helvetica', '', 11);

// add a page
$pdf->AddPage();
$style = array(
	'border'=>1,
	'padding' => '5',
	'vpadding' => 'auto',
    'hpadding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => array(255,255,255)
);

$qry="SELECT pl.id,pl.`personloanno`,el.`personname` FROM `tblpersonloandetails` pl
	INNER JOIN `tblenquiry` e ON e.id = pl.`enqid`
	INNER JOIN `tblenquiryloanperson` el ON el.`id` = pl.`personid`
	WHERE pid = $id group by pl.id";
$res = getmenual($qry); 
$num = $res->rowCount();

if($num > 0)
{
	$str.='<table cellspacing="0" cellpadding="1" border="0"><tr align="center">';
	$i=0;

	for($j=0;$j<$skip;$j++) // skip barcode
	{	
		$mod=$j%$perrow;

		$str.='<td align="center" style="padding-left:0px;height:175"><br><br>';
		$str .= '<br>';
		$str .='</td>';
		$i++;

		if($mod==$perrow-1)
		{
			$str.='</tr>';
			$str.='<tr>';	
		}	
	}
	while($row = $res->fetch())
	{
	
		$mod=$i%$perrow;
		$params=$pdf->serializeTCPDFtagParameters(array($row['personloanno'], 'QRCODE,H', '', '', 25, 25, array('border' => 0, 'padding' => 2, 'fgcolor' => array(0, 0, 0), 'fontsize' => 8), 'N'));;
		$str.='<td align="center" style="padding-left:0px;height:175;font-size:9px"><br><br><br>'.substr($row['personname'], 0, 16).'<br>'.$row['personloanno'].'<br>';
		$str .= '<tcpdf method="write2DBarcode" params="'.$params.'" /><br>';
		$str .='</td>';
	
		if($mod==$perrow-1)
		{
			$str.='</tr>';
			$str.='<tr>';
		}
		$i++;
	}
	$str.='</tr></table>';
}	
$pdf->writeHTML($str,true, false,false,false,'left');
$pdf->Output('example_049.pdf', 'I');

?>