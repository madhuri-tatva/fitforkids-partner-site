<?php
include("../../includes/config.php");
if(isset($_POST)){

    if(!empty($_POST['Message']) || !empty($_POST['upload_post_id'])){

        // CREATE

        $data = array(
            "Message"       => json_encode($_POST['Message']),
            "Category"       => $_POST['Category'],
            "UserId"        => $_SESSION['UserId'],
            "CreateDate"    => $db->now(),
            "WallType"       => $_POST['WallType']
        );
        if (isset($_POST['upload_post_id']) && $_POST['upload_post_id'] != '') {
                $data['upload_post_id'] = $_POST['upload_post_id'];
        }
        if (isset($_POST['Team_Id']) && $_POST['Team_Id'] != '') {
            $data['team_id'] = $_POST['Team_Id'];
        }
        $insert_id = $db->insert('thread', $data);
        if($insert_id){
            $data_upload = explode(',', $_POST['upload_post_id']);
            $data_new = array(
                    "Msg_id"       => $insert_id
                );

        // echo "<pre>";print_r($data_upload);exit;
            if (count($data_upload) > 1) {
                foreach ($data_upload as $key => $value) {
                    $db->where('Id',$value);
                    $result = $db->update('chat_media', $data_new);
                }
            }else{
                $db->where('Id',$_POST['upload_post_id']);
                $result = $db->update('chat_media', $data_new);
            }

            //Retrieve Code
            $result_msg = json_decode($_POST['Message']);

            if (json_last_error() === JSON_ERROR_NONE) {
                $return_msg = json_decode($_POST['Message']);
            } else {
                $return_msg = $_POST['Message'];
            }
            if($result_msg){
                  echo $return_msg;
            }
         ?>
            <div class="message-box"  id="message_' . $insert_id . '">
            <div>
            <!-- <button class="btnEditAction" name="edit" onClick="showEditBox(this,
            '<?php echo $insert_id; ?>')">Edit</button>
            <button class="btnDeleteAction" name="delete" onClick="callCrudAction(\'delete\',
            '<?php echo $insert_id; ?>')">Delete</button> -->
            </div>
            <div class="message-content"><?php echo $return_msg; ?></div></div>';
        <?php }

        exit;

    }

}

?>