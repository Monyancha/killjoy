<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_killjoy = "localhost";
$database_killjoy = "euqjdems_killjoy";
$username_killjoy = "euqjdems_nawisso";
$password_killjoy = "N@w!1970";
$killjoy = mysql_pconnect($hostname_killjoy, $username_killjoy, $password_killjoy) or trigger_error(mysql_error(),E_USER_ERROR); 
?>