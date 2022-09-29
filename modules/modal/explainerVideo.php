<?php
// echo "<pre>";print_r($_GET);exit;
include("../../includes/config.php");
$action = $_GET['action'];
$page_name = '';
if($action == "battle"){
	$page_name = "battle";
}elseif($action == "profile"){
	$page_name = "profile";
}elseif($action == "landing_page"){
	$page_name = "landing_page";
}elseif($action == "dashboard"){
	$page_name = "dashboard";
}elseif($action == "body"){
	$page_name = "body";
	$msg_id = $_GET['msg_id'];
}elseif($action == "mind"){
	$page_name = "mind";
}elseif($action == "soul"){
	$page_name = "soul";
}elseif($action == "msg"){
	$table = "thread_message";
	$msg_id = $_GET['msg_id'];
}elseif($action == "post"){
	$table = "thread";
}else{
	$page_name = "";
}

if ($action != "msg") {
	$vidUrl = '';
	if(isset($msg_id) && $msg_id>0 && $page_name=='body'){
		$db->where('ID',$msg_id);
		$mediaData = $db->getOne('body_media');
		$vidUrl = $mediaData['Media'];
	}else{
		$db->orderBy("Id","desc");
		$db->where('Page',$page_name);
		$mediaData = $db->getOne('media');
		// echo "<pre>";print_r($mediaData);
		if ($mediaData['URL'] != '') {
			$vidUrl = $mediaData['URL'];
		}
	}
}else{
	$chatmedias = $db->get('chat_media');

	$db->where('Id',$msg_id);
	$uploadData = $db->getOne("chat_media");
	// echo "<pre>";print_r($uploadData);exit;
	if ($uploadData['URL'] != '') {
		$vidUrl = $uploadData['URL'];
	}
}
?>

<div class="modal-header">
	<button class="md-close"></button>
</div>

<div class="section">


	<input type="hidden" name="videoAction" class="" value="<?php echo $action; ?>" />
	<div class="video-responsive">
    	<iframe id="cartoonVideo" width="100%" height="420" src="<?php echo $vidUrl;?>" frameborder="0" autoplay allowfullscreen></iframe>
	</div>
	<!-- <iframe id="cartoonVideo" autoplay width="1024" height="768" src="<?php echo $vidUrl;?>" frameborder="0" allowfullscreen></iframe> -->



</div>



<script>
	$(document).ready(function() {
  	// set unique id to videoplayer for the Webflow video element
	  var src = $('.videoplayer').children('iframe').attr('src');

	  $('#modal-video').click(function(e) {
	    e.preventDefault();
	    $('.video-responsive').children('iframe').attr('src', src);
	    $('.modal-background').fadeIn();
	  });
	  $('.md-close').click(function(e) {
	    e.preventDefault();
	    $('.video-responsive').children('iframe').attr('src', '');
	    $('.modal-background').fadeOut();
	  });
	});

$('#btn-modal-save').off('click');

$('html').on('click','.success a.close',function(){
	$('.md-modal').removeClass('md-show');
	$('.md-content-inner').html('');
});

// if($("#modal-video-media").hasClass("hide")){
// 	$("#modal-video-media iframe").removeAttr("src");
// }


</script>
<!-- //Stop Video -->



