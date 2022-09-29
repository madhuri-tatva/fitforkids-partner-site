<?php
include("../../includes/config.php");

$value = !empty($_REQUEST['value']) ? $_REQUEST['value'] : '';
$coachee_id = !empty($_REQUEST['coachee_id']) ? $_REQUEST['coachee_id'] : '';
$type = $_REQUEST['type'];

if(!empty($value)){
    if(!empty($type)){
        // for coachee
        $db->where('user_id',$_SESSION['UserId']);
        $db->where('coachee_id', $coachee_id);
        $db->where('coach_id', $_SESSION['UserId']);
        $isExists = $db->get('coach_notes');

        if(!empty($isExists)){
            $updateAry = array(
                "notes" => $value,
                'updated_at' => $db->now(),
                'updated_by' => $_SESSION['UserId']
            );
            $db->where('user_id',$_SESSION['UserId']);
            $db->where('coachee_id', $coachee_id);
            $db->where('coach_id', $_SESSION['UserId']);
            $db->update('coach_notes', $updateAry);
            echo 'success';
        }else{
            $insertAry = array(
                "coach_id" => $_SESSION['UserId'],
                "user_id" => $_SESSION['UserId'],
                "coachee_id" => $coachee_id,
                "notes" => $value,
                "created_at" => $db->now(),
                'created_by' => $_SESSION['UserId']
            );
            $db->insert('coach_notes', $insertAry);
            echo 'success';
        }

    }else{
        //for coach
        $db->where('user_id',0);
        $db->where('coach_id', $_SESSION['UserId']);
        $db->where('coachee_id', $coachee_id);
        $isExists1 = $db->get('coach_notes');

        if(!empty($isExists1)){
            $updateAry = array(
                "notes" => $value,
                'updated_at' => $db->now(),
                'updated_by' => $_SESSION['UserId']
            );
            $db->where('user_id',0);
            $db->where('coach_id',$_SESSION['UserId']);
            $db->where('coachee_id', $coachee_id);
            $db->update('coach_notes', $updateAry);
            echo 'success';
        }else{
            $insertAry = array(
                "coach_id" => $_SESSION['UserId'],
                "user_id" =>0,
                "coachee_id" =>$coachee_id,
                "notes" => $value,
                "created_at" => $db->now(),
                'created_by' => $_SESSION['UserId']
            );
            $db->insert('coach_notes', $insertAry);
            echo 'success';
        }
    }
}else{echo 'error';}
?>