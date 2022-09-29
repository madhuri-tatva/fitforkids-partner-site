<?php

include("../../includes/config.php");
header('Content-type: text/plain; charset=utf-8');

if(isset($_GET)){

    $usersMatch = array();

    if($team != 0){

        $db->where('TeamId',$team);
        $usersMatch = $db->get('users');

    }

    $usersByTeam = array();
    foreach($usersMatch as $user){

        $usersByTeam[$user['TeamId']][] = $user['Id'];

    }

    // Counseling
    // $counseling = $db->get('counseling');

    // $counselingSorted = array();

    // foreach($counseling as $item){

    //     if(isset($counselingSorted[$item['FamilyId']])){

    //         if($counselingSorted[$item['FamilyId']] < $item['Date']){

    //             $counselingSorted[$item['FamilyId']] = $item['Date'];

    //         }

    //     }else{

    //         $counselingSorted[$item['FamilyId']] = $item['Date'];

    //     }

    // }

/*
    $dateFrom = date('Y-m-d',strtotime(strtr($_GET['DateFrom'], '/', '-')));
    $dateTo = date('Y-m-d',strtotime(strtr($_GET['DateTo'], '/', '-')));
    $type = $_GET['Type'];

    if(!empty($_GET['DateFrom']) && !empty($_GET['DateFrom'])){

        $db->where('Date',$dateFrom,'>=');
        $db->where('Date',$dateTo,'<=');

    }


    $stats = $db->get('stats');

    $statsSorted = array();
    foreach($stats as $stat){

        if($team != 0){
            if(in_array($stat['UserId'], $usersByTeam[$team])){
                $statsSorted[] = $stat;
            }
        }else{
            $statsSorted[] = $stat;
        }

    }


    if(!empty($_GET['DateFrom']) && !empty($_GET['DateFrom'])){

        $db->where('Date',$dateFrom,'>=');
        $db->where('Date',$dateTo,'<=');

    }

    $meetups = $db->get('meetup');
    $meetupsSorted = array();
    foreach($meetups as $meetup){

        $meetupsSorted[$meetup['UserId']][] = $meetup['Date'];

    }
*/

    // ALL TEAMS
    $teams = $db->get('teams');
    $teamsSorted = array();
    // $teamsSorted[0] = array(
    //     "Name" => "Intet hold"
    // );
    // foreach($teams as $team){

    //     $teamsSorted[$team['Id']] = $team;

    // }

    $teamsByTrainers = array();

    foreach($teams as $team){

        $trainers = explode(',',$team['Trainers']);

        if($trainers){
            foreach($trainers as $trainer){

                $teamsByTrainers[$trainer][$team['Id']] = $team;

            }
        }

    }


    // ALL USERS
    $db->where('Type',1);
    $db->orderBy('Firstname','ASC');
    $users = $db->get('users');
    // echo "<pre>";print_r($users);exit;

    $usersSorted[0] = array(
        "Firstname" => "",
        "Lastname" => ""
    );

    $usersSorted = array();
    // $usersSortedParents = array();
    // $usersSortedChildren = array();
    // foreach($users as $user){

    //     $usersSorted[$user['Id']] = $user;

    //     if($user['ParentId'] == 0){

    //         $usersSortedParents[$user['Id']] = $user;

    //     }else{

    //         $usersSortedChildren[$user['FamilyId']][$user['Id']] = $user;

    //     }

    // }

    // $usersRelevant = array();

        // Sort

        $arrayHeader = array(
            "Fornavn",
            "Efternavn",
            "E-mail",
            "Telefonnummer",
            "Personalegruppe",
            "Hold",
            "Antal træninger",
            "CPR-nr.",
            "Børneattest"
        );

        // USER ARRAY
        $arrayStats = array();
        $arrayStats[] = $arrayHeader;
        // echo "<pre>";print_r($users);exit;
        foreach($users as $user){

            if($user['EmployeeGroup'] == 1){
                $employeeGroup = "Administration";
            }elseif($user['EmployeeGroup'] == 2){
                $employeeGroup = "Teamleder";
            }elseif($user['EmployeeGroup'] == 3){
                $employeeGroup = "Instruktør";
            }elseif($user['EmployeeGroup'] == 4){
                $employeeGroup = "Coach";
            }elseif($user['EmployeeGroup'] == 0 && $user['is_instructor']==1){
                $employeeGroup = "Instruktør afventer godkendelse";
            }
            if($user['child_certificate']==1){
                $child_certificate = 'X';
            }else{
                $child_certificate = '';
            }
            $cpr_number = $user['CPR_Nr'];
            $db->where('InstructorId',$user['Id']);
            $coinstructors = $db->get('coinstructor');

            $coinstructorSorted = array();
            foreach($coinstructors as $coinstructor){
                $user_id = $coinstructor['UserId'];

                $coinstructorSorted[$coinstructor['UserId']][] = $coinstructor['Date'];

            }
            // echo "<pre>";print_r($coinstructorSorted);
            if(isset($coinstructorSorted[$user_id])){
                $coinstructorCount = count($coinstructorSorted);
            }else{
                $coinstructorCount = 0;
            }
            $teamsSorted = array();
            if(isset($teamsByTrainers[$user['Id']])){
                foreach($teamsByTrainers[$user['Id']] as $team){
                    $teamsSorted[$user['Id']][] = $team['Name'];
                }
            }
            $teamName = '';
            if (!empty($teamsSorted)) {
                $teamName = implode('-', $teamsSorted[$user['Id']]);
            }else{
                $teamName = "Intet hold";
            }
            // echo "<pre>";print_r($teamsSorted);
            // echo "<pre>";print_r($teamName);
            // foreach($teamsByTrainers[$user['Id']] as $team){
            //         echo $team['Name']."<br>";

            //     // $teamsSorted = implode(',', $team['Name']);

            // }
            // echo "<pre>";print_r($user['TeamId']);
            // echo "/n";
            // echo "<pre>";print_r($teamsSorted[$user['TeamId']]['Name']);
            // echo "<br/>";
            // $children = '';
            // if(isset($usersSortedChildren[$user['FamilyId']])){

            //     foreach($usersSortedChildren[$user['FamilyId']] as $child){

            //         $children .= $child['Firstname'] . ', ';

            //     }

            // }

            // $children = substr($children, 0, -2);


                $arrayStats[] = array(
                    $user['Firstname'],
                    $user['Lastname'],
                    $user['Email'],
                    $user['PhoneNumber'],
                    $employeeGroup,
                    $teamName,
                    $coinstructorCount,
                    $cpr_number,
                    $child_certificate
                );


        }
        // echo "<pre>";print_r($arrayStats);exit;

        /*
        foreach($statsSortedNewest as $stat){

            if(isset($usersSorted[$stat['UserId']])){

                $arrayStats[] = array(
                    $usersSorted[$stat['UserId']]['Firstname'] . " " . $usersSorted[$stat['UserId']]['Lastname'],
                    $stat['Weight'],
                    $stat['Height'],
                    $stat['Fatpercentage'],
                    $stat['BMI'],
                    $stat['Workout1'],
                    $stat['Workout2'],
                    $stat['Workout3'],
                    $stat['Workout4']
                );

            }

        }
        */
        // exit;
        array_to_csv_download($arrayStats);

}

?>