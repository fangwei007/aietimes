<?php
 //************************
  $db = new Database("localhost", "aietimedb", "root", "4608");
                     //Hosting , databasename, username, password 
  /* <Instruction>
   include("DatabaseClass.php");
   $db->connect();
    //main code
	$db->close();
  */
 //************************
class Database
{
 private $Hostserver = "";
 private $Database = "";
 private $User = "";
 private $Password = "";
 
 private $linked;
 //************************
 //Constructor
 //************************
 function Database($Hostserver,$Database,$User,$Password){
	 $this->Hostserver = $Hostserver;
	 $this->Database = $Database;
	 $this->User = $User;
	 $this->Password = $Password;
 }
 //************************
 //Connects to the database
 //************************
function connect(){
	 $this->linked = @mysql_connect($this->Hostserver, $this->User, $this->Password);//use @ to avoid leaking account info while warning
	 if (!$this->linked){
		 die('Could not connect: ' . mysql_error());
		 }//select database;
	$db_selected = @mysql_select_db($this->Database, $this->linked);
	if (!$db_selected){
		die ("Can't use DB: " . mysql_error());
		}
	 }
 //************************
 //close connection to the database
 //************************
function close(){
 mysql_close($this->linked); 
}
}

function tep_db_prepare_input($string) {

    if (is_string($string))
	{

      return trim(tep_sanitize_string(stripslashes($string)));

    }
	elseif (is_array($string))
	{

      reset($string);

      while (list($key, $value) = each($string))
	  {

        $string[$key] = tep_db_prepare_input($value);

      }

      return $string;

    }
	else
	{

      return $string;

    }
}
function tep_db_num_rows($db_query)
{

    return mysql_num_rows($db_query);

}

function tep_db_fetch_array($db_query)
{

    return mysql_fetch_array($db_query, MYSQL_ASSOC);

}

function tep_sanitize_string($string)
{

    $string = str_replace(' +', ' ', trim($string));



    return preg_replace("/[<>]/", '_', $string);

}

?>