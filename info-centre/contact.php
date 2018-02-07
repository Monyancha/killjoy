<?php
ob_start();
require_once('../Connections/rentaguide.php');

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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "frm_fb")) {
	$MM_dupKeyRedirect="contactfin.php";
	$fbmail = $_POST['txt_email'];
	$fbname = $_POST['txt_name'];
  $insertSQL = sprintf("INSERT INTO tbl_feedback (fb_name, fb_email, fb_comments) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['txt_name'], "text"),
                       GetSQLValueString($_POST['txt_email'], "text"),
                       GetSQLValueString($_POST['txt_msg'], "text"));

  mysql_select_db($database_rentaguide, $rentaguide);
  $Result1 = mysql_query($insertSQL, $rentaguide) or die(mysql_error());
  
  $refone = mysql_insert_id();
  $reference = "<font size='+2'><strong>RaG-".$refone."</strong></font>" ;
  $urlreference = "RaG-".$refone."";
  
require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = "host27.axxesslocal.co.za ";
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Username = "accounts@rentaguide.co.za";
$mail->Password = "N@w!1970";
$mail->Port = "465";
$mail->SetFrom('accounts@rentaguide.co.za', 'Rent-a-Guide');
$mail->AddReplyTo("accounts@rentaguide.co.za","Rent-a-Guide");


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
</style><script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-101245629-1', 'auto');
ga('send', 'pageview');
</script>  
</head><body>Dear Admin<br><br>Please view the feedback with reference ". $reference ." below<br><br>Feedback: ". $_POST['txt_msg']."<br><br>The feedback was sent from ".$_POST['txt_name']."<br><br>Please reivew the feedback and reply to ".$_POST['txt_email']." in due course.<br><br><br><br>Thank you, the Rent-a-Guide team: https://www.rentaguide.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:accounts@rentaguide.co.za'>Rent-a-Guide</a></font><br></body></html>";

$mail->Subject    = "Rent-a-Guide Feedback";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$body = "$message\r\n";
$body = wordwrap($body, 70, "\r\n");
$mail->MsgHTML($body);
$address = 'feedback@rentaguide.co.za';
$mail->AddAddress($address, "$address");

if(!$mail->Send()) {

echo "Mailer Error: " . $mail->ErrorInfo;

} else {	

}


    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsermail=".$fbmail."&"."requsername=".$fbname."&"."reqreference=".$urlreference;
    header ("Location: $MM_dupKeyRedirect");

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.rentaguide.co.za/info-centre/index.html">
<title>find answers to all your quesions - info centre - community centre - guidelines - help - contact us</title>
<meta name="keywords" content="help, contact, terms, guidelines, usage, policy, notice, privacy, rental, offers" />
<meta name="description" content="a Place where you can find answers to all your questions relating to the Rent-a-Guide community. Find help or view the guidelines. Contact us." />
<script type="text/javascript" src="fancybox/lib/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link href="info.css" rel="stylesheet" type="text/css" />
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-101245629-1', 'auto');
ga('send', 'pageview');
</script>  
</head>
<body>



<body>
</body>
</html>