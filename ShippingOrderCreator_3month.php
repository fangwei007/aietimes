<?php
// http://en.wikipedia.org/wiki/Cron
//use Cron to run this php file every 24 hours.
//use the following link to learn more about Cron
//http://www.phpro.org/tutorials/Run-Script-From-Cron.html
function shippingFeecalCulator($totalweight){
	return $totalweight*20; 
}

include("DatabaseClass.php");
$db->connect();
			$date=getdate();
			$today=$date['year'].'-'.$date['mon'].'-'.$date['mday'];
			//$a=date("Y-m-d",strtotime($today."+1 Month"));//the next month
			$year = date("o",strtotime($today));
			$month = date("m",strtotime($today));
//1. run every day to create a shipping order if status meets the conditions
$user_id="";
//---shipping period = '3'-----------------------------------------------------------------------------------------
if(1){
	//~~~~~~~~~~~~~~~~~~~~~~~~
	//`shipping_period`='1' is the key for choose shipping period
	//build an other function to deal with `shipping_period`='3' and `shipping_period`='0.25'
	//~~~~~~~~~~~~~~~~~~~~~~~

//-----shipping period = '3' (default)-------------------------------------------------------------------------------------------
	$sql_1="SELECT * 
			FROM `issueinfo`,`order_magazineinfo`,`magazineinfo` 
			WHERE `issueinfo`.`order_mag_id`=`order_magazineinfo`.`order_mag_id`
			 AND `order_magazineinfo`.`mag_id`=`magazineinfo`.`mag_id`
			 AND `order_magazineinfo`.`shipping_period`='3'
			 AND `issue_month`<='".$month."'
			 AND `issue_year`<='".$year."' 
			 AND `issueinfo`.`status`='waiting'
			 ORDER BY `user_id`"; //find out all the issues that need to be shipped, no matter whose issue it is.
	$result_1=mysql_query($sql_1);	
	while($row_1=mysql_fetch_array($result_1)){
		if($user_id!==$row_1['user_id']){
			//echo "create new shipping order<br />";
			//$get_shippingorder_id;
			$user_id=$row_1['user_id'];
			//get the credicard_id to generate payment
		
			$check_billing_info="SELECT * FROM `creditcardinfo` WHERE `user_id`='".$row_1['user_id']."'";
			$r=mysql_fetch_row(mysql_query($check_billing_info));
			$credicard_id=$r[0];
			
			//generatepayment					
			$generatePayment="INSERT INTO paymentinfo (creditcard_id,date,ordertype,status)
							VALUES
			('".$credicard_id."','".$today."','ShippingOrder','pending')";//pending -> charged 
			mysql_query($generatePayment);
			$get_paymentID=mysql_insert_id();//return the last inserted ID
			
			//get the address_id
			$check_shipping_address="SELECT `default_addressid` FROM `useraccount` WHERE `user_id`='".$row_1['user_id']."'";
			$addr=mysql_fetch_row(mysql_query($check_shipping_address));
			$default_addressid=$addr[0];			
			
		    $create_shipping_order="INSERT INTO `shipping_order` (`payment_id`,`address_id`,`user_id`,`status`)
			VALUES('".$get_paymentID."','".$default_addressid."','".$row_1['user_id']."','pending')";
			mysql_query($create_shipping_order);
			$get_shippingorder_id=mysql_insert_id();
			
			$create_shippingorder_issue="INSERT INTO `shippingorder_issue` (`shipping_order_id`,`issue_id`)
			VALUES('".$get_shippingorder_id."','".$row_1['issue_id']."')";
			mysql_query($create_shippingorder_issue);	
			
			mysql_query("UPDATE `issueinfo` SET `status` = 'OnShippingOrder' WHERE `issue_id` = '".$row_1['issue_id']."';");
			
			//echo "userID: ".$user_id."---".$row_1['issue_id']."<br />";
		}
		else{
			//echo "add to the previous shipping order";			
			$create_shippingorder_issue="INSERT INTO `shippingorder_issue` (`shipping_order_id`,`issue_id`)
			VALUES('".$get_shippingorder_id."','".$row_1['issue_id']."')";
			mysql_query($create_shippingorder_issue);			
			//echo "userID: ".$user_id."---".$row_1['issue_id']."<br />";
			mysql_query("UPDATE `issueinfo` SET `status` = 'OnShippingOrder' WHERE `issue_id` = '".$row_1['issue_id']."';");
			
			//$weight=$row_1['weight_per_issue']+$weight;
		}
	}
	//calculate shipping fee
	
	//"SELECT SUM() FROM";
}

$db->close();

header("location: chargeShipping.php");
?>