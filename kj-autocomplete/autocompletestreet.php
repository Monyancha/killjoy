<?php require_once('../Connections/rentaguide.php'); ?>
 <?php
	$q=$_GET['q'];
	$mysqli=mysqli_connect('localhost','txtktfzf_nawisso','N@w!1970','txtktfzf_rentaguide') or die("Database Error");
	$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT DISTINCT streetaddress AS Street, tbl_listings.listing_id As id, citytown as Town FROM tbl_listings LEFT JOIN tbl_reviews ON tbl_reviews.listing_id = tbl_listings.listing_id WHERE tbl_listings.streetaddress LIKE '%$my_data%' AND tbl_reviews.is_approved='1' ORDER BY Street ASC";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$new_id = "<div style='display:none'>" . $row['id'] ."</div>";
			echo ucwords($row['Street']). ", ". $row['Town']. " " . $new_id. "\n ";	
		}
	}
?>
