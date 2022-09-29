<?php
include("../../includes/config.php");

$targetPath =  MAIN_PATH. '/uploads/inbox/';
$target_dir = '/uploads/inbox/';
if(!is_dir($targetPath)) {
    mkdir($targetPath, 0777,true);
}
$response = array( 
    'status' => 0, 
    'message' => 'Something went wrong, please try again.' 
); 
$uploadedFile = '';
if(!empty($_FILES["attachment"]["name"])){ 
    $fileName = $time = date("dmY").time().basename($_FILES["attachment"]["name"]); 
    $targetFilePath  = $target_dir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath , PATHINFO_EXTENSION));

    $allowTypes = array('pdf', 'mp3'); 
    if(in_array($fileType, $allowTypes)){ 
        if(move_uploaded_file($_FILES["attachment"]["tmp_name"], MAIN_PATH.$targetFilePath)){ 
            $uploadedFile = $fileName; 
        } else {        
            $response['message'] = 'Sorry, there was an error uploading your file.'; 
        } 
    } else {
        $response['message'] = 'Sorry, PDf & mp3 files are allowed to upload.'; 
    }
}

$insertArr = array(   
    "parent_id" => 0,     
    "subject" => $_POST['subject'],
    "message" => $_POST['message'],
    "attachment" => $uploadedFile,
    "to_user" => $_POST['to_user'],
    "from_user" => $_SESSION['UserId'],
    "created_at" => $db->now(),
    "updated_at" => $db->now()
);  
$insert =  $db->insert('internal_mail', $insertArr);   
if($insert){ 
    $response['status'] = 1; 
    $response['message'] = 'Form data submitted successfully!'; 
} 

echo json_encode($response);

?>