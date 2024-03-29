<?php
function str2hex( $str ) {
  return array_shift( unpack('H*', $str) );
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
########## Google Settings.. Client ID, Client Secret #############
$google_client_id 		= '32395259765-4r2hmjouf7q0fd8hv9vqhge8e0jj6mf9.apps.googleusercontent.com';
$google_client_secret 	= 'kVcGAmuS9EoYdndGytNmJl_Z';
$google_developer_key 	= '';
$google_redirect_url 	= 'https://www.killjoy.co.za/admin/google-signin.php';
########## mysql details (Replace with yours) #############
$db_username = "euqjdems_nawisso"; //Database Username
$db_password = "N@w!1970"; //Database Password
$hostname = "localhost"; //mysql Hostname
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
	  $profile_image_url 	= filter_var($user['picture'], FILTER_VALIDATE_URL);
	  $is_active            = filter_var("1", FILTER_SANITIZE_NUMBER_INT);
	  $is_social            = filter_var("google", FILTER_SANITIZE_STRING);
	  $personMarkup 		= "$email<div><img src='$profile_image_url?sz=50'></div>";
	  $_SESSION['token'] 	= $gClient->getAccessToken();
	  $login_seccess_url = 'https://www.killjoy.co.za/index.php?requsername='.$email.''; 
	  
	  if (isset($_SESSION['remember_me'])) {
	
	$identifier = $email;	
	$session_identifier = str2hex( $identifier  );
	$token = bin2hex(openssl_random_pseudo_bytes(16));
	$session_token = password_hash($token, PASSWORD_BCRYPT);
	
	$colname_rs_recall_exist = "-1";
if (isset($_SESSION['remember_me'])) {
  $colname_rs_recall_exist = $session_identifier;
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_recall_exist = sprintf("SELECT * FROM kj_recall WHERE social_users_identifier = %s", GetSQLValueString($colname_rs_recall_exist, "text"));
$rs_recall_exist = mysqli_query( $killjoy, $query_rs_recall_exist) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_recall_exist = mysqli_fetch_assoc($rs_recall_exist);
$totalRows_rs_recall_exist = mysqli_num_rows($rs_recall_exist);

if (!$totalRows_rs_recall_exist) {
	
	
  $insertSQL = sprintf("INSERT INTO kj_recall (social_users_identifier, social_users_token, request_platform, user_agent, user_ip_address) VALUES(%s, %s, %s, %s, %s)",
	                   GetSQLValueString($session_identifier, "text"),
					   GetSQLValueString($session_token, "text"),
					   GetSQLValueString("google", "text"),
					   GetSQLValueString($browser, "text"),
					    GetSQLValueString($user_ip, "text"));
					   


  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
  setcookie("kj_s_identifier", $session_identifier, time()+31556926 ,'/');
  setcookie("kj_s_token", $token, time()+31556926 ,'/');
	
}
	  }
	  
	      if (isset($_SESSION['PrevUrl']) && true) {
      $login_seccess_url = $_SESSION['PrevUrl'];	
    }
    header('Location: ' . filter_var($login_seccess_url, FILTER_SANITIZE_URL));
  	  
	   	   echo '<script type="text/javascript">location.reload(true);</script>';
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
	echo '</body></html>';	
} 
else // user logged in 
{
   /* connect to mysql */
    $connecDB = ($GLOBALS["___mysqli_ston"] = mysqli_connect($hostname,  $db_username,  $db_password))or die("Unable to connect to mysql");
    mysqli_select_db($connecDB, $db_name);
	
    //compare user id in our database
    $result = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT COUNT(g_id) FROM social_users WHERE g_id=$user_id");
	if($result === false) { 
		die(mysqli_error($GLOBALS["___mysqli_ston"])); //result is false show db error and exit.
		
		
	}
	
	$UserCount = mysqli_fetch_array($result);
 
    if($UserCount[0]) //user id exist in database
    {
		
@mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE social_users SET user_agent='$browser', user_region='$region', user_ip_address='$user_ip', created_date=now() WHERE g_id=$user_id");
		
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
font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
font-size: 20px;
}
body {
background-repeat: no-repeat;
margin-left:50px;
}
</style></head><body>Dear ". $name ."<br><br>You have been logged into <a href='https://www.killjoy.co.za'>your Killjoy account</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy Alerts</a><br><br>Thank
you, the Killjoy Community: <a href='https://www.killjoy.co.za'>https://www.killjoy.co.za</a><br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";
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


			
			 header('Location: ' .$login_seccess_url);  
		
    }else{ //user is new
 
		@mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO social_users (g_id, g_name, g_email, g_link, g_image, g_active, g_social, user_agent, user_city, user_region, user_ip_address, created_date) VALUES ($user_id, '$user_name','$email','$profile_url','$profile_image_url', '$is_active', '$is_social', '$browser', '$city', '$region', '$user_ip', now())");
		
			  $_SESSION['kj_username'] = $email;
              $_SESSION['kj_authorized'] = "1"; 
	 
	   header('Location: ' . filter_var($login_seccess_url, FILTER_SANITIZE_URL));
	  
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
font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
font-size: 20px;
}
body {
background-repeat: no-repeat;
margin-left:50px;
}
</style></head><body>Dear ". $name ."<br><br>Thank you for signing up. You have been logged into <a href='https://www.killjoy.co.za'>your Killjoy account</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy Alerts</a><br><br>Thank
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
$newsubject = $mail->Subject;
$comments = $mail->msgHTML($body);
  $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));

 header('Location: ' . filter_var($login_seccess_url, FILTER_SANITIZE_URL));
	}


	 


    

	 
  
	
}


?>