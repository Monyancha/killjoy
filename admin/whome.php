<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

$name = $_GET['reqname'];
$email = $_GET['requsername'];

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<link rel="alternate" href="https://www.killjoy.co.za/" hreflang="en" />
<link rel="apple-touch-icon" sizes="57x57" href="favicons/apple-icon-57x57.png" />
<link rel="apple-touch-icon" sizes="60x60" href="favicons/apple-icon-60x60.png" />
<link rel="apple-touch-icon" sizes="72x72" href="favicons/apple-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="76x76" href="favicons/apple-icon-76x76.png" />
<link rel="apple-touch-icon" sizes="114x114" href="favicons/apple-icon-114x114.png" />
<link rel="apple-touch-icon" sizes="120x120" href="favicons/apple-icon-120x120.png" />
<link rel="apple-touch-icon" sizes="144x144" href="favicons/apple-icon-144x144.png" />
<link rel="apple-touch-icon" sizes="152x152" href="favicons/apple-icon-152x152.png" />
<link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-icon-180x180.png" />
<link rel="icon" type="image/png" sizes="192x192"  href="favicons/android-icon-192x192.png" />
<link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="96x96" href="favicons/favicon-96x96.png" />
<link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png" />
<link rel="manifest" href="/manifest.json" />
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="canonical" href="https://www.killjoy.co.za/index.php">
<title>killjoy - change your mail account</title>
<script src="../fancybox/libs/jquery-3.3.1.min.js" ></script>
<link rel="stylesheet" href="../fancybox/dist/jquery.fancybox.min.css" />
<script src="../fancybox/dist/jquery.fancybox.min.js"></script>
<link href="../css/login-page/mailcomplete.css" rel="stylesheet" type="text/css" />
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<div id="notexist" class="completeexist"><div class="completecells">Dear <?php echo $name ?></div><div class="completecells">The email address your are changing to: <?php echo $email ?> already exists.</div><div class="completecells">Please use a different email address.</div>
<div class="completecells"><a class="try-again" href="changemail.php">Retry <span class="icon-rotate-left"></span></a></div></div>;

<script type="text/javascript">
  $.fancybox.open({
    src  : '#notexist',
    type : 'inline',
    opts : {
        afterClose : function( instance, current ) {
            location.href='changemail.php';
        }
    }
});
</script>
</body>
</html>