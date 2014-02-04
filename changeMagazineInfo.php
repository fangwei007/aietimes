<?php
function SecureSQL($str){
	$newstr=str_replace("'","\'",$str);//SQL-Security
	return $newstr;
	}
if($_POST['issue_period']==="Weekly")	
	$issue_per_year = 48;
elseif($_POST['issue_period']==="Monthly")	
	$issue_per_year = 12;
else $issue_per_year = 4;

include("DatabaseClass.php");
$db->connect();
mysql_query("UPDATE `magazineinfo` SET `mag_name`='".@SecureSQL($_POST[mag_name])."',`category`='".@SecureSQL($_POST[category])."',`issue_period`='$_POST[issue_period]',`price_per_year` = '".@SecureSQL($_POST[price_per_year])."',`weight_per_issue` = '".@SecureSQL($_POST[weight_per_issue])."',`cover_pic`='images/magazines/".@SecureSQL($_POST[files])."',`description`='".@SecureSQL($_POST[description])."',`issue_per_year` = '$issue_per_year' WHERE `mag_id` =".$_GET['ID'].";");
$db->close();
header("location: addMagazines.php");
?>