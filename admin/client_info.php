<?php
//add these code to the beginning of every admin pages!!!
	$cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
	if(!isset($_SESSION['admin_username'])||($_SESSION['admin_key'] !== "h#ka%sa251820fas"))
	header("location: adminDashboard.php");//For security, header the user back to login page if that user is not properly logged 
//getting sorting values 
include("DatabaseClass.php");
$flag = false;
$searchBy = '';
$searchFor = '';
$sortBy = '';
$sortOrder = '';

if(isset($_GET)){
	$searchBy = @$_GET['selSearchBy'];
	$searchFor = tep_db_prepare_input(@$_GET['tSearchFor']);
	//$searchFor = $_GET['tSearchFor'];
	//sort option	
	$sortBy = @$_GET['selSortName'];
	$sortOrder = @$_GET['selSortType'];	

	$flag = true;

}else{
	$flag = false;
}
?>
<!DOCTYPE html>
<html>
<head>
<link href="AdminCSS.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="table.css" type="text/css" media="print, projection, screen" />
<style>
#apDiv {
	position:absolute;
	left:344px;
	top:80px;
	width:132px;
	height:100px;
	z-index:1;
	display: none;
}
#apDiv a{
	text-decoration: none;
	color: #F90;
	font-weight: bold;
	text-align: center;
}
#PMDiv {
	position:absolute;
	left:483px;
	top:80px;
	width:163px;
	height:100px;
	z-index:1;
	display: none;
}
#PMDiv a{
	text-decoration: none;
	color: #F90;
	font-weight: bold;
	text-align: center;
}
</style>

<script type="text/javascript" src="js/AC_RunActiveContent.js"></script>

<script type="text/javascript" src="js/jquery.js"></script>

<script type="text/javascript" src="js/j_default.js"></script>

<script type="text/javascript">
function showmenu(){
	document.getElementById("menuDIV").style.display="block";
}
function hidemenu(){
	document.getElementById("menuDIV").style.display="none";
}
function showmenu2(){
	document.getElementById("apDiv").style.display="block";
}
function hidemenu2(){
	document.getElementById("apDiv").style.display="none";
}
function showmenu3(){
	document.getElementById("PMDiv").style.display="block";
}
function hidemenu3(){
	document.getElementById("PMDiv").style.display="none";
}

//search,sort - start
function checkSearch(){
	var tsBy = $("#selSearchBy").val();
	var tsFor = $("#tSearchFor").val();
	if((tsBy == '') && (tsFor == '')){
		$('#search-result-msg')
			.html($('<div class="contact-error"></div>').append('Please select your search type and write something to search'))
			.fadeIn(200);

		return false;
		
	}else if((tsBy == '') && (tsFor != '')){
		$('#search-result-msg')
		.html($('<div class="contact-error"></div>').append('Please select your search type'))
		.fadeIn(200);

		return false;

	}else if((tsBy != '') && (tsFor == '')){
		$('#search-result-msg')
		.html($('<div class="contact-error"></div>').append('Please write something to search'))
		.fadeIn(200);

		return false;

	}else if((tsBy != '') && (tsFor != '')){

		return true;

	}
}



function checkSortSubmit(){
	var tsBy = $("#selSortName").val();
	var tsFor = $("#selSortType").val();
	if((tsBy == '') && (tsFor == '')){
		$('#search-result-msg')
			.html($('<div class="contact-error"></div>').append('Please select your sorting type and order to sort'))
			.fadeIn(200);

		return false;

	}else if((tsBy == '') && (tsFor != '')){
		$('#search-result-msg')
		.html($('<div class="contact-error"></div>').append('Please select your sorting type'))
		.fadeIn(200);

		return false;

	}else if((tsBy != '') && (tsFor == '')){
		$('#search-result-msg')
		.html($('<div class="contact-error"></div>').append('Please select your sorting order'))
		.fadeIn(200);

		return false;
		
	}else if((tsBy != '') && (tsFor != '')){
		$("#frmSearch").submit();
	}
}

//search,sort - end
</script>
</head>
<body class="bg">
<div id="menuDIV" onmouseover="showmenu()" onmouseout="hidemenu()">
      <a href="packing.php"><div id="menulink">Packing</div></a>
      <a href="receive.php"><div id="menulink">Receive Magazines</div></a>
