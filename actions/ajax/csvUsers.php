<?php

include("../../includes/config.php");
header('Content-type: text/plain; charset=utf-8');

if(isset($_GET)){

    $monthDay = array(
        1 => "Januar",
        2 => "Februar",
        3 => "Marts",
        4 => "April",
        5 => "Maj",
        6 => "Juni",
        7 => "Juli",
        8 => "August",
        9 => "September",
        10 => "Oktober",
        11 => "November",
        12 => "December"
    );

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
    $counseling = $db->get('counseling');

    $counselingSorted = array();

    foreach($counseling as $item){

        if(isset($counselingSorted[$item['FamilyId']])){

            if($counselingSorted[$item['FamilyId']] < $item['Date']){

                $counselingSorted[$item['FamilyId']] = $item['Date'];

            }

        }else{

            $counselingSorted[$item['FamilyId']] = $item['Date'];

        }

    }

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
    $teamsSorted[0] = array(
        "Name" => "Intet hold"
    );
    foreach($teams as $team){

        $teamsSorted[$team['Id']] = $team;

    }

    // ALL USERS
    $db->where('Type', 0);
    $users = $db->get('users');

    $usersSorted[0] = array(
        "Firstname" => "",
        "Lastname" => ""
    );
    $usersSorted = array();
    $usersSortedParents = array();
    $usersSortedChildren = array();
    $usersSortedPartner = array();
    foreach($users as $user){

        $usersSorted[$user['Id']] = $user;

        if(($user['ParentId'] == 0)){
            $usersSortedParents[$user['Id']] = $user;
        }else if(($user['ParentId'] != 0 && $user['is_partner'] == 1)){
            $usersSortedPartner[$user['FamilyId']][$user['Id']] = $user;
        }else{
            if($user['is_partner'] == 0)
                $usersSortedChildren[$user['FamilyId']][$user['Id']] = $user;
        }
    }

    $usersRelevant = array();

        // Sort

        $arrayHeader = array(
            "Fornavn",
            "Efternavn",
            "Kundetype",
            "Børn",
            "Telefon",
            "E-mail",
            "Hold",
            "Kommune",
            "Opstartsdato",
            "Partner"
        );

        // USER ARRAY
        $arrayStats = array();
        $arrayStats[] = $arrayHeader;

        foreach($usersSortedParents as $user){
            $children = '';
            $partnerName = '';
            if(isset($usersSortedChildren[$user['FamilyId']])){
                foreach($usersSortedChildren[$user['FamilyId']] as $child){
                    $partnerChild = array();
                    if(isset($child['ParentId_2']) && !empty($child['ParentId_2'])){
                        if(strpos($child['ParentId_2'], ',')){
                            $partnerChild = explode(',',$child['ParentId_2']);
                        }else{
                            array_push($partnerChild, $child['ParentId_2']);
                        }

                    }

                    if($child['ParentId'] == $user['Id'] || in_array($user['Id'], $partnerChild)){
                        $children .= $child['Firstname'] . ', ';
                    }
                }

            }
            if(isset($usersSortedPartner[$user['FamilyId']])){
                foreach($usersSortedPartner[$user['FamilyId']] as $partner){
                    $partnerName .= $partner['Firstname'] . ', ';
                }
            }

            $children = substr($children, 0, -2);
            $partnerName = substr($partnerName, 0, -2);

                $joinDate = '';
                if(!empty($user['join_month']) && !empty($user['join_year'])){
                    $joinDate = $monthDay[$user['join_month']]. ' '.$user['join_year'];
                }

                if($user['special_page_access'] == 1){
                    $special_page_access = "Body";
                }elseif($user['special_page_access'] == 2){
                    $special_page_access = "Body+Mind";
                }elseif($user['special_page_access'] == 3){
                    $special_page_access =  "Body+Mind+All";
                }else{$special_page_access = '';}

                $arrayStats[] = array(
                    $user['Firstname'],
                    $user['Lastname'],
                    $special_page_access,
                    $children,
                    $user['PhoneNumber'],
                    $user['Email'],
                    $teamsSorted[$user['TeamId']]['Name'],
                    $user['Region'],
                    $joinDate,
                    $partnerName
                );


        }

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


        array_to_csv_download($arrayStats);

}

?>