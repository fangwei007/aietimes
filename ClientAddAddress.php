<?php
function SecureSQL($str){
	$newstr=str_replace("'","\'",$str);//SQL-Security
	return $newstr;
	}
include("DatabaseClass.php");
$cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
$user_id=$_SESSION['user_id'];
$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$phone = $_POST['phone'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$country = $_POST['country'];
$zip = $_POST['zipcode'];

if(empty($firstname) || empty($lastname))
{
	echo 'Incomplete input!';
	exit;
}
else
{
	$pattern_name="/^[A-Za-z]+$/";
	if(!preg_match($pattern_name, $firstname) || !preg_match($pattern_name, $lastname))
	{
		echo "<script>";
		echo "alert('Illegal name!');";
		echo "window.location.href=\"ClientNewAddress.php\";";
		echo "</script>";
		exit;		
	}

	$pattern_zip ="/(^\d{5}-\d{4}$)|(^\d{5}$)|(^[A-Za-z]\d[A-Za-z]\s?\d[A-Za-z]\d$)/";
	if (!preg_match($pattern_zip, $zip))
	{
		echo "<script>";
		echo "alert('Zipcode is illegal!');";
		echo "window.location.href=\"ClientNewAddress.php\";";
		echo "</script>";
		exit;
	}
	$db->connect();
		$sql = "INSERT INTO shippingaddress (user_id,first_name,last_name,address1,address2,city,state,zip,country,phone) VALUES";
		$sql .="('$user_id','$firstname','$lastname','$address1','$address2','$city','$state','$zip','$country','$phone')";
		$result = mysql_query($sql);
	
	}
	$db->close();
header("location: ClientAddress.php");
?>