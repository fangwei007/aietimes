<?php

include("DatabaseClass.php");
$db->connect();

function magazine_price($mag_id,$numer_of_issue){
	$price=mysql_query("SELECT price_per_year, issue_per_year FROM magazineinfo WHERE mag_id = '".$mag_id."'");
	$row = mysql_fetch_array($price);		
	$price_per_issue = $row[0]/$row[1];	
	$amount=$price_per_issue*$numer_of_issue;
	return $amount;
	}	
echo magazine_price(28,12);


?>