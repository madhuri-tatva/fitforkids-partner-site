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

<h1><?php echo _('Create employee group'); ?></h1>
<p><?php echo _("Use employee groups to better organize your team."); ?></p>

<div class="md-col-4 form-group">
    <input type="text" id="input-title" class="form-control" placeholder="<?php echo _('New title'); ?>" />
</div>

<div class="md-col-4 form-group" id="input-title-store">
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


<div class="md-col-4">
    <a class="btn-cta" id="btn-title"><?php echo _('Create'); ?></a>
</div>

<?php }else{ 

$title_data = title_data_by_id($id);

?>
<h1><?php echo _('Edit employee group'); ?></h1>

<div class="md-col-4 form-group">
    <input type="text" id="input-title" class="form-control" placeholder="<?php echo _('Employee group'); ?>" value="<?php echo $title_data['Title']; ?>" />
</div>

<div class="md-col-4 form-group" id="input-title-store">
    <select name="NewDepartmentId" class="form-control">
        <option class="<?php if($title_data['DepartmentId'] == 0){ echo "selected"; } ?>" value="0"><?php echo _('All'); ?></option>
    <?php
        $data_departments = department_by_customer_id($_SESSION['CustomerId']);
        foreach ($data_departments as $department){

            if($title_data['DepartmentId'] == $department['Id']){ 
                $selected = "selected"; 
            }else{
                $selected = "";
            }

            echo "<option value='". $department['Id'] ."' " . $selected . ">" . $department['Name'] . "</option>";
        }
    ?>
    </select>
</div>

<div class="md-col-4">
    <a class="btn-cta" id="btn-title"><?php echo _('Edit'); ?></a>
</div>

<?php } ?>

<script type="text/javascript">

$('#btn-title').click( function() {

    addAlert(0,"");
    var errors = 0;

    var title_name   = $('#input-title').val();
    var title_type   = $('#ModalTitleType').val();
    var title_id     = $('#ModalTitleId').val();

    $("#input-title-store").find("li.selected").each(function(){
        title_storeid = $(this).attr("data-value");  
    });

    if(title_name == ''){
        $('#input-title').addClass('error');
        errors += 1;
    }


    if(errors < 1){
        console.log('Great success!');

        $.ajax({
          method: 'post',
          url: '/actions/ajax/crud_title.php',
          data: {
            'TitleName': title_name,
            'TitleType': title_type,
            'TitleId': title_id,
            'TitleStoreId': title_storeid,
          },
          success: function() {
            window.location.replace('https://www.plangy.com/administration');
          }
        });

    }else{
        console.log(title_name);
        console.log(title_type);
        console.log(title_id);
        console.log(title_storeid);
        console.log('Great error!');
    }
 
});

jQuery(function($) {
    $('select.form-control').niceSelect();
});
</script>