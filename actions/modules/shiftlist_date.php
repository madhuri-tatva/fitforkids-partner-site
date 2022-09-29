
<?php 
include("../../includes/config.php");

putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);

$date = $_GET['date'];
$current_year = substr($_GET['date'],0,4);

$holidays_observances = get_holidays_observances($current_year);

if(!empty($holidays_observances)){

    $array_holidays = $holidays_observances['Holidays'];
    $array_observances = $holidays_observances['Observances'];

}else{

    $array_holidays = 0;
    $array_observances = 0;

}

$shifts_overview = shift_overview_by_date($date,$_SESSION['CurrentDepartment'],$_SESSION['UserId']);
$shifts_groups = array_slice($shifts_overview,0,3);


    /*if(!empty($array_holidays[$date])){
        echo "<a class='holidays'>" . $array_holidays[$date] . "</a>";
    }

    if(!empty($array_observances[$date])){
        echo "<a class='observances'>" . $array_observances[$date] . "</a>";
    }*/

    if(!empty($array_holidays[$date])){
        echo "<div class='row holidays'>";
        echo "<div class='item'>";
        echo "<div class='item-header'><a>" . $array_holidays[$date] . "</a></div>";
        echo "</div>";
        echo "</div>";
    }

    if(!empty($array_observances[$date])){
        echo "<div class='row observances'>";
        echo "<div class='item'>";
        echo "<div class='item-header'><a>" . $array_observances[$date] . "</a></div>";
        echo "</div>";
        echo "</div>";
    }


    foreach($shifts_groups as $key => $shifts_group){

        // Sorting

        $shifts_sorted = array();
        $shifts_this_user = array();

        foreach($shifts_group as $shift){

            if($shift['UserId'] == $_SESSION['UserId']){
                $shifts_this_user[] = $shift;
            }else{
                $shifts_sorted[] = $shift;
            }

        }

        $shifts_final = array_merge($shifts_this_user, $shifts_sorted);

        foreach($shifts_final as $shift){

        $shift_data     = shift_by_id($shift['Id']);
        $user_data      = user_data_by_id($shift['UserId']);

        $shifttime      = convert_start_end_date($shift_data['Start'],$shift_data['End']);

        $start = strstr($shift_data['Start'], ' ');
        $start = explode(':', $start);

        $end = strstr($shift_data['End'], ' ');
        $end = explode(':', $end);

        $starthour = str_replace(' ', '', $start['0']);
        $endhour = str_replace(' ', '', $end['0']);

        $class = '';

            $absence_icon = '';

            if($key == 'Normal'){
                $class = 'normal';
            }elseif($key == 'Available'){
                $class = 'available';
            }elseif($key == 'Absence'){
                $class = 'absence';
                $user_original = user_data_by_id($shift_data['OriginalUserId']); 

                if($shift['AbsenceId'] != 0){
                    $absence_icon = get_absence_icon($shift['AbsenceId']);
                }
            }

    ?>

        <div class="row <?php echo $class; ?> <?php echo $class; ?> <?php if($_SESSION['Type'] == 3 && $_SESSION['UserId'] != $shift['UserId']){ echo "disable"; }elseif($_SESSION['Type'] == 3 && $_SESSION['UserId'] == $shift['UserId']){ echo "limited"; } ?>">
            <span class="unexpand"><i class="icon-save"></i></span>
            <span class="item">
                <?php if($_SESSION['Type'] < 3){ ?>
                <div class="item-actions">
                    <span class="btn-delete-shift"><img src="/assets/img/icon-cancel.png"></span>
                </div>
                <?php } ?>
                <div class="item-header">
                    <i><?php echo $absence_icon; ?></i>
                    <?php if($user_data == NULL){ ?>
                        <span><?php echo _('Open shift'); ?></span>
                    <?php }elseif($key == 'Absence'){ ?>
                        <span><?php echo $user_original['Firstname'] . " " . $user_original['Lastname']; ?></span>
                    <?php }else{ ?>
                        <span><?php echo $user_data['Firstname'] . " " . $user_data['Lastname']; ?></span>
                    <?php } ?>
                    
                    <span class="time-view"><?php echo $shifttime; ?></span>
                    <div class="time-edit">
                        <div class="time-inputs">

                            <input name="TimeHourStart" class="form-control field-starthour" value="<?php echo $starthour; ?>" <?php if($_SESSION['Type'] == 3){ echo 'disabled';} ?> />
                            <span class="char">:</span>
                            <input name="TimeMinuteStart" class="form-control field-startminute" value="<?php echo $start['1']; ?>" <?php if($_SESSION['Type'] == 3){ echo 'disabled';} ?> />
                            <span class="char">-</span>
                            <input name="TimeHourEnd" class="form-control field-endhour" value="<?php echo $endhour; ?>" <?php if($_SESSION['Type'] == 3){ echo 'disabled';} ?> />
                            <span class="char">:</span>
                            <input name="TimeMinuteEnd" class="form-control field-endminute" value="<?php echo $end['1']; ?>" <?php if($_SESSION['Type'] == 3){ echo 'disabled';} ?> />

                            <input type="hidden" class="field-date" value="<?php echo $shift_data['Date']; ?>" />
                            <input type="hidden" class="field-shiftid" value="<?php echo $shift_data['Id']; ?>" />
                            <input type="hidden" class="field-thisuser" value="<?php echo $user_data['Id']; ?>" />
                            <input type="hidden" class="field-originaluser" value="<?php echo $shift_data['OriginalUserId']; ?>" />
                        </div>
                    </div>

                </div>

                <div class="item-content">


                    <div class="form-group md-col-6 field-absence">
                        <label class="control-label col-sm-12 col-md-12" for="">
                            <?php echo _('Absence'); ?>
                        </label>
                        <div class="col-sm-8 col-md-12">
                            <select name="AbsenceId" class="form-control <?php if($shift['TakenDate'] != '0000-00-00 00:00:00'){ echo "disabled"; } ?>">
                              <?php
                                  echo "<option value='0'>". _('None') ."</option>";

                                  $db->where("CustomerId", 0);
                                  $standard_absence = $db->get('absence');
                                      foreach ($standard_absence as $data) { ?>
                                          <option value="<?php echo $data['Id']; ?>" <?php if($data['Id'] == $shift['AbsenceId']){ echo "selected";} ?>><?php echo _($data['Title']); ?></option>
                                      <?php }

                                  $db->where("CustomerId", $_SESSION['CustomerId']);
                                  $db->where("DepartmentId", 0);
                                  $standard_absence = $db->get('absence');
                                      foreach ($standard_absence as $data) { ?>
                                          <option value="<?php echo $data['Id']; ?>" <?php if($data['Id'] == $shift['AbsenceId']){ echo "selected";} ?>><?php echo $data['Title']; ?></option>
                                      <?php }

                                  $db->where("CustomerId", $_SESSION['CustomerId']);
                                  $db->where("DepartmentId", $_SESSION['DepartmentId']);
                                  $standard_absence = $db->get('absence');
                                      foreach ($standard_absence as $data) { ?>
                                          <option value="<?php echo $data['Id']; ?>" <?php if($data['Id'] == $shift['AbsenceId']){ echo "selected";} ?>><?php echo $data['Title']; ?></option>
                                      <?php }
                              ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group md-col-3">
                        <label class="control-label col-sm-12 col-md-12 hide-sm" for="">
                            &nbsp;
                        </label>
                        <div class="col-sm-8 col-md-12 pg-input-radio">
                            <input class="field-available" name="Available" type="checkbox" <?php if($shift['Available'] == 1){ echo "checked"; } ?> />
                            <span class='checkmark'></span>
                            <p><?php echo _('Make available'); ?></p>
                        </div>
                    </div>

                    <?php if($_SESSION['Type'] == 3){ ?>
                    <input type="hidden" class="field-currentuser" name="CurrentUser" value="<?php echo $shift['UserId'] ?>" ?>
                    <?php } ?>

                    <?php if($_SESSION['Type'] < 3){ ?>
                    <?php 
                    $users = users_by_department_id($shift['DepartmentId']);
                    ?>
                    <div class="row">
                        <div class="form-group md-col-12 field-currentuser">
                            <label class="control-label col-sm-12 col-md-12" for="">
                                <?php echo _('Current employee. Assign to another employee.'); ?>
                            </label>
                            <div class="col-sm-8 col-md-12">
                                <select name="CurrentUser" class="form-control">
                                    <?php if($user_data == NULL){ ?>
                                        <option value="0" selected><?php echo _('Open shift'); ?></option>
                                    <?php } ?>
                                    <?php foreach($users as $employees){ ?>
                                        <option value="<?php echo $employees['Id']; ?>" <?php if($employees['Id'] == $shift['UserId']){ echo "selected"; } ?>><?php echo $employees['Firstname'] . " " . $employees['Lastname']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group md-col-12">
                            <label class="control-label col-sm-12 col-md-12" for="">
                                Note
                            </label>
                            <div class="col-sm-8 col-md-12">
                                <textarea name="Note" class="field-note"><?php echo $shift['Note']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <? } ?>





                </div>
            </span>
        </div>

    <?php 
        }
    }
     ?>



<script type="text/javascript">

    $(".close-sidecontent").click(function(){
        $("#wrapper").removeClass("push");
        $("#sidecontent").removeClass("push");
    });

    $(".item .item-header").click(function(){
        $(this).parents("div:eq(0)").find('.item').addClass("expand");
        $(this).parents("div:eq(0)").find('.unexpand').addClass('active');
    });

    $(".unexpand").click(function(){
        //$(this).parents('.item').removeClass('expand');
        $(this).parents("div:eq(0)").find('.item').each(function(){
            $(this).removeClass('expand');

            var isChecked = $(this).find('.field-available').prop("checked");
            
            if(isChecked == true) {
                var available = 1;
            }else{
                var available = 0;
            }

            var absence = $(this).find('.field-absence .selected').attr("data-value");
            var currentuser = $(this).find('.field-currentuser .selected').attr("data-value");
            var note = $(this).find('.field-note').val();
            var starthour = $(this).find('.field-starthour').val();
            var startminute = $(this).find('.field-startminute').val();
            var endhour = $(this).find('.field-endhour').val();
            var endminute = $(this).find('.field-endminute').val();
            var shiftid = $(this).find('.field-shiftid').val();
            var thisuser = $(this).find('.field-thisuser').val();
            var originaluser = $(this).find('.field-originaluser').val();
            var date = $(this).find('.field-date').val();


            if(currentuser == undefined){
                var currentuser = $(this).find('.field-currentuser').val();
            }


            $.ajax({  
                type: 'POST',  
                url: 'https://www.plangy.com/actions/update_shift.php', 
                data: { 
                    TimeHourStart: starthour, 
                    TimeMinuteStart: startminute, 
                    TimeHourEnd: endhour, 
                    TimeMinuteEnd: endminute,
                    CurrentUser: currentuser,
                    UserId: thisuser,
                    OriginalUserId: originaluser,
                    Available: available,
                    AbsenceId: absence,
                    Note: note,
                    ShiftId: shiftid,
                    Date: date
                },
                success: function(response) {
                    //content.html(response);
                }
            });

            // Udating the whole calendar
            setTimeout(function() {
                $("#list").load("https://www.plangy.com/actions/modules/shiftlist_date.php?date="+date);
                $("#d"+date).load("https://www.plangy.com/actions/modules/shiftlist_date_miniature.php?date=d"+date);
            }, 500);

        });

        $(this).removeClass("active");

    });

    $('.btn-delete-shift').click( function() {
        console.log('Great success!');

        var shift_id = $(this).parents('div:eq(1)').find('.field-shiftid').val();
        var date = $(this).parents('div:eq(1)').find('.field-date').val();


        $.ajax({
          method: 'post',
          url: '/actions/ajax/crud_shift.php',
          data: {
            'ShiftId': shift_id,
            'ShiftActionType': 3,
          },
          success: function() {

            // Udating the whole calendar
            setTimeout(function() {
                $("#list").load("https://www.plangy.com/actions/modules/shiftlist_date.php?date="+date);
                $("#d"+date).load("https://www.plangy.com/actions/modules/shiftlist_date_miniature.php?date=d"+date);
            }, 500);

          }
        });
     
    });

    jQuery(function($) {
        $('select.form-control').niceSelect();
    });
    
    </script>
