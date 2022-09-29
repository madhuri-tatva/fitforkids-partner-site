<?php
include("../../includes/config.php");



if(isset($_POST)){

    if($_POST['Action'] == 1){

        // CREATE

        $data = array(
            "UserId"            => $_POST['UserId'],
            "FamilyId"          => $_POST['FamilyId'],
            "Date"              => date('Y-m-d'),
            "Note"              => $_POST['Note'],
            "CreateDate"        => $db->now()
        );

        $db->insert('counseling', $data);

        exit;

    }elseif($_POST['Action'] == 2){

        // UPDATE



        if(!empty($_POST['Date'])){

            $date = date('Y-m-d',strtotime(strtr($_POST['Date'], '/', '-')));

        }else{

            $date = date('Y-m-d');

        }

        //$date = date('Y-m-d H:i:s',strtotime($_POST['Date']));

        $data = array(
            "UserId"            => $_POST['UserId'],
            "FamilyId"          => $_POST['FamilyId'],
            "Note"              => $_POST['Note'],
            "Date"              => $date
        );

        $db->where('Id',$_POST['StatId']);
        $db->update('counseling', $data);
            
        exit;

    }elseif($_POST['Action'] == 3){

        // DELETE
        exit;

    }

}

?>