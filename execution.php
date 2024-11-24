<?php
//error_reporting(0);
session_start();
$username = $_SESSION['username'];
if($username == ""){
	header("location:index.html");
}
require 'app_config.php';

$sess_vmg_dir = isset($_SESSION['sess_vmg']['name']) ? $_SESSION['sess_vmg']['name'] : '';
$sess_ver_dir = isset($_SESSION['sess_vmg']['version']) ? $_SESSION['sess_vmg']['version'] : '';

$component = isset($_GET['com']) ? strtoupper($_GET['com']):'RPC';
$_SESSION['sess_vmg']['component'] = $component;
$sess_component = isset($_SESSION['sess_vmg']['component']) ?  $_SESSION['sess_vmg']['component'] : '';

$test_label = isset($_GET['label']) ? $_GET['label']:'';
$_SESSION['sess_vmg']['label'] = $test_label;
$sess_label = isset($_SESSION['sess_vmg']['label']) ?  $_SESSION['sess_vmg']['label'] : '';

if ($sess_vmg_dir == '' && $sess_ver_dir =='' || $sess_component =='') {
	header('location:select_device.php?d=0&v=0');
}



require 'includes/DirectoryHelper.php';
$objDir = new DirectoryHelper();
// Read vmg related folder

$dirPathP = AUTOMATION_DIR.DS.$sess_vmg_dir.DS.$sess_ver_dir.DS.TEST_DIR.$sess_component;
$read_test_suite_folder = $objDir->readDirectory($dirPathP);
$exclude = array('.','..','.svn');

//echo '<pre>';
//print_r($read_test_suite_folder);
$read_test_suite_folder = array_diff($read_test_suite_folder, $exclude);

//echo '<pre>';
//print_r($read_test_suite_folder);

//echo '<br>';

//$read_test_suite_folder = $appconfig_suite_order;
if($sess_component =='RPC'){
	$read_test_suite_folder = array_merge($appconfig_suite_order,array_diff($read_test_suite_folder,$appconfig_suite_order));
}


//print_r($read_test_suite_folder);
?>




<?php
######################################################
############## Read Execution Configuration ##########
/*
* Read the config XML and make test case check box checked at DOM ready
* and make fill the respective test case seleted option
*/

$global_config = array(); 
//require 'Lib/XML2Array.php';

//$xml_file_name = strtolower($sess_vmg_dir).'_'.strtolower($sess_label).'.xml';
//$xml_path = EXECUTION_CONFIG_DIR.DS.$xml_file_name;
//if(file_exists($xml_path)){
	//$xml = simplexml_load_file($xml_path);
	//$array = XML2Array::createArray($xml->asXML());
	//$global_config = $array['cfg'];	
//}


/*echo '<pre>';
print_r($global_config);
$json = json_encode($global_config);
print_r($json);*/

######################################################
############## End Read Execution Configuration ##########
?>


<?php
###########################################################
#################	Check which Test Are running ##########
exec('ps -ax | grep main',$out2);
//echo '<pre>';
//print_r($out2);
//echo '</pre>';

$P = strtolower($sess_vmg_dir);
$V = str_replace('_', '.', $sess_ver_dir);
$G = strtolower($_SESSION['sess_vmg']['label']);
$u = $username;

$search_with=array($P,$V,$G,$u);
//$out2 = array('3059 pts/2    T      0:00 python main_driver.py -S sanity -P vmg2 -C gui -V 1.3 -G sanity -u krishnendu -p pass1234','3016 pts/2    T      0:00 python main_driver.py -S suite_loadtest -P vmg2 -C gui -V 1.3 -G sanity -u guiautomation -p pass1234');
$running_test=array();
$disable_run_button=false;
foreach ($out2 as $output_value) {	
	if ( (strpos($output_value,$P) !== false) && (strpos($output_value,$V) !== false) && (strpos($output_value,$G) !== false) && (strpos($output_value,$u) !== false)) {
		$running_test[]= $output_value;
	}
}
if(isset($running_test) && sizeof($running_test)>0){
	$disable_run_button = true;
}

###########################################################
#################	Check which Test Are running ##########
?>




<!DOCTYPE HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RGB Automation</title>
<link rel="icon" href="rgbfav.ico" />
<link href="cssfiles/common.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="cssfiles/style-new.css" media="all">
<script	type="text/javascript" src="jquery-1.3.2.js"></script>
<script	type="text/javascript" src="jsfiles/common.js"></script>

