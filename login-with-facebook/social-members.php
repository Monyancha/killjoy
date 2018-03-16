<?php require_once 'facebook-php-sdk/autoload.php'; ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
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
  $response = $fb->get('/me?fields=id,name', $fb_user_token);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user = $response->getGraphUser();

echo 'Name: ' . $user['name'];
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
</body>
</html>