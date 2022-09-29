<?php
include("../../includes/config.php");

if(isset($_POST)){

    if($_POST['Action'] == 1){
         // Insert /Update Coachee Diary notes
         $coach_id = (isset($_POST['coach_id'])) ? $_POST['coach_id']: '';
         $coachee_id = (isset($_POST['coachee_id'])) ? $_POST['coachee_id'] : '';
         $cover_page = (isset($_POST['cover_page'])) ? $_POST['cover_page']: '';
         $cover_image = (isset($_POST['cover_image'])) ? $_POST['cover_image']: '';
         $page_number = (isset($_POST['page_number'])) ? $_POST['page_number'] : '';
         $diary_date = (isset($_POST['diary_date'])) ? $_POST['diary_date'] : '';
         $notes = $_POST['notes'];
         $diary_id = (isset($_POST['diary_id'])) ? $_POST['diary_id']  : 0;
         if($diary_id>0 && !empty($notes)){
             $db->where('id',$diary_id);
             $existing_note = $db->getOne('coachee_diary');
             if($existing_note){
                 $coachee_arr = array(
                     "coach_id" => $coach_id,
                     "coachee_id" => $coachee_id,
                     "notes" => $notes,
                     // "cover_page" => $cover_page,
                     // "page_number" => $page_number,
                     "date" =>  date('Y-m-d',strtotime($diary_date)),
                     "updated_at" => $db->now(),
                 );
                 $db->where('id',$existing_note['id']);
                 $db->update('coachee_diary', $coachee_arr);
                 echo 'update';
             }else{echo 'error';}
         }elseif($coach_id>=0 && !empty($coachee_id) && !empty($cover_page) && !empty($page_number) && !empty($cover_image) && !empty($notes)){
             $db->where('coach_id',$coach_id);
             $db->where('coachee_id',$coachee_id);
             $db->where('cover_page',$cover_page);
             $db->where('page_number',$page_number);
             $existing_note = $db->getOne('coachee_diary');
             if($existing_note){
                 $coachee_arr = array(
                     "coach_id" => $coach_id,
                     "coachee_id" => $coachee_id,
                     "notes" => $notes,
                     // "cover_page" => $cover_page,
                     // "page_number" => $page_number,
                     // "date" =>   date('Y-m-d'),
                     "updated_at" => $db->now(),
                 );
                 $db->where('id',$existing_note['id']);
                 $db->update('coachee_diary', $coachee_arr);
                 echo 'update';
             }else{
                 $coachee_arr = array(
                     "coach_id" => $coach_id,
                     "coachee_id" => $coachee_id,
                     "notes" => $notes,
                     "cover_page" => $cover_page,
                     "cover_image" => $cover_image,
                     "page_number" => $page_number,
                     "date" =>  ($diary_date) ? date('Y-m-d',strtotime($diary_date)) : date('Y-m-d'),
                     "created_at" => $db->now(),
                 );
                 $db->insert('coachee_diary', $coachee_arr);
                 echo 'insert' ;
             }
         }else{echo 'values empty';}
         exit;
    }elseif($_POST['Action'] == 2){
        // UPDATE
        $coach_id = $_POST['coach_id'];
        if($coach_id>0 && !empty($_POST['coachee_ids'])){
            $existing_coach_ids = null;
            $existing_coachee_ids = null;
            $new_coachee_ids_arr  = explode(',',$_POST['coachee_ids']);
            $db->where('Id',$coach_id);
            $coach_user = $db->getOne('users');
            $existing_coachee_ids = $coach_user['coachee_ids'];
            $coachee_data = array(
                "coachee_ids" => $_POST['coachee_ids'],
            );
            $db->where('Id',$coach_id);
            $db->update('users', $coachee_data);
            if(!empty($existing_coachee_ids)){
                $existing_coachee_ids_arr = explode(',', $existing_coachee_ids);

                $coacheeIDsMerged = array_merge($new_coachee_ids_arr,$existing_coachee_ids_arr);
                $coacheeIDsMerged = array_unique($coacheeIDsMerged);
                foreach($coacheeIDsMerged as $coacheeID){
                    $db->where('Id',$coacheeID);
                    $coachee_user = $db->getOne('users');
                    $existing_coach_ids = $coachee_user['coach_ids'];
                    if(in_array($coacheeID,$new_coachee_ids_arr)){
                        if(!empty($existing_coach_ids)){
                            $existing_coach_ids_arr = explode(',', $existing_coach_ids);
                            if(!in_array($coach_id, $existing_coach_ids_arr)){
                                $new_coach_ids = $existing_coach_ids.','.$coach_id;
                                $coach_data = array(
                                    "coach_ids" => $new_coach_ids,
                                );
                                $db->where('Id',$coacheeID);
                                $db->update('users', $coach_data);
                            }
                        }else{
                            $coach_data = array(
                                        "coach_ids" => $coach_id,
                                     );
                            $db->where('Id',$coacheeID);
                            $db->update('users', $coach_data);
                        }
                    }else{
                        if(!empty($existing_coach_ids)){
                            $existing_coach_ids_arr = explode(',', $existing_coach_ids);
                            $new_coach_ids = [];
                            foreach ($existing_coach_ids_arr as $v) {
                                if($v!=$coach_id){
                                    array_push($new_coach_ids, $v);
                                }
                            }
                                $data_arr = array(
                                    "coach_ids" => ($new_coach_ids) ? rtrim(implode(',',$new_coach_ids),',') : NULL
                                );
                                $db->where('Id',$coacheeID);
                                $db->update('users', $data_arr);
                        }else{
                            $data_arr = array(
                                            "coach_ids" => NULL,
                                        );
                            $db->where('Id',$coacheeID);
                            $db->update('users', $data_arr);
                        }
                    }
                }
            }
        }
        exit;
    }elseif($_POST['Action'] == 3){
        // DELETE recorded video
        $video_id = $_POST['video_id'];
        // $coach_id = $_POST['coach_id'];
        // $coachee_id = $_POST['coachee_id'];
        // && $coachee_id>0
        if($video_id>0){
            $db->where('id',$video_id);
            $video = $db->getOne('training_videos');
            if($video){
                $video_file =  $_SERVER['DOCUMENT_ROOT'] .'/uploads/coachvidoes/'.$video['video_file'];
                if(file_exists($video_file)){
                    unlink($video_file);
                }
                $db->where('id',$video_id);
                // $db->where('coach_id',$coach_id);
                // $db->where('coachee_id',$coachee_id);
                $db->delete('training_videos');
            }
        }
        exit;

    }

}

?>