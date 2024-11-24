<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
	header("location:index.html");
}

$sess_vmg_dir = isset($_SESSION['sess_vmg']['name']) ? $_SESSION['sess_vmg']['name'] : '';
$sess_ver_dir = isset($_SESSION['sess_vmg']['version']) ? $_SESSION['sess_vmg']['version'] : '';
$sess_username = $username;
if ($sess_vmg_dir == '' && $sess_ver_dir =='') {
	header('location:select_device.php?d=0&v=0');
}
require 'app_config.php';




echo '<pre>';
print_r($_POST);





//Test Suite Assign, Test Suite Checkbox checked
if( isset($_POST['itemSelect']) ){
	$suiteName = implode(",",$_POST['itemSelect']);
	$suite_order = explode(',', $suiteName);
	foreach ($suite_order as $key=>$value) {
		$suite = explode(':', $value);
		$ts_name[] = $suite[0];
		$ts_serial[] = $suite[1];
	}
	$pyParam['S'] = implode(',', $ts_name);
}
//die();