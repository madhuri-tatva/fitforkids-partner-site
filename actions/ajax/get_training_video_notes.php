<?php
include("../../includes/config.php");

$video_id = !empty($_REQUEST['video_id']) ? $_REQUEST['video_id'] : 0;

if(!empty($video_id)){
    
    $db->where('user_id', $_SESSION['UserId']);
    $db->where('coach_id', $_SESSION['coach_id']);
    $db->where('video_id', $video_id);
    $db->orderBy('id','DESC');
    $notesData = $db->getOne('coach_training_video_notes');
 
    if(!empty($notesData)){
        echo json_encode($notesData, true);
    }else{
        echo 0;
    }
} else {
    echo 0;
}


?>