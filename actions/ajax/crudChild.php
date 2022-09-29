<?php
include("../../includes/config.php");

if(isset($_POST)){
    
    $db->where('FamilyId',$_SESSION['FamilyId']);
    $db->where('ParentId',0);
    $parent = $db->getOne('users');

    
    $familyTeamId = $parent['TeamId'];

    if($_POST['Action'] == 1){

        // CREATE
        //$password = doubleSalt($_POST['password']);        
        if(!empty($_POST['Age'])){
            $age = date('Y-m-d',strtotime(strtr($_POST['Age'], '/', '-')));
        }else{
            $age = 0;
        }
        $data = array(
            "Active"        => 1,
            "Firstname"     => $_POST['Firstname'],
            "Lastname"      => $_POST['Lastname'],
            "Age"           => $age,
            "Gender"        => $_POST['Gender'],
            "ChildType"     => (isset($_POST['ChildType']) && !empty($_POST['ChildType'])) ? $_POST['ChildType'] : 0,
            "GoalType"      => $_POST['GoalType'],
            "ParentId"      => $_SESSION['UserId'],
            "FamilyId"      => $_SESSION['FamilyId'],
            "TeamId"        => $familyTeamId,
            "CreateDate"    => $db->now()
        );

        $insertedId = $db->insert('users', $data);

        $insertAry = array(
            'parent_id' => $_SESSION['UserId'],
            'child_id' => $insertedId,
            'access_right' => 2,
            'created_at' => $db->now()
        );
        $db->insert("child_profile_access_rights", $insertAry);

        if(isset($_POST['loggedinParentType']) && $_POST['loggedinParentType'] == 1){
            //partner - copy child to parent account directly
            if(isset($_POST['allowCopyChild']) && $_POST['allowCopyChild'] == 1){                
                $updateData['ParentId_2'] = $_POST['partnerParentUserId'];
                $db->where('Id', $insertedId);
                $db->update('users', $updateData);

                //inser access right
                $insertAry = array(
                    'parent_id' => $_POST['partnerParentUserId'],
                    'child_id' => $insertedId,
                    'access_right' => 2,
                    'created_at' => $db->now()
                );
                $db->insert("child_profile_access_rights", $insertAry);
            }
            
        }else{
            //parent
            //copy child to selected partner
            if(isset($_POST['selectedPartner']) && !empty($_POST['selectedPartner'])){ 
                $partnerIds = '';   
                foreach($_POST['selectedPartner'] as $value){
                    $valueAry = explode(':', $value);

                    if($partnerIds == '')
                        $partnerIds = $valueAry[0];
                    else
                        $partnerIds .= ','.$valueAry[0];

                    $insertAry = array(
                        'parent_id' => $valueAry[0],
                        'child_id' => $insertedId,
                        'access_right' => $valueAry[1],
                        'created_at' => $db->now()
                    );

                    $db->insert("child_profile_access_rights", $insertAry);
                }
              
                // $partnerIds = implode(',', $_POST['selectedPartner']);
                

                $updateData['ParentId_2'] = $partnerIds;
                $db->where('Id', $insertedId);
                $db->update('users', $updateData);
            }
        }
        exit;

    }elseif($_POST['Action'] == 2){
        // UPDATE      
        if(!empty($_POST['Age'])){
            $age = date('Y-m-d',strtotime(strtr($_POST['Age'], '/', '-')));
        }else{
            $age = 0;
        }

        //$age = date('Y-m-d',strtotime($_POST['Age']));
        if(isset($_POST['password'])){
            $password = doubleSalt($_POST['password']);

            $data = array(
                "Firstname"     => $_POST['Firstname'],
                "Lastname"      => $_POST['Lastname'],
                "Age"           => $age,
                "Gender"        => $_POST['Gender'],
                "ChildType"     => (isset($_POST['ChildType']) && !empty($_POST['ChildType'])) ? $_POST['ChildType'] : 0,
                "GoalType"      => $_POST['GoalType'],
                "PasswordHash"  => $password 
            );
        }else{
            $data = array(
                "Firstname"     => $_POST['Firstname'],
                "Lastname"      => $_POST['Lastname'],
                "Age"           => $age,
                "Gender"        => $_POST['Gender'],
                "ChildType"     => (isset($_POST['ChildType']) && !empty($_POST['ChildType'])) ? $_POST['ChildType'] : 0,
                "GoalType"      => $_POST['GoalType']
            );
        }
        $db->where('Id',$_POST['UserId']);
        $db->update('users', $data);

        $db->where('parent_id', $_SESSION['UserId']);
        $db->where('child_id', $_POST['UserId']);
        $isParentRightExists = $db->getOne('child_profile_access_rights');

        if(empty($isParentRightExists)){
            $insertAry = array(
                'parent_id' => $_SESSION['UserId'],
                'child_id' => $_POST['UserId'],
                'access_right' => 2,
                'created_at' => $db->now()
            );
            $db->insert("child_profile_access_rights", $insertAry);
        }

        //copy child to selected partner
        if(isset($_POST['selectedPartner']) && !empty($_POST['selectedPartner'])){ 
            $partnerIds = '';   
            foreach($_POST['selectedPartner'] as $value){
                $valueAry = explode(':', $value);

                if($partnerIds == '')
                    $partnerIds = $valueAry[0];
                else
                    $partnerIds .= ','.$valueAry[0];

                
                $db->where('parent_id', $valueAry[0]);
                $db->where('child_id', $_POST['UserId']);
                $isExists = $db->getOne('child_profile_access_rights');

                if(!empty($isExists)){
                    $updateAry = array(                        
                        'access_right' => $valueAry[1],
                        'updated_at' => $db->now()
                    );
                    $db->where('parent_id', $valueAry[0]);
                    $db->where('child_id', $_POST['UserId']);
                    $db->update("child_profile_access_rights", $updateAry);
                }else{
                    $insertAry = array(
                        'parent_id' => $valueAry[0],
                        'child_id' => $_POST['UserId'],
                        'access_right' => $valueAry[1],
                        'created_at' => $db->now()
                    );
                    $db->insert("child_profile_access_rights", $insertAry);
                }
            }
          
            // $partnerIds = implode(',', $_POST['selectedPartner']);
            

            $updateData['ParentId_2'] = $partnerIds;
            $db->where('Id', $_POST['UserId']);
            $db->update('users', $updateData);
        }
        exit;

    }elseif($_POST['Action'] == 3){

        // DELETE
        exit;

    }

}

?>