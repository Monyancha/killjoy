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


if (isset($_POST["txt_comments"])){
	$newstring = htmlentities($_POST["txt_comments"], ENT_COMPAT, 'UTF-8');
  $insertSQL = sprintf("INSERT INTO tbl_review_comments (address_comment_id, social_user, social_comments, was_checked, checked_by, is_approved) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txt_commentId'], "int"),
					   GetSQLValueString($_SESSION['kj_username'], "text"),
                       GetSQLValueString($newstring, "text"),
					      GetSQLValueString(0, "int"),
					   GetSQLValueString(' ', "text"),
                       GetSQLValueString(0, "int"));

mysql_select_db($database_killjoy, $killjoy);
$Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());   


$ratingid = $_POST['txt_commentId'];
  
mysql_select_db($database_killjoy, $killjoy);
$query_get_address = sprintf("SELECT * FROM tbl_address_comments LEFT JOIN tbl_address ON tbl_address.sessionid = tbl_address_comments.sessionid LEFT JOIN tbl_review_comments ON tbl_review_comments.address_comment_id = tbl_address_comments.id WHERE tbl_address_comments.id = %s", GetSQLValueString($_POST['txt_commentId'], "int"));
$get_address = mysql_query($query_get_address, $killjoy) or die(mysql_error());
$row_get_address = mysql_fetch_assoc($get_address);
$totalRows_get_address = mysql_num_rows($get_address);

$ismail = $row_get_address['social_user'];



  
date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');

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
$message = "<!DOCTYPE html><html><head><style type='text/css'>
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
#imagepreview {
	height: 180px;
	width: 180px;
	max-width: 180px;
	margin-top: 10px;
	margin-bottom: 10px;
	margin-left: 50px;
	border: thin solid #00F;
	border-radius:5px;
}

.approve {
	font-family: Tahoma, Geneva, sans-serif;
	color: #FFF;
	height: 40px;
	width: 150px;
	text-align: center;
	vertical-align: middle;
	background-color: #0F0;
	padding-top: 5px;
	padding-bottom: 5px;
	cursor: pointer;
	font-size: 1.25em;
	display: inline-block;
	border-radius:4px
}
.reject {
	font-family: Tahoma, Geneva, sans-serif;
	color: #FF0;
	height: 40px;
	width: 150px;
	text-align: center;
	vertical-align: middle;
	background-color: #F00;
	padding-top: 5px;
	padding-bottom: 5px;
	font-size: 1.25em;
	display: inline-block;
	border-radius:4px
}
.mailtbl {
	width: 260px;
	cursor: pointer;
	position: relative;
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 1.15px;
	line-height: 1.25px;
	text-align:justify;
}


</style></head><body>Dear Killjoy Admin<br><br>Please moderate the following comment for the <strong>".$row_get_address['str_number']."&nbsp;".$row_get_address['street_name']."&nbsp;".$row_get_address['city']."</strong>review, The reference number is: &nbsp;<strong><font color='#0000FF'><strong>".$_SESSION['kj_propsession']."</strong></font></strong><br><br>.All reviews are subjected to the Terms and Conditions as stipulated by our <a href='info-centre/fair-review-policy.html'>Fair Review Policy</a>.<br><br>The rental property comment was submitted by: <a href='mailto:".$_SESSION['kj_username']."'>".$_SESSION['kj_username']."</a> on $date at $time<br><br><table class='mailtbl' border='0' cellspacing='3' cellpadding='3'>
  <tr>
    <td>Comment:<br>".$newstring."</td>
  </tr>
</table><br>
<a href='http://localhost/killjoy/admin/moderator.php'>Moderate Comments</a>
</body></html>";
$mail->Subject = "Killjoy Assess Comment";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$body = "$message\r\n";
$body = wordwrap($body, 70, "\r\n");
$mail->MsgHTML($body);
$address = "friends@killjoy.co.za";
$mail->AddAddress($address, "Killjoy");
if(!$mail->Send()) {
echo "Mailer Error: " . $mail->ErrorInfo;
}

if (filter_var($ismail, FILTER_VALIDATE_EMAIL)) {
$newsubject = "New Comment";
$comments = $mail->msgHTML($body);

$insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robors" content="noindex,nofollow" />
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>killjoy - update member property experience field</title>
</head>
<body>

</body>
</html>
