<?php
$cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");

if(isset($_SESSION['user_id'])){
unset($_SESSION['user_id']);
unset($_SESSION['first_name']);
session_destroy();
}
header("location: index.php");
?>