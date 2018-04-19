<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/killjoy.php');
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

$colname_rs_user_details = "-1";
if (isset($_SESSION['user_email'])) {
  $colname_rs_user_details = $_SESSION['user_email'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_user_details = sprintf("SELECT g_name, g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_user_details, "text"));
$rs_user_details = mysql_query($query_rs_user_details, $killjoy) or die(mysql_error());
$row_rs_user_details = mysql_fetch_assoc($rs_user_details);
$totalRows_rs_user_details = mysql_num_rows($rs_user_details);
$name = $row_rs_user_details['g_name'];
$email = $row_rs_user_details['g_email'];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>killjoy - registration completed</title>
<link href="../../jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.dialog.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.resizable.min.css" rel="stylesheet" type="text/css">
<script src="../../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<script src="../../SpryAssets/jquery.ui-1.10.4.dialog.min.js"></script>
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css">
<link href="../css/dialog-styling.css" rel="stylesheet" type="text/css">
</head>

<body>
<div data-role="page" id="page">

<div class="confirm" id="confirm">Dear <?php echo $name ?>, your email address was successfully changed <a href="mailto:<?php echo $email ?>"></a> . An email has been sent to <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a>. Please follow the link in the Email to verify your new email address.<br><br> Thank you, the <a href="https://www.killjoy.co.za">The killjoy Team</a></div>
 </div>
<script type="text/javascript">
$(function() {
	$( "#confirm" ).dialog(); 
	$( "#confirm" ).dialog({ title: "Success!" });
	
    	});
	
</script>

<script type="text/javascript">
	 var elem = $("#confirm");
	$("#confirm").dialog({ closeText: '' });
 elem.dialog({
       resizable: false,
    title: 'title',
    buttons: {
       Ok: function() {
          $(this).dialog('close');
		   parent.location.href ="../index.php";
       } //end function for Ok button
    }//end buttons
 });     // end dialog
 elem.dialog('open');
	
	</script>
</body>
</html>