<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>killjoy.co.za - help and support - faq</title>

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
<link href="../iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="css/checks.css" rel="stylesheet" type="text/css" />
<link href="css/support.css" rel="stylesheet" type="text/css">
<script src="../fancybox/libs/jquery-3.3.1.min.js" ></script>
<script type="text/javascript" src="../kj-autocomplete/lib/jQuery-1.4.4.min.js"></script>
<script type="text/javascript" src="../kj-autocomplete/jquery.autocomplete.js"></script>
<link href="../kj-autocomplete/jquery.answerfinder.css" rel="stylesheet" type="text/css" />


</head>

<body>
<div class="header"><div class="header-text"><h1><span style="padding-right: 25px; vertical-align: middle;" class="icon-life-bouy"></span>Help and Support</h1></div><div class="search-container"><div class="search-text">Find answers to your questions</div><div class="search-box"><form action="index.php" name="findanswers" id="findanswers"><input placeholder="type a question to find an answer" autofocus class="searchfield" type="search" data-type="search" name="q" id="q"></form></div></div></div>
<div class="search-results-container"></div>


<script type="text/javascript">
var $j = jQuery.noConflict();
$j(document).ready(function(){
$j("#q").autocomplete("../kj-autocomplete/findanswers.php", {
			 minLength: 10, 
			 delay: 500,
	         selectFirst: false
	        

});
 $j("#q").result(function() {
$j("#findanswers").submit();
$j("#q").val('');	 
});
 });

 
</script>

</body>
</html>