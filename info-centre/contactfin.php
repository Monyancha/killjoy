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
$mail->Host = "killjoy.co.za";
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Username = "friends@killjoy.co.za";
$mail->Password = "806Ppe##44VX";
$mail->Port = "465";
$mail->SetFrom('friends@killjoy.co.za', 'Killjoy Community');
$mail->AddReplyTo("friends@killjoy.co.za","Killjoy Community");


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
	font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';
font-size: 20px;
}
body {
	background-repeat: no-repeat;
	margin-left:50px;
}
</style>
</head><body>Dear ".$name."<br><br>Thank you for taking time to write to us.<br><br>We have received your feedback with reference ". $reference ." and will do our best to reply as soon as possible.<br><br>The feedback was sent from ".$email."<br><br>Thank you, the Killjoy team: https://www.killjoy.co.za<br><br><font size='2'>If you received this email by mistake, pleace let us know: <a href='mailto:support@killjoy.co.za'>Killjoy</a></font><br></body></html>";

$mail->Subject    = "Killjoy.co.za Feedback";

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

echo '<div id="feedback" class="feedbox">Thank you '.$name.' for taking the time to write to us. We have received your feedback and will reply ASAP. Your reference nr is: '.$reference.' Please quote this reference number in all correspondence with us. The <a href="https://www.killjoy.co.za">Killjoy.co.za</a> Team</div>';

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en-za">
<meta name="robots" content="noindex, nofollow" />
<link rel="canonical" href="https://www.killjoy.co.za/info-centre/index.php">
<title>killjoy.co.za - Notice and Takedown Procedure</title>
<meta name="keywords" content="Notice, Takedown" />
<meta name="description" content="Unhappy with a review your rental property received? Follow this guide to get it removed" />
<link href="info.css" rel="stylesheet" type="text/css" />
<script src="fancybox/libs/jquery-3.3.1.min.js" ></script>
<link rel="stylesheet" href="fancybox/dist/jquery.fancybox.min.css" />
<script src="fancybox/dist/jquery.fancybox.min.js"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113531379-1"></script>
</head>

<body>
<script type="text/javascript">	
  $.fancybox.open({
    src  : '#feedback',
    type : 'inline',
    opts : {
        afterClose : function( instance, current ) {
            location.href='index.html';
        }
    }
});
</script>

</body>
</html>