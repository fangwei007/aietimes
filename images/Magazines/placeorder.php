<?php
$cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
	if(!isset($_SESSION['user_id']))
	header("location: index.php");//For security, header the user back to login page if that user is not properly logged
    
function magazine_price($mag_id,$quantity, $period){
	$price=mysql_query("SELECT price_per_issue, price_three_months, price_six_months, price_nine_months, price_twelve_months FROM magazineinfo WHERE mag_id = '".$mag_id."'");
	$row = mysql_fetch_array($price);
	if($period =="1 issue")
	{
		$amount=$row[0]*$quantity;
		
		}
	if($period =="3 months")
	{
		$amount=$row[1]*$quantity;
		}
	if($period=="6 months")
	{
		$amount=$row[2]*$quantity;
		}
	if($period=="9 months")
	{
		$amount=$row[3]*$quantity;
		}
	if($period=="12 months")
	{
		$amount=$row[4]*$quantity;
		}
	return $amount;	
	}
	
include("DatabaseClass.php");
$db->connect();

//check if this client has registered the billing info
$check_billing_info="SELECT * FROM `creditcardinfo` WHERE `user_id`='".$_SESSION['user_id']."'";
if($r=mysql_fetch_row(mysql_query($check_billing_info)))
$credicard_id=$r[0];
else
header("location: billing.php");

$date=getdate();
$today = date('Y-m-d');

for($i=0;$i<$_SESSION['count'];$i++){
 $single_magazine_price[$i] = magazine_price($_SESSION['mag_id'][$i],$_SESSION['quantity'][$i],$_SESSION['period'][$i])."<br>";
}
$amount = array_sum($single_magazine_price);

$generatePayment="INSERT INTO paymentinfo (creditcard_id,date,amount,ordertype,status)
VALUES
('".$credicard_id."','".$today."','".$amount."','MagazineOrder','pending')";//pending -> charged -> fulfill(subscribe)
mysql_query($generatePayment);
$get_paymentID=mysql_insert_id();//return the last inserted ID

$get_address="SELECT * FROM `shippingaddress` WHERE `user_id`='".$_SESSION['user_id']."'";
$r1=mysql_fetch_row(mysql_query($get_address));
$get_addressID=$r1[0];


$placeOrder="INSERT INTO `order` ( `user_id` , `payment_id` , `start_date` , `status`,`address_id` )
VALUES ('".$_SESSION['user_id']."','".$get_paymentID."','".$today."','pending','".$get_addressID."')";//pending -> charged -> fulfill(subscribe)
mysql_query($placeOrder);
$get_orderID=mysql_insert_id();//return the last inserted order_id

for($i=0;$i<$_SESSION['count'];$i++){
	$sql_get_issue_per_month="SELECT `issue_per_month` FROM `magazineinfo` WHERE `mag_id` = ".$_SESSION['mag_id'][$i];
	//echo $sql_get_issue_per_month;
	$result=mysql_query($sql_get_issue_per_month);
	$row = mysql_fetch_array($result);
	//echo $row['issue_per_month'];
	if($_SESSION['period'][$i]== "1 issue"){
		$day = 14;		
		}
	else if($_SESSION['period'][$i]== "3 months"){
		$day = 98;
		}
	else if($_SESSION['period'][$i]== "6 months"){
		$day = 182;
		}
	else if($_SESSION['period'][$i]== "9 months"){
		$day = 266;
		}
	else if($_SESSION['period'][$i]== "12 months"){
		$day = 350;
		}
	$subscribe_date = explode("-",$today);	
	$end_date = date('Y-m-d',mktime(0,0,0,$subscribe_date[1],$subscribe_date[2]+$day,$subscribe_date[0]));
	if($row['issue_per_month']==4)
	$order_magazings = "INSERT INTO `order_magazineinfo` ( `quantity`, `mag_id` , `order_id` , `subscribe_date` , `number_of_issue`, price, `order_period`,`end_date`)
VALUES ('".$_SESSION['quantity'][$i]."','".$_SESSION['mag_id'][$i]."','".$get_orderID."','".$today."','". $_SESSION['number_of_issue'][$i]."','".magazine_price($_SESSION['mag_id'][$i],$_SESSION['quantity'][$i],$_SESSION['period'][$i])."','".$_SESSION['period'][$i]."','".$end_date."')";
	else
	$order_magazings = "INSERT INTO `order_magazineinfo` ( `quantity`, `mag_id` , `order_id` , `subscribe_date` , `number_of_issue`, price, `shipping_period`, `order_period`,`end_date` )
VALUES ('".$_SESSION['quantity'][$i]."','".$_SESSION['mag_id'][$i]."','".$get_orderID."','".$today."','". $_SESSION['number_of_issue'][$i]."','".magazine_price($_SESSION['mag_id'][$i],$_SESSION['quantity'][$i],$_SESSION['period'][$i])."', '3', '".$_SESSION['period'][$i]."','".$end_date."')";
mysql_query($order_magazings);
$get_order_magazineID=mysql_insert_id();
}


$db->close();
for($i=0;$i<$_SESSION['count'];$i++){
unset($_SESSION['mag_id'][$i]);
unset($_SESSION['number_of_issue'][$i]);
}
unset($_SESSION['count']);
header("location: Myorder.php");
?>
