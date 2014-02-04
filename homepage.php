<?php
	$cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
	if(!isset($_SESSION['user_id']))
	header("location: index.php");//modify it from index.php For security, header the user back to login page if that user is not properly logged
?>
<!DOCTYPE html>
<html>
<head>
<title>AIE-Times</title>
<link href="AIEtimeCSS.css" rel="stylesheet" type="text/css" />
<link href="form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function showmenu(){
	document.getElementById("menuDIV").style.display="block";
}
function hidemenu(){
	document.getElementById("menuDIV").style.display="none";
}
</script>
<style>
div.img
  {
  margin:2px;
  height:auto;
  width:auto;
  float:left;
  text-align:center;
  }
</style>
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
<div style="height:190px;">
<div style="float:left; width:220px; height:200px; margin-left:20px;margin-right:4px">

<?php
if (!isset($_SESSION['user_id'])&&isset($_POST['submit'])) {
	include("DatabaseClass.php");
    // Connect to the database
	$db->connect();
    // Grab the user-entered log-in data
    $user_email = mysql_real_escape_string(trim($_POST['email']));
	//mysql_real_escape_string — Escapes special characters in a string for use in an SQL statement.
	//trim — Strip whitespace (or other characters) from the beginning and end of a string.
    $user_password = mysql_real_escape_string(trim($_POST['password']));
	  
      if (!empty($user_email) && !empty($user_password)) {
        // Look up the username and password in the database
        $query_result = mysql_query("SELECT user_id, first_name, email, password FROM useraccount WHERE email = '$user_email' AND password = '$user_password'");

        if (mysql_num_rows($query_result) === 1) {
          // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
          $row = mysql_fetch_array($query_result);
		  $cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
          $_SESSION['user_id'] = $row['user_id'];
		  $_SESSION['first_name'] = $row['first_name'];
          setcookie('email', $row['email'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
		  $home_url = 'homepage.php';
          header('Location: ' . $home_url);
        }
        else {
          echo "<script>invalidelogin()</script>";
        }
      }
      else {
		echo "<script>invalidelogin()</script>";
      }
 $db->close();
}
?>


</div>
<div style="float:left;width:400px;">
<img src="images/magazine.jpg" height="192"  margin="0px">
</div>
</div>

<div id="content2" style="height:400px">
<!-- Beginning of coding area-->

<h1 id="title"></h1>
<h1 align="center" id="title">Want to subscribe magazines of USA but live in other countries?<br>
AIE will do the best to serve you with a minimum shipping fee. </h1>
<br>
<div style=" margin-left:150px;margin-right:auto;">
<?php
include("DatabaseClass.php");
$db->connect();
 	$sql = "select * from magazineinfo";
	$res = mysql_query($sql);  
	for($i=0;$i<5;$i++){
    	$result = mysql_fetch_row($res);
		  echo "<div class='img'>";
  		  echo "<a href = 'Display.php?newpath=".$result[0]."'><img src='".$result[5]."' width=\"120\" height=\"160\" /></a><br>";
        echo "<div style='background-color:#123; width:140px; font-size:12px;'><a href = 'Display.php?newpath=".$result[0]."'>".$result[1]."</a></div><br>";
          echo "Price: $".$result[3]."<br>Issues: ".$result[7]."/month<br>"; 	
		  echo "</div>";	  
	}
$db->close();
?>

</div>

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
<p style="margin-left:550px;color:#FFF; font-size:12px">&copy; 2010-2013 by Wei Fang </p>
</div>
</body>
</html>