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




// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['g_email'])) {
  $loginUsername=$_POST['g_email'];
  $password=$_POST['g_pass'];
  $MM_fldUserAuthorization = "access_level";
  $MM_redirectLoginSuccess = "admin-lounge.php";
  $MM_redirectLoginFailed = "gotoadmin.php";
  $MM_redirecttoReferrer = true;
  mysqli_select_db( $killjoy, $database_killjoy);
  	
  $LoginRS__query=sprintf("SELECT g_name, g_email, g_pass, access_level FROM social_users WHERE g_email=%s AND access_level=%s",
  GetSQLValueString($loginUsername, "text"),GetSQLValueString(1, "int"));
   
  $LoginRS = mysqli_query( $killjoy, $LoginRS__query) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
   $row_LoginRS = mysqli_fetch_assoc($LoginRS); 
   
  $loginFoundUser = mysqli_num_rows($LoginRS);
   $hashedpassword = mysqli_result($LoginRS, 0, 'g_pass');
   $adminUsername = mysqli_result($LoginRS, 0, 'g_name');
  if (password_verify($password, $hashedpassword)) {
	 
    
    $loginStrGroup  = mysqli_result($LoginRS, 0, 'access_level');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['kj_adminUsername'] = $loginUsername;
	 $_SESSION['kj_adminName'] = $adminUsername;
    $_SESSION['kj_usergroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>Killjoy - register to use the app</title>
<link href="css/admin-lounge/desktop.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/checks.css" rel="stylesheet" type="text/css" />
<link href="../css/login-page/mailcomplete.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="loungeaccess" class="form" name="loungeaccess" method="POST" action="admin-lounge-access-control.php">

<div class="maincontainer" id="maincontainer">
  <div class="header">Admin Lounge Access</div>
  <div class="fieldlabels" id="fieldlabels">Username:</div>
  <div class="formfields" id="formfields"><span id="sprytextfield1">
  <label>
    <input name="g_email" type="text" class="inputfields" id="g_email" />
  </label>
  <span class="textfieldRequiredMsg">!</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></div>
  <div class="fieldlabels" id="fieldlabels">Password:</div>
      <div class="formfields" id="formfields"><span id="sprypassword1">
      <label>
          <input name="g_pass" type="password" class="inputfields" id="g_pass" />
      </label>
    <span class="passwordRequiredMsg">!</span></span></div>
<div class="accpetfield" id="accpetfield"> <div class="accepttext">This page gives you access to the admin lounge area. Please type in your <strong>admin account</strong> username and password.</div></div>
    <div class="formfields" id="formfields">
    <button class="nextbutton">Continue <span class="icon-smile"></span></button>
    </div>
</div>
<input type="hidden" name="MM_insert" value="register" />
</form>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email");

</script>
<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
</script>
</body>
</html>