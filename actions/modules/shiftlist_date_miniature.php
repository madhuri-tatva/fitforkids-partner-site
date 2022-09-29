<?php 
include("../../includes/config.php");

putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);

$date = $_GET['date'];
$date = substr($date, 1);
$current_year = substr($_GET['date'],1,4);

$date_formatted = '"' . $date . '"';

$holidays_observances = get_holidays_observances($current_year);

if(!empty($holidays_observances)){

    $array_holidays = $holidays_observances['Holidays'];
    $array_observances = $holidays_observances['Observances'];
    $holidays_observances_count = 1;

}else{

    $array_holidays = 0;
    $array_observances = 0;
    $holidays_observances_count = 0;

}

$events = events_by_date_department($date,$_SESSION['CurrentDepartment']);

$overview_shifts = shift_overview_by_date($date,$_SESSION['CurrentDepartment'],$_SESSION['UserId']);
$overview_shifts_groups = array_slice($overview_shifts,0,3);

$overview_shifts_total_count = array_slice($overview_shifts,5,1);
$count = $overview_shifts_total_count['TotalCount'];


    if(!empty($array_holidays[$date])){
        echo "<a class='holidays'>" . $array_holidays[$date] . "</a>";
    }

    if(!empty($array_observances[$date])){
        echo "<a class='observances'>" . $array_observances[$date] . "</a>";
    }




    foreach($overview_shifts_groups as $key => $overview_shifts_group){    


        // Sorting

        $shifts_sorted = array();
        $shifts_this_user = array();

        foreach($overview_shifts_group as $shift){

            if($shift['UserId'] == $_SESSION['UserId']){
                $shifts_this_user[] = $shift;
            }else{
                $shifts_sorted[] = $shift;
            }

        }

        $shifts_final = array_merge($shifts_this_user, $shifts_sorted);


        foreach($shifts_final as $shift){


            $isnormal = 0;
            $isnotapproved = 0;
            $isopen = 0;


            if($shift['Available'] == 0){
                $isnormal = 1;
            }

            if($shift['Available'] == 1 && $shift['Approved'] == 1){
                $isopen = 1;
            }

            if($shift['Available'] == 1 && $shift['Approved'] == 0){
                $isnotapproved = 1;
            }

            $shifts_id  = array();

            $shift_time = convert_start_end_date($shift['Start'],$shift['End']);

            $class = '';
            /*if($shift['Available'] == 1 && $shift['Approved'] == 1){
                $class .= ' available';
            }
            if($shift['Available'] == 1 && $shift['Approved'] == 0){
                $class .= ' notapproved';
            }
            if($shift['AbsenceId'] != 0){
                $class .= ' absence';
            }
            if($shift['UserId'] != $shift['OriginalUserId'] && $shift['Available'] == 0 && $shift['TakenDate'] != '0000-00-00 00:00:00'){
                $class .= ' normal';
            }*/

            $absence_icon = '';

            if($key == 'Normal'){
                $class = 'green';
            }elseif($key == 'Available'){
                $class = 'blue';
            }elseif($key == 'Absence'){
                $class = 'red';

                if($shift['AbsenceId'] != 0){
                    $absence_icon = get_absence_icon($shift['AbsenceId']);
                }
            }

            if($_SESSION['UserId'] == $shift['UserId']){
                $class .= ' highlight';
            }

            if($shift_time != '-'){

                if($shift['UserId'] == 0){

                    echo "<a onclick='getshift(" . $shift['Id'] . ")' class='blue md-trigger' data-modal='modal-4'><i>". $absence_icon . "</i>" . $shift_time . " " . _('Open shift') . "</a>";

                }else{

                    if($key == 'Absence'){
                        $user_original = user_data_by_id($shift['OriginalUserId']);
                        $firstname = $user_original['Firstname'];
                    }else{
                        $user = user_data_by_id($shift['UserId']);
                        $firstname = $user['Firstname'];
                    }
                                        

                    if($_SESSION['Type'] < 3){

                        echo "<a onclick='getshift(" . $shift['Id'] . ")' class='" . $class . " md-trigger' data-modal='modal-4'><i>". $absence_icon . "</i>" . $shift_time . " " . $firstname . "</a>";
                    
                    }else{

                        if($_SESSION['UserId'] == $shift['UserId']){

                            echo "<a onclick='getshift(" . $shift['Id'] . ")' class='" . $class . " md-trigger' data-modal='modal-4'><i>". $absence_icon . "</i>" . $shift_time . " " . $user['Firstname'] . "</a>";

                        }elseif($shift['Available'] == 1){

                            echo "<a onclick='getshift(" . $shift['Id'] . ")' class='" . $class . " md-trigger' data-modal='modal-4'><i>". $absence_icon . "</i>" . $shift_time . " " . $firstname . "</a>";
                        
                        }else{
                            
                            echo "<a class='" . $class . "'><i>". $absence_icon . "</i>" . $shift_time . " " . $firstname . "</a>";
                        
                        }

                    }

                }

            }

        }

    }


    echo "</div>";


?>

