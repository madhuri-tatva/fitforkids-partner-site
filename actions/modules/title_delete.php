<?php 
include("../../includes/config.php");

putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);

$id = $_GET['id'];
$departmentid = $_GET['departmentid'];
$title_data = title_data_by_id($id);

?>


<h1><?php echo _('Delete the employee group') . " [" . $title_data['Title'] . "]"; ?></h1>
<p><?php echo _("If you delete this employee group, it will be removed from all the users who have been assigned to this employee group."); ?></p>
<a class="btn-cancel" id="btn-cancel"><?php echo _('Delete'); ?></a>

<script type="text/javascript">

$('#btn-cancel').click( function() {

    $.ajax({
      method: 'post',
      url: '/actions/ajax/crud_title.php',
      data: {
        'TitleType': 3,
        'TitleId': <?php echo $id; ?>,
        'TitleStoreId': <?php echo $departmentid; ?>
      },
      success: function() {
        window.location.replace('https://www.plangy.com/administration');
      }
    });
 
});

</script>