<?php
include("../../includes/config.php");

$notes_user_id = !empty($_REQUEST['notes_user_id']) ? $_REQUEST['notes_user_id'] : 0;
$notes_coach_id = !empty($_REQUEST['notes_coach_id']) ? $_REQUEST['notes_coach_id'] : 0;
$video_id = !empty($_REQUEST['video_id']) ? $_REQUEST['video_id'] : 0;

$status_note = !empty($_REQUEST['status_note']) ? $_REQUEST['status_note'] : '';
$mal_note = !empty($_REQUEST['mal_note']) ? $_REQUEST['mal_note'] : '';
$obstacles_note = !empty($_REQUEST['obstacles_note']) ? $_REQUEST['obstacles_note'] : '';
$motivation_note = !empty($_REQUEST['motivation_note']) ? $_REQUEST['motivation_note'] : '';
$pattern_break_note = !empty($_REQUEST['pattern_break_note']) ? $_REQUEST['pattern_break_note'] : '';
$new_alternative_note = !empty($_REQUEST['new_alternative_note']) ? $_REQUEST['new_alternative_note'] : '';
$good_ending_note = !empty($_REQUEST['good_ending_note']) ? $_REQUEST['good_ending_note'] : '';


if(!empty($notes_user_id) && !empty($video_id)){

    $db->where('user_id', $notes_user_id);
    $db->where('coach_id', $notes_coach_id);
    $db->where('video_id', $video_id);
    $isNotesExists = $db->getOne('coach_training_video_notes');

    if(empty($isNotesExists)){
        //Insert
        $insertAry = array(
            "user_id" => $notes_user_id,
            "coach_id" => $notes_coach_id,
            "video_id" => $video_id,
            "notes_from" => $notes_user_id,
            "status_note" => $status_note,
            "mal_note" => $mal_note,
            "obstacles_note" => $obstacles_note,
            "motivation_note" => $motivation_note,
            "pattern_break_note" => $pattern_break_note,
            "new_alternative_note" => $new_alternative_note,
            "good_ending_note" => $good_ending_note,
            "created_at" => $db->now(),
            "created_by" => $notes_user_id
        );
        $db->insert('coach_training_video_notes', $insertAry);
    }else{
        //update
        $updateAry = array(
            "notes_from" => $notes_user_id,
            "status_note" => $status_note,
            "mal_note" => $mal_note,
            "obstacles_note" => $obstacles_note,
            "motivation_note" => $motivation_note,
            "pattern_break_note" => $pattern_break_note,
            "new_alternative_note" => $new_alternative_note,
            "good_ending_note" => $good_ending_note,
            "updated_at" => $db->now(),
            "updated_by" => $notes_user_id
        );
        $db->where('id', $isNotesExists['id']);
        $db->update("coach_training_video_notes", $updateAry);
    }
    echo 1;
} else {
    echo 0;
}


?>