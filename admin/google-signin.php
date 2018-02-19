<?php

########## Google Settings.. Client ID, Client Secret #############
$google_client_id 		= '32395259765-4r2hmjouf7q0fd8hv9vqhge8e0jj6mf9.apps.googleusercontent.com';
$google_client_secret 	= 'kVcGAmuS9EoYdndGytNmJl_Z';
$google_redirect_url 	= 'http://localhost/killjoy/admin/google-signin.php';
$google_developer_key 	= '';
########## MySql details (Replace with yours) #############
$db_username = "euqjdems_nawisso"; //Database Username
$db_password = "N@w!1970"; //Database Password
$hostname = "localhost"; //Mysql Hostname
$db_name = 'euqjdems_killjoy'; //Database Name
###################################################################

//include google api files
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_Oauth2Service.php';

//start session
session_start();

$gClient = new Google_Client();
$gClient->setApplicationName('Login to killjoy.co.za');
$gClient->setClientId($google_client_id);
$gClient->setClientSecret($google_client_secret);
$gClient->setRedirectUri($google_redirect_url);
$gClient->setDeveloperKey($google_developer_key);

$google_oauthV2 = new Google_Oauth2Service($gClient);

//If user wish to log out, we just unset Session variable
if (isset($_REQUEST['reset'])) 
{
  unset($_SESSION['token']);
  $gClient->revokeToken();
  header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
}


if (isset($_GET['code'])) 
{ 
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
	return;
}


if (isset($_SESSION['token'])) 
{ 
		$gClient->setAccessToken($_SESSION['token']);
}


if ($gClient->getAccessToken()) 
{
	  //Get user details if user is logged in
	  $user 				= $google_oauthV2->userinfo->get();
	  $user_id 				= $user['id'];
	  $user_name 			= filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	  $email 				= filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	  $profile_url 			= filter_var($user['link'], FILTER_VALIDATE_URL);
	  $profile_image_url 	= filter_var("media/profile.png", FILTER_SANITIZE_SPECIAL_CHARS);
	  $is_active            = filter_var("1", FILTER_SANITIZE_NUMBER_INT);
	  $is_social            = filter_var("1", FILTER_SANITIZE_NUMBER_INT);
	  $personMarkup 		= "$email<div><img src='$profile_image_url?sz=50'></div>";
	  $_SESSION['token'] 	= $gClient->getAccessToken();
}
else 
{
	//get google login url
	$authUrl = $gClient->createAuthUrl();
	$fbloginurl = "../login-with-facebook/fblogin.php";
}

//HTML page start
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<title>Login to killjoy.co.za</title>';
echo '<link href="../css/login-page/desktop.css" rel="stylesheet" type="text/css" />';
echo '</head>';
echo '<body>';

if(isset($authUrl)) //user is not logged in, show login button
{   echo '<div class="maincontainer" id="loginwrapper">';
	echo '<a class="login" href="'.$authUrl.'"><div class="gplussignin"></div></a>';
	echo '<a class="login" href="'.$fbloginurl.'"><div class="fbsignin"></div></a>';
	echo '<form action="checkuser.php" method="post">';
	echo '<div class="usemail">Or Continue with your Email</div>';
	echo '<input class="usermail" type="email" name="usermail" id="usermail" />';
	echo '<br />';
	echo '<button class="nextbutton">Next <span class="icon-arrow-circle-right"></span></button>';
	echo '</form>';
	echo '</div>';
} 
else // user logged in 
{
   /* connect to mysql */
    $connecDB = mysql_connect($hostname, $db_username, $db_password)or die("Unable to connect to MySQL");
    mysql_select_db($db_name,$connecDB);
	
    //compare user id in our database
    $result = mysql_query("SELECT COUNT(g_id) FROM social_users WHERE g_id=$user_id");
	if($result === false) { 
		die(mysql_error()); //result is false show db error and exit.
	}
	
	$UserCount = mysql_fetch_array($result);
 
    if($UserCount[0]) //user id exist in database
    {
		
			$_SESSION['kj_username'] = $email;
            $_SESSION['kj_authorized'] = "1";  
			
			 if (isset($_SESSION['PrevUrl']) && true) {
      $login_seccess_url  = $_SESSION['PrevUrl'];	
 } else {
	 
	$login_seccess_url      = 'http://localhost/killjoy/index.php';  
 }
	        header('Location: ' . filter_var($login_seccess_url  , FILTER_SANITIZE_URL));
		
    }else{ //user is new
		$_SESSION['kj_username'] = $email;
     $_SESSION['kj_authorized'] = "1";  
	 
	 
	  if (isset($_SESSION['PrevUrl']) && true) {
      $login_seccess_url  = $_SESSION['PrevUrl'];	
 } else {
	 $login_seccess_url      = 'http://localhost/killjoy/index.php'; 
 }
		@mysql_query("INSERT INTO social_users (g_id, g_name, g_email, g_link, g_image, g_active, g_social, created_date) VALUES ($user_id, '$user_name','$email','$profile_url','$profile_image_url', '$is_active', '$is_social', now())");
	}

	$_SESSION['kj_username'] = $email;
     $_SESSION['kj_authorized'] = "1"; 
	 
date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
$name = $user_name;
$email = $email;
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

	header('Location: ' . filter_var($login_seccess_url  , FILTER_SANITIZE_URL));
	
	
}
 
echo '</body></html>';
?>