<?php require_once('Connections/killjoy.php'); ?>
<?php

ob_start();
if (!isset($_SESSION)) {
session_start();
}
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

$colname_rs_show_user = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_show_user = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_user = sprintf("SELECT g_name FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_show_user, "text"));
$rs_show_user = mysql_query($query_rs_show_user, $killjoy) or die(mysql_error());
$row_rs_show_user = mysql_fetch_assoc($rs_show_user);
$totalRows_rs_show_user = mysql_num_rows($rs_show_user);

$maxRows_rs_show_review = 5;
$pageNum_rs_show_review = 0;
if (isset($_GET['pageNum_rs_show_review'])) {
  $pageNum_rs_show_review = $_GET['pageNum_rs_show_review'];
}
$startRow_rs_show_review = $pageNum_rs_show_review * $maxRows_rs_show_review;

$colname_rs_show_review = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_show_review = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_review = sprintf("SELECT tbl_address.sessionid as propsession, tbl_address_comments.id AS listingsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, COUNT(tbl_address_comments.sessionid) AS ratingCount, (SELECT COUNT(tbl_address_comments.sessionid) FROM tbl_address_comments WHERE tbl_address_comments.social_user = %s) AS totalReviews, DATE_FORMAT(tbl_address_comments.rating_date, '%%d-%%b-%%y')AS ratingDate, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage FROM tbl_address_comments LEFT JOIN tbl_address ON tbl_address.sessionid = tbl_address_comments.sessionid LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN social_users on social_users.g_email = tbl_address_comments.social_user WHERE tbl_address_comments.social_user = %s GROUP BY tbl_address.sessionid ORDER BY tbl_address.street_name ASC", GetSQLValueString($colname_rs_show_review, "text"),GetSQLValueString($colname_rs_show_review, "text"));
$query_limit_rs_show_review = sprintf("%s LIMIT %d, %d", $query_rs_show_review, $startRow_rs_show_review, $maxRows_rs_show_review);
$rs_show_review = mysql_query($query_limit_rs_show_review, $killjoy) or die(mysql_error());
$row_rs_show_review = mysql_fetch_assoc($rs_show_review);

if (isset($_GET['totalRows_rs_show_review'])) {
  $totalRows_rs_show_review = $_GET['totalRows_rs_show_review'];
} else {
  $all_rs_show_review = mysql_query($query_rs_show_review);
  $totalRows_rs_show_review = mysql_num_rows($all_rs_show_review);
}
$totalPages_rs_show_review = ceil($totalRows_rs_show_review/$maxRows_rs_show_review)-1;
?>
<?php
$currentPage = $_SERVER["PHP_SELF"];
?>
<?php
$queryString_rs_show_review = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_show_review") == false && 
        stristr($param, "totalRows_rs_show_review") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_show_review = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_show_review = sprintf("&totalRows_rs_show_review=%d%s", $totalRows_rs_show_review, $queryString_rs_show_review);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>Killjoy - view or change your killjoy.co.za member reviews</title>
<link href="css/member-reviews/profile.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.close {
	position: absolute;
	top: 0px;
	right:0px;
	display: block;
	z-index:9999;
	font-size: 16px;
	font-weight: bold;
	color: #FFFFFF;
	background-color: #F00;
	border: thin solid #F00;
	width: 20px;
	height:20px;
	display: block;
	text-align: center;
	border-radius:50%;
	line-height: 20px;
	font-family: Tahoma, Geneva, sans-serif;
	}
</style>
<link href="css/pagenav.css" rel="stylesheet" type="text/css" />
<link href="../jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.dialog.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.resizable.min.css" rel="stylesheet" type="text/css">
<link href="css/dialog-styling.css" rel="stylesheet" type="text/css" />
<script src="../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<script src="../SpryAssets/jquery.ui-1.10.4.dialog.min.js"></script>
</head>
<body>
<?php if ($totalRows_rs_show_review > 0) { // Show if recordset not empty ?>
 <div data-role="page" id="memberreviews-page">
  <div id="formcontainer">
    <div class="reivews-header">You have <?php echo $row_rs_show_review['totalReviews'] ?> Reviews of <?php echo $totalRows_rs_show_review ?> Properties</div>
    <?php do { $sessionid = filter_var($row_rs_show_review['propsession'], FILTER_SANITIZE_SPECIAL_CHARS); $ratingcount = $row_rs_show_review['ratingCount']; $listingsession = $row_rs_show_review['listingsession']?>
    <a href="editreviews.php?tarsus=<?php echo $captcha?>&claw=<?php echo $sessionid ?>&beak=<?php echo $listingsession; ?>&alula=<?php echo $smith ?>">
    <div class="reviewlist"><div class="imagebox"><img src="../<?php echo $row_rs_show_review['propertyImage']; ?>" alt="property review image" class="propertyimage" /><div class="close"><?php echo $ratingcount ?></div></div><div class="addressfield"><?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?> <?php echo $row_rs_show_review['city']; ?></div></div>
    </a>
     <?php } while ($row_rs_show_review = mysql_fetch_assoc($rs_show_review)); ?>
    <div class="accpetfield" id="accpetfield"> <div class="accepttext">The number indicator at the top right of the image display the count of reviews. Click on any of your reviews to view or make changes to the review. </div></div>
     <?php if ($totalRows_rs_show_review > 1) { // Show if recordset not empty ?>
  <div class="navcontainer" id="navbar"><div class="prevbtn"><?php if ($pageNum_rs_show_review > 0) { // Show if not first page ?>
    <a title="Go to the previous page" class="masterTooltip" href="<?php printf("%s?pageNum_rs_show_review=%d%s", $currentPage, max(0, $pageNum_rs_show_review - 1), $queryString_rs_show_review); ?>"><img src="images/nav/prev-btn.png" /></a>
    <?php } // Show if not first page ?></div><div class="navtext">Showing review <?php echo ($startRow_rs_show_review + 1) ?> to <?php echo min($startRow_rs_show_review + $maxRows_rs_show_review, $totalRows_rs_show_review) ?> of <?php echo $totalRows_rs_show_review ?></div>
    <div class="netxbtn"><?php if ($pageNum_rs_show_review < $totalPages_rs_show_review) { // Show if not last page ?>
      <a title="Go to the next page" class="masterTooltip" href="<?php printf("%s?pageNum_rs_show_review=%d%s", $currentPage, min($totalPages_rs_show_review, $pageNum_rs_show_review + 1), $queryString_rs_show_review); ?>"><img src="images/nav/next-btn.png" /></a>
      <?php } // Show if not last page ?></div></div></div>
  <?php } // Show if recordset not empty ?>
  <?php } // Show if recordset not empty ?>
 
  
  <?php if ($totalRows_rs_show_review == 0) { // Show if recordset empty ?>
  
  <div class="formcontainer" id="formcontainer2"> 
    <div class="empty" id="empty">You have no rental property reviews.<a href="review.php"> Create your first Review</a></div>    
</div>
    
  <?php } // Show if recordset empty ?>
<script type="text/javascript">
	 var elem = $("#formcontainer");
	$("#formcontainer").dialog({ closeText: '' });
     elem.dialog({
     resizable: false,
	 autoOpen: false,
     title: '<?php echo $row_rs_show_user['g_name']; ?>\'s Reviews',
	 draggable: false,
    });     // end dialog
     elem.dialog('open');
	$('#formcontainer').bind('dialogclose', function(event) {
     window.location = "index.php";
 });
	
	</script>

</body>
</html>

