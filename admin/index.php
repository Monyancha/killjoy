<?php

########## Google Settings.. Client ID, Client Secret #############
$google_client_id 		= '32395259765-4r2hmjouf7q0fd8hv9vqhge8e0jj6mf9.apps.googleusercontent.com';
$google_client_secret 	= 'kVcGAmuS9EoYdndGytNmJl_Z';
$google_redirect_url 	= 'http://localhost/killjoy/admin/index.php';
$login_seccess_url      = 'http://localhost/killjoy/index.php'; 
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
	  $profile_image_url 	= filter_var($user['picture'], FILTER_VALIDATE_URL);
	  $personMarkup 		= "$email<div><img src='$profile_image_url?sz=50'></div>";
	  $_SESSION['token'] 	= $gClient->getAccessToken();
}
else 
{
	//get google login url
	$authUrl = $gClient->createAuthUrl();
}

//HTML page start
echo '<html xmlns="http://www.w3.org/1999/xhtml">';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<title>Login to killjoy.co.za</title>';
echo '</head>';
echo '<body>';

if(isset($authUrl)) //user is not logged in, show login button
{
	echo '<a class="login" href="'.$authUrl.'"><img src="images/google-login-button.png" /></a>';
	echo '<br />';
	echo '<br />';
	echo '<a class="login" href="'.$authUrl.'"><img src="images/facebook-signin.png" /></a>';
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
		echo 'Welcome back '.$user_name.'!';
    }else{ //user is new
		echo 'Hello! '.$user_name.', Thanks for Registering!';
		@mysql_query("INSERT INTO social_users (g_id, g_name, g_email, g_link, g_image, created_date) VALUES ($user_id, '$user_name','$email','$profile_url','$profile_image_url', now())");
	}

	
	header('Location: ' . filter_var($login_seccess_url  , FILTER_SANITIZE_URL));
	
	//list all user details
	echo '<pre>'; 
	print_r($user);
	echo '</pre>';	
}
 
echo '</body></html>';
?>
