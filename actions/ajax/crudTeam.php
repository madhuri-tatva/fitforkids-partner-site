<?php
include("../../includes/config.php");



if(isset($_POST)){

    if($_POST['Action'] == 1){

        // CREATE

        $data = array(
            "Name"          => $_POST['Name'],
            "UserId"        => $_POST['LeaderId'],
            "Trainers"      => $_POST['Trainers'],
            "CreateDate"    => $db->now()
        );

        $db->insert('teams', $data);

        exit;

    }elseif($_POST['Action'] == 2){

        // UPDATE

        $data = array(
            "Name"          => $_POST['Name'],
            "UserId"        => $_POST['LeaderId'],
            "Trainers"      => $_POST['Trainers'],
            "CreateDate"    => $db->now()
        );

        $db->where('Id',$_POST['TeamId']);
        $db->update('teams', $data);
            
        exit;

    }elseif($_POST['Action'] == 3){

        // DELETE
        exit;

    }

}

?>