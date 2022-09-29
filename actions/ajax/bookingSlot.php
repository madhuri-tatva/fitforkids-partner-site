<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("../../includes/config.php");

    $current_Date = date("Y-m-d");   
    $db->where('user_id',$_SESSION['UserId']);
    $db->where('start', $current_Date, '>=');
    $data = $db->get('booking_slot');
  
    // print_r($data);
    echo json_encode($data);
    exit;
?>