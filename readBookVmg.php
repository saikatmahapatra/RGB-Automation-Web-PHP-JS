<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
$conffile=$_REQUEST['name'];
$filePath="/var/www/html/vmgBook.txt";
$handle=fopen($filePath,'r')or die("can't open file");
$dataArray=array();
$eachLine="";
$i=0;
while(!feof($handle)){
        $eachLine=fgets($handle);
        $valArray=explode("|",$eachLine);
        if($valArray[1]==trim($conffile)){      
        $dataArray[$i]['incrVal']=$valArray[0];
        $dataArray[$i]['vmgBox']=strtolower($valArray[1]);
        $dataArray[$i]['fromdatetime']=date('Y-m-d h:i:s',$valArray[2]);
        $dataArray[$i]['todatetime']=date('Y-m-d h:i:s',$valArray[3]);
        $dataArray[$i]['uname']=$valArray[4];
        }       

$i+=1;
}
$string="";
                if($conffile!=""){
                        if(count($dataArray)>0){
                                        $string.='<table width="100%" border="1" align="center" cellpadding="2" cellspacing="2">';
                                        foreach($dataArray as $key=>$value){
                                                $string.='<tr><td align="center" valign="middle">VMG-'.$value["vmgBox"].'</td><td align="center" valign="middle">'.$value["fromdatetime"].'</td><td align="center" valign="middle">'.$value["todatetime"].'</td><td align="center" valign="middle">'.$value["uname"].'</td></tr>';
                                         }
                         }else{
                                $string.='<tr><td colspan="2" align="center">No Records Found</td></tr>';
                         }
                 }else{
                                $string.='<tr><td colspan="2" align="center">No Records Found</td></tr>';
                 }
                 $string.='</table>';
print $string;
exit;    
?> 