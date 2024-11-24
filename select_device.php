<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
	header("location:index.html");
}
include 'app_config.php';
require 'includes/DirectoryHelper.php';
$objDir = new DirectoryHelper();
// Read vmg related folder
$read_folder = $objDir->readDirectory(AUTOMATION_DIR);
$device = array_diff($read_folder, $automation_exclude_dir);


// Get from query string
$folder_vmg = isset($_GET['device']) ? $_GET['device'] : '';
$folder_version = isset($_GET['version']) ? $_GET['version'] : '';


// read version folder
if($folder_vmg){
	$read_version = $objDir->readDirectory(AUTOMATION_DIR.DS.$folder_vmg);
	$version = array_diff($read_version, $version_exclude_dir);
}


// Set session data
$_SESSION['sess_vmg']['name'] = $folder_vmg;
$_SESSION['sess_vmg']['version'] = $folder_version;

if(isset($_GET['redirect_page'])){
	header('location:'.$_GET['redirect_page'].'.php');
}

?>

<!DOCTYPE HTML>
<head>
<title>RGB Automation</title>
<link rel="icon" href="rgbfav.ico" />
<link rel="stylesheet" href="cssfiles/style-new.css" media="all">
</head>
<body>



<table width="100%">
<tr>
<td colspan="2" align="left" valign="top" class="logo_bg"><img align="top" src="../resources/images/rbgLogo-lg.png"></td>
</tr>
<tr>
<td align="left" valign="top" class="app_title">RGB Automation</td>
<td align="right"><?php echo $_SESSION['username'];?>, [ <a href="logout.php" title="Click here to Logout">Logout</a> ]</td>
</tr>
</table>


<div class="breadcrumb">
    <ul>
        <li>
        <?php if($_GET['device']){?>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>">Product</a>
        <?php } else{?>
        <span class="sel-val">Product</span>
        <?php }?>
        <span class="sel-val"><?php echo isset($_GET['device']) ? $_GET['device'] :'';?></span>        
        <span class="sep">&gt;&gt;</span>
        </li>
        
        <?php if(isset($_GET['device'])){?>        
        <li>Version</li>
        <?php }?>
    </ul>
</div>
<br/>

<?php if( isset($_GET['error']) && $_GET['error'] == 'file_not_found'){
echo '<div  style="font-size:12px;" class="error">Sorry, User configuration file does not exist</div>';
}?>




<table width="100%" height="%" border="0">
	<tr>
		<td><?php
		if(!$folder_vmg){
			echo '<h3>Select a Product</h3>';
			foreach ($device as $key=>$val){
				echo '<a href="'.$_SERVER['PHP_SELF'].'?device='.$val.'" title="">'.$val.'</a>';
				echo '<br>';
			}
		}
		if(isset($version)){
			echo '<h3>Select a Version</h3>';			
			foreach ($version as $key=>$val){
				echo '<a href="'.$_SERVER['PHP_SELF'].'?device='.$_GET['device'].'&version='.$val.'&redirect_page=menu" title="">'.str_replace('_','.',$val).'</a>';
				echo '<br>';
			}
		}
		?></td>

	</tr>
	<tr>
		<td></td>
	</tr>

</table>

</body>
</html>
