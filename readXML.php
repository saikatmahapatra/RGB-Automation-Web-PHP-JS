<?php
if(!session_id()){
	session_start();
}

require 'Lib/XML2Array.php';
/*$string_xml = '<?xml version="1.0" encoding="utf-8"?>
<cfg vmg="vmg1" ver="3.5" test="sanity">
<channel>	
	<tc_selected>VMG1_RPC_22-1468</tc_selected>
	<tc_selected>VMG1_RPC_23-1468</tc_selected>
	<tc_selected>VMG1_RPC_28-1468</tc_selected>
</channel>
<deviceconfig>	
	<tc_selected>VMG1_RPC_6-1424</tc_selected>
	<tc_selected>VMG1_RPC_9-1424</tc_selected>		
</deviceconfig>
</cfg>
';*/
$xml_path = 'execution_config_xml/vmg1_sanity.xml';
$xml = simplexml_load_file($xml_path);
//print_r($xml);
//echo $xml->asXML();
$array = XML2Array::createArray($xml->asXML());
$global_config = $array['cfg'];
?>
