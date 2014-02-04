<?php
session_start();
include("DatabaseClass.php");
//$firstname = trim($_POST['firstname']);
//$lastname = trim($_POST['lastname']);
$gender = $_POST['gender'];
//$_POST['birthday']=$_POST['year']."-".$_POST['month']."-".$_POST['day'];
//$birthday = $_POST['birthday'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$email = trim($_POST['email']);
$phone = $_POST['phone'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$country = $_POST['country'];
$zip = $_POST['zipcode'];
//$cardnumber = $_POST['cardnumber'];
//$cardtype = $_POST['cardtype'];
//$cardcvc = $_POST['cardcvc'];
//$expmonth = $_POST['expmonth'];
//$expyear = $_POST['expyear'];
/*if(empty($firstname) || empty($lastname) || empty($password) || $cpassword!=$password)
{
	echo 'Incomplete input!';
	exit;
}
else
{
	/*$pattern_name="/^[A-Za-z]+$/";
	if(!preg_match($pattern_name, $firstname) || !preg_match($pattern_name, $lastname))
	{
		echo "<script>";
		echo "alert('Illegal name!');";
		echo "window.location.href=\"RegisterHP1.php\";";
		echo "</script>";
		exit;		
	}*/
	$pattern_pw="/^(\w){6,20}$/";
	if(!preg_match($pattern_pw, $password))
	{
		echo "<script>";
		echo "alert('Illegal password!');";
		echo "window.location.href=\"edit1.php\";";
		echo "</script>";
		exit;		
	}
	$pattern_email ="/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
	if (!preg_match($pattern_email, $email))
	{
		echo "<script>";
		echo "alert('Email is illegal!');";
		echo "window.location.href=\"edit1.php\";";
		echo "</script>";
		exit;
	}
	$pattern_address ="([a-zA-Z0-9][a-zA-Z0-9!@#$%*.()]$)";//"/^[a-zA-Z0-9][\.a-zA-Z\s,0-9]*?[a-zA-Z]+$/ ";
	if (!preg_match($pattern_address, $address1) ||(!preg_match($pattern_address, $address2) && $address2 != NULL) || !preg_match($pattern_address, $city) || !preg_match($pattern_address, $state) || !preg_match($pattern_address, $country))
	{
		echo "<script>";
		echo "alert('Address is illegal!');";
		echo "window.location.href=\"edit1.php\";";
		echo "</script>";
		exit;
	}
	$pattern_zip ="/(^\d{5}-\d{4}$)|(^\d{5}$)|(^[A-Za-z]\d[A-Za-z]\s?\d[A-Za-z]\d$)/";
	if (!preg_match($pattern_zip, $zip))
	{
		echo "<script>";
		echo "alert('Zipcode is illegal!');";
		echo "window.location.href=\"edit1.php\";";
		echo "</script>";
		exit;
	}
	$db->connect();
	//"SELECT * FROM `creditcardinfo` WHERE `user_id`='".$_SESSION['user_id']."'"
	//$sql = "SELECT * FROM `useraccount` WHERE `user_id`='".$_SESSION['user_id']."'";
	//$result = mysql_query($sql);
	/*if($result && mysql_num_rows($result)>0)
	{
		echo "<script>";
		echo "alert('This user is already existed!');";
		echo "window.location.href=\"RegisterHP1.php\";";			
		echo "</script>";
	}*/
	//else
	//{UPDATE table_name
//SET column_name = new_value
//WHERE column_name = some_value
		$jd=date('Y-m-d'); 
		$password=sha1($password);
		$sql = "UPDATE useraccount SET password = '$password',gender = '$gender',join_date = '$jd',email = '$email',phone = '$phone' WHERE `user_id`='".$_SESSION['user_id']."'";
		$sql2 = "UPDATE shippingaddress SET address1 = '$address1',address2 = '$address2',city = '$city',state = '$state', zip = '$zip', country = '$country', phone = '$phone' WHERE `user_id`='".$_SESSION['user_id']."'";
		$result = mysql_query($sql);
		$result2 = mysql_query($sql2);
		/*if(!$result)
		{
			@mysql_free_result($result);
			$db->close();
			echo "<script>";
		    echo "alert('E-mail is already exsited!');";
			echo "window.location.href=\"RegisterHP1.php\";";	
		    echo "</script>";
			exit;
		}*/
		echo "<script>";
		echo "alert('Customer Information has been updated!');";
		echo "window.location.href=\"account.php\";";
		echo "</script>";/*
		$user_id = mysql_query("SELECT `user_id` FROM `useraccount` WHERE `first_name` = '".$firstname."' AND `last_name`= '".$lastname."'");
		$UID = mysql_fetch_array($user_id);
		$sql2 = "INSERT INTO creditcardinfo (user_id,first_name,last_name,billing_address1,billing_address2,billing_city,billing_state,billing_country,billing_zip,card_number,card_type,card_cvc,exp_month,exp_year) VALUES";
		$sql2 .="('$UID[0]','$firstname','$lastname','$address1','$address2','$city','$state','$country','$zip','$cardnumber','$cardtype','$cardcvc','$expmonth','$expyear')";
        $result2 = mysql_query($sql2);
		
		$sql3 = "INSERT INTO shippingaddress (user_id,first_name,last_name,address1,address2,city,state,country,zip,phone) VALUES";
		$sql3 .="('$UID[0]','$firstname','$lastname','$address1','$address2','$city','$state','$country','$zip','$phone')";
        $result3 = mysql_query($sql3);
		
		$address_id = mysql_query("SELECT `address_id`,`user_id` FROM `shippingaddress` WHERE `user_id` = '".$UID[0]."'");
		$AID = mysql_fetch_array($address_id);
		$sql4 = "UPDATE useraccount  SET default_addressid='".$AID[0]."' WHERE `user_id` = '".$AID[1]."'";
		$result4 = mysql_query($sql4);
	}*/
	$db->close();
//}
	
?>