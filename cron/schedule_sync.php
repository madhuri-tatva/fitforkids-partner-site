<?php
include("../includes/config.php");
global $db;

$path = $_SERVER["DOCUMENT_ROOT"]."/shifts/";

$users = $db->get('users');

$year = date('Y');
$yearfuture = $year + 2;

for($i = $year; $i <= $yearfuture; $i++){

	foreach($users as $user){

		$filename = $user['Id'].$user['Key'];
		$filepath = $path.$i."/".$filename.".json";

		//echo $filepath;

		if (file_exists($filepath)) {
		    echo "File exists";
		} else {
	        $fp = fopen($filepath, 'w');
	        fwrite($fp, json_encode('JSON'));
	        fclose($fp);

		    echo "File doesn't exit. New file has been created";
		}

		echo "<br>";
	}

}

?>