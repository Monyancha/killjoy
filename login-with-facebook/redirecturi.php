<?
ob_start();
if (!isset($_SESSION)) {
session_start();
}

if (isset($_SESSION['PrevUrl']) && true) {
      $login_seccess_url  = $_SESSION['PrevUrl'];	
 }
 else { 
 
	 
$login_seccess_url = 'https://www.killjoy.co.za/index.php';  

 }
 
$loginusername = -1;
if (isset($_GET['requsername'])) {
	
	$loginusername = $_GET['requsername'];
}

$_SESSION['kj_username'] = $loginusername;
$_SESSION['kj_authorized'] = "1";

header ("Location: $login_seccess_url");

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex,nofollow" />
<title>killjoy.co.za - facebook redirect url for killjoy signin</title>
</head>

<body>
</body>
</html>