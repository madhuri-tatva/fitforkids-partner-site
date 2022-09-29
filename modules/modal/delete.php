<?php
include("../../includes/config.php");

$type   = $_GET['type'];
$id     = $_GET['id'];

?>


<div id="modal-delete-message" class="modal-delete-message init-message">
    <h2><?php echo _('Are you sure?'); ?></h2>
    <button id="btn-modal-cancel-delete" class="btn"><?php echo _('No, cancel action'); ?></button>
    <button id="btn-modal-confirm-delete" onclick="confirmDelete(<?php echo $type; ?>,<?php echo $id; ?>)" class="btn btn-delete"><?php echo _('Yes, delete this'); ?></button>
</div>

<script>

$('#btn-modal-cancel-delete').click(function(){

    $('#modal-delete').removeClass('md-show');

});



function confirmDelete($type,$id){

    if($type == 1){
        var urlValue = '/actions/ajax/crud_landingpage.php';
        var listReloadValue = '/modules/lists/list-landingpages.php';
        var returnPageValue = '/landingpages';
    }else if($type == 2){

    }else if($type == 3){

    }

    $.ajax({
        type: "POST",
        url: urlValue,
        data: {
            'Action': 3,
            'Id': $id
        },
        success: function(data)
        {

            window.location.replace(returnPageValue);

            /*$('#list-outer').load(listReloadValue, function(){

                setTimeout(function() {
                    $('#opty-js').load("/actions/js.php");
                }, 500);

            });

            $('#modal-delete').removeClass('md-show');*/

        }
    });

}

</script>
