<?php require_once('../Connections/stomer.php'); ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/stomer.php');
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

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
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
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

mysqli_select_db( $stomer, $database_stomer);
$query_rs_categories = "SELECT * FROM st_categories ORDER BY st_categoryName ASC";
$rs_categories = mysqli_query( $stomer, $query_rs_categories) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_categories = mysqli_fetch_assoc($rs_categories);
$totalRows_rs_categories = mysqli_num_rows($rs_categories);

mysqli_select_db( $stomer, $database_stomer);
$query_rs_tags = "SELECT * FROM st_tags ORDER BY st_tagName ASC";
$rs_tags = mysqli_query( $stomer, $query_rs_tags) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_tags = mysqli_fetch_assoc($rs_tags);
$totalRows_rs_tags = mysqli_num_rows($rs_tags);

$colname_show_error = "-1";
if (isset($_SESSION['sessionid'])) {
  $colname_show_error = $_SESSION['sessionid'];
}
mysqli_select_db( $stomer, $database_stomer);
$query_show_error = sprintf("SELECT * FROM tbl_uploaderror WHERE sessionid = %s", GetSQLValueString($colname_show_error, "text"));
$show_error = mysqli_query( $stomer, $query_show_error) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_show_error = mysqli_fetch_assoc($show_error);
$totalRows_show_error = mysqli_num_rows($show_error);

$maxRows_image_path = 8;
$pageNum_image_path = 0;
if (isset($_GET['pageNum_image_path'])) {
  $pageNum_image_path = $_GET['pageNum_image_path'];
}
$startRow_image_path = $pageNum_image_path * $maxRows_image_path;

$colname_image_path = "-1";
if (isset($_SESSION['sessionid'])) {
  $colname_image_path = $_SESSION['sessionid'];
}
mysqli_select_db( $stomer, $database_stomer);
$query_image_path = sprintf("SELECT * FROM tbl_eventimages WHERE sessionid = %s ORDER BY upload_time ASC", GetSQLValueString($colname_image_path, "text"));
$query_limit_image_path = sprintf("%s LIMIT %d, %d", $query_image_path, $startRow_image_path, $maxRows_image_path);
$image_path = mysqli_query( $stomer, $query_limit_image_path) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_image_path = mysqli_fetch_assoc($image_path);

if (isset($_GET['totalRows_image_path'])) {
  $totalRows_image_path = $_GET['totalRows_image_path'];
} else {
  $all_image_path = mysqli_query($GLOBALS["___mysqli_ston"], $query_image_path);
  $totalRows_image_path = mysqli_num_rows($all_image_path);
}
$totalPages_image_path = ceil($totalRows_image_path/$maxRows_image_path)-1;
if (mysqli_num_rows($image_path)==0) {
	$emptymessage = "There are no more images, click add photos to upload images";
if ((isset($_SESSION['sessionid'])) && ($_SESSION['sessionid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $deleteSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
  $insertSQL = sprintf("INSERT INTO tbl_uploaderror(sessionid, error_message) VALUES (%s, %s)",
                 GetSQLValueString($_SESSION['sessionid'], "text"),
GetSQLValueString($emptymessage , "text"));	

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));	   
}
}

$colname_featured_image = "-1";
if (isset($_SESSION['sessionid'])) {
  $colname_featured_image = $_SESSION['sessionid'];
}
mysqli_select_db( $stomer, $database_stomer);
$query_featured_image = sprintf("SELECT * FROM tbl_eventimages WHERE sessionid = %s AND featured_image = 1", GetSQLValueString($colname_featured_image, "text"));
$featured_image = mysqli_query( $stomer, $query_featured_image) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_featured_image = mysqli_fetch_assoc($featured_image);
$totalRows_featured_image = mysqli_num_rows($featured_image);
$image_id = $row_image_path['image_id'];

