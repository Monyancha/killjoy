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
  

 $rowID = $_POST["property_id"]; // ◄■■ PARAMETER FROM AJAX.
 $_SESSION['_kj_rv_session'] = $rowID;
 setcookie("is_anchor", "1");
 
 
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
 <?php
mysql_free_result($Recordset1);
?>
