<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('Connections/killjoy.php');
function hex2str( $hex ) {
  return pack('H*', $hex);
}
function str2hex( $str ) {
  return array_shift( unpack('H*', $str) );
}

$consent = NULL;
if (isset($_COOKIE['consent']) && $_COOKIE['consent'] != " ") {
	
	$consent = "1";
}

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

$showsignin = -1;
if(isset($_SESSION['kj_authorized'])) {
	
	$showsignin = 1;
	
} else {
	
	$showsignin = 0;
	
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


$colname_rs_get_remember = "-1";
if (isset($_COOKIE['kj_s_identifier']) && $_COOKIE['kj_s_token']) {
  $colname_rs_get_remember = $_COOKIE['kj_s_identifier'];
   $password_token = $_COOKIE['kj_s_token'];

mysql_select_db($database_killjoy, $killjoy);
$query_rs_get_remember = sprintf("SELECT social_users_identifier, social_users_token FROM kj_recall WHERE social_users_identifier = %s", GetSQLValueString($colname_rs_get_remember, "text"));
$rs_get_remember = mysql_query($query_rs_get_remember, $killjoy) or die(mysql_error());
$row_rs_get_remember = mysql_fetch_assoc($rs_get_remember);
$totalRows_rs_get_remember = mysql_num_rows($rs_get_remember);


 $loginUsername=hex2str( $row_rs_get_remember['social_users_identifier'] );
 $MM_fldUserAuthorization = "";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_killjoy, $killjoy); 
  
$LoginRS__query=sprintf("SELECT social_users.g_email, kj_recall.social_users_identifier, kj_recall.social_users_token FROM social_users LEFT JOIN kj_recall ON       kj_recall.social_users_identifier=%s WHERE social_users.g_email = %s",
GetSQLValueString($_COOKIE['kj_s_identifier'], "text"),GetSQLValueString($loginUsername, "text")); 
	   
  $LoginRS = mysql_query($LoginRS__query, $killjoy) or die(mysql_error());
  $row_LoginRS = mysql_fetch_assoc($LoginRS);
  
  
  $loginFoundUser = mysql_num_rows($LoginRS);  
   $password_token = $_COOKIE['kj_s_token'];
  $hashedpassword =  $row_LoginRS['social_users_token'];  
  
    if (password_verify($password_token, $hashedpassword)) { //user is authenticated
	
     $loginStrGroup = "";
	 
    
	
if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //create a new token to associate with the session identifier
	
	$token = bin2hex(openssl_random_pseudo_bytes(16));
	setcookie("kj_s_token", $token, time()+31556926 ,'/');
	$session_token = password_hash($token, PASSWORD_BCRYPT);
	
		               $updateSQL = sprintf("UPDATE kj_recall SET social_users_token=%s WHERE social_users_identifier=%s",
                       GetSQLValueString($session_token, "text"),
                       GetSQLValueString($_COOKIE['kj_s_identifier'], "text"));
					     mysql_select_db($database_killjoy, $killjoy);
                          $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());

    $_SESSION['kj_username'] = $loginUsername;
    $_SESSION['kj_usergroup'] = $loginStrGroup;	
	$_SESSION['kj_authorized'] = "1"; 
	

   }

     }

$colname_rs_social_users = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_social_users = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_social_users = sprintf("SELECT g_name, g_email, g_image FROM social_users WHERE g_email = %s AND g_active =1", GetSQLValueString($colname_rs_social_users, "text"));
$rs_social_users = mysql_query($query_rs_social_users, $killjoy) or die(mysql_error());
$row_rs_social_users = mysql_fetch_assoc($rs_social_users);
$totalRows_rs_social_users = mysql_num_rows($rs_social_users);

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

