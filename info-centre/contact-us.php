<?php
ob_start();
require_once('../Connections/killjoy.php'); 

function generateRandomString($length = 4) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$captcha = generateRandomString();

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <script type="application/ld+json">
/*structerd data markup compiled by http://www.midnightowl.co.za */
{
  "@context": "http://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "item": {
      "@id": "httpw://www.killjoy.co.za/index.php",
      "name": "Home",
      "image": "https://www.killjoy.co.za/images/icons/home-icon.png"
    }
  },{
    "@type": "ListItem",
    "position": 2,
    "item": {
      "@id": "https://www.killjoy.co.za/info-centre/contact-us.php",
      "name": "Contact Us",
      "image": "https://www.killjoy.co.za/images/icons/contact-us.png"
    }
    }]
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.killjoy.co.za/info-centre/index.php">
<title>killjoy.co.za - Contact Us - Get in touch - share your thoughts - help us improve</title>
<meta name="keywords" content="contact, us, share, thoughts, improvements, guides, speak, email, call" />
<meta name="description" content="Would you like to speak to us, share an idea or suggest and improvement? You came to the right place. Complete the form and share your thougts." />
<link href="info.css" rel="stylesheet" type="text/css" />
<link href="contact.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113531379-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113531379-1');
</script>
</head>
<body>
<div class="banner" id="banner"></div>
<div class="container" id="container">
  <h1>Contact Us</h1>
  <p>Have a question? Complete the details below and we will get back to you as soon as is convenient. We aim to answer all questions within a 24 hour time frame, depending on workload. </p>
  <form action="contact.php" method="POST" name="frm_fb" class="contactform" id="frm_fb">
    <table border="0" cellpadding="0" cellspacing="0" class="contacttbl">
      <tr>
        <td colspan="2" class="tblhdr">Contact Us</td>
      </tr>
      <tr>
        <td class="leftcells">Your name:</td>
        <td class="fields"><span id="sprytextfield1">
          <input name="txt_name" type="text" class="txtfields" id="txt_name" />
        <span class="textfieldRequiredMsg">?</span></span></td>
      </tr>
      <tr>
        <td class="leftcells">Your email address:</td>
        <td class="fields"><span id="sprytextfield2">
        <input name="txt_email" type="text" class="txtfields" id="txt_email" />
        <span class="textfieldRequiredMsg">?</span><span class="textfieldInvalidFormatMsg">invalid email address</span></span></td>
      </tr>
      <tr>
        <td class="leftcells">Your message:</td>
        <td class="fields"><span id="sprytextarea1">
          <textarea name="txt_msg" cols="45" rows="5" class="txtarea" id="txt_msg"></textarea>
        <span class="textareaRequiredMsg">?</span></span></td>
      </tr>
      <tr>
        <td class="leftcells">&nbsp;</td>
        <td class="fields"><input name="txt_captcha" type="text" disabled="disabled" class="captcha" id="txt_captcha" value="<?php echo $captcha; ?>" readonly="readonly" /></td>
      </tr>
      <tr>
        <td class="leftcells">Retype the numbers above:</td>
        <td class="fields"><span id="spryconfirm1">
          <input name="txt_retype" type="text" class="captchacfrm" id="txt_retype" />
        <span class="confirmRequiredMsg">?</span><span class="confirmInvalidMsg">The numbers don't match.</span></span></td>
      </tr>
      <tr>
        <td class="leftcells">&nbsp;</td>
        <td class="fields"><input name="btn_send" type="submit" class="submit" id="btn_send" value="Send" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="frm_fb" />
  </form>
  <p>&nbsp;</p>
  <h2>&nbsp;</h2>
</div>
<div class="infofooter" id="infofooter"><img src="../images/icons/angry-owl.jpg" alt="reviews and advice on property rentals and rental complaints" width="64" height="64" border="0" /><p>killjoy.co.za: Reviews and advice on property rentals, rental complaints and more.</p></div>
<div class="copyright" id="copyright">&copy; 2017 killjoy.co.za killjoy.co.za <a href="terms-of-use.html">Terms-of-Use</a> and <a href="privacy-policy.html">Privacy Policy</a></div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "email");
var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "txt_captcha");
</script>
</body>
</html>
