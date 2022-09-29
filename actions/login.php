<?php
include("../includes/config.php");


if(!empty($_COOKIE['visitor'])){
	$cookie = $_COOKIE['visitor'];
}else{
	$cookie = '';
}

$_SESSION['errormsg'] = FALSE;
if(isset($_REQUEST['access'])){

	loginactioncustomersilent($_REQUEST['access'], $cookie);

}else{

	if(isset($_REQUEST['username']) && isset($_REQUEST['password'])){
	    $username 	= trim($_REQUEST['username']);
	    $password 	= $_REQUEST['password'];

	    $username = strtolower($username);

	    loginactioncustomer($username, $password, 'dashboard', $cookie);
	}else{
	    $_SESSION['errormsg'] = 'error0';
	    //header("Location: /login");
		//error 0 username and pass not sent
		//error 1 username and pass combi not exist
		//error 2 user cant access site without login
	}

}

exit;
?>