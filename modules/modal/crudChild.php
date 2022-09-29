<?php
include("../../includes/config.php");

$action = $_GET['action'];

if(isset($_GET['userid'])){
	$childId = $_GET['userid'];
}else{
	$childId = 0;
}

$partnerListStyle = 'hide';

$readOnly = $disabledSelect = $opacity = $checked = $disabled = '';

$childFirstName 	= '';
$childLastName 		= '';
$childAge			= '';
$childGender 		= '';
$childType 			= '';
$childGoalType 		= '0,0,0,0,0';


//Is partner or parent
$db->where("Id", $_SESSION['UserId']);
$db->where("is_partner", 1);
$loggedInUserData = $db->getOne("users");

if(!empty($loggedInUserData)) {
	//Partner
	$partnerParentUserId = $loggedInUserData['ParentId'];
	$partnerListStyle = 'hide';
	$childTickboxStyle = '';
	$is_partner = 1;
}else {
	//Parent
	//Get linked partners
	$partnerParentUserId = 0;
	$is_partner = 0;

	$db->where("ParentId", $_SESSION['UserId']);
	$db->where("is_partner", 1);
	$partnerData = $db->get("users");

	$childTickboxStyle = $partnerListStyle = 'hide';

	if(!empty($partnerData)) {
		$childTickboxStyle = '';
	}
}



if($action == 1){

	// Create

}elseif($action == 2){
	$childTickboxStyle = 'hide';
	$disabled = 'disabled="disabled"';
	$partnerListStyle = '';

	// Update
	$db->where('Id',$childId);
	$childData = $db->getOne('users');
	$copiedPartner1 = array();

	if(isset($childData['ParentId_2']) && !empty($childData['ParentId_2'])){
		$copiedPartner1 = explode(',',$childData['ParentId_2']);
	}
	// if(empty($copiedPartner1))
	// {
	// 	$partnerListStyle = 'hide';
	// }

	array_push($copiedPartner1,$childData['ParentId']);




	if($is_partner == 1){
		$partnerListStyle = 'hide';
	}

	$childFirstName 		= $childData['Firstname'];
	$childLastName 			= $childData['Lastname'];
	$childAge 				= $childData['Age'];
	$childGender 			= $childData['Gender'];
	$childType 				= $childData['ChildType'];

	if(!empty($childData['GoalType'])){
		$childGoalType 			= $childData['GoalType'];
	}

	//access right
	$db->where("parent_id", $_SESSION['UserId']);
	$db->where("child_id", $childId);
	// $db->where("access_right", 2);
	$accessRights = $db->getOne("child_profile_access_rights");


	if(!empty($accessRights) && $accessRights['access_right'] == 1){
		$readOnly = 'readonly="readonly"';
		$disabledSelect = 'disabled="disabled"';
		$opacity = 'style="opacity: 0;"';
	}

}elseif($action == 3){

	// Delete

}

$childGoalType = explode(',',$childGoalType);
?>


<div class="modal-header">
	<div class="col-md-6">
		<h3>Barn</h3>
	</div>
	<div class="col-md-6">
		<?php if(($action == 2 && empty($accessRights)) || ($action == 2 && $accessRights['access_right'] == 2) || $action == 1) { ?>
			<button id="btn-modal-save" class="btn-cta btn-save">Gem</button>
		<?php } ?>
	</div>
</div>

