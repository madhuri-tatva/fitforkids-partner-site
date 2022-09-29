<?php
include("../../includes/config.php");

$action = $_GET['action'];

if(isset($_GET['id'])){
	$categoryId = $_GET['id'];
}else{
	$categoryId = 0;
}

$categoryName = '';
$categoryDescription = '';
$categoryParent = '';
$categoryActive = '';

if(isset($_GET['parent'])){
	$categoryParent = $_GET['parent'];
}

$categoryMenu = array();
$categoryMenuHighlight = array();

if($action == 1){

	// Create

}elseif($action == 2){

	// Update
	$db->where('Id',$categoryId);
	$categoryData = $db->getOne('categories');

	$categoryName = $categoryData['Name'];
	$categoryDescription = $categoryData['Description'];
	$categoryParent = $categoryData['ParentCategory'];
	$categoryActive = $categoryData['Active'];

	$db->where('Category',$categoryId);
	$categoryNav = $db->getOne('category_nav');

	if(isset($categoryNav)){

		$categoryMenu = explode(',',$categoryNav['Categories']);
		$categoryMenuHighlight = explode(',',$categoryNav['Highlights']);

	}


}elseif($action == 3){

	// Delete

}

//d($categoryMenu);
//d($categoryMenuHighlight);
?>

<style>
.nice-select.form-control:hover {
    border: 0!important;
}

.box-choose span {
    margin-right: 5px;
    margin-bottom: 10px;
    padding-right: 5px;
    border-right: 1px solid #ddd;
    display: inline-flex;
    font-size: 13px;
    cursor: pointer;
}

.box-choose span.active {
    font-weight: 600;
    color: #001b5d;
}

#categoryMenuHighlight span.active {
	color: #a5cc7f;
}
</style>

<div class="modal-header">
	<div class="col-md-6">
		<h3>Category</h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save-category" class="btn-cta btn-save">Save</button>
	</div>
</div>

<div class="section">


	<input type="hidden" name="categoryId" class="" value="<?php echo $categoryId; ?>" />
	<input type="hidden" name="categoryAction" class="" value="<?php echo $action; ?>" />

	<div class="row">
		<div class="col-md-6">
			<label>Navn</label>
			<input type="text" name="categoryName" class="" value="<?php echo $categoryName; ?>" />
		</div>
		<div id="categorySection" class="col-md-6">
			<label>Hovedkategori</label>
			<select class="form-control">
				<option value="1">Body</option>
				<option value="2">Mind</option>
				<option value="3">Soul</option>
			</select>
		</div>
	</div>

	<div class="row">
		<div id="categoryParentCategory" class="col-md-6 hide">
			<label>Underkategori</label>
			<select class="form-control">
				<option value="0">None</option>
				<?php
				$db->where('ParentCategory',0);
				$categories = $db->get('categories');

				foreach($categories as $category){ ?>

					<option value="<?php echo $category['Id']; ?>" <?php if($category['Id'] == $categoryParent){ echo "selected"; } ?>><?php echo $category['Name']; ?></option>

				<?php } ?>
			</select>
		</div>
		<div id="categoryActive" class="col-md-6">
			<label>Aktiv</label>
			<select class="form-control">
				<option value="1" <?php if($categoryActive == '1'){ echo "selected"; } ?>>Yes</option>
				<option value="0" <?php if($categoryActive == '0'){ echo "selected"; } ?>>No</option>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<label>Beskrivelse</label>
			<textarea type="text" name="categoryDescription" class=""><?php echo $categoryDescription; ?></textarea>
		</div>
	</div>


	<?php
	$class = '';
	if($categoryParent != 0){
		$class = 'hide';
	}
	?>

	<div class="row <?php echo $class; ?>">
		<div id="categoryMenuHighlight" class="col-md-6 box-choose">
			<label>Menu bjælke highlight</label>
			<?php
			$categories = $db->get('categories');
			?>

			<div class="sortable">
			<?php
			foreach($categories as $category){ 

				if(in_array($category['Id'], $categoryMenuHighlight)){
					$class = 'active';
				}else{
					$class = '';
				}

			?>

				<span class="<?php echo $class; ?>" data-id="<?php echo $category['Id']; ?>"><?php echo $category['Name']; ?></span>

			<?php } ?>
			</div>
		</div>
		<div id="categoryMenu" class="col-md-6 box-choose">
			<label>Menu bjælke</label>
			<?php
			$categories = $db->get('categories');
			?>

			<div class="sortable">
			<?php
			foreach($categories as $category){ 

				if(in_array($category['Id'], $categoryMenu)){
					$class = 'active';
				}else{
					$class = '';
				}

			?>

				<span class="<?php echo $class; ?>" data-id="<?php echo $category['Id']; ?>"><?php echo $category['Name']; ?></span>

			<?php } ?>
			</div>
		</div>
	</div>

</div>



<script>

$(function(){

    $(".sortable").sortable({

        items: "span",
        start: function(e, ui) {
        },
        stop: function(e, ui) {
            itemProductPosition();
        }

    });

});

$('select').niceSelect();


$(document).off('click','#btn-modal-save');

$('html').on('click','.success a.close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});


</script>


