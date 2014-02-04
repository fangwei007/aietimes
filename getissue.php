<?php
/*
This file is for the AJAX function of receive.php
*/
include("DatabaseClass.php");
$db->connect();
$y=$_GET["y"];
$m=$_GET["m"];
$w=$_GET["w"];
$n=$_GET["n"];//mag_name
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
echo "<table border='1' cellspacing='0' class='tt'>
	<thead>
		<tr>
			<th>Magazine ID</th>
			<th>Issue Week</th>
			<th>Issue Month</th>
			<th>Issue Year</th>
			<th>Status</th>
			<th>User ID</th>
		</tr>
	</thead>
    <tbody>";
while($row = mysql_fetch_array($result))
  {
  $count++;
  echo "<tr>";
  echo "<td>" . $row['mag_name'] . "</td>";
  echo "<td>" . $row['issue_week'] . "</td>";
  echo "<td>" . $row['issue_month'] . "</td>";
  echo "<td>" . $row['issue_year'] . "</td>";
  echo "<td>" . $row['status'] . "</td>";
  echo "<td>" . $row['user_id'] . "</td>";
  echo "</tr>";
  
if(isset($_POST['receive']))  echo $row['issue_id'];
  }      
echo "</tbody></table>";
echo "Count: ".$count."<br />";
$db->close();
?>