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
<li><a href="picking.php" onmouseover="showmenu()" onmouseout="hidemenu()">Warehouse Management</a></li>
<li><a href="AdminLoggedIn.php" onmouseover="showmenu2()" onmouseout="hidemenu2()">Client Service</a></li>
<li><a href="homepage.php" onmouseover="showmenu3()" onmouseout="hidemenu3()">Pricelist Manager</a></li>
<li><a href="about.php">About AIE</a></li>
</ul>
<div id="content">
<div id="content2">
<!-- Beginning of coding area-->
<div align="center" style="margin-left:30px">
<form align="center" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
<select name="target">
  <option>Customer ID</option>
</select>
<input type="text" name="search" value="" size="40" placeholder=''/>
<input type="submit" value="search" name="submit">
</form>
</div>
<hr align="center" noshade="noshade" size="2px" width="80%"/>
<br>

<?php
	include("DatabaseClass.php");
	$link = $db->connect();
	echo "<h1>Client Info</h1>";
    if(isset($_POST['search'])&&$_POST['search']!="")
    {
   	$ID = strip_tags($_POST["search"]);
    $category = $_POST["target"];
    //echo "You are searching <FONT color='orange'>".$ID."</FONT> <FONT color='orange'>".$category."</FONT><br/><br/>";
    //mysearch($name,$category,$issue_period,$price);
    $sql = "select * from useraccount where user_id = ".$ID;
	$res = mysql_query($sql); 
 	echo "<table border='1' cellspacing='0' class='tt' >";
	echo "<thead align='center'>";
	echo "<tr>";
	echo "<th>User ID</th>";
	echo "<th>Name</th>";
    echo "<th>Register Date</th>";
    echo "<th>Email</th>";
    echo "<th>Details</th>";
	echo "</tr>";
	echo "</thead>";
    echo "<tbody align='center'>";
    while($result = mysql_fetch_row($res)){
		
		  echo "<tr><td><font color=\"#CC9933\">$result[0]</font></td>";
		  echo "<td><a href='userdisplay.php?UID=".$result[0]."'><button class='button'>Details</button></a><br/></td></tr>";
		  
		}
   }
   else
   {
   	$sql = "select * from useraccount";
	$res = mysql_query($sql);	
	echo "<table border='1' cellspacing='0' class='tt' >";
	echo "<thead align='center'>";
	echo "<tr>";
	echo "<th>User ID</th>";
	echo "<th>Name</th>";
    echo "<th>Register Date</th>";
    echo "<th>Email</th>";
    echo "<th>Details</th>";
	echo "</tr>";
	echo "</thead>";
    echo "<tbody align='center'>"; 
    while($result = mysql_fetch_row($res))
    {
		echo "<tr><td><font color=\"#CC9933\">$result[0]</font></td>";
		echo "<td><a href='userdisplay.php?UID=".$result[0]."'><button class='button'>Details</button></a><br/></td></tr>";
   	}
   
   }
   echo "</tbody>";
   echo "</table>";
?>




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