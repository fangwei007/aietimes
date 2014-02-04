<?php
// http://en.wikipedia.org/wiki/Cron
//use Cron to run this php file every 24 hours.
//use the following link to learn more about Cron
//http://www.phpro.org/tutorials/Run-Script-From-Cron.html
function shippingFeecalCulator($totalweight){
	return $totalweight*20; 
}
function createshippingorder($order_mag_id,$month,$today){//$order_mag_id=$row['order_mag_id']
			$sql_1="SELECT * 
			FROM `issueinfo`,`order_magazineinfo`,`magazineinfo` 
			WHERE `issueinfo`.`order_mag_id`=`order_magazineinfo`.`order_mag_id`
			 AND `order_magazineinfo`.`mag_id`=`magazineinfo`.`mag_id`
			 AND `order_magazineinfo`.`order_mag_id`='".$order_mag_id."'
			 AND `issue_date`<'".$month."'
			 AND `issueinfo`.`status`='waiting'"; //find out all the issues that need to be shipped
			 $result_1=mysql_query($sql_1);
			 
			 $flag=0;//fetch first issue->create shipping order;fetch the rest issues->insert into current order;
			 // flag : shippingorder exsist:1; otherwise:0   
	
			while($row_1=mysql_fetch_array($result_1)){
		    //$user_id=$row_1['user_id'];
			if($flag==0){//fetch the first issues
			$flag=1;
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
			}
			else{			
			//the 'order exist' condition. and this issue of course belongs to this shippingorder
			$insert_shippingorder_issue="INSERT INTO `shippingorder_issue` (`shipping_order_id`,`issue_id`)
			VALUES('".$get_shippingorder_id."','".$row_1['issue_id']."')";
			mysql_query($insert_shippingorder_issue);	
			mysql_query("UPDATE `issueinfo` SET `status` = 'OnShippingOrder' WHERE `issue_id` = '".$row_1['issue_id']."';");
			}
		}
			return $get_shippingorder_id;
}
//function- insert issues into a ShippingOrder
function insertToShippingOrder($order_mag_id,$shippingorder_id,$month){
			$sql_1="SELECT * 
			FROM `issueinfo`,`order_magazineinfo`,`magazineinfo` 
			WHERE `issueinfo`.`order_mag_id`=`order_magazineinfo`.`order_mag_id`
			 AND `order_magazineinfo`.`mag_id`=`magazineinfo`.`mag_id`
			 AND `order_magazineinfo`.`order_mag_id`='".$order_mag_id."'
			 AND `issue_date`<'".$month."'
			 AND `issueinfo`.`status`='waiting'"; //find out all the issues that need to be shipped
			 $result_1=mysql_query($sql_1);
			 
	 while($row_1=mysql_fetch_array($result_1)){
		 	$insert_shippingorder_issue="INSERT INTO `shippingorder_issue` (`shipping_order_id`,`issue_id`)
			VALUES('".$shippingorder_id."','".$row_1['issue_id']."')";
			mysql_query($insert_shippingorder_issue);	
			mysql_query("UPDATE `issueinfo` SET `status` = 'OnShippingOrder' WHERE `issue_id` = '".$row_1['issue_id']."';");
	 }
	 return 0;
}
	//generate shipping cost for those new order(price=0)
