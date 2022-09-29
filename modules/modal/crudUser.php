<?php
include("../../includes/config.php");

// Teams
$db->orderBy('Name','ASC');
$teams = $db->get('teams');

$action = $_GET['action'];

if(isset($_GET['userid'])){
	$userId = $_GET['userid'];
}else{
	$userId = 0;
}

if(isset($_GET['type'])){
	$type = $_GET['type'];
}else{
	$type = 0;
}

$userFirstName 		= '';
$userLastName 		= '';
$userEmail 			= '';
$userPhoneNumber 	= '';
$userAddress 		= '';
$userZipcode 		= '';
$userCity 			= '';
$userAge			= '';
$userGender 		= '';
$userTeam 			= '';
$userEmployeeGroup	= '';
$userMunicipality	= '';
$userState			= '';
$userType 			= $type;
$userPersonal	= 0;
$userCommon		= 0;
$userPageAccess	= 0;
//instructor
$is_instructor		= 0;
$userChildCertificate		= 0;
if($action == 1){

	// Create

}elseif($action == 2){

	// Update
	$db->where('Id',$userId);
	$userData = $db->getOne('users');

	$userFirstName 		= $userData['Firstname'];
	$userLastName 		= $userData['Lastname'];
	$userEmail 			= $userData['Email'];
	$userPhoneNumber 	= $userData['PhoneNumber'];
	$userAddress 		= $userData['Address'];
	$userZipcode 		= $userData['Zipcode'];
	$userCity 			= $userData['City'];
	$userAge 			= $userData['Age'];
	$userGender 		= $userData['Gender'];
	$userTeam 			= $userData['TeamId'];
	$userEmployeeGroup	= $userData['EmployeeGroup'];
	$userMunicipality	= $userData['Region'];
	$userState			= $userData['State'];
	$userType 			= $type;
	$userPersonal	= $userData['Personal_channel'];
	$userCommon		= $userData['Common_channel'];
	$userPageAccess	= $userData['special_page_access'];
	//  instrctor
	$userCprNo		= $userData['CPR_Nr'];
	$userChildCertificate = $userData['child_certificate'];
	$is_instructor		= $userData['is_instructor'];
}elseif($action == 3){

	// Delete

}
?>

<style>
.error{
	background-color: #F2DEDE!important;
    border: 1px solid #ff0000!important;
}
.section{
	max-height:calc(100vh - 160px);
	overflow-x:hidden;
}
</style>

<div class="modal-header">
	<div class="col-md-6">
		<h3>Tilmeld</h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save-user" class="btn-cta btn-save">Gem</button>
	</div>
</div>

