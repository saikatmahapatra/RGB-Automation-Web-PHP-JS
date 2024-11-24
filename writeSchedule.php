<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
/*************************************************/
// Created by : RGB Networks
// Created on : 10/08/2011
// Run selected test cases from automationrgbui.php 
// no input expected
/*************************************************/
//Function using for month to integer value conversion
function monthInt($month){
        switch($month)
        {
                case 'Jan' :
                                $returnVal=1;
                                break;
                case 'Feb' :
                                $returnVal=2;
                                break;
                case 'Mar' :
                                $returnVal=3;
                                break;
                case 'Apr' :
                                $returnVal=4;
                                break;
                case 'May' :
                                $returnVal=5;
                                break;
                case 'Jun' :
                                $returnVal=6;
                                break;
                case 'Jul' :
                                $returnVal=7;
                                break;
                case 'Aug' :
                                $returnVal=8;
                                break;
                case 'Sep' :
                                $returnVal=9;
                                break;
                case 'Oct' :
                                $returnVal=10;
                                break;
                case 'Nov' :
                                $returnVal=11;
                                break;
                case 'Dec' :
                                $returnVal=12;
                                break;
                case 'Default':
                                $returnVal=1;
                                break;
        }
        return $returnVal;
}
//all post data from schedule page
$conffile=$_POST['conffile'];
$from_date_time=$_POST['from_date_time'];
$username=$_POST['uname'];

//manupulating datetime inputs
$dateTimeArray=explode(" ",$from_date_time);
$dateArray=explode("-",$dateTimeArray[0]);
$timeArray=explode(":",$dateTimeArray[1]);
$month=monthInt($dateArray[1]);
$timestamp=mktime($timeArray[0],$timeArray[1],$timeArray[2],$month,$dateArray[0],$dateArray[2]);

//The URI which was given in order to access this page
$requestURL=($_SERVER['REQUEST_URI']);
// array which find out username from the URI
$usernameArr=explode('/',$requestURL);
// computing user name
$sudouname=substr($usernameArr[1],1,strlen($usernameArr[1]));
//taking current time stamp
$curTimestamp=time();

//check current time stamp
if($curTimestamp>$timestamp)
{
        print "<font color=red>Please select future date</font>";
        exit;
}
/*********************Check Booking Start***************************************/
/*$fileBookPath="/var/www/html/vmgBook.txt";
define("TEXT_FILE_B", $fileBookPath);
// number of lines to read from the end of file
define("LINES_COUNT_B", 1);

$fsizeB = round(filesize(TEXT_FILE_B)/1024/1024,2);
$linesB = read_file(TEXT_FILE_B, LINES_COUNT_B);
$incrementalArrB=explode("|",$linesB[0]);
$incrementalValB=$incrementalArrB[0];
$bookHandle = fopen($fileBookPath, "r");
$stringDataB="";
$countVal=0;
while(!feof($bookHandle)){
        $eachLineB=fgets($bookHandle);
        $valArrayB=explode("|",$eachLineB);
        $vmgBox=$valArrayB[1];
        $fromtimestamp=$valArrayB[2];
        $totimestamp=$valArrayB[3];
        $uname=$valArrayB[4];
        if(trim($valArrayB[0])!=""){
                if($uname==$sudouname && $vmgBox==$conffile){
                        if($timestamp>=$fromtimestamp && $timestamp<=$totimestamp){
                                if($valArrayB[5]=='N'){
                                $countVal=1;
                                }else{
                                $countVal=2;
                                }
                                $stringDataB.=$valArrayB[0]."|";
                                $stringDataB.=$valArrayB[1]."|";
                                $stringDataB.=$valArrayB[2]."|";
                                $stringDataB.=$valArrayB[3]."|";
                                $stringDataB.=$valArrayB[4]."|";
                                $stringDataB.="Y|";
                                $stringDataB.="\n";
                        }else{
                                $stringDataB.=$valArrayB[0]."|";
                                $stringDataB.=$valArrayB[1]."|";
                                $stringDataB.=$valArrayB[2]."|";
                                $stringDataB.=$valArrayB[3]."|";
                                $stringDataB.=$valArrayB[4]."|";
                                $stringDataB.=$valArrayB[5]."|";
                                $stringDataB.="\n";
                        }
                }else{
                                $stringDataB.=$valArrayB[0]."|";
                                $stringDataB.=$valArrayB[1]."|";
                                $stringDataB.=$valArrayB[2]."|";
                                $stringDataB.=$valArrayB[3]."|";
                                $stringDataB.=$valArrayB[4]."|";
                                $stringDataB.=$valArrayB[5]."|";
                                $stringDataB.="\n";
                }
        }
}
$fptmp=fopen($fileBookPath,'w')or die("can't open file");
fwrite($fptmp, $stringDataB);
fclose($fptmp);


if($countVal==0){
print "<font color=red>You dont have booked VMG in that duration</font>";
exit;
}
if($countVal==2){
print "<font color=red>Already scheduled in that duration</font>";
exit;
}*/
/**********************Check Booking End***************************************/
//full path for lock txt file
$filePath="/var/www/html/vmg.txt";                              
// define path for lock txt file
define("TEXT_FILE", $filePath);
// number of lines to read from the end of file
define("LINES_COUNT", 1);

