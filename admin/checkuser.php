<?php require_once('../Connections/killjoy.php'); ?>
<?php
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


$colname_rs_checkuser = "-1";
if (isset($_POST['usermail'])) {
  $colname_rs_checkuser = $_POST['usermail'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_checkuser = sprintf("SELECT g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_checkuser, "text"));
$rs_checkuser = mysql_query($query_rs_checkuser, $killjoy) or die(mysql_error());
$row_rs_checkuser = mysql_fetch_assoc($rs_checkuser);
$totalRows_rs_checkuser = mysql_num_rows($rs_checkuser);


    if($totalRows_rs_checkuser) //user id exist in database
    {
		$_SESSION['user_email'] = $_POST['usermail'];
    }else{ //user is new
		$_SESSION['user_email'] = $_POST['usermail'];
		  $insertSQL = sprintf("INSERT INTO social_users (g_email) VALUES (%s)",
                       GetSQLValueString($_POST['usermail'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
		
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

<?php
mysql_free_result($rs_checkuser);
?>
