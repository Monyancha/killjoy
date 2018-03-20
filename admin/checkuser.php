<?php require_once('../Connections/killjoy.php'); ?>
<?php
if (!isset($_SESSION)) {
session_start();
}
function str2hex( $str ) {
  return array_shift( unpack('H*', $str) );
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

if (isset($_POST['usermail']) && $_POST['usermail'] != " ") {
$colname_rs_checkuser = "-1";
if (isset($_POST['usermail'])) {
  $colname_rs_checkuser = $_POST['usermail'];
}
$user_exists = "login.php";
$user_not_exists = "register.php"; 
mysql_select_db($database_killjoy, $killjoy);
$query_rs_checkuser = sprintf("SELECT g_email, g_active FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_checkuser, "text"));
$rs_checkuser = mysql_query($query_rs_checkuser, $killjoy) or die(mysql_error());
$row_rs_checkuser = mysql_fetch_assoc($rs_checkuser);
$totalRows_rs_checkuser = mysql_num_rows($rs_checkuser);
$isverified = $row_rs_checkuser[''];


if (isset($_SESSION['remember_me'])) {
	
	$identifier = $_POST['usermail'];	
	$session_identifier = str2hex( $identifier  );
	setcookie("kj_s_identifier", $session_identifier, time()+31556926 ,'/');
	$token = bin2hex(openssl_random_pseudo_bytes(16));
	setcookie("kj_s_token", $token, time()+31556926 ,'/');
}


    if($totalRows_rs_checkuser) //user id exist in database
    {
		$_SESSION['user_email'] = $_POST['usermail'];
		
		//redirect to login page
		header('Location: ' . filter_var($user_exists  , FILTER_SANITIZE_URL));
        }else if($totalRows_rs_checkuser > 0 && $isverified = "0") { //user has not verified their email
		$_SESSION['user_email'] = $_POST['usermail'];
	
			//redirect to create new user page				   
	header('Location: ' . filter_var($user_not_exists  , FILTER_SANITIZE_URL));

	} else { //user is new
		
		$_SESSION['user_email'] = $_POST['usermail'];
	
			//redirect to create new user page				   
	header('Location: ' . filter_var($user_not_exists  , FILTER_SANITIZE_URL));
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="alternate" href="https://www.killjoy.co.za/" hreflang="en" />
<link rel="apple-touch-icon" sizes="57x57" href="favicons/apple-icon-57x57.png" />
<link rel="apple-touch-icon" sizes="60x60" href="favicons/apple-icon-60x60.png" />
<link rel="apple-touch-icon" sizes="72x72" href="favicons/apple-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="76x76" href="favicons/apple-icon-76x76.png" />
<link rel="apple-touch-icon" sizes="114x114" href="favicons/apple-icon-114x114.png" />
<link rel="apple-touch-icon" sizes="120x120" href="favicons/apple-icon-120x120.png" />
<link rel="apple-touch-icon" sizes="144x144" href="favicons/apple-icon-144x144.png" />
<link rel="apple-touch-icon" sizes="152x152" href="favicons/apple-icon-152x152.png" />
<link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-icon-180x180.png" />
<link rel="icon" type="image/png" sizes="192x192"  href="favicons/android-icon-192x192.png" />
<link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="96x96" href="favicons/favicon-96x96.png" />
<link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png" />
<link rel="manifest" href="/manifest.json" />
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>killjoy.co.za - check if user exists</title>
</head>

<body>
</body>
</html>

<?php
mysql_free_result($rs_checkuser);
?>
