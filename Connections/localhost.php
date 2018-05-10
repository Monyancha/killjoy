<?php
# FileName="Connection_php_mysql.htm"
# Type="mysql"
# HTTP="true"
$hostname_localhost = "localhost";
$database_localhost = "euqjdems_killjoy";
$username_localhost = "euqjdems_nawisso";
$password_localhost = "N@w!1970";
$localhost = ($GLOBALS["___mysqli_ston"] = mysqli_connect($hostname_localhost,  $username_localhost,  $password_localhost)) or trigger_error(mysqli_error($GLOBALS["___mysqli_ston"]),E_USER_ERROR); 
?>