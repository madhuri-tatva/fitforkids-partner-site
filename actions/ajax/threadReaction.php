<?php
include("../../includes/config.php");

if(isset($_POST) && !empty($_POST['ThreadId']) && !empty($_POST['value']) && !empty($_SESSION['UserId'])){
   
    $db->where('thread_id',$_POST['ThreadId']);
    $db->where('user_id',$_SESSION['UserId']);
    $userThreadLikes = $db->getOne('user_likes');
    
    if (!empty($userThreadLikes)) {
        if($userThreadLikes['reactions']==$_POST['value']){
            $data = array(
                "reactions" => NULL
            );
        }else{
            $data = array(
                "reactions" => $_POST['value']
            );
        }    
        $db->where('thread_id',$_POST['ThreadId']);
        $db->where('user_id',$_SESSION['UserId']);
        $db->update('user_likes',$data);
    } else {
        $data =  array(
            'user_id' => $_POST['UserId'],
            'thread_id' => $_POST['ThreadId'],
            'reactions' => $_POST['value'],
            'created_at' => $db->now(),
            'updated_at' => $db->now(),
        );
        $insert_id = $db->insert('user_likes',$data);
        if ($insert_id) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}

?>