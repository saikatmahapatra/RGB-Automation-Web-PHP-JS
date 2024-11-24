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
        <P align="right"><b><a href="home.php">Back</a><br></b></P>
        </td>
</tr>
</table>
<hr size="5" color="#ff0000" width="100%" noshade> 
<table width="100%" height="%" border="0">
<tr>
        <td>
        <a href="gen2sanitySet1.php">Gen2 sanity Set - 1 (Without Basic Configuration)</a><br>
        <a href="gen2sanitySet2.php">Gen2 sanity Set - 2 (Without Basic Configuration)</a><br>
        <a href="gen2sanitySet3.php">Gen2 sanity Set - 3 (With Basic Configuration)</a><br>
        <a href="gen2sanitySet4.php">Gen2 sanity Set - 4 (Without Basic Configuration)</a><br>
        <a href="gen2sanitySet5.php">Gen2 sanity Set - 5 (Without Basic Configuration)</a><br>
        
        </td>

</tr>
<tr>
        <td>
                
        </td>
</tr>

</table>

</body>
</html>