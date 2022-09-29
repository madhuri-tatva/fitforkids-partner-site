<?php
include("../../includes/config.php");



if(isset($_POST)){

    if($_POST['Action'] == 1){

        // CREATE

        if(!empty($_POST['Date'])){

            $date = date('Y-m-d',strtotime(strtr($_POST['Date'], '/', '-')));

        }else{

            $date = date('Y-m-d');

        }

        $data = array(
            "UserId"            => $_POST['UserId'],
            "Date"              => $date,
            "Weight"            => $_POST['Weight'],
            "Height"            => $_POST['Height'],
            "Fatpercentage"     => $_POST['Fatpercentage'],
            "BMI"               => $_POST['BMI'],
            "Zscore"            => $_POST['Zscore'],
            "Fitnessgrad"       => $_POST['Fitnessgrad'],
            "Workout1"          => $_POST['Workout1'],
            "Workout2"          => $_POST['Workout2'],
            "Workout3"          => $_POST['Workout3'],
            "Workout4"          => $_POST['Workout4'],
            "Fatkilo"           => $_POST['Fatkilo'],
            "Squat"             => $_POST['Squat'],
            "CreateDate"        => $db->now()
        );

        $db->insert('stats', $data);

        exit;

    }elseif($_POST['Action'] == 2){

        // UPDATE

        if(!empty($_POST['Date'])){

            $date = date('Y-m-d',strtotime(strtr($_POST['Date'], '/', '-')));

            $data = array(
                "UserId"            => $_POST['UserId'],
                "Weight"            => $_POST['Weight'],
                "Height"            => $_POST['Height'],
                "Fatpercentage"     => $_POST['Fatpercentage'],
                "BMI"               => $_POST['BMI'],
                "Zscore"            => $_POST['Zscore'],
                "Fitnessgrad"       => $_POST['Fitnessgrad'],
                "Workout1"          => $_POST['Workout1'],
                "Workout2"          => $_POST['Workout2'],
                "Workout3"          => $_POST['Workout3'],
                "Workout4"          => $_POST['Workout4'],
                "Fatkilo"           => $_POST['Fatkilo'],
                "Squat"             => $_POST['Squat'],
                "Date"              => $date
            );

        }else{


            $data = array(
                "UserId"            => $_POST['UserId'],
                "Weight"            => $_POST['Weight'],
                "Height"            => $_POST['Height'],
                "Fatpercentage"     => $_POST['Fatpercentage'],
                "BMI"               => $_POST['BMI'],
                "Zscore"            => $_POST['Zscore'],
                "Fitnessgrad"       => $_POST['Fitnessgrad'],
                "Workout1"          => $_POST['Workout1'],
                "Workout2"          => $_POST['Workout2'],
                "Workout3"          => $_POST['Workout3'],
                "Workout4"          => $_POST['Workout4'],
                "Fatkilo"           => $_POST['Fatkilo'],
                "Squat"             => $_POST['Squat']
            );

        }
        $db->where('Id',$_POST['StatId']);
        $db->update('stats', $data);
            
        exit;

    }elseif($_POST['Action'] == 3){

        // DELETE
        $db->where('Id',$_POST['StatId']);
        $db->delete('stats');
        exit;

    }

}

?>