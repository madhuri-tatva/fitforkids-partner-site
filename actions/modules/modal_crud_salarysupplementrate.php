<?php 
include("../../includes/config.php");

putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);


$type = $_GET['type'];
$id = $_GET['id'];


if(!empty($id)){
    $salary_data = salarysupplementrate_by_id($id);

    $salary_rule_data = salarysupplementrule_by_supplementid($salary_data['Id']);

}


if($type == 1 or $type == 2){ 

$db->where('CustomerId',$_SESSION['CustomerId']);
$db->orderBy("ReferenceId","desc");
$rate = $db->getOne('salarysupplementrates');
$increment_ref = $rate['ReferenceId'] + 1;


?>

<script>
jQuery(function($) {
    var date = $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' }).val();
});
</script>

<?php if($type == 1){ ?>
    <h1><?php echo _('Create supplement rate'); ?></h1>
<?php }elseif($type == 2){ ?>
    <h1><?php echo _('Edit supplement rate'); ?></h1>
<?php } ?>

<ul class="nav nav-tabs" role="tablist">
    <li id="nav-tab-supplementrate" role="presentation" class="active"><a href="#tab-supplementrate" aria-controls="tab-supplementrate" role="tab" data-toggle="tab"><?php echo _('Supplement rate'); ?> <span id="nav-tab-supplementrate-noti" class="notification"></span></a></li>
    <li id="nav-tab-supplementrules" role="presentation"><a href="#tab-supplementrules" aria-controls="tab-supplementrules" role="tab" data-toggle="tab"><?php echo _('Supplement rules'); ?> <span id="nav-tab-supplementrules-noti" class="notification"></span></a></li>
</ul>

<div class="tab-content">

    <div role="tabpanel" class="tab-pane fade in active" id="tab-supplementrate">

        <div class="row">

            <div class="md-col-4 form-group">
                <label><?php echo _('Supplement name'); ?></label>
                <input type="text" id="input-title" class="form-control" placeholder="<?php echo _('Name'); ?>" value="<?php if(!empty($salary_data)){ echo $salary_data['Title']; } ?>" />
            </div>

            <div class="md-col-4 form-group">
                <label><?php echo _('Amount'); ?></label>
                <input type="text" id="input-rate" class="form-control" placeholder="<?php echo _('Amount'); ?>" value="<?php if(!empty($salary_data)){ echo $salary_data['Rate']; } ?>" />
            </div>

            <div class="md-col-4 form-group">
                <label><?php echo _('Reference ID'); ?></label>
                <input type="text" id="input-reference" class="form-control" placeholder="<?php echo _('Ref. ID'); ?>" value="<?php if(!empty($salary_data)){ echo $salary_data['ReferenceId']; }else{ echo $increment_ref; } ?>" />
            </div>

        </div>

        <div class="row">

            <div class="md-col-12 form-group" id="input-supplementtype">
                <label><?php echo _('Supplement type'); ?></label>
                <select name="SupplementType" class="form-control">
                    <!--<option value="0" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 0){ echo "selected"; } } ?>><?php echo _('Choose supplement type'); ?></option>-->
                    <option value="1" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 1){ echo "selected"; } } ?>><?php echo _('Supplements before tax'); ?></option>
                    <option value="2" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 2){ echo "selected"; } } ?>><?php echo _('Supplements after tax'); ?></option>
                    <option value="3" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 3){ echo "selected"; } } ?>><?php echo _('Gross deduction - w. holiday pay reduction'); ?></option>
                    <option value="4" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 4){ echo "selected"; } } ?>><?php echo _('Deductions after tax'); ?></option>
                    <option value="5" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 5){ echo "selected"; } } ?>><?php echo _('Travel allowance'); ?></option>
                    <option value="6" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 6){ echo "selected"; } } ?>><?php echo _('Payment of holiday'); ?></option>
                    <option value="7" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 7){ echo "selected"; } } ?>><?php echo _('Supplements before tax (not pension and holiday entitled)'); ?></option>
                    <option value="8" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 8){ echo "selected"; } } ?>><?php echo _('Anniversary bonus'); ?></option>
                    <option value="9" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 9){ echo "selected"; } } ?>><?php echo _('Supplement before tax (without pension but holiday entitlement)'); ?></option>
                    <option value="10" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 10){ echo "selected"; } } ?>><?php echo _('Reimbursement of expenses'); ?></option>
                    <option value="11" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 11){ echo "selected"; } } ?>><?php echo _('Deduction gross income no vacation and pension'); ?></option>
                    <option value="12" <?php if(!empty($salary_data)){ if($salary_data['SupplementType'] == 12){ echo "selected"; } } ?>><?php echo _('Availability days'); ?></option>
                </select>
            </div>

        </div>

        <div class="row">

            <div class="md-col-6 form-group" id="input-type">
                <label><?php echo _('Rate type'); ?></label>
                <select name="Type" class="form-control">
                    <option value="1" <?php if(!empty($salary_data)){ if($salary_data['Type'] == 1){ echo "selected"; } } ?>><?php echo _('Hourly rate'); ?></option>
                    <option value="2" <?php if(!empty($salary_data)){ if($salary_data['Type'] == 2){ echo "selected"; } } ?>><?php echo _('Monthly rate'); ?></option>
                </select>
            </div>

            <div class="md-col-6 form-group" id="input-currency">
                <label><?php echo _('Currency'); ?></label>
                <select name="Currency" class="form-control">
                    <option value="DKK" <?php if(!empty($salary_data)){ if($salary_data['Currency'] == 'DKK'){ echo "selected"; } } ?>><?php echo _('DKK'); ?></option>
                </select>
            </div>

        </div>

        <div class="row">

            <div class="md-col-6 form-group" id="input-store">
                <label><?php echo _('Store restriction'); ?></label>
                <select name="DepartmentId" class="form-control">
                    <option value="0" <?php if(!empty($salary_data)){ if($salary_data['DepartmentId'] == 0){ echo "selected"; } } ?>><?php echo _('No restriction'); ?></option>
                <?php 
                    $data_departments = department_by_customer_id($_SESSION['CustomerId']);
                    //echo var_dump($data_users); 
                    foreach ($data_departments as $department){

                        $class = '';

                        if(!empty($salary_data)){
                            if($salary_data['DepartmentId'] == $department['Id']){ 
                                $class = 'selected';
                            }
                        }

                        echo "<option value='". $department['Id'] ."' " . $class . ">" . $department['Name'] . "</option>";
                    }
                ?>
                </select>
            </div>

            <div class="md-col-6 form-group" id="input-employeegroup">
                <label><?php echo _('Employee group restriction'); ?></label>
                <select name="EmployeeGroup" class="form-control">
                    <option value="0" <?php if(!empty($salary_data)){ if($salary_data['EmployeeGroup'] == 0){ echo "selected"; } } ?>><?php echo _('No restriction'); ?></option>
                <?php 
                
                    $data_titles = titles_by_department_id($_SESSION['CustomerId'], $_SESSION['CurrentDepartment']);

                    foreach ($data_titles as $title){

                        $class = '';

                        if(!empty($salary_data)){
                            if($salary_data['EmployeeGroup'] == $title['Id']){ 
                                $class = 'selected';
                            }
                        }

                        echo "<option value='". $title['Id'] ."' " . $class . ">" . _('Only') . " " . $title['Title'] . "</option>";
                    }
                    
                ?>
                </select>
            </div>

        </div>

    </div>

    <div role="tabpanel" class="tab-pane fade in" id="tab-supplementrules">

        <div class="row modal-supplementrules">

            <?php if(empty($salary_rule_data)){ ?>
            <div class="message"><?php echo _('There are no rules for this suppliment rate yet'); ?></div>
            <?php }else{

                $salary_rule_weekdays = substr($salary_rule_data['Weekdays'],1,13);
                $salary_rule_weekdays = explode(',',$salary_rule_weekdays);

            } ?>

            <div id="new-supplementrule" class="new-supplementrule">
                <div class="row divider">

                    <div id="alertmodal"></div>

                    <div class="row">
                        <label><?php echo _('Specific days of the week'); ?></label>
                    </div>

                    <div class="md-col-3  pg-input-radio" id="input-supplementdays-monday">
                        <input name="" id="field-supple-monday" class="field-supple-monday" type="checkbox" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_weekdays[0] == 1){ echo "checked"; } } ?> />
                        <span class='checkmark'></span>
                        <p><?php echo _('Monday'); ?></p>
                    </div>

                    <div class="md-col-3  pg-input-radio" id="input-supplementdays-tuesday">
                        <input name="" id="field-supple-tuesday" class="field-supple-tuesday" type="checkbox" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_weekdays[1] == 1){ echo "checked"; } } ?> />
                        <span class='checkmark'></span>
                        <p><?php echo _('Tuesday'); ?></p>
                    </div>

                    <div class="md-col-3  pg-input-radio" id="input-supplementdays-wednesday">
                        <input name="" id="field-supple-wednesday" class="field-supple-wednesday" type="checkbox" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_weekdays[2] == 1){ echo "checked"; } } ?> />
                        <span class='checkmark'></span>
                        <p><?php echo _('Wednesday'); ?></p>
                    </div>

                    <div class="md-col-3  pg-input-radio" id="input-supplementdays-thursday">
                        <input name="" id="field-supple-thursday" class="field-supple-thursday" type="checkbox" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_weekdays[3] == 1){ echo "checked"; } } ?> />
                        <span class='checkmark'></span>
                        <p><?php echo _('Thursday'); ?></p>
                    </div>

                    <div class="md-col-3  pg-input-radio" id="input-supplementdays-friday">
                        <input name="" id="field-supple-friday" class="field-supple-friday" type="checkbox" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_weekdays[4] == 1){ echo "checked"; } } ?> />
                        <span class='checkmark'></span>
                        <p><?php echo _('Friday'); ?></p>
                    </div>

                    <div class="md-col-3  pg-input-radio" id="input-supplementdays-saturday">
                        <input name="" id="field-supple-saturday" class="field-supple-saturday" type="checkbox" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_weekdays[5] == 1){ echo "checked"; } } ?> />
                        <span class='checkmark'></span>
                        <p><?php echo _('Saturday'); ?></p>
                    </div>

                    <div class="md-col-3  pg-input-radio" id="input-supplementdays-sunday">
                        <input name="" id="field-supple-sunday" class="field-supple-sunday" type="checkbox" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_weekdays[6] == 1){ echo "checked"; } } ?> />
                        <span class='checkmark'></span>
                        <p><?php echo _('Sunday'); ?></p>
                    </div>

                </div>

                <div class="row">

                    <div class="md-col-3 form-group">

                        <div class="pg-input-radio pg-input-switch switch-block">
                            <div class="btn-switch">

                                <div class="switch-text">
                                    <p><?php echo _('Apply after'); ?></p>
                                </div>

                                <input name="" id="field-supple-applyafter" class="field-supple-applyafter" type="checkbox" id="" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_data['TimeFrom'] != '00:00:00'){ echo "checked"; } } ?>>
                                <span class="checkmark"></span>

                                <div class="switch-expand-outer field-time">
                                    <?php if(!empty($salary_rule_data)){ 

                                        if($salary_rule_data['TimeFrom'] != '00:00:00'){
                                            
                                            $salary_rule_timefrom = substr($salary_rule_data['TimeFrom'], 0, 5);
                                            $salary_rule_timefrom = explode(":",$salary_rule_timefrom);

                                        }

                                    } ?>

                                    <div class="row">
                                        <div class="md-col-3">
                                            <?php echo _('Kl.'); ?>
                                        </div>
                                        <div class="md-col-4">
                                            <input type="text" id="field-supple-applyafter-hour" class="form-control field-supple-applyafter-hour" placeholder="hh" value="<?php if(!empty($salary_rule_data)){ if($salary_rule_data['TimeFrom'] != '00:00:00'){ echo $salary_rule_timefrom[0]; }  } ?>" />
                                        </div>
                                        <div class="md-col-1">
                                            <?php echo ":"; ?>
                                        </div>
                                        <div class="md-col-4">
                                            <input type="text" id="field-supple-applyafter-minute" class="form-control field-supple-applyafter-minute" placeholder="mm" value="<?php if(!empty($salary_rule_data)){ if($salary_rule_data['TimeFrom'] != '00:00:00'){ echo $salary_rule_timefrom[1]; }  } ?>" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <p><?php echo _('This supplement rate will only be applied after the above time.'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="md-col-3 form-group">

                        <div class="form-group pg-input-radio pg-input-switch switch-block">
                            <div class="btn-switch">

                                <div class="switch-text">
                                    <p><?php echo _('Stop after'); ?></p>
                                </div>

                                <input name="" id="field-supple-stop" class="field-supple-stop" type="checkbox" id="" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_data['TimeTo'] != '00:00:00'){ echo "checked"; } } ?>>
                                <span class="checkmark"></span>

                                <div class="switch-expand-outer field-time">
                                    <?php if(!empty($salary_rule_data)){ 

                                        if($salary_rule_data['TimeTo'] != '00:00:00'){
                                            
                                            $salary_rule_timeto = substr($salary_rule_data['TimeTo'], 0, 5);
                                            $salary_rule_timeto = explode(":",$salary_rule_timeto);

                                        }

                                    } ?>

                                    <div class="row">
                                        <div class="md-col-3">
                                            <?php echo _('Kl.'); ?>
                                        </div>
                                        <div class="md-col-4">
                                            <input type="text" id="field-supple-stop-hour" class="form-control field-supple-stop-hour" placeholder="hh" value="<?php if(!empty($salary_rule_data)){ if($salary_rule_data['TimeTo'] != '00:00:00'){ echo $salary_rule_timeto[0]; }  } ?>" />
                                        </div>
                                        <div class="md-col-1">
                                            <?php echo ":"; ?>
                                        </div>
                                        <div class="md-col-4">
                                            <input type="text" id="field-supple-stop-minute" class="form-control field-supple-stop-minute" placeholder="mm" value="<?php if(!empty($salary_rule_data)){ if($salary_rule_data['TimeTo'] != '00:00:00'){ echo $salary_rule_timeto[1]; }  } ?>" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <p><?php echo _('This supplement rate will stop after the above time.'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="md-col-3 form-group">

                        <div class="pg-input-radio pg-input-switch switch-block">
                            <div class="btn-switch">

                                <div class="switch-text">
                                    <p><?php echo _('Holidays'); ?></p>
                                </div>

                                <input name="" id="field-supple-holidays" class="field-supple-holidays" type="checkbox" id="" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_data['Holidays'] != '0'){ echo "checked"; } } ?>>
                                <span class="checkmark"></span>

                                <div class="switch-expand-outer">

                                    <div class="row">
                                        <div class="md-col-12">
                                            <p><?php echo _('This supplement rate will only be applied on official holidays.'); ?></p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="md-col-3 form-group">

                        <div class="pg-input-radio pg-input-switch switch-block">
                            <div class="btn-switch">

                                <div class="switch-text">
                                    <p><?php echo _('Limited period'); ?></p>
                                </div>

                                <input name="" id="field-supple-period" class="field-supple-period" type="checkbox" id="" value="1" <?php if(!empty($salary_rule_data)){ if($salary_rule_data['DateFrom'] != '0000-00-00'){ echo "checked"; } } ?>>
                                <span class="checkmark"></span>

                                <div class="switch-expand-outer">

                                    <div class="row">
                                        <div class="md-col-12 date">
                                            <label><?php echo _('From'); ?></label>
                                            <input type="text" id="field-supple-period-start" class="form-control datepicker field-supple-period-start" placeholder="dd-mm-yyyy" value="<?php if(!empty($salary_rule_data)){ if($salary_rule_data['DateFrom'] != '0000-00-00'){ echo $salary_rule_data['DateFrom']; } } ?>" />
                                            <label><?php echo _('Till'); ?></label>
                                            <input type="text" id="field-supple-period-end" class="form-control datepicker field-supple-period-end" placeholder="dd-mm-yyyy" value="<?php if(!empty($salary_rule_data)){ if($salary_rule_data['DateTo'] != '0000-00-00'){ echo $salary_rule_data['DateTo']; } } ?>" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <p><?php echo _('This supplement rate will only be applied within the above period.'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                </div>
            </div>

        </div>

    </div>

</div>

<div class="row bottom-actions">

    <div class="md-col-12 center">
        <a class="btn-cta" id="btn-salarysupplement">
            <?php 
            if($type == 1){
                echo _('Create supplement'); 
            }elseif($type == 2){
                echo _('Edit supplement'); 
            }
            ?> 
        </a>
    </div>

</div>

<?php }elseif($type == 3){  ?>


<h1><?php echo _('Remove') . " [" . $salary_data['Title'] . "]"; ?></h1>
<p><?php echo _('This rate will not be completely deleted as if might be used for previous salaries.'); ?></p>
<p><?php echo _('When removing this rate it will also be removed from the users who has this rate assigned.'); ?></p>
<a class="btn-cancel" id="btn-cancel"><?php echo _('Remove'); ?></a>

<?php } ?>




