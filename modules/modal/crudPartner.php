<?php
include("../../includes/config.php");

$action = $_GET['action'];
$hideTerms = $disableEmail = '';

if (isset($_GET['userid'])) {
    $PartnerId = $_GET['userid'];
} else {
    $PartnerId = 0;
}

//Get chidren list
$hideCopyChild = $hideChildList = 'hide';

if(isset($_SESSION['UserId']) && !empty($_SESSION['UserId'])){
    $db->where('ParentId', $_SESSION['UserId']);
	$db->where('is_partner', 0);
    $childData = $db->get('users');
    
    if(!empty($childData)){
        $hideCopyChild = '';
    }
}

$is_partner = 1;

$partnerFirstName = '';
$partnerLastName = '';
$partnerTitle = '';
$partnerPrefix = '';
$partnerAge = '';
$partnerGender = '';
$partnerEmail = '';
$partnerRelation = '';
$checked = $disabled = '';
$current_relation = 0;
if ($action == 1) {
    // Create


} elseif ($action == 2) {    
    $hideTerms = $hideCopyChild = 'hide';
    $disabled = 'disabled="disabled"';
           
    $copiedChild = $db->rawQuery("SELECT Id FROM pg_users WHERE ParentId = ".$_SESSION['UserId']." AND is_partner = 0 AND FIND_IN_SET($PartnerId,ParentId_2) > 0");
    
    $copiedChild1 = array();    
    if(!empty($copiedChild)){
        $hideChildList = ''; 
           
        foreach($copiedChild as $val){
            array_push($copiedChild1, $val['Id']);
        }
    }else{
        $hideChildList = 'hide';
    }
    
    // Update
	$db->where('Id', $PartnerId);
	$db->where('is_partner', $is_partner);
    $partnerData = $db->getOne('users');
    
    $partnerFirstName = $partnerData['Firstname'];
    $partnerLastName = $partnerData['Lastname'];
    $partnerTitle = $partnerData['PartnersiteTitle'];
    $partnerPrefix = $partnerData['Prefix'];
    // $partnerAge = $partnerData['Age'];
    // $partnerGender = $partnerData['Gender'];
    $partnerEmail = $partnerData['Email'];
    $partnerRelation = $partnerData['partnerRelation'];
    $is_partner = $partnerData['is_partner'];
    $current_relation = $partnerData['current_relation_with'];
    
} elseif ($action == 3) {
    
    // Delete
}
?>


<div class="modal-header">
    <div class="col-md-6">
        <h3>Colleague</h3>
    </div>
    <div class="col-md-6">
        <button id="btn-partner-modal-save" class="btn-cta btn-save">Save</button>
    </div>
</div>

