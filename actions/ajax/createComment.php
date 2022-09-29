<?php
include("../../includes/config.php");

if(isset($_POST)){


        // CREATE

        $data = array(
            "ThreadId"      => $_POST['ThreadId'],
            "Message"       => json_encode($_POST['Message']),
            "UserId"        => $_SESSION['UserId'],
            "CreateDate"    => $db->now()
        );
        if (isset($_POST['upload_id']) && $_POST['upload_id'] != '') {
            $data['upload_id'] = $_POST['upload_id'];
        }
        $last_msg_id = $db->insert('thread_message', $data);
        $data_upload = explode(',', $_POST['upload_id']);
        $data_new = array(
                "Msg_id"       => $last_msg_id
            ); 

        if (count($data_upload) > 1) {
            foreach ($data_upload as $key => $value) {
                $db->where('Id',$value);
                $result = $db->update('chat_media', $data_new);
            }
        }else{
            $db->where('Id',$_POST['upload_id']);
            $result = $db->update('chat_media', $data_new);
        }
         
            

        exit;


}

?>