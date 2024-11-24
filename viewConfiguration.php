<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
$requestURL=($_SERVER['REQUEST_URI']);
$usernameArr=explode('/',$requestURL);
//print_r($usernameArr);
$nodeusername=substr($usernameArr[1],1,strlen($usernameArr[1]));
$firstParam="/home2/".$nodeusername."/public_html/cgi-bin/vmg_lib/main_cfgs/";
        // full path to text file
        define("TEXT_FILE", $firstParam."/server.cfg.".$username);
        // number of lines to read from the end of file
        //define("LINES_COUNT", 10);
        $handle = fopen(TEXT_FILE, "r");
        $i=0;
        $cfgArray=array();
        while(!feof($handle)){
        $cfgArray[$i]=fgets($handle);
        $i+=1;
        }
/*      print_r($cfgArray);
        exit;*/
        $configArr=array();
        $count=0;
        foreach($cfgArray as $value){
                if(trim($value)!=""){
                //$configArr[$count]=$value;
                $matches=array();
                   if(preg_match_all("/(.*?)\((.*?)\)\s+\"(.*?)\"/",$value,$matches)){
                                //print_r($matches);
                                $j=0;
                                foreach($matches as $val){
                                        if(!empty($val)){
                                        $configArr[$count][$j]=trim($val[0]);
                                        $j+=1;
                                        }
                                }
                        }else{
                                $configArr[$count][0]="";
                                $configArr[$count][1]="";
                                $configArr[$count][2]="";
                                $configArr[$count][3]=$value;
                        }
                $count+=1;
                }       
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
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top"><table width="800" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="288" align="left" valign="bottom"><a href="index.html" style="font-size:medium;">Home</a></td>
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
                <form name="f1" action="saveconfig.php" method="post" enctype="multipart/form-data">
                <table width="600" border="1" align="center" cellpadding="2" cellspacing="2">
                <tr><td align="center" valign="middle" colspan="3"><?php if($_REQUEST['up']==1){?><font color="#FF0000">File has been successfully updated<?php }?></font></td></tr>
                <tr><td colspan="2" align="center" valign="middle">View Configuration File</td>
                </tr><tr>
                                <?php
                                $t=1; 
                                foreach($configArr as $value){
                                        if(preg_match("/^#/",$value[3])){
                                        ?>
                                        
                                        <td colspan="2" align="center" valign="middle">
                                        <?php print str_replace("#","",$value[3]);
                                                  $prefix=substr($value[3],1,3);
                                        ?>
                                        <input type="hidden" name="heading-<?php print $t;?>" value="<?php print $value[3];?>" />                                       </td>
                                   </tr>
                                        <?php
                                        $t+=1;
                                        }else{
                                        ?>
                                        <tr>
                                          <td width="289" align="left" valign="middle"><?php print $value[2];?></td>
                                          <td width="291" align="left" valign="middle"><?php print $value[3];?></td>
                                        </tr>
                                        <?php
                                        } 
                                }
                                ?>
                                <tr>
                                <td colspan="2" align="center" valign="middle">
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