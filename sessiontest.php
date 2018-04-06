<?php

if (!isset($_SESSION)) {
  session_start();
}
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
echo '<pre>';
var_dump($_COOKIE);
echo '</pre>';
echo '<pre>';
var_dump($_FILES);
echo '</pre>';

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="robots" CONTENT="noindex,nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<title>Untitled Document</title>
</head>

<body>
<p>&nbsp;</p>
<p><?php echo $_SESSION['PrevUrl']; ?>
</p>
<p></p>
<p>&nbsp;</p>
<p><?php echo $_SESSION['sessionid']; ?></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
