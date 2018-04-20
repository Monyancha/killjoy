<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

$name = $_GET['reqname'];
$email = $_GET['requsername'];

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>killjoy - registration completed</title>
<link href="../../jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.dialog.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.resizable.min.css" rel="stylesheet" type="text/css">
<script src="../../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<script src="../../SpryAssets/jquery.ui-1.10.4.dialog.min.js"></script>
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css">
<link href="../css/dialog-styling.css" rel="stylesheet" type="text/css">
</head>

<body>
<div data-role="page" id="page">

<div class="confirm" id="confirm">Dear <?php echo $name ?>, Someone is already using the email address <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a> . Please choose a different email address.<br><br> Thank you, the <a href="https://www.killjoy.co.za">The killjoy Team</a></div>
 </div>
<script type="text/javascript">
$(function() {
	$( "#confirm" ).dialog(); 
	$( "#confirm" ).dialog({ title: "Email Exists!" });
	
    	});
	
</script>

<script type="text/javascript">
	 var elem = $("#confirm");
	$("#confirm").dialog({ closeText: '' });
 elem.dialog({
       resizable: false,
    title: 'title',
    buttons: {
       Retry: function() {
          $(this).dialog('close');
		   parent.location.href ="changeemail.php?email=<?php echo $email ?>";
       } //end function for Ok button
    }//end buttons
 });     // end dialog
 elem.dialog('open');
	$('#confirm').bind('dialogclose', function(event) {
     window.location = "changeemail.php?email=<?php echo $email ?>";
 });	
	
	</script>
</body>
</html>