<script type="text/javascript">

$('#btn-add-rule').click( function() {

    addAlertModal(0,"");
    var errors = 0;

    var isChecked = 0;
    var isChecked_applyafter = 0;
    var isChecked_stop = 0;

    var supply_actiontype = <?php echo $type; ?>;

    $('html').parents("div:eq(0)").find('.row').each(function(){
        $('input').removeClass('error_1');
    });
    $('html').parents("div:eq(0)").find('.row').each(function(){
        $('input').removeClass('error_2');
    });

    // Days
    isChecked = $("#field-supple-monday").prop("checked");
    if(isChecked == true) {
        var supple_mon = 1;
    }else{
        var supple_mon = 0;
    }

    isChecked = $("#field-supple-tuesday").prop("checked");
    if(isChecked == true) {
        var supple_tue = 1;
    }else{
        var supple_tue = 0;
    }

    isChecked = $("#field-supple-wednesday").prop("checked");
    if(isChecked == true) {
        var supple_wed = 1;
    }else{
        var supple_wed = 0;
    }

    isChecked = $("#field-supple-thursday").prop("checked");
    if(isChecked == true) {
        var supple_thu = 1;
    }else{
        var supple_thu = 0;
    }

    isChecked = $("#field-supple-friday").prop("checked");
    if(isChecked == true) {
        var supple_fri = 1;
    }else{
        var supple_fri = 0;
    }

    isChecked = $("#field-supple-saturday").prop("checked");
    if(isChecked == true) {
        var supple_sat = 1;
    }else{
        var supple_sat = 0;
    }

    isChecked = $("#field-supple-sunday").prop("checked");
    if(isChecked == true) {
        var supple_sun = 1;
    }else{
        var supple_sun = 0;
    }

    // Holidays
    isChecked = $("#field-supple-holidays").prop("checked");
    if(isChecked == true) {
        var supple_holidays = 1;
    }else{
        var supple_holidays = 0;
    }

    // Apply after
    isChecked_applyafter = $("#field-supple-applyafter").prop("checked");
    if(isChecked_applyafter == true) {
        var supple_applyafter_hour = $('#field-supple-applyafter-hour').val();
        var supple_applyafter_minute = $('#field-supple-applyafter-minute').val();

        if(supple_applyafter_hour == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-applyafter-hour').addClass('error');
        }

        if(supple_applyafter_minute == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-applyafter-minute').addClass('error');
        }

    }else{
        var supple_applyafter_hour = 0;
        var supple_applyafter_minute = 0;
    } 

    // Stop
    isChecked_stop = $("#field-supple-stop").prop("checked");
    if(isChecked_stop == true) {
        var supple_stop_hour = $('#field-supple-stop-hour').val();
        var supple_stop_minute = $('#field-supple-stop-minute').val();

        if(supple_stop_hour == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-stop-hour').addClass('error');
        }

        if(supple_stop_minute == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-stop-minute').addClass('error');
        }

    }else{
        var supple_stop_hour = 0;
        var supple_stop_minute = 0;
    } 

    // Limited period
    isChecked = $("#field-supple-period").prop("checked");
    if(isChecked == true) {
        var supple_period_start = $('#field-supple-period-start').val();
        var supple_period_end = $('#field-supple-period-end').val();

        if(supple_period_start == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-period-start').addClass('error');
        }

        if(supple_period_end == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-period-end').addClass('error');
        }

    }else{
        var supple_period_start = 0;
        var supple_period_end = 0;
    } 


    // Validation
    var supple_applyafter_hour_int      = Number.isInteger(parseInt(+supple_applyafter_hour));
    var supple_applyafter_minute_int    = Number.isInteger(parseInt(+supple_applyafter_minute));
    var supple_stop_hour_int            = Number.isInteger(parseInt(+supple_stop_hour));
    var supple_stop_minute_int          = Number.isInteger(parseInt(+supple_stop_minute));


    if(supple_applyafter_hour_int == false){
        addAlertModal(3,"<?php echo _('The start hour has to be a number.'); ?>");
        $('.field-supple-applyafter-hour').addClass('error');
    }

    if(supple_applyafter_hour > 24){
        addAlertModal(3,"<?php echo _('The start hour has to be below 24.'); ?>");
        $('.field-supple-applyafter-hour').addClass('error');
    }


    if(supple_applyafter_minute_int == false){
        addAlertModal(3,"<?php echo _('The start minute has to be a number.'); ?>");
        $('.field-supple-applyafter-minute').addClass('error');
    }

    if(supple_applyafter_minute > 60){
        addAlertModal(3,"<?php echo _('The start minute has to be below 60.'); ?>");
        $('.field-supple-applyafter-minute').addClass('error');
    }


    if(supple_stop_hour_int == false){
        addAlertModal(3,"<?php echo _('The stop hour has to be a number.'); ?>");
        $('.field-supple-stop-hour').addClass('error');
    }

    if(supple_stop_hour > 24){
        addAlertModal(3,"<?php echo _('The stop hour has to be below 24'); ?>");
        $('.field-supple-stop-hour').addClass('error');
    }


    if(supple_stop_minute_int == false){
        addAlertModal(3,"<?php echo _('The stop minute has to be a number.'); ?>");
        $('.field-supple-stop-minute').addClass('error');
    }

    if(supple_stop_minute > 60){
        addAlertModal(3,"<?php echo _('The stop minute has to be below 60.'); ?>");
        $('.field-supple-stop-minute').addClass('error');
    }


    if(supple_period_start > supple_period_end){
        addAlertModal(3,"<?php echo _("The start date can't be later than the end date."); ?>");
        $('.field-supple-period-start').addClass('error');
        $('.field-supple-period-end').addClass('error');
    }

    if(isChecked_applyafter == true && isChecked_stop == true){

        var supple_applyafter = pad(supple_applyafter_hour,2) + pad(supple_applyafter_minute,2) + '';
        var supple_stop = pad(supple_stop_hour,2) + pad(supple_stop_minute,2) + '';

        console.log(supple_applyafter);
        console.log(supple_stop);

        if(supple_applyafter > supple_stop){
            addAlertModal(3,"<?php echo _("The start time can't be later than the end time of the shift."); ?>");
            $('.field-supple-period-start').addClass('error');
            $('.field-supple-period-end').addClass('error');
        }

    }


    $('html').find('.error').each(function(){
        errors += 1;
    });


    if(errors > 0){
        $('#nav-tab-supplementrules').addClass('nav-error');
        document.getElementById('nav-tab-supplementrules-noti').innerHTML = errors;
    }

    /*
    console.log('Console log:');
    console.log(supply_actiontype);
    console.log('Days');
    console.log(supple_mon);
    console.log(supple_tue);
    console.log(supple_wed);
    console.log(supple_thu);
    console.log(supple_fri);
    console.log(supple_sat);
    console.log(supple_sun);
    console.log('Holidays');
    console.log(supple_holidays);
    console.log('Apply after');
    console.log(supple_applyafter_hour);
    console.log(supple_applyafter_minute);
    console.log('Stop after');
    console.log(supple_stop_hour);
    console.log(supple_stop_minute);
    console.log('Period');
    console.log(supple_period_start);
    console.log(supple_period_end);
    */

});

$('#btn-cancel').click( function() {

    var salary_id               = $('#ModalSalarySupplementDeleteId').val();
    var salary_store            = $('#ModalSalarySupplementDeleteStoreId').val();

    $.ajax({
      method: 'post',
      url: '/actions/ajax/crud_salarysupplementrate.php',
      data: {
        'ActionType': 3,
        'SalaryId': salary_id,
        'SalaryDepartmentId': salary_store
      },
      success: function() {
        window.location.replace('https://www.plangy.com/salary');
      }
    });

});

$('#btn-salarysupplement').click( function() {

    addAlertModal(0,"");
    var errors = 0;



    $('#tab-supplementrate').parents("div:eq(0)").find('.row').each(function(){
        $('input').removeClass('error_1');
    });
    
    $('#tab-supplementrules').parents("div:eq(0)").find('.row').each(function(){
        $('input').removeClass('error_2');
    });

    var salary_actiontype       = $('#ModalSupplementActionType').val();
    var salary_id               = $('#ModalSalarySupplementRateId').val();
    var salary_title            = $('#input-title').val();
    var salary_reference        = $('#input-reference').val();
    var salary_rate             = $('#input-rate').val();
    var salary_overwrite        = 0;
    var salary_atp              = 0;

    $("#input-currency").find("li.selected").each(function(){
        salary_currency = $(this).attr("data-value");  
    });

    $("#input-supplementtype").find("li.selected").each(function(){
        salary_supplementtype = $(this).attr("data-value");  
    });

    $("#input-type").find("li.selected").each(function(){
        salary_type = $(this).attr("data-value");  
    });

    $("#input-store").find("li.selected").each(function(){
        salary_store = $(this).attr("data-value");  
    });

    $("#input-employeegroup").find("li.selected").each(function(){
        salary_employeegroup = $(this).attr("data-value");  
    });

    if(salary_title == ''){
        $('#input-title').addClass('error_1');
        errors += 1;
    }

    if(salary_rate == ''){
        $('#input-rate').addClass('error_1');
        errors += 1;
    }

    if(salary_reference == ''){
        $('#input-reference').addClass('error_1');
        errors += 1;
    }

    if(salary_supplementtype == 0){
        $('#input-supplementtype').addClass('error_1');
        errors += 1;
    }





    // Supplement rules start

    var isChecked = 0;
    var isChecked_applyafter = 0;
    var isChecked_stop = 0;

    var supply_actiontype = <?php echo $type; ?>;



    // Days
    isChecked = $("#field-supple-monday").prop("checked");
    if(isChecked == true) {
        var supple_mon = 1;
    }else{
        var supple_mon = 0;
    }

    isChecked = $("#field-supple-tuesday").prop("checked");
    if(isChecked == true) {
        var supple_tue = 1;
    }else{
        var supple_tue = 0;
    }

    isChecked = $("#field-supple-wednesday").prop("checked");
    if(isChecked == true) {
        var supple_wed = 1;
    }else{
        var supple_wed = 0;
    }

    isChecked = $("#field-supple-thursday").prop("checked");
    if(isChecked == true) {
        var supple_thu = 1;
    }else{
        var supple_thu = 0;
    }

    isChecked = $("#field-supple-friday").prop("checked");
    if(isChecked == true) {
        var supple_fri = 1;
    }else{
        var supple_fri = 0;
    }

    isChecked = $("#field-supple-saturday").prop("checked");
    if(isChecked == true) {
        var supple_sat = 1;
    }else{
        var supple_sat = 0;
    }

    isChecked = $("#field-supple-sunday").prop("checked");
    if(isChecked == true) {
        var supple_sun = 1;
    }else{
        var supple_sun = 0;
    }

    // Holidays
    isChecked = $("#field-supple-holidays").prop("checked");
    if(isChecked == true) {
        var supple_holidays = 1;
    }else{
        var supple_holidays = 0;
    }

    // Apply after
    isChecked_applyafter = $("#field-supple-applyafter").prop("checked");
    if(isChecked_applyafter == true) {
        var supple_applyafter_hour = $('#field-supple-applyafter-hour').val();
        var supple_applyafter_minute = $('#field-supple-applyafter-minute').val();

        if(supple_applyafter_hour == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-applyafter-hour').addClass('error_2');
        }

        if(supple_applyafter_minute == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-applyafter-minute').addClass('error_2');
        }

    }else{
        var supple_applyafter_hour = 0;
        var supple_applyafter_minute = 0;
    } 

    // Stop
    isChecked_stop = $("#field-supple-stop").prop("checked");
    if(isChecked_stop == true) {
        var supple_stop_hour = $('#field-supple-stop-hour').val();
        var supple_stop_minute = $('#field-supple-stop-minute').val();

        if(supple_stop_hour == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-stop-hour').addClass('error_2');
        }

        if(supple_stop_minute == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-stop-minute').addClass('error_2');
        }

    }else{
        var supple_stop_hour = 0;
        var supple_stop_minute = 0;
    } 

    // Limited period
    isChecked = $("#field-supple-period").prop("checked");
    if(isChecked == true) {
        var supple_period_start = $('#field-supple-period-start').val();
        var supple_period_end = $('#field-supple-period-end').val();

        if(supple_period_start == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-period-start').addClass('error_2');
        }

        if(supple_period_end == ''){
            addAlertModal(3,"<?php echo _('This field cannot be empty.'); ?>");
            $('.field-supple-period-end').addClass('error_2');
        }

    }else{
        var supple_period_start = 0;
        var supple_period_end = 0;
    } 


    // Validation
    var supple_applyafter_hour_int      = Number.isInteger(parseInt(+supple_applyafter_hour));
    var supple_applyafter_minute_int    = Number.isInteger(parseInt(+supple_applyafter_minute));
    var supple_stop_hour_int            = Number.isInteger(parseInt(+supple_stop_hour));
    var supple_stop_minute_int          = Number.isInteger(parseInt(+supple_stop_minute));


    if(supple_applyafter_hour_int == false){
        addAlertModal(3,"<?php echo _('The start hour has to be a number.'); ?>");
        $('.field-supple-applyafter-hour').addClass('error_2');
    }

    if(supple_applyafter_hour > 24){
        addAlertModal(3,"<?php echo _('The start hour has to be below 24.'); ?>");
        $('.field-supple-applyafter-hour').addClass('error_2');
    }


    if(supple_applyafter_minute_int == false){
        addAlertModal(3,"<?php echo _('The start minute has to be a number.'); ?>");
        $('.field-supple-applyafter-minute').addClass('error_2');
    }

    if(supple_applyafter_minute > 60){
        addAlertModal(3,"<?php echo _('The start minute has to be below 60.'); ?>");
        $('.field-supple-applyafter-minute').addClass('error_2');
    }


    if(supple_stop_hour_int == false){
        addAlertModal(3,"<?php echo _('The stop hour has to be a number.'); ?>");
        $('.field-supple-stop-hour').addClass('error_2');
    }

    if(supple_stop_hour > 24){
        addAlertModal(3,"<?php echo _('The stop hour has to be below 24'); ?>");
        $('.field-supple-stop-hour').addClass('error_2');
    }


    if(supple_stop_minute_int == false){
        addAlertModal(3,"<?php echo _('The stop minute has to be a number.'); ?>");
        $('.field-supple-stop-minute').addClass('error_2');
    }

    if(supple_stop_minute > 60){
        addAlertModal(3,"<?php echo _('The stop minute has to be below 60.'); ?>");
        $('.field-supple-stop-minute').addClass('error_2');
    }


    if(supple_period_start > supple_period_end){
        addAlertModal(3,"<?php echo _("The start date can't be later than the end date."); ?>");
        $('.field-supple-period-start').addClass('error_2');
        $('.field-supple-period-end').addClass('error_2');
    }

    if(isChecked_applyafter == true && isChecked_stop == true){

        var supple_applyafter = pad(supple_applyafter_hour,2) + pad(supple_applyafter_minute,2) + '';
        var supple_stop = pad(supple_stop_hour,2) + pad(supple_stop_minute,2) + '';

        console.log(supple_applyafter);
        console.log(supple_stop);

        if(supple_applyafter > supple_stop){
            addAlertModal(3,"<?php echo _("The start time can't be later than the end time of the shift."); ?>");
            $('.field-supple-period-start').addClass('error_2');
            $('.field-supple-period-end').addClass('error_2');
        }

    }

    var errors_1 = 0;
    var errors_2 = 0;


    $('html').find('.error_1').each(function(){
        errors_1 += 1;
    });

    $('html').find('.error_2').each(function(){
        errors_2 += 1;
    });


    if(errors_1 > 0){
        $('#nav-tab-supplementrate').addClass('nav-error');
        document.getElementById('nav-tab-supplementrate-noti').innerHTML = errors_1;
    }else{
        $('#nav-tab-supplementrate').removeClass('nav-error');
        document.getElementById('nav-tab-supplementrate-noti').innerHTML = '';
    }

    if(errors_2 > 0){
        $('#nav-tab-supplementrules').addClass('nav-error');
        document.getElementById('nav-tab-supplementrules-noti').innerHTML = errors_2;
    }else{
        $('#nav-tab-supplementrules').removeClass('nav-error');
        document.getElementById('nav-tab-supplementrules-noti').innerHTML = '';
    }

    // Supplement rules end




    errors = errors_1 + errors_2;


    if(errors == 0){

        $.get("/actions/ajax/checksalarysupplementreference.php?ref=" + salary_reference + "&id=" + salary_id, function(data) {
            var data_from_ajax = data;

            if(data_from_ajax == 1){
                $('#input-reference').addClass('error_1');
                errors += 1;
            }else{

                if(errors < 1){

                    console.log('Great success!');

                    var supple_weekdays = '{' + supple_mon + ',' + supple_tue + ',' + supple_wed + ',' + supple_thu + ',' + supple_fri + ',' + supple_sat + ',' + supple_sun + '}';

                    console.log(supple_weekdays);

                    $.ajax({
                      method: 'post',
                      url: '/actions/ajax/crud_salarysupplementrate.php',
                      data: {
                        'ActionType': salary_actiontype,
                        'SalaryId': salary_id,
                        'SalaryDepartmentId': salary_store,
                        'SalaryTitle': salary_title,
                        'SalaryType': salary_type,
                        'SalarySupplementType': salary_supplementtype,
                        'SalaryReferenceId': salary_reference,
                        'SalaryRate': salary_rate,
                        'SalaryCurrency': salary_currency,
                        'SalaryOverwrite': salary_overwrite,
                        'SalaryATP': salary_atp,
                        'SalaryEmployeeGroup': salary_employeegroup,
                        'SupplementWeekdays': supple_weekdays,
                        'SupplementHolidays': supple_holidays,
                        'SupplementApplyAfterHour': supple_applyafter_hour,
                        'SupplementApplyAfterMinute': supple_applyafter_minute,
                        'SupplementStopHour': supple_stop_hour,
                        'SupplementStopMinute': supple_stop_minute,
                        'SupplementPeriodStart': supple_period_start,
                        'SupplementPeriodEnd': supple_period_end
                      },
                      success: function() {
                        window.location.replace('https://www.plangy.com/salary');
                      }
                    });

                }else{
                    console.log('Great error!');
                }
     

            }
        });

    }

});

jQuery(function($) {
    $('select.form-control').niceSelect();
});


</script>