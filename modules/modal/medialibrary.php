<?php
include("../../includes/config.php");

if(!empty($_GET['id'])){
    $formId = $_GET['id'];
    $formAction = 2;
}else{
    $formId = 0;
    $formAction = 1;
}


if(!empty($formId)){

    $db->where('Id',$formId);
    $form = $db->getOne('forms');

    if($form['CustomerId'] != $_SESSION['CustomerId']){
        exit;
    }
}

?>


<div class="modal-header">
    <div class="col-md-6">
        <?php if(!empty($formId)){ ?>
            <h3><?php echo _('Edit form'); ?></h3>
        <?php }else{ ?>
            <h3><?php echo _('Add new form'); ?></h3>
        <?php } ?>
    </div>
    <div class="col-md-6">
        <?php if(!empty($_GET['id'])){ ?>
        <button id="btn-modal-delete" class="btn-cta btn-delete">Delete</button>
        <?php } ?>
        <button id="btn-modal-save" class="btn-cta btn-save">Save</button>
    </div>
</div>

<div class="section list-add">

    <div class="row">

        <div class="col-md-12">
            <input id="form-title" name="formTitle" class="form-title" placeholder="<?php echo _('Form title'); ?>" value="<?php if(!empty($formId)){ echo $form['Name']; } ?>" />
            <input id="form-action" type="hidden" value="<?php echo $formAction; ?>" />
            <input id="form-id" type="hidden" value="<?php echo $formId; ?>" />
        </div>

        <div id="opty-form-fields" class="sortable">
            <?php if(!empty($formId)){

                $db->where('FormId',$formId);
                $db->orderBy('Position','ASC');
                $fields = $db->get('formfields');

                foreach($fields as $field){

                    $uid = rand(1000,9999);
                    $fieldId = $field['Id'];

                    if($field['Type'] == 'default input'){ ?>
                        <div data-id="<?php echo $uid; ?>" data-field-id="<?php echo $fieldId; ?>" class="row field" data-field-type="default input">
                            <em>Default input</em>
                            <input type="text" class="field-label" placeholder="Field title" value="<?php echo $field['Name']; ?>" />
                        </div>
                    <?php }elseif($field['Type'] == 'input'){ ?>
                        <div data-id="<?php echo $uid; ?>" data-field-id="<?php echo $fieldId; ?>" class="row field" data-field-type="input">
                            <em>Input</em>
                            <input type="text" class="field-label" placeholder="Field title" value="<?php echo $field['Name']; ?>" />
                            <a onclick="formFieldDelete(<?php echo $uid; ?>,<?php echo $fieldId; ?>,1,<?php echo $formAction; ?>)">
                            <i class="btn-delete"></i>
                            </a>
                        </div>
                    <?php }else if($field['Type'] == 'textarea'){ ?>
                        <div data-id="<?php echo $uid; ?>" data-field-id="<?php echo $fieldId; ?>" class="row field" data-field-type="textarea">
                            <em>Textarea</em>
                            <input type="text" class="field-label" placeholder="Field title" value="<?php echo $field['Name']; ?>" />
                            <a onclick="formFieldDelete(<?php echo $uid; ?>,<?php echo $fieldId; ?>,1,<?php echo $formAction; ?>)">
                            <i class="btn-delete"></i>
                            </a>
                        </div>
                    <?php }else if($field['Type'] == 'select'){ ?>
                        <div data-id="<?php echo $uid; ?>" data-field-id="<?php echo $fieldId; ?>" class="row field" data-field-type="select">
                            <em>Select</em>
                            <input type="text" class="field-label" placeholder="Field title" value="<?php echo $field['Name']; ?>" />
                            <a onclick="formFieldDelete(<?php echo $uid; ?>,<?php echo $fieldId; ?>,1,<?php echo $formAction; ?>)">
                            <i class="btn-delete"></i>
                            </a>
                            <span class="btn-add-option" onclick="addFormOption(<?php echo $uid; ?>,'select')">Add option</span>
                            <div class="opty-options">
                                <?php 
                                if(!empty($field['Options'])){
                                    $options = explode(',',$field['Options']);
                                    foreach($options as $option){ 

                                    $uidOption = rand(1000,9999);
                                    //$optionId = $option['Id'];

                                    ?>
                                    <div data-id="<?php echo $uidOption; ?>" class="option">
                                        <input type="text" placeholder="Option" value="<?php echo $option; ?>" />
                                        <a onclick="formFieldDelete(<?php echo $uidOption; ?>,<?php echo $fieldId; ?>,2,<?php echo $formAction; ?>)">
                                            <i class="btn-delete"></i>
                                        </a>
                                    </div>
                                    <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php }else if($field['Type'] == 'checkbox'){ ?>
                        <div data-id="<?php echo $uid; ?>" data-field-id="<?php echo $fieldId; ?>" class="row field" data-field-type="checkbox">
                            <em>Checkbox</em>
                            <input type="text" class="field-label" placeholder="Field title" value="<?php echo $field['Name']; ?>" />
                            <a onclick="formFieldDelete(<?php echo $uid; ?>,<?php echo $fieldId; ?>,1,<?php echo $formAction; ?>)">
                            <i class="btn-delete"></i>
                            </a>
                            <span class="btn-add-option" onclick="addFormOption(<?php echo $uid; ?>,'checkbox')">Add option</span>
                            <div class="opty-options">
                                <?php 
                                if(!empty($field['Options'])){
                                    $options = explode(',',$field['Options']);
                                    foreach($options as $option){ 

                                    $uidOption = rand(1000,9999);
                                    ?>
                                    <div data-id="<?php echo $uidOption; ?>" class="option">
                                        <input type="text" placeholder="Option" value="<?php echo $option; ?>" />
                                        <a onclick="formFieldDelete(<?php echo $uidOption; ?>,<?php echo $fieldId; ?>,2,<?php echo $formAction; ?>)">
                                            <i class="btn-delete"></i>
                                        </a>
                                    </div>
                                    <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php }else if($field['Type'] == 'radio'){ ?>
                        <div data-id="<?php echo $uid; ?>" data-field-id="<?php echo $fieldId; ?>" class="row field" data-field-type="radio">
                            <em>Radio</em>
                            <input type="text" class="field-label" placeholder="Field title" value="<?php echo $field['Name']; ?>" />
                            <a onclick="formFieldDelete(<?php echo $uid; ?>,<?php echo $fieldId; ?>,<?php echo $fieldId; ?>,1,<?php echo $formAction; ?>)">
                            <i class="btn-delete"></i>
                            </a>
                            <span class="btn-add-option" onclick="addFormOption(<?php echo $uid; ?>,'radio')">Add option</span>
                            <div class="opty-options">
                                <?php 
                                if(!empty($field['Options'])){
                                    $options = explode(',',$field['Options']);
                                    foreach($options as $option){ 

                                    $uidOption = rand(1000,9999);
                                    ?>
                                    <div data-id="<?php echo $uidOption; ?>" class="option">
                                        <input type="text" placeholder="Option" value="<?php echo $option; ?>" />
                                        <a onclick="formFieldDelete(<?php echo $uidOption; ?>,<?php echo $fieldId; ?>,2,<?php echo $formAction; ?>)">
                                            <i class="btn-delete"></i>
                                        </a>
                                    </div>
                                    <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php }

                }

            }else{ 

                $uid = rand(1000,9999);
            ?>

            <div data-id="email" data-field-id="email" class="row field" data-field-type="default input">
                <em>Default input</em>
                <input type="text" class="field-label" placeholder="Email" value="Email" />
                <span class="small">(The e-mail field is required to be in the form)</span>
            </div>

            <?php } ?>
        </div>
        
    </div>

    <div class="row">

        <div class="col-md-12">
            <div id="area-add-field">
                <span class="opic-add-input-simple"></span>
                <div class="col-md-12 form-field-types">

                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn-modal" onclick="addFormField('input')"><i></i> <?php  echo _('Regular field'); ?></a>
                        </div>

                        <div class="col-md-6">
                            <a class="btn-modal" onclick="addFormField('textarea')"><i></i> <?php  echo _('Text field'); ?></a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn-modal" onclick="addFormField('select')"><i></i> <?php  echo _('Select'); ?></a>
                        </div>

                        <div class="col-md-6">
                            <a class="btn-modal" onclick="addFormField('checkbox')"><i></i> <?php  echo _('Checkbox'); ?></a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <a class="btn-modal" onclick="addFormField('radio')"><i></i> <?php  echo _('Radio'); ?></a>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
    </div>

</div>

<?php if(!empty($_GET['id'])){ ?>
<div id="modal-delete-message" class="modal-delete-message">
    <h2><?php echo _('Are you sure?'); ?></h2>
    <button id="btn-modal-cancel-delete" class="btn"><?php echo _('No, cancel action'); ?></button>
    <button id="btn-modal-confirm-delete" onclick="formDelete(<?php echo $formId; ?>)" class="btn btn-delete"><?php echo _('Yes, delete this'); ?></button>
</div>

<script>

$('#btn-modal-cancel-delete').click(function(){

    $('#modal-delete-message').removeClass('show');

});

$('#btn-modal-delete').click(function(){

    $('#modal-delete-message').addClass('show');

});


function formDelete($id){

    $.ajax({
        type: "POST",
        url: '/actions/ajax/crud_form.php',
        data: {
            'Action': 3,
            'FormId': $id
        },
        success: function(data)
        {

            $('#list-outer').load('/modules/lists/list-forms.php', function(){

                setTimeout(function() {
                    $('#opty-js').load("/actions/js.php");
                }, 500);

            });

            $('#modal-form-crud').removeClass('md-show');
            /*setTimeout(function() {
                $('#modal-builder-content div').remove();
            }, 500);*/

        }
    });

}

</script>
<?php } ?>