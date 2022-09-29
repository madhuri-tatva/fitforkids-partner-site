<?php
include("../../includes/config.php");

if(isset($_POST) && !empty($_POST)){
    $userId = $_POST['user_id'];
    $type = $_POST['type'];
    $data = $_POST['data'];

    if(!empty($userId) && !empty($type) && !empty($data)){
        //Check measuremnt is alreay stored

        $db->where('user_id', $userId);
        $db->orderBy('id','DESC');
        $measurementData = $db->getOne('user_measurement_details');

        if(!empty($measurementData) && $measurementData['set_measurements'] != 5 && $type > 1){
            //update
            if($type == 1){
                $updateData = array(
                    "measurement_data_1" => json_encode($data),
                    "set_measurements" => $type,
                    "version" => 3,
                    "updated_at" => $db->now()
                );
            }elseif($type == 2){
                $updateData = array(
                    "measurement_data_2" => json_encode($data),
                    "set_measurements" => $type,
                    "version" => 3,
                    "updated_at" => $db->now()
                );
            }elseif($type == 3){
                $updateData = array(
                    "measurement_data_3" => json_encode($data),
                    "set_measurements" => $type,
                    "version" => 3,
                    "updated_at" => $db->now()
                );
            }elseif($type == 4){
                $updateData = array(
                    "measurement_data_4" => json_encode($data),
                    "set_measurements" => $type,
                    "version" => 3,
                    "updated_at" => $db->now()
                );
            }elseif($type == 5){
                $updateData = array(
                    "measurement_data_5" => json_encode($data),
                    "set_measurements" => $type,
                    "version" => 3,
                    "updated_at" => $db->now()
                );
            }
            $db->where('user_id', $userId);
            $db->where('id',$measurementData['id']);
            $db->update('user_measurement_details', $updateData);
        }else{
            //insert
            $insertData = array(
                "user_id" => $userId,
                "set_measurements" => $type,
                "version" => 3,
                "created_at" => $db->now()
            );
            if($type == 1){
                $insertData['measurement_data_1'] = json_encode($data);
            }elseif($type == 2){
                $insertData['measurement_data_2'] = json_encode($data);
            }elseif($type == 3){
                $insertData['measurement_data_3'] = json_encode($data);
            }elseif($type == 4){
                $insertData['measurement_data_4'] = json_encode($data);
            }elseif($type == 5){
                $insertData['measurement_data_5'] = json_encode($data);
            }

            $db->insert('user_measurement_details', $insertData);
        }
        echo 1;

    }else{
        echo 0;
    }
}
?>