mysql_select_db($database_killjoy, $killjoy);
$query_rs_structured_review = "select tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, tbl_address.postal_code as postal_code, tbl_address.province as province, tbl_address_comments.rating_feeling as feeling, tbl_address_comments.rating_comments as comments, tbl_address_comments.social_user as social_user, (SELECT COUNT(tbl_approved.sessionid) FROM tbl_approved WHERE tbl_approved.sessionid = tbl_address_comments.sessionid AND tbl_approved.is_approved=1) AS reviewCount, DATE_FORMAT(tbl_address_comments.rating_date, '%d-%b-%y')AS ratingDate, ROUND(AVG(tbl_address_rating.rating_value),2) AS Avgrating, MIN(tbl_address_rating.rating_value) AS worstRating, MAX(tbl_address_rating.rating_value) AS bestRating, COUNT(tbl_address_rating.rating_value) AS ratingCount, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage from tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id WHERE (tbl_address_comments.rating_date > DATE_SUB(now(), INTERVAL 1 MONTH)) AND tbl_approved.is_approved=1 GROUP BY tbl_address_comments.sessionid";
$rs_structured_review = mysql_query($query_rs_structured_review, $killjoy) or die(mysql_error());
$row_rs_structured_review = mysql_fetch_assoc($rs_structured_review);
$totalRows_rs_structured_review = mysql_num_rows($rs_structured_review);

?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="SpryAssets/redirection-mobile.js"></script><script type="text/javascript">// <![CDATA[
 SA.redirection_mobile ({
 mobile_url : "www.killjoy.co.za/m/index.php",
 });
</script>

