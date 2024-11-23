<html>
<head>
<title>PHPMailer - Mail() advanced test</title>
</head>
<body>

<?php
require_once '../class.phpmailer.php';
include('get_content.php');
$user = $_REQUEST['user'];
$name = $_REQUEST['name'];
$email = $_REQUEST['email'];
$tk = $_REQUEST['tk'];
$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch

try {
  $mail->AddAddress($email, $name);
  $mail->SetFrom('info@daypayz.com', 'Daypay');
  $mail->AddReplyTo('support@daypayz.com', 'Daypayz Support');
  $mail->Subject = 'Password Reset';
  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
  $mail->MsgHTML(curl_get_contents("https://www.daypayz.com/dashboard/mail/forgot.php?user=$user&tk=$tk&add=$link&name=$name&email=$j"));
  $mail->AddAttachment('images/logo.jpg');      // attachment
  $mail->Send();
  echo "Message Sent OK";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
?>
</body>
</html>
