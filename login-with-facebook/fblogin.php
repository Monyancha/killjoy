<!--
Author : Midnight Owl
-->
<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}
	 
$login_seccess_url = 'redirecturi.php';  


 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Killjoy.co.za - Facebook Login</title>
<meta charset="UTF-8">
<script type="text/javascript" src="../fancybox/lib/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="../fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="../fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<link href="../css/login-page/fblogin.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script>
var getInfo;
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      isLogin();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please authorize ' +
        'killjoy.co.za';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1787126798256435',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.5' // use graph api version 2.5
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };
  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function isLogin() {
     FB.api('/me','GET', {fields: 'name,email,id,link,picture.width(150).height(150)'}, function(response) {
      var loginData = "name="+response.name+"&email="+response.email+"&fb_Id="+response.id+"&link="+response.link+"&profilePictureUrl="+response.picture.data.url;
      
      
      //ajax reqest to server..

      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("POST", "logindata.php", true);
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById('response').innerHTML = xmlhttp.responseText;
        };
      }
      xmlhttp.send(loginData);
	  window.location.href = "<?php echo $login_seccess_url; ?>"+"?requsername="+response.email;
	  
	   });
  }
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->
<div class="loginmsg" name="loginmsg" id="loginmsg">
<div class="fb-login-btn" id="fblogin-btn"><fb:login-button scope="public_profile,email" size="large" button-type="continue_with" height="100" id="login" onlogin="checkLoginState();">Continue with Facebook</fb:login-button></div>
<div id="status" class="statusmsg" name="status"></div>
<div id="response"></div>
</div>

<script type="text/javascript">
var $j = jQuery.noConflict();
$j('#status').bind("DOMSubtreeModified",function() {
	
 $j.fancybox({
	 helpers : {
     overlay : {
     css : {
  'background' : 'rgba(200, 201, 203, 0.40)'
   }
}

},
// top, right, bottom, left
href: '#loginmsg', 
modal: false,
 'afterClose'  : function() {			   
 location.href ="https://www.killjoy.co.za/admin/index.php";		
		 
 },
 
 });
return false;
});
 
</script>


</body>
</html>
