<?php
include("../../includes/config.php");
// $_SESSION['UserId'] = 970;
// $_SESSION['EmployeeGroup'] = 1;
$db->where('Id', $_SESSION['UserId']);
$userData = $db->getOne('users');
// $db->where('UserId', $_SESSION['UserId']);
// $db->where('Category', $_GET['category']);
$db->where('WallType', 2);
$db->orderBy('CreateDate', 'DESC');
$threadsData = $db->get('thread');

$chatmedias = $db->get('chat_media');

$chatSorted = array();

foreach ($chatmedias as $chat_data){
  $chatSorted[$chat_data['Id']] = $chat_data;
}
$typearray = array(
"mp4",
"avi",
"mkv"
);

$emojiArray  = array('like','love','laugh','wow','sad');
if (!empty($threadsData)){ ?>
    <ul>
      <?php foreach ($threadsData as $threadData){
              $i = 0;
              $threadDate = date('d-m-Y', strtotime($threadData['CreateDate']));
              $threadTime = date('H:i', strtotime($threadData['CreateDate']));

              $db->where('Id', $threadData['UserId']);
              $threadUser = $db->getOne('users');

              // Calculate total thread likes from user_likes table
               $db->where('thread_id',$threadData['Id']);
               $userThreads = $db->get('user_likes');
               $likesArr = array_column($userThreads, 'likes');
               $likes = array_sum($likesArr);

              // Calculate total thread reactions from user_likes table
               $reactions= $like_count=$love_count=$laugh_count=$wow_count=$sad_count=0;
               $current_user_reaction= NULL;
               $usersName = NULL;
                foreach ($userThreads as $th) {
                    if($th['reactions']){
                        $reactions = $reactions+1;
                        if($th['reactions']=='like'){
                          $like_count= $like_count+1;
                        }
                        if($th['reactions']=='love'){
                          $love_count= $love_count+1;
                        }
                        if($th['reactions']=='laugh'){
                          $laugh_count= $laugh_count+1;
                        }
                        if($th['reactions']=='wow'){
                          $wow_count= $wow_count+1;
                        }
                        if($th['reactions']=='sad'){
                          $sad_count= $sad_count+1;
                        }
                        if(($th['user_id']==$_SESSION['UserId'])){
                          $current_user_reaction = $th['reactions'];
                        }
                    }
                }
                //Retrieve Code
                $result_json = json_decode($threadData['Message']);
                if (json_last_error() === JSON_ERROR_NONE){
                    $return_msg = json_decode($threadData['Message']);
                }else{
                    $return_msg = $threadData['Message'];
                }

                $is_same_team = 0;$all_trainers =[];
                if(!empty($threadUser)) {
                  $db->where('Id', $threadUser['TeamId']);
                  $team_trainers = $db->getOne('teams');
                  if(!empty($team_trainers) && !empty($team_trainers['Trainers'])){
                    $all_trainers = explode(',', $team_trainers['Trainers']);
                  }
                  if($threadUser['TeamId'] == $userData['TeamId']){
                          $is_same_team = 1;
                  }elseif(!empty($team_trainers['UserId']) && ($_SESSION['UserId'] == $team_trainers['UserId']) && ($threadUser['TeamId'] == $team_trainers['Id'])){
                          $is_same_team = 1;
                  }elseif(!empty($team_trainers['Trainers']) && in_array($_SESSION['UserId'],$all_trainers) && ($threadUser['TeamId'] == $team_trainers['Id']) ){
                          $is_same_team = 1;
                  }
                  if($userData['TeamId']==0){
                        $is_same_team = 0;
                  }
                }
                if(!empty($threadUser) && $is_same_team ==1 ){?>
                  <div class="chat-thread">
                          <div class="chat-thread-header">
                              <a href="./profil/<?php echo $threadUser['Id']; ?>">
                                <div class="chat-avatar" style="background-image: url('<?php echo $threadUser['Avatar']; ?>'); background-size: cover; background-repeat: no-repeat; background-position: center;">
                                </div>
                              </a>
                              <div class="chat-userinfo">
                                <strong><?php echo $threadUser['Firstname'] . " " . $threadUser['Lastname']; ?></strong>
                                <span class="date"><?php echo $threadDate; ?> - kl. <?php echo $threadTime; ?></span>
                              </div>
                            </div>
                            <div class="chat-thread-content" id="message_<?php echo $threadData["Id"]; ?>">
                                <p class="message-content edit_post"><?php echo $return_msg; ?></p>
                                <?php if (!empty($threadData['Image'])){
                                        echo "<img src='" . $threadData['Image'] . "' />";
                                } ?>
                                <?php if (($threadData['UserId'] == $_SESSION['UserId'])) { ?>
                                  <div class="edit_post_div">
                                      <i class="glyphicon glyphicon-pencil btnEdit" onClick="showEditArea(this, <?php echo $threadData["Id"]; ?>)"></i>
                                      <i class="glyphicon glyphicon-trash btnDelete" onClick="callCrudAction('delete_post',<?php echo $threadData["Id"]; ?>)"></i>
                                  </div>
                                <?php } ?>
                            </div>
                            <div class="image-list">
                                <?php $i=0;
                                  foreach ($chatSorted as $key => $value){
                                    if(in_array($value['Type'], $typearray) && ($value['Msg_id'] == $threadData['Id']) && $value['Msg_type'] == '1'){?>
                                      <div id="image-list">
                                         <div class="chatlist-image">
                                            <video style="width: 100%" controls disablepictureinpicture controlslist="nodownload">
                                                <source src="<?php echo $value['URL']; ?>" type="video/<?php echo $value['Type'] ?>">
                                            </video>
                                          </div>
                                     </div>
                                  <?php }if (!in_array($value['Type'], $typearray) && ($value['Msg_id'] == $threadData['Id'] && $value['Msg_type'] == '1')){ ?>
                                       <div id="image-list">
                                          <div class="chatlist-image">
                                            <img class="img-small" src="<?php echo $value["URL"]; ?>" />
                                          </div>
                                        </div>
                                  <?php } $i++;
                                } ?>
                            </div>
                            <style>
                               .tip,.tip-content span {
                                cursor: pointer;
                               }
                            </style>
                            <div class="chat-thread-meta">
                                <ul class="magic-number-list">
                                    <li>
                                      <div id="likes-tooltip_<?php echo $threadData['Id']; ?>1" class="tip-content">
                                        <a onclick="likeThreadNew(7,<?php echo $threadData['Id']; ?>,<?php echo $threadData['Category']; ?>,<?php echo $_SESSION['UserId']; ?>)" >7</a>
                                        <a onclick="likeThreadNew(17,<?php echo $threadData['Id']; ?>,<?php echo $threadData['Category']; ?>,<?php echo $_SESSION['UserId']; ?>)" >17</a>
                                        <a onclick="likeThreadNew(27,<?php echo $threadData['Id']; ?>,<?php echo $threadData['Category']; ?>,<?php echo $_SESSION['UserId']; ?>)" >27</a>
                                        <a onclick="likeThreadNew(47,<?php echo $threadData['Id']; ?>,<?php echo $threadData['Category']; ?>,<?php echo $_SESSION['UserId']; ?>)" >47</a>
                                        <a onclick="likeThreadNew(57,<?php echo $threadData['Id']; ?>,<?php echo $threadData['Category']; ?>,<?php echo $_SESSION['UserId']; ?>)" >57</a>
                                      </div>
                                      <a class="hover-likes tip" data-toggle="tooltip"  data-tip="likes-tooltip_<?php echo $threadData['Id']; ?>" rel="tooltip" href="#">
                                          <?php if ($likes > 0){ ?>
                                              <i>
                                                 <svg viewBox="0 -28 512.00002 512" xmlns="http://www.w3.org/2000/svg">
                                                      <path d="m471.382812 44.578125c-26.503906-28.746094-62.871093-44.578125-102.410156-44.578125-29.554687 0-56.621094 9.34375-80.449218 27.769531-12.023438 9.300781-22.917969 20.679688-32.523438 33.960938-9.601562-13.277344-20.5-24.660157-32.527344-33.960938-23.824218-18.425781-50.890625-27.769531-80.445312-27.769531-39.539063 0-75.910156 15.832031-102.414063 44.578125-26.1875 28.410156-40.613281 67.222656-40.613281 109.292969 0 43.300781 16.136719 82.9375 50.78125 124.742187 30.992188 37.394531 75.535156 75.355469 127.117188 119.3125 17.613281 15.011719 37.578124 32.027344 58.308593 50.152344 5.476563 4.796875 12.503907 7.4375 19.792969 7.4375 7.285156 0 14.316406-2.640625 19.785156-7.429687 20.730469-18.128907 40.707032-35.152344 58.328125-50.171876 51.574219-43.949218 96.117188-81.90625 127.109375-119.304687 34.644532-41.800781 50.777344-81.4375 50.777344-124.742187 0-42.066407-14.425781-80.878907-40.617188-109.289063zm0 0"/>
                                                 </svg>
                                              </i>
                                          <?php } ?>
                                          <?php if ($likes <= 1) { ?>
                                          <?php echo '<span>'.$likes.'</span>'; ?> Like
                                          <?php }else{ ?>
                                          <?php echo $likes; ?> Likes
                                          <?php } ?>
                                      </a>
                                    </li>
                                    <li class="tooltip-wrapper">
                                      <ul class="emoji-icon-container">
                                        <?php  foreach ($emojiArray as $icon) { ?>
                                          <li><img src="/assets/images/reaction-icons/<?= $icon; ?>.png" class="emoji-icon" data-emoji-rating="<?= $threadData['Id']; ?>" onclick="reactionThread('<?= $icon; ?>',<?= $threadData['Id']; ?>,<?= $threadData['Category']; ?>,<?= $_SESSION['UserId']; ?>)" />
                                          </li>
                                        <?php }   ?>
                                      </ul>
                                      <a class="tooltip-icon" style="padding: 5px 0;">
                                          <?php
                                              if($like_count>0){echo '<img src="/assets/images/reaction-icons/like.png" />';}
                                              if($love_count>0){echo '<img src="/assets/images/reaction-icons/love.png" />';}
                                              if($laugh_count>0){echo '<img src="/assets/images/reaction-icons/laugh.png" />';}
                                              if($wow_count>0){echo '<img src="/assets/images/reaction-icons/wow.png" />';}
                                              if($sad_count>0){echo '<img src="/assets/images/reaction-icons/sad.png" />';}?>
                                            <?= ($reactions>0) ? $reactions : ''?>
                                      </a>
                                      <a class="emoji-link" onclick="toggleEmojiPanel(this)"><?php if($current_user_reaction !=NULL){echo '<b style="text-transform: capitalize;"> Reactions</b> <img src="/assets/images/reaction-icons/'.$current_user_reaction.'.png" style="width:18px;" />';}else{echo ' Reactions';}?> </a>
                                    </li>
                                    <li><a>Kommentarer</a></li>
                               </ul>
                            </div>
                          <?php
                               $db->where('ThreadId', $threadData['Id']);
                               $threadmessagesData = $db->get('thread_message');
                              if(!empty($threadmessagesData)){ ?>
                                <div class="chat-thread-comments">
                                  <?php foreach ($threadmessagesData as $threadmessageData){

                                      $threadDate = date('d-m-Y', strtotime($threadmessageData['CreateDate']));
                                      $threadTime = date('H:i', strtotime($threadmessageData['CreateDate']));
                                      //Retrieve Code
                                      $result_json = json_decode($threadmessageData['Message']);
                                      if(json_last_error() === JSON_ERROR_NONE){
                                          $return_message = json_decode($threadmessageData['Message']);
                                      }else{
                                          $return_message = $threadmessageData['Message'];
                                      }
                                      $db->where('Id', $threadmessageData['UserId']);
                                      $threadUser = $db->getOne('users');
                                      if(!empty($threadUser)){?>
                                        <div class="chat-comment">
                                          <a href="./profil/<?php echo $threadUser['Id']; ?>">
                                            <div class="chat-avatar" style="background-image: url('<?php echo $threadUser['Avatar']; ?>'); background-size: cover; background-repeat: no-repeat; background-position: center;">
                                           </div>
                                          </a>
                                          <div class="chat-message" id="message_<?php echo $threadmessageData["Id"]; ?>">
                                            <strong><?php echo $threadUser['Firstname'] . " " . $threadUser['Lastname'] ?></strong>
                                              <div class="chat_div">
                                                  <div class="the-message message-content"><?php echo $return_message; ?>
                                                  </div>
                                                  <?php if($threadmessageData['UserId'] == $_SESSION['UserId']){ ?>
                                                    <div class="edit_chat_div">
                                                        <i class="glyphicon glyphicon-pencil btnEdit" onClick="showEditBox(this, <?php echo $threadmessageData["Id"]; ?>)"></i>
                                                        <i class="glyphicon glyphicon-trash btnDelete" onClick="callCrudAction('delete',<?php echo $threadmessageData["Id"]; ?>)"></i>
                                                    </div>
                                                  <?php } ?>
                                              </div>
                                              <div class="image-list">
                                                <?php $i = 0; foreach ($chatSorted as $key => $val){
                                                      if(in_array($val['Type'], $typearray) && $val['Msg_id'] == $threadmessageData['Id'] && $val['Msg_type'] == '2'){ ?>
                                                            <div id="image-list">
                                                                <div class="chatlist-image toggle_<?php echo $threadmessageData["Id"]; ?>">
                                                                    <video style="width: 100%" controls disablepictureinpicture controlslist="nodownload">
                                                                        <source src="<?php echo $val['URL']; ?>" type="video/mp4">
                                                                    </video>
                                                                </div>
                                                            </div>
                                                      <?php }
                                                      if (!in_array($val['Type'], $typearray) && $val['Msg_id'] == $threadmessageData['Id'] && $val['Msg_type'] == '2'){ ?>
                                                          <div id="image-list">
                                                              <div class="chatlist-image toggle_<?php echo $threadmessageData["Id"]; ?>"><img class="img-small" src="<?php echo $val["URL"]; ?>" /></div>
                                                            </div>
                                                      <?php } $i++;
                                                  } ?>
                                              </div>
                                          </div>
                                        </div>
                                      <?php }
                                  } ?>
                                </div>
                              <?php } ?>
                              <div class="chat-write chat-write-comment" id="before-commentpic">
                                   <div class="chat-avatar" style="background-image: url(<?php echo $userData['Avatar']; ?>); background-size: cover; background-repeat: no-repeat; background-position: center;">
                                   </div>
                                  <div class="add_comment">
                                      <input class="chat-input-write" id="chat_comment" data-emojiable="true" data-emoji-input="unicode" data-thread="<?php echo $threadData['Id']; ?>" placeholder="Din kommentar..." />
                                      <input type="file" data-id="<?php echo $threadData['Id']; ?>" name="comment_photo" id="comment_images" class="comment-pin" accept="image/*,.mp4,.avi,.mkv">
                                      <i class="glyphicon glyphicon-paperclip upload-pin"></i>
                                      <input type="hidden" id="comment_id" name="comment_id" class="comment_id" value="" data-cls="preview_<?php echo $threadData['Id']; ?>">
                                      <span class="btn btn-cta btn-send-comment" data-id="btn-send-comment" data-thread="<?php echo $threadData['Id']; ?>">Send</span>
                                  </div>
                              </div>
                              <div class="progress-wrapper commentMain_<?php echo $threadData['Id']; ?>" id="main-progress" style="display:none; position:relative; top:15px;" >
                                      <h6 id="status1" class="commentText">Uploading :</h6>
                                      <div class="progress">
                                          <div class="bar"></div >
                                          <div class="percent commentPercent" id="progress-bar"></div >
                                      </div>
                                      <div id="status"></div>
                              </div>
                              <div class="preview-commentpic" id="preview_<?php echo $threadData['Id']; ?>">
                                  <div class="preview_<?php echo $threadData['Id']; ?>" style="position: relative; left: 60px;">
                                  </div>
                              </div>
                  </div>
                <?php } ?>
      <?php } ?>
    </ul>
<?php }  ?>
<script>

  $('.comment-pin').on('change',function(e){
   if( $('.single-image').length )
   {
      $('.single-image').remove();
   }
    var files = e.target.files;
    var formdata = new FormData(); //direct form not object
    $.each($(".comment-pin"), function(i, obj) {
        $.each(obj.files,function(j, file){
                formdata.append('file['+j+']', file);
        });
    });
    $(".comment-pin").val('');
    //append the file relation to index
    formdata.append('action',3);
    formdata.append('msgid','');
    formdata.append('msg_type',2);
    formdata.append('uploadType',1);
    var data_id = $(this).attr('data-id');
    formdata.append('preview_div',data_id);
    $.ajax({
      xhr: function() {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function(evt) {
          if (evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            percentComplete = parseInt(percentComplete * 100);
            $('.commentMain_'+data_id).css("display","block");
            $(".commentText").text("Uploading :");
            $(".commentPercent").css("width",percentComplete+'%');
            if (percentComplete === 100) {
              $(".commentText").text("Uploaded successfully !");
              setTimeout(function(){
                $('.commentMain_'+data_id).css("display","none");
              }, 2000);
            }
          }
        }, false);
        return xhr;
      },
      url: "../../file-upload-media.php",
      method: 'POST',
      data: formdata,
      success: function(response) {
        upload_comment(response);
      },
      complete: function(){
      },
      error: function() {
      },
      contentType: false,
      processData: false
    });
  });

   $('.delete_post').on('click',function(){
       $("#modal-upload-media").addClass("md-show");
       $("#modal-upload-media").css("display","block");
   });

   $('html').on('click','#btn-modal-delete',function(){

     document.getElementById("dropzone").submit();
     $('#modal-upload-media').removeClass('md-show');

   });

   $('html').on('click','.upload_vid',function(){
       $("#modal-video").addClass("md-show");
       $("#modal-video").css("display","block");
   });
   $('html').on('click','.close_icon',function(){
       $("#modal-video").css("display","none");
       $('#modal-video').removeClass('md-show');
   });
   $('html').on('click','.add_chat',function(){
       $("#modal-upload-media").addClass("md-show");
       $("#modal-upload-media").css("display","block");
   });
   $('html').on('click','#btn-modal-close',function(){
       $("#modal-upload-media").css("display","none");
   });
   $(document).on('keyup keypress', '.add_comment .chat-input-write', function(e) {
     if(e.which == 13) {
       e.preventDefault();
       return false;
     }
   });
   $(function() {
     // Initializes and creates emoji set from sprite sheet
     window.emojiPicker = new EmojiPicker({
       emojiable_selector: '[data-emojiable=true]',
       assetsPath: 'assets/lib/img/',
       popupButtonClasses: 'fa fa-smile-o'
     });
     window.emojiPicker.discover();
   });

   $('.image-list').slick({
      infinite: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      // responsive: [
      //   {
      //     breakpoint: 1199,
      //     settings: {
      //       slidesToShow: 4,
      //       slidesToScroll: 4,
      //       variableWidth: false,
      //     }
      //   },
      //   {
      //     breakpoint: 992,
      //     settings: {
      //       slidesToShow: 2,
      //       slidesToScroll: 2,
      //       variableWidth: false,
      //     }
      //   },
      //   {
      //     breakpoint: 360,
      //     settings: {
      //       slidesToShow: 1,
      //       slidesToScroll: 1,
      //       variableWidth: false,
      //     }
      //   }
      //   // You can unslick at a given breakpoint now by adding:
      //   // settings: "unslick"
      //   // instead of a settings object
      // ],
      // variableWidth: true,
      // prevArrow: $('.prev'),
      // nextArrow: $('.next'),
      arrows: false,
      dots: true,
    });
   $('.image-list').slickLightbox({
    src: 'src',
    itemSelector: '.chatlist-image img',
    background: 'rgba(0, 0, 0, 0.9)'
  });
