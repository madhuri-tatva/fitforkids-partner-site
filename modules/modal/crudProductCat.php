<?php
include ("../../includes/config.php");
// echo "<pre>";print_r($_POST);
if (isset($_GET['action']))
{
    $action = $_GET['action'];
}
else
{
    $action = 0;
}
if (isset($_GET['productid']))
{
    $productid = $_GET['productid'];
}
else
{
    $productid = 0;
}

$medias = $db->get('media');

// Sort media
$mediaSorted = array();
foreach ($medias as $media)
{

    if ($media['Type'] == 'jpg' or $media['Type'] == 'jpeg' or $media['Type'] == 'png')
    {
        $mediaSorted['Images'][] = $media;
    }
    else
    {
        $mediaSorted['Medias'][] = $media;
    }

}

$productTitle = '';
$productSection = (isset($_GET['section'])) ? $_GET['section'] : 0;
$productCategory = (isset($_GET['categoryid'])) ? $_GET['categoryid'] : 0;
$productSubcategory = 0;
$productDescription = '';
$productMedias = array();
$productThumbnail = 0;

if ($action == 1)
{

    // Create
    
}
elseif ($action == 2)
{

    // Update
    $db->where('Id', $productid);
    $product = $db->getOne('products');

    $productTitle = $product['Name'];
    $productMedias = $product['Medias'];

    $productMedias = explode(',', $productMedias);

    $productThumbnail = $product['Thumbnail'];

}
elseif ($action == 3)
{
    // Delete
}
$allCategories = $db->get('categories');
$allCategoriesLevel1Sorted = array();
$allCategoriesLevel2Sorted = array();

foreach ($allCategories as $category)
{

    if ($category['Level'] == 1)
    {
        $allCategoriesLevel1Sorted[$category['Id']] = $category;
    }
    else
    {
        $allCategoriesLevel2Sorted[$category['Id']] = $category;
    }

}
?>
<!-- <link href="/assets/lib/css/dropzone.min.css" rel="stylesheet"> -->
<link id="dropzone-css" href="/assets/lib/css/dropzone.css" rel="stylesheet">
<link id="font-awesome-css" href="/assets/lib/css/font-awesome.min.css" rel="stylesheet">
<link id="onyx-css" href="/assets/css/draganddrop.css" rel="stylesheet">

<div class="modal-header">
	<div class="col-md-6">
		<h3>Placer filer</h3>
	</div>
	<div class="col-md-6">
		<button id="btn-modal-save-resource-v2" class="btn-cta btn-save">Gem</button>
	</div>
</div>

<div class="section">

	<input type="hidden" name="productAction" class="" value="<?php echo $action; ?>" />
	<input type="hidden" name="productId" class="" value="<?php echo $productid; ?>" />
	<input type="hidden" name="categoryId" class="" value="<?php echo $productCategory; ?>" />
	<input type="hidden" name="section" class="" value="<?php echo $productSection; ?>" />

	<div class="row">
		<div class="col-md-12">
			<label>Titel</label>
			<input type="text" name="productTitle" class="" value="<?php echo $productTitle; ?>" />
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">

			<!-- Files section -->
			<h4 class="section-sub-title"><span>Upload</span> Your Files</h4>

			<form action="file-upload-category.php" class="dropzone files-container">
				<div class="fallback">
					<input name="file" type="file" multiple />
				</div>
				<input type="hidden" name="action" value="5">
			</form>

			<!-- Notes -->
			<span>JPG, PNG, MP4, AVI, MKV, PDF, DOC (Word), XLS (Excel), PPT, ODT and RTF files types are supported.</span>

			<!-- Uploaded files section -->
			<h4 class="section-sub-title"><span>Uploaded</span> Files (<span class="uploaded-files-count">0</span>)</h4>
			<span class="no-files-uploaded">No files uploaded yet.</span>

			<!-- Preview collection of uploaded documents -->
			<div class="preview-container dz-preview uploaded-files">
				<div id="previews">
					<div id="onyx-dropzone-template">
						<div class="onyx-dropzone-info">
							<div class="thumb-container">
								<img data-dz-thumbnail />
							</div>
							<div class="details">
								<div>
									<span data-dz-name></span> <span data-dz-size></span>
								</div>
								<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
								<div class="dz-error-message"><span data-dz-errormessage></span></div>
								<div class="actions">
									<a href="#!" data-dz-remove><i class="fa fa-times"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="media_upload" class="media_upload" value="">
			<!-- Warnings -->
			<div id="warnings">
				<span>Warnings will go here!</span>
			</div>

		</div>
	</div><!-- /End row -->
		
	<div class="row">
		<div class="col-md-6">
			<label>Filer</label>
			<div class="section-file show-search">

				<?php if (!empty($mediaSorted['Medias'])) { ?>
					<table id="modal-medias" class="list">
						<thead>
							<tr>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				<?php } ?>

			</div>
		</div>
		<div class="col-md-6">
			<label>Thumbnail</label>
			<div class="section-file show-search">

				<?php if (!empty($mediaSorted['Images'])) { ?>
					<table id="modal-images" class="list">
						<thead>
							<tr>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				<?php } ?>
			</div>
		</div>
	</div>

	<?php if ($productid != 0 && $_SESSION['Type'] == 1) { ?>
		<div class="row">
			<div class="col-md-12">
				<a class="btn-delete-item btn-delete" data-type="3" data-id="<?php echo $productid; ?>">Slet dette produkt</a>
			</div>
		</div>
	<?php } ?> 

