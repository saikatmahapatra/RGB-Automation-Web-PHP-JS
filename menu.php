<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
require 'app_config.php';
// Get all session info about selected vmg and version
$sess_vmg_dir = isset($_SESSION['sess_vmg']['name']) ? $_SESSION['sess_vmg']['name'] : '';
$sess_ver_dir = isset($_SESSION['sess_vmg']['version']) ? $_SESSION['sess_vmg']['version'] : '';

if ($sess_vmg_dir == '' && $sess_ver_dir =='') {
	header('location:select_device.php?d=0&v=0');
}

// Check component folder

$dir = AUTOMATION_DIR.DS.$sess_vmg_dir.DS.$sess_ver_dir.DS.TEST_DIR;
$res = scandir($dir);
$test_component = array_diff($res,array('.','..','.svn','__init__.py','__init__.pyc'));

?>

<!DOCTYPE HTML>
<head><title>RGB Automation</title>
<link rel="icon" href="rgbfav.ico" />
<link rel="stylesheet" href="cssfiles/style-new.css" media="all">
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
            <li>
                <a href="select_device.php">Product</a>
                <span class="sel-val"><?php echo $sess_vmg_dir;?></span>        
                <span class="sep">&gt;&gt;</span>
            </li>
            
            <li>
                <a href="select_device.php?device=<?php echo $sess_vmg_dir;?>">Version</a>
                <span class="sel-val"><?php echo str_replace('_','.',$sess_ver_dir);?></span>
                <span class="sep">&gt;&gt;</span> 
            </li>
            <li>
                Test Level
            </li>        
        </ul>
        </div>
        </td>
	</tr>
</table>




<?php //print_r($_SESSION);?>
<table width="100%" height="%" border="0">
<tr>

        <td class="navigation">       
        <h3>Testing Type and Component</h3>
        <a href="configuration.php" target="" class="menu_link" title="Configuration">Configuration</a></br>
        <?php
        // for this vmg , this version find component folder name
        // now for each component folder create test lable
         
        foreach ($test_component as $com_value) {
        	$i=0;
        	foreach ($appconfig_test_label_name as $lable=>$label_text){
        		if($com_value =='GUI'){        			
        			?>
        			<a href="execution.php?com=<?php echo strtolower($com_value);?>&label=sanity" class="menu_link"><?php echo $sess_vmg_dir.'&nbsp'.str_replace('_','.',$sess_ver_dir);?> Sanity Testing - <?php echo $com_value;?></a><br>
        			<?php
        			if($i=='0'){break;} 
        		}else {
        			?>
        			<a href="execution.php?com=<?php echo strtolower($com_value);?>&label=<?php echo $lable;?>" class="menu_link"><?php echo $sess_vmg_dir.'&nbsp'.str_replace('_','.',$sess_ver_dir);?> <?php echo $label_text;?> - <?php echo $com_value;?></a><br>
        			<?php 
        		}
        		?>
 
        		<?php 
        		$i++;
        	}	
        	//echo $com_value;
        }
        ?>       
    </td>

</tr>
<tr>
        <td>
                
        </td>
</tr>

</table>

</body>
</html>