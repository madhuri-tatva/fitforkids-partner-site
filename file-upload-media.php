<?php
require_once("includes/config.php");

/**
 * Dropzone PHP file upload/delete
 */
// echo "<pre>";print_r($_FILES);exit;
function thumbnail($url, $filename, $width = 300, $height = true) {

    // download and create gd image
    $image = ImageCreateFromString(file_get_contents($url));

    // calculate resized ratio
    // Note: if $height is set to TRUE then we automatically calculate the height based on the ratio
    $height = $height === true ? (ImageSY($image) * $width / ImageSX($image)) : $height;

    // create image 
    $output = ImageCreateTrueColor($width, $height);
    ImageCopyResampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));

    // save image
    ImageJPEG($output, $filename, 95); 

    // return resized image
    return $output; // if you need to use it

}

// Check if the request is for deleting or uploading
$delete_file = 0;
if(isset($_POST['delete_file'])){ 
    $delete_file = $_POST['delete_file'];
}


$dateYear = date('Y');
$dateMonth = sprintf("%02d", date('m'));

$targetPath = dirname( __FILE__ ) . '/uploads/chat/';
$targetUrl = '/uploads/chat/';
if(!is_dir($targetPath)) {
    mkdir($targetPath, 0777,true);
}

$targetThumbPath = dirname( __FILE__ ) . '/uploads/chat/thumb/';
$targetThumbUrl = '/uploads/chat/thumb/';

if(!is_dir($targetThumbPath)) {
    mkdir($targetThumbPath, 0777,true);
}
// echo "<pre>";print_r($_POST);exit;
if ($_POST['action'] != 5 ) {
    $countfiles = count($_FILES['file']['name']);
    $resArr  = array();

    // Looping all files
    for($i=0;$i<$countfiles;$i++){

        // Check if it's an upload or delete and if there is a file in the form
        if ( !empty($_FILES) && $delete_file == 0 ) {

            // Check if the upload folder is exists
            if ( file_exists($targetPath) && is_dir($targetPath) ) {

                // Check if we can write in the target directory
                if ( is_writable($targetPath) ) {

                    /**
                     * Start dancing
                     */

                    //$randomNumber = mt_rand(100000, 999999);
                    $randomNumber = '';

                    $tempFile = $_FILES['file']['tmp_name'][$i];
                    $file_name = $_FILES["file"]["name"][$i];
                    $array = explode('.', $file_name);
                    $fileName=$array[0];
                    $fileExt=$array[1];
                    $newfile=$fileName."_".time().".".$fileExt;


                    $targetFile = $targetPath . $newfile;
                    $targetFileUrl = $targetUrl . $newfile;

                    $thumbnailName = $newfile;
                    $targetThumbnail = $targetThumbPath . $newfile;



                    $fileType = explode('.', $newfile);
                    $fileType = trim(end($fileType));


                    // Path
                    $targetFilePosition = strrpos($targetFile, '.');
                    $targetFile = substr($targetFile, 0, $targetFilePosition) . substr($targetFile, $targetFilePosition);
                    $targetFile = substr($targetFile, 0, $targetFilePosition) . substr($targetFile, $targetFilePosition);


                    // URL
                    $targetFilePosition = strrpos($targetFileUrl, '.');
                    $targetFileUrl = substr($targetFileUrl, 0, $targetFilePosition) . substr($targetFileUrl, $targetFilePosition);
                    $targetFileUrl = substr($targetFileUrl, 0, $targetFilePosition) . substr($targetFileUrl, $targetFilePosition);


                    // Thumbnail
                    $targetFilePosition = strrpos($thumbnailName, '.');
                    $thumbnailName = substr($thumbnailName, 0, $targetFilePosition) . substr($thumbnailName, $targetFilePosition);
                    $thumbnailName = substr($thumbnailName, 0, $targetFilePosition) . '_thumb' . substr($thumbnailName, $targetFilePosition);

                    // Thumbnail
                    $targetFilePosition = strrpos($thumbnailName, '.');
                    $targetThumbnail = substr($targetThumbnail, 0, $targetFilePosition) . substr($targetThumbnail, $targetFilePosition);
                    $targetThumbnail = substr($targetThumbnail, 0, $targetFilePosition) . '_thumb' . substr($targetThumbnail, $targetFilePosition);


                    // Check if there is any file with the same name
                    if ( !file_exists($targetFile) ) {

                        // Upload the file
                        move_uploaded_file($tempFile, $targetFile);

                        // Be sure that the file has been uploaded
                        if ( file_exists($targetFile) ) {

                            // $response = array (
                            //     'status'    => 'success',
                            //     'file_link' => $targetFile
                            // );

                            $data  = array(
                                'Type' => $fileType,
                                'URL' => $targetFileUrl,
                                'FileName' => $newfile,
                                "CreateDate"    => $db->now()
                            );

                            if (isset($_POST['msg_type']) && !empty($_POST['msg_type'])) {
                                $data['Msg_type'] = $_POST['msg_type'];
                            }
                            if (isset($_POST['preview_div']) && !empty($_POST['preview_div'])) {
                                $preview_div = $_POST['preview_div'];
                            }else{
                                $preview_div = "default";
                            }
                            if (isset($_POST['uploadType']) && !empty($_POST['uploadType'])) {
                                $uploadType = $_POST['uploadType'];
                            }else{
                                $uploadType = "postFile";
                            }
                            if (isset($_POST['msgid']) && !empty($_POST['msgid'])) {
                                $data['Msg_id'] = $_POST['msgid'];
                            }else{
                                $data['Msg_id'] = "";
                            }
                            $last_id = $db->insert('chat_media',$data);
                            if (isset($_POST['action'])) {
                                $data['id'] = $last_id;
                                array_push($resArr, $data);
                            }

                            ob_flush();
                            ob_start();
                            //var_dump($_FILES);
                            //var_dump($_POST);
                            file_put_contents("dump.txt", ob_get_flush());


                            //$thumbnail = thumbnail($targetFile,$thumbnailName);

                            //move_uploaded_file($thumbnailName, $targetThumbnail);


                        } else {
                            $resArr = array (
                                'status' => 'error',
                                'info'   => 'Couldn\'t upload the requested file :(, a mysterious error happend.'
                            );
                        }

                    } else {
                        // A file with the same name is already here
                        $resArr = array (
                            'status'    => 'error',
                            'info'      => 'A file with the same name is exists.',
                            'file_link' => $targetFile
                        );
                    }

                } else {
                    $resArr = array (
                        'status' => 'error',
                        'info'   => 'The specified folder for upload isn\'t writeable.'
                    );
                }
            } else {
                $resArr = array (
                    'status' => 'error',
                    'info'   => 'No folder to upload to :(, Please create one.'
                );
            }

            // Return the response
            // echo json_encode($response);
        }

    }
    $resultArr = array (
        'status' => "success",
        'data' => $resArr,
        'flag' => 3,
        'uploadType' => $uploadType,
        'preview_div' => $preview_div
    );
    echo json_encode($resultArr);
}
    


