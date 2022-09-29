<?php
include("../../includes/config.php");

$action = $_GET['action'];

if(isset($_GET['id'])){
	$pageId = $_GET['id'];
}else{
	$pageId = 0;
}

$pageSlug 				= '';
$pageTitle 				= '';
$pageContent 			= '';
$pageMetaTitle 			= '';
$pageMetaDescription 	= '';

if($action == 1){

	// Create

}elseif($action == 2){

	// Update
	$db->where('Id',$pageId);
	$pageData = $db->getOne('cms');

	$pageSlug 				= $pageData['slug'];
	$pageTitle 				= $pageData['CMSTitle'];
	$pageContent 			= $pageData['CMSContent'];
	$pageMetaTitle 			= $pageData['MetaTitle'];
	$pageMetaDescription 	= $pageData['MetaDescription'];

}elseif($action == 3){

	// Delete

}
?>

<div class="modal-header">
	<div class="col-md-6">
		<h3>Page</h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save" class="btn-cta btn-save">Save</button>
	</div>
</div>

<div class="section">

	<div class="row">
		<div class="col-md-6">
			<label>Slug</label>
			<input type="text" name="pageSlug" class="" value="<?php echo $pageSlug; ?>" <?php if($action == 2){ echo "disabled"; } ?> />
		</div>
		<div class="col-md-6">
			<label>Title</label>
			<input type="text" name="pageTitle" class="" value="<?php echo $pageTitle; ?>" />
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<label>Content</label>
			<textarea type="text" name="pageContent" class=""><?php echo $pageContent; ?></textarea>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			<label>Meta title</label>
			<input type="text" name="pageMetaTitle" class="" value="<?php echo $pageMetaTitle; ?>" />
		</div>
		<div class="col-md-6">
			<label>Meta description</label>
			<input type="text" name="pageMetaDescription" class="" value="<?php echo $pageMetaDescription; ?>" />
		</div>
	</div>

</div>



<script>

$('#btn-modal-save').off('click');
$('#btn-modal-save').unbind('click');

$('html').on('click','.success a.close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});

console.log(<?php echo $pageId; ?>);

$('html').on('click','#btn-modal-save',function(){

	var slug 				= $('input[name=pageSlug]').val();
	var title 				= $('input[name=pageTitle]').val();
	var content 			= $('textarea[name=pageContent]').val();
	var metatitle 			= $('input[name=pageMetaTitle]').val();
	var metadescription 	= $('input[name=pageMetaDescription]').val();
	var id 					= <?php echo $pageId; ?>;

	console.log(content);

    $.ajax({
        url: "/actions/ajax/crudPage.php",
        method: "POST",        
        data: {
        	action: '<?php echo $action; ?>',
        	pageId: id,
        	slug: slug,
        	title: title,
        	content: content,
        	metatitle: metatitle,
        	metadescription: metadescription
        },
        success: function(data){
        	console.log(data);
        	$('#modal-page .md-content-inner').html('<div class="success"><div class="animation-ctn"><div class="icon icon--order-success svg"><svg xmlns="http://www.w3.org/2000/svg" width="100px" height="100px"><g fill="none" stroke="#2cb48f" stroke-width="2"><circle cx="50" cy="50" r="48" style="stroke-dasharray:480px, 480px; stroke-dashoffset: 960px;"></circle><circle id="colored" fill="#2cb48f" cx="50" cy="50" r="48" style="stroke-dasharray:480px, 480px; stroke-dashoffset: 960px;"></circle><polyline class="st0" stroke="#fff" stroke-width="7" points="30,50 46,65 75,36" style="stroke-dasharray:100px, 100px; stroke-dashoffset: 200px;"/></g></svg></div></div><h1>Job done!</h1><a class="close" href="/pages">Go back to pages</a></div>');
        },
        error: function(error) {
            console.log('error');
        }
    });

});

</script>


