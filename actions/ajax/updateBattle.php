<?php
include("../../includes/config.php");

if(isset($_POST)){

    if($_POST['Action'] == 1){

        $data = array(
            "Active" => 1
        );

        $db->where('Id',$_POST['BattleId']);
        $db->update('battle', $data);

    }elseif($_POST['Action'] == 2){

        $data = array(
            "Active" => 0
        );

        $db->where('Id',$_POST['BattleId']);
        $db->update('battle', $data);

    }elseif($_POST['Action'] == 9){

        $db->where('Id',$_POST['BattleId']);
        $db->delete('battle');

    }


    exit;

}

?>