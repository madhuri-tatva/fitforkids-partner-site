<?php
include("../../includes/config.php");
use PHPCPSMS\SMS;

if(isset($_POST)){
    $user_id = $_SESSION['UserId'];
    $db->where('id', $user_id);
    $user = $db->getOne('users');

    $dispDetail = $user['Firstname'].', ';
    if($user['Gender'] != '') {
        $db->where('ParentId', $user['Id']);
        $db->where('is_partner', 0);
        $childrens = $db->get('users',null ,['Firstname']);
        if(!empty($childrens)) {
            $dispChild = '';
            foreach($childrens as $child){
                  $dispChild .= ($dispChild == '') ? $child['Firstname']  : ' & '.$child['Firstname'];
            }
            if(trim($user['Gender']) == 'Male') {
                $dispDetail .= 'far til '. $dispChild;
            } else {
                $dispDetail .= 'mor til '. $dispChild;
            }
        }
    }
    if($user['Region']){
        $dispDetail .= ' fra ' .$user['Region'] . ', ';
    }

    $type = $_POST['type'];
    $sDate = new DateTime($_POST['startDate']);
    $date = $sDate->format('l j-M-Y');
    $startTime =  $sDate->format('H.i');
    $eDate = new DateTime($_POST['endDate']);
    $endTime = $eDate->format('H.i');
    $sms = new sms($sms_token);

    $dispMessage =  strftime('%A', strtotime($_POST['startDate'])) . ' d. ' . strftime('%e. %B', strtotime($_POST['startDate'])) . ' kl. ' . $startTime . '.' ;

    $engMonth = array('January', 'February','March','April','May','June','July','Auguest','September','October','November','December');
    $dnsMonth = array('januar', 'februar','marts','april','kan','juni','juli','august','september','oktober','november','december');
    $dispMessage = str_ireplace($engMonth, $dnsMonth, $dispMessage);
    $engDay = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    $dnsDay = array('søndag','mandag','tirsdag','onsdag','torsdag','fredag','lørdag');
    $dispMessage = str_ireplace($engDay, $dnsDay, $dispMessage);


    $phoneNo = ($user['PhoneNumber'] != 0)? $user['PhoneNumber'] : '-';
    $array = array(
        'firstname' => $_SESSION['Firstname'],
        'lastname' => $_SESSION['Lastname'],
        'name' => '',
        'userdetail' =>  $dispDetail,
        "email" => CONTACT_EMAIL,
        "datedisplay" => $dispMessage,
        "phonenumber" => $user['Firstname'].' har telefonnr. '. $phoneNo
    );
    if($type == 1) {
        $current_Date = date("Y-m-d");
        $db->where('user_id', $user_id);
        $db->where('start', $current_Date, '>=');
        $data = $db->get('booking_slot');
        if (count($data) == 0 && empty($data)) {
            $insertArr = array(
                "user_id" =>  $user_id,
                "title" => $_POST['title'],
                "start"  => $_POST['startDate'],
                "end"    => $_POST['endDate'],
                "created" => $db->now()
            );

            $db->insert('booking_slot', $insertArr);
            send_mail('da_DK','Bestil tid' ,$array);
        }

    } else {
            $db->where('id', $_POST['id']);
		    $db->delete('booking_slot');
            send_mail('da_DK','Slet aftale' ,$array);
    }
    if($user['PhoneNumber'] != 0) {
        $msgText = '';
        if($type == 1) {
            $msgText = "Hej " . $_SESSION['Firstname'] . "\n" ."Du har en aftale med din FitforKids-coach " . $dispMessage . "\n" ."Vi glæder os til at lytte til dig. Kh dit FitforKids Coach-team " ;
        } else {
            $msgText = "Hej " . $_SESSION['Firstname'] . "\n" ."Du har slettet din coach-aftale " . $dispMessage . "\n" ."Vi håber du finder en anden tid der passer bedre. Kh dit FitforKids Coach-team ";
        }
        $sms->recipient = $user['PhoneNumber'];
        $sms->message = $msgText;
        $response = $sms->send();
        $result = json_decode($response, true);
        if($result['error']){
            echo $result['error']['message'];
        } else {
            print_r($result);
        }
    }
        exit;

}

?>