<?php
	session_start();
	if(!isset($_SESSION['user_id']))
	header("location: index.php");//For security, header the user back to login page if that user is not properly logged
?>
<!DOCTYPE html>
<html>
<head>
<link href="AIEtimeCSS.css" rel="stylesheet" type="text/css" />
<link href="form.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="table.css" type="text/css" media="print, projection, screen" />
<script type="text/javascript">
function showmenu(){
	document.getElementById("menuDIV").style.display="block";
}
function hidemenu(){
	document.getElementById("menuDIV").style.display="none";
}
</script>
</head>
<body class="bg">
<div id="menuDIV" onmouseover="showmenu()" onmouseout="hidemenu()">
      <a href="account.php"><div id="menulink">Account</div></a>
      <a href="Myorder.php"><div id="menulink">Order</div></a>
      <a href="shipping.php"><div id="menulink">Shipping</div></a>
</div>
<div id="banner">
<span style="color:#09F">A</span><span style="color:#F60">I</span><span style="color:#09F">E</span><span style="font-size:30px">TIMEs</span>
<span style="font-size:10px; color: #666; font-style:normal;">Subscribe Magazines from USA. No matter where you are in the world!</span>
<span style="margin-left:500px; ; font-size:14px;"> 
<?php
	//session_start();
	echo "<div style='position:absolute;right:60px;top:0px;width:300px;'>";
	if(isset($_SESSION['first_name'])) echo "Hi! ".$_SESSION['first_name']." ";
	if(isset($_SESSION['count']))
	$count=$_SESSION['count'];
	else $count=0;
	
	echo "<a href='shoppingcart.php' style='text-decoration: none;margin:5px;padding:5px;width:50px;color:white;background-color:#09F;'>Cart(".$count.") items</a>"." ";
	
if(isset($_SESSION['first_name']))
echo "<a href='logout.php' style='text-decoration: none;margin:5px;padding:5px;width:50px;color:white;background-color:#F60;'>log out</a>";
	echo "</div>";
?>
</span>
</div>
<div id="main">
<ul id="menu">
<li><a href="homepage.php">Home</a></li>
<li><a href="magazines.php">Magazines</a></li>
<li><a href="account.php" onmouseover="showmenu()" onmouseout="hidemenu()">My account</a></li>
<li><a href="about.php">About AIE</a></li>
</ul>
<div id="content">
<div id="content2" style="height:auto;">
<!-- Beginning of coding area-->
<h1>My Magazines</h1>
<table border='1' cellspacing='0' class='tt' >
	<thead align='center'>
		<tr>
        	<th>Order Mag ID</th>
			<th>Magazine</th>
			<th>Subscribe Date</th>
			<th>Number of Issues</th>
			<th>Shipping Period</th>
            <th>Weight/Issue</th>
            <th>Status</th>
		</tr>
	</thead>
    <tbody align='center'>
