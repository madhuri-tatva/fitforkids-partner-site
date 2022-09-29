<?php
include("../includes/config.php");
global $db;

if($_SESSION['Admin'] != 1){
	exit;
}

$path = $_SERVER["DOCUMENT_ROOT"]."/shifts/";


$date1 = '2018-02-11';
$date2 = date('Y-m-d');

$date3 = strtotime($date2) - strtotime($date1);
$date3 = $date3 / 60 / 60  / 24;

//echo $date3;

/*$db->where('Type',3);
$all_users = $db->get('users');

$data_user = array(
	"DashShiftlistSetting" => 1
);


foreach($all_users as $user){

	echo $user['DashShiftlistSetting'];

	if($user['DashShiftlistSetting'] == 0){
		echo "Update this";

		$db->where('Id',$user['Id']);
		$db->update('users',$data_user);
	}else{
		echo "OK";
	}

	echo $user['Firstname'];
	echo "<br>";
}*/

/*
$all_shifts = $db->get('schedule');

foreach($all_shifts as $shift){

	$Day = date('N', strtotime($shift['Date']));

	echo $shift['Day'];
	echo "-";
	echo $Day;

	if($Day == $shift['Day']){
		echo "All good";
	}else{

		$data = array(
			"Day" => $Day
		);

		$db->where('Id', $shift['Id']);
		$db->update('schedule',$data);
	}

	echo "<br>";
}
*/

/*$db->where("CustomerId", 1);
$db->delete('schedule');*/





// CUSTOMER TYPES
// 0 = Freemium
// 1 = Pro
// 2 = To be deleted

// DEPARTMENT STATUSSES
// 0 - Registed but not paid
// 1 - Active and paid
// 2 - Past due
// 3 - Unpaid
// 4 - Canceled
// 9 - Trial period


// GET ALL CUSTOMERS

$date = date('Y-m-d H:i:s');

$subscription = \Stripe\Subscription::retrieve('sub_DNCyQ6nMGqehCf');
echo $subscription['status'];

//$db->where("Status", 1);
$db->where("Type", 1);
$customers = $db->get('customers');

// FOREACH CUSTOMERS
foreach($customers as $customer){

	echo "<strong>" . $customer['Company'] . "</strong>";

	// GET ALL DEPARTMENTS
	$db->where("CustomerId", $customer['Id']);
	$departments = $db->get('departments');

	// FOR EACH DEPARTMENTS 
	foreach($departments as $department){

		echo "<br>|<br>";

		echo "<strong>" . $department['Name'] . "</strong>";


		// CHECK IF DEPARTMENT SUBSCRIPTION IS VALID
		if($department['ActiveUntil'] < $date){

			if(!empty($department['StripeSubscriptionId'])){

				$subscription = \Stripe\Subscription::retrieve($department['StripeSubscriptionId']);

				if($subscription['status'] == 'active'){
					echo " <br>Is active";
				}elseif($subscription['status'] == 'past_due'){
					echo " <br>Is past due";
		            $data = array('Status' => 2);
		            $db->where("Id", $department['Id']);
		            $db->update('departments', $data);
				}elseif($subscription['status'] == 'unpaid'){
					echo " <br>Is unpaid";
		            $data = array('Status' => 3);
		            $db->where("Id", $department['Id']);
		            $db->update('departments', $data);
				}elseif($subscription['status'] == 'canceled'){
					echo " <br>Is canceled";
		            $data = array('Status' => 4);
		            $db->where("Id", $department['Id']);
		            $db->update('departments', $data);
				}elseif($subscription['status'] == 'trialing'){
					echo " <br>Is trialing";
		            $data = array('Status' => 9);
		            $db->where("Id", $department['Id']);
		            $db->update('departments', $data);
				}

			}else{

				echo " <br>Not customer yet";

			}

		}else{

			echo " <br>";

			if($department['Status'] == 0){
				echo " Registered but not paid";
			}elseif($department['Status'] == 1){
				echo " Active and paid";
			}elseif($department['Status'] == 2){
				echo " Past due";
			}elseif($department['Status'] == 3){
				echo " Unpaid";
			}elseif($department['Status'] == 4){
				echo " Cancelled";
			}elseif($department['Status'] == 9){
				echo " In Trial";
			}

			$days = abs(strtotime($department['ActiveUntil']) - strtotime($date));
			$days = $days / 86400;

			echo "<br>Renew Date " . $department['ActiveUntil'];
			echo "<br>Days " . $days;

		}



		// CHECK IF EXTRA USER SUBSCRIPTION IS VALID
		if($department['UserCapacity'] > 5){

			$db->where("ProductId", $department['Id']);
			$db->where("ProductType", 2);
			$orders = $db->get('orders');

			$user_capacity = 5;

			/*foreach($orders as $order){

				$subscription_users = \Stripe\Subscription::retrieve($order['StripeId']);

				if($subscription_users['status'] == 'active'){
					$user_capacity = $user_capacity + $subscription_users['quantity'];
					echo " - Users OK";
				}
				
				if($subscription_users['status'] != 'active' && $subscription_users['current_period_end'] > date('Y-m-d H:i:s')){
					$user_capacity = $user_capacity + $subscription_users['quantity'];
					echo " - Current OK";
				}

			}*/
			
            /*$data = array('UserCapacity' => $user_capacity);
            $db->where("Id", $department['Id']);
            $db->update('departments', $data);*/

            //echo " " . $user_capacity;

		}

	}

	echo "<br>---------------------------------";
	echo "<br>--------------------------------- <br>";

}


?>


----- ALL STORES -----
<br>

<?php

/*
$all_subscriptions_stores = \Stripe\Subscription::all(array(
	'limit'=>10,
	'plan'=>'plan_DNCSqVam4JFfMM'
));

foreach($all_subscriptions_stores->data as $data){
	echo $data['status'] . " " . $data['id'];
	echo "<br>";

}
*/

?>


----- ALL USERS -----
<br>

<?php

/*
$all_subscriptions_users = \Stripe\Subscription::all(array(
	'limit'=>10,
	'plan'=>'plan_DNCTRUwx6FV0Gi'
));

foreach($all_subscriptions_users->data as $data){
	echo $data['status'] . " " . $data['id'];
	echo "<br>";

}
*/

?>