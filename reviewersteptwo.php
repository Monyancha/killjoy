<?php require_once('Connections/killjoy.php'); ?>
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

$colname_rs_showproperty = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_showproperty = $_SESSION['kj_propsession'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_showproperty = sprintf("SELECT * FROM tbl_address WHERE sessionid = %s", GetSQLValueString($colname_rs_showproperty, "text"));
$rs_showproperty = mysql_query($query_rs_showproperty, $killjoy) or die(mysql_error());
$row_rs_showproperty = mysql_fetch_assoc($rs_showproperty);
$totalRows_rs_showproperty = mysql_num_rows($rs_showproperty);

$colname_rs_property_image = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_property_image = $_SESSION['kj_propsession'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_property_image = sprintf("SELECT image_url,  image_id FROM tbl_propertyimages WHERE sessionid = %s", GetSQLValueString($colname_rs_property_image, "text"));
$rs_property_image = mysql_query($query_rs_property_image, $killjoy) or die(mysql_error());
$row_rs_property_image = mysql_fetch_assoc($rs_property_image);
$totalRows_rs_property_image = mysql_num_rows($rs_property_image);

$colname_show_error = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_show_error = $_SESSION['kj_propsession'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_show_error = sprintf("SELECT * FROM tbl_uploaderror WHERE sessionid = %s", GetSQLValueString($colname_show_error, "text"));
$show_error = mysql_query($query_show_error, $killjoy) or die(mysql_error());
$row_show_error = mysql_fetch_assoc($show_error);
$totalRows_show_error = mysql_num_rows($show_error);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="canonical" href="https://www.killjoy.co.za/review.php">
<title>killjoy - property review page</title>
<link href="css/property-reviews/desktop.css" rel="stylesheet" type="text/css" />
<link href="css/property-reviews/profile.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/close.css" rel="stylesheet" type="text/css" />
<link href="css/tooltips.css" rel="stylesheet" type="text/css" />
<link href="css/property-reviews/fileupload.css" rel="stylesheet" type="text/css" />
<body>

<div id="locationField" class="reviewcontainer">
    <form  action="<?php echo $editFormAction; ?>" method="POST" name=addressField class="reviewform">    
    <div class="formheader">Review a Rental Property</div>
    <div class="addressfield"><?php echo $row_rs_showproperty['str_number']; ?>&nbsp;<?php echo $row_rs_showproperty['street_name']; ?>&nbsp;<?php echo $row_rs_showproperty['city']; ?></div>
     <div class="stepfields" id="stepone"><ol type="1" start="3"><li>Add photo</li></ol></div>   
    <div class="fieldlabels" id="fieldlabels">Add or change the photo for the property</div>
<div class="imagebox" id="imagebox"><label title="upload a photo for this property" for="files">
  <?php if ($totalRows_rs_property_image == 0) { // Show if recordset not empty ?>
    <img title="click me to add a photo for this property" class="addhouse" src="media/image-add-512.png" />
    <?php } // Show if recordset empty ?>
    <div id="wrapper" class="wrapper">
    <?php if ($totalRows_rs_property_image > 0) { // Show if recordset not empty ?>
    <img src="<?php echo $row_rs_property_image['image_url']; ?>" alt="killjoy.co.za rental property image" class="propertyphoto"/> 
    <span title="remove this rental property photo" onClick="unlink_thumb('<?php echo $image_id;?>')" class="propclose"></span>
      <?php } // Show if recordset empty ?>
    </label>     
    </div>
<input onChange="return acceptimage()"  id="files" name="files[]" type="file" accept="image/x-png,image/gif,image/jpeg" /></div>
<div id="uploader" class="uploader"><img src="images/loading24x24.gif" width="24" height="24" alt="killjoy.co.za member profile image upload status indicator" class="indicator" />Uploading</div>
<div class="logoloaderrors" id="logoloaderror"><?php if ($totalRows_show_error > 0) { // Show if recordset empty ?><ol>
<?php do { ?><li><?php echo $row_show_error['error_message']; ?><?php } while ($row_show_error = mysql_fetch_assoc($show_error)); ?></li>
</ol>
<?php } ?>
</div>
  <div class="stepfields" id="stepone"><ol type="1" start="2"><li>Rate</li></ol></div> 
  <div class="fieldlabels" id="fieldlabels">Edit the property details, if necessary:</div>
   <div class="fieldlabels" id="fieldlabels">Street/Unit Nr and Name:</div>
   <div class="streetaddress" id="streetaddress"><div class="streetnumber"><input class="streetnr" id="street_number" name="street_number"></input></div><div class="streetname"><input class="streetnm" id="route" name="streetname"></input></div></div>  
   <div class="fieldlabels" id="fieldlabels">City or Town:</div>
   <div class="formfields" id="citybox"><input class="cityname" id="locality" name="citytown" readonly="true"></input></div>
    <div class="fieldlabels" id="provbox">Province and Postal code:</div>
    <div class="provincecode" id="provincecode"><div class="province"><input class="provincename" name="province" id="administrative_area_level_1"></input></div><div class="postcode"><input class="postcd" id="postal_code" name="postal_code" ></input></div></div>  
     <div class="fieldlabels" id="fieldlabels">Country:</div>
     <div class="formfields" id="countrybox"><input class="cityname" id="country" name="country"readonly="true"></input></div><button class="nextbutton">Next <span class="icon-arrow-circle-right"></span></button>
 
 <input type="hidden" name="MM_insert" value="addressField">
  <input type="hidden" name="txt_sessionid" id="txt_sessionid" value="<?php echo $row_rs_showproperty['sessionid']; ?>" />
  </form>
</div>



    
 <script type="text/javascript">
 function acceptimage() {
var data = new FormData();
jQuery.each(jQuery('#files')[0].files, function(i, file) {
data.append('file-'+i, file);
data.append('txt_sesseyed', $("#txt_sesseyed").val());
 }); 
$.ajax({
url: 'admin/propertyimageupload.php',
data: data, 	
enctype: 'multipart/form-data', 
cache: false,
contentType: false,
processData: false,
type: 'POST',
 beforeSend: function(){
$('.uploader').show();
},
complete: function(){
$('.uploader').hide(); // Handle the complete event
},
success : function (data)
{ 
  $('#logoloaderror').load(document.URL +  ' #logoloaderror');  
    $('#imagebox').load(document.URL +  ' #imagebox');
  
  
			  
			
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
return false();	
}
</script>

<script type="text/javascript">
function unlink_thumb ( image_id ) 
{ $.ajax( { type    : "POST",
async   : false,
data    : { "image_id" : image_id }, 
url     : "admin/removeprofileimage.php",
success : function ( image_id )
{  $('#logoloaderror').load(document.URL +  ' #logoloaderror');  
    $('#imagebox').load(document.URL +  ' #imagebox');
						   
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>
</body>
<?php
mysql_free_result($rs_showproperty);

mysql_free_result($rs_property_image);

mysql_free_result($show_error);
?>