<div class="section">
    <input type="hidden" name="partnerAction" class="" value="<?php echo $action; ?>" />
    <input type="hidden" name="partnerId" class="" value="<?php echo $PartnerId; ?>" />
    <input type="hidden" name="is_partner" class="" value="<?php echo $is_partner; ?>" />
    <input type="hidden" name="familyId" class="" value="<?php echo (isset($_SESSION['FamilyId']) && !empty($_SESSION['FamilyId'])) ? $_SESSION['FamilyId'] : 0; ?>" />

    <div class="row">
        <div class="col-md-6">
            <label>Firstname <span class="required-field">*<!-- Påkrævet--></span></label>
            <input type="text" name="partnerFirstName" class="" value="<?php echo $partnerFirstName; ?>" />
        </div>
        <div class="col-md-6">
            <label>Surname <span class="required-field">*<!-- Påkrævet--></span></label>
            <input type="text" name="partnerLastName" class="" value="<?php echo $partnerLastName; ?>" />
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label>Title</label>
            <input  type="" class="form-control" id="partnerTitle" name="PartnersiteTitle" value="<?php echo $partnerTitle; ?>" placeholder="Enter title">
             <!-- <input id="partnerAge" type="text" name="partnerAge" class="datepicker" value=" -->
            <?php
            // if ($partnerAge == '0000-00-00') {
            //     echo '00/00/0000';
            // } else {
            //     echo date('d/m/Y', strtotime($partnerAge));
            // } ?>" 
            <!-- autocomplete="off" /> --> 
        </div>
        <div class="col-md-6">
            <label>Prefix</label>
            <!-- <div class="custom-dropdown"> -->
                <select name="Prefix" id="Prefix" class="form-control">
                    <option value="0">Select Prefix</option>
                    <option value="Ms." <?php if($partnerPrefix == 'Ms.'){ echo "selected"; } ?>>Ms.</option>
                    <option value="Mrs." <?php if($partnerPrefix == 'Mrs.'){ echo "selected"; } ?>>Mrs.</option>
                    <option value="Mr." <?php if($partnerPrefix == 'Mr.'){ echo "selected"; } ?>>Mr.</option>
                    <option value="Other" <?php if($partnerPrefix == 'Other'){ echo "selected"; } ?>>Other</option>
                </select>
            <!-- </div> -->
        </div>
        <!-- <div class="col-md-6">
            <label>Køn <span class="required-field">*</span></label>
            <div class="row pg-input-radio pg-radio">
                <div class="col-md-6">
                    <input type="radio" class="form-control" name="partnerGender" id="partnerGenderMale" value="Male" <?php
            // if ($partnerGender == 'Male') {
            //     echo "checked";
            // }
            ?>>
                    <span class="checkmark"></span>
                    <label for="partnerGenderMale"><?php echo _('Mand'); ?></label>
                </div>
                <div class="col-md-6">
                    <input type="radio" class="form-control" name="partnerGender" id="partnerGenderFemale" value="Female" <?php
                    // if ($partnerGender == 'Female') {
                    //     echo "checked";
                    // }
            ?>>
                    <span class="checkmark"></span>
                    <label for="partnerGenderFemale"><?php echo _('Kvinde'); ?></label>
                </div>
            </div>

        </div> -->
    </div>
    <div class="row">
        <!-- <div class="col-md-6">
            <label>Relation <span class="required-field">*</span></label>
            <input type="text" name="partnerRelation" class="" value="<?php //echo $partnerRelation; ?>" />
            <select name="partnerRelation" id="partnerRelation" class="form-control">
                <option value="1" <?php if($partnerRelation == 'Ægtefælle' || $partnerRelation == 'Spouse') //echo 'selected="selected"'; ?>>Ægtefælle</option>
                <option value="2" <?php if($partnerRelation == 'Samlever') //echo 'selected="selected"'; ?>>Samlever</option>
                <option value="3" <?php if($partnerRelation == 'Kæreste') //echo 'selected="selected"'; ?>>Kæreste</option>                
                <option value="4" <?php if($partnerRelation == 'Ekskone/Eksmand') //echo 'selected="selected"'; ?>>Ekskone/Eksmand</option>
                <option value="5" <?php if($partnerRelation == 'Andet') //echo 'selected="selected"'; ?>>Andet</option>
            </select>
        </div> -->
        <?php
        if($partnerEmail != ''){
            $hideCls = '';
            $disableEmail = 'readonly="readonly"';
        }else{
            $hideCls = 'hide';
        }
        ?>
        <!-- email-field-section -->
        <div class="col-md-6 <?php //echo $hideCls; ?>" id="">
            <label>E-mail <span class="required-field">*<!-- Påkrævet--></span></label>
            <input type="email" name="partnerEmail" class="" value="<?php echo $partnerEmail; ?>" <?php echo $disableEmail; ?>/>
        </div>        
    </div>

    <div class="row <?php //echo $hideTerms; ?>" style="margin-top: 20px;">
        <div class="col-md-12" style="display: inline-flex; padding-left: 9px;">
            <!-- <input name='add_partner_term' class='' type='checkbox' id='add_partner_term' value='1' style="width: 16%; "/> -->
            <!-- <span>Hvis du gerne vil have sendt en e-mail til din partner, der beder ham/hende om at oprette sin egen profil på FamArena, så han/hun også kan deltage i alt det sjove og gode, så skriv deres e-mailadresse her og klik på boks. De vil derefter modtage en e-mail med et link, der fører direkte til denne profilside, og 1-2-3 er din partner en del af den digitale FitforKids-magi!</span> -->
            <span>If you have a colleague who would also like to be part of implementing FitforKids in your territory, please enter his/her e-mail address above. We will then forward a link, that will lead them directly to their very own profile data page and you can join FitforKids together.</span>
        </div>
    </div>

    <!-- <div class="row hide <?php echo $hideCopyChild; ?>" id="copy_child_row" style="margin-top: 20px;">
        <div class="col-md-10" style="display: inline-flex; padding-left: 9px;">
            <input name='copy_child' class='' type='checkbox' id='copy_child' value='1' style="width: 6%; "/>
            <span>Ønsker du at give denne partner adgang til at se dit barns profil her på FamArena?</span>
        </div>
    </div> -->
    
    <div class="row <?php echo $hideChildList; ?>" style="margin-top: 20px;" id="children-list-section">
        <div class="col-md-12" >
        <label>Child <span class="required-field">*<!-- Påkrævet--></span></label>
        <?php
        foreach($childData as $child){
            $db->where('child_id', $child['Id']);
			$db->where('parent_id', $PartnerId);
            $partnerAccessRight = $db->getOne('child_profile_access_rights');
            
            $checked1 = $checked2 = '';
			$disabled1 = '';
                    
            if(($action == 2 && in_array($child['Id'], $copiedChild1)) || empty($copiedChild1)){
                if($action == 2 && $partnerAccessRight['access_right'] == 1){
                    $checked1 = 'checked="checked"';
                    $checked2 = '';
                    $disabled1 = 'disabled="disabled"';
                } else if($action == 2 && $partnerAccessRight['access_right'] == 2){
                    $rightsOptionStyle = '';
                    $checked2 = 'checked="checked"';
                    $checked1 = '';
                    $disabled1 = 'disabled="disabled"';
                }else if($action == 1){
                    $checked1 = $checked2 = $disabled1 = '';
                }else{
                    $checked1 = '';
					$checked2 = '';
					$disabled1 = 'disabled="disabled"';
                }
            }         
            ?> 
            <!-- <div class="row" style="margin-left: -19px !important;">
			    <div class="col-md-3">
				    <b><label style="display: inline;"><?php echo $child['Firstname'].' '.$child['Lastname']; ?>:</spalabeln></b>
				</div>

				<div class="col-md-4" >
                <input name='child_<?php echo $child['Id'];?>[]' class='child_radio' type='radio' id='child_se_<?php echo $child['Id']; ?>' value='<?php echo $child['Id']; ?>' data-val="1" style="width: 12%; " <?php echo $checked1; echo $disabled1; ?>/> Se data/profil
				</div>

				<div class="col-md-4">
                <input name='child_<?php echo $child['Id'];?>[]' class='child_radio' type='radio' id='child_rette_<?php echo $child['Id']; ?>' value='<?php echo $child['Id']; ?>' data-val="2" style="width: 12%; " <?php echo $checked2; echo $disabled1; ?>/> Se + Rette data/profil
				</div>
			</div> -->

            <!-- <input name='child[]' class='child_checkbox' type='checkbox' id='child_<?php echo $child['Id']; ?>' value='<?php echo $child['Id']; ?>' style="width: 12%; " <?php echo $checked; echo $disabled; ?>/>
            <span style="display: inline;"><?php echo $child['Firstname'].' '.$child['Lastname']; ?></span><br/> -->
            <?php
        }
        ?>  
        </div>
    </div>
    <?php
    if($action == 2 && $current_relation == 1){
        $current_relation_checked = 'checked="checked"';
    }else{
        $current_relation_checked = '';
    }
    ?>
    
    <!-- <div class="row" style="margin-top: 20px;">
        <div class="col-md-10" style="display: inline-flex;">
            <input name='current_relation' class='' type='checkbox' id='current_relation' value='1' style="width: 6%;" <?php echo $current_relation_checked; ?>/>
            <span>Nuværende forhold til</span>
        </div>
    </div> -->

    <?php if ($PartnerId != 0) { ?>
        <div class="row">
            <div class="col-md-12">
                <a class="btn-delete-partner btn-delete" data-type="1" data-id="<?php echo $PartnerId; ?>">Delete Colleague</a>
            </div>
        </div>
<?php } ?>
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12 col-sm-12 required-field">
        All fields marked with * must be filled.
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 required-field">
    <span class="required-field hide" id="partnerUserErr">Please fill in values ​​in mandatory fields.</span>
    </div>
</div>


</div>
<script>
// $('select').niceSelect();

    $('#btn-partner-modal-save').off('click');

    $('html').on('click', '.success a.close', function () {
        $('.md-modal').removeClass('md-show');
        $('.md-content-inner').html('');
    });

    $('#add_partner_term').click(function(){
        if($(this).is(":checked")){
          $('#email-field-section').removeClass('hide');
          $('#copy_child_row').removeClass('hide');
        } else if($(this).is(":not(:checked)")) {
            $('#email-field-section').addClass('hide');
            $('#copy_child_row').addClass('hide');
        }
    });

    $('#copy_child').click(function(){
        if($(this).is(":checked")){
          $('#children-list-section').removeClass('hide');
        } else if($(this).is(":not(:checked)")) {
            $("input[class=child_checkbox]").removeAttr("checked");
            $('#children-list-section').addClass('hide');
        }
    });
    
</script>