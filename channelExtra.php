<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
/*************************************************/
// Created by : RGB Networks
// Created on : 01/08/2011
// Run Channel Extra test case 
// no input expected
/*************************************************/


// include file to use testlink API's
require_once 'util.php';
// include file to use testlink API's            
require_once 'sample.inc.php';
$unitTestDescription="Test - getTestSuitesForTestPlan";
$data=array();
$debug=false;            
//The URI which was given in order to access this page
$requestURL=($_SERVER['REQUEST_URI']);
// array which find out username from the URI
$usernameArr=explode('/',$requestURL);
// computing user name
$nodeusername=substr($usernameArr[1],1,strlen($usernameArr[1]));
//Path of configuration file 
$cfgPath="/home2/".$nodeusername."/public_html/cgi-bin/vmg_lib/main_cfgs/";    

/*****************************Parsing Configuration File****************************************************/
//deifne file of configuration file
define("TEXT_FILE", $cfgPath."server.cfg.".$username);

$tcmList=10;
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
        $TEMP_HOST_IP_LIN=$value[3];                    //store Host Ip for Linux in temp var
        
        if($value[1]=='MAC' && $value[2]=='VNC_IP')
        $TEMP_HOST_IP_MAC=$value[3];                    //store Host Ip for MAC in temp var
        
        if($value[1]=='WIN' && $value[2]=='UNAME')
        $TEMP_UNAME_WIN=$value[3];                              //store Username for Windows in temp var
        
        if($value[1]=='LIN' && $value[2]=='UNAME')
        $TEMP_UNAME_LIN=$value[3];                              //store Username for Linux in temp var
        
        if($value[1]=='MAC' && $value[2]=='UNAME')
        $TEMP_UNAME_MAC=$value[3];                              //store Username for MAC in temp var
        
        if($value[1]=='WIN' && $value[2]=='PASS')
        $TEMP_PASS_WIN=$value[3];                               //store Password for Windows in temp var
        
        if($value[1]=='LIN' && $value[2]=='PASS')
        $TEMP_PASS_LIN=$value[3];                               //store Password for Linux in temp var
        
        if($value[1]=='MAC' && $value[2]=='PASS')
        $TEMP_PASS_MAC=$value[3];                               //store Password for MAC in temp var

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


function getResponseRPC($dataArr,$debugmode,$dateType)
{
        // test link API's url
        $server_url="http://10.70.111.21/testlink/lib/api/xmlrpc.php";    
        $client = new IXR_Client($server_url);
        $client->debug=$debugmode;
        if(!$client->query($dateType, $dataArr))
        {
                echo "something went wrong - " . $client->getErrorCode() . " - " . $client->getErrorMessage();                  
                $response=NULL;
        }
        else
        {
                $response=$client->getResponse();
        }
        return $response;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VMG UI Test Automation</title>
<link rel="icon" href="rgbfav.ico" />
<link href="cssfiles/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-1.3.2.js"></script>
<script type="text/javascript" src="jsfiles/commonChannel.js"></script>
<script type="text/javascript" src="jsfiles/chainedSelects.js"></script>
</head>
<body onload="comboSelect();">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top"><table width="800" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="288" align="left" valign="bottom"><a href="home.php" style="font-size:medium;">Home</a></td>
        <td width="512" align="center" valign="middle" class="header_text"><img src="img/RGB_Logo.jpg" alt="" width="200" height="63" align="left" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top" style="border-bottom:#999999 1px solid;">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top"><table width="800" border="0" cellspacing="0" cellpadding="0">
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
                  <td width="70%" align="left" valign="middle"><b>Channel Extra</b></td>
                                  <td width="30%" align="center" valign="middle">&nbsp;</td>
                </tr>
            </table>            </td>
          </tr>
                  <?php
                                $dataTestCase=array();
                                $dataTestCase["devKey"] = "2524c5ab9f842ca5b6725d6969409ba9" ;
                                $dataTestCase["testsuiteid"] = 620 ;
                                //RPC response of Test cases for each Test Suite
                                $responseTestCase=getResponseRPC($dataTestCase,$debug,'tl.getTestCasesForTestSuite');
                                ?>
          <tr>
            <td align="left" valign="top">
            <table width="100%" border="0" cellspacing="2" cellpadding="2" >
              <tr>
                <td align="left" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="43%" align="left" valign="middle"><b><?php print $responseTestCase[0]['name'];?> 
                                          </b><input type="hidden" name="testcase" id="testcase" value="<?php print $responseTestCase[0]['external_id'];?>" readonly="true"/></td>
                      <td width="16%" align="center" valign="middle">&nbsp;</td>
                      <td width="41%" align="right" valign="middle">&nbsp;</td>
                    </tr>
                                        <tr>
                      <td width="50%" align="left" valign="middle" >Number of TCM </td>
                                          <td width="50%" align="left" valign="middle" >
                                          <select name="notcm" id="notcm" onchange="comboSelect();">
                                                <?php
                                                for($i=1;$i<=$tcmList;$i++){
                                                ?>
                                                        <option value="<?php print $i;?>"><?php print $i;?></option>
                                                <?php
                                                }
                                                ?>
                                          </select>
                                          </td>
                                        </tr>
                                        <tr height="5px"><td colspan="2"></td></tr>
                                        <tr>
                      <td width="50%" align="left" valign="middle" >Number of Channel</td>
                                          <!--style="display:none"-->
                                          <td width="50%" align="left" valign="middle" ><select name="nochannel" id="nochannel">
                                          <?php
                                                for($j=1;$j<=$tcmList*6;$j++){
                                          ?>
                                          <option value="<?php print $j;?>"><?php print $j;?></option>
                                          <?php
                                                }
                                          ?>
                                          </select></td>
                                        </tr>
                                        <tr height="5px"><td colspan="2"></td></tr>
                                        <tr>
                      <td width="50%" align="left" valign="middle" >Output</td>
                                          <td width="50%" align="left" valign="middle" ><input name="output" id="output" value="8" size="2" readonly="true"/></td>
                                        </tr>
                </table>                </td>
              </tr>
            </table>            </td>
          </tr>
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
                <td align="center" valign="top"><input type="submit" name="submit_test" id="submit_test" value="Run Automation now" /><input type="button" name="submit_abort" id="submit_abort" value="Abort Process" onclick="return callAbort();"/>
                 <!-- <input type="button" name="submit_test2" id="submit_test2" value="Schedule Automation" onclick="return popWindow('scheduleUI.php');"/>--></td>
                <td width="16%" align="center" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" valign="top" colspan="3"><div id='testCaseDis' style="color:#000066">&nbsp;</div></td>
              </tr>
                           <tr>
                <td align="center" valign="top" colspan="3"><!--<div id='imgdis' style="display:none"><img src="img/progress_bar.gif" /></div>--></td>
              </tr>
            </table>            </td>
          </tr>
        </table>
                </form></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
