<?php
require_once "/home/daypay5/public_html/dashboard/class.phpmailer.php";
include "/home/daypay5/public_html/dashboard/dbcon.php";

$result = mysqli_query($con, "SELECT * FROM mails where status=0");
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $email = $row['recipient'];
        $body = $row['body'];
        $subject =$row['subject']; 
		$result = mysqli_query($con,"SELECT Username FROM users WHERE Username='$username'");
        $user = mysqli_fetch_array($result)['Username'];
        $date = date("Y-m-d H-i-s");

        mail_sender::send($subject, $body,$email,$username);
        mysqli_query($con,"UPDATE mails SET status=1, date_sent=NOW() WHERE id=$id");
        mysqli_query($con,"INSERT INTO sent_mail (Receiver,User,Heading,Body,Date,Mail_Id) VALUE('$email','$username','$subject','$body','$date','$id');");
    }
}

class mail_sender
{
	static public function send($subject,$body,$email,$username)
	{
	$mail = new PHPMailer(true);
		try {
			  $mail->AddAddress($email, $username);
			  $mail->SetFrom('info@daypayz.com', 'Daypayz');
			  $mail->AddReplyTo('support@daypayz.com', 'Daypayz Support');
			  $mail->Subject = $subject;
			  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
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
