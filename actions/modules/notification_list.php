<?php
include("../../includes/config.php");

//Language
putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);

?>

<?php
if(isset($_GET['category'])){
    $category = $_GET['category'];
}

$current_date = date('Y-m-d H:i:s');

// Condition 1 - get not completed
$db->where('CustomerId', $_SESSION['CustomerId']);
$db->where('DepartmentId', $_SESSION['CurrentDepartment']);
$db->where('UserId','0','!=');
$db->where('End',$current_date,'<');
$db->where('Completed',0,'=','OR');

// Condition 2 - get available
$db->where('CustomerId', $_SESSION['CustomerId']);
$db->where('DepartmentId', $_SESSION['CurrentDepartment']);
$db->where('Available',1,'=','OR');

// Condition 3 - get not approved absence
$db->where('CustomerId', $_SESSION['CustomerId']);
$db->where('DepartmentId', $_SESSION['CurrentDepartment']);
$db->where('AbsenceId',0,'!=');
$db->where('Approved',0,'=','OR');

// Condition 4 - get not approved takeovers
$db->where('CustomerId', $_SESSION['CustomerId']);
$db->where('DepartmentId', $_SESSION['CurrentDepartment']);
$db->where('TakenDate','0000-00-00 00:00:00','!=');
$db->where('ApprovedTakeover',0,'=');


$db->orderBy("Date","desc");
$shifts = $db->get('schedule');

$shifts_not_completed           = array();
$shifts_available               = array();
$shifts_absence_not_approved    = array();
$shifts_takeover_not_approved   = array();

foreach($shifts as $shift){

    $shift_time = convert_start_end_date($shift['Start'],$shift['End']);

    if($shift_time != '-'){

        // Condition 1
        if($shift['UserId'] != 0 && $shift['End'] < $current_date && $shift['Completed'] == 0){
            $shifts_not_completed[] = $shift;
        }

        // Condition 2
        if($shift['Available'] == 1){
            $shifts_available[] = $shift;
        }

        // Condition 3
        if($shift['AbsenceId'] != 0 && $shift['Approved'] == 0){
            $shifts_absence_not_approved[] = $shift;
        }

        // Condition 4
        if($shift['TakenDate'] != '0000-00-00 00:00:00' && $shift['ApprovedTakeover'] == 0){
            $shifts_takeover_not_approved[] = $shift;
        }

    }

}


$shifts_absence_not_approved_count = count($shifts_absence_not_approved);
$shifts_available_count = count($shifts_available);
$shifts_not_completed_count = count($shifts_not_completed);
$shifts_takeover_not_approved_count = count($shifts_takeover_not_approved);


$shifts_all = array(
    "AbsenceNotApproved" => $shifts_absence_not_approved,
    "Available" => $shifts_available,
    "NotCompleted" => $shifts_not_completed,
    "TakeoverNotApproved" => $shifts_takeover_not_approved
);



if(isset($_GET['category'])){

    if(!empty($shifts_all[$category])){

        echo "<ul class='list'>";

            echo "<li class='head'>";
            echo "<div class='md-col-4'><strong>" . _('Shift') . "</strong></div>";
            echo "<div class='md-col-2'><strong>" . _('Scheduled time') . "</strong></div>";

            if($category == 'NotCompleted'){
                echo "<div class='md-col-2'><strong>" . _('Registred time') . "</strong></div>";
            }else{
                echo "<div class='md-col-2'> </div>";
            }

            echo "<div class='md-col-4'><strong>" . _('Current status') . "</strong></div>";
            echo "</li>";

        foreach($shifts_all[$category] as $data){

            $user           = user_data_by_id($data['UserId']); 
            $shifttime      = convert_start_end_date($data['Start'],$data['End']);
            $shifttime_hours = convert_hours_decimal_pretty(convert_hours($shift['Start'],$shift['End']));

            if($_SESSION['PunchClockSetting'] == 1){

                $shifttime_registred        = convert_start_end_date($data['CheckIn'],$data['CheckOut']);
                $shifttime_hours_registred  = convert_hours_decimal_pretty(convert_hours($shift['CheckIn'],$shift['CheckOut']));

            }else{

                $shifttime_registred        = $shifttime;
                $shifttime_hours_registred  = $shifttime_hours;

            }

            if($shifttime_registred == '-'){
                $shifttime_registred = _('No check in made');
            }

            echo "<li>";

            echo "<div class='pg-input-radio'>";
            echo "<input name='Check_".$category."' class='field-check' type='checkbox' id='input-check' value='".$data['Id']."'>";
            echo "<span class='checkmark'></span>";
            echo "<div class='md-col-4'><strong><span>" . convert_date_pretty($data['Date'],$_SESSION['Country']) . "</span>";

            echo "<span class='name'>";

            if(empty($user['Id'])){
                echo _('Open shift');
            }else{
                echo $user['Firstname'] . " " . $user['Lastname'];
            }

            echo "</span></strong></div>";

            echo "<div class='md-col-2'><span class='time time-org'>";
            echo "<span>" . $shifttime . "</span><em>(" . $shifttime_hours . ")</em>";
            echo "</span></div>";

            if($category == 'NotCompleted'){

                echo "<div class='md-col-2'><span class='time time-reg'>";
                echo "<span>" . $shifttime_registred . "</span><em>(" . $shifttime_hours_registred . ")</em>";
                echo "</span></div>";

            }else{
                echo "<div class='md-col-2'> </div>";
            }

            echo "<div class='md-col-4'>";

            if($category == 'AbsenceNotApproved'){
                echo "<em>" . _('Not yet approved') . "</em>";
            }elseif($category == 'NotCompleted'){
                echo "<em>" . _('Not yet completed') . "</em>";
            }elseif($category == 'TakeoverNotApproved'){
                echo "<em>" . _('Not yet approved') . "</em>";
            }

            echo "</div>";

            echo "</div>";
            //echo "
            //<div class='actions'>
            //    <a class='approve'><i class='icon-ok'></i></a>
            //    <a class='delete'><i class='icon-delete'></i></a>
            //</div>";
            echo "</li>";

        }

        echo "</ul>";

    }else{
        echo "<div class='msg'>" . _('No pending actions. Everything is in order!') . "</div>";
    }

}

?>