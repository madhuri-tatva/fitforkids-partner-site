<?php
include("../../includes/config.php");

$action = $_GET['action'];
$page = $_GET['page'];

$userId = $_GET['userid'];

// Get latest stats
$db->where('UserId',$userId);
$db->orderBy('Id', 'DESC');
$latestStat = $db->getOne('stats');

if(!empty($latestStat)){
	$statId 			= $latestStat['Id'];
	$statWeight 		= '';
	$statHeight 		= $latestStat['Height'];
	$statFatpercentage = '';
	$statFatkilo = '';
	$statBMI = '';
	$statBMR = '';
	$statZscore = '';
	$statFitnessgrad = '';
	$statWorkout1 = '';
	$statWorkout2 = '';
	$statWorkout3 = '';
	$statWorkout4 = '';
	$statSquat = '';
	$statDate = date('d/m/Y');
}else{
	$statId = 0;
	$statWeight = '';
	$statHeight = '';
	$statFatpercentage = '';
	$statFatkilo = '';
	$statBMI = '';
	$statBMR = '';
	$statZscore = '';
	$statFitnessgrad = '';
	$statWorkout1 = '';
	$statWorkout2 = '';
	$statWorkout3 = '';
	$statWorkout4 = '';
	$statSquat = '';
	$statDate = date('d/m/Y');
}


$db->where('Id',$userId);
$thisUser = $db->getOne('users');
$from 	= new DateTime($thisUser['Age']);
$to   	= new DateTime('today');
$age 	= $from->diff($to)->y;

if($thisUser['Age'] == '0000-00-00'){
	$age = 0;
}else{
	$age = $age;
}


$from 	= new DateTime($thisUser['Age']);
$to   	= new DateTime('today');
$age 	= $from->diff($to)->y;

if($thisUser['Age'] == '0000-00-00'){
	$age = 0;
}

               

$db->where('Id',$thisUser['TeamId']);
$thisTeam = $db->getOne('teams');


$currentDate = date('d-m-Y');

/*if(isset($_GET['userid'])){
	$userId = $_GET['userid'];
}else{
	$userId = 0;
}*/

$statId = 0;
// $statWeight = '';
// $statHeight = '';
// $statFatpercentage = '';
// $statBMI = '';
// $statBMR = '';
// $statZscore = '';
// $statFitnessgrad = '';
// $statWorkout1 = '';
// $statWorkout2 = '';
// $statWorkout3 = '';
// $statWorkout4 = '';
// $statDate = date('d/m/Y');

if($action == 1){
	// Create
	$statId = 0;
}elseif($action == 2){

	$db->where('Id',$_GET['statid']);
	$thisStat = $db->getOne('stats');

	// Update
	$statId 			= $_GET['statid'];
	$statWeight 		= $thisStat['Weight'];
	$statHeight 		= $thisStat['Height'];
	$statFatpercentage 	= $thisStat['Fatpercentage'];
	$statFatkilo 		= $thisStat['Fatkilo'];
	$statBMI 			= $thisStat['BMI'];
	$statBMR 			= $thisStat['BMR'];
	$statZscore 		= $thisStat['Zscore'];
	$statFitnessgrad    = $thisStat['Fitnessgrad'];
	$statWorkout1 		= $thisStat['Workout1'];
	$statWorkout2 		= $thisStat['Workout2'];
	$statWorkout3 		= $thisStat['Workout3'];
	$statWorkout4 		= $thisStat['Workout4'];
	$statDate 			= $thisStat['Date'];
	$statSquat 			= $thisStat['Squat'];
	$statDate			= date('d/m/Y',strtotime($statDate));

}elseif($action == 3){
	// $db->where('Id',$_POST['StatId']);
	// $db->delete('stats');
	// exit;
	// Delete

}


$ageprecise = $age * 10;

if($thisUser['Gender'] == 'Male'){
	$gender = 1;
}else{
	$gender = 2;
}

