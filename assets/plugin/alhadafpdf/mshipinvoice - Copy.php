<?php
require_once dirname(__DIR__, 3).'\config\init.php';
require_once dirname(__DIR__, 3).'\config\apiconfig.php';
ob_start();
// Include the main TCPDF library (search for installation path).

require_once('tcpdf_include.php');

$id=$_REQUEST['id'];
$type = $_REQUEST['type'];
$isshow = $_REQUEST['isshow'];


$time = $_REQUEST['time'];
$date=date('Y-m-d');

$qrybooking="SELECT bd.*,pm1.organizationname,isnull(pm.contact,pm1.contact) as agentcontact 
FROM bookingdetails bd 
INNER JOIN tblpersonmaster pm1 on pm1.id = bd.uid
LEFT JOIN tblpersonmaster pm on  CONVERT(varchar(255) ,pm.id) = CONVERT(varchar(255),bd.subagentid)  
WHERE bd.bookedstatus = 1 AND bd.id=:id";
        
$bookingarray=array(
    ':id'=>$id,
);
//  print_r($bookingarray);	
$resbookingdetails=$DB->getmenual($qrybooking,$bookingarray,'BookingInfo');


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
for($i=0;$i<sizeof($resbookingdetails);$i++)
{
    $html .='<table cellpadding="1" cellspacing="5" style="border-collapse: collapse;background-color: #fff;border:1px solid #e8e8e8;font-size:12px;border-radius: 10px;color:#000000;" id="printdata">';
        $html .='<tr>';
            $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';

                $html.='<table cellpadding="1" cellspacing="5" style="border-collapse: collapse;">';
                    $html.='<tr>';
                        $html.='<td align="left">';
                            $html.='<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">';
                                $html.='<tr>';
                                    $html.='<td align="left" width="25%" style="vertical-align:middle;text-align:left;border-bottom: none;line-height:1;">';
                                        $html.='<span class="barcode-img" data-bookingdid="'.$resbookingdetails[$i]->getbookingdetailid().'" data-pnrno="'.$resbookingdetails[$i]->getpnrno().'">
                                                    <span id="barcode'.$resbookingdetails[$i]->getbookingdetailid().'">';
                                                        $params=$pdf->serializeTCPDFtagParameters(array($resbookingdetails[$i]->getpnrno(), 'QRCODE,H', '', '', 25, 25, array(), 'N'));
                                                        $html.= '<tcpdf method="write2DBarcode" params="'.$params.'" /><br>';
                                                $html.='</span>
                                                </span>';

                                                if($resbookingdetails[$i]->getcancelstatus()==1 && $resbookingdetails[$i]->gettotalvalidpassanger()==0 && $resbookingdetails[$i]->gettotalvaliditems()==0)
                                                {
                                                    $html.='<span style="color: #dc3545;">Cancelled</span>';
                                                    
                                                }	
                                                else if($resbookingdetails[$i]->getcancelstatus()==0 && ($resbookingdetails[$i]->gettotalcancelpassanger()>0 || $resbookingdetails[$i]->gettotalcancelitems()>0))
                                                {
                                                    $html.='<span style="color: #ffc107;">Partially Cancelled</span>';
                                                }
                                                else if($resbookingdetails[$i]->getcancelstatus()==0 && $resbookingdetails[$i]->gettotalcancelpassanger()==0 && $resbookingdetails[$i]->gettotalcancelitems()==0)
                                                {
                                                    $html.='<span style="color: #28a745;">Booked</span>';
                                                }
                                                
                                    $html.='</td>';

                                    
                                    $html.='<td align="center" width="50%" style="vertical-align:middle;text-align:center;border-bottom: none;line-height:1;">';
                                        $html.='<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">';
                                            $html.='<tr>';
                                                $html.='<td valign="center" width="30%" style="vertical-align:middle;border: none;">
                                                        <span style="color: #0b2347;font-size: 24px;">From</span>
                                                        <div style="font-size: 12px;font-weight: 500;margin:0;display:block;line-height:1.5;">'.$resbookingdetails[$i]->getFromterminal().' RORO TERMINAL'.'</div>
                                                        <span style="color: #008cff;">'.$resbookingdetails[$i]->getFromtime().'</span>
                                                    </td>';
                                                $html.='<td valign="center" width="40%" style="vertical-align:middle;border: none;">
                                                        <img src="'.$imgurl.'img/ferry.png" width="100px" alt="'.$resbookingdetails[$i]->getFromterminal().' RORO TERMINAL To '.$resbookingdetails[$i]->getToterminal().' RORO TERMINAL">
                                                    </td>';
                                                $html.='<td valign="center" width="30%" style="vertical-align:middle;border: none;">
                                                        <span style="color: #0b2347;font-size: 24px;">To</span>
                                                        <div style="font-size: 12px;font-weight: 500;margin:0;display:block;line-height:1.5;">'.$resbookingdetails[$i]->getToterminal().' RORO TERMINAL</div>';
                                                        // <span style="color: #008cff;">'.$resbookingdetails[$i]->getTotime().'</span>
                                                $html.='</td>';
                                            $html.='</tr>';
                                            $html.='<tr>';
                                                // $html.='<td colspan="3" style="vertical-align:middle;border: none;">
                                                //         <span style="color: #333333;">'.$resbookingdetails[$i]->getFromterminallocation().'</span>
                                                //     </td>';
                                            $html.='</tr>';
                                        $html.='</table>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="25%" style="vertical-align:middle;text-align:right;border-bottom: none;line-height:1;">';
                                        $html.='<table cellpadding="1" cellspacing="0" style="border-collapse: collapse;">';
                                            $html.='<tr>';
                                                $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;">
                                                            <span style="color: #9e9d9d;">Travelling Date</span>
                                                            <br>
                                                            <span style="color: #08051a;">'.$resbookingdetails[$i]->getFormatjourneydate().'</span>
                                                        </td>';
                                            $html.='</tr>';
                                            $html.='<tr>';
                                                $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;">
                                                            <span style="color: #9e9d9d;">Booking Id</span>
                                                            <br>
                                                            <span style="color: #08051a;">'.$resbookingdetails[$i]->gettransactionid().'</span>
                                                        </td>';
                                            $html.='</tr>';
                                            $html.='<tr>';
                                                $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;">
                                                            <span style="color: #9e9d9d;">Booking Date-Time</span>
                                                            <br>
                                                            <span style="color: #08051a;">'.$resbookingdetails[$i]->getentry_date().'</span>
                                                        </td>';
                                            $html.='</tr>';
                                        $html.='</table>';
                                    $html.='</td>';
                                $html.='</tr>';

                                

                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;text-align:left;border-bottom: none;">';
                                            $html.='<span style="color: #9e9d9d;">Payment Ref. No: </span>';
                                            if($resbookingdetails[$i]->getbanktxnid())
                                            {
                                                $html.='<span style="color: #08051a;">'.$resbookingdetails[$i]->getbanktxnid().'</span>';
                                            }
                                            else if($resbookingdetails[$i]->getAgentUniqid())
                                            {
                                                $html.='<span style="color: #08051a;">'.$resbookingdetails[$i]->getAgentUniqid().'</span>';
                                            }
                                            
                                    $html.='</td>';
                                    $html.='<td align="right" width="10%" style="vertical-align:middle;text-align:right;border-bottom: none;"> </td>';
                                    $html.='<td align="right" width="50%" style="vertical-align:middle;text-align:right;border-bottom: none;">';
                                                $html.='<span style="color: #9e9d9d;">Reporting Time: </span>';
                                                $html.='<span style="color: #08051a;">'.$resbookingdetails[$i]->getReportingTime().'</span>';
                                    $html.='</td>';
                                $html.='</tr>';

                                
                                if($resbookingdetails[$i]->getorganizationname() && $resbookingdetails[$i]->getagentcontact())
                                {
                                    $html.='<tr>';
                                        $html.='<td align="left" width="40%" style="vertical-align:middle;text-align:left;border-bottom: none;">';
                                                $html.='<span style="color: #9e9d9d;">Name: </span>';
                                                $html.='<span style="color: #08051a;">'.$resbookingdetails[$i]->getorganizationname().'</span>';
                                        $html.='</td>';
                                        $html.='<td align="right" width="10%" style="vertical-align:middle;text-align:right;border-bottom: none;"> </td>';
                                        $html.='<td align="right" width="50%" style="vertical-align:middle;text-align:right;border-bottom: none;">';
                                                    $html.='<span style="color: #9e9d9d;">Agent Contact: </span>';
                                                    $html.='<span style="color: #08051a;">'.$resbookingdetails[$i]->getagentcontact().'</span>';
                                        $html.='</td>';
                                    $html.='</tr>';
                                }

                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;text-align:left;border-bottom: none;">';
                                            $html.='<span style="color: #9e9d9d;">Contact No: </span>';
                                            $html.='<span style="color: #08051a;">'.$resbookingdetails[$i]->getContactNo().'</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="10%" style="vertical-align:middle;text-align:right;border-bottom: none;"> </td>';
                                    $html.='<td align="right" width="50%" style="vertical-align:middle;text-align:right;border-bottom: none;">';
                                                $html.='<span style="color: #9e9d9d;">Email Id: </span>';
                                                $html.='<span style="color: #08051a;">'.$resbookingdetails[$i]->getEmailId().'</span>';
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
                                        <span style="color: #0b2347;font-size: 20px;font-weight: 600;margin-top: 0px;margin-right: 0px;margin-bottom: 0px;margin-left: 0px;line-height: 1;">'.$resbookingdetails[$i]->getvesselname().'</span>
                                        <br>
                                        <span style="margin-top: 0px;margin-right: 0px;margin-bottom: 0px;margin-left: 0px;">PNR No.: '.$resbookingdetails[$i]->getpnrno().'</span>
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
                                        $html.='<span style="color: #9e9d9d;">Passengers:</span>';
                                        if($resbookingdetails[$i]->getCat_isvehicleallowed()==1)
                                        {
                                            $html.='1 '.$resbookingdetails[$i]->getCategoryname().',';
                                        }
                                        $html.=''.$resbookingdetails[$i]->getTotalnoofpassenger().' Passengers';
                                    $html.='</td>';
                                    $html.='<td align="right" width="47%" style="vertical-align:middle;">';
                                        if($resbookingdetails[$i]->getCat_isvehicleallowed()==1)
                                        {
                                            $html.='<sup style="color: #9e9d9d;margin:0;vertical-align:middle;font-size:12px;">Vehicle:</sup>';
                                            $html.='<sup><img style="margin:0;" src="'.$imgurl.$resbookingdetails[$i]->getcatimg().'" height="20"></sup>';
                                        }
                                        
                                    $html.='</td>';
                                    $html.='<td align="left" width="3%" style="vertical-align:middle;"></td>';
                                $html.='</tr>';
                            $html.='</table>';  
                        $html.='</td>';
                    $html.='</tr>'; 
                    
                    
                    if($resbookingdetails[$i]->getCat_isvehicleallowed()==1)
                    {
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
                                            <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.$resbookingdetails[$i]->getCategoryname().'</td>
                                            <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.$resbookingdetails[$i]->getVehicleName().'</td>
                                            <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.$resbookingdetails[$i]->getVehicleNumber().'</td>';

                                            $html.='<td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">';
                                            if($resbookingdetails[$i]->getcancelstatus()==0)
                                            {
                                                $html.='<span style="color: #28a745;">'.$config->getConfirmedStatus().'</span>';
                                            }
                                            else
                                            {
                                                $html.='<span style="color: #dc3545;">'.$config->getCancelledStatus().'</span><br>('.$resbookingdetails[$i]->getcancelreason().')';
                                            }

                                            $html.='</td>
                                        </tr>';
                                    $html.='</tbody>';
                                $html.='</table>';               
                    
                            $html .='</td>';
                        $html .='</tr>';
                    }
                
                    
                    // tblbookingdetails
                    $qrypasstype="SELECT pb.*,isnull(pb.isseatselect,0) as isseatselect,ISNULL(pb.pfinaldiscountamt,0) AS pfinaldiscountamt,ISNULL(pb.ptotalprice,0) AS ptotalprice 
                    FROM tblbookingpassengertype pb 
                    WHERE pb.bookingid=:bookingid AND pb.bookingdetailsid=:bookingdetailsid";
                    $passarray=array(':bookingid'=>$resbookingdetails[$i]->getbookingid(),':bookingdetailsid'=>$resbookingdetails[$i]->getbookingdetailid());
                    // print_r($passarray);
                    $Passengertype=$DB->getmenual($qrypasstype,$passarray,'PassengertypeInfo');
                    if(sizeof($Passengertype)>0)
                    {
                        $html .='<tr>';
                            $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';

                                $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;">';
                                    $html.='<thead style="background-color: #f6f6f6;">';
                                        $html.='<tr style="background-color: #f6f6f6;">
                                            <th align="left" width="8%" style="vertical-align:middle;">Sr No.</th>
                                            <th align="left" width="20%" style="vertical-align:middle;">Passenger</th>
                                            <th align="left" width="15%" style="vertical-align:middle;">Gender</th>
                                            <th align="left" width="6%" style="vertical-align:middle;">Age</th>
                                            <th align="left" width="20%" style="vertical-align:middle;">Class</th>
                                            <th align="left" width="10%" style="vertical-align:middle;">Seat No.</th>';

                                            // if($resbookingdetails[$i]->getIschkpickupdrop() == 1)
                                            // {   
                                            //     $html.='<th align="left" width="10%" style="vertical-align:middle;">Vehicle No.</th>';
                                            // }
                                            
                                            $html.='<th align="left" width="22%" style="vertical-align:middle;">Status</th>
                                        </tr>';
                                    $html.='</thead>';
                                    $html.='<tbody>';
                                    for($p=0;$p<sizeof($Passengertype);$p++)
                                    {
                                        $qrypassdetail="SELECT pbd.*,g.name AS gender,
                                        ISNULL(pbd.iscancel,0) AS cancelstatus,
                                        ISNULL(pbd.seatno,0) AS seatno,
                                        ISNULL(pbd.cancel_reason,'') AS cancelreason,
                                        ISNULL((SELECT COUNT(id) FROM tblbookingpassengerdetails WHERE ISNULL(iscancel,0)=1  AND  bookingid=:bookingid1 AND bookingdetailsid=:bookingdetailsid1 AND bookingpasstypeid=:bookingpasstypeid1),0) AS cancelpassanger,
                                        isnull(pbd.isseatconfirm,1) as isseatconfirm 
                                        FROM tblbookingpassengerdetails pbd 
                                        INNER JOIN tblgender g ON g.id=pbd.passengergenderid
                                        WHERE pbd.bookingid=:bookingid AND pbd.bookingdetailsid=:bookingdetailsid AND pbd.bookingpasstypeid=:bookingpasstypeid";
                                        $passdarray=array(
                                                ':bookingid'=>$resbookingdetails[$i]->getbookingid(),
                                                ':bookingdetailsid'=>$resbookingdetails[$i]->getbookingdetailid(),
                                                ':bookingpasstypeid'=>$Passengertype[$p]->getId(),
                                                ':bookingid1'=>$resbookingdetails[$i]->getbookingid(),
                                                ':bookingdetailsid1'=>$resbookingdetails[$i]->getbookingdetailid(),
                                                ':bookingpasstypeid1'=>$Passengertype[$p]->getId(),
                                            );
                                            // print_r($passdarray);
                                    
                                        $Passengertypedetail=$DB->getmenual($qrypassdetail,$passdarray,'PassengerDetailInfo');	
                                        
                                        if(sizeof($Passengertypedetail)>0)
                                        {
                                            $intit=1;
                                            for($pd=0;$pd<sizeof($Passengertypedetail);$pd++)
                                            {
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.$intit.'</td>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.$Passengertypedetail[$pd]->getPassengerFirstName().' '.$Passengertypedetail[$pd]->getPassengerLastName().'</td>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.$Passengertypedetail[$pd]->getgender().' / '.$Passengertypedetail[$pd]->getPassengerType().'</td>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.$Passengertypedetail[$pd]->getPassengerAge().'</td>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.$Passengertypedetail[$pd]->getPassengerClass().'</td>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.$Passengertypedetail[$pd]->getPassengerSeatPrefix().$Passengertypedetail[$pd]->getPassengerSeatNo().'</td>';
                                                    
                                                    // if($resbookingdetails[$i]->getIschkpickupdrop() == 1)
                                                    // {
                                                    //     $html.='<td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.$Passengertypedetail[$pd]->getPassengerVehicleSeatPrefix().$Passengertypedetail[$pd]->getPassengerVehicleSeatNo().'</td>';
                                                    // }

                                                    if($Passengertypedetail[$pd]->getcancelstatus()==0)
                                                    {
                                                        $html.='<td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;"><span style="color: #28a745;">'.$config->getConfirmedStatus().'</span></td>';
                                                    }
                                                    else
                                                    {
                                                        $html.='<td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;"><span style="color: #dc3545;">'.$config->getCancelledStatus().'</span><span> ('.$Passengertypedetail[$pd]->getcancelreason().')</span></td>';
                                                    }
                                                    
                                                    
                                                $html.='</tr>';
                                            }
                                        }
                                    }
                                    $html.='</tbody>';
                                $html.='</table>';               

                            $html .='</td>';
                        $html .='</tr>';
                    }
                

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
                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #FFF;font-size:11px">Rs. '.$IISMethods->ind_format($resbookingdetails[$i]->gettotal_pfinalpriceafterdiscount()).'</td>
                                                </tr>';
                                            $html.='</tbody>';
                                        $html.='</table>';  
                                    $html.='</td>';
                                    $html.='<td align="left" style="vertical-align:middle;width:33.333%;">';
                                            $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #ddd;width:100%;">';
                                                $html.='<tbody>';
                                                    $html.='<tr>
                                                        <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px;width:65%;">Vehicle Charges';
                                                        if($resbookingdetails[$i]->getextraweight() > 0)
                                                        {
                                                            $html.='<br><small>(Extra Ton Price : '.$resbookingdetails[$i]->getextraweight().'x'.$resbookingdetails[$i]->getextratonprice()/$resbookingdetails[$i]->getextraweight().'='.$resbookingdetails[$i]->getextratonprice().')</small><br><small>inc. in Vehicle Charges</small>';
                                                        }                                                        
                                                        $html.='</td>
                                                        <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px;width:35%;">Rs. '.$IISMethods->ind_format($resbookingdetails[$i]->gettotal_cfinalpriceafterdiscount()).'</td>
                                                    </tr>';
                                                $html.='</tbody>';
                                            $html.='</table>';  
                                        $html.='</td>';
                                        $html.='<td align="left" style="vertical-align:middle;width:33.333%;">';
                
                                            $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: none;width:100%;">';
                                                $html.='<tbody>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Add-On Amount</td>
                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Rs. '.$IISMethods->ind_format($resbookingdetails[$i]->gettotal_addonfinalpriceafterdiscount()).'</td>
                                                </tr>';
                                                $html.='</tbody>';
                                            $html.='</table>';  
                                        $html.='</td>';
                                    $html .='</tr>';
                                    $html .='<tr>';

                                        if($resbookingdetails[$i]->getIschkpickupdrop() == 1)
                                        {
                                            $html.='<td align="left" style="vertical-align:middle;width:33.333%;">';
                
                                                $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #ddd;width:100%;">';
                                                    $html.='<tbody>';
                                                    
                                                            if($resbookingdetails[$i]->getIschkpickupdrop() == 1)
                                                            {
                                                                $html.='<tr>
                                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Bus Fare</td>
                                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Rs. '.$IISMethods->ind_format($resbookingdetails[$i]->getTotalBusFare()).'</td>
                                                                </tr>';
                                                            }

                                                        $html.='</tbody>';
                                                    $html.='</table>';  
                                            $html.='</td>';
                                        }
                                        
                                        if($IISMethods->ind_format($resbookingdetails[$i]->getFeesurcharge()) > 0)
                                        {
                                            $html.='<td align="left" style="vertical-align:middle;width:33.333%;">';
                        
                                                        $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #ddd;width:100%;">';
                                                            $html.='<tbody>';
                                                            if($IISMethods->ind_format($resbookingdetails[$i]->getFeesurcharge()) > 0)
                                                            {
                                                                $html.='<tr>
                                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Fee & Surcharges</td>
                                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Rs. '.$IISMethods->ind_format($resbookingdetails[$i]->getFeesurcharge()).'</td>
                                                                </tr>';
                                                            }

                                                        $html.='</tbody>';
                                                    $html.='</table>';  
                                            $html.='</td>';
                                        }
                                        
                                        if($resbookingdetails[$i]->getcancellationapply()==1)
                                        {
                                            $html.='<td align="left" style="vertical-align:middle;width:33.333%;">';
                        
                                                        $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: none;width:100%;">';
                                                            $html.='<tbody>';

                                                            if($resbookingdetails[$i]->getcancellationapply()==1)
                                                            {
                                                                $html.='<tr>
                                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Free Cancellation</td>
                                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px">Rs. '.$IISMethods->ind_format($resbookingdetails[$i]->getcancellationcharge()).'</td>
                                                                </tr>';
                                                            }

                                                    $html.='</tbody>';
                                                $html.='</table>';  
                                            $html.='</td>';
                                        }
                                        
                                    $html .='</tr>';
                                    $html .='<tr>';
                                        $html.='<td align="right" style="vertical-align:middle;width:33.333%;">';
                        
                                      
                                        $html.='</td>';
                                        $html.='<td align="right" style="vertical-align:middle;width:33.333%;">';
                        
                                            
                                        $html.='</td>';
                                        $html.='<td align="right" style="vertical-align:middle;width:33.333%;">';
                        
                                            $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #ddd;width:100%;">';
                                                $html.='<tbody>';

                                                        $totalamount = (double) $resbookingdetails[$i]->gettotal_pfinalpriceafterdiscount() + $cancellationcharge + (double)$resbookingdetails[$i]->gettotal_cfinalpriceafterdiscount() + (double)$resbookingdetails[$i]->getTotalBusFare() + (double)$resbookingdetails[$i]->getFeesurcharge() + (double)$resbookingdetails[$i]->gettotal_addonfinalpriceafterdiscount();
                                                    
                                                        $html.='<tr>
                                                            <td align="left" style="vertical-align:middle;font-size:11px"><b>Total Amount</b></td>
                                                            <td align="right" style="vertical-align:middle;font-size:11px"><b>Rs. '.$IISMethods->ind_format($totalamount).'</b></td>
                                                        </tr>';
                                                $html.='</tbody>';
                                            $html.='</table>';  
                                        $html.='</td>';

                                        
                                       
                                $html.='</tr>';

                                $html .='<tr>';
                                        $html.='<td align="right" style="vertical-align:middle;width:33.333%;">';
                        
                                      
                                        $html.='</td>';
                                        
                                       

                                        $Total_Refundable_Amt=(double)$resbookingdetails[$i]->getcrefundamtwithgst() + (double)$resbookingdetails[$i]->getprefundamtwithgst() + (double)$resbookingdetails[$i]->getaddonrefundamtwithgst();
                                        $Total_cancellationcharge = (double)$resbookingdetails[$i]->getccancellationcharge() + (double)$resbookingdetails[$i]->getpcancellationcharge() + (double)$resbookingdetails[$i]->getaddoncancellationcharge();
                                        if($Total_Refundable_Amt>0 )
                                        {
                                            $html.='<td align="right" style="vertical-align:middle;width:33.333%;">';
                                                $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #fff;width:100%;">';
                                                $html.='<tbody>';

                                                
                                                    if($Total_Refundable_Amt>0 )
                                                    {
                                                        $html.='<tr>
                                                            <td align="left" style="vertical-align:middle;width:60%;font-size:11px">Total Cancellation Charge</td>
                                                            <td align="right" style="vertical-align:middle;width:40%;font-size:11px">Rs. '.$IISMethods->ind_format($Total_cancellationcharge).'</td>
                                                        </tr>';
                                                    }

                                                    $html.='</tbody>';
                                                $html.='</table>';   
                                            
                                            $html.='</td>';


                                            $html.='<td align="left" style="vertical-align:middle;width:33.333%;">';
                                                    $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #fff;width:100%;">';
                                                        $html.='<tbody>';


                                                            if($Total_Refundable_Amt>0 )
                                                            {
                                                                $html.='<tr>
                                                                    <td align="left" style="vertical-align:middle;width:60%;font-size:11px"><b>Total Refund Amount</b></td>
                                                                    <td align="right" style="vertical-align:middle;width:40%;font-size:11px"><b>Rs. '.$IISMethods->ind_format($Total_Refundable_Amt).'</b></td>
                                                                </tr>';
                                                            }

                                                    $html.='</tbody>';
                                                $html.='</table>';   
                                            $html.='</td>';
                                        }
                                       
                                $html.='</tr>';


                            $html.='</table>'; 
                        $html.='</td>';
                        
                    $html.='</tr>';


                    $html .='<tr>';
                        // Important Note
                        $html.='<td align="left" style="vertical-align:middle;width:100%;">';
                            $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;">';
                                $html.='<tbody>';
                                    $html.='<tr>';
                                        $html.='<td align="left" style="vertical-align:middle;border: 1px solid #ddd;">';
                                        $html.='<b style="font-size:12px;">Important</b>';
                                        $html.=''.$IISMethods->sanitize($resbookingdetails[$i]->getbookingimportant(),'OUT').'
                                        </td>';
                        
                                $html.='</tr>';
                            $html.='</table>'; 
                        $html.='</td>';
                        
                    $html.='</tr>';
                $html.='</table>';  
            $html .='</td>';
        $html .='</tr>';

        
        $qryitemcategory="SELECT bac.* FROM tblbookingaddoncategory bac WHERE bac.bookingid=:bookingid AND bac.bookingdetailsid=:bookingdetailsid AND ISNULL(bac.iscomplementary,0)=0";
        $itemcatarray=array(
            ':bookingid'=>$resbookingdetails[$i]->getbookingid(),
            ':bookingdetailsid'=>$resbookingdetails[$i]->getbookingdetailid(),
                    );

                    // print_r($itemcatarray);
        $Addoninfo=$DB->getmenual($qryitemcategory,$itemcatarray,'AddOnInfo');
        
        if(sizeof($Addoninfo)>0)
        {
            for($c=0;$c<sizeof($Addoninfo);$c++)
            {
                $html .='<tr>';
                    $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: left;vertical-align:middle;">';
                        $html .='<table cellpadding="1" cellspacing="0" style="border-collapse: collapse;">';
                            // Game Start
                            $html .='<tr >';
                                $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: left;vertical-align:middle;">';

                                    $html .='<table cellpadding="0" cellspacing="10" style="border-collapse: collapse;border-top: 1px dashed #bbbaba;">';
                                        $html.='<thead>';
                                            $html.='<tr>';
                                                $html.='<th colspan="3" align="center" style="vertical-align:middle;"><span style="color: #008cff;">'.$Addoninfo[$c]->getCatName().'</span></th>';
                                            $html.='</tr>';
                                        $html.='</thead>';
                                        $html.='<tbody>';

                                        $qryitems="SELECT bi.*,CONCAT('".$imgurl."',ai.img) AS itemimg 
                                                FROM tblbookingaddonitems bi 
                                                INNER JOIN tblagencyitemmaster ai ON ai.id=bi.itemid
                                                WHERE bi.bookingid=:bookingid AND bi.bookingdetailsid=:bookingdetailsid AND bi.bookingcatid=:bookingcatid AND ISNULL(bi.iscomplementary,0)=0";
                                        $itemarray=array(
                                            ':bookingid'=>$resbookingdetails[$i]->getbookingid(),
                                            ':bookingdetailsid'=>$resbookingdetails[$i]->getbookingdetailid(),
                                            ':bookingcatid'=>$Addoninfo[$c]->getId(),
                                        );
                                        $Itemdetailinfo=$DB->getmenual($qryitems,$itemarray,'ItemDetailInfo');
                                        if(sizeof($Itemdetailinfo)>0)
                                        {	
                                            for($ii=0;$ii<sizeof($Itemdetailinfo);$ii++)
                                            {
                                                
                                                $modulo = $ii % 3;
                                                                
                                                // if(modulo == 0)
                                                // {
                                                //     if(i > 0)
                                                //     {
                                                //         tbldata+='</tr>';
                                                //     }
                                                //     tbldata+='<tr>';
                                                // }
                                                if($modulo == 0)
                                                {
                                                    if($c > 0)
                                                    {
                                                        // $html.='</tr>';
                                                    }
                                                    // $html.='<tr>';
                                                }

                                                $html.='<tr>';
                                                    $html.='<td align="left" style="vertical-align: middle;border: 1px solid #ddd;">';
                                                        $html .='<table cellpadding="0" cellspacing="8" style="border-collapse: collapse;">
                                                            <tr>
                                                                <td align="left" width="25%" style="vertical-align:middle;">
                                                                    <img src="'.$Itemdetailinfo[$ii]->getitemimg().'" width="50px" height="50px" alt="'.$Itemdetailinfo[$ii]->getItemName().'" style="border-radius: 4px;overflow: hidden;box-shadow: 0 0 10px -5px #000;">
                                                                </td>
                                                                <td align="left" width="75%" style="vertical-align:top;">
                                                                    <b style="margin:0;display:block;">'.$Itemdetailinfo[$ii]->getItemName().'</b><br>
                                                                    <span style="color: #ff6600;font-size:16px;display:block:"><small>Rs.</small>  '.$IISMethods->ind_format($Itemdetailinfo[$ii]->gettotalamtafterdiscount(),2).'</span><br>
                                                                    <span style="color: #008cff;font-size:10px;text-align: right;display:block:">Qty: '.$Itemdetailinfo[$ii]->getQty().'</span>
                                                                </td>
                                                            </tr>
                                                        </table>';
                                                    $html.='</td>';
                                                $html.='</tr>';
                                            
                                            }
                                        }

                                            
                                        $html.='</tbody>';
                                    $html.='</table>';                

                                $html .='</td>';
                            $html .='</tr>';
                            // Game End

                        $html .='</table>';
                    $html .='</td>';
                $html .='</tr>';
            }

        }  
   

    $html .='</table>'; // first main table close
}

