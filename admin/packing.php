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
#PMDiv {
	position:absolute;
	left:483px;
	top:80px;
	width:163px;
	height:100px;
	z-index:1;
	display: none;
}
#PMDiv a{
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
function showmenu3(){
	document.getElementById("PMDiv").style.display="block";
}
function hidemenu3(){
	document.getElementById("PMDiv").style.display="none";
}
</script>
</head>
<body class="bg">
<div id="menuDIV" onmouseover="showmenu()" onmouseout="hidemenu()">
      <a href="packing.php"><div id="menulink">Packing</div></a>
      <a href="receive.php"><div id="menulink">Receive Magazines</div></a>
</div>
<div id="apDiv" onmouseover="showmenu2()" onmouseout="hidemenu2()">
      <a href="client_info.php"><div id="menulink">Client Info</div></a>
      <a href="orderprocess.php"><div id="menulink">Order Process</div></a>
      <a href="chargeShipping.php"><div id="menulink">Shipping Process</div></a>
      <a href="addMagazines.php"><div id="menulink">Add Magazines</div></a>
</div>
<div id="PMDiv" onmouseover="showmenu3()" onmouseout="hidemenu3()">
      <a href="pricelist_manager.php"><div id="menulink">Manage Pricelist</div></a>
      <a href="countrylist_manager.php"><div id="menulink">Manage Countrylist</div></a>
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
<li><a href="packing.php" onmouseover="showmenu()" onmouseout="hidemenu()">Warehouse Management</a></li>
<li><a href="client_info.php" onmouseover="showmenu2()" onmouseout="hidemenu2()">Client Service</a></li>
<li><a href="pricelist_manager.php" onmouseover="showmenu3()" onmouseout="hidemenu3()">Pricelist Manager</a></li>
<li><a href="about.php">About AIE</a></li>
</ul>
<div id="content">
<div id="content2" style="height:800px">
<!-- Beginning of coding area-->
<div style="margin-left:800px">
</div>
<br>
<?php
			$date=getdate();
			$today=$date['year'].'-'.$date['mon'].'-'.$date['mday'];//change format
include("DatabaseClass.php");
$db->connect();
if(isset($_GET['ID'])){
 	$chargeshipping="UPDATE `shipping_order` SET `status`='Shipped',`shipping_date`='".$today."' WHERE `shipping_order_id`='".$_GET['ID']."'";
	mysql_query($chargeshipping);	
}
$db->close();
?>
<h1>Packing</h1>
<table border='1' cellspacing='0' class='tt' >
	<thead align='center'>
		<tr>
        	<th>User ID</th>
			<th>Shipping Order ID</th>
			<th>address_id ID</th>
			<th>Payment ID</th>
			<th>shipping_weight</th>
            <th>shipping_price</th>
			<th>Status</th>
            <th>Packing List</th>
		</tr>
	</thead>
    <tbody align='center'>
<?php
	$db->connect();