function shippingfeegenerator(){
	$GetNewShippingOrder="SELECT * FROM shipping_order WHERE shipping_price=0";
	$result=mysql_query($GetNewShippingOrder);
	
	while($row=mysql_fetch_array($result)){
		$getWeight="SELECT SUM(weight_per_issue) 
		FROM `shippingorder_issue`,`issueinfo`,`magazineinfo`
		WHERE `shippingorder_issue`.`shipping_order_id`=".$row['shipping_order_id']."
		AND `shippingorder_issue`.`issue_id`=`issueinfo`.`issue_id`
		AND `issueinfo`.`mag_id`=`magazineinfo`.`mag_id`";	
		$result1=mysql_query($getWeight);
		$SUMweight=mysql_fetch_array($result1);
		$Price=shippingFeecalCulator($SUMweight[0]);
		mysql_query("UPDATE `shipping_order` SET `shipping_weight` = '".$SUMweight[0]."',
`shipping_price` = '".$Price."' WHERE `shipping_order`.`shipping_order_id` =".$row['shipping_order_id']);	
		mysql_query("UPDATE `paymentinfo` SET `amount` = '".$Price."' WHERE `paymentinfo`.`payment_id` =".$row['payment_id']);	
	}
}


include("DatabaseClass.php");
$db->connect();
			$date=getdate();
			$today=$date['year'].'-'.$date['mon'].'-'.$date['mday'];//change format
			$onemonth=date("Y-m-d",strtotime($today."+1 Month"));//the date of next month
			$twomonth=date("Y-m-d",strtotime($today."+2 Month"));//the date of two months later
			$threemonth=date("Y-m-d",strtotime($today."+3 Month"));//the date of three months later
			
			$year = date("o",strtotime($today));
			$month = date("m",strtotime($today));
//1. run every day to create a shipping order if status meets the conditions
//magazines have the same shipping order
$user_id="";
$flag=0;
$re1="";$re2="";$re3="";
$sql="SELECT `issueinfo`.`user_id`,`order_magazineinfo`.`order_mag_id`,`shipping_period` FROM `order_magazineinfo`,`issueinfo` 
WHERE `issueinfo`.`order_mag_id`=`order_magazineinfo`.`order_mag_id`
AND `issue_date`='".$today."' 
AND `status`='waiting'
ORDER BY `user_id`,`shipping_period`";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)){
if($user_id!==$row['user_id']){
	echo $user_id=$row['user_id'];
	if($row['shipping_period']==1){
     	//echo "<br />------------------------------------------<br />creat shipping_order=1<br />";
		$re1=createshippingorder($row['order_mag_id'],$onemonth,$today);
		//echo "shippingorder_id".$re1[0];
		$flag=1;
		}
	elseif($row['shipping_period']==2){
		//echo "<br />------------------------------------------<br />creat shipping_order=2<br />";
		$re2=createshippingorder($row['order_mag_id'],$twomonth,$today);
		$flag=2;
		}
	elseif($row['shipping_period']==3){
		//echo "<br />------------------------------------------<br />creat shipping_order=3<br />";
		$re3=createshippingorder($row['order_mag_id'],$threemonth,$today);
		$flag=3;
		}
	else{}
	}
else{//$user_id==$row['user_id'] : this order_mag_id belongs to the previous client (check if timespan is the same, if so, insert every issues, otherwise, create another shipping order)
	if($row['shipping_period']==1){
		if($flag==1){
			//echo "insert different mag into current shipping order SP1<br />";
			//echo $flag."<br />";
			//echo "shippingorder_id".$re1[0];
		 	
			//select all issues belongs to this mag_order 			
			$row['order_mag_id'];
			insertToShippingOrder($row['order_mag_id'],$re1,$onemonth);
			}
		 else{ 
			 $re1=createshippingorder($row['order_mag_id'],$onemonth,$today);
			 $flag=1;
			 }
		}
	elseif($row['shipping_period']==2){
				if($flag==2){
					
					$row['order_mag_id'];
					insertToShippingOrder($row['order_mag_id'],$re2,$twomonth);
			}
		 else{
			 $re2=createshippingorder($row['order_mag_id'],$twomonth,$today);
			 $flag=2;	
			 }		
		}
	elseif($row['shipping_period']==3){
			if($flag==3){
				$row['order_mag_id'];
				insertToShippingOrder($row['order_mag_id'],$re3,$threemonth);
			}
		 else{
			 $re3=createshippingorder($row['order_mag_id'],$threemonth,$today);
			 $flag=3;		
			 }		
		}
	else{}
}
}
shippingfeegenerator();


$db->close();
header("location: chargeShipping.php");
?>