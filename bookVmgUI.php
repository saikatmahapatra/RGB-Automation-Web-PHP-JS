<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
$requestURL=($_SERVER['REQUEST_URI']);
$usernameArr=explode('/',$requestURL);
$nodeusername=substr($usernameArr[1],1,strlen($usernameArr[1]));
$firstParam="/home2/".$nodeusername."/public_html/cgi-bin/vmg_lib/main_cfgs/";
if ($handle = opendir($firstParam)) {
        $cfgFileArray=array();
        $l=0;
    /* This is loop over the directory. */
    while (false !== ($file = readdir($handle))) {
        if(preg_match("/(.cfg)/",$file)){
                        $cfgFileArray[$l]=$file;
                        $l+=1;
                }
    }
    closedir($handle);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VMG UI Test Automation</title>
<link rel="icon" href="rgbfav.ico" />
<style type="text/css">
<!--
body{
margin:0px;
}
.header_text{
font-family:Georgia, "Times New Roman", Times, serif;
font-size:18px;
font-style:italic;
font-weight:bold;
color:#000;
}
td {
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
font-weight:normal;
color:#333333;}
-->
</style>
<script type="text/javascript" src="jquery-1.3.2.js"></script>
<script type="text/javascript" src="datetimepicker.js"></script>
<script language="javascript">
function trim(str)
{
   // if(!str || typeof str != 'string')
       // return null;

    return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
}
function loadvmg(objVal){
        $.ajax({  
        type: "POST", 
        url: 'readBookVmg.php', 
        data: "name="+objVal.value,  
        complete: function(html){  
        $("#targetDiv").html(html.responseText);  
        $("#resultDiv").html("");
        }  
        });  
}
function vmgSubmit(){
        if(validation()==false){
        return false;
        }
        var str = $("form").serialize();
        var constr=unescape(str);
        $.ajax({
        type: "POST", 
        url: 'writeVMGbook.php', 
        data: constr,  
        complete: function(txt){  
                $("#resultDiv").html(txt.responseText);  
        }  
        });
}

function validation(){
var doc=document.bookingform;
        if(doc.conffile.value==""){
                alert("Please select VMG box");
                doc.conffile.focus();
                return false;
        }       
        if(trim(doc.from_date_time.value)==""){
                alert("From Date Time can't be not blank");
                doc.from_date_time.focus();
                return false;
        }
        if(trim(doc.to_date_time.value)==""){
                alert("To Date Time can't be not blank");
                doc.to_date_time.focus();
                return false;
        }
}
</script>
</head>

<body>
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
    <td align="center" valign="top"><table width="700" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="40" align="center" valign="middle" class="header_text"><span style="border-bottom:#999999 1px solid;">VMG UI Test Automation Framework Schedule</span></td>
      </tr>
          <tr>
        <td align="left" valign="top">
                <table width="850" border="1" align="center" cellpadding="2" cellspacing="2">
                <tr>
                  <td align="center" valign="middle" colspan="2">Booking Details </td>
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
                <form name="bookingform" action="" method="post" enctype="multipart/form-data">
                <table width="850" border="1" align="center" cellpadding="2" cellspacing="2">
                <tr>
                  <td colspan="6" align="center" valign="middle"><div id="resultDiv"></div></td>
                  </tr>
                <tr>
                  <td colspan="6" align="center" valign="middle">Book   VMG</td>
                </tr>
                <tr>
                  <td width="166" align="center" valign="middle">VMGIP</td>
                  <td width="186" align="center" valign="middle">From Date Time </td>
                  <td width="186" align="center" valign="middle">To Date Time </td>
                  </tr>
                <tr>
                  <td align="center" valign="middle">
                  <select name='conffile' id="conffile" onchange="loadvmg(this);">
                <option value="">Select VMG Box</option>
                <?php
                foreach($cfgFileArray as $value){
                        $cfgval=explode('.',$value);
                ?>
                <option value="<?php print $cfgval[2];?>" <?php if("VMG".$cfgval[2]==$_REQUEST['vmg']) print "selected";?>><?php print "VMG".$cfgval[2];?></option>
                <?php
                }
                ?>
                </select>                 </td>
                  <td align="center" valign="middle"><input type="text" name="from_date_time" id="from_date_time"/>
                <a href="javascript:NewCal('from_date_time','ddmmmyyyy',true,24)"><img src="img/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></td>
                  <td align="center" valign="middle"><input type="text" name="to_date_time" id="to_date_time"/><a href="javascript:NewCal('to_date_time','ddmmmyyyy',true,24)"><img src="img/cal.gif" width="16" height="16" border="0" alt="Pick a date"></a></td>
                  </tr>
                <tr>
                  <td colspan="6" align="center" valign="middle"><input type="button" onclick="return vmgSubmit();" name="schedule" id="schedule" value="Schedule"/></td>
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