function generateRandomString($length = 10) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}
$sessionid = generateRandomString();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "events")) {
	
$postdate = mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $_POST['eventDate']);
$availabledate = date('Y-m-d', strtotime(str_replace('-', '-', $postdate)));
  $updateSQL = sprintf("UPDATE st_events SET category_id=%s, tagId=%s, st_eventTitle=%s, st_evenDesc=%s, st_userName=%s, st_evenDate=%s WHERE sessionid=%s",
                       GetSQLValueString($_POST['cateGory'], "int"),
                       GetSQLValueString($_POST['tags'], "int"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"),
                       GetSQLValueString($availabledate, "date"),
                       GetSQLValueString($_POST['txt_sesseyed'], "text"));

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
  $colname_last_id = "-1";
if (isset($_SESSION['sessionid'])) {
  $colname_last_id = $_SESSION['sessionid'];
}
mysqli_select_db( $stomer, $database_stomer);
$query_last_id = sprintf("SELECT MAX(st_eventid) as eventid FROM st_events WHERE sessionid = %s", GetSQLValueString($colname_last_id, "text"));
$last_id = mysqli_query( $stomer, $query_last_id) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_last_id = mysqli_fetch_assoc($last_id);
$totalRows_last_id = mysqli_num_rows($last_id);
$lastid = $row_last_id['eventid'];
 
 if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "events")) {
  $updateSQL = sprintf("UPDATE tbl_eventimages SET st_eventid=%s WHERE sessionid=%s",
                       GetSQLValueString($lastid, "int"),
                       GetSQLValueString($_POST['txt_sesseyed'], "text"));

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $updateSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
}


 
 

  $updateGoTo = "../guestbook.php";
  
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
   $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $deleteSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
     $_SESSION['sessionid'] = NULL;
  unset($_SESSION['sessionid']);
 
  
  header(sprintf("Location: %s", $updateGoTo));
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script type="application/ld+json">
/*structerd data markup compiled by http://www.midnightowl.co.za */
{
  "@context": "http://schema.org",
  "@type": "GardenStore",
   "telephone": "021 868 3641",
        "image": [
    "http://www.stomer.co.za/images/1x1/nursery.jpg",
    "http://www.stomer.co.za/images/4x3/nursery.jpg",
    "http://www.stomer.co.za/images/16x9/nursery.jpg"
   ],
      "name": "St. Omer Nursery",
  "url": "http://www.stomer.co.za",
  "logo": "http://www.stomer.co.za/images/stomerlogo.jpg",
   "address": {
      "@type": "PostalAddress",
      "addressLocality": "Paarl",
      "addressRegion": "WC",
      "postalCode": "7646",
      "streetAddress": "Swawelstert Road, Dal Josaphat, Paarl"
	 
  },
   "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday"
          ],
          "opens": "08:00",
          "closes": "17:30"
        },
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": "Saturday",
          "opens": "08:00",
          "closes": "16:00"
        }
  
      ],
  "priceRange" : "Between R50 and R250",
   "sameAs": [
    "https://www.facebook.com/stomerfarm/?rf=1448086842129016"
    ],
	 "department": [
    {
      "@type": "Restaurant",
      "image": [
        "http://www.stomer.co.za/images/1x1/restaurant.jpg",
    "http://www.stomer.co.za/images/4x3/restaurant.jpg",
    "http://www.stomer.co.za/images/16x9/restaurant.jpg"
   ],
      "name": "The Garden Room Coffee Shop",	  
      "telephone": "021 868 3641",
	  "hasMenu": [
  {
   "@type": "Menu",
   "name": "Breakfast",
   "url": "http://www.stomer.co.za/menus/breakfast-menu/breakfast-menu.html"
  },
  {
   "@type": "Menu",
   "name": "Lunch",
   "url": "http://www.stomer.co.za/menus/lunch-menu/lunch-menu.html"
  }
  ],
	  "servesCuisine": [
       "South African",
	   "Pizza",
        "Kids Meals",
		"Tea Rooom",
		"Coffee Shop",
		"Light Meals",
		"Croissants",
		"Farm Style Breakfast",
		"Affordable Meals"
    ],
	  "priceRange" : "Between R50 and R250",
	  "address": {
    "@type": "PostalAddress",
      "addressLocality": "Paarl",
      "addressRegion": "WC",
      "postalCode": "7646",
      "streetAddress": "Swawelstert Road, Dal Josaphat, Paarl"
	 
  },
  
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "Monday",
            "Tuesday",
            "Wednesday"
                      ],
          "opens": "08:30",
          "closes": "17:00"
        },
		    {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": "Thursday",
          "opens": "08:00",
          "closes": "17:00"
        },
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
		  "Friday",
		  "Saturday"
		  ],
          "opens": "08:00",
          "closes": "16:00"
        }
  
      ]
	}
    ]
}
</script>
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "item": {
      "@id": "http://www.stomer.co.za/index.html",
      "name": "Home",
      "image": "http://www.stomer.co.za/images/crumb-icons/home-icon.png"
    }
  }]
}
</script>
<META NAME="robots" CONTENT="noindex,nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za" />
<title>st omer nursery - wholesale herb growers - buy plants - water wise - green - coffee shop - functions - kiddies parties - paarl- western cape - south africa</title>
<meta name="description" content="St Omer nursery is a wholesale herb and plant nursery situtated in Paarl, Western Cape that offer many attractions, including a coffes shop. Buy plants from us." />
<meta name="keywords" content="buy plants, nursery, shade plants, vegetable plants, herbal plants, local nurseries, sun plants, wholesale,  growers, flowers, functions, kids, parties, venue, coffee, shop, restaurant, events, functions, birthdays, high, tea, kitchen, braai, facilities, outdoors, catering, cake, meals, pizza" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="css/menu.css" rel="stylesheet" type="text/css" />
<link href="css/links.css" rel="stylesheet" type="text/css" />
<link href="css/headers.css" rel="stylesheet" type="text/css" />
<link href="css/thumbsgallery.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../fancybox/lib/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="../fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
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
<link rel="manifest" href="favicons/manifest.json" />
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<link href="css/breadcrumbs.css" rel="stylesheet" type="text/css" />
<link href="../css/contact-us/contactform.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>.
<script type="text/javascript">
$( function() {  
$( "#eventDate" ).datepicker({  dateFormat: "dd-mm-yy"}); } );
</script>
<link href="../css/select.css" rel="stylesheet" type="text/css">
<link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="css/close.css" rel="stylesheet" type="text/css" />
<link href="css/uploadstatus.css" rel="stylesheet" type="text/css" />
<link href="css/checkbutton.css" rel="stylesheet" type="text/css" />
<link href="css/checkradio.css" rel="stylesheet" type="text/css" />
</head>
<body onLoad="set_session()">
<div class="headermedia" id="headermedia"><i onClick="window.open('https://www.facebook.com/stomerfarm/?rf=1448086842129016', '_blank')" style="cursor:pointer; padding-right:10px" class="fa fa-facebook" aria-hidden="true"></i><i onClick="window.open('https://www.google.co.za/maps/place/St+Omer+Farm/@-33.7055694,19.0201469,15z/data=!4m5!3m4!1s0x0:0x332b296238126841!8m2!3d-33.7055694!4d19.0201469', '_blank')" style="cursor:pointer; padding-right:10px" class="fa fa-map-marker" aria-hidden="true"></i><a id="inline" href="#phone"><span style="color:#FFFFFF; padding-right:10px" class="fa fa-phone"></span></a><a id="inline" href="#email"><span style="color:#FFFFFF; padding-right:10px" class="fa fa-envelope"></span></a></div>
<div class="logobanner" id="logobanner"></div>
<div class="crumbswrapper" id="crumbswrapper">
<ul id="crumbs">
  <li>
