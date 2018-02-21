<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>killjoy - registration page</title>
<script type="text/javascript" src="../fancybox/lib/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="../fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link href="../css/login-page/mailcomplete.css" rel="stylesheet" type="text/css" />
</head>

<body>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<div id="notexist" class="completeexist"><div class="completecells">Dear Editor</div><div class="completecells">Thank you for assessing this review. An email has been sent to the user to notify them of your actions.</div><div class="completecells"><a class="close" href="admin-lounge.php">Close</a></div></div>;

<script type="text/javascript">
var $j = jQuery.noConflict();
$j(document).ready(function() {
 $j.fancybox({
	 helpers : {
overlay : {
css : {
  'background' : 'rgba(200, 201, 203, 0.40)'
   }
}
},
href: '#notexist', 
modal: false,
 'afterClose'  : function() {			   
 location.href ="../index.php";		
		 
 },
 
 });
return false;
});
 
</script>
</body>
</html>