<?php
include("../../includes/config.php");

if(isset($_POST)){

    $data = array(
        "Active"        => 1,
        "Gender"        => $_POST['Gender'],
        "Adult"         => $_POST['Adult'],
        "Age"           => $_POST['Age'],
        "Region"        => $_POST['Region'],
        "Municipality"  => $_POST['Municipality'],
        "Team"          => $_POST['Team'],
        "Gender2"        => $_POST['Gender2'],
        "Adult2"         => $_POST['Adult2'],
        "Age2"           => $_POST['Age2'],
        "Region2"        => $_POST['Region2'],
        "Municipality2"  => $_POST['Municipality2'],
        "Team2"          => $_POST['Team2'],
        "Category"       => $_POST['Category'],
        "DateFrom"       => date('Y-m-d',strtotime(strtr($_POST['DateFrom'], '/', '-'))),
        "DateTo"         => date('Y-m-d',strtotime(strtr($_POST['DateTo'], '/', '-'))),
        "CreateDate"     => $db->now()
    );
    
    $db->insert('battle', $data);

    exit;

}

?>