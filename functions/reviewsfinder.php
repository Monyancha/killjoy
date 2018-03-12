<?php
require_once('../Connections/killjoy.php');
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
 $editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
 if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
 if ( isset( $_POST['txt_address'] ) ) {
 $searchaddress = $_POST['txt_address'];
$searchaddress = addslashes($searchaddress);
 if(preg_match_all('/\d+/', $searchaddress, $numbers))
$lastnum = end($numbers[0]);
setcookie("listsessionid",  htmlspecialchars($lastnum));
 }
$insertSQL = sprintf("INSERT INTO tbl_reviewsearches (search_text) VALUES (%s)",
		 GetSQLValueString($_POST['txt_address'], "text"));
 mysqli_select_db( $rentaguide, $database_rentaguide);
$Result1 = mysqli_query( $rentaguide, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
 $insertGoTo = "launchshowreviews.php?accesscheck=index.php";
if (isset($_SERVER['QUERY_STRING'])) {
$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
$insertGoTo .= $_SERVER['QUERY_STRING'];
}
header(sprintf("Location: %s", $insertGoTo));
}
 if (!isset($_SESSION)) {
session_start();
}
?>
 <!DOCTYPE html>
<html>
<body>
</body>
</html>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.rentaguide.co.za/launchefindreviews.php">
<title>view rental property reviews - view rental property ratings - view rental property rankings - view rental property complaints</title>
<meta name="keywords" content="rental, property, reviews, ratings, rankings, complaints, tentants, agenciess" />
<meta name="description" content="Find or search for rental property reviews. View the ratings of rental properties. View rental property complaints. See what others think about a rental property " />
<link href="css/findreviews.css" rel="stylesheet" type="text/css">
 </head>
 <body>
 </body>
</html>