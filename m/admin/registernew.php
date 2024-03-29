<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/killjoy.php');

if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
  $browser = 'Internet Explorer';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
   $browser = 'Internet Explorer';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE)
 $browser = 'Mozilla Firefox';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE)
  $browser = 'Google Chrome';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== FALSE)
  $browser = "Opera Mini";
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE)
  $browser = "Opera";
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE)
 $browser = "Safari";
 else
  $browser = 'Something else'; 
  
  function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


$user_ip = getUserIP();

function get_content($URL){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_URL, $URL);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
}

$json = get_content("http://api.ipinfodb.com/v3/ip-city/?key=a2f2062d64fd705bbb32ce4c44e8ebb508d080990528d7cb4f1a0c5e7ddf5c1e&ip=".$user_ip."&format=json"); 
$json = json_decode($json,true); 
$city=$json['cityName'];
$region = $json['regionName'];

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




function generateRandomString($length = 24) {
    $characters = '0123456789abcdefghijklmnopqrstuvw!@#$%^&^*()';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$captcha = generateRandomString();
$captcha = urlencode($captcha);

function generatenewRandomString($length = 24) {
    $characters = '0123456789abcdefghijklmnopqrstuvw!@#$%^&^*()';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$smith = generatenewRandomString();
$smith = urlencode($smith);

	
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "register")) {
	$password = $_POST['g_pass'];
	$plainpassword = $_POST['g_passc'];
$password = password_hash($password, PASSWORD_BCRYPT);

  $updateSQL = sprintf("INSERT INTO social_users (g_name, g_email, g_pass, g_plain, g_image, user_agent, user_city, user_region, user_ip_address) VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['g_name'], "text"),
                       GetSQLValueString($_POST['g_email'], "text"),
					   GetSQLValueString($password, "text"),
                       GetSQLValueString($plainpassword, "text"),
					   GetSQLValueString("media/profile.png", "text"),
					   GetSQLValueString($browser, "text"),
					   GetSQLValueString($city, "text"),
					   GetSQLValueString($region, "text"),
					   GetSQLValueString($user_ip, "text"));
					   

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
  $addressid = -1;
  if (isset($_COOKIE["address_id"])) {
   $addressid = $_COOKIE["address_id"];
  }
  $updateSQL = sprintf("UPDATE tbl_address SET social_user=%s WHERE address_id = %s",
  			            GetSQLValueString($_POST['g_email'], "text"),
					    GetSQLValueString($addressid, "int"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
    $commentid = -1;
  if (isset($_COOKIE["comment_id"])) {
   $commentid  = $_COOKIE["comment_id"];
  }
     $updateSQL = sprintf("UPDATE tbl_address_comments SET social_user=%s WHERE id = %s",
  			            GetSQLValueString($_POST['g_email'], "text"),
					    GetSQLValueString($commentid, "int"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
      $ratingid = -1;
  if (isset($_COOKIE["rating_id"])) {
   $ratingid  = $_COOKIE["rating_id"];
  }
      $updateSQL = sprintf("UPDATE tbl_address_rating SET social_user=%s WHERE id = %s",
  			            GetSQLValueString($_POST['g_email'], "text"),
					    GetSQLValueString($ratingid, "int"));
						
   $imageid = -1;
  if (isset($_COOKIE["image_id"])) {
   $imageid = $_COOKIE["image_id"];
  }
  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
        $updateSQL = sprintf("UPDATE tbl_propertyimages SET social_user=%s WHERE id = %s",
  			            GetSQLValueString($_POST['g_email'], "text"),
					    GetSQLValueString($imageid, "int"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
$user_id = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);

$colname_rs_recall_exist = "-1";
if (isset($_COOKIE['kj_s_identifier'])) {
  $colname_rs_recall_exist = $_COOKIE['kj_s_identifier'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_recall_exist = sprintf("SELECT * FROM kj_recall WHERE social_users_identifier = %s", GetSQLValueString($colname_rs_recall_exist, "text"));
$rs_recall_exist = mysqli_query( $killjoy, $query_rs_recall_exist) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_recall_exist = mysqli_fetch_assoc($rs_recall_exist);
$totalRows_rs_recall_exist = mysqli_num_rows($rs_recall_exist);

$colname_rs_showproperty = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_showproperty = $_SESSION['kj_propsession'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_showproperty = sprintf("SELECT * FROM tbl_address WHERE sessionid = %s", GetSQLValueString($colname_rs_showproperty, "text"));
$rs_showproperty = mysqli_query( $killjoy, $query_rs_showproperty) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_showproperty = mysqli_fetch_assoc($rs_showproperty);
$totalRows_rs_showproperty = mysqli_num_rows($rs_showproperty);

if (!$totalRows_rs_recall_exist) {
  
  if (isset($_SESSION['remember_me'])) {
	  $social_identifier = htmlspecialchars($_COOKIE['kj_s_identifier']);
	  $session_token = $_COOKIE['kj_s_token'];
	  $session_token = password_hash($session_token, PASSWORD_BCRYPT);
  $insertSQL = sprintf("INSERT INTO kj_recall (social_users_identifier, social_users_token, request_platform, user_agent, user_ip_address) VALUES(%s, %s, %s, %s, %s)",
	                   GetSQLValueString($social_identifier, "text"),
					   GetSQLValueString($session_token, "text"),
					   GetSQLValueString("killjoy", "text"),
					   GetSQLValueString($browser, "text"),
					    GetSQLValueString($user_ip, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
	
}
}
$register_seccess_url = "registerconfirm.php";  

date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s'); 

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
$name = $_POST['g_name'];
$email = $_POST['g_email'];
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
</style></head><body>Dear ". $name ."<br><br>We are delighted that you joined the killjoy community.<br><br>We will do our utmost to ensure you enjoy every featurethat this app has to offer.<br><br>Please <font size='4'><a style='text-decoration:none;' target='_parent' href='https://www.killjoy.co.za/m/admin/verifymail.php?owleyes=$captcha&verifier=$email&snowyowl=$smith'>verify your email address</a></font> to ensure it was you who requested to join the commpunity.<br><br>The request to join Killjoy was sent from: <a href='mailto:$email'>$email</a> on $date at $time.<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br>Thank
you, the Killjoy Community: <a href='https://www.killjoy.co.za'>https://www.killjoy.co.za</a><br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Killjoy Account Created";
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

$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;

$newsubject = $mail->Subject;
$comments = $mail->msgHTML($body);
  $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
	
if ((isset($_SESSION["kj_propsession"])) && ($_SESSION["kj_propsession"] != " ")) {
  
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

$newsubject = $mail->Subject;
$comments = $mail->msgHTML($body);
  $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
  unset($_SESSION['kj_propsession']);
  unset($_SESSION['PrevUrl']);
  
  $_SESSION['kj_propsession'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
}
  
header('Location: ' . filter_var($register_seccess_url  , FILTER_SANITIZE_URL));

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>Killjoy - register to use the app</title>
<link href="css/desktop.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="../SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/checks.css" rel="stylesheet" type="text/css" />
<link href="../css/login-page/mailcomplete.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../js/jquery-3.3.1.min.js"></script>
<link href="../../jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.dialog.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.resizable.min.css" rel="stylesheet" type="text/css">
<link href="../css/dialog-styling.css" rel="stylesheet" type="text/css">
<script src="../../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<script src="../../SpryAssets/jquery.ui-1.10.4.dialog.min.js"></script>
</head>
<body>
<div data-role="page" id="page"></div>

<div id="registernew" class="registernew">
<div class="maincontainer" id="maincontainer">
 <form id="register" class="form" name="register" method="POST" target="_top" action="registernew.php">
   <div class="fieldlabels" id="fieldlabels">Your name:</div>
  <div class="formfields" id="formfields"><span id="sprytextfield1">
    <label>
      <input name="g_name" type="text" class="pwdfield" id="g_name" />
    </label>
    <span class="textfieldRequiredMsg">!</span></span></div>
    <div class="fieldlabels" id="fieldlabels">Your email:</div>
      <div class="formfields" id="formfields"><input readonly name="g_email" type="text" class="emailfield" value="<?php echo $_SESSION['user_email']; ?>" /></div>
    <div class="fieldlabels" id="fieldlabels">Password:</div>
      <div class="formfields" id="formfields"><span id="sprypassword1">
      <label>
          <input name="g_pass" type="password" class="pwdfield" id="g_pass" />
      </label>
    <span class="passwordRequiredMsg">!</span></span></div>
    <div class="fieldlabels" id="fieldlabels">Retype Password:</div>
<div class="formfields" id="formfields"><span id="spryconfirm1">
  <label>
    <input name="g_passc" type="password" class="pwdfield" id="g_passc" />
  </label>
  <span class="confirmRequiredMsg">!</span><span class="confirmInvalidMsg">The passwords don't match.</span></span></div>
  <div class="accpetfield" id="accpetfield"> <div class="accepttext">By clicking Continue, you agree to our <a href="../info-centre/terms-of-use.html">Site Terms</a> and confirm that you have read our <a href="../info-centre/help-centre.html">Usage Policy,</a> including our <a href="../info-centre/cookie-policy.php">Cookie Usage Policy.</a></div> </div>
    <div class="formfields" id="formfields">
    <button class="nextbutton">Continue <span class="icon-smile"></span></button>
      <input type="hidden" name="MM_insert" value="register" />    </div>
  
</form>
</div>

</div>

<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");

</script>
<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "g_pass");
</script>
<script type="text/javascript">
	 var elem = $("#registernew");
	$("#registernew").dialog({ closeText: '' });
     elem.dialog({
     resizable: false,
	 autoOpen: false,
     title: 'Sign up',
	 draggable: false,
    });     // end dialog
     elem.dialog('open');
	$('#registernew').bind('dialogclose', function(event) {
     window.location = "../index.php";
 });
	
	</script>
</body>
</html>

