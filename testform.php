<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'developer.tech49@gmail.com';                 // SMTP username
    $mail->Password = 'ttwi fxts apyi aqor';                           // SMTP password
    $mail->SMTPSecure = 'TLS';                           
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('developer.tech49@gmail.com');
    $mail->addAddress('mithilesh849@gmail.com');     // Add a recipient

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Test subject';
    $mail->Body    = 'Hello mail testing';

    $mail->send();
    echo "Sent!";
    exit();
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
?>