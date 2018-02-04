<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<script type="text/javascript" src="fancybox/lib/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<script src="SpryAssets/SpryTooltip.js" type="text/javascript"></script>
<title>killjoy community - home page - rate a rental property - review a rental property - help future tenants - warn tenants against abuse</title>
<meta name="description" content="killjoy is an online community of tenants that stand together to ensure fair treatment and guard against renting properties from abusive landlords. We help future tentants" />
<meta name="keywords" content="property, rentals, advice, reviews, ratings, tenants, complaints, unfair, landlords, abuse, assistance" />
<meta name="viewport" content="width=device-width" />
<link href="css/media.css" rel="stylesheet" type="text/css" />
<link href="iconmoon/style.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryTooltip.css" rel="stylesheet" type="text/css" />
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div class="maincontainer" id="maincontainer">
  <div class="header" id="header"><img src="images/icons/owl-header.jpg" alt="killjoy property rental ratings and reviews" width="512" height="512" class="hdrimg" /><span style="padding-top:10px; padding-right:20px;" class="icon-facebook"></span><span class="icon-twitter"></span>   
  </div>
   <div class="banner" id="banner"></div>
  <div class="heading" id="heading">
    <h1>Killjoy - the online comunity for rental property tenants</h1></div>
  <div class="intro" id="intro">Killjoy’s main mission is to prevent landlord abuse of rental property tenants. It gives you the power to review a rental property and share your personal experiences with future tenants. Future tenants also have the option to view existing property rental reviews before making a decision on letting the property. This way we can all rent smarter. </div>
  <div class="chooser" id="chooser">
    <a id="inline" href="#reviewproperty" title="review a rental property"><div class="choosereview" id="choosereview">Review a Rental Property</div></a>
    <a id="inline" href="#viewpropertyreview" title="view the reviews and ratings for a rental property"><div class="chooseview" id="chooseview">View rental property reviews</div></a>
  </div>
</div>
<div class="tooltipContent" id="sprytooltip1">Review</div>

<div id="reviewproperty" class="reviewproperty" style="display:none"><?php include 'reviewproperty.php' ?></div>
<div id="viewpropertyreview" class="viewpropertyreview" style="display:none"><?php include 'viewpropertyreviews.php' ?></div>

<script type="text/javascript">
$(document).ready(function() {
/* This is basic - uses default settings */

$("a#single_image").fancybox();
/* Using custom settings */

$("a#inline").fancybox({
helpers : {
overlay : {
css : {
  'background' : 'rgba(200, 201, 203, 0.40)'
   }
}
},
'opacity' : 0.4,
'width' :  256,
'height' : 128,
'autoSize' : false,		

'hideOnContentClick': true	});
modal: false,

/* Apply fancybox to multiple items */

$("a.grouped_elements").fancybox({
'transitionIn'	:	'elastic',
'transitionOut'	:	'elastic',
'speedIn'		:	600, 
'speedOut'		:	200, 
'overlayShow'	:	false
});

});
var sprytooltip1 = new Spry.Widget.Tooltip("sprytooltip1", "#choosereview");
</script>
</body>
</html>