<?php
include ("../../includes/config.php");
// echo "<pre>";print_r($_POST);exit;
// echo "<pre>";print_r($_FILES);exit;
if(isset($_GET['id'])){
    $action = $_GET["id"];
    $data = array();
    $resArr = array();
    $result = array();
    if (!empty($action))
    {
        $db->where('Msg_id', $action);
        $db->where('Msg_type', 1);
        $result = $db->get("chat_media");
        foreach($result as $uploadData){
        $data  = array(
            'Type' => $uploadData['Type'],
            'URL' => $uploadData['URL'],
            'FileName' => $uploadData['FileName'],
            'Msg_type' => $uploadData['Msg_type'],
            'Msg_id' => $uploadData['Msg_id'],
            'id' => $uploadData['Id']
        );
        array_push($resArr, $data);
        }

        $resultArr = array (
            'status' => "success",
            'data' => $resArr,
            'uploadType' => 2
        );
        echo json_encode($resultArr);
    }
}
if(isset($_GET['comment_id'])){
    $action = $_GET["comment_id"];
    $data = array();
    $resArr = array();
    $result = array();
    if (!empty($action))
    {
        $db->where('Msg_id', $action);
        $db->where('Msg_type', 2);
        $result = $db->get("chat_media");
        foreach($result as $uploadData){
        $data  = array(
            'Type' => $uploadData['Type'],
            'URL' => $uploadData['URL'],
            'FileName' => $uploadData['FileName'],
            'Msg_type' => $uploadData['Msg_type'],
            'Msg_id' => $uploadData['Msg_id'],
            'id' => $uploadData['Id']
        );
        array_push($resArr, $data);
        }

        $resultArr = array (
            'status' => "success",
            'data' => $resArr,
            'uploadType' => 2
        );
        echo json_encode($resultArr);
    }
}
if(isset($_POST['extra'])){
    $result = array();
    $db->where('Msg_id',"");
    $result = $db->get('chat_media');
    foreach($result as $file){
        $target = $_SERVER['DOCUMENT_ROOT'].$file['URL'];
        if ( file_exists($target) ) {
            // Delete the file
            unlink($target);
        }
    }
    $db->where('Msg_id',"");
    $db->delete('chat_media');
    echo "done";
}
?>