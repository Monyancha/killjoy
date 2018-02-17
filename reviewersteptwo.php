<?php require_once('Connections/killjoy.php'); ?>
<?php
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
<body>

<div id="locationField" class="reviewcontainer">
    <form  action="<?php echo $editFormAction; ?>" method="POST" name=addressField class="reviewform">
    <div class="formheader">Review a Rental Property</div>
     <div class="stepfields" id="stepone"><ol type="1"><li>Search</li></ol></div>   
    <div class="fieldlabels" id="fieldlabels">Search for the property:</div>
<div class="formfields" id="searchbox"><input name="address" class="searchfield" type="text" id="autocomplete" placeholder="find an address" onFocus="geolocate()" size="80" /></div>  
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