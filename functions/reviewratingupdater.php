<?php require_once('../Connections/killjoy.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $theValue) : ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $theValue) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

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

$colname_get_address = "-1";
if (isset($_SESSION['sessionid'])) {
  $colname_get_address = $_SESSION['sessionid'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_get_address = sprintf("SELECT * FROM tbl_address WHERE sessionid = %s", GetSQLValueString($colname_get_address, "text"));
$get_address = mysqli_query( $killjoy, $query_get_address) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_get_address = mysqli_fetch_assoc($get_address);
$totalRows_get_address = mysqli_num_rows($get_address);

if (isset($_POST["txt_rating"])) {
  $updateSQL = sprintf("UPDATE tbl_address_rating SET rating_value=%s WHERE address_comment_id=%s",
                       GetSQLValueString($_POST['txt_rating'], "int"),
                       GetSQLValueString($_POST['txt_ratingid'], "int"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robors" content="noindex,nofollow" />
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>killjoy - update member review rating value</title>
</head>

<body>

</body>
</html>
<?php
((mysqli_free_result($get_address) || (is_object($get_address) && (get_class($get_address) == "mysqli_result"))) ? true : false);
?>
