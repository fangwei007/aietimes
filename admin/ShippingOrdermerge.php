<?php

function shippingFeecalCulator($totalweight){
	return $totalweight*20; 
}

//generate shipping cost for those new order(price=0)
function shippingfeegenerator(){
	$GetNewShippingOrder="SELECT * FROM shipping_order WHERE shipping_price=0";
	//$GetNewShippingOrder="SELECT * FROM shipping_order";
	$result=mysql_query($GetNewShippingOrder);
	
	while($row=mysql_fetch_array($result)){
		$getWeight="SELECT `weight_per_issue`, `quantity` 
		FROM `shippingorder_issue`,`issueinfo`,`magazineinfo`
		WHERE `shippingorder_issue`.`shipping_order_id`=".$row['shipping_order_id']."
		AND `shippingorder_issue`.`issue_id`=`issueinfo`.`issue_id`
		AND `issueinfo`.`mag_id`=`magazineinfo`.`mag_id`";	
		$result1=mysql_query($getWeight);
		$sum_weight=0;
		while($row_weight=mysql_fetch_array($result1))
		{
			$weight=$row_weight['weight_per_issue']*$row_weight['quantity'];
			$sum_weight+=$weight;
		}
		//$sum_weight=118;
		echo $sum_weight."<br/>";
		$ceil_weight=ceil($sum_weight);
		echo $ceil_weight."<br/>";
		
		$get_per_lbs_price="SELECT  `DHL_cost_std` 
							FROM  `shipping_order` ,  `shippingaddress` ,  `countries` ,  `pricelist` 
							WHERE  `shipping_order`.`user_id` =  `shippingaddress`.`user_id` 
							AND  `countries`.`Name` =  `shippingaddress`.`country` 
							AND  `countries`.`DHL_continents_id` =  `pricelist`.`continents_id` 
							AND  `shipping_order`.`shipping_order_id` =  '".$row['shipping_order_id']."'
							AND  `pricelist`.`weight_lbs` =  '+ 1 lbs'
							LIMIT 0 , 30";
		$result_per_lbs=mysql_query($get_per_lbs_price);
		$row_per_lbs=mysql_fetch_array($result_per_lbs);
		$per_lbs_prics=$row_per_lbs['DHL_cost_std'];
		echo $per_lbs_prics."<br/>";
		
		if(($ceil_weight>=1&&$ceil_weight<=20)||$ceil_weight==30||$ceil_weight==40||$ceil_weight==50||$ceil_weight==60||$ceil_weight==70||$ceil_weight==80||$ceil_weight==90||$ceil_weight==100)
		{
			$get_price="SELECT  `DHL_cost_std` 
							FROM  `shipping_order` ,  `shippingaddress` ,  `countries` ,  `pricelist` 
							WHERE  `shipping_order`.`user_id` =  `shippingaddress`.`user_id` 
							AND  `countries`.`Name` =  `shippingaddress`.`country` 
							AND  `countries`.`DHL_continents_id` =  `pricelist`.`continents_id` 
							AND  `shipping_order`.`shipping_order_id` =  '".$row['shipping_order_id']."'
							AND  `pricelist`.`weight_lbs` =  '".$ceil_weight."'
							LIMIT 0 , 30";
			$result_price=mysql_query($get_price);
			$row_price=mysql_fetch_array($result_price);
			$Price=$row_price['DHL_cost_std'];
		}
		
		else if($ceil_weight<100)
		{
			$tmp_weight=floor($ceil_weight/10)*10;
			echo $tmp_weight."<br/>";
			$plus=$ceil_weight-$tmp_weight;
			$get_price="SELECT  `DHL_cost_std` 
						FROM  `shipping_order` ,  `shippingaddress` ,  `countries` ,  `pricelist` 
						WHERE  `shipping_order`.`user_id` =  `shippingaddress`.`user_id` 
						AND  `countries`.`Name` =  `shippingaddress`.`country` 
						AND  `countries`.`DHL_continents_id` =  `pricelist`.`continents_id` 
						AND  `shipping_order`.`shipping_order_id` =  '".$row['shipping_order_id']."'
						AND  `pricelist`.`weight_lbs` =  '".$tmp_weight."'
						LIMIT 0 , 30";
			$result_price=mysql_query($get_price);
			$row_price=mysql_fetch_array($result_price);
			$Price=$row_price['DHL_cost_std'];
			$Price+=($plus*$per_lbs_prics);
		}
		else
		{
			$plus=$ceil_weight-100;
			$get_price="SELECT  `DHL_cost_std` 
						FROM  `shipping_order` ,  `shippingaddress` ,  `countries` ,  `pricelist` 
						WHERE  `shipping_order`.`user_id` =  `shippingaddress`.`user_id` 
						AND  `countries`.`Name` =  `shippingaddress`.`country` 
						AND  `countries`.`DHL_continents_id` =  `pricelist`.`continents_id` 
						AND  `shipping_order`.`shipping_order_id` =  '".$row['shipping_order_id']."'
						AND  `pricelist`.`weight_lbs` =  '100'
						LIMIT 0 , 30";
			$result_price=mysql_query($get_price);
			$row_price=mysql_fetch_array($result_price);
			$Price=$row_price['DHL_cost_std'];
			$Price+=($plus*$per_lbs_prics);
		}
		
		echo $Price;
		
		//$Price=shippingFeecalCulator($sum_weight);
		mysql_query("UPDATE `shipping_order` SET `shipping_weight` = '".$sum_weight."',
`shipping_price` = '".$Price."' WHERE `shipping_order`.`shipping_order_id` =".$row['shipping_order_id']);	
		mysql_query("UPDATE `paymentinfo` SET `amount` = '".$Price."' WHERE `paymentinfo`.`payment_id` =".$row['payment_id']);	
	}
}


include("DatabaseClass.php");
$db->connect();
$date=getdate();
$today=$date['year'].'-'.$date['mon'].'-'.$date['mday'];//change format

//merge shipping orders which will ship lasr isuue in same week
$sql_find_user="SELECT DISTINCT `user_id` FROM `shipping_order`";
//echo $sql_find_user;
$result_user=mysql_query($sql_find_user);
while($row=mysql_fetch_array($result_user)){
	$max_date_issue="SELECT  DISTINCT `shipping_order`.`user_id` ,  `shipping_order`.`shipping_order_id` ,  `shipping_order`.`shipping_weight` ,  `shipping_order`.`payment_id` ,  `issue_date` 
					 FROM  `shipping_order` ,  `shippingorder_issue` A_s,  `issueinfo` A_i
 					 WHERE  `shipping_order`.`user_id` =  '".$row['user_id']."'
					 AND  `shipping_order`.`shipping_order_id` = A_s.`shipping_order_id` 
					 AND A_s.`issue_id` = A_i.`issue_id` 
 					 AND A_i.`issue_date` = ( 
					 SELECT MAX( B_i.`issue_date` ) 
					 FROM  `shippingorder_issue` B_s,  `issueinfo` B_i
					 WHERE B_s.`issue_id` = B_i.`issue_id` 
					 AND B_s.`shipping_order_id` = A_s.`shipping_order_id` ) 
					 ORDER BY  `issue_date` DESC";
	//echo $max_date_issue."<br/>";
	$max_date_issue_result=mysql_query($max_date_issue);
	
	$tmp_shipping_order_id=0;
	$tmp_shipping_weight=0;
	$tmp_payment_id=0;
	$tmp_issue_date;
	//$total_weight=0;
	$flag=0;
	while($row2=mysql_fetch_array($max_date_issue_result)){
		if($tmp_shipping_order_id!=0)
		{
			if($row2['issue_date']==$tmp_issue_date)
			{
				$flag=1;
				//update shippingorder_issue
				$update_shippingorder_issue="UPDATE `shippingorder_issue` SET `shipping_order_id`='".$tmp_shipping_order_id."' 
											 WHERE `shipping_order_id`='".$row2['shipping_order_id']."'";
				//echo $update_shippingorder_issue;
				mysql_query($update_shippingorder_issue);
				 
				$tmp_shipping_weight+=$row2['shipping_weight'];
				//echo $total_weight;
				//calculate a new shipping price
				
				//update new shipping price
				
				//update shipping_order
				$update_shipping_order="UPDATE `shipping_order` SET `shipping_weight`='".$tmp_shipping_weight."' 
											 WHERE `shipping_order_id`='".$tmp_shipping_order_id."'";
				//echo $update_shipping_order;
				mysql_query($update_shipping_order);
				$delete_old_shipping_order="DELETE FROM `shipping_order` WHERE `shipping_order_id`='".$row2['shipping_order_id']."'";
				echo $delete_old_shipping_order;
				mysql_query($delete_old_shipping_order);
				
				//delete old payment info
				$delete_old_payment_info="DELETE FROM `paymentinfo` WHERE `payment_id`='".$row2['payment_id']."'";
				//echo $delete_old_payment_info;
				mysql_query($delete_old_payment_info);
				$delete_old_payment_info="DELETE FROM `paymentinfo` WHERE `payment_id`='".$tmp_payment_id."'";
				//echo $delete_old_payment_info;
				mysql_query($delete_old_payment_info);
				
				//insert new payment info
				$check_billing_info="SELECT * FROM `creditcardinfo` WHERE `user_id`='".$row['user_id']."'";
				$r=mysql_fetch_row(mysql_query($check_billing_info));
				$credicard_id=$r[0];
				
				$generatePayment="INSERT INTO paymentinfo (creditcard_id,date,ordertype,status)
								VALUES
				('".$credicard_id."','".$today."','ShippingOrder','pending')";//pending -> charged 
				mysql_query($generatePayment);
				$get_paymentID=mysql_insert_id();//return the last inserted ID
				$tmp_payment_id=$get_paymentID;
				
				//update paymentID in the new shipping order
				$update_shipping_order="UPDATE `shipping_order` SET `payment_id`='".$get_paymentID."' 
											 WHERE `shipping_order_id`='".$tmp_shipping_order_id."'";
				//echo $update_shipping_order;
				mysql_query($update_shipping_order);
			}
		}
		if($flag==0)
		{
			$tmp_shipping_order_id=$row2['shipping_order_id'];
			$tmp_shipping_weight=$row2['shipping_weight'];
			$tmp_payment_id=$row2['payment_id'];
			$tmp_issue_date=$row2['issue_date'];
		}
		$flag=0;
	}
}

shippingfeegenerator();

$db->close();
header("location: chargeShipping.php");
?>