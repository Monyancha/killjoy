<?php
$signed_request = $_REQUEST['signed_request'];
function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}
list($encoded_sig, $payload) = explode('.', $signed_request, 2);
// decode the data
$sig = base64_url_decode($encoded_sig); // Use this to make sure the signature is correct
$data = json_decode(base64_url_decode($payload), true);
$user_id = $data['user_id'];

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