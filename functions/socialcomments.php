<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

$page =-1;
if (isset($_SESSION['PrevUrl'])) {
$page = $_SESSION['PrevUrl'] ;	
}
require_once('../Connections/killjoy.php');
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
$colname_rs_show_name = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_show_name = $_SESSION['kj_username'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_show_name = sprintf("SELECT g_name FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_show_name, "text"));
$rs_show_name = mysqli_query( $killjoy, $query_rs_show_name) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_show_name = mysqli_fetch_assoc($rs_show_name);
$totalRows_rs_show_name = mysqli_num_rows($rs_show_name);


if (isset($_POST["txt_comments"])){
$newstring = htmlentities($_POST["txt_comments"], ENT_COMPAT, 'UTF-8');
$insertSQL = sprintf("INSERT INTO tbl_review_comments (address_comment_id, social_user, social_comments, was_checked, checked_by, is_approved) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['txt_commentId'], "int"),
					   GetSQLValueString($_SESSION['kj_username'], "text"),
                       GetSQLValueString($newstring, "text"),
					   GetSQLValueString(0, "int"),
					   GetSQLValueString(' ', "text"),
                       GetSQLValueString(1, "int"));

mysqli_select_db( $killjoy, $database_killjoy);
$Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));   


$ratingid = $_POST['txt_commentId'];
  
mysqli_select_db( $killjoy, $database_killjoy);
$query_get_address = sprintf("SELECT tbl_address.str_number, tbl_address.city, tbl_address.street_name, social_users.g_name as reviewerName, tbl_address_comments.social_user as revieWer FROM tbl_address_comments LEFT JOIN tbl_address ON tbl_address.sessionid = tbl_address_comments.sessionid LEFT JOIN social_users ON social_users.g_email=tbl_address_comments.social_user WHERE tbl_address_comments.id = %s", GetSQLValueString($_POST['txt_commentId'], "int"));
$get_address = mysqli_query( $killjoy, $query_get_address) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_get_address = mysqli_fetch_assoc($get_address);
$totalRows_get_address = mysqli_num_rows($get_address);

$ismail = $row_get_address['revieWer'];
  
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
font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
font-size: 20px;
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
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
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
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
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
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
	font-size: 1.15px;
	line-height: 1.25px;
	text-align:justify;
}


</style></head><body>Dear Killjoy Admin<br><br>Please moderate the following comment for the <strong>".$row_get_address['str_number']."&nbsp;".$row_get_address['street_name']."&nbsp;".$row_get_address['city']."</strong> review.<br><br>All reviews are subjected to the Terms and Conditions as stipulated by our <a href='https://www.killjoy.co.za/info-centre/fair-review-policy.html'>Fair Review Policy</a>.<br><br>The rental property comment was submitted by: <a href='mailto:".$_SESSION['kj_username']."'>".$_SESSION['kj_username']."</a> on $date at $time<br><br><table class='mailtbl' border='0' cellspacing='3' cellpadding='3'>
  <tr>
    <td>Comment:<br>".$newstring."</td>
  </tr>
</table><br>
<a href='https://www.killjoy.co.za/admin/moderator.php'>Moderate Comments</a>
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

unset($_SESSION['PrevUrl']);
$_SESSION['PrevUrl'] = NULL;


if (filter_var($ismail, FILTER_VALIDATE_EMAIL)) {
$newsubject = "".$row_rs_show_name['g_name']." Commented";
$comments = "".$row_rs_show_name['g_name']." commented on your review for <strong>".$row_get_address['str_number']."&nbsp;".$row_get_address['street_name']."&nbsp;".$row_get_address['city']."</strong>.<br><br><a href='".$page."'>View the Comment</a> ";

$insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($ismail, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
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
font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
font-size: 20px;
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
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
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
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
	color: #FFF;
	height: 40px;
	width: 200px;
	text-align: center;
	vertical-align: middle;
	background-color: #1B95E0;
	padding-top: 5px;
	padding-bottom: 5px;
	font-size: 1.25em;
	border-radius:4px;
	padding-right: 10px;
	padding-left: 10px;
	font-weight: 500;
}
.mailtbl {
	width: 260px;
	cursor: pointer;
	position: relative;
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
	font-size: 1.15px;
	line-height: 1.25px;
	text-align:justify;
}


</style></head><body>Dear ".$row_get_address['reviewerName']."<br><br>".$row_rs_show_name['g_name']." commented on your review for <strong>".$row_get_address['str_number']."&nbsp;".$row_get_address['street_name']."&nbsp;".$row_get_address['city']."</strong>.<br><br><a class='reject' href='https://www.killjoy.co.za/".$page."'>  View the Comment  </a><br><br><font size='2'>You are receving this email because you are a member of the <a href='https://www.killjoy.co.za'>killjoy.co.za Community</a>. Please <a href='mailto:friends@killjoy.co.za?subject=Unsubscribe'>let us know</a> if you wish to unsubscribe.</font>
</body></html>";
$mail->Subject = "".$row_rs_show_name['g_name']." commented on your review";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$body = "$message\r\n";
$body = wordwrap($body, 70, "\r\n");
$mail->MsgHTML($body);
$address = $ismail;
$mail->AddAddress($address, "Killjoy");
if(!$mail->Send()) {
echo "Mailer Error: " . $mail->ErrorInfo;
}
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