/*$query_result = mysql_query("SELECT * 
FROM  `shipping_order` ,  `shippingorder_issue` ,  `issueinfo` 
WHERE  `shipping_order`.STATUS =  'charged'
AND  `shippingorder_issue`.shipping_order_id =  `shipping_order`.shipping_order_id
AND  `shippingorder_issue`.issue_id =  `issueinfo`.issue_id
ORDER BY  `shipping_order`.user_id ASC
LIMIT 0 , 30");*/
$query_result = mysql_query("SELECT * 
FROM  `shipping_order` ,  `shippingorder_issue` ,  `issueinfo` 
WHERE  `shipping_order`.STATUS =  'charged'
AND  `shippingorder_issue`.shipping_order_id =  `shipping_order`.shipping_order_id
AND  `shippingorder_issue`.issue_id =  `issueinfo`.issue_id
ORDER BY  `shipping_order`.shipping_order_id DESC
LIMIT 0 , 30");
$tmp_shipping_orser_id=0;
$flag_all_received=1;
$shipping_order_id;
$user_id;
$address_id;
$payment_id;
$shipping_weight;
$shipping_price;
$status;
while($row = mysql_fetch_array($query_result)){
	if($row['shipping_order_id']==$tmp_shipping_orser_id)
	{
		if($row[17]=="OnShippingOrder")//$row[17] is issueinfo.status
			$flag_all_received=0;
		$shipping_order_id=$row['shipping_order_id'];
		$user_id=$row['user_id'];
		$address_id=$row['address_id'];
		$payment_id=$row['payment_id'];
		$shipping_weight=$row['shipping_weight'];
		$shipping_price=$row['shipping_price'];
		$status=$row['status'];
		
		$tmp_shipping_orser_id=$row['shipping_order_id'];
	}
	else if($row['shipping_order_id']!=$tmp_shipping_orser_id)
	{
		if($tmp_shipping_orser_id!=0)
		{
			echo "<tr>";
			echo "<td>".$shipping_order_id."</td>";
			echo "<td>".$user_id."</td>";
			echo "<td>".$address_id."</td>";
			echo "<td>".$payment_id."</td>";
			echo "<td>".$shipping_weight."</td>";
			echo "<td>".$shipping_price."</td>";
			echo "<td>".$status."</td>";
			if($flag_all_received==1)
				echo "<td style='background-color:#69F;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='Picking.php?ID=".$shipping_order_id."'>Start Picking</a></td>";
			else if($flag_all_received==0)
				echo "<td style='background-color:#FF0000;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='shippingorder.php?ID=".$shipping_order_id."'>Not Received</a></td>";
		}
		
		//echo $row[17];
		if($row[17]=="OnShippingOrder")//$row[17] is issueinfo.status
			$flag_all_received=0;
		else if($row[17]=="received")//$row[17] is issueinfo.status
			$flag_all_received=1;
		//echo $flag_all_received;
		$shipping_order_id=$row['shipping_order_id'];
		$user_id=$row['user_id'];
		$address_id=$row['address_id'];
		$payment_id=$row['payment_id'];
		$shipping_weight=$row['shipping_weight'];
		$shipping_price=$row['shipping_price'];
		$status=$row['status'];
		
		$tmp_shipping_orser_id=$row['shipping_order_id'];
	}
	/*$tmp_shipping_orser_id=$row['shipping_order_id'];
	echo "<tr>";
	echo "<td>".$row['shipping_order_id']."</td>";
	echo "<td>".$row['user_id']."</td>";
	echo "<td>".$row['address_id']."</td>";
	echo "<td>".$row['payment_id']."</td>";
	echo "<td>".$row['shipping_weight']."</td>";
	echo "<td>".$row['shipping_price']."</td>";
	echo "<td>".$row['status']."</td>";//$row[5] is shipping_order.status
	if($row[16]=="OnShippingOrder")//$row[16] is issueinfo.status
		echo "<td style='background-color:#FF0000;color:#fff;'><a style='color:#FFF;text-decoration: none;'>Not Received</a></td>";
	else if($row[16]=="received")
		echo "<td style='background-color:#69F;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='Picking.php?ID=".$row['shipping_order_id']."'>Start Picking</a></td>";
	echo "</tr>";*/
}

//echo last shipping order
if($tmp_shipping_orser_id!=0)
{
	echo "<tr>";
	echo "<td>".$shipping_order_id."</td>";
	echo "<td>".$user_id."</td>";
	echo "<td>".$address_id."</td>";
	echo "<td>".$payment_id."</td>";
	echo "<td>".$shipping_weight."</td>";
	echo "<td>".$shipping_price."</td>";
	echo "<td>".$status."</td>";
	if($flag_all_received==1)
		echo "<td style='background-color:#69F;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='Picking.php?ID=".		$shipping_order_id."'>Start Picking</a></td>";
	else if($flag_all_received==0)
		echo "<td style='background-color:#FF0000;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='shippingorder.php?ID=".$shipping_order_id."'>Not Received</a></td>";
}
$db->close();
?>
    </tbody>
</table>
<br>
<h1>Shipped Order</h1>
<table border='1' cellspacing='0' class='tt' >
	<thead align='center'>
		<tr>
        	<th>User ID</th>
			<th>Shipping Order ID</th>
			<th>address_id ID</th>
			<th>Payment ID</th>
			<th>shipping_weight</th>
            <th>shipping_price</th>
			<th>Status</th>
		</tr>
	</thead>
    <tbody align='center'>
<?php
	$db->connect();
$query_result = mysql_query("SELECT *
FROM `shipping_order`
WHERE STATUS = 'Shipped'
ORDER BY user_id ASC
LIMIT 0 , 30");
while($row = mysql_fetch_array($query_result)){
	echo "<tr>";
	echo "<td>".$row['user_id']."</td>";
	echo "<td>".$row['shipping_order_id']."</td>";
	echo "<td>".$row['address_id']."</td>";
	echo "<td>".$row['payment_id']."</td>";
	echo "<td>".$row['shipping_weight']."</td>";
	echo "<td>".$row['shipping_price']."</td>";
	echo "<td>".$row['status']."</td>";
	echo "</tr>";
}
$db->close();
?>
    </tbody>
</table>

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