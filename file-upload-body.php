<?php
require_once("includes/config.php");

/**
 * Dropzone PHP file upload/delete
 */

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
$userID = 0;
if(isset($_POST['userID'])){
    $userID = $_POST['userID'];
}


$dateYear = date('Y');
$dateMonth = sprintf("%02d", date('m'));

$targetPath = dirname( __FILE__ ) . '/uploads/body/';
$targetUrl = '/uploads/body/';


$targetThumbPath = dirname( __FILE__ ) . '/uploads/body/';
$targetThumbUrl = '/uploads/body/';

// Check if it's an upload or delete and if there is a file in the form
if (!empty($_FILES) && $delete_file == 0 ) {
    // $countfiles = count($_FILES['file']['name']);
    $resArr  = array();
    // echo "<pre>";print_r("here");exit;

    // Looping all files
    // for($i=0;$i<$countfiles;$i++){

        // Check if it's an upload or delete and if there is a file in the form
        if ( !empty($_FILES) && $delete_file == 0 ) {

            // Check if the upload folder is exists
            if ( file_exists($targetPath) && is_dir($targetPath) ) {

                // Check if we can write in the target directory
                if ( is_writable($targetPath) ) {
                    //$randomNumber = mt_rand(100000, 999999);
                    $randomNumber = '';
                    if (isset($_POST['mtype'])) {
                        $mtype = $_POST['mtype'];
                     }else{
                         $mtype =  '';
                     }
                     if($mtype=='Media'){
                            $ext = 'M';
                     }elseif($mtype=='Thumbnail'){
                            $ext = 'T';
                     }else{
                        $ext = 'N';
                     }
                    $tempFile = $_FILES['file']['tmp_name'];
                    $file_name = $_FILES["file"]["name"];
                    $fileType = $_FILES["file"]["type"];
                    $array = explode('.', $file_name);
                    $fileName= preg_replace('/[^A-Za-z0-9\-]/','',str_replace(' ','',$array[0]));
                    $fileExt=$array[1];
                    $newfile=$fileName."_".time().$ext.".".$fileExt;

                    $targetFile = $targetPath . $newfile;
                    $targetFileUrl = $targetUrl . $newfile;

                // if(!empty($_FILES['thumbnail']['name'])){
                //     $thumbnail_tempFile = $_FILES['thumbnail']['tmp_name'];
                //     $thumbnail_file_name = $_FILES["thumbnail"]["name"];
                //     $thumbnail_fileType = $_FILES["thumbnail"]["type"];
                //     $array1 = explode('.', $thumbnail_file_name);
                //     $thumbnail_fileName= preg_replace('/[^A-Za-z0-9\-]/','',str_replace(' ','',$array1[0]));
                //     $thumbnail_fileExt=$array[1];
                //     $thumbnail_newfile=$thumbnail_fileName."_t".time().".".$thumbnail_fileExt;

                //     $thumbnail_targetFile = $targetPath . $thumbnail_newfile;
                //     $thumbnail_targetFileUrl = $targetUrl . $thumbnail_newfile;
                // }else{
                //     $thumbnail_targetFileUrl = '';
                //     $thumbnail_targetFileUrl = '';
                //     $thumbnail_targetFile = '';
                // }
                    $thumbnailName = $newfile;
                    $targetThumbnail = $targetThumbPath . $newfile;

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
                    if (!file_exists($targetFile) ) {

                        // Upload the file
                        move_uploaded_file($tempFile, $targetFile);
                        // if(!file_exists($thumbnail_targetFile) && $thumbnail_targetFile !='' ){
                        //     move_uploaded_file($tempFile, $thumbnail_targetFile);

                        // }
                        // Be sure that the file has been uploaded
                        if ( file_exists($targetFile) ) {

                            $response = array (
                                'status'    => 'success',
                                'file_link' => $targetFile
                            );


                            $data  = array(
                                'Type' => $mtype,
                                'MediaType' => $fileType,
                                'URL' => $targetFileUrl,
                                'FileName' => $newfile,
                                'UserID' => $userID,
                                'CreateDate' => $db->now()
                            );

                            $last_id = $db->insert('body_media_temp',$data);
                            if (isset($_POST['action'])) {
                                $data['id'] = $last_id;
                                array_push($resArr, $data);
                            }

                            ob_flush();
                            ob_start();
                            //var_dump($_FILES);
                            //var_dump($_POST);
                            // file_put_contents("dump.txt", ob_get_flush());


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

   // }
    $resultArr = array (
        'status' => "success",
        'data' => $resArr
    );
    echo json_encode($resultArr);
    exit;
}


// Remove file
if( $delete_file == 1 ){

    // $imageId = $_POST['delete_file_id'];

    // $db->where('ID',$temp['ID']);
    // $db->delete('body_media_temp');


    //echo dirname(__FILE__);

    $file_path = 'uploads/body/'.$_POST['target_file'];
   // Check if file is exists
    if (file_exists($file_path) ) {
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

    // header('location: /body/nutrition');

    // Return the response
    echo json_encode($response);
    exit;
}
// Remove file
if( $delete_file == 2){

    $imageId = $_POST['delete_file_id'];

    if(!empty($imageId) && $imageId>0){
        $db->where('ID',$imageId);
        $media = $db->getOne('body_media');
        $mediaid = $media['body_media_id'];
        $db->where('ID',$imageId);
        $db->delete('body_media');
        if(!empty($media)){
            $file_path = ltrim($media['Media'], '/');
            $file_thumbnail_path = ltrim($media['Thumbnail'], '/');
        }else{
            $file_path = ltrim($_POST['target_file'],'/');
            $file_thumbnail_path = ltrim($_POST['target_thumbnail'],'/');
        }

        if (file_exists($file_thumbnail_path) ) {
            unlink($file_thumbnail_path);
        }
    // Check if file is exists
        if (file_exists($file_path) ) {

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
    }
    
    // header('location: /body/training');
    $redirection = "/body/training?".rand();
    header('location: '.$redirection);

    /*if(!empty($media) && $mediaid>0){
        header("location: /training-videos/$mediaid");
    }if(!empty($media) && $media['SubType']==3){
         header('location: /body/standard-training-program');
    }elseif(!empty($media) && $media['Type']==2){
         header('location: /body/training');
    }else{
        header('location: /body/nutrition');

    }*/
    // Return the response
    //echo json_encode($response);
    exit;
}
