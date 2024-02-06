<?php
require_once dirname(__DIR__, 3).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
ob_start();
// Include the main TCPDF library (search for installation path).

require_once('tcpdf_include.php');

//$id=$_REQUEST['id'];
$type = $_REQUEST['type'];


//$time = $_REQUEST['time'];
$date=date('Y-m-d');

// $qrybooking="SELECT bd.*,pm1.organizationname,isnull(pm.contact,pm1.contact) as agentcontact 
// FROM bookingdetails bd 
// INNER JOIN tblpersonmaster pm1 on pm1.id = bd.uid
// LEFT JOIN tblpersonmaster pm on  CONVERT(varchar(255) ,pm.id) = CONVERT(varchar(255),bd.subagentid)  
// WHERE bd.bookedstatus = 1 AND bd.id=:id";
        
// $bookingarray=array(
//     ':id'=>$id,
// );
// //  print_r($bookingarray);	
// $resbookingdetails=$DB->getmenual($qrybooking,$bookingarray,'BookingInfo');


// $unqorderid="VIVAAH".str_pad($rowmember['id'],6,'0',STR_PAD_LEFT);
// $invoiceid=$rowmember['id'];

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle($invoiceid);
$pdf->SetSubject($rowmember['packagename']);
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$pdf->setPrintFooter(false);
$pdf->setPrintHeader(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
{
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);
$pdf->SetMargins(5, 5, 5, 5);	
// add a page
// $pdf->AddPage();

$pdf->AddPage('P', 'A4');

$imgurl=$config->getImageurl();



/*
    $html .='<table cellpadding="1" cellspacing="5" style="border-collapse: collapse;background-color: #fff;border:1px solid #e8e8e8;font-size:12px;border-radius: 10px;color:#000000;" id="printdata">';
        $html .='<tr>';
            $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';

                $html.='<table cellpadding="1" cellspacing="5" style="border-collapse: collapse;">';
                    $html.='<tr>';
                        $html.='<td align="left">';
                            $html.='<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">';
                                $html.='<tr>';
                                    $html.='<td align="left" width="25%" style="vertical-align:middle;text-align:left;border-bottom: none;line-height:1;">';
                                        $html.='<span class="barcode-img" >
                                                   QR Code
                                                </span>';

                                                $html.='<span style="color: #28a745;">Booked</span>';
                                                
                                    $html.='</td>';

                                    
                                    $html.='<td align="center" width="50%" style="vertical-align:middle;text-align:center;border-bottom: none;line-height:1;">';
                                        $html.='<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">';
                                            $html.='<tr>';
                                                $html.='<td valign="center" width="30%" style="vertical-align:middle;border: none;">
                                                        <span style="color: #0b2347;font-size: 24px;">From</span>
                                                        <div style="font-size: 12px;font-weight: 500;margin:0;display:block;line-height:1.5;">From RORO TERMINAL'.'</div>
                                                        <span style="color: #008cff;">From Time</span>
                                                    </td>';
                                                $html.='<td valign="center" width="40%" style="vertical-align:middle;border: none;">
                                                        <img src="'.$imgurl.'img/ferry.png" width="100px" alt="From RORO TERMINAL To RORO TERMINAL">
                                                    </td>';
                                                $html.='<td valign="center" width="30%" style="vertical-align:middle;border: none;">
                                                        <span style="color: #0b2347;font-size: 24px;">To</span>
                                                        <div style="font-size: 12px;font-weight: 500;margin:0;display:block;line-height:1.5;">To RORO TERMINAL</div>';
                                                $html.='</td>';
                                            $html.='</tr>';
                                            $html.='<tr>';
                                                
                                            $html.='</tr>';
                                        $html.='</table>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="25%" style="vertical-align:middle;text-align:right;border-bottom: none;line-height:1;">';
                                        $html.='<table cellpadding="1" cellspacing="0" style="border-collapse: collapse;">';
                                            $html.='<tr>';
                                                $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;">
                                                            <span style="color: #9e9d9d;">Travelling Date</span>
                                                            <br>
                                                            <span style="color: #08051a;">Journey Date</span>
                                                        </td>';
                                            $html.='</tr>';
                                            $html.='<tr>';
                                                $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;">
                                                            <span style="color: #9e9d9d;">Booking Id</span>
                                                            <br>
                                                            <span style="color: #08051a;">Transaction ID</span>
                                                        </td>';
                                            $html.='</tr>';
                                            $html.='<tr>';
                                                $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;">
                                                            <span style="color: #9e9d9d;">Booking Date-Time</span>
                                                            <br>
                                                            <span style="color: #08051a;">Entry Date</span>
                                                        </td>';
                                            $html.='</tr>';
                                        $html.='</table>';
                                    $html.='</td>';
                                $html.='</tr>';

                                

                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;text-align:left;border-bottom: none;">';
                                            $html.='<span style="color: #9e9d9d;">Payment Ref. No: </span>';
                                            $html.='<span style="color: #08051a;">123456</span>';
                                            
                                    $html.='</td>';
                                    $html.='<td align="right" width="10%" style="vertical-align:middle;text-align:right;border-bottom: none;"> </td>';
                                    $html.='<td align="right" width="50%" style="vertical-align:middle;text-align:right;border-bottom: none;">';
                                                $html.='<span style="color: #9e9d9d;">Reporting Time: </span>';
                                                $html.='<span style="color: #08051a;">11:00 AM</span>';
                                    $html.='</td>';
                                $html.='</tr>';

                                

                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;text-align:left;border-bottom: none;">';
                                            $html.='<span style="color: #9e9d9d;">Contact No: </span>';
                                            $html.='<span style="color: #08051a;">7600992696</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="10%" style="vertical-align:middle;text-align:right;border-bottom: none;"> </td>';
                                    $html.='<td align="right" width="50%" style="vertical-align:middle;text-align:right;border-bottom: none;">';
                                                $html.='<span style="color: #9e9d9d;">Email Id: </span>';
                                                $html.='<span style="color: #08051a;">ravi.busa@instanceit.com</span>';
                                    $html.='</td>';
                                $html.='</tr>';

                                

                            $html.='</table>';  
                        $html.='</td>';
                    $html.='</tr>';
                $html.='</table>';               

            $html .='</td>';
        $html .='</tr>';


        $html .='<tr>';
            $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';

                $html .='<table cellpadding="0" cellspacing="5">';
                    $html.='<tr>';
                        $html.='<td align="center" style="line-height: 1;">';
                            $html .='<table cellpadding="0" cellspacing="10" style="border-collapse: collapse;border-top: 1px dashed #bbbaba;border-bottom: 1px dashed #bbbaba;font-size:11px;">';
                                $html.='<tr>';
                                    $html.='<td align="center" style="line-height: 1.5;">
                                        <span style="color: #0b2347;font-size: 20px;font-weight: 600;margin-top: 0px;margin-right: 0px;margin-bottom: 0px;margin-left: 0px;line-height: 1;">Vessel Name</span>
                                        <br>
                                        <span style="margin-top: 0px;margin-right: 0px;margin-bottom: 0px;margin-left: 0px;">PNR No.: 1234567890</span>
                                    </td>';
                                $html.='</tr>';
                            $html.='</table>';               
                        $html.='</td>';
                    $html.='</tr>';
                $html.='</table>';               

            $html .='</td>';
        $html .='</tr>';


        $html .='<tr>';
            $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';

                $html .='<table cellpadding="0" cellspacing="5" style="border-collapse: collapse;">';
                    $html .='<tr>';
                        $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';
                            $html .='<table cellpadding="0" cellspacing="-10" valign="center" style="border-collapse: collapse;">';
                                $html.='<tr>';
                                    $html.='<td align="left" width="3%" style="vertical-align:middle;"></td>';
                                    $html.='<td align="left" width="47%" style="vertical-align:middle;">';
                                        $html.='<span style="color: #9e9d9d;">Passengers:</span>1 Passengers';
                                    $html.='</td>';
                                    $html.='<td align="right" width="47%" style="vertical-align:middle;">';
                                        
                                            $html.='<sup style="color: #9e9d9d;margin:0;vertical-align:middle;font-size:12px;">Vehicle:</sup>';
                                            $html.='<sup>Truck</sup>';
                                        
                                    $html.='</td>';
                                    $html.='<td align="left" width="3%" style="vertical-align:middle;"></td>';
                                $html.='</tr>';
                            $html.='</table>';  
                        $html.='</td>';
                    $html.='</tr>'; 
                    
                 

                        $html .='<tr>';
                            $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';
                    
                                $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;">';
                                    $html.='<thead style="background-color: #f6f6f6;">';
                                        $html.='<tr style="background-color: #f6f6f6;">
                                            <th align="left" width="8%" style="vertical-align:middle;">Sr No.</th>
                                            <th align="left" width="26%" style="vertical-align:middle;">Vehicle</th>
                                            <th align="left" width="26%" style="vertical-align:middle;">Vehicle Name</th>
                                            <th align="left" width="20%" style="vertical-align:middle;">Vehicle No.</th>
                                            <th align="left" width="20%" style="vertical-align:middle;">Status</th>
                                        </tr>';
                                    $html.='</thead>';
                                    $html.='<tbody>';
                                        $html.='<tr>
                                            <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">1</td>
                                            <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">Truck</td>
                                            <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">Eicher</td>
                                            <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">GJ051125</td>';

                                            $html.='<td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">';
                                            $html.='<span style="color: #28a745;">Success</span>';
                                            $html.='</td>
                                        </tr>';
                                    $html.='</tbody>';
                                $html.='</table>';               
                    
                            $html .='</td>';
                        $html .='</tr>';

                

                $html.='</table>'; 
            $html .='</td>';
        $html .='</tr>';

        
        $html .='<tr>';
            $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';

                $html .='<table width="100%" cellpadding="0" cellspacing="5" style="border-collapse: collapse;">';
                    $html .='<tr>';
                        $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';

                            $html .='<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;border: 1px solid #ddd;width:100%;">';
                                $html .='<tr>';

                                    // Sub Total
                                    $html.='<td align="left" style="vertical-align:middle;width:33.333%;">';
                                        $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #ddd;width:100%;">';
                                            $html.='<tbody>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #FFF;font-size:11px">Seat Fare</td>
                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #FFF;font-size:11px">Rs. 1</td>
                                                </tr>';
                                            $html.='</tbody>';
                                        $html.='</table>';  
                                    $html.='</td>';
                                    $html.='<td align="left" style="vertical-align:middle;width:33.333%;">';
                                            $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #ddd;width:100%;">';
                                                $html.='<tbody>';
                                                    $html.='<tr>
                                                        <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px;width:65%;">Vehicle Charges';
                                                                                                         
                                                        $html.='</td>
                                                        <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px;width:35%;">Rs. 2</td>
                                                    </tr>';
                                                $html.='</tbody>';
                                            $html.='</table>';  
                                        $html.='</td>';
                                        $html.='<td align="left" style="vertical-align:middle;width:33.333%;">';
                
                                            $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: none;width:100%;">';
                                                $html.='<tbody>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Add-On Amount</td>
                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Rs. 3</td>
                                                </tr>';
                                                $html.='</tbody>';
                                            $html.='</table>';  
                                        $html.='</td>';
                                    $html .='</tr>';
                                    $html .='<tr>';

                                        
                                        
                                            $html.='<td align="left" style="vertical-align:middle;width:33.333%;">';
                        
                                                        $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #ddd;width:100%;">';
                                                            $html.='<tbody>';
                                                            
                                                                $html.='<tr>
                                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Fee & Surcharges</td>
                                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Rs. 4</td>
                                                                </tr>';
                                                            
                                                        $html.='</tbody>';
                                                    $html.='</table>';  
                                            $html.='</td>';
                                       
                                        
                                        
                                        
                                    $html .='</tr>';
                                    $html .='<tr>';
                                        $html.='<td align="right" style="vertical-align:middle;width:33.333%;">';
                        
                                      
                                        $html.='</td>';
                                        $html.='<td align="right" style="vertical-align:middle;width:33.333%;">';
                        
                                            
                                        $html.='</td>';
                                        $html.='<td align="right" style="vertical-align:middle;width:33.333%;">';
                        
                                            $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #ddd;width:100%;">';
                                                $html.='<tbody>';

                                                        $html.='<tr>
                                                            <td align="left" style="vertical-align:middle;font-size:11px"><b>Total Amount</b></td>
                                                            <td align="right" style="vertical-align:middle;font-size:11px"><b>Rs. 10</b></td>
                                                        </tr>';
                                                $html.='</tbody>';
                                            $html.='</table>';  
                                        $html.='</td>';

                                        
                                       
                                $html.='</tr>';



                            $html.='</table>'; 
                        $html.='</td>';
                        
                    $html.='</tr>';


                    
                $html.='</table>';  
            $html .='</td>';
        $html .='</tr>';
  
   

    $html .='</table>'; // first main table close
*/
?>

<?php


// echo $html;
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


// reset pointer to the last page
//$pdf->lastPage();


//ob_clean();
//ob_end_clean();
$pdf->Output();

// if($type == 'view')
// {
//     //ob_clean();
//     $pdf->Output();
// }
// else 
// {
	
// }

//============================================================+
// END OF FILE
//============================================================+
?>