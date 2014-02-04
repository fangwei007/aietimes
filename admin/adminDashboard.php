<!DOCTYPE html>
<html>
<head>
<link href="AdminCSS.css" rel="stylesheet" type="text/css" />
<link href="form.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function invalidelogin(){
	document.getElementById("incorrect").innerHTML="Incorrect E-mail/Password.";
}
</script>
</head>
<body class="bg">
<div id="banner"><span style="color:#09F">A</span><span style="color:#F60">I</span><span style="color:#09F">E</span><span style="font-size:30px">TIMEs - Admin Dashboard</span>
<span style="font-size:10px; color: #666; font-style:normal;">Subscribe Magazines from USA. No matter where you are in the world!</span></div>
<div style="height:450px;">
<div style="margin-left:460px;margin-right:500px;margin-top:100px; background-color:#FFF; height:228px;">
<p align="center" style=" color:#F60;font-weight:bolder;">Admin - Login</p>
<div align="center">
<form class="form" method="post"
action="<?php echo $_SERVER['PHP_SELF'];//The filename of the currently executing script, relative to the document root.
?>">	
    	<span id="incorrect" style="color:#F00"></span><br>
        <label for="username">Username</label><br />
		<input type="text" name="username" value="<?php if (!empty($user_username)) echo $user_username; ?>" /><br>
   		<label for="password">Password</label><br />
		<input  type="password" name="password"  />	
		<div class="submit" style="margin-top:8px;margin-left:2px;">
		<input type="submit" value="Login" name="submit"/> 
        </div>
</form>
<?php
if (!isset($_SESSION['user_id'])&&isset($_POST['username'])) {
	include("DatabaseClass.php");
    // Connect to the database
	$db->connect();
    // Grab the user-entered log-in data
    $user_name = mysql_real_escape_string(trim($_POST['username']));
	//mysql_real_escape_string — Escapes special characters in a string for use in an SQL statement.
	//trim — Strip whitespace (or other characters) from the beginning and end of a string.
    $user_password = mysql_real_escape_string(trim($_POST['password']));
	  
      if (!empty($user_name) && !empty($user_password)) {
        // Look up the username and password in the database
        $query_result = mysql_query("SELECT user_id, username, password FROM adminuser WHERE username = '$user_name' AND password = '$user_password'");
        if (mysql_num_rows($query_result) === 1) {
          // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
          $row = mysql_fetch_array($query_result);
		  $cookies_life_time = 300;//unit is s
	session_start();
	setcookie(session_name() ,session_id(), time() + $cookies_life_time, "/");
          $_SESSION['user_id'] = $row['user_id'];
		  $_SESSION['admin_username'] = $row['username'];
		  $_SESSION['admin_key'] = "h#ka%sa251820fas";//Make sure through AdminDashboard.php is the only method to access the admin pages
          setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30));    // expires in 30 days
		  $home_url = 'client_info.php';
          header('Location: ' . $home_url);
        }
        else {
           echo "<script>invalidelogin()</script>";
        }
      }
      else {
		 echo "<script>invalidelogin()</script>";
      }
 $db->close();
}
?>
</div>
</div>

</div>
<p style="margin-left:550px;color:#FFF; font-size:12px">&copy; 2012 AIE Inc. All rights reserved.</p>
</div>
</body>
</html>