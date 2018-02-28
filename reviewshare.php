<?php require_once('Connections/killjoy.php'); ?>
<?php
if (!isset($_SESSION)) {
session_start();
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
$query_rs_show_review = sprintf("SELECT DISTINCT tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, tbl_address.postal_code AS postalCode, province AS province, rating_feeling as feeling, DATE_FORMAT(tbl_address_comments.rating_date, '%%d-%%b-%%y')AS ratingDate, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage, IFNULL(social_users.g_name, 'Anonymous') As socialUser, tbl_address_comments.rating_comments AS comments FROM tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.sessionid = tbl_address.sessionid LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.sessionid = tbl_address.sessionid LEFT JOIN social_users ON social_users.g_email = tbl_address_comments.social_user WHERE tbl_address.sessionid = %s GROUP BY tbl_address.sessionid ORDER BY tbl_address_comments.rating_date DESC", GetSQLValueString($colname_rs_show_review, "text"));
$rs_show_review = mysql_query($query_rs_show_review, $killjoy) or die(mysql_error());
$row_rs_show_review = mysql_fetch_assoc($rs_show_review);
$totalRows_rs_show_review = mysql_num_rows($rs_show_review);
$property_id = $row_rs_show_review['propsession'];

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

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link href="css/view-reviews/profile.css" rel="stylesheet" type="text/css" />
<link href="css/property-reviews/social.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="formcontainer" id="formcontainer">
  <div class="formheader">Killjoy.co.za Property Review</div>
<div class="imagebox" id="imagebox">  
<img src="<?php echo $row_rs_show_review['propertyImage']; ?>" alt="killjoy.co.za rental property review image" class="propertyimage" /> 
<div class="addressfield"><address>
<?php echo $row_rs_show_review['streetnumber']; ?> <?php echo $row_rs_show_review['streetname']; ?><br />
<?php echo $row_rs_show_review['city']; ?><br />
<?php echo $row_rs_show_review['postalCode']; ?>
</address></div>    
  </div>
<div class="fieldlabels" id="fieldlabels2">Reviewer:</div>
  <div class="userbox"><?php echo $row_rs_show_review['socialUser']; ?></div>
    <div class="fieldlabels" id="fieldlabels3">Review Date:</div>
  <div class="userbox"><?php echo $row_rs_show_review['ratingDate']; ?></div>
  <div class="fieldlabels" id="fieldlabels3">Comments:</div>
 <div class="commentbox"><?php echo $row_rs_show_review['comments']; ?></div>
</div>


 

</body>
</html>
