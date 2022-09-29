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
    $salary_data = salaryrate_by_id($id);
}


if($type == 1){ 

$db->where('CustomerId',$_SESSION['CustomerId']);
$db->orderBy("ReferenceId","desc");
$rate = $db->getOne('salaryrates');
$increment_ref = $rate['ReferenceId'] + 1;


?>

<h1><?php echo _('Create salary rate'); ?></h1>

<div class="row">

    <div class="md-col-4 form-group">
        <label><?php echo _('Salary name'); ?></label>
        <input type="text" id="input-title" class="form-control" placeholder="<?php echo _('Name'); ?>" />
    </div>

    <div class="md-col-4 form-group">
        <label><?php echo _('Amount'); ?></label>
        <input type="text" id="input-rate" class="form-control" placeholder="<?php echo _('Amount'); ?>" />
    </div>

    <div class="md-col-4 form-group">
        <label><?php echo _('Reference ID'); ?></label>
        <input type="text" id="input-reference" class="form-control" placeholder="<?php echo _('Ref. ID'); ?>" value="<?php echo $increment_ref; ?>" />
    </div>

</div>

<div class="row">

    <div class="md-col-6 form-group" id="input-type">
        <label><?php echo _('Rate type'); ?></label>
        <select name="Type" class="form-control">
            <option value="1"><?php echo _('Hourly rate'); ?></option>
            <option value="2"><?php echo _('Monthly rate'); ?></option>
        </select>
    </div>

    <div class="md-col-6 form-group" id="input-currency">
        <label><?php echo _('Currency'); ?></label>
        <select name="Currency" class="form-control">
            <option value="DKK"><?php echo _('DKK'); ?></option>
        </select>
    </div>

</div>

<div class="row">

    <div class="md-col-6 form-group" id="input-store">
        <label><?php echo _('Store restriction'); ?></label>
        <select name="DepartmentId" class="form-control">
            <option value="0"><?php echo _('All stores'); ?></option>
        <?php 
            $data_departments = department_by_customer_id($_SESSION['CustomerId']);
            //echo var_dump($data_users); 

            foreach ($data_departments as $department){
                echo "<option value='". $department['Id'] ."'>" . $department['Name'] . "</option>";
            }
        ?>
        </select>
    </div>

    <div class="md-col-6 form-group" id="input-employeegroup">
        <label><?php echo _('Employee group restriction'); ?></label>
        <select name="EmployeeGroup" class="form-control">
            <option value="0"><?php echo _('No restriction'); ?></option>
        <?php 
        
            $data_titles = titles_by_department_id($_SESSION['CustomerId'], $_SESSION['CurrentDepartment']);
            //echo var_dump($data_users); 

            foreach ($data_titles as $title){
                echo "<option value='". $title['Id'] ."'>" . _('Only') . " " . $title['Title'] . "</option>";
            }
            
        ?>
        </select>
    </div>

</div>

<div class="row">

    <div class="md-col-12 center">
        <a class="btn-cta" id="btn-salaryrate">
            <?php 
            if($type == 1){
                echo _('Create salary rate'); 
            }elseif($type == 2){
                echo _('Edit salary rate'); 
            }
            ?>    
        </a>
    </div>

</div>

<?php }elseif($type == 2){ 

?>

<h1><?php echo _('Edit salary rate'); ?></h1>

<div class="row">

    <div class="md-col-4 form-group">
        <label><?php echo _('Salary name'); ?></label>
        <input type="text" id="input-title" class="form-control" placeholder="<?php echo _('Name'); ?>" value="<?php echo $salary_data['Title']; ?>" />
    </div>

    <div class="md-col-4 form-group">
        <label><?php echo _('Rate'); ?></label>
        <input type="text" id="input-rate" class="form-control" placeholder="<?php echo _('Amount'); ?>" value="<?php echo $salary_data['Rate']; ?>" />
    </div>

    <div class="md-col-4 form-group">
        <label><?php echo _('Reference ID'); ?></label>
        <input type="text" id="input-reference" class="form-control" placeholder="<?php echo _('Ref. ID'); ?>" value="<?php echo $salary_data['ReferenceId']; ?>" />
    </div>

</div>

<div class="row">

    <div class="md-col-6 form-group" id="input-type">
        <label><?php echo _('Rate type'); ?></label>
        <select name="Type" class="form-control">
            <option value="1" <?php if($salary_data['Type'] == 1){ echo "selected"; } ?>><?php echo _('Hourly rate'); ?></option>
            <option value="2" <?php if($salary_data['Type'] == 2){ echo "selected"; } ?>><?php echo _('Monthly rate'); ?></option>
        </select>
    </div>

    <div class="md-col-6 form-group" id="input-currency">
        <label><?php echo _('Currency'); ?></label>
        <select name="Currency" class="form-control">
            <option value="DKK" <?php if($salary_data['Currency'] == 'DKK'){ echo "selected"; } ?>><?php echo _('DKK'); ?></option>
        </select>
    </div>

</div>

<div class="row">

    <div class="md-col-6 form-group" id="input-store">
        <label><?php echo _('Store restriction'); ?></label>
        <select name="DepartmentId" class="form-control">
            <option value="0" <?php if($salary_data['DepartmentId'] == 0){ echo "selected"; } ?>><?php echo _('All stores'); ?></option>
        <?php 
            $data_departments = department_by_customer_id($_SESSION['CustomerId']);

            foreach ($data_departments as $department){

                $class = '';

                if($salary_data['DepartmentId'] == $department['Id']){ 
                    $class = 'selected';
                }

                echo "<option value='". $department['Id'] ."' " . $class . ">" . $department['Name'] . "</option>";
            }
        ?>
        </select>
    </div>

    <div class="md-col-6 form-group" id="input-employeegroup">
        <label><?php echo _('Employee group restriction'); ?></label>
        <select name="EmployeeGroup" class="form-control">
            <option value="0" <?php if($salary_data['EmployeeGroup'] == 0){ echo "selected"; } ?>><?php echo _('No restriction'); ?></option>
        <?php 
        
            $data_titles = titles_by_department_id($_SESSION['CustomerId'], $_SESSION['CurrentDepartment']);
            //echo var_dump($data_users); 

            foreach ($data_titles as $title){
                echo "<option value='". $title['Id'] ."'>" . _('Only') . " " . $title['Title'] . "</option>";
            }
            
        ?>
        </select>
    </div>

</div>

<div class="row">

    <div class="md-col-12 center">
        <a class="btn-cta" id="btn-salaryrate"><?php echo _('Edit salary rate'); ?></a>
    </div>

</div>

<?php }elseif($type == 3){  ?>


<h1><?php echo _('Remove') . " [" . $salary_data['Title'] . "]"; ?></h1>
<p><?php echo _('This rate will not be completely deleted as if might be used for previous salaries.'); ?></p>
<p><?php echo _('When removing this rate it will also be removed from the users who has this rate assigned.'); ?></p>
<a class="btn-cancel" id="btn-cancel"><?php echo _('Remove'); ?></a>

<?php } ?>

