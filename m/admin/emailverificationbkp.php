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

$colname_rs_user_details = "-1";
if (isset($_SESSION['user_email'])) {
  $colname_rs_user_details = $_SESSION['user_email'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_user_details = sprintf("SELECT g_name, g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_user_details, "text"));
$rs_user_details = mysql_query($query_rs_user_details, $killjoy) or die(mysql_error());
$row_rs_user_details = mysql_fetch_assoc($rs_user_details);
$totalRows_rs_user_details = mysql_num_rows($rs_user_details);
$name = $row_rs_user_details['g_name'];
$email = $row_rs_user_details['g_email'];



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<link rel="alternate" href="https://www.killjoy.co.za/" hreflang="en" />
<link rel="apple-touch-icon" sizes="57x57" href="favicons/apple-icon-57x57.png" />
<link rel="apple-touch-icon" sizes="60x60" href="favicons/apple-icon-60x60.png" />
<link rel="apple-touch-icon" sizes="72x72" href="favicons/apple-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="76x76" href="favicons/apple-icon-76x76.png" />
<link rel="apple-touch-icon" sizes="114x114" href="favicons/apple-icon-114x114.png" />
<link rel="apple-touch-icon" sizes="120x120" href="favicons/apple-icon-120x120.png" />
<link rel="apple-touch-icon" sizes="144x144" href="favicons/apple-icon-144x144.png" />
<link rel="apple-touch-icon" sizes="152x152" href="favicons/apple-icon-152x152.png" />
<link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-icon-180x180.png" />
<link rel="icon" type="image/png" sizes="192x192"  href="favicons/android-icon-192x192.png" />
<link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="96x96" href="favicons/favicon-96x96.png" />
<link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png" />
<link rel="manifest" href="/manifest.json" />
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>killjoy - confirm registration</title>
<script type="text/javascript" src="../fancybox/lib/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="../fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link href="../css/login-page/mailcomplete.css" rel="stylesheet" type="text/css" />
</head>

<body>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<div id="notexist" class="completeexist"><div class="completecells">Dear <?php echo $name ?></div><div class="completecells">You have not yet verified your email address: <?php echo $email ?></div><div class="completecells">Please verify your email address to continue</div><div class="completecells">If you have not received the confirmation email, click resend email below to resend the confirmation mail</div><div class="completecells"><div id="sent" class="sent">The mail was sent!</div><span id="sending" class="sending"><img src="../images/loading24x24.gif" width="24" height="24" alt="sending email" /></span><a onClick="sending_mail('<?php echo $email;?>')" href="#"><div id="resend" class="resend">Resend Email</div></a><a class="close" href="../index.php">Close</a></div></div>





<script type="text/javascript">
var $s = jQuery.noConflict();
function sending_mail ( email ) 
{ 
$s.ajax( { type    : "POST",
async   : false,
data    : { "user_name" : email}, 
url     : "../functions/resendconfirmationmail.php",
 beforeSend: function(){
$s('.sending').show();
},

complete: function(){
$s('.sending').hide(); // Handle the complete event
},
success : function ( email )
{  $s('.sent').show();
 $s('.resend').hide();
						   
},
error   : function ( xhr )
{ alert( "wot went wong" );
}
 } );
 return false;
 }
</script>

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
<?php
mysql_free_result($rs_user_details);
?>