</div>



<script src="assets/js/jquery.nice-select.min.js"></script>>
<script src="/assets/lib/js/dropzone.min.js"></script>
<script src="/assets/lib/js/scripts.js"></script>

<script>
		var productId = "<?php echo $productThumbnail; ?>";
		var productMedias = <?php echo json_encode($productMedias); ?>;
	    var mytable = $('#modal-images').DataTable( {
	    	"bSort": false,
	    	// "autoWidth": false,
	    	"pageLength": 5,
	        'ajax': {
	          'url':'/actions/ajax/categoryImage.php'
	      	},
	      	"columnDefs": [ {
				"targets"  : 'no-sort',
				"orderable": false,
			}],
			createdRow: function (row, data, dataIndex) {
	        	$(row).attr('data-media-id', data.id);
	        	if (data.id == productId) {
           			$(row).addClass("active");
           		}

			},
	        "columns": [
	            {
		           'data': "img",
		           'createdCell':  function (td, cellData, rowData, row, col) {
			           $(td).prepend('<img class="img-small" src="'+rowData.image+'" />'); 
			        }
		        },
	            { 
	            	"data": "name",
	            	'createdCell':  function (td, cellData, rowData, row, col) {
			           $(td).addClass("image_name");
			        }

	        	},

		        {
		           'targets': 3,
			        'createdCell':  function (td, cellData, rowData, row, col) {
			           $(td).addClass("right");
			           $(td).prepend('<a  class="md-trigger" data-modal="modal-product"  onclick=modalChooseImage("'+rowData.id+'")>Vælg</a>'); 
			        },
		           'data': "img"
		        },
	        ]
	    } );
	   
	function modalChooseImage(id){

		$('#modal-images tr').removeClass('active');
		$('tr[data-media-id='+id+']').addClass('active');

	}
	function modalChooseMedia(id){
		$('tr[data-media-id='+id+']').toggleClass('active');
 	}
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

	function inArray(needle, haystack) {
	    var length = haystack.length;
	    for(var i = 0; i < length; i++) {
	        if(haystack[i] == needle) return true;
	    }
	    return false;
	}

	var table = $('#modal-medias').DataTable( {
			"bSort": false,
			"pageLength": 8,
	        'ajax': {
	          'url':'/actions/ajax/categoryMedia.php'
	      	},
	      	"columnDefs": [ {
				"targets"  : 'no-sort',
				"orderable": false,
			}],
			createdRow: function (row, data, dataIndex) {
				$(row).attr('data-media-id', data.media_id);
	        	if(inArray(data.media_id, productMedias)) {
           			$(row).addClass("active");
           		}

			},
	        "columns": [
	            { 
	            	"data": "doc_name",
	            	'createdCell':  function (td, cellData, rowData, row, col) {
			           $(td).addClass("image_name");
			        }
	        	},

		        {
		           'targets': 3,
			        'createdCell':  function (td, cellData, rowData, row, col) {
			           $(td).addClass("right");
			           $(td).prepend('<a  class="md-trigger" data-modal="modal-product"  onclick=modalChooseMedia("'+rowData.media_id+'")>Vælg</a>'); 
			        },
		           'data': "img"
		        },
	        ]
	    } );
	

</script>