<div class="section">

	<input type="hidden" name="userAction" class="" value="<?php echo $action; ?>" />
	<input type="hidden" name="userId" class="" value="<?php echo $userId; ?>" />
	<input type="hidden" name="userType" class="" value="<?php echo $userType; ?>" />
	<input type="hidden" name="currentemail" id="currentemail" value="<?php echo $userEmail; ?>" />
	<input type="hidden" name="is_instructor" class="" value="<?php echo $is_instructor; ?>" />
	<?php if($action == 1){ ?>
	<div class="row">
		<div class="col-md-12">
			<label>Adgangskode til denne bruger</label>
			<input type="text" name="userPassword" class="" value="" />
		</div>
	</div>
	<?php } ?>
	<div class="form-group response-block"  style="display: none;">
		<div class="row">
			<div class="col-md-12 col-sm-12 response-message required-field">

			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<label>Fornavn</label>
			<input type="text" name="userFirstName" class="" value="<?php echo $userFirstName; ?>" />
		</div>
		<div class="col-md-6">
			<label>Efternavn</label>
			<input type="text" name="userLastName" class="" value="<?php echo $userLastName; ?>" />
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>E-mail</label>
			<input type="text" name="userEmail" class="" value="<?php echo $userEmail; ?>" />
			<p class="email-error" style="color:#f720f7;font-size:12px;"></p>
		</div>
		<div class="col-md-6">
			<label>Telefon</label>
			<input type="text" name="userPhoneNumber" class="" value="<?php echo $userPhoneNumber; ?>" />
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<label>Adresse</label>
			<input type="text" name="userAddress" class="" value="<?php echo $userAddress; ?>" />
		</div>
		<div class="col-md-4">
			<label>Postnummer</label>
			<input type="text" name="userZipcode" class="" value="<?php echo $userZipcode; ?>" />
		</div>
		<div class="col-md-4">
			<label>By</label>
			<input type="text" name="userCity" class="" value="<?php echo $userCity; ?>" />
		</div>
	</div>

	<div class="row">

		<?php if($_SESSION['Admin'] == 1){ if($type!=0){?>
		<div class="col-md-6" id="userEmployeeGroup">
			<label>Personalegruppe</label>
			<select name="userEmployeeGroup" id="getuserEmployeeGroup" class="">
				<option value="0">Vælg personalegruppe</option>
				<option value="1" <?php if($userEmployeeGroup == 1){ echo "selected"; } ?>>Administration</option>
				<option value="2" <?php if($userEmployeeGroup == 2){ echo "selected"; } ?>>Teamleader</option>
				<option value="3" <?php if($userEmployeeGroup == 3){ echo "selected"; } ?>>Instruktør</option>
				<option value="4" <?php if($userEmployeeGroup == 4){ echo "selected"; } ?>>Coach</option>
			</select>
		</div>
		<?php }else{ ?>
			<div class="col-md-6" id="userPageAccess">
			<label>Page Access?</label>
			<select name="userPageAccess" id="getuserPageAccess" class="">
				<option value="0">Vælg Page Access</option>
				<option value="1" <?php if($userPageAccess == 1){ echo "selected"; } ?> selected>Body</option>
				<option value="2" <?php if($userPageAccess == 2){ echo "selected"; } ?>>Body+Mind</option>
				<option value="3" <?php if($userPageAccess == 3){ echo "selected"; } ?>>Body+Mind+Soul</option>
			</select>
		</div>
		<?php } } ?>




		<div class="col-md-6" id="userTeam">
			<label>Hold</label>
			<select>
				<option value="0">Vælg hold</option>
				<?php foreach($teams as $team){ ?>
					<option value="<?php echo $team['Id']; ?>" <?php if($team['Id'] == $userTeam){ echo "selected"; } ?>><?php echo $team['Name']; ?></option>
				<?php } ?>
			</select>
		</div>

		<div class="col-md-6" id="userMunicipality">
			<label>Kommune</label>
			<select>
                <?php if(!empty($userMunicipality)){ ?>
                    <option value="<?php echo $userMunicipality; ?>"><?php echo $userMunicipality; ?></option>
                <?php }else{ ?>
                	<option value="0">Vælg kommune</option>
                <?php } ?>
                <option value="Albertslund">Albertslund</option>
                <option value="Allerød">Allerød</option>
                <option value="Assens">Assens</option>
                <option value="Ballerup">Ballerup</option>
                <option value="Billund">Billund</option>
                <option value="Bornholm">Bornholm</option>
                <option value="Brøndby">Brøndby</option>
                <option value="Brønderslev">Brønderslev</option>
                <option value="Dragør">Dragør</option>
                <option value="Egedal">Egedal</option>
                <option value="Esbjerg">Esbjerg</option>
                <option value="Fanø">Fanø</option>
                <option value="Favrskov">Favrskov</option>
                <option value="Faxe">Faxe</option>
                <option value="Fredensborg">Fredensborg</option>
                <option value="Fredericia">Fredericia</option>
                <option value="Frederiksberg">Frederiksberg</option>
                <option value="Frederikshavn">Frederikshavn</option>
                <option value="Frederikssund">Frederikssund</option>
                <option value="Furesø">Furesø</option>
                <option value="Faaborg-Midtfyn">Faaborg-Midtfyn</option>
                <option value="Gentofte">Gentofte</option>
                <option value="Gladsaxe">Gladsaxe</option>
                <option value="Glostrup">Glostrup</option>
                <option value="Greve">Greve</option>
                <option value="Gribskov">Gribskov</option>
                <option value="Guldborgsund">Guldborgsund</option>
                <option value="Haderslev">Haderslev</option>
                <option value="Halsnæs">Halsnæs</option>
                <option value="Hedensted">Hedensted</option>
                <option value="Helsingør">Helsingør</option>
                <option value="Herlev">Herlev</option>
                <option value="Herning">Herning</option>
                <option value="Hillerød">Hillerød</option>
                <option value="Hjørring">Hjørring</option>
                <option value="Holbæk">Holbæk</option>
                <option value="Holstebro">Holstebro</option>
                <option value="Horsens">Horsens</option>
                <option value="Hvidovre">Hvidovre</option>
                <option value="Høje-Taastrup">Høje-Taastrup</option>
                <option value="Hørsholm">Hørsholm</option>
                <option value="Ikast-Brande">Ikast-Brande</option>
                <option value="Ishøj">Ishøj</option>
                <option value="Jammerbugt">Jammerbugt</option>
                <option value="Kalundborg">Kalundborg</option>
                <option value="Kerteminde">Kerteminde</option>
                <option value="Kolding">Kolding</option>
                <option value="København">København</option>
                <option value="Køge">Køge</option>
                <option value="Langeland">Langeland</option>
                <option value="Lejre">Lejre</option>
                <option value="Lemvig">Lemvig</option>
                <option value="Lolland">Lolland</option>
                <option value="Lyngby-Taarbæk">Lyngby-Taarbæk</option>
                <option value="Læsø">Læsø</option>
                <option value="Mariagerfjord">Mariagerfjord</option>
                <option value="Middelfart">Middelfart</option>
                <option value="Morsø">Morsø</option>
                <option value="Norddjurs">Norddjurs</option>
                <option value="Nordfyn">Nordfyn</option>
                <option value="Nyborg">Nyborg</option>
                <option value="Næstved">Næstved</option>
                <option value="Odder">Odder</option>
                <option value="Odense">Odense</option>
                <option value="Odsherred">Odsherred</option>
                <option value="Randers">Randers</option>
                <option value="Rebild">Rebild</option>
                <option value="Ringkøbing-Skjern">Ringkøbing-Skjern</option>
                <option value="Ringsted">Ringsted</option>
                <option value="Roskilde">Roskilde</option>
                <option value="Rudersdal">Rudersdal</option>
                <option value="Rødovre">Rødovre</option>
                <option value="Samsø">Samsø</option>
                <option value="Silkeborg">Silkeborg</option>
                <option value="Skanderborg">Skanderborg</option>
                <option value="Skive">Skive</option>
                <option value="Slagelse">Slagelse</option>
                <option value="Solrød">Solrød</option>
                <option value="Sorø">Sorø</option>
                <option value="Stevns">Stevns</option>
                <option value="Struer">Struer</option>
                <option value="Svendborg">Svendborg</option>
                <option value="Syddjurs">Syddjurs</option>
                <option value="Sønderborg">Sønderborg</option>
                <option value="Thisted">Thisted</option>
                <option value="Tønder">Tønder</option>
                <option value="Tårnby">Tårnby</option>
                <option value="Vallensbæk">Vallensbæk</option>
                <option value="Varde">Varde</option>
                <option value="Vejen">Vejen</option>
                <option value="Vejle">Vejle</option>
                <option value="Vesthimmerland">Vesthimmerland</option>
                <option value="Viborg">Viborg</option>
                <option value="Vordingborg">Vordingborg</option>
                <option value="Ærø">Ærø</option>
                <option value="Aabenraa">Aabenraa</option>
                <option value="Aalborg">Aalborg</option>
                <option value="Aarhus">Aarhus</option>
			</select>
		</div>
		<div class="col-md-6" id="userRegion">
			<label>Region</label>
			<select>
				<option value="0">Vælg region</option>
                <option value="hovedstaden" <?php if($userState == 'hovedstaden'){ echo "selected"; } ?>>Hovedstaden</option>
                <option value="sjaelland" <?php if($userState == 'sjaelland'){ echo "selected"; } ?>>Sjælland</option>
                <option value="syddanmark" <?php if($userState == 'syddanmark'){ echo "selected"; } ?>>Syddanmark</option>
                <option value="midtjylland" <?php if($userState == 'midtjylland'){ echo "selected"; } ?>>Midtjylland</option>
                <option value="nordjylland" <?php if($userState == 'nordjylland'){ echo "selected"; } ?>>Nordjylland</option>
			</select>
		</div>

	</div>

	<div class="row">
		<div class="cpr-div" style="display:<?php  if($userEmployeeGroup == 3 || $is_instructor==1 || $userEmployeeGroup == 2) {echo 'block'; }else{echo 'none';}?>">
			<div class="col-md-6">
				<label>CPR-nr.</label>
				<input  type="text"  id="input-cpr-nr" name="CPR_Nr" value="<?php if(!empty($userCprNo)){ echo $userCprNo; } ?>" placeholder="xxxxxx-xxxx" maxlength="11" minlength="11">
			</div>
			<div class="col-md-6">
				<label>Børneattest</label>
				<div class="row pg-input-radio pg-radio">
					<div class="col-md-6">
						<input type="checkbox" class="form-control" name="child_certificate" id="child_certificate" value="1" <?= ($userChildCertificate==1) ? 'checked' : ''?>>
						<span class="checkmark"></span>
						<label for="child_certificate">Børneattest</label>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>

		<div class="col-md-6 dob" style="display:<?php  if($userEmployeeGroup != 3 ) {echo 'block'; }else{echo 'none';}?>">
			<label>Fødselsdag</label>
			<input type="text" name="userAge" class="datepicker" value="<?php if($userAge == '0000-00-00'){ echo '00/00/0000'; }else{ echo date('d/m/Y',strtotime($userAge)); } ?>" data-value="<?php if($userAge == '0000-00-00'){ echo '00/00/0000'; }else{ echo $userAge; } ?>" />
		</div>

		<div class="col-md-4">

			<label>Køn</label>
		    <div class="row pg-input-radio pg-radio">
		        <div class="col-md-6">
		            <input type="radio" class="form-control" name="userGender" id="GenderMale" value="Male" <?php if($userGender == 'Male'){ echo "checked"; } ?>>
		            <span class="checkmark"></span>
		            <label for="GenderMale"><?php echo _('Mand'); ?></label>
		        </div>
		        <div class="col-md-6">
		            <input type="radio" class="form-control" name="userGender" id="GenderFemale" value="Female" <?php if($userGender == 'Female'){ echo "checked"; } ?>>
		            <span class="checkmark"></span>
		            <label for="GenderFemale"><?php echo _('Kvinde'); ?></label>
		        </div>
		    </div>
			<p class="gender-error"></p>
		</div>
	</div>
	<?php  if ($userEmployeeGroup == 2) { ?>
		<div class="row">
			<div class="col-md-6">
				<label>Upload</label>
			    <div class="pg-input-radio pg-radio">
			    	<div class="col-md-6">
			            <input type="checkbox" class="form-control" name="userPersonal" id="UploadNo" value="1" <?php if($userPersonal == 1){ echo "checked"; } ?>>
			            <span class="checkmark"></span>
			            <label for="UploadNo"><?php echo _('Personal Channel'); ?></label>
			        </div>
			    </div>
			    <div class="pg-input-radio pg-radio">
			        <div class="col-md-6">
			            <input type="checkbox" class="form-control" name="userCommon" id="UploadYes" value="1" <?php if($userCommon == 1){ echo "checked"; } ?>>
			            <span class="checkmark"></span>
			            <label for="UploadYes"><?php echo _('Common Channel'); ?></label>
			        </div>
				</div>
			</div>
		</div>
	<?php  }else{ ?>
	<input type="hidden" name="userPersonal" value="<?php echo $userPersonal; ?>">
	<input type="hidden" name="userCommon" value="<?php echo $userCommon; ?>">

	<?php } ?>
	<?php if($userId != 0 && $_SESSION['Type'] == 1 && $_GET['type'] != 4){ ?>
	<div class="row">
		<div class="col-md-12">
			<a class="btn-delete-item btn-delete" data-type="1" data-id="<?php echo $userId; ?>">Slet denne bruger</a>
		</div>
	</div>
	<?php } ?>
	<?php if($userId != 0 && $_GET['type'] == 4){ ?>
	<div class="row">
		<div class="col-md-12">
			<a class="btn-delete-item btn-delete" data-type="4" data-id="<?php echo $userId; ?>">Slet denne bruger</a>
		</div>
	</div>
	<?php } ?>


