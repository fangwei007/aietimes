<?php
include("DatabaseClass.php");
$db->connect();
mysql_query("DELETE FROM `order_magazineinfo` WHERE `order_id` = '".$_GET['ID']."';");
$query_result = mysql_query("SELECT * FROM `order` WHERE `order_id` = '".$_GET['ID']."';");
$row = mysql_fetch_array($query_result);
$temp=$row['payment_id'];
mysql_query("DELETE FROM `order` WHERE `order_id` = '".$_GET['ID']."';");
mysql_query("DELETE FROM `paymentinfo` WHERE `payment_id` = '".$temp."';");
$db->close();
header("location: orderprocess.php");
?>