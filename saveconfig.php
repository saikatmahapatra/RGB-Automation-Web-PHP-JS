<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
$requestURL=($_SERVER['REQUEST_URI']);
$usernameArr=explode('/',$requestURL);
//print_r($usernameArr);
$nodeusername = substr($usernameArr[1],1,strlen($usernameArr[1]));
$cfgPath="/home2/".$nodeusername."/public_html/cgi-bin/vmg_lib/main_cfgs/";
//$auxcfgPath="/home2/".$username."/public_html/cgi-bin/vmg_lib/cfg/";
$maincfg=fopen($cfgPath.'server.cfg.'.$username, 'w') or die("Can't open file11");
//$auxcfg=fopen($auxcfgPath.'server.cfg.'.$_REQUEST['conffile'],'w') or die("Can't open file22");
$stringval="";
/*print "<pre>";
print_r($_POST);
print "</pre>";
exit;*/
foreach($_POST as $key=>$value){
        $keyvalue=explode("-",$key);
        if($keyvalue[0]!='conffile'){
                if(($keyvalue[0]=='LIN' || $keyvalue[0]=='WIN' || $keyvalue[0]=='MAC' || $keyvalue[0]=='SQA') && ($keyvalue[0]!='heading')){
                        $prefix=$keyvalue[0];
                        $paramname="(".$keyvalue[1].")";
                        $paramvalue=' "'.$value.'"';
                        $stringval.=$prefix.$paramname.$paramvalue."\n";
                }else{
                        if($keyvalue[0]=='heading'){
                                /*if(!(preg_match("/#ZZ/",$value)) && !(preg_match("/#ZZ/",$value))){
                                $stringval.="\n".$value."\n";
                                }else{*/
                                $stringval.="\n".$value."\n";
                                //}
                        }
                        if($keyvalue[0]=='gig'){
                        $stringval.="\n".$value."\n";
                        }
                        if($keyvalue[0]=='card'){
                        $stringval.="\n".$value."\n";
                        }
                        /*else{
                                if($keyvalue[0]!='Submit'){
                                $prefix='SQA';
                                $paramname="(".$keyvalue[1].")";
                                $paramvalue=' "'.$value.'"';
                                $stringval.=$prefix.$paramname.$paramvalue."\n";
                                }
                        }*/
                }
        }
}
/*print $stringval;
exit;*/
$cfg=$_POST['conffile'];
//exit;
fwrite($maincfg,$stringval);
//fwrite($auxcfg,$stringval);
fclose($maincfg);
//fclose($auxcfg);
shell_exec("dos2unix ".$cfgPath."server.cfg.".$username);
//shell_exec("dos2unix ".$auxcfgPath."server.cfg.".$cfg);
//shell_exec("sudo chown ".$username.":".$username."  ".$cfgPath."server.cfg");
//print "chown ".$username.":".$username."  ".$cfgPath."server.cfg";
//exit;
/*print $username;
chown($cfgPath."server.cfg",$username);
// Check the result
$stat = stat($cfgPath."server.cfg");
print_r(posix_getpwuid($stat['uid']));
exit;*/
header("location:configuration.php?cfg=".$cfg."&up=1");
exit;
?>