<a title="go to the home page" href="http://www.stomer.co.za/index.html">Home</a>
</li>

</ul>
</div>
<div class="mainbanner" id="mainbanner"></div>
<div class="menubarcontainer" id="menubarcontainer"><nav>
<label for="show-menu" class="show-menu"><img src="images/phone/menusmall.gif" alt="st omer farm" width="33" height="39" /></label>
<input type="checkbox" id="show-menu" role="button" />
<ul id="menu">
  <li>
<a title="go to the home page" href="#">Home</a>
<ul class="hidden">
 </ul>
</li>
   <li>
<a title="view the coffee shop" href="#">Coffee Shop &#x25BE;</a>
<ul class="hidden">
<li><a href="coffeshop.html" title="the most child friendly coffee shop">The Garden Room</a></li>
  <li><a href="menus/breakfast-menu/breakfast-menu.html" title="Bring the kids and enjoy a hearty farm style breakfast">Breakfast Menu</a></li>
  <li><a href="menus/lunch-menu/lunch-menu.html" title="Bring the kids and enjoy a hearty farm style lunch">Lunch Menu</a></li>
  <li><a href="find-a-estate-agenency.php?accesscheck=index.php" title="find an estate agent on Rent-a-Guide">Baby Shower</a></li>  
   <li><a href="find-a-estate-agenency.php?accesscheck=index.php" title="come enjoy your year end function with us">Year End</a></li>                   
