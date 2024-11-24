<?php

/*
 * Read Directory
 * And generate folder file tree in array
 */
class DirectoryHelper{

	var $path;
	var $arr_tree;


	function __construct() {

	}

	function readDirectory($dir=NULL){
        //echo $dir;
		$result = array();
		$exclude = array('.','..','.svn');
		$res = scandir($dir);
		foreach (new DirectoryIterator($dir) as $file){
			if($file->isDot()) continue;
			if($file->isDir())			{
				$result[]=$file->getFilename();
			}
		}
		return $result;
	}
	
function dirToArray($dir) {
		$result = array();
		$cdir = scandir($dir);
		foreach ($cdir as $key => $value){
			if (!in_array($value,array(".",".."))){
				if (is_dir($dir . DIRECTORY_SEPARATOR . $value)){
					$result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
				}
				else{
					$result[] = $value;
				}
			}
		}
		return $result;
	}

	function php_file_tree($directory, $return_link=NULL, $extensions = array()) {
		// Generates a valid XHTML list of all directories, sub-directories, and files in $directory
		// Remove trailing slash
		$code ="";
		if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
		$code .= $this->php_file_tree_dir($directory, $return_link, $extensions);
		return $this->php_file_tree_dir($directory, $return_link, $extensions);
		return $code;
	}

	function php_file_tree_dir($directory, $return_link, $extensions = array(), $first_call = true) {

		// Recursive function called by php_file_tree() to list directories/files

		// Get and sort directories/files
		if( function_exists("scandir") ) $file = scandir($directory); else $file = $this->php4_scandir($directory);
		natcasesort($file);
		// Make directories first
		$files = $dirs = array();
		foreach($file as $this_file) {
			if( is_dir("$directory/$this_file" ) ) $dirs[] = $this_file; else $files[] = $this_file;
		}
		$file = array_merge($dirs, $files);
		// echo '<pre>';
		// print_r($file);
		// echo '<pre>';
		// Filter unwanted extensions
		if( !empty($extensions) ) {
			foreach( array_keys($file) as $key ) {
				if( !is_dir("$directory/$file[$key]") ) {
					$ext = substr($file[$key], strrpos($file[$key], ".") + 1);
					if( !in_array($ext, $extensions) ) unset($file[$key]);
				}
			}
		}

		if( count($file) > 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
			$arr_tree =array();
			$php_file_tree = "<ul";
			if( $first_call ) {
				$php_file_tree .= " class=\"php-file-tree\""; $first_call = false;
			}

			$php_file_tree .= ">";
			$level = 0;
			foreach( $file as $this_file ) {
					
				if( $this_file != "." && $this_file != ".." ) {
					if( is_dir("$directory/$this_file") ) {
						// Directory
						$php_file_tree .= "<li class=\"pft-directory\"><a href=\"#\">" . htmlspecialchars($this_file) . "</a>";
						$php_file_tree .= $this->php_file_tree_dir("$directory/$this_file", $return_link ,$extensions, false);
						$php_file_tree .= "</li>";
							
						// Array version
							
						$arr_tree['dir'][htmlspecialchars($this_file)] = $this->php_file_tree_dir("$directory/$this_file",'');
					} else {
						// File
						// Get extension (prepend 'ext-' to prevent invalid classes from extensions that begin with numbers)
						$ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1);
						$link = str_replace("[link]", "$directory/" . urlencode($this_file), $return_link);
						$php_file_tree .= "<li class=\"pft-file " . strtolower($ext) . "\"><a href=\"$link\">" . htmlspecialchars(
						$this_file) . "</a></li>";
							
						// Array version
						$arr_tree['file'][htmlspecialchars($this_file)] = $this_file;
					}
				}
				$level++;
			}
			$php_file_tree .= "</ul>";
		}
		//return $php_file_tree;
		// echo '<pre>';
		// print_r($arr_tree);
		// echo '</pre>';
		return $arr_tree;
	}

	// For PHP4 compatibility
	function php4_scandir($dir) {
		$dh  = opendir($dir);
		while( false !== ($filename = readdir($dh)) ) {
			$files[] = $filename;
		}
		sort($files);
		return($files);
	}

	function get_generation($tree){
		$res = array();
		foreach($tree as $typeName=>$typeContentArray){
			if($typeName == 'dir'){
				foreach($typeContentArray as $key=>$val){
					$res[] = $key;
				}
			}
		}
		return $res;
	}

	function get_version($tree,$gen){

		$res = array();
		$ver = $tree['dir'][$gen]['dir'];
		foreach($ver as $versionName=>$versionContentArray){
			if($versionName !='cfg'){
				if($versionName){
					$res[] = $versionName;
				}
			}
		}
		return $res;
	}

	function get_component($tree,$gen,$version){
		$res = array();
		$component = $tree['dir'][$gen]['dir'][$version]['dir']['test']['dir'];
		foreach($component as $compName=>$compContentArray){
			if($compName){
				$res[] = $compName;
			}
		}
		return $res;
	}

	function getDropdown($data){
		$html='';
		foreach($data as $key=>$val){

			$html.='<option value="'.$val.'">'.$val.'</option>';
		}
		return $html;
	}

	function getDeviceConfig($dataArray){
		// Array contains => 10.32.111.1_cfg.xls , gouri_cfg.xls
		// Use regualr expressions to get files starting with any ip address
		//$valid = preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $string);
		$result = array();
		if(isset($dataArray) && (sizeof($dataArray)>0) ){
			foreach ($dataArray as $key=>$val){
				$splittedFileName = explode('_', $key);
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
		return $result;
	}
	function get_test_suite($tree,$product_gen,$version,$component){
		$res = array();
		return $data = $tree['dir'][$product_gen]['dir'][$version]['dir']['test']['dir'][$component]['dir'];
		//echo '<pre>';
		//print_r($data);
		foreach($data as $compName=>$compContentArray){
			if($compName){
				$res[] = $compName;
			}
		}
		return $res;
	}

}