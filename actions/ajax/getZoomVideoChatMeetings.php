<?php
include("../../includes/config.php");

include("../../includes/zoomAPIFunctions.php");

$weekDay = array(
    "Sunday" => "Søndag",
    "Monday" => "Mandag",
    "Tuesday" => "Tirsdag",
    "Wednesday" => "Onsdag",
    "Thursday" => "Torsdag",
    "Friday" => "Fredag",
    "Saturday" => "Lørdag",
);
$firstAnchorLink = $secondAnchorLink = $thirdAnchorLink = $fourthAnchorLink = '';
$meetingData = list_meetings();


if($meetingData == 'Token_Refreshed' ){
    return 'Token_Refreshed';
}else if($meetingData == 'Authentication'){
    return 'Authentication';
}else{
    if(!empty($meetingData) && is_array($meetingData)){
        $liveVideoChatGrp = $db->get("zoom_live_videochat_details");

        foreach ($meetingData as $key => $value) {
            foreach ($liveVideoChatGrp as $k => $v) {
                if ($value->id == $v['meeting_id'] && $v['type'] == 1) {
                    $firstAnchorLink = $value->join_url;
                } else if ($value->id == $v['meeting_id'] && $v['type'] == 2) {
                    $secondAnchorLink = $value->join_url;
                }else if ($value->id == $v['meeting_id'] && $v['type'] == 3) {
                    $thirdAnchorLink = $value->join_url;
                }else if ($value->id == $v['meeting_id'] && $v['type'] == 4) {
                    $fourthAnchorLink = $value->join_url;
                }
            }
        }
    }

    if($firstAnchorLink == ''){
        $db->where('type', 1);
        $db->delete('zoom_live_videochat_details');
    }
    if($secondAnchorLink == ''){
        $db->where('type', 2);
        $db->delete('zoom_live_videochat_details');
    }
    if($thirdAnchorLink == ''){
        $db->where('type', 3);
        $db->delete('zoom_live_videochat_details');
    }
    if($fourthAnchorLink == '') {
        $db->where('type', 4);
        $db->delete('zoom_live_videochat_details');
    }

    $returnAry = array(
        'firstAnchorLink' => $firstAnchorLink,
        'secondAnchorLink' => $secondAnchorLink,
        'thirdAnchorLink' => $thirdAnchorLink,
        'fourthAnchorLink' => $fourthAnchorLink,
    );
    echo json_encode($returnAry);

}






?>