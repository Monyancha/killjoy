<?php require_once('../Connections/rentaguide.php'); ?>
 <?php

	$q=$_GET['q'];
	$mysqli=mysqli_connect('localhost','txtktfzf_nawisso','N@w!1970','txtktfzf_rentaguide') or die("Database Error");
	$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT DISTINCT citytown AS City FROM tbl_rentals WHERE citytown LIKE '%$my_data%' ORDER BY City ASC";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			echo ucwords($row['City'])."\n ";	
		}
	}
?>
