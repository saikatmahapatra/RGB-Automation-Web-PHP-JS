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
$read = scandir($dir);
$file_tree = array_diff($read, array('.','..','.svn','__init__.py'));


// Array contains => 10.32.111.1_cfg.xls , gouri_cfg.xls
// Use regualr expressions to get files starting with any ip address
//$valid = preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $string);
$result = array();
if(isset($file_tree) && (sizeof($file_tree)>0) ){
	foreach ($file_tree as $key=>$val){
		$splittedFileName = explode('_', $val);
		//print_r($splittedFileName);
		$ipFromSplittedName = $splittedFileName[0];
		$valid = preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $ipFromSplittedName);
		if($valid ===1){
			$result['device_config'][$ipFromSplittedName] = $ipFromSplittedName;
		}else{
			$result['user_config'][$ipFromSplittedName]=$ipFromSplittedName;
		}
	}

}






############################################################
############ Read User Config file using PHPExcel ###########
$user_file_path = $dir.$sess_username.'_cfg.xls';

// If user config not exists , read from storage template
// On update create with user provided value and save it to the
// required location
if(!file_exists($user_file_path)){
	$user_file_path = TPL_USER_CFG_PATH; // located includes/tpl_user_cfg.xls
}

$user_file_name = $sess_username.'_cfg.xls';
$objReader = PHPExcel_IOFactory::createReader('Excel5');



if(file_exists($user_file_path)){	
	$user_html='';
	$objPHPExcel = $objReader->load($user_file_path);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);	
	$sheetCount = $objPHPExcel->getSheetCount();
	$sheetName = $objPHPExcel->getSheetNames();	
	$sheetDataResult = array();
	foreach ($sheetName as $sheetIndex=>$SheetName) {		
		$sheetDataResult[$SheetName] = $objPHPExcel->getSheet($sheetIndex)->toArray(null,true,true,true);
	}
	$sheet_index = 0;
	foreach ($sheetDataResult as $sheetname=>$sheetdata) {			
		$user_html.='<tr class="user_sheet"><td valign="middle" bgcolor="#CCCCCC" align="center" colspan="4"><b>'.ucwords(str_replace('_', ' ', $sheetname)).'</b></td></tr>';
		$sheet_row=1;
		
		foreach ($sheetdata as $data) {
			$tr_bg_color = '#FFFFCA'; // default green other white		
			$user_html.='<tr class="user_result" bgcolor="'.$tr_bg_color.'">';
			$user_html.='<td width="50" valign="middle" align="left">'.$sheet_row.'</td>';
			$user_html.='<td width="220" valign="middle" align="left">'.$data['A'].'</td>';
			
			// set vmg ip on chnage
			$vmg_ip = isset($_GET['cfg']) ? $_GET['cfg'] : $data['B'];
			
			$user_html.='<td width="200" valign="middle" align="left">	
			<input type="hidden" name="param_name[user]['.$sheetname.'][A'.$sheet_row.']" value="'.$data['A'].'"  />
			<input type="text" name="param_val[user]['.$sheetname.'][B'.$sheet_row.']" value="'.$data['B'].'" id="'.$sheetname.'_'.$data['A'].'" size="29" /></td>';
			$user_html.='<td width="260" valign="middle" align="left">'.@$data['C'].'</td>';
			$user_html.='</tr>';
		$sheet_row++;		
		}
		$sheet_index++;		
	}		
}else {
	//header('location:select_device.php?error=file_not_found');
	$user_html='';
	$user_html.='<tr class="user_file_err"><td class="error" colspan="4">'.$user_file_path.' file not found</td></tr>';
}

//echo $user_html;

############################################################
############ End Read User Config file using PHPExcel ######






############################################################
############ Read VMG Config file using PHPExcel ###########
$vmg_ip_from_user_config = isset($sheetDataResult['automation'][5]['B']) ? $sheetDataResult['automation'][5]['B'] :'';
$vmg_ip = isset($_GET['cfg']) ? $_GET['cfg'] : $vmg_ip_from_user_config;
$vmg_file_path = $dir.$vmg_ip.'_cfg.xls';

// If user config not exists , read from storage template
// On update create with user provided value and save it to the
// required location
if(!file_exists($vmg_file_path)){
	$vmg_file_path = TPL_VMG_CFG_PATH; // located includes/tpl_vmg_cfg.xls
}

$vmg_file_name = $vmg_ip.'_cfg.xls';
$objReader = PHPExcel_IOFactory::createReader('Excel5');

