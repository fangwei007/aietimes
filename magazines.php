<!DOCTYPE html>
<html>
<head>
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
div.search
{
  margin:2px;
  height:700px;
  width:auto;
  float:left;
  margin-right:30px;
}

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
      <a href="Myorder.php"><div id="menulink">Order</div></a>
      <a href="shipping.php"><div id="menulink">Shipping</div></a>
</div>
<div id="banner">
<span style="color:#09F">A</span><span style="color:#F60">I</span><span style="color:#09F">E</span><span style="font-size:30px">TIMEs</span>
<span style="font-size:10px; color: #666; font-style:normal;">Subscribe Magazines from USA. No matter where you are in the world!</span>
<span style="margin-left:500px; ; font-size:14px;"> 
<?php
	$cookies_life_time = 300;//unit is s
	session_start();
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
<div id="content2" style="height:800px">
<!-- Beginning of coding area-->
<div class="search">
<form method="post"  
action="<?php echo $_SERVER['PHP_SELF'];//The filename of the currently executing script, relative to the document root.
?>" enctype="multipart/form-data"><!--To upload file, enctype="multipart/form-data" is needed!!-->
    <label for="mag_name">Magazine Name</label><br />
		<input type="text" name="mag_name"/><br>
		
    <label for="category">Category</label><br />
    <select name="category">
    	<option value="*">Any category</option>
 			<option value="Business&Finance">Business & Finance</option>
 			<option value="Fashion">Fashion</option>
 			<option value="Entertainment">Entertainment</option>
  		<option value="Health&Fitness">Health & Fitness</option>
			<option value="Men">Men's</option>
			<option value="Women">Women's</option>
 			<option value="Sports">Sports</option>
		</select><br>
		
    <label for="issue_period">Issue Period</label><br />
    <select name="issue_period">
    	<option value="*">Any issue</option>
    	<option value="Weekly">Weekly</option>
 			<option value="Monthly">Monthly</option>
    	<option value="Seasonly">Seasonly</option>
		</select><br>
		
    <label for="price_per_year">price/year</label><br />
    <select name="price">
			<option value="*">Any Price</option>  
    	<option value="under10">Under $10</option>
    	<option value="10to20">$10 - $20</option>
    	<option value="20to30">$20 - $30</option>
    	<option value="30to50">$30 - $50</option>
    	<option value="over50">Over $50</option>
    </select><br>
    <input type="submit" value="SEARCH" name= "submit">
</form>
</div>
<?php
	include("DatabaseClass.php");
	$link = $db->connect();
  
   if(isset($_POST['submit']))
   {
   	$name = strip_tags($_POST["mag_name"]);
     $name = str_replace("'", "\'", $name);
	 str_replace("<", "", $name);
     $category = $_POST["category"];
     $issue_period = $_POST["issue_period"];
     $price = $_POST["price"];
     //echo "You are searching <FONT color='orange'>$name</FONT> <FONT color='orange'>$category</FONT> <FONT color='orange'>$issue_period</FONT> <FONT color='orange'>$price</FONT><br/><br/>";
     mysearch($name,$category,$issue_period,$price);
   }
   
   else{
   	$sql = "select * from magazineinfo";
	$res = mysql_query($sql);  
    while($result = mysql_fetch_row($res)){
		  echo "<div class='img'>";
  		  echo "<a href = 'Display.php?newpath=".$result[0]."'><img src='".$result[5]."' width=\"120\" height=\"160\" /></a><br>";
        echo "<div style='background-color:#123; width:140px; font-size:12px;'><a href = 'Display.php?newpath=".$result[0]."'>".$result[1]."</a></div><br>";
          echo "Price: $".$result[3]."<br>Issues: ".$result[7]."/month<br>"; 	
		  echo "</div>";	  
  	  }
   }
 
 function mysearch($name,$category,$issue_period,$price)
  {
  	$flag=0;
  	$sql="SELECT * FROM magazineinfo";
  	if($name!="")
  	{
  		$sql.=" WHERE mag_name like '%".$name."%'";
  		$flag=1;
  	}
  	
  	if(strcmp($category,"*"))
  	{
  		if($flag==1)
  			$sql.=" AND";
  		else if($flag==0)
  		{
  			$sql.=" WHERE";
  			$flag=1;
  		}
  		if(!strcmp($category,"Business&Finance"))
  			$sql.=" category='Business & Finance'";
  		else if(!strcmp($category,"Fashion"))
  			$sql.=" category='Fashion'";
  		else if(!strcmp($category,"Entertainment"))
  			$sql.=" category='Entertainment'";
  		else if(!strcmp($category,"Health&Fitness"))
  			$sql.=" category='Health & Fitness'";
  		elseif(!strcmp($category,"Men"))
  			$sql.=" category='Men\'s'";
  		elseif(!strcmp($category,"Women"))
  			$sql.=" category='Women\'s'";
  		elseif(!strcmp($category,"Sports"))
  			$sql.=" category='Sports'";
  	}
  	
  	if(strcmp($issue_period,"*"))
  	{
  		if($flag==1)
  			$sql.=" AND";
  		else if($flag==0)
  		{
  			$sql.=" WHERE";
  			$flag=1;
  		}
  		if(!strcmp($issue_period,"Weekly"))
  			$sql.=" issue_period='Weekly'";
  		else if(!strcmp($issue_period,"TwoWeeks"))
  			$sql.=" issue_period='Two Weeks'";
  		else if(!strcmp($issue_period,"Monthly"))
  			$sql.=" issue_period='Monthly'";
  		elseif(!strcmp($issue_period,"TwoMonths"))
  			$sql.=" issue_period='Two Months'";
  		elseif(!strcmp($issue_period,"Seasonly"))
  			$sql.=" issue_period='Seasonly'";
  	}
  	
  	if(strcmp($price,"*"))
  	{
  		if($flag==1)
  			$sql.=" AND";
  		else if($flag==0)
  		{
  			$sql.=" WHERE";
  			$flag=1;
  		}
  		if(!strcmp($price,"under10"))
  			$sql.=" price_per_year<10";
  		else if(!strcmp($price,"10to20"))
  			$sql.=" price_per_year>=10 AND price_per_year<20";
  		else if(!strcmp($price,"20to30"))
  			$sql.=" price_per_year>=20 AND price_per_year<30";
  		elseif(!strcmp($price,"30to50"))
  			$sql.=" price_per_year>=30 AND price_per_year<50";
  		elseif(!strcmp($price,"over50"))
  			$sql.=" price_per_year>=50";
  	}
  	
  	$res=mysql_query($sql);
  	if(mysql_affected_rows()!=0)
  	{
  		while($result = @mysql_fetch_row($res))
  		{
		  echo "<div class='img'>";
  		  echo "<a href = 'Display.php?newpath=".$result[0]."'><img src='".$result[5]."' width=\"120\" height=\"160\" /></a><br>";
        echo "<div style='background-color:#123; width:140px; font-size:12px;'><a href = 'Display.php?newpath=".$result[0]."'>".$result[1]."</a></div><br>";
          echo "Price: $".$result[3]."<br>Issues: ".$result[7]."/year<br>"; 	
		  echo "</div>";	  
  	  }				  		
  	}
  	else
  	{
  		echo "No match result!";
  	}
  } 
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
<p style="margin-left:550px;color:#FFF; font-size:12px">&copy; 2010-2012 by Amerika International Express.<br>
All rights reserved. AIETimes.com is a trademark of Amerika International Express.</p>
</div>
</body>
</html>