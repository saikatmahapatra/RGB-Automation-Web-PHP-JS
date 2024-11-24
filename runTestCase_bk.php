<?
//print_r($_POST);
//exit;
$testSuitArr=array();
$count=0;
$secondParam="";
foreach($_POST['itemSelect'] as $keyTestSuite=>$valueTestSuite){
	$testSuiteArr=array();
	$testSuiteArr[$count]=str_replace('/','',$valueTestSuite);
	$secondParam.=strtolower($testSuiteArr[$count]).",";
	$testSuitePostArr='select_'.$testSuiteArr[$count].'2';
	$countSuite=0;
	foreach($_POST[$testSuitePostArr] as $keyTestCase=>$valueTestCase){
		$testCaseArr[$testSuiteArr[$count]][$countSuite]=$valueTestCase;
		$countSuite+=1;
	}
	$count+=1;
}


foreach($testCaseArr as $key=>$value){
	 $stringData="";
	 $absPath="/home2/rebacatest/public_html/cgi-bin/";
	 $filePath=$absPath.strtolower($key)."/".strtolower($key).'.cfg';
	 //exit;
	 foreach($value as $key1=>$value1){
	 $fp=fopen($filePath,'w')or die("can't open file");
	 $stringData.=$value1."\n";
	 //print $stringData;
	 }
	 //exit;
	 fwrite($fp, $stringData);
	 fclose($fp);
}
$firstParam="/home2/rebacatest/public_html/cgi-bin/main_driver.py";
$secondParam=substr($secondParam,0,-1);
$thirdParam=$_POST['uname'];
//> /home2/rebacatest/public_html/vmgtest/phptest/log.txt
$commandArg=$firstParam." -s ".$secondParam." -w -u ".$thirdParam." >/home2/rebacatest/public_html/vmgtest/phptest/log.txt";
print $commandStr="python ".$commandArg;
$strData="<?php\nsystem('".$commandStr."')\n?>";
$fp1=fopen('test.php','w')or die("can't open file");
fwrite($fp1, $strData);
fclose($fp1);
shell_exec("php test.php");
/*$strOP=shell_exec("mkdir sanity");
print $strOP;*/
/*system("python /home2/rebacatest/public_html/cgi-bin/main_driver.py -s sanity -w -u devraj >/home2/rebacatest/public_html/vmgtest/phptest/log.txt");*/
//print "python ".$commandArg;
//exit;
//shell_exec("python ".$commandArg);
//print $strOP;
//header("location:log.txt");
//exit;
//python /home2/rebacatest/public_html/cgi-bin/main_driver.py -s sanity,upgrade -w -u devraj     //Python command
?>