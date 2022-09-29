<?php 
include("../../includes/config.php");

if(!isset( $_SESSION['UserId']))
{
    //expired
    echo "-1";
    session_destroy();
}
else
{
    //not expired
    echo "1";
}

?>