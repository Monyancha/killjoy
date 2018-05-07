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

$MM_restrictGoTo = "../admin/index.php";
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

$colname_rs_show_name = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_show_name = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_name = sprintf("SELECT g_name FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_show_name, "text"));
$rs_show_name = mysql_query($query_rs_show_name, $killjoy) or die(mysql_error());
$row_rs_show_name = mysql_fetch_assoc($rs_show_name);
$totalRows_rs_show_name = mysql_num_rows($rs_show_name);



if (isset($_POST['txt_sessionid'])) {
$sessionid = $_POST["txt_sessionid"];

  $insertSQL = sprintf("INSERT INTO tbl_likes (address_comment_id, social_user, `count`) VALUES (%s, %s, %s)",
                       GetSQLValueString($sessionid, "int"),
					   GetSQLValueString($_SESSION['kj_username'], "text"),
                       GetSQLValueString('1', "int"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());

}

$ratingid = $_POST['txt_commentId'];
  
mysql_select_db($database_killjoy, $killjoy);
$query_get_address = sprintf("SELECT tbl_address.str_number, tbl_address.city, tbl_address.street_name, social_users.g_name as reviewerName, tbl_address_comments.social_user as revieWer FROM tbl_address_comments LEFT JOIN tbl_address ON tbl_address.sessionid = tbl_address_comments.sessionid LEFT JOIN social_users ON social_users.g_email=tbl_address_comments.social_user WHERE tbl_address_comments.id = %s", GetSQLValueString($_POST['txt_commentId'], "int"));
$get_address = mysql_query($query_get_address, $killjoy) or die(mysql_error());
$row_get_address = mysql_fetch_assoc($get_address);
$totalRows_get_address = mysql_num_rows($get_address);


require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');

date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');

 
$name = $row_rs_social_user['g_name'];
$email = $_POST['txt_usermail'];
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
</style></head><body>Dear ".$row_get_address['reviewerName']."<br><br>".$row_rs_show_name['g_name']." responded to your review of <strong>".$row_get_address['str_number']."&nbsp;".$row_get_address['street_name']."&nbsp;".$row_get_address['city']."</strong>.<br><br><a class='reject' href='https://www.killjoy.co.za/".$page."'>  View the Comment  </a><br><br>You are receving this email because you are a member of the <a href='https://www.killjoy.co.za'>killjoy.co.za Community</a><br><br>Thank you, the Killjoy Community: <a href='https://www.killjoy.co.za'>https://www.killjoy.co.za</a><br><br>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br></body></html></body></html>";
$mail->Subject = "Review Response";
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

$newsubject = "".$row_rs_show_name['g_name']." Commented";
$comments = "".$row_rs_show_name['g_name']." commented on your review for <strong>".$row_get_address['str_number']."&nbsp;".$row_get_address['street_name']."&nbsp;".$row_get_address['city']."</strong>.<br><br><a href='".$page."'>View the Comment</a> ";

$insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="../index.php">
</head>
 <body>
 </form>
</body>
</html>
