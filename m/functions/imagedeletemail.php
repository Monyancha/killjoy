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

$colname_rs_showproperty = "-1";
if (isset($_POST['txt_ratingid'])) {
  $colname_rs_showproperty = $_POST['txt_ratingid'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_showproperty = sprintf("SELECT tbl_address_comments.id as commentId, tbl_address_comments.sessionid as sessionId, tbl_address_comments.rating_date AS rating_date, tbl_address.str_number as str_number, tbl_address.street_name as street_name, tbl_address.city as city, tbl_address.postal_code AS postal_code,IFNULL(tbl_propertyimages.image_url,'media/image-add-512.png') AS propertyImage, social_users.g_name as socialUsername, social_users.g_email AS socialUsermail FROM tbl_address_comments LEFT JOIN tbl_address ON tbl_address.sessionid = tbl_address_comments.sessionid LEFT JOIN social_users ON social_users.g_email = tbl_address_comments.social_user LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid WHERE tbl_address_comments.id = %s", GetSQLValueString($colname_rs_showproperty, "int"));
$rs_showproperty = mysqli_query( $killjoy, $query_rs_showproperty) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_showproperty = mysqli_fetch_assoc($rs_showproperty);
$totalRows_rs_showproperty = mysqli_num_rows($rs_showproperty);


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
	color: #FFF;
	height: 40px;
	width: 150px;
	text-align: center;
	vertical-align: middle;
	background-color: #00F;
	padding-top: 5px;
	padding-bottom: 5px;
	font-size: 1.25em;
	display: inline-block;
	border-radius:4px
}
.delete {
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
	color: #FFF;
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


</style></head><body>Dear Killjoy Admin<br><br><strong>The property image has been deleted</strong> for the following review: <strong>".$row_rs_showproperty['str_number']."&nbsp;".$row_rs_showproperty['street_name']."&nbsp;".$row_rs_showproperty['city']."</strong><br><br>The reference number is: &nbsp;<strong><font color='#0000FF'><strong>".$row_rs_showproperty['sessionId']."</strong></font></strong><br><br>Please view the review details and ensure there is an image for the property. Select the link below to obtain an image for the property<br><br>The rental property review was submitted by: <a href='mailto:$email'>".$row_rs_showproperty['socialUsermail']."</a> on $date at $time<br><br><a title='find an image' href='https://www.google.com/maps/search/?api=1&query=".$row_rs_showproperty['str_number'].", ".$row_rs_showproperty['street_name'].", ".$row_rs_showproperty['city'].", ".$row_rs_showproperty['postal_code']."'><img width='200' height='200' id='imagepreview' name='imagepreview' src='https://www.killjoy.co.za/".$row_rs_showproperty['propertyImage']."' class='imagepreview' alt='rental property review image'></a><br><br>
</body></html>";
$mail->Subject = "Killjoy Review Image";
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



?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>killjoy.co.za - user deleted a property imaget</title>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBp0cy7ti0z5MJMAwWiPMNvbJobmWYGyv4&libraries=places&callback=initAutocomplete"async defer></script>
</head>

<body>
</body>
</html>
<?php
((mysqli_free_result($rs_showproperty) || (is_object($rs_showproperty) && (get_class($rs_showproperty) == "mysqli_result"))) ? true : false);
?>
