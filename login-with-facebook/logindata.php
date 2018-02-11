<?php require_once('../Connections/killjoy.php'); ?>
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
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



	include 'dbconfig.php';
	//return $conn variable.
if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$fb_Id = $_POST['fb_Id'];
		$active = "1";
		$profilePictureUrl = "media/profile.png";
		$locale = $_POST['link'];
		
		$colname_rs_checkfbuser = "-1";
if (isset($_POST['email'])) {
  $colname_rs_checkfbuser = $_POST['email'];
}
mysql_select_db($database_killjoy, $killjoy);
$query_rs_checkfbuser = sprintf("SELECT g_email FROM social_users WHERE g_email = %s", GetSQLValueString($colname_rs_checkfbuser, "text"));
$rs_checkfbuser = mysql_query($query_rs_checkfbuser, $killjoy) or die(mysql_error());
$row_rs_checkfbuser = mysql_fetch_assoc($rs_checkfbuser);
$totalRows_rs_checkfbuser = mysql_num_rows($rs_checkfbuser);

 if($totalRows_rs_checkfbuser) //user id exist in database
    {
		$_SESSION['kj_username'] = $email;
        $_SESSION['kj_authorized'] = "1"; 
		//redirect to login page
		header('Location: ' . filter_var($user_exists  , FILTER_SANITIZE_URL));
    }else {
	
		$query = "INSERT INTO social_users(g_name,g_email,g_id,g_image,g_link, g_active) VALUES ('".$name."','".$email."','".$fb_Id."','".$profilePictureUrl."','".$locale."','".$active."')";
		$result = mysqli_query($conn , $query) or die(mysqli_error());
		if ($result) {
			
	  
			
				$_SESSION['kj_username'] = $email;
            $_SESSION['kj_authorized'] = "1";   
			header('Location: ' . filter_var($login_seccess_url  , FILTER_SANITIZE_URL));
			
		
		}
	}
}

 
mysql_free_result($rs_checkfbuser);
?>
