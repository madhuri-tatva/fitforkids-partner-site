<?php
include("../../includes/config.php");

if(isset($_POST)){

	if(isset($_POST['projectUniqueId'])){
		$projectUniqueId = $_POST['projectUniqueId'];
	}else{
		$projectUniqueId = 0;
		exit;
	}

	if(isset($_POST['projectKey'])){
		$projectKey = $_POST['projectKey'];
	}else{
		$projectKey = 0;
		exit;
	}

	if(isset($_POST['action'])){
		$action = $_POST['action'];
	}else{
		$action = 0;
		exit;
	}

	if(isset($_POST['action'])){
		$additional = $_POST['additional'];
	}else{
		$additional = '';
	}

    $data = array(
    	"ProjectId" => $projectUniqueId,
    	"ProjectKey" => $projectKey,
    	"UserId" => $_SESSION['UserId'],
    	"Action" => $action,
    	"AdditionalData" => $additional,
        "CreateDate" => $db->now()
    );

    $db->insert('timeline',$data);

    echo $_POST['projectKey'];
    exit;


}
?>