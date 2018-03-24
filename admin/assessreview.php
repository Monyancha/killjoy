<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/killjoy.php');
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
$colname_show_reviewer = "-1";
if (isset($_GET['sessionid'])) {
  $colname_show_reviewer = $_GET['sessioinid'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_show_reviewer = sprintf("SELECT sessionid, checked_by FROM tbl_approved WHERE sessionid = %s AND rating_date = %s", GetSQLValueString($colname_show_reviewer, "text"), GetSQLValueString($_GET["ratingdate"], "date"));
$show_reviewer = mysql_query($query_show_reviewer, $killjoy) or die(mysql_error());
$row_show_reviewer = mysql_fetch_assoc($show_reviewer);
$totalRows_show_reviewer = mysql_num_rows($show_reviewer);

$colname_rs_show_comments = "-1";
if (isset($_GET['sessionid'])) {
  $colname_rs_show_comments = $_GET['sessionid'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_comments = sprintf("SELECT *, social_users.g_name AS user_name, social_users.g_email AS user_email FROM tbl_address_comments LEFT JOIN tbl_approved ON tbl_approved.rating_date = tbl_address_comments.rating_date LEFT JOIN social_users on social_users.g_email = tbl_address_comments.social_user LEFT JOIN tbl_address on tbl_address.sessionid = tbl_address_comments.sessionid WHERE tbl_address_comments.sessionid = %s AND tbl_address_comments.rating_date = %s AND tbl_approved.was_checked = 0", GetSQLValueString($colname_rs_show_comments, "text"),GetSQLValueString($_GET["ratingdate"], "date"));
$rs_show_comments = mysql_query($query_rs_show_comments, $killjoy) or die(mysql_error());
$row_rs_show_comments = mysql_fetch_assoc($rs_show_comments);
$totalRows_rs_show_comments = mysql_num_rows($rs_show_comments);

if ((isset($_GET["approvebtn"])) && ($_GET["approvebtn"] == "approved")) {

  
$register_seccess_url = "reviewconfirm.php";  
  
date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s'); 

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
$name = $row_rs_show_comments['user_name'];
$email = $row_rs_show_comments['user_email'];
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
</style></head><body>Dear ". $name ."<br><br>Your review of <strong>".$row_rs_show_comments['str_number']." ".$row_rs_show_comments['street_name']." ".$row_rs_show_comments['city']."</strong> was published on $date at $time.<br><br>This is great news and means that your review is visible to the public and can be shared with others.<br><br>The rental property review was reveived from: <a href='mailto:$email'>$email</a><br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br><br><br>Thank you, the Killjoy Community: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Killjoy Review Published";
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

  $updateSQL = sprintf("UPDATE tbl_approved SET was_checked=%s, checked_by=%s, is_approved=%s WHERE sessionid=%s AND rating_date=%s",
                       GetSQLValueString(1, "int"),
					   GetSQLValueString($_GET['checkedby'], "text"),
					   GetSQLValueString(1, "int"),
                       GetSQLValueString($_GET['sessionid'], "text"),
					   GetSQLValueString($_GET['ratingdate'], "date"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  

  $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());


header('Location: ' . filter_var($register_seccess_url  , FILTER_SANITIZE_URL)); 

}	

if ((isset($_GET["declinebtn"])) && ($_GET["declinebtn"] == "declined")) {
	
	  $register_seccess_url = "reviewconfirm.php";  
	  
date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s'); 

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
$name = $row_rs_show_comments['user_name'];
$email = $row_rs_show_comments['user_email'];
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
</style></head><body>Dear ". $name ."<br><br>Your review of <strong>".$row_rs_show_comments['str_number']." ".$row_rs_show_comments['street_name']." ".$row_rs_show_comments['city']."</strong> was revoked on $date at $time.<br><br>This means that it did not meat the Terms and Conditions as definded by the <a href='https://www.killjoy.co.za/info-centre/fair-review-policy.html'>Fair Review Policy</a> guidelines.<br><br>Please review the guidelines carefully then edit your review to ensure it gets published.<br><br>The rental property review was reveived from: <a href='mailto:$email'>$email</a><br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br><br><br>Thank you, the Killjoy Community: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Killjoy Review Revoked";
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
	
  $updateSQL = sprintf("UPDATE tbl_approved SET was_checked=%s, checked_by=%s, is_approved=%s WHERE sessionid=%s AND rating_date=%s",
                       GetSQLValueString(1, "int"),
					   GetSQLValueString($_GET['checkedby'], "text"),
					   GetSQLValueString(0, "int"),
                       GetSQLValueString($_GET['sessionid'], "text"),
					   GetSQLValueString($_GET['ratingdate'], "date"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  
  
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
<meta name="robors" content="noindex,nofollow" />
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>Killjoy - review assessment area</title>
<link href="css/admins/desktop.css" rel="stylesheet" type="text/css" />
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/checks.css" rel="stylesheet" type="text/css" />
<link href="../css/login-page/mailcomplete.css" rel="stylesheet" type="text/css">
</head>
<body>
  <?php if (isset($_POST['reference'])) { //only show this div once button is clicked ?>
  <?php if ($totalRows_rs_show_comments == 0) { // Show if recordset empty ?>
  <div class="waschecked" id="waschecked">This review has already been assessed by: <a style="color:#00F" href="mailto: <?php echo $row_show_reviewer['checked_by']; ?>?subject=Killjoy Review Reference: <?php echo $row_show_reviewer['sessionid']; ?>"><?php echo $row_show_reviewer['checked_by']; ?></a></div>
    <?php } // Show if recordset empty ?>
  
  <?php } ?> 


<br />
</body>
</html>
<?php
mysql_free_result($rs_show_comments);

mysql_free_result($show_reviewer);


?>
