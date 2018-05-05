<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/killjoy.php');

$kj_verifymail = "-1";
if (isset($_GET['owleyes'])) {
  $_SESSION['kj_verifymail'] = $_GET['owleyes'];
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
$colname_rs_get_address = "-1";
if (isset($_COOKIE['address_id'])) {
  $colname_rs_get_address = $_COOKIE['address_id'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_get_address = sprintf("SELECT str_number, street_name, city FROM tbl_address WHERE address_id = %s", GetSQLValueString($colname_rs_get_address, "int"));
$rs_get_address = mysql_query($query_rs_get_address, $killjoy) or die(mysql_error());
$row_rs_get_address = mysql_fetch_assoc($rs_get_address);
$totalRows_rs_get_address = mysql_num_rows($rs_get_address);

if (isset($_SESSION['kj_verifymail']) && $_SESSION['kj_verifymail'] == $_GET['owleyes']) {

$colname_rs_verifymail = "-1";
if (isset($_GET['verifier'])) {
  $colname_rs_verifymail = $_GET['verifier'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_verifymail = sprintf("SELECT g_name, g_email, g_pass, g_plain FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_verifymail, "text"));
$rs_verifymail = mysql_query($query_rs_verifymail, $killjoy) or die(mysql_error());
$row_rs_verifymail = mysql_fetch_assoc($rs_verifymail);
$totalRows_rs_verifymail = mysql_num_rows($rs_verifymail);


$email = $row_rs_verifymail['g_name'];
$password = $row_rs_verifymail['g_plain'];

if (isset($_GET['verifier'])) {
  $loginUsername=$row_rs_verifymail['g_email'];
  $loginPassword=$row_rs_verifymail['g_plain'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "https://www.killjoy.co.za/m/index.php";
  $MM_redirectLoginFailed = "register.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_killjoy, $killjoy);
  
  $LoginRS__query=sprintf("SELECT g_email, g_plain FROM social_users WHERE g_email=%s AND g_plain=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($loginPassword, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $killjoy) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['kj_username'] = $loginUsername;
    $_SESSION['kj_usergroup'] = $loginStrGroup;	
	$_SESSION['kj_authorized'] = "1";     

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
	
	$updateSQL = sprintf("UPDATE social_users SET g_plain=%s, g_active=%s WHERE g_email=%s",
                       GetSQLValueString(NULL, "text"),
					   GetSQLValueString(1, "text"),
                       GetSQLValueString($_GET['verifier'], "text"));
  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  
  date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s'); 
  
  
require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
$name = $row_rs_verifymail['g_name'];
$email = $row_rs_verifymail['g_email'];
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
font-size: 14px;
}
body {
background-repeat: no-repeat;
margin-left:50px;
}
</style></head><body>Dear ". $name ."<br><br>Thank you for verifying your email address<br><br>You may now enjoy all the benefits that this app offer.<br><br>The request to join Killjoy was sent from: <a href='mailto:$email'>$email</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br>Thank you, the online Killjoy Community: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Email Address Verified";
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
    unset($_SESSION['kj_verifymail']);
		
		
	$comments = $mail->msgHTML($body);
    $insertSQL = sprintf("UPDATE user_messages SET u_read = %s WHERE u_email=%s",
                       GetSQLValueString(1 , "int"),
                       GetSQLValueString($email, "text"));

    mysql_select_db($database_killjoy, $killjoy);
     $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
	 
	 $newsubject = $mail->Subject;
    $comments = $mail->msgHTML($body);
    $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

    mysql_select_db($database_killjoy, $killjoy);
     $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
	 
	   $addressid = -1;
  if (isset($_COOKIE["address_id"])) {
   $addressid = $_COOKIE["address_id"];
  }
  $updateSQL = sprintf("UPDATE tbl_address SET social_user=%s WHERE address_id = %s",
  			            GetSQLValueString($email, "text"),
					    GetSQLValueString($addressid, "int"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  
    $commentid = -1;
  if (isset($_COOKIE["comment_id"])) {
   $commentid  = $_COOKIE["comment_id"];
  }
     $updateSQL = sprintf("UPDATE tbl_address_comments SET social_user=%s WHERE id = %s",
  			            GetSQLValueString($email, "text"),
					    GetSQLValueString($commentid, "int"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  
      $ratingid = -1;
  if (isset($_COOKIE["rating_id"])) {
   $ratingid  = $_COOKIE["rating_id"];
  }
      $updateSQL = sprintf("UPDATE tbl_address_rating SET social_user=%s WHERE id = %s",
  			            GetSQLValueString($email, "text"),
					    GetSQLValueString($ratingid, "int"));
						
   $imageid = -1;
  if (isset($_COOKIE["image_id"])) {
   $imageid = $_COOKIE["image_id"];
  }
  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  
        $updateSQL = sprintf("UPDATE tbl_propertyimages SET social_user=%s WHERE id = %s",
  			            GetSQLValueString($email, "text"),
					    GetSQLValueString($imageid, "int"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());

$newsubject = "Property Review Completed";
$comments = "Dear ".$name."<br><br>You are a superstar!. Your review for <strong>".$row_rs_get_address['str_number'].", ".$row_rs_get_address['street_name'].",".$row_rs_get_address['city']." has been recorded<br><br>To make changes to your review <a href='myreviews.php'>Click here</a><br><br>Thank you from all of us at <a href='https://www.killjoy.co.za'>killjoy.co.za</a>";
  $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
  
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="canonical" href="https://www.killjoy.co.za/">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
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
<title>killjoy.co.za - verify email after signup</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($rs_verifymail);

mysql_free_result($rs_get_address);
?>
