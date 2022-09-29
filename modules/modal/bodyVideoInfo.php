<?php
// echo "<pre>";print_r($_GET);exit;
include("../../includes/config.php");
$action = $_GET['action'];
$page_name = '';
$title = '';
if($action == "body"){
	$page_name = "body";
	$media_id = $_GET['mediaid'];
	$db->where('ID',$media_id);
	$mediaData = $db->getOne('body_media');
	$detail = $mediaData['Detail'];
	$subtype = $mediaData['SubType'];
	$title = $mediaData['Title'];
	if(empty($title)){
		if($subtype=='1'){$title = 'Officielle Træningsvideoer';}
		elseif($subtype=='2' || $subtype=='4'){$title = 'Officielle Læringsvideoer';}
		elseif($subtype=='3'){$title = 'STANDARD TRÆNINGSPROGRAM';}
	}
}else{
	$page_name = "";
	$detail ='';
	$title ='Heading Title';
}?>

            <div class="row">
                <div class="col-md-offset-1 col-md-10 andet-below-section">
                    <label id="info-modal-title"><?= $title?></label>
                    <div id="info-modal-content">
                        <p><?= $detail?></p>
                    </div>
                </div>
            </div>


<script>
	function CloseInfoModal(){
        $('.chart-info-modal').removeClass('md-show');
    }
    $('html').on('click','.md-overlay',function(){
        $('.chart-info-modal').removeClass('md-show');
    });
	$('html').on('click','.success a.close',function(){
		$('.md-modal').removeClass('md-show');
		$('.section').html('');
	});
</script>



