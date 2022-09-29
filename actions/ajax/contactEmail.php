<?php
include("../../includes/config.php");

if(isset($_POST)){      
    $db->where('id',$_SESSION['UserId']);
    $user = $db->getOne('users');
    $array = array(
        "firstname" =>  $_SESSION['Firstname'],
        "lastname" => $_SESSION['Lastname'],
        "emailuser"     => $_SESSION['Email'],
        "email" => CONTACT_EMAIL,
        "phonenumber" => ($user['PhoneNumber'] != 0)? $user['PhoneNumber'] : '-',
    );
    send_mail('da_DK',"Kontakt Email", $array);   
    exit;
}

?>