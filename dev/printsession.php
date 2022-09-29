<?php
require "../includes/config.php";

if($_SESSION['Admin'] == 1){
	d($_SESSION);
}else{
	header('Location: /');
}

?>