if($age < 18){
	$bmitype = 1;
	$db->where('Gender',$gender);
	$db->where('Age',$ageprecise);
	$bmi = $db->getOne('bmi');

	$bmiL = str_replace(',', '.', $bmi['L']);
	$bmiM = str_replace(',', '.', $bmi['M']);
	$bmiS = str_replace(',', '.', $bmi['S']);
}else{
	$bmitype = 2;

	$bmiL = 0;
	$bmiM = 0;
	$bmiS = 0;
}

if(!empty($thisUser['ParentId']) && $thisUser['EmployeeGroup'] == 0){
	$db->where('child_id', $thisUser['Id']);
	$db->where('parent_id', $_SESSION['UserId']);	
	$accessRights = $db->getOne("child_profile_access_rights");
}
?>
			

<div class="modal-header">
	<div class="col-md-6">
		<h3>Måling <em><?php echo $currentDate; ?></em></h3>
	</div>

	<div class="col-md-6">
		<?php if((empty($accessRights)) || ($accessRights['access_right'] == 2)) {  ?>
			<button id="btn-modal-save-stat" class="btn-cta btn-save">Gem</button>
		<?php } ?>
	</div>
</div>

<div class="section">
	<input type="hidden" name="statAction" class="" value="<?php echo $action; ?>" />
	<input type="hidden" name="statUserId" class="" value="<?php echo $userId; ?>" />
	<input type="hidden" name="statId" class="" value="<?php echo $statId; ?>" />
	<input type="hidden" name="teamId" class="" value="<?php echo $thisUser['TeamId']; ?>" />
	<input type="hidden" name="page" class="" value="<?php echo $page; ?>" />
	<input type="hidden" name="userId" class="" value="<?php echo $_SESSION['UserId']; ?>" />

	<div class="info">
		<div class="row">
			<div class="col-md-12">
				<div class="profile-image" style="background-image:url(<?php echo $thisUser['Avatar']; ?>); background-repeat: no-repeat; background-size: cover; background-position: center;">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h3><?php echo $thisUser['Firstname'] . " " . $thisUser['Lastname']; ?><?php if($age != 0){ echo ", " . $age . " år"; } ?></h3>
				<h4><?php if(isset($thisTeam['Name'])){ echo $thisTeam['Name']; } ?></h4>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-md-12">
			<h5>Basal data</h5>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<label>Højde i cm</label>
			<input type="number" name="statHeight" id="statHeight" class="" value="<?php echo $statHeight; ?>" />
		</div>
		<div class="col-md-4">
			<label>Vægt i kg</label>
			<input type="number" name="statWeight" id="statWeight" class=""value="<?php echo $statWeight; ?>" />
		</div>
		<div class="col-md-4">
			<label>Fedt %</label>
			<input type="number" name="statFatpercentage" id="statFatpercentage" class=""value="<?php echo $statFatpercentage; ?>" />
		</div>
		<div class="col-md-4">
			<label>BMI</label>
			<input type="number" name="statBMI" id="statBMI" class=""value="<?php echo $statBMI; ?>"  />
		</div>
		<div class="col-md-4">
			<label>Z-score</label>
			<input type="number" name="statZscore" id="statZscore" class=""value="<?php echo $statZscore; ?>"  />
		</div>
		<!-- <div class="col-md-4">
			<label>Kondital tal</label>
			<input type="number" name="statFitnessgrad" id="statFitnessgrad" class="" value="<?php echo $statFitnessgrad; ?>" />
		</div> -->
		<div class="col-md-4">
			<label>Fedt i kg</label>
			<input type="number" name="statFatkilo" id="statFatkilo" class=""value="<?php echo $statFatkilo; ?>"  />
		</div>
		<!-- <div class="col-md-4">
			<label>BMR</label>
			<input type="number" name="statBMR" id="statBMR" class="" value="<?php echo $statBMR; ?>" />
		</div> -->
	</div>

	<div class="row">
		<div class="col-md-12">
			<h5>Øvelser</h5>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>Englehop</label>
			<input type="number" name="statWorkout1" class=""value="<?php echo $statWorkout1; ?>"  />
		</div>
		<div class="col-md-6">
			<label>Armbøjninger</label>
			<input type="number" name="statWorkout2" class=""value="<?php echo $statWorkout2; ?>"  />
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>Mavebøjninger</label>
			<input type="number" name="statWorkout3" class=""value="<?php echo $statWorkout3; ?>" />
		</div>
		<div class="col-md-6">
			<label>Squat</label>
			<input type="number" name="statSquat" id="statSquat" class=""value="<?php echo $statSquat; ?>" />
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<label>Løb på 30 sek (i hele meter)</label>
			<input type="number" name="statWorkout4" class="" value="<?php echo $statWorkout4; ?>" />
		</div>
		<div class="col-md-6">
			<label>Kondital</label>
			<input type="number" name="statFitnessgrad" id="statFitnessgrad" class="" value="<?php echo $statFitnessgrad; ?>"  />
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			
			<input type="" class="datepickert form-control" name="statDate" value="" placeholder="<?php echo $statDate; ?>">
		</div>
	</div>