</div>

<div id="apDiv" onmouseover="showmenu2()" onmouseout="hidemenu2()">      
	<a href="client_info.php"><div id="menulink">Client Info</div></a>
      <a href="orderprocess.php"><div id="menulink">Order Process</div></a>
      <a href="chargeShipping.php"><div id="menulink">Shipping Process</div></a>
      <a href="addMagazines.php"><div id="menulink">Add Magazines</div></a>
</div>

<div id="PMDiv" onmouseover="showmenu3()" onmouseout="hidemenu3()">
      <a href="pricelist_manager.php"><div id="menulink">Manage Pricelist</div></a>
      <a href="countrylist_manager.php"><div id="menulink">Manage Countrylist</div></a>
</div>

<div id="banner"><span style="color:#09F">A</span><span style="color:#F60">I</span><span style="color:#09F">E</span><span style="font-size:30px">TIMEs</span>
<span style="font-size:10px; color: #666; font-style:normal;">Subscribe Magazines from USA. No matter where you are in the world!</span>
<span style="margin-left:500px; ; font-size:14px;"> 
<?php
	echo "<div style='position:absolute;right:60px;top:18px;'>Hi! ".$_SESSION['admin_username']." ";
	echo "<a href='adminlogout.php' style='text-decoration: none;margin:5px;padding:5px;width:50px;color:white;background-color:#F60;'>log out</a></div>";
?>
</span>
</div>
<div id="main">
<ul id="menu">
<li><a href="packing.php" onmouseover="showmenu()" onmouseout="hidemenu()">Warehouse Management</a></li>
<li><a href="client_info.php" onmouseover="showmenu2()" onmouseout="hidemenu2()">Client Service</a></li>
<li><a href="pricelist_manager.php" onmouseover="showmenu3()" onmouseout="hidemenu3()">Pricelist Manager</a></li>
<li><a href="about.php">About AIE</a></li>
</ul>
<div id="content">
<div id="content2">
<!-- Beginning of coding area-->
<div>
    <form id="frmSearch" name="frmSearch" method="get">
		<table cellspacing="1" cellpadding="1" border="0" width="100%">
    		<tbody>
				<tr>
					<td colspan="10" style="font-weight:bold;font-size:15px;">Find Customer</td>
				</tr>
				<tr>
					<td valign="bottom" width="60%">
						<label for="selSearchBy">Search By: </label>
						<select id="selSearchBy" name="selSearchBy">
							<option value="">Select</option>
							<option value="search_userid" <?php if($searchBy=='search_userid'){ ?>selected="selected"<?php } ?>>User ID</option>
							<option value="custname" <?php if($searchBy=='custname'){ ?>selected="selected"<?php } ?>>Customer Name</option>
						</select>

						<label for="tSearchFor">Search For:</label>
							<input type="text" id="tSearchFor" name="tSearchFor" <?php if($searchFor != ''){ ?>value="<?php echo $searchFor; ?>"<?php } ?> />
							<input type="submit" id="btnSearchSubmit" name="btnSearchSubmit" value="Search" onClick="return checkSearch()" style="cursor:pointer;" />
						<span id="searchLoader" style="display:none;"><img src="images/opc-ajax-loader.gif" border="0" alt="Please wait..." /></span>
					</td>
                    
					<td valign="bottom" align="right" width="40%">
						<label for="selSortName">Sort by: </label>
						<select id="selSortName" name="selSortName" onChange="return checkSortSubmit()">
							<option value="">Select</option>
							<option value="userid" <?php if($sortBy=='userid'){ ?>selected="selected"<?php } ?>>User ID</option>
							<option value="regdate" <?php if($sortBy=='regdate'){ ?>selected="selected"<?php } ?>>Reg.Date</option>
						</select>
						
                        <select id="selSortType" name="selSortType" onChange="return checkSortSubmit()">
							<option value="">Select</option>
							<option value="asc" <?php if($sortOrder=='asc'){ ?>selected="selected"<?php } ?>>Ascending</option>
							<option value="desc" <?php if($sortOrder=='desc'){ ?>selected="selected"<?php } ?>>Descending</option>
						</select>
					</td>
				</tr>

				<tr><td colspan="2"><div id='search-result-msg' class='contact-message-box' style='display:none'></div></td></tr>
			</tbody>
		</table>
	</form>
