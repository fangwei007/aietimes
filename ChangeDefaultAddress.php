<?php
	$cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
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
	//$cookies_life_time = 300;//unit is s
	//session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
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
<div id="content2" style="height:auto;">
<!-- Beginning of coding area-->
<h1>Change Default Address</h1>
<div  style="margin-left:10%;">
<?php
include("DatabaseClass.php");
$db->connect();
$query_result = mysql_query("SELECT *
FROM `useraccount`,`shippingaddress`
WHERE `useraccount`.`user_id`='".$_SESSION['user_id']."'
AND `useraccount`.`user_id`=`shippingaddress`.`user_id`");
while($row = mysql_fetch_array($query_result)){
	//echo "<tr>";
	//echo "<td>".$row['first_name']." ".$row['last_name']."</td>";
	//echo "<td> Address: ".$row['address1']." ".$row['address2']." ".$row['city']." ".$row['state']." ".$row['zip']." ".$row['country']." Phone: ".$row['phone']."</td>";
	//echo "<td style='background-color:#69F;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='ChangeDefaultAddress.php'>Change</td>";
	//echo "</tr>";
	
?>

<form method="post" action="changeDefaultAddress.php">
<label for="first">First Name</label><br />
<input type="text" name="first_name" value="<?php echo $row['first_name'];?>"/><br>
<label for="last">Last Name</label><br />
<input type="text" name="last_name" value="<?php echo $row['last_name'];?>"/><br>
		<label for="address1">Address</label><br />
		<input  type="address1" name="address1" value="<?php echo $row['address1'];?>" />	<br>
           		<label for="address2">address2</label><br />
		<input  type="text" name="weight_per_issue" value="<?php echo $row['address2'];?>" /><br>
        <label for="city">city</label><br />
        <input  type="text" name="city" value="<?php echo $row['city'];?>" /><br>
        <label for="state">state</label><br />
        <input  type="text" name="state" value="<?php echo $row['state'];?>" /><br>
        <label for="zip">zip</label><br />
        <input  type="text" name="zip" value="<?php echo $row['zip'];?>" /><br>
                <label for="country">country</label><br />
        <input  type="text" name="country" value="<?php echo $row['country'];?>" /><br>
                <label for="phone">phone</label><br />
        <input  type="text" name="phone" value="<?php echo $row['phone'];?>" /><br>
         <input  type="hidden" name="address_id" value="<?php echo $row['address_id'];?>" /><br>
<input class='button' type="submit" value="Change" name="submit"/> 
        <button class='button'><a style='color:#FFF;text-decoration: none;' href='shipping.php'>Cancel</a></button>
</form>
    <?php
	}
	echo "</td>";
    echo "</tr>";
    echo "</table>";


//if(isset($_POST['first_name'])){
	//echo "```````````".$_POST['address_id'];
if(isset($_POST['first_name']) || isset($_POST['last_name']) || isset($_POST['address1']) || isset($_POST['address2']) || isset($_POST['city']) || isset($_POST['state']) || isset($_POST['zip']) || isset($_POST['country']) || isset($_POST['phone'])) {
	mysql_query("UPDATE `shippingaddress`
	SET `first_name`='".$_POST['first_name']."',`last_name`='".$_POST['last_name']."',`address1`='".$_POST['address1']."',`address2`='".$_POST['address2']."',`city`='".$_POST['city']."',`state`='".$_POST['state']."',`zip`='".$_POST['zip']."',`country`='".$_POST['country']."',`phone`='".$_POST['phone']."'
	WHERE `address_id`='".$_POST['address_id']."'");
	header("location: shipping.php");
	}
$db->close();
?>

</div>
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
        <dd><a href="#">#</a></dd>
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