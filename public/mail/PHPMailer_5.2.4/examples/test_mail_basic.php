<html>
<head>
<title>PHPMailer - Mail() basic test</title>
</head>
<body>

<?php

require_once('../class.phpmailer.php');

$mail             = new PHPMailer(); // defaults to using php "mail()"

$body             = file_get_contents('<I am sirp <b> Wow</b>');
//$body             = preg_replace('/[\]/','',$body);

$mail->SetFrom('fidelis@daypayz.com', 'First Last');

$mail->AddReplyTo("fidelis@daypayz.com","First Last");

$address = "ugwufidelis1@gmail.com";
$mail->AddAddress($address, "Ugwu Fidelis");

$mail->Subject    = "PHPMailer Test Subject via mail(), basic";

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

$mail->AddAttachment("images/phpmailer.gif");      // attachment
$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>

</body>
</html>
