<?php    
 ob_start();
 if (!isset($_SESSION)) {
 session_start();
 }
 require_once('Connections/rentaguide.php');
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
 $rowID = $_POST["my_id"]; // ◄■■ PARAMETER FROM AJAX.
 $_SESSION['_ra_rv_session'] = $rowID;
   $clearanchor = "UPDATE tbl_reviews SET is_anchor = 0";
 				 
 mysqli_select_db( $rentaguide, $database_rentaguide);
 $Result = mysqli_query( $rentaguide, $clearanchor) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
 $setanchor = "UPDATE tbl_reviews SET is_anchor = 1 WHERE sessionid = '$rowID'";
 				 
 mysqli_select_db( $rentaguide, $database_rentaguide);
 $Result1 = mysqli_query( $rentaguide, $setanchor) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <meta http-equiv="content-language" content="en-za">
 <link rel="canonical" href="https://www.rentaguide.co.za/index.php">
 <title>view property rentals - search property rentals- view rental property details</title>
 <meta name="keywords" content="property, rentals, find, search, offers, view, houses, flats, tolet, rent, apartment, share" />
 <meta name="description" content="Find property rentals in your city or town. Search for rentals in your area." />
 </head>
 <body>
 </body>
 </html>