<?php
include("DatabaseClass.php");
$db->connect();
$query_result = mysql_query("SELECT *
FROM `magazineinfo`,`order_magazineinfo`,`order`
WHERE `magazineinfo`.`mag_id`=`order_magazineinfo`.`mag_id`
AND `order`.`order_id`=`order_magazineinfo`.`order_id`
AND `order_magazineinfo`.`mag_id`=`magazineinfo`.`mag_id`
AND `order`.`user_id`='".$_SESSION['user_id']."'
LIMIT 0 , 30");
while($row = mysql_fetch_array($query_result)){
	echo "<tr>";
	echo "<td>".$row['order_mag_id']."</td>";
	echo "<td><img src='".$row['cover_pic']."' width=\"30\" height=\"40\" /></td>";
	echo "<td>".$row['subscribe_date']."</td>";
	echo "<td>".$row['number_of_issue']."</td>";
	echo "<td style='background-color:#69F;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='ChangeShippingPeriod.php?ID=".$row['order_mag_id']."'>";
	if($row['order_period'] == "1 issue") echo "1 Issue";
	else{
	if($row['shipping_period']==1)echo "Weekly";
	else if($row['shipping_period']==2)echo "2 Weeks";
	else if($row['shipping_period']==3) echo "Monthly";
	else if($row['shipping_period']==4) echo "2 Months";
	else echo "3 Months";//shipping_period=3
	}
	//else echo"As Soon As Possible";
	
	
	echo "</td>";
	echo "<td>".$row['weight_per_issue']."</td>";
	echo "<td>".$row['status']."</td>";
	echo "</tr>";
}

?>
    </tbody>
</table>
<!--                                                     主体的calculator部分                                    -->

<?php
static $total_weight;
static $total_price;
static $estimate_date;	
static $shipping_date;
static $first_shipping_date;
static $flag = 1;
//static $today_time;
static $i = 7;
static $j = -8;

function est_date(){
global $flag;
global $i;
global $j;
global $shipping_date;
global $first_shipping_date;
$query_result1 = mysql_query("SELECT * FROM `order`WHERE `user_id`='".$_SESSION['user_id']."'");//找到第一个是pending的日期，然后作为estimate date的参照日期，这里应该是在shipping_order下的status
$num = mysql_num_rows($query_result1);
if($num == 0){
	 echo "unknown";
	 $shipping_date = date('Y-m-d',mktime (0,0,0,0,0,0));
	 $first_shipping_date = date('Y-m-d',mktime (0,0,0,0,0,0));
}
else{
$row = mysql_fetch_array($query_result1, MYSQL_ASSOC);
$olddate = explode("-",$row['start_date']);//如果要用mktime函数，则要用explode拆解日期。 
$today = date('Y-m-d');
if($flag != 1){
                $estimate_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+14+$i,$olddate[0])); 
                echo $estimate_date;
                $i+=7;
              }
else{
                $estimate_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+14,$olddate[0]));
				$estimate_date2 = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+21,$olddate[0]));
                for($i=7;$estimate_date2 <= $today;$i+=7) {
					$estimate_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+14+$i,$olddate[0]));
					$estimate_date2 = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+21+$i,$olddate[0]));
					}
                echo $estimate_date;
				$j = $j + $i;
				//echo $i;echo $j;
				$first_shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+14,$olddate[0]));
				$shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+$i+6,$olddate[0]));//目前感觉加这个没意义//位移问题已经解决
                $flag--;
    }
}
}


//--------------------------------------日期比较方法-----------------------------------------------------------------------------------------

    /* $date2="2007-1-20";   
    $date="2006-1-1";   
      
    if(date("Y-m-d",strtotime($date))>date("Y-m-d",strtotime($date2))){   
        echo "Y";   
    }   
    else{   
        echo "N";   
    }  */
