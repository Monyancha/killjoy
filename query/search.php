<?php require_once('../Connections/killjoy.php'); ?>
<?php

$q=$_GET['q'];
$mysqli=mysqli_connect('localhost','euqjdems_nawisso','N@w!1970','euqjdems_killjoy') or die("Database Error");
$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);

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

mysql_select_db($database_killjoy, $killjoy);
$query_rs_structured_review = "SELECT tbl_address_comments.sessionid as id, tbl_address.str_number as strNumber, tbl_address.street_name AS Street, tbl_address.city as city, (SELECT COUNT(tbl_approved.sessionid) FROM tbl_approved WHERE tbl_approved.sessionid = tbl_address_comments.sessionid AND tbl_approved.is_approved=1) AS reviewCount, MIN(tbl_address_rating.rating_value) as worstRating, MAX(tbl_address_rating.rating_value) as bestRating, tbl_address_comments.social_user as reViewer, tbl_address.postal_code as postal_code, tbl_address.province as province, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage, tbl_address_comments.rating_comments AS comments, tbl_address_comments.rating_feeling as feeling, tbl_address_comments.rating_date as ratingDate, IFNULL(tbl_approved.is_approved,'0') as Status, ROUND(AVG(tbl_address_rating.rating_value),1) AS avgRating, (SELECT COUNT(tbl_address_rating.address_comment_id) FROM tbl_address_comments WHERE tbl_address_rating.address_comment_id = tbl_address_comments.id) as ratingCount FROM `euqjdems_killjoy`.`tbl_address` LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_addressindex ON tbl_addressindex.sessionid=tbl_address.sessionid WHERE tbl_addressindex.address LIKE '%$my_data%' GROUP BY tbl_address_comments.sessionid ORDER BY tbl_address_comments.rating_date DESC";
$rs_structured_review = mysql_query($query_rs_structured_review, $killjoy) or die(mysql_error());
$row_rs_structured_review = mysql_fetch_assoc($rs_structured_review);
$totalRows_rs_structured_review = mysql_num_rows($rs_structured_review);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="application/ld+json">
/*structerd data markup compiled by http://www.midnightowl.co.za */
{
  "@context": "http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "item": {
      "@id": "httpw://www.killjoy.co.za/index.php",
      "name": "Home",
      "image": "https://www.killjoy.co.za/images/icons/home-icon.png"
    }
  },{
    "@type": "ListItem",
    "position": 2,
    "item": {
      "@id": "https://www.killjoy.co.za/search.php",
      "name": "Search",
      "image": "https://www.killjoy.co.za/images/icons/search-icon.png"
    }
    }]
}
</script>
<?php do { ?>
 <script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Residence",
  "name": "<?php echo $row_rs_structured_review['strNumber']; ?>, <?php echo $row_rs_structured_review['Street']; ?>, <?php echo $row_rs_structured_review['city']; ?>, <?php echo $row_rs_structured_review['postal_code']; ?>",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "<?php echo $row_rs_structured_review['city']; ?>",
    "addressRegion": "<?php echo $row_rs_structured_review['province']; ?>",
    "postalCode": "<?php echo $row_rs_structured_review['postal_code']; ?>",
    "streetAddress": "<?php echo $row_rs_structured_review['strNumber']; ?>, <?php echo $row_rs_show_review['streetname']; ?>"
  },
    
  
  "review": [
    {
      "@type": "Review",
      "author": "<?php echo $row_rs_structured_review['reViewer']; ?>",
      "datePublished": "<?php echo $row_rs_structured_review['ratingDate']; ?>",
      "description": "<?php echo $row_rs_structured_review['comments']; ?>",
      "name": "<?php echo $row_rs_structured_review['feeling']; ?>",
      "reviewRating": {
        "@type": "Rating",
        "bestRating": "<?php echo $row_rs_structured_review['bestRating']; ?>",
        "ratingValue": "<?php echo round($row_rs_structured_review['avgRating'],0); ?>",
        "worstRating": "<?php echo $row_rs_structured_review['worstRating']; ?>"
      }
    }
  ],
   "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo round($row_rs_structured_review['avgRating'],0); ?>",
    "reviewCount": "<?php echo $row_rs_structured_review['ratingCount']; ?>"
  },
   "photo": "https://www.killjoy.co.za/<?php echo $row_rs_structured_review['propertyImage']; ?>",
   "image": "https://www.killjoy.co.za/<?php echo $row_rs_structured_review['propertyImage']; ?>"
}
</script>
  <?php } while ($row_rs_structured_review = mysql_fetch_assoc($rs_structured_review)); ?>
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
<title>killjoy - Sitelinks Searchbox</title>
<script type="text/javascript" src="fancybox/lib/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
</head>

<body>

  <div id="viewreviews"><?php include'searchresults.php';?></div>

<script type="text/javascript">
$(document).ready(function() {
 $.fancybox({
	 helpers : {
overlay : {
css : {
  'background' : 'rgba(200, 201, 203, 0.40)'
   }
}
},
href: '#viewreviews', 
modal: false,
 'afterClose'  : function() {			   
 location.href ="https://www.killjoy.co.za";		
		 
 },
 
 });
return false;
});
 
</script>
</body>
</html>
<?php
mysql_free_result($rs_structured_review);
?>
