<?php require_once('Connections/killjoy.php'); ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

$violater = -1;
if(isset($_GET['addressis'])) {
	
	$violater = $_GET['addressis'];
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

if ((isset($_POST["violation_insert"])) && ($_POST["violation_insert"] == "violation")) {
	
  $insertSQL = sprintf("INSERT INTO tbl_violation (address_comment_id, submitted_by, violation_type) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['violater'], "int"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['Violation'], "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
	
	



require('phpmailer-master/class.phpmailer.php');
include('phpmailer-master/class.smtp.php');

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
</style>
</head><body>Dear Admin<br><br>Please review the comment with reference ". $_POST['violater'] ." below<br><br>Feedback: The review comment is ". $_POST['Violation']."<br><br>The feedback was sent from ".$_POST['address']."<br><br>Please reivew the comment and reply to ".$_POST['address']." in due course.<br><br>Thank you, the Killjoy team: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:support@killjoy.co.za'>Killjoy</a></font><br></body></html>";

$mail->Subject    = "Killjoy Review Violation";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$body = "$message\r\n";
$body = wordwrap($body, 70, "\r\n");
$mail->MsgHTML($body);
$address = 'suppport@killjoy.co.za';
$mail->AddAddress($address, "$address");

if(!$mail->Send()) {

echo "Mailer Error: " . $mail->ErrorInfo;

} else {	

}

echo "<script>window.close();</script>";


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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="canonical" href="https://www.killjoy.co.za/review.php">
<title>killjoy - property review page</title>
<link href="css/review-flagger/desktop.css" rel="stylesheet" type="text/css" />
<link href="css/review-flagger/profile.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<body>
<div id="locationField" class="reviewcontainer">
    <form id="flagform"  action="flagit.php" method="POST" name=addressField class="reviewform">
    <div class="formheader">Flag a Review</div>
     <div class="stepfields" id="stepone">Something about you</div>   
    <div class="fieldlabels" id="fieldlabels">Your email address:
      <input type="hidden" name="violater" id="violater" value="<?php echo $violater ?>" />
    </div>
<div class="formfields" id="searchbox"><input required autofocus name="address" class="searchfield" type="email" data-type="search" id="autocomplete" size="80" /></div>  
  <div class="stepfields" id="stepone">Violation type</div> 
  <div class="fieldlabels" id="fieldlabels">Why is this content inappropriate?</div>
  <div class="formfields" id="violation">
   <div class="violation-selection"> 
        <p>
      <label>
        <input type="radio" name="Violation" value="Hateful" id="Violationtype_0" required />
        It contains hateful, violent, or inappropriate content</label>
      <br />
      <label>
        <input type="radio" name="Violation" value="Advertising" id="Violationtype_1" />
        It contains advertising or spam</label>
      <br />
      <label>
        <input type="radio" name="Violation" value="Subject" id="Violationtype_2" />
        Off subject</label>
      <br />
      <label>
        <input type="radio" name="Violation" value="Conflicts" id="Violationtype_3" />
        Conflicts of Interest</label>
      <br />
    </p>
    </div>

  </div>
 <button class="nextbutton">Submit <span style="display: inline; vertical-align: middle;" class="icon-check-square-o"></span></button>
    <div class="accpetfield">Killjoy takes abuse very seriously. If you have located a review or one or more reviews that you believe should be removed, or is in violation of our <a href="info-centre/fair-review-policy.html" target="new">Fair Review Policy</a>, please complete the details above and submit it. We will review your request and get in touch shortly. You may also wish to view the <a href="info-centre/notice-and-takedown.html" target="new">Notice and Takedown Procedure</a> documentaion.</div>
     
 
 <input type="hidden" name="violation_insert" value="violation">
  <label for="txt_szessionid"></label>
  <input type="hidden" name="txt_szessionid" id="txt_szessionid" value="<?php echo htmlspecialchars($sessionid) ?>" />
  </form>
</div>

</body>
</html>

