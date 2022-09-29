<?php
/* include autoloader */
include("../../includes/config.php");

class ICS {
    var $data;
    var $name;
    function ICS($start,$end,$name,$description,$location,$organizer,$attendee) {
        $this->name = $name;
        $this->data = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nBEGIN:VEVENT\nORGANIZER:MAILTO:".$organizer."\nATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=".$attendee.";X-NUM-GUESTS=0:MAILTO:".$attendee."\nDTSTART:".date("Ymd\THis",strtotime($start))."\nDTEND:".date("Ymd\THis",strtotime($end))."\nLOCATION:".$location."\nTRANSP: OPAQUE\nSEQUENCE:0\nUID:\nDTSTAMP:".date("Ymd\THis\Z")."\nSUMMARY:".$name."\nDESCRIPTION:".$description."\nPRIORITY:1\nCLASS:PUBLIC\nBEGIN:VALARM\nTRIGGER:-PT10080M\nACTION:DISPLAY\nDESCRIPTION:Reminder\nEND:VALARM\nEND:VEVENT\nEND:VCALENDAR\n";
    }
    function save() {
        $fileName = 'FFKCalendar_'.time().'_'.$_SESSION["UserId"].'.ics';
        $targetPath =  MAIN_PATH. '/uploads/calendar/';
        if(!is_dir($targetPath)) {
            mkdir($targetPath, 0777,true);
        }
        file_put_contents($targetPath.$fileName,$this->data);
        $fullpath = $targetPath.$fileName;
      if(file_exists($fullpath)){
        $title = $_POST['Title'];
        $detail = $_POST['Detail'];
        $array = array(
          "firstname" => $_SESSION['Firstname'],
          "name" => '',
          "eventinfo" => $title.' </br> '.$detail,
          "email" => $_SESSION['Email']
        );
        send_mail('da_DK',"Calendar", $array, $targetPath.$fileName);
        $array = array(
          "firstname" => $_POST['AttendeeName'],
          "name" => '',
          "eventinfo" => $title.' </br> '.$detail,
          "email" => $_POST['Guests']
        );
        send_mail('da_DK',"Calendar", $array, $targetPath.$fileName);
      }
        // file_put_contents($this->name.".ics",$this->data);
    }

    function show() {
        header("Content-type:text/calendar");
        header('Content-Disposition: attachment; filename="'.$this->name.'.ics"');
        Header('Content-Length: '.strlen($this->data));
        Header('Connection: close');
        echo $this->data;
    }
}
  if(isset($_POST)){
    $startDate = $_POST['StartDate'];
    $endDate = $_POST['EndDate'];
    $title = $_POST['Title'];
    $detail = $_POST['Detail'];
    $sender = $_SESSION['Email'];
    $attendee = $_POST['Guests'];
    $location = $_POST['Location'];
    $organizer = $_SESSION['Email'];
    $event = new ICS($startDate,$endDate,$title,$detail,$location,$organizer,$attendee);
    $eventData = $event->save();
        echo 'mail sent';

  }else{
    echo 'failed';
  }
  exit();
?>
