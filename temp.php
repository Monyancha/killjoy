<?php


	

if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE)
  $browser = 'Internet explorer';
 elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) //For Supporting IE 11
   $browser = 'Internet explorer';
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
  
  echo $browser;
   

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