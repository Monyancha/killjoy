<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/stomer.php');

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

$colname_select_user = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_select_user = $_SESSION['MM_Username'];
}
mysqli_select_db( $stomer, $database_stomer);
$query_select_user = sprintf("SELECT st_username, st_userid FROM st_users WHERE st_username = %s", GetSQLValueString($colname_select_user, "text"));
$select_user = mysqli_query( $stomer, $query_select_user) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_select_user = mysqli_fetch_assoc($select_user);
$totalRows_select_user = mysqli_num_rows($select_user);
$member = $row_select_user['st_userid'];

if (isset($_SESSION['sessionid'])) {
$sessionid = $_SESSION['sessionid'];
}
$path = "../admin/images/products/";
chdir ($path);
if (!file_exists($sessionid) && !is_dir($sessionid)) {
mkdir ($sessionid,0777,true);
} 
$errors = array();
$uploadedFiles = array();
$extension = array("gif", "jpeg", "jpg", "png","GIF","JPEG","JPG","PNG");
$anchor = "#photo";
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
$successmsg = $counter." images successfully uploaded";

  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $deleteSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));

foreach($uploadedFiles as $fileName);


  $insertSQL = sprintf("INSERT INTO tbl_uploaderror (sessionid, error_message) VALUES (%s, %s)",
                       GetSQLValueString($sessionid, "text"),
                       GetSQLValueString($successmsg, "text"));

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
}
}
if($counter>0){
	
if(count($errors)>0)
{
  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $deleteSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
  
foreach($errors as $error)
{
  $insertSQL = sprintf("INSERT INTO tbl_uploaderror (sessionid, error_message) VALUES (%s, %s)",
                       GetSQLValueString($sessionid, "text"),
                       GetSQLValueString($error, "text"));

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));							
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
  $insertSQL = sprintf("INSERT INTO tbl_prodimages(sessionid, image_url, member_id, img_width, img_height, img_size, is_plant)
VALUES (%s, %s, %s, %s, %s, %s, %s)",
GetSQLValueString($sessionid, "text"),
GetSQLValueString($newfile, "text"),
GetSQLValueString($member, "int"),
GetSQLValueString($file_width, "int"),			
GetSQLValueString($file_height, "int"),		
GetSQLValueString($file_size, "int"),
GetSQLValueString(1, "int"));	 

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
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
<?php
((mysqli_free_result($select_user) || (is_object($select_user) && (get_class($select_user) == "mysqli_result"))) ? true : false);

((mysqli_free_result($member_username) || (is_object($member_username) && (get_class($member_username) == "mysqli_result"))) ? true : false);
?>
