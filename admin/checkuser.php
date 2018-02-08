<?php

$user_id = $_POST['usermail'];

########## MySql details (Replace with yours) #############
$db_username = "euqjdems_nawisso"; //Database Username
$db_password = "N@w!1970"; //Database Password
$hostname = "localhost"; //Mysql Hostname
$db_name = 'euqjdems_killjoy'; //Database Name
###################################################################

    $connecDB = mysql_connect($hostname, $db_username, $db_password)or die("Unable to connect to MySQL");
    mysql_select_db($db_name,$connecDB);
	
    //compare user id in our database
    $result = mysql_query("SELECT COUNT(member_username) FROM tbl_members WHERE member_username=$user_id");
	if($result === false) { 
		die(mysql_error()); //result is false show db error and exit.
	}
	
	$UserCount = mysql_fetch_array($result);
 
    if($UserCount[0]) //user id exist in database
    {
		echo 'Welcome back '.$user_name.'!';
    }else{ //user is new
		echo 'Hello! '.$user_name.', Thanks for Registering!';
		@mysql_query("INSERT INTO tbl_members (member_username, date_joined) VALUES ($user_id, now())");
	}



?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>