<?php
	include 'dbconfig.php';
	//return $conn variable.
if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$name = $_POST['name'];
		$email = $_POST['email'];
		$fb_Id = $_POST['fb_Id'];
		$profilePictureUrl = $_POST['profilePictureUrl'];
		$locale = $_POST['locale'];
		$query = "INSERT INTO social_users(g_name,g_email,g_id,g_image,g_link) VALUES ('".$name."','".$email."','".$fb_Id."','".$profilePictureUrl."','".$locale."')";
		$result = mysqli_query($conn , $query) or die(mysqli_error());
		if ($result) {
			// header("LOCATION: fblogin.php?success");
			echo "successful entry";
		}else{
			echo "Oeps";
		}

}else{
	echo "Try again Later";
}

 ?> 