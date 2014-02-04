<?php
	session_start();
	if(!isset($_SESSION['user_id']))
	//header("location: index.php");//For security, header the user back to login page if that user is not properly logged
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
<div id="content2" style="height:500px;">
<!-- Beginning of coding area-->


<div  style="margin:10px 0 0; border: 3px solid #F4F4F4;; overflow: hidden; width: 100%;">

<h1>Estimate Shipping Cost</h1>
<div  align="center" style="overflow-y:scroll;width:100%;height:400px">
	<p>Please select your country for shipping cost.</p>
    <table>
    
    <form name="send" method="post" action="checkout.php">
    
    <select name="ctr">
    
    <?php
		include("DatabaseClass.php");
    	$db->connect();
		$sql="SELECT `ID`, `Name` FROM `countries`";
		$result=mysql_query($sql);
		while($row=mysql_fetch_assoc($result))
		{
			echo "<option value=".$row['ID'].">".$row['Name']."</option>";
		}
    ?>
    
    </select>
    
    <input type="radio" id="show_weight" name="show_weight" value="lbs" checked="checked" /> LB

	<input type="radio" id="show_weight" name="show_weight" value="kg" /> KG<br /><br />
                                    
    <input type="radio" id="show_express" name="show_express" value="Chep" checked="checked" /> Chep
                                    
    <input type="radio" id="show_express" name="show_express" value="DHL" /> DHL
                                    
    <input type="radio" id="show_express" name="show_express" value="TNT" /> TNT
                                    
    <input type="radio" id="show_express" name="show_express" value="Fedex" /> Fedex
                                    
    <input type="radio" id="show_express" name="show_express" value="Fedex Eco" /> Fedex Eco
    
    <input style='height:30px; width:80px;background-color:#09F; color:#FFF' type="submit" value="Submit" />
    
    <button class='button' style='height:30px; width:150px;background-color:#09F;'><a style='color:#FFF;text-decoration: none;' href='shoppingcart.php'>Back to ShoppingCart</a></button>
    </form>
    
    </table>
    
    <?php
		if(@$_POST['show_weight'] == 'lbs' && @$_POST['show_express'] == 'Chep'){

			$tOutput = '';

			$i = 1;
			
			//output country and courier
			$query = "SELECT Name FROM `countries` WHERE ID = '".$_POST['ctr']."'";
			
			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){
				
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				
				$tOutput = '<h3>Country:'.$row['Name'].'&nbsp &nbsp Courier:Cheapest</h3>';
			}
			
			//test if price exist
			$query = "SELECT * FROM `pricelist` p INNER JOIN countries c ON c.DHL_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."'";

			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){

				$tOutput .= '<table id="tabReg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$tOutput .= '<tr class="trhead">

								<td class="tdhead">Weight</td>

								<td class="tdhead">Standard</td>

								<td class="tdhead">Premium</td>

								<td class="tdhead">Business Class</td>

							</tr>';
			
			//get weight
			$query_weight= "SELECT distinct weight_lbs FROM `pricelist`";
			
			$result_weight = mysql_query($query_weight);

			$num_row_weight = mysql_num_rows($result_weight);
			
			if($num_row_weight > 0){
				
				while($row_weight = mysql_fetch_array($result_weight,MYSQL_ASSOC)){
					
					if(($i == 2) || ($i == 11) || ($i == 21)){

						$tOutput .= '<tr class="trothers" style="background-color:#b8cce4">';

					//}else if(($i == 22) || ($i == 23) || ($i == 32)){

					}else if(($i == 30)){

						$tOutput .= '<tr class="trothers" style="background-color:#376091;">';

					}else{

						$tOutput .= '<tr class="trothers">';	

					}
					
					//output cheapeat std price
					
					$cheapest = "";
					
					$dhl_price = "";
					
					$tnt_price = "";
					
					$fedex_price = "";
					
					$fedex_eco_price = "";
					
					$courier = "";
					
					$query = "SELECT DHL_cost_std as dhl FROM `pricelist` p INNER JOIN countries c ON c.DHL_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$dhl_price = $row['dhl'];
						
					}
					
					$query = "SELECT TNT_cost_std as tnt FROM `pricelist` p INNER JOIN countries c ON c.TNT_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$tnt_price = $row['tnt'];
						
					}
					
					$query = "SELECT Fedex_cost_std as fedex FROM `pricelist` p INNER JOIN countries c ON c.Fedex_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_price = $row['fedex'];
						
					}
					
					$query = "SELECT Fedex_Eco_cost_std as fedex_eco FROM `pricelist` p INNER JOIN countries c ON c.Fedex_Eco_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_eco_price = $row['fedex_eco'];
						
					}
					
					//get the cheapeast price
					if($dhl_price != "")
					{
						$cheapest = $dhl_price;	
						$courier = "dhl";
					}
					else if($tnt_price != "")
					{
						$cheapest = $tnt_price;
						$courier = "tnt";
					}
					else if($fedex_price != "")
					{
						$cheapest = $fedex_price;
						$courier = "fedex";
					}
					else if($fedex_eco_price != "")
					{
						$cheapest = $fedex_eco_price;
						$courier = "fedex_eco";
					}
						
					if($cheapest != "")
					{
						if($tnt_price != "" && $tnt_price < $cheapest)
						{
							$cheapest = $tnt_price;
							$courier = "tnt";
						}
						if($fedex_price != "" && $fedex_price < $cheapest)
						{
							$cheapest = $fedex_price;
							$courier = "fedex";
						}
						if($fedex_eco_price != "" && $fedex_eco_price < $cheapest)
						{
							$cheapest = $fedex_eco_price;
							$courier = "fedex_eco";
						}
						
						//output
						if(($i == 30)){

							$tOutput .= '<td style="color:#ffffff;">'.$row_weight['weight_lbs'].'</td>';
						
							if($courier == "dhl")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs DHL Express 2-3 days"/></td>';
								}
								
							else if($courier == "tnt")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs TNT Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs Fedex Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex_eco")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs Fedex Eco Express 3-5 days"/></td>';
								}
								
							else
								$tOutput .= '<td>Not Available</td>';

								 //$tOutput .= '</tr>';

					}else if($row_weight['weight_lbs'] != "+ 0.5 Kg"){

						$wt = ($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs');

						$tOutput .= '<td style="color:#000000;">'.$wt.'</td>';
						
						if($courier == "dhl")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' DHL Express 2-3 days"/></td>';
								}
								
						else if($courier == "tnt")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' TNT Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' Fedex Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex_eco")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' Fedex Eco Express 3-5 days"/></td>';
								}
								
						else
							$tOutput .= '<td>Not Available</td>';

						//$tOutput .= '</tr>';

					}
					}
					
					//output cheapeat prem price
					
					$cheapest = "";
					
					$dhl_price = "";
					
					$tnt_price = "";
					
					$fedex_price = "";
					
					$fedex_eco_price = "";
					
					$courier = "";
					
					$query = "SELECT DHL_cost_prem as dhl FROM `pricelist` p INNER JOIN countries c ON c.DHL_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$dhl_price = $row['dhl'];
						
					}
					
					$query = "SELECT TNT_cost_prem as tnt FROM `pricelist` p INNER JOIN countries c ON c.TNT_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$tnt_price = $row['tnt'];
						
					}
					
					$query = "SELECT Fedex_cost_prem as fedex FROM `pricelist` p INNER JOIN countries c ON c.Fedex_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_price = $row['fedex'];
						
					}
					
					$query = "SELECT Fedex_Eco_cost_prem as fedex_eco FROM `pricelist` p INNER JOIN countries c ON c.Fedex_Eco_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_eco_price = $row['fedex_eco'];
						
					}
					
					//get the cheapeast price
					if($dhl_price != "")
					{
						$cheapest = $dhl_price;	
						$courier = "dhl";
					}
					else if($tnt_price != "")
					{
						$cheapest = $tnt_price;
						$courier = "tnt";
					}
					else if($fedex_price != "")
					{
						$cheapest = $fedex_price;
						$courier = "fedex";
					}
					else if($fedex_eco_price != "")
					{
						$cheapest = $fedex_eco_price;
						$courier = "fedex_eco";
					}
						
					if($cheapest != "")
					{
						if($tnt_price != "" && $tnt_price < $cheapest)
						{
							$cheapest = $tnt_price;
							$courier = "tnt";
						}
						if($fedex_price != "" && $fedex_price < $cheapest)
						{
							$cheapest = $fedex_price;
							$courier = "fedex";
						}
						if($fedex_eco_price != "" && $fedex_eco_price < $cheapest)
						{
							$cheapest = $fedex_eco_price;
							$courier = "fedex_eco";
						}
						
						//output
						if(($i == 30)){

							//$tOutput .= '<td style="color:#ffffff;">'.$row_weight['weight_lbs'].'</td>';
						
							if($courier == "dhl")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs DHL Express 2-3 days"/></td>';
								}
								
							else if($courier == "tnt")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs TNT Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs Fedex Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex_eco")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs Fedex Eco Express 3-5 days"/></td>';
								}
								
							else
								$tOutput .= '<td>Not Available</td>';

								 //$tOutput .= '</tr>';

					}else if($row_weight['weight_lbs'] != "+ 0.5 Kg"){

						//$wt = ($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs');

						//$tOutput .= '<td style="color:#000000;">'.$wt.'</td>';
						
						if($courier == "dhl")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' DHL Express 2-3 days"/></td>';
								}
								
						else if($courier == "tnt")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' TNT Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' Fedex Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex_eco")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' Fedex Eco Express 3-5 days"/></td>';
								}
								
						else
							$tOutput .= '<td>Not Available</td>';

						//$tOutput .= '</tr>';

					}
					}
					
					//output cheapeat bus price
					
					$cheapest = "";
					
					$dhl_price = "";
					
					$tnt_price = "";
					
					$fedex_price = "";
					
					$fedex_eco_price = "";
					
					$courier = "";
					
					$query = "SELECT DHL_cost_bus as dhl FROM `pricelist` p INNER JOIN countries c ON c.DHL_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$dhl_price = $row['dhl'];
						
					}
					
					$query = "SELECT TNT_cost_bus as tnt FROM `pricelist` p INNER JOIN countries c ON c.TNT_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$tnt_price = $row['tnt'];
						
					}
					
					$query = "SELECT Fedex_cost_bus as fedex FROM `pricelist` p INNER JOIN countries c ON c.Fedex_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_price = $row['fedex'];
						
					}
					
					$query = "SELECT Fedex_Eco_cost_bus as fedex_eco FROM `pricelist` p INNER JOIN countries c ON c.Fedex_Eco_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_lbs='".$row_weight['weight_lbs']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_eco_price = $row['fedex_eco'];
						
					}
					
					//get the cheapeast price
					if($dhl_price != "")
					{
						$cheapest = $dhl_price;	
						$courier = "dhl";
					}
					else if($tnt_price != "")
					{
						$cheapest = $tnt_price;
						$courier = "tnt";
					}
					else if($fedex_price != "")
					{
						$cheapest = $fedex_price;
						$courier = "fedex";

					}
					else if($fedex_eco_price != "")
					{
						$cheapest = $fedex_eco_price;
						$courier = "fedex_eco";
					}
						
					if($cheapest != "")
					{
						if($tnt_price != "" && $tnt_price < $cheapest)
						{
							$cheapest = $tnt_price;
							$courier = "tnt";
						}
						if($fedex_price != "" && $fedex_price < $cheapest)
						{
							$cheapest = $fedex_price;
							$courier = "fedex";
						}
						if($fedex_eco_price != "" && $fedex_eco_price < $cheapest)
						{
							$cheapest = $fedex_eco_price;
							$courier = "fedex_eco";
						}
						
						//output
						if(($i == 30)){

							//$tOutput .= '<td style="color:#ffffff;">'.$row_weight['weight_lbs'].'</td>';
						
							if($courier == "dhl")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs DHL Express 2-3 days"/></td>';
								}
								
							else if($courier == "tnt")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs TNT Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs Fedex Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex_eco")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_lbs'].' lbs Fedex Eco Express 3-5 days"/></td>';
								}
								
							else
								$tOutput .= '<td>Not Available</td>';

								 $tOutput .= '</tr>';

					}else if($row_weight['weight_lbs'] != "+ 0.5 Kg"){

						//$wt = ($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs');

						//$tOutput .= '<td style="color:#000000;">'.$wt.'</td>';
						
						if($courier == "dhl")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' DHL Express 2-3 days"/></td>';
								}
								
						else if($courier == "tnt")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' TNT Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' Fedex Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex_eco")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_lbs']:$row_weight['weight_lbs'].' lbs').' Fedex Eco Express 3-5 days"/></td>';
								}
								
						else
							$tOutput .= '<td>Not Available</td>';

						$tOutput .= '</tr>';

					}
					}

					$i++;
					
				}//end of while
				

			$tOutput .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';

			$tOutput .= '	<tr><td height="5px"></td></tr>

							<tr>

			                	<td class="main" colspan="3">

			                	<table id="tabFormula" cellpadding="2" cellspacing="2" width="100%">

			                		<tr><td style="font-size:11px;">&nbsp;Shipping charges are based on higher of actual weight and dimension weight.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Special handling fee may apply to certain remote area</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Above rates do not include insurance, tax and duties.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;AIE reserves the right to change the price</td></tr>

			                	</table>

			                	</td>

			                </tr>';

			$tOutput .= '</table><div style="clear:both;"></div>';

			echo $tOutput;	

			}//end if numrows
			
			}//end if price exist

			else{

				$err = '<table id="tabReg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$err .= '<tr class="trothers"><td colspan="3">Sorry, No pricelist available</td></tr>';

				$err .= '</table>';

				echo $err;

			}

		}
		
		else if(@$_POST['show_weight'] == 'kg' && @$_POST['show_express'] == 'Chep'){

			$tOutput = '';

			$i = 1;
			
			//output country and courier
			$query = "SELECT Name FROM `countries` WHERE ID = '".$_POST['ctr']."'";
			
			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){
				
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				
				$tOutput = '<h3>Country:'.$row['Name'].'&nbsp &nbsp Courier:Cheapest</h3>';
			}
			
			//test if price exist
			$query = "SELECT * FROM `pricelist` p INNER JOIN countries c ON c.DHL_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."'";

			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){

				$tOutput .= '<table id="tabRegKg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$tOutput .= '<tr class="trhead">

								<td class="tdhead">Weight</td>

								<td class="tdhead">Standard</td>

								<td class="tdhead">Premium</td>

								<td class="tdhead">Business Class</td>

							</tr>';
			
			//get weight
			$query_weight= "SELECT distinct weight_kg FROM `pricelist`";
			
			$result_weight = mysql_query($query_weight);

			$num_row_weight = mysql_num_rows($result_weight);
			
			if($num_row_weight > 0){
				
				while($row_weight = mysql_fetch_array($result_weight,MYSQL_ASSOC)){
					
					if(($i == 2) || ($i == 11) || ($i == 21)){

						$tOutput .= '<tr class="trothers" style="background-color:#B2A1C7">';

					//}else if(($i == 22) || ($i == 23) || ($i == 32)){

					}else if(($i == 30 || $i == 31)){

						$tOutput .= '<tr class="trothers" style="background-color:#675185;">';

					}else{

						$tOutput .= '<tr class="trothers">';	

					}
					
					//output cheapeat std price
					
					$cheapest = "";
					
					$dhl_price = "";
					
					$tnt_price = "";
					
					$fedex_price = "";
					
					$fedex_eco_price = "";
					
					$courier = "";
					
					$query = "SELECT DHL_cost_std as dhl FROM `pricelist` p INNER JOIN countries c ON c.DHL_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$dhl_price = $row['dhl'];
						
					}
					
					$query = "SELECT TNT_cost_std as tnt FROM `pricelist` p INNER JOIN countries c ON c.TNT_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$tnt_price = $row['tnt'];
						
					}
					
					$query = "SELECT Fedex_cost_std as fedex FROM `pricelist` p INNER JOIN countries c ON c.Fedex_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_price = $row['fedex'];
						
					}
					
					$query = "SELECT Fedex_Eco_cost_std as fedex_eco FROM `pricelist` p INNER JOIN countries c ON c.Fedex_Eco_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_eco_price = $row['fedex_eco'];
						
					}
					
					//get the cheapeast price
					if($dhl_price != "")
					{
						$cheapest = $dhl_price;	
						$courier = "dhl";
					}
					else if($tnt_price != "")
					{
						$cheapest = $tnt_price;
						$courier = "tnt";
					}
					else if($fedex_price != "")
					{
						$cheapest = $fedex_price;
						$courier = "fedex";
					}
					else if($fedex_eco_price != "")
					{
						$cheapest = $fedex_eco_price;
						$courier = "fedex_eco";
					}
						
					if($cheapest != "")
					{
						if($tnt_price != "" && $tnt_price < $cheapest)
						{
							$cheapest = $tnt_price;
							$courier = "tnt";
						}
						if($fedex_price != "" && $fedex_price < $cheapest)
						{
							$cheapest = $fedex_price;
							$courier = "fedex";
						}
						if($fedex_eco_price != "" && $fedex_eco_price < $cheapest)
						{
							$cheapest = $fedex_eco_price;
							$courier = "fedex_eco";
						}
						
						//output
						if(($i == 30 ||$i == 31)){

							$tOutput .= '<td style="color:#ffffff;">'.$row_weight['weight_kg'].'</td>';
						
							if($courier == "dhl")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg DHL Express 2-3 days"/></td>';
								}
								
							else if($courier == "tnt")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg TNT Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg Fedex Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex_eco")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg Fedex Eco Express 3-5 days"/></td>';
								}
								
							else
								$tOutput .= '<td>Not Available</td>';

								 //$tOutput .= '</tr>';

					}else if($i != 30 &&$i != 31){

						$wt = ($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg');

						$tOutput .= '<td style="color:#000000;">'.$wt.'</td>';
						
						if($courier == "dhl")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' DHL Express 2-3 days"/></td>';
								}
								
						else if($courier == "tnt")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' TNT Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' Fedex Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex_eco")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' Fedex Eco Express 3-5 days"/></td>';
								}
								
						else
							$tOutput .= '<td>Not Available</td>';

						//$tOutput .= '</tr>';

					}
					}
					
					//output cheapeat prem price
					
					$cheapest = "";
					
					$dhl_price = "";
					
					$tnt_price = "";
					
					$fedex_price = "";
					
					$fedex_eco_price = "";
					
					$courier = "";
					
					$query = "SELECT DHL_cost_prem as dhl FROM `pricelist` p INNER JOIN countries c ON c.DHL_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$dhl_price = $row['dhl'];
						
					}
					
					$query = "SELECT TNT_cost_prem as tnt FROM `pricelist` p INNER JOIN countries c ON c.TNT_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$tnt_price = $row['tnt'];
						
					}
					
					$query = "SELECT Fedex_cost_prem as fedex FROM `pricelist` p INNER JOIN countries c ON c.Fedex_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_price = $row['fedex'];
						
					}
					
					$query = "SELECT Fedex_Eco_cost_prem as fedex_eco FROM `pricelist` p INNER JOIN countries c ON c.Fedex_Eco_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_eco_price = $row['fedex_eco'];
						
					}
					
					//get the cheapeast price
					if($dhl_price != "")
					{
						$cheapest = $dhl_price;	
						$courier = "dhl";
					}
					else if($tnt_price != "")
					{
						$cheapest = $tnt_price;
						$courier = "tnt";
					}
					else if($fedex_price != "")
					{
						$cheapest = $fedex_price;
						$courier = "fedex";
					}
					else if($fedex_eco_price != "")
					{
						$cheapest = $fedex_eco_price;
						$courier = "fedex_eco";
					}
						
					if($cheapest != "")
					{
						if($tnt_price != "" && $tnt_price < $cheapest)
						{
							$cheapest = $tnt_price;
							$courier = "tnt";
						}
						if($fedex_price != "" && $fedex_price < $cheapest)
						{
							$cheapest = $fedex_price;
							$courier = "fedex";
						}
						if($fedex_eco_price != "" && $fedex_eco_price < $cheapest)
						{
							$cheapest = $fedex_eco_price;
							$courier = "fedex_eco";
						}
						
						//output
						if(($i == 30 || $i == 31)){

							//$tOutput .= '<td style="color:#ffffff;">'.$row_weight['weight_kg'].'</td>';
						
							if($courier == "dhl")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg DHL Express 2-3 days"/></td>';
								}
								
							else if($courier == "tnt")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg TNT Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg Fedex Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex_eco")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg Fedex Eco Express 3-5 days"/></td>';
								}
								
							else
								$tOutput .= '<td>Not Available</td>';

								 //$tOutput .= '</tr>';

					}else if($i != 30 &&$i != 31){

						//$wt = ($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg');

						//$tOutput .= '<td style="color:#000000;">'.$wt.'</td>';
						
						if($courier == "dhl")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' DHL Express 2-3 days"/></td>';
								}
								
						else if($courier == "tnt")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' TNT Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' Fedex Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex_eco")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' Fedex Eco Express 3-5 days"/></td>';
								}
								
						else
							$tOutput .= '<td>Not Available</td>';

						//$tOutput .= '</tr>';

					}
					}
					
					//output cheapeat bus price
					
					$cheapest = "";
					
					$dhl_price = "";
					
					$tnt_price = "";
					
					$fedex_price = "";
					
					$fedex_eco_price = "";
					
					$courier = "";
					
					$query = "SELECT DHL_cost_bus as dhl FROM `pricelist` p INNER JOIN countries c ON c.DHL_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$dhl_price = $row['dhl'];
						
					}
					
					$query = "SELECT TNT_cost_bus as tnt FROM `pricelist` p INNER JOIN countries c ON c.TNT_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$tnt_price = $row['tnt'];
						
					}
					
					$query = "SELECT Fedex_cost_bus as fedex FROM `pricelist` p INNER JOIN countries c ON c.Fedex_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_price = $row['fedex'];
						
					}
					
					$query = "SELECT Fedex_Eco_cost_bus as fedex_eco FROM `pricelist` p INNER JOIN countries c ON c.Fedex_Eco_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."' AND weight_kg='".$row_weight['weight_kg']."'";

					$result = mysql_query($query);

					$num_row = mysql_num_rows($result);
					
					if($num_row > 0){
						
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
							
						$fedex_eco_price = $row['fedex_eco'];
						
					}
					
					//get the cheapeast price
					if($dhl_price != "")
					{
						$cheapest = $dhl_price;	
						$courier = "dhl";
					}
					else if($tnt_price != "")
					{
						$cheapest = $tnt_price;
						$courier = "tnt";
					}
					else if($fedex_price != "")
					{
						$cheapest = $fedex_price;
						$courier = "fedex";
					}
					else if($fedex_eco_price != "")
					{
						$cheapest = $fedex_eco_price;
						$courier = "fedex_eco";
					}
						
					if($cheapest != "")
					{
						if($tnt_price != "" && $tnt_price < $cheapest)
						{
							$cheapest = $tnt_price;
							$courier = "tnt";
						}
						if($fedex_price != "" && $fedex_price < $cheapest)
						{
							$cheapest = $fedex_price;
							$courier = "fedex";
						}
						if($fedex_eco_price != "" && $fedex_eco_price < $cheapest)
						{
							$cheapest = $fedex_eco_price;
							$courier = "fedex_eco";
						}
						
						//output
						if(($i == 30 || $i == 31)){

							//$tOutput .= '<td style="color:#ffffff;">'.$row_weight['weight_kg'].'</td>';
						
							if($courier == "dhl")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg DHL Express 2-3 days"/></td>';
								}
								
							else if($courier == "tnt")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg TNT Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg Fedex Express 2-3 days"/></td>';
								}
								
							else if($courier == "fedex_eco")
							{
								$tOutput .= '<td style="color:#ffffff;">$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.$row_weight['weight_kg'].' kg Fedex Eco Express 3-5 days"/></td>';
								}
								
							else
								$tOutput .= '<td>Not Available</td>';

								 $tOutput .= '</tr>';

					}else if($i != 30 &&$i != 31){

						//$wt = ($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg');

						//$tOutput .= '<td style="color:#000000;">'.$wt.'</td>';
						
						if($courier == "dhl")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' DHL Express 2-3 days"/></td>';
								}
								
						else if($courier == "tnt")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' TNT Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' Fedex Express 2-3 days"/></td>';
								}
								
						else if($courier == "fedex_eco")
							{
								$tOutput .= '<td>$ '.$cheapest.'&nbsp &nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$cheapest.' for '.($i==1?$row_weight['weight_kg']:$row_weight['weight_kg'].' kg').' Fedex Eco Express 3-5 days"/></td>';
								}
								
						else
							$tOutput .= '<td>Not Available</td>';

						$tOutput .= '</tr>';

					}
					}

					$i++;
					
				}//end of while
				

			$tOutput .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';

			$tOutput .= '	<tr><td height="5px"></td></tr>

							<tr>

			                	<td class="main" colspan="3">

			                	<table id="tabFormula" cellpadding="2" cellspacing="2" width="100%">

			                		<tr><td style="font-size:11px;">&nbsp;Shipping charges are based on higher of actual weight and dimension weight.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Special handling fee may apply to certain remote area</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Above rates do not include insurance, tax and duties.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;AIE reserves the right to change the price</td></tr>

			                	</table>

			                	</td>

			                </tr>';

			$tOutput .= '</table><div style="clear:both;"></div>';

			echo $tOutput;	

			}//end if numrows
			
			}//end if price exist

			else{

				$err = '<table id="tabRegKg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$err .= '<tr class="trothers"><td colspan="3">Sorry, No pricelist available</td></tr>';

				$err .= '</table>';

				echo $err;

			}

		}

		else if(@$_POST['show_weight'] == 'lbs' && @$_POST['show_express'] == 'DHL'){

			$tOutput = '';

			$i = 1;
			
			$query = "SELECT Name FROM `countries` WHERE ID = '".$_POST['ctr']."'";
			
			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){
				
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				
				$tOutput = '<h3>Country:'.$row['Name'].'&nbsp &nbsp Courier:DHL</h3>';
			}

			$query = "SELECT p.weight_lbs, p.DHL_cost_std as std, p.DHL_cost_prem as prem, p.DHL_cost_bus as bus FROM `pricelist` p INNER JOIN countries c ON c.DHL_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."'";

			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);

			if($num_row > 0){

				$tOutput .= '<table id="tabReg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$tOutput .= '<tr class="trhead">

								<td class="tdhead">Weight</td>

								<td class="tdhead">Standard</td>

								<td class="tdhead">Premium</td>

								<td class="tdhead">Business Class</td>

							</tr>';

				while($row = mysql_fetch_array($result,MYSQL_ASSOC)){

					if(($i == 2) || ($i == 11) || ($i == 21)){

						$tOutput .= '<tr class="trothers" style="background-color:#b8cce4">';

					//}else if(($i == 22) || ($i == 23) || ($i == 32)){

					}else if(($i == 30)){

						$tOutput .= '<tr class="trothers" style="background-color:#376091;">';

					}else{

						$tOutput .= '<tr class="trothers">';	

					}

					

					//if(($i == 22) || ($i == 23) || ($i == 32)){

					if(($i == 30)){

						$tOutput .= '<td style="color:#ffffff;">'.$row['weight_lbs'].'</td>

								 	<td style="color:#ffffff;">'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['std'].' for '.$row['weight_lbs'].' lbs DHL Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['prem'].' for '.$row['weight_lbs'].' lbs DHL Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['bus'].' for '.$row['weight_lbs'].' lbs DHL Express 2-3 days"/>').'</td>

								 </tr>';

					}else if($row['weight_lbs'] != '+ 0.5 Kg'){

						$wt = ($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs');

						$tOutput .= '<td style="color:#000000;">'.$wt.'</td>

								 	<td>'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['std'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' DHL Express 2-3 days"/>').'</td>

									<td>'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['prem'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' DHL Express 2-3 days"/>').'</td>

									<td>'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['bus'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' DHL Express 2-3 days"/>').'</td>

								 </tr>';

					}

					

					$i++;

				}//end of while

				$tOutput .= '</table>';

			//}

			$tOutput .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';

			$tOutput .= '	<tr><td height="5px"></td></tr>

							<tr>

			                	<td class="main" colspan="3">

			                	<table id="tabFormula" cellpadding="2" cellspacing="2" width="100%">

			                		<tr><td style="font-size:11px;">&nbsp;Shipping charges are based on higher of actual weight and dimension weight.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Special handling fee may apply to certain remote area</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Above rates do not include insurance, tax and duties.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;AIE reserves the right to change the price</td></tr>

			                	</table>

			                	</td>

			                </tr>';

			$tOutput .= '</table><div style="clear:both;"></div>';

			echo $tOutput;	

			}//end if numrows

			else{

				$err = '<table id="tabReg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$err .= '<tr class="trothers"><td colspan="3">Sorry, No pricelist available</td></tr>';

				$err .= '</table>';

				echo $err;

			}

		}
		
		else if(@$_POST['show_weight'] == 'lbs' && @$_POST['show_express'] == 'TNT'){

			$tOutput = '';

			$i = 1;
			
			$query = "SELECT Name FROM `countries` WHERE ID = '".$_POST['ctr']."'";
			
			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){
				
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				
				$tOutput = '<h3>Country:'.$row['Name'].'&nbsp &nbsp Courier:TNT</h3>';
			}

			$query = "SELECT p.weight_lbs, p.TNT_cost_std as std, p.TNT_cost_prem as prem, p.TNT_cost_bus as bus FROM `pricelist` p INNER JOIN countries c ON c.TNT_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."'";

			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);

			if($num_row > 0){

				$tOutput .= '<table id="tabReg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$tOutput .= '<tr class="trhead">

								<td class="tdhead">Weight</td>

								<td class="tdhead">Standard</td>

								<td class="tdhead">Premium</td>

								<td class="tdhead">Business Class</td>

							</tr>';

				while($row = mysql_fetch_array($result,MYSQL_ASSOC)){

					if(($i == 2) || ($i == 11) || ($i == 21)){

						$tOutput .= '<tr class="trothers" style="background-color:#b8cce4">';

					//}else if(($i == 22) || ($i == 23) || ($i == 32)){

					}else if(($i == 30)){

						$tOutput .= '<tr class="trothers" style="background-color:#376091;">';

					}else{

						$tOutput .= '<tr class="trothers">';	

					}

					

					//if(($i == 22) || ($i == 23) || ($i == 32)){

					if(($i == 30)){

						$tOutput .= '<td style="color:#ffffff;">'.$row['weight_lbs'].' lbs</td>

								 	<td style="color:#ffffff;">'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.$row['weight_lbs'].' lbs TNT Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.$row['weight_lbs'].' lbs TNT Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.$row['weight_lbs'].' lbs TNT Express 2-3 days"/>').'</td>

								 </tr>';

					}else if($row['weight_lbs'] != '+ 0.5 Kg'){

						$wt = ($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs');

						$tOutput .= '<td style="color:#000000;">'.$wt.'</td>

								 	<td>'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' TNT Express 2-3 days"/>').'</td>

									<td>'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' TNT Express 2-3 days"/>').'</td>

									<td>'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' TNT Express 2-3 days"/>').'</td>

								 </tr>';

					}

					

					$i++;

				}//end of while

				$tOutput .= '</table>';

			//}

			$tOutput .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';

			$tOutput .= '	<tr><td height="5px"></td></tr>

							<tr>

			                	<td class="main" colspan="3">

			                	<table id="tabFormula" cellpadding="2" cellspacing="2" width="100%">

			                		<tr><td style="font-size:11px;">&nbsp;Shipping charges are based on higher of actual weight and dimension weight.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Special handling fee may apply to certain remote area</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Above rates do not include insurance, tax and duties.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;AIE reserves the right to change the price</td></tr>

			                	</table>

			                	</td>

			                </tr>';

			$tOutput .= '</table><div style="clear:both;"></div>';

			echo $tOutput;	

			}//end if numrows

			else{

				$err = '<table id="tabReg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$err .= '<tr class="trothers"><td colspan="3">Sorry, No pricelist available</td></tr>';

				$err .= '</table>';

				echo $err;

			}

		}
		
		else if(@$_POST['show_weight'] == 'lbs' && @$_POST['show_express'] == 'Fedex'){

			$tOutput = '';

			$i = 1;
			
			$query = "SELECT Name FROM `countries` WHERE ID = '".$_POST['ctr']."'";
			
			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){
				
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				
				$tOutput = '<h3>Country:'.$row['Name'].'&nbsp &nbsp Courier:Fedex</h3>';
			}

			$query = "SELECT p.weight_lbs, p.Fedex_cost_std as std, p.Fedex_cost_prem as prem, p.Fedex_cost_bus as bus FROM `pricelist` p INNER JOIN countries c ON c.Fedex_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."'";

			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);

			if($num_row > 0){

				$tOutput .= '<table id="tabReg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$tOutput .= '<tr class="trhead">

								<td class="tdhead">Weight</td>

								<td class="tdhead">Standard</td>

								<td class="tdhead">Premium</td>

								<td class="tdhead">Business Class</td>

							</tr>';

				while($row = mysql_fetch_array($result,MYSQL_ASSOC)){

					if(($i == 2) || ($i == 11) || ($i == 21)){

						$tOutput .= '<tr class="trothers" style="background-color:#b8cce4">';

					//}else if(($i == 22) || ($i == 23) || ($i == 32)){

					}else if(($i == 30)){

						$tOutput .= '<tr class="trothers" style="background-color:#376091;">';

					}else{

						$tOutput .= '<tr class="trothers">';	

					}

					

					//if(($i == 22) || ($i == 23) || ($i == 32)){

					if(($i == 30)){

						$tOutput .= '<td style="color:#ffffff;">'.$row['weight_lbs'].' lbs</td>

								 	<td style="color:#ffffff;">'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['std'].' for '.$row['weight_lbs'].' lbs Fedex Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['prem'].' for '.$row['weight_lbs'].' lbs Fedex Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['bus'].' for '.$row['weight_lbs'].' lbs Fedex Express 2-3 days"/>').'</td>

								 </tr>';

					}else if($row['weight_lbs'] != '+ 0.5 Kg'){

						$wt = ($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs');

						$tOutput .= '<td style="color:#000000;">'.$wt.'</td>

								 	<td>'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['std'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' Fedex Express 2-3 days"/>').'</td>

									<td>'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['prem'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' Fedex Express 2-3 days"/>').'</td>

									<td>'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['bus'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' Fedex Express 2-3 days"/>').'</td>

								 </tr>';

					}

					

					$i++;

				}//end of while

				$tOutput .= '</table>';

			//}

			$tOutput .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';

			$tOutput .= '	<tr><td height="5px"></td></tr>

							<tr>

			                	<td class="main" colspan="3">

			                	<table id="tabFormula" cellpadding="2" cellspacing="2" width="100%">

			                		<tr><td style="font-size:11px;">&nbsp;Shipping charges are based on higher of actual weight and dimension weight.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Special handling fee may apply to certain remote area</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Above rates do not include insurance, tax and duties.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;AIE reserves the right to change the price</td></tr>

			                	</table>

			                	</td>

			                </tr>';

			$tOutput .= '</table><div style="clear:both;"></div>';

			echo $tOutput;	

			}//end if numrows

			else{

				$err = '<table id="tabReg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$err .= '<tr class="trothers"><td colspan="3">Sorry, No pricelist available</td></tr>';

				$err .= '</table>';

				echo $err;

			}

		}
		
		else if(@$_POST['show_weight'] == 'lbs' && @$_POST['show_express'] == 'Fedex Eco'){

			$tOutput = '';

			$i = 1;
			
			$query = "SELECT Name FROM `countries` WHERE ID = '".$_POST['ctr']."'";
			
			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){
				
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				
				$tOutput = '<h3>Country:'.$row['Name'].'&nbsp &nbsp Courier:Fedex Eco</h3>';
			}

			$query = "SELECT p.weight_lbs, p.Fedex_Eco_cost_std as std, p.Fedex_Eco_cost_prem as prem, p.Fedex_Eco_cost_bus as bus FROM `pricelist` p INNER JOIN countries c ON c.Fedex_Eco_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."'";

			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);

			if($num_row > 0){

				$tOutput .= '<table id="tabReg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$tOutput .= '<tr class="trhead">

								<td class="tdhead">Weight</td>

								<td class="tdhead">Standard</td>

								<td class="tdhead">Premium</td>

								<td class="tdhead">Business Class</td>

							</tr>';

				while($row = mysql_fetch_array($result,MYSQL_ASSOC)){

					if(($i == 2) || ($i == 11) || ($i == 21)){

						$tOutput .= '<tr class="trothers" style="background-color:#b8cce4">';

					//}else if(($i == 22) || ($i == 23) || ($i == 32)){

					}else if(($i == 30)){

						$tOutput .= '<tr class="trothers" style="background-color:#376091;">';

					}else{

						$tOutput .= '<tr class="trothers">';	

					}

					

					//if(($i == 22) || ($i == 23) || ($i == 32)){

					if(($i == 30)){

						$tOutput .= '<td style="color:#ffffff;">'.$row['weight_lbs'].' lbs</td>

								 	<td style="color:#ffffff;">'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['std'].' for '.$row['weight_lbs'].' lbs Fedex Eco Express 3-5 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['prem'].' for '.$row['weight_lbs'].' lbs Fedex Eco Express 3-5 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['bus'].' for '.$row['weight_lbs'].' lbs Fedex Eco Express 3-5 days"/>').'</td>

								 </tr>';

					}else if($row['weight_lbs'] != '+ 0.5 Kg'){

						$wt = ($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs');

						$tOutput .= '<td style="color:#000000;">'.$wt.'</td>

								 	<td>'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['std'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' Fedex Eco Express 3-5 days"/>').'</td>

									<td>'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['prem'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' Fedex Eco Express 3-5 days"/>').'</td>

									<td>'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['bus'].' for '.($i==1?$row['weight_lbs']:$row['weight_lbs'].' lbs').' Fedex Eco Express 3-5 days"/>').'</td>

								 </tr>';

					}

					

					$i++;

				}//end of while

				$tOutput .= '</table>';

			//}

			$tOutput .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';

			$tOutput .= '	<tr><td height="5px"></td></tr>

							<tr>

			                	<td class="main" colspan="3">

			                	<table id="tabFormula" cellpadding="2" cellspacing="2" width="100%">

			                		<tr><td style="font-size:11px;">&nbsp;Shipping charges are based on higher of actual weight and dimension weight.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Special handling fee may apply to certain remote area</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Above rates do not include insurance, tax and duties.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;AIE reserves the right to change the price</td></tr>

			                	</table>

			                	</td>

			                </tr>';

			$tOutput .= '</table><div style="clear:both;"></div>';

			echo $tOutput;	

			}//end if numrows

			else{

				$err = '<table id="tabReg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$err .= '<tr class="trothers"><td colspan="3">Sorry, No pricelist available</td></tr>';

				$err .= '</table>';

				echo $err;

			}

		}
		
		//for kg
		else if(@$_POST['show_weight'] == 'kg' && @$_POST['show_express'] == 'DHL'){

			$tOutput = '';

			$i = 1;
			
			$query = "SELECT Name FROM `countries` WHERE ID = '".$_POST['ctr']."'";
			
			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){
				
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				
				$tOutput = '<h3>Country:'.$row['Name'].'&nbsp &nbsp Courier:DHL</h3>';
			}

			$query = "SELECT p.weight_kg, p.DHL_cost_std as std, p.DHL_cost_prem as prem, p.DHL_cost_bus as bus FROM `pricelist` p INNER JOIN countries c ON c.DHL_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."'";

			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);

			if($num_row > 0){

				$tOutput .= '<table id="tabRegKg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$tOutput .= '<tr class="trhead">

								<td class="tdhead">Weight</td>

								<td class="tdhead">Standard</td>

								<td class="tdhead">Premium</td>

								<td class="tdhead">Business Class</td>

							</tr>';

				while($row = mysql_fetch_array($result,MYSQL_ASSOC)){

					if(($i == 2) || ($i == 11) || ($i == 21)){

						$tOutput .= '<tr class="trothers" style="background-color:#B2A1C7">';

					//}else if(($i == 22) || ($i == 23) || ($i == 32)){

					}else if(($i == 30 ||$i == 31)){

						$tOutput .= '<tr class="trothers" style="background-color:#675185;">';

					}else{

						$tOutput .= '<tr class="trothers">';	

					}

					

					//if(($i == 22) || ($i == 23) || ($i == 32)){

					if(($i == 30 ||$i == 31)){

						$tOutput .= '<td style="color:#ffffff;">'.$row['weight_kg'].'</td>

								 	<td style="color:#ffffff;">'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['std'].' for '.$row['weight_kg'].' kg DHL Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['prem'].' for '.$row['weight_kg'].' kg DHL Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['bus'].' for '.$row['weight_kg'].' kg DHL Express 2-3 days"/>').'</td>

								 </tr>';

					}else if($i != 30 &&$i != 31){

						$wt = ($i==1?$row['weight_kg']:$row['weight_kg'].' kg');

						$tOutput .= '<td style="color:#000000;">'.$wt.'</td>

								 	<td>'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['std'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' DHL Express 2-3 days"/>').'</td>

									<td>'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['prem'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' DHL Express 2-3 days"/>').'</td>

									<td>'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/dhl_logo.png" alt="dhl_logo" width="47" height="11" title="$ '.$row['bus'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' DHL Express 2-3 days"/>').'</td>

								 </tr>';

					}

					

					$i++;

				}//end of while

				$tOutput .= '</table>';

			//}

			$tOutput .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';

			$tOutput .= '	<tr><td height="5px"></td></tr>

							<tr>

			                	<td class="main" colspan="3">

			                	<table id="tabFormula" cellpadding="2" cellspacing="2" width="100%">

			                		<tr><td style="font-size:11px;">&nbsp;Shipping charges are based on higher of actual weight and dimension weight.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Special handling fee may apply to certain remote area</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Above rates do not include insurance, tax and duties.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;AIE reserves the right to change the price</td></tr>

			                	</table>

			                	</td>

			                </tr>';

			$tOutput .= '</table><div style="clear:both;"></div>';

			echo $tOutput;	

			}//end if numrows

			else{

				$err = '<table id="tabRegKg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$err .= '<tr class="trothers"><td colspan="3">Sorry, No pricelist available</td></tr>';

				$err .= '</table>';

				echo $err;

			}

		}
		
		else if(@$_POST['show_weight'] == 'kg' && @$_POST['show_express'] == 'TNT'){

			$tOutput = '';

			$i = 1;
			
			$query = "SELECT Name FROM `countries` WHERE ID = '".$_POST['ctr']."'";
			
			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){
				
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				
				$tOutput = '<h3>Country:'.$row['Name'].'&nbsp &nbsp Courier:TNT</h3>';
			}

			$query = "SELECT p.weight_kg, p.TNT_cost_std as std, p.TNT_cost_prem as prem, p.TNT_cost_bus as bus FROM `pricelist` p INNER JOIN countries c ON c.TNT_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."'";

			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);

			if($num_row > 0){

				$tOutput .= '<table id="tabRegKg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$tOutput .= '<tr class="trhead">

								<td class="tdhead">Weight</td>

								<td class="tdhead">Standard</td>

								<td class="tdhead">Premium</td>

								<td class="tdhead">Business Class</td>

							</tr>';

				while($row = mysql_fetch_array($result,MYSQL_ASSOC)){

					if(($i == 2) || ($i == 11) || ($i == 21)){

						$tOutput .= '<tr class="trothers" style="background-color:#B2A1C7">';

					//}else if(($i == 22) || ($i == 23) || ($i == 32)){

					}else if(($i == 30 ||$i == 31)){

						$tOutput .= '<tr class="trothers" style="background-color:#675185;">';

					}else{

						$tOutput .= '<tr class="trothers">';	

					}

					

					//if(($i == 22) || ($i == 23) || ($i == 32)){

					if(($i == 30 ||$i == 31)){

						$tOutput .= '<td style="color:#ffffff;">'.$row['weight_kg'].' kg</td>

								 	<td style="color:#ffffff;">'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.$row['weight_kg'].' kg TNT Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.$row['weight_kg'].' kg TNT Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.$row['weight_kg'].' kg TNT Express 2-3 days"/>').'</td>

								 </tr>';

					}else if($i != 30 &&$i != 31){

						$wt = ($i==1?$row['weight_kg']:$row['weight_kg'].' kg');

						$tOutput .= '<td style="color:#000000;">'.$wt.'</td>

								 	<td>'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' TNT Express 2-3 days"/>').'</td>

									<td>'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' TNT Express 2-3 days"/>').'</td>

									<td>'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/tnt_logo.png" alt="tnt_logo" width="28" height="15" title="$ '.$row['std'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' TNT Express 2-3 days"/>').'</td>

								 </tr>';

					}

					

					$i++;

				}//end of while

				$tOutput .= '</table>';

			//}

			$tOutput .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';

			$tOutput .= '	<tr><td height="5px"></td></tr>

							<tr>

			                	<td class="main" colspan="3">

			                	<table id="tabFormula" cellpadding="2" cellspacing="2" width="100%">

			                		<tr><td style="font-size:11px;">&nbsp;Shipping charges are based on higher of actual weight and dimension weight.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Special handling fee may apply to certain remote area</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Above rates do not include insurance, tax and duties.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;AIE reserves the right to change the price</td></tr>

			                	</table>

			                	</td>

			                </tr>';

			$tOutput .= '</table><div style="clear:both;"></div>';

			echo $tOutput;	

			}//end if numrows

			else{

				$err = '<table id="tabRegKg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$err .= '<tr class="trothers"><td colspan="3">Sorry, No pricelist available</td></tr>';

				$err .= '</table>';

				echo $err;

			}

		}
		
		else if(@$_POST['show_weight'] == 'kg' && @$_POST['show_express'] == 'Fedex'){

			$tOutput = '';

			$i = 1;
			
			$query = "SELECT Name FROM `countries` WHERE ID = '".$_POST['ctr']."'";
			
			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){
				
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				
				$tOutput = '<h3>Country:'.$row['Name'].'&nbsp &nbsp Courier:Fedex</h3>';
			}

			$query = "SELECT p.weight_kg, p.Fedex_cost_std as std, p.Fedex_cost_prem as prem, p.Fedex_cost_bus as bus FROM `pricelist` p INNER JOIN countries c ON c.Fedex_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."'";

			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);

			if($num_row > 0){

				$tOutput .= '<table id="tabRegKg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$tOutput .= '<tr class="trhead">

								<td class="tdhead">Weight</td>

								<td class="tdhead">Standard</td>

								<td class="tdhead">Premium</td>

								<td class="tdhead">Business Class</td>

							</tr>';

				while($row = mysql_fetch_array($result,MYSQL_ASSOC)){

					if(($i == 2) || ($i == 11) || ($i == 21)){

						$tOutput .= '<tr class="trothers" style="background-color:#B2A1C7">';

					//}else if(($i == 22) || ($i == 23) || ($i == 32)){

					}else if(($i == 30 ||$i == 31)){

						$tOutput .= '<tr class="trothers" style="background-color:#675185;">';

					}else{

						$tOutput .= '<tr class="trothers">';	

					}

					

					//if(($i == 22) || ($i == 23) || ($i == 32)){

					if(($i == 30 ||$i == 31)){

						$tOutput .= '<td style="color:#ffffff;">'.$row['weight_kg'].' kg</td>

								 	<td style="color:#ffffff;">'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['std'].' for '.$row['weight_kg'].' kg Fedex Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['prem'].' for '.$row['weight_kg'].' kg Fedex Express 2-3 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['bus'].' for '.$row['weight_kg'].' kg Fedex Express 2-3 days"/>').'</td>

								 </tr>';

					}else if($i != 30 &&$i != 31){

						$wt = ($i==1?$row['weight_kg']:$row['weight_kg'].' kg');

						$tOutput .= '<td style="color:#000000;">'.$wt.'</td>

								 	<td>'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['std'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' Fedex Express 2-3 days"/>').'</td>

									<td>'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['prem'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' Fedex Express 2-3 days"/>').'</td>

									<td>'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp &nbsp &nbsp'.'<img src="images/fedex_logo.png" alt="fedex_logo" width="36" height="12" title="$ '.$row['bus'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' Fedex Express 2-3 days"/>').'</td>

								 </tr>';

					}

					

					$i++;

				}//end of while

				$tOutput .= '</table>';

			//}

			$tOutput .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';

			$tOutput .= '	<tr><td height="5px"></td></tr>

							<tr>

			                	<td class="main" colspan="3">

			                	<table id="tabFormula" cellpadding="2" cellspacing="2" width="100%">

			                		<tr><td style="font-size:11px;">&nbsp;Shipping charges are based on higher of actual weight and dimension weight.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Special handling fee may apply to certain remote area</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Above rates do not include insurance, tax and duties.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;AIE reserves the right to change the price</td></tr>

			                	</table>

			                	</td>

			                </tr>';

			$tOutput .= '</table><div style="clear:both;"></div>';

			echo $tOutput;	

			}//end if numrows

			else{

				$err = '<table id="tabRegKg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$err .= '<tr class="trothers"><td colspan="3">Sorry, No pricelist available</td></tr>';

				$err .= '</table>';

				echo $err;

			}

		}
		
		else if(@$_POST['show_weight'] == 'kg' && @$_POST['show_express'] == 'Fedex Eco'){

			$tOutput = '';

			$i = 1;
			
			$query = "SELECT Name FROM `countries` WHERE ID = '".$_POST['ctr']."'";
			
			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);
			
			if($num_row > 0){
				
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				
				$tOutput = '<h3>Country:'.$row['Name'].'&nbsp &nbsp Courier:Fedex Eco</h3>';
			}

			$query = "SELECT p.weight_kg, p.Fedex_Eco_cost_std as std, p.Fedex_Eco_cost_prem as prem, p.Fedex_Eco_cost_bus as bus FROM `pricelist` p INNER JOIN countries c ON c.Fedex_Eco_continents_id = p.continents_id AND c.ID = '".$_POST['ctr']."'";

			$result = mysql_query($query);

			$num_row = mysql_num_rows($result);

			if($num_row > 0){

				$tOutput .= '<table id="tabRegKg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$tOutput .= '<tr class="trhead">

								<td class="tdhead">Weight</td>

								<td class="tdhead">Standard</td>

								<td class="tdhead">Premium</td>

								<td class="tdhead">Business Class</td>

							</tr>';

				while($row = mysql_fetch_array($result,MYSQL_ASSOC)){

					if(($i == 2) || ($i == 11) || ($i == 21)){

						$tOutput .= '<tr class="trothers" style="background-color:#B2A1C7">';

					//}else if(($i == 22) || ($i == 23) || ($i == 32)){

					}else if(($i == 30 ||$i == 31)){

						$tOutput .= '<tr class="trothers" style="background-color:#675185;">';

					}else{

						$tOutput .= '<tr class="trothers">';	

					}

					

					//if(($i == 22) || ($i == 23) || ($i == 32)){

					if(($i == 30 ||$i == 31)){

						$tOutput .= '<td style="color:#ffffff;">'.$row['weight_kg'].' kg</td>

								 	<td style="color:#ffffff;">'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['std'].' for '.$row['weight_kg'].' kg Fedex Eco Express 3-5 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['prem'].' for '.$row['weight_kg'].' kg Fedex Eco Express 3-5 days"/>').'</td>

									<td style="color:#ffffff;">'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['bus'].' for '.$row['weight_kg'].' kg Fedex Eco Express 3-5 days"/>').'</td>

								 </tr>';

					}else if($i != 30 &&$i != 31){

						$wt = ($i==1?$row['weight_kg']:$row['weight_kg'].' kg');

						$tOutput .= '<td style="color:#000000;">'.$wt.'</td>

								 	<td>'.($row['std']==0.00?'Not Available':'$ '.$row['std'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['std'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' Fedex Eco Express 3-5 days"/>').'</td>

									<td>'.($row['prem']==0.00?'Not Available':'$ '.$row['prem'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['prem'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' Fedex Eco Express 3-5 days"/>').'</td>

									<td>'.($row['bus']==0.00?'Not Available':'$ '.$row['bus'].'&nbsp'.'<img src="images/fedex_eco_logo.png" alt="fedex_eco_logo" width="53" height="12" title="$ '.$row['bus'].' for '.($i==1?$row['weight_kg']:$row['weight_kg'].' kg').' Fedex Eco Express 3-5 days"/>').'</td>

								 </tr>';

					}

					

					$i++;

				}//end of while

				$tOutput .= '</table>';

			//}

			$tOutput .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">';

			$tOutput .= '	<tr><td height="5px"></td></tr>

							<tr>

			                	<td class="main" colspan="3">

			                	<table id="tabFormula" cellpadding="2" cellspacing="2" width="100%">

			                		<tr><td style="font-size:11px;">&nbsp;Shipping charges are based on higher of actual weight and dimension weight.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Special handling fee may apply to certain remote area</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;Above rates do not include insurance, tax and duties.</td></tr>

			                		<tr><td style="font-size:11px;">&nbsp;AIE reserves the right to change the price</td></tr>

			                	</table>

			                	</td>

			                </tr>';

			$tOutput .= '</table><div style="clear:both;"></div>';

			echo $tOutput;	

			}//end if numrows

			else{

				$err = '<table id="tabRegKg" cellpadding="2" cellspacing="2" width="100%" border="0" align="center">';

				$err .= '<tr class="trothers"><td colspan="3">Sorry, No pricelist available</td></tr>';

				$err .= '</table>';

				echo $err;

			}

		}
    ?>

</div>
</div>


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
<p style="margin-left:550px;color:#FFF; font-size:12px">&copy; 2010-2012 by Amerika International Express.<br>
All rights reserved. AIETimes.com is a trademark of Amerika International Express.</p>
</div>
</body>
</html>