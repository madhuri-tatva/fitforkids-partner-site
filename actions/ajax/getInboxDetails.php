<?php
include("../../includes/config.php");
if(isset($_POST['id'])){

    $db->where('pg_internal_mail.id', $_POST['id']);
    if($_POST['tab'] == 'tab-inbox') {
        $db->join('users','pg_internal_mail.from_user = pg_users.id');
    } else {
        $db->join('users','pg_internal_mail.to_user = pg_users.id');
    }
    $inboxDetails = $db->getOne('internal_mail',['pg_internal_mail.id','from_user','to_user','subject','message','attachment','created_at','updated_at','pg_users.id as UserId','Firstname','Lastname','Email']);

    $sentDispDate = strftime('%a %e. %b %Y %H.%M', strtotime($inboxDetails['created_at']));
    $engMonth = array('Jan', 'Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
    $dnsMonth = array('jan', 'feb','mar','aprl','kan','jun','jul','aug','sep','okt','nov','dec');
    $sentDispDate = str_ireplace($engMonth, $dnsMonth, $sentDispDate);
    $engDay = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
    $dnsDay = array('søn','man','tir','ons','tor','fre','lør');
    $sentDispDate = str_ireplace($engDay, $dnsDay, $sentDispDate);

    $text = '<div class="meta-inner"><div class="tab"><button class="prl10" onclick=openDetailPage("","my-inbox","'.$_POST['tab'].'")><em><img src="\assets\img\back-arrow.png" alt="back-arrow"></em>Tilbage</button></div></div>';
    $text .= '<div class="box-tab-content mail-detail-wrapper" id="tab-inbox">';
    $text .=  '<p class="mydetail">Til : <span class="mail-sender-name">'.$inboxDetails['Firstname'].' '.$inboxDetails['Lastname'].'</span> <span class="mail-sender-email">&lt;'.$inboxDetails['Email'].'&gt;</span></p>' ;
    if($inboxDetails['attachment']){
    $text .= '<p class="mydetail"><a  title="'.$inboxDetails['attachment'].'" class="attach-pdf"><em><img src="/assets/images/attachment-clip.svg" alt="attachment-clip"></em></a>Oprettet kl : <span class="mail-sender-name">'.$sentDispDate.'</span></p>';
    }else{
        $text .= '<p class="mydetail">Oprettet kl : <span class="mail-sender-name">'.$sentDispDate.'</span></p>';
    }
    $text .= '<p class="mydetail subject">Emne : <span class="mail-sender-name">'.$inboxDetails['subject'].'</span></p>';
    $text .= '<p class="mydetail description">Tekst : <span class="formatText">'.$inboxDetails['message'].'</span></p>';
    // if($inboxDetails['attachment'])
    //     $text .= '<p>Vedhæftet fil : <a href="'.BASE_HREF.'uploads/inbox/'.$inboxDetails['attachment'].'" download><span>'.$inboxDetails['attachment'].'</span></p>';

    $ext = pathinfo($inboxDetails['attachment'], PATHINFO_EXTENSION);
    if($inboxDetails['attachment'] && $ext == 'pdf')
        $text .= '<p class="mydetail have-attachment">Vedhæftet fil : <iframe class="attachment-cls " src="'.BASE_HREF.'uploads/inbox/'.$inboxDetails['attachment'].'" width="-webkit-fill-available" height="300px"  /></p>';
    if($inboxDetails['attachment'] && $ext == 'mp3')
        $text .= '<p class="mydetail have-attachment">Vedhæftet fil : <audio controls> <source  src="'.BASE_HREF.'uploads/inbox/'.$inboxDetails['attachment'].'" > Your browser does not support the audio element.</audio></p>';
    $text .= '</div>';
    echo $text;
}
?>