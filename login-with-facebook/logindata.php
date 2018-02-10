<?php
	include 'dbconfig.php';
	//return $conn variable.
if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$fb_Id = $_POST['fb_Id'];
		$active = "1";
		$profilePictureUrl = $_POST['profilePictureUrl'];
		$locale = $_POST['link'];
		$query = "INSERT INTO social_users(g_name,g_email,g_id,g_image,g_link, g_active) VALUES ('".$name."','".$email."','".$fb_Id."','".$profilePictureUrl."','".$locale."','".$active."')";
		$result = mysqli_query($conn , $query) or die(mysqli_error());
		if ($result) {
			
	$_SESSION['kj_username'] = $loginUsername;
    $_SESSION['kj_usergroup'] = $loginStrGroup;	
	$_SESSION['kj_authorized'] = "1";     
			// header("LOCATION: fblogin.php?success");
			echo "successful entry";
		}else{
			echo "Oeps";
		}

}else{
	echo "Try again Later";
}

 ?> 