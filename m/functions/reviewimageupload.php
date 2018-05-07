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

$colname_rs_social_user = "-1";
if (isset($_SESSION['kj_username'])) {
  $colname_rs_social_user = $_SESSION['kj_username'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_social_user = sprintf("SELECT g_name, g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_social_user, "text"));
$rs_social_user = mysql_query($query_rs_social_user, $killjoy) or die(mysql_error());
$row_rs_social_user = mysql_fetch_assoc($rs_social_user);
$totalRows_rs_social_user = mysql_num_rows($rs_social_user);

$colname_get_address = "-1";
if (isset($_SESSION['sessionid'])) {
  $colname_get_address = $_SESSION['sessionid'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_get_address = sprintf("SELECT * FROM tbl_address WHERE sessionid = %s", GetSQLValueString($colname_get_address, "text"));
$get_address = mysql_query($query_get_address, $killjoy) or die(mysql_error());
$row_get_address = mysql_fetch_assoc($get_address);
$totalRows_get_address = mysql_num_rows($get_address);

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');
$name = $row_rs_social_user['g_name'];
$email = $row_rs_social_user['g_email'];
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
</style></head><body>Dear ". $name ."<br><br>You have made changes to a property review.</strong> <br><br>Your changes to the review of <strong>".$row_get_address['str_number']."&nbsp;".$row_get_address['street_name']."&nbsp;".$row_get_address['city']."</strong> has been recorded and your reference number is: &nbsp;<strong><font color='#0000FF'><strong>".$_SESSION['sessionid']."</strong></font></strong><br><br>Please note that your review is under assessment from one of our editors and will be published as soon as the editor approves of the the content in your review. All reviews are subjected to the Terms and Conditions as stipulated by our <a href='info-centre/fair-review-policy.html'>Fair Review Policy</a>.<br><br>The rental property review change was submitted by: <a href='mailto:$email'>$email</a> on $date at $time<br><br>If this was not you, please let us know by sending an email to: <a href='mailto:friends@killjoy.co.za'>Killjoy</a><br><br>Thank
you, the Killjoy Community: <a href='https://www.killjoy.co.za'>https://www.killjoy.co.za</a><br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:friends@killjoy.co.za'>Killjoy</a></font><br><br></body></html>";


if (isset($_SESSION['sessionid'])) {
$sessionid = $_SESSION['sessionid'];
}

$path = "../../media/properties/";
chdir ($path);
if (!file_exists($sessionid) && !is_dir($sessionid)) {
mkdir ($sessionid,0777,true);
} 
$errors = array();
$uploadedFiles = array();
$extension = array("gif", "jpeg", "jpg", "png","GIF","JPEG","JPG","PNG");
$totalBytes = 2097152;
$UploadFolder = "$sessionid/";
$dir = $UploadFolder;
$fi = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
$fileCount = iterator_count($fi);
$counter = 0;
foreach($_FILES as $eachFile)
{   if($eachFile['size'] > 0); 
$temp = $eachFile["tmp_name"];	                 
$name = $eachFile["name"];
if(empty($temp))
{
break;
}
$counter++;
$UploadOk = true;
if($eachFile["size"] > $totalBytes)
{
$UploadOk = false;
array_push($errors, "<span style='color: #FE8374'><span class='icon-user-times'></span> ".$name."  is larger than 2Mb.</span>");
}				
$ext = pathinfo($name, PATHINFO_EXTENSION);
if(in_array($ext, $extension) == false){
$UploadOk = false;
array_push($errors, "<span style='color: #FE8374'><span class='icon-user-times'></span> ".$name." is an invalid file type.");
}
if(file_exists($UploadFolder."/".$name) == true){
$UploadOk = false;
array_push($errors, $name."<span style='color: #FE8374'><span class='icon-user-times'></span> ".$name." file exists.");
}

if($UploadOk == true){
move_uploaded_file($temp,$UploadFolder."/".$name);						
array_push($uploadedFiles, $name);						
$successmsg = "<span style='color: #2ab934; font-wight:bolder;'><span class='icon-camera'></span> your image was uploaded</span>";


  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($deleteSQL, $killjoy) or die(mysql_error());


foreach($uploadedFiles as $fileName);

  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($deleteSQL, $killjoy) or die(mysql_error());


  $insertSQL = sprintf("INSERT INTO tbl_uploaderror (sessionid, error_message) VALUES (%s, %s)",
                       GetSQLValueString($sessionid, "text"),
                       GetSQLValueString($successmsg, "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
}
}
if($counter>0){
	
if(count($errors)>0)
{
  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($deleteSQL, $killjoy) or die(mysql_error());
  
foreach($errors as $error)
{
  $insertSQL = sprintf("INSERT INTO tbl_uploaderror (sessionid, error_message) VALUES (%s, %s)",
                       GetSQLValueString($sessionid, "text"),
                       GetSQLValueString($error, "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());						
 }
 }
if(count($uploadedFiles)>0){
	
foreach($uploadedFiles as $fileName)	  
{			
 $file = $path.$UploadFolder.$fileName;	
 $newfile = str_replace("../", "", "$file");
list($width, $height) = getimagesize("$path$UploadFolder$fileName");
$file_width=$width;
$file_height=$height;	
$file_size = filesize("$path$UploadFolder$fileName"); // Get file size in bytes
$file_size = $file_size / 1024; 							
$query=sprintf("INSERT INTO tbl_propertyimages(image_url, sessionid, social_user, img_width, img_height, img_size) VALUES (%s, %s, %s, %s, %s, %s)",
GetSQLValueString($newfile, "text"),
GetSQLValueString($sessionid, "text"),
GetSQLValueString($_SESSION['kj_username'], "text"),
GetSQLValueString($file_width, "int"),			
GetSQLValueString($file_height, "int"),		
GetSQLValueString($file_size, "int"));	

 mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($query, $killjoy) or die(mysql_error());
  
$updateSQL = sprintf("UPDATE tbl_approved SET was_checked=%s, checked_by=%s, is_approved=%s WHERE address_comment_id=%s",
                       GetSQLValueString(0, "int"),
					   GetSQLValueString('', "text"),
					   GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['txt_ratingid'], "int"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($updateSQL, $killjoy) or die(mysql_error());
   
  

 }
 
 if ($Result1) {
date_default_timezone_set('Africa/Johannesburg');
$date = date('d-m-Y H:i:s');
$time = new DateTime($date);
$date = $time->format('d-m-Y');
$time = $time->format('H:i:s');

$mail->Subject    = "Killjoy Review Updated";
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

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());
 
	 
 }
	
}								
}
else{
echo "Please, Select file(s) to upload.";
}
?>
<html>
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<link rel="canonical" href="https://www.rentaguide.co.za/admin/events.php">
<title>Add a new rental property offer - properties for rent - properties to let - rental property listings - houses and flats for rent</title>
<meta name="keywords" content="rental, property, offer, listing, advertise, houses, flats, apartments, letting, tolet, owner, agency, rent, stay, share " />
<meta name="description" content="add a property rental offer for your house, flat or apartment. find a suitable tenant in your town or city." />
</head>
<body>

</body>
</html>

<?php
mysql_free_result($get_address);

mysql_free_result($rs_social_user);
?>

