<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('Connections/killjoy.php');
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

$colname_rs_member_profile = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_member_profile = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_member_profile = sprintf("SELECT g_name, g_email, g_image, user_city as City, DATE_FORMAT(created_date, '%%M %%D, %%Y') as joined_date, g_social AS social, location_sharing, anonymous FROM social_users WHERE g_email = %s AND g_active =1", GetSQLValueString($colname_rs_member_profile, "text"));
$rs_member_profile = mysql_query($query_rs_member_profile, $killjoy) or die(mysql_error());
$row_rs_member_profile = mysql_fetch_assoc($rs_member_profile);
$totalRows_rs_member_profile = mysql_num_rows($rs_member_profile);




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

$colname_rs_profile_image = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_profile_image = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_profile_image = sprintf("SELECT g_image, id AS image_id FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_profile_image, "text"));
$rs_profile_image = mysql_query($query_rs_profile_image, $killjoy) or die(mysql_error());
$row_rs_profile_image = mysql_fetch_assoc($rs_profile_image);
$totalRows_rs_profile_image = mysql_num_rows($rs_profile_image);
$image_id = $row_rs_profile_image['image_id'];?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="kj-autocomplete/lib/jQuery-1.4.4.min.js"></script>
<script type="text/javascript" src="kj-autocomplete/jquery.autocomplete.js"></script>
<link href="kj-autocomplete/jquery.quickfindagency.css" rel="stylesheet" type="text/css" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>Killjoy - view and change your killjoy.co.za profile</title>
<link href="css/member-profile/profile.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/fileupload.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/close.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/toggles.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/tooltips.css" rel="stylesheet" type="text/css" />
</head>
<body onLoad="set_session()">
<form id="register" class="form" name="register" method="POST" action="myprofile.php">
<div class="formcontainer" id="formcontainer"><div class="formheader">Killjoy.co.za Member Profile</div>
<div class="imagebox" id="imagebox"><label title="upload a new profile photo" for="files">
  <?php if ($row_rs_profile_image['g_image'] == "media/profile.png") { // Show if recordset empty ?>
    <img src="media/profile-bg.png" width="50" height="50" />
    <?php } // Show if recordset empty ?>
    <div id="wrapper" class="wrapper">
    <?php if ($row_rs_profile_image['g_image'] != "media/profile.png") { // Show if recordset empty ?>   
    <img src="<?php echo $row_rs_profile_image['g_image']; ?>" alt="killjoy.co.za member profile image" class="profilephoto" /> 
    <span title="remove your profile photo" onClick="unlink_thumb('<?php echo $image_id;?>')" class="close"></span>
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
  <div class="fieldlabels" id="fieldlabels">Your name or screen name:</div>
  <div class="formfields" id="membername">
    <label>
      <input onchange="member_name()" name="g_name" type="text" class="inputfields" id="g_name" value="<?php echo $row_rs_member_profile['g_name']; ?>" />
    </label>
   </div>
    <div class="fieldlabels" id="fieldlabels">Your email:</div>
      <div class="formfields" id="formfields"><input readonly="readonly" name="g_email" type="text" class="emailfield" value="<?php echo $row_rs_member_profile['g_email']; ?>" />       
      <a href="admin/changemail.php">Change</a></div>       
    <div class="fieldlabels" id="fieldlabels">Date Joined:<span class="changepassword">
      <input name="txt_sesseyed" type="hidden" id="txt_sesseyed" value="<?php echo $sessionid ;?>" />
    </span></div>
      <div class="datefield" id="formfields"><?php echo $row_rs_member_profile['joined_date']; ?></div>
      
       <div class="fieldlabels" id="fieldlabels">Privacy settings:</div>      
  <div class="formfields" id="privacy">
   <?php if ($totalRows_rs_member_profile > 0) { // Show if recordset not empty ?>
    <a href="#" id="locationsettings" class="masterTooltip" title="select this option if you do not wish to share your location. We use this information to provide a better experience for users of the killjoy.co.za app." ><span class="toggletext">Share your location:</span>
      <label class="switch"><input <?php if (!(strcmp($row_rs_member_profile['location_sharing'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" onclick="member_location()" name="location" id="location" value="1"><span class="slider round"></span></label></a>
        <div class="locale" id="locale"><label for="password">City/Town</label>
          <textarea name="password" class="city" id="password" autocomplete="new-password"><?php echo $row_rs_member_profile['City']; ?></textarea>
        </div>
    <a href="#" id="privacysettings" class="masterTooltip" title="select this option if you wish to remain anonymoys. None of your personal details will appear on reviews or anywhere else on this site" ><span class="toggletext">Remain Anonymous:</span>
      <label class="switch">
        <input <?php if (!(strcmp($row_rs_member_profile['anonymous'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" onclick="member_privacy()" name="anonymous" id="anonymous" value="1">
        <span class="slider round"></span>
        </label>      
      </a>
      <?php } // Show if recordset not empty ?>
  </div>
  

      <div class="danger" id="danger">Danger Zone</div>
       <?php if ($row_rs_member_profile['social'] == "No") { // Show if not signed in with a social account ?>
    <div class="deactivate" id="changepassword"><a href="admin/change.php">Change password</a></div>
       <?php } //end of social user ?>
   <div class="deactivate" id="deactivate"><a href="admin/deactivate.php">Deactivate Account</a></div>
<div class="accpetfield" id="accpetfield"> <div class="accepttext">By ching your details and settings, you agree to our <a href="info-centre/terms-of-use.html">Site Terms</a> and confirm that you have read our <a href="info-centre/help-centre.html">Usage Policy,</a> including our <a href="info-centre/cookie-policy.php">Cookie Usage Policy.</a></div> </div>
</div>
<input type="hidden" name="MM_insert" value="update" />

</form>
<div class="updated" id="updated">Your profile was updated</div>


<script type="text/javascript">
$(document).ready( function() {
    $("#password").focus( function() {
        if ( $(this).val()=="<?php echo $row_rs_member_profile['City']; ?>") {
            $(this).val('');
        } 
    });
	 });
</script>



<script type="text/javascript">
function acceptimage() {
var data = new FormData();
jQuery.each(jQuery('#files')[0].files, function(i, file) {
data.append('file-'+i, file);
data.append('txt_sesseyed', $("#txt_sesseyed").val());
 }); 
$.ajax({
url: 'admin/profileimageupload.php',
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

<script type="text/javascript">
 function member_location ( location ) 
{ $.ajax( { type    : "POST",
data: $("input[name=location]:checked").serialize(),
url     : "functions/userlocationupdater.php",
success : function (location)
		  {     
		  $('#privacy').removeClass('formfields'); 
		  $('#privacy').load(document.URL +  ' #privacy');  
		   },
		error   : function ( xhr )
		  { alert( "error" );
		  }
		  
} );
 return false;	
}
</script>

<script type="text/javascript">
 function member_privacy ( anonymous ) 
{ $.ajax( { type    : "POST",
data: $("input[name=anonymous]:checked").serialize(),
url     : "functions/userprivacyupdater.php",
success : function (anonymous)
		  {   $('#privacy').removeClass('formfields');   
		    $('#privacy').load(document.URL +  ' #privacy');  
		     },
		error   : function ( xhr )
		  { alert( "error" );
		  }
		  
} );
 return false;	
}
</script>

<script type="text/javascript">
 function member_name( txt_name ) 
{ $.ajax( { type    : "POST",
data:  { "txt_name" : $("#g_name").val(), "MM_insert" : $("#MM_insert").val()},
url     : "functions/usernameupdater.php",
success : function (anonymous)
		  {   $('#membername').removeClass('formfields');   
		    $('#membername').load(document.URL +  ' #membername');  
		     },
		error   : function ( xhr )
		  { alert( "error" );
		  }
		  
} );
 return false;	
}
</script>




<script type="text/javascript">
$(document).ready(function() {
// Tooltip only Text
$('.masterTooltip').hover(function(){
        // Hover over code
        var title = $(this).attr('title');
        $(this).data('tipText', title).removeAttr('title');
        $('<p class="tooltip"></p>')
        .text(title)
        .appendTo('body')
        .fadeIn('slow');
}, function() {
        // Hover out code
        $(this).attr('title', $(this).data('tipText'));
        $('.tooltip').remove();
}).mousemove(function(e) {
        var mousex = e.pageX + 20; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
        $('.tooltip')
        .css({ top: mousey, left: mousex })
});
});
</script>

<script type="text/javascript">
var $j = jQuery.noConflict();
$j(document).ready(function(){
$j("#password").autocomplete("kj-autocomplete/cityfinder.php", {
			 minLength: 10, 
			delay: 500,
selectFirst: true
});
 $j("#password").result(function() {
$j.ajax( { type    : "POST",
data    : { "txt_city" : $("#password").val()}, 
url     : "functions/usercityupdater.php",
  success : function (data)
  { 
  
   $j('#locale').load(document.URL +  ' #locale');  
      $j("#updated").show();
setTimeout(function() { $("#updated").hide(); }, 3000);
  
},
complete: function (data) {
	   $j('#locale').load(document.URL +  ' #locale');
	      $("#updated").show();
setTimeout(function() { $("#updated").hide(); }, 3000);

}
		
});
 return false;
});
 });
 
</script>

</body>
</html>
<?php
mysql_free_result($rs_member_profile);

mysql_free_result($show_error);

mysql_free_result($rs_profile_image);
?>
