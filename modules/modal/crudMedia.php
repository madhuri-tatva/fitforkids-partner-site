<?php
include("../../includes/config.php");


if($_GET['action'] == 1){

	?>
<style type="text/css">
	.dropzone_video .dz-default{
		background-color:rgba(0, 0, 0, .2);padding: 10px;
	}
	.dz-button{
		background: transparent !important;
    	border: none !important;
    	font-size: medium !important;
	}
</style>
	<div class="modal-header">
		<div class="col-md-6">
			<h3>Tilføj fil til bibliotek</h3>
		</div>
		<div class="col-md-6">
			<button id="btn-modal-close" class="btn-cta btn-close">Gem</button>
		</div>
	</div>

	<div class="section">

		<div class="col-md-12">
			
			<form action="/file-upload.php" class="dropzone files-container" id="dropzone">
				<div class="fallback">
					<input name="file" type="file" />
					<input name='fileThumbnail' type='hidden' value=''>
				</div>
			</form>
			<span class="small">JPG, PNG, MP4, MP3, DOCX, PPT, PDF er understøttet. Max. 1000 mb</span>


			<h4 class="section-sub-title"><span>Uploaded</span> Files (<span class="uploaded-files-count">0</span>)</h4>
			<span class="no-files-uploaded">No files uploaded yet.</span>

			<div class="preview-container dz-preview uploaded-files">
				<div id="previews">
					<div id="onyx-dropzone-template">
						<div class="onyx-dropzone-info">
							<div class="thumb-container">
								<img data-dz-thumbnail />
							</div>
							<div class="details">
								<div class="dz-message" data-dz-message><span>Upload</span></div>
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

		</div>

	</div>

<?php }elseif($_GET['action'] == 2){ 

	$imageId = $_GET['mediaid'];
	$db->where('Id',$imageId);
	$image = $db->getOne('media');
	?>

	<div class="modal-header">
		<div class="col-md-6">
			<h3>Delete from library</h3>
		</div>
		<div class="col-md-6">
			<button id="btn-modal-delete" class="btn-cta btn-delete">Delete</button>
		</div>
	</div>

	<div class="section">

		<div class="col-md-12">

			<form id="dropzone" action="/file-upload.php" method="post" class="dropzone files-container" style="opacity: 0;">
				<div class="fallback">
					<input name="target_file" type="hidden" value="<?php echo $_SERVER['DOCUMENT_ROOT']; ?><?php echo $image['URL']; ?>" />
					<input name="delete_file" type="hidden" value="1" />
					<input name="delete_file_id" type="hidden" value="<?php echo $imageId; ?>" />
				</div>
			</form>

		</div>

	</div>

<?php } 
elseif($_GET['action'] == 3){

	?>
<style type="text/css">
	.dropzone_video .dz-default{
		background-color:rgba(0, 0, 0, .2);padding: 10px;
	}
	.dz-button{
		background: transparent !important;
    	border: none !important;
    	font-size: medium !important;
	}
</style>
	<div class="modal-header">
		<div class="col-md-6">
			<h3>Upload video</h3>
		</div>
		<div class="col-md-6">
			<button id="btn-modal-close" class="btn-cta btn-close">Gem</button>
		</div>
	</div>

	<div class="section">

		<div class="col-md-12">
			<!-- style="background-color:rgba(0, 0, 0, .2);padding: 10px;" -->
			<form action="/file-upload.php" class="dropzone dropzone_video" id="dropzone" >
				<div class="dropdown" style="margin-bottom: 23px;">
					<select name="page" class="form-control" id="page">
						<option disabled selected>Select</option>
						<option value="landing_page">Landing Page</option>
						<option value="dashboard">Dashboard</option>
						<option value="profile">Profile</option>
						<option value="battle">Battle</option>
						<option value="body">Body</option>
						<option value="mind">Mind</option>
						<option value="soul">Soul</option>
					</select>
				</div>
				<div class="fallback">
					<input name="file" type="file" />
					<input name='fileThumbnail' type='hidden' value=''>
				</div>
			</form>
			<span class="small">Video files only. Max. 1000 mb</span>

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
			<div id="warnings">
				<span></span>
			</div>

		</div>

	</div>

<?php } ?>
<!-- /uploads/hubspot-logo.png -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.6/min/dropzone.min.js"></script>
<script src="/assets/js/dropzone-extend.js"></script>

<script>

	// $("#dropzone").dropzone();
// console.log(response);
	$('#btn-modal-close').click(function(){

		$('#modal-media').removeClass('md-show');

	});

</script>


