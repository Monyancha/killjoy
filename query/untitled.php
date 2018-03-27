<?php require_once('../Connections/killjoy.php'); ?>
 <?php
	$q=$_GET['q'];
	$mysqli=mysqli_connect('localhost','euqjdems_nawisso','N@w!1970','euqjdems_killjoy') or die("Database Error");
	$my_data=mysqli_real_escape_string($mysqli, $_GET['q']);
	$sql="SELECT DISTINCT (SELECT COUNT(tbl_address_comments.sessionid) FROM tbl_address_comments WHERE tbl_address_comments.sessionid = tbl_address.sessionid) AS reviewCount, str_number as strNumber, street_name AS Street, tbl_address.sessionid As id, city as Town FROM tbl_address LEFT JOIN tbl_address_comments ON tbl_address_comments.sessionid = tbl_address.sessionid LEFT JOIN tbl_approved ON tbl_approved.address_comment_id = tbl_address_comments.id WHERE tbl_address.street_name LIKE '%$my_data%' OR tbl_address.str_number LIKE '%$my_data%' AND tbl_approved.is_approved = '1' GROUP BY tbl_address.sessionid ORDER BY Street ASC";
	$result = mysqli_query($mysqli,$sql) or die(mysqli_error());
	
function generateRandomString($length = 24) {
    $characters = '0123456789abcdefghijklmnopqrstuvw!@#$%^&^*()';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$captcha = filter_var(generateRandomString(), FILTER_SANITIZE_SPECIAL_CHARS);
$captcha = urlencode($captcha);

function generatenewRandomString($length = 24) {
    $characters = '0123456789abcdefghijklmnopqrstuvw!@#$%^&^*()';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$smith = filter_var(generateRandomString(), FILTER_SANITIZE_SPECIAL_CHARS);
$smith = urlencode($smith);

	
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
			echo ($marker."<a href='https://www.killjoy.co.za/viewer.php?tarsus=".$captcha."&claw=".$row['id'] ."&alula=".$smith."' title='There $are $title $reviews for this property'>".$row['strNumber']." ".$row['Street']). ", ". $row['Town']. " " . $new_id."</a>". "\n ";	
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>killjoy.co.za - search engine rich card.</title>
</head>

<body>
</body>
</html>