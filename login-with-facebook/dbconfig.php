<?php
$servername = "localhost";
$username = "euqjdems_nawisso";
$password = "N@w!1970";
$database = "euqjdems_killjoy";

// Create connection
$conn = mysqli_connect($servername, $username, $password,$database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
