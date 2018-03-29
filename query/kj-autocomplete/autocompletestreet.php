<?php require_once('../Connections/killjoy.php'); ?>
 <?php
	$q=$_GET['q'];
	$mysqli=mysqli_connect('localhost','euqjdems_nawisso','N@w!1970','euqjdems_killjoy') or die("Database Error");
	$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT tbl_address_comments.sessionid as id, tbl_address.str_number as strNumber, tbl_address.street_name AS Street, tbl_address.city as city, (SELECT COUNT(tbl_approved.sessionid) FROM tbl_approved WHERE tbl_approved.sessionid = tbl_address_comments.sessionid AND tbl_approved.is_approved=1) AS reviewCount, IFNULL(tbl_propertyimages.image_url,'images/icons/house-outline-bg.png') AS propertyImage, tbl_address_comments.rating_date as ratingDate, IFNULL(tbl_approved.is_approved,'0') as Status, ROUND(AVG(tbl_address_rating.rating_value),1) AS avgRating, (SELECT COUNT(tbl_address_rating.address_comment_id) FROM tbl_address_comments WHERE tbl_address_rating.address_comment_id = tbl_address_comments.id) as ratingCount FROM `euqjdems_killjoy`.`tbl_address` LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_propertyimages ON tbl_propertyimages.sessionid = tbl_address.sessionid LEFT JOIN tbl_address_rating ON tbl_address_rating.address_comment_id = tbl_address_comments.id LEFT JOIN tbl_addressindex ON tbl_addressindex.sessionid=tbl_address.sessionid WHERE tbl_addressindex.address LIKE '%$my_data%' GROUP BY tbl_address_comments.sessionid ORDER BY tbl_address_comments.rating_date DESC";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	

	
	if($result)
	{
		while($row=mysqli_fetch_array($result))
		{   
		
			
	if ($row['reviewCount'] > 1) {
		$are = "are";
		$reviews = "reviews";
	} else {
     $are = "is";
	$reviews = "review";	
	}
		    $countreviews = $row['reviewCount'];
			$new_id = "<div style='display:none'>" . $row['id'] ."</div>";
			$title = $row['reviewCount'];
			$marker = "<div class='locationmarker'><span class='icon-map-marker'></span></div>";
			echo strip_tags($row['strNumber']." ".$row['Street']. " ". $row['city']."\n ");	
		}
	}
?>