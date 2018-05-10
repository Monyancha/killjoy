<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('Connections/killjoy.php');
$page = $_SERVER['REQUEST_URI'];
$_SESSION['PrevUrl'] = $page;

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

$MM_restrictGoTo = "admin/index.php";
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

$colname_rs_social_user = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_social_user = $_SESSION['kj_username'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_social_user = sprintf("SELECT g_name, g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_social_user, "text"));
$rs_social_user = mysqli_query( $killjoy, $query_rs_social_user) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_social_user = mysqli_fetch_assoc($rs_social_user);
$totalRows_rs_social_user = mysqli_num_rows($rs_social_user);

$colname_rs_showproperty = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_showproperty = $_SESSION['kj_propsession'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_showproperty = sprintf("SELECT * FROM tbl_address WHERE sessionid = %s", GetSQLValueString($colname_rs_showproperty, "text"));
$rs_showproperty = mysqli_query( $killjoy, $query_rs_showproperty) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_showproperty = mysqli_fetch_assoc($rs_showproperty);
$totalRows_rs_showproperty = mysqli_num_rows($rs_showproperty);
$streetnr = $row_rs_showproperty['str_number'];
$street = $row_rs_showproperty['street_name'];
$city = $row_rs_showproperty['city'];

require('phpmailer-master/class.phpmailer.php');
include('phpmailer-master/class.smtp.php');

date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');

 
$name = $row_rs_social_user['g_name'];
$email = $row_rs_social_user['g_email'];
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
</style></head><body>Dear ". $name ."<br><br>Thank you for making South Africa a better place!<br><br>Your review of <strong>".$row_rs_showproperty['str_number']."&nbsp;".$row_rs_showproperty['street_name']."&nbsp;".$row_rs_showproperty['city']."</strong> has been recorded and your reference number is: &nbsp;<strong><font color='#0000FF'><strong>".$_SESSION['kj_propsession']."</strong></font></strong><br><br>Please note that your review is under assessment from one of our editors and will be published as soon as the editor approves of the the content in your review. All reviews are subjected to the Terms and Conditions as stipulated by our <a href='info-centre/fair-review-policy.html'>Fair Review Policy</a>.<br><br>The rental property review was submitted by: <a href='mailto:$email'>$email</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br>Thank
you, the Killjoy Community: <a href='https://www.killjoy.co.za'>https://www.killjoy.co.za</a><br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html></body></html>";
$mail->Subject = "Review Completed";
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
  
  
  $addressid = -1;
  if (isset($_COOKIE["address_id"])) {
   $addressid = $_COOKIE["address_id"];
  }
  $updateSQL = sprintf("UPDATE tbl_address SET social_user=%s WHERE address_id = %s",
  			            GetSQLValueString($email, "text"),
					    GetSQLValueString($addressid, "int"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
    $commentid = -1;
  if (isset($_COOKIE["comment_id"])) {
   $commentid  = $_COOKIE["comment_id"];
  }
     $updateSQL = sprintf("UPDATE tbl_address_comments SET social_user=%s WHERE id = %s",
  			            GetSQLValueString($email, "text"),
					    GetSQLValueString($commentid, "int"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
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
  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
        $updateSQL = sprintf("UPDATE tbl_propertyimages SET social_user=%s WHERE id = %s",
  			            GetSQLValueString($email, "text"),
					    GetSQLValueString($imageid, "int"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));

$newsubject = $mail->Subject;
$comments = $mail->msgHTML($body);
  $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>killjoy - registration completed</title>
<link href="../jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.dialog.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.resizable.min.css" rel="stylesheet" type="text/css">
<script src="../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<script src="../SpryAssets/jquery.ui-1.10.4.dialog.min.js"></script>
<link href="iconmoon/style.css" rel="stylesheet" type="text/css">
<link href="css/dialog-styling.css" rel="stylesheet" type="text/css">
</head>
<style>
	.confirm {
		width: 90% !important;
	}	
	</style>
<body>
<div data-role="page" id="page">

<div class="confirm" id="confirm"><img style="width: 100px;height: 100px;vertical-align: middle;" src="images/icons/gold_medal.png" class="first-place" width="100" height="100"  alt="killjoy rental property review completed" /> <?php echo $row_rs_social_user['g_name']; ?> you are a superstar! </p>
  <p>Thank you for sharing your personal experiences. Your review of <strong><?php echo $streetnr ?>&nbsp;<?php echo $street ?>&nbsp;<?php echo $city ?></strong> has been recorded and we are working hard to get it published.</p>
  <p>Your review is under assessment from one of our editors and will be published as soon as the editor approves of the the content in your review. All reviews are subjected to the Terms and Conditions as stipulated by our <a href='info-centre/fair-review-policy.html'>Fair Review Policy</a></p></div>
 </div>
<script type="text/javascript">
$(function() {
	$( "#confirm" ).dialog(); 
	$( "#confirm" ).dialog({ title: "Review Completed!" });
	
    	});
	
</script>

<script type="text/javascript">
	 var elem = $("#confirm");
	$("#confirm").dialog({ closeText: '' });
 elem.dialog({
       resizable: false,
    title: 'title',
    buttons: {
       Ok: function() {
          $(this).dialog('close');
		   parent.location.href ="index.php";
       } //end function for Ok button
    }//end buttons
 });     // end dialog
 elem.dialog('open');
	$('#confirm').bind('dialogclose', function(event) {
     window.location = "index.php";
 });	
	
	</script>
</body>
</html>