//------------------------------------------------------------------------------------------------------------------------------------------	
/*--------------------------------------------------------------------
$olddate = '2012-06-20'; //如果要用mktime函数，则要用explode拆解日期。  
$oldtime = strtotime($olddate);  
$passtime = time()-$oldtime; //经过的时间戳。  
echo 'you spend'.floor($passtime/(24*60*60)).'day'.'<br />'; //12天*/
//---------------------------------------------------------------------
function cal_show($week, $flag_weight, $flag_price) {
	global $total_weight;
	global $total_price;
	global $shipping_date;
	global $first_shipping_date;
	global $i;
	//global $today_time;
	$total_price = 0;
	$total_weight = 0;
$query_result1 = mysql_query("SELECT * FROM `order`WHERE `user_id`='".$_SESSION['user_id']."'AND `start_date`<='".$shipping_date."'");//应该添加status = shipping//之前没有=
$num0 = mysql_num_rows($query_result1);
if($num0 == 0);
else{
//-----------------------------------主体计算部分--------------------------------------------------------------------------------------
while($row = mysql_fetch_array($query_result1, MYSQL_ASSOC)){
	//use $row['start_date']
	    $startdate = explode("-",$row['start_date']);//如果要用mktime函数，则要用explode拆解日期。 
        $start_shipping_date = date('Y-m-d',mktime (0,0,0,$startdate[1],$startdate[2]+14,$startdate[0])); //自定义的初始化shipping日期
		$startime = strtotime($start_shipping_date);
		$today = date('Y-m-d');
		//--------------------------------------------------------------------------------------------------------------
		$todaydate = explode("-",$today);
		$today2 = date('Y-m-d',mktime (0,0,0,$todaydate[1],$todaydate[2],$todaydate[0]));
		$query_result2 = mysql_query("SELECT * FROM `order`WHERE `user_id`='".$_SESSION['user_id']."'AND `start_date`<'".$today2."'");
        $num = mysql_num_rows($query_result2); 
		//--------------------------------------------------------------------------------------------------------------
		$today_time = strtotime($today) ;
		$passtime = $today_time - $startime;
		$n = floor($passtime/(24*60*60*7));
		$result = $n % 12;
		$magazineInfo = mysql_query("SELECT *
			FROM `order_magazineinfo`,`magazineinfo`
			WHERE `order_id`='".$row['order_id']."'
			AND `order_magazineinfo`.`mag_id`=`magazineinfo`.`mag_id`");
		while($list = mysql_fetch_array($magazineInfo, MYSQL_ASSOC)){
			$weight_per_issue=$list['weight_per_issue'];
			$number_of_issue=$list['number_of_issue'];
			$price=$list['price'];
	if($n <= 0) {//First shippment is still waiting.
			if($week == 0 && $num == 0) {//only the first order works
			    if($list['order_period'] == "1 issue") {//如果就one issue的话shipping_period不再起作用
					if($flag_weight == 0 && $flag_price == 0) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*".$list['quantity']."\n";
													 $total_weight += $weight_per_issue*$list['quantity'];
				}
				else{
				if($list['shipping_period'] == 1){
			 	if($flag_weight == 0 && $flag_price == 0)	echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*".$list['quantity']."\n";
			                                                $total_weight += $weight_per_issue*$list['quantity'];
															}
				}
			}
			else{	
			    if($shipping_date <= $list['end_date']){//用于限制订单寿命
			   
				$startime2 = strtotime($start_shipping_date);
				$preshippingdate = explode("-",$shipping_date);
				$pre_shipping_date = date('Y-m-d',mktime (0,0,0,$preshippingdate[1],$preshippingdate[2]+7,$preshippingdate[0]));
				$endtime = strtotime($pre_shipping_date);
		        $passtime2 = $endtime - $startime2;
	         	$m = floor($passtime2/(24*60*60*7));
		        $result2 = $m % 12;
			if($m<0);
			else{
				if($list['order_period'] == "1 issue") {//如果就one issue的话shipping_period不再起作用
					if($flag_weight == 0 && $flag_price == 0) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*".$list['quantity']."\n";
													 $total_weight += $weight_per_issue*$list['quantity'];
				}
				else{
				if($list['shipping_period'] == 1) {
					                                 if($flag_weight == 0 && $flag_price == 0) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*".$list['quantity']."\n";
													 $total_weight += $weight_per_issue*$list['quantity'];
				}
				if($result2== 3 || $result2 == 1 || $result2 ==5  || $result2 ==7  || $result2 == 9 || $result2 == 11 ){
					if($list['shipping_period'] == 2) {
						if($flag_weight == 0 && $flag_price == 0) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*2*".$list['quantity']."\n";
						$total_weight += $weight_per_issue*2*$list['quantity'];
						}
					else if (($result2 == 3 || $result2 ==7 || $result2 == 11) && $list['shipping_period'] == 3) {
						if($flag_weight == 0 && $flag_price == 0 && $list['issue_per_month']==4) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*4*".$list['quantity']."\n";
						if($flag_weight == 0 && $flag_price == 0 && $list['issue_per_month']==1) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*".$list['quantity']."\n";
						if ($list['issue_per_month']==4) $total_weight += $weight_per_issue*4*$list['quantity'];
						else $total_weight += $weight_per_issue*$list['quantity'];
						}
					else if($result2 == 7 && $list['shipping_period'] == 4) {
						if($flag_weight == 0 && $flag_price == 0 && $list['issue_per_month']==4) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*8*".$list['quantity']."\n";
						if($flag_weight == 0 && $flag_price == 0 && $list['issue_per_month']==1) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*2*".$list['quantity']."\n";
						if ($list['issue_per_month']==4) $total_weight += $weight_per_issue*8*$list['quantity'];
						else $total_weight += $weight_per_issue*2*$list['quantity'];
						}
					else if($result2 == 11 && $list['shipping_period'] == 5) {
						if($flag_weight == 0 && $flag_price == 0 && $list['issue_per_month']==4) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*12*".$list['quantity']."\n";
						if($flag_weight == 0 && $flag_price == 0 && $list['issue_per_month']==1) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*3*".$list['quantity']."\n";
						if ($list['issue_per_month']==4) $total_weight += $weight_per_issue*12*$list['quantity'];
						else $total_weight += $weight_per_issue*3*$list['quantity'];
						}
				   }            
			    }
			  }
			}
			}
			}
	else {  
		if($shipping_date <= $list['end_date']){
		$today_time2 = strtotime("+$i day") ;
		$passtime2 = $today_time2 - $startime;
		$p = floor($passtime2/(24*60*60*7));
		$result3 = $p % 12; 
		$result4 = $p % 8;
				if($list['shipping_period'] == 1) {
					                                 if($flag_weight == 0 && $flag_price == 0) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*".$list['quantity']."\n";
													 $total_weight += $weight_per_issue*$list['quantity'];
				}     
				if($result3 == 2 || $result3 == 0 || $result3 == 4 || $result3 == 6 || $result3 == 8 || $result3 == 10){
					if($list['shipping_period'] == 2) {
						if($flag_weight == 0 && $flag_price == 0) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*2*".$list['quantity']."\n";
						$total_weight += $weight_per_issue*2*$list['quantity'];
						}
					else if (($result3 == 0 || $result3 == 4 || $result3 == 8)&& $list['shipping_period'] == 3  ) {
						if($flag_weight == 0 && $flag_price == 0) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*4*".$list['quantity']."\n";
						$total_weight += $weight_per_issue*4*$list['quantity'];
						}
					else if (($result3 == 4 || $result3 == 8 || $result3 == 0) && $result4 == 0 && $list['shipping_period'] == 4  ) {
						if($flag_weight == 0 && $flag_price == 0) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*8*".$list['quantity']."\n";
						$total_weight += $weight_per_issue*8*$list['quantity'];
						}
					else if (($result3 == 0)&& $list['shipping_period'] == 5  ) {
						if($flag_weight == 0 && $flag_price == 0) echo "<img src='".$list['cover_pic']."' width=\"45\" height=\"60\" />*12*".$list['quantity']."\n";
						$total_weight += $weight_per_issue*12*$list['quantity'];
						}
				}
			}	
		}
	}	
}
	if($flag_weight == 1) echo round($total_weight, 2);
	$ceil_weight=ceil($total_weight);
	//if($flag_price == 1) echo round($total_price,2);
	$get_per_lbs_price="SELECT  `DHL_cost_std` 
							FROM  `useraccount` ,  `shippingaddress` ,  `countries` ,  `pricelist` 
							WHERE  `useraccount`.`user_id` =  '".$_SESSION['user_id']."'
							AND `useraccount`.`default_addressid` = `shippingaddress`.`address_id` 
							AND  `countries`.`Name` =  `shippingaddress`.`country` 
							AND  `countries`.`DHL_continents_id` =  `pricelist`.`continents_id` 
							AND  `pricelist`.`weight_lbs` =  '+ 1 lbs'
							LIMIT 0 , 30";
	$result_per_lbs = mysql_query($get_per_lbs_price);
	$row_per_lbs = mysql_fetch_array($result_per_lbs);
	$per_lbs_prics = $row_per_lbs['DHL_cost_std'];
	//$total_price = $ceil_weight * $per_lbs_prics;
	if(($ceil_weight>=1&&$ceil_weight<=20)||$ceil_weight==30||$ceil_weight==40||$ceil_weight==50||$ceil_weight==60||$ceil_weight==70||$ceil_weight==80||$ceil_weight==90||$ceil_weight==100)
		{
			$get_price="SELECT  `DHL_cost_std` 
							FROM  `useraccount` ,  `shippingaddress` ,  `countries` ,  `pricelist` 
							WHERE  `useraccount`.`user_id` =  '".$_SESSION['user_id']."'
							AND `useraccount`.`default_addressid` = `shippingaddress`.`address_id` 
							AND  `countries`.`Name` =  `shippingaddress`.`country` 
							AND  `countries`.`DHL_continents_id` =  `pricelist`.`continents_id` 
							AND  `pricelist`.`weight_lbs` =  '".$ceil_weight."'
							LIMIT 0 , 30";
			$result_price=mysql_query($get_price);
			$row_price=mysql_fetch_array($result_price);
			$Price=$row_price['DHL_cost_std'];
			$total_price = $Price;
		}
		
		else if($ceil_weight<100)
		{
			$tmp_weight=floor($ceil_weight/10)*10;
			echo $tmp_weight."<br/>";
			$plus=$ceil_weight-$tmp_weight;
			$get_price="SELECT  `DHL_cost_std` 
							FROM  `useraccount` ,  `shippingaddress` ,  `countries` ,  `pricelist` 
							WHERE  `useraccount`.`user_id` =  '".$_SESSION['user_id']."'
							AND `useraccount`.`default_addressid` = `shippingaddress`.`address_id` 
							AND  `countries`.`Name` =  `shippingaddress`.`country` 
							AND  `countries`.`DHL_continents_id` =  `pricelist`.`continents_id` 
							AND  `pricelist`.`weight_lbs` =  '".$tmp_weight."'
							LIMIT 0 , 30";
			$result_price=mysql_query($get_price);
			$row_price=mysql_fetch_array($result_price);
			$Price=$row_price['DHL_cost_std'];
			$Price+=($plus*$per_lbs_prics);
			$total_price = $Price;
		}
		else
		{
			$plus=$ceil_weight-100;	
			$get_price="SELECT  `DHL_cost_std` 
							FROM  `useraccount` ,  `shippingaddress` ,  `countries` ,  `pricelist` 
							WHERE  `useraccount`.`user_id` =  '".$_SESSION['user_id']."'
							AND `useraccount`.`default_addressid` = `shippingaddress`.`address_id` 
							AND  `countries`.`Name` =  `shippingaddress`.`country` 
							AND  `countries`.`DHL_continents_id` =  `pricelist`.`continents_id` 
							AND  `pricelist`.`weight_lbs` =  '100'
							LIMIT 0 , 30";
			$result_price=mysql_query($get_price);
			$row_price=mysql_fetch_array($result_price);
			$Price=$row_price['DHL_cost_std'];
			$Price+=($plus*$per_lbs_prics);
			$total_price = $Price;
		}
	if($flag_price == 1) echo round($total_price,2);
}
}

//-----------------------------------------------------------------------------------------------------------------------------------
?>


<h1>Calendar of Future 12 Weeks</h1>
<style type="text/css">
/* 页面基本样式 */
    td{
    font-family:Arial;
    font-size:12px;
}

/* 日程表格样式 */
#calTable {
    border-collapse:collapse;
    border:1px solid #333;
}

