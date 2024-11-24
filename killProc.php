<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
ini_set("max_execution_time",0);
//The URI which was given in order to access this page   
//$requestURL=($_SERVER['REQUEST_URI']); 
// array which find out username from the URI  
//$usernameArr=explode('/',$requestURL);
// computing user name   
//$nodeusername=substr($usernameArr[1],1,strlen($usernameArr[1]));
//print "ps aux | grep main_driver.py | grep apache | grep ".$username;

exec('ps aux | grep main_driver.py | grep apache | grep -i "w '.$username.'"',$output2);

//exec("pstree -nap | grep main | grep -v grep | head -n 1 | sed 's/.*python,\(.*\) .*/\1/' | cut -d ' ' -f 1",$output2);

print "<pre>";
print_r($output2);
$str="";

foreach($output2 as $key=>$value){
        $procIDArray=array();
        $procIDArray=explode(" ",$value);
        //print_r($value);
        //print_r($procIDArray);
        for($i=1;$i<count($procIDArray);$i++){
                if($procIDArray[$i]!=""){
                        $str.=$procIDArray[$i]." ";
                        $i=count($procIDArray);
                        //break;
                }
        }
        unset($procIDArray);
}
//print "</pre>";
//print "aaaaa=".$str;
//exec(" kill -9 ".$str);
echo "kill -9 ".$str;
echo "kill command commented";
echo '<div class="processAbortResponse">Process successfully aborted</div>';

?>
