<?php
//add these code to the beginning of every admin pages!!!
	$cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
	if(!isset($_SESSION['admin_username'])||($_SESSION['admin_key'] !== "h#ka%sa251820fas"))
	header("location: adminDashboard.php");//For security, header the user back to login page if that user is not properly logged
?>
<!DOCTYPE html>
<html>
<head>
<link href="AdminCSS.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="table.css" type="text/css" media="print, projection, screen" />
<link href="form.css" rel="stylesheet" type="text/css" />
<style>
#apDiv {
	position:absolute;
	left:344px;
	top:80px;
	width:132px;
	height:100px;
	z-index:1;
	display: none;
}
#apDiv a{
	text-decoration: none;
	color: #F90;
	font-weight: bold;
	text-align: center;
}
</style>
<script type="text/javascript">
function showmenu(){
	document.getElementById("menuDIV").style.display="block";
}
function hidemenu(){
	document.getElementById("menuDIV").style.display="none";
}
function showmenu2(){
	document.getElementById("apDiv").style.display="block";
}
function hidemenu2(){
	document.getElementById("apDiv").style.display="none";
}
</script>
</head>
<body class="bg">
<div id="menuDIV" onmouseover="showmenu()" onmouseout="hidemenu()">
      <a href="packing.php"><div id="menulink">Packing</div></a>
      <a href="receive.php"><div id="menulink">Receive Magazines</div></a>
</div>
<div id="apDiv" onmouseover="showmenu2()" onmouseout="hidemenu2()">
      <a href="AdminLoggedIn.php"><div id="menulink">Client Info</div></a>
      <a href="orderprocess.php"><div id="menulink">Order Process</div></a>
      <a href="chargeShipping.php"><div id="menulink">Shipping Process</div></a>
      <a href="addMagazines.php"><div id="menulink">Add Magazines</div></a>
</div>

<div id="banner"><span style="color:#09F">A</span><span style="color:#F60">I</span><span style="color:#09F">E</span><span style="font-size:30px">TIMEs</span>
<span style="font-size:10px; color: #666; font-style:normal;">Subscribe Magazines from USA. No matter where you are in the world!</span>
<span style="margin-left:500px; ; font-size:14px;"> 
<?php
	echo "<div style='position:absolute;right:60px;top:18px;'>Hi! ".$_SESSION['admin_username']." ";
	echo "<a href='adminlogout.php' style='text-decoration: none;margin:5px;padding:5px;width:50px;color:white;background-color:#F60;'>log out</a></div>";
?>
</span>
</div>
<div id="main">
<ul id="menu">
<li><a href="homepage.php" onmouseover="showmenu()" onmouseout="hidemenu()">Warehouse Management</a></li>
<li><a href="AdminLoggedIN.php" onmouseover="showmenu2()" onmouseout="hidemenu2()">Client Service</a></li>
<li><a href="about.php">About AIE</a></li>
</ul>
<div id="content">
<div id="content2" style="height:800px">
<!-- Beginning of coding area-->
<div style="margin-left:800px">
</div>
<br>
<?php
echo "<h1>Packing</h1>";
include("DatabaseClass.php");
$db->connect();
if(isset($_GET['ID'])){
	
 	//$chargeshipping="UPDATE `shipping_order` SET `status`='charged' WHERE `shipping_order_id`='".$_GET['ID']."'";
	//mysql_query($chargeshipping);	
	//update status of paymentInfo
//	$row1=mysql_fetch_array(mysql_query("SELECT `payment_id` FROM `shipping_order` WHERE `shipping_order_id`='".$_GET['ID']."'"));	
	//mysql_query("UPDATE `aietimedb`.`paymentinfo` SET `status` = 'charged' WHERE `paymentinfo`.`payment_id` ='".$row1['payment_id']."'");
 $shippinginfo=mysql_query("SELECT * 
 FROM `shippingaddress`,`shipping_order`,`useraccount`
 WHERE `shippingaddress`.`address_id`=`shipping_order`.`address_id`
 AND `shippingaddress`.`user_id`=`useraccount`.`user_id`
 AND `shipping_order`.`shipping_order_id`=".$_GET['ID']);
 while($row2 = mysql_fetch_array($shippinginfo)){
 echo "<table width='500px' style='margin:30px'>";
    echo "<tr>";
    echo "<td>Name: ".$row2['first_name']." ".$row2['last_name']."</td>"; 
	echo "<td>Address: <br>".$row2['address1']." ".$row2['address2']."<br>";
	echo $row2['city']." ".$row2['state']." ".$row2['country'];
	echo "</td>";
	echo "<td>Phone: ".$row2[10]."</td>"; 
    echo "</tr>";	
    echo "</table>";
}}
	?>
<table border='1' cellspacing='0' class='tt' >
	<thead align='center'>
		<tr>
        	<th>Name</th>
			<th>Magazine ID</th>
			<th>Category</th>
			<th>Year</th>
			<th>Month</th>
            <th>Week</th>
			<th>Status</th>
		</tr>
	</thead>
    <tbody align='center'>
<?php
$query_result = mysql_query("SELECT *
FROM `shippingorder_issue`,`issueinfo`,`magazineinfo`
WHERE  `shippingorder_issue`.`issue_id`=`issueinfo`.`issue_id`
AND `issueinfo`.`mag_id`=`magazineinfo`.`mag_id`
AND `shippingorder_issue`.`shipping_order_id`=".$_GET['ID']);
while($row = mysql_fetch_array($query_result)){
	echo "<tr>";
	echo "<td>".$row['mag_name']."</td>";
	echo "<td>".$row['mag_id']."</td>";
	echo "<td>".$row['category']."</td>";
	echo "<td>".$row['issue_year']."</td>";
	echo "<td>".$row['issue_month']."</td>";
	echo "<td>".$row['issue_week']."</td>";
	echo "<td>".$row['status']."</td>";
	echo "</tr>";
}
echo   "</tbody></table>";
echo "<a style='color:#FFF;text-decoration: none;' href='packing.php?ID=".$_GET['ID']."'><button class='button'>Done</button></a> ";
echo "<a style='color:#FFF;text-decoration: none;' href='packing.php'><button class='button'>Cancel</button></a>";
$db->close();
?>



<br>
<br>

<!-- End of Coding area-->
</div>
</div>
</div>

<div id="footer">
  <div class="t"> <!-- to make this div in middle,set the proper of class"t" as margin-left:auto; margin-right:auto  -->
    <div class="sm">
      <dl>
        <dt>Home</dt>
        <dd><a href="homepage.php">Homepage</a></dd>
        <dd><a href="#" title="get latitude&longitude of your pin">Register</a></dd>
      </dl>
      <dl>
        <dt>My Account</dt>
        <dd><a href="http://www.w3schools.com">PHP</a></dd>
        <dd><a href="http://www.w3schools.com">JS</a></dd>
      </dl>
      <dl>
        <dt>About AIE</dt>
        <dd><a href="#">Customer Service</a></dd>
        <dd><a href="#">Contact US</a></dd>
      </dl>
    </div>
    	<div align="center">
        	<img alt="credit card" src="images/footer_cc_img.png" width="211" height="30" />
			<img alt="western union" src="images/western-union-logo.png" width="121" height="30" />
			<img alt="wire transfer" src="images/wire_transfer.png" width="115" height="30" />
        </div>
  </div>
<p style="margin-left:550px;color:#FFF; font-size:12px">&copy; 2010-2012 by Amerika International Express.
All rights reserved. <br>AIETimes.com is a trademark of Amerika International Express.</p>
</div>
</body>
</html>