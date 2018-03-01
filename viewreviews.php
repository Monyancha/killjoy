<?php require_once('Connections/killjoy.php'); ?>
<?php

$page = $_SERVER['REQUEST_URI'];
$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

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
$query_rs_show_review = sprintf("SELECT DISTINCT tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, tbl_address.postal_code AS postalCode, province AS province, rating_feeling as feeling, DATE_FORMAT(tbl_address_comments.rating_date, '%%d-%%b-%%y')AS ratingDate, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage, IFNULL(social_users.g_name, 'Anonymous') As socialUser, tbl_address_comments.rating_comments AS comments FROM tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.sessionid = tbl_address.sessionid LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.sessionid = tbl_address.sessionid LEFT JOIN social_users ON social_users.g_email = tbl_address_comments.social_user WHERE tbl_address.sessionid = %s GROUP BY tbl_address.sessionid ORDER BY tbl_address_comments.rating_date DESC", GetSQLValueString($colname_rs_show_review, "text"));
$rs_show_review = mysql_query($query_rs_show_review, $killjoy) or die(mysql_error());
$row_rs_show_review = mysql_fetch_assoc($rs_show_review);
$totalRows_rs_show_review = mysql_num_rows($rs_show_review);
$property_id = $row_rs_show_review['propsession'];

$colname_rs_show_rating = "-1";
if (isset($_GET['claw'])) {
  $colname_rs_show_rating = $_GET['claw'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_rating = sprintf("SELECT ROUND(AVG(tbl_address_rating.rating_value),2) AS Avgrating, COUNT(tbl_address_rating.id) AS ratingCount, MAX(tbl_address_rating.rating_value) AS bestRating,  MIN(tbl_address_rating.rating_value) AS worstRating FROM tbl_address_rating WHERE sessionid = %s", GetSQLValueString($colname_rs_show_rating, "text"));
$rs_show_rating = mysql_query($query_rs_show_rating, $killjoy) or die(mysql_error());
$row_rs_show_rating = mysql_fetch_assoc($rs_show_rating);
$totalRows_rs_show_rating = mysql_num_rows($rs_show_rating);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta property="og:url" content="https://www.killjoy.co.za/viewreviews.php" />
<meta property="og:type" content="place" />
<meta property="og:title" content="<a href='https://www.killjoy.co.za'>killjoy.co.za</a> view the review for <?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?> <?php echo $row_rs_show_review['city']; ?> <?php echo $row_rs_show_review['postalCode']; ?> " />
<meta property="og:description" content="The tentant share their experience of living at this address" />
<meta property="og:image" content="https://www.killjoy.co.za/<?php echo $row_rs_show_review['propertyImage']; ?>" />
<meta property="fb:app_id" content="1787126798256435" />
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Residence",
  "name": "<?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?> <?php echo $row_rs_show_review['city']; ?>",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "<?php echo $row_rs_show_review['city']; ?>",
    "addressRegion": "<?php echo $row_rs_show_review['province']; ?>",
    "postalCode": "<?php echo $row_rs_show_review['postalCode']; ?>",
    "streetAddress": "<?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?> <?php echo $row_rs_show_review['city']; ?>"
  },
    
  
  "review": [
    {
      "@type": "Review",
      "author": "<?php echo $row_rs_show_review['socialUser']; ?>",
      "datePublished": "<?php echo $row_rs_show_review['ratingDate']; ?>",
      "description": "<?php echo $row_rs_show_review['comments']; ?>",
      "name": "<?php echo $row_rs_show_review['feeling']; ?>",
      "reviewRating": {
        "@type": "Rating",
        "bestRating": "<?php echo $row_rs_show_rating['bestRating']; ?>",
        "ratingValue": "<?php echo $row_rs_show_rating['Avgrating']; ?>",
        "worstRating": "<?php echo $row_rs_show_rating['worstRating']; ?>"
      }
    }
  ],
   "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo $row_rs_show_rating['Avgrating']; ?>",
    "reviewCount": "<?php echo $row_rs_show_rating['ratingCount']; ?>"
  },
   "photo": "https://www.killjoy.co.za/<?php echo $row_rs_show_review['propertyImage']; ?>",
   "image": "https://www.killjoy.co.za/<?php echo $row_rs_show_review['propertyImage']; ?>"
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<title>Killjoy - see the review for <?php echo $row_rs_show_review['streetnumber']; ?>, <?php echo $row_rs_show_review['streetname']; ?>, <?php echo $row_rs_show_review['city']; ?>, <?php echo $row_rs_show_review['postalCode']; ?></title>
<link href="css/view-reviews/profile.css" rel="stylesheet" type="text/css" />
<link href="css/property-reviews/social.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/property-reviews/radios.css" rel="stylesheet" type="text/css" />
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
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.12&appId=1787126798256435&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="formcontainer" id="formcontainer">
  <div class="formheader">Killjoy.co.za Property Review</div>
