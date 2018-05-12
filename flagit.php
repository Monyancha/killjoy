<?php require_once('Connections/killjoy.php'); ?>
<?php

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

if (isset($_SESSION['kj_username'])) {
	
	$social_user = $_SESSION['kj_username'];
	
} else {
	
	$social_user = "Anonymous";
}

$colname_rs_check_city = "-1";
if (isset($_POST['street_number'])) {
  $colname_rs_check_city = $_POST['street_number'];
}
$coltwo_rs_check_city = "-1";
if (isset($_POST['streetname'])) {
  $coltwo_rs_check_city = $_POST['streetname'];
}
$colthree_rs_check_city = "-1";
if (isset($_POST['citytown'])) {
  $colthree_rs_check_city = $_POST['citytown'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_check_city = sprintf("SELECT sessionid, str_number, street_name, city FROM tbl_address WHERE str_number = %s AND street_name = %s AND city = %s", GetSQLValueString($colname_rs_check_city, "text"),GetSQLValueString($coltwo_rs_check_city, "text"),GetSQLValueString($colthree_rs_check_city, "text"));
$rs_check_city = mysqli_query( $killjoy, $query_rs_check_city) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_check_city = mysqli_fetch_assoc($rs_check_city);
$totalRows_rs_check_city = mysqli_num_rows($rs_check_city);
$emptysession = $row_rs_check_city['sessionid'];

if (!$totalRows_rs_check_city) {

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addressField")) {
	$propertysession = $_POST['txt_szessionid'];
	 $insertSQL = sprintf("INSERT INTO tbl_address (social_user, sessionid, str_number, street_name, city, province, postal_code, Country) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
	                   GetSQLValueString($social_user, "text"),
                       GetSQLValueString($_POST['txt_szessionid'], "text"),
                       GetSQLValueString($_POST['street_number'], "text"),
                       GetSQLValueString($_POST['streetname'], "text"),
                       GetSQLValueString($_POST['citytown'], "text"),
                       GetSQLValueString($_POST['province'], "text"),
                       GetSQLValueString($_POST['postal_code'], "text"),
                       GetSQLValueString($_POST['country'], "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
   $addressid = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
  setcookie("address_id", $addressid, time()+60*60*24*30 ,'/');
  
  $insertGoTo = "reviewsteptwo.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  $_SESSION['kj_propsession'] = $propertysession;
  header(sprintf("Location: %s", $insertGoTo));
}

} else {
	
	$insertGoTo = "reviewsteptwo.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
	
$_SESSION['kj_propsession'] = $emptysession ;
 header(sprintf("Location: %s", $insertGoTo));	
}

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
<link rel="canonical" href="https://www.killjoy.co.za/review.php">
<title>killjoy - property review page</title>
<link href="css/review-flagger/desktop.css" rel="stylesheet" type="text/css" />
<link href="css/review-flagger/profile.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="jquery-validation/demo/css/screen.css">
<script src="jquery-validation/lib/jquery.js"></script>
<script src="jquery-validation/dist/jquery.validate.js"></script>
<body>
<div id="locationField" class="reviewcontainer">
    <form id="flagform"  action="<?php echo $editFormAction; ?>" method="POST" name=addressField class="reviewform">
    <div class="formheader">Flag a Review</div>
     <div class="stepfields" id="stepone">Something about you</div>   
    <div class="fieldlabels" id="fieldlabels">Your email address:</div>
<div class="formfields" id="searchbox"><input autofocus name="address" class="searchfield" type="email" data-type="search" id="autocomplete" size="80" /></div>  
  <div class="stepfields" id="stepone">Or</li></ol></div> 
  <div class="fieldlabels" id="fieldlabels">Enter/Edit the property details, if necessary:</div>
  <div class="fieldlabels" id="fieldlabels">City or Town:</div>
  <div class="formfields" id="countrybox"><span id="sprytextfield6">
       <input name="country" class="countryname" id="country" value="South Africa" />
       <span class="textfieldRequiredMsg"></span></span>
       
      </input></div><button class="nextbutton">Next <span class="icon-arrow-circle-right"></span></button>
     
 
 <input type="hidden" name="MM_insert" value="addressField">
  <label for="txt_szessionid"></label>
  <input type="hidden" name="txt_szessionid" id="txt_szessionid" value="<?php echo htmlspecialchars($sessionid) ?>" />
  </form>
</div>
<script>
$(document).ready(function () {

    $('#flagform').validate({ // initialize the plugin
        rules: {
            address: {
                required: true,
                email: true
            },
            field2: {
                required: true,
                minlength: 5
            }
        },
        submitHandler: function (form) { // for demo
            alert('valid form submitted'); // for demo
            return false; // for demo
        }
    });

});
</script>


