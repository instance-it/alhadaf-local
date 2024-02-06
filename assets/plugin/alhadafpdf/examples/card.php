<?php include('../../functions/function.php');

$id=$_REQUEST['id'];
$time = time();
$perrow=5;

require_once('tcpdf_include.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Touch Lebs');
$pdf->SetTitle('Touch Lebs');
$pdf->SetSubject('Touch Lebs');
$pdf->SetKeywords('Touch Lebs');

$pdf->setPrintFooter(false);
$pdf->setPrintHeader(false);
$pdf->SetMargins(15, 85, 100, true);

$pdf->SetAutoPageBreak(TRUE, 0);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

$pdf->SetFont('courier', '', 11);
//$pdf->SetFont('timesI', '', 11);

// add a page
$pdf->AddPage();

$qry="SELECT tc.*,tl.`img`,tl.fontcolorcode FROM `tblcardissuemaster` tc INNER JOIN `tblloyaltymaster` tl ON tl.`id`=tc.`cardid` WHERE tc.id=$id";
$res = getmenual($qry); 
$num = $res->rowCount();
$row = $res->fetch();

$colorcode=$row['fontcolorcode'];
// Card Image
$pdf->Image('../../'.$row['img'], 10, 50, 190, '', '', '', '', false, 300);


//$f= dirname(dirname(__DIR__))."/files/".$row['cardname'].'-'.$row['cardno'].".pdf"; 

// Print a text
$html = '<span style="color:'.$colorcode.';font-weight:bold;font-size:18pt;">'.$row['personname'].'</span>';
$pdf->writeHTML($html, true, false, true, false, '');
$html = '<span style="color:'.$colorcode.';font-size:14pt;">Contact No.: '.$row['contactno'].'</span>';
$pdf->writeHTML($html, true, false, true, false, '');
$html = '<span style="color:'.$colorcode.';font-size:14pt;">Card No.: '.$row['cardno'].'</span>';
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->SetFont('timesBI', '', 30);

$pdf->MultiCell(130, 60, $row['cardname'], 0, 'L', 0, 1, '55', '110', true);

// new style
$style = array(
    'border' => false,
    'padding' => 1,
    'fgcolor' => array(0,0,0),
    'bgcolor' => array(255,255,255),
);

// QRCODE,H : QR-CODE Best error correction
$pdf->write2DBarcode($row['cardno'], 'QRCODE,H', 15, 108, 18, 18, $style, 'N');






$filename= dirname(dirname(__DIR__))."/files/".$row['cardname'].'-'.$row['cardno'].".pdf"; 
$filename1= $row['cardname'].'-'.$row['cardno'].".pdf"; 

$cnsdata=array(
	'file'=>$filename1,
);
executedata('u','tblcardissuemaster',$cnsdata,'id='.$id);

$pdf->Output($filename, 'F');
exit();


//$pdf->Output('example_049.pdf', 'I');

?>