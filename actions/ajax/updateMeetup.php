<?php 
include("../../includes/config.php");


if($_POST){

	$data = array(
		"Status" 		=> $_POST['Status'],
		"UserId" 		=> $_POST['UserId'],
		"Date" 			=> $db->now(),
		"CreateDate" 	=> $db->now()
	);

	$db->insert('meetup',$data);

}


?>
