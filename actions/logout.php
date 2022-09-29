<?php
if($_SESSION['Country'] == 'Denmark'){

	if(isset($_COOKIE['accesstoken'])){

		$db->where('Token',$_COOKIE['accesstoken']);
		$db->delete('login');

		unset($_COOKIE['accesstoken']);
	}

	session_destroy();
	header("Location: https://www.plangy.com/login");
}else{

	if(isset($_COOKIE['accesstoken'])){

		$db->where('Token',$_COOKIE['accesstoken']);
		$db->delete('login');

		unset($_COOKIE['accesstoken']);
	}

	session_destroy();
	header("Location: https://www.plangy.com/login");
}
?>