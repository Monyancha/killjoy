<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script type="text/javascript" src="kj-autocomplete/lib/jQuery-1.4.4.min.js"></script>
<script type="text/javascript" src="kj-autocomplete/jquery.autocomplete.js"></script>
<link href="kj-autocomplete/jquery.quickfindagency.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type="text/javascript">
var $j = jQuery.noConflict();
$j(document).ready(function(){
$j("#password").autocomplete("kj-autocomplete/cityfinder.php", {
			 minLength: 10, 
			delay: 500,
selectFirst: true
});
 $j("#password").result(function() {
$j.ajax( { type    : "POST",
data    : { "txt_city" : $("#password").val()}, 
url     : "functions/usercityupdater.php",
  success : function (data)
  { 
  
   $j('#locale').load(document.URL +  ' #locale');  
      $j("#updated").show();
setTimeout(function() { $("#updated").hide(); }, 3000);
  
},
complete: function (data) {
	   $j('#locale').load(document.URL +  ' #locale');
	      $("#updated").show();
setTimeout(function() { $("#updated").hide(); }, 3000);

}
		
});
 return false;
});
 });
 
</script>

</body>
</html>