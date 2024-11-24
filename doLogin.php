<?php
session_start();
$serverAddr=$_SERVER['SERVER_ADDR'];
$requestURL = $_SERVER['REQUEST_URI'];
$usernameArr=explode('/',$requestURL);
$nodeusername=substr($usernameArr[1],1,strlen($usernameArr[1]));

$is_ajax = $_REQUEST['is_ajax'];
if(isset($is_ajax) && $is_ajax)
{
	//print $serverAddr."/~".$nodeusername."/user_info.json";
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	//$input = file_get_contents("/home2/".$nodeusername."/public_html/cgi-bin/vmg_lib/user_info/user_info.json");
	#$input = file_get_contents("http://".$serverAddr."/~".$nodeusername."/cgi-bin/vmg_lib/user_info/user_info.json");

	$input = file_get_contents("../shared_lib/user_info/user_info.json");
	//echo __FILE__;
	//print_r($input);
	//die();
	$json=json_decode($input);
	$jsonUname = trim($json->$username->uid);
	$jsonPass = trim($json->$username->pw);

	if($username == $jsonUname && $password == $jsonPass){
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		echo "success";
	}
}

?>