if(file_exists($vmg_file_path)){	
	$vmg_html='';
	$objPHPExcel = $objReader->load($vmg_file_path);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);	
	$sheetCount = $objPHPExcel->getSheetCount();
	$sheetName = $objPHPExcel->getSheetNames();	
	$sheetDataResult = array();
	foreach ($sheetName as $sheetIndex=>$SheetName) {		
		$sheetDataResult[$SheetName] = $objPHPExcel->getSheet($sheetIndex)->toArray(null,true,true,true);
	}
	
	//echo '<pre>';
	//print_r($sheetDataResult);
	
	foreach ($sheetDataResult as $sheetname=>$sheetdata) {	
		//$vmg_html.='<tr><td colspan="4"><strong>'.$sheetname.'</strong></td></tr>';		
		$vmg_html.='<tr class="vmg_sheet"><td valign="middle" bgcolor="#CCCCCC" align="center" colspan="4"><b>'.ucwords(str_replace('_', ' ', $sheetname)).'</b></td></tr>';
		$sheet_row=1;
		
		if($sheetname != 'network_config'){
			foreach ($sheetdata as $data) {	
				$tr_bg_color = '#EBFFD7'; // default green other white		
				$vmg_html.='<tr class="vmg_result" bgcolor="'.$tr_bg_color.'">';
				$vmg_html.='<td width="50" valign="middle" align="left">'.$sheet_row.'</td>';
				$vmg_html.='<td width="220" valign="middle" align="left">'.$data['A'].'</td>';
				$vmg_html.='<td width="200" valign="middle" align="left">
				<input type="hidden" name="param_name[vmg]['.$sheetname.'][A'.$sheet_row.']" value="'.$data['A'].'"  />
				<input type="text" name="param_val[vmg]['.$sheetname.'][B'.$sheet_row.']" value="'.$data['B'].'" id="'.$sheetname.'_'.$data['A'].'" size="29" />
				</td>';
				$vmg_html.='<td width="260" valign="middle" align="left">'.@$data['C'].'</td>';
				$vmg_html.='</tr>';
			$sheet_row++;
			}
		}//end if not network_config

		if($sheetname == 'network_config'){
			$col=1;
			foreach ($sheetdata as $data) {	
				$tr_bg_color = '#EBFFD7'; // default green other white		
				echo '<pre>';
				print_r($data['A']);
				$vmg_html.='<tr class="vmg_result network_config" bgcolor="'.$tr_bg_color.'">';
				$vmg_html.='<td colspan="4">';
				$vmg_html.='<table border="1" width="100%" cellpadding="0" cellspacing="0">
							<tr class="param_name">
							<td class="">'.$data['A'.$col].'</td>
							<td class="">'.$data['B'.$col].'</td>
							<td class="">'.$data['C'.$col].'</td>
							<td class="">'.$data['D'.$col].'</td>
							<td class="">'.$data['E'.$col].'</td>
							<td class="">'.$data['F'.$col].'</td>
							<td class="">'.$data['G'.$col].'</td>
							</tr>			
							
							</table>';
				$vmg_html.='</td>';
				$vmg_html.='</tr>';
				
			
			$col++;	
			$sheet_row++;
			}
		}//end if network_config
		
		
		
	}		
}else {
	$vmg_html='';
	$vmg_html.='<tr class="vmg_file_err"><td class="error" colspan="4">'.$vmg_file_path.' file not found</td></tr>';
}

//echo $vmg_html;
$vmg_html.='<tr><td colspan="4" align="center"><input type="button" name="submit_btn" value="Update"  /></td></tr>
</table>
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>

</body>
</html>';
############################################################
############ End Read VMG Config file using PHPExcel ###########




$vmgArray=array('10.32.97.31'=>'TinkerBell(10.32.97.31)',
'10.32.97.32'=>'Goofy(10.32.97.32)',
'10.32.99.31'=>'Nemo(10.32.99.31)',
'10.32.97.37'=>'Tweety(10.32.97.37)',
'10.32.98.140'=>'Simba(10.32.98.140)',
'10.32.98.142'=>'Pluto(10.32.98.142)',
'10.32.97.170'=>'SQA(10.32.97.170)',
'10.32.98.141'=>'Pinky(10.32.98.141)',
'10.33.0.56'=>'Mars(10.33.0.56)',
'10.33.0.53'=>'Neptune(10.33.0.53)',
'10.33.0.59'=>'Mercury(10.33.0.59)',
'10.33.0.50'=>'Venus(10.33.0.50)',
'10.33.1.52'=>'Paul(10.33.1.52)')
;
?>




<!DOCTYPE HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RGB Automation</title>
<link rel="icon" href="rgbfav.ico" />
<link rel="stylesheet" href="cssfiles/style-new.css" media="all">
<script type="text/javascript" src="jquery-1.3.2.js"></script>
<script language="javascript">
function loadcfg(vmg){		
        if(vmg.value!=""){
        	var retVal1 = confirm("You are about to override the running vmg config,Do you want to continue?");
		}		
        if(retVal1){
        	window.location.href='configuration.php?cfg='+vmg.value;			
		}
        else{
        	window.location.href='configuration.php?cfg=""';
        }
}
</script>


