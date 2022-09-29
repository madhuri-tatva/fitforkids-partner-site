<?php
include("../includes/config.php");
global $db;


$date = date('Y-m-d H:i:s');

$db->where('ApprovalSetting',1);
$departments = $db->get('departments');

foreach($departments as $department){

	//echo $department['Name'];
	echo "<br>";

	$db->where('DepartmentId',$department['Id']);
	$shifts = $db->get('schedule');

	$i = 0;

	foreach($shifts as $shift){

		if($shift['Completed'] == 0){

			$start 	= substr($shift['Start'],-8,8);
			$end 	= substr($shift['End'],-8,8);

			if($start != '00:00:00' && $end != '00:00:00'){

				$paidshift = 1;

				if($shift['AbsenceId'] != 0){

					// Check if billable
					$absence = get_absence($shift['AbsenceId']);

					if($absence['Type'] == 0){
						$paidshift = 0;
					}

				}


				if($shift['End'] < $date && $shift['Available'] == 0 && $paidshift == 1){

					$i++;

					$data = array(
						"Completed" => 1
					);

					$db->where('Id', $shift['Id']);
					$db->update('schedule', $data);

					echo "Mark as complete " . $shift['End'];
					echo "<br>";

				}else{

					echo "To be done";
					echo "<br>";

				}

			}else{

				// Clean up empty shifts
				$db->where('Id', $shift['Id']);
				$db->delete('schedule');

				echo "Empty shifts cleaned";
				echo "<br>";

			}

		}

	}


	echo "-----------------------";
	echo "<br>";

	echo count($shifts);

	echo "<br>";

	echo $i;

	echo "<br>";
	echo "<br>";

}


?>