</ul>
</li>
<li>
<a title="we cater for a whole range of functions" href="#">Special Occasions &#x25BE;</a>
<ul class="hidden">
  <li><a href="kiddies-parties.html" title="we cater for all ages kiddies parties">Kiddies Parties</a></li>
  <li><a href="find-a-estate-agenency.php?accesscheck=index.php" title="find an estate agent on Rent-a-Guide">Kitchen Tea</a></li>
  <li><a href="find-a-estate-agenency.php?accesscheck=index.php" title="find an estate agent on Rent-a-Guide">Baby Shower</a></li>  
   <li><a href="find-a-estate-agenency.php?accesscheck=index.php" title="come enjoy your year end function with us">Year End</a></li>                   
</ul>
</li>
<li><a href="info-centre/index.html" title="View proerpty rental and review guidelines, amenities and policies" target="_new">Events</a> </li>
<li><a href="info-centre/contact-us.php" title="contus us, view the directions to the farm" target="_new">Contact</a></li>
</ul>
</nav>
</div>
<div class="headingcontainer" id="headingcontainer">
<h1>St. Omer Farm - Create new events</h1></div>
<div class="welcomebox" id="welcomebox">
<form id="events" name="events" method="POST" action="<?php echo $editFormAction; ?>">
<table border="0" cellpadding="0" cellspacing="0" class="contacttbl">
  <tr>
    <td><h2 class="inputs">Add a new event for a Special Occasion</h2></td>
  </tr>
  <tr>
    <td class="labels">Event Date:</td>
  </tr>
  <tr>
    <td><input name="eventDate" type="text" class="datepicker" id="eventDate" /></td>
  </tr>
  <tr>
    <td class="labels">Cetegory:</td>
  </tr>
  <tr>
    <td class="inputs">
      <select name="cateGory" id="cateGory">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_categories['st_categoryId']?>"><?php echo $row_rs_categories['st_categoryName']?></option>
        <?php
} while ($row_rs_categories = mysqli_fetch_assoc($rs_categories));
  $rows = mysqli_num_rows($rs_categories);
  if($rows > 0) {
      mysqli_data_seek($rs_categories,  0);
	  $row_rs_categories = mysqli_fetch_assoc($rs_categories);
  }
?>
      </select>
    </td>
  </tr>
  <tr>
    <td class="labels">Tag:</td>
  </tr>
  <tr>
    <td class="inputs">
      <select name="tags" id="tags">
        <?php
do {  
?>
        <option value="<?php echo $row_rs_tags['st_tagId']?>"><?php echo $row_rs_tags['st_tagName']?></option>
        <?php
} while ($row_rs_tags = mysqli_fetch_assoc($rs_tags));
  $rows = mysqli_num_rows($rs_tags);
  if($rows > 0) {
      mysqli_data_seek($rs_tags,  0);
	  $row_rs_tags = mysqli_fetch_assoc($rs_tags);
  }
