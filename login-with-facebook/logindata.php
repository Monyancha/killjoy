<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

$login_seccess_url = "http://localhost/killjoy/index.php";

	include 'dbconfig.php';
	//return $conn variable.
if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$fb_Id = $_POST['fb_Id'];
		$active = "1";
		$profilePictureUrl = $_POST['profilePictureUrl'];
		$locale = $_POST['link'];
		
		$result = mysql_query("SELECT COUNT(g_email) FROM social_users WHERE g_email=$email");
	if($result === false) { 
		die(mysql_error()); //result is false show db error and exit.
	}
	
	$UserCount = mysql_fetch_array($result);
 
    if($UserCount[0]) //user id exist in database
    {
		echo 'Welcome back '.$user_name.'!';
    }else{ //user is new
		echo 'Hello! '.$user_name.', Thanks for Registering!';
		@mysql_query("INSERT INTO social_users(g_name,g_email,g_id,g_image,g_link, g_active) VALUES ('".$name."','".$email."','".$fb_Id."','".$profilePictureUrl."','".$locale."','".$active."')");
	}

	$_SESSION['kj_username'] = $email;
    $_SESSION['kj_authorized'] = "1";  
	header('Location: ' . filter_var($login_seccess_url  , FILTER_SANITIZE_URL));
		
		
	
}

 ?> 