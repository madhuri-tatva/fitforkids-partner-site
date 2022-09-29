<?php
include("../../includes/config.php");

$section = $_GET['section'];

if(isset($_GET['id'])){
	$categoryId = $_GET['id'];
}else{
	$categoryId = 0;
}

$medias = $db->get('media');

// Sort media
$mediasSorted = array();
foreach($medias as $media){

	$mediasSorted[$media['Id']] = $media;

}


$products = $db->get('products');

// Sort media
$productsSorted = array();
foreach($products as $product){

	$productsSorted[$product['Id']] = $product;

}

?>

<style>
#modal-category-add td {
    border-bottom: 1px solid #eee;
}

.nice-select.form-control:hover {
    border: 0!important;
}
</style>

<div class="modal-header">
	<div class="col-md-6">
		<h3>Tilføj ressource</h3>
	</div>
	<div class="col-md-6">
		
	</div>
</div>

<div class="section">


	<input type="hidden" name="categoryAddId" class="" value="<?php echo $categoryId; ?>" />
	<input type="hidden" name="categoryAddSection" class="" value="<?php echo $section; ?>" />

	<div class="row">
		<div class="col-md-12">

			<div class="section-file show-search">

				<?php if(!empty($productsSorted)){ ?>
				<table id="modal-products" class="list">
					<thead>
						<tr>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($productsSorted as $product){ ?>
						<tr class="" data-product-id="<?php echo $product['Id']; ?>">
							<td>

								<?php if(isset($mediasSorted[$product['Thumbnail']])){ ?> 

									<img class="img-small" src="<?php echo $mediasSorted[$product['Thumbnail']]['URL']; ?>" />

								<?php } ?>
									
							</td>
							<td><?php echo $product['Name']; ?></td>
							<td class="right"><a class="btn-add-this" data-product="<?php echo $product['Id']; ?>">Tilføj</a></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<?php } ?>

			</div>

		</div>
	</div>

</div>



<script>
$('select').niceSelect();


$(document).off('click','#btn-modal-save');

$('html').on('click','.success a.close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});


</script>