</div>

<script>
$('select').niceSelect();

$('#btn-modal-save-stat').off('click');

$('html').on('click','.success a.close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});

jQuery(function($) { 
    $('html').on('focus',".datepickert", function(){
        $(this).datepicker({
        	dateFormat: "dd-mm-yy"
        });
    });
});


$('html').on('change','input',function(){

	var BMIL = <?php echo $bmiL; ?>;
	var BMIM = <?php echo $bmiM; ?>;
	var BMIS = <?php echo $bmiS; ?>;

	var BMIType = <?php echo $bmitype; ?>;



	var weight = $('#statWeight').val();
	var height = $('#statHeight').val();
	var fatper = $('#statFatpercentage').val();

	var heightFactor = (height / 100) * 2;
	var heightMeters = (height / 100);
	var age = <?php echo $age; ?>;
	console.log("age ", age);
	var gender = <?php echo $gender; ?>;


	var BMI = weight / Math.pow(heightMeters,2);
	var BMI = BMI.toFixed(2);
	var BMR = 0;
	var fatKg = weight * fatper;
	var fatKg = fatKg/100;
	var fatKg = fatKg.toFixed(2);

	console.log(BMI + 'OLD');
	/*if(BMIType == 1){

		var BMIFactor1 = BMI / BMIM;

		console.log(BMI + 'F1');
		console.log(BMIM + 'F1');
		console.log(BMIFactor1 + 'F1');

		var BMIChild = (Math.pow(BMIFactor1, BMIL) - 1)/(BMIL*BMIS);

		console.log(Math.pow(BMIFactor1, BMIL));

		var BMI = BMIChild.toFixed(2);


	}*/

	var BMIFactor1 = BMI / BMIM;
	var Zscore = (Math.pow(BMIFactor1, BMIL) - 1)/(BMIL*BMIS);
	var Zscore = Zscore.toFixed(2);

	$('#statZscore').val(Zscore);

	console.log(BMI + 'NEW');

	if(BMI == NaN || BMI == 'NaN'){
		BMI = 0;
	}

	if(gender == 1) {
		BMR = ((66.5 + (13.75 * weight) + (5.003 * height) - (6.775 * age)) * 4.186) / 1000;
	} else if(gender == 2) {
		BMR = ((655.1 + (9.563 * weight) + (1.850 * height) - (4.676 * age)) * 4.186) / 1000;
	} else {
		BMR = 0;
	}

	$('#statBMI').val(BMI);
	$('#statBMR').val(BMR.toFixed(2));
	$('#statFatkilo').val(fatKg);

});



</script>



