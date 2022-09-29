<?php
include("../../includes/config.php");

if(isset($_POST)){

    $teamMembers    = explode(',',$_POST['TeamMembers']); 
    $teamId         = $_POST['TeamId']; 

    $db->where('TeamId',$teamId);
    $currentTeamMembers = $db->get('users');

    $currentTeamMembersSorted = array();

    foreach($currentTeamMembers as $currentTeamMember){

        $currentTeamMembersSorted[] = $currentTeamMember['Id'];

    }

    $teamMembersMerged = array_merge($teamMembers,$currentTeamMembersSorted);
    $teamMembersMerged = array_unique($teamMembersMerged);



    $allUsers = $db->get('users');

    $allUsersSorted = array();

    foreach($allUsers as $user){

        $allUsersSorted[$user['Id']] = $user;

    }


    foreach($teamMembersMerged as $teamMember){

        echo $teamMember;
        $thisFamilyId = $allUsersSorted[$teamMember]['FamilyId'];

        if(in_array($teamMember,$teamMembers)){

            $data = array(
                "TeamId" => $teamId
            );

            $db->where('Id',$teamMember);
            $db->update('users',$data);

            // Update Family

            if($thisFamilyId != 0){

                $db->where('FamilyId',$thisFamilyId);
                $children = $db->get('users');
                if(isset($children)){
                    foreach($children as $child){

                        $db->where('Id',$child['Id']);
                        $db->update('users',$data);

                    }
                }

            }

        }else{

            $data = array(
                "TeamId" => 0
            );

            $db->where('Id',$teamMember);
            $db->update('users',$data);

            // Update Family

            if($thisFamilyId != 0){

                $db->where('FamilyId',$thisFamilyId);
                $children = $db->get('users');
                if(isset($children)){
                    foreach($children as $child){

                        $db->where('Id',$child['Id']);
                        $db->update('users',$data);

                    }
                }

            }

        }

    }

    //var_dump($teamMembersMerged);

}

?>