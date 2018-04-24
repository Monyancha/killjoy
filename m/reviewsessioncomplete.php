<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('Connections/killjoy.php');


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

$colname_rs_social_user = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_social_user = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_social_user = sprintf("SELECT g_name, g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_social_user, "text"));
$rs_social_user = mysql_query($query_rs_social_user, $killjoy) or die(mysql_error());
$row_rs_social_user = mysql_fetch_assoc($rs_social_user);
$totalRows_rs_social_user = mysql_num_rows($rs_social_user);

$colname_rs_showproperty = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_showproperty = $_SESSION['kj_propsession'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_showproperty = sprintf("SELECT * FROM tbl_address WHERE sessionid = %s", GetSQLValueString($colname_rs_showproperty, "text"));
$rs_showproperty = mysql_query($query_rs_showproperty, $killjoy) or die(mysql_error());
$row_rs_showproperty = mysql_fetch_assoc($rs_showproperty);
$totalRows_rs_showproperty = mysql_num_rows($rs_showproperty);
$streetnr = $row_rs_showproperty['str_number'];
$street = $row_rs_showproperty['street_name'];
$city = $row_rs_showproperty['city'];

?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>killjoy - registration completed</title>
<link href="../jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.dialog.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.resizable.min.css" rel="stylesheet" type="text/css">
<script src="../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<script src="../SpryAssets/jquery.ui-1.10.4.dialog.min.js"></script>
<link href="iconmoon/style.css" rel="stylesheet" type="text/css">
<link href="css/dialog-styling.css" rel="stylesheet" type="text/css">
</head>
<style>
	.confirm {
		width: 90% !important;
	}	
	</style>
<body>
<div data-role="page" id="page">

<div class="confirm" id="confirm"><img style="width: 100px;height: 100px;vertical-align: middle;" src="images/icons/gold_medal.png" class="first-place" width="100" height="100"  alt="killjoy rental property review completed" /> <?php echo $row_rs_social_user['g_name']; ?> you are a superstar! </p>
  <p>Thank you for sharing your personal experiences. Your review of <strong><?php echo $streetnr ?>&nbsp;<?php echo $street ?>&nbsp;<?php echo $city ?></strong> has been recorded and we are working hard to get it published.</p>
  <p>Your review is under assessment from one of our editors and will be published as soon as the editor approves of the the content in your review. All reviews are subjected to the Terms and Conditions as stipulated by our <a href='info-centre/fair-review-policy.html'>Fair Review Policy</a></p></div>
 </div>
<script type="text/javascript">
$(function() {
	$( "#confirm" ).dialog(); 
	$( "#confirm" ).dialog({ title: "Review Completed!" });
	
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
		   parent.location.href ="index.php";
       } //end function for Ok button
    }//end buttons
 });     // end dialog
 elem.dialog('open');
	$('#confirm').bind('dialogclose', function(event) {
     window.location = "index.php";
 });	
	
	</script>
</body>
</html>