<?php
include("../../includes/config.php");



// Employees
// $db->where('Type',1);
$db->orWhere('EmployeeGroup',2);
$db->orWhere('EmployeeGroup',3);
$db->orderBy('Firstname','ASC');
$employees = $db->get('users');

$action = $_GET['action'];

if(isset($_GET['teamid'])){
	$teamId = $_GET['teamid'];
}else{
	$teamId = 0;
}


$teamName 		= '';
$teamLeader 	= '';
$teamTrainers 	= '';

if($action == 1){

	// Create

}elseif($action == 2){

	// Update
	$db->where('Id',$teamId);
	$teamData = $db->getOne('teams');

	$teamName 		= $teamData['Name'];
	$teamLeader 	= $teamData['UserId'];
	$teamTrainers 	= $teamData['Trainers'];

}elseif($action == 3){

	// Delete

}

if($teamTrainers == ''){

	$teamTrainers = '';
	$teamTrainersExploded = array();

}else{

	$teamTrainers = $teamTrainers;
	$teamTrainersExploded = explode(',',$teamTrainers);

}
?>


<div class="modal-header">
	<div class="col-md-6">
		<h3>Hold</h3>
	</div>
	<div class="col-md-6">
		<input type="hidden" id="trainers-selected" value="<?php echo $teamTrainers; ?>" />
		<button id="btn-modal-save-team" class="btn-cta btn-save">Gem</button>
	</div>
</div>

<div class="section">

	<input type="hidden" name="teamAction" class="" value="<?php echo $action; ?>" />
	<input type="hidden" name="teamId" class="" value="<?php echo $teamId; ?>" />

	<div class="row">
		<div class="col-md-6">
			<label>Navn</label>
			<input type="text" name="teamName" class="" value="<?php echo $teamName; ?>" />
		</div>
		<div id="teamLeader" class="col-md-6">
			<label>Teamleder</label>
			<select name="teamLeader">
				<option value="0">Vælg leder</option>
				<?php
				 foreach($employees as $employee){
				 if($employee['EmployeeGroup'] == 2){ ?>
					<option value="<?php echo $employee['Id']; ?>" <?php if($employee['EmployeeGroup'] == 2 && $employee['Id'] == $teamLeader){ echo "selected"; } ?>><?php echo $employee['Firstname']." ".$employee['Lastname']; ?></option>
				<?php }} ?>
			</select>
		</div>
	</div>

	<div class="row row-list">
		<div class="col-md-12">
			<label>Instruktører</label>
			<div class="section-file">

				<table id="modal-trainers" class="list">
					<thead>
						<tr>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($employees as $employee){ ?>
							<tr class="<?php if(in_array($employee['Id'], $teamTrainersExploded)){ echo 'active'; } ?>" data-trainer-id="<?php echo $employee['Id'] ?>">
								<td><?php echo $employee['Firstname']." ".$employee['Lastname']; ?></td>
								<td class="right"><a class="md-trigger" onclick="modalChooseEmp(<?php echo $employee['Id']; ?>)">Vælg</a></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>

			</div>
		</div>
	</div>

	<?php if($teamId != 0 && $_SESSION['Admin'] == 1){ ?>
		<div class="row">
			<div class="col-md-12">
				<a class="btn-delete-item btn-delete" data-type="2" data-id="<?php echo $teamId; ?>">Slet dette hold</a>
			</div>
		</div>
	<?php } ?>


</div>

<script src="/assets/js/jquery.nice-select.min.js"></script>

<script>
	$('select').niceSelect();

	$('#btn-modal-save').off('click');

	$('html').on('click','.success a.close',function(){
		$('.md-modal').removeClass('md-show');
		$('.md-content-inner').html('');
	});

	$('#modal-trainers').dataTable( {
		"pageLength": 10,
		"order": [],
		"columnDefs": [ {
			"targets"  : 'no-sort',
			"orderable": false,
		}]
	});


</script>