?>
<script>
    
    var bookingdid=$(this).attr('data-bookingdid');
    var pnrno=$(this).attr('data-pnrno');
    // console.log('#barcode'+bookingdid+'');
    var qrcode = new QRCode(document.getElementById("barcode"+bookingdid+""), 
    {
        width : 100,
        height : 100
    });
    qrcode.makeCode(pnrno);


</script>
<?php


// echo $html;
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


// reset pointer to the last page
$pdf->lastPage();


if($type == 'view')
{
	$pdf->Output('ticketview.pdf', 'FI');
}
else 
{
	$ordno=$resbookingdetails[0]->getpnrno();

    $year = date('Y');
    $month = date('m');

    $config = new config();

    $imgbaseurl = $config->getImageurl().'uploads/';

    $foldername='e-ticket';

    if($isshow==1)
    {
        $extradepth='';
    }
    else
    {
        $extradepth='../';
    }

    if (!file_exists($imgbaseurl.$year)) 
    {
        mkdir($extradepth.'../../assets/uploads/'.$year, 0777, true);
    }

    $imgyearurl = $config->getImageurl().'uploads/'.$year.'/';

    if (!file_exists($imgyearurl.$month)) 
    {
        mkdir($extradepth.'../../assets/uploads/'.$year.'/'.$month, 0777, true);
    }

    $imgmonthurl = $config->getImageurl().'uploads/'.$year.'/'.$month.'/';

    if (!file_exists($imgmonthurl.$foldername)) 
    {
        mkdir($extradepth.'../../assets/uploads/'.$year.'/'.$month.'/'.$foldername, 0777, true);
    }

    // $targetPath = dirname(__DIR__, 3).'assets/uploads/'.$year.'/'.$month.'/'.$foldername.'/'.$ordno.'_'.$time.'.pdf';

    $targetPath = dirname(__DIR__, 3).'/assets/uploads/'.$year.'/'.$month.'/'.$foldername.'/'.$ordno.'_'.$time.'.pdf';
    
    $pdfname='uploads/'.$year.'/'.$month.'/'.$foldername.'/'.$ordno.'_'.$time.'.pdf';

	$bookingdata['[pdf_file]']=$pdfname;
    $bookingupd['[id]']=$id;
    $DB->executedata('u','tblbookingdetails',$bookingdata,$bookingupd);

    $pdf->Output($targetPath,'F');
    
	/* $fname=dirname(__DIR__, 3).'/files/boq/'.$ordno.'_'.$time.'.pdf'; */

	
	exit();
}

//============================================================+
// END OF FILE
//============================================================+
?>