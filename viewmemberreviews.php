<?php require_once('Connections/killjoy.php'); ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "admin/index.php";
if (!((isset($_SESSION['kj_username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['kj_username'], $_SESSION['kj_authorized'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
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

$colname_rs_edit_reviews = "-1";
if (isset($_GET['claw'])) {
  $colname_rs_edit_reviews = $_GET['claw'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_edit_reviews = sprintf("SELECT tbl_address.sessionid as propsession, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, DATE_FORMAT(tbl_address_comments.rating_date, '%%d-%%b-%%y')AS ratingDate, tbl_address_comments.rating_feeling As feeLing, tbl_address_comments.rating_comments AS comments, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage,  ROUND(AVG(tbl_address_rating.rating_value),0) AS Avgrating FROM tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid  LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.sessionid = tbl_address.sessionid LEFT JOIN social_users on social_users.g_email = tbl_address.social_user LEFT JOIN tbl_address_rating ON tbl_address_rating.sessionid = tbl_address.sessionid WHERE tbl_address.sessionid = %s GROUP BY tbl_address.sessionid ORDER BY tbl_address_comments.rating_date DESC", GetSQLValueString($colname_rs_edit_reviews, "text"));
$rs_edit_reviews = mysql_query($query_rs_edit_reviews, $killjoy) or die(mysql_error());
$row_rs_edit_reviews = mysql_fetch_assoc($rs_edit_reviews);
$totalRows_rs_edit_reviews = mysql_num_rows($rs_edit_reviews);



function generateRandomString($length = 10) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
return $randomString;
}
$sessionid = generateRandomString();

	


$colname_show_error = "-1";
if (isset($_SESSION['sessionid'])) {
  $colname_show_error = $_SESSION['sessionid'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_show_error = sprintf("SELECT * FROM tbl_uploaderror WHERE sessionid = %s", GetSQLValueString($colname_show_error, "text"));
$show_error = mysql_query($query_show_error, $killjoy) or die(mysql_error());
$row_show_error = mysql_fetch_assoc($show_error);
$totalRows_show_error = mysql_num_rows($show_error);

$colname_rs_property_image = "-1";
if (isset($_GET['claw'])) {
  $colname_rs_property_image = $_GET['claw'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_property_image = sprintf("SELECT image_url AS g_image, image_id AS image_id FROM tbl_propertyimages WHERE sessionid = %s", GetSQLValueString($colname_rs_property_image, "text"));
$rs_property_image = mysql_query($query_rs_property_image, $killjoy) or die(mysql_error());
$row_rs_property_image = mysql_fetch_assoc($rs_property_image);
$totalRows_rs_property_image = mysql_num_rows($rs_property_image);
$image_id = $row_rs_property_image['image_id'];?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="alternate" href="https://www.killjoy.co.za/" hreflang="en" />
<link rel="apple-touch-icon" sizes="57x57" href="favicons/apple-icon-57x57.png" />
<link rel="apple-touch-icon" sizes="60x60" href="favicons/apple-icon-60x60.png" />
<link rel="apple-touch-icon" sizes="72x72" href="favicons/apple-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="76x76" href="favicons/apple-icon-76x76.png" />
<link rel="apple-touch-icon" sizes="114x114" href="favicons/apple-icon-114x114.png" />
<link rel="apple-touch-icon" sizes="120x120" href="favicons/apple-icon-120x120.png" />
<link rel="apple-touch-icon" sizes="144x144" href="favicons/apple-icon-144x144.png" />
<link rel="apple-touch-icon" sizes="152x152" href="favicons/apple-icon-152x152.png" />
<link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-icon-180x180.png" />
<link rel="icon" type="image/png" sizes="192x192"  href="favicons/android-icon-192x192.png" />
<link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="96x96" href="favicons/favicon-96x96.png" />
<link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png" />
<link rel="manifest" href="/manifest.json" />
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>Killjoy - view and change your killjoy.co.za reviews</title>
<link href="css/edit-reviews/profile.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="admin/css/checks.css" rel="stylesheet" type="text/css" />
<link href="css/edit-reviews/fileupload.css" rel="stylesheet" type="text/css" />
<link href="css/edit-reviews/close.css" rel="stylesheet" type="text/css" />
<link href="css/edit-reviews/rating_selection.css" rel="stylesheet" type="text/css" />
<link href="css/edit-reviews/radios.css" rel="stylesheet" type="text/css" />
</head>
<body onLoad="set_session()">
<form id="register" class="form" name="register" method="POST" action="myprofile.php">
<div class="formcontainer" id="formcontainer">
<div class="formheader">Killjoy.co.za member reviews</div>
<div class="imagebox" id="imagebox"><label title="upload a new profile photo" for="files">  
  <?php if ($totalRows_rs_property_image == 0) { // Show if recordset empty ?>
    <img src="media/image-add-65x65.png" title="click me to add a photo for this property" />
    <?php } // Show if recordset empty ?>
<div id="wrapper" class="wrapper">
      <?php if ($totalRows_rs_property_image > 0) { // Show if recordset empty ?>
    <img src="<?php echo $row_rs_property_image['g_image']; ?>" alt="killjoy.co.za member profile image" class="profilephoto" /> 
    <span title="remove this property rental review image" onClick="unlink_thumb('<?php echo $image_id;?>')" class="close"></span>
      <?php } // Show if recordset empty ?>
</label>
     
    </div>
<input onChange="acceptimage()"  id="files" name="files[]" type="file" accept="image/x-png,image/gif,image/jpeg" /></div>
<div id="uploader" class="uploader"><img src="images/loading24x24.gif" width="24" height="24" alt="killjoy.co.za member profile image upload status indicator" class="indicator" />Uploading</div>
<div class="logoloaderrors" id="logoloaderror"><?php if ($totalRows_show_error > 0) { // Show if recordset empty ?><ol>
<?php do { ?><li><?php echo $row_show_error['error_message']; ?><?php } while ($row_show_error = mysql_fetch_assoc($show_error)); ?></li>
</ol>
<?php } ?>
</div>
  <div class="fieldlabels" id="fieldlabels">Property address:</div>
  <div class="formfields" id="streetdetails">
    <label>
      <input onchange="return update_fields()" name="txt_streetnumber" type="text" class="streetnumber" id="txt_streetnumber" value="<?php echo $row_rs_edit_reviews['streetnumber']; ?>" />
      <input onchange="return update_street()" name="txt_streetname" type="text" class="streetname" id="txt_streetname" value="<?php echo ucfirst($row_rs_edit_reviews['streetname']); ?>" />
    </label>
    </div>
    <div class="formfields" id="citydetails"><input onchange="return update_city()" name="txt_city" type="text" id="txt_city" class="emailfield" value="<?php echo ucfirst($row_rs_edit_reviews['city']); ?>" />
    </div>
   <div class="fieldlabels" id="fieldlabels">Your rating:</div>
   <div class="ratingbox" id="ratingdiv">
     <input name="property_id" id="property_id" type="hidden" value="<?php echo $row_rs_edit_reviews['propsession']; ?>" />
      <label for="owleyes"></label>
      <label>
        <input name="click_count" type="hidden" id="click_count" value="1" />
      </label>
      <fieldset class="fieldset" onClick="rating_score()" id="button">
          <div class='rating_selection'>
          <input <?php if (!(strcmp($row_rs_edit_reviews['Avgrating'],"0"))) {echo "checked=\"checked\"";} ?> checked id='rating_0' name='rating' type='radio' value='0'><label for='rating_0'>
            <span>Unrated</span>
            </label><input <?php if (!(strcmp($row_rs_edit_reviews['Avgrating'],"1"))) {echo "checked=\"checked\"";} ?> id='rating_1' name='rating' type='radio' value='1'><label title="Do not rent this property" for='rating_1'>
              <span>Rate 1 Star</span>
              </label><input <?php if (!(strcmp($row_rs_edit_reviews['Avgrating'],"2"))) {echo "checked=\"checked\"";} ?> id='rating_2' name='rating' type='radio' value='2'><label title="Rent this property with caution" for='rating_2'>
                <span>Rate 2 Stars</span>
                </label><input <?php if (!(strcmp($row_rs_edit_reviews['Avgrating'],"3"))) {echo "checked=\"checked\"";} ?> id='rating_3' name='rating' type='radio' value='3'><label title="Consider renting this property" for='rating_3'>
                  <span>Rate 3 Stars</span>
                  </label><input <?php if (!(strcmp($row_rs_edit_reviews['Avgrating'],"4"))) {echo "checked=\"checked\"";} ?> id='rating_4' name='rating' type='radio' value='4'><label title="Others recommended renting this property" for='rating_4'>
                    <span>Rate 4 Stars</span>
                    </label><input <?php if (!(strcmp($row_rs_edit_reviews['Avgrating'],"5"))) {echo "checked=\"checked\"";} ?> id='rating_5' name='rating' type='radio' value='5'><label title="Definately rent this property" for='rating_5'>
                      <span>Rate 5 Stars</span>
        </label></div>
      </fieldset>        
      </div>      
            <div class="fieldlabels" id="moodselector">Describe your mood:</div>
      <div class="cc-selector" id="moodselectors">
      <fieldset onChange="member_feeling()" class="moodies">
        <input <?php if (!(strcmp($row_rs_edit_reviews['feeLing'],"not a happy tenant"))) {echo "checked=\"checked\"";} ?> title="I am not a happy tenant" id="visa" type="radio" name="credit_card" value="not a happy tenant" />
        <label class="drinkcard-cc visa" for="visa"></label>
        <input <?php if (!(strcmp($row_rs_edit_reviews['feeLing'],"a very happy tenant"))) {echo "checked=\"checked\"";} ?> title="I am a very happy tenant" id="mastercard" type="radio" name="credit_card" value="a very happy tenant" />
        <label class="drinkcard-cc mastercard"for="mastercard"></label>
        </fieldset>
    </div>
      <div class="fieldlabels" id="fieldlabels">Your Experience:</div>
  <div class="formfields" id="experiencedetails">
    <label>
      <textarea wrap="physical" onchange="update_comments()" class="commentbox" name="txt_experience" id="txt_experience" cols="45" rows="5"><?php echo $row_rs_edit_reviews['comments']; ?></textarea>
    </label>
    </div>
<div class="fieldlabels" id="fieldlabels">Review Date:<span class="changepassword">
      <input name="txt_sesseyed" type="hidden" id="txt_sesseyed" value="<?php echo $_GET['claw']; ?>" />
    </span></div>
      <div class="datefield" id="formfields"><?php echo $row_rs_edit_reviews['ratingDate']; ?></div>
    <div class="accpetfield" id="accpetfield"> <div class="accepttext">By clicking Update, you agree to our <a href="info-centre/terms-of-use.html">Site Terms</a> and confirm that you have read our <a href="info-centre/help-centre.html">Usage Policy,</a> including our <a href="info-centre/cookie-policy.php">Cookie Usage Policy.</a></div> </div>
</div>
<input type="hidden" name="MM_insert" value="update" />
</form>
<script type="text/javascript">
 function acceptimage() {
var data = new FormData();
jQuery.each(jQuery('#files')[0].files, function(i, file) {
data.append('file-'+i, file);
data.append('txt_sesseyed', $("#txt_sesseyed").val());
 }); 
$.ajax({
url: 'functions/reviewimageupload.php',
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
 function rating_score ( txt_rating ) 
{ $.ajax( { type    : "POST",
data: {"txt_sesseyed" : $("#txt_sesseyed").val(), "txt_rating" : $("input[name=rating]:checked").val()},
url     : "functions/reviewratingupdater.php",
success : function (txt_rating)
		  {   
		  $("#ratingdiv").removeClass("ratingbox");
          $("#ratingdiv").load(location.href + " #ratingdiv");
			
		  },
		error   : function ( xhr )
		  { alert( "error" );
		  }
		  
} );
 return false;	
}
</script>

<script type="text/javascript">
 function member_feeling ( credit_card ) 
{ $.ajax( { type    : "POST",
data: {"txt_sesseyed" : $("#txt_sesseyed").val(), "txt_feeling" :$("input[name=credit_card]:checked").val()},
url     : "functions/reviewfeelingupdater.php",
success : function (txt_feeling)
		  {     
		  $("#moodselectors").removeClass("cc-selector");
          $("#moodselectors").load(location.href + " #moodselectors");
		  },
		error   : function ( xhr )
		  { alert( "error" );
		  }
		  
} );
 return false;	
}
</script>

<script type="text/javascript">
 function set_session ( txt_sesseyed ) 
{ $.ajax( { type    : "POST",
data    : { "txt_sesseyed" : $("#txt_sesseyed").val()}, 
url     : "admin/member_session.php",
success : function (data)
{ 
  
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>

<script type="text/javascript">
 function update_fields ( txt_streetnumber ) 
 
{ $.ajax( { type    : "POST",
data: {"txt_sesseyed" : $("#txt_sesseyed").val(), "txt_streetnumber" : $("#txt_streetnumber").val()},
url     : "functions/reviewfieldupdater.php",
success : function (data)
{ 
    $("#streetdetails").removeClass("formfields");
$("#streetdetails").load(location.href + " #streetdetails");
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>

<script type="text/javascript">
 function update_comments ( txt_experience ) 
 
{ $.ajax( { type    : "POST",
data: {"txt_sesseyed" : $("#txt_sesseyed").val(), "txt_experience" : $("textarea#txt_experience").val()},
url     : "functions/reviewcommentupdater.php",
success : function (data)
{ 
    $("#experiencedetails").removeClass("formfields");
$("#experiencedetails").load(location.href + " #experiencedetails");
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>

<script type="text/javascript">
 function update_street ( txt_streetname ) 
 
{ $.ajax( { type    : "POST",
data: {"txt_sesseyed" : $("#txt_sesseyed").val(), "txt_streetname" : $("#txt_streetname").val()},
url     : "functions/reviewstreetupdater.php",
success : function (data)
{ 
    $("#streetdetails").removeClass("formfields");
$("#streetdetails").load(location.href + " #streetdetails");
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>

<script type="text/javascript">
 function update_city ( txt_streetname ) 
 
{ $.ajax( { type    : "POST",
data: {"txt_sesseyed" : $("#txt_sesseyed").val(), "txt_city" : $("#txt_city").val()},
url     : "functions/reviewcityupdater.php",
success : function (data)
{ 
    $("#citydetails").removeClass("formfields");
$("#citydetails").load(location.href + " #citydetails");
},
error   : function ( xhr )
{ alert( "error" );
}
 } );
 return false;
 }
</script>


<script type="text/javascript">
function unlink_thumb ( image_id ) 
{ $.ajax( { type    : "POST",
async   : false,
data    : { "image_id" : image_id }, 
url     : "functions/removereviewimage.php",
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

<script>
    $("#txt_experience").autogrow();
</script>

</body>
</html>
<?php
mysql_free_result($rs_edit_reviews);

mysql_free_result($show_error);

mysql_free_result($rs_property_image);
?>
