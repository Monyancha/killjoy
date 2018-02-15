
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_rs_kj_recall = "-1";
if (isset($_COOKIE['kj_recallmember'])) {
  $colname_rs_kj_recall = $_COOKIE['kj_recallmember'];
  

mysql_select_db($database_killjoy, $killjoy);
$query_rs_kj_recall = sprintf("SELECT g_email, g_pass, g_active FROM social_users WHERE g_email = %s AND g_active = 1", GetSQLValueString($colname_rs_kj_recall, "text"));
$rs_kj_recall = mysql_query($query_rs_kj_recall, $killjoy) or die(mysql_error());
$row_rs_kj_recall = mysql_fetch_assoc($rs_kj_recall);
$totalRows_rs_kj_recall = mysql_num_rows($rs_kj_recall);

  $loginUsername=$row_rs_kj_recall ['g_email'];
  $loginPassword=$_COOKIE['kj_recallmember_acc'];
  $MM_fldUserAuthorization = "";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_killjoy, $killjoy);
  
  $LoginRS__query=sprintf("SELECT g_email, g_pass FROM social_users WHERE g_email=%s AND g_pass=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($loginPassword, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $killjoy) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);  
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['kj_username'] = $loginUsername;
    $_SESSION['kj_usergroup'] = $loginStrGroup;	
	$_SESSION['kj_authorized'] = "1";     

     }
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