</div>
<!--<hr align="center" noshade="noshade" size="2px" width="80%"/>-->


<div style="border: 0 solid red; float: left; text-align: justify; width: 100%;">
	<h1>Client Info</h1>
	<table border='1' cellspacing='0' border="0" class=tt>
    	<tbody align='center'>
			<tr style="height:32px; font-weight:bold;background-color: #F4F4F4;">
				<th>User ID</th>
				<th>Name</th>
				<th>Register Date</th>
				<th>Email</th>
				<th>Details</th>
			</tr>
		
<?php
//include("DatabaseClass.php");
$link = $db->connect();
//pagination > start

// How many adjacent pages should be shown on each side?
$adjacents = 3;

/* 
First get total number of rows in data table. 
If you have a WHERE clause in your query, make sure you mirror it here.
*/

/* Get data. */
if($flag == false)
{
	$query = "SELECT COUNT(*) as num FROM useraccount c ORDER BY c.user_id DESC";
}
else
{
	if($searchBy=='search_userid')
	{
		$query = "SELECT COUNT(*) as num FROM useraccount c WHERE c.user_id LIKE '%".$searchFor."%' ORDER BY c.user_id DESC";
	}
	elseif($searchBy=='custname')
	{
		$query = "SELECT COUNT(*) as num FROM useraccount c WHERE c.first_name LIKE '%".$searchFor."%' OR c.last_name LIKE '%".$searchFor."%' ORDER BY c.user_id DESC";
	}
	else
	{
		$query = "SELECT COUNT(*) as num FROM useraccount c ORDER BY c.user_id DESC";	
	}
}

$total_pages = mysql_fetch_array(mysql_query($query));
$total_pages = @$total_pages[num];
//echo $total_pages;

/* Setup vars for query. */
if(isset($_GET) && !empty($HTTP_GET_VARS) && (count($HTTP_GET_VARS)>1))
{
	$is_ques_mark = "&";
	$other_link = substr($_SERVER['REQUEST_URI'],stripos($_SERVER['REQUEST_URI'],"?"),strlen($_SERVER['REQUEST_URI']));
	if(stripos($other_link,"&page"))
	{
		$other_link = substr($other_link,0,stripos($other_link,"&page"));
	} 
	$targetpage = ""."client_info.php" . $other_link . ""; 				//your file name  (the name of this file)	
}
else
{
	$is_ques_mark = "?";
	$targetpage = ""."client_info.php" .""; 				//your file name  (the name of this file)
}
$target_final = $targetpage . $is_ques_mark;

$limit = 3; 								//how many items to show per page
$page = @$_GET['page'];

if($page) 
$start = ($page - 1) * $limit; 			//first item to display on this page
else
$start = 0;								//if no page var is given, set start to 0

