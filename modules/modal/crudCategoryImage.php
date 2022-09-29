<?php
include("../../includes/config.php");


?>

<div class="modal-header">
	<div class="col-md-6"></div>	
	<button id="btn-modal-close" class="btn-cta btn-save">
		<img src="/assets/img/close-white.svg">
	</button>	
</div>

<div class="section">
	<img class="modal-image" src="<?php echo $_GET['url']; ?>">
</div>



<script>

$(document).off('click','#btn-modal-close');

$('html').on('click','#btn-modal-close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});


</script>


