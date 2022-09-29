<link id="dropzone-css" href="/assets/lib/css/dropzone.css" rel="stylesheet">
<link id="font-awesome-css" href="/assets/lib/css/font-awesome.min.css" rel="stylesheet">
<link id="onyx-css" href="/assets/css/draganddrop.css" rel="stylesheet">
<?php
   include("../../includes/config.php");
   // echo "<pre>";print_r($_GET);exit;
   
   if($_GET['action'] != 5){
   
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
      <button id="btn-modal-close" class="btn-cta btn-close close_btn">Gem</button>
   </div>
</div>
<div class="section">
   <div class="col-md-12">
      <!-- Files section -->
      <h4 class="section-sub-title"><span>Upload</span> Your Files</h4>
      <form action="/file-upload-media.php" class="dropzone files-container">
         <div class="fallback">
            <input name="file" type="file" multiple />
            <input name='fileThumbnail' type='hidden' value=''>
         </div>
         <input type="hidden" name="msgid" value="<?php echo $_GET['msgid']; ?>">
         <input type="hidden" name="msg_type" value="<?php echo $_GET['msg_type']; ?>">
         <input type="hidden" name="action" class="action" value="<?php echo $_GET['action']; ?>">
      </form>
      <input type="hidden" name="upload_id[]" class="upload_new" value="">
      <!-- Notes -->
      <span>Only JPG, PNG, MP4, MKV, AVI files types are supported.</span>
      <!-- <span>Maximum file size is 25MB.</span> -->
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
      <!-- Warnings -->
      <div id="warnings">
         <span>Warnings will go here!</span>
      </div>
   </div>
</div>
<!-- /End row -->
<?php } elseif($_GET['action'] == 5){ 
   // echo "<pre>";print_r($_GET);exit;
   	$imageId = $_GET['msgid'];
   	$db->where('Id',$imageId);
   	$image = $db->getOne('chat_media');
   	// echo "<pre>";print_r($image);exit;
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
      <form id="dropzone" action="/file-upload-media.php" method="post" class="dropzone files-container" style="opacity: 0;">
         <div class="fallback">
            <input name="target_file" type="hidden" value="<?php echo $_SERVER['DOCUMENT_ROOT']; ?><?php echo $image['URL']; ?>" />
            <input name="delete_file" type="hidden" value="1" />
            <input name="delete_file_id" type="hidden" value="<?php echo $_GET['msgid']; ?>" />
            <input name="delete_file_type" type="hidden" value="<?php echo $_GET['msg_type']; ?>" />
            <input name="delete_file_msg_id" type="hidden" value="<?php echo $image['Msg_id']; ?>" />
            <input type="hidden" name="action" class="action" value="<?php echo $_GET['action']; ?>">
         </div>
      </form>
   </div>
</div>
<?php } 
   ?>
<!-- /uploads/hubspot-logo.png -->
<script src="/assets/lib/js/dropzone.min.js"></script>
<script src="/assets/lib/js/upload_script.js"></script>
<script>
   $('#btn-modal-close').click(function(){   
   	$('#modal-upload-media').removeClass('md-show');
   });
   
   $('.md-close').click(function(e) {
   	$('#modal-upload-media').removeClass('md-show');
   });
   $('.close_btn').click(function(){
   	// var action = $(".action").val();
   	// var new_id = $(".upload_new").val();
   	// if (action == 1 || action == 2) {
   	// 	$('.upload_post_id').val(new_id);
   	// }else{
   	// 	$('.upload_id').val(new_id);
   	// }
   
   });
   
</script>