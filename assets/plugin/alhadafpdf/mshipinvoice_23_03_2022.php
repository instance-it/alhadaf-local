<?php
require_once dirname(__DIR__, 3).'\config\init.php';

//require_once dirname(__DIR__, 3).'\config\apiconfig.php';
ob_start();
// Include the main TCPDF library (search for installation path).

require_once('tcpdf_include.php');

//$id=$_REQUEST['id'];
$id='8D0FC82C-C25E-4955-BB15-21F4D07D8059';
$type = $_REQUEST['type'];


//$time = $_REQUEST['time'];
$date=date('Y-m-d');

$qryord="select oi.id,oi.invoiceno,oi.invoicedate,case when (isnull(pm.address,'')<>'') then pm.address else 'NA' end as member_address,o.totaltaxableamt,o.totaltax,o.totalpaid 
    from tblorderinvoice oi 
    inner join tblorder o on o.id=oi.orderid 
    inner join tblpersonmaster pm on pm.id=o.uid 
    where o.id=:id";      
$ordarray=array(
    ':id'=>$id,
);
$resord=$DB->getmenual($qryord,$ordarray);
$roword=$resord[0];

// $unqorderid="VIVAAH".str_pad($rowmember['id'],6,'0',STR_PAD_LEFT);
// $invoiceid=$rowmember['id'];

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Test');
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
//$pdf->SetMargins(5, 5, 5, 5);	
$pdf->SetMargins(10, 10, 10, true);	
// add a page
$pdf->AddPage();

//$pdf->AddPage('P', 'A4');

$imgurl=$config->getImageurl();


$html ='';


