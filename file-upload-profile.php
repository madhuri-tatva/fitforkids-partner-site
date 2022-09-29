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


$dateYear = date('Y');
$dateMonth = sprintf("%02d", date('m'));

$targetPath = dirname( __FILE__ ) . '/uploads_profile/';
$targetUrl = '/uploads_profile/';


$targetThumbPath = dirname( __FILE__ ) . '/uploads_profile/thumb/';
$targetThumbUrl = '/uploads_profile/thumb/';


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

            $tempFile = $_FILES['file']['tmp_name'];


            $targetFile = $targetPath . $_FILES['file']['name'];
            $targetFileUrl = $targetUrl . $_FILES['file']['name'];

            $thumbnailName = $_FILES['file']['name'];
            $targetThumbnail = $targetThumbPath . $_FILES['file']['name'];



            $fileType = explode('.', $_FILES['file']['name']);
            $fileType = trim(end($fileType));


            // Path
            $targetFilePosition = strrpos($targetFile, '.');
            $targetFile = substr($targetFile, 0, $targetFilePosition) . '_' . $_SESSION['UserId'] . substr($targetFile, $targetFilePosition);
            $targetFile = substr($targetFile, 0, $targetFilePosition) . substr($targetFile, $targetFilePosition);


            // URL
            $targetFilePosition = strrpos($targetFileUrl, '.');
            $targetFileUrl = substr($targetFileUrl, 0, $targetFilePosition) . '_' . $_SESSION['UserId'] . substr($targetFileUrl, $targetFilePosition);
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

                    $response = array (
                        'status'    => 'success',
                        'file_link' => $targetFile
                    );

                    $data  = array(
                        'Avatar' => $targetFileUrl
                    );
                    $db->where('Id',$_SESSION['UserId']);
                    $db->update('users',$data);


                    ob_flush();
                    ob_start();
                    //var_dump($_FILES);
                    //var_dump($_POST);
                    file_put_contents("dump.txt", ob_get_flush());


                    //$thumbnail = thumbnail($targetFile,$thumbnailName);

                    //move_uploaded_file($thumbnailName, $targetThumbnail);


                } else {
                    $response = array (
                        'status' => 'error',
                        'info'   => 'Couldn\'t upload the requested file :(, a mysterious error happend.'
                    );
                }

            } else {
                // A file with the same name is already here
                $response = array (
                    'status'    => 'error',
                    'info'      => 'A file with the same name is exists.',
                    'file_link' => $targetFile
                );
            }

        } else {
            $response = array (
                'status' => 'error',
                'info'   => 'The specified folder for upload isn\'t writeable.'
            );
        }
    } else {
        $response = array (
            'status' => 'error',
            'info'   => 'No folder to upload to :(, Please create one.'
        );
    }

    // Return the response
    echo json_encode($response);
    exit;
}


// Remove file
if( $delete_file == 1 ){

    /*$imageId = $_POST['delete_file_id'];

    $db->where('Id',$imageId);
    $db->delete('media');*/

    $dataUser = array(
        "Avatar" => ""
    );

    $db->where('Id',$_POST['delete_file_userid']);
    $db->update('users',$dataUser);

    $file_path = $_POST['target_file'];

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
    header('location: /edit-user/'.$_POST['delete_file_userid']);
    exit;
}

?>