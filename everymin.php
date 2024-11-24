<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}

error_reporting(3);
$curTimestamp=time();
$filePath="/var/www/html/vmg.txt";
$handle=fopen($filePath,'r')or die("can't open file");
$dataArray=array();
$eachLine="";
$writeStr="";
//$emailnotification=' -m ';            //enable later


while(!feof($handle)){
        $eachLine=fgets($handle);
        $valArray=explode("|",$eachLine);
        if($valArray[2]<$curTimestamp){
                if($valArray[0]!=""){
                $vmgBox=$valArray[1];
                $timestamp=$valArray[2];
                $uname=$valArray[3];
                $sudouname=$valArray[4];
                        $t=0;
                        for($i=5;$i<count($valArray)-1;$i++){
                                $testSuites.=strtolower($valArray[$i]).',';
                                $filename[$t]=strtolower($valArray[$i])."_".$timestamp."_".$vmgBox.'.cfg';
                                $t+=1;
                        }
                }
        }else{
                if($valArray[0]!=""){
                $writeStr.=$valArray[0]."|";
                $writeStr.=strtolower($valArray[1])."|";
                $writeStr.=$valArray[2]."|";
                $writeStr.=$valArray[3]."|";
                $writeStr.=$valArray[4]."|";
                        $g=0;
                        for($i=5;$i<count($valArray)-1;$i++){
                                $testSuites.=$valArray[$i].',';
                                $writeStr.=$valArray[$i]."|";
                                $g+=1;
                        }
                $writeStr.="\n";
                }
        }
}
fclose($handle);
if(trim($sudouname)==""){
        $ftmp=fopen("/var/www/html/test.txt",'w');
        fwrite($ftmp,"Lock file is empty         ".date("Y-m-d h:i:s"));
        fclose($ftmp);
        exit;
}
$updatehandle=fopen($filePath,'w')or die("can't open file");  
fwrite($updatehandle, $writeStr);       
fclose($updatehandle);                          



$testSuites=substr($testSuites,0,-1);
for($l=0;$l<count($filename);$l++){
        $tmpreadfilePath="/var/www/html/".$filename[$l];
        $fileread=fopen($tmpreadfilePath,'r')or die("can't open file");
        $testcaseArray=array();
        $u=0;
        while(!feof($fileread)){
        $testcaseArray[$u]=fgets($fileread);
        $u+=1;
        }
        fclose($fileread);
        $writeFilename=array();
        $stringData="";
        $writeFilename=explode("_",$filename[$l]);
        $tmpwritefilePath="/home2/".$sudouname."/public_html/cgi-bin/".$writeFilename[0]."/".$writeFilename[0].".cfg";
        $filewrite=fopen($tmpwritefilePath,'w')or die("can't open file");
        foreach($testcaseArray as $value){
                $stringData.=$value."\n";
        }
        $stringDataFinal=trim($stringData);
        fwrite($filewrite, $stringDataFinal);
        fclose($filewrite);
        unlink($tmpreadfilePath);     
}
$sudouname=trim($sudouname);
$firstParam="/home2/".trim($sudouname)."/public_html/cgi-bin/main_driver.py";
$secondParam=trim($testSuites);
//$thirdParam=$uname;           //Prev user input
$thirdParam=$sudouname;        // Now from url
$commandArg=$firstParam." -s ".$secondParam." -w ".$sudouname." -u ".$thirdParam." >/home2/".$sudouname."/public_html/vmgtest/phptest/automationLog.txt";
//print $strOutput="/usr/local/bin/python ".$commandArg;
/*$commandArg="/usr/local/bin/python /home2/autovmgui/public_html/cgi-bin/main_driver.py -s sanity -w autovmgui -u QA >/home2/autovmgui/public_html/vmgtest/phptest/automationLog.txt";*/
//exit;
try{
shell_exec("/usr/local/bin/python ".$commandArg);         
$strOutput="success";
} catch (Exception $e) {
    $strOutput=$e->getMessage();
}
$ftmp=fopen("/var/www/html/errorLog.txt",'w');
fwrite($ftmp,$strOutput."        ".date("Y-m-d h:i:s"));
fclose($ftmp);
?>