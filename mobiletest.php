<?php require_once('Connections/killjoy.php'); ?>
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
$query_rs_show_comments = "SELECT * FROM kj_recall";
$rs_show_comments = mysql_query($query_rs_show_comments, $killjoy) or die(mysql_error());
$row_rs_show_comments = mysql_fetch_assoc($rs_show_comments);
$totalRows_rs_show_comments = mysql_num_rows($rs_show_comments);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['textinput'])) {
  $loginUsername=$_POST['textinput'];
  $password=$_POST['passwordinput'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "viewreviews.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_killjoy, $killjoy);
  
  $LoginRS__query=sprintf("SELECT g_id, g_name FROM social_users WHERE g_id=%s AND g_name=%s",
    GetSQLValueString($loginUsername, "double"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $killjoy) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/jquery.ui.autocomplete.min.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/jquery.ui.menu.min.css" rel="stylesheet" type="text/css" />
<script src="http://code.jquery.com/jquery-1.5.min.js" type="text/javascript"></script>
<script src="http://code.jquery.com/mobile/1.0a3/jquery.mobile-1.0a3.min.js" type="text/javascript"></script>
<script src="SpryAssets/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="SpryAssets/jquery.ui-1.10.4.autocomplete.min.js" type="text/javascript"></script>
</head>

<body>
<div data-role="page" id="page">
  <div data-role="header">
    <h1>Header</h1>
  </div>
  <form action="<?php echo $loginFormAction; ?>" method="POST">
  <div data-role="content">
    <div data-role="fieldcontain">
      <label for="slider">Value:</label>
      <input type="range" name="slider" id="slider" value="0" min="0" max="100" />
      <div data-role="fieldcontain">
        <label for="textinput">Text Input:</label>
        <input type="text" name="textinput" id="textinput" value=""  />
      </div>
      <div data-role="fieldcontain">
        <label for="passwordinput">Password Input:</label>
        <input type="password" name="passwordinput" id="passwordinput" value=""  />
      </div>
      <input type="text" id="Autocomplete1" />
      <input type="submit" value="Submit" data-icon="forward" data-iconpos="right" />
    </div>
  </div>
  </form>
  <div data-role="footer">
    <h4>Footer</h4>
  </div>
</div>
<script type="text/javascript">
$(function() {
	$( "#Autocomplete1" ).autocomplete(); 
});
</script>
</body>
</html>
<?php
mysql_free_result($rs_show_comments);
?>
