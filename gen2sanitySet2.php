<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
/*************************************************/
// Created by : RGB Networks
// Created on : 09/11/2012
// Selected test cases and run at now or schedule 
// no input expected
/*************************************************/


$arrayOrder=array("gen2upgradekeepconfig","gen2channelpresetset2","gen2packagerconfigset2","gen2packagertestcaseset2","gen2bulkandreboot");


//The URI which was given in order to access this page
$requestURL=($_SERVER['REQUEST_URI']);

// array which find out username from the URI
$usernameArr=explode('/',$requestURL);

// computing user name
$nodeusername=substr($usernameArr[1],1,strlen($usernameArr[1]));

//Path of configuration file 
$cfgPath="/home2/".$nodeusername."/public_html/cgi-bin/vmg_lib/main_cfgs/";    

/*****************************Parsing Configuration File****************************************************/

//define file of configuration file
define("TEXT_FILE", $cfgPath."server.cfg.".$username);

// number of lines to read from the end of file
$handle = fopen(TEXT_FILE, "r");
$i=0;
$cfgArray=array();
while(!feof($handle))
{
    $cfgArray[$i]=fgets($handle);
    $i+=1;
}

$configArr=array();
$count=0;

foreach($cfgArray as $value)
{
    if(trim($value)!="")
        {
        $matches=array();
                //match with variable and value format with configuartion file
        if(preg_match_all("/(.*?)\((.*?)\)\s+\"(.*?)\"/",$value,$matches))
                {
                        $j=0;
                        foreach($matches as $val)
                        {
                                if(!empty($val))
                                {
                                        $configArr[$count][$j]=trim($val[0]);
                                        $j+=1;
                                }
                        }
                }
                else
                {
                $configArr[$count][0]="";
                $configArr[$count][1]="";
                $configArr[$count][2]="";
                $configArr[$count][3]=$value;
                }
        $count+=1;
        }               
}

foreach($configArr as $key=>$value)
{
        if($value[1]=='WIN' && $value[2]=='VNC_IP')
                $TEMP_HOST_IP_WIN=$value[3];           //store Host Ip for Windows in temp var
        
        if($value[1]=='LIN' && $value[2]=='VNC_IP')
                $TEMP_HOST_IP_LIN=$value[3];           //store Host Ip for Linux in temp var
        
        if($value[1]=='MAC' && $value[2]=='VNC_IP')
                $TEMP_HOST_IP_MAC=$value[3];           //store Host Ip for MAC in temp var
        
        if($value[1]=='WIN' && $value[2]=='UNAME')
                $TEMP_UNAME_WIN=$value[3];             //store Username for Windows in temp var
        
        if($value[1]=='LIN' && $value[2]=='UNAME')
                $TEMP_UNAME_LIN=$value[3];             //store Username for Linux in temp var
        
        if($value[1]=='MAC' && $value[2]=='UNAME')
                $TEMP_UNAME_MAC=$value[3];             //store Username for MAC in temp var
        
        if($value[1]=='WIN' && $value[2]=='PASS')
                $TEMP_PASS_WIN=$value[3];              //store Password for Windows in temp var
        
        if($value[1]=='LIN' && $value[2]=='PASS')
                $TEMP_PASS_LIN=$value[3];              //store Password for Linux in temp var
        
        if($value[1]=='MAC' && $value[2]=='PASS')
                $TEMP_PASS_MAC=$value[3];              //store Password for MAC in temp var

        if($value[1]=='WIN' && $value[2]=='status' && $value[3]=='1')   //check status for OS if '1' it means using this OS
        {     
                $HOST_IP=$TEMP_HOST_IP_WIN;
                $UNAME=$TEMP_UNAME_WIN;
                $PASS=$TEMP_PASS_WIN;
        }
        
        if($value[1]=='LIN' && $value[2]=='status' && $value[3]=='1')     //check status for OS if '1' it means using this OS
        {
                $HOST_IP=$TEMP_HOST_IP_LIN;
                $UNAME=$TEMP_UNAME_LIN;
                $PASS=$TEMP_PASS_LIN;
        }
        
        if($value[1]=='MAC' && $value[2]=='status' && $value[3]=='1')      //check status for OS if '1' it means using this OS
        {
                $HOST_IP=$TEMP_HOST_IP_MAC;
                $UNAME=$TEMP_UNAME_MAC;
                $PASS=$TEMP_PASS_MAC;
        }       
}
        if($HOST_IP=="")
                $HOST_IP="Default Info : 10.70.111.61:2";     //Default Host IP
        if($UNAME=="")
                $UNAME="autovmgui";                           //Default Username
        if($PASS=="")
                $PASS="pass1234";                            //Default Password
        $vmgbox="HOST -".$HOST_IP." ,Username -".$UNAME." ,Password -".$PASS;   //information for VNC information


