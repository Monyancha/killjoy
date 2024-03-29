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

$MM_restrictGoTo = "index.php";
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

function generateRandomString($length = 10) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}
$sessionid = generateRandomString();

$colname_rs_member_profile = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_member_profile = $_SESSION['kj_username'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_member_profile = sprintf("SELECT g_name, g_email, g_image, DATE_FORMAT(created_date, '%%M %%D, %%Y') as joined_date, g_social AS social FROM social_users WHERE g_email = %s AND g_active =1", GetSQLValueString($colname_rs_member_profile, "text"));
$rs_member_profile = mysqli_query( $killjoy, $query_rs_member_profile) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_member_profile = mysqli_fetch_assoc($rs_member_profile);
$totalRows_rs_member_profile = mysqli_num_rows($rs_member_profile);

if (isset($_COOKIE['kj_s_identifier'])) {
  $deleteSQL = sprintf("DELETE FROM kj_recall WHERE social_users_identifier=%s",
                       GetSQLValueString($_COOKIE['kj_s_identifier'], "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $deleteSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "update")) {
$register_success_url = "../index.php";


  $copySQL = sprintf("INSERT INTO inactive_users SELECT * FROM social_users WHERE g_email = %s",
                        GetSQLValueString($_SESSION['kj_username'], "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $copySQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
  
$password = password_hash($sessionid, PASSWORD_BCRYPT);
  $updateSQL = sprintf("DELETE FROM social_users WHERE g_email = %s",
                     GetSQLValueString($_SESSION['kj_username'], "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
    
    $updateSQL = sprintf("DELETE FROM user_messages WHERE u_email = %s",                                   
					   GetSQLValueString($_SESSION['kj_username'], "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s'); 
  
require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
$name = $_POST['g_name'];
$email = $_POST['g_email'];
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
font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
font-size: 16px;
}
body {
background-repeat: no-repeat;
margin-left:50px;
}
</style></head><body>Dear ". $name ."<br><br>Your <a href='https://www.killjoy.co.za'>killjoy.co.za</a> account has been deleted<br><br>Please note that it can take up to two weeks for your related information to be deleted. We are right on it.<br><br>The request to delete your killjoy.co.za account was sent from: <a href='mailto:$email'>$email</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br>Thank
you, the Killjoy Community: <a href='https://www.killjoy.co.za'>https://www.killjoy.co.za</a><br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "killjojy.co.za Account Deactivated";
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
unset($_SESSION);
session_destroy();
header("location:index.php");

if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
	
	if (isset($_COOKIE['kj_recallmember'])) {
		
		
	}
}

header('Location: ' . filter_var($register_success_url  , FILTER_SANITIZE_URL));

}

$colname_show_error = "-1";
if (isset($_SESSION['sessionid'])) {
  $colname_show_error = $_SESSION['sessionid'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_show_error = sprintf("SELECT * FROM tbl_uploaderror WHERE sessionid = %s", GetSQLValueString($colname_show_error, "text"));
$show_error = mysqli_query( $killjoy, $query_show_error) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_show_error = mysqli_fetch_assoc($show_error);
$totalRows_show_error = mysqli_num_rows($show_error);

$colname_rs_profile_image = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_profile_image = $_SESSION['kj_username'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_profile_image = sprintf("SELECT g_image, id AS id FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_profile_image, "text"));
$rs_profile_image = mysqli_query( $killjoy, $query_rs_profile_image) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_profile_image = mysqli_fetch_assoc($rs_profile_image);
$totalRows_rs_profile_image = mysqli_num_rows($rs_profile_image);
$id = $row_rs_profile_image['id'];?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robors" content="noindex,nofollow" />
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<link rel="alternate" href="https://www.killjoy.co.za/" hreflang="en" />
<link rel="apple-touch-icon" sizes="57x57" href="../favicons/apple-icon-57x57.png" />
<link rel="apple-touch-icon" sizes="60x60" href="../favicons/apple-icon-60x60.png" />
<link rel="apple-touch-icon" sizes="72x72" href="../favicons/apple-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="76x76" href="../favicons/apple-icon-76x76.png" />
<link rel="apple-touch-icon" sizes="114x114" href="../favicons/apple-icon-114x114.png" />
<link rel="apple-touch-icon" sizes="120x120" href="../favicons/apple-icon-120x120.png" />
<link rel="apple-touch-icon" sizes="144x144" href="../favicons/apple-icon-144x144.png" />
<link rel="apple-touch-icon" sizes="152x152" href="../favicons/apple-icon-152x152.png" />
<link rel="apple-touch-icon" sizes="180x180" href="../favicons/apple-icon-180x180.png" />
<link rel="icon" type="image/png" sizes="192x192"  href="../favicons/android-icon-192x192.png" />
<link rel="icon" type="image/png" sizes="32x32" href="../favicons/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="96x96" href="../favicons/favicon-96x96.png" />
<link rel="icon" type="image/png" sizes="16x16" href="../favicons/favicon-16x16.png" />
<link rel="manifest" href="/manifest.json" />
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Killjoy - deactivate member profile</title>
<link href="../css/deactivate-account/profile.css" rel="stylesheet" type="text/css" />
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="../css/property-reviews/checks.css" rel="stylesheet" type="text/css" />
</head>
<body onLoad="set_session()">

<div class="maincontainer" id="formcontainer">
 <div class="formheader">Deactivate Account</div>
 <form id="register" class="form" name="register" method="POST" action="deactivateacc.php">
  <div class="fieldlabels" id="fieldlabels">Your name:</div>
  <div class="formfields" id="formfields">
    <label>
      <input readonly="readonly" name="g_name" type="text" class="emailfield" id="g_name" value="<?php echo $row_rs_member_profile['g_name']; ?>" />
    </label>
    </div>
    <div class="fieldlabels" id="fieldlabels">Your email:</div>
      <div class="formfields" id="formfields"><input readonly name="g_email" type="text" class="emailfield" value="<?php echo $row_rs_member_profile['g_email']; ?>" />
      </div>
    <div class="accpetfield" id="accpetfield">
      <div class="accepttext">Your account will be deactivated immediately, but it can take up to 2 weeks to remove any linked information to your account.</div></div>
         <div class="remember"><input enabled type="checkbox" name="remember_me" id="remember_me" value="1" /><label for="remember_me">I understand the implications</label></div>
    <div class="formfields" id="formfields">
    <button disabled="disabled" id="deactivatebtn" class="nextbutton">Deactivate <span class="icon-exclamation-circle"></span></button>
    </div>
    <input type="hidden" name="MM_insert" value="update" />
</form>
</div>

<script>
	$('#remember_me').click(function(){
     
    if($(this).attr('checked') == false){
         $('#deactivatebtn').attr("disabled","disabled");   
    }
    else {
        $('#deactivatebtn').removeAttr('disabled');
		$('#remember_me').attr('disabled', true);
    }
});
</script>

</body>
</html>