</div>



<script>

initReady();

//$(".datepick").datepicker();

$('select').niceSelect();

$('#btn-modal-save').off('click');

$('html').on('click','.success a.close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});


</script>




<script>
$('#input-cpr-nr').on('input', function() {
    let text=$(this).val()                       //Get the value
    // console.log(text)
    text=text.replace(/\D/g,'')                        //Remove illegal characters
    if(text.length>6) text=text.replace(/.{6}/,'$&-')  //Add hyphen at pos.7
	$(this).val(text);                                 //Set the new text
	var strLength = $(this).val().length;
    if (strLength < 11) {
        $('#input-cpr-nr').addClass('error');
        return false;
    }else{
        $('#input-cpr-nr').removeClass('error');
    }
});

</script>
<?php //if($userType!=0){?>
<script>
$('#getuserEmployeeGroup').on('change', function() {
    let emp_group=$(this).val()                       //Get the userEmployeeGroup value
    if(emp_group==3 || emp_group==2)  {               //  userEmployeeGroup is Instructor or teamleader?
    	$(".cpr-div").css('display','block');    // show CPR field
		$(".dob").css('display','none');	// hide DOB field
	}else{
		$(".cpr-div").css('display','none');	// hide CPR field
    	$(".dob").css('display','block');    // show DOB field
	}
});
</script>
<?php //} ?>
