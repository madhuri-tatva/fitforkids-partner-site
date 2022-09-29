<?php
include("../includes/config.php");
if(isset($_REQUEST['username']) && isset($_REQUEST['password'])){
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    loginactioncustomer($username, $password);
}else{
    $_SESSION['errormsg'] = 'error0';
    header("Location:".$basehref."login");
//error 0 username and pass not sent
//error 1 username and pass combi not exist
//error 2 user cant access site without login
//
}
exit;
?>