$html .='<table cellpadding="1" cellspacing="5" style="border-collapse: collapse;background-color: #fff;border:1px solid #e8e8e8;font-size:12px;border-radius: 10px;color:#000000;" id="printdata">';
    $html .='<tr>';
        $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';

            $html.='<table cellpadding="1" cellspacing="5" style="border-collapse: collapse;">';
                $html.='<tr>';
                    $html.='<td align="left">';
                        $html.='<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">';
                            $html.='<tr>';

                                $html.='<td align="left" width="30%" style="vertical-align:middle;text-align:left;border-bottom: none;line-height:2;">';
                                    $html.='<span class="barcode-img">
                                                <img src="'.$imgurl.'images/logo.png" width="100" />
                                            </span>';
                                            
                                $html.='</td>';

                                $html.='<td align="center" width="40%" style="vertical-align:middle;text-align:center;border-bottom: none;line-height:2;">
                                    <span style="color: #5785ae;font-size: 28px;font-weight: bold;">INVOICE</span>
                                </td>';

                                $html.='<td align="right" width="30%" style="vertical-align:middle;text-align:right;border-bottom: none;line-height:2;">';
                                    $html.='<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">';
                                        $html.='<tr>';
                                            $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;line-height:1;">
                                                        <span style="color: #08051a;font-size: 10px;">'.$CompanyInfo->getAddress().'</span>
                                                    </td>';
                                        $html.='</tr>';
                                        $html.='<tr>';
                                            $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;line-height:1;">
                                                        <span style="color: #08051a;font-size: 9px;">'.$CompanyInfo->getEmail1().'</span>
                                                    </td>';
                                        $html.='</tr>';
                                        $html.='<tr>';
                                            $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;line-height:1;">
                                                        <span style="color: #08051a;font-size: 9px;">TEL : '.$CompanyInfo->getContact1().'</span>
                                                    </td>';
                                        $html.='</tr>';
                                    $html.='</table>';
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

            $html .='<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;padding-top: 0px;">';
                    $html .='<tr>';
                        $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';
                            $html .='<table cellpadding="0" cellspacing="0" valign="center" style="border-collapse: collapse;">';
                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                        $html.='<span style="color: #5785ae;">INVOICE NO</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                            $html.='<span style="color: #08051a;font-size:12px;">'.$roword['invoiceno'].'</span>';
                                    $html.='</td>';
                                $html.='</tr>';
                            $html.='</table>';  
                        $html.='</td>';
                    $html.='</tr>'; 


                    $html .='<tr>';
                        $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';
                            $html .='<table cellpadding="0" cellspacing="0" valign="center" style="border-collapse: collapse;">';
                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                        $html.='<span style="color: #08051a;">INVOICE DATE</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                            $html.='<span style="color: #08051a;font-size:12px;">'.$roword['invoicedate'].'</span>';
                                    $html.='</td>';
                                $html.='</tr>';
                            $html.='</table>';  
                        $html.='</td>';
                    $html.='</tr>'; 
                

                    $html .='<tr>';
                        $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';
                            $html .='<table cellpadding="0" cellspacing="0" valign="center" style="border-collapse: collapse;">';
                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                        $html.='<span style="color: #08051a;">INVOICE DUE DATE</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                            $html.='<span style="color: #08051a;font-size:12px;">'.$roword['invoicedate'].'</span>';
                                    $html.='</td>';
                                $html.='</tr>';
                            $html.='</table>';  
                        $html.='</td>';
                    $html.='</tr>'; 
                    

                    $html .='<tr>';
                        $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';
                            $html .='<table cellpadding="0" cellspacing="0" valign="center" style="border-collapse: collapse;">';
                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                        $html.='<span style="color: #08051a;">BILL TO ADDRESS</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                            $html.='<span style="color: #08051a;font-size:12px;">'.$roword['member_address'].'</span>';
                                    $html.='</td>';
                                $html.='</tr>';
                            $html.='</table>';  
                        $html.='</td>';
                    $html.='</tr>'; 



                    //Order Detail
                    $qryod="select id,type,itemname,durationname,price,taxable,igsttaxamt,finalprice,igst,isnull(couponamount,0) as couponamount,expirydate,n_expirydate,strvalidityduration,
                        case when (type = 1) then 'Membership' when (type = 2) then 'Packages' when (type = 3) then 'Course' else '' end as typename
                        from tblorderdetail where orderid = :orderid";
                    $odparms = array(
                        ':orderid'=>$id, 
                    );
                    $resod=$DB->getmenual($qryod,$odparms);

                    if(sizeof($resod) > 0)
                    {
                        $html .='<tr>';
                            $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';
                    
                                $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;">';
                                    $html.='<thead style="background-color: #f6f6f6;">';
                                        $html.='<tr style="background-color: #f6f6f6;">
                                            <th align="left" width="45%" style="vertical-align:middle;">Description</th>
                                            <th align="right" width="20%" style="vertical-align:middle;">Unit Cost</th>
                                            <th align="center" width="15%" style="vertical-align:middle;">Qty</th>
                                            <th align="right" width="20%" style="vertical-align:middle;">Amount</th>
                                        </tr>';
                                    $html.='</thead>';
                                    $html.='<tbody>';
                                    for($i=0;$i<sizeof($resod);$i++)
							        {
                                        $rowod=$resod[$i];

                                        $html.='<tr>
                                            <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;">'.$rowod['itemname'].'<p style="font-size: 9px;color: #666666;">Validity : '.$rowod['strvalidityduration'].'</p></td>
                                            <td align="right" style="vertical-align:middle;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;">'.round($rowod['taxable'],2).'</td>
                                            <td align="center" style="vertical-align:middle;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;">1</td>
                                            <td align="right" style="vertical-align:middle;border-bottom: 1px solid #ddd;">'.round($rowod['taxable'],2).'</td>
                                        </tr>';
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
                                $html.='<td align="left" style="vertical-align:middle;width:65%;">';

                                    $qrypterm="select * from tblwebcontentdetail where contenttypeid=:contenttypeid AND type = 3 order by (case when (displayorder>0) then displayorder else 99999 end)";
                                    $ptermparms = array(
                                        ':contenttypeid'=>$config->getInvoiceTermsConditionsId(),
                                    );
                                    $respterm=$DB->getmenual($qrypterm,$ptermparms);
                                    if(sizeof($respterm)>0)
                                    {	
                                        $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #ddd;width:100%;">';
                                            $html.='<tbody>';
                                                $html.='<tr>
                                                        <td align="left" style="vertical-align:middle;border-bottom: 1px solid #FFF;font-size:11px">TERMS & CONDITIONS</td>
                                                    </tr>';
                                                    for($i=0;$i<sizeof($respterm);$i++)
                                                    {
                                                        $rowpterm=$respterm[$i];

                                                        $html.='<tr>
                                                            <td align="left" style="vertical-align:middle;border-bottom: 1px solid #FFF;font-size:9px">'.$rowpterm['invtnc'].'</td>
                                                        </tr>';
                                                    }
                                            $html.='</tbody>';
                                        $html.='</table>'; 
                                    }
                                    


                                $html.='</td>';
                                $html.='<td align="left" style="vertical-align:middle;width:35%;">';
                                        $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-left: 1px solid #ddd;border-bottom: 2px solid #ddd;width:100%;">';
                                            $html.='<tbody>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px;width:65%;">Sub Total</td>
                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px;width:35%;">'.round($roword['totaltaxableamt'],3).'</td>
                                                </tr>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px;width:65%;">Tax</td>
                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px;width:35%;">'.round($roword['totaltax'],3).'</td>
                                                </tr>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px;width:65%;"><b>Total</b></td>
                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #fff;font-size:11px;width:35%;"><b>'.round($roword['totalpaid'],3).'</b></td>
                                                </tr>';
                                            $html.='</tbody>';
                                        $html.='</table>';  
                                    $html.='</td>';
                                    
                                $html .='</tr>';
                                
                                $html .='<tr>';
                                    $html.='<td align="left" style="vertical-align:middle;width:100%;">';
                                        $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-right: 1px solid #ddd;width:100%;">';
                                            $html.='<tbody>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #FFF;font-size:11px">Thank you for your business</td>
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



// echo $html;
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


// reset pointer to the last page
$pdf->lastPage();


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