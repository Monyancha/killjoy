<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
$sessionid = $_POST["txt_sesseyed"];
$_SESSION['sessionid'] = $sessionid;

    


?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="../index.php">
</head>
 <body>
</body>
</html>