// Remove file
if( $delete_file == 1 ){
    function removeFromString($item, $str) {
        $items = explode(',', $item);

        while(($i = array_search($str, $items)) !== false) {
            unset($items[$i]);
        }

        return implode(',', $items);
    }

    $imageId = $_POST['delete_file_id'];
    $db->where('Id',$imageId);
    $db->delete('chat_media');

    if ($_POST['delete_file_type'] == 1){
        if(isset($_POST['delete_file_msg_id'])){
            $db->where('Id',$_POST['delete_file_msg_id']);
            $image = $db->getOne('thread');
            $data_item = $image['upload_post_id'];
            $updateArr = removeFromString($data_item, $_POST['delete_file_id']);
            $data_new = array(
                    "upload_post_id"       => $updateArr
                ); 
            $db->where('Id',$_POST['delete_file_msg_id']);
            $result = $db->update('thread', $data_new);
        }
    }else{
        $db->where('Id',$_POST['delete_file_msg_id']);
        $image = $db->getOne('thread_message');
        $data_item = $image['upload_id'];
        $updateArr = removeFromString($data_item, $_POST['delete_file_id']);
        $data_new = array(
                "upload_id"       => $updateArr
            ); 
        // echo "<pre>";print_r($data_new);exit;
        $db->where('Id',$_POST['delete_file_msg_id']);
        $result = $db->update('thread_message', $data_new);
    }

    // $db->where('Id',$imageId);
    // $db->delete('media');


    //echo dirname(__FILE__);

    $file_path = $_POST['target_file'];

    //echo $file_path;
    //unlink($file_path);
    //echo "herro";

    // Check if file is exists
    if ( file_exists($file_path) ) {

        // Delete the file
        unlink($file_path);

        // Be sure we deleted the file
        if ( !file_exists($file_path) ) {
            $response = array (
                'status' => 'success',
                'info'   => 'Successfully Deleted.'
            );
        } else {
            // Check the directory's permissions
            $response = array (
                'status' => 'error',
                'info'   => 'We screwed up, the file can\'t be deleted.'
            );
        }
    } else {
        // Something weird happend and we lost the file
        $response = array (
            'status' => 'error',
            'info'   => 'Couldn\'t find the requested file :('
        );
    }

    header('location: /chat');

    // Return the response
    echo json_encode($response);
}

?>