<script type="application/ld+json">
{
  "@context":"http://schema.org",
  "@type":"ItemList",
  "itemListElement":[
    {
      "@type":"SiteNavigationElement",
      "position":1,
      "name": "Home",
      "description": "View a list of the latest rental property reviews or submit a rental property review. Search for rental properties to view the shared experiences left by tenants.",
      "url":"https://www.killjoy.co.za/"
    },
    {
      "@type":"SiteNavigationElement",
      "position":2,
      "name": "Review a Rental Property",
      "description": "Share your rental property experiences with future tenants by submitting a rental property review",
      "url":"https://www.killjoy.co.za/review.php"
    },
	    {
      "@type":"SiteNavigationElement",
      "position":3,
      "name": "View Rental Property Reviews",
      "description": "See what tenants had to say about a rental property and the experience they shared.",
      "url":"https://www.killjoy.co.za/findproperties.php"
    },
		    {
      "@type":"SiteNavigationElement",
      "position":4,
      "name": "Search Rental Property Reviews",
      "description": "Search for a rental property by name or location and see what tenants share about their experiences.",
      "url":"https://query.killjoy.co.za/search.php?q=find-a-property"
    },
		    {
      "@type":"SiteNavigationElement",
      "position":5,
      "name": "Sign in",
      "description": "Sign in to view your personal rental property reviews, make changes to your account and share or comment on other reviews.",
      "url":"https://www.killjoy.co.za/admin/"
    }
  ]
}
</script>
<script type='application/ld+json'> 
{
  "@context": "http://www.schema.org",
  "@type": "WebSite",
  "name": "Killjoy",
     "author": {
      "@type": "Website",
      "name": "Killjoy",
	   "url": "https://www.killjoy.co.za",
	   "potentialAction": {
    "@type": "SearchAction",
    "target": "https://www.killjoy.co.za/help-and-support/index.php?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
    },
     "image": "https://www.killjoy.co.za/images/logos/logo.gif",
     "sameAs": [
    "https://www.facebook.com/killjoy",
    "https://twitter.com/@KilljoySocial"
 
  ],
  "alternateName": "a community for rental property tenants",
  "url": "https://www.killjoy.co.za",
  "description": "Killjoy is an online comunity where rental property tenants can share their personal experiences with future tenants",
  "keywords" :"rental properties, reviews, ratings, complaints, share, experiences, tenants "
 
}
 </script>
 <script type="application/ld+json">
/*structerd data markup compiled by http://www.midnightowl.co.za */
{
  "@context": "http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "item": {
      "@id": "https://www.killjoy.co.za/index.php",
      "name": "Home",
      "image": "https://www.killjoy.co.za/images/icons/home-icon.png"
    }
    }]
}
</script>
<script type="application/ld+json">
{
    "@context": "http://schema.org",
    "@type": "Person",
    "name": "Iwan Ross",
    "url": "http://www.iwanross.co.za",
     "disambiguatingDescription": "Technical Content Writer and Editor",
    "homeLocation": {
        "@type": "Place",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "South Africa"
        }
    },
    "sameAs": [
        "https://www.facebook.com/iwan.ross.790",
        "https://twitter.com/iwan_ross",
        "https://plus.google.com/109643714716104501907",
        "https://www.instagram.com/iwan_ross/",
        "https://www.linkedin.com/in/iwan-ross/",
        "https://za.pinterest.com/iwanross/"
    ]
}
</script>
 <?php do { //repeat all recrods for structured markup ?>
 <script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Residence",
  "name": "<?php echo $row_rs_structured_review['streetnumber']; ?>, <?php echo $row_rs_structured_review['streetname']; ?>, <?php echo $row_rs_structured_review['city']; ?>, <?php echo $row_rs_structured_review['postal_code']; ?>",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "<?php echo $row_rs_structured_review['city']; ?>",
    "addressRegion": "<?php echo $row_rs_structured_review['province']; ?>",
    "postalCode": "<?php echo $row_rs_structured_review['postal_code']; ?>",
    "streetAddress": "<?php echo $row_rs_structured_review['streetnumber']; ?>, <?php echo $row_rs_show_review['streetname']; ?>"
  },
    
  
  "review": [
    {
      "@type": "Review",
      "author": "<?php echo $row_rs_structured_review['social_user']; ?>",
      "datePublished": "<?php echo $row_rs_structured_review['ratingDate']; ?>",
      "description": "<?php echo $row_rs_structured_review['comments']; ?>",
      "name": "<?php echo $row_rs_structured_review['feeling']; ?>",
      "reviewRating": {
        "@type": "Rating",
        "bestRating": "<?php echo $row_rs_structured_review['bestRating']; ?>",
        "ratingValue": "<?php echo round($row_rs_structured_review['Avgrating'],0); ?>",
        "worstRating": "<?php echo $row_rs_structured_review['worstRating']; ?>"
      }
    }
  ],
   "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo round($row_rs_structured_review['Avgrating'],0); ?>",
    "reviewCount": "<?php echo $row_rs_structured_review['ratingCount']; ?>"
  },
   "photo": "https://www.killjoy.co.za/<?php echo $row_rs_structured_review['propertyImage']; ?>",
   "image": "https://www.killjoy.co.za/<?php echo $row_rs_structured_review['propertyImage']; ?>"
}
</script>
 <?php } while ($row_rs_structured_review = mysql_fetch_assoc($rs_structured_review)); ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113531379-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113531379-1');
</script>

<meta charset="utf-8">
<link rel="alternate" href="https://www.killjoy.co.za/" hreflang="en-ZA" />
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
<script src="fancybox/libs/jquery-3.3.1.min.js" ></script>
<link rel="stylesheet" href="fancybox/dist/jquery.fancybox.min.css" />
<script src="fancybox/dist/jquery.fancybox.min.js"></script>
<title>killjoy community - rental property - reviews - ratings - sahre your experience - assist future tentants make better decisions</title>
<meta name="description" content="killjoy is an online community of tenants that stand together to ensure fair treatment and guard against renting properties from abusive landlords. We help future tentants" />
<meta name="keywords" content="property, rentals, advice, reviews, ratings, tenants, complaints, unfair, landlords, abuse, assistance" />
<link href="css/media.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
<link href="css/latestreviews.css" rel="stylesheet" type="text/css">
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

	<style type="text/css">
	span.stars, span.stars span {
	display: inline-block;
	height: 48px;
	background-image: url(images/stars/biggerstars.png);
	background-repeat: repeat-x;
	background-position: 0 -48px;
	vertical-align: middle;
	width: inherit;
	text-shadow: 0 0 0px;
}

