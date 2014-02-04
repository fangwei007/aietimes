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
<div id="content2" style="height:auto">
<!-- Beginning of coding area-->
<div align="center" style="margin-left:30px">
</div>

<br>

<h1>Order Process</h1>
<table border='1' cellspacing='0' class='tt' >
	<thead align='center'>
		<tr>
			<th>Order ID</th>
			<th>User ID</th>
			<th>Payment ID</th>
			<th>Order Date</th>
            <th>Magazine</th>
			<th>Status</th>
            <th>Fulfill</th>
            <th>Cancel</th>
		</tr>
	</thead>
    <tbody align='center'>
<?php
include("DatabaseClass.php");
	$db->connect();
$query_result = mysql_query("SELECT *
FROM `order`
WHERE STATUS = 'pending'
ORDER BY user_id, start_date ASC
LIMIT 0 , 30");
while($row = mysql_fetch_array($query_result)){
	echo "<tr>";
	echo "<td>".$row['order_id']."</td>";
	echo "<td>".$row['user_id']."</td>";
	echo "<td>".$row['payment_id']."</td>";
	echo "<td>".$row['start_date']."</td>";
	echo "<td><ol>";
		$magazineInfo = mysql_query("SELECT *
			FROM `order_magazineinfo`,`magazineinfo`
			WHERE `order_id`='".$row['order_id']."'
			AND `order_magazineinfo`.`mag_id`=`magazineinfo`.`mag_id`");
		while($list = mysql_fetch_array($magazineInfo)){
			echo "<li>".$list['mag_name']." --- MagID: ".$list['mag_id']." --- Issue Num: ".$list['number_of_issue']." --- Price: ".$list['price']."</li>";}
	echo "</ol></td>";
	echo "<td>".$row['status']."</td>";
	echo "<td style='background-color:#69F;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='fulfillOrder.php?ID=".$row['order_id']."'>Fulfill</a></td>";
	echo "<td style='background-color:#F63;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='orderCancel.php?ID=".$row['order_id']."'>Cancel</a></td>";
	echo "</tr>";
}
$db->close();
?>
    </tbody>
</table>


<br>
<h1>Fulfilled Order</h1>
<table border='1' cellspacing='0' class='tt' >
	<thead align='center'>
		<tr>
			<th>Order ID</th>
			<th>User ID</th>
			<th>Payment ID</th>
			<th>Order Date</th>
            <th>Magazine</th>
			<th>Status</th>
		</tr>
	</thead>
    <tbody align='center'>
<?php
	$db->connect();
$query_result = mysql_query("SELECT * FROM `order`WHERE STATUS = 'Subscribed' ORDER BY user_id, start_date ASC
LIMIT 0 , 30");
while($row = mysql_fetch_array($query_result)){
	$magazineInfo = mysql_query("SELECT *
FROM `order_magazineinfo`,`magazineinfo`
WHERE `order_id`='".$row['order_id']."'
AND `order_magazineinfo`.`mag_id`=`magazineinfo`.`mag_id`");
	echo "<tr>";
	echo "<td>".$row['order_id']."</td>";
	echo "<td>".$row['user_id']."</td>";
	echo "<td>".$row['payment_id']."</td>";
	echo "<td>".$row['start_date']."</td>";
	echo "<td><ol>";
		while($list = mysql_fetch_array($magazineInfo)){
			echo "<li>".$list['mag_name']." --- MagID: ".$list['mag_id']." --- Issue Num: ".$list['number_of_issue']." --- Price: ".$list['price']."</li>";}
	echo "</ol></td>";
	echo "<td>".$row['status']."</td>";
}
$db->close();
?>
    </tbody>
</table>
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