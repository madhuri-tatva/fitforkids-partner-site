<?php
include("../includes/config.php");


if (isset($_POST)) {
        $db->where('user_id',$_SESSION['UserId']);
        $thisUser = $db->getOne('user_inspiration');
            if($_POST['Action']==1 && isset($_FILES)){
                // Profile image insert-update
                if(!empty($thisUser)){
                        $targetPath = MAIN_PATH.'/assets/images/inspiration/profile/';
                        $targetUrl = 'assets/images/inspiration/profile/';
                        $tempFile = $_FILES['profile_image']['tmp_name'];
                        $file_name = $_FILES["profile_image"]["name"];
                        $fileType = $_FILES["profile_image"]["type"];
                        $array = explode('.', $file_name);
                        $fileName= preg_replace('/[^A-Za-z0-9\-]/','',str_replace(' ','',$array[0]));
                        $fileExt= $array[1];
                        $newfile= $fileName."_".time().".".$fileExt;

                        $targetFile = $targetPath.$newfile;
                        $targetFileUrl = $targetUrl.$newfile;
                        if(!file_exists($targetFile) ) {
                                // Upload the file
                                move_uploaded_file($tempFile, $targetFile);
                                $data  = array(

                                            'profile_image' => $targetFileUrl,
                                            'updated_at' => $db->now()
                                        );
                            $db->where('id',$thisUser['id']);
                            $db->update('user_inspiration',$data);
                            $old_file_path = $_SERVER['DOCUMENT_ROOT'].'/'.$thisUser['profile_image'];
                            if(file_exists($old_file_path)){

                                unlink($old_file_path);
                            }
                            echo 'update successfully';
                        }else{
                            echo 'Update error';
                        }

                }else{
                        $targetPath = MAIN_PATH. '/assets/images/inspiration/profile/';
                        $targetUrl = 'assets/images/inspiration/profile/';
                        $tempFile = $_FILES['profile_image']['tmp_name'];
                        $file_name = $_FILES["profile_image"]["name"];
                        $fileType = $_FILES["profile_image"]["type"];
                        $array = explode('.', $file_name);
                        $fileName= preg_replace('/[^A-Za-z0-9\-]/','',str_replace(' ','',$array[0]));
                        $fileExt= $array[1];
                        $newfile= $fileName."_".time().".".$fileExt;

                        $targetFile = $targetPath.$newfile;
                        $targetFileUrl = $targetUrl.$newfile;
                        if(!file_exists($targetFile) ) {
                                // Upload the file
                                move_uploaded_file($tempFile, $targetFile);
                                $data  = array(
                                            'user_id' =>$_SESSION['UserId'],
                                            'profile_image' => $targetFileUrl,
                                            'created_at' => $db->now()
                                        );
                                $last_id = $db->insert('user_inspiration',$data);
                                echo $last_id;
                        }else{
                            echo 'File exist, try again';
                        }
                }
            }elseif($_POST['Action']==2 && isset($_POST)){
                // colour code save
                 if(!empty($thisUser)){
                     $data  = array(
                                'colour1' => $_POST['color1'],
                                'colour2' =>  $_POST['color2'],
                                'updated_at' => $db->now()
                            );
                        $db->where('id',$thisUser['id']);
                        $db->update('user_inspiration',$data);
                        echo 1;
                 }else{
                     $data  = array(
                                'user_id' =>$_SESSION['UserId'],
                                'colour1' => $_POST['color1'],
                                'colour2' =>  $_POST['color2'],
                                'created_at' => $db->now()
                            );
                     $last_id=  $db->insert('user_inspiration',$data);
                     echo $last_id;
                 }
            }elseif($_POST['Action']==3 && isset($_POST) && isset($_POST['category_id'])>0 && isset($_POST['image'])){
                // colour code save
                $category_id = $_POST['category_id'];
                $image_path = $_POST['image'];
                if($category_id==1){
                        $data['inspiration_image1'] = $image_path;
                }elseif($category_id==2) {
                    $data['inspiration_image2'] = $image_path;
                }elseif($category_id==3) {
                    $data['inspiration_image3'] = $image_path;
                }elseif($category_id==4) {
                    $data['inspiration_image4'] = $image_path;
                }elseif($category_id==5) {
                    $data['inspiration_image5'] = $image_path;
                }elseif($category_id==6) {
                    $data['inspiration_image6'] = $image_path;
                }elseif($category_id==7) {
                    $data['inspiration_image7'] = $image_path;
                }elseif($category_id==8) {
                    $data['inspiration_image8'] = $image_path;
                }elseif($category_id==9) {
                    $data['inspiration_image9'] = $image_path;
                }elseif($category_id==10) {
                    $data['inspiration_image10'] = $image_path;
                }
                 if(!empty($thisUser)){
                        $data['updated_at']  =  $db->now();
                        $db->where('id',$thisUser['id']);
                        $db->update('user_inspiration',$data);
                        echo 1;
                 }else{
                     $data['user_id'] = $_SESSION['UserId'];
                     $data['created_at'] = $db->now();
                     $last_id=  $db->insert('user_inspiration',$data);
                     echo $last_id;
                 }
            }
}else{
    echo 'Post error';
}
exit();

?>