$(document).tooltip({
        items: ".hover-likes.tip",
        content: function() {
                var el_tip= $(this).data('tip');
                var el_html = $('#'+el_tip).html();
                console.log(el_html);
                /* var id=el_id.split('-')[1];
                var d_link = "http://www.somesite.com/page.php?id=" + id ;
                d_link = "<p><a href=\"" + d_link + "\"> go to link " +
                id + " </a></p>" ; */
               //  d_link = '<h1>content</h1>';
                return el_html ;
        }
       /*  , open: function( event, ui ) {
                ui.tooltip.animate({ top: ui.tooltip.position().top + 4 }, "fast" );
            }
        , close: function( event, ui ) {
                ui.tooltip.hover(
                    function () {
                     $(this).stop(true).fadeTo(400, 1);
                        //.fadeIn("slow"); // doesn't work because of stop()
                    },
                    function () {
                        $(this).fadeOut("400", function(){ $(this).remove(); })
                    }
                );
        } */
});
</script>
<script type="text/javascript">
  function toggleEmojiPanel(obj){
  var targetDiv = $(obj).closest('.tooltip-wrapper').find(".emoji-icon-container").css('display');
  if(targetDiv == "none") {
    $(obj).closest('.tooltip-wrapper').find(".emoji-icon-container").show();
  }else{
    // $(obj).prev(".emoji-icon-container").hide();
    $(obj).closest('.tooltip-wrapper').find(".emoji-icon-container").hide();
  }
  setTimeout(function() {
    $(obj).closest('.tooltip-wrapper').find(".emoji-icon-container").hide();
    },3000);
}
   $('.chat-thread-meta .magic-number-list li .hover-likes').click(function(e){
      e.stopPropagation();
      e.preventDefault();
      $(this).closest('li').toggleClass('show-like-list');
   });
   $(document).click(function(event) {
       $('.chat-thread-meta .magic-number-list li').removeClass('show-like-list, show-emoji-container');
   });
   $('.chat-thread-meta .magic-number-list li .emoji-link').click(function(e){
      e.stopPropagation();
      e.preventDefault();
      $(this).closest('li').toggleClass('show-emoji-container');
   });
</script>