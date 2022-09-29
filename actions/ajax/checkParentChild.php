<?php
include("../../includes/config.php");

if(isset($_POST)){     
    $newIdsArr = $_POST['ids'];
    $db->where('id', $newIdsArr);
    $idsSize = sizeof($newIdsArr);
    $newArr = array_map(function($val) { return explode('_',$val)[0]; }, $newIdsArr);    
    $ids = join("','",$newArr); 
    $hwData = $db->rawQuery("SELECT P1.UserId, P1.Weight, P1.Id, P1.Height FROM `pg_stats` as P1 INNER JOIN ( SELECT DISTINCT UserId, Id FROM `pg_stats` WHERE `UserId` IN ('$ids') AND (`Height` != '' OR `Weight` != '') ORDER BY Id DESC) P2 ON P1.Id = P2.Id GROUP BY P1.UserId");
    echo json_encode($hwData);
}

?>