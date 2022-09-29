<?php 
include("../includes/config.php");

$departmentid = $_GET['departmentid'];
$customerid = $_GET['customerid'];

if($_SESSION['Admin']){

	$_SESSION['CustomerId'] = $customerid;
	$_SESSION['DepartmentId'] = $departmentid;
	$_SESSION['CurrentDepartment'] = $departmentid;

}

header('Location: /dashboard');

exit;
?>