<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/killjoy.php');
$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "gotoadmin.php";
if (!((isset($_SESSION['kj_adminUsername'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['kj_adminUsername'], $_SESSION['kj_usergroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
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

?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rs_social_comments = 10;
$pageNum_rs_social_comments = 0;
if (isset($_GET['pageNum_rs_social_comments'])) {
  $pageNum_rs_social_comments = $_GET['pageNum_rs_social_comments'];
}
$startRow_rs_social_comments = $pageNum_rs_social_comments * $maxRows_rs_social_comments;

mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_social_comments = "SELECT * FROM tbl_review_comments WHERE was_checked = 0";
$query_limit_rs_social_comments = sprintf("%s LIMIT %d, %d", $query_rs_social_comments, $startRow_rs_social_comments, $maxRows_rs_social_comments);
$rs_social_comments = mysqli_query( $killjoy, $query_limit_rs_social_comments) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_social_comments = mysqli_fetch_assoc($rs_social_comments);

if (isset($_GET['totalRows_rs_social_comments'])) {
  $totalRows_rs_social_comments = $_GET['totalRows_rs_social_comments'];
} else {
  $all_rs_social_comments = mysqli_query($GLOBALS["___mysqli_ston"], $query_rs_social_comments);
  $totalRows_rs_social_comments = mysqli_num_rows($all_rs_social_comments);
}
$totalPages_rs_social_comments = ceil($totalRows_rs_social_comments/$maxRows_rs_social_comments)-1;

$queryString_rs_social_comments = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_social_comments") == false && 
        stristr($param, "totalRows_rs_social_comments") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_social_comments = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_social_comments = sprintf("&totalRows_rs_social_comments=%d%s", $totalRows_rs_social_comments, $queryString_rs_social_comments);
$query_rs_social_comments = "SELECT * from tbl_review_comments WHERE was_checked = 0";
$rs_social_comments = mysqli_query( $killjoy, $query_rs_social_comments) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_social_comments = mysqli_fetch_assoc($rs_social_comments);
$totalRows_rs_social_comments = mysqli_num_rows($rs_social_comments);




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
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<meta name="robors" content="noindex,nofollow" />
<link rel="canonical" href="https://www.killjoy.co.za/admin/admin-lounge.php">
<title>Killjoy - moderation tool</title>
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/moderator-page/checks.css" rel="stylesheet" type="text/css" />
<link href="../css/login-page/mailcomplete.css" rel="stylesheet" type="text/css">
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113531379-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113531379-1');
</script>
<link href="css/moderator-page/desktop.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../fancybox/lib/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="../fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
</head>
<body>
<div class="header">Moderation Tool</div>
<div class="maincontainer" id="maincontainer">
<table border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="headers">
    <td>Link</td>
    <td>User</td>
    <td>Comments</td>
    <td>Comment Date</td>
    <td>Approve</td>
  </tr>
  <?php do { $comment_id = $row_rs_social_comments['id']; $string = $row_rs_social_comments['social_comments']; $newstring = preg_replace(
              "~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~",
              "<a href=\"\\0\">\\0</a>", 
              $string); ?>
    <tr>
      <td><a href="moderatoractionpage.php?recordID=<?php echo $row_rs_social_comments['id']; ?>"> <?php echo $row_rs_social_comments['id']; ?>&nbsp; </a></td>
      <td><a href="mailto:<?php echo $row_rs_social_comments['social_user']; ?>"><?php echo $row_rs_social_comments['social_user']; ?></a>&nbsp; </td>
      <td class="commentbox"><?php echo $newstring; ?>&nbsp; </td>
      <td><?php echo $row_rs_social_comments['comment_date']; ?>&nbsp; </td>
      <td><input <?php if (!(strcmp($row_rs_social_comments['is_approved'],1))) {echo "checked=\"checked\"";} ?> name="approve" onClick="update_comments('<?php echo $comment_id; ?>')"  id="approve" type="checkbox" value="<?php echo $row_rs_social_comments['id']; ?>" /></td>
    </tr>
    <?php } while ($row_rs_social_comments = mysqli_fetch_assoc($rs_social_comments)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rs_social_comments > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rs_social_comments=%d%s", $currentPage, 0, $queryString_rs_social_comments); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rs_social_comments > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rs_social_comments=%d%s", $currentPage, max(0, $pageNum_rs_social_comments - 1), $queryString_rs_social_comments); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rs_social_comments < $totalPages_rs_social_comments) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rs_social_comments=%d%s", $currentPage, min($totalPages_rs_social_comments, $pageNum_rs_social_comments + 1), $queryString_rs_social_comments); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rs_social_comments < $totalPages_rs_social_comments) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rs_social_comments=%d%s", $currentPage, $totalPages_rs_social_comments, $queryString_rs_social_comments); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>


Comments <?php echo ($startRow_rs_social_comments + 1) ?> to <?php echo min($startRow_rs_social_comments + $maxRows_rs_social_comments, $totalRows_rs_social_comments) ?> of <?php echo $totalRows_rs_social_comments ?><br />
</div>

<script type="text/javascript">
 function update_comments ( comment_id )  
{ $.ajax( { type    : "POST",
data: {"txt_commentId" : comment_id},
url     : "../functions/moderatecomments.php",
success : function (data)
{ 
    $("#maincontainer").removeClass("maincontainer");
    $("#maincontainer").load(location.href + " #maincontainer");

},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>
</body>
</html>
