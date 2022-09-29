<?php
include("../../includes/config.php");



if(isset($_POST)){
    if($_POST['Action'] == 1){
        if($_POST['EmployeeGroup'] == 3){
            $mail_exist = instructor_usercheck(trim($_POST['Email']));
        }elseif($_POST['EmployeeGroup'] == 0 && $_POST['is_instructor']==1){
            $mail_exist = instructor_usercheck(trim($_POST['Email']));
        }else{
            $mail_exist = usercheck(trim($_POST['Email']));
        }
        if($mail_exist == true){
            $mail_exist = 1;
            exit;
        }else{
            $mail_exist = 0;
        }
        // CREATE
        // echo "<pre>";print_r($_POST);exit;
        if(!empty($_POST['Password'])){
            $password = doubleSalt($_POST['Password']);
        }else{
            $password = '';
        }


        if(!empty($_POST['Age'])){

            $age = date('Y-m-d',strtotime(strtr($_POST['Age'], '/', '-')));

        }else{

            $age = 0;

        }

        if($_POST['EmployeeGroup'] == 1){
            $admin = 1;
        }else{
            $admin = 0;
        }

        if($_POST['EmployeeGroup'] == 0){
            $type = 0;
        }else{
            $type = 1;
        }

        $data = array(
            "Active"        => 1,
            "Type"          => $type,
            "Firstname"     => $_POST['Firstname'],
            "Lastname"      => $_POST['Lastname'],
            "Email"         => str_replace(' ','',$_POST['Email']),
            "PhoneNumber"   => $_POST['PhoneNumber'],
            "Address"       => $_POST['Address'],
            "City"          => $_POST['City'],
            "Zipcode"       => $_POST['Zipcode'],
            "Age"           => $age,
            "Gender"        => $_POST['Gender'],
            "TeamId"        => $_POST['Team'],
            "EmployeeGroup" => $_POST['EmployeeGroup'],
            "special_page_access" => (isset($_POST['special_page_access'])) ? $_POST['special_page_access'] : 0,
            "Region"        => $_POST['Municipality'],
            "State"         => $_POST['Region'],
            "Admin"         => $admin,
            "PasswordHash"  => $password,
            "CPR_Nr" => $_POST['CPR_Nr'],
            "child_certificate" => $_POST['child_certificate'],
            "CreateDate"    => $db->now()
        );

        $last_id = $db->insert('users', $data);

        $db->where('Id',$_POST['Team']);
        $teamData       = $db->getOne('teams');
        $teamName 		= $teamData['Name'];
	    $teamLeader 	= $teamData['UserId'];
	    $teamTrainers 	= $teamData['Trainers'];
        if($_POST['EmployeeGroup'] == 3 || $_POST['EmployeeGroup'] == 2){
            if($teamTrainers == ''){

                $teamTrainers = $last_id;

            }else{

                // $teamTrainers = $teamTrainers;
                $teamTrainersExploded = explode(',',$teamTrainers);
                array_push($teamTrainersExploded, $last_id);
                $teamTrainers = implode(",",$teamTrainersExploded);
            }
            $data_new = array(
                "UserId" => $teamLeader,
                "Trainers" => $teamTrainers,
                "Name" => $teamName
            );
            $db->where('Id',$_POST['Team']);
            $db->update('teams', $data_new);
        }else{
            if($teamTrainers == ''){

                $teamTrainers = '';
                $data_new = array();

            }else{
                $teamTrainersExploded = explode(',',$teamTrainers);
                if(in_array($newTrainer,$teamTrainersExploded)){
                    $trainerId = array_search($newTrainer, $teamTrainersExploded);
                    unset($teamTrainersExploded[$trainerId]);
                }
                $teamTrainers = implode(",",$teamTrainersExploded);

            }
            $data_new = array(
                "UserId" => $teamLeader,
                "Trainers" => $teamTrainers,
                "Name" => $teamName
            );
            $db->where('Id',$_POST['Team']);
            $db->update('teams', $data_new);

        }

        exit;


    }elseif($_POST['Action'] == 2){

        // UPDATE
        // echo "<pre>";print_r($_POST);exit;
        if($_POST['CurrentEmail']!==trim($_POST['Email'])){
            // $mail_exist = usercheck(trim($_POST['Email']));
            if($_POST['EmployeeGroup'] == 3){
                $mail_exist = instructor_usercheck(trim($_POST['Email']));
            }elseif($_POST['EmployeeGroup'] == 0 && $_POST['is_instructor']==1){
                $mail_exist = instructor_usercheck(trim($_POST['Email']));
            }else{
                $mail_exist = usercheck(trim($_POST['Email']));
            }
            if($mail_exist == true){
                $mail_exist = 1;
                echo 'Email Already Exist';
                exit;
            }else{
                $mail_exist = 0;
            }
        }
        if(!empty($_POST['Age'])){

            $age = date('Y-m-d',strtotime(strtr($_POST['Age'], '/', '-')));

        }else{

            $age = 0;

        }

        if($_POST['EmployeeGroup'] == 1){
            $admin = 1;
        }else{
            $admin = 0;
        }

        if($_POST['EmployeeGroup'] == 0){
            if($_POST['is_instructor']==1){
                $type=1;
            }else{
                $type = 0;
            }
       }else{
           $type = 1;
       }

        if($_POST['EmployeeGroup'] == 2){
            $Personal_channel = $_POST['Personal_channel'];
            $Common_channel = $_POST['Common_channel'];
        }else{
            $Personal_channel = 0;
            $Common_channel = 0;
        }
        $db->where('Id',$_POST['UserId']);
        $thisUser= $db->getOne('users');
        if(isset($_POST['special_page_access']) && $_POST['special_page_access'] !=''){
            $special_page_access = $_POST['special_page_access'];
        }else{
            $special_page_access = (!empty($thisUser)) ? $thisUser['special_page_access']: 0;
        }

        if(isset($_POST['password'])){

            if($_POST['password']){
                $password = doubleSalt($_POST['password']);

                $data = array(
                    "Active"        => 1,
                    "Type"          => $type,
                    "Firstname"     => $_POST['Firstname'],
                    "Lastname"      => $_POST['Lastname'],
                    "Email"         => str_replace(' ','',$_POST['Email']),
                    "PhoneNumber"   => $_POST['PhoneNumber'],
                    "Address"       => $_POST['Address'],
                    "City"          => $_POST['City'],
                    "Zipcode"       => $_POST['Zipcode'],
                    "Age"           => $age,
                    "Gender"        => $_POST['Gender'],
                    "TeamId"        => $_POST['Team'],
                    "EmployeeGroup" => $_POST['EmployeeGroup'],
                    "special_page_access" => $special_page_access,
                    "Admin"         => $admin,
                    "PasswordHash"  => $password,
                    "Personal_channel" => $Personal_channel,
                    "Common_channel" => $Common_channel,
                    "CPR_Nr" => $_POST['CPR_Nr'],
                    "child_certificate" => $_POST['child_certificate'],
                    "CreateDate"    => $db->now()
                );

            }

        }else{

            $data = array(
                "Active"        => 1,
                "Type"          => $type,
                "Firstname"     => $_POST['Firstname'],
                "Lastname"      => $_POST['Lastname'],
                "Email"         => str_replace(' ','',$_POST['Email']),
                "PhoneNumber"   => $_POST['PhoneNumber'],
                "Address"       => $_POST['Address'],
                "City"          => $_POST['City'],
                "Zipcode"       => $_POST['Zipcode'],
                "Age"           => $age,
                "Gender"        => $_POST['Gender'],
                "TeamId"        => $_POST['Team'],
                "EmployeeGroup" => $_POST['EmployeeGroup'],
                "special_page_access" => $special_page_access,
                "Admin"         => $admin,
                "Personal_channel" => $Personal_channel,
                "Common_channel" => $Common_channel,
                "CPR_Nr" => $_POST['CPR_Nr'],
                "child_certificate" => $_POST['child_certificate'],
                "CreateDate"    => $db->now()
            );

        }

        // echo "<pre>";print_r($data);exit;
        $db->where('Id',$_POST['UserId']);
        $db->update('users', $data);

        $teamId = $_POST['Team'];
        $thisFamilyId = $thisUser['FamilyId'];
        if($thisFamilyId != 0){
            $db->where('FamilyId',$thisFamilyId);
            // $db->where('is_partner', 0);
            $children = $db->get('users');
            $team_id_data = array(
                "TeamId" => $teamId
            );
            if(isset($children)){
                foreach($children as $child){
                    $db->where('Id',$child['Id']);
                    $db->update('users',$team_id_data);

                }
            }
        }

        $db->where('Id',$_POST['Team']);
        $teamData       = $db->getOne('teams');
        $teamName       = $teamData['Name'];
        $teamLeader     = $teamData['UserId'];
        $teamTrainers   = $teamData['Trainers'];
        $newTrainer   = $_POST['UserId'];

        if($_POST['EmployeeGroup'] == 3 || $_POST['EmployeeGroup'] == 2){

            if($teamTrainers == ''){
                $teamTrainers = $newTrainer;
            }else{

                $teamTrainersExploded = explode(',',$teamTrainers);
                array_push($teamTrainersExploded, $newTrainer);
                $teamTrainers = implode(",",$teamTrainersExploded);
            }
            $data_new = array(
                "UserId" => $teamLeader,
                "Trainers" => $teamTrainers,
                "Name" => $teamName
            );
            $db->where('Id',$_POST['Team']);
            $db->update('teams', $data_new);

        }else{
            if($teamTrainers == ''){
                $teamTrainers = '';

            }else{
                $teamTrainersExploded = explode(',',$teamTrainers);
                if(in_array($newTrainer,$teamTrainersExploded)){
                    $trainerId = array_search($newTrainer, $teamTrainersExploded);
                    unset($teamTrainersExploded[$trainerId]);
                }
                $teamTrainers = implode(",",$teamTrainersExploded);

            }
            $data_new = array(
                "UserId" => $teamLeader,
                "Trainers" => $teamTrainers,
                "Name" => $teamName
            );
            $db->where('Id',$_POST['Team']);
            $db->update('teams', $data_new);


        }


        exit;

    }elseif($_POST['Action'] == 3){

        // DELETE
        exit;

    }

}

?>