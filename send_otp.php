<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

function sendOTP($toEmail, $username, $otp) {
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        // your Gmail credentials
        $mail->Username = 'davidbwashi@gmail.com';
        $mail->Password = 'hssm iwvq nimx otty';  // see step 3 below

        $mail->SMTPSecure = 'tls';
        $mail->Port = 3306;

        // sender info
        $mail->setFrom('islaehkr@gmail.com', 'E.E');
        $mail->addAddress($toEmail);

        // email content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Verification Code';
        $mail->Body = "Hello <b>$username</b>,<br><br>Your OTP code is <b>$otp</b>.<br><br>Use it to verify your email.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
