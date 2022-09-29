<?php 

include('includes/config.php');
// $to = 'testffk@mailinator.com';
// $subject = 'Test Email';
// $message = 'Test message';

// $name = 'Test Name';
// $from = 'ffkpartnersite@mailinator.com';

// $headers = "From:" . $name . "<" . $from . ">\r\n";
// $headers .= "MIME-Version: 1.0\r\n";
// $headers .= "Content-Type: text/html; charset=UTF8\r\n";

// mail($to,$subject,$message,$headers);


$user_id = 1;
$link = $basehref.'verify?token&'.$user_id;

$arrayMail = array(
    "firstname" 	=> 'Demo',
    "lastname" 	=> 'User',
    "email" 		=> 'madhuri.tarapra@tatvasoft.com',
    "link" 		    => $link
);

$db->where('Id',22);
$userData = $db->getOne('users');

$arrayMailAdmin = array(
    "firstname"     => $userData['Firstname'],
    "lastname"      => $userData['Lastname'],
    "organisation"  => $userData['OrganisationName'],
    "country"       => $userData['Region'],            
    "phonenumber"   => $userData['PhoneNumber'],
    "emailuser"     => $userData['Email'],
    "region"        => $userData['Region'],
    "email"         => "madhuri.tarapra@tatvasoft.com"
);

send_mail("da_DK","Register",$arrayMail);
send_mail("da_DK","Ny bruger",$arrayMailAdmin);

?>