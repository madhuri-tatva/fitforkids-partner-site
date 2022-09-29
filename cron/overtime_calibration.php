<?php
include("../includes/config.php");
global $db;


$date = date('Y-m-d H:i:s');
$current_year = date('Y');
$past_year = $current_year - 1;
$current_week = date('W');

$db->where('Completed',1);
$shifts = $db->get('schedule');

$db->where('ContractTime',0,'!=');
$users = $db->get('users');

foreach($users as $user){

	$shifts_by_userid = searcharray($shifts,'UserId',$user['Id']);

	for($year = $current_year; $year >= $past_year; $year--){

		$shifts_by_year = searcharray($shifts_by_userid,'Year',$year);

		if($year == $current_year){

			$start_week = 1;
			$end_week = $current_week;

			for($week = $start_week; $week <= $end_week; $week++){

				$shifts_by_week = searcharray($shifts_by_year,'Week',$week);

				if(!empty($shifts_by_week)){

					//d($shifts_by_week);

					// Calculate if total hours surpasses the contract

					$total_hours = 0;
					$all_shift_ids = '';

					foreach($shifts_by_week as $shift){

						$all_shift_ids = $all_shift_ids .",". $shift['Id'];
						$shift_total = convert_hours($shift['Start'],$shift['End']);
						$total_hours += $shift_total;
					}


					$total_minutes = convert_minutes($total_hours);

					//echo "Contract : " . $user['ContractTime'] . "<br>";
					//echo "This week : " . $total_minutes . "<br>";

					if($total_minutes > $user['ContractTime']){
						$overtime = $total_minutes - $user['ContractTime'];
						//echo "Overtime : " . $overtime;

						$check_overtime = overtime_check($user['Id'],$year,$week);

						if($check_overtime == true){

							if($check_overtime['Time'] != $total_minutes){

								$data = array(
									'ContractTime' => $user['ContractTime'],
									'Overtime' => $overtime,
									'Time' => $total_minutes,
									'Shifts' => $all_shift_ids
								);

								$db->where('Id',$check_overtime['Id']);
								$db->update('overtime', $data);

							}else{

								//echo "Do nothing";

							}

						}else{

							$data = array(
								'CustomerId' => $user['CustomerId'],
								'UserId' => $user['Id'],
								'Week' => $week,
								'Year' => $year,
								'Shifts' => $all_shift_ids,
								'ContractTime' => $user['ContractTime'],
								'Overtime' => $overtime,
								'Time' => $total_minutes,
								'CreateDate' => $db->now()
							);	

							$db->insert('overtime', $data);						

						}

					}

				}

			}

		}else{

			$start_week = 1;
			$end_week = 53;

		}

	}

}

?>