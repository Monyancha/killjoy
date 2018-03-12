<?php
	$q=$_COOKIE['my_city'];
	$p=$_COOKIE['my_suburb'];
	$m = $_GET['q'];
	$mysqli=mysqli_connect('localhost','txtktfzf_nawisso','N@w!1970','txtktfzf_rentaguide') or die("Database Error");
	$my_city=mysqli_real_escape_string($mysqli, $_COOKIE['my_city']);
	$my_sub=mysqli_real_escape_string($mysqli, $_COOKIE['my_suburb']);
	$no_data = mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT DISTINCT streetaddress AS Street FROM tbl_listings WHERE citytown LIKE '%$my_city%' AND suburb LIKE '%$my_sub%' UNION SELECT DISTINCT streetaddress AS Street FROM tbl_listings WHERE citytown LIKE '%$my_city%' AND suburb LIKE '%$my_sub%' ORDER BY Street ASC";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			echo ucfirst($row['Street']."\n ");	
		}
		
		if(!empty($result)) {
			echo ucfirst($no_data);
		}
	}
?>
