<?php
include("../../includes/config.php");
$userId = $_SESSION['UserId'];

if($userId){
    $db->where('Id', $userId);
    $userData = $db->getOne('users');
    $team_name = '';
    if($userData['TeamId']>0){
        $db->where('Id',$userData['TeamId']);
        $team = $db->getOne('teams');
        $team_name =(!empty($team)) ? $team['Name'] : '';
    }
    if(!empty($userData['Email'])){
        $array = array(
            "firstname" =>  $userData['Firstname'],
            "lastname" =>   $userData['Lastname'],
            "email" =>  $userData['Email']
        );
        send_mail('da_DK',"User Kostan plan delete", $array);

        $array_admin = array(
            "firstname" =>  $userData['Firstname'],
            "lastname" =>   $userData['Lastname'],
            "hold" =>   $team_name,
            "email" =>  KOSTAN_EMAIL
        );
        send_mail('da_DK',"Kostan plan delete request", $array_admin);

        $db->where('session_id',$userId);
        $db->orderBy('id','DESC');
        $last_submitted_kostan = $db->getOne('kostan_detail');
        if(!empty($last_submitted_kostan)){
             $data = array(
                    "is_deleted" => 1
                );
            $db->where('id',$last_submitted_kostan['id']);
            $db->update('kostan_detail',$data);
        }
        $_SESSION['whatnow_access'] = 0;
    }
}
?>