<div class="imagebox" id="imagebox">  
<img src="<?php echo $row_rs_show_review['propertyImage']; ?>" alt="killjoy.co.za rental property review image" class="propertyimage" /> 
<div class="addressfield"><address><?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?><br /><?php echo $row_rs_show_review['city']; ?><br /><?php echo $row_rs_show_review['postalCode']; ?></address></div>    
  </div>

  <div class="fieldlabels" id="fieldlabels1">Rating:</div>
  <div class="ratingbox"><span class="stars" id="stars"><?php echo $row_rs_show_rating['Avgrating']; ?></span> <?php echo $row_rs_show_rating['Avgrating']; ?> From: <?php echo $row_rs_show_rating['ratingCount']; ?></div>
    <div class="fieldlabels" id="fieldlabels2">Reviewer:</div>
  <div class="userbox"><?php echo $row_rs_show_review['socialUser']; ?></div>
    <div class="fieldlabels" id="fieldlabels2">The tenant's mood:</div>
   <div style="margin-left:25px" class="cc-selector">
        <input disabled="disabled" <?php if (!(strcmp($row_rs_show_review['feeling'],"not a happy tenant"))) {echo "checked=\"checked\"";} ?> id="visa" type="radio" name="credit-card" value="not a happy tenant" />
        <label class="drinkcard-cc visa" for="visa"></label>
        <input disabled="disabled" readonly="readonly" <?php if (!(strcmp($row_rs_show_review['feeling'],"a very happy tenant"))) {echo "checked=\"checked\"";} ?> id="mastercard" type="radio" name="credit-card" value="a very happy tenant" />
        <label class="drinkcard-cc mastercard"for="mastercard"></label>
    </div>
    <div class="fieldlabels" id="fieldlabels3">Review Date:</div>
  <div class="userbox"><?php echo $row_rs_show_review['ratingDate']; ?></div>
  <div class="fieldlabels" id="fieldlabels3">The shared experience:</div>
 <div class="commentbox"><?php echo $row_rs_show_review['comments']; ?></div>
 <div class="socialicons" id="socialicons"><div class="fb-like" data-layout="button_count" data-action="like" data-href="<?php echo $page ?>" data-size="large" data-show-faces="false" data-share="true"></div><div class="gplus-share"><div class="g-plus" data-action="share" data-height="42" data-href="<?php echo $page ?>"></div><div class="in-share"><script type="IN/Share" data-url="<?php echo json_encode($page) ?>" ></script></div></div>
</div>
<div class="social_comments"><div class="fb-comments" data-href="https://www.killjoy.co.za/viewreviews.php" data-width="80%" data-numposts="5"></div></div>

</div>
<script>
    $(".fb-comments").attr("data-href", window.location.href);
</script>
<script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
<script type="text/javascript">
  window.___gcfg = {lang: 'en-GB'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
</script>
<script type="text/javascript">
$(function() {
$('span.stars').stars();
});
  </script>
<script type="text/javascript">
   $("#comments").autogrow();
</script>  

<script type="text/javascript">
function facebook_score ( propertyident ) 
{ $.ajax( { type    : "POST",
data    : { "property_id" : propertyident }, 
url     : "functions/facebook_clicks.php",
success : function (recipeident)
{ 
window.open(
  'https://www.facebook.com/sharer/sharer.php?u=https://www.killjoy.co.za/viewer.php?claw=<?php echo $property_id ?>',
  '_new' // <- This is what makes it open in a new window.
);
$("#socialicons").removeClass("socialicons");
$("#socialicons").load(location.href + " #socialicons");
  
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
mysql_free_result($rs_show_review);

mysql_free_result($rs_show_rating);
?>