<script type="text/javascript">
$(document).ready(function(e) {
    $('input[name="submit_btn"]').click(function(e) {
		var conf = confirm("Are you sure, you want to change parameter values?\n");
		if(conf==false){
			return false;
			}
		if(conf == true){					
			$('#form_configwrite').submit();
			return true;
		}     
		
    });
});
</script>

</head>

<body>


<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top">
    
    
    
    <table width="100%">
<tr>
<td colspan="2" align="left" valign="top" class="logo_bg"><img align="top" src="../resources/images/rbgLogo-lg.png"></td>
</tr>

<tr>
                    <td align="left" valign="top" class="app_title">RGB Automation</td>
                    <td align="right"><?php echo $_SESSION['username'];?>, [ <a href="logout.php" title="Click here to Logout">Logout</a> ]</td>
                </tr>
</table>
    
    </td>
  </tr>
  
  
  
 
  
  <tr id="breadcrumb_holder">
  <td colspan="3" valign="top" align="left">
  <div class="breadcrumb">
   	 <ul>
        <li>
        	<a href="select_device.php">Product</a>
        	<span class="sel-val"><?php echo $sess_vmg_dir;?></span>        
        	<span class="sep">&gt;&gt;</span>
        </li>
        
        <li>
        	<a href="select_device.php?device=<?php echo $sess_vmg_dir;?>">Version</a>
        	<span class="sel-val"><?php echo str_replace('_', '.', $sess_ver_dir);?></span>
            <span class="sep">&gt;&gt;</span> 
        </li>
        <li>
        	<a href="menu.php">Test Level</a>
        	<span class="sel-val"></span>
            <span class="sep">&gt;&gt;</span> 
        </li>
        
        <li>
        	User and VMG Configuration
        </li>        
    </ul>
</div>

  </td></tr>
  
  <tr>
    <td align="center" valign="top">
    
    <table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="40" align="center" valign="middle" class="header_text"><span>Configuration</span></td>
      </tr>
      
      <tr>
        <td align="left" valign="top">
                <form id ="form_configwrite" name="f1" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" >
                <input type="hidden" name="hdn_user_file_name" value="<?php echo $user_file_name;?>" />
                <input type="hidden" name="hdn_vmg_file_name" value="<?php echo $vmg_file_name;?>" />
                <input type="hidden" name="hdn_action" value="write" />
                
                
                <table class="config-table"  border="0" width="750" align="center" cellpadding="0" cellspacing="0">                
                <tr>
                <td align="center" valign="middle" colspan="4">
				<?php if(isset($_REQUEST['up']) && $_REQUEST['up']==1){?>
                <font color="#FF0000">File has been successfully updated<?php }?>
                </font>
                </td>
                </tr>
                <tr>
                <td align="center" valign="middle" colspan="4">&nbsp;<strong>Select VMG</strong>
                
                <select name='conffile' onchange="loadcfg(this);">
                <option value="">Select VMG Configuration File</option>
                <?php foreach ($result['device_config'] as $device_config) {  	?>
                	<option value="<?php echo $device_config?>" 
					<?php //echo ($vmg_ip_from_user_config == $device_config) ? 'selected="selected"' : ( (@$_GET['cfg'] == $device_config) ? 'selected="selected"' : '' ); ?> 
                    <?php
                    if(!isset($_GET['cfg'])){
						echo ($vmg_ip_from_user_config == $device_config) ? 'selected="selected"' :'';
						}else{
							echo ($_GET['cfg'] == $device_config) ? 'selected="selected"' :'';
							}
					?>
                    >
                    
                	<?php echo isset($vmgArray[$device_config]) ? $vmgArray[$device_config] : $device_config;?>                	
                	</option>
                	<?php   } ?>
                </select>
                
                </td>
               
                </tr>
                
           

                <tr><td align="center" colspan="4"><h3>User Configuration</h3></td></tr>
                
				<?php echo isset($user_html) ? $user_html : '<tr><td colspan="4">user html variable is not ready</td></tr>';?>
                
                <tr><td colspan="4" align="center"><h3>VMG Configuration</h3></td></tr>
                
                <?php echo isset($vmg_html) ? $vmg_html : '<tr><td colspan="4">vmg html variable is not ready</td></tr>';?>












<?php
###################################################### Write ###########################################
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
	//header('location:'.$_SERVER['PHP_SELF'].'?up=1');
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
	header('location:'.$_SERVER['PHP_SELF'].'?up=1');
	#########################################################
	################### End Write VMG Config File ###########
	
	
}

?>