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
<?php
include("DatabaseClass.php");
$link=$db->connect();
$error = "";
$success = "";
if($_POST){
	//echo '<pre/>';print_r($_POST);exit;
	//checking image
	//define a maxim size for the uploaded images in Kb
	define ("MAX_SIZE","1024"); 
	$newname = '';

	//This function reads the extension of the file. It is used to determine if the file  is an image by checking the extension.
	function getExtension($str) {
	         $i = strrpos($str,".");
	         if (!$i) { return ""; }
	         $l = strlen($str) - $i;
	         $ext = substr($str,$i+1,$l);
	         return $ext;
	}

	$error = "";

	//reads the name of the file the user submitted for uploading
	$f_csv = $_FILES['impCsv']['name'];

	//if it is not empty
	if ($f_csv){
		//get the original name of the file from the clients machine
		$filename = stripslashes($_FILES['impCsv']['name']);

		//get the extension of the file in a lower case format
		$extension = getExtension($filename);
		$extension = strtolower($extension);

		//if it is not a known extension, we will suppose it is an error and will not upload the file,  

		//otherwise we will do more tests
		if($extension != "csv"){
			//print error message
			$error .= '<h1 style="color:red">Unknown extension!</h1>';
		}

		else{
			//get the size of the image in bytes

			//in which the uploaded file was stored on the server
			$size=filesize($_FILES['impCsv']['tmp_name']);

			//compare the size with the maxim size we defined and print error if bigger
			if ($size > MAX_SIZE*1024){
				$error .= '<h1 style="color:red">You have exceeded the size limit!</h1>';
			}
			else{
			   $users = file($_FILES['impCsv']['tmp_name']);
			   $flag = false;			   

			   //checking if data exist
	       	   $qry_data = "SELECT *FROM countries LIMIT 1";
	       	   $qry_result = mysql_query($qry_data);
	       	   $qry_val = mysql_fetch_array($qry_result,MYSQL_ASSOC);

			   //$continents_id=1;
			   foreach ($users as $user) {
			      list($col1, $col2, $col3, $col4, $col5, $col6) = explode(",", $user);
			      if($col2 == 'Albania'){
			      	 $flag = true;
			      }

			      if(($flag == true) && ($col1 != '')){
			      	  $col_count = 2;
						  $DHL_continents_id=$col1;
						  $TNT_continents_id=$col1;
						  $Fedex_continents_id=$col1;
						  $Fedex_Eco_continents_id=$col1;
						  $country_id=$col6;
						   
					      $DHL_country = 'col'.$col_count;
						  $col_count++;
						  $TNT_country = 'col'.$col_count;
						  $col_count++;
						  $Fedex_country = 'col'.$col_count;
						  $col_count++;
						  $Fedex_Eco_country = 'col'.$col_count;
						  
						  if($col2 == '')
						  {
							  $DHL_continents_id=0;
						  }
							  
						  if($col3 == '')
						  {
							  $Fedex_continents_id=0;
						  }
						  
						  if($col4 == '')
						  {
							  $Fedex_Eco_continents_id=0;
						  }
						  
						  if($col5 == '')
						  {
							  $TNT_continents_id=0;
						  }
						  
						  //update
						  if($qry_val['Name'] != '')  
						  {
							  $query = "UPDATE countries SET DHL_continents_id=".$DHL_continents_id.", TNT_continents_id=".$TNT_continents_id.", Fedex_continents_id=".$Fedex_continents_id.", Fedex_Eco_continents_id=".$Fedex_Eco_continents_id." WHERE ID='".$country_id."';";
						  }
						  //insert
						  else if($qry_val['Name'] == '')
						  {
						  	  $query = "INSERT INTO countries(DHL_continents_id,TNT_continents_id,Fedex_continents_id,Fedex_Eco_continents_id,Name) VALUES('".$DHL_continents_id."','".$TNT_continents_id."','".$Fedex_continents_id."','".$Fedex_Eco_continents_id."','".$$DHL_country."')";
						  }

					       //echo $query."<br/>";
					       $result = mysql_query($query);
			      }
			   }//end foreach

			   if($result){
			   	  $error = "";
				  	$success = "<h3>Countrylist imported successfully.</h3>";
			   }else{
			      $error .= "<h3 style='color:red'>Problem importing pricelist</h3>";
				  	$success = "";
			   }  
			}//end else
		}
	}//end if

	else{
		$error .= "<h3 style='color:red'>You didn't select any file.</h3>";
	}
}
?>

<!-- body : Start -->
<form name="fImport" action="" method="post" enctype="multipart/form-data">
	<table cellpadding="0" cellspacing="0" width="95%" border="0" align="center">
		<tr><td colspan="2" align="center">
			<?php 
			if($error != ''){
				echo $error;
			}
			if($success != ''){
				echo $success;
			}
			?>
			</td>
        </tr>

		<tr>
			<td class="main" style="font-size:14px; color:#003366;" colspan="2">
				<b>Manage Countrylist</b><hr>
			</td>
		</tr>

		<tr>
			<td colspan="2" id="adminGlobalMsg" align="center"></td>
		</tr>

		<tr>
			<td height="3px;"></td>
		</tr>

		<tr>
			<td width="20%" align="left" valign="top">Select CSV file : </td>
			<td width="75%" align="left">
				<input type="file" id="impCsv" name="impCsv" />
			</td>
		</tr>

		<tr>
			<td height="3px;"></td>
		</tr>

		<tr>
			<td>&nbsp;</td>
			<td width="75%" align="left">
				<input type="submit" id="priceSaveBtn" name="priceSaveBtn" value="Import" style="cursor:pointer;" />
				<span id="spinPage" style="display:none;"><img src="images/spinner.gif" border="0" alt="spinner" /></span>
			</td>
		</tr>
	</table>
</form>
<!-- bodyend -->

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