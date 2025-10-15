<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require 'vendor/autoload.php';

//Create an instance; passing true enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
   $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'pascal.safari@strathmore.edu';                     //SMTP username
    $mail->Password   = 'zado biwl cmgk fojr';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS

    //Recipients
    $re_username = 'Pascal Safari';
    $mail->setFrom('pascal.safari@strathmore.edu', $re_username);
    $mail->addAddress('josuepascal15@gmail.com', 'Josue Pascal');   //Add a recipient
    // $mail->addAddress('ellen@example.com'); //Name is optional
//     $mail->addReplyTo('pascal.safari@strathmore.edu', 'Pascal Safari');
//     // $mail->addCC('cc@example.com');
//     // $mail->addBCC('bcc@example.com');

    //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    $username = $mail->Username; 

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Welcome to BBIT 2.2! Account Verification';

    $mail->Body    = "<p>Hello <b>$re_username</b>,</p>
    <p>You request is being processed.</p>";
   

$mail->AltBody = 'Hello, Please visit the verification link to complete registration.';


    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}