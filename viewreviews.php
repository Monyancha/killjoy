<?php require_once('Connections/killjoy.php'); ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}


$page = $_SERVER['REQUEST_URI'];
$_SESSION['PrevUrl'] = $page;

$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "admin/index.php";
if (!((isset($_SESSION['kj_username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['kj_username'], $_SESSION['kj_authorized'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}



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

$colname_rs_show_review = "-1";
if (isset($_GET['claw'])) {
  $colname_rs_show_review = $_GET['claw'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_review = sprintf("SELECT DISTINCT tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, tbl_address.postal_code AS postalCode, DATE_FORMAT(tbl_address_comments.rating_date, '%%d-%%b-%%y')AS ratingDate, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage, IFNULL(social_users.g_name, 'Anonymous') As socialUser, tbl_address_comments.rating_comments AS comments FROM tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.sessionid = tbl_address.sessionid LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.sessionid = tbl_address.sessionid LEFT JOIN social_users ON social_users.g_email = tbl_address_comments.social_user WHERE tbl_address.sessionid = %s GROUP BY tbl_address.sessionid ORDER BY tbl_address_comments.rating_date DESC", GetSQLValueString($colname_rs_show_review, "text"));
$rs_show_review = mysql_query($query_rs_show_review, $killjoy) or die(mysql_error());
$row_rs_show_review = mysql_fetch_assoc($rs_show_review);
$totalRows_rs_show_review = mysql_num_rows($rs_show_review);

$colname_rs_show_rating = "-1";
if (isset($_GET['claw'])) {
  $colname_rs_show_rating = $_GET['claw'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_rating = sprintf("SELECT ROUND(AVG(tbl_address_rating.rating_value),2) AS Avgrating, COUNT(tbl_address_rating.id) AS ratingCount FROM tbl_address_rating WHERE sessionid = %s", GetSQLValueString($colname_rs_show_rating, "text"));
$rs_show_rating = mysql_query($query_rs_show_rating, $killjoy) or die(mysql_error());
$row_rs_show_rating = mysql_fetch_assoc($rs_show_rating);
$totalRows_rs_show_rating = mysql_num_rows($rs_show_rating);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<META NAME="robots" CONTENT="noindex">
<link rel="canonical" href="https://www.killjoy.co.za/viewer.php">
<title>Killjoy - see the review for <?php echo $row_rs_show_review['streetnumber']; ?>, <?php echo $row_rs_show_review['streetname']; ?>, <?php echo $row_rs_show_review['city']; ?>, <?php echo $row_rs_show_review['postalCode']; ?></title>
<link href="css/view-reviews/profile.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
   	$.fn.stars = function() {
    return $(this).each(function() {
        // Get the value
        var val = parseFloat($(this).html());
        // Make sure that the value is in 0 - 5 range, multiply to get width
        var size = Math.max(0, (Math.min(5, val))) * 32;
        // Create stars holder
        var $span = $('<span />').width(size);
        // Replace the numerical value with stars
        $(this).html($span);
    });
}
	</script>

	<style type="text/css">
	span.stars, span.stars span {
	display: inline-block;
	height: 32px;
	background-image: url(images/stars/property-rating.png);
	background-repeat: repeat-x;
	background-position: 0 -32px;
	vertical-align: middle;
	width: 160px;
}

span.stars span {
    background-position: 0 0;
}
	.videoholder {
	margin-right: auto;
	margin-left: auto;
	padding: 5px;
	height: 280px;
	width: 400px;
}
    </style>

</head>
<body onLoad="set_session()">

<div class="formcontainer" id="formcontainer">
  <div class="formheader">Killjoy.co.za Property Review</div>
<div class="imagebox" id="imagebox">  
<img src="<?php echo $row_rs_show_review['propertyImage']; ?>" alt="killjoy.co.za member profile image" class="propertyimage" /> 
<div class="addressfield"><address><?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?><br /><?php echo $row_rs_show_review['city']; ?><br /><?php echo $row_rs_show_review['postalCode']; ?></address></div>    
  </div>

  <div class="fieldlabels" id="fieldlabels1">Rating:</div>
  <div class="ratingbox"><span class="stars" id="stars"><?php echo $row_rs_show_rating['Avgrating']; ?></span> <?php echo $row_rs_show_rating['Avgrating']; ?> From: <?php echo $row_rs_show_rating['ratingCount']; ?></div>
    <div class="fieldlabels" id="fieldlabels2">Reviewer:</div>
  <div class="userbox"><?php echo $row_rs_show_review['socialUser']; ?></div>
    <div class="fieldlabels" id="fieldlabels3">Review Date:</div>
  <div class="userbox"><?php echo $row_rs_show_review['ratingDate']; ?></div>
  <div class="fieldlabels" id="fieldlabels3">Comments:</div>
 <div class="commentbox"><?php echo $row_rs_show_review['comments']; ?></div>
</div>


</script>
<script type="text/javascript">
$(function() {
$('span.stars').stars();
});
  </script>
<script type="text/javascript">
   $("#comments").autogrow();
</script>    
</body>
</html>
<?php
mysql_free_result($rs_show_review);

mysql_free_result($rs_show_rating);
?>
