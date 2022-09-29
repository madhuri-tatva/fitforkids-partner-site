<?php
include('includes/config.php');

$userEmail = 'mjust87@gmail.com';
// $userEmail = 'bhagyesh.koshti@cand-it.dk';
exit;
$db->where('Email', $userEmail);
$userData = $db->getOne('users');
// var_dump($userData);

if (!empty($userData)) {
    $user_id = base64_encode($userData['Id']);
    $link = $basehref . 'verify?token&' . $user_id;

    //Mail
    $arrayMail = array(
        "firstname" => $userData['Firstname'],
        "email" => $userData['Email'],
        "link" => $link
    );
    send_mail("da_DK", "Register", $arrayMail);

    echo $userEmail .' : Email sent. <br/>';
    echo "success";
} else {
    echo 'No user found with this email';
}
?>
