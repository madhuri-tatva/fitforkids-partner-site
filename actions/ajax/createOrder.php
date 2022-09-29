<?php
include("../../includes/config.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer;


if(isset($_POST)){

    if($_POST['fieldCurrency'] == ''){
        $currency = 'EUR';
    }else{
        $currency = $_POST['fieldCurrency'];
    }

    $data = array(
        "CustomerId"          => $_POST['fieldClient'],
        "ContactId"           => $_POST['fieldContact'],
        "Campaign"            => $_POST['fieldCampaign'],
        "CampaignDescription" => $_POST['fieldCampaignDescription'],
        "Terms"               => $_POST['fieldTerms'],
        "Format"              => $_POST['fieldRadioFormat'],
        "Language"            => $_POST['fieldRadioLanguage'],
        "ShowClientNo"        => $_POST['showClientNo'],
        "ShowAmount"          => $_POST['showAmount'],
        "ShowComments"        => $_POST['showComments'],
        "Cart"                => json_encode($_POST['quoteCart']),
        "ClientArtNo"         => $_POST['fieldClientArtNo'],
        "CommentTitle"        => $_POST['fieldCommentTitle'],
        "Currency"            => $currency,
        "TotalPrice"          => $_POST['quoteCartTotal'],
        "CreateDate"          => $db->now()
    );

    if($_POST['fieldCartId'] == 'xxxx'){

        $db->insert('orders',$data);
        $orderId = $db->getInsertId();

    }else{

        $db->where('Id',$_POST['fieldCartId']);
        $db->update('orders',$data);
        $orderId = $_POST['fieldCartId'];

    }


    $db->where('Id',$_POST['fieldClient']);
    $customerData = $db->getOne('customers');

    $db->where('Id',$_POST['fieldContact']);
    $contactData = $db->getOne('users');


    //$cartFinal = $_POST['quoteCart'];


    if($_POST['fieldRadioFormat'] == 'csv'){

        // Prepare cart array
        $cartFinal = $_POST['quoteCart'];
        $cartArray = array();
        
        foreach($cartFinal as $key => $item){ 

            $itemArtNo          = explode('::|',$item[0]);
            $itemClientArtNo    = explode('::|',$item[1]);
            $itemDescription    = explode('::|',$item[2]);
            $itemEAN13          = explode('::|',$item[3]);
            $itemPackaging      = explode('::|',$item[4]);
            $itemPhoto          = explode('::|',$item[5]);
            $itemAmount         = explode('::|',$item[6]);
            $itemCurrency       = explode('::|',$item[7]);
            $priceTotal         = explode('::|',$item[8]);
            $itemComments       = explode('::|',$item[9]);

            $cartArray[$key] = array(
                $itemArtNo[1],
                $itemClientArtNo[1],
                $itemDescription[1],
                $itemEAN13[1],
                $itemPackaging[1],
                $itemAmount[1],
                $itemCurrency[1],
                $priceTotal[1],
                $itemComments[1]
            );

        }


        $str1 = array("Æ", "Ø", "Å", "æ", "ø", "å");
        $str2 = array("AE", "OE", "AA", "ae", "oe", "aa");

        //$customerCompany = str_replace($str1, $str2, $customerData['Company']);
        //$customerCity = str_replace($str1, $str2, $customerData['City']);
        //$customerAddress = str_replace($str1, $str2, $customerData['Address']);

        $customerCompany    = $customerData['Company'];
        $customerCity       = $customerData['City'];
        $customerAddress    = $customerData['Address'];


        $array = array(
          array($customerCompany,'','','','','','','',$_POST['fieldCampaign']),
          array($customerAddress,'','','','','','','','Date: ' . date('d-m-Y')),
          array($customerData['Zipcode'] . ' ' . $customerCity,'','','','','','','',''),
          array('Att.: ' . $customerData['Firstname'] . ' ' . $customerData['Lastname'],'','','','','','','',''),
          array('','','','','','','','',''),
          array('No.',$orderId,'','','','','','Your ref.: ',$contactData['Firstname'] . ' '. $contactData['Lastname']),
          array('Date:',date('d-m-Y'),'','','','','','Sales rep.:',$contactData['Firstname'] . ' ' . $contactData['Lastname']),
          array('Client no.:',$customerData['Id'],'','','','','','E-mail:',$contactData['Email']),
          array('','','','','','','','',''),
          array("Art No","Client art. no.","Description","Product bar code
        EAN 13","Packaging","Amount","EUR","Total","Comments")
        );

        $array = array_merge($array,$cartArray);

        $fileCSV = generateCSV($array);


        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'mail.iomweb.dk';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'test@iomweb.dk';
            $mail->Password   = 'Sommer2019!';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('test@iomweb.dk', 'Plast 1 A/S');
            $mail->addAddress($contactData['Email'], $contactData['Firstname']);

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');
            //$mail->addAttachment($filePDF);
            $mail->addStringAttachment($fileCSV,'quotation.csv');


            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Plast 1 A/S | Quotation';
            $mail->Body    = "Dear " . $contactData['Firstname'] . "<br>Thanks for your order. We've attached the quotation in this mail.";
            $mail->AltBody = "Dear " . $contactData['Firstname'] . "<br>Thanks for your order. We've attached the quotation in this mail.";

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }elseif($_POST['fieldRadioFormat'] == 'pdf'){

        $array = array(
            "CustomerId"          => $_POST['fieldClient'],
            "ContactId"           => $_POST['fieldContact'],
            "Campaign"            => $_POST['fieldCampaign'],
            "CampaignDescription" => $_POST['fieldCampaignDescription'],
            "Terms"               => $_POST['fieldTerms'],
            "Format"              => $_POST['fieldRadioFormat'],
            "Language"            => $_POST['fieldRadioLanguage'],
            "ShowClientNo"        => $_POST['showClientNo'],
            "ShowAmount"          => $_POST['showAmount'],
            "ShowComments"        => $_POST['showComments'],
            "Cart"                => json_encode($_POST['quoteCart']),
            "ClientArtNo"         => $_POST['fieldClientArtNo'],
            "CommentTitle"        => $_POST['fieldCommentTitle'],
            "Currency"            => $currency,
            "TotalPrice"          => $_POST['quoteCartTotal'],
            "OrderId"             => $orderId,
            "CreateDate"          => $db->now()
        );

        $filePDF = generatePDF($array);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'mail.iomweb.dk';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'test@iomweb.dk';
            $mail->Password   = 'Sommer2019!';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('test@iomweb.dk', 'Plast 1 A/S');
            $mail->addAddress($contactData['Email'], $contactData['Firstname']);

            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');
            //$mail->addAttachment($filePDF);
            $mail->addStringAttachment($filePDF, 'quotation.pdf');


            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Plast 1 A/S | Quotation';
            $mail->Body    = "Dear " . $contactData['Firstname'] . "<br>Thanks for your order. We've attached the quotation in this mail.";
            $mail->AltBody = "Dear " . $contactData['Firstname'] . "<br>Thanks for your order. We've attached the quotation in this mail.";

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }


    }

    exit;

}

?>