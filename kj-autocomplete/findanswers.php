<?php require_once('../Connections/killjoy.php'); ?>
 <?php
	$q=$_GET['q'];
	$mysqli=mysqli_connect('localhost','euqjdems_nawisso','N@w!1970','euqjdems_killjoy') or die("Database Error");
	$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT DISTINCT title as Title FROM tbl_faq WHERE title LIKE '%$my_data%' OR instructions like '%$my_data%' ORDER BY Title ASC";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	

	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{   
		
	   		$title = $row['Title'];
			echo strip_tags("<a href='#'>".$row['Title']."</a>"). "\n ";	
		}
	}
?>