<?php 
include("../../includes/config.php");
$date = date('Y-m-d');


if($_POST){
	$userIdArr = array();
	$attendance_data = json_decode($_POST['data'],true);
	// $newdata = array_search('coinstructorid', $attendance_data);
	foreach($attendance_data as $ad){
		$data = array(
			"Status" 			=> $ad['status'],
			"UserId" 			=> $ad['userid'],
			"Date" 				=> $db->now(),
			"CreateDate" 		=> $db->now()
		);
		// $userIdArr[] = $ad['userid'];
		if (isset($ad['co_id']) && $ad['co_id'] != '') {
			$data['InstructorId'] = $ad['co_id'];
		}else{
			$data['InstructorId'] = $_SESSION['UserId'];
		}

		$db->insert('coinstructor',$data);
		unset($data['InstructorId']);
		if (!empty($ad['coinstructorid']) && $ad['coinstructorid'] != 0) {
			$data['InstructorId'] = $ad['coinstructorid'];
		}
		$db->insert('coinstructor',$data);
		unset($data['InstructorId']);
		// $db->where('Date',$date);
		// $db->where('UserId',$ad['userid']);
		// $db->where('CoinstructorId',$_SESSION['UserId']);
		// $meetups = $db->get('coinstructor');

		$db->where('Date',$date);
		$db->where('UserId',$ad['userid']);
		$db_data = $db->get('meetup');
		if (count($db_data) == 0 && empty($db_data)) {
			$db->insert('meetup',$data);
		}
		// $data['InstructorId'] = $_SESSION['UserId'];
		// $db->insert('coinstructor',$data);

		// if (empty($meetups) && count($meetups) == 0) {
			
		// 	if ($data['CoinstructorId'] != 0) {
		// 	}
		// }
	}
}


?>
