<?php

	$token = bin2hex(openssl_random_pseudo_bytes(16));
	setcookie("kj_s_token", $token, time()+31556926 ,'/');
	$session_token = password_hash($token, PASSWORD_BCRYPT);
	
		               $updateSQL = sprintf("UPDATE kj_recall SET social_users_token=%s WHERE social_users_identifier=%s",
                       GetSQLValueString($session_token, "text"),
                       GetSQLValueString($_COOKIE['kj_s_identifier'], "text"));
					     mysql_select_db($database_killjoy, $killjoy);
                          $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
	




























?>











<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<form action="" method="get"><input name="user" type="text" /><input name="password" type="password" /></form>
<body>
</body>
</html>