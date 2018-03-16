<?php require_once 'facebook-php-sdk/autoload.php'; ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
$fb = new Facebook\Facebook([
  'app_id' => '1787126798256435', // Replace {app-id} with your app id
  'app_secret' => 'fae0e14cba74629bcece216a0a0d18f7',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['public_profile,email,user_hometown,user_location']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://www.killjoy.co.za/login-with-facebook/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

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