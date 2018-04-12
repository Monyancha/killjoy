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

if (isset($_SESSION['sessionid'])) {
$sessionid = $_SESSION['sessionid'];
}

$path = "../media/members/";
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
array_push($errors, $name." file size is larger than 2 Mb.");
}				
$ext = pathinfo($name, PATHINFO_EXTENSION);
if(in_array($ext, $extension) == false){
$UploadOk = false;
array_push($errors, $name." is invalid file type.");
}
if(file_exists($UploadFolder."/".$name) == true){
$UploadOk = false;
array_push($errors, $name." file exists.");
}

if($UploadOk == true){
move_uploaded_file($temp,$UploadFolder."/".$name);						
array_push($uploadedFiles, $name);						
$successmsg = "your image was successfully uploaded";


  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($deleteSQL, $killjoy) or die(mysql_error());


foreach($uploadedFiles as $fileName);


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
list($width, $height) = getimagesize("../../$path$UploadFolder$fileName");
$file_width=$width;
$file_height=$height;	
$file_size = filesize("../../$path$UploadFolder$fileName"); // Get file size in bytes
$file_size = $file_size / 1024; 							
  $insertSQL = sprintf("UPDATE social_users SET g_image=%s WHERE g_email=%s",
GetSQLValueString($newfile, "text"),
GetSQLValueString($_SESSION['kj_username'], "text"));	 

 mysql_select_db($database_killjoy, $killjoy);
  $Result1 = mysql_query($insertSQL, $killjoy) or die(mysql_error());		
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
<title>Add a new rental property offer - properties for rent - properties to let - rental property listings - houses and flats for rent</title>
<meta name="keywords" content="rental, property, offer, listing, advertise, houses, flats, apartments, letting, tolet, owner, agency, rent, stay, share " />
<meta name="description" content="add a property rental offer for your house, flat or apartment. find a suitable tenant in your town or city." />
</head>
<body>

</body>
</html>