span.stars span {
    background-position: 0 0;
}
	
.close:before {
	content: '<?php echo $row_rs_user_message['messageCount']; ?>';
	font-family: Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
    font-size: 20px;
	font-weight: bolder;
	color: red;
	width: auto;
	height:auto;
	display: block;
	text-align: center;
	text-shadow: 0 0 5px white;
	font-weight: 700;
		
	}
.close {
	position: absolute;
	top: 0px;
	left: 0px;
	display: block;
	z-index:9999;
	visibility: visible;
	}
.reviewscount {
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
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
	}
	
    </style>
<link href="css/myBtn.css" rel="stylesheet" type="text/css" />

<link href="css/cookies.css" rel="stylesheet" type="text/css" />
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div class="maincontainer" id="maincontainer">
  <div class="header" id="header"><a href="https://www.killjoy.co.za" title="visit the killjoy.co.za home page" ><img src="images/icons/owl-header-white.gif?dummy=09876543" alt="killjoy property rental ratings and reviews" width="512" height="512" class="hdrimg" /></a><div class="facebook-icon"><span class="icon-facebook"></span></div><a  target="_new" href="https://twitter.com/KilljoySocial" title="view the killjoy.co.za twitter social profile page"><div class="twitter-icon"><span class="icon-twitter"></span></div></a>
<?php if ($totalRows_rs_social_users > 0) { // Show if recordset not empty ?>
      <a href="#"><div class="profile" id="profile"><img src="<?php echo $row_rs_social_users['g_image']; ?>" alt="killjoy - rental property reviews and advice" name="profile_image" class="profile_image"></div></a>
      <?php } // Show if recordset not empty ?>
      <div class="memberprofile" id="memberprofile"><a href="member.php" title"view and make changes to your killjoy.co.za profile"><div class="myprofile">My Profile</div></a><a href="myreviews.php" title"view a list of your personal killjoy property reviews"><div class="myreviews">My Reviews</div></a><a title="logout of your killjoy.co.za account"  href="admin/logout.php"><div class="mesignout">Sign Out</div></a></div>
      <div class="memberprofile" id="memberprofile"><a href="member.php" title"view and make changes to your killjoy.co.za profile"><div class="myprofile">My Profile</div></a><a id="inline"  href="myreviews.php" title"view a list of your personal killjoy property reviews"><div class="myreviews">My Reviews</div></a><a title="logout of your killjoy.co.za account"  href="admin/logout.php"><div class="mesignout">Sign Out</div></a></div>
