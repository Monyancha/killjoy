<?php require_once('../Connections/killjoy.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addressField")) {
  $insertSQL = sprintf("INSERT INTO tbl_address (str_number, street_name, city, province, postal_code, Country) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['street_number'], "text"),
                       GetSQLValueString($_POST['route'], "text"),
                       GetSQLValueString($_POST['locality'], "text"),
                       GetSQLValueString($_POST['province'], "text"),
                       GetSQLValueString($_POST['postal_code'], "text"),
                       GetSQLValueString($_POST['country'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
}
?>
<body>

<div id="locationField">
    <form name=addressField  action="<?php echo $editFormAction; ?>" method="POST">
        <input name="address" type="text" id="autocomplete" placeholder="Enter your address"
               onFocus="geolocate()" size="80"></input>

</div>

        <table id="address">
        <tr>
          <td class="label">Street address</td>

          <td class="slimField"><input class="field" id="street_number" name="street_number"
                ></input></td>

          <td class="wideField" colspan="2"><input class="field" id="route" name="route"
                readonly="true"></input></td>
        </tr>
        <tr>
          <td class="label">City</td>

          <!-- Note: Selection of address components in this example is typical.
               You may need to adjust it for the locations relevant to your app. See
               https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
          -->
          <td class="wideField" colspan="3"><input class="field" id="locality" name="locality"
                readonly="true"></input></td>
        </tr>
        <tr>
          <td class="label">State</td>
          <td class="slimField"><input class="field" name="province"
                id="administrative_area_level_1" readonly></input></td>
          <td class="label">Zip code</td>
          <td class="wideField"><input class="field" id="postal_code" name="postal_code"
                readonly="true"></input></td>
        </tr>
        <tr>
          <td class="label">Country</td>
          <td class="wideField" colspan="3"><input class="field"
                id="country" name="country"readonly="true"></input></td>
        </tr>
      </table>
    <input type="submit" value="Submit" onClick="validateForm()">
    <input type="hidden" name="MM_insert" value="addressField">
  </form>

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