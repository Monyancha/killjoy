<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

if (isset($_SESSION['token'])) {

echo $_SESSION['token'].'<br />';

$authObj = json_decode($_SESSION['token']);
$accessToken = $authObj->access_token;
$refreshToken = $authObj->refresh_token;
$tokenType = $authObj->token_type;
$expiresIn = $authObj->expires_in;

echo 'access_token = ' . $accessToken;
echo '<br />';
echo 'refresh_token = ' . $refreshToken;
echo '<br />';
echo 'token_type = ' . $tokenType;
echo '<br />';
echo 'expires_in = ' . $expiresIn;
echo '<br />';
}

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