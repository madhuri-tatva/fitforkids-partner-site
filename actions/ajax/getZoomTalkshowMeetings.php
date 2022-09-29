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

$meetingData = list_meetings();
$isFutureTalkshow = 0;

if($meetingData == 'Token_Refreshed' ){
    return 'Token_Refreshed';
}else if($meetingData == 'Authentication'){
    return 'Authentication';
}else{
    if(!empty($meetingData) && is_array($meetingData)){
        $liveVideoChatGrp = $db->get("zoom_live_videochat_details", null, 'meeting_id');
        if (!empty($liveVideoChatGrp)) {
            foreach ($meetingData as $key => $value) {
                foreach ($liveVideoChatGrp as $val) {
                    if ($value->id == $val['meeting_id']) {
                        unset($meetingData[$key]);
                    }
                }
            }
            $meetingData = array_values($meetingData);
        }
        $TalkshowMeetingData = $db->get("zoom_meeting_details");
        if(!empty($TalkshowMeetingData)){
                 foreach ($meetingData as $key => $value) {
                    foreach ($TalkshowMeetingData as $t_value) {
                        if($t_value['meeting_status']==0 && $t_value['meeting_id']==$value->id){
                            unset($meetingData[$key]);
                        }
                    }
                }
            $meetingData = array_values($meetingData);
        }
    }

    $returnAry = array();

    if(!empty($meetingData) && is_array($meetingData)){
        $chatPageData = $AltHostName = $talkshowLeftSideText = '';
        $timeduration = $meetingData[0]->duration;

        $meetingTopic =   $meetingData[0]->topic;
        // $meetingHostName = $meetingData[0]->host_name;
        $meetingHostName = 'FitForKids Team';
        if(!empty($meetingTopic) && !empty(strpos($meetingTopic, '@'))){
            $meeting_title_arr = explode('@',$meetingTopic);
            $meeting_title = (isset($meeting_title_arr[0])) ? $meeting_title_arr[0] : $meetingTopic ;
            $meetingHostName = (isset($meeting_title_arr[1])) ? $meeting_title_arr[1] : $meetingHostName ;
        }else{
            $meeting_title = $meetingTopic;
            $meetingHostName = $meetingHostName;
        }

        $chatPageData = 'Next Up: '.$meeting_title.' '.$weekDay[date('l', strtotime(date('Y-m-d', strtotime($meetingData[0]->start_time))))].' '.date('d/m H:i', strtotime($meetingData[0]->start_time));

        if(isset($meetingData[0]->settings->alternative_hosts) && !empty($meetingData[0]->settings->alternative_hosts)){
            $Alternative_hosts_arr = explode(';',$meetingData[0]->settings->alternative_hosts);
            for ($i=0; $i <count($Alternative_hosts_arr) ; $i++) {
                if($Alternative_hosts_arr[$i] != 'administration@fitforkids.dk'){
                    $AltHostName = ' / '.meetingHostDetails($Alternative_hosts_arr[$i]);
                }
            }
        }

        $talkshowLeftSideText = '<a class="ticket-link" target="_blank" href="'.$meetingData[0]->join_url.'">
                                <p>'.$weekDay[date('l', strtotime(date('Y-m-d', strtotime($meetingData[0]->start_time))))]
                                .' ' . date('d/m', strtotime($meetingData[0]->start_time)).'</p>
                                <p>Kl. '.date('H:i', strtotime($meetingData[0]->start_time)).' - '.date('H:i', strtotime($meetingData[0]->start_time." +$timeduration minutes")).'</p>
                                <p>'.$meeting_title.'<br/></p>
                                <p>'.$meetingHostName .
                                '<br/></p></a>';



        $returnAry['chatPageData'] = $chatPageData;
        $returnAry['talkshowLeftSideText'] = $talkshowLeftSideText;

        $talkshowRightSideText = '';
        foreach ($meetingData as $key => $val) {
            if ($key > 0 && $key <= 4) {
                    $altHostNames = '';
                    $futureTopic =   $val->topic;
                    $futureHostName = 'FitForKids Team';
                    if(!empty($meetingTopic) && !empty(strpos($futureTopic, '@'))){
                        $future_title_arr = explode('@',$futureTopic);
                        $future_title =(isset($future_title_arr[0])) ? $future_title_arr[0] : $futureTopic ;
                        $futureHostName = (isset($future_title_arr[1])) ? $future_title_arr[1] : $futureHostName ;
                    }else{
                        $future_title = $futureTopic;
                        $futureHostName = $futureHostName;
                    }
                    if(isset($val->settings->alternative_hosts) && !empty($val->settings->alternative_hosts)){
                        $alternative_hosts_arr = explode(';',$meetingData[0]->settings->alternative_hosts);
                        for ($i=0; $i <count($alternative_hosts_arr) ; $i++) {
                            if($alternative_hosts_arr[$i] != 'administration@fitforkids.dk'){
                                $altHostNames = ' / '.meetingHostDetails($alternative_hosts_arr[$i]);
                            }
                        }
                    }
                    $isFutureTalkshow = 1;

                $talkshowRightSideText .= '<a class="ticket-link" target="_blank" href="' .$val->join_url.'"><p>'.$weekDay[date("l", strtotime(date("Y-m-d", strtotime($val->start_time))))].' d. '.date("d/m", strtotime($val->start_time)).' kl. '.date("H:i", strtotime($val->start_time)).' <br/>'.$future_title.' '.$futureHostName .'</p><br/></a>';

            }
        }
        $returnAry['talkshowRightSideText'] = $talkshowRightSideText;
        if(empty($isFutureTalkshow)){
            $returnAry['talkshowRightSideText'] = '<p>Ikke mere talkshow fundet!</p>';
        }
    }else{
        $returnAry['chatPageData'] = 'No talkshow scheduled!';
        $returnAry['talkshowLeftSideText'] = '<p>No talkshow scheduled!</p>';
        $returnAry['talkshowRightSideText'] = '<p>No talkshow scheduled!</p>';
    }


    echo json_encode($returnAry);

}






?>