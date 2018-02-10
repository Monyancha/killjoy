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
	
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "register")) {
	
	$password = $_POST['g_pass'];
$password = password_hash($password, PASSWORD_BCRYPT);
  $updateSQL = sprintf("UPDATE social_users SET g_name=%s, g_pass=%s WHERE g_email=%s",
                       GetSQLValueString($_POST['g_name'], "text"),
                       GetSQLValueString($password, "text"),
                       GetSQLValueString($_POST['g_email'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  
require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');	
$successgoto = "rentalcomplete.php";
$subject = $_POST['subject'];
$comments = $_POST['enquiry'];
$name = $_POST['name'];
$email = $_POST['email'];
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = "host25.axxesslocal.co.za";
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Username = "celebrate@stomer.co.za";
$mail->Password = "Gs@Ry6.@9drK";
$mail->Port = "465";
$mail->SetFrom('celebrate@stomer.co.za', 'Online Enquiry');
$mail->AddReplyTo("$email");
$message = "<html>

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
</style></head><body>Dear St. Omer<br><br>Please review this enquiry in connection with <strong>".$subject."</strong><br><br>
From: <a href='mailto:$email'>$email</a><br><br>Name: $name<br><br>Comments: $comments<br><br><br><br>Thank you, the online St. Omer Community: https://www.stomer.co.za<br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:celebrate@stomer.co.za'>St. Omer Nursery</a></font><br><br></body></html>";
$mail->Subject    = "Online Enquiry";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$body = "$message\r\n";
$body = wordwrap($body, 70, "\r\n");
$mail->MsgHTML($body);
$address = 'jthom@mweb.co.za';
$mail->AddAddress($address, "St. Omer Online");
if(!$mail->Send()) {
echo "Mailer Error: " . $mail->ErrorInfo;
}

 echo '<div id="notexist" class="completeexist"><div class="completecells">Dear '.$name.'</div><div class="completecells">Thank you, your enquiry has reached our inbox</div><div class="completecells">We will respond shortly</div><div class="completecells"><a href="../contact-us.php">Close</a></div></div>';
  

  
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>Killjoy - register to use the app</title>
<link href="css/desktop.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationCheckbox.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationCheckbox.css" rel="stylesheet" type="text/css" />

<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/checks.css" rel="stylesheet" type="text/css" />
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
  <div class="accpetfield" id="accpetfield"><span id="sprycheckbox1">
    <label>
      <input type="checkbox" name="g_accept" id="g_accept" />
      <div class="accepttext">I agree to the Killjoy <a href="../info-centre/terms-of-use.html">site terms</a> and <a href="../info-centre/help-centre.html">usage policy</a></div> 
      </label>
    <span class="checkboxRequiredMsg">!</span></span></div>
    <div class="formfields" id="formfields">
    <button class="nextbutton">Continue <span class="icon-smile"></button>
    </div>
</div>
<input type="hidden" name="MM_insert" value="register" />
</form>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
</script>

<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "g_pass");
var sprycheckbox1 = new Spry.Widget.ValidationCheckbox("sprycheckbox1");
</script>
</body>
</html>