/* 每日单元格样式 */
td.calBox {
    border:1px solid #333;
    width:50px;
    height:40px;
    vertical-align:top;
}

td.calBox1 {
    text-align:center;
    background:#69F;
    border:1px solid #333;
    color:#FFF;}
	
/* 周标识样式 */
td.day {
    text-align:center;
    background:#C3D9FF;
    border:1px solid #333;
    color:#112ABB;
}

</style>




<!-- 新建任务box 	-->

<!-- 编辑任务box -->

<!-- 日历表格 -->
<table id="calTable" align="center">
<tr>
    <td class="day">Shipping Week:</td>
    <td class="day">1</td>
    <td class="day">2</td>
    <td class="day">3</td>
    <td class="day">4</td>
    <td class="day">5</td>
    <td class="day">6</td>
    <td class="day">7</td>
    <td class="day">8</td>
    <td class="day">9</td>
    <td class="day">10</td>
    <td class="day">11</td>
    <td class="day">12</td>
</tr>
<tr>
    <td class="calBox1">Estimate date: </td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
    <td class="calBox1"><?php est_date();?></td>
</tr>
<tr>
    <td  rowspan="2" class="day" id="calBox0"><table></table></td>
</tr>

<tr>
    <!-- 这里要修改为程序控制，根据period来输出-->
         
    <td class="calBox" id="calBox0"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php global $i;$i=13;cal_show(0,0,0);$i+=7;?></table></div></td>
    <td class="calBox" id="calBox1"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php global $shipping_date;global $i;global $j;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,0); $i+=7;?></table></div></td>
    <td class="calBox" id="calBox2"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(2,0,0); $i+=7;?></table></div></td>
    <td class="calBox" id="calBox3"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,0); $i+=7;?></table></div></td>
    <td class="calBox" id="calBox4"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(3,0,0); $i+=7;?></table></div></td>
    <td class="calBox" id="calBox5"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,0); $i+=7;?></table></div></td>
    <td class="calBox" id="calBox6"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(2,0,0); $i+=7;?></table></div></td>
    <td class="calBox" id="calBox7"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,0); $i+=7;?></table></div></td>
    <td class="calBox" id="calBox8"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(3,0,0); $i+=7;?></table></div></td>
    <td class="calBox" id="calBox9"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,0); $i+=7;?></table></div></td>
    <td class="calBox" id="calBox10"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(2,0,0); $i+=7;?></table></div></td>
    <td class="calBox" id="calBox11"><div align="center" style="overflow-y:scroll;width:86px;height:150px"><table><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,0); $i+=7;?></table></div></td>
