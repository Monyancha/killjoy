<?php require_once('../Connections/stomer.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $theValue) : ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $theValue) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

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
?>
<?php
// *** Validate request to login to this site.
ob_start();
if (!isset($_SESSION)) {
session_start();
}
$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "login.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = true;
  mysqli_select_db( $stomer, $database_stomer);
  
  $LoginRS__query=sprintf("SELECT st_username, st_userpwd FROM st_users WHERE st_username=%s AND st_userpwd=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysqli_query( $stomer, $LoginRS__query) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  $loginFoundUser = mysqli_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
	unset($_SESSION['NO__member']);
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
	 $_SESSION['NO__member'] = $loginUsername;
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-108851141-1"></script>
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'UA-108851141-1');
</script>
<META NAME="robots" CONTENT="noindex,nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za" />
<title>st omer nursery - wholesale herb growers - buy plants - water wise - green - coffee shop - functions - kiddies parties - paarl- western cape - south africa</title>
<meta name="description" content="St Omer nursery is a wholesale herb and plant nursery situtated in Paarl, Western Cape that offer many attractions, including a coffes shop. Buy plants from us." />
<meta name="keywords" content="buy plants, nursery, shade plants, vegetable plants, herbal plants, local nurseries, sun plants, wholesale,  growers, flowers, functions, kids, parties, venue, coffee, shop, restaurant, events, functions, birthdays, high, tea, kitchen, braai, facilities, outdoors, catering, cake, meals, pizza" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
<link href="css/links.css" rel="stylesheet" type="text/css" />
<link href="css/headers.css" rel="stylesheet" type="text/css" />
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../fancybox/lib/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="../fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
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
<link rel="manifest" href="favicons/manifest.json" />
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<link href="css/breadcrumbs.css" rel="stylesheet" type="text/css" />
<link href="../css/contact-us/contactform.css" rel="stylesheet" type="text/css" />
<link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>
<body onLoad="set_session()">
<div class="headermedia" id="headermedia"><i onClick="window.open('https://www.facebook.com/stomerfarm/?rf=1448086842129016', '_blank')" style="cursor:pointer; padding-right:10px" class="fa fa-facebook" aria-hidden="true"></i><i onClick="window.open('https://www.google.co.za/maps/place/St+Omer+Farm/@-33.7055694,19.0201469,15z/data=!4m5!3m4!1s0x0:0x332b296238126841!8m2!3d-33.7055694!4d19.0201469', '_blank')" style="cursor:pointer; padding-right:10px" class="fa fa-map-marker" aria-hidden="true"></i><a id="inline" href="#phone"><span style="color:#FFFFFF; padding-right:10px" class="fa fa-phone"></span></a><a id="inline" href="#email"><span style="color:#FFFFFF; padding-right:10px" class="fa fa-envelope"></span></a></div>
<div class="logobanner" id="logobanner"></div>
<div class="crumbswrapper" id="crumbswrapper">
<ul id="crumbs">
  <li>
<a title="go to the home page" href="http://www.stomer.co.za/index.html">Home</a>
</li>
</ul>
</div>
<div class="mainbanner" id="mainbanner"></div>
<div class="menubarcontainer" id="menubarcontainer"><nav>
<label for="show-menu" class="show-menu"><img src="images/phone/menusmall.gif" alt="st omer farm" width="33" height="39" /></label>
<input type="checkbox" id="show-menu" role="button" />
<ul id="menu">
  <li>
<a title="go to the home page" href="#">Home</a>
<ul class="hidden">
 </ul>
</li>
   <li>
<a title="view the coffee shop" href="#">Coffee Shop &#x25BE;</a>
<ul class="hidden">
<li><a href="coffeshop.html" title="the most child friendly coffee shop">The Garden Room</a></li>
  <li><a href="menus/breakfast-menu/breakfast-menu.html" title="Bring the kids and enjoy a hearty farm style breakfast">Breakfast Menu</a></li>
  <li><a href="menus/lunch-menu/lunch-menu.html" title="Bring the kids and enjoy a hearty farm style lunch">Lunch Menu</a></li>
  <li><a href="find-a-estate-agenency.php?accesscheck=index.php" title="find an estate agent on Rent-a-Guide">Baby Shower</a></li>  
   <li><a href="find-a-estate-agenency.php?accesscheck=index.php" title="come enjoy your year end function with us">Year End</a></li>                   
</ul>
</li>
<li>
<a title="we cater for a whole range of functions" href="#">Special Occasions &#x25BE;</a>
<ul class="hidden">
  <li><a href="kiddies-parties.html" title="we cater for all ages kiddies parties">Kiddies Parties</a></li>
  <li><a href="find-a-estate-agenency.php?accesscheck=index.php" title="find an estate agent on Rent-a-Guide">Kitchen Tea</a></li>
  <li><a href="find-a-estate-agenency.php?accesscheck=index.php" title="find an estate agent on Rent-a-Guide">Baby Shower</a></li>  
   <li><a href="find-a-estate-agenency.php?accesscheck=index.php" title="come enjoy your year end function with us">Year End</a></li>                   
</ul>
</li>
<li><a href="info-centre/index.html" title="View proerpty rental and review guidelines, amenities and policies" target="_new">Events</a> </li>
<li><a href="info-centre/contact-us.php" title="contus us, view the directions to the farm" target="_new">Contact</a></li>
</ul>
</nav>
</div>
<div class="headingcontainer" id="headingcontainer">
<h1>St. Omer Farm - Admin Area</h1></div>
<div class="welcomebox" id="welcomebox">
<form id="loginform" name="loginform" method="POST" action="<?php echo $loginFormAction; ?>">
<table border="0" cellpadding="0" cellspacing="0" class="contacttbl">
  <tr>
    <td><h2 class="inputs">User Login</h2></td>
  </tr>
  <tr>
    <td class="labels">Username</td>
  </tr>
  <tr>
    <td class="inputs"><span id="sprytextfield1">
      <label>
        <input name="username" type="text" class="userimputs" id="username" />
      </label>
      <span class="textfieldRequiredMsg">Username please</span></span></td>
  </tr>
  <tr>
    <td class="labels">Password</td>
  </tr>
  <tr>
    <td class="inputs"><span id="sprytextfield2">
      <label>
        <input name="password" type="password" class="userimputs" id="password" />
      </label>
      <span class="textfieldRequiredMsg">Password please</span></span></td>
  </tr>
  <tr>
    <td class="labels">&nbsp;</td>
  </tr>
  <tr>
    <td class="inputs"><input name="login" type="submit" class="submit" id="login" value="Log In" /></td>
  </tr>
  <tr>
    <td class="labels"><?php if (isset($_SESSION['NO__member'])) { ?><div class="logoloaderrors" id="logoloaderrors">That username or password is incorrect</div><?php } //show if the member or username incorrect ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  </table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>


<br />



<div style="display:none"><div id="phone" class="phone"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;&nbsp;021 868 3641</div></div>

<div style="display:none"><div id="email" class="email"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;&nbsp;<a href="mailto:jthom@stomer.co.za">jthom@stomer.co.za</a></div></div>

    <div class="footer">&copy; St. Omer Farm - 2017&#8482; All Right Reserved<div class="designer">Designed and maintaineed by <a href="http://www.midnightowl.co.za" title="web design and marketing" target="_new">Midnight Owl</a><img src="../images/desktop/midnight-owl.gif" alt="wholesale herb growers and plant nursery" class="footerimg" /></div></div>

<script type="text/javascript">
$(document).ready(function() {
/* This is basic - uses default settings */

$("a#single_image").fancybox();
/* Using custom settings */

$("a#inline").fancybox({
helpers : {
overlay : {
css : {
  'background' : 'rgba(0, 0, 255, 0.40)'
   }
}
},
'opacity' : 0.4,
'width' :  200,
'height' : 20,
'autoSize' : false,		

'hideOnContentClick': true	});
/* Apply fancybox to multiple items */

$("a.grouped_elements").fancybox({
'transitionIn'	:	'elastic',
'transitionOut'	:	'elastic',
'speedIn'		:	600, 
'speedOut'		:	200, 
'overlayShow'	:	false
});
$("a.grouped_plants").fancybox({
'transitionIn'	:	'elastic',
'transitionOut'	:	'elastic',
'speedIn'		:	600, 
'speedOut'		:	200, 
'overlayShow'	:	false
});

});
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
</script>



</body>

</html>