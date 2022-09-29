<?php
include("../../includes/config.php");

$itemType = $_POST['Type'];
$itemId = $_POST['Id'];
$destination = '';

if($itemType == 1){

	$db->where('Id',$itemId);
	$deletingUser = $db->getOne('users');

	if($_SESSION['Admin'] == 1){

		$db->where('Id',$itemId);
		$db->delete('users');
		if(isset($deletingUser['FamilyId'])) {
			if(!empty($deletingUser['FamilyId']) && ($deletingUser['FamilyId'] == $_SESSION['FamilyId'])){
				$destination = 'edit-user/'.$_SESSION['UserId'];
			}else{
				$destination = 'admin-users';
			}
		}

	}else{

		$db->where('Id',$_SESSION['UserId']);
		$thisUser = $db->getOne('users');


		$db->where('Id',$itemId);
		$deletingUser = $db->getOne('users');

		if($thisUser['FamilyId'] == $deletingUser['FamilyId']){

			$db->where('Id',$itemId);
			$db->delete('users');

		}

		$destination = 'edit-user/'.$_SESSION['UserId'];

	}
	$db->where('child_id', $itemId);
	$db->delete('child_profile_access_rights');


}elseif($itemType == 2){

	$db->where('Id',$itemId);
	$db->delete('teams');
	$destination = 'admin';

}elseif($itemType == 3){

	$db->where('Id',$itemId);
	$db->delete('products');
	$destination = 'admin-categories';

}elseif($itemType == 4){

	$db->where('Id',$_SESSION['UserId']);
		$thisUser = $db->getOne('users');


		$db->where('Id',$itemId);
		$deletingUser = $db->getOne('users');
		
		$db->where('Id',$itemId);
		$db->delete('users');


		$destination = 'admin-trainers';

}

echo $destination;


?>