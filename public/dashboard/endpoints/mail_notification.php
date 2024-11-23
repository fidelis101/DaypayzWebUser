<?php
require_once './class.phpmailer.php';
class mail_sender
{
	static public function send($subject,$body,$username)
	{
	$mail = new PHPMailer(true);
		include("dbcon.php");
		$result = mysqli_query($con,"SELECT Email FROM users WHERE Username='$username'");
		$email = mysqli_fetch_array($result)['Email'];
		try {
			  $mail->AddAddress($email, $username);
			  $mail->SetFrom('info@daypayz.com', 'Daypayz');
			  $mail->AddReplyTo('support@daypayz.com', 'Daypayz Support');
			  $mail->Subject = $subject;
			  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
			  $mail->MsgHTML($body);
			  $mail->Send();
			  return "Mail sent";
			} catch (phpmailerException $e) {
			  return $e->errorMessage(); //Pretty error messages from PHPMailer
			} catch (Exception $e) {
			  return $e->getMessage(); //Boring error messages from anything else!
			}
	}
}