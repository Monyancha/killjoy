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





function hex2str( $hex ) {
  return pack('H*', $hex);
}

function str2hex( $str ) {
  return array_shift( unpack('H*', $str) );
}

$txt = 'friends@killjoy.co.za';
$hex = str2hex( $txt );
$str = hex2str( $hex );

echo "{$txt} => {$hex} => {$str}\n";

$colname_get_user = "-1";
if (isset($_COOKIE['kj_s_identifier'])) {
  $colname_get_user = $_COOKIE['kj_s_identifier'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_get_user = sprintf("SELECT social_users.g_email,  social_users_sessionid, social_users_identifier FROM kj_recall LEFT JOIN social_users ON social_users.g_email = kj_recall. social_users_identifier WHERE social_users_identifier = %s", GetSQLValueString($colname_get_user, "text"));
$get_user = mysql_query($query_get_user, $killjoy) or die(mysql_error());
$row_get_user = mysql_fetch_assoc($get_user);
$totalRows_get_user = mysql_num_rows($get_user);

ob_start();
if (!isset($_SESSION)) {
session_start();
}



mysql_free_result($get_user);
?>
