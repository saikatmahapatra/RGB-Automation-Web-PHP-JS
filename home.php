<?php
session_start();
$username = $_SESSION['username'];
if($username == ""){
        header("location:index.html");
}
?>

<html>
<head><title>RGB Testing</title>
<link rel="icon" href="rgbfav.ico" />

</head>
<body bgcolor="khaki">
<b><font size="4">
<img align="top" src="img/RGB_Logo.jpg">
<center><marquee behavior="alternate">VMG UI Test Automation</marquee></center>
<table width=100%>
<tr>
        <td>
        <right>Welcome <font size = "5" color = 6633FF><?php print $username ?> </b></right>
        </td>
        <td>
        <P align="right"><b><a href="index.html">Logout</a><br></b></P>
        </td>
</tr>
</table>
<hr size="5" color="#ff0000" width="100%" noshade> 
<span class="breadcrumb"><a href="select_device.php">Product List</a> &gt;&gt;Home</span>
<table width="100%" height="%" border="0">
<tr>
        <td>
        <a href="home.php">Home</a><br>
        <a href="configuration.php">Configure</a><br>
        <a href="gen2sanity.php">Gen2 Medium Sanity Automation</a><br>
        <a href="gen2FullSanity.php">Gen2 Full Sanity Automation</a><br>
        <a href="gen2setSanity.php">Gen2 Medium Sanity sets</a><br>
        <a href="sqasanity.php">Gen1 Medium Sanity Automation</a><br>
        <a href="fullsanity.php">Gen1 Full Sanity Automation</a><br>
        <a href="parameter.php">Parameter Automation</a><br>
        <a href="tabsanity.php">Tab Sanity Automation</a><br>
        <a href="quicksanity.php">Quick Sanity Automation</a><br>
        <!--<a href="bookVmgUI.php">VMG Booking</a><br>
        <a href="automationrgbui.php">Execute Automation</a><br>-->
        <a href="channelExtra.php">Channel Extra</a><br>
        </td>

</tr>
<tr>
        <td>
                
        </td>
</tr>

</table>

</body>
</html>