<?php
include("../../includes/config.php");

if(isset($_GET)){

    // Team
    $team = $_GET['Team'];

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
    $users = $db->get('users');

    $usersSorted[0] = array(
        "Firstname" => "",
        "Lastname" => ""
    );
    $usersSorted = array();
    foreach($users as $user){

        $usersSorted[$user['Id']] = $user;

    }

    $usersRelevant = array();

    //var_dump($stats);

    if($type == 1){

        // Sort

        $statsSortedNewest = array();
        $statsSortedFirst = array();

        foreach($statsSorted as $stat){


            if(isset($statsSortedNewest[$stat['UserId']])){

                if($statsSortedNewest[$stat['UserId']]['Date'] < $stat['Date']){

                    $statsSortedNewest[$stat['UserId']] = $stat;

                }

                //$statsSortedNewest[$stat['UserId']]['Meetup']++;
                $usersRelevant[$stat['UserId']] = $stat['UserId'];

            }else{

                $statsSortedNewest[$stat['UserId']] = $stat;
                //$statsSortedNewest[$stat['UserId']]['Meetup'] = 1;
                $usersRelevant[$stat['UserId']] = $stat['UserId'];

            }



            if(isset($statsSortedFirst[$stat['UserId']])){

                if($statsSortedFirst[$stat['UserId']]['Date'] > $stat['Date']){

                    $statsSortedFirst[$stat['UserId']] = $stat;

                }

            }else{

                $statsSortedFirst[$stat['UserId']] = $stat;

            }

        }

        $arrayHeader = array(
            "Navn",
            "Fødselsdagsdato",
            "Hold",
            "Navn på forældre",
            "Fremmøde",
            "Start dato",
            "Start vægt",
            "Start højde",
            "Start fedtprocent",
            "Start BMI",
            "Start Z score",
            "Start Englehop",
            "Start Armbøjninger",
            "Start Mavebøjninger",
            "Start Løb",
            "Seneste dato",
            "Seneste vægt",
            "Seneste højde",
            "Seneste fedtprocent",
            "Seneste BMI",
            "Seneste Z score",
            "Seneste Englehop",
            "Seneste Armbøjninger",
            "Seneste Mavebøjninger",
            "Seneste Løb",
            "Seneste Kostvejledning",
            "FitforKids",
            "Mål"
        );

        // USER ARRAY
        $arrayStats = array();
        $arrayStats[] = $arrayHeader;

        foreach($usersRelevant as $user){

            if(isset($usersSorted[$user])){

                if(isset($usersSorted[$user]['ParentId'])){
                    $parentId = $usersSorted[$user]['ParentId'];
                }else{
                    $parentId = 0;
                }
                
                if(isset($usersSorted[$parentId])){
                    $parentName = $usersSorted[$parentId]['Firstname'] . " " . $usersSorted[$parentId]['Lastname'];
                }else{
                    $parentName = '';
                }

                if(isset($meetupsSorted[$user])){
                    $meetCount = count($meetupsSorted[$user]);
                }else{
                    $meetCount = 0;
                }

                if(isset($usersSorted[$user]['ChildType'])){
                    $childType = $usersSorted[$user]['ChildType'];
                    if($childType == 1){
                        $childType = 'FitforKids barn';
                    }elseif($childType == 2){
                        $childType = 'FitforKids søskende';
                    }else{
                        $childType = '';
                    }
                }else{
                    $childType = '';
                }

                if(isset($usersSorted[$user]['GoalType'])){

                    $goalType = '';
                    $goalTypes = explode(',',$usersSorted[$user]['GoalType']);
                    
                    if(isset($goalTypes[0])){
                        if($goalTypes[0] == 1){
                            $goalType .= 'Tabe sig';
                        }
                    }
                    
                    if(isset($goalTypes[1])){
                        if($goalTypes[1] == 1){
                            $goalType .= ', I bedre form';
                        }
                    }

                    if(isset($goalTypes[2])){
                        if($goalTypes[2] == 1){
                            $goalType .= ', Få flere venner';
                        }
                    }

                    if(isset($goalTypes[3])){
                        if($goalTypes[3] == 1){
                            $goalType .= ', Alle sammen';
                        }
                    }

                    if(isset($goalTypes[4])){
                        if($goalTypes[4] == 1){
                            $goalType .= ', (Ikke noget, er søskende/med som støtte)';
                        }
                    }
                }else{
                    $goalType = '';
                }

                if(isset($counselingSorted[$usersSorted[$user]['FamilyId']])){
                    $latestCounseling = date('d-m-Y',strtotime($counselingSorted[$usersSorted[$user]['FamilyId']]));
                }else{
                    $latestCounseling = '';
                }

                if($statsSortedFirst[$user]['Zscore'] == 'NaN'){
                    $statsSortedFirst[$user]['Zscore'] = '';
                }
                
                if($statsSortedNewest[$user]['Zscore'] == 'NaN'){
                    $statsSortedNewest[$user]['Zscore'] = '';
                }
                

                $arrayStats[] = array(
                    $usersSorted[$user]['Firstname'] . " " . $usersSorted[$user]['Lastname'],
                    date('d-m-Y',strtotime($usersSorted[$user]['Age'])),
                    $teamsSorted[$usersSorted[$user]['TeamId']]['Name'],
                    $parentName,
                    $meetCount,
                    date('d-m-Y',strtotime($statsSortedFirst[$user]['Date'])),
                    $statsSortedFirst[$user]['Weight'],
                    $statsSortedFirst[$user]['Height'],
                    $statsSortedFirst[$user]['Fatpercentage'],
                    $statsSortedFirst[$user]['BMI'],
                    $statsSortedFirst[$user]['Zscore'],
                    $statsSortedFirst[$user]['Workout1'],
                    $statsSortedFirst[$user]['Workout2'],
                    $statsSortedFirst[$user]['Workout3'],
                    $statsSortedFirst[$user]['Workout4'],
                    date('d-m-Y',strtotime($statsSortedNewest[$user]['Date'])),
                    $statsSortedNewest[$user]['Weight'],
                    $statsSortedNewest[$user]['Height'],
                    $statsSortedNewest[$user]['Fatpercentage'],
                    $statsSortedNewest[$user]['BMI'],
                    $statsSortedNewest[$user]['Zscore'],
                    $statsSortedNewest[$user]['Workout1'],
                    $statsSortedNewest[$user]['Workout2'],
                    $statsSortedNewest[$user]['Workout3'],
                    $statsSortedNewest[$user]['Workout4'],
                    $latestCounseling,
                    $childType,
                    $goalType
                    // $coinstructorCount
                );

            }

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

    }else{

        $arrayHeader = array(
            "Navn",
            "Fødselsdagsdato",
            "Hold",
            "Navn på forældre",
            "Fremmøde",
            "Dato",
            "Vægt",
            "Højde",
            "Fedtprocent",
            "BMI",
            "Z score",
            "Englehop",
            "Armbøjninger",
            "Mavebøjninger",
            "Løb",
            "Seneste Kostvejledning",
            "FitforKids",
            "Mål",
        );

        // USER ARRAY
        $arrayStats = array();
        $arrayStats[] = $arrayHeader;

        foreach($statsSorted as $stat){

            if(isset($usersSorted[$stat['UserId']])){

                $user = $stat['UserId'];

                if(isset($usersSorted[$user]['ParentId'])){
                    $parentId = $usersSorted[$user]['ParentId'];
                }else{
                    $parentId = 0;
                }
                
                if(isset($usersSorted[$parentId])){
                    $parentName = $usersSorted[$parentId]['Firstname'] . " " . $usersSorted[$parentId]['Lastname'];
                }else{
                    $parentName = '';
                }

                if(isset($meetupsSorted[$user])){
                    $meetCount = count($meetupsSorted[$user]);
                }else{
                    $meetCount = 0;
                }


                if(isset($usersSorted[$user]['ChildType'])){
                    $childType = $usersSorted[$user]['ChildType'];
                    if($childType == 1){
                        $childType = 'FitforKids barn';
                    }elseif($childType == 2){
                        $childType = 'FitforKids søskende';
                    }else{
                        $childType = '';
                    }
                }else{
                    $childType = '';
                }

                if(isset($usersSorted[$user]['GoalType'])){

                    $goalType = '';
                    $goalTypes = explode(',',$usersSorted[$user]['GoalType']);
                    
                    if(isset($goalTypes[0])){
                        if($goalTypes[0] == 1){
                            $goalType .= 'Tabe sig';
                        }
                    }
                    
                    if(isset($goalTypes[1])){
                        if($goalTypes[1] == 1){
                            $goalType .= ', I bedre form';
                        }
                    }

                    if(isset($goalTypes[2])){
                        if($goalTypes[2] == 1){
                            $goalType .= ', Få flere venner';
                        }
                    }

                    if(isset($goalTypes[3])){
                        if($goalTypes[3] == 1){
                            $goalType .= ', Alle sammen';
                        }
                    }

                    if(isset($goalTypes[4])){
                        if($goalTypes[4] == 1){
                            $goalType .= ', (Ikke noget, er søskende/med som støtte)';
                        }
                    }
                    

                }else{
                    $goalType = '';
                }

                if(isset($counselingSorted[$usersSorted[$user]['FamilyId']])){
                    $latestCounseling = date('d-m-Y',strtotime($counselingSorted[$usersSorted[$user]['FamilyId']]));
                }else{
                    $latestCounseling = '';
                }

                if($stat['Zscore'] == 'NaN'){
                    $stat['Zscore'] = '';
                }
                

                $arrayStats[] = array(
                    $usersSorted[$user]['Firstname'] . " " . $usersSorted[$user]['Lastname'],
                    date('d-m-Y',strtotime($usersSorted[$user]['Age'])),
                    $teamsSorted[$usersSorted[$user]['TeamId']]['Name'],
                    $parentName,
                    $meetCount,
                    date('d-m-Y',strtotime($stat['Date'])),
                    $stat['Weight'],
                    $stat['Height'],
                    $stat['Fatpercentage'],
                    $stat['BMI'],
                    $stat['Zscore'],
                    $stat['Workout1'],
                    $stat['Workout2'],
                    $stat['Workout3'],
                    $stat['Workout4'],
                    $latestCounseling,
                    $childType,
                    $goalType
                );


            }

        }

        array_to_csv_download($arrayStats);

    }

}

?>