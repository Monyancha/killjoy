// this is the data that updates the recipies pages social scripts and links

window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
ga('create', 'UA-108851141-1', 'auto');
ga('send', 'pageview');


function facebook_score ( recipeident ) 
{ $.ajax( { type    : "POST",
data    : { "recipe_id" : recipeident }, 
url     : "../functions/facebook_clicks.php",
success : function (recipeident)
{ 
window.open(
  'https://www.facebook.com/sharer/sharer.php?u='+ Settings.facebook,
  '_new' // <- This is what makes it open in a new window.
);
$("#socialicons").removeClass("socialicons");
$("#socialicons").load(location.href + " #socialicons");
  
},
error   : function ( xhr )
{ alert( "error" );
}
} );

 }


function twitter_score ( recipeident ) 
{ $.ajax( { type    : "POST",
data    : { "recipe_id" : recipeident }, 
url     : "../functions/twitter_clicks.php",
success : function (recipeident)
{ 
window.open(
  'https://twitter.com/home?status=Checkout%20this%20delicious%20recipe!%20'+ Settings.twitter,
  '_new' // <- This is what makes it open in a new window.
);
$("#socialicons").removeClass("socialicons");
$("#socialicons").load(location.href + " #socialicons");
},
error   : function ( xhr )
{ alert( "error" );
}
} );
 }


function googleplus_score ( recipeident ) 
{ $.ajax( { type    : "POST",
data    : { "recipe_id" : recipeident }, 
url     : "../functions/googleplus_clicks.php",
success : function (recipeident)
{ 
window.open(
  'https://plus.google.com/share?url='+ Settings.google,
  '_new' // <- This is what makes it open in a new window.
);
$("#socialicons").removeClass("socialicons");
$("#socialicons").load(location.href + " #socialicons");  
},
error   : function ( xhr )
{ alert( "error" );
}
} );
 }



function linkedin_score ( recipeident ) 
{ $.ajax( { type    : "POST",
data    : { "recipe_id" : recipeident }, 
url     : "../functions/linkedin_clicks.php",
success : function (recipeident)
{ 
window.open(
  'https://www.linkedin.com/shareArticle?mini=true&url='+ Settings.linkedinurl + '&title=' + Settings.linkedintitle + '&summary=' + Settings.linkedindesc + '&source=' +Settings.linkedinauthor,
  '_new' // <- This is what makes it open in a new window.
);
$("#socialicons").removeClass("socialicons");
$("#socialicons").load(location.href + " #socialicons");
},
error   : function ( xhr )
{ alert( "error" );
}
} );
 }



function pinterest_score ( recipeident ) 
{ $.ajax( { type    : "POST",
data    : { "recipe_id" : recipeident }, 
url     : "../functions/pinterest_clicks.php",
success : function (recipeident)
{ 
window.open(
  'https://pinterest.com/pin/create/button/?url='+ Settings.pinteresturl + '&media='+ Settings.pinterestmedia +'&description='+ Settings.pinterestdesc,
  '_new' // <- This is what makes it open in a new window.
);
$("#socialicons").removeClass("socialicons");
$("#socialicons").load(location.href + " #socialicons");
},
error   : function ( xhr )
{ alert( "error" );
}
} );
 }
