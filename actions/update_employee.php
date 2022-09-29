<?php
include("../includes/config.php");


if ($_POST) {
    $db->where('user_id', $_POST['UserId']);
    $db->orderBy('id','DESC');
    $measurementDetails = $db->getOne('user_measurement_details');

    $db->where('Id',$_POST['UserId']);
    $thisUser = $db->getOne('users');

    // $usercheck = usercheck($_POST['Email']);
    if($thisUser['EmployeeGroup'] == 3){
        $usercheck = instructor_usercheck(trim($_POST['Email']));
    }elseif($thisUser['EmployeeGroup'] == 0 && $thisUser['is_instructor']==1){
        $usercheck = instructor_usercheck(trim($_POST['Email']));
    }else{
        $usercheck = usercheck(trim($_POST['Email']));
    }
    if(($usercheck == true) && ($usercheck['Email'] != $_POST['Email'])){
        echo "<div class='alert error'>" . _('The e-mail') . " <strong>" . $_POST['Email'] .  "</strong> " . _('already exist. Try again with another e-mail address') . "</div>";

    }else{

        $id = $_POST['UserId'];

        if(!empty($_POST['Age'])){

            $age = date('Y-m-d',strtotime(strtr($_POST['Age'], '/', '-')));

        }else{

            $age = $thisUser['Age'];

        }



        if(empty($_POST['Password'])){

            $data = array(
                "Firstname"             => $_POST['Firstname'],
                "Lastname"              => $_POST['Lastname'],
                "PartnersiteTitle" => $_POST['PartnersiteTitle'],
                "OrganisationName" => $_POST['OrganisationName'],
                "Prefix" => $_POST['Prefix'],
                "PhoneNumber"           => $_POST['PhoneNumber'],
                "Address"               => $_POST['Address'],
                "Zipcode"               => $_POST['Zipcode'],
                "City"                  => $_POST['City'],
                "Region"                => $_POST['Municipality'],
                "State"                 => $_POST['Region'],
                // "Age"                   => $age,
                "Goal"                  => $_POST['Goal'],
                // "Gender"                => $_POST['Gender'],
                "Phrase"                => $_POST['Phrase'],
                "Interests"                => $_POST['Interests'],
                "Description"           => $_POST['Description'],
                "TeamRequest"           => $_POST['TeamRequest'],
                // "Email"                 => $_POST['Email'],
                // "join_month"            => $_POST['join_month'],
                // "join_year"             => $_POST['join_year'],
            );

        }else{

            $password = doubleSalt($_POST['Password']);
            $data = array(
                "Firstname"             => $_POST['Firstname'],
                "Lastname"              => $_POST['Lastname'],
                "PasswordHash"          => $password,
                "PhoneNumber"           => $_POST['PhoneNumber'],
                "Address"               => $_POST['Address'],
                "Zipcode"               => $_POST['Zipcode'],
                "City"                  => $_POST['City'],
                "Region"                => $_POST['Municipality'],
                "State"                 => $_POST['Region'],
                "Age"                   => $age,
                "Goal"                  => $_POST['Goal'],
                "Gender"                => $_POST['Gender'],
                "Phrase"                => $_POST['Phrase'],
                "Interests"                => $_POST['Interests'],
                "Description"           => $_POST['Description'],
                "TeamRequest"           => $_POST['TeamRequest'],
                // "Email"                 => $_POST['Email'],
                "join_month"            => $_POST['join_month'],
                "join_year"             => $_POST['join_year'],
            );

        }
        if (isset($_POST['CPR_Nr']) && !empty($_POST['CPR_Nr'])) {
            $data['CPR_Nr'] = $_POST['CPR_Nr'];
        }
        $array_check = input_array_check($data);

        if($array_check > 0){
            echo "<script type='text/javascript'>addAlert(3,'". _('Something went wrong. Try again.') . "');</script>";
        }else{

            $db->where('Id', $id);
            $db->update('users', $data);

            if($_POST['SendMail'] == 1){

                $secret = randomPassword();

                $data = array (
                    'Email' => $_POST['Email'],
                    'Secret' => $secret,
                    'CreateDate' => $db->now()
                    );

                $db->insert('generate_password', $data);

                $array = array(
                    "firstname" => $_POST['Firstname'],
                    "lastname" => $_POST['Lastname'],
                    "email" => $_POST['Email'],
                    "secret" => $secret
                );

            }



        }


        // Update family
        $db->where('FamilyId',$thisUser['FamilyId']);
        $family = $db->get('users');

        foreach($family as $user){

            $data = array(
                "TeamRequest" => $_POST['TeamRequest']
            );

            $db->where('Id',$user['Id']);
            $db->update('users',$data);

        }
        if($thisUser['is_instructor']==1 || $thisUser['EmployeeGroup']>0){
            // echo '0';  // redirect to edit-user page if it is instructor/coach/admin/teamleader
             echo '2';  // redirect to my-inspiration page if it is instructor/coach/admin/teamleader
        }else{
            if(empty($measurementDetails)){
                echo '1';  // redirect to measurement questionnaire page
            // }elseif($measurementDetails['set_measurements']==5){
            // echo '0';  // redirect to edit-user page $thisUser['TeamId']==0 &&
            }else{
                echo '2';  // redirect to my-inspiration  page
            }
        }

    }

}


?>
