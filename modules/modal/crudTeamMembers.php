<?php
include("../../includes/config.php");

// Teams
$db->orderBy('Name','ASC');
$allTeams = $db->get('teams');

$allTeamsSorted = array();
foreach($allTeams as $team){

	$allTeamsSorted[$team['Id']] = $team;

}


// Users
//$db->where('Type',0);

if(isset($_GET['teamid'])){
	$teamId = $_GET['teamid'];
}else{
	$teamId = 0;
}

$db->orderBy('Firstname','ASC');
$users = $db->get('users');

$usersByTeam = array();
$usersByTeamString = array();
$usersRelevant = array();
foreach($users as $user){

	$usersByTeam[$user['TeamId']][] = $user['Id'];

	if(empty($usersByTeamString[$user['TeamId']])){
		$usersByTeamString[$user['TeamId']] = $user['Id'].',';
	}else{
		$usersByTeamString[$user['TeamId']] .= $user['Id'].',';
	}
	
	if($user['TeamRequest'] == $teamId){
		$usersRelevant[$user['Id']] = $user;
	}
	
	if($user['TeamId'] == $teamId){
		$usersRelevant[$user['Id']] = $user;
	}

}

if(!empty($usersByTeamString[$teamId])){
	$usersByTeamString[$teamId] = substr($usersByTeamString[$teamId],0,-1);
}else{
	$usersByTeamString[$teamId] = '';
}


if(empty($usersByTeam[$teamId])){
	$usersByTeam[$teamId] = array(0);
}


?>
			

<div class="modal-header">
	<div class="col-md-6">
		<h3>Hold</h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save-teammembers" class="btn-cta btn-save">Gem</button>
	</div>
</div>

<div class="section">

	<input type="hidden" name="teamId" class="" value="<?php echo $teamId; ?>" />

	<div class="row">
		<div class="col-md-12">
			<table id="modal-users" class="list" data-teammembers="<?php echo $usersByTeamString[$teamId]; ?>">
				<thead>
					<tr>
						<th>Navn</th>
						<th>Hold</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php 
				if(isset($users)){
				foreach($usersRelevant as $user){ ?>
					<tr class="<?php if(in_array($user['Id'], $usersByTeam[$teamId])){ echo 'active selected'; } ?>" data-user-id="<?php echo $user['Id'] ?>">
						<td><?php echo $user['Firstname'] . " " . $user['Lastname']; ?></td>
						<td><?php if($user['TeamId'] != 0){ echo $allTeamsSorted[$user['TeamId']]['Name']; } ?></td>
						<td class="right"><a onclick="modalChooseUser(<?php echo $user['Id']; ?>)">Vælg</a></td>
					</tr>
				<?php 
					} 
				} ?>
				</tbody>
			</table>
		</div>
	</div>


</div>


<script>


$('select').niceSelect();

$('#btn-modal-save').off('click');

$('html').on('click','.success a.close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});

$('#modal-users').dataTable( {
    "pageLength": 10,
    "order": [],
    "columnDefs": [ {
      "targets"  : 'no-sort',
      "orderable": false,
    }]
});


</script>



