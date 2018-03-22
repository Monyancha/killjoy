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

$colname_rs_my_reviews = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_my_reviews = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_my_reviews = sprintf("SELECT tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, COUNT(tbl_address_comments.sessionid) AS ratingCount, DATE_FORMAT(tbl_address_comments.rating_date, '%%d-%%b-%%y')AS ratingDate, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage FROM tbl_address_comments LEFT JOIN tbl_address ON tbl_address.sessionid = tbl_address_comments.sessionid  LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN social_users on social_users.g_email = tbl_address_comments.social_user WHERE tbl_address_comments.social_user = %s GROUP BY tbl_address.sessionid ORDER BY tbl_address_comments.rating_date DESC", GetSQLValueString($colname_rs_my_reviews, "text"));
$rs_my_reviews = mysql_query($query_rs_my_reviews, $killjoy) or die(mysql_error());
$row_rs_my_reviews = mysql_fetch_assoc($rs_my_reviews);
$totalRows_rs_my_reviews = mysql_num_rows($rs_my_reviews);
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
<link href="css/tooltips.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.close:before {
	content: '<?php echo $row_rs_my_reviews['ratingCount']; ?>';
	font-family: "Arial Rounded MT Bold";
	font-size: 17px;
	font-weight: bolder;
	color: #FFFFFF;
	background-color: #F00;
	border: thin solid #F00;
	width: 20px;
	height:20px;
	display: block;
	text-align: center;
	border-radius:50%;
	line-height: 20px;
	}
.close {
	position: absolute;
	top: 0px;
	right:0px;
	display: block;
	z-index:9999;
	}
</style>
</head>
<body>
<?php if ($totalRows_rs_my_reviews > 0) { // Show if recordset not empty ?>
  <div class="formcontainer" id="formcontainer">
    <div class="formheader">Killjoy.co.za Member Reviews</div>
    <?php do { $sessionid = filter_var($row_rs_my_reviews['propsession'], FILTER_SANITIZE_SPECIAL_CHARS);?>
    <a class="masterTooltip" title="view or change your review for <?php echo $row_rs_my_reviews['streetnumber']; ?> <?php echo $row_rs_my_reviews['streetname']; ?> <?php echo $row_rs_my_reviews['city']; ?>" href="editreviews.php?tarsus=<?php echo $captcha?>&claw=<?php echo $sessionid ?>&alula=<?php echo $smith ?>">
    <div class="reviewlist"><div class="imagebox"><img src="<?php echo $row_rs_my_reviews['propertyImage']; ?>" alt="property review image" class="propertyimage" /><div class="close"></div></div><div class="addressfield"><?php echo $row_rs_my_reviews['streetnumber']; ?> <?php echo $row_rs_my_reviews['streetname']; ?> <?php echo $row_rs_my_reviews['city']; ?></div></div>
    <?php } while ($row_rs_my_reviews = mysql_fetch_assoc($rs_my_reviews)); ?>
    </a>
    <div class="accpetfield" id="accpetfield"> <div class="accepttext">Click on any of your reviews to view or make changes to the review </div></div>
  </div>
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_rs_my_reviews == 0) { // Show if recordset empty ?>
  <div class="formcontainer" id="formcontainer2">
    <div class="empty" id="empty">You have no rental property reviews.<a href="review.php"> Create your first Review</a></div>
    
    </div>
  <?php } // Show if recordset empty ?>
<script type="text/javascript">
$(document).ready(function() {
// Tooltip only Text
$('.masterTooltip').hover(function(){
        // Hover over code
        var title = $(this).attr('title');
        $(this).data('tipText', title).removeAttr('title');
        $('<p class="tooltip"></p>')
        .text(title)
        .appendTo('body')
        .fadeIn('slow');
}, function() {
        // Hover out code
        $(this).attr('title', $(this).data('tipText'));
        $('.tooltip').remove();
}).mousemove(function(e) {
        var mousex = e.pageX + 20; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
        $('.tooltip')
        .css({ top: mousey, left: mousex })
});
});
</script>

</body>
</html>
<?php
mysql_free_result($rs_my_reviews);
?>