/* Get data. */
//echo "sortBy:".$sortBy."<br/>"."sortOrder:".$sortOrder."<br/>"."searchBy:".$searchBy."<br/>";
if($flag == false)
{
	if(($sortBy!='') && ($sortBy=='userid') && ($sortOrder!=''))
	{
		$sql = "SELECT c.* FROM useraccount c ORDER BY c.user_id ".$sortOrder." LIMIT ".$start.", ".$limit."";
	}
	else if(($sortBy!='') && ($sortBy=='regdate') && ($sortOrder!=''))
	{
		$sql = "SELECT c.* FROM useraccount c ORDER BY c.join_date ".$sortOrder." LIMIT ".$start.", ".$limit."";
	}
	else
	{
		$sql = "SELECT c.* FROM useraccount c ORDER BY c.user_id DESC LIMIT ".$start.", ".$limit."";	
	}
}
else
{
	if($searchBy=='search_userid')
	{
		if(($sortBy!='') && ($sortBy=='userid') && ($sortOrder!=''))
		{
			$sql = "SELECT c.* FROM useraccount c WHERE c.user_id LIKE '%".$searchFor."%' ORDER BY c.user_id ".$sortOrder." LIMIT ".$start.", ".$limit."";
		}
		else if(($sortBy!='') && ($sortBy=='regdate') && ($sortOrder!=''))
		{
			$sql = "SELECT c.* FROM useraccount c WHERE c.user_id LIKE '%".$searchFor."%' ORDER BY c.join_date ".$sortOrder." LIMIT ".$start.", ".$limit."";
		}
		else
		{
			$sql = "SELECT c.* FROM useraccount c WHERE c.user_id LIKE '%".$searchFor."%' ORDER BY c.user_id DESC LIMIT ".$start.", ".$limit."";	
		}
	}
	elseif($searchBy=='custname')
	{
		if(($sortBy!='') && ($sortBy=='userid') && ($sortOrder!=''))
		{
			$sql = "SELECT c.* FROM useraccount c WHERE c.first_name LIKE '%".$searchFor."%' OR c.last_name LIKE '%".$searchFor."%' ORDER BY c.user_id ".$sortOrder." LIMIT ".$start.", ".$limit."";
		}
		else if(($sortBy!='') && ($sortBy=='regdate') && ($sortOrder!=''))
		{
			$sql = "SELECT c.* FROM useraccount c WHERE c.first_name LIKE '%".$searchFor."%' OR c.last_name LIKE '%".$searchFor."%' ORDER BY c.join_date ".$sortOrder." LIMIT ".$start.", ".$limit."";
		}
		else
		{
			$sql = "SELECT c.* FROM useraccount c WHERE c.first_name LIKE '%".$searchFor."%' OR c.last_name LIKE '%".$searchFor."%' ORDER BY c.user_id DESC LIMIT ".$start.", ".$limit."";	
		}
	}
	else
	{
		if(($sortBy!='') && ($sortBy=='userid') && ($sortOrder!=''))
		{
			$sql = "SELECT c.* FROM useraccount c ORDER BY c.user_id ".$sortOrder." LIMIT ".$start.", ".$limit."";
		}
		else if(($sortBy!='') && ($sortBy=='regdate') && ($sortOrder!=''))
		{
			$sql = "SELECT c.* FROM useraccount c ORDER BY c.join_date ".$sortOrder." LIMIT ".$start.", ".$limit."";
		}
		else
		{
			$sql = "SELECT c.* FROM useraccount c ORDER BY c.user_id DESC LIMIT ".$start.", ".$limit."";	
		}	
	}
}//end else

//echo $sql;
$result = mysql_query($sql);
$row = tep_db_num_rows($result);

/* Setup page vars for display. */
if ($page == 0) $page = 1;					//if no page var is given, default to 1.
$prev = $page - 1;							//previous page is page - 1
$next = $page + 1;							//next page is page + 1
$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
$lpm1 = $lastpage - 1;						//last page minus 1

