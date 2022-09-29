<?php include("../../includes/config.php");




if(isset($_POST['action'])){



    if($_POST['action'] == 'calendarchangedefault'){

         $newid = $_POST['calendarid'];

    //Update in Session
         $_SESSION['adminchosenshop'] = $newid ;

    //Update in DB



    }


}