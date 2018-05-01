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

$maxRows_rs_latest_reviews = 15;
$pageNum_rs_latest_reviews = 0;
if (isset($_GET['pageNum_rs_latest_reviews'])) {
  $pageNum_rs_latest_reviews = $_GET['pageNum_rs_latest_reviews'];
}
$startRow_rs_latest_reviews = $pageNum_rs_latest_reviews * $maxRows_rs_latest_reviews;

mysql_select_db($database_killjoy, $killjoy);
$query_rs_latest_reviews = "SELECT tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, tbl_address.postal_code as postal_code, tbl_address.province as province, tbl_address_comments.rating_feeling as feeling, tbl_address_comments.rating_comments as comments, tbl_address_comments.social_user as social_user, (SELECT COUNT(tbl_approved.sessionid) FROM tbl_approved WHERE tbl_approved.sessionid = tbl_address_comments.sessionid AND tbl_approved.is_approved=1) AS reviewCount, DATE_FORMAT(tbl_address_comments.rating_date, '%d-%b-%y')AS ratingDate, ROUND(AVG(tbl_address_rating.rating_value),1) AS Avgrating, MIN(tbl_address_rating.rating_value) AS worstRating, MAX(tbl_address_rating.rating_value) AS bestRating, (SELECT COUNT(social_shares.count) FROM social_shares WHERE social_shares.address_comment_id=tbl_address_comments.id) AS total_shares, (SELECT COUNT(tbl_impressions.address_comment_id) FROM tbl_impressions WHERE tbl_impressions.address_comment_id = tbl_address_comments.id) AS impressions, COUNT(CASE WHEN tbl_likes.count=1 THEN tbl_likes.count=1 END) AS likes, COUNT(tbl_review_comments.address_comment_id) AS social_comments , COUNT(tbl_address_rating.rating_value) AS ratingCount, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.jpg') AS propertyImage, IF(social_users.anonymous='0',social_users.g_name,'Anonymous') As socialUser from tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_review_comments ON tbl_review_comments.address_comment_id= tbl_address_comments.id LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id LEFT JOIN social_users ON social_users.g_email=tbl_address_comments.social_user LEFT JOIN tbl_likes on tbl_likes.address_comment_id=tbl_address_comments.id  WHERE (tbl_address_comments.rating_date > DATE_SUB(now(), INTERVAL 1 MONTH)) AND tbl_approved.is_approved=1 GROUP BY tbl_address_comments.sessionid";
$query_limit_rs_latest_reviews = sprintf("%s LIMIT %d, %d", $query_rs_latest_reviews, $startRow_rs_latest_reviews, $maxRows_rs_latest_reviews);
$rs_latest_reviews = mysql_query($query_limit_rs_latest_reviews, $killjoy) or die(mysql_error());
$row_rs_latest_reviews = mysql_fetch_assoc($rs_latest_reviews);


if (isset($_GET['totalRows_rs_latest_reviews'])) {
  $totalRows_rs_latest_reviews = $_GET['totalRows_rs_latest_reviews'];
} else {
  $all_rs_latest_reviews = mysql_query($query_rs_latest_reviews);
  $totalRows_rs_latest_reviews = mysql_num_rows($all_rs_latest_reviews);
}
$totalPages_rs_latest_reviews = ceil($totalRows_rs_latest_reviews/$maxRows_rs_latest_reviews)-1;



?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Killjoy App</title>
<link href="../jquery-mobile/jquery.mobile.theme-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../jquery-mobile/jquery.mobile.structure-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="css/gui.css" rel="stylesheet" type="text/css">
<link href="css/reviews-list.css" rel="stylesheet" type="text/css">
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

<style type="text/css">
	span.stars, span.stars span {
	display: inline-block;
	height: 48px;
	background-image: url(images/stars/biggerstars.png);
	background-repeat: repeat-x;
	background-position: 0 -48px;
	vertical-align: middle;
	width: 240px;
	text-shadow: 0 0 0px;
}

