<?php
include("../../includes/config.php");

$itemType = $_POST['Type'];
$itemId = $_POST['Id'];
$isPartner = (isset($_POST['isPartner']) && !empty($_POST['isPartner'])) ? 1: 0;
$destination = '';

if($itemType == 1 && $isPartner == 1){

	$db->where('Id',$itemId);
	$db->where('is_partner',1);
	$deletingUser = $db->getOne('users');
	
	if(!empty($deletingUser)){
		$db->where('Id',$itemId);
		$db->delete('users');
		$destination = 'edit-user/'.$_SESSION['UserId'];
	}

	/*if(!empty($deletingUser)){
		//remove all personal child 
		$db->where('ParentId',$itemId);
		$db->where('is_partner',0);
		$db->delete('users');

		//Remove partner ID 2 from all child 
		$db->where('ParentId',$_SESSION['UserId']);
		$db->where('is_partner',0);
		$deletingUserChild = $db->get('users');
		
		if(!empty($deletingUserChild)){
			foreach($deletingUserChild as $deleteChild){
				$parentId_2 = explode(',', $deleteChild['ParentId_2']);								
				if(!empty($parentId_2) && in_array($itemId, $parentId_2)){
					$finalizeAry = array_diff($parentId_2, [$itemId]);					
					if(!empty($finalizeAry)){
						$updateAry['ParentId_2'] = implode(',', $finalizeAry);											
					}else{
						$updateAry['ParentId_2'] = NULL;
					}
					$db->where('Id',$deleteChild['Id']);
					$db->update('users', $updateAry);	
				}
			}
			$db->where('Id',$itemId);
			$db->delete('users');
			$destination = 'edit-user/'.$_SESSION['UserId'];		
		}
		$db->where('parent_id', $itemId);
		$db->delete('child_profile_access_rights');
	}*/
}

echo $destination;


?>