<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script type="text/javascript" src="kj-autocomplete/lib/jQuery-1.4.4.min.js"></script>
<script type="text/javascript" src="kj-autocomplete/jquery.autocomplete.js"></script>
<link href="kj-autocomplete/jquery.streetfinder.css" rel="stylesheet" type="text/css" />
<style type="text/css">
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>
</head>
<body>
<script type="text/javascript">
var $j = jQuery.noConflict();
$j(document).ready(function(){
$j("#address").autocomplete("kj-autocomplete/addressfinder.php", {
			 minLength: 10, 
			delay: 500,
selectFirst: true
});
 $j("#address").result(function() {
$j("#findreviews").submit();
$j("#address").val('');	 
});
 });

 
</script>


</body>
</html>