/*****************************Parsing SERVER.CFG File****************************************************/

/*//function for sorting test cases asc order eg VMGUI-7,VMGUI-8
function sortTestcase($arraysortTestCase,$folderName)
{
        $arraysort=array();
        for($i=0;$i < count($arraysortTestCase[$folderName]);$i++)
        {
                $testCase=explode("-",$arraysortTestCase[$folderName][$i]);
                if($testCase[1]!="")
                        $arraysort[$folderName][$i]=$testCase[1];
                else
                        $arraysort[$folderName][$i]=$testCase[0];
        }
        
        for($x = 0; $x < count($arraysort[$folderName]); $x++) 
        {
                for($y = $x; $y < count($arraysort); $y++) 
                {
                        if($arraysort[$folderName][$x] > $arraysort[$folderName][$y])
                        {
                                $hold = $arraysort[$folderName][$x];
                                $arraysort[$folderName][$x] = $arraysort[$folderName][$y];
                                $arraysort[$folderName][$y] = $hold;
                        }
                }
        }
        //print_r($arraysort);
        //print count($arraysort);
        $returearray=array();
        for($j=0;$j < count($arraysort[$folderName]);$j++)
        {
                 //if(strlen($arraysort[$folderName][$j])<5)
                        $returearray[$folderName][$j]="VMGUI-".$arraysort[$folderName][$j];
                 //else
                        //$returearray[$folderName][$j]=$arraysort[$folderName][$j];
        }
        return $returearray;
}*/


/**********************Read TestCases*******************************************/
$testcaseArray=array();

