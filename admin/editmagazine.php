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
<link href="AIEtimeCSS.css" rel="stylesheet" type="text/css" />
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
      <a href="#"><div id="menulink">Account</div></a>
      <a href="#"><div id="menulink">Shipping</div></a>
</div>

<div id="banner"><span style="color:#09F">A</span><span style="color:#F60">I</span><span style="color:#09F">E</span><span style="font-size:30px">TIMEs</span>
<span style="font-size:10px; color: #666; font-style:normal;">Subscribe Magazines from USA. No matter where you are in the world!</span></div>
<div id="main">
<ul id="menu">
<li><a href="packing.php" onmouseover="showmenu()" onmouseout="hidemenu()">Warehouse Management</a></li>
<li><a href="client_info.php" onmouseover="showmenu2()" onmouseout="hidemenu2()">Client Service</a></li>
<li><a href="pricelist_manager.php" onmouseover="showmenu3()" onmouseout="hidemenu3()">Pricelist Manager</a></li>
<li><a href="about.php">About AIE</a></li>
</ul>
<div id="content">
<div id="content2">
<!-- Beginning of coding area-->
<div  style="margin-left:38%;">
<?php
include("DatabaseClass.php");
$db->connect();
$query_result = mysql_query("SELECT * FROM magazineinfo WHERE mag_id='".$_GET['ID']."'");
$row = mysql_fetch_array($query_result);
//$db->close();
?>
<form method="post" action="changeMagazineInfo.php?ID=<?PHP echo $_GET['ID']; ?>">
 <label for="mag_name">Magazine Name</label><br />
		<input type="text" name="mag_name" value="<?php echo $row[1];?>"/><br>
       	<label for="category">Category</label><br />
        <select name="category">
 		 <option>Business & Finance</option>
 		 <option>Fashion</option>
 		 <option>Entertainment</option>
  		 <option>Health & Fitness</option>
		 <option>Men's</option>
		 <option>Women's</option>
 		 <option>Sports</option>
		</select><br>
        <label for="issue_period">Issue Period</label><br />
        <select name="issue_period">
         <option >Weekly</option>
 		 <option >Monthly</option>
         <option >Seasonly</option>
		</select><br>
           		<label for="price_per_issue">price/issue</label><br />
		<input  type="text" name="price_per_issue" value="<?php echo $row[3];?>" />	<br>
           		<label for="price_three_months">price/3 months</label><br />
		<input  type="text" name="price_three_months" value="<?php echo $row[9];?>" />	<br>
                <label for="price_six_months">price/6 months</label><br />
		<input  type="text" name="price_six_months" value="<?php echo $row[10];?>" />	<br>
                <label for="price_nine_months">price/9 months</label><br />
		<input  type="text" name="price_nine_months" value="<?php echo $row[11];?>" />	<br>
                <label for="price_twelve_months">price/12 months</label><br />
		<input  type="text" name="price_twelve_months" value="<?php echo $row[12];?>" />	<br>
                <label for="weight_per_issue">weight/issue(lb)</label><br />
		<input  type="text" name="weight_per_issue" value="<?php echo $row[4];?>" /><br>
                <label for="issue_per_month">issue/month</label><br />
		<input  type="text" name="issue_per_month" value="<?php echo $row[7];?>" />	<br>
                <label for="status">status</label><br />
		<input  type="text" name="status" value="<?php echo $row[8];?>" /><br>
                <label for="file">Cover Picture</label><br />
		<input  type="file" name="files" value="<?php echo $row[5];?>"/><!--when type="file", value will not appear on screen but it will still appear in code-->	<br>
           		<label for="description">Description</label><br />
        <textarea name="description" rows="10" cols="18" ><?php echo $row[6]?></textarea><br>
		<input type="submit" value="Change" name="submit"/> <a href="addMagazines.php"><button>Cancel</button></a>
</form>
<?php $db->close();?>
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