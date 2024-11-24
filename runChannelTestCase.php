<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
/*************************************************/
// Created by : RGB Networks
// Created on : 01/08/2011
// Run selected test cases from channelExtra.php 
// no input expected
/*************************************************/
//set_time_limit(1000000000);            
//set page execution time out unlimited
ini_set("max_execution_time",0);
//The URI which was given in order to access this page   
$requestURL=($_SERVER['REQUEST_URI']); 
// array which find out username from the URI  
$usernameArr=explode('/',$requestURL);
// computing user name   
$nodeusername=substr($usernameArr[1],1,strlen($usernameArr[1]));
// email notification param 
$emailnotification=' -m ';

$testcase=$_POST['testcase'];
$notcm=$_POST['notcm'];
$nochannel=$_POST['nochannel'];
$output=$_POST['output'];

$stringData="";
$absPath="/home2/".$nodeusername."/public_html/cgi-bin/";
$filePath=$absPath."/channelextra/channelextra.cfg".$username;
// create automationlog file
exec("touch ".$filePath);
exec("chmod 777 ".$filePath);
exec("dos2unix ".$filePath);
//openning testsuite.cfg file
$fp=fopen($filePath,'w')or die("can't open file");
$stringData.=$testcase."\n";
fwrite($fp, $stringData);
fclose($fp);

exec('ps aux | grep main_driver.py | grep apache | grep -i "w '.$username.'"',$output2);

//exec("ps aux | grep main_driver.py | grep ".$nameIDArray[$username],$output3);
/*print "ps aux | grep main_driver.py | grep ".$nameIDArray[$username];
exit;
print "<pre>";
print_r($output3);
print "</pre>";
exit;
*/
if(count($output2) >1 || count($output3) > 1){
        /*print "Test cases already executing please stop prior processes before run another <a href='http://10.70.111.51/~".$username."/vmgtest/phptest/automationrgbui.php'>click here </a>";*/
        print "<font color='red'><b>Test cases already executing! Please stop the running processes to execute the new ones.</b></font>";
        exit;
}
// create automationlog file
exec("touch /home2/".$nodeusername."/public_html/vmgtest/phptest/".$username."_automationLog.txt");
exec("chmod 777 /home2/".$nodeusername."/public_html/vmgtest/phptest/".$username."_automationLog.txt");
exec("dos2unix /home2/".$nodeusername."/public_html/vmgtest/phptest/".$username."_automationLog.txt");

$firstParam="/home2/".$nodeusername."/public_html/cgi-bin/main_driver.py";    
$secondParam="channelextra -t ".$testcase;
$thirdParam=$username;
$fourthParam=" -a ".$notcm." -c ".$nochannel." -o ".$output;
//build the command which need to execute       
$commandArg=$firstParam." -s ".$secondParam." -w ".$username.$emailnotification." -u ".$thirdParam.$fourthParam." >/home2/".$nodeusername."/public_html/vmgtest/phptest/".$username."_automationLog.txt";

/*print "/usr/local/bin/python ".$commandArg;
exit;*/
//commented line use for debugging 
/*print "/usr/local/bin/python ".$commandArg;
print "<br>";
exit;*/                
//execute the command
shell_exec("source /etc/profile;/usr/local/staf/STAFEnv.sh;/usr/local/bin/python ".$commandArg." &");
//shell_exec("/usr/local/bin/python ".$commandArg);

print "<font color='#000066'><b>Executing<br>$testcase<br>It might take several minutes to complete the cycle. The report link will be dispatched to the email address after the cycle ends. The User can move to the home page.</b></font>";
exit;
?>