<div class="section">

	<input type="hidden" name="childAction" class="" value="<?php echo $action; ?>" />
	<input type="hidden" name="childId" class="" value="<?php echo $childId; ?>" />
	<input type="hidden" name="familyId" class="" value="<?php echo $_SESSION['FamilyId']; ?>" />
	<input type="hidden" name="loggedinParentType" class="" value="<?php echo $is_partner; ?>" />
	<input type="hidden" name="partnerParentUserId" class="" value="<?php echo $partnerParentUserId; ?>" />

	<div class="row">
		<div class="col-md-6">
			<label>Fornavn <span class="required-field">*<!-- Påkrævet--></span></label>
			<input type="text" name="childFirstName" class="" value="<?php echo $childFirstName; ?>" <?php echo $readOnly;  ?>/>
		</div>
		<div class="col-md-6">
			<label>Efternavn <span class="required-field">*<!-- Påkrævet--></span></label>
			<input type="text" name="childLastName" class="" value="<?php echo $childLastName; ?>" <?php echo $readOnly;  ?>/>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>Alder</label>
			<input id="childAge" type="text" name="childAge" class="datepicker" value="<?php if($childAge == '0000-00-00'){ echo '00/00/0000'; }else{ echo date('d/m/Y',strtotime($childAge)); } ?>" autocomplete="off" <?php echo $readOnly;  ?>/>
		</div>
		<div class="col-md-6">

			<label>Køn</label>
		    <div class="row pg-input-radio pg-radio">
		        <div class="col-md-6">
		            <input type="radio" class="form-control" name="childGender" id="ChildGenderMale" value="Male" <?php if($childGender == 'Male'){ echo "checked"; } ?> <?php echo $disabledSelect; echo $opacity; ?>>
		            <span class="checkmark"></span>
		            <label for="ChildGenderMale"><?php echo _('Dreng'); ?></label>
		        </div>
		        <div class="col-md-6">
		            <input type="radio" class="form-control" name="childGender" id="ChildGenderFemale" value="Female" <?php if($childGender == 'Female'){ echo "checked"; } ?> <?php echo $disabledSelect; echo $opacity; ?>>
		            <span class="checkmark"></span>
		            <label for="ChildGenderFemale"><?php echo _('Pige'); ?></label>
		        </div>
		    </div>

		</div>
	</div>
	<div class="row">
		<!-- <div class="col-md-6">
			<label>FitforKids</label>
		    <div class="row pg-input-radio pg-radio">
		        <div class="col-md-6">
		            <input type="radio" class="form-control" name="childType" id="ChildTypeFitforkids" value="1" <?php if($childType == 1){ echo "checked"; } ?> >
		            <span class="checkmark"></span>
		            <label for="ChildType"><?php //echo _('FitforKids barn'); ?></label>
		        </div>
		        <div class="col-md-6">
		            <input type="radio" class="form-control" name="childType" id="childTypeSibling" value="2" <?php if($childType == 2){ echo "checked"; } ?>>
		            <span class="checkmark"></span>
		            <label for="ChildType"><?php //echo _('Søskende'); ?></label>
		        </div>
		    </div>
		</div> -->
		<div class="col-md-12">
			<label>Mål</label>
		    <div class="row pg-input-radio pg-checkbox">
		        <div class="col-md-4" style="padding-left: 0px !important;">
		            <input type="checkbox" class="form-control childGoalType" name="childGoalType" id="ChildGoalTypeWeight" value="1" <?php if(isset($childGoalType[0]) && $childGoalType[0] == 1){ echo "checked"; } ?>>
		            <span class="checkmark"></span>
					<label for="childGoalType"><?php echo _('Tabe sig'); ?></label>
		        </div>
		        <div class="col-md-4" style="padding-left: 0px !important;">
		            <input type="checkbox" class="form-control childGoalType" name="childGoalType" id="ChildGoalTypeStamina" value="1" <?php if(isset($childGoalType[1]) && $childGoalType[1] == 1){ echo "checked"; } ?>>
		            <span class="checkmark"></span>
					<label for="childGoalType"><?php echo _('I bedre form'); ?></label>
		        </div>
				<div class="col-md-4" style="padding-left: 0px !important;">
		            <input type="checkbox" class="form-control childGoalType" name="childGoalType" id="ChildGoalTypeStamina" value="1" <?php if(isset($childGoalType[2]) && $childGoalType[2] == 1){ echo "checked"; } ?>>
		            <span class="checkmark"></span>
		            <label for="childGoalType"><?php echo _('Få flere venner'); ?></label>
		        </div>
		    </div>

			<div class="row pg-input-radio pg-checkbox">
			<div class="col-md-4" style="padding-left: 0px !important;">
		            <input type="checkbox" class="form-control childGoalTypeAll" name="childGoalType" id="ChildGoalTypeStamina" value="1" <?php if(isset($childGoalType[3]) && $childGoalType[3] == 1){ echo "checked"; } ?>>
		            <span class="checkmark"></span>
		            <label for="childGoalType"><?php echo _('Alle sammen'); ?></label>
		        </div>
				<div class="col-md-8" style="padding-left: 0px !important;">
		            <input type="checkbox" class="form-control childGoalType" name="childGoalType" id="ChildGoalTypeStamina" value="1" <?php if(isset($childGoalType[4]) && $childGoalType[4] == 1){ echo "checked"; } ?>>
		            <span class="checkmark"></span>
		            <label for="childGoalType"><?php echo _('(Ikke noget, er søskende/med som støtte)'); ?></label>
		        </div>
			</div>
		</div>
	</div>

	<div class="row <?php echo $childTickboxStyle; ?>" style="margin-top: 20px;">
		<div class="col-md-12" style="padding-left: 9px;">
		<input name='copy_child_to_partner' class='' type='checkbox' id='copy_child_to_partner' value='1' style="width: 6%; "/>
            <span>Ønsker du at give din partner adgang til dit barns profil?</span>
		</div>
	</div>

	<div class="row <?php echo $partnerListStyle; ?>" style="margin-top: 20px;" id="partner-list-section">
		<div class="col-md-12">
			<label>Partner <span class="required-field">*<!-- Påkrævet--></span></label>
			<?php
			if($is_partner == 0){

				foreach($partnerData as $partner){
					if(isset($childData) && $childData['ParentId'] != $_SESSION['UserId']){

						if(!in_array($partner['Id'], $copiedPartner1)){
							continue;
						}
					}
					$db->where('child_id', $childId);
					$db->where('parent_id', $partner['Id']);
					$partnerAccessRight = $db->getOne('child_profile_access_rights');

					$rightsOptionStyle = '';
					$checked1 = $checked2 = '';
					$disabled1 = '';


					if((!empty($copiedPartner1) && in_array($partner['Id'], $copiedPartner1)) || empty($copiedPartner1)){

						$rightsOptionStyle = '';
						if($action == 2 && $partnerAccessRight['access_right'] == 1){
							$checked1 = 'checked="checked"';
							$checked2 = '';
							$disabled1 = 'disabled="disabled"';
						} else if($action == 2 && $partnerAccessRight['access_right'] == 2){
							$rightsOptionStyle = '';
							$checked2 = 'checked="checked"';
							$checked1 = '';
							$disabled1 = 'disabled="disabled"';
						} elseif($action == 1){
							$rightsOptionStyle = '';
							$checked1 = $checked2 = $disabled1 = '';
						}else{
							$db->where("Id", $childId);
							$db->where('ParentId', $partner['Id']);
							$childParentData = $db->getOne('users');

							if(!empty($childParentData)){
								$checked2 = 'checked="checked"';
								$checked1 = '';
								$disabled1 = 'disabled="disabled"';
							}else{
								$checked2 = '';
								$checked1 = '';
								$disabled1 = '';
							}
							$rightsOptionStyle = '';


						}
					}
					?>
					<div class="row <?php echo $rightsOptionStyle; ?>" style="margin-left: -19px !important;">
						<div class="col-md-3">
						<b><label style="display: inline;"><?php echo $partner['Firstname'].' '.$partner['Lastname']; ?>:</label></b>
						</div>

						<div class="col-md-4" >
							<input name='partner_<?php echo $partner['Id']; ?>[]' class='partner_radio' type='radio' id='partner_se_<?php echo $partner['Id']; ?>' value='<?php echo $partner['Id']; ?>' data-val="1" style="width: 12%; " <?php echo $checked1; echo $disabled1; ?>/> Se data/profil

						</div>

						<div class="col-md-4">
							<input name='partner_<?php echo $partner['Id']; ?>[]' class='partner_radio' type='radio' id='partner_rette_<?php echo $partner['Id']; ?>' value='<?php echo $partner['Id']; ?>' data-val="2" style="width: 12%; " <?php echo $checked2; echo $disabled1; ?>/> Se + Rette data/profil

						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>

	<?php
	 if(($action == 2 && empty($accessRights)) || ($action == 2 && $accessRights['access_right'] == 2) || $action == 1) {
		if($childId != 0){ ?>
		<div class="row">
			<div class="col-md-12">
				<a class="btn-delete-item btn-delete" data-type="1" data-id="<?php echo $childId; ?>">Slet barn</a>
			</div>
		</div>
		<?php } ?>

		<div class="row" style="margin-top: 20px;">
    		<div class="col-md-12 col-sm-12 required-field">
        		Alle felter markeret med * skal udfyldes.
    		</div>
		</div>
		<?php } ?>
	<div class="row">
    	<div class="col-md-12 col-sm-12 required-field">
    		<span class="required-field hide" id="childUserErr">Udfyld venligst værdier i obligatoriske felter.</span>
    	</div>
	</div>



</div>



<script type="text/javascript">

$('select').niceSelect();

$('#btn-modal-save').off('click');

$('html').on('click','.success a.close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});

$('#copy_child_to_partner').click(function(){

	var is_partner = '<?php echo $is_partner; ?>';
	if($(this).is(":checked") && is_partner == 0){

        $('#partner-list-section').removeClass('hide');
    } else if($(this).is(":not(:checked)") && is_partner == 0) {

        $("input[class=partner_checkbox]").removeAttr("checked");
        $('#partner-list-section').addClass('hide');
    }
});

</script>


<script>
// add multiple select / deselect functionality
$(".childGoalTypeAll").click(function () {
	  if($(".childGoalTypeAll").is(":checked")){
	    $('.childGoalType').prop('checked','checked');
	  }else{
	    $('.childGoalType').removeAttr('checked');
	  }
});
</script>
<script>
// if all checkbox are selected, check the childGoalTypeAll checkbox
$(".childGoalType").click(function(){
	if($(".childGoalType").length == $(".childGoalType:checked").length) {
		$(".childGoalTypeAll").prop("checked", "checked");
	} else {
		$(".childGoalTypeAll").removeAttr("checked");
	}
 });
</script>