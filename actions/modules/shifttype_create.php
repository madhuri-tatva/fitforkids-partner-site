<?php 
include("../../includes/config.php");

putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);


$type = $_GET['type'];
$id = $_GET['id'];


if($type == 1){

?>

<h1><?php echo _('Create shift type'); ?></h1>
<p><?php echo _("Use shift types to better organize your schedule."); ?></p>

<div class="md-col-4 form-group">
    <input type="text" id="input-shifttype" class="form-control" placeholder="<?php echo _('New shift type'); ?>" />
</div>

<div class="md-col-4 form-group" id="input-shifttype-store">
    <select name="NewDepartmentId" class="form-control">
        <option value="0"><?php echo _('Select store'); ?></option>
        <option value="0"><?php echo _('All'); ?></option>
    <?php 
        $data_departments = department_by_customer_id($_SESSION['CustomerId']);
        //echo var_dump($data_users); 

        foreach ($data_departments as $department){
            echo "<option value='". $department['Id'] ."'>" . $department['Name'] . "</option>";
        }
    ?>
    </select>
</div>

<input type="hidden" id="ModalShiftTypeAction" class="form-control" value="1" />

<div class="md-col-4">
    <a class="btn-cta" id="btn-shifttype"><?php echo _('Create'); ?></a>
</div>

<?php }else{ 

$shifttype_data = shifttype_data_by_id($id);

?>
<h1><?php echo _('Edit shift type'); ?></h1>

<div class="md-col-4 form-group">
    <input type="text" id="input-shifttype" class="form-control" placeholder="<?php echo _('Shift type'); ?>" value="<?php echo $shifttype_data['Title']; ?>" />
</div>

<div class="md-col-4 form-group" id="input-shifttype-store">
    <select name="NewDepartmentId" class="form-control">
        <option class="<?php if($shifttype_data['DepartmentId'] == 0){ echo "selected"; } ?>" value="0"><?php echo _('All'); ?></option>
    <?php
        $data_departments = department_by_customer_id($_SESSION['CustomerId']);
        foreach ($data_departments as $department){

            if($shifttype_data['DepartmentId'] == $department['Id']){ 
                $selected = "selected"; 
            }else{
                $selected = "";
            }

            echo "<option value='". $department['Id'] ."' " . $selected . ">" . $department['Name'] . "</option>";
        }
    ?>
    </select>
</div>

<input type="hidden" id="ModalShiftTypeAction" class="form-control" value="2" />

<div class="md-col-4">
    <a class="btn-cta" id="btn-shifttype"><?php echo _('Edit'); ?></a>
</div>

<?php } ?>

<script type="text/javascript">

$('#btn-shifttype').click( function() {

    addAlert(0,"");
    var errors = 0;

    var shifttype_name   = $('#input-shifttype').val();
    var shifttype_action = $('#ModalShiftTypeAction').val();
    var shifttype_id     = $('#ModalShiftTypeId').val();

    $("#input-shifttype-store").find("li.selected").each(function(){
        shifttype_storeid = $(this).attr("data-value");  
    });

    if(shifttype_name == ''){
        $('#input-shifttype').addClass('error');
        errors += 1;
    }


    if(errors < 1){
        console.log('Great success!');

        $.ajax({
          method: 'post',
          url: '/actions/ajax/crud_shifttype.php',
          data: {
            'ShiftTypeName': shifttype_name,
            'ShiftTypeAction': shifttype_action,
            'ShiftTypeId': shifttype_id,
            'ShiftTypeStoreId': shifttype_storeid,
          },
          success: function() {
            window.location.replace('https://www.plangy.com/administration');
          }
        });


    }else{
        console.log(shifttype_name);
        console.log(shifttype_action);
        console.log(shifttype_id);
        console.log(shifttype_storeid);
        console.log('Great error!');
    }
 
});

jQuery(function($) {
    $('select.form-control').niceSelect();
});
</script>