<?php
/*
 * 24-April-2014
 */
require 'app_config.php';
ini_set("max_execution_time",0);
error_reporting(0);

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



// Define Python Command Parameter
$pyParam = array(
'S'=>'', 	/* Suite Name (suite1,suite2) */
'T'=>'',	/* Testcase Name (tc1.py, tc2.py) */
'N'=>'',	/* Nos. of exe (1,4) */
'P'=>'',	/* Product Name (vmg1) */
'C'=>'',	/* Component Name  (gui, rpc) */
'V'=>'',	/* Version (3.5) */
'G'=>'',	/* Type of Testing/Test Label (sanity, regression, loadtesting) */
'u'=>'',	/* Username , test will run from his/her account */
'p'=>'',	/* Password of the said user */
'M'=>'',	/* Mail notificatio flag enable or disable */
'E'=>''		/* Email Address, to which notification/status mail will be sent */
);

//Not mandatory
unset($pyParam['M']);
unset($pyParam['E']);

// Product Name Assign
$pyParam['P'] = strtolower($sess_vmg_dir);
// Component Assign
$pyParam['C'] = strtolower($_SESSION['sess_vmg']['component']);
// Version Asign
$pyParam['V'] = str_replace('_','.',$sess_ver_dir);
// Type of Testing
$pyParam['G'] = strtolower($_SESSION['sess_vmg']['label']);
// Username Assign
$pyParam['u'] = $username;
// Password Assign
$pyParam['p'] = $_SESSION['password'];

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

//print_r($ts_serial);
// For each TS Serial Get test case file from selected drop down
if (isset($ts_serial)) {
	foreach ($ts_serial as $array_index=>$serial_no) {
		if(isset($_POST['select_'.$serial_no.'2'])){
			//echo '<pre>';
			//print_r( $_POST['select_'.$serial_no.'2'] ); // pattern select_checkbox_sr1[] -----move--> select_checkbox_sr2[]
			//$tc_py_file[] = $_POST['select_'.$serial_no.'2'];
			foreach ($_POST['select_'.$serial_no.'2'] as $key=>$value) {
				$tc_py_file[] = $value;
			}
		}
		$ts_no_execution[] = $_POST['nooftimes_'.$serial_no];
	}

	$pyParam['T'] = implode(',', $tc_py_file);
	$pyParam['N'] = implode(',', $ts_no_execution);
}


// Email notification
if(isset($_POST['email_notification'])){
	$pyParam = array_merge($pyParam,array('M'=>''));
}

// Emergency email address
if( isset($_POST['emergency_email']) && ($_POST['emergency_email'] !="")){
	$pyParam = array_merge($pyParam,array('E'=>$_POST['emergency_email']));
}



#######################################################
################# Now execute command #################
//echo exec('whoami'); // os user
$pyCommand='';
$pyCommand.='/usr/local/bin/python '.AUTOMATION_DIR.'/main_driver.py ';
foreach ($pyParam  as $key=>$value) {
	$pyCommand.= ' -'.$key.' '.$value;
}

//echo $pyCommand;
exec($pyCommand,$out); // use 2>&1 at the end of command for debugging

/*echo '<pre>';
 print_r($out);
 echo '</pre>';*/


exec('ps -ax | grep main',$out2);
//echo '<pre>';
//print_r($out2);
//echo '</pre>';

/*$search_with=array($pyParam['P'],$pyParam['V'],$pyParam['G'],$pyParam['u']);
$out2 = array('3059 pts/2    T      0:00 python main_driver.py -S suite_loadtest -P vmg2 -C gui -V 1.3 -G sanity -u guiautomation -p pass1234','3016 pts/2    T      0:00 python main_driver.py -S suite_loadtest -P vmg2 -C gui -V 1.3 -G sanity -u guiautomation -p pass1234');

foreach ($out2 as $output_value) {
	foreach ($search_with as $serach_keyword){
		if ( strpos( $output_value, $serach_keyword ) !== FALSE ){
		echo "The word appeared !!" ;
		}else{
		echo 'not Appeared';
		}
		}
	if (strpos($output_value,$pyParam['P']) !== false) {
		echo 'true';
	}else {
		echo 'false';
	}
}*/
#######################################################
################# Now execute command #################




// Test Print Array
//echo '<pre>';
//print_r($pyParam);
?>