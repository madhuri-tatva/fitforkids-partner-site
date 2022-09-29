<?php 
include("../../includes/config.php");

if($domian_url == 'www.plangy.com'){
    putenv('LC_ALL=en_GB');
    setlocale(LC_ALL, 'en_GB');

    bindtextdomain('en_GB', "../../locale");
    bind_textdomain_codeset('en_GB', 'UTF-8'); 

    textdomain('en_GB');
}elseif($domian_url == 'www.plangy.dk'){
    putenv('LC_ALL=da_DK');
    setlocale(LC_ALL, 'da_DK');

    bindtextdomain('da_DK', "../../locale");
    bind_textdomain_codeset('da_DK', 'UTF-8'); 

    textdomain('da_DK');
}

?>

<div class="form-inner wide">

    <form action="" id="schedule" method="post">
        <div class="meta-actions">
            <span class="btn-cta" id="submit"><?php echo _('Create schedule'); ?></span>
            <span id="skip" class="btn-std"><?php echo _('Skip'); ?></span>
        </div>

        <div class="schedule-simple">
    
<?php

$startweek 				= $_SESSION['Schedule_WeekFrom'];
$endweek 				= $_SESSION['Schedule_WeekTo'];
$year 					= $_SESSION['Schedule_Year'];
$departmentid 			= $_SESSION['Schedule_DepartmentId'];
$frequence 				= $_SESSION['Schedule_Frequence'];
$customerid 			= $_SESSION['CustomerId'];

$hourstart 				= $_SESSION['Schedule_TimeHourStart'];
$minutestart 			= $_SESSION['Schedule_TimeMinuteStart'];
$hourend 				= $_SESSION['Schedule_TimeHourEnd'];
$minuteend 				= $_SESSION['Schedule_TimeMinuteEnd'];

