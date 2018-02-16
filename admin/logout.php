<?php require_once('../Connections/killjoy.php'); ?>
<?php
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

if (isset($_COOKIE['kj_recallmember'])) {
  $deleteSQL = sprintf("DELETE FROM kj_recall WHERE social_users_email=%s",
                       GetSQLValueString($_COOKIE['kj_recallmember'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($deleteSQL, $killjoy) or die(mysql_error());
}


session_start();
// although 2nd and 3rd line is not needed session_destroy() is needed,
// but just to be extra sure that no session remains in the cache.
$_SESSION = array();
unset($_SESSION);
session_destroy();
header("location:index.php");

if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
	
	if (isset($_COOKIE['kj_recallmember'])) {
		
		
	}
}
?>
