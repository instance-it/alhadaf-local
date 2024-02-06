<?php
require_once dirname(__DIR__, 3).'\config\init.php';

//require_once dirname(__DIR__, 3).'\config\apiconfig.php';
ob_start();
// Include the main TCPDF library (search for installation path).

require_once('tcpdf_include.php');

$id=$_REQUEST['id'];
$type = $_REQUEST['type'];
$isshow = $_REQUEST['isshow'];


$date=date('Y-m-d');

$qryord="select distinct so.id,so.orderno as invoiceno,so.orderdate as invoicedate,convert(varchar,so.timestamp,103) as date_1,convert(varchar,so.timestamp,8) as time_1,pm.personname as membername,case when (isnull(pm.address,'')<>'') then pm.address else 'NA' end as member_address,so.totaltaxableamt,so.totaltax,so.totalpaid 
    from tblserviceorder so 
    inner join tblpersonmaster pm on pm.id=so.uid 
    where so.id=:id";      
$ordarray=array(
    ':id'=>$id,
);
$resord=$DB->getmenual($qryord,$ordarray);
$roword=$resord[0];

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Alhadaf Shooting Range');
$pdf->SetTitle($roword['invoiceno']);
$pdf->SetSubject($roword['invoiceno']);
$pdf->SetKeywords('TCPDF, PDF');

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

                                $html.='<td align="left" width="35%" style="vertical-align:middle;text-align:left;border-bottom: none;line-height:2;">';
                                    $html.='<span class="barcode-img">
                                                <img src="'.$imgurl.'images/logo.png" width="80" />
                                            </span>';
                                            
                                $html.='</td>';

                                $html.='<td align="center" width="30%" style="vertical-align:middle;text-align:center;border-bottom: none;line-height:2.5;background-color: #d5d4d2;">
                                    <span style="color: #5785ae;font-size: 30px;font-weight: bold;">INVOICE</span>
                                </td>';

                                $html.='<td align="right" width="35%" style="vertical-align:middle;text-align:right;border-bottom: none;line-height:2;">';
                                    $html.='<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">';
                                        $html.='<tr>';
                                            $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;line-height:1;">
                                                        <span style="color: #5785ae;font-size: 10px;">'.$CompanyInfo->getAddress().'</span>
                                                    </td>';
                                        $html.='</tr>';
                                        $html.='<tr>';
                                            $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;line-height:1;">
                                                        <span style="color: #5785ae;font-size: 9px;">'.$CompanyInfo->getEmail1().'</span>
                                                    </td>';
                                        $html.='</tr>';
                                        $html.='<tr>';
                                            $html.='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px;margin-top:0px; margin-right: 0px; margin-bottom:0px; margin-left: 0px;line-height:1;">
                                                        <span style="color: #5785ae;font-size: 9px;">TEL : '.$CompanyInfo->getContact1().'</span>
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
                                $html.='</td>';
                                $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='</tr>';
                            
                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                        $html.='<span style="color: #5785ae;font-weight: bold;">INVOICE NO</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                            $html.='<span style="color: #5785ae;font-size:12px;font-weight: bold;">'.$roword['invoiceno'].'</span>';
                                    $html.='</td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                        $html.='<span style="color: #5785ae;font-weight: bold;">INVOICE DATE</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                            $html.='<span style="color: #5785ae;font-size:12px;font-weight: bold;">'.$roword['date_1'].' '.$roword['time_1'].'</span>';
                                    $html.='</td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                        $html.='<span style="color: #5785ae;font-weight: bold;">INVOICE DUE DATE</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                            $html.='<span style="color: #5785ae;font-size:12px;font-weight: bold;">'.$roword['date_1'].'</span>';
                                    $html.='</td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                        $html.='<span style="color: #5785ae;font-weight: bold;">CUSTOMER NAME</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                            $html.='<span style="color: #5785ae;font-size:12px;font-weight: bold;">'.$roword['membername'].'</span>';
                                    $html.='</td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                    $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                        $html.='<span style="color: #5785ae;font-weight: bold;">BILL TO ADDRESS</span>';
                                    $html.='</td>';
                                    $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                            $html.='<span style="color: #5785ae;font-size:12px;font-weight: bold;">'.$roword['member_address'].'</span>';
                                    $html.='</td>';
                                $html.='</tr>';

                                $html.='<tr>';
                                $html.='<td align="left" width="40%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='<td align="right" width="60%" style="vertical-align:middle;">';
                                $html.='</td>';
                                $html.='</tr>';

                            
                            $html.='</table>';  
                        $html.='</td>';
                    $html.='</tr>'; 



                    //Order Detail
                    $qryod="select id,itemname,qty,price,taxable,igsttaxamt,finalprice,igst
                        from tblserviceorderdetail where isnull(finalprice,0) > 0 and orderid = :orderid";
                    $odparms = array(
                        ':orderid'=>$id, 
                    );
                    $resod=$DB->getmenual($qryod,$odparms);

                    if(sizeof($resod) > 0)
                    {
                        $html .='<tr>';
                            $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';
                    
                                $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;">';
                                    $html.='<thead style="background-color: #d5d4d2;">';
                                        $html.='<tr style="background-color: #d5d4d2;">
                                            <th align="left" width="45%" style="vertical-align:middle;color:#5785ae;font-size: 14px;font-weight: bold;border-right: 1px solid #ddd;border-left: 1px solid #ddd;">Description</th>
                                            <th align="right" width="20%" style="vertical-align:middle;color:#5785ae;font-size: 14px;font-weight: bold;border-right: 1px solid #ddd;">Unit Cost</th>
                                            <th align="center" width="15%" style="vertical-align:middle;color:#5785ae;font-size: 14px;font-weight: bold;border-right: 1px solid #ddd;">Qty</th>
                                            <th align="right" width="20%" style="vertical-align:middle;color:#5785ae;font-size: 14px;font-weight: bold;border-right: 1px solid #ddd;">Amount</th>
                                        </tr>';
                                    $html.='</thead>';
                                    $html.='<tbody>';
                                    for($i=0;$i<sizeof($resod);$i++)
							        {
                                        $rowod=$resod[$i];

                                        $html.='<tr>
                                            <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;color:#5785ae;border-left: 1px solid #ddd;color:#5785ae;">'.$rowod['itemname'].'</td>
                                            <td align="right" style="vertical-align:middle;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;color:#5785ae;">'.round($rowod['taxable']/$rowod['qty'],2).'</td>
                                            <td align="center" style="vertical-align:middle;border-bottom: 1px solid #ddd;border-right: 1px solid #ddd;color:#5785ae;">'.$rowod['qty'].'</td>
                                            <td align="right" style="vertical-align:middle;border-bottom: 1px solid #ddd;color:#5785ae;border-right: 1px solid #ddd;color:#5785ae;">'.round($rowod['taxable'],2).'</td>
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

            $html .='<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">';
                $html .='<tr>';
                    $html .='<td style="padding-top:0px; padding-right: 0px; padding-bottom:0px; padding-left: 0px; text-align: center;vertical-align:middle;">';

                        $html .='<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;width:100%;">';
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
                                                        <td align="left" style="vertical-align:middle;border-bottom: 1px solid #FFF;font-size:12px;color:#5785ae;font-weight: bold;">TERMS & CONDITIONS</td>
                                                    </tr>';
                                                    $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #FFF;font-size:9px;color:#5785ae;">';
                                                    for($i=0;$i<sizeof($respterm);$i++)
                                                    {
                                                        $rowpterm=$respterm[$i];

                                                        $html.='<p style="line-height: 0.2;">'.$rowpterm['invtnc'].'</p>';
                                                    }
                                                    $html.='</td>
                                                    </tr>';
                                            $html.='</tbody>';
                                        $html.='</table>'; 
                                    }
                                    


                                $html.='</td>';
                                $html.='<td align="left" style="vertical-align:middle;width:35%;">';
                                        $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;border-left: 1px solid #ddd;width:100%;">';
                                            $html.='<tbody>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;font-size:12px;width:65%;color:#5785ae;">Sub Total</td>
                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #ddd;font-size:11px;width:35%;color:#5785ae;">'.round($roword['totaltaxableamt'],3).'</td>
                                                </tr>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;font-size:12px;width:65%;color:#5785ae;">Tax</td>
                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #ddd;font-size:11px;width:35%;color:#5785ae;">'.round($roword['totaltax'],3).'</td>
                                                </tr>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;border-bottom: 1px solid #ddd;font-size:12px;width:65%;color:#5785ae;"><b>Total</b></td>
                                                    <td align="right" style="vertical-align:middle;border-bottom: 1px solid #ddd;font-size:11px;width:35%;color:#5785ae;"><b>'.round($roword['totalpaid'],3).'</b></td>
                                                </tr>';
                                            $html.='</tbody>';
                                        $html.='</table>';  
                                    $html.='</td>';
                                    
                                $html .='</tr>';
                                
                                $html .='<tr>';
                                    $html.='<td align="left" style="vertical-align:middle;width:100%;">';
                                        $html .='<table cellpadding="8" cellspacing="0" style="border-collapse: collapse;width:100%;">';
                                            $html.='<tbody>';
                                                $html.='<tr>
                                                    <td align="left" style="vertical-align:middle;font-size:12px;color: #5785ae;">Thank you for your business</td>
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


$pdf->Output();

if($type == 'view')
{
    $pdf->Output();
}
else 
{
	$invoiceno=$roword['invoiceno'];

    $year = date('Y');
    $month = date('m');
    $time = time();

    $config = new config();

    $imgbaseurl = $config->getImageurl().'uploads/';

    $foldername='serviceorderinvoice';

    if($isshow==1)
    {
        $extradepth='../';
    }
    else
    {
        $extradepth='';
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

    $targetPath = dirname(__DIR__, 3).'/assets/uploads/'.$year.'/'.$month.'/'.$foldername.'/'.$invoiceno.'_'.$time.'.pdf';
    
    $pdfname='uploads/'.$year.'/'.$month.'/'.$foldername.'/'.$invoiceno.'_'.$time.'.pdf';

	$orderinvdata['[pdfurl]']=$pdfname;
    $orderinvupd['[id]']=$roword['id'];
    $DB->executedata('u','tblserviceorder',$orderinvdata,$orderinvupd);

    $pdf->Output($targetPath,'F');

	
	exit();
}

//============================================================+
// END OF FILE
//============================================================+
?>