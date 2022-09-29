<?php 
include("../../includes/config.php");

putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);

$id = $_GET['id'];
$departmentid = $_GET['departmentid'];
$shifttype_data = shifttype_data_by_id($id);

?>


<h1><?php echo _('Delete the shift type') . " [" . $shifttype_data['Title'] . "]"; ?></h1>
<p><?php echo _("If you delete this shift type, it will be removed from all shifts that have this shift type."); ?></p>
<a class="btn-cancel" id="btn-cancel"><?php echo _('Delete'); ?></a>

<script type="text/javascript">

$('#btn-cancel').click( function() {

    $.ajax({
      method: 'post',
      url: '/actions/ajax/crud_shifttype.php',
      data: {
        'ShiftTypeAction': 3,
        'ShiftTypeId': <?php echo $id; ?>,
        'ShiftTypeStoreId': <?php echo $departmentid; ?>
      },
      success: function() {
        window.location.replace('https://www.plangy.com/administration');
      }
    });
 
});

</script>