<?php if(!isset($_SESSION['kj_authorized'])) { ?><div class="signin" id="signin"><a href="admin/index.php">Sign in</a></div><?php } ?>
<?php if ($row_rs_user_message['messageCount'] > 0 && (isset($_SESSION['kj_authorized']))) { // Show if recordset not empty ?>
  <div class="messages" id="messages"><span class="icon-envelope-o"></span><div id="close" name="close" class="close"></div></div>
  <?php } // Show if recordset not empty ?>
  <div class="membermessages" id="membermessages">   
  <ul>
      <?php do { $messagesession = $row_rs_member_message['id'] ?>
        <li><a href="mymessages.php?tarsus=<?php echo $captcha?>&claw=<?php echo $messagesession ?>&alula=<?php echo $smith ?>"><?php echo $row_rs_member_message['u_sunject']; ?></a></li>
        <?php } while ($row_rs_member_message = mysql_fetch_assoc($rs_member_message)); ?>
      </ul>
  </div>
  </div>  
  <div id="hidemenus" class="hidemenus">
   <div class="banner" id="banner"></div>
  <div class="heading" id="heading">
    <h1>Killjoy - the online community for rental property tenants</h1></div>
  <div class="intro" id="intro">Killjoy’s main mission is to prevent landlord abuse of rental property tenants. It gives you the power to review a rental property and share your personal experiences with future tenants. Future tenants also have the option to view existing property rental reviews before making a decision on letting the property. This way we can all rent smarter. </div>
  <div class="chooser" id="chooser">
    <a href="review.php" title="review a rental property" ><div class="choosereview" id="choosereview">Review a Rental Property</div></a>
    <a href="findproperties.php"  title="view the reviews and ratings for a rental property" ><div class="chooseview" id="chooseview">View rental property reviews</div></a>   
  </div>
  <div class="latestheader"><h2>Latest Reviews</h2></div>
  <?php do { $sessionid = filter_var($row_rs_latest_reviews['propsession'], FILTER_SANITIZE_SPECIAL_CHARS); $reviewcount = $row_rs_latest_reviews['reviewCount'];?>
  <a  title="There <?php if($reviewcount > 1) { //plural?>are<?php } ?> <?php if($reviewcount < 2) { //singular?>is<?php } ?> <?php echo $reviewcount ?> <?php if($reviewcount > 1) { //plural?>shared experiences<?php } ?> <?php if($reviewcount < 2) { //singular?>shared experience<?php } ?> for <?php echo $row_rs_latest_reviews['streetnumber']; ?> <?php echo $row_rs_latest_reviews['streetname']; ?> <?php echo $row_rs_latest_reviews['city']; ?>" href="viewer.php?tarsus=<?php echo $captcha?>&claw=<?php echo $sessionid ?>&alula=<?php echo $smith ?>"><div class="latestreviews" id="latestreviews">    
      <div class="propertyimagecontainer" id="propertyimagecontainer"><img src="<?php echo $row_rs_latest_reviews['propertyImage']; ?>" alt="killjoy property rental reviews and advice" class="propertyimage" /><div class="reviewscount"><?php echo $reviewcount ?></div></div>
      <div class="addressbox" id="addressbox"><address><?php echo $row_rs_latest_reviews['streetnumber']; ?><br /><?php echo $row_rs_latest_reviews['streetname']; ?><br /><?php echo $row_rs_latest_reviews['city']; ?></address></div>
      <div class="ratingbox"><span class="stars" id="stars"><?php echo $row_rs_latest_reviews['Avgrating']; ?></span><div class="rating-value"><?php echo round($row_rs_latest_reviews['Avgrating'],1); ?></div></div><div class="latest-impressions">  <div class="like-action"><?php if ($row_rs_latest_reviews['likes'] > 0) { ?><span class="like-count"><?php echo $row_rs_latest_reviews['likes'] ?></span><span id="heart-o" style="color: red;cursor: pointer" class="icon-heart"></span><?php } ?><?php if($row_rs_latest_reviews['likes'] == 0) { ?><span style="cursor: pointer" onClick="review_likes('<?php echo $sessionid;?>')" class="icon-heart-o"></span><?php } ?></div><div class="comment-action"><span class="comment-count"><?php echo $row_rs_latest_reviews['social_comments'] ?></span><span  class="icon-bubble"></span></div><div class="impression-action"><span class="impression-count"><?php echo $row_rs_latest_reviews['impressions'] ?></span><span class="icon-stats-bars"></span></div><div class="share-action"><span class="share-count"><?php echo $row_rs_latest_reviews['total_shares'] ?></span><span class="icon-mail-forward"></span></div></div></div>
    </a>
	  <hr class="review-devider"></hr>
    <?php } while ($row_rs_latest_reviews = mysql_fetch_assoc($rs_latest_reviews)); ?>
    </div>
