<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}

if (isset($_SESSION['PrevUrl']) && true) {
      $login_seccess_url  = $_SESSION['PrevUrl'];	
 }
	 
$login_seccess_url = 'https://www.killjoy.co.za/index.php';  



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.12&appId=1787126798256435&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script type="text/javascript">
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }
</script>
<script type="text/javascript">
  function isLogin() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me','GET', {fields: 'name,email,id,link,picture.width(150).height(150)'}, function(response) {
      var loginData = "name="+response.name+"&email="+response.email+"&fb_Id="+response.id+"&link="+response.link+"&profilePictureUrl="+response.picture.data.url;
      console.log('Successful login for: ' + loginData);
      
      //ajax reqest to server..

      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("POST", "logindata.php", true);
      xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
           window.location = "<?php echo $login_seccess_url ?>";
        };
      }
      xmlhttp.send(loginData);
     window.location = "<?php echo $login_seccess_url ?>";
     });
  }
</script>

<div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" onlogin="checkLoginState();"></div>
</body>

</html>