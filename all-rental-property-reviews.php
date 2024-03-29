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

mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_structured_review = "select tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, tbl_address.postal_code as postal_code, tbl_address.province as province, tbl_address_comments.rating_feeling as feeling, tbl_address_comments.rating_comments as comments, tbl_address_comments.social_user as social_user, (SELECT COUNT(tbl_approved.sessionid) FROM tbl_approved WHERE tbl_approved.sessionid = tbl_address_comments.sessionid AND tbl_approved.is_approved=1) AS reviewCount, DATE_FORMAT(tbl_address_comments.rating_date, '%d-%b-%y')AS ratingDate, ROUND(AVG(tbl_address_rating.rating_value),2) AS Avgrating, MIN(tbl_address_rating.rating_value) AS worstRating, MAX(tbl_address_rating.rating_value) AS bestRating, COUNT(tbl_address_rating.rating_value) AS ratingCount, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage from tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id WHERE tbl_approved.is_approved=1 GROUP BY tbl_address_comments.sessionid";
$rs_structured_review = mysqli_query( $killjoy, $query_rs_structured_review) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_structured_review = mysqli_fetch_assoc($rs_structured_review);
$totalRows_rs_structured_review = mysqli_num_rows($rs_structured_review);

mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_all_reviews = "select tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city,(SELECT COUNT(tbl_approved.sessionid) FROM tbl_approved WHERE tbl_approved.sessionid = tbl_address_comments.sessionid AND tbl_approved.is_approved=1) AS reviewCount, DATE_FORMAT(tbl_address_comments.rating_date, '%d-%b-%y')AS ratingDate, ROUND(AVG(tbl_address_rating.rating_value),2) AS Avgrating, MIN(tbl_address_rating.rating_value) AS worstRating, MAX(tbl_address_rating.rating_value) AS bestRating, COUNT(tbl_address_rating.rating_value) AS ratingCount, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage from tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id WHERE tbl_approved.is_approved=1 GROUP BY tbl_address_comments.sessionid";
$rs_all_reviews = mysqli_query( $killjoy, $query_rs_all_reviews) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_all_reviews = mysqli_fetch_assoc($rs_all_reviews);
$totalRows_rs_all_reviews = mysqli_num_rows($rs_all_reviews);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
 <?php } while ($row_rs_structured_review = mysqli_fetch_assoc($rs_structured_review)); ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113531379-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113531379-1');
</script>

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
<script type="text/javascript" src="fancybox/lib/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<script src="SpryAssets/SpryTooltip.js" type="text/javascript"></script>
<title>killjoy community - home page - rate a rental property - review a rental property - help future tenants - warn tenants against abuse</title>
<meta name="description" content="killjoy is an online community of tenants that stand together to ensure fair treatment and guard against renting properties from abusive landlords. We help future tentants" />
<meta name="keywords" content="property, rentals, advice, reviews, ratings, tenants, complaints, unfair, landlords, abuse, assistance" />
<meta name="viewport" content="width=device-width" />
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

