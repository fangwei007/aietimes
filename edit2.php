<?php
	session_start();
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


<!--<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/edittable.js"></script>-->


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
	//session_start();
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
<div id="content2" style="height:450px;">
<!-- Beginning of coding area-->
<h1>Credit Infomation Update</h1>
<form name="send" method="post" action="editer2.php" onsubmit="return Check()">
<table width="1000" border="0"cellpadding="5">       
   <tr>
      <td width="71"><font size="2">Card type:</font></td>
      <td width="58"><select name="cardtype" size="1">
 		 <option>visa</option>
 		 <option>matercard</option>
		</select></td><td width="6"><font color="FF6699">*</font></td>
      <td width="77"><font size="2">Card number:</font></td>
      <td width="259"><input name="cardnumber" type="text" id="cardnumber" size="35"/></td><td width="455"><font color="FF6699">*</font></td>
   </tr>
</table>
<table width="1000" border="0"cellpadding="5">     
      <td width="67"><font size="2"> Exp date:</font></td>
      <td width="137"> 
           <select name="expmonth" size=1>
           <script>
                   var expmonthbegin=1,expmonthend=12;
                   document.write("<option value=''selected>month</option>")
                   for(var i=expmonthbegin;i<=expmonthend;i++){
                   document.write ("<option value="+i+">"+i+"</option>")
                   }
           </script> 
           </select>
           <select name="expyear" size=1>
           <script>
		           var expyearbegin=2000,expyearend=2025;
                   document.write("<option value=''selected>year</option>")
                   for(var i=expyearbegin;i<=expyearend;i++){
                   document.write ("<option value="+i+">"+i+"</option>")
                   }
           </script>
           </select>
       </td><td width="6"><font color="FF6699">*</font></td>
           <td width="70"><font size="2">Card CVC:</font></td>
           <td width="49"><input name="cardcvc" type="text" id="cardcvc" size="5"/></td><td width="49"><img src="images/cvc.jpg" width="49" height="37" /></td><td width="6"><font color="FF6699">*</font></td><td width="518"><script type="text/javascript">function open_win(){window.open("images/cvc.jpg")}</script><input type="button" value="What is CVC?" onclick="open_win()"  height="1"/></td>
</table>
<table width="1000" border="0"cellpadding="5">  
  <tr>
      <td width="64"><font size="2">Billing Address 1:</font></td>
      <td width="207"><input name="address1" type="text" id="address1" size="40"/></td><td width="691"><font color="FF6699">*</font></td>
  </tr>
  <tr>    
      <td width="64"><font size="2">Billing Address 2:</font></td>
      <td width="207"><input name="address2" type="text" id="address2" size="40"/></td>
      
  </tr>
</table>
<table width="1000" border="0"cellpadding="5">    
    <tr>    
      <td width="61"><font size="2">City:</font></td>
      <td width="154"><input name="city" type="text" id="city" size="20"/></td><td width="6"><font color="FF6699">*</font></td>
      <td width="49"><font size="2">State:</font></td>
      <td width="154"><input name="state" type="text" id="state" size="20"/></td><td width="502"><font color="FF6699">*</font></td>
    </tr>
    <tr>
      <td width="61"><font size="2">Country:</font></td>
      <td width="154"><input name="country" type="text" id="country" size="20"/></td><td width="6"><font color="FF6699">*</font></td>
      <td width="49"><font size="2">Zipcode:</font></td>
      <td width="154"><input name="zipcode" type="text" id="zipcode" size="20"/></td><td width="502"><font color="FF6699">*</font></td>
   </tr> 
</table>
<table width="1000" border="0"cellpadding="5">       
  <tr>
      <td width="142" ><font color="#FF6699" size="-3">Notice: * must be provided.</font></td>
  </tr>    
  <tr>
      <td  align="left" ><button class="button"type="submit" name="submit" value="Submit" >Submit</button></td>
      <td width="832"><a href="account.php"><button  class="button" type="reset">Cancel</button></a></td>
  </tr>
 
</table>
</form>
<script language="javascript">
function Check()
{
	if (document.send.cardnumber.value == ""){
		window.alert('Please input a card!');
		return false;
	}
	if (document.send.expmonth.value == ""){
		window.alert('Exp date needed!');
		return false;
	}
	if (document.send.expyear.value == ""){
		window.alert('Exp date needed!');
		return false;
	}
	if (document.send.cardcvc.value == ""){
		window.alert('Please input the cvc number!');
		return false;
	if (document.send.address1.value == ""){
		window.alert('Please input a address!');
		return false;
	}
	if (document.send.city.value == ""){
		window.alert('Please input a city!');
		return false;
	}
	if (document.send.state.value == ""){
		window.alert('Please input a state!');
		return false;
	}
	if (document.send.country.value == ""){
		window.alert('Please input a country!');
		return false;
	}
	if (document.send.zipcode.value == ""){
		window.alert('Please input a zipcode!');
		return false;
	}

	}
	return true;
}
</script>
<br>
<br><br>
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