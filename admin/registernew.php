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

	
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "register")) {
	$password = $_POST['g_pass'];
	$plainpassword = $_POST['g_passc'];
$password = password_hash($password, PASSWORD_BCRYPT);

  $updateSQL = sprintf("INSERT INTO social_users (g_name, g_email, g_pass, g_plain, g_image) VALUES(%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['g_name'], "text"),
                       GetSQLValueString($_POST['g_email'], "text"),
					   GetSQLValueString($password, "text"),
                       GetSQLValueString($plainpassword, "text"),
					   GetSQLValueString("media/profile.png", "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  
$user_id = mysql_insert_id();

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
	  $insertSQL = sprintf("INSERT INTO kj_recall (social_users_identifier, social_users_token) VALUES(%s, %s)",
	                   GetSQLValueString($social_identifier, "text"),
					   GetSQLValueString($session_token, "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
	
}
}
$register_seccess_url = "registerconfirm.php";  

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
</style></head><body>Dear ". $name ."<br><br>We are delighted that you joined the killjoy community.<br><br>We will do our utmost to ensure you enjoy every feature that this app has to offer.<br><br>Please <font size='4'><a style='text-decoration:none;' href='https://www.killjoy.co.za/admin/verifymail.php?owleyes=$captcha&verifier=$email&snowyowl=$smith'>verify your email address</a></font> to ensure it was you who requested to join the commpunity.<br><br>The request to join Killjoy was sent from: <a href='mailto:$email'>$email</a> on $date at $time.<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br><br><br>Thank you, the Killjoy Community: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Killjoy.co.za Account Created";
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

$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;
unset($_SESSION['remember_me']);

$newsubject = $mail->Subject;
$comments = $mail->msgHTML($body);
  $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
  
header('Location: ' . filter_var($register_seccess_url  , FILTER_SANITIZE_URL));

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
<link href="css/desktop.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/checks.css" rel="stylesheet" type="text/css" />
<link href="../css/login-page/mailcomplete.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="register" class="form" name="register" method="POST" action="registernew.php">

<div class="maincontainer" id="maincontainer"><div class="header">Create New Account</div>
  <div class="fieldlabels" id="fieldlabels">Your name:</div>
  <div class="formfields" id="formfields"><span id="sprytextfield1">
    <label>
      <input name="g_name" type="text" class="inputfields" id="g_name" />
    </label>
    <span class="textfieldRequiredMsg">!</span></span></div>
    <div class="fieldlabels" id="fieldlabels">Your email:</div>
      <div class="formfields" id="formfields"><input readonly name="g_email" type="text" class="emailfield" value="<?php echo $_SESSION['user_email']; ?>" /></div>
    <div class="fieldlabels" id="fieldlabels">Password:</div>
      <div class="formfields" id="formfields"><span id="sprypassword1">
      <label>
          <input name="g_pass" type="password" class="inputfields" id="g_pass" />
      </label>
    <span class="passwordRequiredMsg">!</span></span></div>
    <div class="fieldlabels" id="fieldlabels">Retype Password:</div>
<div class="formfields" id="formfields"><span id="spryconfirm1">
  <label>
    <input name="g_passc" type="password" class="inputfields" id="g_passc" />
  </label>
  <span class="confirmRequiredMsg">!</span><span class="confirmInvalidMsg">The passwords don't match.</span></span></div>
  <div class="accpetfield" id="accpetfield"> <div class="accepttext">By clicking Continue, you agree to our <a href="../info-centre/terms-of-use.html">Site Terms</a> and confirm that you have read our <a href="../info-centre/help-centre.html">Usage Policy,</a> including our <a href="../info-centre/cookie-policy.php">Cookie Usage Policy.</a></div> </div>
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
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "g_pass");
</script>
</body>
</html>