<body>
  <?php

    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $database = 'addressTest';
    $con = mysqli_connect($host, $user, $pass, $database);
    if ($con){
        echo 'successfully connected';
    }

  $number = $street = $town = $county = $country = '';



  if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //print_r($_POST); use this to debug POST data 
    $number = $_POST["street_number"];  
    $street = $_POST["route"];
    $town = $_POST["locality"];
    $country = $_POST["country"];

    $sql = "INSERT INTO address (Number, Street, City, State, Country) VALUES ('$number', '$street', '$town', '$county', '$country')";
        $insert = mysqli_query($con, $sql);
        if ($insert){
            echo "inserted successfully";    
    }
  }

  ?>

<div id="locationField">
    <form name=addressField  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input id="autocomplete" placeholder="Enter your address"
               onFocus="geolocate()" type="text" name="address"></input>

</div>

        <table id="address">
        <tr>
          <td class="label">Street address</td>

          <td class="slimField"><input class="field" id="street_number" name="street_number"
                readonly="true"></input></td>

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
          <td class="slimField"><input class="field"
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
    administrative_area_level_1: 'short_name',
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAygtLX9ROxE1TF6RJ9KG6yWJ_zTv5IZG4&libraries=places&callback=initAutocomplete"
    async defer></script>