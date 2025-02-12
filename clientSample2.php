<?php
 /**
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 * This script is distributed under the GNU General Public License 2 or later.
 *
 * Filename $RCSfile: clientSample2.php,v $
 *
 * @version $Revision: 1.7 $
 * @modified $Date: 2009/05/01 20:36:56 $ by $Author: franciscom $
 *
 *
 * A sample client implementation in php
 * 
 * @author 		Asiel Brumfield <asielb@users.sourceforge.net>
 * @package 	TestlinkAPI
 * @link      http://testlink.org/api/
 *
 *
 *
 * rev: 20081013 - franciscom - minor improvements to avoid reconfigure server url
 *                              added test of getTestSuitesForTestPlan()
 *      20080818 - franciscom - start work on custom field tests
 *      20080306 - franciscom - added dBug to improve diagnostic info.
 *      20080305 - franciscom - refactored
 */
 
require_once 'util.php';
require_once 'sample.inc.php';
$unitTestDescription="Test - getTestSuitesForTestPlan";

/**
* getTestCasesForTestPlan
* List test cases linked to a test plan
* 
* @param struct $args
* @param string $args["devKey"]
* @param int $args["testplanid"]
* @param int $args["testcaseid"] - optional
* @param int $args["buildid"] - optional
* @param int $args["keywordid"] - optional
* @param boolean $args["executed"] - optional
* @param int $args["$assignedto"] - optional
* @param string $args["executestatus"] - optional
* @return mixed $resultInfo
*/
$data=array();
$data["devKey"] = "2524c5ab9f842ca5b6725d6969409ba9" ;
$data["testplanid"] = "2";
//$data["deep"] = "true";
//$data["details"] = "full";

// optional
// $args["testcaseid"] - optional
// $args["buildid"] - optional
// $args["keywordid"] - optional
// $args["executed"] - optional
// $args["$assignedto"] - optional
// $args["executestatus"] - optional

//$debug=true;
$debug=true;
echo $unitTestDescription;

$server_url="http://10.70.111.21/testlink/lib/api/xmlrpc.php";
$client = new IXR_Client($server_url);

$client->debug=$debug;



new dBug($data);
if(!$client->query('tl.getTestSuitesForTestPlan', $data))
{
		echo "something went wrong - " . $client->getErrorCode() . " - " . $client->getErrorMessage();			
		$response=null;
}
else
{
		$response=$client->getResponse();
}

echo "<br> Result was: ";
// Typically you'd want to validate the result here and probably do something more useful with it
// print_r($response);
new dBug($response);
echo "<br>";


?>