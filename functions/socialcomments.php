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


if ((isset($_POST["txt_comments"])) && ($_POST["txt_comments"] != "")) {
  $insertSQL = sprintf("INSERT INTO tbl_review_comments (address_comment_id, social_user, social_comments) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['txt_commentId'], "text"),
					   GetSQLValueString($_SESSION['kj_username'], "text"),
                       GetSQLValueString($_POST['txt_comments'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());

$colname_get_address = "-1";
if (isset($_POST['txt_commentId'])) {
  $colname_get_address = $_POST['txt_commentId'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_get_address = sprintf("SELECT * FROM tbl_address_comments LEFT JOIN tbl_address ON tbl_address.sessionid = tbl_address_comments.sessionid LEFT JOIN tbl_review_comments ON tbl_review_comments.address_comment_id = tbl_address_comments.id WHERE tbl_address_comments.id = %s", GetSQLValueString($colname_get_address, "int"));
$get_address = mysql_query($query_get_address, $killjoy) or die(mysql_error());
$row_get_address = mysql_fetch_assoc($get_address);
$totalRows_get_address = mysql_num_rows($get_address);

$ratingid = $_POST['txt_commentId'];
  
$colname_rs_social_user = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_social_user = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_social_user = sprintf("SELECT g_name, g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_social_user, "text"));
$rs_social_user = mysql_query($query_rs_social_user, $killjoy) or die(mysql_error());
$row_rs_social_user = mysql_fetch_assoc($rs_social_user);
$totalRows_rs_social_user = mysql_num_rows($rs_social_user);



  

  
$insertSQL = sprintf("INSERT INTO tbl_approved_comments (address_comment_id, was_checked, checked_by, is_approved) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['txt_commentId'], "int"),
                       GetSQLValueString(0, "int"),
					   GetSQLValueString('', "text"),
					   GetSQLValueString(0, "int"));
                      

mysql_select_db($database_killjoy, $killjoy);
$Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());  
  
  
date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
$name = $row_rs_social_user['g_name'];
$email = $row_rs_social_user['g_email'];
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
</style></head><body>Dear ". $name ."<br><br>Thank you for commenting on <strong>".$row_get_address['str_number']."&nbsp;".$row_get_address['street_name']."&nbsp;".$row_get_address['city']."</strong> <br><br>Your voice has been heard and we are just quickly checking that the comment is in line with our <a href='info-centre/fair-review-policy.html'>Fair Review Policy</a>. We will keep you in the loop and let you know as soon as you comment has been assessed.<br><br>The comment was sent from: <a href='mailto:$email'>$email</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br><br><br>Thank you, the Killjoy Community: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Killjoy Comment Received";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$body = "$message\r\n";
$body = wordwrap($body, 70, "\r\n");
$mail->MsgHTML($body);
$address = $email;
$mail->AddAddress($address, "Killjoy");
if(!$mail->Send()) {
echo "Mailer Error: " . $mail->ErrorInfo;
}
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


</style></head><body>Dear Killjoy Admin<br><br>Please assess the following comment for the <strong>".$row_get_address['str_number']."&nbsp;".$row_get_address['street_name']."&nbsp;".$row_get_address['city']."</strong>review, The reference number is: &nbsp;<strong><font color='#0000FF'><strong>".$_SESSION['kj_propsession']."</strong></font></strong><br><br>.All reviews are subjected to the Terms and Conditions as stipulated by our <a href='info-centre/fair-review-policy.html'>Fair Review Policy</a>.<br><br>The rental property review was submitted by: <a href='mailto:$email'>$email</a> on $date at $time<br><br><a href='https://www.killjoy.co.za/".$row_get_address['image_url']."'><img width='200' height='120' id='imagepreview' name='imagepreview' src='https://www.killjoy.co.za/".$row_get_address['image_url']."' class='imagepreview' alt='rental property review image'></a><br><br><table class='mailtbl' border='0' cellspacing='3' cellpadding='3'>
  <tr>
    <td>The tenant's experience:<br>".utf8_encode($row_get_address['social_comments'])."</td>
  </tr>
</table><br>
<a class='approve' id='approve' href='https://www.killjoy.co.za/admin/assessreview.php?approvebtn=approve&sessionid=".$ratingid."&checkedby=friends@killjoy.co.za&listing=".$ratingid."&ratingdate=".utf8_encode($row_get_address['rating_date'])."'>&nbsp;&nbsp;&nbsp;Approve&nbsp;&nbsp;&nbsp;</a><br><br>
<a class='reject' id='reject' href='https://www.killjoy.co.za/admin/assessreview.php?declinebtn=declined&sessionid=".$ratingid."&checkedby=friends@killjoy.co.za&listing=".$ratingid."&ratingdate= ".utf8_encode($row_get_address['rating_date'])."'>&nbsp;&nbsp&nbsp;&nbsp;Reject&nbsp;&nbsp&nbsp;&nbsp;</a>
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


$newsubject = $mail->Subject;
$comments = $mail->msgHTML($body);

$insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
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
<?php
mysql_free_result($get_address);

mysql_free_result($rs_social_user);

mysql_free_result($rs_new_comments);
?>