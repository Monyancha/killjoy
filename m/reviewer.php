<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/killjoy.php');
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
mysql_select_db($database_killjoy, $killjoy);
$query_rs_check_city = sprintf("SELECT sessionid, str_number, street_name, city FROM tbl_address WHERE str_number = %s AND street_name = %s AND city = %s", GetSQLValueString($colname_rs_check_city, "text"),GetSQLValueString($coltwo_rs_check_city, "text"),GetSQLValueString($colthree_rs_check_city, "text"));
$rs_check_city = mysql_query($query_rs_check_city, $killjoy) or die(mysql_error());
$row_rs_check_city = mysql_fetch_assoc($rs_check_city);
$totalRows_rs_check_city = mysql_num_rows($rs_check_city);
$emptysession = $row_rs_check_city['sessionid'];

if (!$totalRows_rs_check_city) {

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

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
  
   $addressid = mysql_insert_id();
  setcookie("address_id", $addressid, time()+60*60*24*30 ,'/');
  
  $insertGoTo = "reviewersteptwo.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  $_SESSION['kj_propsession'] = $propertysession;
  header(sprintf("Location: %s", $insertGoTo));
}

} else {
	
	$insertGoTo = "reviewersteptwo.php";
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
<link rel="apple-touch-icon" sizes="57x57" href="../favicons/apple-icon-57x57.png" />
<link rel="apple-touch-icon" sizes="60x60" href="../favicons/apple-icon-60x60.png" />
<link rel="apple-touch-icon" sizes="72x72" href="../favicons/apple-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="76x76" href="../favicons/apple-icon-76x76.png" />
<link rel="apple-touch-icon" sizes="114x114" href="../favicons/apple-icon-114x114.png" />
<link rel="apple-touch-icon" sizes="120x120" href="../favicons/apple-icon-120x120.png" />
<link rel="apple-touch-icon" sizes="144x144" href="../favicons/apple-icon-144x144.png" />
<link rel="apple-touch-icon" sizes="152x152" href="../favicons/apple-icon-152x152.png" />
<link rel="apple-touch-icon" sizes="180x180" href="../favicons/apple-icon-180x180.png" />
<link rel="icon" type="image/png" sizes="192x192"  href="../favicons/android-icon-192x192.png" />
<link rel="icon" type="image/png" sizes="32x32" href="../favicons/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="96x96" href="../favicons/favicon-96x96.png" />
<link rel="icon" type="image/png" sizes="16x16" href="../favicons/favicon-16x16.png" />
<link rel="manifest" href="/manifest.json" />
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="canonical" href="https://www.killjoy.co.za/review.php">
<title>killjoy - property review page</title>
<link href="css/property-reviews/desktop.css" rel="stylesheet" type="text/css" />
<link href="css/property-reviews/profile.css" rel="stylesheet" type="text/css" />
<link href="../jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.dialog.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.resizable.min.css" rel="stylesheet" type="text/css">
<script src="../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<script src="../SpryAssets/jquery.ui-1.10.4.dialog.min.js"></script>
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css">
<link href="css/dialog-styling.css" rel="stylesheet" type="text/css">
<body>
<div data-role="page" id="reviewer-page">
    <form target="_parent"  action="reviewer.php" method="POST" name=addressField class="reviewform">
      <div class="stepfields" id="stepone">Search</div>   
    <div class="fieldlabels" id="fieldlabels">Search for the property:</div>
<div class="formfields" id="searchbox"><input required autofocus name="address" class="searchfield" type="text" id="autocomplete" placeholder="find an address" onFocus="geolocate()" size="80" /></div>  
  <div class="stepfields" id="stepone">Or fill in the address</div> 
  <div class="fieldlabels" id="fieldlabels">Enter/Edit the property details, if necessary:</div>
   <div class="fieldlabels" id="fieldlabels">Street/Unit Nr and Name:</div>
   <div class="streetaddress" id="streetaddress"><div class="street-number"><input name="street_number" required class="street-number-field" type="text" id="street_number" /></div><div class="street-name"><input required class="street-name-field" id="route" name="streetname" /></div></div>  
   <div class="fieldlabels" id="fieldlabels">City or Town:</div>
   <div class="formfields" id="citybox"><span id="sprytextfield3">
     <input class="cityname" id="locality" name="citytown" />
     <span class="textfieldRequiredMsg"></span></span>
     </input></div>
    <div class="fieldlabels" id="provbox">Province and Postal code:</div>
    <div class="streetaddress" id="streetaddress"><div class="street-number"><input required class="street-number-field" id="postal_code" name="postal_code" /></div><div class="street-name"><input required class="street-name-field" name="province" id="administrative_area_level_1" /></div></div> 
     <input type="hidden" name="txt_szessionid" id="txt_szessionid" value="<?php echo htmlspecialchars($sessionid) ?>" /> 
    
    <div class="formfields" id="countrybox"><input type="hidden" class="cityname" id="country" name="country" /></div>
    <button class="nextbutton">Next <span class="icon-arrow-circle-right"></span></button> 
    
 <input type="hidden" name="MM_insert" value="addressField">
  <label for="txt_szessionid"></label>
 
  </form>

</div>

<script>
  // This example displays an address form, using the autocomplete feature
  // of the Google Places API to help users fill in the information.

  // This example requires the Places library. Include the libraries=places
  // parameter when you first load the API. For example:
  // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

  var placeSearch, autocomplete;
  var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'long_name',
    country: 'long_name',
    postal_code: 'short_name'
  };

  function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
        {types: ['geocode']});

    autocomplete.setComponentRestrictions(
        {'country': ['za']});

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
  }

  function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

    for (var component in componentForm) {
      document.getElementById(component).value = '';
    }

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        var val = place.address_components[i][componentForm[addressType]];
        document.getElementById(addressType).value = val;
      }
    }
  }

  // Bias the autocomplete object to the user's geographical location,
  // as supplied by the browser's 'navigator.geolocation' object.
  function geolocate() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var geolocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        var circle = new google.maps.Circle({
          center: geolocation,
          radius: position.coords.accuracy
        });
        autocomplete.setBounds(circle.getBounds());
      });
    }
  }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBp0cy7ti0z5MJMAwWiPMNvbJobmWYGyv4&libraries=places&callback=initAutocomplete"
    async defer></script>
<script type="text/javascript">
$(function() {
	$( "#reviewer-page" ).dialog(); 
	$( "#reviewer-page" ).dialog({ title: "Review a Property" });
	
    	});
	
</script>

<script type="text/javascript">
	 var elem = $("#reviewer-page");
	$("#reviewer-page").dialog({ closeText: '' });
 elem.dialog({
       resizable: false,
    title: 'Review a Property',
	     });     // end dialog
 elem.dialog('open');
	$('#reviewer-page').bind('dialogclose', function(event) {
     window.location = "index.php";
 });
	
	</script>

