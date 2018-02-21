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
  mysql_select_db($database_killjoy, $killjoy);
  	
  $LoginRS__query=sprintf("SELECT g_email, g_pass, access_level FROM social_users WHERE g_email=%s",
  GetSQLValueString($loginUsername, "text"));
   
  $LoginRS = mysql_query($LoginRS__query, $killjoy) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
   $hashedpassword = $row_LoginRS['g_pass'];
  if (password_verify($password, $hashedpassword)) {
	 
    
    $loginStrGroup  = mysql_result($LoginRS,0,'access_level');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

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
    <button class="nextbutton">Continue <span class="icon-smile"></button>
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