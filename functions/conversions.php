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

mysql_select_db($database_killjoy, $killjoy);
$query_rs_conversions = "SELECT FROM_DAYS(TO_DAYS(DATE_FORMAT(c_date, '%Y-%d-01')) -MOD(TO_DAYS(c_date) -2, 7)) AS week_beginning, COUNT(id) as totalConversions, SUM(is_happy)/COUNT(id)*100 as Good, SUM(not_happy)/COUNT(id)*100 as not_good FROM  tbl_conversions WHERE ( c_date > DATE_SUB(now(), INTERVAL 30  DAY))   GROUP BY FROM_DAYS(TO_DAYS(c_date) -MOD(TO_DAYS(c_date) -2, 7))";
$rs_conversions = mysql_query($query_rs_conversions, $killjoy) or die(mysql_error());
$row_rs_conversions = mysql_fetch_assoc($rs_conversions);
$totalRows_rs_conversions = mysql_num_rows($rs_conversions);
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
mysql_free_result($rs_conversions);
?>
