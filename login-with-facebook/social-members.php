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
$picture="http://graph.facebook.com/$userid/picture";

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
<img src="http://graph.facebook.com/<?php echo $userid ?>/picture" />
</body>
</html>