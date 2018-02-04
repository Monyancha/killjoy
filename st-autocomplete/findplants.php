<?php require_once('../Connections/stomer.php'); ?>
 <?php

	$q=$_GET['q'];
	$mysqli=mysqli_connect('localhost','gxeuaurh_stomer','St0m3r','gxeuaurh_stomer') or die("Database Error");
	$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT DISTINCT plant_name AS Product, 'tbl_plants' AS Source, page_url AS page FROM tbl_plants WHERE plant_name LIKE '%$my_data%' ORDER BY Product ASC";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	
	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			
			if ($row['Source'] == "tbl_herbs") {				
				$source = "<div id='type' class='type'>Herb</div>";				
			}
					if ($row['Source'] == "tbl_vegetables") {				
				$source = "<div id='type' class='type'>Vegetable</div>";				
					}
					
					$prodpage = "<a onclick='window.location.href=". json_encode($row['page'])."' href=". json_encode($row['page']).">" . $row['Product']. "</a>";
				
				
			echo $prodpage. "" . "\n ";	
		}
	}
?>
