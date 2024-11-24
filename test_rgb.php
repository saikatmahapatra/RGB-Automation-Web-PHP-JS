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
<SCRIPT TYPE="text/javascript">
<!--
function popup(mylink, windowname)
{
if (! window.focus)return true;
var href="popupbasic.html";
window.open(href, "popup", 'width=420,height=100,scrollbars=yes');
return false;
}
//-->
</SCRIPT>

<script language="javascript">
    function fnMoveItems(lstbxFrom, lstbxTo) {
       // alert(lstbxFrom);
        var varFromBox = document.getElementById(lstbxFrom);
        var varToBox = document.getElementById(lstbxTo);
        if ((varFromBox != null) && (varToBox != null)) {
            if (varFromBox.length < 1) {
                alert('There are no items in the source ListBox');
                return false;
            }
            if (varFromBox.options.selectedIndex == -1) // when no Item is selected the index will be -1
            {
                alert('Please select an Item to move');
                return false;
            }
            while (varFromBox.options.selectedIndex >= 0) {
                var newOption = new Option(); // Create a new instance of ListItem 

                newOption.text = varFromBox.options[varFromBox.options.selectedIndex].text;
                newOption.value = varFromBox.options[varFromBox.options.selectedIndex].value;
                                newOption.selected = varFromBox.options[varFromBox.options.selectedIndex].selected;
                varToBox.options[varToBox.length] = newOption; //Append the item in Target Listbox

                varFromBox.remove(varFromBox.options.selectedIndex); //Remove the item from Source Listbox 

            }
        }
        return false;
    }
        
        function fnAddItems(lstbxFrom, lstbxTo) {
       // alert(lstbxFrom);
        var varFromBox = document.getElementById(lstbxFrom);
        var varToBox = document.getElementById(lstbxTo);
        if ((varFromBox != null) && (varToBox != null)) {
            if (varFromBox.length < 1) {
                alert('There are no items in the source ListBox');
                return false;
            }
            if (varFromBox.options.selectedIndex == -1) // when no Item is selected the index will be -1
            {
                alert('Please select an Item to move');
                return false;
            }
            while (varFromBox.options.selectedIndex >= 0) {
                var newOption = new Option(); // Create a new instance of ListItem 

                newOption.text = varFromBox.options[varFromBox.options.selectedIndex].text;
                newOption.value = varFromBox.options[varFromBox.options.selectedIndex].value;
                                //newOption.selected = varFromBox.options[varFromBox.options.selectedIndex].selected;
                varToBox.options[varToBox.length] = newOption; //Append the item in Target Listbox

                varFromBox.remove(varFromBox.options.selectedIndex); //Remove the item from Source Listbox 

            }
        }
        return false;
    }

</script>
<script type="text/javascript" src="jquery-1.3.2.js"></script>

<script language="javascript">
function changeName(){
        $('input[name=uname]').attr('value', "");
}
function unchecked() {
        $('input[name=itemSelect[]]').attr('checked', false);
}
$(document).ready(function() {
        $('pre').hide();
        $(".itemSelect").live("click", function() {
        var selectedItems = new Array();
        var unselectedItems =new Array();
        $("input[name='itemSelect[]']:checked").each(function() {selectedItems.push($(this).val());});
        $("input[name='itemSelect[]']:not(:checked)").each(function() {unselectedItems.push($(this).val());})
        if (selectedItems.length == 0){
                uncheckedItem=unselectedItems[0].replace('\/','');
                $('#'+uncheckedItem).hide();
        }else{
                for(var i=0;i<selectedItems.length;i++){
                var checkedItem="";
                checkedItem=selectedItems[i].replace('\/','');
                $('#'+checkedItem).show();
                }
        }
        for(var i=0;i<unselectedItems.length;i++){
        var uncheckedItem="";
        uncheckedItem=unselectedItems[i].replace('\/','');
        $('#'+uncheckedItem).hide();
        }
});
});
</script>

</head>
<?php
require_once 'util.php';
require_once 'sample.inc.php';
$unitTestDescription="Test - getTestSuitesForTestPlan";

$data=array();
$data["devKey"] = "2524c5ab9f842ca5b6725d6969409ba9" ;
$data["testplanid"] = "2";
//$data["testsuiteid"] = "10";

$debug=true;
//echo $unitTestDescription;
function getResponseRPC($dataArr,$debugmode,$dateType){
        $server_url="http://10.70.111.21/testlink/lib/api/xmlrpc.php";
        
        $client = new IXR_Client($server_url);
        
        $client->debug=$debugmode;
        if(!$client->query($dateType, $dataArr))
        {
                        echo "something went wrong - " . $client->getErrorCode() . " - " . $client->getErrorMessage();                  
                        $response=NULL;
        }
        else
        {
                        $response=$client->getResponse();
        }
        return $response;
}