?>
      </select>
   </td>
  </tr>
  <tr>
    <td class="labels">Title:</td>
  </tr>
  <tr>
    <td class="inputs">
      <input name="title" type="text" class="titleevent" id="title">
   </td>
  </tr>
  <tr>
    <td class="labels">Details:</td>
  </tr>
  <tr>
    <td class="inputs"><textarea name="description" cols="45" rows="5" class="comments" id="description"></textarea>
    </td>
  </tr>
  <tr>
    <td><span class="listingtblcentre">
      <input name="txt_sesseyed" type="hidden" id="txt_sesseyed" value="<?php echo $sessionid ;?>" />
    </span></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div id="featuredset"><?php if ($totalRows_featured_image > 0) { // Show if recordset not empty ?><img src="../<?php echo $row_featured_image['image_url']; ?>" alt="property rental review featured photo" class="featuredimg" /><?php } // Show if recordset not empty ?> </div></td>
  </tr>
  <tr>
    <td id="listingphotos"><?php do { $image_id = $row_image_path['image_id'];?>
<div id="wrapper" class="wrapper"><?php if ($totalRows_image_path > 0) { // Show if recordset empty ?><span onClick="unlink_thumb('<?php echo $image_id;?>')" class="close"></span><input onClick="set_featured('<?php echo $image_id;?>')"  type="radio" name="chk_featured" id="chk_featured" /><img src="../<?php echo $row_image_path['image_url']; ?>" width="29" class="editeventimage" /><?php } ?>
</div> <?php } while ($row_image_path = mysqli_fetch_assoc($image_path)); ?>
 
<?php if($totalRows_image_path > 0) { ?>
<div id="instructions" class="instructions">Click on the <img src="images/checkmarksmall.png" width="15" height="16" alt="upload rental property images for a rentap property advertisement" /> checkbox to set as the featured image<br />
Click on the <span class="delimage">&#10008;</span> to delete the image
</div>
<?php } ?><div id="uploader" class="uploader"><img src="images/loading24x24.gif" width="24" height="24" alt="property rental images upload progress indicator" class="indicator" />Uploading</div></td>
  </tr>
  <tr>
    <td class="imgmsgbox"></td>
  </tr>
  <tr>
    <td class="inputs"><div class="logoloader" id="logoloader">
<label for="files" class="files">
<i style="padding-left:5px; padding-right:10px; padding-top:5px; padding-bottom:5px;" class="fa fa-cloud-upload"></i>Add Photos</label>
<input onChange="return acceptimage()" id="files" name="files[]" type="file" multiple/></div></td>
  </tr>
  <tr>
    <td class="labels"><div class="logoloaderrors" id="logoloaderror"><?php if ($totalRows_show_error > 0) { // Show if recordset empty ?><ol>
<?php do { ?><li><?php echo $row_show_error['error_message']; ?><?php } while ($row_show_error = mysqli_fetch_assoc($show_error)); ?></li>
</ol>
<?php } ?>
</div></td>
  </tr>
  <tr>
    <td class="inputs"><input name="submit" type="submit" class="submit" id="submit" value="Submit" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="MM_update" value="events" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>


<br />



<div style="display:none"><div id="phone" class="phone"><i class="fa fa-phone" aria-hidden="true"></i>&nbsp;&nbsp;021 868 3641</div></div>

<div style="display:none"><div id="email" class="email"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;&nbsp;<a href="mailto:jthom@stomer.co.za">jthom@stomer.co.za</a></div></div>

    <div class="footer">&copy; St. Omer Farm - 2017&#8482; All Right Reserved<div class="designer">Designed and maintaineed by <a href="http://www.midnightowl.co.za" title="web design and marketing" target="_new">Midnight Owl</a><img src="../images/desktop/midnight-owl.gif" alt="wholesale herb growers and plant nursery" class="footerimg" /></div></div>

<script type="text/javascript">
$(document).ready(function() {
/* This is basic - uses default settings */

$("a#single_image").fancybox();
/* Using custom settings */

$("a#inline").fancybox({
helpers : {
overlay : {
css : {
  'background' : 'rgba(0, 0, 255, 0.40)'
   }
}
},
'opacity' : 0.4,
'width' :  200,
'height' : 20,
'autoSize' : false,		

'hideOnContentClick': true	});





/* Apply fancybox to multiple items */

$("a.grouped_elements").fancybox({
'transitionIn'	:	'elastic',
'transitionOut'	:	'elastic',
'speedIn'		:	600, 
'speedOut'		:	200, 
'overlayShow'	:	false
});
$("a.grouped_plants").fancybox({
'transitionIn'	:	'elastic',
'transitionOut'	:	'elastic',
'speedIn'		:	600, 
'speedOut'		:	200, 
'overlayShow'	:	false
});

});
</script>

<script type="text/javascript">
$(document).ready(function(){
$(".lnav").click(function () { 
var leftPos = $('.scroller').scrollLeft();
$(".scroller").animate({scrollLeft: leftPos - 120}, 800);
});
$(".rnav").click(function () { 
var leftPos = $('.scroller').scrollLeft();
$(".scroller").animate({scrollLeft: leftPos + 120}, 800);
});
});
</script>

<script type="text/javascript">
$(document).ready(function(){
$(".leftnav").click(function () { 
var leftPos = $('.imgscroller').scrollLeft();
$(".imgscroller").animate({scrollLeft: leftPos - 120}, 800);
});
$(".rightnav").click(function () { 
var leftPos = $('.imgscroller').scrollLeft();
$(".imgscroller").animate({scrollLeft: leftPos + 120}, 800);
});
});
</script>

<script type="text/javascript">
 function set_session ( txt_sesseyed ) 
{ $.ajax( { type    : "POST",
data    : { "txt_sesseyed" : $("#txt_sesseyed").val()}, 
url     : "../functions/set_session.php",
success : function (data)
{ 
  
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>
<script type="text/javascript">
 function acceptimage() {
var data = new FormData();
jQuery.each(jQuery('#files')[0].files, function(i, file) {
data.append('file-'+i, file);
data.append('txt_sesseyed', $("#txt_sesseyed").val());
 }); 
$.ajax({
url: '../functions/eventsphotoupload.php',
data: data, 	
enctype: 'multipart/form-data', 
cache: false,
contentType: false,
processData: false,
type: 'POST',
 beforeSend: function(){
$('.uploader').show();
},
complete: function(){
$('.uploader').hide(); // Handle the complete event
},
success : function (data)
{ 
  $('#logoloaderror').load(document.URL +  ' #logoloaderror');       
  $('#listingphotos').load(document.URL +  ' #listingphotos');
  $('#logoloader').load(document.URL +  ' #logoloader');
  $('#suggestionbox').load(document.URL +  ' #suggestionbox	');	
  
			  
			
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
return false();	
}
</script>
<script type="text/javascript">
 function set_featured ( image_id ) 
{ $.ajax( { type    : "POST",
async   : false,
data    : { "image_id" : image_id  }, 
url     : "../functions/setfeaturedeventimage.php",
success : function ( image_id )
{ 
$('#featuredset').load(document.URL +  ' #featuredset');
$('#featuredmsg').load(document.URL +  ' #featuredmsg');
$('#logoloaderror').load(document.URL +  ' #logoloaderror');
					 
 
						   
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>
<script type="text/javascript">
function unlink_thumb ( image_id ) 
{ $.ajax( { type    : "POST",
async   : false,
data    : { "image_id" : image_id }, 
url     : "../functions/unlinkeventpreviewimage.php",
success : function ( user_id )
{ $('#listingphotos').load(document.URL +  ' #listingphotos');
  $('#suggestionbox').load(document.URL +  ' #suggestionbox');
  $('#logoloaderror').load(document.URL +  ' #logoloaderror');
  $('#logoloader').load(document.URL +  ' #logoloader');
  $('#featuredset').load(document.URL +  ' #featuredset');

						   
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
<?php
((mysqli_free_result($rs_categories) || (is_object($rs_categories) && (get_class($rs_categories) == "mysqli_result"))) ? true : false);
?>
