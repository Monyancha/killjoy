<?php require_once('../Connections/killjoy.php'); ?>
 <?php

	$q=$_GET['q'];
	$mysqli=mysqli_connect('localhost','euqjdems_nawisso','N@w!1970','euqjdems_killjoy') or die("Database Error");
	$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT City AS City FROM tbl_cities WHERE City LIKE '%$my_data%' ORDER BY City ASC";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			echo utf8_encode(ucwords($row['City']))."\n ";	
		}
	}
?>
