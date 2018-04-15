<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/localhost.php');
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


$page = $_SERVER['REQUEST_URI'];
$_SESSION['PrevUrl'] = $page;

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
<style type='text/css'>
.ui-page .ui-header {
    background: #6EADC1 !important;
	height: 65px;
	position: relative;
}
	.ui-page .ui-header a:link {text-decoration: none; color: #FFFEFD}
.ui-page .ui-header a:active {text-decoration: none; color: #FFFEFD}
.ui-page .ui-header a:hover {text-decoration: none; color: #FFFEFD}
.ui-page .ui-header a:visited {text-decoration: none; color: #FFFEFD}
	.ui-page .ui-footer {
    background: #6EADC1 !important;
		position: absolute;
		bottom: 0px;
		width: 100%;
}
</style>
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
</head>

<body>
<div data-role="page" id="index-page">
  <div class="header" data-role="header">
    <h1>Killjoy</h1>
    <?php if ($totalRows_rs_social_users > 0) { // Show if recordset not empty ?>
	  <div class="social-user-image" id="socialuserimage"><img src="<?php echo $row_rs_social_users['g_image']; ?>" alt="killjoy app" name="profile_image" id="profile_image"></div>
	  <?php } ?>
	  <?php if(!isset($_SESSION['kj_authorized'])) { ?>
	  <div class="social-user-signin"><a target="_top" href="admin/index-signin.php">Sign in</a></div>
	  <?php } ?>
    <img class="site-header-logo" src="images/icons/owl-header-white.gif" width="512" height="512" alt=""/>
    <div class="social-user-menu" id="socialusermenu"><a target="_top" href="member.php"><div class="social-user-profile" id="socialprofile">My Profile</div></a><div class="social-user-reviews">My Reviews</div><a target="_top" href="admin/logout.php"><div class="social-user-signout">Sign Out</div></a></div>
     </div>
 <div data-role="content"><form>
 Content 
 </form></div>
 <div style="display: none;" id="sign-in-content">
	<h2>Hello</h2>
	<p>You are awesome.</p>
</div>
 
  <div data-role="footer">
    <h4>Footer</h4>
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
