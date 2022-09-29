<?php
include("../../includes/config.php");

if(isset($_POST)){

    if($_POST['action'] == 1){

        // CREATE

        $data = array(
            "Firstname"     => $_POST['firstname'],
            "Lastname"      => $_POST['lastname'],
            "PhoneNumber"   => $_POST['phonenumber'],
            "Email"         => $_POST['email'],
            "Address"       => $_POST['address'],
            "Zipcode"       => $_POST['zipcode'],
            "City"          => $_POST['city'],
            "Company"       => $_POST['company'],
            "VatNumber"     => $_POST['vat'],
            "ContactId"     => $_SESSION['UserId'],
            "CreateDate"    => $db->now()
        );

        $db->insert('customers', $data);

        exit;

    }elseif($_POST['action'] == 2){

        // UPDATE

        if(isset($_POST['customerId'])){

            $data = array(
                "Firstname"     => $_POST['firstname'],
                "Lastname"      => $_POST['lastname'],
                "PhoneNumber"   => $_POST['phonenumber'],
                "Email"         => $_POST['email'],
                "Address"       => $_POST['address'],
                "Zipcode"       => $_POST['zipcode'],
                "City"          => $_POST['city'],
                "Company"       => $_POST['company'],
                "VatNumber"     => $_POST['vat'],
            );

            $db->where('Id',$_POST['customerId']);
            $db->update('customers', $data);

        }
        exit;

    }elseif($_POST['action'] == 3){

        // DELETE
        exit;

    }

}

?>