</tr>
<tr>
    <td class="calBox1" id="calBox0">Total Weight:</td>
    <td class="calBox1" id="calBox1"><?php global $first_shipping_date;global $shipping_date;global $j;
	$olddate = explode("-",$first_shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+$j,$olddate[0]));global $i;$i=13;cal_show(0,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox2"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox3"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(2,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox4"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox5"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(3,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox6"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox7"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(2,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox8"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox9"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(3,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox10"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox11"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(2,1,0);$i+=7;?></td>
    <td class="calBox1" id="calBox11"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,1,0);$i+=7;?></td>
</tr>
<tr>
    <td class="day" id="calBox0">Total Price:</td>
    <td class="day" id="calBox1"><?php global $first_shipping_date;global $shipping_date;$shipping_date= $first_shipping_date;global $j;
	$olddate = explode("-",$first_shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+$j,$olddate[0]));global $i;$i=13;cal_show(0,0,1);$i+=7;?></td>
    <td class="day" id="calBox2"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,1);$i+=7;?></td>
    <td class="day" id="calBox3"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(2,0,1);$i+=7;?></td>
    <td class="day" id="calBox4"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,1);$i+=7;?></td>
    <td class="day" id="calBox5"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(3,0,1);$i+=7;?></td>
    <td class="day" id="calBox6"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,1);$i+=7;?></td>
    <td class="day" id="calBox7"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(2,0,1);$i+=7;?></td>
    <td class="day" id="calBox8"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,1);$i+=7;?></td>
    <td class="day" id="calBox9"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(3,0,1);$i+=7;?></td>
    <td class="day" id="calBox10"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,1);$i+=7;?></td>
    <td class="day" id="calBox11"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(2,0,1);$i+=7;?></td>
    <td class="day" id="calBox11"><?php  global $shipping_date;global $i;
	$olddate = explode("-",$shipping_date);
    $shipping_date = date('Y-m-d',mktime (0,0,0,$olddate[1],$olddate[2]+7,$olddate[0]));cal_show(1,0,1);$i+=7;?></td>
