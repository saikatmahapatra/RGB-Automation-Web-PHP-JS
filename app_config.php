<?php
/*
 * Error Handling
 */
error_reporting(0);

/*
 * Constants
 */

$automation_path = explode('/RGBAutomation',$_SERVER['SCRIPT_FILENAME']);
$automation_path = $automation_path[0];


define('DS',DIRECTORY_SEPARATOR);


define('AUTOMATION_DIR', $automation_path);
//echo AUTOMATION_DIR;


define('COMPONENT_DIR', 'components'.DS);
define('TEST_DIR', 'tests'.DS);
define('CONFIG_DIR', 'cfg'.DS.'main_cfgs'.DS);
define('TEST_CASE_DIR', 'testcases'.DS);
define('EXECUTION_CONFIG_DIR', 'execution_config_xml');

define('TPL_USER_CFG_PATH', 'includes/tpl_user_cfg.xls'); // blank user config file dir
define('TPL_VMG_CFG_PATH', 'includes/tpl_vmg_cfg.xls'); // blank vmg config file dir



/*
 * Exclude Directory Name While reading directory
 */
$automation_exclude_dir = array(
							'.svn',
							'.',
							'..',
							'console_logs',
							'disk_space',
							'resources',
							'shared_lib',
							'squish',
							'config',
							'phptest',
                            'RGBAutomation',
							'RGBAutomation1',

);
$version_exclude_dir = array(
							'.svn',
							'.',
							'..',
							'cfg',
							'logs',
);
$appconfig_test_component_name = array(
							'RPC'=>'RPC',
							'GUI'=>'GUI',
);

$appconfig_test_label_name = array(
						'sanity'=>'Sanity Testing',
						'regression'=>'Regression Testing',
						'loadtesting'=>'Load Testing',
);

/*
 * Set Suite Folder Custom Name to Display At Execution page
 * Example 
 * $appconfig_suite_name = array(
 * 	'devicereboot' => 'your Custom Name i.e Device Reboot',
 * 	)
 */
$appconfig_suite_name = array(
	'channel' => 'Channel',
	'deviceconfig' =>'Device Config',
	'deviceinfo'=>'Device Info',
	'devicereboot'=>'Device Reboot',
	'devicerestore'=>'Device Restore',
	'deviceupgrade'=>'Device Upgrade',
	'deviceuserauth'=>'Device User Authentication',
	'homepage'=>'Home Page',
	'deviceredundancy'=>'Device Redundancy',
	'bcttest'=>'BCT Test',
	'legacy'=>'Legacy',
	'loadtest'=>'Load Test',
);

/*
 * RPC Suite Order, to display at Execution page
 */
$appconfig_suite_order = array('deviceupgrade',
								'deviceconfig',
								'deviceinfo',
								'channel',
								'devicerestore',
								'deviceuserauth',
								'deviceredundancy',
								'devicereboot',
								'homepage',
								);

/*
 * GUI Suite Order, to display at Execution page
 */								
								
?>