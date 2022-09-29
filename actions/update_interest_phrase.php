<?php
include("../includes/config.php");


if ($_POST) {
    $db->where('Id',$_POST['UserId']);
    $thisUser = $db->getOne('users');

      if(!empty($thisUser)){      

        $id = $_POST['UserId'];
        if(isset($_POST['Interests']) && $_POST['Action']==1) {
            $data['Interests'] = $_POST['Interests'];
            $db->where('Id', $id);
            $db->update('users', $data);
            echo 1;
        }
        if(isset($_POST['Phrase']) && $_POST['Action']==2) {
            $data['Phrase'] = $_POST['Phrase'];
            $db->where('Id', $id);
            $db->update('users', $data);
            echo 1;
        }  
      }else{
        echo 0;
      }

}
exit();

?>
