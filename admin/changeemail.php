<?php require_once('../Connections/killjoy.php'); ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "admin/index.php";
if (!((isset($_SESSION['kj_username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['kj_username'], $_SESSION['kj_authorized'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
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

function generateRandomString($length = 24) {
    $characters = '0123456789abcdefghijklmnopqrstuvw!@#$%^&^*()';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$captcha = generateRandomString();
$captcha = urlencode($captcha);

function generatenewRandomString($length = 24) {
    $characters = '0123456789abcdefghijklmnopqrstuvw!@#$%^&^*()';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$smith = generatenewRandomString();
$smith = urlencode($smith);

$colname_rs_get_name = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_get_name = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_get_name = sprintf("SELECT g_name, g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_get_name, "text"));
$rs_get_name = mysql_query($query_rs_get_name, $killjoy) or die(mysql_error());
$row_rs_get_name = mysql_fetch_assoc($rs_get_name);
$totalRows_rs_get_name = mysql_num_rows($rs_get_name);

if (isset($_POST['g_email'])) {
	
$newemail = $_POST['g_email2'];

 $MM_dupKeyRedirect="whome.php";
  $loginUsername = $_POST['g_email2'];
  $loginName = $_POST['g_name'];
  $LoginRS__query = sprintf("SELECT g_email FROM social_users WHERE g_email=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_killjoy, $killjoy);
  $LoginRS=mysql_query($LoginRS__query, $killjoy) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername."&reqname=".$loginName;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }


	
$updateSQL = sprintf("UPDATE social_users SET g_email=%s, g_active=%s WHERE g_email=%s",
                       GetSQLValueString($newemail, "text"),
					   GetSQLValueString(0, "text"),
                       GetSQLValueString($_POST['g_email'], "text"));

mysql_select_db($database_killjoy, $killjoy);
$Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());

$updateSQL = sprintf("UPDATE tbl_address SET social_user=%s WHERE social_user=%s",
                       GetSQLValueString($newemail, "text"),
					   GetSQLValueString($_POST['g_email'], "text"));

mysql_select_db($database_killjoy, $killjoy);
$Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());

$updateSQL = sprintf("UPDATE tbl_address_comments SET social_user=%s WHERE social_user=%s",
                       GetSQLValueString($newemail, "text"),
					   GetSQLValueString($_POST['g_email'], "text"));

mysql_select_db($database_killjoy, $killjoy);
$Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());

$updateSQL = sprintf("UPDATE tbl_address_rating SET social_user=%s WHERE social_user=%s",
                       GetSQLValueString($newemail, "text"),
					   GetSQLValueString($_POST['g_email'], "text"));

mysql_select_db($database_killjoy, $killjoy);
$Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());

$updateSQL = sprintf("UPDATE tbl_propertyimages SET social_user=%s WHERE social_user=%s",
                       GetSQLValueString($newemail, "text"),
					   GetSQLValueString($_POST['g_email'], "text"));

mysql_select_db($database_killjoy, $killjoy);
$Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  
 $updateSQL = sprintf("UPDATE user_messages SET u_email=%s WHERE u_email=%s",
                       GetSQLValueString($newemail, "text"),
					   GetSQLValueString($_POST['g_email'], "text"));

mysql_select_db($database_killjoy, $killjoy);
$Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());

$password_changed_url = "changemailconfirm.php";
	
date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
$name = $_POST['g_name'];
$email = $_POST['g_email2'];
$email_1 = "friends@killjoy.co.za";
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = "killjoy.co.za";
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Username = "friends@killjoy.co.za";
$mail->Password = "806Ppe##44VX";
$mail->Port = "465";
$mail->SetFrom('friends@killjoy.co.za', 'Killjoy Community');
$mail->AddReplyTo("friends@killjoy.co.za","Killjoy Community");
$message = "<html><head><style type='text/css'>
a:link {
text-decoration: none;
}
a:visited {
text-decoration: none;
}
a:hover {
text-decoration: none;
}
a:active {
text-decoration: none;
}
body,td,th {
font-family: Tahoma, Geneva, sans-serif;
font-size: 14px;
}
body {
background-repeat: no-repeat;
margin-left:50px;
}
</style></head><body>Dear ". $name ."<br><br>Your <a href='https://www.killjoy.co.za'>killjoy.co.za</a> account email address was successfully changed.<br><br>Please <font size='4'><a style='text-decoration:none;' href='https://www.killjoy.co.za/admin/verifychangemail.php?owleyes=$captcha&verifier=$email&snowyowl=$smith'>verify your email address</a></font> to ensure it was you who requested to change your email address.<br><br>The email address change was sent from: <a href='mailto:$email'>$email</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br><br><br>Thank you, the Killjoy Community: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Killjoy Email Change";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$body = "$message\r\n";
$body = wordwrap($body, 70, "\r\n");
$mail->MsgHTML($body);
$address = $email;
$mail->AddAddress($address, "Killjoy");
$mail->AddCC($email_1, "Killjoy");
if(!$mail->Send()) {
echo "Mailer Error: " . $mail->ErrorInfo;
}
$_SESSION = array();


$newsubject = $mail->Subject;
$comments = $mail->msgHTML($body);
  $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
unset($_SESSION);
session_destroy();

setcookie("user_name", $name);
setcookie ("user_email", $email);

    header('Location: ' . filter_var($password_changed_url  , FILTER_SANITIZE_URL));
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
<title>Killjoy - change your email address</title>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/checks.css" rel="stylesheet" type="text/css" />
<link href="css/login/desktop.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="register" class="form" name="register" method="POST" action="changeemail.php">

<div class="maincontainer" id="maincontainer">
  <div class="header">Change your email</div>
  <div class="fieldlabels" id="fieldlabels">Your name:</div>
  <div class="formfields" id="formfields"><span id="sprytextfield1">
    <label>
      <input name="g_name" type="text" class="emailfield" id="g_name" value="<?php echo $row_rs_get_name['g_name']; ?>" />
    </label>
    <span class="textfieldRequiredMsg">!</span></span></div>
    <div class="fieldlabels" id="fieldlabels">Your current email:</div>
      <div class="formfields" id="formfields"><input readonly name="g_email" type="text" class="emailfield" value="<?php echo $row_rs_get_name['g_email']; ?>" /></div>
        <div class="fieldlabels" id="fieldlabels">New email:</div>
      <div class="formfields" id="formfields"><span id="sprytextfield2">
      <label>
        <input name="g_email2" type="text" class="inputfields" id="g_email" />
      </label>
      <span class="textfieldRequiredMsg">!</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></div>
        <div class="fieldlabels" id="fieldlabels">Confirm email:</div>
    <div class="formfields" id="formfields"><span id="spryconfirm1">
      <label>
        <input name="g_confirm" type="text" class="inputfields" id="g_confirm" />
      </label>
    <span class="confirmRequiredMsg">!</span><span class="confirmInvalidMsg">The emails don't match.</span></span></div>
    
  <div class="accpetfield" id="accpetfield"> <div class="accepttext">NOTE: You have to verify your new email and sign in again after you change your email address.</div></div>
    <div class="formfields" id="formfields">
    <button class="nextbutton">Continue <span class="icon-smile"></span></button>
    </div>
</div>
</form>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "g_email");
</script>
</body>
</html>
<?php
mysql_free_result($rs_get_name);
?>
