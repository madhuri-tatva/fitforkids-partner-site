<?php
include("../includes/config.php");

if(isset($_POST)){
    if (isset($_POST['is_instructor']) && $_POST['is_instructor']==1) {
        $mail_exist = instructor_usercheck(trim($_POST['Email']));
        if($mail_exist == true){
            $mail_exist = 1;
            echo 'E-mail-id already registered as an instructor.';
            exit;
        }
    }else{
        $mail_exist = usercheck(trim($_POST['Email']));
        if($mail_exist == true){
            $mail_exist = 1;
            echo 'E-mail-id already registered';
            exit;
        }
    }
    if($_POST['Firstname'] != '' or $_POST['Email'] != '' or $_POST['PhoneNumber'] != ''){
    	// Create user
        $password = doubleSalt($_POST['Password']);
        $data = array(
            "Active"        => 1,
            "Firstname"     => $_POST['Firstname'],
            "Lastname"      => $_POST['Lastname'],
            "PartnersiteTitle"      => $_POST['Title'],
            "OrganisationName" => $_POST['OrganisationName'],
            "Email"         => trim($_POST['Email']),
            "PhoneNumber"   => $_POST['PhoneNumber'],
            "Region"        => $_POST['Region'],
            "is_verify"     => 0,
            "special_page_access" => 1,
            "PasswordHash"  => $password,
            "CreateDate"    => $db->now()
        );
        if (isset($_POST['EmployeeGroup']) && !empty($_POST['EmployeeGroup']) ) {
            $data['EmployeeGroup'] = $_POST['EmployeeGroup'];
            $data['Type'] = 1;
        }
        if (isset($_POST['is_instructor']) && !empty($_POST['is_instructor']) ) {
            $data['is_instructor'] = $_POST['is_instructor'];
            $data['Type'] = 1;
        }
        if (isset($_POST['TeamId']) && !empty($_POST['TeamId']) ) {
            $data['TeamId'] = 0;
            $data['TeamRequest'] = $_POST['TeamId'];
        }
        $db->insert('users', $data);
        $userId = $db->getInsertId();

        if (!isset($_POST['EmployeeGroup'])) {
            // Create family
            $dataFamily = array(
                "UserId" 		=> $userId,
                "CreateDate"    => $db->now()
            );
            $db->insert('families', $dataFamily);
            $familyId = $db->getInsertId();
            // Update user
            $dataUser = array(
                "FamilyId"      => $familyId
            );
            $db->where('Id',$userId);
            $db->update('users',$dataUser);
        }
            $user_id = base64_encode($userId);
            $link = $basehref.'verify?token&'.$user_id;
    	//Mail
    	$arrayMail = array(
            "firstname" 	=> $_POST['Firstname'],
            "lastname"      => $_POST['Lastname'],
    	    "email" 		=> trim($_POST['Email']),
            "link" 		    => $link
    	);
        $arrayMailAdmin = array(
            "firstname"     => $_POST['Firstname'],
            "lastname"      => $_POST['Lastname'],
            "organisation"  => $_POST['OrganisationName'],
            "country"       => $_POST['Region'],            
            "phonenumber"   => $_POST['PhoneNumber'],
            "emailuser"     => $_POST['Email'],
            "region"        => $_POST['Region'],
            "email"         => "info@fitforkids.dk"
        );
        if(isset($_POST['is_instructor']) && $_POST['is_instructor']==1) {
            $team_name ='';
            if(isset($_POST['TeamId']) && !empty($_POST['TeamId']) ) {
                $db->where('Id',$_POST['TeamId']);
                $team_data = $db->getOne('teams');
                if($team_data){
                    $team_name = $team_data['Name'];
                }
            }
            $arrayInstMailAdmin = array(
                "firstname" => $_POST['Firstname'],
                "lastname" => $_POST['Lastname'],                
                "phonenumber" => $_POST['PhoneNumber'],
                "emailuser" => $_POST['Email'],
                "teamname" => $team_name,
                "email" => "info@fitforkids.dk"
            );
            send_mail("da_DK", "Register Instructor", $arrayMail);
            send_mail("da_DK", "Ny Instruktør", $arrayInstMailAdmin);
        }else{
            send_mail("da_DK","Register",$arrayMail);
            send_mail("da_DK","Ny bruger",$arrayMailAdmin);
        }
        echo "success";

    }

}
exit;
?>