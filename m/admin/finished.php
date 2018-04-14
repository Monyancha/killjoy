<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

$name = $_SESSION['user_name'];
$email = $_SESSION['user_email'];

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<link href="../../jquery-mobile/jquery.mobile-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.dialog.min.css" rel="stylesheet" type="text/css">
<link href="../../SpryAssets/jquery.ui.resizable.min.css" rel="stylesheet" type="text/css">
<script src="../../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<script src="../../SpryAssets/jquery.ui-1.10.4.dialog.min.js"></script>
<style type="text/css">

.ui-dialog-titlebar {
	background: #6DACC0;#FFFEFD;
	border:
	}

.ui-dialog .ui-dialog-titlebar-close {
    right:0;
	display: none;
}	
.confirm {text-align: center;}
	#confirm a:active {text-decoration: none; color: cornflowerblue}
	#confirm a:visited {text-decoration: none;color: cornflowerblue}
	#confirm a:hover {text-decoration: none;color: cornflowerblue}
	#confirm a:link {text-decoration: none;color: cornflowerblue}

</style>
</head>

<body>
<div data-role="page" id="page">
  </div>
<div class="confirm" id="confirm">Dear <?php echo $name ?>, Thank you for registering. An email has been sent to <?php echo $email ?>. Please follow the instructions in th e link to activate your account.<br><br> Thank you, the <a href="https://www.killjoy.co.za">The killjoy Team</a><br><br><a href="../index.php">Close</a></div>
<script type="text/javascript">
$(function() {
	$( "#confirm" ).dialog(); 
});
</script>
</body>
</html>