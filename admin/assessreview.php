<?php require_once('../Connections/killjoy.php'); ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
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


$colname_rs_show_comments = "-1";
if (isset($_POST['reference'])) {
  $colname_rs_show_comments = $_POST['reference'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_comments = sprintf("SELECT social_user, sessionid, rating_comments FROM tbl_address_comments WHERE sessionid = %s", GetSQLValueString($colname_rs_show_comments, "text"));
$rs_show_comments = mysql_query($query_rs_show_comments, $killjoy) or die(mysql_error());
$row_rs_show_comments = mysql_fetch_assoc($rs_show_comments);
$totalRows_rs_show_comments = mysql_num_rows($rs_show_comments);


$string = $row_rs_show_comments['rating_comments'];
if ($totalRows_rs_show_comments) {

$string_to_array = explode(" ",$string);

foreach($string_to_array as $word)

{
   $query = mysql_query("SELECT bad_word from tbl_profanity WHERE bad_word = '$word'");
   $totalRows_badword = mysql_num_rows($query);
   
   
   while($row = mysql_fetch_assoc($query))
  
   {
	    
	    
       $word_found = $row['bad_word'];
	   $new_word = preg_replace('/(?!^.?).(?!.{0}$)/','*',$word_found);
	  	   
	   $key = array_search($word_found,$string_to_array);
	   $length = strlen($word_found) +1;
	   
	   
	   $replace = array($key => $new_word);
	   $string_to_array = array_replace($string_to_array,$replace);
	  

	      }
   
   
}
$new_string = implode(" ",$string_to_array);


}



if ((isset($_POST["approvebtn"])) && ($_POST["approvebtn"] == "approved")) {
  $updateSQL = sprintf("UPDATE tbl_approved SET was_checked=%s, is_approved=%s WHERE sessionid=%s",
                       GetSQLValueString(1, "int"),
					   GetSQLValueString(1, "int"),
                       GetSQLValueString($_POST['txt_sessionid'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  

}	

if ((isset($_POST["declinebtn"])) && ($_POST["declinebtn"] == "declined")) {
  $updateSQL = sprintf("UPDATE tbl_approved SET was_checked=%s, is_approved=%s WHERE sessionid=%s",
                       GetSQLValueString(1, "int"),
					   GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['txt_sessionid'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
  

}	



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>Killjoy - register to use the app</title>
<link href="css/admins/desktop.css" rel="stylesheet" type="text/css" />
<script src="../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/checks.css" rel="stylesheet" type="text/css" />
<link href="../css/login-page/mailcomplete.css" rel="stylesheet" type="text/css">
</head>
<body>
<form id="register" class="form" name="register" method="POST" action="checkreview.php">

<div class="maincontainer" id="maincontainer">
  <div class="header">Assess a Review</div>
  <div class="fieldlabels" id="fieldlabels">Review Reference Number:</div>
  <div class="formfields" id="formfields"><span id="sprytextfield1">
    <label>
      <input name="reference" type="text" class="inputfields" id="reference" />
    </label>
    <span class="textfieldRequiredMsg">!</span></span></div>
  <div class="formfields" id="formfields"></div>
    <div class="accpetfield" id="accpetfield"> <div class="accepttext">Paste the reference number of the review in the box above and click on continue.<a href="../info-centre/cookie-policy.php"></a></div> </div>
    <div class="formfields" id="formfields">
    <button class="nextbutton">Continue <span class="icon-checkbox-checked"></button>
    </div>
</div>
<input type="hidden" name="MM_insert" value="register" />
<input type="hidden" name="MM_update" value="reviewed" />
</form>

<br />
<?php if ($totalRows_rs_show_comments > 0) { // Show if recordset not empty ?>
  <form id="status" name="status" method="post" action="">
    <div class="maincontainer" id="maincontainer2">
      <div class="header">Assessment Results</div>
      <div class="fieldlabels" id="fieldlabels2">Status	</div>
      <div class="formfields" id="formfields2"><span id="sprytextfield2"><span class="textfieldRequiredMsg">!</span></span></div>
      <div class="fieldlabels"><input name="txt_sessionid" type="hidden" value="<?php echo $row_rs_show_comments['sessionid']; ?>" /></div>
            <div class="accpetfield" id="accpetfield2">
        <div class="accepttext"><?php echo $new_string; ?></div></div>
      <div class="formfields" id="formfields2">
        <button class="nextbutton" value="approved" name="approvebtn" id="approvebtn">Approve <span class="icon-checkbox-checked"></button>
                <button class="declinebutton" value="declined" name="declinebtn" id="declinebtn">Decline <span class="icon-cross"></button>
      </div>
    </div>
  </form>
  <?php } // Show if recordset not empty ?>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");

</script>
</body>
</html>
<?php
mysql_free_result($rs_show_comments);


?>
