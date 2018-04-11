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

function hex2str( $hex ) {
  return pack('H*', $hex);
}

function str2hex( $str ) {
  return array_shift( unpack('H*', $str) );
}

$txt = 'friends@killjoy.co.za';
$hex = str2hex( $txt );
$str = hex2str( $hex );


if (isset($_COOKIE['kj_s_token'])) {
  $password_token= $_COOKIE['kj_s_token'];
}


$colname_rs_get_remember = "-1";
if (isset($_COOKIE['kj_s_identifier'])) {
  $colname_rs_get_remember = $_COOKIE['kj_s_identifier'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_get_remember = sprintf("SELECT social_users_identifier, social_users_token FROM kj_recall WHERE social_users_identifier = %s", GetSQLValueString($colname_rs_get_remember, "text"));
$rs_get_remember = mysql_query($query_rs_get_remember, $killjoy) or die(mysql_error());
$row_rs_get_remember = mysql_fetch_assoc($rs_get_remember);
$totalRows_rs_get_remember = mysql_num_rows($rs_get_remember);
$username = hex2str( $row_rs_get_remember['social_users_identifier'] );
$hashedpassword =  $row_rs_get_remember['social_users_token'];
$newpassword = password_verify($_COOKIE['kj_s_token'], $hashedpassword);

echo $username .'<br />';

echo $newpassword;
















mysql_free_result($rs_get_remember);
?>
