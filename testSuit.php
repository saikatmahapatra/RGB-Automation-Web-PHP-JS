<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
require_once 'util.php';
require_once 'sample.inc.php';
$unitTestDescription="Test - getTestSuitesForTestPlan";

$data=array();
$data["devKey"] = "2524c5ab9f842ca5b6725d6969409ba9" ;
//$data["testplanid"] = "2";
$data["testsuiteid"] = "10";

$debug=true;
//echo $unitTestDescription;

$server_url="http://10.70.111.21/testlink/lib/api/xmlrpc.php";
$client = new IXR_Client($server_url);

$client->debug=$debug;



//new dBug($data);
/*if(!$client->query('tl.getTestSuitesForTestPlan', $data))
{
                echo "something went wrong - " . $client->getErrorCode() . " - " . $client->getErrorMessage();                  
                $response=NULL;
}
else
{
                $response=$client->getResponse();
}*/

if(!$client->query('tl.getTestCasesForTestSuite', $data))
{
                echo "something went wrong - " . $client->getErrorCode() . " - " . $client->getErrorMessage();                  
                $response=NULL;
}
else
{
                $response=$client->getResponse();
}
print_r($response);
//print 
/*$pos = strpos($responseStr, "<methodResponse");
print $pos;
exit;*/
//echo "<br> Result was: ";
// Typically you'd want to validate the result here and probably do something more useful with it
// print_r($response);
//new dBug($response);
//echo "<br>";


?>