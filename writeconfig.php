<?php
ob_start();
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
//error_reporting(E_ALL);
require 'Lib/Excel.php';
$dir = AUTOMATION_DIR.DS.$sess_vmg_dir.DS.CONFIG_DIR;





if( isset($_POST['hdn_action']) && $_POST['hdn_action'] == 'write'){
	//echo '<pre>';
	//print_r($_POST);
	//die();
	
	$save_to_path = AUTOMATION_DIR.DS.$sess_vmg_dir.DS.CONFIG_DIR;
	
	#########################################################
	################### Write User Config File ###############
	$objPHPExcel = new PHPExcel();

	$sheet_index = 0 ;
	foreach ($_POST['param_name']['user'] as $sheetname=>$sheetdata) {
		//$sheet = $objPHPExcel->getActiveSheet();
		//$sheet = $objPHPExcel->setActiveSheetIndex(0);
		$objWorkSheet = $objPHPExcel->createSheet($sheet_index);
		//$objPHPExcel->removeSheetByIndex(3);
		$i=1;
		foreach ($sheetdata as $col_index=>$col_value) {
			//$objPHPExcel->getActiveSheet()->SetCellValue($col_index, $col_value);			
			$objWorkSheet->SetCellValue('B'.$i, $_POST['param_val']['user'][$sheetname]['B'.$i]);
			$objWorkSheet->SetCellValue($col_index, $col_value);
			$i++;
		}
		
		$objWorkSheet->setTitle($sheetname);
		//$objPHPExcel->removeSheetByIndex(3);
		
		$sheet_index++;
	}
	
	/*
	 * Delete worksheet starting with Worksheet
	 */
	$sheetName = $objPHPExcel->getSheetNames();
	//print_r($sheetName);
	foreach ($sheetName as $key=>$value) {		
		$valid = preg_match("/Worksheet$/", $value) ;
		if($valid==1){			
			try {
				$objPHPExcel->removeSheetByIndex($key);
			} catch (Exception $e) {
				//echo $e;
			}
		}
	}
	$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

	
	$file_name = $_POST['hdn_user_file_name'];
	$objWriter->save($save_to_path.$file_name);
	//header('location:configuration-v2.php?msg=success');
	#########################################################
	################### End Write User Config File ###########
	
	
	
	
	
	
	
	
	#########################################################
	################### Write VMG Config File ###############
	$objPHPExcel = new PHPExcel();

	$sheet_index = 0 ;
	foreach ($_POST['param_name']['vmg'] as $sheetname=>$sheetdata) {		
		$objWorkSheet = $objPHPExcel->createSheet($sheet_index);		
		$i=1;
		foreach ($sheetdata as $col_index=>$col_value) {					
			$objWorkSheet->SetCellValue('B'.$i, $_POST['param_val']['vmg'][$sheetname]['B'.$i]);
			$objWorkSheet->SetCellValue($col_index, $col_value);
			$i++;
		}
		
		$objWorkSheet->setTitle($sheetname);
		//$objPHPExcel->removeSheetByIndex(3);
		
		$sheet_index++;
	}
	
	/*
	 * Delete worksheet starting with Worksheet
	 */
	$sheetName = $objPHPExcel->getSheetNames();	
	foreach ($sheetName as $key=>$value) {		
		$valid = preg_match("/Worksheet$/", $value) ;
		if($valid==1){			
			try {
				$objPHPExcel->removeSheetByIndex($key);
			} catch (Exception $e) {
				//echo $e;
			}
		}
	}
	$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
	
	$file_name = $_POST['hdn_vmg_file_name'];
	$objWriter->save($save_to_path.$file_name);
	header('location:configuration.php?up=1');
	#########################################################
	################### End Write VMG Config File ###########
	
	
}
?>