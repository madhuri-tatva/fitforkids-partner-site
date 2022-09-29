<?php
include("../../includes/config.php");

$action = $_GET['action'];

if(isset($_GET['id'])){
	$customerId = $_GET['id'];
}else{
	$customerId = 0;
}

$customerFirstName 		= '';
$customerLastName 		= '';
$customerPhoneNumber 	= '';
$customerEmail 			= '';

$customerAddress 		= '';
$customerZipcode 		= '';
$customerCity 			= '';
$customerCompany 		= '';
$customerVAT 			= '';

if($action == 1){

	// Create

}elseif($action == 2){

	// Update
	$db->where('Id',$customerId);
	$customerData = $db->getOne('customers');

	$customerFirstName 		= $customerData['Firstname'];
	$customerLastName 		= $customerData['Lastname'];
	$customerPhoneNumber 	= $customerData['PhoneNumber'];
	$customerEmail 			= $customerData['Email'];

	$customerAddress 		= $customerData['Address'];
	$customerZipcode 		= $customerData['Zipcode'];
	$customerCity 			= $customerData['City'];
	$customerCompany 		= $customerData['Company'];
	$customerVAT 			= $customerData['VatNumber'];

}elseif($action == 3){

	// Delete

}
?>

<div class="modal-header">
	<div class="col-md-6">
		<h3>Customer</h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save" class="btn-cta btn-save">Save</button>
	</div>
</div>

<div class="section">


	<input type="hidden" name="customerAction" class="" value="<?php echo $action; ?>" />
	<input type="hidden" name="customerId" class="" value="<?php echo $customerId; ?>" />

	<div class="row">
		<div class="col-md-6">
			<label>First name</label>
			<input type="text" name="customerFirstName" class="" value="<?php echo $customerFirstName; ?>" />
		</div>
		<div class="col-md-6">
			<label>Last name</label>
			<input type="text" name="customerLastName" class="" value="<?php echo $customerLastName; ?>" />
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>Phone number</label>
			<input type="text" name="customerPhoneNumber" class="" value="<?php echo $customerPhoneNumber; ?>" />
		</div>
		<div class="col-md-6">
			<label>Email</label>
			<input type="text" name="customerEmail" class="" value="<?php echo $customerEmail; ?>" />
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>Address</label>
			<input type="text" name="customerAddress" class="" value="<?php echo $customerAddress; ?>" />
		</div>
		<div class="col-md-6">
			<label>Zipcode</label>
			<input type="text" name="customerZipcode" class="" value="<?php echo $customerZipcode; ?>" />
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>City</label>
			<input type="text" name="customerCity" class="" value="<?php echo $customerCity; ?>" />
		</div>
		<div class="col-md-6">
			<label>Company</label>
			<input type="text" name="customerCompany" class="" value="<?php echo $customerCompany; ?>" />
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>VAT</label>
			<input type="text" name="customerVAT" class="" value="<?php echo $customerVAT; ?>" />
		</div>
	</div>

</div>



<script>

$('#btn-modal-save').off('click');

$('html').on('click','.success a.close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});



</script>


