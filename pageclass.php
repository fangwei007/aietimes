<?php
/*
mysql_pager.class.php
三个参数：mysql_query()的结果， url变量page， 我要的每页记录数。
例子底部也有
*/

class mysql_pager{
	//define properties
	var $page;
	var $result;
	var $results_per_page = 3;
	var $total_pages;
	/*
	Define the methods
	
	下面是构造函数，和类同名
	需要查询的结果句柄，当前页码，每页记录数
	like: $f->mysql_pager($result, 1, 15);
	
	*/
function mysql_pager($result, $current_page, $results_per_page){
	
	if(!$result){
		echo "<div align=center>Database didn't work, wrong results</div>\n";
		return;
	}
	$this->result = $result;
	
	if(!$current_page || $current_page < 0)
	$this->page = 1;
	else $this->page = $current_page;
	
	if(!empty($results_per_page))
	$this->results_per_page = $results_per_page;
	
	$numrows = @mysql_num_rows($this->result);
	if(!$numrows){
		echo "<div align=center>Query result is empty!</div>\n";
		return;
	}
	
	$this->total_pages = ceil($numrows / $this->results_per_page);
}

/*
下面是打印内容的函数，可以不用，也可以扩展，这里只打印ID
*/

function print_paged_results() {
	//echo "<table border=0 align=center>\n";
	$start = ($this->page - 1)*$this->results_per_page;
	@mysql_data_seek($this->result, $start);
	$x=0;
	for($i=1; $i<=$this->results_per_page && $row = @mysql_fetch_array($this->result); $i++){
		//if($x++ & 1) $bgcolor = "#F2F2FF";
		//else $bgcolor = "#EEEEEE";
		
		//echo "<tr bgcolor=$bgcolor><td>".$row[0]."</td></tr>";
		//编辑这部分输出任何你想要HTML
		echo "<tr>";
	echo "<td>".$row[0]."</td>";
	echo "<td>".$row[1]."</td>";
	echo "<td>".$row[2]."</td>";
	echo "<td><ol>";
		$magazineInfo = mysql_query("SELECT *
			FROM `order_magazineinfo`,`magazineinfo`
			WHERE `order_id`='".$row['order_id']."'
			AND `order_magazineinfo`.`mag_id`=`magazineinfo`.`mag_id`");
		while($list = mysql_fetch_array($magazineInfo)){
			echo "<li>".$list['mag_name']." --- MagID: ".$list['mag_id']." --- 	Qty: ".$list['quantity']." --- Price: ".$list['price']."</li>";}
	echo "</ol></td>";
	echo "<td>".$row[3]."</td>";
	if($row[4]==="Subscribed")	
	echo "<td style='background-color:#69F;color:#fff;'>".$row[4]."</td>";
	if($row[4]==="pending")	echo "<td style='background-color:#F63;color:#fff;'>Pending</td>";
	echo "</tr>";

		
	}
	echo "</table>\n";
}

/*
下面是打印页码和链接的函数
在需要显示页码的地方调用
*/

function print_navigation() {
	global $PHP_SELF;
	echo "<div align=center>";
	
	for($i=1; $i<= $this->total_pages; $i++)
	{		#loop to print<< 1 2 3 ... $total_pages >>
	    if($i==1 && $this->page > 1) #Prints the << first to goto the previous page(not on page 1)
		echo "<a href=\"$PHP_SELF?page=".($this->page-1)."\" onMouseOver=\"status=Previous Page;return true;\" onMouseOut=\"status=;return true;\"><font color=\"#000000\"><< </font></a>";
		
		if($i==$this->page)#Doesn't print a link itself, just prints page number
		echo "<font color=\"#ff3333\">$i </font>";
		
		if($i != $this->page) #Other links that aren't this page go here
		echo "<a href=\"$PHP_SELF?page=$i\" onMouseOver=\"status=Go to Page $i;return true;\" onMouseOut=\"status=;return true;\"><font color=\"#000000\">$i </font></a>";
		
		if($i==$this->total_pages && $this->page != $this->total_pages) #Link for next page >> (not the last page)
		echo "<a href=\"$PHP_SELF?page=".($this->page+1)."\" onMouseOver=\"status=Go to the Next Page;return true;\" onMouseOut=\"status=;return true;\"><font color=\"#000000\">>></font></a>";
	}
	echo "</div>\n";
}
}

?>
