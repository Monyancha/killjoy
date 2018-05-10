<?php require_once('Connections/killjoy.php'); ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
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
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_show_review = sprintf("SELECT DISTINCT tbl_address_comments.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, tbl_address.postal_code AS postalCode, province AS province, rating_feeling as feeling, tbl_address_comments.rating_date AS ratingDate, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage, IF(social_users.anonymous='0',social_users.g_name,'Anonymous') As socialUser, tbl_address_comments.rating_comments AS comments FROM tbl_address_comments LEFT JOIN tbl_address ON tbl_address.sessionid = tbl_address_comments.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address_comments.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id LEFT JOIN social_users ON social_users.g_email = tbl_address_comments.social_user WHERE tbl_address_comments.sessionid = %s AND tbl_approved.is_approved='1' ORDER BY tbl_address_comments.rating_date DESC", GetSQLValueString($colname_rs_show_review, "text"));
$query_limit_rs_show_review = sprintf("%s LIMIT %d, %d", $query_rs_show_review, $startRow_rs_show_review, $maxRows_rs_show_review);
$rs_show_review = mysqli_query( $killjoy, $query_limit_rs_show_review) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_show_review = mysqli_fetch_assoc($rs_show_review);

if (isset($_GET['totalRows_rs_show_review'])) {
  $totalRows_rs_show_review = $_GET['totalRows_rs_show_review'];
} else {
  $all_rs_show_review = mysqli_query($GLOBALS["___mysqli_ston"], $query_rs_show_review);
  $totalRows_rs_show_review = mysqli_num_rows($all_rs_show_review);
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

$thispage = "<a href='http://www.killjoy.co.za'>killjoy.co.za</a>";    
$url = utf8_encode($thispage);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="fancybox/libs/jquery-3.3.1.min.js" ></script>
<link rel="stylesheet" href="fancybox/dist/jquery.fancybox.min.css" />
<script src="fancybox/dist/jquery.fancybox.min.js"></script>
 </script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113531379-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113531379-1');
</script>

</head>

<body>

<div id="viewreviews"><?php include'viewreviews.php';?></div>

<script type="text/javascript">
  $.fancybox.open({
    src  : '#viewreviews',
    type : 'inline',
    opts : {
        afterClose : function( instance, current ) {
            location.href='index.php';
        }
    }
});
</script>
</body>
</html>