<script type="text/javascript">

$('#btn-cancel').click( function() {

    var salary_id               = $('#ModalSalaryDeleteId').val();
    var salary_store            = $('#ModalSalaryDeleteStoreId').val();

    $.ajax({
      method: 'post',
      url: '/actions/ajax/crud_salaryrate.php',
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

$('#btn-salaryrate').click( function() {

    addAlert(0,"");
    var errors = 0;

    var salary_actiontype       = $('#ModalActionType').val();
    var salary_id               = $('#ModalSalaryRateId').val();
    var salary_title            = $('#input-title').val();
    var salary_reference        = $('#input-reference').val();
    var salary_rate             = $('#input-rate').val();
    var salary_overwrite        = 0;
    var salary_atp              = 0;

    $("#input-currency").find("li.selected").each(function(){
        salary_currency = $(this).attr("data-value");  
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
        $('#input-title').addClass('error');
        errors += 1;
    }

    if(salary_rate == ''){
        $('#input-rate').addClass('error');
        errors += 1;
    }

    if(salary_reference == ''){
        $('#input-reference').addClass('error');
        errors += 1;
    }

    $.get("/actions/ajax/checksalaryreference.php?ref=" + salary_reference + "&id=" + salary_id, function(data) {
        var data_from_ajax = data;

        if(data_from_ajax == 1){
            $('#input-reference').addClass('error');
            errors += 1;
        }else{

            if(errors < 1){
                console.log('Great success!');

                $.ajax({
                  method: 'post',
                  url: '/actions/ajax/crud_salaryrate.php',
                  data: {
                    'ActionType': salary_actiontype,
                    'SalaryId': salary_id,
                    'SalaryDepartmentId': salary_store,
                    'SalaryTitle': salary_title,
                    'SalaryType': salary_type,
                    'SalaryReferenceId': salary_reference,
                    'SalaryRate': salary_rate,
                    'SalaryCurrency': salary_currency,
                    'SalaryOverwrite': salary_overwrite,
                    'SalaryATP': salary_atp,
                    'SalaryEmployeeGroup': salary_employeegroup,
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

});

jQuery(function($) {
    $('select.form-control').niceSelect();
});
</script>