$responseRPC=getResponseRPC($data,$debug,'tl.getTestSuitesForTestPlan');
//print_r()
/*$response=array();
$i=0;
foreach($responseRPC as $key=>$value){
        $response[$i]=$value['name'];
        $i++;
}*/
//print_r($response);
function sortVal($a,$b){
        return strcmp($b['name'], $a['name']);
}
usort($responseRPC,"sortVal");
//$response=array_multisort($response,SORT_ASC,SORT_STRING);
?>
<body onload="unchecked();">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top"><table width="800" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" valign="middle" class="header_text"><img src="img/RGB_Logo.jpg" alt="" width="200" height="63" /></td>
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
          <form name="caseform" action="runTestCase.php" method="post" enctype="multipart/form-data"> 
      <tr>
        <td align="left" valign="top"><table width="600" border="1" align="center" cellpadding="2" cellspacing="2">
          <tr id="1">
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                  <td width="5%" align="left" valign="middle">UserName : </td>
                  <td width="9%" align="left" valign="middle"><input type="text" name="uname" id="uname" value='QA' onfocus="changeName();"/></td>
                </tr>
                                <tr><td colspan="2">&nbsp;</td></tr>
                <tr id='checkID'>
                                <?php 
                                foreach($responseRPC as $key=>$value){
                                ?>
                  <td width="5%" align="left" valign="middle"><input type="checkbox" name="itemSelect[]" class='itemSelect' id="itemSelect" value=<?php print $value['name'];?> title="Please check to view the test cases of <?php print $value['name'];?>"/></td>
                  <td width="9%" align="left" valign="middle"><?php print $value['name'];?></td>
                                  <?php
                                  }
                                  ?>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                  <td height="25" align="left" valign="middle"><b>Test cases in Testlink Database</b></td>
                                   <td height="25" align="center" valign="middle"><b>Test Cases for Execution</b></td>
                </tr>
                <?php
                                foreach($responseRPC as $key1=>$value1){
                                $dataTestCase=array();
                                $dataTestCase["devKey"] = "2524c5ab9f842ca5b6725d6969409ba9" ;
                                $dataTestCase["testsuiteid"] =$value1['id'] ;
                                $responseTestCase=getResponseRPC($dataTestCase,$debug,'tl.getTestCasesForTestSuite');
                                //print_r($responseTestCase);
                                
                                ?>
                <tr>
                  <td align="left" valign="top" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="<?php print $value1['name'];?>" style="display:none;">
                                  <tr>
                  <td height="25" align="left" valign="middle"><b><?php print ucfirst($value1['name']);?></b></td>
                </tr>
                      <tr>
                        <td width="43%" align="left" valign="middle">
                                                <select size="100" name="select_<?php print $value1['name']?>1[]" multiple="multiple" id="<?php print $value1['name']?>1" style="height:70px;width:230px;">
                                                <?php 
                                                foreach($responseTestCase as $keyTestCase=>$valueTestCase){
                                                $displayListName=$valueTestCase['external_id']."_".$valueTestCase['parent_id']."_".$valueTestCase['name'];
                                                ?>
                            <option value="<?php print $valueTestCase['external_id'];?>" title="<?php print $valueTestCase['name'];?>"><?php print $displayListName;?></option>
                        <?php
                                                }
                                                ?>
                          </select>
                        </td>
                        <td width="16%" align="center" valign="middle"><input type="button" name="button" id="button" value="&gt;&gt;" onclick="return fnMoveItems('<?php print $value1['name']?>1','<?php print $value1['name']?>2')" />
                            <br />
                            <br />
                            <input type="button" name="button2" id="button2" value="&lt;&lt;" onclick="return fnAddItems('<?php print $value1['name']?>2','<?php print $value1['name']?>1')" /></td>
                        <td width="41%" align="right" valign="middle"><select size="100" name="select_<?php print $value1['name']?>2[]" multiple="multiple" id="<?php print $value1['name']?>2" style="height:70px;width:230px;">
                           <!-- <option value="">Test Case</option>
                            <option value="">Test Case1</option>
                            <option value="">Test Case2</option>-->
                        </select></td>
                      </tr>
                  </table></td>
                </tr>
                                <?php
                                }
                                ?>
                <tr>
                                
                  <td align="left" valign="top" style="border-bottom:#999999 1px solid;">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" valign="top">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="15%" align="center" valign="top">&nbsp;</td>
                  <td width="32%" align="center" valign="top"><input type="submit" name="button3" id="button3" value="Execute" /></td>
                  <td width="37%" align="center" valign="top"><input onclick="return popup()" type="submit" name="button4" id="button4" value="Schedule" /></td>
                  <td width="16%" align="center" valign="top">&nbsp;</td>
                </tr>
                                </form>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
