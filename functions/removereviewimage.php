<?php require_once('../Connections/killjoy.php'); ?>
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




$colname_image_path = "-1";
if (isset($_POST['image_id'])) {
  $colname_image_path = $_POST['image_id'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_image_path = sprintf("SELECT image_url FROM tbl_propertyimages WHERE image_id = %s", GetSQLValueString($colname_image_path, "int"));
$image_path = mysql_query($query_image_path, $killjoy) or die(mysql_error());
$row_image_path = mysql_fetch_assoc($image_path);
$totalRows_image_path = mysql_num_rows($image_path);
$path = $row_image_path['image_url'];


 if ((isset($_POST["image_id"])) && ($_POST["image_id"] != "")) {
	 $successmsg = "your profile image was removed";
 $rowID = $_POST["image_id"];
  $updateSQL = sprintf("UPDATE social_users SET g_image=%s WHERE id=%s",
                       GetSQLValueString("media/profile.png", "text"),
                       GetSQLValueString($rowID, "int"));
  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());

 
  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

   mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($deleteSQL, $killjoy) or die(mysql_error());
 
  $insertSQL = sprintf("INSERT INTO tbl_uploaderror(sessionid, error_message) VALUES (%s, %s)",
                 GetSQLValueString($_SESSION['sessionid'], "text"),
                GetSQLValueString($successmsg, "text"));	

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());	   
   unlink($path);
 }
 	
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.killjoy.co.za/">
</head>
 <body>
</html>
