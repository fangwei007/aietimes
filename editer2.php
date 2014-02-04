<?php
session_start();
include("DatabaseClass.php");
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$country = $_POST['country'];
$zip = $_POST['zipcode'];
$cardnumber = $_POST['cardnumber'];
$cardtype = $_POST['cardtype'];
$cardcvc = $_POST['cardcvc'];
$expmonth = $_POST['expmonth'];
$expyear = $_POST['expyear'];
	$pattern_address ="([a-zA-Z0-9][a-zA-Z0-9!@#$%*.()]$)";//"/^[a-zA-Z0-9][\.a-zA-Z\s,0-9]*?[a-zA-Z]+$/ ";
	if (!preg_match($pattern_address, $address1) ||(!preg_match($pattern_address, $address2) && $address2 != NULL) || !preg_match($pattern_address, $city) || !preg_match($pattern_address, $state) || !preg_match($pattern_address, $country))
	{
		echo "<script>";
		echo "alert('Address is illegal!');";
		echo "window.location.href=\"edit2.php\";";
		echo "</script>";
		exit;
	}
	$pattern_zip ="/(^\d{5}-\d{4}$)|(^\d{5}$)|(^[A-Za-z]\d[A-Za-z]\s?\d[A-Za-z]\d$)/";
	if (!preg_match($pattern_zip, $zip))
	{
		echo "<script>";
		echo "alert('Zipcode is illegal!');";
		echo "window.location.href=\"edit2.php\";";
		echo "</script>";
		exit;
	}
	$db->connect();
	//这里的信用卡号老是存不对是什么原因？奇怪
		$sql = "UPDATE creditcardinfo SET card_number = '$cardnumber', card_type = '$cardtype',exp_month = '$expmonth',exp_year = '$expyear',card_cvc = '$cardcvc',billing_address1 = '$address1', billing_address2 = '$address2', billing_city = '$city', billing_state = '$state', billing_zip = '$zip', billing_country = '$country' WHERE `user_id`='".$_SESSION['user_id']."'";
		$result = mysql_query($sql);
		
		echo "<script>";
		echo "alert('Credit Information has been updated!');";
		echo "window.location.href=\"account.php\";";
		echo "</script>";
	$db->close();
//}
	
?>