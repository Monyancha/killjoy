<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Killjoy App</title>
<link href="../jquery-mobile/jquery.mobile.theme-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="../jquery-mobile/jquery.mobile.structure-1.3.0.min.css" rel="stylesheet" type="text/css">
<link href="css/gui.css" rel="stylesheet" type="text/css">
<script src="../jquery-mobile/jquery-1.11.1.min.js"></script>
<script src="../jquery-mobile/jquery.mobile-1.3.0.min.js"></script>
<style type='text/css'>
.ui-page .ui-header {
    background: #6EADC1 !important;
	height: 65px;
	position: relative;
}
	.ui-page .ui-header a:link {text-decoration: none; color: #FFFEFD}
.ui-page .ui-header a:active {text-decoration: none; color: #FFFEFD}
.ui-page .ui-header a:hover {text-decoration: none; color: #FFFEFD}
.ui-page .ui-header a:visited {text-decoration: none; color: #FFFEFD}
	.ui-page .ui-footer {
    background: #6EADC1 !important;
		position: absolute;
		bottom: 0px;
		width: 100%;
}
</style>
<link href="iconmoon/style.css" rel="stylesheet" type="text/css">
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
<link href="css/site-menu.css" rel="stylesheet" type="text/css">
<meta name="msapplication-TileColor" content="#ffffff" />
<meta name="msapplication-TileImage" content="favicons/ms-icon-144x144.png" />
<meta name="theme-color" content="#ffffff" />
<link rel="stylesheet" type="text/css" href="fancybox/dist/jquery.fancybox.min.css" />
</head>

<body>
<div data-role="page" id="index-page">
  <div class="header" data-role="header">
    <h1>Killjoy.co.za</h1>
	  <div class="social-user-image" id="socialuserimage"></div>
	  <div class="social-user-signin"><a data-fancybox data-src="#sign-in-content" href="javascript:;">Sign in</a></div>
    <img class="site-header-logo" src="images/icons/owl-header-white.gif" width="512" height="512" alt=""/>
    <div class="social-user-menu" id="socialusermenu"><div class="social-user-profile">My Profile</div><div class="social-user-reviews">My Reviews</div><div class="social-user-signout">Sign Out</div></div>
     </div>
 <div data-role="content"><form>Content</form></div>
 <div style="display: none;" id="sign-in-content">
	<h2>Hello</h2>
	<p>You are awesome.</p>
</div>
<a data-fancybox data-type="iframe" data-src="admin/index-signin.php" href="javascript:;">
	Webpage
</a>
 
  <div data-role="footer">
    <h4>Footer</h4>
  </div>
</div>
<script type="text/javascript" src="js/index.js"></script>
<script src="fancybox/dist/jquery.fancybox.js"></script>
<script src="fancybox/dist/jquery.fancybox.min.js"></script>
</body>
</html>