</head>
<body>
<table width="100%" border="0" align="center" cellpadding="0"
	cellspacing="0">
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
			<li><a href="select_device.php">Product</a> <span class="sel-val"><?php echo $sess_vmg_dir;?></span>
			<span class="sep">&gt;&gt;</span></li>

			<li><a href="select_device.php?device=<?php echo $sess_vmg_dir;?>">Version</a>
			<span class="sel-val"><?php echo str_replace('_','.',$sess_ver_dir);?></span> <span
				class="sep">&gt;&gt;</span></li>
			<li><a href="menu.php">Test Level</a> <span class="sel-val"> <?php echo isset($_GET['label']) ? ucwords($_GET['label']).' ' : '';?>
			<?php echo isset($_GET['com']) ? strtoupper($_GET['com']) : '';?> </span>
			<span class="sep">&gt;&gt;</span></li>
			Execution
			<li></li>
		</ul>
		</div>

		</td>
	</tr>
	<tr>
		<td align="center" valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="40" align="center" valign="middle" class="header_text"><span><?php echo $sess_vmg_dir.' '.str_replace('_','.',$sess_ver_dir).' '.strtoupper($sess_component).' '.ucwords($_GET['label']).' Automation'; ?></span></td>
			</tr>
			<tr>
				<td align="left" valign="top">
				<form name="caseform" id='caseform' action="#" method="post"
					enctype="multipart/form-data" onsubmit="">
				<table width="98%" border="1" align="center" cellpadding="0"
					cellspacing="0">

					<tr>

						<td align="left" valign="top">
						<table width="95%" border="0" cellspacing="4" cellpadding="0">
							<tr>
								<td align="left" valign="top"><input type="checkbox"
									name="email_notification" id="em" value="1" checked="checked" />
								<label for="em">Send email</label></td>
								<td id="override_email_holder" valign="top" align="left"><label for="email" id="email_label">Override Email
								ID(s): </label> <input type="text" id="email"
									name="emergency_email" value="" size="40" /></td>
							</tr>
						</table>
						</td>


					</tr>


					<tr>
						<td align="left" valign="top">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
							<?php
							$suiteCount=0;
							$ts =1;
							foreach($read_test_suite_folder as $folderName){ //$arrayOrder
								#echo 'testsuite folder='.$ts;
								?>
								<td width="3%" align="left" valign="middle">
                                
                                <input
									type="checkbox" name="itemSelect[]" class='itemSelect'
									id="itemSelect"
									value=<?php echo strtolower($folderName).':'.$ts;?>
									title="Please check to view the test cases of <?php print $folderName;?>" 
                       				<?php echo (array_key_exists(strtolower($folderName), $global_config) ) ? 'checked="checked"' : '';?>
                       				/>
                                    
								</td>

								<td width="11%" align="left" valign="middle" nowrap="nowrap">
								<?php echo isset($appconfig_suite_name[$folderName]) ? $appconfig_suite_name[$folderName] : ucwords(str_replace('_', ' ', $folderName));?>
								</td>
								<?php
								if($suiteCount>3){
									//print $suiteCount;
									$suiteCount=-1;
									print "</tr>";
									print "<tr>";
								}
								$suiteCount+=1;
								$ts++;
							}
							?>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td align="left" valign="top">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="47%" align="center" valign="middle"><b>All Test cases</b></td>
								<td width="10%" align="center" valign="middle">&nbsp;</td>
								<td width="43%" align="center" valign="middle"><b>Test Cases for
								Execution</b></td>
							</tr>
						</table>
						</td>
					</tr>




					<!----Test Cases loop here------------------------->
					<?php
					$test_case = 1;
					foreach($read_test_suite_folder as $Key=>$Value){
						$displayKey = isset($appconfig_suite_name[$Value]) ? $appconfig_suite_name[$Value] : $Value;
                    ?>
					
                    <?php
					//echo '<pre>';
					//print_r($global_config[$Value]['tc_selected']);
                    $ts_selected = $global_config[$Value]['tc_selected'];
					$ts_selected = array_flip($ts_selected);
					?>
                    
					<tr class="dynamic_testcase_holder">                   
						<td align="left" valign="top">
						<table width="100%" border="0" cellspacing="2" cellpadding="2"	id="<?php print $Value;?>" style="display:none;">
							<tr>
								<td height="25" align="left" valign="top"><b><?php print ucwords($displayKey);?></b></td>
							</tr>



							<!---------------------------Move and remove all button------------------------------------------->
							<tr>
								<td align="left" valign="top">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="43%" align="left" valign="middle"><input
											type="button" name="addAll" value="Move All"
											onclick="return fnSelectAllItems('<?php print $test_case;?>1','<?php print $test_case;?>2')" /></td>
										<td width="16%" align="left" valign="middle"></td>
										<td width="41%" align="left" valign="middle"><input
											type="button" name="removeAll" value="Remove All"
											onclick="return fnDeSelectAllItems('<?php print $test_case;?>1','<?php print $test_case;?>2')" /></td>
									</tr>
								</table>
								</td>
							</tr>
							<!---------------------------Move and remove all button------------------------------------------->



							<tr>
								<td align="left" valign="top">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="47%" align="left" valign="middle">
										<?php #echo 'test case py ='.$test_case;?>										
										<select
											size="100" name="select_<?php print $test_case;?>1[]"
											multiple="multiple" id="<?php print $test_case;?>1"
											style="height: 70px; width: 480px;">



											<?php
											$test_suite_folder = $Value;											
											if($sess_component =='RPC'){											
												$test_case_folder_path = $dirPathP.DS.$test_suite_folder.DS.TEST_CASE_DIR;
												$test_case_files = array_diff(scandir($test_case_folder_path) , array('.','..','channelxls','.svn','__init__.py','__init__.pyc'));
											
											}
											
											if($sess_component =='GUI'){												
												$test_case_folder_path = $dirPathP.DS.$test_suite_folder.DS;
												$result = array();
												foreach (new DirectoryIterator($test_case_folder_path) as $file){
													if($file->isDot()) continue;
													if($file->isDir()){
														$result[]=$file->getFilename();
													}
												}
												//echo '<pre>';
												$test_case_files = array_diff($result,array('.','..','.svn','__init__.py','__init__.pyc'));
												//print_r($test_case_files);
												//die('llll');
											}


											natsort($test_case_files);
											foreach($test_case_files as $valueTestCase){
												list($testCaseName,$ext)=split("\.",$valueTestCase);
													$displayListName=$testCaseName;
													$valueArr=explode("-",$displayListName);
													$valueArr1=explode("_",$valueArr[1]);
													if(!preg_match('/Driver*/',$valueArr[0])){
														$valueTestCase=$valueArr[0];
													}else {
														$valueTestCase=$valueArr[0];
													}
												if($sess_component =='RPC'){
													if($ext == 'py'){
													?>                                               
													<option value="<?php echo $valueTestCase; ?>" title="<?php print $displayListName;?>" <?php echo (array_key_exists($valueTestCase, $ts_selected) ) ? 'selected="selected"' : '';?>><?php print $displayListName;?></option>
													<?php
													}
												}elseif($sess_component =='GUI'){
												?>
												<option value="<?php echo $valueTestCase; ?>" title="<?php print $displayListName;?>" <?php echo (array_key_exists($valueTestCase, $ts_selected) ) ? 'selected="selected"' : '';?>><?php print $displayListName;?></option>
												<?php 
												}
													
											}										
											?>


										</select>								
										
                                        
                                        
                                        </td>
										<td width="6%" align="center" valign="middle"><input
											type="button" name="button" align="middle" id="button"
											value="&gt;&gt;"
											onclick="return fnMoveItems('<?php print $test_case;?>1','<?php print $test_case;?>2')" />
										<br />
										<br />
										<input type="button" name="button2" id="button2"
											value="&lt;&lt;"
											onclick="return fnAddItems('<?php print $test_case;?>2','<?php print $test_case;?>1')" /></td>
										<td width="57%" align="right" valign="middle"><select
											class="optionSelect" size="100"
											name="select_<?php print $test_case;?>2[]" multiple="multiple"
											id="<?php print $test_case;?>2" style="height: 70px; width: 480px;">
										</select></td>
									</tr>
									<tr>
										<td width="45%" align="left" valign="middle"></td>
										<td width="10%" align="center" valign="middle"><b>Number of
										times to be executed</b> <input id="nooftimes" type="text"
											name="nooftimes_<?php print $test_case;?>" value="1" size="5"
											align="middle" /></td>
										<td width="45%" align="right" valign="middle"></td>
									</tr>
								</table>
								</td>
							</tr>
						</table>
						</td>
					</tr>
					<?php
					$test_case++;
					}
					?>
					<!----Test Cases loop here------------------------->




					<tr>
						<td align="left" valign="top"><a
							href="http://10.70.111.21/testlink/index.php"
							title="To view excecuting testcases with Username= autovmgui and Password=autovmgui"
							target="_blank">Test Link</a></td>
					</tr>
                    
                    <tr>
						<td align="left" valign="top">						
						<?php 
						$usr = explode('~', $_SERVER['PHP_SELF']);						
						$usr = explode('/', $usr[1]);
						$usr[0];
						$_SERVER['HTTP_HOST'];
						
						?>
						
                        <?php if($sess_vmg_dir=='VMG2'){?>
                        <a	href="http://<?php echo $_SERVER['HTTP_HOST'].'/~'.$usr[0].'/automation/automation_report.php';?>" title="View Detailed Test Report"	target="_blank">Detailed Test Report</a></td>
                        <?php }?>
                        <?php if($sess_vmg_dir=='VMG1'){?>
                        <a	href="http://<?php echo $_SERVER['HTTP_HOST'].'/~'.$usr[0].'/automation/gen1_automation_report.php';?>" title="View Detailed Test Report"	target="_blank">Detailed Test Report</a></td>
                        <?php }?>
					</tr>
                    
                    
					<?php /*<tr>
						<td align="left" valign="top"><a
							href="https://wiki/mywiki/SanityTesting?highlight=%28sanity%29"
							title="Click to view Wiki Page" target="_blank">Wiki</a></td>
					</tr>*/ ?>

					<tr>
						<td align="left" valign="top">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="15%" align="center" valign="top">&nbsp;</td>
								<td align="center" valign="top"><input type="button"
									name="submit_test" id="submit_test" onclick="javascript:return callExecute();" value="Run Automation now" <?php echo ($disable_run_button==true) ? 'disabled="disabled"' : '';?> />
								<?php /*?><input type="button" name="submit_test2" id="submit_test2"
									value="Schedule Automation"
									onclick="return popWindow('scheduleUI.php');" /><?php */?> 
                                    <input type="button" name="submit_abort" id="submit_abort" value="Abort Process" onclick="return callAbort();" disabled="disabled" />
                                    
                                    <?php /*?><input
									type="button" name="submit_config" id="submit_config"
									value="Update Config" onclick="return updateConfig();" /><?php */?>
                                    
                                    </td>
								<td width="16%" align="center" valign="top">&nbsp;</td>
							</tr>							
							<tr>
								<td align="center" valign="top" colspan="3">
								<div id='imgdis' style="display:none;">
                                <div id="ajaxLoaderText"style="color:#FF0000; font-weight:bold;">Running Automation. Please wait...</div>
                                <img src="img/ajax-loader.gif" />
                                </div>
                                
                                <div id='imgdis_abort' style="display:none;">
                                <div id="ajaxLoaderText"style="color:#0066FF; font-weight:bold;">Aborting Process. Please wait...</div>                                
                                </div>
                                
                                
								</td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
				</form>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>


