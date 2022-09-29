<?php
include("../../includes/config.php");


$db->where('Id',$_GET['id']);
$orderData = $db->getOne('orders');



$db->where('Id',$orderData['CustomerId']);
$customerData = $db->getOne('customers');
?>

<div class="modal-header">
	<div class="col-md-12">
		<h3>Confirm delete</h3>
	</div>
</div>

<div class="section">

	<p>You have chosen to delete quote no. <?php echo sprintf('%08d', $orderData['Id']); ?>, <?php echo $customerData['Company']; ?>, <?php echo $orderData['Campaign']; ?>, <?php echo $orderData['TotalPrice'] . " " . $orderData['Currency']; ?>, <?php echo $customerData['Firstname']; ?> <?php echo $customerData['Lastname']; ?>.</p>

	<div class="modal-action">
		<button id="btn-modal-delete" data-order="<?php echo $orderData['Id']; ?>" class="btn-cta btn-delete">Confirm</button>
		<button id="btn-modal-abort" class="btn-cta btn-abort">Abort</button>
	</div>

</div>



<script>

$('html').on('click','#btn-modal-abort',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});

</script>


