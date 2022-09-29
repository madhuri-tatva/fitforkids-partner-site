<?php
include("../../includes/config.php");

$uid = $_GET['uid'];
$type = $_GET['type'];
?>


<?php if($type == 'block-text'){ ?>

<div class="modal-header">
	<div class="col-md-6">
		<h3>Edit text block</h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save" class="btn-cta btn-save">Save</button>
	</div>
</div>

<div class="section">

	<form id="modal-block-edit" action="" method="POST">

		<div class="col-md-12">
			<textarea id="modal-block-text" class="texteditor" name="modal-block-text"></textarea>
		</div>

		<div class="col-md-6">
			<div class="row">
				<h4>CSS class</h4>
				<input type="text" name="modalBlockCssClass" class="std" />
			</div>
			<div class="row">
				<h4>ID</h4>
				<input type="text" name="modalBlockId" class="std" />
			</div>
		</div>

	</form>

</div>

<script>

var currentBlockText = $('[data-id=<?php echo $uid; ?>] .block-inner').html();

$('#modal-block-text').html(currentBlockText);

$('#modal-builder #btn-modal-save').click(function(){

	tinyMCE.triggerSave(); 

	var form = $('#modal-block-edit').serialize();

	console.log(form);

	var blockText = $('textarea#modal-block-text').val();
	var uid = '<?php echo $uid; ?>';

	console.log(blockText);

	$('[data-id=<?php echo $uid; ?>] .block-inner').html(blockText);


});

</script>

<?php }elseif($type == 'block-form'){ ?>

<div class="modal-header">
	<div class="col-md-6">
		<h3>Edit form block</h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save" class="btn-cta btn-save">Save</button>
	</div>
</div>

<div class="section">

	<h3>Select form</h3>

	<?php 

	$db->where('CustomerId',$_SESSION['CustomerId']);
	$db->where('DepartmentId',$_SESSION['CurrentDepartment']);
	$forms = $db->get('forms');

	if(!empty($forms)){
	?>

	<div id="modal-form-select">
		<select>
			<option>Select form</option>
			<?php foreach($forms as $form){ ?>

				<option value="<?php echo $form['Id'] ?>"><?php echo $form['Name']; ?></option>

			<?php } ?>
		</select>
	</div>

	<?php }else{ ?>
		<div class="msg">There are no forms yet. <a href="/forms" target="_blank">Create one here</a></div>
	<?php } ?>

</div>

<script>

$('#modal-builder #btn-modal-save').click(function(){

	var form = $('#modal-builder #modal-form-select .selected').attr('data-value');

	$('[data-id=<?php echo $uid; ?>] .block-inner').load('/actions/ajax/assemble_form.php?formid='+form);

	console.log(form);

});

</script>

<?php } ?>