</tr>
<tr>
<!-- 在这添加分页的按钮 一下只是样式
    <td colspan="13">
        <input type="button" value="Last Page" onClick="prevMonth()"/>
        <input type="button" value="Current Page" onClick="thisMonth()"/>
        <input type="button" value="Next Page" onClick="nextMonth()"/>
        <span id="dateInfo"></span>
    </td>-->
</tr>
</table>
<table><tr><td><font color="#FF0000">After order has been fullfilled, they will be shown on the calendar.</td></tr></table>
<h1>Change Default Shipping Address</h1>
<table border='1' cellspacing='0' class='tt' >
	<thead align='center'>
		<tr>
        	<th>Name</th>
			<th>Address</th>
			<th>Phone</th>
			<th>Change</th>
		</tr>
	</thead>
    <tbody align='center'>
<?php

$query_result = mysql_query("SELECT *
FROM `useraccount`,`shippingaddress`
WHERE `useraccount`.`user_id`='".$_SESSION['user_id']."'
AND `useraccount`.`user_id`=`shippingaddress`.`user_id`
AND `useraccount`.`default_addressid`=`shippingaddress`.`address_id`");
while($row = mysql_fetch_array($query_result)){
	echo "<tr>";
	echo "<td>".$row['first_name']." ".$row['last_name']."</td>";
	echo "<td>".$row['address1'].", ".$row['address2']." ".$row['city'].", ".$row['state']." ,".$row['zip']." ,".$row['country']."</td>";
	echo "<td>".$row['phone']."</td>";
	echo "<td style='background-color:#69F;color:#fff;'><a style='color:#FFF;text-decoration: none;' href='ChangeDefaultAddress.php?ID=".$row['default_addressid']."'>Change</td>";
	echo "</tr>";}
?>
    </tbody>
</table>
<h1>My Shipping Orders</h1>
<table border='1' cellspacing='0' class='tt' >
	<thead align='center'>
		<tr>
        	<th>User ID</th>
			<th>Shipping Order ID</th>
			<th>Shipping Info</th>
			<th>shipping_weight</th>
            <th>shipping_price</th>
            <th>shipping_date</th>
			<th>Status</th>
		</tr>
	</thead>
    <tbody align='center'>
<?php

$query_result = mysql_query("SELECT *
FROM `shipping_order`,`shippingaddress`
WHERE `shipping_order`.`user_id`='".$_SESSION['user_id']."'
AND `shipping_order`.`address_id`=`shippingaddress`.`address_id`
LIMIT 0 , 30");
while($row = mysql_fetch_array($query_result)){
	echo "<tr>";
	echo "<td>".$row['user_id']."</td>";
	echo "<td>".$row['shipping_order_id']."</td>";
	echo "<td>".$row['address1']." ".$row['address2']." ".$row['city']." ".$row['state']." ".$row['zip']." ".$row['country']." ".$row['phone']."</td>";
	echo "<td>".$row['shipping_weight']."</td>";
	echo "<td>".$row['shipping_price']."</td>";
	echo "<td style='background-color:#69F;color:#fff;'>";
	if($row['shipping_date']!=='0000-00-00') 
	echo $row['shipping_date'];
	else
	echo "waiting";
	echo "</td>";
	echo "<td style='background-color:#69F;color:#fff;'>".$row['status']."</td>";
	echo "</tr>";
}
$db->close();
?>
    </tbody>
</table>

<br>
<br>

<!-- End of Coding area-->
</div>
</div>
</div>
<div id="footer">
  <div class="t"> <!-- to make this div in middle,set the proper of class"t" as margin-left:auto; margin-right:auto  -->
    <div class="sm">
      <dl>
        <dt>Home</dt>
        <dd><a href="homepage.php">Homepage</a></dd>
        <dd><a href="#" title="get latitude&longitude of your pin">Register</a></dd>
      </dl>
      <dl>
        <dt>My Account</dt>
        <dd><a href="http://www.w3schools.com">PHP</a></dd>
        <dd><a href="http://www.w3schools.com">JS</a></dd>
      </dl>
      <dl>
        <dt>About AIE</dt>
        <dd><a href="#">Customer Service</a></dd>
        <dd><a href="#">Contact US</a></dd>
      </dl>
    </div>
    	<div align="center">
        	<img alt="credit card" src="images/footer_cc_img.png" width="211" height="30" />
			<img alt="western union" src="images/western-union-logo.png" width="121" height="30" />
			<img alt="wire transfer" src="images/wire_transfer.png" width="115" height="30" />
        </div>
  </div>
<p style="margin-left:550px;color:#FFF; font-size:12px">&copy; 2010-2012 by Amerika International Express.<br>
All rights reserved. AIETimes.com is a trademark of Amerika International Express.</p>
</div>
</body>
</html>