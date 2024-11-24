<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
/*************************************************/
// Created by : RGB Networks
// Created on : 10/08/2011
// Selected test cases and run at now or schedule 
// no input expected
/*************************************************/
//The URI which was given in order to access this page
$requestURL=($_SERVER['REQUEST_URI']);
// array which find out username from the URI
$usernameArr=explode('/',$requestURL);
// computing user name
$nodeusername=substr($usernameArr[1],1,strlen($usernameArr[1]));
//path of the directory
$dirPath="/home2/".$nodeusername."/public_html/cgi-bin/vmg_lib/server_cfgs_repository/".$username."/";
// read directory
if ($handle = opendir($dirPath)) {
        //define array to store cfg files
        $cfgFileArray=array();
        $l=0;
    /*loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        if(preg_match("/(.cfg)/",$file)){
                        $cfgFileArray[$l]=$file;
                        $l+=1;
                }
    }
    closedir($handle);
}
// name alias with VMG IP
$vmgArray=array('10.32.97.31'=>'TinkerBell(10.32.97.31)',
'10.32.97.32'=>'Goofy(10.32.97.32)',
'10.32.99.31'=>'Nemo(10.32.99.31)',
'10.32.97.37'=>'Tweety(10.32.97.37)',
'10.32.98.140'=>'Simba(10.32.98.140)',
'10.32.98.142'=>'Pluto(10.32.98.142)',
'10.32.97.170'=>'SQA(10.32.97.170)',
'10.32.98.141'=>'Pinky(10.32.98.141)',
'10.33.0.56'=>'Mars(10.33.0.56)',
'10.33.0.53'=>'Neptune(10.33.0.53)',
'10.33.0.59'=>'Mercury(10.33.0.59)',
'10.33.0.50'=>'Venus(10.33.0.50)');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VMG UI Test Automation</title>
<link rel="icon" href="rgbfav.ico" />
<link href="cssfiles/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-1.3.2.js"></script>
<script type="text/javascript" src="datetimepicker.js"></script>
<script type="text/javascript" src="jsfiles/common.js"></script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top"><table width="800" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="288" align="left" valign="bottom"><!--<a href="index.html" style="font-size:medium;">Home</a>--></td>
        <td width="512" align="center" valign="middle" class="header_text"><img src="img/RGB_Logo.jpg" alt="" width="200" height="63" align="left" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" valign="top" style="border-bottom:#999999 1px solid;">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="40" align="center" valign="middle" class="header_text"><span style="border-bottom:#999999 1px solid;">VMG UI Test Automation Framework Schedule</span></td>
      </tr>
          <tr>
        <td align="left" valign="top">
                <table width="850" border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="center" valign="middle" colspan="2">Scheduled Details </td>
                </tr>
                <tr><td colspan="2" align="center"><div id="targetDiv"><table width="100%" border="1" align="center" cellpadding="2" cellspacing="2"><tr><td colspan="2" align="center">No Records Found</td></tr></table></div></td></tr>
                <?php
                if(count($dataArray)>1){
                        if($_REQUEST['vmg']!="" && isset($_REQUEST['vmg'])){
                                foreach($dataArray as $key=>$value){
                                        if($value['vmgBox']!=""){
                                ?>
                                        <tr>
                                          <td align="center" valign="middle"><?php print $value['vmgBox'];?></td> 
                                          <td align="center" valign="middle"><?php print $value['from_tm_hr'];?> <?php print $value['from_tm_mm'];?> to <?php print $value['to_tm_hr'];?>  <?php print $value['to_tm_mm'];?></td>
                                        </tr>
                                         <?php
                                         }
                                 }
                         }
                 }
                 ?>
        </table>
                </td>
      </tr>
          <tr><td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
      <tr>
        <td align="left" valign="top" colspan="2">
                <form name="scheduleform" action="" method="post" enctype="multipart/form-data">
                <?php
                        // form post data send in hidden format
                        if($_REQUEST['vmg']=="" && !isset($_REQUEST['vmg']))
                        {
                                foreach($_REQUEST['itemSelect'] as $keyTestSuite=>$valueTestSuite)
                                {
                                        ?>
                                        <input type="hidden" name="itemSelect[]" value="<?php print $valueTestSuite;?>" />
                                        <?php
                                        $testSuiteArr=array();
                                        $testSuiteArr[$count]=str_replace('/','',$valueTestSuite);
                                        $testSuitePostArr='select_'.$testSuiteArr[$count].'2';
                                        $countSuite=0;
                                        foreach($_REQUEST[$testSuitePostArr] as $keyTestCase=>$valueTestCase){
                                        ?>
                                        <input type="hidden" name="<?php print $testSuitePostArr?>[]" value="<?php print $valueTestCase;?>">
                                        <?php
                                        }
                                        $count+=1;
                                }
                        }
                ?>
                <table width="850" border="1" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td colspan="6" align="center" valign="middle"><div id="resultDiv"></div></td>
                  </tr>
                <tr>
                  <td colspan="6" align="center" valign="middle">Set Schedule in  VMG</td>
                </tr>
                <tr>
                  <td width="166" align="center" valign="middle">VMGIP</td>
                  <td width="186" align="center" valign="middle">From Date Time </td>
                </tr>
                <tr>
                  <td align="center" valign="middle">
                  <select name='conffile' id="conffile" onchange="loadvmg(this);">
                  <option value="">Select VMG Box</option>
                        <?php
                        // looping with cfgs
                        foreach($cfgFileArray as $value)
                        {
                                $cfgval=explode('.',$value);
                                $vmgindex=$cfgval[2];
                        ?>
                                <option value="<?php print $cfgval[2];?>" <?php if("VMG".$cfgval[2]==$_REQUEST['vmg']) print "selected";?>><?php print $vmgArray[$vmgindex];?></option>
                        <?php
                        }
                        ?>
                </select></td>
                  <td align="center" valign="middle"><input type="text" name="from_date_time" id="from_date_time"/>
                <a href="javascript:NewCal('from_date_time','ddmmmyyyy',true,24)"><img src="img/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></td>
                  </tr>
                <tr>
                  <td colspan="6" align="center" valign="middle"><input type="button" name="schedule" id="schedule" value="Schedule"  onclick="return schedulerSubmit();"/>
                  <input type="button" name="close" id="close" value="Close"  onclick="closeWin();"/>
                  </td>
                  </tr>
        </table>
                </form>
                </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>