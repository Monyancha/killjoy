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

mysql_select_db($database_killjoy, $killjoy);
$query_rs_conversions = "SELECT FROM_DAYS(TO_DAYS(DATE_FORMAT(c_date, '%Y-%d-01')) -MOD(TO_DAYS(c_date) -2, 7)) AS week_beginning, COUNT(id) as totalConversions, SUM(is_happy)/COUNT(id)*100 as Good, SUM(not_happy)/COUNT(id)*100 as not_good FROM  tbl_conversions WHERE ( c_date > DATE_SUB(now(), INTERVAL 30  DAY))   GROUP BY FROM_DAYS(TO_DAYS(c_date) -MOD(TO_DAYS(c_date) -2, 7))";
$rs_conversions = mysql_query($query_rs_conversions, $killjoy) or die(mysql_error());
$row_rs_conversions = mysql_fetch_assoc($rs_conversions);
$totalRows_rs_conversions = mysql_num_rows($rs_conversions);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>killjoy.co.za -conversion rates</title>
<style media="screen" type="text/css" title="Conversions">
.conversioncontainer {
	height: 100px;
	width: 300px;	
	height: 100px;
	position: relative;
	margin-right: auto;
	margin-left: auto;	
}
.conversioncenter {
	height: 100px;
	width: 100px;
	background-image: url(../images/conversions/conversion-arrows-soft.png);
	background-repeat: no-repeat;
	background-position: 0px 0px;
	position: absolute;
	left: 100px;
	top: 0px;
	border-radius:50%;
	z-index: 99;
}
.good {
	height: 30px;
	width: <?php echo round($row_rs_conversions['Good'], 0) ?>px;
	right: 200px;
	top: 35px;
	position: absolute;
	direction: rtl;
	background-repeat: repeat-x;
	background-image: url(../images/conversions/good.png);
	background-position: -140px 0px;
	border-top-left-radius: 17.5px;
	border-bottom-left-radius:17.5px;
	
}

.goodcurve {
	height: 30px;
	width: 5px;
	right: 200px;
	top: 35px;
	position: absolute;
	direction: rtl;
	background-color: #FFF;	
		border-top-left-radius: 50%;
	border-bottom-left-radius:50%;
	
}
.bad {
	position: absolute;
	left: 200px;
	top: 35px;
	height: 30px;
	width: <?php echo round($row_rs_conversions['not_good'], 0) ?>px;
	background-image: url(../images/conversions/bad.png);
	background-repeat: repeat-x;
	background-position: 0px 0px;
	border-top-right-radius: 17.5px;
	border-bottom-right-radius:17.5px;
	
}

.badcurve {
	position: absolute;
	left: 200px;
	top: 35px;
	height: 30px;
	width:5px;
	background-color: #FFF;	
	border-top-right-radius: 50%;
	border-bottom-right-radius:50%;
}

.badtext {
	position: absolute;
	left: 200px;
	top: 70px;
	height: 30px;
	width:100px;
	background-color: #FFF;
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 0.8em;
	color: #216CF1;	
}

.goodtext {
	height: 30px;
	width: 100px;
	;
	right: 200px;
	position: absolute;
	direction: rtl;
	background-color: #FFF;
	top: 70px;
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 0.8em;
	color: #216CF1;
	}

</style>
</head>

<body>
<div class="conversioncontainer"><div class="conversioncenter"></div><div class="good"></div><div class="goodcurve"></div><div class="goodtext">Happy <?php echo round($row_rs_conversions['Good'], 0) ?>%</div><div class="badtext"><?php echo round($row_rs_conversions['not_good'], 0) ?>% Unhappy</div><div class="bad"></div><div class="badcurve"></div></div>

</body>
</html>
<?php
mysql_free_result($rs_conversions);
?>