<div class="debug_out" id='testCaseDis'>&nbsp;</div>






<?php 
if(isset($running_test) && sizeof($running_test)>0){
	$count_verb = (sizeof($running_test) >1) ? 'are' :'is';
	echo '<div class="runningTestContainer">';
	echo '<div class="runningTestTitle">The following '.$G.' '.$count_verb.' currently running from your account :</div>';
	foreach ($running_test as $running_tst) {
		echo '<div class="running">'.$running_tst.'</div>';
	}
	echo '</div>';
}
?>



<script type="text/javascript">
// Email Over ride show / hide
$(document).ready(function(e) {
    $('input[name="email_notification"]').click(function(e) {
		//alert("hi");
		//override_email_holder
		 if($(this).is(':checked')){
			//alert("check");
			$('#email_label').removeClass('hide');
			$('input[name="emergency_email"]').removeClass('hide');
		}
		else{
			//alert("uncheck");
			$('#email_label').addClass('hide');
			$('input[name="emergency_email"]').val('');
			$('input[name="emergency_email"]').addClass('hide');
		}
	});
});



// Get check box check item's name

$(document).ready(function(e) {
    $("input[name^=itemSelect]:checked").each(function(){
		//alert($(this).val());
		var cb_val = $(this).val().split(':');
		var ts_name = cb_val[0];
		var ts_sr = cb_val[1];
		//alert(ts_name);
		$('table[id="'+ts_name+'"]').removeAttr('style');
		
		// Source Select
		//alert('From - select_'+ts_sr+'1');
		fnMoveItems(ts_sr+'1',ts_sr+'2');
		// Destination Select
		//alert('To - select_'+ts_sr+'2');
		
	});
});

/*$(document).ready(function(e) {
    $('div .debug_out').hide('fast');
	$('#show_debug').click(function(e) {
		alert('hi');
        $('div .debug_out').show('slow');
    });
});*/

</script>