<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
$page = $_SERVER['REQUEST_URI'];
$_SESSION['PrevUrl'] = $page;

date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');
$click_time = "$date - $time";

function generateRandomString($length = 24) {
    $characters = '0123456789abcdefghijklmnopqrstuvw!@#$%^&^*()';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$captcha = filter_var(generateRandomString(), FILTER_SANITIZE_SPECIAL_CHARS);
$captcha = urlencode($captcha);

function generatenewRandomString($length = 24) {
    $characters = '0123456789abcdefghijklmnopqrstuvw!@#$%^&^*()';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$smith = filter_var(generateRandomString(), FILTER_SANITIZE_SPECIAL_CHARS);
$smith = urlencode($smith);

require_once('../Connections/localhost.php');
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

$colname_rs_social_users = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_social_users = $_SESSION['kj_username'];
}
mysql_select_db($database_localhost, $localhost);
$query_rs_social_users = sprintf("SELECT g_name, g_email, g_image FROM social_users WHERE g_email = %s AND g_active =1", GetSQLValueString($colname_rs_social_users, "text"));
$rs_social_users = mysql_query($query_rs_social_users, $localhost) or die(mysql_error());
$row_rs_social_users = mysql_fetch_assoc($rs_social_users);
$totalRows_rs_social_users = mysql_num_rows($rs_social_users);

$colname_rs_user_message = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_user_message = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_user_message = sprintf("SELECT *, DATE_FORMAT(u_date, '%%d-%%b-%%y') AS messageDate, COUNT(id) as messageCount FROM user_messages WHERE u_email = %s AND u_read=%s ORDER BY u_date DESC", GetSQLValueString($colname_rs_user_message, "text"), GetSQLValueString(0, "int"));
$rs_user_message = mysql_query($query_rs_user_message, $killjoy) or die(mysql_error());
$row_rs_user_message = mysql_fetch_assoc($rs_user_message);
$totalRows_rs_user_message = mysql_num_rows($rs_user_message);

$colname_rs_member_message = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_member_message = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_member_message = sprintf("SELECT * FROM user_messages WHERE u_email = %s AND u_read = %s ORDER BY u_date DESC", GetSQLValueString($colname_rs_user_message, "text"),GetSQLValueString(0, "int"));
$rs_member_message = mysql_query($query_rs_member_message, $killjoy) or die(mysql_error());
$row_rs_member_message = mysql_fetch_assoc($rs_member_message);
$totalRows_rs_member_message = mysql_num_rows($rs_member_message);



?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Killjoy App</title>
<link href="../jquery-mobile/jquery.mobile.theme-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../jquery-mobile/jquery.mobile.structure-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="css/gui.css" rel="stylesheet" type="text/css">
<script src="../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>

<link href="iconmoon/style.css" rel="stylesheet" type="text/css">
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
<link href="css/site-menu.css" rel="stylesheet" type="text/css">
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<link rel="stylesheet" type="text/css" href="fancybox/dist/jquery.fancybox.min.css" />

<style>
	.social-user-messages:before {
		
		font-family: Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", "serif";
		content: '<?php echo $row_rs_user_message['messageCount']; ?>';
		font-size: 26px;
		color: #F7F7F7;
		background-color: red;
		width:45px;
		height:45px;
		line-height: 45px;
		top:0px;
		left:-22.5px;
		position: absolute;
		border-radius: 50%;
		border: thin solid #F9F4F4;
		
		
	}	
</style>
</head>

<body>
<div data-role="page" id="index-page">
  <div class="header" data-role="header">
    <h1>Killjoy</h1>
    <?php if ($totalRows_rs_social_users > 0) { // Show if recordset not empty ?>
	  <div class="social-user-image" id="socialuserimage"><img src="../<?php echo $row_rs_social_users['g_image']; ?>" alt="killjoy app" name="profile_image" id="profile_image" width="100"></div>
	 <?php if ($row_rs_user_message['messageCount'] > 0 && (isset($_SESSION['kj_authorized']))) { // Show if recordset not empty ?>
	  <div id="usermessages" class="social-user-messages"><span class="icon-envelope-o"></span></div>
	  <?php } ?>
	  <div id="usermessagemenu" class="social-user-message-menu"><ul><?php do { $messagesession = $row_rs_member_message['id'] ?>
        <li><a target="_parent" id="inline" href="membermessages.php?tarsus=<?php echo $captcha?>&claw=<?php echo $messagesession ?>&alula=<?php echo $smith ?>"><?php echo $row_rs_member_message['u_sunject']; ?></a></li>
        <?php } while ($row_rs_member_message = mysql_fetch_assoc($rs_member_message)); ?></ul></div>
	  	  <?php } ?>
	  <?php if(!isset($_SESSION['kj_authorized'])) { ?>
	  <div class="social-user-signin"><a target="_parent" href="admin/index-signin.php">Sign in</a></div>
	  <?php } ?>
    <img class="site-header-logo" src="images/icons/owl-header-white.gif" alt=""/>
    <div class="social-user-menu" id="socialusermenu"><a target="_parent" href="myprofile.php"><div class="social-user-profile" id="socialprofile">My Profile</div></a><a target="_self" href="memberreviews.php"><div class="social-user-reviews">My Reviews</div></a><a target="_top" href="admin/logout.php"><div class="social-user-signout">Sign Out</div></a></div>
  </div>
 <div id="maincontent" class="maincontent" data-role="content">
 <div class="logo-banner" id="logo-banner"></div>
 <div class="app-title" id="maintitle"><h1>Killjoy</h1></div>
 <div class="app-taggline" id="tagline"><h2>The social app for rental property tenants</h2></div>
 <div class="option-chooser" id="chooseone"><a target="_self" href="reviewer.php"><div class="choose-review">Review a Property</div></a><a href="findreviews.php" target="_self"><div class="choose-view">View Rental Reviews</div></a></div> 	
 </div> 
  <div data-role="footer" id="footer-banner">
    <h4>Driven by <a href="https://www.midnightowl.co.za">Midnight Owl</a></h4>
    <div class="facebook-icon">asdfa</div>
  </div>
</div>
<script type="text/javascript" src="js/index.js"></script>
<script src="fancybox/dist/jquery.fancybox.js"></script>
<script src="fancybox/dist/jquery.fancybox.min.js"></script>
</body>
</html>
<?php
mysql_free_result($rs_social_users);
?>
