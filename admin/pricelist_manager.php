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
$db->connect();

if($_POST){

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

		//if it is not a known extension, we will suppose it is an error and will not  upload the file,  

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

			}else{

			   $users = file($_FILES['impCsv']['tmp_name']);

			   $flag = false;

			   

			   //checking if data exist

	       	   $qry_data = "SELECT *FROM pricelist LIMIT 1";

	       	   $qry_result = mysql_query($qry_data);

	       	   $qry_val = mysql_fetch_array($qry_result,MYSQL_ASSOC);

	       	   

			   foreach ($users as $user) {

			      list($col1, $col2, $col3, $col4, $col5, $col6, $col7, $col8, $col9, $col10, $col11, $col12, $col13, $col14, $col15, $col16,
				  	   $col17, $col18, $col19, $col20, $col21, $col22, $col23, $col24, $col25, $col26, $col27, $col28, $col29, $col30, $col31, $col32,
					   $col33, $col34, $col35, $col36, $col37, $col38, $col39, $col40, $col41, $col42, $col43, $col44, $col45, $col46, $col47, $col48, $col49, $col50,
					   $col51, $col52, $col53, $col54, $col55, $col56, $col57, $col58, $col59, $col60, 
					   $col61, $col62, $col63, $col64, $col65, $col66, $col67, $col68, $col69, $col70,
					   $col71, $col72, $col73, $col74, $col75, $col76, $col77, $col78, $col79, $col80, 
					   $col81, $col82, $col83, $col84, $col85, $col86, $col87, $col88, $col89, $col90,
					   $col91, $col92, $col93, $col94, $col95, $col96, $col97, $col98, $col99, $col100, 
					   $col101, $col102, $col103, $col104, $col105, $col106, $col107, $col108, $col109, $col110,
					   $col111, $col112, $col113, $col114, $col115, $col116, $col117, $col118, $col119, $col120, 
					   $col121, $col122, $col123, $col124, $col125, $col126, $col127, $col128, $col129, $col130,
					   $col131, $col132, $col133, $col134, $col135, $col136, $col137, $col138, $col139, $col140, 
					   $col141, $col142, $col143, $col144, $col145, $col146, $col147, $col148, $col149, $col150,
					   $col151, $col152, $col153, $col154) = explode(",", $user);

			      if($col1 == 'Letter'){

			      	 $flag = true;

			      }

			      

			      if(($flag == true) && ($col1 != '') && ($col2 != '')){

			      	  $col_count = 3;

				      for($i=1; $i<39; $i++){

					       $cost_bus = 'col'.$col_count;
						   $col_count++;
						   $cost_prem = 'col'.$col_count;
						   $col_count++;
						   $cost_std = 'col'.$col_count;
						   $col_count++;
						   //echo $$cost_std.'&nbsp'.$$cost_prem.'&nbsp'.$$cost_bus.'<br/>';
						   
					//update DHL cost
						if(($_POST['expType'] == 1) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET DHL_cost_std=".str_replace("$","",$$cost_std).", DHL_cost_prem=".str_replace("$","",$$cost_prem).", DHL_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col2."' AND weight_lbs='".$col1."';";
							}  
						else if(($_POST['expType'] == 1) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,DHL_cost_std,DHL_cost_prem,DHL_cost_bus) VALUES('".$i."','".$col2."','".$col1."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
							
					//update TNT cost
						else if(($_POST['expType'] == 2) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET TNT_cost_std=".str_replace("$","",$$cost_std).", TNT_cost_prem=".str_replace("$","",$$cost_prem).", TNT_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col2."' AND weight_lbs='".$col1."';";
							}  
						else if(($_POST['expType'] == 2) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,TNT_cost_std,TNT_cost_prem,TNT_cost_bus) VALUES('".$i."','".$col2."','".$col1."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
							
					//update Fedex cost
						else if(($_POST['expType'] == 3) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET Fedex_cost_std=".str_replace("$","",$$cost_std).", Fedex_cost_prem=".str_replace("$","",$$cost_prem).", Fedex_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col2."' AND weight_lbs='".$col1."';";
							}  
						else if(($_POST['expType'] == 3) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,Fedex_cost_std,Fedex_cost_prem,Fedex_cost_bus) VALUES('".$i."','".$col2."','".$col1."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
							
					//update Fedex Eco cost
						else if(($_POST['expType'] == 4) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET Fedex_Eco_cost_std=".str_replace("$","",$$cost_std).", Fedex_Eco_cost_prem=".str_replace("$","",$$cost_prem).", Fedex_Eco_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col2."' AND weight_lbs='".$col1."';";
							}  
						else if(($_POST['expType'] == 4) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,Fedex_Eco_cost_std,Fedex_Eco_cost_prem,Fedex_Eco_cost_bus) VALUES('".$i."','".$col2."','".$col1."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
					

					       //echo $query."<br/>";
					       $result = mysql_query($query);
					       $col_count++;
				      }//exit inner for
			      }
				  
				  else if(($flag == true) && ($col1 == '+ 1 lbs')){
					  
			      	  $col_count = 3;

				      for($i=1; $i<39; $i++){

					       $cost_bus = 'col'.$col_count;
						   $col_count++;
						   $cost_prem = 'col'.$col_count;
						   $col_count++;
						   $cost_std = 'col'.$col_count;
						   $col_count++;
						   //echo $$cost_std.'&nbsp'.$$cost_prem.'&nbsp'.$$cost_bus.'<br/>';
						   
					//update DHL cost
						if(($_POST['expType'] == 1) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET DHL_cost_std=".str_replace("$","",$$cost_std).", DHL_cost_prem=".str_replace("$","",$$cost_prem).", DHL_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col1."' AND weight_lbs='".$col1."';";
							}  
						else if(($_POST['expType'] == 1) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,DHL_cost_std,DHL_cost_prem,DHL_cost_bus) VALUES('".$i."','".$col1."','".$col1."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
							
					//update TNT cost
						else if(($_POST['expType'] == 2) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET TNT_cost_std=".str_replace("$","",$$cost_std).", TNT_cost_prem=".str_replace("$","",$$cost_prem).", TNT_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col1."' AND weight_lbs='".$col1."';";
							}  
						else if(($_POST['expType'] == 2) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,TNT_cost_std,TNT_cost_prem,TNT_cost_bus) VALUES('".$i."','".$col1."','".$col1."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
							
					//update Fedex cost
						else if(($_POST['expType'] == 3) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET Fedex_cost_std=".str_replace("$","",$$cost_std).", Fedex_cost_prem=".str_replace("$","",$$cost_prem).", Fedex_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col1."' AND weight_lbs='".$col1."';";
							}  
						else if(($_POST['expType'] == 3) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,Fedex_cost_std,Fedex_cost_prem,Fedex_cost_bus) VALUES('".$i."','".$col1."','".$col1."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
							
					//update Fedex Eco cost
						else if(($_POST['expType'] == 4) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET Fedex_Eco_cost_std=".str_replace("$","",$$cost_std).", Fedex_Eco_cost_prem=".str_replace("$","",$$cost_prem).", Fedex_Eco_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col1."' AND weight_lbs='".$col1."';";
							}  
						else if(($_POST['expType'] == 4) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,Fedex_Eco_cost_std,Fedex_Eco_cost_prem,Fedex_Eco_cost_bus) VALUES('".$i."','".$col1."','".$col1."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
					

					       //echo $query."<br/>";
					       $result = mysql_query($query);
					       $col_count++;
				      }//exit inner for
				  }
				  
				  else if(($flag == true) && ($col2 == '+ 0.5 Kg')){
					  
			      	  $col_count = 3;

				      for($i=1; $i<39; $i++){

					       $cost_bus = 'col'.$col_count;
						   $col_count++;
						   $cost_prem = 'col'.$col_count;
						   $col_count++;
						   $cost_std = 'col'.$col_count;
						   $col_count++;
						   //echo $$cost_std.'&nbsp'.$$cost_prem.'&nbsp'.$$cost_bus.'<br/>';
						   
					//update DHL cost
						if(($_POST['expType'] == 1) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET DHL_cost_std=".str_replace("$","",$$cost_std).", DHL_cost_prem=".str_replace("$","",$$cost_prem).", DHL_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col2."' AND weight_lbs='".$col2."';";
							}  
						else if(($_POST['expType'] == 1) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,DHL_cost_std,DHL_cost_prem,DHL_cost_bus) VALUES('".$i."','".$col2."','".$col2."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
							
					//update TNT cost
						else if(($_POST['expType'] == 2) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET TNT_cost_std=".str_replace("$","",$$cost_std).", TNT_cost_prem=".str_replace("$","",$$cost_prem).", TNT_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col2."' AND weight_lbs='".$col2."';";
							}  
						else if(($_POST['expType'] == 2) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,TNT_cost_std,TNT_cost_prem,TNT_cost_bus) VALUES('".$i."','".$col2."','".$col2."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
							
					//update Fedex cost
						else if(($_POST['expType'] == 3) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET Fedex_cost_std=".str_replace("$","",$$cost_std).", Fedex_cost_prem=".str_replace("$","",$$cost_prem).", Fedex_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col2."' AND weight_lbs='".$col2."';";
							}  
						else if(($_POST['expType'] == 3) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,Fedex_cost_std,Fedex_cost_prem,Fedex_cost_bus) VALUES('".$i."','".$col2."','".$col2."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
							
					//update Fedex Eco cost
						else if(($_POST['expType'] == 4) && ($qry_val['DHL_cost_std'] != '')){
							//update
					       	 $query = "UPDATE pricelist SET Fedex_Eco_cost_std=".str_replace("$","",$$cost_std).", Fedex_Eco_cost_prem=".str_replace("$","",$$cost_prem).", Fedex_Eco_cost_bus=".str_replace("$","",$$cost_bus)." WHERE continents_id='".$i."' AND weight_kg='".$col2."' AND weight_lbs='".$col2."';";
							}  
						else if(($_POST['expType'] == 4) && ($qry_val['DHL_cost_std'] == '')){
							//insert
							$query = "INSERT INTO pricelist(continents_id,weight_kg,
					       			weight_lbs,Fedex_Eco_cost_std,Fedex_Eco_cost_prem,Fedex_Eco_cost_bus) VALUES('".$i."','".$col2."','".$col2."',".str_replace("$","",$$cost_std).",".str_replace("$","",$$cost_prem).",".str_replace("$","",$$cost_bus).");";
							}
					
					

					       //echo $query."<br/>";
					       $result = mysql_query($query);
					       $col_count++;
				      }//exit inner for
				  }

			   }//end foreach*/
			   

			   if($result){

			   	  $error = "";

				  $success = "<h3>Pricelist imported successfully.</h3>";

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
			if(@$error != ''){
				echo @$error;
			}
			if(@$success != ''){
				echo @$success;
			}
			?>
			</td>
        </tr>

		<tr>
			<td class="main" style="font-size:14px; color:#003366;" colspan="2">
				<b>Manage Pricelist</b><hr>
			</td>
		</tr>

		<tr>
			<td colspan="2" id="adminGlobalMsg" align="center"></td>
		</tr>

		 <tr>
			<td width="20%" align="left" valign="top">Select Express Type: </td>
			<td width="75%" align="left">
				<select id="expType" name="expType">
					<option value="1">DHL</option>
					<option value="2">TNT</option>
					<option value="3">Fedex</option>
					<option value="4">Fedex Eco</option>
				</select>
			</td>
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