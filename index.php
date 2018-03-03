<?php require_once('Connections/killjoy.php'); ?>

<?php

ob_start();
if (!isset($_SESSION)) {
session_start();
}
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
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

$colname_rs_kj_recall = "-1";
if (isset($_COOKIE['kj_recallmember'])) {
  $colname_rs_kj_recall = $_COOKIE['kj_recallmember'];
}  

mysql_select_db($database_killjoy, $killjoy);
$query_rs_kj_recall = sprintf("SELECT g_email, kj_recall.social_users_pass AS password, g_active FROM social_users LEFT JOIN kj_recall on kj_recall.social_user_id = social_users.id WHERE g_email = %s AND g_active = 1", GetSQLValueString($colname_rs_kj_recall, "text"));
$rs_kj_recall = mysql_query($query_rs_kj_recall, $killjoy) or die(mysql_error());
$row_rs_kj_recall = mysql_fetch_assoc($rs_kj_recall);
$totalRows_rs_kj_recall = mysql_num_rows($rs_kj_recall);

  $loginUsername=$row_rs_kj_recall ['g_email'];
  $loginPassword=$row_rs_kj_recall ['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_killjoy, $killjoy);
  
  $LoginRS__query=sprintf("SELECT g_email, g_pass FROM social_users WHERE g_email=%s AND g_pass=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($loginPassword, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $killjoy) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);  
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['kj_username'] = $loginUsername;
    $_SESSION['kj_usergroup'] = $loginStrGroup;	
	$_SESSION['kj_authorized'] = "1"; 
	
	
  // Put your whole code here
  	 echo '<script type="text/javascript">$("#header).load(document.URL +  " #header");</script>';
    

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
$query_rs_latest_reviews = "select tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, DATE_FORMAT(tbl_address_comments.rating_date, '%d-%b-%y')AS ratingDate, ROUND(AVG(tbl_address_rating.rating_value),2) AS Avgrating, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage from tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.sessionid = tbl_address.sessionid LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.sessionid = tbl_address.sessionid WHERE (tbl_address_comments.rating_date > DATE_SUB(now(), INTERVAL 1 MONTH)) AND tbl_approved.is_approved = 1 GROUP BY tbl_address.sessionid ORDER BY tbl_address_comments.rating_date DESC";
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type='application/ld+json'> 
{
  "@context": "http://www.schema.org",
  "@type": "WebSite",
  "name": "Killjoy",
     "author": {
      "@type": "Website",
      "name": "Midnight Owl",
	   "url": "https://www.midnightowl.co.za"
    },
     "image": "https://www.killjoy.co.za/images/logos/logo.gif",
     "sameAs": [
    "https://www.facebook.com/killjoy",
    "https://www.twittter.com/killjoy"
 
  ],
  "alternateName": "a community for rental property tenants",
  "url": "https://www.killjoy.co.za",
  "description": "Killjoy is an online comunity where rental property tenants can share their personal experiences with future tenants",
  "keywords" :"rental properties, reviews, ratings, complaints, share, experiences, tenants "
 
}
 </script>
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
    </style>
<link href="css/myBtn.css" rel="stylesheet" type="text/css" />

<link href="css/tooltips.css" rel="stylesheet" type="text/css" />
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div class="maincontainer" id="maincontainer">
  <div class="header" id="header"><a href="https://www.killjoy.co.za" title="visit the killjoy.co.za home page" class="masterTooltip"><img src="images/icons/owl-header-white.gif?dummy=09876543" alt="killjoy property rental ratings and reviews" width="512" height="512" class="hdrimg" /></a><span style="padding-top:10px; padding-right:20px;" class="icon-facebook"></span><a class="masterTooltip" target="_new" href="https://twitter.com/KilljoySocial" title="view the killjoy.co.za twitter social profile page"><span class="icon-twitter"></span></a>
<?php if ($totalRows_rs_social_users > 0) { // Show if recordset not empty ?>
      <a href="#"><div class="profile" id="profile"><img id="profile_image" src="<?php echo $row_rs_social_users['g_image']; ?>" alt="killjoy - rental property reviews and advice"></div></a>
      <?php } // Show if recordset not empty ?>
      <div class="memberprofile" id="memberprofile"><a href="member.php" title"view and make changes to your killjoy.co.za profile"><div class="myprofile">My Profile</div></a><a id="inline"  href="myreviews.php" title"view a list of your personal killjoy property reviews"><div class="myreviews">My Reviews</div></a><a title="logout of your killjoy.co.za account"  href="admin/logout.php"><div class="mesignout">Sign Out</div></a></div>
<?php if(!isset($_SESSION['kj_authorized'])) { ?><div class="signin" id="signin"><a href="admin/index.php"><font size="+2">Sign in</font></a></div><?php } ?>
      </div>  
   <div class="banner" id="banner"></div>
  <div class="heading" id="heading">
    <h1>Killjoy - the online community for rental property tenants</h1></div>
  <div class="intro" id="intro">Killjoy’s main mission is to prevent landlord abuse of rental property tenants. It gives you the power to review a rental property and share your personal experiences with future tenants. Future tenants also have the option to view existing property rental reviews before making a decision on letting the property. This way we can all rent smarter. </div>
  <div class="chooser" id="chooser">
    <a href="review.php" title="review a rental property" class="masterTooltip"><div class="choosereview" id="choosereview">Review a Rental Property</div></a>
    <a id="inline" href="#viewpropertyreview" title="view the reviews and ratings for a rental property" class="masterTooltip"><div class="chooseview" id="chooseview">View rental property reviews</div></a>   
  </div>
  <div class="latestheader"><h2>Latest Reviews</h2></div>
  <?php do { $sessionid = filter_var($row_rs_latest_reviews['propsession'], FILTER_SANITIZE_SPECIAL_CHARS);?>
    <a class="masterTooltip" title="view the tenants experience of <?php echo $row_rs_latest_reviews['streetnumber']; ?> <?php echo $row_rs_latest_reviews['streetname']; ?> <?php echo $row_rs_latest_reviews['city']; ?>" href="viewer.php?tarsus=<?php echo $captcha?>&claw=<?php echo $sessionid ?>&alula=<?php echo $smith ?>"><div class="latestreviews" id="latestreviews">    
      <div class="propertyimagecontainer" id="propertyimagecontainer"><img src="<?php echo $row_rs_latest_reviews['propertyImage']; ?>" alt="killjoy property rental reviews and advice" class="propertyimage" /></div>
      <div class="addressbox" id="addressbox"><address><?php echo $row_rs_latest_reviews['streetnumber']; ?><br /><?php echo $row_rs_latest_reviews['streetname']; ?><br /><?php echo $row_rs_latest_reviews['city']; ?></address></div>
      <div class="ratingbox">Rating: <span class="stars" id="stars"><?php echo $row_rs_latest_reviews['Avgrating']; ?></span><?php echo $row_rs_latest_reviews['Avgrating']; ?></div>
      <div class="datebox">Date: <?php echo $row_rs_latest_reviews['ratingDate']; ?></div>
    </div>
    </a>
    <?php } while ($row_rs_latest_reviews = mysql_fetch_assoc($rs_latest_reviews)); ?>
<div class="footer" id="footerdiv">&copy; <?php echo date("Y"); ?> Copyright killjoy.co.za. All rights reserved.
    <div class="designedby" id="designedby">Designed and Maintained by <a href="http://www.midnightowl.co.za" title="view the designers of this site" target="_new">Midnight Owl</a></div>
  </div>
</div>



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
        $("#profile").click(function() {
            $("#memberprofile").fadeToggle();
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
mysql_free_result($rs_latest_reviews);
?>



