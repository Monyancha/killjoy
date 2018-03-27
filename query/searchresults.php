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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rs_search_results = 10;
$pageNum_rs_search_results = 0;
if (isset($_GET['pageNum_rs_search_results'])) {
  $pageNum_rs_search_results = $_GET['pageNum_rs_search_results'];
}
$startRow_rs_search_results = $pageNum_rs_search_results * $maxRows_rs_search_results;

mysql_select_db($database_killjoy, $killjoy);
$query_rs_search_results = "SELECT DISTINCT (SELECT COUNT(tbl_address_comments.sessionid) FROM tbl_address_comments WHERE tbl_address_comments.sessionid = tbl_address.sessionid) AS reviewCount, city as city, str_number as strNumber, IFNULL(tbl_propertyimages.image_url,'https://www.killjoy.co.za/images/icons/house-outline-bg.png') AS propertyImage, street_name AS Street, tbl_address.sessionid As id, city as Town FROM tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id WHERE tbl_address.street_name LIKE '%$my_data%' OR tbl_address.str_number LIKE '%$my_data%' AND tbl_approved.is_approved = '1' GROUP BY tbl_address.sessionid ORDER BY Street ASC";
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
</head>
<body>
<div class="formcontainer">
<div class="formheader">Showing results for <span class="mydata"><?php echo $my_data ?></span></div>

<?php do { ?>
  <div class="results"><div class="marker"><span class='icon-map-marker'></span></div><img class="image" src="<?php echo $row_rs_search_results['propertyImage']; ?>"  alt="search results image"/><div class="addressfield"><?php echo $row_rs_search_results['strNumber'] ?>, <?php echo $row_rs_search_results['Street'] ?>, <?php echo $row_rs_search_results['city'] ?></div></div>
  <?php } while ($row_rs_search_results = mysql_fetch_assoc($rs_search_results)); ?>
  <table border="0">
    <tr>
      <td><?php if ($pageNum_rs_search_results > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rs_search_results=%d%s", $currentPage, 0, $queryString_rs_search_results); ?>">First</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rs_search_results > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rs_search_results=%d%s", $currentPage, max(0, $pageNum_rs_search_results - 1), $queryString_rs_search_results); ?>">Previous</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rs_search_results < $totalPages_rs_search_results) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rs_search_results=%d%s", $currentPage, min($totalPages_rs_search_results, $pageNum_rs_search_results + 1), $queryString_rs_search_results); ?>">Next</a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_rs_search_results < $totalPages_rs_search_results) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rs_search_results=%d%s", $currentPage, $totalPages_rs_search_results, $queryString_rs_search_results); ?>">Last</a>
          <?php } // Show if not last page ?></td>
    </tr>
  </table>
</div>


</body>
</html>
<?php
mysql_free_result($rs_search_results);
?>
