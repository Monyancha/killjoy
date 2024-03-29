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

if (isset($_SESSION['sessionid'])) {
$sessionid = $_SESSION['sessionid'];
}

$path = "../../media/members/";
$webpath = "../..//media/members/";
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
array_push($errors, "<span style='color: #FE8374'><span class='icon-user-times'></span> ".$name." file exists.");
}

if($UploadOk == true){
move_uploaded_file($temp,$UploadFolder."/".$name);	
	
	if (move_uploaded_file($temp,$UploadFolder."/".$name)) {
	copy($UploadFolder, $destination2);
		
	}
array_push($uploadedFiles, $name);						
$successmsg = "<span style='color: #2ab934; font-wight:bolder;'><span class='icon-camera'></span> your image was uploaded</span>";


  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $deleteSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));


foreach($uploadedFiles as $fileName);


  $insertSQL = sprintf("INSERT INTO tbl_uploaderror (sessionid, error_message) VALUES (%s, %s)",
                       GetSQLValueString($sessionid, "text"),
                       GetSQLValueString($successmsg, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
}
}
if($counter>0){
	
if(count($errors)>0)
{
  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $deleteSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
foreach($errors as $error)
{
  $insertSQL = sprintf("INSERT INTO tbl_uploaderror (sessionid, error_message) VALUES (%s, %s)",
                       GetSQLValueString($sessionid, "text"),
                       GetSQLValueString($error, "text"));

  mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));						
 }
 }
if(count($uploadedFiles)>0){
 foreach($uploadedFiles as $fileName)	  
{			
 $file = $path.$UploadFolder.$fileName;	
 $newfile = str_replace("../", "", "$file");
list($width, $height) = getimagesize("../../$path$UploadFolder$fileName");
$file_width=$width;
$file_height=$height;	
$file_size = filesize("../../$path$UploadFolder$fileName"); // Get file size in bytes
$file_size = $file_size / 1024; 							
  $insertSQL = sprintf("UPDATE social_users SET g_image=%s WHERE g_email=%s",
GetSQLValueString($newfile, "text"),
GetSQLValueString($_SESSION['kj_username'], "text"));	 

 mysqli_select_db( $killjoy, $database_killjoy);
  $Result1 = mysqli_query( $killjoy, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));		
 }
		  
setcookie("img_anchor", $anchor);			
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
<title>profile image deleter</title>
<meta name="keywords" content="rental, property, offer, listing, advertise, houses, flats, apartments, letting, tolet, owner, agency, rent, stay, share " />
<meta name="description" content="add a property rental offer for your house, flat or apartment. find a suitable tenant in your town or city." />
</head>
<body>

</body>
</html>
