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
      <a href="myorder.php"><div id="menulink">Order</div></a>
      <a href="shipping.php"><div id="menulink">Shipping</div></a>
</div>
<div id="banner">
<span style="color:#09F">A</span><span style="color:#F60">I</span><span style="color:#09F">E</span><span style="font-size:30px">TIMEs</span>
<span style="font-size:10px; color: #666; font-style:normal;">Subscribe Magazines from USA. No matter where you are in the world!</span>
<span style="margin-left:500px; ; font-size:14px;"> 
<?php
	session_start();
	echo "<div style='position:absolute;right:60px;top:0px;width:300px;'>";
	if(isset($_SESSION['first_name'])) echo "Hi! ".$_SESSION['first_name']." ";//右上角的显示
	if(isset($_SESSION['count']))
	$count=$_SESSION['count'];
	else {
		$_SESSION['count']=0;
		$count=0;
	}
	
	echo "<a href='shoppingcart.php' style='text-decoration: none;margin:5px;padding:5px;width:50px;color:white;background-color:#09F;'>Cart(".$count.") items</a>"." ";//购物车里的显示
	
if(isset($_SESSION['first_name']))
echo "<a href='logout.php' style='text-decoration: none;margin:5px;padding:5px;width:50px;color:white;background-color:#F60;'>log out</a>";//退出登录的显示
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
<div id="content2">
<!-- Beginning of coding area-->
<h1>Shopping Cart</h1>
<div style="margin-left:200px;">
<?php
function magazine_price($mag_id,$quantity, $period){
	$price=mysql_query("SELECT price_per_issue, price_three_months, price_six_months, price_nine_months, price_twelve_months FROM magazineinfo WHERE mag_id = '".$mag_id."'");
	$row = mysql_fetch_array($price);
	if($period =="1 issue")
	{
		$amount=$row[0]*$quantity;
		}
	if($period =="3 months")
	{
		$amount=$row[1]*$quantity;
		}
	if($period=="6 months")
	{
		$amount=$row[2]*$quantity;
		}
	if($period=="9 months")
	{
		$amount=$row[3]*$quantity;
		}
	if($period=="12 months")
	{
		$amount=$row[4]*$quantity;
		}
	return $amount;	
	}
if($count==0){
	echo "<br><h2>Your Shopping cart is empty!</h2><br>";
	echo "<button class='button'><a style='color:#FFF;text-decoration: none;' href='magazines.php'>Continue Shopping</a></button>";
	echo "<br>
<br>
<br>
<br>
";
}
else{
echo "<button class='button'><a style='color:#FFF;text-decoration: none;' href='placeorder.php'>Place Order</a></button> ";
echo "<button class='button'><a style='color:#FFF;text-decoration: none;' href='magazines.php'>Continue Shopping</a></button>";
	include("DatabaseClass.php");
	$db->connect();
for($i=0;$i<$_SESSION['count'];$i++){
	echo "<br>";
	$query_result1 = mysql_query("SELECT *
					FROM `magazineinfo`
					WHERE `mag_id` = '".$_SESSION['mag_id'][$i]."'");
	$result1 = mysql_fetch_array($query_result1);
	echo "<table width='500px' style='margin:30px'>";
    echo "<tr>";
    echo "<td rowspan = 8><a href = 'Display.php?newpath=".$result1[0]."'><img src='".$result1[5]."' width=\"120\" height=\"155\" /></a></td>";
    echo "<td>Name: ".$result1[1]."</td>"; 
    echo "</tr>";	
    echo "<tr>";
    echo "<td>Category: ".$result1[2]."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Cover Price: $ ".$result1[3]." per issue</td>";
    echo "</tr>";
	echo "<tr>";
    echo "<td>Period: ".$result1[7]." issues per month</td>";
    echo "</tr>";
	echo "<tr>";	
    echo "<td>Issues: ".$_SESSION['period'][$i]."</td>";
    echo "</tr>";
	
	echo "<tr>";	
    echo "<td>Qty: ".$_SESSION['quantity'][$i]."</td>";
    echo "</tr>";
	echo "<tr>";	
    echo "<td>Subtotal: $".magazine_price($_SESSION['mag_id'][$i],$_SESSION['quantity'][$i],$_SESSION['period'][$i])."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><button class='button' style='height:50px;background-color:#09F;'><a style='color:#FFF;text-decoration: none;' href='delete_item.php?item=".$i."'>Delete</a></button></td>";
	echo "<td><button class='button' style='height:50px; width:100px;background-color:#09F;'><a style='color:#FFF;text-decoration: none;' href='checkout.php'>Estimate Shipping Cost</a></button></td> ";
    echo "</tr>";
    echo "</table>";
	echo "<hr align='center' noshade='noshade' size='2px' width='80%'/>";
	}
	$db->close();
echo "<button class='button'><a style='color:#FFF;text-decoration: none;' href='placeorder.php'>Place Order</a></button> ";
echo "<button class='button'><a style='color:#FFF;text-decoration: none;' href='magazines.php'>Continue Shopping</a></button>";
}
?>
<!--</table>-->

</div>
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