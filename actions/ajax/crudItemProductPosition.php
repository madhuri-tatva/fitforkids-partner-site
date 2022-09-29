<?php
include("../../includes/config.php");

if(isset($_POST)){

    $positions = $_POST['positions'];

    foreach($positions as $data){

        $dataExploded = explode('_',$data);

        $sqlQuery = array(
            "Position" => $dataExploded[0]
        );

        $db->where('Id',$dataExploded[1]);
        $db->update('products',$sqlQuery);

    }

    exit;

}

?>