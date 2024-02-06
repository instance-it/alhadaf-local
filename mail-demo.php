<?php require_once 'config/init.php'; 


$email_body = 'test';
$emailto='ravi.busa@instanceit.com';	
$subject='test';
$data=date('Y-m-d H:i:s');

// $aa=$DB->sendemail($emailto,$subject,$data,'','','','Credai','');
// print_r($aa);
// exit;


$emailstatus = 0;
$errormsg = '';

$qrysett = "SELECT * FROM tblsmtpconfig ";
$settparams = array();
$ressett=$DB->getmenual($qrysett, $settparams);
$numsett=sizeof($ressett);
if($numsett > 0)
{
    $rowsett=$ressett[0];

    $mailhost=$rowsett['hostname'];
    $mailPort=$rowsett['port'];
    $mailusername=$rowsett['emailid'];
    $mailpassword=$rowsett['password'];
    $mailemail=$rowsett['replyemailid'];
    $mailsendername=$rowsett['name'];


    // $mailhost='smtppro.zoho.com';
    // $mailPort='587';
    // $mailusername='noreply@e-smartfitness.com';
    // $mailpassword='Sfit@2018';
    // $mailemail='noreply@e-smartfitness.com';
    // $mailsendername='DG Sea';

    // $mailhost='smtppro.zoho.in';
    // $mailPort='587';
    // $mailusername='noreply@dgferry.com';
    // $mailpassword='7ptwuYgvyNfz'; 
    // $mailemail='noreply@dgferry.com';
    // $mailsendername='Alhadaf Shooting Range';

    // $mailhost='smtp-relay.sendinblue.com';
    // $mailPort='587';
    // $mailusername='credai365@credaichennai.in';
    // $mailpassword='xsmtpsib-5af74441a28a6e57a705d4d73976d054334d927b96a51c1c3d27c64424e9f231-8KPAOd25t13XcH4J'; 
    // $mailemail='credai365@credaichennai.in';
    // $mailsendername='Credai Build Mart';

    //Ispl@2101
//7ptwuYgvyNfz

    if($sendername){
        $mailsendername=$sendername;
    }

    if($data)
    {
        $email_body = $data;
        
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 4;
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        //Set the hostname of the mail server
        $mail->Host = $mailhost;
        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = $mailPort;
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication
        $mail->Username = $mailusername;
        //Password to use for SMTP authentication
        $mail->Password = $mailpassword;

        //Set who the message is to be sent from
        $mail->setFrom($mailemail, $mailsendername);
        //Set an alternative reply-to address
        $mail->addReplyTo($mailemail, $mailsendername);
        
        
        //Set who the message is to be sent to
        $addresses = explode(',',$emailto);
        foreach ($addresses as $address) {
            $mail->AddAddress(trim($address));
        }
        //$mail->addAddress($emailto, '');
        //$mail->addAddress('inquiry@memighty.com', '');

        // bcc
        $bccaddresses = explode(',',$bcc);
        foreach ($bccaddresses as $bccaddress) {
           // $mail->AddBCC(trim($bccaddress));
        }

        // cc 
        $ccaddresses = explode(',',$cc);
        foreach ($ccaddresses as $ccaddress) {
           // $mail->AddCC(trim($ccaddress));
        }
        // $mail->AddCC("abhay4768@gmail.com", "bla");               
        // $mail->AddBCC("instanceit@gmail.com", "test");	
        
        //Set the subject line
        $mail->Subject = $subject;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML($email_body);
        //Replace the plain text body with one created manually
        //$mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
       // $mail->addAttachment($files);

        $sent=$mail->send();
        // if($files)
        // {
        // 	unlink($files);
        // }
        
        if(!$sent) {
            $emailstatus = 0;
            $errormsg = $mail->ErrorInfo;
        }else {
            $emailstatus = 1;
            $errormsg = "Success";
        }
    }	
}

$data = ['emailstatus'=>$emailstatus,'emailerrormsg'=>$errormsg];

print_r($data);

?>
