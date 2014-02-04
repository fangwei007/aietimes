<?php
	session_start();
	if(!isset($_SESSION['user_id']))
	header("location: index.php");//For security, header the user back to login page if that user is not properly logged
?>
<!DOCTYPE html>
<html>
<head>
<link href="AIEtimeCSS.css" rel="stylesheet" type="text/css" />
<link href="form.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="table.css" type="text/css" media="print, projection, screen" />
<script type="text/javascript">
function showmenu(){
	document.getElementById("menuDIV").style.display="block";
}
function hidemenu(){
	document.getElementById("menuDIV").style.display="none";
}
</script>


<!--<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/edittable.js"></script>-->


</head>
<body class="bg">
<div id="menuDIV" onmouseover="showmenu()" onmouseout="hidemenu()">
      <a href="account.php"><div id="menulink">Account</div></a>
      <a href="Myorder.php"><div id="menulink">Order</div></a>
      <a href="shipping.php"><div id="menulink">Shipping</div></a>
</div>
<div id="banner">
<span style="color:#09F">A</span><span style="color:#F60">I</span><span style="color:#09F">E</span><span style="font-size:30px">TIMEs</span>
<span style="font-size:10px; color: #666; font-style:normal;">Subscribe Magazines from USA. No matter where you are in the world!</span>
<span style="margin-left:500px; ; font-size:14px;"> 
<?php
	//session_start();
	echo "<div style='position:absolute;right:60px;top:0px;width:300px;'>";
	if(isset($_SESSION['first_name'])) echo "Hi! ".$_SESSION['first_name']." ";
	if(isset($_SESSION['count']))
	$count=$_SESSION['count'];
	else $count=0;
	
	echo "<a href='shoppingcart.php' style='text-decoration: none;margin:5px;padding:5px;width:50px;color:white;background-color:#09F;'>Cart(".$count.") items</a>"." ";
	
if(isset($_SESSION['first_name']))
echo "<a href='logout.php' style='text-decoration: none;margin:5px;padding:5px;width:50px;color:white;background-color:#F60;'>log out</a>";
	echo "</div>";
?>
</span>
</div>
<div id="main">
<ul id="menu">
<li><a href="homepage.php">Home</a></li>
<li><a href="magazines.php">Magazines</a></li>
<li><a href="account.php" onmouseover="showmenu()" onmouseout="hidemenu()">My account</a></li>
<li><a href="about.php">About AIE</a></li>
</ul>
<div id="content">
<div id="content2" style="height:500px;">
<!-- Beginning of coding area-->
<?php
include("DatabaseClass.php");
$db->connect();?>
<h1>My Info</h1>
<table border='1' cellspacing='0' class='tt' >
	<thead align='center'>
		<tr>
        	<th>user_id</th>
			<th>address</th>
			<th>city</th>
			<th>state</th>
			<th>zip</th>
            <th>email</th>       
		</tr>
 	</thead>
    <tbody align='center'>
<?php
$query_result = mysql_query("SELECT *
FROM `useraccount`,`shippingaddress`
WHERE `useraccount`.`user_id`=`shippingaddress`.`user_id`
AND `useraccount`.`user_id`='".$_SESSION['user_id']."'
LIMIT 0 , 30");
while($row = mysql_fetch_array($query_result)){
	echo "<tr>";
	echo "<td>".$row['user_id']."</td>";
	echo "<td>".$row['address1']." ".$row['address2']."</td>";
	echo "<td>".$row['city']."</td>";
	echo "<td>".$row['state']."</td>";
	echo "<td>".$row['zip']."</td>";
	echo "<td>".$row['email']."</td>";
	//echo "<td rowspan=\"3\"><button class='button'><a href='edit.php'>Edit</a></button></td";
	echo "</tr>";
	//echo "<tr><td><button class='button'><a href='edit.php'>Edit</a></button></td></tr>";
}

?>
    </tbody>
</table>

<table align='right'>
		<tr>
        	<th><button><a href="edit1.php"><font color="#000000">Edit</font></a></button></th>  
		</tr>
</table>

<table border='1' cellspacing='0' class='tt' >
	<thead align='center'>
		<tr>
        	<th>Card No.</th>
			<th>billing_address</th>
			<th>billing_city</th>
			<th>billing_country</th>
		</tr>
	</thead>
    <tbody align='center'>
<?php
$query_result = mysql_query("SELECT *
FROM `creditcardinfo`
WHERE `creditcardinfo`.`user_id`='".$_SESSION['user_id']."'");
while($row = mysql_fetch_array($query_result)){
	echo "<tr>";
	echo "<td>".$row['card_number']."</td>";
	echo "<td>".$row['billing_address1']."</td>";
	echo "<td>".$row['billing_city']."</td>";
	echo "<td>".$row['billing_country']."</td>";
	echo "</tr>";
}
  echo  "</tbody>";
echo "</table>";
?>
<table align='right'>
		<tr>
        	<th><button><a href="edit2.php"><font color="#000000">Edit</font></a></button></th>  
		</tr>
</table>
<?php
$db->close();
?>
<br>
<br><br>
<br>
<br>
<br>
<br>
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
<p style="margin-left:550px;color:#FFF; font-size:12px">&copy; 2010-2012 by Amerika International Express.<br>
All rights reserved. AIETimes.com is a trademark of Amerika International Express.</p>
</div>
</body>
</html>