/* 
Now we apply our rules and draw the pagination object. 
We're actually saving the code to a variable in case we want to draw it more than once.
*/
$pagination = "";
if($lastpage > 1)
{	
	$pagination .= "<div style=\"margin: 3px; padding: 3px;\">";
	//previous button
	if ($page > 1) 
		$pagination.= "<a href=\"".$target_final."page=$prev\">previous&nbsp;</a>";
	else
		$pagination.= "<span style=\"padding: 2px 5px 2px 5px; margin: 2px; border: 1px solid #EEE; color: #DDD;\">previous&nbsp;</span>";	
	//pages	
	if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
	{	
		for ($counter = 1; $counter <= $lastpage; $counter++)
		{
			if ($counter == $page)
				$pagination.= "<span style=\"padding: 2px 5px 2px 5px; margin: 2px; border: 1px solid #000099; font-weight: bold; background-color: #000099; color: #FFF;\">&nbsp;$counter&nbsp;</span>";
			else
				$pagination.= "<a href=\"".$target_final."page=$counter\">&nbsp;$counter&nbsp;</a>";					
		}
	}
	elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
	{
		//close to beginning; only hide later pages
		if($page < 1 + ($adjacents * 2))		
		{
			for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span style=\"padding: 2px 5px 2px 5px; margin: 2px; border: 1px solid #000099; font-weight: bold; background-color: #000099; color: #FFF;\">&nbsp;$counter&nbsp;</span>";
				else
					$pagination.= "<a href=\"".$target_final."page=$counter\">&nbsp;$counter&nbsp;</a>";					
			}
			$pagination.= "...";
			$pagination.= "<a href=\"".$target_final."page=$lpm1\">&nbsp;$lpm1&nbsp;</a>";
			$pagination.= "<a href=\"".$target_final."page=$lastpage\">&nbsp;$lastpage&nbsp;</a>";		
		}
		//in middle; hide some front and some back
		elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
		{
			$pagination.= "<a href=\"".$target_final."page=1\">&nbsp;1&nbsp;</a>";
			$pagination.= "<a href=\"".$target_final."page=2\">&nbsp;2&nbsp;</a>";
			$pagination.= "...";
			for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span \"padding: 2px 5px 2px 5px; margin: 2px; border: 1px solid #000099; font-weight: bold; background-color: #000099; color: #FFF;\">&nbsp;$counter&nbsp;</span>";
				else
					$pagination.= "<a href=\"".$target_final."page=$counter\">&nbsp;$counter&nbsp;</a>";					
			}
		$pagination.= "...";
		$pagination.= "<a href=\"".$target_final."page=$lpm1\">&nbsp;$lpm1&nbsp;</a>";
		$pagination.= "<a href=\"".$target_final."page=$lastpage\">&nbsp;$lastpage&nbsp;</a>";		
	}

	//close to end; only hide early pages
	else
	{
		$pagination.= "<a href=\"".$target_final."page=1\">&nbsp;1&nbsp;</a>";
		$pagination.= "<a href=\"".$target_final."page=2\">&nbsp;2&nbsp;</a>";
		$pagination.= "...";
		for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
		{
			if ($counter == $page)
				$pagination.= "<span \"padding: 2px 5px 2px 5px; margin: 2px; border: 1px solid #000099; font-weight: bold; background-color: #000099; color: #FFF;\">&nbsp;$counter&nbsp;</span>";
			else
				$pagination.= "<a href=\"".$target_final."page=$counter\">&nbsp;$counter&nbsp;</a>";					
		}
	}
}
//next button
if ($page < $counter - 1) 
	$pagination.= "<a href=\"".$target_final."page=$next\">&nbsp;next</a>";
else
	$pagination.= "<span style=\"padding: 2px 5px 2px 5px; margin: 2px; border: 1px solid #EEE; color: #DDD;\">&nbsp;next</span>";
	$pagination.= "</div>\n";		
}
//pagination > end

$cust_row = tep_db_num_rows($result);
if($cust_row > 0)
{
	$i = 1;

	while($cust_val = tep_db_fetch_array($result))
	{
?>
		<tr>
			<td>
				<?php echo $cust_val['user_id']; ?>
			</td>
			<td>
				<?php echo $cust_val['first_name'] . ' ' . $cust_val['last_name']; ?>
			</td>
			<td>
				<?php echo $cust_val['join_date']; ?>
			</td>
			<td>
				<?php echo $cust_val['email']; ?>
			</td>
			<td>
				<?php echo "<a href='userdisplay.php?UID=".$cust_val['user_id']."'><button class='button'>Details</button></a><br/>"; ?>
			</td>
		</tr>
		<?php
		$i++; 
	}//end while
		?>
		<tr><td colspan="20"><span style="font-weight:bold; color:blue;">Total Customer(s): <?php echo $total_pages; ?></span></td></tr>
		<?php 
}//end if
else
{
		?>
		<tr style="background-color:#E3E3E3;">
			<td colspan="20" width="100%" align="center">
				No records found
			</td>
		</tr>
	<?php 
}//end else
	?>
	</tbody>
</table>

<?php 
	if($pagination != '')
	{
		echo '<table border="0" width="100%" cellpadding="0" cellspacing="0" class="portfolio"><tr><td align="center">'.$pagination.'</td></tr></table>';
	}
?>

</div>
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
<p style="margin-left:550px;color:#FFF; font-size:12px">&copy; 2010-2012 by Amerika International Express.
All rights reserved. <br>AIETimes.com is a trademark of Amerika International Express.</p>
</div>
</body>
</html>