// read lock txt file from the tail
function read_file($file, $lines) {
    //global $fsize;
    $handle = fopen($file, "r");
    $linecounter = $lines;
    $pos = -2;
    $beginning = false;
    $text = array();
    while ($linecounter > 0) {
        $t = " ";
        while ($t != "\n") {
            if(fseek($handle, $pos, SEEK_END) == -1) {
                $beginning = true; 
                break; 
            }
            $t = fgetc($handle);
            $pos --;
        }
        $linecounter --;
        if ($beginning) {
            rewind($handle);
        }
        $text[$lines-$linecounter-1] = fgets($handle);
        if ($beginning) break;
    }
    fclose ($handle);
    return array_reverse($text);
}
$fsize = round(filesize(TEXT_FILE)/1024/1024,2);
$lines = read_file(TEXT_FILE, LINES_COUNT);
$incrementalArr=explode("|",$lines[0]);
$incrementalVal=$incrementalArr[0];
if($incrementalVal=="")
{
        $incrementalVal=0;
}
$incrementalVal+=1;   // increment row index by 1

//initilize and build string to insert into lock txt file
$stringData="";
$stringData.=$incrementalVal."|";
$stringData.=strtolower($conffile)."|";
$stringData.=$timestamp."|";
//Prev user input eg: QA,Devraj
//$stringData.=$username."|"; 
// Now from url       
$stringData.=$sudouname."|";             
$stringData.=$sudouname."|";
foreach($_POST['itemSelect'] as $keyTestSuite=>$valueTestSuite){
        $stringData.=$valueTestSuite."|";
}
$stringData.="\n";


$handletmp=fopen($filePath,'r')or die("can't open lock file");
$dataArray=array();
$eachLine="";
$i=0;
while(!feof($handletmp))
{
        $eachLine=fgets($handletmp);
        $valArray=explode("|",$eachLine);
        if($valArray[1]!="")
        {
                $dataArray[$i]['incrVal']=$valArray[0];
                $dataArray[$i]['vmgBox']=strtolower($valArray[1]);
                $dataArray[$i]['timestamp']=$valArray[2];
        //duplicateSchedule($dataArray[$i]['vmgBox'],$dataArray[$i]['from_tm_hr'],$dataArray[$i]['from_tm_mm'],$dataArray[$i]['to_tm_hr'],$dataArray[$i]['to_tm_mm'],$dataArray[$i]['dayselect'],$conffile,$from_tm_hr,$from_tm_mm,$to_tm_hr,$to_tm_mm,$dayselect);
        }
        else
        {
                break;
        }       
$i+=1;
}
fclose($handletmp);
$fptmp=fopen($filePath,'a')or die("can't open lock file");
fwrite($fptmp, $stringData);
fclose($fptmp);

// initialize test suites array
$testSuitArr=array();
$count=0;
$testSuiteStr="";
foreach($_POST['itemSelect'] as $keyTestSuite=>$valueTestSuite)
{
        $testSuiteArr=array();
        $testSuiteArr[$count]=str_replace('/','',$valueTestSuite);
        $testSuiteStr.=strtolower($testSuiteArr[$count]).",";
        $testSuitePostArr='select_'.$testSuiteArr[$count].'2';
        $countSuite=0;
        foreach($_POST[$testSuitePostArr] as $keyTestCase=>$valueTestCase)
        {
                $testCaseArr[$testSuiteArr[$count]][$countSuite]=$valueTestCase;
                $countSuite+=1;
        }
        $count+=1;
}
//function for sorting test cases asc order eg VMGUI-7,VMGUI-8
function sortTestcase($arraysortTestCase)
{
        $arraysort=array();
        for($i=0;$i < count($arraysortTestCase);$i++)
        {
                 $testCase=explode("-",$arraysortTestCase[$i]);
                 $arraysort[$i]=$testCase[1];
        }
        for($x = 0; $x < count($arraysort); $x++)
        {
                for($y = $x; $y < count($arraysort); $y++) 
                {
                        if($arraysort[$x] > $arraysort[$y])
                        {
                                $hold = $arraysort[$x];
                                $arraysort[$x] = $arraysort[$y];
                                $arraysort[$y] = $hold;
                        }
                }
        }
        $returearray=array();
        for($j=0;$j < count($arraysort);$j++)
        {
                $returearray[$j]="VMGUI-".$arraysort[$j];
        }
        return $returearray;
}
foreach($testCaseArr as $key=>$value)
{
         $testCaseStr="";
         $filePath="";
         //absoulte path where file will create
         $absPath="/var/www/html/";
         //file creating with testsuite,vmg and current timestamp
         $filePath=$absPath."/".strtolower($key)."_".$timestamp."_".$conffile.'.cfg';
         //$value=sortTestcase($value);   //remove sorting of testcases
         foreach($value as $key1=>$value1)
         {
                 $fp=fopen($filePath,'w')or die("can't open newly gernerated file");
                 $testCaseStr.=$value1."\n";
         }
         chmod($filePath,0777);
         /*print $testCaseStr;
         exit;*/
         fwrite($fp,$testCaseStr);
         fclose($fp);
}
//reponse to schedule main page
print "<font color='red'>Your request has been scheduled at   ".$from_date_time."</font>";
exit;
?>
