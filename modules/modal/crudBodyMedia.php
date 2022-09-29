<?php
include("../../includes/config.php");
// action 1 = add media, 2 = update media, 3= delete media, 4= media position update

if($_GET['action'] == 1){
		$type = ($_GET['type']) ? $_GET['type'] : '';
		$subtype = ($_GET['subtype']) ? $_GET['subtype'] : '';
		$mediaid = ($_GET['mediaid']) ? $_GET['mediaid'] : 0;
		?>
		<style>
		.md-modal{
			width:46%;
			max-width:none;
		}
		.uploaded-files span, .uploaded-files a {
				color: #000 !important;
		}
		</style>

		<script type="text/javascript">
		<?php if($subtype==4){?>
			var page_name_type = 12;
		<?php }elseif($mediaid==='SubMedia'){?>
			var page_name_type = 12;
		<?php }else{ ?>
			var page_name_type = 2;
		<?php } ?>
		</script>
		<!-- Insert form -->
		<div class="modal-header body-learning-video-popup-heading">
		<h3>
				<?php if($type=='2'){
					echo 'FitforKids Training videos ';
				}elseif($type ==1 && $subtype == 2){
					echo 'FitforKids Dietary Philosofy';
				}elseif($type ==1 && $subtype == 4){
					echo 'FitforKids Partner’s Videoer';
				}?> 
				
				<?php 
				/*if($subtype=='1'){
					echo ' Officielle Træningsvideoer';
				}elseif($subtype=='2'){
					echo ' Officielle Læringsvideoer';
				}elseif($subtype=='3'){
					echo ' TRÆNINGSPROGRAM';
				}*/?>
			</h3>
			<button id="btn-admin-upload-file" class="btn-cta btn-save">Gem</button>

		</div>

	<div class="section body-learning-video-popup">
		<input type="hidden" name="mediaType" class="" value="<?= $type; ?>" />
		<input type="hidden" name="mediaID" class="" value="<?= $mediaid; ?>" />
		<input type="hidden" name="mediaSubType" class="" value="<?= $subtype; ?>" />
		<input type="hidden" name="UserID" class="" value="<?= $_SESSION['UserId']; ?>" />
		<input type="hidden" name="Action" class="" value="4" />

		<div class="row">
			<div class="col-md-12 form-group">
				<label>Titel <?php if($subtype=='1'){echo 'til officielle træningsvideoer ';}elseif($subtype=='2'){echo 'til officielle læringsvideoer';}?></label>
				<input type="text" name="mediaTitle" class="form-control" value="" />
			</div>
			<?php if($mediaid==='SubMedia'){?>
				<div class="col-md-12 form-group">
					<label>Sub Title</label>
					<input type="text" name="mediaSubTitle" class="form-control" placeholder="8 øvelser a 5 min." value="" />
				</div>
				<div class="col-md-12 form-group" >
					<label>Total Video Time</label>
					<input type="text" name="mediaTotalTime" class="form-control" placeholder="39.40" value="00.00" />
				</div>
			<?php }else{ ?>
				<input type="hidden" name="mediaSubTitle" class="form-control" placeholder="eg: 8 øvelser a 5 min." value="" />
				<input type="hidden" name="mediaTotalTime" class="form-control" placeholder="39.40" value="" />
			<?php } ?>
			<div class="col-md-12 form-group">
				<label>Video beskrivelse</label>
				<textarea name="mediaDetail" class="form-control" value="" ></textarea>
			</div>
		</div>
		<?php 
		
		?>
		<!-- Start preview media -->
		<?php  if($type == 1 || $type==2) { ?>
		<div class="row">
			<div class="col-md-12 form-group">
					<label>Upload preview image</label>
						<form action="/file-upload-body.php" class="dropzone dropzone99 files-container" style="margin:0 auto;">
							<div class="fallback">
								<input name="thumbnail" type="file" accept="image/*"   />
							</div>
							<input type="hidden" name="action" value="5">
							<input type="hidden" name="mtype" value="Thumbnail">
							<input type="hidden" name="userID" value="<?= $_SESSION['UserId'] ?>">
						</form>
						<span>JPG, JPEG and PNG file types are supported.</span>
						<h4 class="section-sub-title"><span>Uploaded</span> Files (<span class="uploaded-files-count">0</span>)</h4>
						<span class="no-files-uploaded">No files uploaded yet.</span>
						<div class="preview-container dz-preview uploaded-files">
							<div class="previews-thumbnail">
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
											<!-- <form action="/file-upload-body.php" class="dropzone files-container"> -->
												<a href="#!" data-dz-remove><i class="fa fa-trash"></i></a>
												<input type="hidden" name="delete_file" value="1">
											<!-- </form> -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="media_upload" class="media_upload" value="">
			</div>
		</div>
		<?php } ?>
		<!-- end preview media -->
		<div class="row">
			<div class="col-md-12 form-group">
					<label>Upload media</label>
						<form action="/file-upload-body.php" class="dropzone <?php  if($subtype==4){echo  'dropzone12';}elseif($mediaid==='SubMedia'){echo 'dropzone12' ;}else{echo 'dropzone2';} ?>  files-container" style="margin:0 auto;">
							<div class="fallback">
								<input name="file" type="file" accept=".mp4,.avi,.mkv" required="required"/>
							</div>
							<input type="hidden" name="action" value="5">
							<input type="hidden" name="mtype" value="Media">
							<input type="hidden" name="userID" value="<?= $_SESSION['UserId'] ?>">
						</form>
						<!-- <span>MP4, AVI, MKV, <?php if($subtype==4){echo 'image'; }elseif($mediaid === 'SubMedia'){echo 'image';}else{} ?> files types are supported.</span> -->
						<span>JPG, PNG, MP4, AVI, MKV, MP3, DOCX, PPT, PDF files types are supported.</span>
						<h4 class="section-sub-title"><span>Uploaded</span> Files (<span class="uploaded-files-count">0</span>)</h4>
						<span class="no-files-uploaded">No files uploaded yet.</span>
						<div class="preview-container dz-preview uploaded-files">
							<div class="previews <?php  if($subtype==4){echo  'previews12';}elseif($mediaid==='SubMedia'){echo 'previews12' ;}else{echo 'previews2';} ?> ">
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
											<!-- <form action="/file-upload-body.php" class="dropzone files-container"> -->
												<a href="#!" data-dz-remove><i class="fa fa-trash"></i></a>
												<input type="hidden" name="delete_file" value="1">
											<!-- </form> -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="media_upload" class="media_upload" value="">
			</div>
		</div>
		<?php  if($subtype==3) {?>
		<div class="row">
			<div class="col-md-12 form-group">
					<label>Upload PDF</label>
						<form action="/file-upload-body.php" class="dropzone dropzone3 files-container" style="margin:0 auto;">
							<div class="fallback">
								<input name="thumbnail" type="file" accept="application/pdf"   />
							</div>
							<input type="hidden" name="action" value="5">
							<input type="hidden" name="mtype" value="Thumbnail">
							<input type="hidden" name="userID" value="<?= $_SESSION['UserId'] ?>">
						</form>
						<span>Only PDF file type is supported.</span>
						<h4 class="section-sub-title"><span>Uploaded</span> Files (<span class="uploaded-files-count">0</span>)</h4>
						<span class="no-files-uploaded">No files uploaded yet.</span>
						<div class="preview-container dz-preview uploaded-files">
							<div class="previews-thumbnail">
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
											<!-- <form action="/file-upload-body.php" class="dropzone files-container"> -->
												<a href="#!" data-dz-remove><i class="fa fa-trash"></i></a>
												<input type="hidden" name="delete_file" value="1">
											<!-- </form> -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="media_upload" class="media_upload" value="">
			</div>
		</div>
		<?php } ?>
	</div>

	<script src="/assets/lib/js/dropzone.min.js"></script>
	<script src="/assets/lib/js/scripts_body_media.js"></script>

<?php }elseif($_GET['action'] == 2){
	$id = $_GET['mediaid'];
	$db->where('ID',$id);
	$media = $db->getOne('body_media');
	$type=$media['Type'];
	$subtype = $media['SubType'];
	$db->where('body_media_id',$media['ID']);
	$subvideos = $db->get('body_media');
	$submediacount =  0;
	if(count($subvideos)>0){
		$submediacount = $subvideos;
	}
	?>
	<style>
		.md-modal{
			width:46%;
			max-width:none;
		}
		.uploaded-files span, .uploaded-files a {
				color: #000 !important;
		}
		</style>
		<script type="text/javascript">
		<?php if($subtype==4){?>
			var page_name_type = 12;
		<?php }elseif($submediacount>0){?>
			var page_name_type = 12;
		<?php }else{ ?>
			var page_name_type = 2;
		<?php } ?>
		</script>
	<!-- update media form -->
	<input type="hidden" name="Action" value="2">
	<input type="hidden" name="mediaType" class="" value="<?= $media['Type']; ?>" />
	<input type="hidden" name="mediaSubType" class="" value="<?= $media['SubType']; ?>" />
	<input type="hidden" name="mediaid" value="<?= $media['ID']?>">
	<input type="hidden" name="UserID" value="<?= $media['UserID']?>">

	<div class="modal-header  body-learning-video-popup-heading">
		<h3><?php if($type=='1'){echo 'BODY: ERNÆRING ';}elseif($type=='2'){echo ' BODY: TRÆNING ';}?> <?php if($subtype=='1'){echo ' Officielle Træningsvideoer';}elseif($subtype=='2'){echo ' Officielle Læringsvideoer';}elseif($subtype=='3'){echo ' TRÆNINGSPROGRAM';}?></h3>
		<button id="btn-admin-update-media" type="submit" class="btn-cta btn-close">Gem</button>
	</div>

	<div class="section body-learning-video-popup">
		<div class="row">
			<div class="col-md-12 form-group">
				<div class="dropdown" style="margin-bottom: 23px;">
					<label>Title</label>
					<input type="text" name="title" placeholder="Title" value="<?= $media['Title']?>">
				</div>
			</div>
			<?php if(($type==2 && $subtype==1) || $submediacount>0){?>
					<div class="col-md-12 form-group" <?= ($media['body_media_id']>0) ? 'style="display:none;"' : ''?>>
						<label>Sub Title</label>
						<input type="text" name="subtitle" class="form-control" placeholder="8 øvelser a 5 min." value="<?= $media['SubTitle']?>" />
					</div>
					<div class="col-md-12 form-group" <?= ($media['body_media_id']>0) ? 'style="display:none;"' : ''?>>
						<label>Total Video Timing</label>
						<input type="text" name="totaltime" class="form-control" placeholder="39.40" value="<?= $media['TotalTime']?>" />
					</div>
			<?php }else{ ?>
					<input type="hidden" name="subtitle" class="form-control" placeholder="8 øvelser a 5 min." value="<?= $media['SubTitle']?>" />
					<input type="hidden" name="totaltime" class="form-control" placeholder="8 øvelser a 5 min." value="<?= $media['TotalTime']?>" />
			<?php } ?>
			<div class="col-md-12 form-group">
				<label>Video beskrivelse</label>
				<textarea name="detail" class="form-control"><?= $media['Detail']?></textarea>
			</div>
			<!-- Start preview media -->
		<?php  if($type == 1 || $type==2) { ?>
			<div class="col-md-12 form-group">
						<label>Upload preview media</label>
							<form action="/file-upload-body.php" class="dropzone dropzone99 files-container" style="margin:0 auto;">
								<div class="fallback">
								<input name="thumbnail" type="file" accept="image/*"   />
								</div>
								<input type="hidden" name="action" value="5">
								<input type="hidden" name="mtype" value="Thumbnail">
								<input type="hidden" name="userID" value="<?= $_SESSION['UserId'] ?>">
							</form>
							<span>JPG, JPEG and PNG file types are supported.</span>
							<h4 class="section-sub-title"><span>Uploaded</span> Files (<span class="uploaded-files-count">0</span>)</h4>
							<span class="no-files-uploaded">No files uploaded yet.</span>
							<div class="preview-container dz-preview uploaded-files">
								<div class="previews-thumbnail">
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
													<a href="#!" data-dz-remove><i class="fa fa-trash"></i></a>
													<input type="hidden" name="delete_file" value="1">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="media_upload" class="media_upload" value="">
						</div>
						<?php if($media['Thumbnail']){?>
					<div class="col-md-12 form-group">
							<a href="<?= $media['Thumbnail']; ?>" target="_blank">
							<img src="<?php echo $media['Thumbnail']; ?>" height="80px" width="80px"  alt="thumbnail <?= $media['Thumbnail']; ?>">
							</a>
					</div>
			<?php } ?>
		<?php } ?>
		<!-- end preview media -->
				<div class="col-md-12 form-group">
						<label>Upload media</label>
							<form action="/file-upload-body.php" class="dropzone <?php  if($subtype==4){echo  'dropzone12';}elseif($submediacount>0){echo 'dropzone12' ;}else{echo 'dropzone2';} ?> files-container" style="margin:0 auto;">
								<div class="fallback">
									<input name="file" type="file" accept=".mp4,.avi,.mkv" required="required"/>
								</div>
								<input type="hidden" name="action" value="5">
								<input type="hidden" name="mtype" value="Media">
								<input type="hidden" name="userID" value="<?= $_SESSION['UserId'] ?>">
							</form>
							<!-- <span>MP4, AVI, MKV, <?php if($subtype==4){echo 'image'; }elseif($submediacount >0){echo 'image';}else{} ?> files types are supported.</span> -->
							<span>JPG, PNG, MP4, AVI, MKV, MP3, DOCX, PPT, PDF files types are supported.</span>
							<h4 class="section-sub-title"><span>Uploaded</span> Files (<span class="uploaded-files-count">0</span>)</h4>
							<span class="no-files-uploaded">No files uploaded yet.</span>
							<div class="preview-container dz-preview uploaded-files">
								<div class="previews <?php  if($subtype==4){echo  'previews12';}elseif($submediacount>0){echo 'previews12' ;}else{echo 'previews2';} ?>">
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
													<a href="#!" data-dz-remove><i class="fa fa-trash"></i></a>
													<input type="hidden" name="delete_file" value="1">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="media_upload" class="media_upload" value="">
				</div>
				<?php if($media['Media']){?>
				<div class="col-md-6">
					<?php $media_name = explode('/',$media['Media']);?>
					<?php $type= explode('/',$media['MediaType']);
	        		if(isset($type[0]) && $type[0]=='image'){?>
						<a href="<?= $media['Media']; ?>" target="_blank">
							<img src="<?= $media['Media']; ?>" height="80px" width="80px" alt="media view">
						</a>
					<?php }elseif((isset($type[0]) && $type[0]=='video')){?>
						<a href="<?= $media['Media']; ?>"   target="_blank">
							<video poster="" style="height:70px;">
									<source type="video/mp4" src="<?= $media['Media'] ?>" >
							</video>
						</a>
					<?php }else{?>
						<a href="<?= $media['Media']; ?>" class="btn" style="padding:0 10px;" target="_blank">
							<?= $media['MediaType']?> <?= trim(end($media_name)); ?>
						</a>
					<?php } ?>
				</div>
				<?php } if($subtype==3) {?>
				<div class="col-md-12 form-group">
						<label>Upload PDF</label>
							<form action="/file-upload-body.php" class="dropzone dropzone3 files-container" style="margin:0 auto;">
								<div class="fallback">
									<input name="thumbnail" type="file" accept="application/pdf"   />
								</div>
								<input type="hidden" name="action" value="5">
								<input type="hidden" name="mtype" value="Thumbnail">
								<input type="hidden" name="userID" value="<?= $_SESSION['UserId'] ?>">
							</form>
							<span>Only PDF file type is supported.</span>
							<h4 class="section-sub-title"><span>Uploaded</span> Files (<span class="uploaded-files-count">0</span>)</h4>
							<span class="no-files-uploaded">No files uploaded yet.</span>
							<div class="preview-container dz-preview uploaded-files">
								<div class="previews-thumbnail">
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
													<a href="#!" data-dz-remove><i class="fa fa-trash"></i></a>
													<input type="hidden" name="delete_file" value="1">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="media_upload" class="media_upload" value="">
				</div>
				<?php if($media['Thumbnail']){?>
					<div class="col-md-12 form-group">
							<a href="<?= $media['Thumbnail']; ?>" target="_blank">
							<img src="/assets/images/training-pdf-icon.jpg<?php $media['Thumbnail']; ?>" height="80px" width="80px"  alt="thumbnail <?= $media['Thumbnail']; ?>">
							</a>
					</div>
			<?php } }?>
			<div class="col-md-12 form-group">
				<div>
					<label style="margin-top:10px;">Display Status</label>
					<div class="row pg-input-radio pg-radio" style="position:relative;">
						<div class="col-md-6">
							<input type="radio" class="form-control" name="mediaStatus" id="show_status" value="1" <?= ($media['Status']==1) ? 'checked' : ''?>>
							<span class="checkmark"></span>
							<label for="show_status">Show</label>
						</div>
						<div class="col-md-6">
							<input type="radio" class="form-control" name="mediaStatus" id="hide_status" value="0" <?= ($media['Status']==0) ? 'checked' : ''?>>
							<span class="checkmark"></span>
							<label for="hide_status">Hide</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="/assets/lib/js/dropzone.min.js"></script>
	<script src="/assets/lib/js/scripts_body_media.js"></script>
<?php }elseif($_GET['action'] == 3){
	// Delete Body Media
	$id = $_GET['mediaid'];
	$db->where('ID',$id);
	$image = $db->getOne('body_media');?>

	<div class="modal-header">
		<div class="col-md-6">
			<h3>Delete from library</h3>
		</div>
		<div class="col-md-6">
			<button id="btn-modal-delete" class="btn-cta btn-delete">Delete</button>
		</div>
	</div>
	<div class="media-content section body-learning-video-popup" style="overflow-y:hidden;">
		<?php $type= explode('/',$image['MediaType']);
         if(isset($type[0]) && $type[0]=='image'){?>
			<div class="col-md-6">
			<a href="<?= $image['Media']; ?>" target="_blank"><img src="<?= $image['Media']; ?>" height="100px" width="100px"  alt="media <?= $image['Media']; ?>"></a>
			</div>
		<?php }elseif(isset($type[0]) && $type[0]=='video'){ ?>
			<div class="col-md-6">
				<a href="<?= $image['Media']; ?>" target="_blank"><video poster="" style="height:70px;">
					<source type="video/mp4" src="<?= $image['Media'] ?>" >
				</video></a>
			</div>
		<?php }elseif(isset($type[0]) && $type[0]=='pdf'){ ?>
			<div class="col-md-6">
				<a href="<?= $image['Media']; ?>" target="_blank">
				   <img src="/assets/images/training-pdf-icon.jpg" alt="pdf-icon">
				</a>
			</div>
		<?php } ?>
		<div class="col-md-12">
			<a href="<?= $image['Media']; ?>" target="_blank"><?= $image['Media']; ?></a>
		</div>
		<?php if($image['Thumbnail']){?>
			<div class="col-md-12">
				<img src="/assets/images/training-pdf-icon.jpg" alt="pdf-icon"> </br>
				<a href="<?= $image['Thumbnail']; ?>" target="_blank"><?= $image['Thumbnail']; ?></a>
			</div>
		<?php } ?>
	<!-- </div>
	<div class="section"> -->
		<div class="col-md-12 form-group">
				<form id="dropzone" action="/file-upload-body.php" method="post" class="dropzone files-container" style="opacity: 0;">
				<div class="fallback">
					<input name="target_file" type="hidden" value="<?php $_SERVER['DOCUMENT_ROOT']; ?><?php echo $image['Media']; ?>" />
					<input name="target_thumbnail" type="hidden" value="<?php  $_SERVER['DOCUMENT_ROOT']; ?><?php echo $image['Thumbnail']; ?>" />
					<input name="action" type="hidden" value="0" />
					<input name="delete_file" type="hidden" value="2" />
					<input name="delete_file_id" type="hidden" value="<?php echo $id; ?>" />
				</div>
			</form>

		</div>
	</div>
<?php }elseif($_GET['action'] == 4){
	// Sort Body Media
	$type = ($_GET['type']) ? $_GET['type'] : '';
	$subtype = ($_GET['subtype']) ? $_GET['subtype'] : '';
	$id = ($_GET['mediaid']) ? $_GET['mediaid'] : 0 ;
	$db->where('Type',$type);
	$db->where('SubType',$subtype);
	$db->where('body_media_id',$id);
    $db->orderBy('Position','asc');
	$media_data = $db->get('body_media');?>
	<div class="modal-header body-learning-video-popup-heading">
		<div class="col-md-6">
		<h3>
				<?php if($type=='2'){
					echo 'FitforKids Training videos ';
				}elseif($type ==1 && $subtype == 2){
					echo 'FitforKids Dietary Philosofy';
				}elseif($type ==1 && $subtype == 4){
					echo 'FitforKids Partner’s Videoer';
				}?> 
				
				<?php 
				/*if($subtype=='1'){
					echo ' Officielle Træningsvideoer';
				}elseif($subtype=='2'){
					echo ' Officielle Læringsvideoer';
				}elseif($subtype=='3'){
					echo ' TRÆNINGSPROGRAM';
				}*/?>
			</h3>
		</div>
		<div class="col-md-6">
			<button id="btn-modal-close" class="btn-cta">Close</button>
		</div>
	</div>
	<input type="hidden" name="sortType" class="" value="<?= $type; ?>" />
	<input type="hidden" name="sortSubType" class="" value="<?= $subtype; ?>" />
	<input type="hidden" name="sortMediaID" class="" value="<?= $id; ?>" />

	<div class="md-content section body-learning-video-popup">
		 	<table  class="sortlist table table-stripped">
				<thead>
					<tr id="0">
						<th width="10%">S. No.</th>
						<th width="60%">Title</th>
						<th width="20%">Thumbnail</th>
						<th width="10%"></th>
					</tr>
				</thead>
				<tbody>
					<?php $i=1; foreach($media_data as $v){
						   $type= explode('/',$v['MediaType']);
						   $media_name= ($v['Media']) ? explode('/',$v['Media']): [];
						   $exts_name ='';
						   if(isset($media_name[3])){
							$exts= explode('.',$media_name[3]);
							$exts_name = trim(end($exts));
						   }
						?>
						<tr class="<?= ($i%2==0) ? 'even' : 'odd'?> ui-sortable-handle" id="<?= $v['ID']; ?>" >
							<td width="10%"><?= $i++;?></td>
							<td width="60%"><a href="javascript:void(0);"><?= $v['Title']?></a></td>
							<td width="20%"><a href="javascript:void(0);" title="<?= $exts_name?> <?= trim(end($media_name)) ?>">
						   	<?php
								if(isset($type[0]) && $type[0]=='image'){
									if(!empty($v['Thumbnail'])){ ?>
										<img src="<?= $v['Thumbnail']?>" width="100px" alt="">
									<?php }elseif(!empty($v['Media'])){ ?>
										<img src="<?= $v['Media']?>" width="100px" alt="">
									<?php }
								}elseif(isset($exts_name) && $exts_name=='pdf'){
									if(!empty($v['Thumbnail'])){ ?>
										<img src="<?= $v['Thumbnail']?>" width="100px" alt="">
									<?php }else{ ?>
										<img src="../assets/images/training-pdf-icon.jpg"  height="50px" width="50px" alt="pdf-icon">
									<?php }
								}elseif(isset($exts_name) && ($exts_name=='docx' || $exts_name=='doc')){
									if(!empty($v['Thumbnail'])){ ?>
										<img src="<?= $v['Thumbnail']?>" width="100px" alt="">
									<?php }else{ ?>
										<img src="../assets/img/icon-word.png"  height="50px" width="50px" alt="doc-icon">
										<?php
									}
								}elseif(isset($exts_name) && ($exts_name=='ppt'|| $exts_name=='pptx')){
									if(!empty($v['Thumbnail'])){ ?>
										<img src="<?= $v['Thumbnail']?>" width="100px" alt="">
									<?php }else{ ?>
										<img src="../assets/img/icon-powerpoint.png"  height="50px" width="50px" alt="ppt-icon">
									<?php }
								}elseif(isset($type[0]) && ($type[0]=='video')){
									if(!empty($v['Thumbnail'])){ ?>
										<img src="<?= $v['Thumbnail']?>" width="100px" alt="">
									<?php }else{ ?>
										<video poster="" style="height:70px;">
											<source type="video/mp4" src="<?= $v['Media'] ?>" >
										</video>
									<?php }
								} else { ?>
									<small><?= $v['MediaType']?> <?= trim(end($media_name)) ?></small>
								<?php } ?>
							</a></td>
							<td width="10%" ><a href="javascript:void(0);" style="color:#4682B4 !important;"><i class="fa fa-arrows-v" style="font-size:20px;"></i></a></td>
						</tr>
					<?php  } ?>
				</tbody>
			</table>
		<div>

		</div>
	</div>

	</script>
		<script>
			$(document).ready(function(){
				$("tbody").sortable({
					update: function( event, ui ) {
						updateOrder();
					}
				});
			});
			function updateOrder() {
				var action = 5;
				var type = $('#modal-media input[name=sortType]').val();
				var subtype = $('#modal-media input[name=sortSubType]').val();
				var mediaid = $('#modal-media input[name=sortMediaID]').val();
				var item_order = new Array();
				$('table.sortlist tr').each(function() {
					item_order.push($(this).attr("id"));
				});
				var order_string = item_order;
				// console.log(order_string);
				$.ajax({
					type: "Post",
					url: "/actions/ajax/crudBodyMedia.php",
					data: {
							'sort_ids': order_string,
							'Type' : type,
							'SubType' : subtype,
							'mediaid' : mediaid,
							'Action' : action,
						},
					cache: false,
					success: function(data){
						console.log(data);
					}
				});
			}
			$('html').on('click','#btn-modal-close',function(){
				$('#modal-media').removeClass('md-show');
				window.location.reload(true);
			});
		</script>
<?php } ?>
<style>
	.md-content input {
		background: #fff !important;
		border:1px dotted !important;
	}
	.md-content textarea {
		background: #fff !important;
		border:1px dotted !important;
	}
	/* thumbnail css */
	.preview-container .previews-thumbnail .onyx-dropzone-info img {
		max-width: 100%;
		height: auto;
	}
	.preview-container .previews-thumbnail .onyx-dropzone-info > .thumb-container {
		flex: 0 0 50px;
		max-width: 50px;
		border-radius: 10px;
		overflow: hidden;
		margin-right: 17px;
	}
	.previews-thumbnail > div {
		display:flex;
	}
	.preview-container .previews-thumbnail .onyx-dropzone-info {
		display: flex;
		flex-wrap: nowrap;
		padding-top: 15px;
		width: 100%;
	}
	.preview-container .previews-thumbnail .onyx-dropzone-info > .thumb-container {
		flex: 0 0 50px;
		max-width: 50px;
		border-radius: 10px;
		overflow: hidden;
		margin-right: 17px;
	}
	.preview-container .previews-thumbnail .onyx-dropzone-info > .details {
		position: relative;
		flex: 0 0 calc(100% - 50px);
		max-width: calc(100% - 50px);
		padding-right: 30px;
	}
	.preview-container .previews-thumbnail .onyx-dropzone-info .actions {
		position: absolute;
		right: 0;
		top: 50%;
		line-height: 1;
		transform: translateY(-50%);
	}
</style>