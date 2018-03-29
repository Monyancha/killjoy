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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rs_search_results = 5;
$pageNum_rs_search_results = 0;
if (isset($_GET['pageNum_rs_search_results'])) {
  $pageNum_rs_search_results = $_GET['pageNum_rs_search_results'];
}
$startRow_rs_search_results = $pageNum_rs_search_results * $maxRows_rs_search_results;

mysql_select_db($database_killjoy, $killjoy);
$query_rs_search_results = "SELECT tbl_address_comments.sessionid as id, tbl_address.str_number as strNumber, tbl_address.street_name AS Street, tbl_address.city as city, (SELECT COUNT(tbl_address_comments.sessionid) FROM tbl_address_comments WHERE tbl_address_comments.sessionid = tbl_address.sessionid AND tbl_approved.is_approved=1) AS reviewCount, IFNULL(tbl_propertyimages.image_url,'https://www.killjoy.co.za/images/icons/house-outline-bg.png') AS propertyImage, IFNULL(tbl_approved.is_approved,'0') as Status, ROUND(AVG(tbl_address_rating.rating_value),1) AS avgRating, (SELECT COUNT(tbl_address_rating.address_comment_id) FROM tbl_address_comments WHERE tbl_address_rating.address_comment_id = tbl_address_comments.id) as ratingCount FROM `euqjdems_killjoy`.`tbl_address` LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id = tbl_address_comments.id WHERE (CONVERT(`str_number` USING utf8) LIKE '%$my_data%' OR CONVERT(`street_name` USING utf8) LIKE '%$my_data%' OR CONVERT(`city` USING utf8) LIKE '%$my_data%' OR CONVERT(`province` USING utf8) LIKE '%$my_data%' OR CONVERT(`postal_code` USING utf8) LIKE '%$my_data%' OR CONVERT(`Country` USING utf8) LIKE '%$my_data%')  AND  (CONVERT(`str_number` USING utf8) LIKE '%$my_data%' OR CONVERT(`street_name` USING utf8) LIKE '%$my_data%' OR CONVERT(`city` USING utf8) LIKE '%$my_data%' OR CONVERT(`province` USING utf8) LIKE '%$my_data%' OR CONVERT(`postal_code` USING utf8) LIKE '%$my_data%' OR CONVERT(`Country` USING utf8) LIKE '%$my_data%')  AND  (CONVERT(`str_number` USING utf8) LIKE '%$my_data%' OR CONVERT(`street_name` USING utf8) LIKE '%$my_data%' OR CONVERT(`city` USING utf8) LIKE '%$my_data%' OR CONVERT(`province` USING utf8) LIKE '%$my_data%' OR CONVERT(`postal_code` USING utf8) LIKE '%$my_data%' OR CONVERT(`Country` USING utf8) LIKE '%$my_data%')  AND  (CONVERT(`str_number` USING utf8) LIKE '%$my_data%' OR CONVERT(`street_name` USING utf8) LIKE '%$my_data%' OR CONVERT(`city` USING utf8) LIKE '%$my_data%' OR CONVERT(`province` USING utf8) LIKE '%$my_data%' OR CONVERT(`postal_code` USING utf8) LIKE '%Dal%')  AND  (CONVERT(`str_number` USING utf8) LIKE '%$my_data%' OR CONVERT(`street_name` USING utf8) LIKE '%$my_data%' OR CONVERT(`city` USING utf8) LIKE '%$my_data%' OR CONVERT(`province` USING utf8) LIKE '%$my_data%' OR CONVERT(`postal_code` USING utf8) LIKE '%$my_data%' OR CONVERT(`Country` USING utf8) LIKE '%$my_data%') GROUP BY tbl_address_comments.sessionid ORDER BY tbl_address_comments.rating_date DESC";
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


$queryString_rs_search_results = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_search_results") == false && 
        stristr($param, "totalRows_rs_search_results") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_search_results = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_search_results = sprintf("&totalRows_rs_search_results=%d%s", $totalRows_rs_search_results, $queryString_rs_search_results);

