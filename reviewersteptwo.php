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

$colname_rs_showproperty = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_showproperty = $_SESSION['kj_propsession'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_showproperty = sprintf("SELECT * FROM tbl_address WHERE sessionid = %s", GetSQLValueString($colname_rs_showproperty, "text"));
$rs_showproperty = mysql_query($query_rs_showproperty, $killjoy) or die(mysql_error());
$row_rs_showproperty = mysql_fetch_assoc($rs_showproperty);
$totalRows_rs_showproperty = mysql_num_rows($rs_showproperty);

$colname_rs_property_image = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_property_image = $_SESSION['kj_propsession'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_property_image = sprintf("SELECT image_url,  image_id FROM tbl_propertyimages WHERE sessionid = %s", GetSQLValueString($colname_rs_property_image, "text"));
$rs_property_image = mysql_query($query_rs_property_image, $killjoy) or die(mysql_error());
$row_rs_property_image = mysql_fetch_assoc($rs_property_image);
$totalRows_rs_property_image = mysql_num_rows($rs_property_image);

$colname_show_error = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_show_error = $_SESSION['kj_propsession'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_show_error = sprintf("SELECT * FROM tbl_uploaderror WHERE sessionid = %s", GetSQLValueString($colname_show_error, "text"));
$show_error = mysql_query($query_show_error, $killjoy) or die(mysql_error());
$row_show_error = mysql_fetch_assoc($show_error);
$totalRows_show_error = mysql_num_rows($show_error);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="canonical" href="https://www.killjoy.co.za/review.php">
<title>killjoy - property review page</title>
<link href="css/property-reviews/desktop.css" rel="stylesheet" type="text/css" />
<link href="css/property-reviews/profile.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/close.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/fileupload.css" rel="stylesheet" type="text/css" />
<body>

<div id="locationField" class="reviewcontainer">
    <form  action="<?php echo $editFormAction; ?>" method="POST" name=addressField class="reviewform">    
    <div class="formheader">Review a Rental Property</div>
    <div class="addressfield"><?php echo $row_rs_showproperty['str_number']; ?>&nbsp;<?php echo $row_rs_showproperty['street_name']; ?>&nbsp;<?php echo $row_rs_showproperty['city']; ?></div>
     <div class="stepfields" id="stepone"><ol type="1" start="3"><li>Add photo</li></ol></div>   
    <div class="fieldlabels" id="fieldlabels">Add or change the photo for the property</div>
<div class="imagebox" id="imagebox"><label title="upload a new profile photo" for="files">
  <?php if ($row_rs_property_image['g_image'] == "media/profile.png") { // Show if recordset empty ?>
    <img src="media/profile-bg.png" width="50" height="50" />
    <?php } // Show if recordset empty ?>
    <div id="wrapper" class="wrapper">
    <?php if ($row_rs_property_image['g_image'] != "media/profile.png") { // Show if recordset empty ?>   
    <img src="<?php echo $row_rs_property_image['g_image']; ?>" alt="killjoy.co.za member profile image" class="profilephoto" /> 
    <span title="remove your profile photo" onClick="unlink_thumb('<?php echo $image_id;?>')" class="close"></span>
      <?php } // Show if recordset empty ?>
    </label>
     
    </div>
<input onChange="return acceptimage()"  id="files" name="files[]" type="file" accept="image/x-png,image/gif,image/jpeg" /></div>
<div id="uploader" class="uploader"><img src="images/loading24x24.gif" width="24" height="24" alt="killjoy.co.za member profile image upload status indicator" class="indicator" />Uploading</div>
<div class="logoloaderrors" id="logoloaderror"><?php if ($totalRows_show_error > 0) { // Show if recordset empty ?><ol>
<?php do { ?><li><?php echo $row_show_error['error_message']; ?><?php } while ($row_show_error = mysql_fetch_assoc($show_error)); ?></li>
</ol>
<?php } ?>
</div>
  <div class="stepfields" id="stepone"><ol type="1" start="2"><li>Edit</li></ol></div> 
  <div class="fieldlabels" id="fieldlabels">Edit the property details, if necessary:</div>
   <div class="fieldlabels" id="fieldlabels">Street/Unit Nr and Name:</div>
   <div class="streetaddress" id="streetaddress"><div class="streetnumber"><input class="streetnr" id="street_number" name="street_number"></input></div><div class="streetname"><input class="streetnm" id="route" name="streetname"></input></div></div>  
   <div class="fieldlabels" id="fieldlabels">City or Town:</div>
   <div class="formfields" id="citybox"><input class="cityname" id="locality" name="citytown" readonly="true"></input></div>
    <div class="fieldlabels" id="provbox">Province and Postal code:</div>
    <div class="provincecode" id="provincecode"><div class="province"><input class="provincename" name="province" id="administrative_area_level_1"></input></div><div class="postcode"><input class="postcd" id="postal_code" name="postal_code" ></input></div></div>  
     <div class="fieldlabels" id="fieldlabels">Country:</div>
     <div class="formfields" id="countrybox"><input class="cityname" id="country" name="country"readonly="true"></input></div><button class="nextbutton">Next <span class="icon-arrow-circle-right"></span></button>
 
 <input type="hidden" name="MM_insert" value="addressField">
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
<?php
mysql_free_result($rs_showproperty);

mysql_free_result($rs_property_image);

mysql_free_result($show_error);
?>
