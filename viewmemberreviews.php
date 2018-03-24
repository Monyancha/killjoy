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

$colname_rs_show_rating = "-1";
if (isset($_GET['beak'])) {
  $colname_rs_show_rating = $_GET['beak'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_rating = sprintf("SELECT rating_value FROM tbl_address_rating WHERE address_comment_id = %s", GetSQLValueString($colname_rs_show_rating, "int"));
$rs_show_rating = mysql_query($query_rs_show_rating, $killjoy) or die(mysql_error());
$row_rs_show_rating = mysql_fetch_assoc($rs_show_rating);
$totalRows_rs_show_rating = mysql_num_rows($rs_show_rating);

$maxRows_rs_show_review = 1;
$pageNum_rs_show_review = 0;
if (isset($_GET['pageNum_rs_show_review'])) {
  $pageNum_rs_show_review = $_GET['pageNum_rs_show_review'];
}
$startRow_rs_show_review = $pageNum_rs_show_review * $maxRows_rs_show_review;

$colname_rs_show_review = "-1";
if (isset($_GET['claw'])) {
  $colname_rs_show_review = $_GET['claw'];
}
$username_rs_show_review = "-1";
if (isset($_SESSION['kj_username'])) {
  $username_rs_show_review = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_show_review = sprintf("SELECT tbl_address.sessionid as propsession, tbl_approved.is_approved as status, tbl_address.str_number as streetnumber, tbl_address.street_name as streetname, tbl_address.city as city, tbl_address_comments.id as ratingid, tbl_address_comments.rating_feeling As feeLing, tbl_address_comments.rating_comments AS comments, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage FROM tbl_address_comments LEFT JOIN tbl_address ON tbl_address.sessionid = tbl_address_comments.sessionid  LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id  = tbl_address_comments.id LEFT JOIN social_users on social_users.g_email = tbl_address_comments.social_user WHERE tbl_address_comments.sessionid = %s AND tbl_address_comments.social_user = %s ORDER BY tbl_address_comments.rating_date DESC", GetSQLValueString($colname_rs_show_review, "text"),GetSQLValueString($username_rs_show_review, "text"));
$query_limit_rs_show_review = sprintf("%s LIMIT %d, %d", $query_rs_show_review, $startRow_rs_show_review, $maxRows_rs_show_review);
$rs_show_review = mysql_query($query_limit_rs_show_review, $killjoy) or die(mysql_error());
$row_rs_show_review = mysql_fetch_assoc($rs_show_review);

if (isset($_GET['totalRows_rs_show_review'])) {
  $totalRows_rs_show_review = $_GET['totalRows_rs_show_review'];
} else {
  $all_rs_show_review = mysql_query($query_rs_show_review);
  $totalRows_rs_show_review = mysql_num_rows($all_rs_show_review);
}
$totalPages_rs_show_review = ceil($totalRows_rs_show_review/$maxRows_rs_show_review)-1;
$ratingdate = $row_rs_show_review['ratingDate'];





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
$image_id = $row_rs_property_image['image_id'];

$currentPage = $_SERVER["PHP_SELF"];
?>
<?php


$queryString_rs_show_review = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rs_show_review") == false && 
        stristr($param, "totalRows_rs_show_review") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rs_show_review = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rs_show_review = sprintf("&totalRows_rs_show_review=%d%s", $totalRows_rs_show_review, $queryString_rs_show_review);
?>
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
<link href="css/pagenav.css" rel="stylesheet" type="text/css" />
<link href="css/status.css" rel="stylesheet" type="text/css" />
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
<div class="statustext">Status:<?php if($row_rs_show_review['status'] == 1)  { //if stats is approved ?><div class="isapproved"><span class="icon-thumbs-o-up"></span></div><?php } ?><?php if($row_rs_show_review['status'] == 0)  { //if status revoked ?><div class="notapproved"><span class="icon-thumbs-o-down"></span></div><?php } ?></div>
  <div class="fieldlabels" id="fieldlabels">Property address:</div>
  <div class="formfields" id="streetdetails">
    <label>
      <input onChange="return update_fields()" name="txt_streetnumber" type="text" class="streetnumber" id="txt_streetnumber" value="<?php echo $row_rs_show_review['streetnumber']; ?>" />
      <input onChange="return update_street()" name="txt_streetname" type="text" class="streetname" id="txt_streetname" value="<?php echo ucfirst($row_rs_show_review['streetname']); ?>" />
    </label>
    </div>
    <div class="formfields" id="citydetails"><input onChange="return update_city()" name="txt_city" type="text" id="txt_city" class="emailfield" value="<?php echo ucfirst($row_rs_show_review['city']); ?>" />
    <?php echo $row_rs_show_rating['rating_value']; ?></div>
   <div class="fieldlabels" id="fieldlabels">Your rating:</div>
   <div class="ratingbox" id="ratingdiv">
     <input name="property_id" id="property_id" type="hidden" value="<?php echo $row_rs_show_review['propsession']; ?>" />
      <label for="owleyes"></label>
      <label>
        <input name="click_count" type="hidden" id="click_count" value="1" />
      </label>
      <fieldset class="fieldset" onClick="rating_score()" id="button">
          <div class='rating_selection'>
          <input  <?php if (!(strcmp($row_rs_show_rating['rating_value'],"0"))) {echo "checked=\"checked\"";} ?> checked id='rating_0' name='rating' type='radio' value='0'><label for='rating_0'>
            <span>Unrated</span>
            </label><input  <?php if (!(strcmp($row_rs_show_rating['rating_value'],"1"))) {echo "checked=\"checked\"";} ?> id='rating_1' name='rating' type='radio' value='1'><label title="Do not rent this property" for='rating_1'>
              <span>Rate 1 Star</span>
              </label><input  <?php if (!(strcmp($row_rs_show_rating['rating_value'],"2"))) {echo "checked=\"checked\"";} ?> id='rating_2' name='rating' type='radio' value='2'><label title="Rent this property with caution" for='rating_2'>
                <span>Rate 2 Stars</span>
                </label><input  <?php if (!(strcmp($row_rs_show_rating['rating_value'],"3"))) {echo "checked=\"checked\"";} ?> id='rating_3' name='rating' type='radio' value='3'><label title="Consider renting this property" for='rating_3'>
                  <span>Rate 3 Stars</span>
                  </label><input  <?php if (!(strcmp($row_rs_show_rating['rating_value'],"4"))) {echo "checked=\"checked\"";} ?> id='rating_4' name='rating' type='radio' value='4'><label title="Others recommended renting this property" for='rating_4'>
                    <span>Rate 4 Stars</span>
                    </label><input  <?php if (!(strcmp($row_rs_show_rating['rating_value'],"5"))) {echo "checked=\"checked\"";} ?> id='rating_5' name='rating' type='radio' value='5'><label title="Definately rent this property" for='rating_5'>
                      <span>Rate 5 Stars</span>
        </label></div>
      </fieldset>        
      </div>      
            <div class="fieldlabels" id="moodselector">Describe your mood:</div>
      <div class="cc-selector" id="moodselectors">
      <fieldset onChange="member_feeling()" class="moodies">
        <input <?php if (!(strcmp($row_rs_show_review['feeLing'],"not a happy tenant"))) {echo "checked=\"checked\"";} ?> title="I am not a happy tenant" id="visa" type="radio" name="credit_card" value="not a happy tenant" />
        <label class="drinkcard-cc visa" for="visa"></label>
        <input <?php if (!(strcmp($row_rs_show_review['feeLing'],"a very happy tenant"))) {echo "checked=\"checked\"";} ?> title="I am a very happy tenant" id="mastercard" type="radio" name="credit_card" value="a very happy tenant" />
        <label class="drinkcard-cc mastercard"for="mastercard"></label>
        </fieldset>
    </div>
     <?php if ($totalRows_rs_show_review > 1) { // Show if recordset not empty ?>
  <div class="navcontainer" id="navbar"><div class="prevbtn"><?php if ($pageNum_rs_show_review > 0) { // Show if not first page ?>
    <a title="Go to the previous page" class="masterTooltip" href="<?php printf("%s?pageNum_rs_show_review=%d%s", $currentPage, max(0, $pageNum_rs_show_review - 1), $queryString_rs_show_review); ?>"><img src="images/nav/prev-btn.png" /></a>
    <?php } // Show if not first page ?></div><div class="navtext">Showing review <?php echo ($startRow_rs_show_review + 1) ?> of <?php echo $totalRows_rs_show_review ?></div>
    <div class="netxbtn"><?php if ($pageNum_rs_show_review < $totalPages_rs_show_review) { // Show if not last page ?>
      <a title="Go to the next page" class="masterTooltip" href="<?php printf("%s?pageNum_rs_show_review=%d%s", $currentPage, min($totalPages_rs_show_review, $pageNum_rs_show_review + 1), $queryString_rs_show_review); ?>"><img src="images/nav/next-btn.png" /></a>
      <?php } // Show if not last page ?></div></div>
  <?php } // Show if recordset not empty ?>
      <div class="fieldlabels" id="fieldlabels">Your Experience:</div>
  <div class="formfields" id="experiencedetails">
    <label>
      <textarea wrap="physical" onChange="update_comments()" class="commentbox" name="txt_experience" id="txt_experience" cols="45" rows="5"><?php echo $row_rs_show_review['comments']; ?></textarea>
    </label>
    </div>
<div class="fieldlabels" id="fieldlabels">Review Date:<span class="changepassword">
      <input name="txt_sesseyed" type="hidden" id="txt_sesseyed" value="<?php echo $_GET['claw']; ?>" />
    </span></div>
      <div class="datefield" id="formfields"><?php echo date('d M Y' , strtotime($row_rs_show_review['ratingDate'])); ?>
        <input name="txt_ratingid" type="hidden" id="txt_ratingid" value="<?php echo $row_rs_show_review['ratingid']; ?>" />
      </div>
    <div class="accpetfield" id="accpetfield"> <div class="accepttext">By updating this review, you agree to our <a href="info-centre/terms-of-use.html">Site Terms</a> and confirm that you have read our <a href="info-centre/help-centre.html">Usage Policy,</a> including our <a href="info-centre/fair-review-policy.html">Fair Review Policy.</a></div> </div>
</div>
<input type="hidden" name="MM_insert" value="update" />
</form>
<div class="updated" id="updated">The review was updated</div>
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
data: {"txt_ratingid" : $("#txt_ratingid").val(), "txt_rating" : $("input[name=rating]:checked").val()},
url     : "functions/reviewratingupdater.php",
success : function (txt_rating)
		  {   
		  $("#ratingdiv").removeClass("ratingbox");
          $("#ratingdiv").load(location.href + " #ratingdiv");
		      $("#updated").show();
setTimeout(function() { $("#updated").hide(); }, 3000);			
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
data: {"txt_sesseyed" : $("#txt_sesseyed").val(), "txt_feeling" :$("input[name=credit_card]:checked").val(), "txt_date" : $("#txt_date").val()},
url     : "functions/reviewfeelingupdater.php",
success : function (txt_feeling)
		  {     
		  $("#moodselectors").removeClass("cc-selector");
          $("#moodselectors").load(location.href + " #moodselectors");
		      $("#updated").show();
setTimeout(function() { $("#updated").hide(); }, 3000);
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
    $("#updated").show();
setTimeout(function() { $("#updated").hide(); }, 3000);
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
    $("#updated").show();
setTimeout(function() { $("#updated").hide(); }, 3000);
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
    $("#updated").show();
setTimeout(function() { $("#updated").hide(); }, 3000);
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
    $("#updated").show();
setTimeout(function() { $("#updated").hide(); }, 3000);
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
mysql_free_result($rs_show_review);

mysql_free_result($show_error);

mysql_free_result($rs_property_image);

mysql_free_result($rs_show_rating);
?>
