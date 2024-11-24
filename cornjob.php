<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
$conffile=$argv[2];
//$sudouname='rebacatest';
$rowval=$argv[1];
//$conffile='vmg37';
//$sudouname='rebacatest';
//$rowval=9;
$filePath="/var/www/html/".strtolower($conffile).".txt";
$handle=fopen($filePath,'r')or die("can't open file11");
$dataArray=array();
$eachLine="";
$emailnotification=' -m ';
//$i=0;
while(!feof($handle)){
        $eachLine=fgets($handle);
        $valArray=array();
        $valArray=explode("|",$eachLine);
        //print_r($valArray);
        //$dataArray[$i]['incrVal']=$valArray[0];
        if($valArray[0]==$rowval){
                $vmgBox=strtolower($valArray[1]);
                $from_tm_hr=$valArray[2];
                $from_tm_mm=$valArray[3];
                $to_tm_hr=$valArray[4];
                $to_tm_mm=$valArray[5];
                $uname=$valArray[7];
                $sudouname=$valArray[8];
                //print $count=count($valArray);
                        $t=0;
                        for($i=9;$i<count($valArray)-1;$i++){
                                $testSuites.=$valArray[$i].',';
                                $filename[$t]=strtolower($valArray[$i])."_".$from_tm_hr."_".$from_tm_mm."_".$conffile.'.cfg';
                                $t+=1;
                        }
        }else{
                if($valArray[0]!=""){
                $writeStr.=$valArray[0]."|";
                $writeStr.=strtolower($valArray[1])."|";
                $writeStr.=$valArray[2]."|";
                $writeStr.=$valArray[3]."|";
                $writeStr.=$valArray[4]."|";
                $writeStr.=$valArray[5]."|";
                $writeStr.=$valArray[6]."|";
                $writeStr.=$valArray[7]."|";
                $writeStr.=$valArray[8]."|";
                        $g=0;
                        for($i=9;$i<count($valArray)-1;$i++){
                                $testSuites.=$valArray[$i].',';
                                $writeStr.=$valArray[$i]."|";
                                $g+=1;
                        }
                $writeStr.="\n";
                }
        }
}
fclose($handle);
$updatehandle=fopen($filePath,'w')or die("can't open file44");
fwrite($updatehandle, $writeStr);
fclose($updatehandle);

$testSuites=substr($testSuites,0,-1);
for($l=0;$l<count($filename);$l++){
        $tmpreadfilePath="/var/www/html/".$filename[$l];
        $fileread=fopen($tmpreadfilePath,'r')or die("can't open file22");
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
        $filewrite=fopen($tmpwritefilePath,'w')or die("can't open file33");
        foreach($testcaseArray as $value){
                $stringData.=$value."\n";
        }
        fwrite($filewrite, $stringData);
        fclose($filewrite);
        unlink($tmpreadfilePath);
}

$firstParam="/home2/".$sudouname."/public_html/cgi-bin/main_driver.py";
$secondParam=$testSuites;
$thirdParam=$uname;
$commandArg=$firstParam." -s ".$secondParam." -w ".$sudouname.$emailnotification." -u ".$thirdParam." >/home2/".$sudouname."/public_html/vmgtest/phptest/automationLog.txt";
//exit;
shell_exec("/usr/local/bin/python ".$commandArg);
?>