.close:before {
	content: '<?php echo $row_rs_user_message['messageCount']; ?>';
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

<link href="css/tooltips.css" rel="stylesheet" type="text/css" />
<link href="css/cookies.css" rel="stylesheet" type="text/css" />
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div class="maincontainer" id="maincontainer">
  <div class="header" id="header"><a href="https://www.killjoy.co.za" title="visit the killjoy.co.za home page" class="masterTooltip"><img src="images/icons/owl-header-white.gif?dummy=09876543" alt="killjoy property rental ratings and reviews" width="512" height="512" class="hdrimg" /></a><span style="padding-top:10px; padding-right:20px;" class="icon-facebook"></span><a class="masterTooltip" target="_new" href="https://twitter.com/KilljoySocial" title="view the killjoy.co.za twitter social profile page"><span class="icon-twitter"></span></a>
      <div class="memberprofile" id="memberprofile"><a href="member.php" title"view and make changes to your killjoy.co.za profile"><div class="myprofile">My Profile</div></a><a id="inline"  href="myreviews.php" title"view a list of your personal killjoy property reviews"><div class="myreviews">My Reviews</div></a><a title="logout of your killjoy.co.za account"  href="admin/logout.php"><div class="mesignout">Sign Out</div></a></div>
  <div class="membermessages" id="membermessages">   
  </div>
  </div>  
  <div id="hidemenus" class="hidemenus">
   <div class="banner" id="banner"></div>
  <div class="heading" id="heading">
    <h1>killjoy.co.za - all rental property reviews</h1></div>
  <div class="intro" id="intro">This page lists all the rental property reviews ever recorder on killjoy.co.za</div>
  <div class="chooser" id="chooser">
    <a href="review.php" title="review a rental property" class="masterTooltip"><div class="choosereview" id="choosereview">Review a Rental Property</div></a>
    <a href="findproperties.php"  title="view the reviews and ratings for a rental property" class="masterTooltip"><div class="chooseview" id="chooseview">View rental property reviews</div></a>   
  </div>
  <div class="latestheader"><h2>Latest Reviews</h2></div>
  <?php do { $sessionid = filter_var($row_rs_all_reviews['propsession'], FILTER_SANITIZE_SPECIAL_CHARS); $reviewcount = $row_rs_all_reviews['reviewCount'];?>
  <a class="masterTooltip" title="There <?php if($reviewcount > 1) { //plural?>are<?php } ?> <?php if($reviewcount < 2) { //singular?>is<?php } ?> <?php echo $reviewcount ?> <?php if($reviewcount > 1) { //plural?>shared experiences<?php } ?> <?php if($reviewcount < 2) { //singular?>shared experience<?php } ?> for <?php echo $row_rs_all_reviews['streetnumber']; ?> <?php echo $row_rs_all_reviews['streetname']; ?> <?php echo $row_rs_all_reviews['city']; ?>" href="viewer.php?tarsus=<?php echo $captcha?>&claw=<?php echo $sessionid ?>&alula=<?php echo $smith ?>"><div class="latestreviews" id="latestreviews">    
      <div class="propertyimagecontainer" id="propertyimagecontainer"><img src="<?php echo $row_rs_all_reviews['propertyImage']; ?>" alt="killjoy property rental reviews and advice" class="propertyimage" /><div class="reviewscount"><?php echo $reviewcount ?></div></div>
      <div class="addressbox" id="addressbox"><address><?php echo $row_rs_all_reviews['streetnumber']; ?><br /><?php echo $row_rs_all_reviews['streetname']; ?><br /><?php echo $row_rs_all_reviews['city']; ?></address></div>
      <div class="ratingbox">Rating: <span class="stars" id="stars"><?php echo $row_rs_all_reviews['Avgrating']; ?></span><?php echo round($row_rs_all_reviews['Avgrating'],0); ?></div>
      <div class="datebox">Date: <?php echo $row_rs_all_reviews['ratingDate']; ?></div>
    </div>
    </a>
    <?php } while ($row_rs_all_reviewss = mysqli_fetch_assoc($rs_all_reviews)); ?>
<div class="footer" id="footerdiv">&copy; <?php echo date("Y"); ?> Copyright killjoy.co.za. All rights reserved.
    <div class="designedby" id="designedby">Designed and Maintained by <a href="https://www.midnightowl.co.za" target="_new"  title="view the designers of this site">Midnight Owl</a></div>
  </div>
  <?php if ($consent == 0) { // Show if recordset empty ?>
  <div class="cookiewarning" id="cookiewarning">    
  <div class="cookiemessage" id="cookiemessage">This site uses cookies. By continuing you <a  target="_new" title="View our cookie policy" href="info-centre/cookie-policy.php">agree to our use of cookies</a>.</div><a onClick="my_button('<?php echo $click_time; ?>')" href="#"><div class="gotit">Got it!</div></a></div>   
</div>
</div>
<?php } // Show if recordset empty ?>


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
<?php
((mysqli_free_result($rs_structured_review) || (is_object($rs_structured_review) && (get_class($rs_structured_review) == "mysqli_result"))) ? true : false);

((mysqli_free_result($rs_all_reviews) || (is_object($rs_all_reviews) && (get_class($rs_all_reviews) == "mysqli_result"))) ? true : false);
?>
