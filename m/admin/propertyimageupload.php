<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/killjoy.php');require_once('../Connections/killjoy.php');

if (isset($_SESSION['kj_username'])) {
	
	$social_user = $_SESSION['kj_username'];
	
} else {
	
	$social_user = "Anonymous";
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

if (isset($_SESSION['kj_propsession'])) {
$sessionid = $_SESSION['kj_propsession'];
	

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
array_push($errors, "<span style='color: #FE8374'><span class='icon-user-times'></span> ".$name." file exists.");
}

if($UploadOk == true){
move_uploaded_file($temp,$UploadFolder."/".$name);						
array_push($uploadedFiles, $name);						
$successmsg = "<span style='color: #2ab934; font-wight:bolder;'><span class='icon-camera'></span> your image was uploaded</span>";


  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['kj_propsession'], "text"));

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
                       GetSQLValueString($_SESSION['kj_propsession'], "text"));

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
list($width, $height) = getimagesize("$path$UploadFolder$fileName");
$file_width=$width;
$file_height=$height;	
$file_size = filesize("$path$UploadFolder$fileName"); // Get file size in bytes
$file_size = $file_size / 1024; 							
$query=sprintf("INSERT INTO tbl_propertyimages(image_url, sessionid, social_user, img_width, img_height, img_size) VALUES (%s, %s, %s, %s, %s, %s)",
GetSQLValueString($newfile, "text"),
GetSQLValueString($sessionid, "text"),
GetSQLValueString($social_user, "text"),
GetSQLValueString($file_width, "int"),			
GetSQLValueString($file_height, "int"),		
GetSQLValueString($file_size, "int"));	
 mysqli_select_db( $killjoy, $database_killjoy);
$Result1 = mysqli_query( $killjoy, $query) or die(mysqli_error($GLOBALS["___mysqli_ston"]));	
  
   $imageid = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
  setcookie("image_id", $imageid, time()+60*60*24*30 ,'/');  
	
	
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