<div class="footer" id="footerdiv">&copy; <?php echo date("Y"); ?> Copyright killjoy.co.za. All rights reserved.
    <div class="designedby" id="designedby"><span class="icon-bolt"></span> <a href="https://www.midnightowl.co.za" target="_new"  title="view the designers of this site">Midnight Owl</a><div id="assistant" class="killjoy-assist"><span class="icon-question"></span></div><a href="info-centre/index.html" target="_new"><div id="info-center" class="info-center"><span class="icon-info"></span></div></a></div>
  </div>
	<div id="searchbox" class="search-box"></div>
  <?php if ($consent == 0) { // Show if recordset empty ?>
  <div class="cookiewarning" id="cookiewarning">    
  <div class="cookiemessage" id="cookiemessage">This site uses cookies. By continuing you <a  target="_new" title="View our cookie policy" href="info-centre/cookie-policy.php">agree to our use of cookies</a>.</div><a onClick="my_button('<?php echo $click_time; ?>')" href="#"><div class="gotit">Got it!</div></a></div>   

</div>
<?php } // Show if recordset empty ?>


<script type="text/javascript">
$(document).ready(function() {
/* This is basic - uses default settings */

$("a#single_image").fancybox();
/* Using custom settings */

$("a#inline").fancybox({
helpers : {
overlay : {
css : {
  'background' : 'rgba(200, 201, 203, 0.40)'
   }
}
},
'opacity' : 0.4,
'width' :  256,
'height' : 128,
'autoSize' : false,		

'hideOnContentClick': true	});
modal: false,

/* Apply fancybox to multiple items */

$("a.grouped_elements").fancybox({
'transitionIn'	:	'elastic',
'transitionOut'	:	'elastic',
'speedIn'		:	600, 
'speedOut'		:	200, 
'overlayShow'	:	false
});

});
</script>
<script type="text/javascript">
function Load_external_content()
{
      $('#latestreviews').load('latestreviews').hide().fadeIn(3000);
}
setInterval('latestreviews()', 10000);
</script>

<script type="text/javascript">
$(document).ready(
    function() {
        $("#assistant").click(function() {
            $("#searchbox").fadeToggle();
			 		
        });
    });
</script>

<script type="text/javascript">
$(document).ready(
    function() {
        $("#profile").click(function() {
            $("#memberprofile").fadeToggle();
			 $("#membermessages").hide();
			
        });
    });
</script>
<script type="text/javascript">
$(document).ready(
    function() {
        $("#messages").click(function() {
            $("#membermessages").fadeToggle();
			$("#memberprofile").hide();
        });
    });
</script>
<script type="text/javascript">
$(document).ready(
    function() {
        $("#hidemenus").click(function() {
            $("#membermessages").hide();
			$("#memberprofile").hide();
			$("#searchbox").hide();
			        });
    });
</script>
<script type="text/javascript">
 // When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {footerFunction()};
 function footerFunction() {
if ($(window).scrollTop() == $(document).height()-$(window).height()) {
document.getElementById("footerdiv").style.display = "block";
document.getElementById("myBtn").style.display = "block";
} else {
document.getElementById("footerdiv").style.display = "none";
document.getElementById("myBtn").style.display = "none";
}
}
 // When the user clicks on the button, scroll to the top of the document
function topFunction() {
document.body.scrollTop = 0;
document.documentElement.scrollTop = 0;
}
 
</script>
<script type="text/javascript">
$(function() {
$('span.stars').stars();
});
  </script>
  
  
  
<button onClick="topFunction()" id="myBtn" title="Go to top">Top</button>


<script type="text/javascript">
 function my_button ( click_time ) 
{ $.ajax( { type    : "POST",
data    : { "time_is" : click_time }, 
url     : "functions/consent_cookie.php",
success : function (click_time)

{ 
$('#cookiewarning').removeClass('cookiewarning');
$('#cookiewarning').load(document.URL +  ' #cookiewarning');  	
  
},
error   : function ( xhr )
{ alert( "error" );
}
} );
 }
</script>
</body>
</html>




