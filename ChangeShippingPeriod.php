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
<h1>Change Shipping Period</h1>
<div  style="margin-left:10%;">
<?php
include("DatabaseClass.php");
$db->connect();
if(isset($_GET['ID'])){
$query_result = mysql_query("SELECT * FROM 
`order_magazineinfo`,`magazineinfo` 
WHERE order_mag_id='".$_GET['ID']."'
AND `order_magazineinfo`.`mag_id`=`magazineinfo`.`mag_id` 
");
$row = mysql_fetch_array($query_result);
 echo "<table width='500px' style='margin:30px'>";
    echo "<tr>";
    echo "<td rowspan = 6><img src='".$row['cover_pic']."' width=\"220\" height=\"280\" /></td>";
    echo "<td>Name: ".$row['mag_name']."</td>"; 
    echo "</tr>";	
    echo "<tr>";
    echo "<td>Category: ".$row['category']."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Issue Period: ".$row['order_period']."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Price: $".$row['price']."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>";
  echo   "<form method='post' action='ChangeShippingPeriod.php'>";
 echo "<label>Shipping Period</label><br />";
   echo      "<select name='shipping_period'>";
   		if($row['issue_per_month']==4)
		{
        	echo "<option value='1'>One Week</option>";
 			echo "<option value='2'>Two Weeks</option>";
		}
        echo "<option value='3'>One Month</option>";
		echo "<option value='4'>Two Months</option>";
		echo "<option value='5'>Three Month</option>";
   //   echo   "<option value='0'>As soon as possible</option>";
	echo	"</select><br>";
	echo "<input type='hidden' value='".$row['order_mag_id']."' name='ordermagid'/>";
			?>
		<input class='button' type="submit" value="Change" name="submit"/> 
        <a style='color:#FFF;text-decoration: none;' href='shipping.php'><button class='button'>Cancel</button></a>
</form>
    <?php
	echo "</td>";
    echo "</tr>";
    echo "</table>";
}
if(isset($_POST['shipping_period'])){
	mysql_query("UPDATE `order_magazineinfo`
	SET `shipping_period`='".$_POST['shipping_period']."'
	WHERE `order_mag_id`='".$_POST['ordermagid']."'");
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