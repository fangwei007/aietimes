
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

function reloadPrice(whichradion,price)
{
	var issue=whichradion.getAttribute("id");
	var showprice=document.getElementById("cover_price");
	showprice.innerText=price;
}
function showDivmenu(choice)
{
	var menu=choice.getAttribute("id");
	var showdivmenu=document.getElementById("text");
	if(menu=="product_details"){
	showdivmenu.innerText="time";
	}
	if(menu=="customer_reviews"){
	showdivmenu.innerText="reviews";	
	}
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
	$cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
	echo "<div style='position:absolute;right:60px;top:0px;width:300px;'>";
	if(isset($_SESSION['first_name'])) echo "Hi! ".$_SESSION['first_name']." ";
	if(isset($_SESSION['count']))
	$count=$_SESSION['count'];
	else {
		$_SESSION['count']=0;
		$count=$_SESSION['count'];
	}
	
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
<div id="content2" style="height:1000px">
<!-- Beginning of coding area-->
<script language="javascript">

</script>
<?php
    include("DatabaseClass.php");
    $db->connect();
	global $mag_id;
	if(isset($_GET['newpath']))
    $mag_id=$_GET['newpath'];
   // $sql = "select * from magazineinfo where mag_id = '".$_GET['newpath']."'";
    $sql = "select * from magazineinfo where mag_id = '".$mag_id."'";
    $res = mysql_query($sql);
	$result = mysql_fetch_row($res);
		
	$three_months = $result[7]*3;
	$six_months = $result[7]*6;
	$nine_months = $result[7]*9;
	$twelve_months = $result[7]*12;

   
    echo "<form name=\"send\" method=\"post\" action=\"subscribe.php?MID=".$result[0]."&NUM=".$result[7]."\">";
	echo "<table width='600px' style='margin:30px'>";
    echo "<tr>";
    echo "<td rowspan = 12><img src='".$result[5]."' width=\"230\" height=\"300\" /></td>";
	echo "<td rowspan = 12>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
    echo "<td><b>Name:</b></td>";
	echo "<td>".$result[1]."</td>"; 
    echo "</tr>";	
	echo "<tr>";
    echo "<td><b>Availability:</b></td>";
	echo "<td>".$result[8]."</td>";
    echo "</tr>";
    echo "<tr>";
	echo "<td><b>Cover Price:</b></td>";
    echo "<td id=\"cover_price\">".$result[3]."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan=\"2\"><b>Choose Issues  (Required fields)</td>";
    echo "</tr>";
	echo "<tr>";
    echo "<td colspan=\"2\"><input id=\"1_issue\" type=\"radio\" value=\"1 issue\" name=\"period\" onclick=\"reloadPrice(this,".$result[3].")\" checked=\"checked\";><span><label for=\"options_6_2\">1 issue </label></span></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan=\"2\"><input id=\"3_months\" type=\"radio\" value=\"3 months\" name=\"period\" onclick=\"reloadPrice(this,".$result[9].")\";><span><label for=\"options_6_2\">3 months (".$three_months." issues)</label></span></td>";
    echo "</tr>";
	echo "<tr>";
    echo "<td colspan=\"2\"><input id=\"6_months\" type=\"radio\" value=\"6 months\" name=\"period\" onclick=\"reloadPrice(this,".$result[10].")\";><span><label for=\"options_6_2\">6 months (".$six_months." issues)</label></span></td>";
    echo "</tr>";
	echo "<tr>";
    echo "<td colspan=\"2\"><input id=\"9_months\" type=\"radio\" value=\"9 months\" name=\"period\" onclick=\"reloadPrice(this,".$result[11].")\";><span><label for=\"options_6_2\">9 months (".$nine_months." issues)</label></span></td>";
    echo "</tr>";
	echo "<tr>";
    echo "<td colspan=\"2\"><input id=\"12_months\" type=\"radio\" value=\"12 months\" name=\"period\" onclick=\"reloadPrice(this,".$result[12].")\";><span><label for=\"options_6_2\">12 months (".$twelve_months." issues)</label></span></td>";
    echo "</tr>";
	if ($result[8]=="in stock"){
	echo "<tr>";
    echo "<td colspan=\"2\"><b>Qty:&nbsp;&nbsp;&nbsp;&nbsp;<input id=\"qty\" type=\"text\" value=\"1\" maxlength=\"12\" name=\"qty\" style=\"width:25px;\"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><button class=\"button\" type=\"submit\" name=\"submit\" value=\"Submit\" style= \"width:100px; length:25px;\">Add to Cart</button></td>";
	echo "</tr>";}
	else {
	echo "<tr>";
	echo "<td colspan=\"2\"><b>Temporarily out of stock.</b></td>";
	echo "</tr>";}
    echo "</table>";
	echo "</form>";
	
	if($result[7]==1){
	$frequency= "Monthly";}
	else if($result[7]==4){
	$frequency= "Weekly";}
	else if($result[7]==30){
	$frequency= "Daily";}
    echo "<tr width=\"711\"><td><h1><font size=\"2\">Product Details:</font></h1></td>";
    echo "<div bordercolor=\"#CCCC33\" align=\"left\" border=\"1\" width=\"725\" bgcolor=\"#FFFFFF\" >";
	echo "<tr>";
	echo "<td><b>Name: </b></td>";
	echo "<td>".$result[1]."</td>"; 
    echo "</tr><br>";	
	echo "<tr>";
    echo "<td><b>Description: </b></td>";
	echo "<td>".$result[6]."</td>";
	echo "</tr><br>";
	echo "<tr>";
    echo "<td><b>Publishing Frequency: </b></td>";
	echo "<td>".$frequency."</td>";
	echo "</tr><br>";
	echo "<tr>";
    echo "<td><b>Issue Per Year: </b></td>";
	echo "<td>".$twelve_months."</td>";
	echo "</tr><br>"; 
	
	$sql = "select rate,review from mag_review where mag_id = '".$mag_id."'";
	$sql2 = "select avg(rate) from mag_review where mag_id = '".$mag_id."'";
    $res = mysql_query($sql);
	$res2 = mysql_query($sql2);
	$result2 = mysql_fetch_row($res2);
	$result3 = round($result2[0]);
	echo "<tr width=\"711\"><td><h1><font size=\"2\">Comments:</font></h1></td>";
	
	
	echo "<td align=\"right\"><font size=\"2\"><h3>Average Rate: ".$result3."</h3></font></td></tr>";
    echo "<div bordercolor=\"#CCCC33\" align=\"left\" border=\"1\" width=\"725\" bgcolor=\"#FFFFFF\" >";
	$i=1;
		while($result = mysql_fetch_row($res))
		{
			
		echo "<div>";
		echo "<td height=\"40\" width=\"200\"><b>Customer ".$i.":</b>  &nbsp;&nbsp;</td>";
    	echo "<td height=\"40\">Rate- ".$result[0]." &nbsp;&nbsp;&nbsp;&nbsp;</td>";
		echo "<td height=\"40\">Comment-".$result[1]." </td>";
    	echo "</div>";
		$i++;
		}
        echo "</table>";
	      
		
		$db->close();
		echo "<h1><font size=\"2\">Write a review:</font></h1>";
        echo "<div bordercolor=\"#CCCC33\" align=\"left\" border=\"1\" width=\"725\" bgcolor=\"#FFFFFF\" >";
?>
<!--<ul id="menu">
<li><button id= "product_details"  style= \"width:100px; length:25px;\" onclick="showDivmenu(this)">Product Details</button></li>
<li><button id= "customer_reviews"  style= \"width:100px; length:25px;\" onclick="showDivmenu(this)">Customer Reviews</button></li>
</ul>-->

<form name="send" method="post" action="Display.php?newpath=<?php echo $mag_id;?>">
<div  align="left">
      <font size="2"><b>Rate:</b></font>
      <select name="rate">
         <option>0</option>
 		 <option>1</option>
 		 <option>2</option>
         <option>3</option>
 		 <option>4</option>
         <option>5</option>
		</select>
      <br>
      <font size="2"><h3>Comment:</h3></font>
      <textarea name="review" cols="18" rows="10" class="input" style="max-width:400px;max-height:200px;min-width:400px;min-height:200px;"></textarea>
          <!--<input name="review" type="text" class="input" id="review" value="" size="100%">-->
      <button class="button"type="submit" name="submit" value="Submit" >Submit</button>

</div>
</form>

<?php
   // $db->connect();
   if(isset($_POST['review']))
        //echo $mag_id;
		$rate = @$_POST['rate'];
 		$review = @$_POST['review'];
		if(empty($review)){
			echo "<script>";
			echo "window.alert('No review, you can't submit!');";
			//echo ".location.href=\"Disply.php\";";
			echo "</script>";
		}
		//if(isset($review))
		//{
			//exit;
		//}
		else 
		{
			$pattern_name="/^[a-zA-Z0-9][\.a-zA-Z\s,0-9]*?[a-zA-Z]+$/ ";
			if(!preg_match($pattern_name, $review))
			{
				echo "<script>";
				echo "alert('Illegal review!');";
				echo "window.location.href=\"Display.php?newpath=$mag_id\";";
				echo "</script>";
			}
			else{
	    $db->connect();
			$sql = "INSERT INTO mag_review(mag_id, rate, review) VALUES ('$mag_id','$rate','$review')";
			$result = mysql_query($sql);  $db->close();
				echo "<script>";
				echo "window.location.href=\"Display.php?newpath=$mag_id\";";
				echo "</script>";}
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