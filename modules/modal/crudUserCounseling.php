<?php
include("../../includes/config.php");


$action = $_GET['action'];

$userId = $_GET['userid'];

$db->where('Id',$userId);
$thisUser = $db->getOne('users');


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
$statNote = '';
$statDate = date('d-m-Y');
$statFamilyId = $thisUser['FamilyId'];

if($action == 1){

	// Create

}elseif($action == 2){

	$db->where('Id',$_GET['id']);
	$thisStat = $db->getOne('counseling');

	// Update
	$statId 			= $_GET['id'];
	$statNote 			= $thisStat['Note'];
	$statDate 			= $thisStat['Date'];
	$statDate			= date('d/m/Y',strtotime($statDate));

}elseif($action == 3){

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





?>
			

<div class="modal-header">
	<div class="col-md-6">
		<h3>Kost vejledning <em><?php echo $currentDate; ?></em></h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save-counseling" class="btn-cta btn-save">Gem</button>
	</div>
</div>

<div class="section">

	<input type="hidden" name="statAction" class="" value="<?php echo $action; ?>" />
	<input type="hidden" name="statUserId" class="" value="<?php echo $userId; ?>" />
	<input type="hidden" name="statFamilyId" class="" value="<?php echo $statFamilyId; ?>" />
	<input type="hidden" name="statId" class="" value="<?php echo $statId; ?>" />
	<input type="hidden" name="ageprecise" class="" value="<?php echo $age; ?>" />

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
			<label>Note</label>
			<textarea type="text" name="statNote" class=""><?php echo $statNote; ?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			
			<input type="" class="datepicker form-control" name="statDate" value="<?php echo $statDate; ?>" value="<?php echo $statDate; ?>" placeholder="Dato">
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



$('html').on('change','input',function(){

	var BMIL = <?php echo $bmiL; ?>;
	var BMIM = <?php echo $bmiM; ?>;
	var BMIS = <?php echo $bmiS; ?>;

	var BMIType = <?php echo $bmitype; ?>;



	var weight = $('#statWeight').val();
	var height = $('#statHeight').val();

	var heightFactor = (height / 100) * 2;
	var heightMeters = (height / 100);

	var BMI = weight / heightMeters * heightMeters;
	var BMI = BMI.toFixed(2);

	console.log(BMI + 'OLD');

	if(BMIType == 1){

		var BMIFactor1 = BMI / BMIM;

		console.log(BMIFactor1);

		var BMIChild = (Math.pow(BMIFactor1, BMIL) - 1)/BMIL*BMIS;

		console.log(Math.pow(BMIFactor1, BMIL));
		console.log(BMIChild + 'BMI Child');

		var BMI = BMIChild.toFixed(2);


	}

	console.log(BMI + 'NEW');

	if(BMI == NaN || BMI == 'NaN'){
		BMI = 0;
	}

	$('#statBMI').val(BMI);

});

</script>



