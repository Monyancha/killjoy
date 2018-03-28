<?php require_once('../Connections/rentaguide.php'); ?>
 <?php
	$q=$_GET['q'];
	$mysqli=mysqli_connect('localhost','txtktfzf_nawisso','N@w!1970','txtktfzf_rentaguide') or die("Database Error");
	$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT DISTINCT streetaddress AS Street, sessionid AS sessionid, citytown as Town, suburb AS Suburb, 'tbl_listings' AS Source, COUNT(tbl_listings.listing_id) AS rowcount FROM tbl_listings WHERE streetaddress LIKE '%$my_data%' OR citytown LIKE '%$my_data%' OR suburb LIKE '%$my_data%' AND tbl_listings.listing_id IN(SELECT tbl_reviews.listing_id FROM tbl_reviews) UNION SELECT DISTINCT streetaddress AS Street, sessionid As sessionid, citytown as Town, suburb AS Suburb, 'tbl_rentals' AS Source, COUNT(tbl_rentals.rental_id) AS rowcount FROM tbl_rentals WHERE streetaddress LIKE '%$my_data%' OR citytown LIKE '%$my_data%' OR suburb LIKE '%$my_data%' UNION SELECT DISTINCT streetaddress AS Street, sessionid As sessionid, citytown as Town, suburb AS Suburb, 'tbl_estateagencies' AS Source,COUNT(tbl_estateagencies.agency_id) AS rowcount FROM tbl_estateagencies WHERE streetaddress LIKE '%$my_data%' OR citytown LIKE '%$my_data%' OR suburb LIKE '%$my_data%' ORDER BY Street ASC";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	

	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{
			if ($row['Source'] == "tbl_listings") {
				if ($row['rowcount'] > 1) {
				$source = "<div id='rentals' class='rentals'>Reviews</div>";
				}
				if ($row['rowcount'] < 2) {
				$source = "<div id='rentals' class='rentals'>Review</div>";
				}
				}
					
				if ($row['Source'] == "tbl_rentals") {
					if ($row['rowcount'] > 1) {
				$source = "<div id='rentals' class='rentals'>Rentals</div>";
				}
				if ($row['rowcount'] < 2) {
				$source = "<div id='rentals' class='rentals'>Rental</div>";
				}
				}
				
				if ($row['Source'] == "tbl_estateagencies") {
				if ($row['rowcount'] > 1) {
				$source = "<div id='rentals' class='rentals'>Agencies</div>";
				}
				if ($row['rowcount'] < 2) {
				$source = "<div id='rentals' class='rentals'>Agency</div>";
				}
				}
				
				$groupcount = "<div id='rentalsix' class='rentals'>".$row['rowcount']."</div>";
				
			$newstreet = substr($row['Street'], 0, 35);
			
			
			if ($row['rowcount'] != 0) {
			echo ucwords($newstreet. ", ". $row['Suburb']. ", ". $row['Town']. " ". $source. " ". $groupcount. "\n ");	
			}
		}
	}
?>
