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
$query_rs_member_profile = sprintf("SELECT g_name, g_email, g_image, user_city as City, DATE_FORMAT(created_date, '%%M %%D, %%Y') as joined_date, g_social AS social, approx_location as approx, location_sharing, anonymous FROM social_users WHERE g_email = %s AND g_active =1", GetSQLValueString($colname_rs_member_profile, "text"));
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

if (isset($_SESSION['sessionid'])) {
$sessionid = $_SESSION['sessionid'];
} else { $sessionid = generateRandomString();
		
	   }

	

$colname_show_error = "-1";
if (isset($_SESSION['sessionid'])) {
  $colname_show_error = $_SESSION['sessionid'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_show_error = sprintf("SELECT error_message FROM tbl_uploaderror WHERE sessionid = %s ORDER BY error_time ASC LIMIT 1", GetSQLValueString($colname_show_error, "text"));
$show_error = mysql_query($query_show_error, $killjoy) or die(mysql_error());
$row_show_error = mysql_fetch_assoc($show_error);
$totalRows_show_error = mysql_num_rows($show_error);

$colname_rs_profile_image = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_profile_image = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_profile_image = sprintf("SELECT g_image, id AS id FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_profile_image, "text"));
$rs_profile_image = mysql_query($query_rs_profile_image, $killjoy) or die(mysql_error());
$row_rs_profile_image = mysql_fetch_assoc($rs_profile_image);
$totalRows_rs_profile_image = mysql_num_rows($rs_profile_image);
$id = $row_rs_profile_image['id'];?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="kj-autocomplete/lib/jQuery-1.4.4.min.js"></script>
<script type="text/javascript" src="kj-autocomplete/jquery.autocomplete.js"></script>
<link href="kj-autocomplete/jquery.quickfindagency.css" rel="stylesheet" type="text/css" />
<meta http-equiv="content-language" content="en-ZA">
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>Killjoy - view and change your killjoy.co.za profile</title>
<link href="css/member-profile/profile.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/fileupload.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/close.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/toggles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
"<link href="../jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.dialog.min.css" rel="stylesheet" type="text/css">
<link href="../SpryAssets/jquery.ui.resizable.min.css" rel="stylesheet" type="text/css">
<link href="css/dialog-styling.css" rel="stylesheet" type="text/css" />
<script src="../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<script src="../SpryAssets/jquery.ui-1.10.4.dialog.min.js"></script>

</head>
<body onLoad="set_session()">
<div data-role="page" id="page"></div>

<div class="membersprofile" id="membersprofile">
<div class="maincontainer" id="maincontainer">
<form autocomplete="off" id="register" class="form" name="register" method="POST" action="myprofile.php">
  <input autocomplete="false" name="hidden" type="text" style="display:none;">
<div class="imagebox" id="imagebox"><label title="upload a new profile photo" for="files">
  <?php if ($row_rs_profile_image['g_image'] == "media/profile.png") { // Show if recordset empty ?>
    <img src="media/profile-bg.png" width="100" height="100" />
    <?php } // Show if recordset empty ?>
      </label>
    <div id="wrapper" class="wrapper">
    <?php if ($row_rs_profile_image['g_image'] != "media/profile.png") { // Show if recordset empty ?>   
    <img src="<?php echo $row_rs_profile_image['g_image']; ?>" alt="killjoy.co.za member profile image" class="profilephoto" /> 
    <span title="remove your profile photo" onClick="unlink_thumb('<?php echo $id;?>')" class="close"></span>
      <?php } // Show if recordset empty ?>     
    </div>
	</div>
<input onChange="return acceptimage()"  id="files" name="files[]" type="file" accept="image/x-png,image/gif,image/jpeg" />
<div id="uploader" class="uploader"><img src="images/loading24x24.gif" width="24" height="24" alt="killjoy.co.za member profile image upload status indicator" class="indicator" />Uploading</div>
  <div class="fieldlabels" id="fieldlabels">Your name or screen name:</div>
  <div class="formfields" id="membername">
    <label>
      <input onchange="member_name()" name="g_name" type="text" class="inputfields" id="g_name" value="<?php echo $row_rs_member_profile['g_name']; ?>" />
    </label>
   </div>
    <div class="fieldlabels" id="fieldlabels">Your email:</div>
      <div class="formfields" id="formfields"><input readonly="readonly" name="g_email" type="text" class="emailfield" value="<?php echo $row_rs_member_profile['g_email']; ?>" /><div class="editemail" id="editemail"><a href="admin/changemail.php"><span class="icon-pencil"></span></a></div></div>       
    <div class="fieldlabels" id="fieldlabels">Date Joined:<span class="changepassword">
      <input name="txt_sesseyed" type="hidden" id="txt_sesseyed" value="<?php echo $sessionid ;?>" />
    </span></div>
      <div class="datefield" id="formfields"><?php echo $row_rs_member_profile['joined_date']; ?></div>
      
   <div class="fieldlabels" id="fieldlabels"><span class="icon-lock"></span> Privacy settings:</div>      
  <div class="privacycontainer" id="privacy">
   <?php if ($totalRows_rs_member_profile > 0) { // Show if recordset not empty ?>
    <a href="#" id="locationsettings" title="select this option if you do not wish to share your location. We use this information to provide a better experience for users of the killjoy.co.za app." ><span class="toggletext">Location:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <label class="switch"><input <?php if (!(strcmp($row_rs_member_profile['location_sharing'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" onclick="member_location()" name="location" id="location" value="1"><div class="slider round"><!--ADDED HTML --><span class="on">ON</span><span class="off">OFF</span><!--END--></div></label></a>
        <div class="locale" id="locale">
          <textarea name="password" class="city" id="password" autocomplete="new-password"><?php echo $row_rs_member_profile['City']; ?></textarea>
          <div class="approx" id="approx"><?php if ($row_rs_member_profile['City'] == "Undefined") { ?>Approximate: <?php echo $row_rs_member_profile['approx']; ?><?php } ?></div>
        </div>
   <span class="toggletext">Anonymous:</span>
      <label class="switch"><input <?php if (!(strcmp($row_rs_member_profile['anonymous'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" onclick="member_privacy()" name="anonymous" id="anonymous" value="1"><div class="slider round"><!--ADDED HTML --><span class="on">ON</span><span class="off">OFF</span><!--END--></div></label>      
      
      <?php } // Show if recordset not empty ?>
  </div>
      <div class="danger" id="danger"><span class="icon-exclamation-triangle"></span> Danger Zone </div>
       <?php if ($row_rs_member_profile['social'] == "No") { // Show if not signed in with a social account ?>
    <div class="deactivate" id="changepassword"><a target="_parent" href="admin/change.php">Change password <span class="icon-exclamation-circle"></span></a> </div>
       <?php } //end of social user ?>
   <div class="deactivate" id="deactivate"><a target="_parent" href="admin/deactivate.php">Deactivate Account <span class="icon-exclamation-circle"></span></a></div>
<div class="accpetfield" id="accpetfield"> <div class="accepttext">By updating your details and settings, you agree to our <a href="info-centre/terms-of-use.html">Site Terms</a> and confirm that you have read our <a href="info-centre/help-centre.html">Usage Policy,</a> including our <a href="info-centre/cookie-policy.php">Cookie Usage Policy.</a></div> </div>
<input type="hidden" name="MM_insert" value="update" />
</form>
<div class="updated" id="updated">Your profile was updated <span class="icon-check"></span></div>
<div class="uploaded" id="uploaded"><span class="icon-camera"></span>
<?php echo $row_show_error['error_message']; ?>
</div>
</div>


<script type="text/javascript">
	 var elem = $("#membersprofile");
	$("#membersprofile").dialog({ closeText: '' });
     elem.dialog({
     resizable: false,
	 autoOpen: false,
     title: ' <?php echo $row_rs_member_profile['g_name']; ?>\'s profile',
	 draggable: false,
    });     // end dialog
     elem.dialog('open');
	$('#membersprofile').bind('dialogclose', function(event) {
     window.location = "index.php";
 });
	
	</script>

<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
</script>

	
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
  $('#imagebox').load(document.URL +  ' #imagebox');
	$("#uploaded").show();
	setTimeout(function() { $("#uploaded").hide(); }, 3000);	  
			
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
function unlink_thumb ( id ) 
{ $.ajax( { type    : "POST",
async   : false,
data    : { "id" : id }, 
url     : "admin/removeprofileimage.php",
success : function ( id )
{  $('#imagebox').load(document.URL +  ' #imagebox');
 $("#uploaded").show();
	setTimeout(function() { $("#uploaded").hide(); }, 3000);	
						   
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
 function member_privacy ( anonymous ) 
{ $.ajax( { type    : "POST",
data: $("input[name=anonymous]:checked").serialize(),
url     : "functions/userprivacyupdater.php",
success : function (anonymous)
		  {   $('#privacy').removeClass('formfields');   
		    $('#privacy').load(document.URL +  ' #privacy');  
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
 function member_name( txt_name ) 
{ $.ajax( { type    : "POST",
data:  { "txt_name" : $("#g_name").val(), "MM_insert" : $("#MM_insert").val()},
url     : "functions/usernameupdater.php",
success : function (anonymous)
		  {   $('#membername').removeClass('formfields');   
		    $('#membername').load(document.URL +  ' #membername');  
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