echo $my_data;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="kj-autocomplete/lib/jQuery-1.4.4.min.js"></script>
<script type="text/javascript" src="kj-autocomplete/jquery.autocomplete.js"></script>
<link href="kj-autocomplete/jquery.quickfindagency.css" rel="stylesheet" type="text/css" />
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
<link href="css/search-results/pagenav.css" rel="stylesheet" type="text/css" />
<link href="css/placeholde.css" rel="stylesheet" type="text/css" />
<strong></strong>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Search killjoy.co.za for rental property reviews and the information tenant's share about their experiences of living at a property. You can search by street or city. Sitelinks Searchbox" />
<meta name="keywords" content="search rental properties, search reviews, find reviews, search street, search city, search town, find properties, quick find, view, reviews, ratings, tenant, experience, share, sitelinks searchbox" />
<script type="text/javascript">
   	$.fn.stars = function() {
    return $(this).each(function() {
        // Get the value
        var val = parseFloat($(this).html());
        // Make sure that the value is in 0 - 5 range, multiply to get width
        var size = Math.max(0, (Math.min(5, val))) * 16;
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
	height: 16px;
	background-image: url(images/stars/blue-white-16x32l.png);
	background-repeat: repeat-x;
	background-position: 0 -16px;
	vertical-align: middle;
	width: 80px;
	margin-right:5px;
	
}

span.stars span {
    background-position: 0 0;
}
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
<div class="searchheader">Killjoy.co.za -Sitelinks Searchbox</div>
<div class="searchcontainer"><form name="search" id="search" action="search.php" method="get"><input name="q" placeholder="enter the street or city name" type="text" class="searchfield" id="q" /></form></div>
<div class="formheader">Showing results for <span class="mydata"><?php echo $my_data ?></span></div>
  <?php do { ?>
    <a class="masterTooltip" <?php if ($row_rs_search_results['reviewCount'] >= 1) { // Show if recordset not empty ?>title="&nbsp;&nbsp;There <?php if($row_rs_search_results['reviewCount'] > 1) { ?>are<?php } ?><?php if($row_rs_search_results['reviewCount'] < 2) { ?>is<?php } ?>  <?php echo $row_rs_search_results['reviewCount'] ?><?php if($row_rs_search_results['reviewCount'] < 2) { ?> review <?php } ?> <?php if($row_rs_search_results['reviewCount'] > 1) { ?>reviews<?php } ?> for <?php echo $row_rs_search_results['strNumber'] ?>, <?php echo $row_rs_search_results['Street'] ?>, <?php echo $row_rs_search_results['city'] ?> "<?php } // Show if recordset not empty ?> href="https://www.killjoy.co.za/viewer.php?tarsus=<?php echo $smith ?>&claw=<?php echo $row_rs_search_results['id'] ?>&alula=<?php echo $captcha ?>"><div class="results"><div class="marker"><span class='icon-map-marker'></span></div><img class="image" src="<?php echo $row_rs_search_results['propertyImage']; ?>"  alt="search results image"/><div class="addressfield"><?php echo $row_rs_search_results['strNumber'] ?>, <?php echo $row_rs_search_results['Street'] ?>, <?php echo $row_rs_search_results['city'] ?></div><?php if($row_rs_search_results['Status'] > 0) { ?><div class="ratingbox"><span class="stars" id="stars"><?php echo $row_rs_search_results['avgRating']; ?></span><span class="ratingsummary">Rating: <?php echo $row_rs_search_results['avgRating']; ?> from <?php echo $row_rs_search_results['ratingCount']; ?> Reviews</span></div><?php }?> <?php if ($row_rs_search_results['Status'] > 0) { // Show if recordset not empty ?><div class="reviewcount">    
  <?php echo $row_rs_search_results['reviewCount'] ?>  
    </div><?php } // Show if recordset not empty ?></div></a>
    <?php } while ($row_rs_search_results = mysql_fetch_assoc($rs_search_results)); ?>
     <?php if ($totalRows_rs_search_results > 1) { // Show if recordset not empty ?>
  <div class="navcontainer" id="navbar"><div class="prevbtn"><?php if ($pageNum_rs_search_results > 0) { // Show if not first page ?>
    <a title="Go to the previous page" class="masterTooltip" href="<?php printf("%s?pageNum_rs_search_results=%d%s", $currentPage, max(0, $pageNum_rs_search_results - 1), $queryString_rs_search_results); ?>"><img src="images/nav/prev-btn.png" /></a>
    <?php } // Show if not first page ?></div><div class="navtext">Showing results <?php echo ($startRow_rs_search_results + 1) ?> to <?php echo min($startRow_rs_search_results + $maxRows_rs_search_results, $totalRows_rs_search_results) ?> of <?php echo $totalRows_rs_search_results ?></div>
    <div class="netxbtn"><?php if ($pageNum_rs_search_results < $totalPages_rs_search_results) { // Show if not last page ?>
      <a title="Go to the next page" class="masterTooltip" href="<?php printf("%s?pageNum_rs_search_results=%d%s", $currentPage, min($totalPages_rs_search_results, $pageNum_rs_search_results + 1), $queryString_rs_search_results); ?>"><img src="images/nav/next-btn.png" /></a>
      <?php } // Show if not last page ?></div></div>
  <?php } // Show if recordset not empty ?>
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

</script>
<script type="text/javascript">
var $s = jQuery.noConflict();
$s(function() {
$s('span.stars').stars();
});
  </script>
  
<script type="text/javascript">
var $j = jQuery.noConflict();
$j(document).ready(function(){
$j("#q").autocomplete("kj-autocomplete/autocompletestreet.php", {
			 minLength: 10, 
			delay: 500,
selectFirst: true
});
 $j("#q").result(function() {
$j("#search").submit();
$j("#q").val('');	 
});
 });
 $(document).ready(function() {
$(window).keydown(function(event){
if(event.keyCode == 13) {
event.preventDefault();
return false;
}
});
});
 
</script>

</body>
</html>
<?php
mysql_free_result($rs_search_results);

mysql_free_result($rs_search_engine);
?>
