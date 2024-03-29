<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('Connections/killjoy.php'); 
if (isset($_SESSION['kj_username'])) {
	
	$social_user = $_SESSION['kj_username'];
	$review_complete_url = "reviewsessioncomplete.php";
	
	
} else {
	
	$social_user = "Anonymous";
	$review_complete_url = "reviewcomplete.php";
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $theValue) : ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $theValue) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

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

	
$moodvalue = NULL;
if (isset($_COOKIE['mood'])) {
$moodvalue = $_COOKIE['mood'];
}

$expervalue = NULL;
if (isset($_COOKIE['experience'])) {
$expervalue  = $_COOKIE['experience'];
}

$hasrated = NULL;
if (isset($_COOKIE['hasrated'])) {
$hasrated  = $_COOKIE['hasrated'];
}
$colname_rs_showproperty = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_showproperty = $_SESSION['kj_propsession'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_showproperty = sprintf("SELECT *, IFNULL(tbl_propertyimages.image_url, 'media/image-add-512.png') AS propertyImage FROM tbl_address LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid WHERE tbl_address.sessionid = %s", GetSQLValueString($colname_rs_showproperty, "text"));
$rs_showproperty = mysqli_query( $killjoy, $query_rs_showproperty) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_showproperty = mysqli_fetch_assoc($rs_showproperty);
$totalRows_rs_showproperty = mysqli_num_rows($rs_showproperty);

$colname_rs_check_index = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_check_index = $_SESSION['kj_propsession'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_check_index = sprintf("SELECT sessionid FROM tbl_addressindex WHERE sessionid = %s", GetSQLValueString($colname_rs_check_index, "text"));
$rs_check_index = mysqli_query( $killjoy, $query_rs_check_index) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_check_index = mysqli_fetch_assoc($rs_check_index);
$totalRows_rs_check_index = mysqli_num_rows($rs_check_index);



$colname_rs_property_image = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_rs_property_image = $_SESSION['kj_propsession'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_property_image = sprintf("SELECT image_url, id FROM tbl_propertyimages WHERE sessionid = %s", GetSQLValueString($colname_rs_property_image, "text"));
$rs_property_image = mysqli_query( $killjoy, $query_rs_property_image) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_property_image = mysqli_fetch_assoc($rs_property_image);
$totalRows_rs_property_image = mysqli_num_rows($rs_property_image);
$id = $row_rs_property_image['id'];

$colname_show_error = "-1";
if (isset($_SESSION['kj_propsession'])) {
  $colname_show_error = $_SESSION['kj_propsession'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_show_error = sprintf("SELECT error_message FROM tbl_uploaderror WHERE sessionid = %s ORDER BY error_time DESC LIMIT 1", GetSQLValueString($colname_show_error, "text"));
$show_error = mysqli_query( $killjoy, $query_show_error) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_show_error = mysqli_fetch_assoc($show_error);
$totalRows_show_error = mysqli_num_rows($show_error);

$colname_rs_social_user = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_social_user = $_SESSION['kj_username'];
}
mysqli_select_db( $killjoy, $database_killjoy);
$query_rs_social_user = sprintf("SELECT g_name, g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_social_user, "text"));
$rs_social_user = mysqli_query( $killjoy, $query_rs_social_user) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_rs_social_user = mysqli_fetch_assoc($rs_social_user);
$totalRows_rs_social_user = mysqli_num_rows($rs_social_user);

$address = $row_rs_showproperty['str_number']." ".$row_rs_showproperty['street_name']." ".$row_rs_showproperty['city']." ".$row_rs_showproperty['province']." ".$row_rs_showproperty['postal_code'];


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addressField")) {
		
		if(empty($_POST['rating'])) {		
		setcookie("hasrated", "no");
		setcookie("mood", $_POST['credit-card']);
		setcookie("experience", $_POST['txt_comments']);
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit;
		
		
	}

 
 }



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addressField")) {

  $insertSQL = sprintf("INSERT INTO tbl_address_comments (social_user, sessionid, rating_comments, rating_feeling) VALUES (%s, %s, %s, %s)",
  			            GetSQLValueString($social_user, "text"),
						GetSQLValueString($_SESSION['kj_propsession'], "text"),
                        GetSQLValueString($_POST['txt_comments'], "text"),
					    GetSQLValueString($_POST['credit-card'], "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
   $commentid = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
   
     $insertSQL = sprintf("INSERT INTO tbl_approved (sessionid, address_comment_id) VALUES (%s, %s)",
                       GetSQLValueString($_SESSION['kj_propsession'], "text"),GetSQLValueString($commentid, "int"));
                                              

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
  $approveid = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
  
  
    $insertSQL = sprintf("INSERT INTO tbl_address_rating (address_comment_id, social_user, sessionid, rating_value) VALUES (%s, %s, %s, %s)",
	                     GetSQLValueString($commentid, "int"),
						GetSQLValueString($social_user, "text"),
						GetSQLValueString($_SESSION['kj_propsession'], "text"),  
                        GetSQLValueString($_POST['rating'], "int"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  $ratingid = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
 
  
  
  mysqli_select_db( $killjoy, $database_killjoy);
$query_get_rating_date = "SELECT DATE_FORMAT(rating_date, '%Y-%m-%d&nbsp;%H:%i:%s') AS rating_date FROM tbl_address_comments WHERE id = '$commentid'";
$get_rating_date = mysqli_query( $killjoy, $query_get_rating_date) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_get_rating_date = mysqli_fetch_assoc($get_rating_date);
$totalRows_get_rating_date = mysqli_num_rows($get_rating_date);

mysqli_select_db( $killjoy, $database_killjoy);
$query_get_rating_comments = "SELECT rating_comments FROM tbl_address_comments WHERE id = '$commentid'";
$get_rating_comments = mysqli_query( $killjoy, $query_get_rating_comments) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_get_rating_comments = mysqli_fetch_assoc($get_rating_comments);
$totalRows_get_rating_comments = mysqli_num_rows($get_rating_comments);
  
  if(!$totalRows_rs_check_index) {
  
   $insertSQL = sprintf("INSERT INTO tbl_addressindex (sessionid, address) VALUES (%s, %s)",
  			            GetSQLValueString($_SESSION['kj_propsession'], "text"),
					    GetSQLValueString($address, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));  

  
  }
  


require('phpmailer-master/class.phpmailer.php');
include('phpmailer-master/class.smtp.php');

date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');

if (isset($_SESSION['kj_username'])) { 
$name = $row_rs_social_user['g_name'];
$email = $row_rs_social_user['g_email'];
$email_1 = "friends@killjoy.co.za";
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = "killjoy.co.za";
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Username = "friends@killjoy.co.za";
$mail->Password = "806Ppe##44VX";
$mail->Port = "465";
$mail->SetFrom('friends@killjoy.co.za', 'Killjoy Community');
$mail->AddReplyTo("friends@killjoy.co.za","Killjoy Community");
$message = "<html><head><style type='text/css'>
a:link {
text-decoration: none;
}
a:visited {
text-decoration: none;
}
a:hover {
text-decoration: none;
}
a:active {
text-decoration: none;
}
body,td,th {
font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
font-size: 20px;
}
body {
background-repeat: no-repeat;
margin-left:50px;
}
</style></head><body>Dear ". $name ."<br><br>Thank you for making South Africa a better place!<br><br>Your review of <strong>".$row_rs_showproperty['str_number']."&nbsp;".$row_rs_showproperty['street_name']."&nbsp;".$row_rs_showproperty['city']."</strong> has been recorded and your reference number is: &nbsp;<strong><font color='#0000FF'><strong>".$_SESSION['kj_propsession']."</strong></font></strong><br><br>Please note that your review is under assessment from one of our editors and will be published as soon as the editor approves of the the content in your review. All reviews are subjected to the Terms and Conditions as stipulated by our <a href='info-centre/fair-review-policy.html'>Fair Review Policy</a>.<br><br>The rental property review was submitted by: <a href='mailto:$email'>$email</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br>Thank
you, the Killjoy Community: <a href='https://www.killjoy.co.za'>https://www.killjoy.co.za</a><br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html></body></html>";
$mail->Subject = "Review Completed";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$body = "$message\r\n";
$body = wordwrap($body, 70, "\r\n");
$mail->MsgHTML($body);
$address = $email;
$mail->AddAddress($address, "Killjoy");
if(!$mail->Send()) {
echo "Mailer Error: " . $mail->ErrorInfo;
}

$newsubject = $mail->Subject;
$comments = $mail->msgHTML($body);
  $insertSQL = sprintf("INSERT INTO user_messages (u_email, u_sunject, u_message) VALUES (%s, %s, %s)",
                       GetSQLValueString($email, "text"),
					   GetSQLValueString($newsubject , "text"),
                       GetSQLValueString($comments, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));

}
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = "killjoy.co.za";
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Username = "friends@killjoy.co.za";
$mail->Password = "806Ppe##44VX";
$mail->Port = "465";
$mail->SetFrom('friends@killjoy.co.za', 'Killjoy Community');
$mail->AddReplyTo("friends@killjoy.co.za","Killjoy Community");
$message = "<!DOCTYPE html><html><head><style type='text/css'>
a:link {
text-decoration: none;
}
a:visited {
text-decoration: none;
}
a:hover {
text-decoration: none;
}
a:active {
text-decoration: none;
}
body,td,th {
font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
font-size: 20px;
}
body {
background-repeat: no-repeat;
margin-left:50px;
}
#imagepreview {
	height: 180px;
	width: 180px;
	max-width: 180px;
	margin-top: 10px;
	margin-bottom: 10px;
	margin-left: 50px;
	border: thin solid #00F;
	border-radius:5px;
}

.approve {
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
	color: #FFF;
	height: 40px;
	width: 150px;
	text-align: center;
	vertical-align: middle;
	background-color: #0F0;
	padding-top: 5px;
	padding-bottom: 5px;
	cursor: pointer;
	font-size: 1.25em;
	display: inline-block;
	border-radius:4px
}
.reject {
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
	color: #FFF;
	height: 40px;
	width: 150px;
	text-align: center;
	vertical-align: middle;
	background-color: #00F;
	padding-top: 5px;
	padding-bottom: 5px;
	font-size: 1.25em;
	display: inline-block;
	border-radius:4px
}
.delete {
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
	color: #FFF;
	height: 40px;
	width: 150px;
	text-align: center;
	vertical-align: middle;
	background-color: #F00;
	padding-top: 5px;
	padding-bottom: 5px;
	font-size: 1.25em;
	display: inline-block;
	border-radius:4px
}
.mailtbl {
	width: 260px;
	cursor: pointer;
	position: relative;
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
	font-size: 1.15px;
	line-height: 1.25px;
	text-align:justify;
}


</style></head><body>Dear Killjoy Admin<br><br>Please assess the following review for <strong>".$row_rs_showproperty['str_number']."&nbsp;".$row_rs_showproperty['street_name']."&nbsp;".$row_rs_showproperty['city'].".</strong> The reference number is: &nbsp;<strong><font color='#0000FF'><strong>".$_SESSION['kj_propsession']."</strong></font></strong><br><br>All reviews are subjected to the Terms and Conditions as stipulated by our <a href='info-centre/fair-review-policy.html'>Fair Review Policy</a>.<br><br>The rental property review was submitted by: <a href='mailto:".$social_user."'>$email</a> on $date at $time<br><br><a href='https://www.killjoy.co.za/".$row_rs_showproperty['propertyImage']."'><img width='200' height='120' id='imagepreview' name='imagepreview' src='https://www.killjoy.co.za/".$row_rs_showproperty['propertyImage']."' class='imagepreview' alt='rental property review image'></a><br><br><table class='mailtbl' border='0' cellspacing='3' cellpadding='3'>
  <tr>
    <td>The tenant's experience:<br>".utf8_encode($row_get_rating_comments['rating_comments'])."</td>
  </tr>
</table><br>
<a class='approve' id='approve' href='https://www.killjoy.co.za/admin/assessreview.php?approvebtn=approve&sessionid=".$commentid."&checkedby=friends@killjoy.co.za&listing=".$approveid."&ratingdate=".utf8_encode($row_get_rating_date['rating_date'])."'>&nbsp;&nbsp;&nbsp;Approve&nbsp;&nbsp;&nbsp;</a><br><br>
<a class='reject' id='reject' href='https://www.killjoy.co.za/admin/assessreview.php?declinebtn=declined&sessionid=".$commentid."&checkedby=friends@killjoy.co.za&listing=".$approveid."&ratingdate= ".utf8_encode($row_get_rating_date['rating_date'])."'>&nbsp;&nbsp&nbsp;&nbsp;Reject&nbsp;&nbsp&nbsp;&nbsp;</a><br><br>
<a class='delete' id='reject' href='https://www.killjoy.co.za/admin/deletereview.php?deletebrn=delete&sessionid=".$commentid."&checkedby=friends@killjoy.co.za&listing=".$approveid."&ratingdate= ".utf8_encode($row_get_rating_date['rating_date'])."'>&nbsp;&nbsp&nbsp;&nbsp;Delete&nbsp;&nbsp&nbsp;&nbsp;</a>
</body></html>";
$mail->Subject = "Killjoy Assess Review";
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$body = "$message\r\n";
$body = wordwrap($body, 70, "\r\n");
$mail->MsgHTML($body);
$address = "friends@killjoy.co.za";
$mail->AddAddress($address, "Killjoy");
if(!$mail->Send()) {
echo "Mailer Error: " . $mail->ErrorInfo;
}
  
        setcookie("comment_id", $commentid, time()+60*60*24*30 ,'/');
		setcookie("rating_id", $ratingid, time()+60*60*24*30 ,'/');
        setcookie('mood', '', time()-1000);
        setcookie('experience', '', time()-1000);
		setcookie('hasrated', '', time()-1000);
        header('Location: ' . $review_complete_url);
}
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
<link rel="canonical" href="https://www.killjoy.co.za/review.php">
<title>killjoy - property review page</title>
<link href="css/review-two/desktop.css" rel="stylesheet" type="text/css" />
<link href="css/review-two/profile.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/member-profile/close.css" rel="stylesheet" type="text/css" />
<link href="css/review-two/fileupload.css" rel="stylesheet" type="text/css" />
<link href="css/review-two/rating_selection.css" rel="stylesheet" type="text/css" />
<link href="css/review-two/checks.css" rel="stylesheet" type="text/css">
<link href="css/review-two/tooltips.css" rel="stylesheet" type="text/css" />
<link href="css/review-two/radios.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css" />
<link href="css/emailtbls.css" rel="stylesheet" type="text/css" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113531379-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113531379-1');
</script>
</head>
<body>

<div id="locationField" class="reviewcontainer">
    <form  action="reviewersteptwo.php" method="POST" name=addressField class="reviewform">    
    <div class="formheader"><?php echo $row_rs_showproperty['str_number']; ?>&nbsp;<?php echo $row_rs_showproperty['street_name']; ?></div>
    <div class="stepfields" id="stepone">Property Preview</div>   
    <div class="fieldlabels" id="fieldlabels">Add or change the photo for the property</div>
<div  class="imagebox" id="imagebox"><label for="files">
   <?php if ($totalRows_rs_property_image == 0) { // Show if recordset not empty ?>
   <img title="click me to add a photo for this property" class="addhouse" width="100" height="100" src="media/image-add-512.png" />
    <?php } // Show if recordset empty ?>
      </label>
         <div id="wrapper" class="wrapper">
           <?php if ($totalRows_rs_property_image > 0) { // Show if recordset not empty ?>
<img src="<?php echo $row_rs_property_image['image_url']; ?>" alt="killjoy.co.za rental property image" class="propertyphoto"/> 
    <span title="remove this image" onClick="unlink_thumb('<?php echo $id;?>')" class="close"></span>
      <?php } // Show if recordset empty ?>     
    </div>
    <div class="logoloaderrors" id="logoloaderror"><?php if ($totalRows_show_error > 0) { // Show if recordset empty ?><ol>
<?php do { ?><li><?php echo $row_show_error['error_message']; ?><?php } while ($row_show_error = mysqli_fetch_assoc($show_error)); ?></li>
</ol>
<?php } ?>
</div>
	</div>
		<input onChange="return acceptimage()"  id="files" name="files[]" type="file" accept="image/x-png,image/gif,image/jpeg" />
<div id="uploader" class="uploader"><img src="images/loading24x24.gif" width="24" height="24" alt="killjoy.co.za member profile image upload status indicator" class="indicator" />Uploading</div>
  <input name="property_id" id="property_id" type="hidden" value="<?php echo $row_rs_showproperty['address_id']; ?>" />
  <div class="stepfields" id="stepone">Rate</div> 
  <div class="fieldlabels" id="fieldlabels">Rate the rental property:</div>
   <div class="ratingbox" id="ratingdiv">
          <label for="owleyes"></label>
      <label>
        <input name="click_count" type="hidden" id="click_count" value="1" />
      </label>
      <fieldset class="fieldset" onClick="rating_score()" id="button">
        <div class='rating_selection'>
          <input  checked id='rating_0' name='rating' type='radio' value='0'><label for='rating_0'>
            <span>Unrated</span>
            </label><input id='rating_1' name='rating' type='radio' value='1'><label title="Do not rent this property" for='rating_1'>
              <span>Rate 1 Star</span>
              </label><input id='rating_2' name='rating' type='radio' value='2'><label title="Rent this property with caution" for='rating_2'>
                <span>Rate 2 Stars</span>
                </label><input id='rating_3' name='rating' type='radio' value='3'><label title="Consider renting this property" for='rating_3'>
                  <span>Rate 3 Stars</span>
                  </label><input id='rating_4' name='rating' type='radio' value='4'><label title="Others recommended renting this property" for='rating_4'>
                    <span>Rate 4 Stars</span>
                    </label><input id='rating_5' name='rating' type='radio' value='5'><label title="Definately rent this property" for='rating_5'>
                      <span>Rate 5 Stars</span>
        </label></div>
      </fieldset>        
      </div>
   <?php if ($hasrated != NULL) { // Show if recordset empty ?>
  <div class="norating" id="norating">Please rate this property</div>
  <?php } // Show if recordset empty ?>

      <div class="fieldlabels" id="fieldlabels">Describe your mood:</div>
      <div class="cc-selector">
      <span id="spryradio1">
        <input <?php if (!(strcmp($moodvalue,"not a happy tenant"))) {echo "checked=\"checked\"";} ?> title="I am a very happy tenant" id="visa" type="radio" name="credit-card" value="a very happy tenant" />
        <label class="drinkcard-cc visa" for="visa"></label>
        <input <?php if (!(strcmp($moodvalue,"a very happy tenant"))) {echo "checked=\"checked\"";} ?>  title="I am not a happy tenant" id="mastercard" type="radio" name="credit-card" value="not a happy tenant" />
        <label class="drinkcard-cc mastercard"for="mastercard"></label>
      <span class="radioRequiredMsg">Choose your mood.</span></span>
    </div>
      <div class="stepfields" id="stepone">Comment</div> 
       <div class="fieldlabels" id="fieldlabels">Share your experience:</div>
  <div class="formfields" id="commentbox"><span id="sprytextarea1">
    <textarea name="txt_comments" placeholder="tell future tenants what it was like to live at this property. Use as many words as you like." cols="" rows="" wrap="physical" class="commentbox"><?php echo $expervalue  ?></textarea>
    <span class="textareaRequiredMsg">Share your experience</span></span></div>
      <div class="accpetfield" id="accpetfield"> <div class="accepttext">By clicking Finish, you agree to our <a href="info-centre/terms-of-use.html">Site Terms</a> and confirm that you have read our <a href="info-centre/help-centre.html">Usage Policy,</a> including the <a href="info-centre/fair-review-policy.html">Fair Review Policy.</a> You also agree that you are in no ways affiliated with this property by either means of being a landlord or letting agency.</div> </div>
     <button class="nextbutton">Finish <span class="icon-checkbox-checked"></span></button> 
 <input type="hidden" name="MM_insert" value="addressField">
  <input type="hidden" name="txt_sessionid" id="txt_sessionid" value="<?php echo $row_rs_showproperty['sessionid']; ?>" />   
  </form>    
</div>   
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
function unlink_thumb ( id ) 
{ $.ajax( { type    : "POST",
async   : false,
data    : { "id" : id }, 
url     : "admin/removepropertyimage.php",
success : function ( id )
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
    $("#txt_comments").autogrow();
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
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1");
</script>

</body>
<?php
((mysqli_free_result($rs_showproperty) || (is_object($rs_showproperty) && (get_class($rs_showproperty) == "mysqli_result"))) ? true : false);

((mysqli_free_result($rs_property_image) || (is_object($rs_property_image) && (get_class($rs_property_image) == "mysqli_result"))) ? true : false);

((mysqli_free_result($show_error) || (is_object($show_error) && (get_class($show_error) == "mysqli_result"))) ? true : false);

((mysqli_free_result($rs_social_user) || (is_object($rs_social_user) && (get_class($rs_social_user) == "mysqli_result"))) ? true : false);

((mysqli_free_result($rs_check_index) || (is_object($rs_check_index) && (get_class($rs_check_index) == "mysqli_result"))) ? true : false);
?>
