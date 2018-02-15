<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
require_once('../Connections/stomer.php');


$colname_image_path = "-1";
if (isset($_POST['image_id'])) {
  $colname_image_path = $_POST['image_id'];
}
mysqli_select_db( $stomer, $database_stomer);
$query_image_path = sprintf("SELECT image_url, featured_image FROM tbl_prodimages WHERE image_id = %s", GetSQLValueString($colname_image_path, "int"));
$image_path = mysqli_query( $stomer, $query_image_path) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
$row_image_path = mysqli_fetch_assoc($image_path);
$totalRows_image_path = mysqli_num_rows($image_path);
$successmsg = "Image ".$row_image_path['image_url']." deleted";
$path = $row_image_path['image_url'];


 if ((isset($_POST["image_id"])) && ($_POST["image_id"] != "")) {
 $rowID = $_POST["image_id"];
  $deleteSQL = sprintf("DELETE FROM tbl_prodimages WHERE image_id=%s",
                       GetSQLValueString($rowID, "int"));

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $deleteSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));

 
  $deleteSQL = sprintf("DELETE FROM tbl_uploaderror WHERE sessionid=%s",
                       GetSQLValueString($_SESSION['sessionid'], "text"));

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $deleteSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));
 
  $insertSQL = sprintf("INSERT INTO tbl_uploaderror(sessionid, error_message) VALUES (%s, %s)",
                 GetSQLValueString($_SESSION['sessionid'], "text"),
GetSQLValueString($successmsg, "text"));	

  mysqli_select_db( $stomer, $database_stomer);
  $Result1 = mysqli_query( $stomer, $insertSQL) or die(mysqli_error($GLOBALS["___mysqli_ston"]));	   
unlink($path);
 }
 	
 ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.killjoy.co.za/">
</head>
 <body>
 <form name="form" action="<?php echo $editFormAction; ?>" method="POST">
 <input name="error" type="text" />
 <input type="hidden" name="MM_insert" value="form" />
 </form>
</body>
</html>
<?php
((mysqli_free_result($image_path) || (is_object($image_path) && (get_class($image_path) == "mysqli_result"))) ? true : false);
?>
