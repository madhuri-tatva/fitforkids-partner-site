<?php
include ("../../includes/config.php");
// echo "<pre>";print_r($_POST);exit;
// echo "<pre>";print_r($_FILES);exit;
$action = $_POST["action"];
if (!empty($action))
{
    switch ($action)
    {
        case "edit":
            $data = array(
                "Message" => json_encode(htmlspecialchars($_POST['txtmessage'])) ,
                "UserId" => $_SESSION['UserId']
            );
            $data_upload = $_POST['upload_id'];

            $db->where('Id', $_POST['message_id']);
            $uploadData = $db->getOne("thread_message");

            $oldVal = $uploadData['upload_id'];
            

            if (isset($_POST['upload_id']) && $_POST['upload_id'] != '' && !empty($data_upload))
            {
                $data['upload_id'] = $data_upload;
            }
            else{
                $data['upload_id'] = "";
            }

            $data_new = array(
                "Msg_id" => $_POST['message_id']
            );

            if ($data_upload != $oldVal || $data_upload == "")
            {
                $db->where('Id', $oldVal);
                $result = $db->getOne('chat_media');
                if($result){
                    $path = $_SERVER['DOCUMENT_ROOT'].$result['URL'];
                    if ( file_exists($path) ) {
                        unlink($path);
                    }
                }
                $db->where('Id', $oldVal);
                $db->delete('chat_media');

                if($data_upload != ""){
                    $db->where('Id', $data_upload);
                    $result = $db->update('chat_media', $data_new);
                }
            }
            

            $db->where('Id', $_POST['message_id']);
            $result = $db->update('thread_message', $data);
            //Retrieve Code
            $result_json = json_decode($_POST['txtmessage']);

            if (json_last_error() === JSON_ERROR_NONE)
            {
                $return_message = json_decode($_POST['txtmessage']);
            }
            else
            {
                $return_message = htmlspecialchars($_POST['txtmessage']);
            }
            if ($result)
            {
                echo $return_message;
            }
            break;

        case "delete":
            if (!empty($_POST["message_id"]))
            {
                $files = array();
                $db->where('Id', $_POST['message_id']);
                $db->delete('thread_message');

                $db->where('Msg_id', $_POST['message_id']);
                $files = $db->get('chat_media');

                foreach($files as $file){
                    $target_path = $_SERVER['DOCUMENT_ROOT'].$file['URL'];
                    if ( file_exists($target_path) ) {
                        unlink($target_path);
                    }
                }
                $db->where('Msg_id', $_POST['message_id']);
                $db->delete('chat_media');
            }
        break;

        case "edit_post":

            $data = array(
                "Message" => json_encode(htmlspecialchars($_POST['txtpost'])) ,
                "UserId" => $_SESSION['UserId']
            );
            $db->where('Id', $_POST['message_id']);
            $uploadData = $db->getOne("thread");
            $oldVal = explode(',', $uploadData['upload_post_id']);

            if (isset($_POST['upload_post_id']) && $_POST['upload_post_id'] != '')
            {
                $data_post_upload = explode(',', $_POST['upload_post_id']);
            
                if (count($oldVal) > 0)
                {
                    foreach ($oldVal as $k => $val)
                    {
                        if (!in_array($val, $data_post_upload))
                        {
                            $db->where('Id', $val);
                            $result = $db->getOne('chat_media');
                            if($result){
                                $path = $_SERVER['DOCUMENT_ROOT'].$result['URL'];
                                if ( file_exists($path) ) {
                                    unlink($path);
                                }
                            }
                            $db->where('Id', $val);
                            $db->delete('chat_media');
                        }
                    }
                }
                $data['upload_post_id'] = implode(',', $data_post_upload);
            }
            else
            {
                $data_post_upload = array();
                $db->where('Msg_id', $_POST['message_id']);
                $result = $db->get('chat_media');
                if($result){
                    foreach($result as $res){
                        $path = $_SERVER['DOCUMENT_ROOT'].$res['URL'];
                        if ( file_exists($path) ) {
                            unlink($path);
                        }
                    }
                }
                $db->where('Msg_id', $_POST['message_id']);
                $db->delete('chat_media');

                $data['upload_post_id'] = "";
            }

            $data_new = array(
                "Msg_id" => $_POST['message_id']
            );

            // if (isset($_POST['upload_post_id']) && $_POST['upload_post_id'] != '' && !empty($data_post_upload))
            // {
            //     $data['upload_post_id'] = implode(',', $data_post_upload);
            // }
            // else{
            //     $data['upload_post_id'] = "";
            // }
            // echo "<pre>";print_r($data);exit;

            if (count($data_post_upload) > 0)
            {
                foreach ($data_post_upload as $key => $value)
                {
                    $db->where('Id', $value);
                    $result = $db->update('chat_media', $data_new);
                }
            }
            // else
            // {
            //     $db->where('Id', $_POST['upload_post_id']);
            //     $result = $db->update('chat_media', $data_new);
            // }

            $db->where('Id', $_POST['message_id']);
            $result = $db->update('thread', $data);
            //Retrieve Code
            $result_msg = json_decode($_POST['txtpost']);

            if (json_last_error() === JSON_ERROR_NONE)
            {
                $return_msg = json_decode($_POST['txtpost']);
            }
            else
            {
                $return_msg = htmlspecialchars($_POST['txtpost']);
            }
            if ($result)
            {
                echo $return_msg;
            }
        break;

        case "delete_post":
            if (!empty($_POST["message_id"]))
            {
                $files = array();
                $comments = array();
                $db->where('Id', $_POST['message_id']);
                $db->delete('thread');

                $db->where('ThreadId',$_POST['message_id']);
                $comments = $db->get("thread_message");
                foreach($comments as $comment){
                    $db->where('Id',$comment['upload_id']);
                    $comment_file = $db->getOne('chat_media');

                    $commentFilePath = $_SERVER['DOCUMENT_ROOT'].$comment_file['URL'];
                    if ( file_exists($commentFilePath) ) {
                        unlink($commentFilePath);
                    }
                    $db->where('Id',$comment['upload_id']);
                    $db->delete('chat_media');
                }
                $db->where('ThreadId',$_POST['message_id']);
                $db->delete('thread_message');

                $db->where('Msg_id', $_POST['message_id']);
                $files = $db->get("chat_media");

                foreach($files as $file){
                    $target_path = $_SERVER['DOCUMENT_ROOT'].$file['URL'];
                    if ( file_exists($target_path) ) {
                        unlink($target_path);
                    }
                }
                $db->where('Msg_id', $_POST['message_id']);
                $db->delete('chat_media');
            }
        break;
    }
}

?>
