<?php
include("../../includes/config.php");

$action = $_GET['action'];
$productid = $_GET['productid'];

$medias = $db->get('media');

// Sort media
$mediaSorted = array();
foreach($medias as $media){

	if($media['Type'] == 'jpg' or $media['Type'] == 'jpeg' or $media['Type'] == 'png'){
		$mediaSorted['Images'][] = $media;
	}else{
		$mediaSorted['Medias'][] = $media;
	}

}

$productTitle = '';
$productSection = 0;
$productCategory = 0;
$productSubcategory = 0;
$productDescription = '';
$productMedias = array();
$productThumbnail = 0;


if($action == 1){

	// Create

}elseif($action == 2){

	// Update
	$db->where('Id',$productid);
	$product = $db->getOne('products');

	$productTitle = $product['Name'];
	$productSection = $product['Section'];
	$productCategory = $product['Category'];
	$productSubcategory = $product['Subcategory'];
	$productDescription = $product['Description'];
	$productMedias = $product['Medias'];

	$productMedias = explode(',',$productMedias);

	$productThumbnail = $product['Thumbnail'];

}elseif($action == 3){

	// Delete

}


$allCategories = $db->get('categories');
$allCategoriesLevel1Sorted = array();
$allCategoriesLevel2Sorted = array();

foreach($allCategories as $category){

	if($category['Level'] == 1){
		$allCategoriesLevel1Sorted[$category['Id']] = $category; 
	}else{
		$allCategoriesLevel2Sorted[$category['Id']] = $category; 
	}

}
?>
			

<div class="modal-header">
	<div class="col-md-6">
		<h3>Placer filer</h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save-resource" class="btn-cta btn-save">Gem</button>
	</div>
</div>

<div class="section">

	<input type="hidden" name="productAction" class="" value="<?php echo $action; ?>" />
	<input type="hidden" name="productId" class="" value="<?php echo $productid; ?>" />

	<div class="row">
		<div class="col-md-12">
			<label>Titel</label>
			<input type="text" name="productTitle" class="" value="<?php echo $productTitle; ?>" />
		</div>
	</div>

	<div class="row">
		<div class="col-md-4" id="select-section">
			<label>Sektion</label>
			<select class="">
				<option value="0" <?php if($productSection == 0){ echo "selected"; } ?>>Vælg sektion</option>
				<option value="1" <?php if($productSection == 1){ echo "selected"; } ?>>Body</option>
				<option value="2" <?php if($productSection == 2){ echo "selected"; } ?>>Soul</option>
				<option value="3" <?php if($productSection == 3){ echo "selected"; } ?>>Mind</option>
			</select>
		</div>
		<div class="col-md-4" id="select-category">
			<label>Kategori</label>
			<select class="">
				<option value="0" <?php if($productCategory == 0){ echo "selected"; } ?>>Vælg kategori</option>
				<?php foreach($allCategoriesLevel1Sorted as $category){ ?>
					<option value="<?php echo $category['Id'] ?>" <?php if($productCategory == $category['Id']){ echo "selected"; } ?>><?php echo $category['Name'] ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-md-4" id="select-subcategory">
			<label>Underkategori</label>
			<select class="">
				<option value="0" <?php if($productSubcategory == 0){ echo "selected"; } ?>>Vælg underkategori</option>
				<?php foreach($allCategoriesLevel2Sorted as $category){ ?>
					<option value="<?php echo $category['Id'] ?>" <?php if($productSubcategory == $category['Id']){ echo "selected"; } ?>><?php echo $category['Name'] ?></option>
				<?php } ?>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<label>Beskrivelse</label>
			<textarea name="productDescription"><?php echo $productDescription; ?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>Filer</label>
			<div class="section-file show-search">

				<?php if(!empty($mediaSorted['Medias'])){ ?>
				<table id="modal-medias" class="list">
					<thead>
						<tr>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($mediaSorted['Medias'] as $media){ ?>
						<tr class="<?php if(in_array($media['Id'], $productMedias)){ echo 'active'; } ?>" data-media-id="<?php echo $media['Id'] ?>">
							<td><?php echo $media['FileName']; ?></td>
							<td class="right"><a class="md-trigger" data-modal="modal-product" onclick="modalChooseMedia(<?php echo $media['Id']; ?>)">Vælg</a></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<?php } ?>

			</div>
		</div>
		<div class="col-md-6">
			<label>Thumbnail</label>
			<div class="section-file show-search">

				<?php if(!empty($mediaSorted['Images'])){ ?>
				<table id="modal-images" class="list">
					<thead>
						<tr>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($mediaSorted['Images'] as $media){ ?>
						<tr class="<?php if($media['Id'] == $productThumbnail){ echo 'active'; } ?>" data-media-id="<?php echo $media['Id'] ?>">
							<td><img class="img-small" src="<?php echo $media['URL']; ?>" /></td>
							<td><?php echo $media['FileName']; ?></td>
							<td class="right"><a  class="md-trigger" data-modal="modal-product" onclick="modalChooseImage(<?php echo $media['Id']; ?>)">Vælg</a></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<?php } ?>

			</div>
		</div>
	</div>

	<?php if($productid != 0 && $_SESSION['Type'] == 1){ ?>
	<div class="row">
		<div class="col-md-12">
			<a class="btn-delete-item btn-delete" data-type="3" data-id="<?php echo $productid; ?>">Slet dette produkt</a>
		</div>
	</div>
	<?php } ?>

</div>



<script src="/assets/js/jquery.nice-select.min.js"></script>

<script>

$(document).on('change','#select-category',function(){

	var parentCategory = $('#select-category .selected').attr('data-value');
	$('#select-subcategory').load('/actions/ajax/getSubCategories.php?parent='+parentCategory);

});

$('select').niceSelect();

$('#btn-modal-save').off('click');

$('html').on('click','.success a.close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});

$('#modal-medias').dataTable( {
    "pageLength": 8,
    "order": [],
    "columnDefs": [ {
      "targets"  : 'no-sort',
      "orderable": false,
    }]
});

$('#modal-images').dataTable( {
    "pageLength": 5,
    "order": [],
    "columnDefs": [ {
      "targets"  : 'no-sort',
      "orderable": false,
    }]
});

</script>


