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
<div id="content2" style="height:700px;;">
<h1>Add Magazine for sale into database</h1>
<div style="width:220px;float:left;padding:1px;">
<form method="post"  
action="<?php echo $_SERVER['PHP_SELF'];//The filename of the currently executing script, relative to the document root.
?>" enctype="multipart/form-data"><!--To upload file, enctype="multipart/form-data" is needed!!-->
        <label for="mag_name">Magazine Name</label><br />
		<input type="text" name="mag_name"/><br>
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
           		<label for="price_per_year">price/year</label><br />
		<input  type="text" name="price_per_year"  />	<br>
           		<label for="weight_per_issue">weight/issue(lb)</label><br />
		<input  type="text" name="weight_per_issue"  />	<br>
           		<label for="file">Cover Picture</label><br />
		<input  type="file" name="files"  />	<br>
           		<label for="description">Description</label><br />
        <textarea name="description" rows="10" cols="18" placeholder="Description"></textarea>
		<input type="submit" value="Summit" name="submit"/> 
</form>
<?php
	include("DatabaseClass.php");
function SecureSQL($str){
	$newstr=str_replace("'","\'",$str);//SQL-Security
	return $newstr;
	}
	
if (isset($_POST['submit'])) {
if($_POST[issue_period]==="Weekly")	
	$issue_per_year = 48;
elseif($_POST[issue_period]==="Monthly")	
	$issue_per_year = 12;
else $issue_per_year = 4;
    // Connect to the database
	$db->connect();
	//$des=SecureSQL($_POST[description]);
	$addMagazine="INSERT INTO magazineinfo (mag_name,category,issue_period,price_per_year,weight_per_issue,cover_pic,description,issue_per_year)
VALUES
('".@SecureSQL($_POST[mag_name])."','".@SecureSQL($_POST[category])."','$_POST[issue_period]','".@SecureSQL($_POST[price_per_year])."','".@SecureSQL($_POST[weight_per_issue])."','images/magazines/".@SecureSQL($_POST[files])."','".@SecureSQL($_POST[description])."','$issue_per_year')";
	mysql_query($addMagazine);
	$db->close();
	
if ($_FILES["files"]["error"] > 0&&$_FILES["files"]["error"] !==4)
  {
  echo "Error: " . $_FILES["files"]["error"] . "<br />";
  }
else{  
if ((($_FILES["files"]["type"] == "image/gif")
|| ($_FILES["files"]["type"] == "image/jpeg")
|| ($_FILES["files"]["type"] == "image/pjpeg"))
&& ($_FILES["files"]["size"] < 2000000000))
  {
  if ($_FILES["files"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["files"]["error"] . "<br />";
    }
  else
    {
    if (file_exists("upload/" . $_FILES["files"]["name"]))
      {
      echo $_FILES["files"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["files"]["tmp_name"],
      "images/magazines/" . $_FILES["files"]["name"]);
      }
    }
  }
else
  {
  echo "Invalid file";
  }
}
}
?>


</div>
<div style="width:800px;float:left;padding:1px;">

<table border='1' cellspacing='0' class='tt'>
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Category</th>
			<th>Issue Per Year</th>
			<th>Price/year</th>
			<th>Weight/Issue</th>
            <th>Edit</th>
            <th>Delete</th>
		</tr>
	</thead>
    <tbody>
<?php
	$db->connect();
$query_result = mysql_query("SELECT * FROM magazineinfo");
while($row = mysql_fetch_array($query_result)){
	echo "<tr>";
	echo "<td>".$row[0]."</td>";
	echo "<td>".$row[1]."</td>";
	echo "<td>".$row[2]."</td>";
	echo "<td>".$row[3]."</td>";
	echo "<td>".$row[4]."</td>";
	echo "<td>".$row[5]."</td>";
	echo "<td style='background-color:#69F;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='editmagazine.php?ID=".$row[0]."'>Edit</a></td>";
	echo "<td style='background-color:#F63;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='deleteMagazineInfo.php?ID=".$row[0]."'>Delete</td>";
	echo "</tr>";
}
$db->close();
?>
    </tbody>
</table>

</div>

<br>
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