$users = users_by_department_id($_SESSION['DepartmentId']);



    // Global values
    echo "<input type='hidden' name='Year' value='".$year."' />";
    echo "<input type='hidden' name='StartWeek' value='".$startweek."' />";
    echo "<input type='hidden' name='EndWeek' value='".$endweek."' />";
    echo "<input type='hidden' name='Frequence' value='".$frequence."' />";
    echo "<input type='hidden' name='CustomerId' value='".$customerid."' />";
    echo "<input type='hidden' name='DepartmentId' value='".$departmentid."' />";

    // Define shift
    $validation_hours = "maxlength='2' min='0' data-parsley-min='0' max='24' data-parsley-max='24' data-parsley-trigger='focusout'";
    $validation_minutes = "maxlength='2' min='0' data-parsley-min='0' max='60' data-parsley-max='60' data-parsley-trigger='focusout'";

    // Number of weeks
    $number_of_weeks = $endweek - $startweek + 1;

    $dto = new DateTime();
    $dto->setISODate($year, $startweek);

    $week = $startweek - 1;

    for($i=1; $i<=$frequence; $i++){

        $end_by_minutes = ($hourend * 60) + $minuteend;
        $start_by_minutes = ($hourstart * 60) + $minutestart;
        $difference = $end_by_minutes - $start_by_minutes;

        //$format = "%02d:%02d";
        $difference_hours = floor($difference / 60);
        $difference_minutes = ($difference % 60);
        $difference_final = sprintf("%02d",$difference_hours) .":". sprintf("%02d",$difference_minutes);


        $total_difference_hours = floor(($difference * 7) / 60);
        $total_difference_minutes = (($difference * 7) % 60);
        $total_difference_final = sprintf("%02d",$total_difference_hours) .":". sprintf("%02d",$total_difference_minutes);


    
        $ini_total_shift_value = $difference;
        $ini_total_row_value = $difference * 7;

        $ini_total_shift = $difference_final;
        $ini_total_row = $total_difference_final;

        $unique_id = 0;

        $current_week = $week + $i;

        echo "<div class='outer'>";

        echo "<div id='row_" .$i. "' class='row row-parent'>";

        //echo "<h2>Week " . $current_week . "</h2>";

        echo "<div class='row first'>";

        echo "<div class='col first'>";

        echo "<h2>". _('Week') ." ";
        for ($n = $current_week; $n <= $endweek; $n+=$frequence) {
            echo "<span>" . $n . "</span> ";
        }
        echo "</h2>";
        echo "<em>". _('Roll') . " " . $i . "</em>";

        echo "</div>";

        for($day=1; $day<=7; $day++){
            echo "<div class='col'>";
            echo "<h4>" . _($dto->format('l')) . "</h4>";

            $count = 0;

            $backtrack = $count * -7;

            $dto->modify($backtrack . " day");

            $dto->modify('+1 day');
            echo "</div>";
        }
        echo "<div class='col last'>";
        echo "</div>";

        echo "</div>";



        echo "<div class='row wrapper-total-day'>";
        echo "<div class='col first'>";
        echo "</div>";

        for($day=1; $day<=7; $day++){
            echo "<div class='col'>";
            echo "<input type='text' class='total_day_".$day."' value='".$ini_total_shift."' disabled />";
            echo "<span> "._('hours')."</span>";
            echo "</div>";
        }

        echo "<div class='col last'>";
        echo "</div>";

        echo "</div>";

        $shift_week = '';




        $shift_week .= "<div class='row'>";
        $shift_week .= "<span class='btn-remove'><img src='/assets/img/icon-cancel.png' /></span>";

        $shift_week .= "<div class='col first'>";
        $shift_week .= "<select class='form-control center' name='UserId_".$i."[]'>";
        $shift_week .= "<option value=''>". _('Choose employee') ."</option>";
        //$shift_week .= "<option value='open'>". _('Open shift') ."</option>";

        foreach($users as $user){
            if(!empty($user['Title'])){
                $title = title_by_id($user['Title']);
                $shift_week .= "<option value='".$user['Id']."'>".$user['Firstname']." ".$user['Lastname']." - ".$title ."</option>";
            }else{
                $shift_week .= "<option value='".$user['Id']."'>".$user['Firstname']." ".$user['Lastname']."</option>";
            }
            
        }

        $shift_week .= "</select>";
        $shift_week .= "</div>";


        for($day=1; $day<=7; $day++){

            $shift = "<div class='time'><strong>"._('Start')."</strong><em>"._(date('D',strtotime('Sunday +' . $day . 'days')))."</em>";
            $shift .= "<input onclick='this.setSelectionRange(0, this.value.length)' class='hours starthour' name='starthour_".$i."[]' type='text' value='" . $hourstart . "' ". $validation_hours ."  /><span>:</span>";
            $shift .= "<input onclick='this.setSelectionRange(0, this.value.length)' class='minutes startminute' name='startminute_".$i."[]' type='text' value='" . $minutestart . "' ". $validation_minutes ." /></div>";
            $shift .= "<div class='seperator'>-</div>";
            $shift .= "<div class='time'><strong>"._('End')."</strong>";
            $shift .= "<input onclick='this.setSelectionRange(0, this.value.length)' class='hours endhour' name='endhour_".$i."[]' type='text' value='" . $hourend . "' ". $validation_hours ." /><span>:</span>";
            $shift .= "<input onclick='this.setSelectionRange(0, this.value.length)' class='minutes endminute' name='endminute_".$i."[]' type='text' value='" . $minuteend . "' ". $validation_minutes ." />";
            $shift .= "</div>";
            $shift .= "<input type='hidden' name='total_shift_".$day."' class='total day_".$day." disabled' value='".$ini_total_shift_value."' disabled />";
            $shift .= "<div class='field-clear'><input type='checkbox' class='btn-clear' checked /><span class='checkmark'></span></div>";


            $shift_week .= "<div class='col'>";

            $shift_week .= "<div class='day'>";

            $shift_week .= $shift; 

            $shift_week .= "</div>";

            $shift_week .= "</div>";


            $count = 0;

            $backtrack = $count * -7;

            $dto->modify($backtrack . " day");

            $dto->modify('+1 day');

        }

        $shift_week .=  "<div class='col last'>";
        $shift_week .= "<input type='text' class='total_row disabled' name='total_row' value='".$ini_total_row."' disabled />";
        $shift_week .=  "<span> "._('hours')."</span>";
        $shift_week .=  "</div>";

        $shift_week .= "</div>";

        echo $shift_week;

        echo "</div>";

        echo "<button id='addMore_". $i . "' class='btn-cta btn-add-more'>" . _('Add shift') ."</button>";

        echo "</div>";

        ?>

		<script type="text/javascript">

            $('#skip').click( function() {

                reg_continue('success');

                return false; 

            });
        
		    jQuery(function($) {
		      $('select.form-control').niceSelect();
		    });
		</script>

        <div id="js_<?php echo $i; ?>">

            <script type="text/javascript">

                $('.btn-remove').click( function() {
                    $(this).parents("div:eq(0)").remove();
                });

                $('.btn-clear').change( function() {
                    var isChecked = this.checked;
                    
                    if(isChecked) {
                        //$(this).parents("div:eq(1)").find(".hours").prop("disabled",false);
                        //$(this).parents("div:eq(1)").find(".minutes").prop("disabled",false);
                        $(this).parents("div:eq(1)").find('.starthour').val('<?php echo $hourstart; ?>');
                        $(this).parents("div:eq(1)").find('.startminute').val('<?php echo $minutestart; ?>');
                        $(this).parents("div:eq(1)").find('.endhour').val('<?php echo $hourend; ?>');
                        $(this).parents("div:eq(1)").find('.endminute').val('<?php echo $minuteend; ?>');
                        $(this).parents("div:eq(2)").find('.day').removeClass('disable');
                    } else {
                        $(this).parents("div:eq(2)").find('.day').addClass('disable');
                        $(this).parents("div:eq(1)").find('.total').val('00');
                        $(this).parents("div:eq(1)").find('.starthour').val('00');
                        $(this).parents("div:eq(1)").find('.startminute').val('00');
                        $(this).parents("div:eq(1)").find('.endhour').val('00');
                        $(this).parents("div:eq(1)").find('.endminute').val('00');
                        $(this).parents("div:eq(2)").find('input').removeClass('parsley-error');
                        $(this).parents("div:eq(2)").find('input').removeClass('error');
                        $(this).parents("div:eq(2)").find('input').removeClass('error_1');
                        $(this).parents("div:eq(2)").find('input').removeClass('error_2');
                        $(this).parents("div:eq(2)").find('input').removeClass('error_3');
                        $(this).parents("div:eq(2)").find('input').removeClass('bug');
                        //$(this).parents("div:eq(1)").find(".hours").prop("disabled",true);
                        //$(this).parents("div:eq(1)").find(".minutes").prop("disabled",true);
                        //$('.dropdown').get(0).selectedIndex = 0;
                        //$('.myCheckbox').attr('checked', false);
                    }
                    
                });


                $('.btn-add-more').click( function() {
                         
                    // Sum monday
                    var sum_monday = 0;
                    $(this).parents("div:eq(0)").find('.day_1').each(function(){
                        sum_monday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_monday / 60);          
                    var sum_minutes = sum_monday % 60;
                    var sum_monday = "" + pad(sum_hours,2) + pad(sum_minutes,2);


                    if(sum_monday == 0){
                        sum_monday = '0:00';
                    }else{
                        var str = ""+sum_monday+"";
                        var len = str.length;
                        var sum_monday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(0)").find('.total_day_1').val(sum_monday);


                    // Sum tuesday
                    var sum_tuesday = 0;
                    $(this).parents("div:eq(0)").find('.day_2').each(function(){
                        sum_tuesday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_tuesday / 60);          
                    var sum_minutes = sum_tuesday % 60;
                    var sum_tuesday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_tuesday == 0){
                        sum_tuesday = '0:00';
                    }else{
                        var str = ""+sum_tuesday+"";
                        var len = str.length;
                        var sum_tuesday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(0)").find('.total_day_2').val(sum_tuesday);


                    // Sum wednesday
                    var sum_wednesday = 0;
                    $(this).parents("div:eq(0)").find('.day_3').each(function(){
                        sum_wednesday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_wednesday / 60);          
                    var sum_minutes = sum_wednesday % 60;
                    var sum_wednesday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_wednesday == 0){
                        sum_wednesday = '0:00';
                    }else{
                        var str = ""+sum_wednesday+"";
                        var len = str.length;
                        var sum_wednesday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(0)").find('.total_day_3').val(sum_wednesday);



                    // Sum thursday
                    var sum_thursday = 0;
                    $(this).parents("div:eq(0)").find('.day_4').each(function(){
                        sum_thursday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_thursday / 60);          
                    var sum_minutes = sum_thursday % 60;
                    var sum_thursday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_thursday == 0){
                        sum_thursday = '0:00';
                    }else{
                        var str = ""+sum_thursday+"";
                        var len = str.length;
                        var sum_thursday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(0)").find('.total_day_4').val(sum_thursday);



                    // Sum friday
                    var sum_friday = 0;
                    $(this).parents("div:eq(0)").find('.day_5').each(function(){
                        sum_friday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_friday / 60);          
                    var sum_minutes = sum_friday % 60;
                    var sum_friday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_friday == 0){
                        sum_friday = '0:00';
                    }else{
                        var str = ""+sum_friday+"";
                        var len = str.length;
                        var sum_friday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(0)").find('.total_day_5').val(sum_friday);



                    // Sum saturday
                    var sum_saturday = 0;
                    $(this).parents("div:eq(0)").find('.day_6').each(function(){
                        sum_saturday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_saturday / 60);          
                    var sum_minutes = sum_saturday % 60;
                    var sum_saturday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_saturday == 0){
                        sum_saturday = '0:00';
                    }else{
                        var str = ""+sum_saturday+"";
                        var len = str.length;
                        var sum_saturday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(0)").find('.total_day_6').val(sum_saturday);



                    // Sum sunday
                    var sum_sunday = 0;
                    $(this).parents("div:eq(0)").find('.day_7').each(function(){
                        sum_sunday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_sunday / 60);          
                    var sum_minutes = sum_sunday % 60;
                    var sum_sunday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_sunday == 0){
                        sum_sunday = '0:00';
                    }else{
                        var str = ""+sum_sunday+"";
                        var len = str.length;
                        var sum_sunday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(0)").find('.total_day_7').val(sum_sunday);


                });



                $('.day input').change( function() {

                    addAlert(0,"");

                    var starthour = $(this).parents("div:eq(1)").find('.starthour').val();
                      var starthour = pad(starthour,2);
                      $(this).parents("div:eq(1)").find('.starthour').val(starthour);

                    var startminute = $(this).parents("div:eq(1)").find('.startminute').val();
                      var startminute = pad(startminute,2);
                      $(this).parents("div:eq(1)").find('.startminute').val(startminute);

                    var endhour = $(this).parents("div:eq(1)").find('.endhour').val();
                      var endhour = pad(endhour,2);
                      $(this).parents("div:eq(1)").find('.endhour').val(endhour);

                    var endminute = $(this).parents("div:eq(1)").find('.endminute').val();
                      var endminute = pad(endminute,2);
                      $(this).parents("div:eq(1)").find('.endminute').val(endminute);

                    $(this).removeClass('bug');
                    $(this).parents("div:eq(2)").find('.day').removeClass('error_1');

                    // Validate if start hour are more than 24
                    if(starthour > 24){
                        $(this).parents('div:eq(0)').find('input.starthour').addClass('bug');
                        $(this).parents("div:eq(2)").find('.day').addClass('error_1');
                    }

                    // Validate if end hour are more than 24
                    if(endhour > 24){
                        $(this).parents('div:eq(0)').find('input.endhour').addClass('bug');
                        $(this).parents("div:eq(2)").find('.day').addClass('error_1');
                    }

                    // Validate if start minute are more than 60
                    if(startminute > 60){
                        $(this).parents('div:eq(0)').find('input.startminute').addClass('bug');
                        $(this).parents("div:eq(2)").find('.day').addClass('error_1');
                    }

                    // Validate if start minute are more than 60
                    if(endminute > 60){
                        $(this).parents('div:eq(0)').find('input.endminute').addClass('bug');
                        $(this).parents("div:eq(2)").find('.day').addClass('error_1');
                    }

                    var starthour_int = Number.isInteger(parseInt(+starthour));
                    var endhour_int = Number.isInteger(parseInt(+endhour));
                    var startminute_int = Number.isInteger(parseInt(+startminute));
                    var endminute_int = Number.isInteger(parseInt(+endminute));


                    $(this).removeClass('bug');
                    $(this).parents("div:eq(2)").find('.day').removeClass('error_2');

                    if(starthour_int == false){
                        $(this).parents('div:eq(0)').find('input.starthour').addClass('bug');
                        $(this).parents("div:eq(2)").find('.day').addClass('error_2');
                        addAlert(3,"<?php echo _('The start hour has to be a number.'); ?>");
                    }

                    if(endhour_int == false){
                        $(this).parents('div:eq(0)').find('input.endhour').addClass('bug');
                        $(this).parents("div:eq(2)").find('.day').addClass('error_2');
                        addAlert(3,"<?php echo _('The end hour has to be a number.'); ?>");
                    }

                    if(startminute_int == false){
                        $(this).parents('div:eq(0)').find('input.startminute').addClass('bug');
                        $(this).parents("div:eq(2)").find('.day').addClass('error_2');
                        addAlert(3,"<?php echo _('The start minute has to be a number.'); ?>");
                    }

                    if(endminute_int == false){
                        $(this).parents('div:eq(0)').find('input.endminute').addClass('bug');
                        $(this).parents("div:eq(2)").find('.day').addClass('error_2');
                        addAlert(3,"<?php echo _('The end minute has to be a number.'); ?>");
                    }

                    var starthour = starthour * 60;
                    var start = parseFloat(starthour) + parseFloat(startminute);
                    //console.log(start);

                    var endhour = endhour * 60;
                    var end = parseFloat(endhour) + parseFloat(endminute);

                    // Validate if end is greater then midnight
                    if(end > '1440'){
                        $(this).parents('div:eq(0)').find('input.endhour').addClass('bug_1');
                        $(this).parents('div:eq(0)').find('input.endminute').addClass('bug_1');
                        $(this).parents("div:eq(2)").find('.day').addClass('error_1');
                    }else{
                        $(this).parents("div:eq(0)").find('input').removeClass('bug_1');
                    }

                    // Validate if start is greater than end
                    if(start > end){
                        $(this).parents("div:eq(2)").find('.day').addClass('error');
                        addAlert(3,"<?php echo _("The start time can't be later than the end time of the shift."); ?>");
                    }else{
                        $(this).parents("div:eq(2)").find('.day.error').removeClass('error');
                    }

                    //var difference = pad(difference(start, end),4);
                    var difference = end - start;
                    var difference = pad(difference,4);
                    
                    $(this).parents("div:eq(1)").find('.total').val(difference);


                    var sum = 0;
                    $(this).parents("div:eq(3)").find('.total').each(function(){
                        sum += parseInt(this.value);
                    });


                    var sum_hours = Math.floor( sum / 60);          
                    var sum_minutes = sum % 60;
                    var sum = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    var str = ""+sum+"";
                    var len = str.length;
                    var sum = str.substring(0, len-2) + ":" + str.substring(len-2);
   
                    $(this).parents("div:eq(3)").find('.total_row').val(sum);

                         
                    // Sum monday
                    var sum_monday = 0;
                    $(this).parents("div:eq(4)").find('.day_1').each(function(){
                        sum_monday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_monday / 60);          
                    var sum_minutes = sum_monday % 60;
                    var sum_monday = "" + pad(sum_hours,2) + pad(sum_minutes,2);


                    if(sum_monday == 0){
                        sum_monday = '0:00';
                    }else{
                        var str = ""+sum_monday+"";
                        var len = str.length;
                        var sum_monday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(4)").find('.total_day_1').val(sum_monday);

     
                    // Sum tuesday
                    var sum_tuesday = 0;
                    $(this).parents("div:eq(4)").find('.day_2').each(function(){
                        sum_tuesday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_tuesday / 60);          
                    var sum_minutes = sum_tuesday % 60;
                    var sum_tuesday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_tuesday == 0){
                        sum_tuesday = '0:00';
                    }else{
                        var str = ""+sum_tuesday+"";
                        var len = str.length;
                        var sum_tuesday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(4)").find('.total_day_2').val(sum_tuesday);


                    // Sum wednesday
                    var sum_wednesday = 0;
                    $(this).parents("div:eq(4)").find('.day_3').each(function(){
                        sum_wednesday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_wednesday / 60);          
                    var sum_minutes = sum_wednesday % 60;
                    var sum_wednesday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_wednesday == 0){
                        sum_wednesday = '0:00';
                    }else{
                        var str = ""+sum_wednesday+"";
                        var len = str.length;
                        var sum_wednesday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(4)").find('.total_day_3').val(sum_wednesday);



                    // Sum thursday
                    var sum_thursday = 0;
                    $(this).parents("div:eq(4)").find('.day_4').each(function(){
                        sum_thursday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_thursday / 60);          
                    var sum_minutes = sum_thursday % 60;
                    var sum_thursday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_thursday == 0){
                        sum_thursday = '0:00';
                    }else{
                        var str = ""+sum_thursday+"";
                        var len = str.length;
                        var sum_thursday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(4)").find('.total_day_4').val(sum_thursday);



                    // Sum friday
                    var sum_friday = 0;
                    $(this).parents("div:eq(4)").find('.day_5').each(function(){
                        sum_friday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_friday / 60);          
                    var sum_minutes = sum_friday % 60;
                    var sum_friday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_friday == 0){
                        sum_friday = '0:00';
                    }else{
                        var str = ""+sum_friday+"";
                        var len = str.length;
                        var sum_friday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(4)").find('.total_day_5').val(sum_friday);



                    // Sum saturday
                    var sum_saturday = 0;
                    $(this).parents("div:eq(4)").find('.day_6').each(function(){
                        sum_saturday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_saturday / 60);          
                    var sum_minutes = sum_saturday % 60;
                    var sum_saturday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_saturday == 0){
                        sum_saturday = '0:00';
                    }else{
                        var str = ""+sum_saturday+"";
                        var len = str.length;
                        var sum_saturday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(4)").find('.total_day_6').val(sum_saturday);



                    // Sum sunday
                    var sum_sunday = 0;
                    $(this).parents("div:eq(4)").find('.day_7').each(function(){
                        sum_sunday += parseInt(this.value);
                    });

                    var sum_hours = Math.floor( sum_sunday / 60);          
                    var sum_minutes = sum_sunday % 60;
                    var sum_sunday = "" + pad(sum_hours,2) + pad(sum_minutes,2);

                    if(sum_sunday == 0){
                        sum_sunday = '0:00';
                    }else{
                        var str = ""+sum_sunday+"";
                        var len = str.length;
                        var sum_sunday = str.substring(0, len-2) + ":" + str.substring(len-2);
                    }

                    $(this).parents("div:eq(4)").find('.total_day_7').val(sum_sunday);
                    

                });

                // Submit & Validation
                $('#submit').click( function() {

                    var errors = 0;

                    $("div.nice-select").find("li[data-value='']").each(function(){
                        $(this).parents("div:eq(1)").removeClass('error');
                    });

                    $("div.nice-select").find("li[data-value=''].selected").each(function(){
                        $(this).parents("div:eq(1)").addClass('error');
                    });

                    function hasDuplicates(array) {
                        var allvalues = Object.create(null);
                        for (var i = 0; i < array.length; ++i) {
                            var value = array[i];

                            if (value in allvalues) {

                                $("#schedule").find("li[data-value='"+value+"'].selected").each(function(){
                                    $(this).parents("div:eq(1)").addClass('error');
                                });                                

                                return true;

                            }
                            
                            allvalues[value] = true;

                        }
                        return false;

                    }

                    $(".row-parent").each(function(){

                        var users = new Array();

                        $(this).find("li.selected").each(function(){

                            var datavalue = $(this).attr("data-value");

                            if(datavalue != 'open'){
                                var val = $(this).attr("data-value");
                                users.push(val);
                            }
                            
                        });

                        var duplicates = hasDuplicates(users);


                    });

                    /*$("div.nice-select").find("li[data-value='open'].selected").each(function(){
                        $(this).parents("div:eq(4)").removeClass('error');
                    });*/


                    $("html").find(".error").each(function(){
                        errors += 1;
                        addAlert(3,'<?php echo _("Employee is not selected. Please select an employee for your shift."); ?>');
                    });

                    $("html").find(".error_1").each(function(){
                        errors += 1;
                        addAlert(3,"<?php echo _("Something on your start and end time isn't right. Please check if the fields is correct."); ?>");
                    });

                    if(errors >= 1){
                        console.log('Great error!' + errors);
                    }else{
                        console.log('Great success!' + errors);
                        //addAlert(1,'Success');

					 	var data = $('#schedule').serialize();

					    $.ajax({ 
					        data: data, 
					        type: 'post',
					        url: '/actions/register_schedule.php',
					        success: function() {
					            reg_continue('success');
					        }
					    });

					    return false; 

                    }

                });


            </script>

        </div>


        <script type="text/javascript">

        $(document).ready(function() {  
            $(function() {
              $("#addMore_<?php echo $i; ?>").click(function(e) {
                e.preventDefault();
                $("#row_<?php echo $i; ?>").append("<?php echo $shift_week; ?>");
              });
            });

            $("#addMore_<?php echo $i; ?>").click(function() {
                $("#js_<?php echo $i; ?>").load("/actions/ajax/getjs.php?hs=<?php echo $hourstart; ?>&ms=<?php echo $minutestart; ?>&he=<?php echo $hourend; ?>&me=<?php echo $minuteend; ?>");
            });


        });
        </script>        

        <?php
        

    }
?>
            

        </div>

    </form>

</div>
