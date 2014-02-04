<?php
/*
This file is for receive.php to update the status of issues from 'waiting' to 'received' according to the conditions user typed in
*/
include("DatabaseClass.php");
$db->connect();
$y=$_POST["year"];
$m=$_POST["month"];
$w=$_POST["week"];
$n=$_POST["name"];//mag_name
$date=getdate();
$today=$date['year'].'-'.$date['mon'].'-'.$date['mday'];

if(isset($_POST['amount'])){ 
$received_amount=$_POST['amount'];
$flag=0;
$sql="SELECT * FROM issueinfo, magazineinfo WHERE `issueinfo`.`mag_id`=`magazineinfo`.`mag_id` AND `issueinfo`.`status`='OnShippingOrder' AND ";
if($m!=NULL){
	if($flag==1)$sql.=" AND ";
$sql.="issue_month = '".$m."' ";
$flag=1;
}
if($y!=NULL){
	if($flag==1)$sql.=" AND ";
$sql.="issue_year = '".$y."' ";
$flag=1;
}
if($w!=NULL){
	if($flag==1)$sql.=" AND ";
$sql.="issue_week = '".$w."' ";
$flag=1;
}
if($n!=""){
	if($flag==1)$sql.=" AND ";
  	$sql.="mag_name like '%".$n."%'";
  	$flag=1;
}
$count=0;
//echo $sql;
$result = mysql_query($sql);

for($i=0;$i<$received_amount;$i++)
  {
  $row = mysql_fetch_array($result);
  $update="UPDATE issueinfo SET `received_date` = '".$today."',`issueinfo`.`status`='received' WHERE `issueinfo`.`issue_id`='".$row['issue_id']."'";
  
  //create shipping order for those "ASAP mag_order issue"
  
  
  
  mysql_query($update);
  }
}
$db->close();
header("location: receive.php");
?>