<?php

$db->where('ThreadId',$_GET['threadId']);
$threadmessagesData = $db->get('thread_message');

if(!empty($threadmessagesData)){ ?>

<div class="chat-thread-comments">

  <?php foreach($threadmessagesData as $threadmessageData){ 

  	$threadDate = date('d-m-Y',strtotime($threadmessageData['CreateDate']));
  	$threadTime = date('H:i',strtotime($threadmessageData['CreateDate']));

  ?>
    
    <div class="chat-comment">
        <div class="chat-avatar">
        </div>
        <div class="chat-message">
            <strong>Pelle</strong>
            <span class="the-message"><?php echo $threadmessageData['message']; ?></span>
        </div>
    </div>

  <?php } ?>

</div>

<?php } ?>


    <!--<div class="chat-thread-comments">
        <div class="chat-comment">
            <div class="chat-avatar">
            </div>
            <div class="chat-message">
                <strong>Pelle Plesner</strong>
                <span class="the-message">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</span>
            </div>
        </div>
    </div>-->