foreach($arrayOrder as $folderName){
        $folderName=strtolower($folderName);
        $dirPathP="/home2/".$nodeusername."/public_html/cgi-bin/".$folderName."/testcases";
        
        // read directory
        if ($handleP = opendir($dirPathP)) {
                //define array to store python files
                $l=0;
                /*loop over the directory. */
                while (false !== ($fileP = readdir($handleP))){
                        //if(preg_match('/Driver*/',$file)){
                                if(preg_match('/py/',$fileP)){
                                        $fileSplit=explode("-",$fileP);
                                        $testcaseNumber=explode("_",$fileSplit[1]);
                                        if($testcaseNumber[0]!="")
                                                $arrayIndex=$testcaseNumber[0];
                                        else
                                                $arrayIndex=$fileP;
                                        $testcaseArray[$folderName][$arrayIndex]=$fileP;
                                        $l+=1;
                                }
                        //}
                }
                closedir($handleP);
                ksort($testcaseArray[$folderName]);
        }
        
}
/**********************Read TestCases*******************************************/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VMG UI Test Automation</title>
<link rel="icon" href="rgbfav.ico" />
<link href="cssfiles/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-1.3.2.js"></script>
<script type="text/javascript" src="jsfiles/common.js"></script>
<!--<script type="text/javascript" src="jsfiles/chainedSelects.js"></script>-->
</head>
<body onload="unchecked();">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
                <td align="center" valign="top">
                        <table width="800" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                                <td width="288" align="left" valign="bottom"><a href="home.php" style="font-size:medium;">Home</a></td>
                                <td width="512" align="center" valign="middle" class="header_text"><img src="img/RGB_Logo.jpg" alt="" width="200" height="63" align="left" /></td>
                                <td width="288" align="right" valign="bottom"><a href="gen2setSanity.php" style="font-size:medium;">Back</a></td>
                          </tr>
                        </table>
                </td>
        </tr>
        <tr>
                <td align="center" valign="top" style="border-bottom:#999999 1px solid;"></td>
        </tr>
        <tr>
                <td align="center" valign="top">
                        <table width="800" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                                <td height="40" align="center" valign="middle" class="header_text"><span style="border-bottom:#999999 1px solid;">VMG UI Test Automation Framework Schedule</span></td>
                         </tr>
                         <tr>
                                <td align="left" valign="top">
                                <form name="caseform" id='caseform' action="#" method="post" enctype="multipart/form-data" onsubmit="return callExecute();">
                                                <table width="800" border="1" align="center" cellpadding="0" cellspacing="0">
                                                  <tr>
                                                        <td align="left" valign="top">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                        <tr>
                                                                         <?php 
                                                                                $suiteCount=0;
                                                                                foreach($arrayOrder as $folderName){
                                                                          ?>
                                                                                  <td width="3%" align="left" valign="middle"><input type="checkbox" name="itemSelect[]" class='itemSelect' id="itemSelect" value=<?php print strtolower($folderName);?> title="Please check to view the test cases of <?php print $folderName;?>"/></td>
                                                                                  <td width="11%" align="left" valign="middle" nowrap="nowrap">
                                                                                  <?php
                                                                                  if(strtolower($folderName)=="gen2upgradekeepconfig"){
                                                                                        print "Gen2 Upgrade keeping configuration";
                                                                                  }
                                                                                  else if(strtolower($folderName)=="gen2channelpresetset2"){
                                                                                        print "Gen2 Channel Preset Set - 2";
                                                                                  }
                                                                                  else if(strtolower($folderName)=="gen2packagerconfigset2"){
                                                                                        print "Gen2 Packager Config Set - 2";
                                                                                  }
                                                                                  else if(strtolower($folderName)=="gen2packagertestcaseset2"){
                                                                                        print "Gen2 Packager Testcases Set - 2";
                                                                                  }
                                                                                  else if(strtolower($folderName)=="gen2bulkandreboot"){
                                                                                        print "Gen2 Bulk Export Import Reboot";
                                                                                  }
                                                                                  
                                                                                  else{
                                                                                        print ucwords($folderName);
                                                                                  }
                                                                                  
                                                                                  ?></td>
                                                                         <?php
                                                                                        if($suiteCount>3){
                                                                                                //print $suiteCount;
                                                                                                $suiteCount=-1;
                                                                                                print "</tr>";
                                                                                                print "<tr>";
                                                                                        }
                                                                                $suiteCount+=1;
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
                                                                          <td width="43%" align="center" valign="middle"><b>Test Cases for Execution</b></td>
                                                                        </tr>
                                                                </table>
                                                   </td>
                                                 </tr>
                                                 
                                                 
                                                 <!----Test Cases loop here------------------------->
                                                 <?php
                                                        foreach($testcaseArray as $Key=>$Value){
                                                                /*print $Key;
                                                                print "<br>";*/
                                                                  if(strtolower($Key)=="gen2upgradekeepconfig"){
                                                                        $displayKey="Gen2 Upgrade keeping configuration";
                                                                  }
                                                                  else if(strtolower($Key)=="gen2channelpresetset2"){
                                                                        $displayKey="Gen2 Channel Preset Set - 2";
                                                                  }
                                                                  else if(strtolower($Key)=="gen2packagerconfigset2"){
                                                                        $displayKey="Gen2 Packager Config Set - 2";
                                                                  }
                                                                  else if(strtolower($Key)=="gen2packagertestcaseset2"){
                                                                        $displayKey="Gen2 Packager Testcases Set - 2";
                                                                  }
                                                                  else if(strtolower($Key)=="gen2bulkandreboot"){
                                                                        $displayKey="Gen2 Bulk Export Import Reboot";
                                                                  }
                                                                  
                                                                  else{
                                                                        $displayKey=ucwords($Key);
                                                                  }
                                                 ?>
                                                 <tr>
                                                        <td align="left" valign="top">
                                                                <table width="100%" border="0" cellspacing="2" cellpadding="2" id="<?php print $Key;?>" style="display:none;">
                                                                  <tr>
                                                                        <td height="25" align="left" valign="top"><b><?php print ucwords($displayKey);?></b></td>
                                                                  </tr>
                                                                        <?php 
                                                                                if($Key=="parameter"){
                                                                        ?>
                                                                  <tr>
                                                                        <td align="left" valign="top">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                        
                                                                   <tr>
                                                                         <td width="43%" align="left" valign="middle">From &nbsp;&nbsp;&nbsp;<select name="frominput" id="frominput">
                                                                                  <?php
                                                                                        for($j=1;$j<=500;$j++){
                                                                                  ?>
                                                                                  <option value="<?php print $j;?>"><?php print $j;?></option>
                                                                                  <?php
                                                                                        }
                                                                                  ?>
                                                                                  </select></td>
                                                                                  <td width="16%" align="left" valign="middle"></td>
                                                                                  <td width="41%" align="left" valign="middle">To &nbsp;&nbsp;&nbsp;<select name="toinput" id="toinput">
                                                                                  <?php
                                                                                        for($d=1;$d<=500;$d++){
                                                                                  ?>
                                                                                  <option value="<?php print $d;?>"><?php print $d;?></option>
                                                                                  <?php
                                                                                        }
                                                                                  ?>
                                                                                  </select></td>
                                                                  </tr>
                                                                                  
                                                                        </table>
                                                                  </td>
                                                                </tr>
                                                                <?php
                                                                }
                                                                ?>
                                <!---------------------------Move and remove all button------------------------------------------->                     
                                                                 <tr>
                                                                        <td align="left" valign="top">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                        
                                                                   <tr>
                                                                         <td width="43%" align="left" valign="middle"><input type="button" name="addAll" value="Move All" onclick="return fnSelectAllItems('<?php print $Key;?>1','<?php print $Key;?>2')"/></td>
                                                                          <td width="16%" align="left" valign="middle"></td>
                                                                          <td width="41%" align="left" valign="middle"><input type="button" name="removeAll" value="Remove All" onclick="return fnDeSelectAllItems('<?php print $Key;?>1','<?php print $Key;?>2')"/> </td>
                                                                   </tr>
                                                                                  
                                                                        </table>
                                                                  </td>
                                                                </tr>
                                        <!---------------------------Move and remove all button------------------------------------------->                     
                                                                  <tr>
                                                                        <td align="left" valign="top">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                  <td width="47%" align="left" valign="middle" >
                                                                                  <select size="100" name="select_<?php print $Key;?>1[]" multiple="multiple" id="<?php print $Key;?>1" style="height:70px;width:480px;">
                                                                                        <?php 
                                                                                        foreach($Value as $valueTestCase){
                                                                                        list($testCaseName,$ext)=split("\.",$valueTestCase);
                                                                                        $displayListName=$testCaseName;
                                                                                        $valueArr=explode("-",$displayListName);
                                                                                        $valueArr1=explode("_",$valueArr[1]);
                                                                                        if(!preg_match('/Driver*/',$valueArr[0]))
                                                                                                $valueTestCase=$valueArr[0]."-".$valueArr1[0];
                                                                                        else
                                                                                                $valueTestCase=$valueArr[0];
                                                                                        ?>
                                                                                                <option value="<?php print $valueTestCase;?>" title="<?php print $displayListName;?>"><?php print $displayListName;?></option>
                                                                                         <?php
                                                                                         }
                                                                                         ?>></select></td>
                                                                                  <td width="6%" align="center" valign="middle"><input type="button" name="button" align="middle" id="button" value="&gt;&gt;" onclick="return fnMoveItems('<?php print $Key;?>1','<?php print $Key;?>2')" />
                                                                                          <br />
                                                                                          <br />
                                                                                          <input type="button" name="button2" id="button2" value="&lt;&lt;" onclick="return fnAddItems('<?php print $Key;?>2','<?php print $Key;?>1')"/></td>
                                                                                  <td width="57%" align="right" valign="middle"><select class="optionSelect" size="100" name="select_<?php print $Key;?>2[]" multiple="multiple" id="<?php print $Key;?>2" style="height:70px;width:480px;">
                                                                                        </select></td>
                                                                                </tr>
                                                                                <tr>    <td width="45%" align="left" valign="middle" ></td>
                                                                                        <td width="10%" align="center" valign="middle"><b>Number of times to be executed</b><input id="nooftimes" type="text" name="nooftimes_<?php print $Key;?>" value="1" size="5" align="middle"/></td>
                                                                                        <td width="45%" align="right" valign="middle" ></td>
                                                                                </tr>
                                                                                
                                                                                
                                                                        </table>
                                                                        </td>
                                                                  </tr>
                                                           </table>
                                                  </td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                                 <!----Test Cases loop here------------------------->
                                        
                                                 <tr>
                                                        <td align="left" valign="top"><a href="http://10.70.111.21/testlink/index.php" title="To view excecuting testcases with Username= autovmgui and Password=autovmgui" target="_blank">Test Link</a></td>
                                                 </tr>
                                                 <tr>
                                                        <td align="left" valign="top"><a href="https://wiki/mywiki/SanityTesting?highlight=%28sanity%29" title="Click to view Wiki Page" target="_blank">Wiki</a></td>
                                                 </tr>
                                                 <tr>
                                                        <td align="left" valign="top">VNC Info for client for selenium: <?php print $vmgbox;?></td>
                                                 </tr>
                                                 <tr>
                                                        <td align="left" valign="top">
                                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                  <tr>
                                                                        <td width="15%" align="center" valign="top">&nbsp;</td>
                                                                        <td align="center" valign="top"><input type="submit" name="submit_test" id="submit_test" value="Run Automation now" />
                                                                          <input type="button" name="submit_test2" id="submit_test2" value="Schedule Automation" onclick="return popWindow('scheduleUI.php');"/>
                                                                           <input type="button" name="submit_abort" id="submit_abort" value="Abort Process" onclick="return callAbort();"/>
                                                                          </td>
                                                                        <td width="16%" align="center" valign="top">&nbsp;</td>
                                                                  </tr>
                                                                  <tr>
                                                                        <td align="center" valign="top" colspan="3"><div id='testCaseDis' style="color:#000066">&nbsp;</div></td>
                                                                  </tr>
                                                                   <tr>
                                                                        <td align="center" valign="top" colspan="3"><div id='imgdis' style="display:none"><img src="img/progress_bar.gif" /></div></td>
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