<?php
include("../../includes/config.php");
if(isset($_POST)){
    if($_POST['Action'] == 1){
        //action 1 insert chat message
         $messageBody =  $_POST['message'];
        if(!empty($messageBody) && !empty($_POST['recepient_id']) && !empty($_SESSION['UserId'])){
            $data = array(
                "participant1"   => $_SESSION['UserId'],
                "participant2"  => $_POST['recepient_id'],
                "message"  => $messageBody,
                "msg_type"  => 'Text',
                "sender_id"   => $_SESSION['UserId'],
                "created_at" => $db->now(),
            );
        $last_id= $db->insert('coach_chat', $data);
            echo $last_id;
        }else{
            echo 0;
        }
   }elseif($_POST['Action'] == 2){
       // action 2 get ajax chat messages
        $recepientUserID = $_POST['recepient_id'];
        $chatID = $_POST['chat_id'];
        $userID = $_SESSION['UserId'];
        $where = '(participant1 = '.$recepientUserID.' OR participant2 = '.$recepientUserID.')
        AND (participant1 = '.$userID.' OR participant2 = '.$userID.')';
        $db->where($where);
        if($chatID>0){
            $db->where('id',$chatID);
        }
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
                        if($mediaType == 'jpg' || $mediaType == 'jpeg' || $mediaType == 'png' || $mediaType == 'svg'){
                            $message= '<p class="has-image"><a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="'.$media['URL'].'"  width="50px" height="50px" /></a></p>';
                        }elseif($mediaType == 'mp4'){
                            $message= '<p class="has-image"><a href="'.$basehref.'uploads/'.$media['FileName'].'" target="_blank"><img src="../assets/img/icon-play-neg.svg" alt="video-play" style="min-width:auto;width:55px;margin-right: 40px;" /></a></p>';
                        }elseif($mediaType == 'pdf'){
                            $message= '<p class="has-image"><a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="/assets/img/icon-pdf.png"  style="min-width:auto;width:45px;margin-right: 40px;" /></a></p>';
                        }elseif($mediaType == 'doc' || $mediaType == 'docx'){
                            $message= '<p class="has-image"><a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="/assets/img/icon-word.png"  style="min-width:auto;width:45px;margin-right: 40px;" /></a></p>';
                        }elseif($mediaType == 'ppt' || $mediaType == 'pptx'){
                            $message= '<p class="has-image"><a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank"><img src="/assets/img/icon-powerpoint.png" style="min-width:auto;width:45px;margin-right: 40px;" /></a></p>';
                        }else{
                            $message= '<p><a href="'.$media['URL'].'" title="'.$media['URL'].'" target="_blank">'.$media['URL'].' '.$mediaType.'</a></p>';
                        }
                    }else{
                        $message= '<p> Media has deleted'.$value['message'].'</p>';
                    }
                }elseif($value['media_id']==0 && $value['msg_type']=='Media'){
                    $mtype= explode('/',$value['media_type']);
                    $exts= explode('/',$value['message']);
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
                        $message= '<p><a href="'.$value['message'].'"  target="_blank">'.$value['message'].'</a></p>';
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
                    <div class="chat-item sender <?= ($value['msg_type']=="Media") ?  'media-msg' : ''?>">
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
            <?php } } }
   }elseif($_POST['Action'] == 3){
       // action 3 insert chat media
       if(!empty($_POST['media_id']) && !empty($_POST['recepient_id']) && !empty($_SESSION['UserId'])){
            $db->where('Id',$_POST['media_id']);
            $media = $db->getOne('media');
            if($media){
                $messageBody = $media['URL'];
                $data = array(
                    "participant1" => $_SESSION['UserId'],
                    "participant2" => $_POST['recepient_id'],
                    "message"  => $messageBody,
                    "msg_type"  => 'Media',
                    'media_type' => $media['Type'],
                    "media_id"  => $_POST['media_id'],
                    "sender_id" => $_SESSION['UserId'],
                    "created_at" => $db->now(),
                );
                $last_id= $db->insert('coach_chat', $data);
                echo $last_id;
            }else{
                echo 0;
            }
        }else{
            echo 0;
        }
   }elseif($_POST['Action'] == 4){
    // action 4 insert cochee chat media
    if(!empty($_POST['recepient_id']) && !empty($_SESSION['UserId'])){
         $db->where('UserId',$_SESSION['UserId']);
         $db->where('Type','Chat');
         $db->orderBy('ID','desc');
         $media = $db->getOne('body_media_temp');
         if(!empty($media)){
             $messageBody = $media['URL'];
             $data = array(
                 "participant1" => $_SESSION['UserId'],
                 "participant2" => $_POST['recepient_id'],
                 "message"  => $media['URL'],
                 "msg_type"  => 'Media',
                 'media_type' => $media['MediaType'],
                 "media_id"  => $_POST['media_id'],
                 "sender_id" => $_SESSION['UserId'],
                 "created_at" => $db->now(),
             );
            $last_id= $db->insert('coach_chat', $data);
            $db->where('ID',$media['ID']);
            $db->delete('body_media_temp');
             echo $last_id;
         }else{
             echo 0;
         }
     }else{
         echo 0;
     }
}
}
?>