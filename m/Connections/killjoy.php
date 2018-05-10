<?php
# FileName="Connection_php_mysql.htm"
# Type="mysql"
# HTTP="true"
$hostname_killjoy = "localhost";
$database_killjoy = "euqjdems_killjoy";
$username_killjoy = "euqjdems_nawisso";
$password_killjoy = "N@w!1970";
$killjoy = ($GLOBALS["___mysqli_ston"] = mysqli_connect($hostname_killjoy,  $username_killjoy,  $password_killjoy)) or trigger_error(mysqli_error($GLOBALS["___mysqli_ston"]),E_USER_ERROR); 
?>