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




$colname_image_path = "-1";
if (isset($_POST['id'])) {
  $colname_image_path = $_POST['id'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_image_path = sprintf("SELECT image_url FROM tbl_propertyimages WHERE id = %s", GetSQLValueString($colname_image_path, "int"));
$image_path = mysqli_query( $killjoy, $query_image_path) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_image_path = mysqli_fetch_assoc($image_path);
$totalRows_image_path = mysqli_num_rows($image_path);
$path = "../".$row_image_path['image_url'];


 if ((isset($_POST["id"])) && ($_POST["id"] != "")) {
	$successmsg = "<span style='color: #FE8374'><span class='icon-trash-o'></span> your image was removed</span>";
 $rowID = $_POST["id"];
  $updateSQL = sprintf("DELETE FROM tbl_propertyimages WHERE id=%s",
                          GetSQLValueString($rowID, "int"));
  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));

 
  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['kj_propsession'], "text"));

   mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $deleteSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
 
  $insertSQL = sprintf("INSERT INTO tbl_uploaderror(sessionid, error_message) VALUES (%s, %s)",
                 GetSQLValueString($_SESSION['kj_propsession'], "text"),
                GetSQLValueString($successmsg, "text"));	

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));	   
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
