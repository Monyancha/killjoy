<?php require_once('../Connections/rentaguide.php'); ?>
 <?php
	$q=$_GET['q'];
	$mysqli=mysqli_connect('localhost','txtktfzf_nawisso','N@w!1970','txtktfzf_rentaguide') or die("Database Error");
	$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT DISTINCT agency_name AS Agency, tbl_estateagencies.sessionid AS estatesession, tbl_estatelogos.sessionid as logosession, citytown as Town, suburb AS Suburb, tbl_estatelogos.image_url As Logo FROM tbl_estateagencies LEFT JOIN tbl_estatelogos ON tbl_estatelogos.sessionid = tbl_estateagencies.sessionid WHERE agency_name LIKE '%$my_data%' ORDER BY Agency ASC";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			$user_id = $row['estatesession'];
			$logo = "<div id='nowshow'><a onclick='my_listing(" . json_encode($user_id) . ")' href='#'><img src=". $row['Logo'] ." alt='estate agent logo' name='previewimage' class='agencylogo' id='previewimage'/></a></div>";				
			$new_id = "<div id='sessionid' style='display:none'>" . $row['estatesession'] ."</div>";
			$agency = "<a onclick='my_listing(" . json_encode($user_id) . ")' href='#'>" .$row['Agency']. "</a>";
			$town = "<a onclick='my_listing(" . json_encode($user_id) . ")' href='#'>" . $row['Town']. "</a>";
			$suburb = "<a onclick='my_listing(" . json_encode($user_id) . ")' href='#'>" . $row['Suburb']. "</a>";			
			echo ucwords("<a onclick='my_listing(" . json_encode($user_id) . ")' href='#'>".$logo .$agency. ", ". $town. ", ". $suburb. "" . $new_id."</a>". "\r\n ");	
		}
	}
?>