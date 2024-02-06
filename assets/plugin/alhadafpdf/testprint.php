<?php require_once dirname(__DIR__, 3).'/config/init.php';
ob_start();
// Include the main TCPDF library (search for installation path).

require_once('tcpdf_include.php');

$pid=$_REQUEST['pid'];
$type = $_REQUEST['type'];
$time = $_REQUEST['time'];
$date=date('Y-m-d');


$symbol='₹';


// $unqorderid="VIVAAH".str_pad($rowmember['id'],6,'0',STR_PAD_LEFT);
// $invoiceid=$rowmember['id'];

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetSubject($rowmember['packagename']);
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
$pdf->SetTitle($rowle['personname']." - Dr.Pooja Arora");
$pdf->setPrintFooter(false);
$pdf->setPrintHeader(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);
$pdf->SetMargins(10, 10, 10, true);	
// add a page
$pdf->AddPage();



$htmldata ='<style>
span.title {
	text-align: LEFT;
	text-transform: uppercase;
	float:Left;
	font-weight: bold;
	font-size:22px !important;
}
</style>';

$htmldata .='<h2>Test 123</h2>';

			
// echo $html; #e5f5b8
// output the HTML content
$pdf->writeHTML($htmldata, true, false, true, false, '');


// reset pointer to the last page
$pdf->lastPage();
$pdf->Output();
// if($type == 'view')
// {

	// $pdf->Output();
// }
// else 
// {
// 	$ordno=str_replace( array( '\'', '/', ':' , '*', '?', '"', '<', '>', '|' ), '-', $qrow['proformano']);

// 	$pdfname = 'files/proforma/'.$ordno.'_'.$time.'.pdf';
// 	$upddata = array(
// 		'pdf_file'=>$pdfname,
// 	);			
// 	// executedata('u','tblproforma',$upddata,'id='.$qrow['id']);

// 	$fname=dirname(__DIR__).'/files/proforma/'.$ordno.'_'.$time.'.pdf';

// 	$pdf->Output($fname,'F');
// 	exit();
// }

//============================================================+
// END OF FILE
//============================================================+
