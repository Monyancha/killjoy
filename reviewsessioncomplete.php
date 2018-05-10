<?php require_once('Connections/killjoy.php'); ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}


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

$colname_rs_social_user = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_social_user = $_SESSION['kj_username'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_social_user = sprintf("SELECT g_name, g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_social_user, "text"));
$rs_social_user = mysqli_query( $killjoy, $query_rs_social_user) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_social_user = mysqli_fetch_assoc($rs_social_user);
$totalRows_rs_social_user = mysqli_num_rows($rs_social_user);

$colname_rs_showproperty = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_showproperty = $_SESSION['kj_propsession'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_showproperty = sprintf("SELECT * FROM tbl_address WHERE sessionid = %s", GetSQLValueString($colname_rs_showproperty, "text"));
$rs_showproperty = mysqli_query( $killjoy, $query_rs_showproperty) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_showproperty = mysqli_fetch_assoc($rs_showproperty);
$totalRows_rs_showproperty = mysqli_num_rows($rs_showproperty);
$streetnr = $row_rs_showproperty['str_number'];
$street = $row_rs_showproperty['street_name'];
$city = $row_rs_showproperty['city'];

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>killjoy - rental property review completed</title>
<script src="fancybox/libs/jquery-3.3.1.min.js" ></script>
<link rel="stylesheet" href="fancybox/dist/jquery.fancybox.min.css" />
<script src="fancybox/dist/jquery.fancybox.min.js"></script>
<link href="css/login-page/mailcomplete.css" rel="stylesheet" type="text/css" />
<link href="query/iconmoon/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="notexist" class="completeexist"><div class="completecells"><img src="images/icons/gold_medal.png" class="first-place" width="512" height="512" alt="killjoy rental property review completed" /> <?php echo $row_rs_social_user['g_name']; ?> you are a superstar! Thank you for sharing your personal experience.</div><div class="completecells">Your review of <strong><?php echo $streetnr ?>&nbsp;<?php echo $street ?>&nbsp;<?php echo $city ?></strong> has been recorded.</div><div class="completecells">Please note that your review is under assessment from one of our editors and will be published as soon as the editor approves of the the content in your review.</div><div class="completecells">All reviews are subjected to the Terms and Conditions as stipulated by our <a href='info-centre/fair-review-policy.html'>Fair Review Policy</a></div><div class="completecells"><a href="index.php">Close</a></div></div>;
<script type="text/javascript">
  $.fancybox.open({
    src  : '#notexist',
    type : 'inline',
    opts : {
        afterClose : function( instance, current ) {
            location.href='index.php';
        }
    }
});
</script>
</body>
</html>