span.stars span {
    background-position: 0 0;
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
 <div class="latest-reviews-header"><h3>Upcoming Rental Reviews</h3></div>
 <?php do { $sessionid = $row_rs_latest_reviews['propsession']?>
 
 <a target="_parent" href="viewreviews.php?tarsus=<?php echo $captcha?>&claw=<?php echo $sessionid ?>&alula=<?php echo $smith ?>"><div id="latest-reviews" class="latest-reviews"> 
 <div id="reviewimage" class="latest-reviews-image-banner"><img class="reviewimage" src="../<?php echo $row_rs_latest_reviews['propertyImage'] ?>"  alt="rental property review image"/></div>
 <div class="review-address"><?php echo $row_rs_latest_reviews['streetnumber'] ?> <?php echo $row_rs_latest_reviews['streetname'] ?> <?php echo $row_rs_latest_reviews['city'] ?></div>
 <div class="review-author"><?php echo $row_rs_latest_reviews['socialUser'] ?></div>
  <div class="review-rating"><span hidden="srarrating" class="stars" id="stars"><?php echo $row_rs_latest_reviews['Avgrating'] ?></span><span class="review_count"><?php echo $row_rs_latest_reviews['Avgrating'] ?></span></div>
  <div id="<?php echo $sessionid ?>" class="review-actions">
    <div class="like-action"><?php if ($row_rs_latest_reviews['likes'] > 0) { ?><span class="like-count"><?php echo $row_rs_latest_reviews['likes'] ?></span><span id="heart-o" style="color: red;cursor: pointer" class="icon-heart"></span><?php } ?><?php if($row_rs_latest_reviews['likes'] == 0) { ?><span style="cursor: pointer" onClick="review_likes('<?php echo $sessionid;?>')" class="icon-heart-o"></span><?php } ?></div><div class="comment-action"><span class="comment-count"><?php echo $row_rs_latest_reviews['social_comments'] ?></span><span  class="icon-bubble"></span></div><div class="impression-action"><span class="impression-count"><?php echo $row_rs_latest_reviews['impressions'] ?></span><span class="icon-stats-bars"></span></div><div class="share-action"><span class="share-count"><?php echo $row_rs_latest_reviews['total_shares'] ?></span><span class="icon-mail-forward"></span></div></div>   
 </div>
	 </a>
  <?php } while ($row_rs_latest_reviews = mysql_fetch_assoc($rs_latest_reviews)); ?>
 </div> 
  <div data-role="footer" id="footer-banner">
    <h4>Driven by <a href="https://www.midnightowl.co.za">Midnight Owl</a></h4>
    <div class="facebook-icon"><a href="https://www.facebook.com" target="new"><span class="icon-facebook"></span></a></div>
    <div class="twitter-icon"><a href="https://www.twitter.com/@KilljoySocial" target="new"><span class="icon-twitter"></span></a></div>
    <div id="gplus" class="gplus-icon"><a href="https://plus.google.com/discover" target="new"><span class="icon-google-plus"></span></a></div>
  </div>
</div>
<script type="text/javascript" src="js/index.js"></script>
<script src="fancybox/dist/jquery.fancybox.js"></script>
<script src="fancybox/dist/jquery.fancybox.min.js"></script>

	<script type="text/javascript">
   	$.fn.stars = function() {
    return $(this).each(function() {
        // Get the value
        var val = parseFloat($(this).html());
        // Make sure that the value is in 0 - 5 range, multiply to get width
        var size = Math.max(0, (Math.min(5, val))) * 48;
        // Create stars holder
        var $span = $('<span />').width(size);
        // Replace the numerical value with stars
        $(this).html($span);
    });
}
	</script>

<script type="text/javascript">
$(function() {
$('span.stars').stars();
});
  </script>
  

</body>
</html>
