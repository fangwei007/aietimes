<?php
include("DatabaseClass.php");
$db->connect();
$query_result = mysql_query("SELECT * FROM `order` WHERE `order_id` = '".$_GET['ID']."';");
$row = mysql_fetch_array($query_result);
$user_id=$row['user_id'];
$temp=$row['payment_id'];
mysql_query("UPDATE `paymentinfo` SET `status` = 'charged' WHERE `payment_id` = '".$temp."';");
mysql_query("UPDATE `order` SET `status` = 'Subscribed' WHERE `order_id` = '".$_GET['ID']."';");
//find our every issue these order related to and then insert issue info into table-issue,info
$query_result2 = mysql_query("SELECT *
FROM magazineinfo, order_magazineinfo
WHERE magazineinfo.mag_id = order_magazineinfo.mag_id
AND order_magazineinfo.order_id =  '".$_GET['ID']."'");
//echo $query_result2;

while($row2 = mysql_fetch_array($query_result2)){
	if($row2['issue_per_month']==="1"){
		if($row2['order_period']=="1 issue")
			$month_count=1;
		else if($row2['order_period']=="3 months")
			$month_count=3;
		else if($row2['order_period']=="6 months")
			$month_count=6;
		else if($row2['order_period']=="9 months")
			$month_count=9;
		else if($row2['order_period']=="12 months")
			$month_count=12;
		//echo $sday=$row2['subscribe_date']."<br />"; //this format is Date, to use the following for loop, string type is needed.
		$saday=date("o",strtotime($sday=$row2['subscribe_date']))."-".date("m",strtotime($sday=$row2['subscribe_date']))."-01";
		for($i=1;$i<=$month_count;$i++){//$i=1 means that your subscribe will start next month.!!!!!!!!!
				$a=date("Y-m-d",strtotime($sday."+".$i." Month"));
				$year = date("o",strtotime($a));
				$month = date("m",strtotime($a));
				$num=4;//number of issue for every month. 4 means weekly magazines;
			//for($j=1;$j<=$num;$j++){ 
			  $issuedate=$year.'-'.$month.'-01';
			  //for($k=0;$k<$row2['quantity'];$k++)
			  //{
              	$insertIssues="INSERT INTO `issueinfo` ( `order_mag_id`,`mag_id` ,`user_id`, `issue_week` , `issue_month` , `issue_year`, `issue_date`,`status` )VALUES ('".$row2['order_mag_id']."','".$row2['mag_id']."','".$user_id."','".$j."','".$month."','".$year."','".$issuedate."','waiting')";
			  //echo $insertIssues;
				mysql_query($insertIssues);
			  //}
			//}
		}
	}

	else if($row2['issue_per_month']==="4"){
		
		$flag_1_issue=0;
		
		if($row2['order_period']=="1 issue")
		{
			$month_count=1;
			$flag_1_issue=1;
		}
		else if($row2['order_period']=="3 months")
			$month_count=3;
		else if($row2['order_period']=="6 months")
			$month_count=6;
		else if($row2['order_period']=="9 months")
			$month_count=9;
		else if($row2['order_period']=="12 months")
			$month_count=12;
		//echo $sday=$row2['subscribe_date']."<br />"; //this format is Date, to use the following for loop, string type is needed.
		$saday=date("o",strtotime($sday=$row2['subscribe_date']))."-".date("m",strtotime($sday=$row2['subscribe_date']))."-01";
		for($i=1;$i<=$month_count;$i++){//$i=1 means that your subscribe will start next month.!!!!!!!!!
				$a=date("Y-m-d",strtotime($sday."+".$i." Month"));
				$year = date("o",strtotime($a));
				$month = date("m",strtotime($a));
				if($flag_1_issue==1)
					$num=1;
				else
					$num=4;//number of issue for every month. 4 means weekly magazines;
			for($j=1,$day=1;$j<=$num;$j++,$day+=7){ 
			  $issuedate=$year.'-'.$month.'-'.$day;
			 //for($k=0;$k<$row2['quantity'];$k++)
			  //{
              $insertIssues="INSERT INTO `issueinfo` ( `order_mag_id`,`mag_id` ,`user_id`, `issue_week` , `issue_month` , `issue_year`, `issue_date`,`status` )VALUES ('".$row2['order_mag_id']."','".$row2['mag_id']."','".$user_id."','".$j."','".$month."','".$year."','".$issuedate."','waiting')";
			  //echo $insertIssues;
			  mysql_query($insertIssues);
			  //}
			}
		}
	}
}
$db->close();
header("location: orderprocess.php");
?>