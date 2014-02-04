<?php
$cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
$item_Num=$_GET['item'];
$count=$_SESSION['count'];//$count start from 0
if(($count-1)==$item_Num){

	unset($_SESSION['mag_id'][$item_Num]);
	unset($_SESSION['number_of_issue'][$item_Num]);
	$_SESSION['count']=$_SESSION['count']-1;	
}
else{
	for($i=$item_Num;$i<($count-1);$i++){
	 $_SESSION['mag_id'][$i]=$_SESSION['mag_id'][$i+1];
	}
	unset($_SESSION['mag_id'][$count-1]);
	unset($_SESSION['number_of_issue'][$count-1]);
	$_SESSION['count']=$_SESSION['count']-1;
}
header("location: shoppingcart.php");
?>