<?php
session_start();
//修改如下
$count=$_SESSION['count'];//$count start from 0
$_SESSION['mag_id'][$count] = $_GET['MID'];
$_SESSION['number_of_issue'][$count] = $_GET['NUM'];
$_SESSION['quantity'][$count] = $_POST['qty'];
$_SESSION['period'][$count] = $_POST['period'];
$_SESSION['count']=$_SESSION['count']+1;
header("location: shoppingcart.php");
?>