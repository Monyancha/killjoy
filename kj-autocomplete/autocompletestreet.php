<?php require_once('../Connections/killjoy.php'); ?>
 <?php
	$q=$_GET['q'];
	$mysqli=mysqli_connect('localhost','euqjdems_nawisso','N@w!1970','euqjdems_killjoy') or die("Database Error");
	$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT DISTINCT street_name AS Street, tbl_address.address_id As id, city as Town FROM tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.sessionid = tbl_address.sessionid WHERE tbl_address.street_name LIKE '%$my_data%' AND tbl_approved.is_approved='1' ORDER BY Street ASC";
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
