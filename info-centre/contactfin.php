<?php
ob_start();

$email = $_GET['requsermail'];
$name = $_GET['requsername'];
$urlreference = $_GET['reqreference'];
$reference = "<font size='+2'><strong>".$urlreference."</strong></font>" ;

require('../phpmailer-master/class.phpmailer.php');
include('../phpmailer-master/class.smtp.php');

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Host = "host27.axxesslocal.co.za ";
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Username = "accounts@rentaguide.co.za";
$mail->Password = "N@w!1970";
$mail->Port = "465";
$mail->SetFrom('accounts@rentaguide.co.za', 'Rent-a-Guide');
$mail->AddReplyTo("accounts@rentaguide.co.za","Rent-a-Guide");


$message = "<html><head><style type='text/css'>
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
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
body {
	background-repeat: no-repeat;
	margin-left:50px;
}
</style><script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-101245629-1', 'auto');
ga('send', 'pageview');
</script>  
</head><body>Dear ".$name."<br><br>Thank you taking time to write to us.<br><br>We have received your feedback with reference ". $reference ." and will do our best to reply as soon as possible.<br><br>The feedback was sent from ".$email."<br><br><br><br>Thank you, the Rent-a-Guide team: https://www.rentaguide.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:accounts@rentaguide.co.za'>Rent-a-Guide</a></font><br></body></html>";

$mail->Subject    = "Rent-a-Guide Feedback";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$body = "$message\r\n";
$body = wordwrap($body, 70, "\r\n");
$mail->MsgHTML($body);
$address = $email;
$mail->AddAddress($address, "$address");

if(!$mail->Send()) {

echo "Mailer Error: " . $mail->ErrorInfo;

} else {

echo '<div id="feedback" class="feedbox">Thank you '.$name.' for taking the time to write to us. We have received your feedback and will reply ASAP. Your reference nr is: '.$reference.' Please quote this reference number in all correspondence with us. The <a href="https://www.rentaguide.co.za">Rent-a-Guide</a> Team</div>';

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<link rel="canonical" href="https://www.rentaguide.co.za/info-centre/index.html">
<title>find answers to all your quesions - info centre - community centre - guidelines - help - contact us</title>
<meta name="keywords" content="help, contact, terms, guidelines, usage, policy, notice, privacy, rental, offers" />
<meta name="description" content="a Place where you can find answers to all your questions relating to the Rent-a-Guide community. Find help or view the guidelines. Contact us." />
<link href="info.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="fancybox/lib/jquery-1.9.0.min.js"></script>
<link rel="stylesheet" href="fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-101245629-1', 'auto');
ga('send', 'pageview');
</script>  
</head>

<body>
    <script type="text/javascript">
$(document).ready(function() {

 $.fancybox({
        href: '#feedback', 
		 'speedIn'		:	0, 
		 'speedOut'		:	500, 
		modal: false,
		   'afterClose'  : function() {
			
               location.href = "index.html";
            }
		
    });
    return false;
});
    </script>

</body>
</html>