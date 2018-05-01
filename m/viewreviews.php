<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('Connections/killjoy.php');

$page = $_SERVER['REQUEST_URI'];
$_SESSION['PrevUrl'] = $page;


$is_authorized = -1;
if (isset($_SESSION['kj_authorized']) && $_SESSION['kj_authorized'] == 1) {
$is_authorized = $_SESSION['kj_authorized'];	
} else {
$is_authorized == 0;	
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

$currentPage = $_SERVER["PHP_SELF"];
$maxRows_rs_show_review = 1;
$pageNum_rs_show_review = 0;
if (isset($_GET['pageNum_rs_show_review'])) {
  $pageNum_rs_show_review = $_GET['pageNum_rs_show_review'];
}
$startRow_rs_show_review = $pageNum_rs_show_review * $maxRows_rs_show_review;

$colname_rs_show_review = "-1";
if (isset($_GET['claw'])) {
  $colname_rs_show_review = $_GET['claw'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_review = sprintf("SELECT DISTINCT tbl_address_comments.id AS commentId, tbl_address_comments.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, tbl_address.postal_code AS postalCode, province AS province, rating_feeling as feeling, tbl_address_comments.rating_date AS ratingDate, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage, IF(social_users.anonymous='0',social_users.g_name,'Anonymous') As socialUser,(SELECT COUNT(tbl_likes.count) FROM tbl_likes WHERE tbl_likes.address_comment_id=tbl_address_comments.id AND tbl_likes.count=1) AS likes, ROUND(AVG(tbl_address_rating.rating_value),0) AS Avgrating, tbl_address_comments.rating_comments AS comments FROM tbl_address_comments LEFT JOIN tbl_address ON tbl_address.sessionid = tbl_address_comments.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address_comments.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id LEFT JOIN social_users ON social_users.g_email = tbl_address_comments.social_user WHERE tbl_address_comments.sessionid = %s AND tbl_approved.is_approved='1' GROUP BY tbl_address_comments.id ORDER BY tbl_address_comments.rating_date DESC", GetSQLValueString($colname_rs_show_review, "text"));
$query_limit_rs_show_review = sprintf("%s LIMIT %d, %d", $query_rs_show_review, $startRow_rs_show_review, $maxRows_rs_show_review);
$rs_show_review = mysql_query($query_limit_rs_show_review, $killjoy) or die(mysql_error());
$row_rs_show_review = mysql_fetch_assoc($rs_show_review);
$addresscommentid = $row_rs_show_review['commentId'];

if ((isset($_GET["claw"])) && ($_GET["claw"] != " ")) {
  $updateSQL = sprintf("INSERT INTO tbl_impressions (address_comment_id, count) VALUES (%s,%s)",
                       GetSQLValueString($addresscommentid, "int"),
                       GetSQLValueString('1', "int"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
}

if (isset($_GET['totalRows_rs_show_review'])) {
  $totalRows_rs_show_review = $_GET['totalRows_rs_show_review'];
} else {
  $all_rs_show_review = mysql_query($query_rs_show_review);
  $totalRows_rs_show_review = mysql_num_rows($all_rs_show_review);
}
$totalPages_rs_show_review = ceil($totalRows_rs_show_review/$maxRows_rs_show_review)-1;
$property_id = $row_rs_show_review['propsession'];
$ratingdate = $row_rs_show_review['ratingDate'];

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


$colname_rs_structured_review = "-1";
if (isset($_GET['claw'])) {
  $colname_rs_structured_review = $_GET['claw'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_structured_review = sprintf("select tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, tbl_address.postal_code as postal_code, tbl_address.province as province, tbl_address_comments.rating_feeling as feeling, tbl_address_comments.rating_comments as comments, tbl_address_comments.social_user as social_user, (SELECT COUNT(tbl_approved.sessionid) FROM tbl_approved WHERE tbl_approved.sessionid = tbl_address_comments.sessionid AND tbl_approved.is_approved=1) AS reviewCount, DATE_FORMAT(tbl_address_comments.rating_date, '%%d-%%b-%%y')AS ratingDate, ROUND(AVG(tbl_address_rating.rating_value),2) AS Avgrating, MIN(tbl_address_rating.rating_value) AS worstRating, MAX(tbl_address_rating.rating_value) AS bestRating, COUNT(tbl_address_rating.rating_value) AS ratingCount, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage from tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id WHERE tbl_address_comments.sessionid = %s AND tbl_approved.is_approved=1 GROUP BY tbl_address_comments.sessionid", GetSQLValueString($colname_rs_structured_review, "text"));
$rs_structured_review = mysql_query($query_rs_structured_review, $killjoy) or die(mysql_error());
$row_rs_structured_review = mysql_fetch_assoc($rs_structured_review);
$totalRows_rs_structured_review = mysql_num_rows($rs_structured_review);

mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_comments = "SELECT *, IF(social_users.anonymous='0',social_users.g_name,'Anonymous') As socialUser FROM tbl_review_comments LEFT JOIN social_users ON social_users.g_email=tbl_review_comments.social_user WHERE address_comment_id = '$addresscommentid' ORDER BY comment_date DESC";
$rs_show_comments = mysql_query($query_rs_show_comments, $killjoy) or die(mysql_error());
$row_rs_show_comments = mysql_fetch_assoc($rs_show_comments);
$totalRows_rs_show_comments = mysql_num_rows($rs_show_comments);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="../ScriptLibrary/jquery-latest.pack.js"></script>
<script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_ZA</script>
<script type="text/javascript">
  window.___gcfg = {lang: 'en-GB'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
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
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="@KilljoySocial" />
<meta name="twitter:creator" content="@iwan_ross" />
<meta name="twitter:title" content="killjoy.co.za view the review for <?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?> <?php echo $row_rs_show_review['city']; ?> <?php echo $row_rs_show_review['postalCode']; ?> ">
<meta name="twitter:description" content="The reviewer's experience: <?php echo $row_rs_show_review['comments']; ?>">
<meta name="twitter:image:src" content="https://www.killjoy.co.za/<?php echo $row_rs_show_review['propertyImage']; ?>">
<meta property="og:type" content="place" />
<meta property="og:url" content="<?php echo $page ?>"/>
<meta property="og:title" content="killjoy.co.za view the review for <?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?> <?php echo $row_rs_show_review['city']; ?> <?php echo $row_rs_show_review['postalCode']; ?> " />
<meta property="og:description" content="The reviewer's experience: <?php echo $row_rs_show_review['comments']; ?>" />
<meta property="og:image" content="https://www.killjoy.co.za/<?php echo $row_rs_show_review['propertyImage']; ?>" />
<meta property="fb:app_id" content="1787126798256435" />
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za" />
<title><?php echo $row_rs_show_review['streetnumber']; ?>,<?php echo $row_rs_show_review['streetname']; ?>,<?php echo $row_rs_show_review['city']; ?>,<?php echo $row_rs_show_review['postalCode']; ?></title>
<meta name="description" content="<?php echo $row_rs_show_review['comments']; ?>" />
<meta name="keywords" content="<?php echo $row_rs_show_review['streetnumber']; ?>, <?php echo $row_rs_show_review['streetname']; ?>, <?php echo $row_rs_show_review['city']; ?>, <?php echo $row_rs_show_review['postalCode']; ?>, property, rentals, reviews, ratings, experience, share, social " />
<link href="css/view-reviews/profile.css" rel="stylesheet" type="text/css" />
<link href="css/view-reviews/social.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	span.stars, span.stars span {
	display: inline-block;
	height: 48px;
	background-image: url(images/stars/biggerstars.png);
	background-repeat: repeat-x;
	background-position: 0 -48px;
	vertical-align: middle;
	width: 240px;
}

span.stars span {
    background-position: 0 0;
}
	
    </style>
<link href="css/pagenav.css" rel="stylesheet" type="text/css" />
<link href="../jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/jquery.ui.dialog.min.css" rel="stylesheet" type="text/css" />
<link href="../SpryAssets/jquery.ui.resizable.min.css" rel="stylesheet" type="text/css" />
<link href="css/dialog-styling.css" rel="stylesheet" type="text/css" />
<script src="../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<script src="../SpryAssets/jquery.ui-1.10.4.dialog.min.js"></script><script type="text/javascript" src="../ScriptLibrary/dmxDataBindings.js"></script>
<script type="text/javascript" src="../ScriptLibrary/dmxDataSet.js"></script>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.12&appId=1787126798256435&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
   <div data-role="page" id="viewreviews-page">
<div id="formcontainer">
 <div class="reivews-header">
  <img src="../<?php echo $row_rs_show_review['propertyImage']; ?>" alt="<?php echo $row_rs_show_review['streetnumber']; ?>, <?php echo $row_rs_show_review['streetname']; ?>, <?php echo $row_rs_show_review['city']; ?>, <?php echo $row_rs_show_review['postalCode']; ?>" class="propertyimage" /> 
  <div class="addressfield"><address><?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?> <?php echo $row_rs_show_review['city']; ?> <?php echo $row_rs_show_review['postalCode']; ?></address></div></div>
<div class="fieldlabels" id="fieldlabels3">Review Date:</div>
  <div class="userbox"><?php echo date('d M Y' , strtotime($row_rs_show_review['ratingDate'])); ?></div>
  <div class="fieldlabels" id="fieldlabels1">Rating:</div>
  <div class="rating-container"> <div class="ratingbox"><span class="stars" id="stars"><?php echo round($row_rs_show_review['Avgrating'],'0'); ?></span></div><div class="rating-text"><?php echo $row_rs_show_review['Avgrating']; ?></div><div class="total-reviews">From <?php echo $totalRows_rs_show_review ?> reviews.</div></div> 
    <div class="fieldlabels" id="fieldlabels2">Reviewer:</div>
  <div class="userbox"><?php echo $row_rs_show_review['socialUser']; ?> <span style="color: #000000;">feeling very</span> <div class="mood-feeling"><?php if (!(strcmp($row_rs_show_review['feeling'],"Happy"))) {echo "<span class='icon-smile'></span>";} ?><?php if (!(strcmp($row_rs_show_review['feeling'],"Sad"))) {echo "<span class='icon-sad'></span>";} ?></div></div>
  
  <div class="fieldlabels" id="fieldlabels3">What the reviewer had to say:  </div>
   <div class="commentbox"><?php echo $row_rs_show_review['comments']; ?></div>
  <input name="txt_commentId" type="hidden" id="txt_commentId" value="<?php echo $row_rs_show_review['commentId']; ?>" />
<div class="fieldlabels" id="fieldlabels3">Share this review:</div>
 
<div class="socialicons" id="socialicons"> <div class="fb-share-button"  data-href="<?php echo $page ?>" data-size="large" data-layout="button_count">
  </div><div class="gplus-share"><div class="g-plus" data-action="share" data-height="42" data-href="<?php echo $page ?>"></div></div><div class="in-share"><script type="IN/Share" data-url="<?php echo json_encode($page) ?>" ></script></div><div class="tweet-share"><a class="twitter-share-button" href="https://twitter.com/share" data-size="large" data-text="<?php echo $page ?>" data-url="<?php echo $page ?>" data-hashtags="example,demo" data-via="twitterdev"
  data-related="twitterapi,twitter" onClick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img src="images/icons/tweet-button-85x30.png" width="94" height="31" /></a></div><div id="reviewlikes" class="like-action"><?php if ($row_rs_show_review['likes'] > 0) { ?><span onClick="review_unlikes('<?php echo $addresscommentid;?>')" id="heart" style="color: red;cursor: pointer; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="icon-heart"></span><span class="like-count"><?php echo $row_rs_show_review['likes'] ?> </span><?php } ?><?php if($row_rs_show_review['likes'] == 0) { ?><span style="cursor: pointer" onClick="review_likes('<?php echo $addresscommentid;?>')" class="icon-heart-o"></span><?php } ?></div></div>
        <?php if ($totalRows_rs_show_review > 1) { // Show if recordset not empty ?>
<div class="navcontainer" id="navbar"><?php if ($pageNum_rs_show_review > 0) { // Show if not first page ?><div onClick="window.location.href='<?php printf("%s?pageNum_rs_show_review=%d%s", $currentPage, max(0, $pageNum_rs_show_review - 1), $queryString_rs_show_review); ?>'" class="prevbtn">
        </div><?php } // Show if not first page ?><div class="navtext">Showing review <?php echo ($startRow_rs_show_review + 1) ?> of <?php echo $totalRows_rs_show_review ?></div>
    <?php if ($pageNum_rs_show_review < $totalPages_rs_show_review) { // Show if not last page ?>
    <div onClick="window.location.href='<?php printf("%s?pageNum_rs_show_review=%d%s", $currentPage, min($totalPages_rs_show_review, $pageNum_rs_show_review + 1), $queryString_rs_show_review); ?>'" class="netxbtn">     
     </div> <?php } // Show if not last page ?></div>
  <?php } // Show if recordset not empty ?>
  <div class="comment-header" id="commentscount"><?php if ($totalRows_rs_show_comments == 0) { // Show if recordset not empty ?>0 Comments<?php } ?><?php if ($totalRows_rs_show_comments > 0) { // Show if recordset not empty ?><?php echo $totalRows_rs_show_comments ?> <?php if($totalRows_rs_show_comments < 2) { //singulare ?>Comment<?php }//singular ?><?php if($totalRows_rs_show_comments > 1) { //singulare ?> Comments<?php }//singular ?><?php } ?></div>
<div class="social_comments" id="socialcomments"><textarea  <?php if($is_authorized == -1) {  ?>placeholder="Sign in to post and view comments"<?php } ?> name="add_comments" id="add_comments" cols="" rows="" class="social-comment-box"></textarea><div class="social-comment-btn-container">
   <?php if($is_authorized == -1) {  ?>
  <input onclick="location.href = 'admin/index-signin.php';" name="btn_signin" type="button" class="social-comment-not-logged-in-btn" id="btn_signin" value="Sign in to post" />
   <?php } ?>
 <?php if($is_authorized == 1) {  ?><input onClick="update_comments()" name="post_in" id="post-comment" type="button" class="social-comment-logged-in-btn" value="Post"><?php } ?></div>  <?php if($is_authorized == 1) {  ?>
  <?php do { ?>
    <div class="reviewcomments" id="reviewcomments"><?php if ($totalRows_rs_show_comments > 0) { // Show if recordset not empty ?><?php echo $row_rs_show_comments['social_comments']; ?><span class="commenter" id="commenter"> -- <?php echo $row_rs_show_comments['socialUser']; ?> - <?php echo date('d M Y' , strtotime($row_rs_show_comments['comment_date'])); ?> </span> <?php } // Show if recordset not empty ?></div>
    <?php } while ($row_rs_show_comments = mysql_fetch_assoc($rs_show_comments)); ?> 
  <?php } ?></div>  


</div>


</div>


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


</script>

<script type="text/javascript">
	 var elem = $("#formcontainer");
	$("#formcontainer").dialog({ closeText: '' });
     elem.dialog({
     resizable: false,
	 autoOpen: false,
     title: '<?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?>',
	 draggable: false,
    });     // end dialog
     elem.dialog('open');
	$('#formcontainer').bind('dialogclose', function(event) {
     window.location = "index.php";
 });
	
	</script>
	
	<script type="text/javascript">
function review_likes ( addresscommentid ) 
{ $.ajax( { type    : "POST",
async   : false,
data    : { "txt_sessionid" : addresscommentid }, 
url     : "functions/reviewlikes.php",
beforeSend: function() {
        // setting a timeout
       <?php if (!(isset($_SESSION['kj_username']) && $_SESSION['kj_username'] != '')) { ?>		   
		   $(window.location.href = 'admin/index-signin.php');	
	<?php } ?>
    },
success : function ( sessionid )
		   
{ 
	$('#reviewlikes').removeClass('like-action'); 
	$('#reviewlikes').load(document.URL +  ' #reviewlikes'); 	   
},
error   : function ( xhr )
{ 
	alert( "You are not Logged in" );
}
 });
 
 return false;
 
 }
</script>
	
	<script type="text/javascript">
function review_unlikes ( addresscommentid ) 
{ $.ajax( { type    : "POST",
async   : false,
data    : { "txt_sessionid" : addresscommentid }, 
url     : "functions/reviewunlikes.php",
beforeSend: function() {
        // setting a timeout
       <?php if (!(isset($_SESSION['kj_username']) && $_SESSION['kj_username'] != '')) { ?>		   
		   $(window.location.href = 'admin/index-signin.php');	
	<?php } ?>
    },
success : function ( sessionid )
		   
		   
{ 
	$('#reviewlikes').removeClass('like-action'); 
	$('#reviewlikes').load(document.URL +  ' #reviewlikes'); 
	
   
						   
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>
 
	<script type="text/javascript">
function share_counter ( addresscommentid ) 
{ $.ajax( { type    : "POST",
async   : false,
data    : { "txt_sessionid" : addresscommentid }, 
url     : "functions/reviewlikes.php",
success : function ( sessionid )
		   
{ 
	$('#reviewlikes').removeClass('like-action'); 
	$('#reviewlikes').load(document.URL +  ' #reviewlikes'); 	   
},
error   : function ( xhr )
{ 
	alert( "You are not Logged in" );
}
 });
 
 return false;
 
 }
</script>

<script type="text/javascript">
   $("#comments").autogrow();
</script>  

<script type="text/javascript">
 function update_comments ( txt_comments )  
{ $.ajax( { type    : "POST",
data: {"txt_commentId" : $("#txt_commentId").val(), "txt_comments" : $("textarea#add_comments").val()},
url     : "functions/socialcomments.php",
success : function (data)
{ 
    $("#reviewcomments").removeClass("reviewcomments");
	 $("#commentscount").removeClass("comment-header");
	 $("#socialcomments").removeClass("social_comments");
    $("#reviewcomments").load(location.href + " #reviewcomments");
	$("#commentscount").load(location.href + " #commentscount");
	$("#socialcomments").load(location.href + " #socialcomments");
	
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>

<script type="text/javascript">
// Get the input field
var input = document.getElementById("add_comments");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Cancel the default action, if needed
  event.preventDefault();
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Trigger the button element with a click
    document.getElementById("post-comment").click();
  }
});
</script>


 </body>
</html>

