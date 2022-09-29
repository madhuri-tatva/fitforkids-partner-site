<?php 
include("../../includes/config.php");

putenv('LC_ALL='.$_SESSION['Language']);
setlocale(LC_ALL, $_SESSION['Language']);

bindtextdomain($_SESSION['Language'], "../../locale");
bind_textdomain_codeset($_SESSION['Language'], 'UTF-8'); 

textdomain($_SESSION['Language']);

$ModalShiftId = $_GET['id'];

echo $_GET['id'];
echo $_GET['shifttype'];
echo $_GET['employeegroup'];
echo $_GET['absence'];

?>


                <div class="meta-actions">

                    <a class="btn btn-default md-close"><?php echo _('Back'); ?></a>

                    <span class="btn-cta save"><?php if($_SESSION['Type'] < 3){ echo _('Save'); }else{ echo _('Done'); } ?></span>

                </div>

                <div id="modal-shifttype-content" class="">

                    <div class="modal-header">
                    
                        <h1><?php echo _('Additional settings'); ?></h1>
                        <p><?php echo _('Configure additional settings to this shift.'); ?></p>

                    </div>

                    <input type="hidden" id="ModalAdvanceId" value="<?php echo $ModalShiftId; ?>" />

                    <!--<div class="modal-advanced-content">
                        <div class="md-col-4">
                            <h3><?php echo _('Shift type'); ?></h3>
                        </div>
                        <div class="md-col-4">
                            <h3><?php echo _('Employee group'); ?></h3>
                        </div>
                        <div class="md-col-4">
                            <h3><?php echo _('Absence'); ?></h3>
                        </div>
                    </div>-->

                    <div class="form-group md-col-12 form-fields">
                        <div class="form-group md-col-4">
                            <label class="control-label col-sm-12 col-md-12" for="">
                                <?php echo _('Shift type'); ?>
                            </label>
                            <div class="col-sm-8 col-md-12 btn-extra modal-field-shifttype">
                                <select name="test" class="form-control">
                                    <option value="0"><?php echo _('Normal shift'); ?></option>
                                    <?php

                                      $db->where("CustomerId", $_SESSION['CustomerId']);
                                      $db->where("DepartmentId", 0);
                                      $standard_absence = $db->get('shifttypes');
                                          foreach ($standard_absence as $data) { ?>
                                              <option value="<?php echo $data['Id']; ?>"><?php echo $data['Title']; ?></option>
                                        <?php }

                                      $db->where("CustomerId", $_SESSION['CustomerId']);
                                      $db->where("DepartmentId", $_SESSION['DepartmentId']);
                                      $standard_absence = $db->get('shifttypes');
                                          foreach ($standard_absence as $data) { ?>
                                              <option value="<?php echo $data['Id']; ?>"><?php echo $data['Title']; ?></option>
                                        <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group md-col-4">
                            <label class="control-label col-sm-12 col-md-12" for="">
                                <?php echo _('Employee group'); ?>
                            </label>
                            <div class="col-sm-8 col-md-12 btn-extra modal-field-title">
                                <select name="test" class="form-control">
                                    <option value="0"><?php echo _('All'); ?></option>
                                    <?php

                                      $db->where("CustomerId", $_SESSION['CustomerId']);
                                      $db->where("DepartmentId", 0);
                                      $standard_titles = $db->get('titles');
                                          foreach ($standard_titles as $data) { ?>
                                              <option value="<?php echo $data['Id']; ?>"><?php echo $data['Title']; ?></option>
                                          <?php }

                                      $db->where("CustomerId", $_SESSION['CustomerId']);
                                      $db->where("DepartmentId", $_SESSION['DepartmentId']);
                                      $standard_titles = $db->get('titles');
                                          foreach ($standard_titles as $data) { ?>
                                              <option value="<?php echo $data['Id']; ?>"><?php echo $data['Title']; ?></option>
                                        <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group md-col-4">
                            <label class="control-label col-sm-12 col-md-12" for="">
                                <?php echo _('Absence'); ?>
                            </label>
                            <div class="col-sm-8 col-md-12 btn-extra modal-field-absence">
                                <select name="test" class="form-control">
                                    <option value="0"><?php echo _('None'); ?></option>
                              <?php

                                  $db->where("CustomerId", 0);
                                  $standard_absence = $db->get('absence');
                                      foreach ($standard_absence as $data) { ?>
                                          <option value="<?php echo $data['Id']; ?>"><?php echo _($data['Title']); ?></option>
                                      <?php }

                                  $db->where("CustomerId", $_SESSION['CustomerId']);
                                  $db->where("DepartmentId", 0);
                                  $standard_absence = $db->get('absence');
                                      foreach ($standard_absence as $data) { ?>
                                          <option value="<?php echo $data['Id']; ?>"><?php echo $data['Title']; ?></option>
                                      <?php }

                                  $db->where("CustomerId", $_SESSION['CustomerId']);
                                  $db->where("DepartmentId", $_SESSION['DepartmentId']);
                                  $standard_absence = $db->get('absence');
                                      foreach ($standard_absence as $data) { ?>
                                          <option value="<?php echo $data['Id']; ?>"><?php echo $data['Title']; ?></option>
                                      <?php }
                              ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>


<script type="text/javascript">
$('.save').click(function(){

    var CurrentModalAdvanceId = $(this).parents("div:eq(-2)").find('#ModalAdvanceId').val();

    var CurrentModalAdvanceTitle = $(this).parents("div:eq(-2)").find(".modal-field-title li.selected").attr("data-value");
    var CurrentModalAdvanceShiftType = $(this).parents("div:eq(-2)").find(".modal-field-shifttype li.selected").attr("data-value");
    var CurrentModalAdvanceAbsence = $(this).parents("div:eq(-2)").find(".modal-field-absence li.selected").attr("data-value");


    console.log(CurrentModalAdvanceTitle);
    console.log(CurrentModalAdvanceShiftType);
    console.log(CurrentModalAdvanceAbsence);


    $('#'+CurrentModalAdvanceId+' .data-settings').attr("data-settings","{"+CurrentModalAdvanceShiftType+","+CurrentModalAdvanceTitle+","+CurrentModalAdvanceAbsence+"}");


});

jQuery(function($) {
    $('select.form-control').niceSelect();
});
</script>
