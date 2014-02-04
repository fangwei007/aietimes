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
			//echo $check_billing_info."<br/>";
			//echo $credicard_id."<br/>";
			
			//generatepayment					
			$generatePayment="INSERT INTO paymentinfo (creditcard_id,date,ordertype,status)
							VALUES
			('".$credicard_id."','".$today."','ShippingOrder','pending')";//pending -> charged 
			mysql_query($generatePayment);
			$get_paymentID=mysql_insert_id();//return the last inserted ID
			//echo $generatePayment."<br/>";
			//echo $get_paymentID."<br/>";
			
			//get the address_id
			$check_shipping_address="SELECT `default_addressid` FROM `useraccount` WHERE `user_id`='".$row_1['user_id']."'";
			$addr=mysql_fetch_row(mysql_query($check_shipping_address));
			$default_addressid=$addr[0];			
			
		    $create_shipping_order="INSERT INTO `shipping_order` (`payment_id`,`address_id`,`user_id`,`status`)
			VALUES('".$get_paymentID."','".$default_addressid."','".$row_1['user_id']."','pending')";
			mysql_query($create_shipping_order);
			$get_shippingorder_id=mysql_insert_id();
			
			$create_shippingorder_issue="INSERT INTO `shippingorder_issue` (`shipping_order_id`,`issue_id`,`quantity`)
			VALUES('".$get_shippingorder_id."','".$row_1['issue_id']."','".$row_1['quantity']."')";
			mysql_query($create_shippingorder_issue);	
			mysql_query("UPDATE `issueinfo` SET `status` = 'OnShippingOrder' WHERE `issue_id` = '".$row_1['issue_id']."';");
			}
			else{			
			//the 'order exist' condition. and this issue of course belongs to this shippingorder
			$insert_shippingorder_issue="INSERT INTO `shippingorder_issue` (`shipping_order_id`,`issue_id`,`quantity`)
			VALUES('".$get_shippingorder_id."','".$row_1['issue_id']."','".$row_1['quantity']."')";
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
		 	$insert_shippingorder_issue="INSERT INTO `shippingorder_issue` (`shipping_order_id`,`issue_id`,`quantity`)
			VALUES('".$shippingorder_id."','".$row_1['issue_id']."','".$row_1['quantity']."')";
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
			$oneweek=date("Y-m-d",strtotime($today."+1 Week"));//the date of next week
			$twoweek=date("Y-m-d",strtotime($today."+2 Week"));//the date of two weeks later
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

//echo $sql."<br/>";

while($row=mysql_fetch_array($result)){
if($user_id!==$row['user_id']){
	//echo $user_id=$row['user_id'];
	if($row['shipping_period']==1){
     	//echo "<br />------------------------------------------<br />creat shipping_order=1<br />";
		$re1=createshippingorder($row['order_mag_id'],$oneweek,$today);
		//echo "shippingorder_id".$re1[0];
		$flag=1;
		}
	elseif($row['shipping_period']==2){
		//echo "<br />------------------------------------------<br />creat shipping_order=2<br />";
		$re2=createshippingorder($row['order_mag_id'],$twoweek,$today);
		$flag=2;
		}
	elseif($row['shipping_period']==3){
		//echo "<br />------------------------------------------<br />creat shipping_order=2<br />";
		$re2=createshippingorder($row['order_mag_id'],$onemonth,$today);
		$flag=3;
		}
	elseif($row['shipping_period']==4){
		//echo "<br />------------------------------------------<br />creat shipping_order=2<br />";
		$re2=createshippingorder($row['order_mag_id'],$twomonth,$today);
		$flag=4;
		}
	elseif($row['shipping_period']==5){
		//echo "<br />------------------------------------------<br />creat shipping_order=3<br />";
		$re3=createshippingorder($row['order_mag_id'],$threemonth,$today);
		$flag=5;
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
			insertToShippingOrder($row['order_mag_id'],$re1,$oneweek);
			}
		 else{ 
			 $re1=createshippingorder($row['order_mag_id'],$oneweek,$today);
			 $flag=1;
			 }
		}
	elseif($row['shipping_period']==2){
				if($flag==2){
					
					$row['order_mag_id'];
					insertToShippingOrder($row['order_mag_id'],$re2,$twoweek);
			}
		 else{
			 $re2=createshippingorder($row['order_mag_id'],$twoweek,$today);
			 $flag=2;	
			 }		
		}
	elseif($row['shipping_period']==3){
				if($flag==3){
					
					$row['order_mag_id'];
					insertToShippingOrder($row['order_mag_id'],$re2,$onemonth);
			}
		 else{
			 $re2=createshippingorder($row['order_mag_id'],$onemonth,$today);
			 $flag=3;	
			 }		
		}
	elseif($row['shipping_period']==4){
				if($flag==4){
					
					$row['order_mag_id'];
					insertToShippingOrder($row['order_mag_id'],$re2,$twomonth);
			}
		 else{
			 $re2=createshippingorder($row['order_mag_id'],$twomonth,$today);
			 $flag=4;	
			 }		
		}
	elseif($row['shipping_period']==5){
			if($flag==5){
				$row['order_mag_id'];
				insertToShippingOrder($row['order_mag_id'],$re3,$threemonth);
			}
		 else{
			 $re3=createshippingorder($row['order_mag_id'],$threemonth,$today);
			 $flag=5;		
			 }		
		}
	else{}
}
}
/*
//merge shipping orders which will ship lasr isuue in same week
$sql_find_user="SELECT DISTINCT `user_id` FROM `shipping_order`";
//echo $sql_find_user;
$result_user=mysql_query($sql_find_user);
while($row=mysql_fetch_array($result_user)){
	$max_date_issue="SELECT  `shipping_order`.`user_id` ,  `shipping_order`.`shipping_order_id` ,  `shipping_order`.`shipping_weight` ,  `shipping_order`.`payment_id` ,  `issue_date` 
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
	$total_weight=0;
	while($row2=mysql_fetch_array($max_date_issue_result)){
		if($tmp_shipping_order_id!=0)
		{
			if($row2['issue_date']==$tmp_issue_date)
			{
				//update shippingorder_issue
				$update_shippingorder_issue="UPDATE `shippingorder_issue` SET `shipping_order_id`='".$tmp_shipping_order_id."' 
											 WHERE `shipping_order_id`='".$row2['shipping_order_id']."'";
				//echo $update_shippingorder_issue;
				//mysql_query($update_shippingorder_issue);
				 
				$total_weight=$tmp_shipping_weight+$row2['shipping_weight'];
				//echo $total_weight;
				//calculate a new shipping price
				
				//update new shipping price
				
				//update shipping_order
				$update_shipping_order="UPDATE `shipping_order` SET `shipping_weight`='".$total_weight."' 
											 WHERE `shipping_order_id`='".$tmp_shipping_order_id."'";
				//echo $update_shipping_order;
				mysql_query($update_shipping_order);
				$delete_old_shipping_order="DELETE FROM `shipping_order` WHERE `shipping_order_id`='".$row2['shipping_order_id']."'";
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
				
				//update paymentID in the new shipping order
				$update_shipping_order="UPDATE `shipping_order` SET `payment_id`='".$get_paymentID."' 
											 WHERE `shipping_order_id`='".$tmp_shipping_order_id."'";
				//echo $update_shipping_order;
				mysql_query($update_shipping_order);
			}
		}
		$tmp_shipping_order_id=$row2['shipping_order_id'];
		$tmp_shipping_weight=$row2['shipping_weight'];
		$tmp_payment_id=$row2['payment_id'];
		$tmp_issue_date=$row2['issue_date'];
	}
}*/

shippingfeegenerator();


$db->close();
header("location: ShippingOrdermerge.php");
?>