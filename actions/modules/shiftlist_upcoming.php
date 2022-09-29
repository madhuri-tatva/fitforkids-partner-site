<?php
include("../../includes/config.php");

//Language
putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);
/*
$department_data = department_data_by_id($_SESSION['DepartmentId']);


$current_year = $_SESSION['CurrentYear'];
$current_week = sprintf('%02d', $_SESSION['CurrentWeek']);
$users = users_week_department($_SESSION['CurrentDepartment'],$current_week,$current_year);
*/

?>

    <?php 


    $current_month  = $_SESSION['ShiftList']['CurrentMonth'];
    $current_year   = $_SESSION['ShiftList']['CurrentYear'];


    $next_month = $current_month + 1;
    $next_year = $current_year;
    if($next_month > 12){
        $next_month = 1;
        $next_year = $next_year + 1;
    }
    $next = $next_year . "/" . $next_month;



    $prev_month = $current_month - 1;
    $prev_year = $current_year;
    if($prev_month < 1){
        $prev_month = 12;
        $prev_year = $prev_year - 1;
    }
    $prev = $prev_year . "/" . $prev_month;

/*
    if($next_week > $last_week){
        $next_week = 1;
        $next_year = $next_year + 1;
    }
    $next = $next_year . "/" . $next_week;



    $prev_week = $current_week - 1;
    $prev_year = $current_year;
    if($prev_week < 1){
        $prev_week = $last_week_prev_year;
        $prev_year = $prev_year - 1;
    }
    $prev = $prev_year . "/" . $prev_week;
    
*/


    ?>

    <div class="calendar-navigation">
        <ul>
            <li><a id="prev" onclick="changemonth(<?php echo $prev_year; ?>,<?php echo $prev_month; ?>);" class="btn-icon"><i class="angle-lefticon-"></i></a></li>
            <li class="current"><a onclick="changemonth('Today','Today');"><?php echo _('This month'); ?></a></li>
            <li><a id="next" class="btn-icon" onclick="changemonth(<?php echo $next_year; ?>,<?php echo $next_month; ?>);"><i class="angle-righticon-"></i></a></li>
        </ul>
    </div>

<div class="box-header">
    <h2><?php echo _('Your upcoming shifts'); ?> <strong><?php echo _(convert_month($current_month)) . " " . substr($current_year,2,2); ?></strong></h2>
</div>

<div class="box-inner noborder">

    <?php 
    $class = '';

    $shift_list_startperiod = date($current_year.'-'.$current_month.'-01');
    $shift_list_startperiod_lastday = date('t',strtotime($shift_list_startperiod));
    $shift_list_endperiod = date($current_year.'-'.$current_month.'-' . $shift_list_startperiod_lastday);
    $shift_list_user = shift_list_user($_SESSION['UserId'],$shift_list_startperiod,$shift_list_endperiod);


    if(!empty($shift_list_user)){

    ?>

    <table class="table" id="shift_list">
        <thead class="list-header hide">
            <tr>
                <th class="col-md-6 no-sort"><?php echo _('Shift'); ?></th>
                <th class="col-md-3 no-sort">Tid</th>
                <th class="col-md-3 no-sort">&nbsp;</th>
                <!--<th class="col-md-2 no-sort sorting_disabled"></th>-->
            </tr>
        </thead>
        <tbody>
        <?php


            $shift_list_today = array();
            $shift_list_prev = array();
            $shift_list_next = array();

            foreach($shift_list_user as $shift){

                if($shift['Date'] == date('Y-m-d')){

                    $shift_list_today[] = $shift;

                }elseif($shift['Date'] < date('Y-m-d')){

                    $shift_list_prev[] = $shift;

                }elseif($shift['Date'] > date('Y-m-d')){

                    $shift_list_next[] = $shift;

                }

            }


            $shift_list_user = array_merge($shift_list_today, $shift_list_next, $shift_list_prev);


            foreach($shift_list_user as $shift){ 

                $shift_interval = convert_start_end_date($shift['Start'],$shift['End']);
                $shift_hours = convert_hours($shift['Start'],$shift['End']);

                if(strtotime($shift['Start']) < strtotime(date('Y-m-d 00:00:00'))){
                    $class = 'grayed';
                }else{
                    $class = '';
                }

                /*echo strtotime($shift['Start']);
                echo "<br>";
                echo strtotime(date('Y-m-d H:i:s'));
                echo "<br>";
                echo "<br>";*/

            ?>

            <tr class="<?php echo $class; ?>">
                <td><?php echo convert_date_pretty($shift['Date'],$_SESSION['Country']) . "<span class='discrete'>" . _('Week') . " " . $shift['Week'] . "</span>"; ?></td>
                <td><?php echo convert_hours_decimal_pretty($shift_hours); ?><em><?php echo $shift_interval; ?></em></td>
                <td><a onclick="getshift(<?php echo $shift['Id']; ?>)" class="btn btn-cta md-trigger" data-modal='modal-4'><?php echo _('View'); ?></a></td>
            </tr>

            <?php }
      
        ?>

        </tbody>
    </table>

    <?php }else{ ?>
        <?php echo "<div class='message'>" . _('No shifts') . "</div>"; ?>
    <?php } ?>

</div>




<script type="text/javascript">
function changemonth($year,$month){
    $("#shiftlist-upcoming-actions").load("https://www.plangy.com/actions/change_month.php?year=" + $year + "&month=" + $month).queue(function(next){
            setTimeout(function(){
                $("#shiftlist-upcoming").load("https://www.plangy.com/actions/modules/shiftlist_upcoming.php");
            }, 100);
        next();
    });
}

$('#shift_list').dataTable( {
    "pageLength": 6,
    "language": {
        "search":       "<?php echo _('Search'); ?>",
        "info":         "<?php echo _('Showing _START_ to _END_ of _TOTAL_ entries'); ?>",
        "infoEmpty":    "<?php echo _('Showing 0 to 0 of 0 entries'); ?>",
        "paginate": {
            "first":      "<?php echo _('First'); ?>",
            "last":       "<?php echo _('Last'); ?>",
            "next":       "<?php echo _('Next'); ?>",
            "previous":   "<?php echo _('Previous'); ?>"
        },
    },
    "order": [],
    "columnDefs": [ {
      "targets"  : 'no-sort',
      "orderable": false,
    }]
});
</script>

<script type="text/javascript" src="https://www.plangy.com/assets/js/classie.js"></script>
<script type="text/javascript" src="https://www.plangy.com/assets/js/modalEffects.js"></script>