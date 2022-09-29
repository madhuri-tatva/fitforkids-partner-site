<?php
include("../../includes/config.php");

if(isset($_POST) && !empty($_POST['ThreadId']) && !empty($_POST['value']) && !empty($_SESSION['UserId'])){
        $db->where('Id',$_POST['ThreadId']);
        $thisThread = $db->getOne('thread');

        $threadLikes = explode(',',$thisThread['Likes']);


        $db->where('thread_id',$_POST['ThreadId']);
        $db->where('user_id',$_SESSION['UserId']);
        $userThreadLikes = $db->getOne('user_likes');

        if (!empty($userThreadLikes)) {
            if($userThreadLikes['likes']==$_POST['value']){
                $data = array(
                    "likes" => 0
                );
            }else{
                $data = array(
                    "likes" => $_POST['value']
                );
            }
            $db->where('thread_id',$_POST['ThreadId']);
            $db->where('user_id',$_SESSION['UserId']);
            $db->update('user_likes',$data);
        } else {
            $data =  array(
                'user_id' => $_POST['UserId'],
                'thread_id' => $_POST['ThreadId'],
                'likes' => $_POST['value'],
                'created_at' => $db->now(),
                'updated_at' => $db->now(),
            );
            // echo "<pre>";
            // print_r($data);
            // die;
            $insert_id = $db->insert('user_likes',$data);
            if ($insert_id) {
                echo 'success';
            } else {
                echo 'error';
            }
        }



    exit;

    // if(empty($threadLikes)){
    //     $threadLikesUpdated = $_SESSION['UserId'];
    // }else{

    //     if(in_array($_SESSION['UserId'], $threadLikes)){

    //         // unset($threadLikes[$_SESSION['UserId']]);
    //         if (($key = array_search($_SESSION['UserId'], $threadLikes)) !== false) {
    //             unset($threadLikes[$key]);
    //         }

    //     }else{

    //         $threadLikes[] = $_SESSION['UserId'];

    //     }
    //     // echo 'here1';
    //     // echo '<pre>';
    //     // print_r($threadLikes);
    //     // exit;


    //     $threadLikesUpdated = '';
    //     foreach($threadLikes as $threadLike){

    //         $threadLikesUpdated .= $threadLike . ',';

    //     }

    //     $threadLikesUpdated = substr($threadLikesUpdated, 0, -1);

    // }

    // $data = array(
    //     "Likes" => $threadLikesUpdated
    // );

    // $db->where('Id',$_POST['ThreadId']);
    // $db->update('thread',$data);


    // exit;

}

?>