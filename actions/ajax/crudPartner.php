<?php
include("../../includes/config.php");

if(isset($_POST)) { 
    $db->where('FamilyId',$_SESSION['FamilyId']);
    $db->where('ParentId',0);
    $parent = $db->getOne('users');
  
    $familyTeamId = $parent['TeamId'];
    $password = '';
    
    if($_POST['Action'] == 1){
        // echo '<pre>';
        // print_r($_POST);
        // exit;
        // CREATE                       
        // if(!empty($_POST['Age'])){
        //     $age = date('Y-m-d',strtotime(strtr($_POST['Age'], '/', '-')));
        // }else{
        //     $age = 0;
        // }  
        if(isset($_POST['isTerm']) && $_POST['isTerm'] != 0) {
            $plainPassword = '1234';
            $password = doubleSalt($plainPassword);
        }
        
        // $partnerRelation = '';
        // if(!empty($_POST['partnerRelation']) && $_POST['partnerRelation'] == 1) {
        //     $partnerRelation = 'Ægtefælle';
        // }else if(!empty($_POST['partnerRelation']) && $_POST['partnerRelation'] == 2){
        //     $partnerRelation = 'Samlever';
        // }else if(!empty($_POST['partnerRelation']) && $_POST['partnerRelation'] == 3){
        //     $partnerRelation = 'Kæreste';
        // }else if(!empty($_POST['partnerRelation']) && $_POST['partnerRelation'] == 4){
        //     $partnerRelation = 'Ekskone/Eksmand';
        // }else if(!empty($_POST['partnerRelation']) && $_POST['partnerRelation'] == 5){
        //     $partnerRelation = 'Andet';
        // }

        $data = array(
            "Active"        => 1,
            "Firstname"     => $_POST['Firstname'],
            "Lastname"      => $_POST['Lastname'],
            "PartnersiteTitle"      => $_POST['PartnersiteTitle'],
            "Prefix"      => $_POST['Prefix'],
            // "Age"           => $age,
            // "Gender"        => $_POST['Gender'],
            "email"     => $_POST['Email'],
            'PasswordHash' => $password,
            // "partnerRelation" => $partnerRelation,
            "ParentId"      => $_SESSION['UserId'],
            "is_partner" => 1,
            // "FamilyId"      => $_SESSION['FamilyId'],
            "TeamId"        => $familyTeamId,
            // "current_relation_with" => $_POST['current_relation'],
            "CreateDate"    => $db->now()
        );

        $insertedId = $db->insert('users', $data);
        $childrenNameList = ''; 

        if(isset($_POST['selectedChild']) && !empty($_POST['selectedChild'])){ 
                    
            foreach($_POST['selectedChild'] as $selectedChild){
                $valueAry = explode(':', $selectedChild);

                $db->where('is_partner', 0);
                $db->where('ParentId', $_SESSION['UserId']);
                $db->where('Id', $valueAry[0]);
                $partnerParent = $db->getOne('users');
                $parentId2Ary = array();

                if(!empty($partnerParent['ParentId_2'])){
                    $parentId2Ary['ParentId_2'] = $partnerParent['ParentId_2'].','.$insertedId;
                }else{
                    $parentId2Ary['ParentId_2'] = $insertedId;
                }

                $db->where('is_partner', 0);
                $db->where('ParentId', $_SESSION['UserId']);
                $db->where('Id', $valueAry[0]);
                $db->update('users', $parentId2Ary);

                if($childrenNameList == '')
                        $childrenNameList = 'og <b>'.$partnerParent['Firstname'].' '.$partnerParent['Lastname'];
                    else
                    $childrenNameList .= ', '.$partnerParent['Firstname'].' '.$partnerParent['Lastname'];
                
                    $insertAry = array(
                        'parent_id' => $insertedId,
                        'child_id' => $valueAry[0],
                        'access_right' => $valueAry[1],
                        'created_at' => $db->now()
                    );
                    $db->insert("child_profile_access_rights", $insertAry);
            }
        }

        $user_id = base64_encode($insertedId);
        
        // if(isset($_POST['isTerm']) && $_POST['isTerm'] != 0) {
            //Registration link email
            // $link = $basehref.'tilmelding?token&'.$user_id;
            $link = $basehref.'login?token&'.$user_id;
            

            $array = array(
                "colleaguefirstname" =>  $_POST['Firstname'],
                "colleaguelastname" =>   $_POST['Lastname'],
                "organisation" => $parent['OrganisationName'],
                "firstname" =>  $parent['Firstname'],
                "lastname" =>   $parent['Lastname'],
                // "userrelation" => $partnerRelation,
                // "childrennames" => $childrenNameList.'</b>',
                "email" =>  $_POST['Email'],
                "link" => $link,
                // 'userpasscode' => $plainPassword    
            ); 

            $arrayMailAdmin = array(
                "firstname"     => $_POST['Firstname'],
                "lastname"      => $_POST['Lastname'],
                "organisation"  => $parent['OrganisationName'],
                "country"       => $parent['Region'],            
                "phonenumber"   => $_POST['PhoneNumber'],
                "emailuser"     => $_POST['Email'],
                // "region"        => $_POST['Region'],
                "email"         => "info@fitforkids.dk"
            );

            send_mail('da_DK',"Partner Registration Request", $array); 
            send_mail('da_DK',"Ny partner bruger", $arrayMailAdmin); 
        // }
        echo 'Added';

    }elseif($_POST['Action'] == 2){  
        // echo '<pre>';
        // print_r($_POST);
        // exit;      
        // UPDATE

        // $partnerRelation = '';
        // if(!empty($_POST['partnerRelation']) && $_POST['partnerRelation'] == 1) {
        //     $partnerRelation = 'Ægtefælle';
        // }else if(!empty($_POST['partnerRelation']) && $_POST['partnerRelation'] == 2){
        //     $partnerRelation = 'Samlever';
        // }else if(!empty($_POST['partnerRelation']) && $_POST['partnerRelation'] == 3){
        //     $partnerRelation = 'Kæreste';
        // }else if(!empty($_POST['partnerRelation']) && $_POST['partnerRelation'] == 4){
        //     $partnerRelation = 'Ekskone/Eksmand';
        // }else if(!empty($_POST['partnerRelation']) && $_POST['partnerRelation'] == 5){
        //     $partnerRelation = 'Andet';
        // }

        // if(!empty($_POST['Age'])){
        //     $age = date('Y-m-d',strtotime(strtr($_POST['Age'], '/', '-')));
        // }else{
        //     $age = 0;
        // }
        //$age = date('Y-m-d',strtotime($_POST['Age']));
            $data = array(
                "Firstname"     => $_POST['Firstname'],
                "Lastname"      => $_POST['Lastname'],
                "PartnersiteTitle"      => $_POST['PartnersiteTitle'],
                "Prefix"      => $_POST['Prefix'],
                // "Age"           => $age,
                // "Gender"        => $_POST['Gender'],
                "Email"         => $_POST['Email'],                            
                // "partnerRelation" => $partnerRelation,
                // "current_relation_with" => $_POST['current_relation'],
            );
            

        // if(isset($_POST['selectedChild']) && !empty($_POST['selectedChild'])){ 
        //     $partnerIds = '';   
        //     foreach($_POST['selectedChild'] as $selectedChild){
        //         $valueAry = explode(':', $selectedChild);

        //         $db->where('parent_id', $_POST['UserId']);
        //         $db->where('child_id', $valueAry[0]);
        //         $isParentRightExists = $db->getOne('child_profile_access_rights');

        //         if(empty($isParentRightExists)){
        //             $insertAry = array(
        //                 'parent_id' => $_POST['UserId'],
        //                 'child_id' => $valueAry[0],
        //                 'access_right' => $valueAry[1],
        //                 'created_at' => $db->now()
        //             );
        //             $db->insert("child_profile_access_rights", $insertAry);
        //         }

        //         $db->where('is_partner', 0);
        //         $db->where('ParentId', $_SESSION['UserId']);
        //         $db->where('Id', $valueAry[0]);
        //         $partnerParent = $db->getOne('users');
        //         $parentId2Ary = array();

        //         if(!empty($partnerParent['ParentId_2'])){
        //             $parentId2Ary['ParentId_2'] = $partnerParent['ParentId_2'].','.$_POST['UserId'];
        //         }else{
        //             $parentId2Ary['ParentId_2'] = $_POST['UserId'];
        //         }

        //         $db->where('is_partner', 0);
        //         $db->where('ParentId', $_SESSION['UserId']);
        //         $db->where('Id', $valueAry[0]);
        //         $db->update('users', $parentId2Ary);
        //     }
        // }
        

        $db->where('Id',$_POST['UserId']);
        $db->update('users', $data);        
        echo 'Updated';
        exit;

    }elseif($_POST['Action'] == 3) {
        // DELETE
        exit;
    }

}

?>