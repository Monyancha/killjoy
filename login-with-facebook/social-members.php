<?php require_once 'facebook-php-sdk/autoload.php'; ?>
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
if (isset($_SESSION['fb_access_token'])); {
	$fb_user_token = $_SESSION['fb_access_token'];
	
}

$fb = new Facebook\Facebook([
  'app_id' => '178712679825643',
  'app_secret' => 'fae0e14cba74629bcece216a0a0d18f7',
  'default_graph_version' => 'v2.12',
  ]);

try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,email,hometown,location,picture,link', $fb_user_token);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphUser();
$userid = $user['id'];
$hometown=explode(",",$user['hometown']['name']);
$location=explode(",",$user['location']['name']);
$picture="http://graph.facebook.com/$userid/picture?type=medium";

echo 'Name: ' . $user['name'] . '<br />';
echo 'Id: ' . $user['id']. '<br />';
echo 'hometown: ' . $hometown[0]. '<br />';
echo 'location: ' . $location[0]. '<br />';
echo 'picture: ' . $picture . '<br />';
echo 'link: ' . $user['link'] . '<br />';
echo 'email: ' . $user['email'] . '<br />';

		$name = $user['name'];
		$email = $user['email'];
		$fb_Id = $user['id'];
		$active = "1";
		$social = "facebook";
		$profilePictureUrl = $picture;
		$hometown = $hometown[0];
		$location = $location[0];
		$locale = $user['link'];
		$login_seccess_url = 'https://www.killjoy.co.za/index.php'; 
		
		$colname_rs_checkfbuser = "-1";
        mysql_select_db($database_killjoy, $killjoy);
         $query_rs_checkfbuser = sprintf("SELECT g_id FROM social_users WHERE g_id = %s", GetSQLValueString($fb_Id, "int"));
         $rs_checkfbuser = mysql_query($query_rs_checkfbuser, $killjoy) or die(mysql_error());
         $row_rs_checkfbuser = mysql_fetch_assoc($rs_checkfbuser);
          $totalRows_rs_checkfbuser = mysql_num_rows($rs_checkfbuser);


 if($totalRows_rs_checkfbuser > 0) //user id exist in database
 
 $query = "UPDATE social_users SET g_name='".$name."',g_email='".$email."',g_id='".$fb_Id."',g_image='".$profilePictureUrl."',g_link='".$locale."', g_active='".$active."', g_social='".$social."', hometown='".$hometown."', locale='".$location."' WHERE g_id='".$fb_Id."'";
		$result = mysql_query($query, $killjoy) or die(mysql_error());
    {
		
			
date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
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
</style></head><body>Dear ". $name ."<br><br>You have been logged into <a href='https://www.killjoy.co.za'>your Killjoy account</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy Alerts</a><br><br>Thank you, the Killjoy Community: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Killjoy Security Alert";
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

$_SESSION['kj_username'] = $email;
$_SESSION['kj_authorized'] = "1";

header('Location: https://www.killjoy.co.za/index.php');

} else { //user is new
	
		$query = "INSERT INTO social_users(g_name,g_email,g_id,g_image,g_link, g_active, g_social, hometown, locale) VALUES ('".$name."','".$email."','".$fb_Id."','".$profilePictureUrl."','".$locale."','".$active."', '".$social."','".$hometown."', '".$location."')";
		$result = mysql_query($query, $killjoy) or die(mysql_error());
		if ($result) {
 
			
date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
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
</style></head><body>Dear ". $name ."<br><br>Thank you for signing up. You have been logged into <a href='https://www.killjoy.co.za'>your Killjoy account</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy Alerts</a><br><br>Thank you, the Killjoy Community: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
$mail->Subject    = "Killjoy.co.za Account Created";
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

$_SESSION['kj_username'] = $email;
$_SESSION['kj_authorized'] = "1";

header('Location: https://www.killjoy.co.za/index.php');
	
	$newsubject = $mail->Subject;
    $comments = $mail->msgHTML($body);
    $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

    mysql_select_db($database_killjoy, $killjoy);
     $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
  

	



			
		
		}
	}

// OR
// echo 'Name: ' . $user->getName();




?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<img src="http://graph.facebook.com/<?php echo $userid ?>/picture?type=large" />
</body>
</html>