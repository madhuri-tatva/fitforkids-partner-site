<?php
include("../../../includes/config.php");
        if(!isset($_SESSION['userid'])){
                        $_SESSION['errormsg'] = 'error2';
                        header("Location:".$basehref."/login");
        }

   //              status: status,
  //                repid:repid

changestatuswithmailandsms($_REQUEST['status'], $_REQUEST['repid']);

?>