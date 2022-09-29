<?php 
include("../includes/config.php");


if($_POST) {

    $db->where('UserId',$_SESSION['UserId']);
    $check = $db->getOne('book');

    // Data

    $dataFinal = array();

    $dataFinal['UserId'] = $_SESSION['UserId'];


    foreach($_POST['Data'] as $data){

        $dataItem = explode('||',$data);
        /*$dataItemFinal = array(
            $dataItem[0] => $dataItem[1]
        );*/

        $dataFinal[$dataItem[0]] = $dataItem[1];

    }

    if(!empty($check)){

        $db->where('Id',$check['Id']);
        $db->update('book',$dataFinal);


    }else{

        $dataFinal['CreateDate'] = $db->now();

        $db->insert('book',$dataFinal);

    }

}


?>
