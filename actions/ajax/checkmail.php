<?php
include("../../includes/config.php");
$email = trim($_GET['email']);

$mail_exist = usercheck($email);

if($mail_exist == true){
    $mail_exist = 1;
}else{
    $mail_exist = 0;
}

echo $mail_exist;

?>