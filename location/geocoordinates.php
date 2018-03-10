<?php

if(isset($_POST['lat'], $_POST['lng'])) {
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    $url = sprintf("https://maps.googleapis.com/maps/api/geocode/json?latlng=%s,%s", $lat, $lng);

    $content = file_get_contents($url); // get json content

    $metadata = json_decode($content, true); //json decoder

    if(count($metadata['results']) > 0) {
        // for format example look at url
        // https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452
        $result = $metadata['results'][0];

        // save it in db for further use
        echo $result['formatted_address'];

    }
    else {
        // no results returned
    }
}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>