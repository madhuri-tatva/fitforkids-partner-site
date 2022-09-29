<?php 
include("../includes/config.php");

if ($_POST) {

    $language = getlanguage($_POST['Country']);

    $data = array(
        "Firstname"             => $_POST['Firstname'],
        "Lastname"              => $_POST['Lastname'],
        "Active"                => 1,
        "PhoneNumber"           => $_POST['PhoneNumber'],
        "Address"               => $_POST['Address'],
        "Zipcode"               => $_POST['Zipcode'],
        "City"                  => $_POST['City'],
        "Email"                 => $_POST['Email'],
        "Type"                  => $_POST['Type'],
        "Country"               => $_POST['Country'],
        "Language"              => $language,
        "SalaryId"              => $_POST['SalaryId'],
        //"SalaryBase"            => $_POST['SalaryBase'],
        "DepartmentId"          => $_SESSION['CurrentDepartment'],
        "CustomerId"            => $_SESSION['CustomerId'],
        "DashShiftlistSetting"  => $_SESSION['DashShiftlistSetting'],
        "Key"                   => rand(10,99)."-".rand(100,999)."-".rand(100,999),
        "CreateDate"            => $db->now()
    );


    $array_check = input_array_check($data);

    if($array_check > 0){
        echo "<script type='text/javascript'>addAlert(3,'". _('Something went wrong. Try again.') . "');</script>";
    }else{

        $id = $db->insert('users', $data);

        // Get User ID
        $user_id = $db->getInsertId();

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
         
        send_mail('da_DK',"New user",$array);

        $initials = generate_initials($user_id);

        $data = array(
            'Initials' => $initials
        );

        $db->where('Id',$user_id);
        $db->update('users',$data);

        logaction(25,0,0);
        
    }


}

?>
