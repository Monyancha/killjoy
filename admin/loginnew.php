<?php require_once('../Connections/killjoy.php'); ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
  $browser = 'Internet Explorer';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
   $browser = 'Internet Explorer';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
 $browser = 'Mozilla Firefox';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
  $browser = 'Google Chrome';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
  $browser = "Opera Mini";
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
  $browser = "Opera";
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
 $browser = "Safari";
 else
  $browser = 'Something else'; 
  
  function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


$user_ip = getUserIP();


  
$login_failed = "-1";
if (isset($_SESSION['login_failed'])) {
  $autherror = "1";
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


$colname_rs_get_name = "-1";
if (isset($_SESSION['user_email'])) {
  $colname_rs_get_name = $_SESSION['user_email'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_get_name = sprintf("SELECT * FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_get_name, "text"));
$rs_get_name = mysql_query($query_rs_get_name, $killjoy) or die(mysql_error());
$row_rs_get_name = mysql_fetch_assoc($rs_get_name);
$totalRows_rs_get_name = mysql_num_rows($rs_get_name);
$user_id = $row_rs_get_name['id'];
$userpass = $row_rs_get_name['g_pass'];


if (isset($_POST['g_email']) && $_POST['g_email'] != " ") {	
	$colname_rs_recall_exist = "-1";
if (isset($_COOKIE['kj_s_identifier'])) {
  $colname_rs_recall_exist = $_COOKIE['kj_s_identifier'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_recall_exist = sprintf("SELECT * FROM kj_recall WHERE social_users_identifier = %s", GetSQLValueString($colname_rs_recall_exist, "text"));
$rs_recall_exist = mysql_query($query_rs_recall_exist, $killjoy) or die(mysql_error());
$row_rs_recall_exist = mysql_fetch_assoc($rs_recall_exist);
$totalRows_rs_recall_exist = mysql_num_rows($rs_recall_exist);

if (!$totalRows_rs_recall_exist) {
	
	if (isset($_SESSION['remember_me'])) {
	  $social_identifier = htmlspecialchars($_COOKIE['kj_s_identifier']);
	  $session_token = $_COOKIE['kj_s_token'];
	  $session_token = password_hash($session_token, PASSWORD_BCRYPT);
	  $insertSQL = sprintf("INSERT INTO kj_recall (social_users_identifier, social_users_token, request_platform, user_agent, user_ip_address) VALUES(%s, %s, %s, %s, %s)",
	                   GetSQLValueString($social_identifier, "text"),
					   GetSQLValueString($session_token, "text"),
					   GetSQLValueString("killjoy", "text"),
					   GetSQLValueString($browser, "text"),
					    GetSQLValueString($user_ip, "text"));


  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
  
	
}
}
  $loginUsername=$_POST['g_email'];
  $password=$_POST['g_pass'];
  $MM_fldUserAuthorization = "";  
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_killjoy, $killjoy);
  
  $LoginRS__query=sprintf("SELECT g_email, g_pass FROM social_users WHERE g_email=%s",
  GetSQLValueString($loginUsername, "text"));
	   
  $LoginRS = mysql_query($LoginRS__query, $killjoy) or die(mysql_error());
  $row_LoginRS = mysql_fetch_assoc($LoginRS);  
 
  $loginFoundUser = mysql_num_rows($LoginRS);
   $hashedpassword = $row_LoginRS['g_pass'];
  if (password_verify($password, $hashedpassword)) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['kj_username'] = $loginUsername;
    $_SESSION['kj_usergroup'] = $loginStrGroup;	
	$_SESSION['kj_authorized'] = "1";       

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
	  
    } else {
	
	$MM_redirectLoginSuccess = "../index.php";
	
	}
	
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
font-family: Tahoma, Geneva, sans-serif;
font-size: 14px;
}
body {
background-repeat: no-repeat;
margin-left:50px;
}
</style></head><body>Dear ". $name ."<br><br>You have been logged into <a href='https://www.killjoy.co.za'>your Killjoy account</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy Alerts</a><br><br>Thank you, the Killjoy Community: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Killjoy Security Alert";
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
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
	$_SESSION['login_failed'] = "1";
    header("Location: ". $MM_redirectLoginFailed );
  }
}
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<title>Killjoy - login</title>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="css/login/desktop.css" rel="stylesheet" type="text/css">
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="register" class="form" name="register" method="POST" action="loginnew.php">

<div class="maincontainer" id="maincontainer">
  <div class="header">Sign into killjoy.co.za</div>
  <div class="fieldlabels" id="fieldlabels">Your name:</div>
  <div class="formfields" id="formfields"><span id="sprytextfield1">
    <label>
      <input name="g_name" type="text" class="emailfield" id="g_name" value="<?php echo $row_rs_get_name['g_name']; ?>" />
    </label>
    <span class="textfieldRequiredMsg">!</span></span></div>
    <div class="fieldlabels" id="fieldlabels">Your email:</div>
      <div class="formfields" id="formfields"><input readonly name="g_email" type="text" class="emailfield" value="<?php echo $_SESSION['user_email']; ?>" /></div>
    <div class="fieldlabels" id="fieldlabels">Password:</div>
     <?php if (isset($_SESSION['login_failed']) && $_SESSION['login_failed'] == 1) { ?><div class="errorlabel" id="errorlabel">The password is incorrect</div><?php }?> 
      <div class="formfields" id="formfields"><span id="sprypassword1">
      <label>
          <input name="g_pass" type="password" class="inputfields" id="g_pass" />
      </label>
    <span class="passwordRequiredMsg">!</span></span></div>
    <div class="formfields" id="formfields"></div>
  <div class="accpetfield" id="accpetfield"> <div class="accepttext"><a href="forgot.php">I forgot my account details<span style="font-size:1.5em; padding-left:10px; vertical-align:middle;" class="icon-frown-o"> </span></a></div></div>
    <div class="formfields" id="formfields">
    <button class="nextbutton">Continue <span class="icon-smile"></span></button>
    </div>
</div>
<input type="hidden" name="MM_insert" value="register" />
</form>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
</script>
<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
</script>
</body>
</html>
<?php
mysql_free_result($rs_get_name);


?>
