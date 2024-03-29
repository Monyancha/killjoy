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

$colname_get_address = "-1";
if (isset($_SESSION['sessionid'])) {
  $colname_get_address = $_SESSION['sessionid'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_get_address = sprintf("SELECT * FROM tbl_address WHERE sessionid = %s", GetSQLValueString($colname_get_address, "text"));
$get_address = mysqli_query( $killjoy, $query_get_address) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_get_address = mysqli_fetch_assoc($get_address);
$totalRows_get_address = mysqli_num_rows($get_address);

$colname_rs_user_details = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_user_details = $_SESSION['kj_username'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_user_details = sprintf("SELECT * FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_user_details, "text"));
$rs_user_details = mysqli_query( $killjoy, $query_rs_user_details) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_user_details = mysqli_fetch_assoc($rs_user_details);
$totalRows_rs_user_details = mysqli_num_rows($rs_user_details);

if (isset($_SESSION["kj_username"])) {
  $updateSQL = sprintf("UPDATE social_users SET location_sharing=%s WHERE g_email=%s",
                       GetSQLValueString($_POST['location'], "int"),
                       GetSQLValueString($_SESSION["kj_username"], "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
  date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s'); 
  
require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
$name = $row_rs_user_details['g_name'];
$email = $row_rs_user_details['g_email'];
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
font-size: 20px;
}
body {
background-repeat: no-repeat;
margin-left:50px;
}
</style></head><body>Dear ". $name ."<br><br>Your <a href='https://www.killjoy.co.za'>killjoy.co.za</a> location settings have been updated<br><br>If you made any changes to your email address or passwords, <a href='https://www.killjoy.co.za/admin/index.php'>please sign in </a>again to ensure the new changes are reflected.<br><br>The updates to your killjoy.co.za account was sent from: <a href='mailto:$email'>$email</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br>Thank
you, the Killjoy Community: <a href='https://www.killjoy.co.za'>https://www.killjoy.co.za</a><br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Killjoy Profile Updated";
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

$newsubject = $mail->Subject;
$comments = $mail->msgHTML($body);
  $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robors" content="noindex,nofollow" />
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>killjoy - update member property mood field</title>
</head>

<body>
</body>
</html>
<?php
((mysqli_free_result($get_address) || (is_object($get_address) && (get_class($get_address) == "mysqli_result"))) ? true : false);
?>
