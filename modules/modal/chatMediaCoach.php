<?php
include ("../../includes/config.php");
if (isset($_GET['action'])){
	if($_GET['action']==1){  // attach media library
		$medias = $db->get('media');
		$mediaSorted = array();
		foreach($medias as $data){
			$mediaSorted[$data['Id']] = $data;
		}
		if(empty($_SESSION['coach_id']) && ($_SESSION['EmployeeGroup']==4||$_SESSION['EmployeeGroup']==1)){?>
			<!-- // attach media library for coach -->
			<div class="modal-header body-learning-video-popup-heading">
				<div class="col-md-6">
					<h3>Media Library</h3>
				</div>
				<div class="col-md-6">
					<button id="btn-modal-chat-media-save" class="btn-cta btn-save">Send</button>
				</div>
			</div>
			<div class="section body-learning-video-popup">
				<div class="col-md-12">
					<style>
					.md-content .section{
						padding:0;
					}
					</style>
					<div class="box-std">
						<div class="box-content">
							<?php if(!empty($medias)){
								$mediaSorted = array();
								foreach($medias as $data){
									$mediaType = strtolower($data['Type']);
									if($mediaType == 'jpg' or $mediaType == 'png'){
										$mediaSorted['Images'][$data['Id']] = $data;
									}elseif($mediaType == 'mp4'){
										$mediaSorted['Videos'][$data['Id']] = $data;
									}else{
										$mediaSorted['Documents'][$data['Id']] = $data;
									}
								}?>
								<input type="hidden" id="mediaID" name="mediaID" value="">
								<input type="hidden" id="receiverID" name="receiverID" value="0">
								<div class="box-tabs">
									<ul class="tabs">
										<li>
											<a onclick="tabchange('tab-images')">Billeder</a>
										</li>
										<li>
											<a onclick="tabchange('tab-videos')">Videoer</a>
										</li>
										<li>
											<a onclick="tabchange('tab-documents')">Dokumenter</a>
										</li>
									</ul>
								</div>
								<div class="box-tab-content" id="tab-images">
								<div class="table-wrapper">
									<table id="medias-images" class="list">
										<thead>
											<tr>
												<th width="20%"></th>
												<th width="40%">Titel</th>
												<th width="15%">Type</th>
												<th width="15%"></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($mediaSorted['Images'] as $media){ if(file_exists($_SERVER['DOCUMENT_ROOT'].$media['URL'])){?>
												<tr data-media-id="<?= $media['Id']?>">
													<td width="20%"><a href="<?= $media['URL'] ?>" title="<?= $media['URL'] ?>" target="_blank"><img class="img-small" src="<?php echo $media['URL']; ?>" /></a></td>
													<td width="40%"><?php echo $media['FileName']; ?></td>
													<td width="15%"><?php echo $media['Type']; ?></td>
													<td width="15%" class="right"><a  onclick="modalChooseImage(<?= $media['Id']?>)">Vælg</a></td>
												</tr>
											<?php } }?>
										</tbody>
									</table>
								</div>
								</div>
								<div class="box-tab-content hide" id="tab-videos">
								<div class="table-wrapper">
									<table id="medias-videos" class="list">
										<thead>
											<tr>
												<th width="10%"></th>
												<th width="80%">Titel</th>
												<th width="10%"></th>

											</tr>
										</thead>
										<tbody>
											<?php foreach($mediaSorted['Videos'] as $media){ if(file_exists($_SERVER['DOCUMENT_ROOT'].$media['URL'])){?>
												<tr data-media-id="<?= $media['Id']?>">
													<td width="10%">
													<a href="<?= $media['URL'] ?>" title="<?= $media['URL'] ?>" target="_blank"><img src="/assets/img/icon-play-neg.svg"  width="50px" height="50px" /></a>
													</td>
													<td width="80%" style="overflow:hidden"><?php echo $media['FileName']; ?></td>
													<td width="10%" class="right"><a onclick="modalChooseImage(<?= $media['Id']?>)">Vælg</a></td>
												</tr>
											<?php } }?>
										</tbody>
									</table>
								</div>
								</div>
								<div class="box-tab-content hide" id="tab-documents">
								<div class="table-wrapper">
									<table id="medias-documents" class="list">
										<thead>
											<tr>
												<th width="10%"></th>
												<th width="60%">Titel</th>
												<th width="10%">Type</th>
												<th width="10%"></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($mediaSorted['Documents'] as $media){if(file_exists($_SERVER['DOCUMENT_ROOT'].$media['URL'])){
												$mediaType = strtolower($media['Type']);     ?>
												<tr data-media-id="<?= $media['Id']?>">
													<td width="10%"><?php if($mediaType == 'pdf'){
														echo '<a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="/assets/img/icon-pdf.png"  width="50px" height="50px" /></a>';
													}elseif($mediaType == 'doc' || $mediaType == 'docx'){
														echo '<a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="/assets/img/icon-word.png"  width="50px" height="50px" /></a>';
													}elseif($mediaType == 'ppt'){
														echo '<a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="/assets/img/icon-powerpoint.png"  width="50px" height="50px" /></a>';
													}?></td>
													<td width="60%"><?php echo $media['FileName']; ?></td>
													<td width="10%"><?php echo $media['Type']; ?></td>
													<td width="10%" class="right"><a onclick="modalChooseImage(<?= $media['Id']?>)">Vælg</a></td>
												</tr>
											<?php } }?>
										</tbody>
									</table>
								</div>
								</div>

							<?php } ?>

						</div>
					</div>
				</div>
			</div>

			<script>
				function tabchange(tab){
					$('.box-tab-content').addClass('hide');
					$('#'+tab).removeClass('hide');
				}
				$('#medias-images').dataTable( {
					"pageLength": 20,
					"order": [],
					"columnDefs": [ {
						"targets"  : 'no-sort',
						"orderable": false,
					}]
				});

				$('#medias-videos').dataTable( {
					"pageLength": 20,
					"order": [],
					"columnDefs": [ {
						"targets"  : 'no-sort',
						"orderable": false,
					}]
				});

				$('#medias-documents').dataTable( {
					"pageLength": 20,
					"order": [],
					"columnDefs": [ {
						"targets"  : 'no-sort',
						"orderable": false,
					}]
				});

				$('html').on('click','#btn-modal-delete',function(){
					$('#modal-media').removeClass('.md-show');

				});
				function modalChooseImage(id){
					// alert(id);
					if($('tr').hasClass('active')){
						$('tr').removeClass('active');
						$('#mediaID').prop('value','');
					}
					if($('tr[data-media-id='+id+']').hasClass('active')){
						$('tr[data-media-id='+id+']').removeClass('active');
						$('#mediaID').prop('value','');
					}else{
						$('tr[data-media-id='+id+']').addClass('active');
						$('#mediaID').prop('value',id);
					}
				}
			</script>
		<?php }elseif($_SESSION['coach_id']>0){ ?>
			<!-- // upload media  for coachee -->
			<style type="text/css">
			.dropzone_video .dz-default{
				background-color:rgba(0, 0, 0, .2);padding: 10px;
			}
			.dz-button{
				background: transparent !important;
				border: none !important;
				font-size: medium !important;
			}
			.uploaded-files span, .uploaded-files a {
					color: #000 !important;
			}
			</style>
			<div class="modal-header body-learning-video-popup-heading">
					<div class="col-md-6">
						<h3>Attach Media</h3>
					</div>
					<div class="col-md-6">
						<button id="coachee-chat-media-save" class="btn-cta btn-save">Send</button>
					</div>
				</div>
				<div class="section body-learning-video-popup">
					<div class="col-md-12">
					<form action="/file-upload-coach.php" class="dropzone files-container" id="dropzone">
						<div class="fallback">
							<input name="file" type="file" />
							<input type="text" name="mtype" value="Chat">
							<input type="text" name="userID" value="<?= $_SESSION['UserId'] ?>">
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
										<div class="dz-message" data-dz-message><span></span></div>
										<div>
											<span data-dz-name></span> <span data-dz-size></span>
										</div>
										<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
										<div class="dz-error-message"><span data-dz-errormessage></span></div>
										<div class="actions">
											<!-- <form action="/file-upload-coach.php" class="dropzone files-container"> -->
											<a href="#!" data-dz-remove style="display:none;"><i class="fa fa-trash"></i></a>
												<input type="hidden" name="delete_file" value="1">
											<!-- </form> -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					</div>
			</div>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
			<script src="/assets/js/dropzone-extend-chat.js"></script>
		<?php } ?>
	<?php }elseif($_GET['action']==2) {?>
			<script type="text/javascript">
			$(document).ready(function(){
				var $cont = $('.md-content .chat-content');
				$cont[0].scrollTop = $cont[0].scrollHeight;
			});
			</script>
			<div class="section coach-chat-popup" style="overflow:auto;">
			<div class="chat-block">
				<div class="chat-heading-block">
					<ul>
						<li>
							<!-- <a href="#" class="emoji-picker-icon emoji-picker" data-type="picker"><img src="../assets/images/smiley-icon.png" alt="smiley-icon"></a> -->
							<!-- <a href="javascript:void(0)" class="fullscreen-icon" ><i  class="fa fa-close"></i></a> -->
						</li>
						<li>
							<div class="file-upload-btn md-trigger1" data-modal="modal-category-add" onclick="chatMediaCoach(1)">
								<!-- <input type="file" accept="image/*,.mp4,.avi,.mkv"> -->
								<em><img src="../assets/images/pin-icon.png" alt="pin-icon"></em>
							</div>
						</li>
					</ul>
				</div>
				<div class="chat-content">
					<?php
					$recepientUserID = $_GET['receiverID'];
					$userID = $_SESSION['UserId'];
					$where = '(participant1 = '.$recepientUserID.' OR participant2 = '.$recepientUserID.')
					AND (participant1 = '.$userID.' OR participant2 = '.$userID.')';
					$db->where($where);

					$chatData=$db->get('coach_chat');
					if(!empty($chatData)){
						$chat_date_array = (array) null;
						foreach ($chatData as $value) {
							$chat_date = date('d.M.Y', strtotime($value['created_at']));
							if($value['sender_id']!=$_SESSION['UserId']){
								if($value['msg_status']==0){
									$db->where('id',$value['id']);
									$db->update('coach_chat',['msg_status'=>1]);
								}
							}
							if($value['media_id']!=0){
								$db->where('Id',$value['media_id']);
								$media = $db->getOne('media');
								if(!empty($media)){
									$mediaType = strtolower($media['Type']);
									if($mediaType == 'jpg' || $mediaType == 'png'){
										$message= '<p class="has-image"><a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="'.$media['URL'].'"  width="50px" height="50px" /><a></p>';
									}elseif($mediaType == 'mp4'){
										$message = '<p class="has-image"><a href="'.$basehref.'uploads/'.$media['FileName'].'" target="_blank"><img src="../assets/img/icon-play-neg.svg" alt="video-play" style="min-width:auto;width:55px;margin-right: 40px;" /></a></p>';
									}elseif($mediaType == 'pdf'){
										$message= '<p class="has-image"><a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="/assets/img/icon-pdf.png" style="min-width:auto;width:45px;margin-right: 40px;"  /></a></p>';
									}elseif($mediaType == 'doc' || $mediaType == 'docx'){
										$message= '<p class="has-image"><a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="/assets/img/icon-word.png" style="min-width:auto;width:45px;margin-right: 40px;"  /></a></p>';
									}elseif($mediaType == 'ppt'){
										$message= '<p class="has-image"><a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="/assets/img/icon-powerpoint.png" style="min-width:auto;width:45px;margin-right: 40px;" /></a></p>';
									}else{
										$message= '<a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><p>'.$media['URL'].' '.$mediaType.'</p></a>';
									}
								}else{
									$message= '<p> Media has deleted'.$value['message'].'</p>';
								}
							}elseif($value['media_id']==0 && $value['msg_type']=='Media'){
								$mtype= explode('/',$value['media_type']);
								if(!empty($mtype)){
									if(isset($mtype[0]) && $mtype[0]=='image'){
										$message= '<p class="has-image"><a href="'.$value['message'].'" title="'.$value['message'].'" target="_blank"><img src="'.$value['message'].'"  width="50px" height="50px" /></a></p>';
									}elseif(isset($mtype[0]) && $mtype[0]== 'video'){
										$message= '<p class="has-image"><a href="'.$value['message'].'" target="_blank"><img src="../assets/img/icon-play-neg.svg" alt="video-play" style="min-width:auto;width:55px;margin-right: 40px;" /></a></p>';
									}elseif(isset($mtype[1]) && $mtype[1]=='pdf'){
										$message= '<p class="has-image"><a href="'.$value['message'].'" title="'.$value['message'].'" target="_blank"><img src="/assets/img/icon-pdf.png"  style="min-width:auto;width:45px;margin-right: 40px;" /></a></p>';
									}elseif(isset($mtype[1]) && ($mtype[1]== 'octet-stream')){
										$exts_arr= explode('/',$value['message']);
										$exts= explode('.',$exts_arr[4]);
										if(isset($exts) && ($exts[1]== 'ppt'|| $exts[1]== 'pptx')){
											$message= '<p class="has-image"><a href="'.$value['message'].'" title="'.$value['message'].'" target="_blank"><img src="/assets/img/icon-word.png"  style="min-width:auto;width:45px;margin-right: 40px;" /></a></p>';
										}elseif(isset($exts) && ($exts[1]== 'doc'|| $exts[1]== 'docx')){
											$message= '<p class="has-image"><a href="'.$value['message'].'" title="'.$value['message'].'" target="_blank"><img src="/assets/img/icon-word.png"  style="min-width:auto;width:45px;margin-right: 40px;" /></a></p>';
										}else{
											$message= '<p ><a href="'.$value['message'].'"  target="_blank">'.$value['message'].'</a></p>';
										}
									}else{
										$message= '<p><a href="'.$value['message'].'" title="'.$value['message'].'" target="_blank">'.$value['message'].'</a></p>';
									}
								}else{
									$message= '<p><a href="'.$value['message'].'" target="_blank">'.$value['message'].'</a></p>';
								}
							}else{
								$message = '<p>'.$value['message'].'</p>';
							}?>
							<?php  if(!in_array($chat_date,$chat_date_array)){
									array_push($chat_date_array,$chat_date);?>
								<p class="date"><?= $chat_date?></p>
							<?php } ?>
							<?php if($value['sender_id']==$_SESSION['UserId']){
								$db->where('Id ',$_SESSION['UserId']);
								$sender=$db->getOne('users');
							?>
								<div class="chat-item sender <?= ($value['msg_type']=='Media') ?  'media-msg' : ''?>">
									<span class="time"><?= date('H.i', strtotime($value['created_at']))?></span>
									<em><?php if($sender['Avatar']){?><img src="<?= $sender['Avatar']; ?>" alt=""><?php } ?></em>
									<?= $message?>
									<?php if($value['msg_status']==1){?>
									<span class="recived-icon"><img src="../assets/images/activity-icon.png" alt="activity-icon"></span>
								<?php } ?>
								</div>
					<?php }else{
						$db->where('Id ',$recepientUserID);
						$reciever=$db->getOne('users');?>
								<div class="chat-item reciever">
									<em><?php if($reciever['Avatar']){?><img src="<?= $reciever['Avatar']; ?>" alt=""><?php } ?></em>
								<span class="time"><?= date('H.i', strtotime($value['created_at']))?></span>
									<?= $message?>
								</div>
					<?php } } } ?>
				</div>
				<div class="chat-footer">
					<div class="form-group">
						<p class="emoji-picker-container" style="top:5px;">
						<textarea class="input-field" data-emojiable="true" data-emoji-input="unicode"  type="text" name="message" id="comment" placeholder="Add a Message"></textarea>
						<input type="hidden" id="recepient_coachee_id" name="recepient_coachee_id" value="<?= (!empty($_GET['receiverID'])) ? $_GET['receiverID'] : 0 ?>">
						</p>
					</div>
					<button class="btn btn-cta btn-send-comment" id="btn-modal-send-msg-btn">Send</button>
				</div>
			</div>
		</div>
		<!-- Begin emoji-picker JavaScript -->
		<script src="<?= $basehref ?>assets/lib/js/config.js"></script>
		<script src="<?= $basehref ?>assets/js/slick-lightbox.min.js"></script>
		<script src="<?= $basehref ?>assets/lib/js/util.js"></script>
		<script src="<?= $basehref ?>assets/lib/js/jquery.emojiarea.js"></script>
		<script src="<?= $basehref ?>assets/lib/js/emoji-picker.js"></script>
		<!-- End emoji-picker JavaScript -->
		<script>
			$(function () {
				// Initializes and creates emoji set from sprite sheet
				window.emojiPicker = new EmojiPicker({
					emojiable_selector: '[data-emojiable=true]',
					assetsPath: '<?= $basehref ?>assets/lib/img/',
					popupButtonClasses: 'fa fa-smile-o'
				});
				window.emojiPicker.discover();
			});
		</script>
		<script type="text/javascript">
			$('html').on('click','#btn-modal-send-msg-btn',function(){
				var action = 1;
				var recepient_coachee_id =$('.md-content .chat-footer input[name=recepient_coachee_id]').val();
				var message = $('.md-content .chat-footer textarea[name=message]').val();
				$.ajax({
						url: "/actions/ajax/add_coach_chat.php",
						method: "POST",
						data: {
								'Action': action,
								'message': message,
								'recepient_id': recepient_coachee_id,
								},
						success: function(resp){
						if(resp>0){
								$('.md-content .emoji-wysiwyg-editor').text('');
								$('.md-content .chat-footer textarea[name=message]').val('');
								ajax_get_chat_data1();
							}
							// console.log(resp);
						},
						error: function(error) {
							console.log('error');
						}
				});
			});
			function ajax_get_chat_data1(chat_id=0){
				var action = 2;
				var recepient_id = <?= $_GET['receiverID']?>;
				var chat_id = chat_id;
					$.ajax({
						url: "/actions/ajax/add_coach_chat.php",
						method: "POST",
						async: true, /* If set to non-async, browser shows page as "Loading.."*/
						cache: false,
						timeout:50000, /* Timeout in ms */
						data: {
								'Action': action,
								'recepient_id': recepient_id,
								'chat_id': chat_id
							},
						success: function(resp){
							if(resp){
								if(chat_id>0){
									$(".md-content .chat-content").append(resp);
									var $cont = $('.md-content .chat-content');
									$cont[0].scrollTop = $cont[0].scrollHeight;
								}else{
									$(".md-content .chat-content").html(resp);
									var $cont = $('.md-content .chat-content');
									if($cont[0].scrollTop >= ($cont[0].scrollHeight - $cont[0].offsetHeight)) {
										$cont[0].scrollTop = $cont[0].scrollHeight;
									}
								}
							}
							setTimeout(
								ajax_get_chat_data1, /* Request next message */
								1000 /* ..after 1 seconds */
							);
						},
						error: function(error) {
							setTimeout(
								ajax_get_chat_data1, /* Try again after.. */
							15000);
							console.log('error');
						}
				});
			}
			var $cont = $('.md-content .chat-content');
			$cont[0].scrollTop = $cont[0].scrollHeight;
			$(document).ready(function(){
				ajax_get_chat_data1(); /* Start the inital request */
			});
		</script>
	<?php } ?>
<?php } ?>
