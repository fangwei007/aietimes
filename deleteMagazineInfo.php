<?php
include("DatabaseClass.php");
$db->connect();
mysql_query("DELETE FROM `magazineinfo` WHERE `mag_id` = '".$_GET['ID']."';");
$db->close();
header("location: addMagazines.php");
?>