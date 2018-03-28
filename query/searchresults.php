<?php require_once('../Connections/killjoy.php'); ?>
 <?php
 
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

$maxRows_rs_search_results = 10;
$pageNum_rs_search_results = 0;
if (isset($_GET['pageNum_rs_search_results'])) {
  $pageNum_rs_search_results = $_GET['pageNum_rs_search_results'];
}
$startRow_rs_search_results = $pageNum_rs_search_results * $maxRows_rs_search_results;

mysql_select_db($database_killjoy, $killjoy);
$query_rs_search_results = "SELECT DISTINCT (SELECT COUNT(tbl_address_comments.sessionid) FROM tbl_address_comments WHERE tbl_address_comments.sessionid = tbl_address.sessionid AND tbl_approved.is_approved=1) AS reviewCount, city as city, str_number as strNumber, IFNULL(tbl_propertyimages.image_url,'https://www.killjoy.co.za/images/icons/house-outline-bg.png') AS propertyImage, IFNULL(tbl_approved.is_approved,'0') as Status, street_name AS Street, tbl_address.sessionid As id, ROUND(AVG(tbl_address_rating.rating_value),1) AS avgRating, (SELECT COUNT(tbl_address_rating.address_comment_id) FROM tbl_address_comments WHERE tbl_address_rating.address_comment_id = tbl_address_comments.id) as ratingCount FROM tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id=tbl_address_comments.id WHERE tbl_address.street_name LIKE '%$my_data%' OR tbl_address.str_number LIKE '%$my_data%' OR city like '%$my_data%' GROUP BY tbl_address.sessionid ORDER BY Street ASC";
$query_limit_rs_search_results = sprintf("%s LIMIT %d, %d", $query_rs_search_results, $startRow_rs_search_results, $maxRows_rs_search_results);
$rs_search_results = mysql_query($query_limit_rs_search_results, $killjoy) or die(mysql_error());
$row_rs_search_results = mysql_fetch_assoc($rs_search_results);

if (isset($_GET['totalRows_rs_search_results'])) {
  $totalRows_rs_search_results = $_GET['totalRows_rs_search_results'];
} else {
  $all_rs_search_results = mysql_query($query_rs_search_results);
  $totalRows_rs_search_results = mysql_num_rows($all_rs_search_results);
}
$totalPages_rs_search_results = ceil($totalRows_rs_search_results/$maxRows_rs_search_results)-1;


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
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/tooltips.css" rel="stylesheet" type="text/css">
<link href="css/search-results/profile.css" rel="stylesheet" type="text/css" />
<link href="css/search-results/results.css" rel="stylesheet" type="text/css" />
<strong></strong>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>killjoy.co.za - search engine rich card.</title>
<style type="text/css">
.tooltip:before {
	font-family: icomoon;
	content: "\f015";
	font-size: 28px;
	color: #FFFFFF;
	vertical-align: middle;
	padding-top: 5px;
	padding-bottom: 5px;
}
.tooltip {
	display:none;
	position:absolute;
	border:1px solid #666666;
	background-color:#55B5CE;
	border-radius:5px;
	padding:10px;
	color:#fff;
	font-size:14px;
	z-index: 9999;
	font-family: Tahoma, Geneva, sans-serif;
	}
</style>
</head>
<body>
<div class="formcontainer">
<div class="formheader">Showing results for <span class="mydata"><?php echo $my_data ?></span></div>
  <?php do {  ?>
    <a class="masterTooltip" <?php if ($row_rs_search_results['reviewCount'] >= 1) { // Show if recordset not empty ?>title="&nbsp;&nbsp;There <?php if($row_rs_search_results['reviewCount'] > 1) { ?>are<?php } ?><?php if($row_rs_search_results['reviewCount'] < 2) { ?>is<?php } ?>  <?php echo $row_rs_search_results['reviewCount'] ?><?php if($row_rs_search_results['reviewCount'] < 2) { ?> review <?php } ?> <?php if($row_rs_search_results['reviewCount'] > 1) { ?>reviews<?php } ?> for <?php echo $row_rs_search_results['strNumber'] ?>, <?php echo $row_rs_search_results['Street'] ?>, <?php echo $row_rs_search_results['city'] ?> "<?php } // Show if recordset not empty ?> href="https://www.killjoy.co.za/viewer.php?tarsus=<?php echo $smith ?>&claw=<?php echo $row_rs_search_results['id'] ?>&alula=<?php echo $captcha ?>"><div class="results"><div class="marker"><span class='icon-map-marker'></span></div><img class="image" src="<?php echo $row_rs_search_results['propertyImage']; ?>"  alt="search results image"/><div class="addressfield"><?php echo $row_rs_search_results['strNumber'] ?>, <?php echo $row_rs_search_results['Street'] ?>, <?php echo $row_rs_search_results['city'] ?></div> <?php if ($row_rs_search_results['Status'] > 0) { // Show if recordset not empty ?><div class="reviewcount">    
  <?php echo $row_rs_search_results['reviewCount'] ?>  
    </div><?php } // Show if recordset not empty ?></div></a>
    <?php } while ($row_rs_search_results = mysql_fetch_assoc($rs_search_results)); ?>
</div>

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
mysql_free_result($rs_search_results);
?>
